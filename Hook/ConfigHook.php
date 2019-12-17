<?php

namespace BestSellers\Hook;


use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class ConfigHook extends BaseHook
{
    public function onModuleConfiguration(HookRenderEvent $event){
        $event->add($this->render("config/module-config.html"));
    }
}