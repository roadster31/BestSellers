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

/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */

/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */

namespace BestSellers;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use Thelia\Module\BaseModule;

class BestSellers extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'bestsellers';

    const GET_BEST_SELLING_PRODUCTS = 'bestsellers.event.get_best_selling_products';

    const BO_MESSAGE_DOMAIN = 'bestsellers.bo.default';

    /* Data cache lifetime in minutes */
    const CACHE_LIFETIME_IN_MINUTES = 1440;

    public function postActivation(ConnectionInterface $con = null): void
    {
        self::setConfigValue('order_types', '2,3,4');
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([THELIA_MODULE_DIR.ucfirst(self::getModuleCode()).'/I18n/*'])
            ->autowire(true)
            ->autoconfigure(true);
    }
}
