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

namespace BestSellers\Form;

use BestSellers\BestSellers;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Form\BaseForm;

class Configuration extends BaseForm
{
    protected function buildForm(): void
    {
        $form = $this->formBuilder;

        $form->add('order', TextType::class, [
            'data' => BestSellers::getConfigValue('order_types'),
            'required' => true,
            'empty_data' => '2,3,4',
        ]);
    }

    public static function getName()
    {
        return 'bestsellers_configuration';
    }
}
