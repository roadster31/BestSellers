<?php

namespace BestSellers\Form;


use BestSellers\BestSellers;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Thelia\Form\BaseForm;

class Configuration extends BaseForm
{
    protected function buildForm()
    {
        $form = $this->formBuilder;

        $form->add('order','text',[
            'data'=> BestSellers::getConfigValue('order_types'),
            'required' => true,
            'empty_data' => '2,3,4',
        ]);
    }

    public function getName(){
        return 'bestsellers_configuration';
    }
}