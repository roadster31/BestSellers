<?php

namespace BestSellers\Controller;


use BestSellers\Form\Configuration;
use BestSellers\BestSellers;
use ClassicRide\ClassicRide;
use Front\Front;
use Symfony\Component\HttpFoundation\JsonResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;


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

        } catch (FormValidationException $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    ClassicRide::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );
            return $this->generateSuccessRedirect($form);
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    ClassicRide::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );
            return $this->generateSuccessRedirect($form);        }

        return $response;
    }
}