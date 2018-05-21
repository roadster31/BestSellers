<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace BestSellers;

use Thelia\Module\BaseModule;

class BestSellers extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'bestsellers';

    const GET_BEST_SELLING_PRODUCTS = "bestsellers.event.get_best_selling_products";

    /* Data cache lifetime in minutes */
    const CACHE_LIFETIME_IN_MINUTES = 1440;
}
