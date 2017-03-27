<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;

class AdsModel extends Model
{

    // объект для работы с БД
    private $dbh;
    private $date;
    // количество страниц
    private $quantityPage;
    // номер текущей страницы
    private $numThisPage;
    // позиция с которой начинается выгрузка из таблицы, необходима для нумерации
    private $startPosition;

    // количество строк на странице
    const ROW_ON_PAGE = 100;
    // максимальное количество номеров страниц, которое должно помещаться в нумерации
    // число обязательно должно быть нечетным и больше "3"
    const MAX_NUMBERS_ON_PAGINATION = 9;

    /**
     * 
     * @param type $numThisPage номер страницы из которой был вызван конструктор
     * по умолчанию 0, необходим для получения элементов и создания нумерации
     */
    public function __construct($numThisPage = 0)
    {
        // передает класса из которого вызывается, для каждого класса свои
        // настройки mysql
        $this->dbh = new MysqlModel(MysqlModel::STKApps);
        $this->date = date("o\-m\-d");
        $this->numThisPage = $numThisPage;
        $this->startPosition = $numThisPage * self::ROW_ON_PAGE;
    }

    // получает объявления заданного диапазона строк
    public function getAds()
    {
        $ads = $this->dbh->query("SELECT * FROM `ads` ORDER BY `ads`.`id` DESC LIMIT $this->startPosition, " . self::ROW_ON_PAGE . ";", 'fetchAll');
        return $ads;
        //return $this->dbh->query("SELECT `id`, `name`, `sku`, `date` FROM ads ORDER BY `ads`.`id` DESC;", 'fetchAll');
    }

    public function getAd($id)
    {
        $returnAd = array();
        $ad = $this->dbh->query("SELECT * FROM ads WHERE `id` = ?;", 'accos', '', array($id));
        $returnAd['name'] = $ad['name'];
        $returnAd['description'] = $ad['description'];
        $returnAd['sku'] = $ad['sku'];
        $returnAd['id'] = $ad['id'];

        /*
         * TODO: надо чтобы скрипт получал названия и цены товаров из БД магазина
         */
        if (!empty($returnAd['sku'])) {
            $sku = explode(',', $returnAd['sku']);
            // необходимо заменить запятые на пробелы, в форму артикулы вставляются 
            // через пробел, а в бд хранятся через запятую, так исторически сложилось
            $returnAd['sku'] = implode(' ', $sku);
            foreach ($sku as $oneSku) {
                $returnAd['products'][] = trim($oneSku);
            }
        }
        return $returnAd;
    }

    /**
     * получает количество страниц
     * @param str $typeResult 'all' если требуется получить нумерацию для страниц
     * всех объявления и 'search' если только для результатов поиска
     * @param str $searchQuery искомое значение из формы поиска
     * @return type количество страниц
     * @throws \Exception ошибка при передаче аргументов
     */
    private function getQuantityPage($typeResult, $searchQuery = null)
    {
        switch ($typeResult) {
            case 'all':
                $quantityRow = $this->dbh->query("SELECT * FROM `ads`;", 'num_row');

                break;

            case 'search':
                $searchQuery = '%' . $searchQuery . '%';
                $quantityRow = $this->dbh->query("SELECT * FROM `ads` WHERE `name` LIKE ?;", 'num_row', '', array($searchQuery));

                break;

            default:
                throw new \Exception("Нет данных для получения количества страниц");

                break;
        }

        $quantityPage = ceil($quantityRow / self::ROW_ON_PAGE);
        return $quantityPage;
    }

