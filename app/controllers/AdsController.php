<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\AdsModel;

class AdsController extends Controller
{
    /*
     * FIXME: класс должен хоть как-то обрабатывать ошибки, на данный момент, в 
     * случае передачи неправильных данных, просто ломается верстка и сыпятся исключения
     */

    private $ads;

    public function __construct()
    {
        parent::__construct();
        // авторизация, если не пройдена, то произойдет переход к форме аутентификации
        $this->checkAuth();
        
        if (isset($_GET['page']) && $_GET['page'] > 0) {
            // отнимаю единицу, потому что в нумерации отсчет от 1, а не от 0
            $numPage = ($_GET['page'] - 1);
        } else {
            $numPage = 0;
        }
        $this->ads = new AdsModel($numPage);
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'][0] = 'ads';
    }

    public function getPage()
    {
        $this->data['adsTable'] = $this->ads->getAds();
        $this->data['pagination'] = $this->ads->getPagination('all');
        $this->view->generate('/ads/tableAds.php', 'indexTemplate.php', $this->data, $this->error);
    }

    public function getAd()
    {
        if (isset($_GET['id'])) {
            $this->data['adInfo'] = $this->ads->getAd($_GET['id']);
            $this->view->generate('/ads/viewAd.php', 'indexTemplate.php', $this->data, $this->error);
        } else {
            // TODO: должен вернуть и отобразить ошибку, если не передан id
            $this->getPage();
        }
    }

    public function editAd()
    {
        if (isset($_GET['id'])) {
            $this->data['adInfo'] = $this->ads->getAd($_GET['id']);
            $this->view->generate('/ads/editAd.php', 'indexTemplate.php', $this->data, $this->error);
        } else {
            // TODO: должен вернуть и отобразить ошибку, если не передан id
            $this->getPage();
        }
    }

    public function saveAd()
    {
        if (isset($_POST)) {
            $this->ads->saveAd($_POST);
            if (!$this->ads->getErrors()) {
                $this->error = $this->ads->getErrors();
                $this->view->generate('/ads/editAd.php', 'indexTemplate.php', $this->data, $this->error);
                exit();
            }
            // переходит к просмотру отредактированного объявления
            $url = 'Location: /ads/getad?id=' . $_POST['id'];
            header($url);
        } else {
            // TODO: должен вернуть и отобразить ошибку, если это не POST запрос
            $this->getPage();
        }
    }

    public function addAd()
    {
        $this->data['description'] = $this->ads->getAdDescription();
        $this->view->generate('/ads/addAd.php', 'indexTemplate.php', $this->data, $this->error);
    }

    public function createAd()
    {
        if (isset($_POST)) {
            $id = $this->ads->createAd($_POST);
            if (is_int($id)) {
                // переходит к просмотру созданного объявления
                $url = 'Location: /ads/getad?id=' . $id;
                header($url);
            } else {
                $this->error = $this->ads->getErrors();
                $this->view->generate('/ads/addAd.php', 'indexTemplate.php', $this->data, $this->error);
            }        
        } else {
            // TODO: должен вернуть и отобразить ошибку, если это не POST запрос
            $this->getPage();
        }
    }
    
    public function delAd() 
    {
        if (isset($_GET['id'])) {
            if ($this->ads->delAd($_GET['id'])) {
                // TODO: необходимо сообщать пользователю об успещном удалении объявления
                $this->getPage();
            } else {
                $this->error = $this->ads->getErrors();
                $this->view->generate('/ads/tableAds.php', 'indexTemplate.php', $this->data, $this->error);
            }  
        } else {
            // TODO: должен вернуть и отобразить ошибку, если не передан id
            $this->getPage();
        }
    }
    
    public function search()
    {
        $this->data['adsTable'] = array();
        $searchQuery = '';
        
        if (isset($_POST['searchQuery'])) {
            $searchQuery = $_POST['searchQuery'];  
        } else if (isset($_GET['page']) && isset($_GET['query'])){
            $searchQuery = $_GET['query'];
        } else {
            // TODO: должен вернуть и отобразить ошибку, если это не POST или GET запрос
            $this->getPage();
        }
        
        
        $searchResult = $this->ads->search($searchQuery); 
        if ($searchResult) {
            $this->data['adsTable'] = $searchResult;
            $this->data['pagination'] = $this->ads->getPagination('search', $searchQuery);
            $this->view->generate('/ads/tableAds.php', 'indexTemplate.php', $this->data, $this->error);
        } else {
            $this->error = $this->ads->getErrors();
            $this->view->generate('/ads/tableAds.php', 'indexTemplate.php', $this->data, $this->error);
        }  
    }
    
}
