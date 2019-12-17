<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace BestSellers\EventListeners;

use BestSellers\BestSellers;
use Propel\Runtime\Connection\PdoConnection;
use Propel\Runtime\Propel;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Action\BaseAction;
use Thelia\Model\Map\OrderProductTableMap;
use Thelia\Model\Map\OrderTableMap;
use Thelia\Model\Map\ProductTableMap;
use Thelia\Model\OrderStatusQuery;

class EventManager extends BaseAction implements EventSubscriberInterface
{
    /** @var AdapterInterface $cacheAdapter */
    protected $cacheAdapter;

    /**
     * DigressivePriceListener constructor.
     * @param AdapterInterface $cacheAdapter
     */
    public function __construct(AdapterInterface $cacheAdapter)
    {
        $this->cacheAdapter = $cacheAdapter;
    }

    public static function getSubscribedEvents()
    {
        return [
            BestSellers::GET_BEST_SELLING_PRODUCTS => [ "calculateBestSellers", 128 ]
        ];
    }

    public function calculateBestSellers(BestSellersEvent $event)
    {
        $cacheKey = sprintf(
            "best_sellers_%s_%s",
            $event->getStartDate()->format('Y-m-d'),
            $event->getEndDate()->format('Y-m-d')
        );

        try {
            $cacheItem = $this->cacheAdapter->getItem($cacheKey);

            if (! $cacheItem->isHit()) {
                /** @var PdoConnection $con */
                $con = Propel::getConnection();

                $statusList = BestSellers::getConfigValue('order_types');
                if (!$statusList || $statusList === '') {
                    $statusList = '2,3,4';
                }

                $query = '
                    SELECT 
                        ' . ProductTableMap::COL_ID . ' as product_id,
                        SUM(' . OrderProductTableMap::COL_QUANTITY . ') as total_quantity,
                        SUM(' . OrderProductTableMap::COL_QUANTITY. ' * IF(' . OrderProductTableMap::COL_WAS_IN_PROMO . ',' . OrderProductTableMap::COL_PROMO_PRICE . ', ' . OrderProductTableMap::COL_PRICE . ')) as total_sales
                    FROM
                        ' . OrderProductTableMap::TABLE_NAME . '
                    LEFT JOIN
                        ' . OrderTableMap::TABLE_NAME . ' on ' . OrderTableMap::COL_ID . ' = ' . OrderProductTableMap::COL_ORDER_ID . '
                    LEFT JOIN
                        ' . ProductTableMap::TABLE_NAME . ' on ' . ProductTableMap::COL_REF . ' = ' . OrderProductTableMap::COL_PRODUCT_REF . '
                    WHERE
                        ' . OrderTableMap::COL_CREATED_AT . ' >= ?    
                    AND
                        ' . OrderTableMap::COL_CREATED_AT . ' <= ?    
                    AND
                        ' . OrderTableMap::COL_STATUS_ID . ' IN ( '. $statusList .' )
                    GROUP BY  
                        ' . ProductTableMap::COL_ID . '
                    ORDER BY
                        total_quantity desc
                    ';

                $query = preg_replace("/order([^_])/", "`order`$1", $query);

                $stmt = $con->prepare($query);

                $res = $stmt->execute([
                    $event->getStartDate()->format("Y-m-d H:i:s"),
                    $event->getEndDate()->format("Y-m-d H:i:s")
                ]);

                $data = [];

                $totalSales = 0;

                while ($res && $result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $data[] = $result;

                    $totalSales += $result['total_sales'];
                }

                $struct = [
                    'data' => $data,
                    'total_sales' => $totalSales
                ];

                $cacheItem
                    ->set(json_encode($struct))
                    ->expiresAfter(60 * BestSellers::CACHE_LIFETIME_IN_MINUTES)
                ;

                $this->cacheAdapter->save($cacheItem);
            }

            $struct = json_decode($cacheItem->get(), true);

            $event
                ->setBestSellingProductsData($struct['data'])
                ->setTotalSales($struct['total_sales'])
            ;

        } catch (InvalidArgumentException $e) {
            // Nothing to do with this, return an empty result.
        }
    }
}
