<?php
/*************************************************************************************/
/*                                                                                   */
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*                                                                                   */
/*************************************************************************************/

/**
 * @author Franck Allimant <franck@cqfdev.fr>
 *
 * Creation date: 23/03/2015 12:09
 */

namespace BestSellers\Hook;

use BestSellers\BestSellers;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Tools\URL;

class HookManager extends BaseHook
{
    public function onMainTopMenuTools(HookRenderBlockEvent $event)
    {
        $event->add(
            [
                'id' => 'bestsellers_menu_tags',
                'class' => '',
                'url' => URL::getInstance()->absoluteUrl('/admin/best-sellers'),
                'title' => $this->trans("Best sellers", [], BestSellers::DOMAIN_NAME)
            ]
        );
    }
}
