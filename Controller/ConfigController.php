<?php

namespace BestSellers\Controller;


use BestSellers\Form\Configuration;
use BestSellers\BestSellers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Thelia\Controller\Admin\BaseAdminController;


class ConfigController extends BaseAdminController
{
    public function setAction()
    {
        $form = new Configuration($this->getRequest());
        $response = null;

        try {
            $configForm = $this->validateForm($form);
            $data = $configForm->get('order')->getData();

            BestSellers::setConfigValue('order_types', $data,null, true);

            $response = $this->render(
                'module-configure',
                ['module_code' => 'BestSellers']
            );
        } catch (\Exception $e) {
            $response = JsonResponse::create(array('error' =>$e->getMessage()), 500);
        }

        return $response;
    }
}