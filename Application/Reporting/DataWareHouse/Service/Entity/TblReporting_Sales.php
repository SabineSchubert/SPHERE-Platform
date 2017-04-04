<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 08:52
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Sales")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Sales extends Element
{
    const ATTR_MONTH = 'Month';
    const ATTR_YEAR = 'Year';
    const ATTR_QUANTITY = 'Quantity';
    const ATTR_SALES_GROSS = 'SalesGross';
    const ATTR_SALES_NET = 'SalesNet';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="integer")
     */
    protected $Month;

    /**
     * @Column(type="integer")
     */
    protected $Year;

    /**
     * @Column(type="integer")
     */
    protected $Quantity;

    /**
     * @Column(type="float")
     */
    protected $SalesGross;

    /**
     * @Column(type="float")
     */
    protected $SalesNet;

    /**
     * @return mixed
     */
    public function getTblReportingPart()
    {
        return $this->TblReporting_Part;
    }

    /**
     * @param mixed $TblReporting_Part
     */
    public function setTblReportingPart($TblReporting_Part)
    {
        $this->TblReporting_Part = $TblReporting_Part;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->Month;
    }

    /**
     * @param mixed $Month
     */
    public function setMonth($Month)
    {
        $this->Month = $Month;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->Year;
    }

    /**
     * @param mixed $Year
     */
    public function setYear($Year)
    {
        $this->Year = $Year;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->Quantity;
    }

    /**
     * @param mixed $Quantity
     */
    public function setQuantity($Quantity)
    {
        $this->Quantity = $Quantity;
    }

    /**
     * @return mixed
     */
    public function getSalesGross()
    {
        return $this->SalesGross;
    }

    /**
     * @param mixed $SalesGross
     */
    public function setSalesGross($SalesGross)
    {
        $this->SalesGross = $SalesGross;
    }

    /**
     * @return mixed
     */
    public function getSalesNet()
    {
        return $this->SalesNet;
    }

    /**
     * @param mixed $SalesNet
     */
    public function setSalesNet($SalesNet)
    {
        $this->SalesNet = $SalesNet;
    }


}