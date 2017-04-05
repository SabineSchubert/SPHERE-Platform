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
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Price")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Price extends Element
{
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
    public function getTblReportingDiscountGroup()
    {
        return $this->TblReporting_DiscountGroup;
    }

    /**
     * @param mixed $TblReporting_DiscountGroup
     */
    public function setTblReportingDiscountGroup($TblReporting_DiscountGroup)
    {
        $this->TblReporting_DiscountGroup = $TblReporting_DiscountGroup;
    }

    /**
     * @return mixed
     */
    public function getPriceGross()
    {
        return $this->PriceGross;
    }

    /**
     * @param mixed $PriceGross
     */
    public function setPriceGross($PriceGross)
    {
        $this->PriceGross = $PriceGross;
    }

    /**
     * @return mixed
     */
    public function getBackValue()
    {
        return $this->BackValue;
    }

    /**
     * @param mixed $BackValue
     */
    public function setBackValue($BackValue)
    {
        $this->BackValue = $BackValue;
    }

    /**
     * @return mixed
     */
    public function getCostsVariable()
    {
        return $this->CostsVariable;
    }

    /**
     * @param mixed $CostsVariable
     */
    public function setCostsVariable($CostsVariable)
    {
        $this->CostsVariable = $CostsVariable;
    }

    /**
     * @return mixed
     */
    public function getValidFrom()
    {
        return $this->ValidFrom;
    }

    /**
     * @param mixed $ValidFrom
     */
    public function setValidFrom($ValidFrom)
    {
        $this->ValidFrom = $ValidFrom;
    }

}