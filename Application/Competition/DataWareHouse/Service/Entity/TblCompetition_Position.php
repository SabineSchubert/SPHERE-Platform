<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 10.05.2017
 * Time: 11:14
 */

namespace SPHERE\Application\Competition\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Position")
 * @Cache(usage="READ_ONLY")
 */
class TblCompetition_Position extends Element
{
    const TBL_COMPETITION_MAIN = 'TblCompetition_Main';
    const TBL_REPORTING_PART = 'TblReporting_Part';
    const TBL_COMPETITION_COMPETITOR = 'TblCompetition_Competitor';
    const ATTR_COMPETITOR = 'Competitor';
    const TBL_COMPETITION_MANUFACTURER = 'TblCompetition_Manufacturer';
    const ATTR_MANUFACTURER = 'Manufacturer';
    const ATTR_PRICE_NET = 'PriceNet';
    const ATTR_PRICE_GROSS = 'PriceGross';
    const ATTR_DISCOUNT = 'Discount';
    const ATTR_VAT = 'Vat';
    const ATTR_DISTRIBUTOR_OR_CUSTOMER = 'DistributorOrCustomer';
    const ATTR_COMMENT = 'Comment';
    const ATTR_PACKING_UNIT = 'PackingUnit';
    const ATTR_DOT = 'Dot';
    const ATTR_ACTION_PART = 'ActionPart';

    /**
     * @Column(type="bigint")
     */
    protected $TblCompetition_Main;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="int")
     */
    protected $TblCompetition_Competitor;

    /**
     * @Column(type="string")
     */
    protected $Competitor;

    /**
     * @Column(type="int")
     */
    protected $TblCompetition_Manufacturer;

    /**
     * @Column(type="string")
     */
    protected $Manufacturer;

    /**
     * @Column(type="float")
     */
    protected $PriceNet;

    /**
     * @Column(type="float")
     */
    protected $PriceGross;

    /**
     * @Column(type="float")
     */
    protected $Discount;

    /**
     * @Column(type="boolean")
     */
    protected $Vat;

    /**
     * @Column(type="string")
     */
    protected $DistributorOrCustomer;

    /**
     * @Column(type="text")
     */
    protected $Comment;

    /**
     * @Column(type="int")
     */
    protected $PackingUnit;

    /**
     * @Column(type="string")
     */
    protected $Dot;

    /**
     * @Column(type="boolean")
     */
    protected $ActionPart;

    /**
     * @return TblCompetition_Main
     */
    public function getTblCompetitionMain()
    {
        return $this->TblCompetition_Main;
    }

    /**
     * @param TblCompetition_Main $TblCompetition_Main
     */
    public function setTblCompetitionMain($TblCompetition_Main)
    {
        $this->TblCompetition_Main = $TblCompetition_Main;
    }

    /**
     * @return TblReporting_Part
     */
    public function getTblReportingPart()
    {
        return $this->TblReporting_Part;
    }

    /**
     * @param TblReporting_Part $TblReportingPart
     */
    public function setTblReportingPart($TblReportingPart)
    {
        $this->TblReportingPart = $TblReportingPart;
    }

    /**
     * @return TblCompetition_Competitor
     */
    public function getTblCompetitionCompetitor()
    {
        return $this->TblCompetition_Competitor;
    }

    /**
     * @param TblCompetition_Competitor $TblCompetition_Competitor
     */
    public function setTblCompetitionCompetitor($TblCompetition_Competitor)
    {
        $this->TblCompetition_Competitor = $TblCompetition_Competitor;
    }

    /**
     * @return string
     */
    public function getCompetitor()
    {
        return $this->Competitor;
    }

    /**
     * @param string $Competitor
     */
    public function setCompetitor($Competitor)
    {
        $this->Competitor = $Competitor;
    }

    /**
     * @return TblCompetition_Manufacturer
     */
    public function getTblCompetitionManufacturer()
    {
        return $this->TblCompetition_Manufacturer;
    }

    /**
     * @param TblCompetition_Manufacturer $TblCompetition_Manufacturer
     */
    public function setTblCompetitionManufacturer($TblCompetition_Manufacturer)
    {
        $this->TblCompetition_Manufacturer = $TblCompetition_Manufacturer;
    }

    /**
     * @return string
     */
    public function getManufacturer()
    {
        return $this->Manufacturer;
    }

    /**
     * @param string $Manufacturer
     */
    public function setManufacturer($Manufacturer)
    {
        $this->Manufacturer = $Manufacturer;
    }

    /**
     * @return float
     */
    public function getPriceNet()
    {
        return $this->PriceNet;
    }

    /**
     * @param float $PriceNet
     */
    public function setPriceNet($PriceNet)
    {
        $this->PriceNet = $PriceNet;
    }

    /**
     * @return float
     */
    public function getPriceGross()
    {
        return $this->PriceGross;
    }

    /**
     * @param float $PriceGross
     */
    public function setPriceGross($PriceGross)
    {
        $this->PriceGross = $PriceGross;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->Discount;
    }

    /**
     * @param float $Discount
     */
    public function setDiscount($Discount)
    {
        $this->Discount = $Discount;
    }

    /**
     * @return boolean
     */
    public function getVat()
    {
        return $this->Vat;
    }

    /**
     * @param boolean $Vat
     */
    public function setVat($Vat)
    {
        $this->Vat = $Vat;
    }

    /**
     * @return string
     */
    public function getDistributorOrCustomer()
    {
        return $this->DistributorOrCustomer;
    }

    /**
     * @param string $DistributorOrCustomer
     */
    public function setDistributorOrCustomer($DistributorOrCustomer)
    {
        $this->DistributorOrCustomer = $DistributorOrCustomer;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->Comment;
    }

    /**
     * @param string $Comment
     */
    public function setComment($Comment)
    {
        $this->Comment = $Comment;
    }

    /**
     * @return int
     */
    public function getPackingUnit()
    {
        return $this->PackingUnit;
    }

    /**
     * @param int $PackingUnit
     */
    public function setPackingUnit($PackingUnit)
    {
        $this->PackingUnit = $PackingUnit;
    }

    /**
     * @return string
     */
    public function getDot()
    {
        return $this->Dot;
    }

    /**
     * @param string $Dot
     */
    public function setDot($Dot)
    {
        $this->Dot = $Dot;
    }

    /**
     * @return boolean
     */
    public function getActionPart()
    {
        return $this->ActionPart;
    }

    /**
     * @param boolean $ActionPart
     */
    public function setActionPart($ActionPart)
    {
        $this->ActionPart = $ActionPart;
    }



}