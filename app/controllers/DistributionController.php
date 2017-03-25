<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\DistributionModel;

class DistributionController extends Controller
{

    private $distribution;

    public function __construct()
    {
        parent::__construct();
        // авторизация, если не пройдена, то произойдет переход к форме аутентификации
        $this->checkAuth();

        $this->distribution = new DistributionModel();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'][0] = 'distribution';
    }

    public function getPage()
    {
        $this->view->generate('/distribution/distributionForm.php', 'indexTemplate.php', $this->data, $this->error);
    }

    public function getNextNumberAd()
    {
        echo $this->distribution->getNextNumberAd();
    }

    public function checkOnRepeat()
    {
        echo $this->distribution->checkOnRepeat($_GET);
    }

    public function getTime()
    {
        echo $this->distribution->getTime();
    }

    public function addAd($dataPOST)
    {
        if (isset($_POST)) {
            if ($this->distribution->addAd($_POST)) {
                $this->data['successful'] = $this->distribution->getSuccessful();
                $this->view->generate('/distribution/distributionForm.php', 'indexTemplate.php', $this->data, $this->error);
            } else {
                $this->error = $this->distribution->getErrors();
                $this->view->generate('/distribution/distributionForm.php', 'indexTemplate.php', $this->data, $this->error);
            }
            
        } else {
            // TODO: должен вернуть и отобразить ошибку, если это не POST запрос
            $this->getPage();
        }
    }

}
