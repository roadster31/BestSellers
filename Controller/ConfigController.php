<?php

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BestSellers\Controller;

use BestSellers\BestSellers;
use BestSellers\Form\Configuration;
use ClassicRide\ClassicRide;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;

class ConfigController extends BaseAdminController
{
    public function setAction()
    {
        $form = $this->createForm(Configuration::getName());
        $response = null;

        try {
            $configForm = $this->validateForm($form);
            $data = $configForm->get('order')->getData();

            BestSellers::setConfigValue('order_types', $data, null, true);

            $response = $this->render(
                'module-configure',
                ['module_code' => 'BestSellers']
            );
        } catch (FormValidationException $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    'Error',
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
                    'Error',
                    [],
                    ClassicRide::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );

            return $this->generateSuccessRedirect($form);
        }

        return $response;
    }
}
