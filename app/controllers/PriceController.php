<?php
namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\PriceModel;

class PriceController extends Controller
{
    /*
     * FIXME: класс должен хоть как-то обрабатывать ошибки, на данный момент, в 
     * случае передачи неправильных данных, просто ломается верстка и сыпятся исключения
     */
    private $uploadPrice;
    
    public function __construct() { 
        parent::__construct();
        // авторизация, если не пройдена, то произойдет переход к форме аутентификации
        $this->checkAuth();
        $this->price = new PriceModel;
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'][0] = 'price';
    }
    
    public function getPage()
    {
        $this->view->generate('/price/uploadPrice.php', 'indexTemplate.php', $this->data, $this->error);
    }
    
    public function uploadPrice() 
    {
        if (isset($_FILES['uploadedFile'])) {
            if ($this->price->uploadPrice($_FILES['uploadedFile'])) {
                $this->view->generate('/price/successUploadPrice.php', 'indexTemplate.php', $this->data, $this->error);
            } else {
                $this->error = $this->price->getErrors();
                $this->view->generate('/price/uploadPrice.php', 'indexTemplate.php', $this->data, $this->error);
            }
            
        } else {
            $this->error = array("Выберите файл");
            $this->view->generate('/price/uploadPrice.php', 'indexTemplate.php', $this->data, $this->error);
        }
    }
    
    public function updatePrice()
    {
        if (isset($_POST)) {
            $this->data['resultUpdate'] = $this->price->updatePrice();
            if (!empty($this->data['resultUpdate'])) {
                $this->view->generate('/price/listProducts.php', 'indexTemplate.php', $this->data, $this->error);
            } else {
                $this->error = $this->price->getErrors();
                $this->view->generate('/price/listProducts.php', 'indexTemplate.php', $this->data, $this->error);
            }
        } else {
            // TODO: должен вернуть и отобразить ошибку, если это не POST запрос
            $this->getPage();
        }
    }
}
