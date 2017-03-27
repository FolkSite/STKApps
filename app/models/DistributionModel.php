<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;

class DistributionModel extends Model
{

    // объект для работы с БД
    private $dbh;

    const PATH_TO_CSV = __DIR__ . '/../../storage/distribution.csv';

    public function __construct()
    {
        // передает класса из которого вызывается, для каждого класса свои
        // настройки mysql
        $this->dbh = new MysqlModel(MysqlModel::STKApps);
    }

    // получает объявления заданного диапазона строк
    public function getDistribution()
    {
        
    }

    public function getNextNumberAd()
    {
        $maxNumberAd = $this->dbh->query("SELECT MAX(`number`) as `max` FROM `distribution`;", 'accos');
        $nextNumberAd = $maxNumberAd['max'] + 1;
        return json_encode(array('number' => $nextNumberAd));
    }

    public function checkOnRepeat($getData)
    {
        $data = $getData["data"];
        $type = $getData["type"];

        switch ($type) {
            case 'id_avito':
                $column = 'id_avito';
                break;

            case 'telephone_number':
                $column = 'telephone_number';
                /* убираю все кроме цифр, чтобы телефонные номера в БД хранились в одном формате */
                $data = preg_replace('/[^0-9]/', '', $data);
                break;

            case 'organization':
                $column = 'organization';
                break;

            default:
                # code...
                break;
        }

        $repeatRows = $this->dbh->query("SELECT * FROM `distribution` WHERE $type = ?", 'fetchAll', '', array($data));

        if (empty($repeatRows)) {
            $repeat = false;
        } else {
            /* если есть повторы, то ищуются номера повторяющихся записей, чтобы потом передать их
              на фронтэнд */
            $repeat = true;
            $numbers = array();

            /* если записей несколько, то они превращаются в строку и перечисляются через запятую */
            foreach ($repeatRows as $row) {
                $numbers[] = $row['number'];
            }

            $numbers = implode(', ', $numbers);
        }

        if ($repeat) {
            return json_encode(array('repeat' => "true", 'data' => $data, 'number' => $numbers));
        } else {
            return json_encode(array('repeat' => "false", 'data' => $data, 'number' => 'none'));
        }
    }

    public function getTime()
    {
        date_default_timezone_set("Europe/Moscow");
        return date("d.m.Y");
    }

    public function addAd($dataPOST)
    {
        // не использую флаги, функция не использует переменные за пределами 
        // своей области видимости, нет риска их переписать
        extract($dataPOST);

        $date = date('d.m.Y');
        $telephone_number = preg_replace('/[^0-9]/', '', $telephone_number);

        $newAd = $this->dbh->query("INSERT INTO `distribution` (`id_avito`, `link`, `header`,"
                . " `price`, `organization`, `name`, `telephone_number`, `address`, "
                . "`message`, `text_ad`, `date`, `number`) VALUES "
                . "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", 'none', '', array($id_avito, $link, $header, $price, $organization,
            $name, $telephone_number, $address, $message, $text_ad, $date, $number));

        if (!empty($newAd)) {
            $this->successful[] = "Объявление добавлено";
            return true;
        } else {
            $this->errors[] = "Ошибка при добавлении объявления в БД";
            return false;
        }
    }

    public function uploadDistribution($file)
    {

        if ($file['type'] != 'application/vnd.ms-excel') {
            $this->errors[] = "Файл должен быть только в формате CSV";
            return false;
        }

        if (copy($file['tmp_name'], self::PATH_TO_CSV)) {
            return true;
        } else {
            $this->errors[] = "Ошибка при загрузке файла на сервер";
            return false;
        }
    }

    public function parseCSV($param)
    {
        $handle = fopen('php://memory', 'w+');
        fwrite($handle, iconv('CP1251', 'UTF-8', file_get_contents(self::PATH_TO_CSV)));
        rewind($handle);

        $date = date("d.m.Y");

        $count_update = $count_insert = 0;

        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            $number = $data[1];
            $id_avito = $data[2];


            /* не все строки являются объявлениеми, поэтому пропускаю те, где не стоит артикул */
            if (is_numeric($number) && $id_avito) {

                $link = $data[3];
                $header = $data[4];
                $price = $data[5];
                $organization = $data[6];
                $name = $data[7];
                /* убираю все кроме цифр, чтобы телефонные номера в БД хранились в одном формате */
                $telephone_number = preg_replace('/[^0-9]/', '', $data[8]);
                $address = $data[9];
                $message = $data[10];
                $text_ad = $data[11];

                /* не у каждой позиции проставлена дата, потому что в оригинальной таблице есть
                  объединенные ячейки с датой */
                if ($data[0] != '') {
                    $date = str_replace('/', '.', $data[0]);
                }

                $ads = $this->dbh->query("SELECT * FROM `distribution` WHERE `number` = ?;", 'fetchAll', '', array($number));

                if (empty($ads)) {

                    $newAds = $this->dbh->query("INSERT INTO `distribution` (`id_avito`, `link`, `header`, `price`, `organization`,
                        `name`, `telephone_number`, `address`, `message`, `text_ad`, `date`,
                        `number`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", 'none', '', array($id_avito, $link, $header, $price, $organization, $name,
                        $telephone_number, $address, $message, $text_ad, $date, $number));

                        $count_insert++;
                } else {
                    $sql = "UPDATE ads SET `id_avito` = '$id_avito', `link` = '$link', `header` = '$header',
                        `price` = '$price', `organization` = '$organization', `name` = '$name',
                        `telephone_number` = '$telephone_number', `address` = '$address',
                        `message` = '$message', `text_ad` = '$text_ad', `date` = '$date'
                        WHERE `number` = $number";

                        $count_update++;
                }
            }
        }
        
        return "Добавлено новых позиций: " . $count_insert . "<br>Обновлено старых позиций: " . $count_update;
        $mysqli->close();
    }

}
