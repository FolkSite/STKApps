<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;

class PriceModel extends Model
{

    // объект для работы с БД
    private $dbh;
    private $date;
    // путь до прайслиста
    private $pathToPricelist;

    public function __construct()
    {
        // передает класса из которого вызывается, для каждого класса свои
        // настройки mysql
        $this->dbh = new MysqlModel(MysqlModel::STK);
        $this->date = date("o\-m\-d");
        $this->pathToPricelist = __DIR__ . '/../../storage/price.csv';
    }

    public function uploadPrice($file)
    {

        if ($file['type'] === 'application/vnd.ms-excel' || $file['type'] === 'text/csv') {
            
        } else {
            $this->errors[] = "Файл должен быть только в формате CSV";
            return false;
        }
        
        if (move_uploaded_file($file['tmp_name'], $this->pathToPricelist)) {
            return true;
        } else {
            $this->errors[] = "Ошибка при загрузке файла на сервер";
            return false;
        }
    }

    private function parsePrice()
    {
        if (!file_exists($this->pathToPricelist)) {
            $this->errors[] = "Файл с прайс-листом не найден";
            return;
        }
        $pricelist = array();

        $handle = fopen('php://memory', 'w+');
        fwrite($handle, iconv('CP1251', 'UTF-8', file_get_contents($this->pathToPricelist)));
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
        }

        foreach ($pricelist as $product) {
            $productSkuFromPricelist = $product['sku'];
            $productNameFromPricelist = $product['name'];
            $productPriceFromPricelist = $product['price'];
            // получает из бд товар, соответствующий артикулу из прайса

            $productFromBd = $this->dbh->query("SELECT * FROM `oc_product` WHERE `sku` = ?;", 'accos', '', array($productSkuFromPricelist));

            // проверяет найден ли товар в БД и пропускает цикл, если нет
            
            if (empty($productFromBd)) {
                $resultUpdate[] = array(
                    'text' => "Товар арт. $productSkuFromPricelist - $productNameFromPricelist отсутствует на сайте",
                    'detail' => "Товар не найден в БД",
                    'class' => "notFound"
                );

                continue;
            }

            $productIdFromBd = $productFromBd["product_id"];
            $productSkuFromBd = $productFromBd["sku"];
            $oldPrice = round($productFromBd['price'], 2);

            // получает описание подукта из БД
            // TODO: полагаю, все это можно сделать одним запросом через JOIN
            $productDescriptionFromBd = $this->dbh->query("SELECT * FROM `oc_product_description` WHERE `product_id` = ?;", 'accos', '', array($productIdFromBd));
            $productNameFromBd = $productDescriptionFromBd['name'];


            if ($oldPrice != $productPriceFromPricelist) {
                $resultUpdate[] = array(
                    'text' => "Цена у товара арт: $productSkuFromBd  - &laquo;$productNameFromPricelist&raquo; с $oldPrice руб на $productPriceFromPricelist руб",
                    'detail' => "id: $productIdFromBd, sku in price: $productSkuFromPricelist, sku in BD: $productSkuFromBd, name in BD: &laquo;$productNameFromBd&raquo;",
                    'class' => "priceChanged"
                );

                // обновляет цену в БД
                $this->dbh->query("UPDATE `oc_product` SET `price` = ? WHERE `sku` = ?;", 'none', '', array($productPriceFromPricelist, $productSkuFromPricelist));
            } else {
                $resultUpdate[] = array(
                    'text' => "Цена у товара арт: $productSkuFromBd  - &laquo;$productNameFromPricelist&raquo; не изменилась",
                    'detail' => "id: $productIdFromBd, sku in price: $productSkuFromPricelist, sku in BD: $productSkuFromBd, name in BD: &laquo;$productNameFromBd&raquo;",
                    'class' => "pricePrevious"
                );
            }
        }
        
        return $resultUpdate;
    }

}
