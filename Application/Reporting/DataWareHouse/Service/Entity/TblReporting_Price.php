<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 05.04.2017
 * Time: 08:19
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Cache;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Price")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Price extends Element
{
    const TBL_REPORTING_PART = 'TblReporting_Part';
    const TBL_REPORTING_DISCOUNT_GROUP = 'TblReporting_DiscountGroup';
    const ATTR_PRICE_GROSS = 'PriceGross';
    const ATTR_BACK_VALUE = 'BackValue';
    const ATTR_COSTS_VARIABLE = 'CostsVariable';
    const ATTR_VALID_FROM  = 'ValidFrom';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_DiscountGroup;

    /**
     * @Column(type="float")
     */
    protected $PriceGross;

    /**
     * @Column(type="float")
     */
    protected $BackValue;

    /**
     * @Column(type="float")
     */
    protected $CostsVariable;

    /**
     * @Column(type="date")
     */
    protected $ValidFrom;

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
    public function setTblReportingPart(TblReporting_Part $TblReporting_Part)
    {
        $this->TblReporting_Part = ( $TblReporting_Part ? $TblReporting_Part->getId() : null );
    }

    /**
     * @return null|TblReporting_DiscountGroup
     */
    public function getTblReportingDiscountGroup()
    {
        return ( $this->TblReporting_DiscountGroup ? DataWareHouse::useService()->getDiscountGroupById( $this->TblReporting_DiscountGroup ) : null );
    }

    /**
     * @param null|TblReporting_DiscountGroup $TblReporting_DiscountGroup
     */
    public function setTblReportingDiscountGroup(TblReporting_DiscountGroup $TblReporting_DiscountGroup)
    {
        $this->TblReporting_DiscountGroup = ( $TblReporting_DiscountGroup ? $TblReporting_DiscountGroup->getId() : null );
    }

    /**
     * @return float
     */
    public function getPriceGross()
    {
        return (float)$this->PriceGross;
    }

    /**
     * @param float $PriceGross
     */
    public function setPriceGross($PriceGross)
    {
        $this->PriceGross = (float)$PriceGross;
    }

    /**
     * @return float
     */
    public function getBackValue()
    {
        return (float)$this->BackValue;
    }

    /**
     * @param float $BackValue
     */
    public function setBackValue($BackValue)
    {
        $this->BackValue = (float)$BackValue;
    }

    /**
     * @return float
     */
    public function getCostsVariable()
    {
        return (float)$this->CostsVariable;
    }

    /**
     * @param float $CostsVariable
     */
    public function setCostsVariable($CostsVariable)
    {
        $this->CostsVariable = (float)$CostsVariable;
    }

    /**
     * @return string
     */
    public function getValidFrom()
    {
        /** @var string $DateTime */
        $DateTime = $this->ValidFrom;
//        if ($DateTime instanceof \DateTime) {
//            return $DateTime;
//        } else {
            return $DateTime;
//        }
    }

    /**
     * @param null|\string $ValidFrom
     */
    public function setValidFrom(/*\DateTime */$ValidFrom = null)
    {
        $this->ValidFrom = $ValidFrom;
    }



}