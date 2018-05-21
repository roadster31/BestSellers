<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

/**
 * Created by Franck Allimant, CQFDev <franck@cqfdev.fr>
 * Date: 21/05/2018 16:34
 */

namespace BestSellers\EventListeners;

use Thelia\Core\Event\ActionEvent;

class BestSellersEvent extends ActionEvent
{
    /** @var \DateTime */
    protected $startDate;

    /** @var \DateTime */
    protected $endDate;

    /** @var array */
    protected $bestSellingProductsData = [];

    /**
     * BestSellersEvent constructor.
     * @param $startDate
     * @param $endDate
     */
    public function __construct(\DateTime $startDate = null, \DateTime $endDate = null)
    {
        $this->startDate = null === $startDate ? new \DateTime("1970-01-01") : $startDate;
        $this->endDate = null === $endDate ? new \DateTime() : $endDate;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     * @return $this
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }


    /**
     * @return array
     */
    public function getBestSellingProductsData()
    {
        return $this->bestSellingProductsData;
    }

    /**
     * @param array $bestSellingProductsData
     * @return $this
     */
    public function setBestSellingProductsData($bestSellingProductsData)
    {
        $this->bestSellingProductsData = $bestSellingProductsData;
        return $this;
    }
}
