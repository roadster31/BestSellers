<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace BestSellers\Loop;

use BestSellers\BestSellers;
use BestSellers\EventListeners\BestSellersEvent;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Product;
use Thelia\Model\Map\ProductTableMap;
use Thelia\Type\EnumListType;
use Thelia\Type\TypeCollection;

/**
 * Class BestSellerLoop
 * @package BestSellers\Loop
 * @method string getStartDate()
 * @method string getEndDate()
 */
class BestSellerLoop extends Product
{
    protected function getArgDefinitions()
    {
        $args = parent::getArgDefinitions();

        return $args->addArguments([
            Argument::createAnyTypeArgument('start_date', null, false),
            Argument::createAnyTypeArgument('end_date', null, false),
            new Argument(
                'order',
                new TypeCollection(
                    new EnumListType(
                        [
                            'id', 'id_reverse',
                            'alpha', 'alpha_reverse',
                            'min_price', 'max_price',
                            'manual', 'manual_reverse',
                            'created', 'created_reverse',
                            'updated', 'updated_reverse',
                            'ref', 'ref_reverse',
                            'visible', 'visible_reverse',
                            'position', 'position_reverse',
                            'promo',
                            'new',
                            'random',
                            'given_id',
                            'sold_count', 'sold_count_reverse'
                        ]
                    )
                ),
                'alpha'
            ),
        ]);
    }

    public function buildModelCriteria()
    {
        $query = parent::buildModelCriteria();

        $startDate = $this->getStartDate() ? new \DateTime($this->getStartDate()) : null;
        $endDate   = $this->getEndDate() ? new \DateTime($this->getEndDate()) : null;

        $event = new BestSellersEvent($startDate, $endDate);

        $this->dispatcher->dispatch(BestSellers::GET_BEST_SELLING_PRODUCTS, $event);

        $caseClause = '';

        array_walk($event->getBestSellingProductsData(), function($item) use (&$caseClause) {
            $caseClause .= sprintf("WHEN %d THEN %f ", $item['product_id'], $item['total_quantity']);
        });

        if (! empty($caseClause)) {
            $query
                ->withColumn('CASE ' . ProductTableMap::ID . ' ' . $caseClause . ' ELSE 0 END', 'sold_quantity')
            ;
        } else {
            $query->withColumn('NULL', 'sold_quantity');
        }

        $orders  = $this->getOrder();

        foreach ($orders as $order) {
            switch ($order) {
                case "sold_count":
                    $query->orderBy('sold_quantity', Criteria::ASC);
                    break;
                case "sold_count_reverse":
                    $query->orderBy('sold_quantity', Criteria::DESC);
                    break;
            }
        }

        return $query;
    }

    /**
     * @param LoopResultRow $loopResultRow
     * @param \Thelia\Model\Product $item
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function addOutputFields(LoopResultRow $loopResultRow, $item)
    {
        $loopResultRow
            ->set("SOLD_QUANTITY", $item->getVirtualColumn('sold_quantity') ?: 'oups')
        ;
    }
}
