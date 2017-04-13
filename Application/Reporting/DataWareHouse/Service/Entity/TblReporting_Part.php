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

/**
 * @Entity
 * @Table(name="TblReporting_Part")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part extends Element
{
    const ATTR_NUMBER = 'Number';
    const ATTR_NUMBER_DISPLAY = 'NumberDisplay';
    const ATTR_NAME = 'Name';
    const ATTR_SPARE_PART_DESIGN = 'SparePartDesign';
    const ATTR_STATUS_ACTIVE = 'StatusActive';

    /**
     * @Column(type="string")
     */
    protected $Number;

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

    //Vorgänger
    //Nachfolger
    //Wahlweise

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