    /**
     * получает массив для создания нумерации
     * @param str $typeResult 'all' если требуется получить нумерацию для страниц
     * всех объявления и 'search' если только для результатов поиска
     * @param str $searchQuery искомое значение из формы поиска
     * @return type массив для построения нумерации
     */
    public function getPagination($typeResult, $searchQuery = null)
    {
        // количество страниц
        $quantityPage = $this->getQuantityPage($typeResult, $searchQuery);
        // прибавляю единицу в занчении 'thisPage', потому что в БД отсчет с 0, а на фронте с 1
        $numThis = $this->numThisPage + 1;
        $maxNums = self::MAX_NUMBERS_ON_PAGINATION;

        switch ($typeResult) {
            case 'all':
                $link = 'getpage?page=';

                break;

            case 'search':
                $link = 'search?query=' . $searchQuery . '&page=';

                break;

            default:
                break;
        }
        
        // число обязательно должно быть нечетным и больше "3" иначе верстка нумерации сломается
        if ($maxNums < 3) {
            $maxNums = 3;
        }
        
        if(($maxNums % 2) == 0){
            $maxNums = $maxNums + 1;
        }

        if ($quantityPage > $maxNums) {
            $numSide = ($maxNums - 1) / 2;

            if (($numThis - $numSide) > 0 && ($numThis + $numSide) < $quantityPage) {
                $paginationStart = $numThis - ($numSide + 1);
                $paginationEnd = $numThis + $numSide;
            } elseif (($numThis - $numSide) > 0 && ($numThis + $numSide) >= $quantityPage) {
                $paginationStart = ($numThis - ($numSide + 1)) + ($quantityPage - ($numThis + $numSide));
                $paginationEnd = $quantityPage;
            } elseif (($numThis - $numSide) <= 0 && ($numThis + $numSide) <= $quantityPage) {
                $paginationStart = 0;
                $paginationEnd = $maxNums;
            }
        }

        $pages = array(
            'quantityPage' => $quantityPage,
            'thisPage' => $numThis,
            'link' => $link,
            'paginationStart' => $paginationStart,
            'paginationEnd' => $paginationEnd,
        );
        return $pages;
    }

    public function saveAd(array $dataFromForm)
    {
        // проверяет, что все поля заполнены
        $this->validatePOST($dataFromForm);
        // вернет TRUE в случае успеха
        return $ad = $this->dbh->query("UPDATE `ads` SET `name` = ?, `sku` = ?, `description` = ?, `date` = ? WHERE `id` = ?", 'none', '', array($dataFromForm['name'], $dataFromForm['sku'], htmlspecialchars($dataFromForm['description']), $this->date, $dataFromForm['id']));
    }

    public function createAd(array $dataFromForm)
    {
        // проверяет, что все поля заполнены
        if (!$this->validatePOST($dataFromForm)) {
            return;
        }

        $newAd = $this->dbh->query("INSERT INTO `ads` (`id`, `name`, `sku`, `description`, `date`) VALUES (NULL, ?, ?, ?, ?)", 'none', '', array($dataFromForm['name'], $dataFromForm['sku'], htmlspecialchars($dataFromForm['description']), $this->date));
        // возвращает id нового объявления
        return $this->dbh->getLastInsertId();
    }

    /**
     * 
     * @param type $dataPOST
     * @return boolean вернет FALSE если хоть одно поле не заполнено
     */
    private function validatePOST($dataPOST)
    {
        if (empty($dataPOST['name'])) {
            $this->errors[] = "Введите заголовок";
        }
        if (empty($dataPOST['sku'])) {
            $this->errors[] = "Введите артикулы";
        }
        if (empty($dataPOST['description'])) {
            $this->errors[] = "Введите текст объявления";
        }

        if (!empty($this->errors)) {
            return false;
        } else {
            return true;
        }
    }

    // ищет по заголовку объявления
    public function search($qurySearchForm)
    {
        if (!empty($qurySearchForm)) {

            $searchQuery = '%' . $qurySearchForm . '%';
            $searchResult = $this->dbh->query("SELECT * FROM `ads` WHERE `name` LIKE ? ORDER BY `ads`.`id` DESC LIMIT $this->startPosition, " . self::ROW_ON_PAGE . ";", 'fetchAll', '', array($searchQuery));

            if (count($searchResult) > 0) {
                return $searchResult;
            } else {
                $this->errors[] = "По вашему запросу ничего не найдено";
                return false;
            }
        } else {
            $this->errors[] = "Введите поисковый запрос";
            return false;
        }
    }

    public function delAd($idAds)
    {
        // в случае ошибки при выполнении запроса PDO должен выкинуть исключение,
        // но это не точно, так что, возможно, стоит сделать дополнительную проверку
        $this->dbh->query("DELETE FROM `ads` WHERE `id` = ?", 'none', '', array($idAds));
        return true;
    }

}
