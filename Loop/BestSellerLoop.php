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

/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */

/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */

namespace BestSellers\Loop;

use BestSellers\BestSellers;
use BestSellers\EventListeners\BestSellersEvent;
use Propel\Runtime\ActiveQuery\Criteria;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Thelia\Core\Security\SecurityContext;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Product;
use Thelia\Model\Map\ProductTableMap;
use Thelia\Type\EnumListType;
use Thelia\Type\TypeCollection;

/**
 * Class BestSellerLoop.
 *
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
                            'sold_count', 'sold_count_reverse',
                            'sold_amount', 'sold_amount_reverse',
                            'sale_ratio', 'sale_ratio_reverse',
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
        $endDate = $this->getEndDate() ? new \DateTime($this->getEndDate()) : null;

        $event = new BestSellersEvent($startDate, $endDate);

        $this->dispatcher->dispatch($event, BestSellers::GET_BEST_SELLING_PRODUCTS);

        $caseClause = $caseSalesClause = '';

        $productData = $event->getBestSellingProductsData();

        array_walk($productData, function ($item) use (&$caseClause, &$caseSalesClause): void {
            $caseClause .= sprintf('WHEN %d THEN %F ', $item['product_id'], $item['total_quantity']);
            $caseSalesClause .= sprintf('WHEN %d THEN %F ', $item['product_id'], $item['total_sales']);
        });

        if (!empty($caseClause)) {
            $query
                ->withColumn('CASE '.ProductTableMap::ID.' '.$caseClause.' ELSE 0 END', 'sold_quantity')
                ->withColumn('CASE '.ProductTableMap::ID.' '.$caseSalesClause.' ELSE 0 END', 'sold_amount')
            ;
        } else {
            $query
                ->withColumn('(0)', 'sold_quantity')
                ->withColumn('(0)', 'sold_amount')
                ;
        }

        if ($event->getTotalSales() !== 0) {
            $query->withColumn('(select 100 * sold_amount / '.$event->getTotalSales().')', 'sale_ratio');
        } else {
            $query->withColumn('(0)', 'sale_ratio');
        }

        $orders = $this->getOrder();

        foreach ($orders as $order) {
            switch ($order) {
                case 'sold_count':
                    $query->orderBy('sold_quantity', Criteria::ASC);
                    break;
                case 'sold_count_reverse':
                    $query->orderBy('sold_quantity', Criteria::DESC);
                    break;
                case 'sold_amount':
                    $query->orderBy('sold_amount', Criteria::ASC);
                    break;
                case 'sold_amount_reverse':
                    $query->orderBy('sold_amount', Criteria::DESC);
                    break;
                case 'sale_ratio':
                    $query->orderBy('sale_ratio', Criteria::ASC);
                    break;
                case 'sale_ratio_reverse':
                    $query->orderBy('sale_ratio', Criteria::DESC);
                    break;
            }
        }

        return $query;
    }

    /**
     * @param \Thelia\Model\Product $item
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function addOutputFields(LoopResultRow $loopResultRow, $item): void
    {
        $loopResultRow
            ->set('SOLD_QUANTITY', $item->getVirtualColumn('sold_quantity'))
            ->set('SOLD_AMOUNT', $item->getVirtualColumn('sold_amount'))
            ->set('SALE_RATIO', $item->getVirtualColumn('sale_ratio'))
        ;
    }
}
