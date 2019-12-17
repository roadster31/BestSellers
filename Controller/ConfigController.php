<?php

namespace BestSellers\Controller;


use BestSellers\Form\Configuration;
use BestSellers\BestSellers;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Model\ConfigQuery;
use TheliaSmarty\Template\Plugins\Form;

class ConfigController extends BaseAdminController
{
    public function setAction()
    {
        $form = new Configuration($this->getRequest());
        $response = null;

        $configForm = $this->validateForm($form);
        BestSellers::setConfigValue('order_types', $configForm->get('order')->getData(),null, true);

        $response = $this->render(
            'module-configure',
            ['module_code' => 'BestSellers']
        );

        return $response;
    }
}