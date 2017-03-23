<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;

class PriceModel extends Model
{

    // объект для работы с БД
    private $dbh;
    private $date;

    // название прайслиста и путь до него
    const PATH_TO_PRICELIST = __DIR__ . '/../../storage/price.csv';

    public function __construct()
    {
        // передает класса из которого вызывается, для каждого класса свои
        // настройки mysql
        $this->dbh = new MysqlModel(MysqlModel::STK);
        $this->date = date("o\-m\-d");
    }

    public function uploadPrice($file)
    {

        if ($file['type'] != 'application/vnd.ms-excel') {
            $this->errors[] = "Файл должен быть только в формате CSV";
            return false;
        }

        if (copy($file['tmp_name'], self::PATH_TO_PRICELIST)) {
            return true;
        } else {
            $this->errors[] = "Неизвестная при загрузке файла на сервер";
            return false;
        }
    }

    private function parsePrice()
    {
        if (!file_exists(self::PATH_TO_PRICELIST)) {
            $this->errors[] = "Файл с прайс-листом не найден";
            return;
        }
        $pricelist = array();

        $handle = fopen('php://memory', 'w+');
        fwrite($handle, iconv('CP1251', 'UTF-8', file_get_contents(self::PATH_TO_PRICELIST)));
        rewind($handle);

        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {

            // если ячейка содержит цифру, то скорее всего, это строка с товаром
            if (is_numeric($data[3])) {

                $sku = $data[0];

                $price = $data[3];
                $price = str_replace(",", ".", $price);
                // округляет цену
                $price = round($price, 2);

                $name = $data[1];

                $pricelist[] = array(
                    'sku' => $sku,
                    'price' => $price,
                    'name' => $name,
                );
            }
        }

        return $pricelist;
    }

    public function updatePrice()
    {
        $resultUpdate = array();
        
        $pricelist = $this->parsePrice();

        if (empty($pricelist)) {
            $this->errors[] = "Не удалось найти товары в загруженном файле";
            return false;
        } else {
            foreach ($pricelist as $product) {
                /*
                $oc_product_query = mysql_query("SELECT * FROM `oc_product` WHERE `sku`  = $skuPrice;", $link);

                $oc_product = mysql_fetch_array($oc_product_query);

                $id = $oc_product["product_id"];

                if ($id) {
                    $oc_product_description_query = mysql_query("SELECT * FROM `oc_product_description` WHERE `product_id` = $id", $link);

                    $oc_prod_desc = mysql_fetch_array($oc_product_description_query);
                }

                $skuInBD = $oc_product["sku"];

                $oldPriceRound = round($oc_product[14], 2);

                $skuBD = $oc_product["sku"];


                if ($oc_product) {
                    if ($oldPriceRound != $priceRound) {

                        echo "<p><font color='green'>Цена у товара арт: " . $oc_product["sku"] . " - «" . $name . "» с " . $oldPriceRound . " руб на " .
                        $priceRound . " руб";

                        if ($debug) {
                            echo "(id: " . $id . ", sku in price: " . $skuPrice . ", sku in BD: $skuBD, name in BD: «" . $oc_prod_desc["name"] . "»)";
                        }

                        echo "</font></p>";
                    } else {

                        echo "<p>Цена у товара арт: " . $oc_product["sku"] . " - «" . $name . "» не изменилась ";

                        if ($debug) {
                            echo "(id: " . $id .
                            ", sku in price: " . $skuPrice . ", sku in BD: $skuBD, name in BD: «"
                            . $oc_prod_desc["name"] . "», старая цена: " . $oldPriceRound .
                            ", новая цена: " . $price . ")";
                        }

                        echo "</p>";
                    }
                } else {
                    echo "<p><font color='red'>Товар арт. " . $skuPrice . " - " . $name . " отсутствует на сайте</font></p>";
                }

                $query = mysql_query("UPDATE `oc_product` SET `price` = $price WHERE `sku` = $skuPrice;", $link);
                 * 
                 */
            }
        }
        
        // TODO: когда доделаю функцию, можно убрать эту проверку, потому что если $pricelist
        // не пустой, то $resultUpdate какую-нибудб строку да вернет
        if (empty($resultUpdate)) {
            $this->errors[] = "Не удалось получить товары из файла";
            return false;
        } else {
            return $resultUpdate;
        }
    }

}
