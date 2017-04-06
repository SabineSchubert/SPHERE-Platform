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
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
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

    const TBL_REPORTING_PART = 'TblReporting_Part';

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
     * @return null|TblReporting_Part
     */
    public function getTblReportingPart()
    {
        return ( $this->TblReporting_Part ? DataWareHouse::useService()->getPartById( $this->TblReporting_Part ) : null );
    }

    /**
     * @param null|TblReporting_Part $TblReporting_Part
     */
    public function setTblReportingPart( TblReporting_Part $TblReporting_Part = null )
    {
        $this->TblReporting_Part = ($TblReporting_Part ? $TblReporting_Part->getId() : null );
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return (int)$this->Month;
    }

    /**
     * @param int $Month
     */
    public function setMonth($Month)
    {
        $this->Month = (int)$Month;
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
     * @return float
     */
    public function getSalesGross()
    {
        return (float)$this->SalesGross;
    }

    /**
     * @param float $SalesGross
     */
    public function setSalesGross($SalesGross)
    {
        $this->SalesGross = round((float)$SalesGross,4);
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