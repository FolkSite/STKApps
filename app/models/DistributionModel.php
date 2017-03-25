<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;

class DistributionModel extends Model
{

    // объект для работы с БД
    private $dbh;
    private $date;

    public function __construct()
    {
        // передает класса из которого вызывается, для каждого класса свои
        // настройки mysql
        $this->dbh = new MysqlModel(MysqlModel::STKApps);
        $this->date = date("o\-m\-d");
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
        $number = $dataPOST['number'];
        $id_avito = $dataPOST['id_avito'];
        $date = date('d.m.Y');
        $link = $dataPOST['link'];
        $header = $dataPOST['header'];
        $price = $dataPOST['price'];
        $organization = $dataPOST['organization'];
        $name = $dataPOST['name'];
        /* убираю все кроме цифр, чтобы телефонные номера в БД хранились в одном формате */
        $telephone_number = preg_replace('/[^0-9]/', '', $dataPOST['telephone_number']);
        $address = $dataPOST['address'];
        $message = $dataPOST['message'];
        $text_ad = $dataPOST['text_ad'];
        
        $newAd = $this->dbh->query("INSERT INTO `distribution` (`id_avito`, `link`, `header`,"
                . " `price`, `organization`, `name`, `telephone_number`, `address`, "
                . "`message`, `text_ad`, `date`, `number`) VALUES "
                . "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", 'none', '', 
                array($id_avito, $link, $header, $price, $organization, $name, $telephone_number, $address, $message, $text_ad, $date, $number));
        
        if (!empty($newAd)) {
            $this->successful[] = "Объявление добавлено";
            return true;
        }  else {
            $this->errors[] = "Ошибка при добавлении объявления в БД";
            return false;
        }
    }
    
}
