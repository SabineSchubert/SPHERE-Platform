<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 29.03.2017
 * Time: 08:28
 */

namespace SPHERE\Application\Reporting\DataWareHouse;


use SPHERE\Application\Reporting\DataWareHouse\Service\Data;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_AssortmentGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Brand;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_DiscountGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode_PartsMore;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_AssortmentGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_Supplier;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_PartsMore;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Price;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductLevel;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_ProductManagerGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManagerGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Section;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Supplier;
use SPHERE\Application\Reporting\DataWareHouse\Service\Setup;
use SPHERE\System\Database\Binding\AbstractService;
use SPHERE\System\Database\Fitting\Element;
use SPHERE\System\Extension\Repository\Debugger;

class Service extends AbstractService
{
	/**
     * @param bool $doSimulation
     * @param bool $withData
     *
     * @return string
     */
    public function setupService($doSimulation, $withData)
    {

        $Protocol = (new Setup($this->getStructure()))->setupDatabaseSchema($doSimulation);
        if (!$doSimulation && $withData) {
            (new Data($this->getBinding()))->setupDatabaseContent();
        }
        return $Protocol;
    }

    /**
     * @param int $Id
     * @return null|TblReporting_ProductManagerGroup
     */
    public function getProductManagerGroupById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getProductManagerGroupById( $Id );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_ProductManager|Element
     */
    public function getProductManagerById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getProductManagerById( $Id );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_MarketingCode|Element
     */
    public function getMarketingCodeById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeById( $Id );
    }

    /**
     * @param TblReporting_MarketingCode $TblReporting_MarketingCode
     * @return null|TblReporting_ProductManager_MarketingCode|Element
     */
    public function getProductManagerMarketingCodeByMarketingCode( TblReporting_MarketingCode $TblReporting_MarketingCode ) {
        return ( new Data( $this->getBinding() ) )->getProductManagerMarketingCodeByMarketingCode( $TblReporting_MarketingCode );
    }

    /**
     * @param TblReporting_ProductManager_MarketingCode $TblReporting_ProductManager_MarketingCode
     * @return null|TblReporting_ProductManager|Element
     */
    public function getProductManagerByProductManagerMarketingCode( TblReporting_ProductManager_MarketingCode $TblReporting_ProductManager_MarketingCode ) {
        return ( new Data( $this->getBinding() ) )->getProductManagerByProductManagerMarketingCode( $TblReporting_ProductManager_MarketingCode );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_ProductGroup|Element
     */
    public function getProductGroupById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getProductGroupById( $Id );
    }

    /**
     * @param TblReporting_MarketingCode $TblReporting_MarketingCode
     * @return null|TblReporting_MarketingCode_ProductGroup[]|Element[]
     */
    public function getMarketingCodeProductGroupByMarketingCode( TblReporting_MarketingCode $TblReporting_MarketingCode ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeProductGroupByMarketingCode( $TblReporting_MarketingCode );
    }

    /**
     * @param array $EntityMarketingCodeProductGroupList
     */
    public function getProductGroupByMarketingCodeProductGroup( $EntityMarketingCodeProductGroupList ) {
        return ( new Data( $this->getBinding() ) )->getProductGroupByMarketingCodeProductGroup( $EntityMarketingCodeProductGroupList );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_ProductLevel|Element
     */
    public function getProductLevelById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getProductLevelById( $Id );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_PartsMore|Element
     */
    public function getPartsMoreById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getPartsMoreById( $Id );
    }

    /**
     * @param TblReporting_MarketingCode $TblReporting_MarketingCode
     * @return null|TblReporting_MarketingCode_PartsMore|Element
     */
    public function getMarketingCodePartsMoreByMarketingCode( TblReporting_MarketingCode $TblReporting_MarketingCode ) {
        return ( new Data($this->getBinding()) )->getMarketingCodePartsMoreByMarketingCode( $TblReporting_MarketingCode );
    }

    /**
     * @param TblReporting_MarketingCode_PartsMore $TblReporting_MarketingCode_PartsMore
     * @return null|TblReporting_PartsMore|Element
     */
    public function getPartsMoreByMarketingCodePartsMore( TblReporting_MarketingCode_PartsMore $TblReporting_MarketingCode_PartsMore ) {
        return ( new Data($this->getBinding()) )->getPartsMoreByMarketingCodePartsMore( $TblReporting_MarketingCode_PartsMore );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_Part|Element
     */
    public function getPartById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getPartById( $Id );
    }

    /**
     * Stammdaten wie Bezeichnung, ET-Baumuster, Vorgänger, Nachfolger, Wahlweise...
     *
     * @param string $Number
     * @return null|TblReporting_Part|Element
     */
    public function getPartByNumber( $Number ) {
        return ( new Data( $this->getBinding() ) )->getPartByNumber( $Number );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_Section|Element
     */
    public function getSectionById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getSectionById( $Id );
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     */
    public function getPartSectionByPart( TblReporting_Part $TblReporting_Part  ) {
        return ( new Data( $this->getBinding() ) )->getPartSectionByPart( $TblReporting_Part );
    }

    /**
     * @param array $EntityPartSectionList
     * @return array $SectionList|null
     */
    public function getSectionListByPartSectionList( $EntityPartSectionList ) {
        return ( new Data( $this->getBinding() ) )->getSectionByPartSection( $EntityPartSectionList );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_AssortmentGroup|Element
     */
    public function getAssortmentGroupById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getAssortmentGroupById( $Id );
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @return null|TblReporting_Part_AssortmentGroup|Element
     */
    public function getPartAssortmentGroupByPart( TblReporting_Part $TblReporting_Part ) {
        return ( new Data( $this->getBinding() ) )->getPartAssortmentGroupByPart( $TblReporting_Part );
    }

    /**
     * @param TblReporting_Part_AssortmentGroup $TblReporting_Part_AssortmentGroup
     * @return null|TblReporting_AssortmentGroup|Element
     */
    public function getAssortmentGroupByPartAssortmentGroup( TblReporting_Part_AssortmentGroup $TblReporting_Part_AssortmentGroup ) {
        return ( new Data( $this->getBinding() ) )->getAssortmentGroupByPartAssortmentGroup( $TblReporting_Part_AssortmentGroup );
    }

    /**
     * @param $Id
     * @return null|TblReporting_Brand|Element
     */
    public function getBrandById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getBrandById( $Id );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_Price|Element
     */
    public function getPriceById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getPriceById( $Id );
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @return null|TblReporting_Price|Element
     */
    public function getPriceByPart( TblReporting_Part $TblReporting_Part ) {
        return ( new Data( $this->getBinding() ) )->getPriceByPart( $TblReporting_Part );
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @return null|TblReporting_Part_MarketingCode|Element
     */
    public function getPartMarketingCodeByPart( TblReporting_Part $TblReporting_Part ) {
        return ( new Data( $this->getBinding() ) )->getPartMarketingCodeByPart( $TblReporting_Part );
    }

    /**
     * @param TblReporting_Part_MarketingCode $TblReporting_Part_MarketingCode
     * @return null|TblReporting_MarketingCode|Element
     */
    public function getMarketingCodeByPartMarketingCode( TblReporting_Part_MarketingCode $TblReporting_Part_MarketingCode ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeByPartMarketingCode( $TblReporting_Part_MarketingCode );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_DiscountGroup|Element
     */
    public function getDiscountGroupById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getDiscountGroupById( $Id );
    }

    /**
     * @param int $Id
     * @return null|TblReporting_Supplier|Element
     */
    public function getSupplierById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getSupplierById( $Id );
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @return null|TblReporting_Part_Supplier[]
     */
    public function getPartSupplierByPart( TblReporting_Part $TblReporting_Part ) {
        return ( new Data( $this->getBinding() ) )->getPartSupplierByPart( $TblReporting_Part );
    }

    //ToDo: Bezeichnung evtl. anpassen

    /**
     * @param array $EntityPartSupplierList
     */
    public function getSupplierListByPartSupplierList( $EntityPartSupplierList ) {
        return ( new Data( $this->getBinding() ) )->getSupplierByPartSupplier( $EntityPartSupplierList );
    }

    /**
         * @param TblReporting_ProductManager $TblReporting_ProductManager
         * @return null|Element[]|TblReporting_ProductManager_ProductManagerGroup[]
         */
    public function getProductManagerGroupAllByProductManager( TblReporting_ProductManager $TblReporting_ProductManager ) {

        return ( new Data($this->getBinding()))->getProductManagerGroupAllByProductManager( $TblReporting_ProductManager );
    }

//    public function getPartPriceByPartNumber( $PartNumber ) {
//ToDo: für View
//    }

}