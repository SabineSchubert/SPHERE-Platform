<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 09:16
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;
use SPHERE\System\Extension\Repository\Debugger;

/**
 * @Entity
 * @Table(name="TblReporting_Part")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part extends Element
{
    const ATTR_NUMBER = 'Number';
    const ATTR_NUMBER_BASIC = 'NumberBasic';
    const ATTR_ES1 = 'Es1';
    const ATTR_ES2 = 'Es2';
    const ATTR_NUMBER_DISPLAY = 'NumberDisplay';
    const ATTR_NAME = 'Name';
    const ATTR_SPARE_PART_DESIGN = 'SparePartDesign';
    const ATTR_STATUS_ACTIVE = 'StatusActive';
    const ATTR_PREDECESSOR = 'Predecessor';
    const ATTR_SUCCESSOR = 'Successor';
    const ATTR_OPTIONAL_NUMBER = 'OptionalNumber';

    const ATTR_EXCHANGE_NUMBER = 'ExchangeNumber';
    const ATTR_EXCHANGE_PART = 'ExchangePart';
    const ATTR_EXHAUSTION = 'Exhaustion';
    const ATTR_LENGTH = 'Length';
    const ATTR_WIDTH = 'Width';
    const ATTR_HEIGHT = 'Height';
    const ATTR_PACKAGING_UNIT = 'PackagingUnit';
    const ATTR_MODEL_SERIES = 'ModelSeries';
    const ATTR_CREATION_DATE = 'CreationDate';
    const ATTR_REPAIR_KIT = 'RepairKit';
    const ATTR_WEIGHT = 'Weight';
    const ATTR_SERIES = 'Series';
    const ATTR_LIFECYCLE = 'Lifecycle';
    const ATTR_COUNTER_SHARE = 'CounterShare';
    const ATTR_PRICE_LEADER = 'PriceLeader';
    const ATTR_PRICE_EQUAL = 'PriceEqual';

    /**
     * @Column(type="string")
     */
    protected $Number;

    /**
     * @Column(type="string")
     */
    protected $NumberBasic;

    /**
     * @Column(type="string")
     */
    protected $Es1;

    /**
     * @Column(type="string")
     */
    protected $Es2;

    /**
     * @Column(type="string")
     */
    protected $NumberDisplay;

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @Column(type="string")
     */
    protected $SparePartDesign;

    /**
     * @Column(type="boolean")
     */
    protected $StatusActive;

    /**
     * @Column(type="string")
     */
    protected $Predecessor;

    /**
     * @Column(type="string")
     */
    protected $Successor;

    /**
     * @Column(type="string")
     */
    protected $OptionalNumber;

    /**
     * @Column(type="string")
     */
    protected $ExchangeNumber;

    /**
     * Tauschteil
     * @Column(type="boolean")
     */
    protected $ExchangePart;

    /**
     * Aufbrauch: abverkaufen von Restbeständen
     * @Column(type="boolean")
     */
    protected $Exhaustion;

    /**
     * @Column(type="float")
     */
    protected $Length;

    /**
     * @Column(type="float")
     */
    protected $Width;

    /**
     * @Column(type="float")
     */
    protected $Height;

    /**
     * @Column(type="integer")
     */
    protected $PackagingUnit;

    /**
     * Baureihe
     * @Column(type="string")
     */
    protected $ModelSeries;

    /**
     * @Column(type="date")
     */
    protected $CreationDate;

    /**
     * @Column(type="boolean")
     */
    protected $RepairKit;

    /**
     * @Column(type="float")
     */
    protected $Weight;

    /**
     * Serie oder Aftersales, d.h. wird noch produziert oder nicht
     * @Column(type="boolean")
     */
    protected $Series;

    /**
     * Alterssegment
     * @Column(type="string")
     */
    protected $Lifecycle;

    /**
     * Thekenanteil: Prozentsatz der Produkte, die im AH direkt verkauft werden ohne Reparatur
     * @Column(type="float")
     */
    protected $CounterShare;

    /**
     * Preisführer
     * @Column(type="boolean")
     */
    protected $PriceLeader;

    /**
     * Preisgleich
     * @Column(type="boolean")
     */
    protected $PriceEqual;

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->Number;
    }

    /**
     * @param string $Number
     */
    public function setNumber($Number)
    {
        $this->Number = $Number;
    }

    /**
     * @return string
     */
    public function getNumberBasic()
    {
        return $this->NumberBasic;
    }

    /**
     * @param string $NumberBasic
     */
    public function setNumberBasic($NumberBasic)
    {
        $this->NumberBasic = $NumberBasic;
    }

    /**
     * @return string
     */
    public function getEs1()
    {
        return $this->Es1;
    }

    /**
     * @param string $Es1
     */
    public function setEs1($Es1)
    {
        $this->Es1 = $Es1;
    }

    /**
     * @return string
     */
    public function getEs2()
    {
        return $this->Es2;
    }

    /**
     * @param string $Es2
     */
    public function setEs2($Es2)
    {
        $this->Es2 = $Es2;
    }

    /**
     * @return string
     */
    public function getNumberDisplay()
    {
        return $this->NumberDisplay;
    }

    /**
     * @param string $NumberDisplay
     */
    public function setNumberDisplay($NumberDisplay)
    {
        $this->NumberDisplay = $NumberDisplay;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     */
    public function setName($Name)
    {
        $this->Name = $Name;
    }

    /**
     * @return string
     */
    public function getSparePartDesign()
    {
        return $this->SparePartDesign;
    }

    /**
     * @param string $SparePartDesign
     */
    public function setSparePartDesign($SparePartDesign)
    {
        $this->SparePartDesign = $SparePartDesign;
    }

    /**
     * @return boolean
     */
    public function getStatusActive()
    {
        return $this->StatusActive;
    }

    /**
     * @param boolean $StatusActive
     */
    public function setStatusActive($StatusActive)
    {
        $this->StatusActive = $StatusActive;
    }

    /**
     * @return mixed
     */
    public function getPredecessor()
    {
        return $this->Predecessor;
    }

    /**
     * @param mixed $Predecessor
     */
    public function setPredecessor($Predecessor)
    {
        $this->Predecessor = $Predecessor;
    }

    /**
     * @return mixed
     */
    public function getSuccessor()
    {
        return $this->Successor;
    }

    /**
     * @param mixed $Successor
     */
    public function setSuccessor($Successor)
    {
        $this->Successor = $Successor;
    }

    /**
     * @return mixed
     */
    public function getOptionalNumber()
    {
        return $this->OptionalNumber;
    }

    /**
     * @param mixed $OptionalNumber
     */
    public function setOptionalNumber($OptionalNumber)
    {
        $this->OptionalNumber = $OptionalNumber;
    }

    /**
     * @return string
     */
    public function getExchangeNumber()
    {
        return $this->ExchangeNumber;
    }

    /**
     * @param string $ExchangeNumber
     */
    public function setExchangeNumber($ExchangeNumber)
    {
        $this->ExchangeNumber = $ExchangeNumber;
    }

    /**
     * @return boolean
     */
    public function getExchangePart()
    {
        return $this->ExchangePart;
    }

    /**
     * @param boolean $ExchangePart
     */
    public function setExchangePart($ExchangePart)
    {
        $this->ExchangePart = $ExchangePart;
    }

    /**
     * @return boolean
     */
    public function getExhaustion()
    {
        return $this->Exhaustion;
    }

    /**
     * @param boolean $Exhaustion
     */
    public function setExhaustion($Exhaustion)
    {
        $this->Exhaustion = $Exhaustion;
    }

    /**
     * @return float
     */
    public function getLength()
    {
        return $this->Length;
    }

    /**
     * @param float $Length
     */
    public function setLength($Length)
    {
        $this->Length = $Length;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->Width;
    }

    /**
     * @param float $Width
     */
    public function setWidth($Width)
    {
        $this->Width = $Width;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->Height;
    }

    /**
     * @param float $Height
     */
    public function setHeight($Height)
    {
        $this->Height = $Height;
    }

    /**
     * @return integer
     */
    public function getPackagingUnit()
    {
        return $this->PackagingUnit;
    }

    /**
     * @param integer $PackagingUnit
     */
    public function setPackagingUnit($PackagingUnit)
    {
        $this->PackagingUnit = $PackagingUnit;
    }

    /**
     * @return string
     */
    public function getModelSeries()
    {
        return $this->ModelSeries;
    }

    /**
     * @param string $ModelSeries
     */
    public function setModelSeries($ModelSeries)
    {
        $this->ModelSeries = $ModelSeries;
    }

    /**
     * @return string
     */
    public function getCreationDate()
    {
        return $this->CreationDate;
    }

    /**
     * @param string $CreationDate
     */
    public function setCreationDate($CreationDate)
    {
        $this->CreationDate = $CreationDate;
    }

    /**
     * @return boolean
     */
    public function getRepairKit()
    {
        return $this->RepairKit;
    }

    /**
     * @param boolean $RepairKit
     */
    public function setRepairKit($RepairKit)
    {
        $this->RepairKit = $RepairKit;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->Weight;
    }

    /**
     * @param float $Weight
     */
    public function setWeight($Weight)
    {
        $this->Weight = $Weight;
    }

    /**
     * @return boolean
     */
    public function getSeries()
    {
        return $this->Series;
    }

    /**
     * @param boolean $Series
     */
    public function setSeries($Series)
    {
        $this->Series = $Series;
    }

    /**
     * @return string
     */
    public function getLifecycle()
    {
        return $this->Lifecycle;
    }

    /**
     * @param string $Lifecycle
     */
    public function setLifecycle($Lifecycle)
    {
        $this->Lifecycle = $Lifecycle;
    }

    /**
     * @return float
     */
    public function getCounterShare()
    {
        return $this->CounterShare;
    }

    /**
     * @param float $CounterShare
     */
    public function setCounterShare($CounterShare)
    {
        $this->CounterShare = $CounterShare;
    }

    /**
     * @return boolean
     */
    public function getPriceLeader()
    {
        return $this->PriceLeader;
    }

    /**
     * @param boolean $PriceLeader
     */
    public function setPriceLeader($PriceLeader)
    {
        $this->PriceLeader = $PriceLeader;
    }

    /**
     * @return boolean
     */
    public function getPriceEqual()
    {
        return $this->PriceEqual;
    }

    /**
     * @param boolean $PriceEqual
     */
    public function setPriceEqual($PriceEqual)
    {
        $this->PriceEqual = $PriceEqual;
    }

    /**
     * @return null|TblReporting_Price|Element
     */
    public function fetchPriceCurrent() {
        return DataWareHouse::useService()->getPriceByPart( $this );
    }

    /**
     * @return null|TblReporting_MarketingCode|Element
     */
    public function fetchMarketingCodeCurrent() {
        $PartMarketingCode = DataWareHouse::useService()->getPartMarketingCodeByPart( $this );
        if( $PartMarketingCode ) {
            return DataWareHouse::useService()->getMarketingCodeByPartMarketingCode( $PartMarketingCode );
        }
        else {
            return null;
        }
    }

    /**
     * @return null|TblReporting_AssortmentGroup|Element
     */
    public function fetchAssortmentGroupCurrent() {
        $PartAssortmentGroup = DataWareHouse::useService()->getPartAssortmentGroupByPart( $this );
        if( $PartAssortmentGroup ) {
            return DataWareHouse::useService()->getAssortmentGroupByPartAssortmentGroup( $PartAssortmentGroup );
        }
        else {
            return null;
        }
    }

    /**
     * @return array Section|null
     */
    public function fetchSectionListCurrent() {
        /** @var array $PartSectionList */
        $PartSectionList = DataWareHouse::useService()->getPartSectionByPart( $this );

        if($PartSectionList) {
            /** @var TblReporting_Section $EntitySectionList */
            return DataWareHouse::useService()->getSectionListByPartSectionList( $PartSectionList );
        }
        else {
            return null;
        }
    }

    /**
     * @return array Supplier|null
     */
    public function fetchSupplierListCurrent() {
        /** @var array $PartSupplierList */
        $PartSupplierList = DataWareHouse::useService()->getPartSupplierByPart( $this );
        if($PartSupplierList) {
            /** @var TblReporting_Supplier $EntitySupplierList */
            return DataWareHouse::useService()->getSupplierListByPartSupplierList( $PartSupplierList );
        }
        else {
            return null;
        }


    }

}