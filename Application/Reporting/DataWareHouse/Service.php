<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 29.03.2017
 * Time: 08:28
 */

namespace SPHERE\Application\Reporting\DataWareHouse;


use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Query\Expr;
use Nette\DateTime;
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
     * @return null|TblReporting_ProductManager[]|Element[]
     */
    public function getProductManagerAll() {
        return ( new Data( $this->getBinding() ) )->getProductManagerAll();
    }

    /**
     * @param int $Id
     * @return null|TblReporting_MarketingCode|Element
     */
    public function getMarketingCodeById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeById( $Id );
    }

    /**
     * @param string $Number
     * @return null|TblReporting_MarketingCode|Element
     */
    public function getMarketingCodeByNumber( $Number ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeByNumber( $Number );
    }

    /**
     * @return null|TblReporting_MarketingCode[]|Element[]
     */
    public function getMarketingCodeAll() {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeAll();
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
     * @param TblReporting_ProductManager $TblReporting_ProductManager
     * @return null|TblReporting_ProductManager_MarketingCode[]|Element[]
     */
    public function getProductManagerMarketingCodeByProductManager( TblReporting_ProductManager $TblReporting_ProductManager ) {
        return ( new Data( $this->getBinding() ) )->getProductManagerMarketingCodeByProductManager( $TblReporting_ProductManager );
    }

    /**
     * @param TblReporting_ProductManager $TblReporting_ProductManager
     * @param TblReporting_MarketingCode $TblReporting_MarketingCode
     * @return null|\SPHERE\System\Database\Binding\AbstractEntity[]|Element[]
     */
    public function getProductManagerMarketingCodeByProductManagerMarketingCode( TblReporting_ProductManager $TblReporting_ProductManager, TblReporting_MarketingCode $TblReporting_MarketingCode ) {
        return ( new Data( $this->getBinding() ) )->getProductManagerMarketingCodeByProductManagerMarketingCode( $TblReporting_ProductManager, $TblReporting_MarketingCode );
    }

    /**
     * @param array $EntityProductManagerMarketingCodeList
     * @return array $MarketingCodeList
     */
    public function getMarketingCodeByProductManagerMarketingCode( $EntityProductManagerMarketingCodeList ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeByProductManagerMarketingCode( $EntityProductManagerMarketingCodeList );
    }

    /**
     * @param TblReporting_MarketingCode $TblReporting_MarketingCode
     * @return null|Element[]|TblReporting_Part_MarketingCode[]
     */
   public function getPartMarketingCodeByMarketingCode( TblReporting_MarketingCode $TblReporting_MarketingCode ) {
       return ( new Data( $this->getBinding() ) )->getPartMarketingCodeByMarketingCode( $TblReporting_MarketingCode );
   }

   /**
    * @param TblReporting_MarketingCode $EntityMarketingCode
    * @return array $PartList
    */
   public function getPartByMarketingCode( $EntityMarketingCode ) {
       return ( new Data( $this->getBinding() ) )->getPartByMarketingCode( $EntityMarketingCode );
   }

    /**
     * @param int $Id
     * @return null|TblReporting_ProductGroup|Element
     */
    public function getProductGroupById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getProductGroupById( $Id );
    }

    /**
     * @param string $Number
     * @return null|TblReporting_ProductGroup|Element
     */
    public function getProductGroupByNumber( $Number ) {
        return ( new Data( $this->getBinding() ) )->getProductGroupByNumber( $Number );
    }

    /**
     * @return null|TblReporting_ProductGroup[]|Element[]
     */
    public function getProductGroupAll() {
        return ( new Data( $this->getBinding() ) )->getProductGroupAll();
    }

    /**
     * @param TblReporting_MarketingCode $TblReporting_MarketingCode
     * @return null|TblReporting_MarketingCode_ProductGroup[]|Element[]
     */
    public function getMarketingCodeProductGroupByMarketingCode( TblReporting_MarketingCode $TblReporting_MarketingCode ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeProductGroupByMarketingCode( $TblReporting_MarketingCode );
    }

    /**
     * @param TblReporting_ProductGroup $TblReporting_ProductGroup
     * @return null|TblReporting_MarketingCode_ProductGroup[]|Element[]
     */
    public function getMarketingCodeProductGroupByProductGroup( TblReporting_ProductGroup $TblReporting_ProductGroup ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeProductGroupByProductGroup( $TblReporting_ProductGroup );
    }

    /**
     * @param array $EntityMarketingCodeProductGroupList
     */
    public function getProductGroupByMarketingCodeProductGroup( $EntityMarketingCodeProductGroupList ) {
        return ( new Data( $this->getBinding() ) )->getProductGroupByMarketingCodeProductGroup( $EntityMarketingCodeProductGroupList );
    }

    /**
     * @param $EntityMarketingCodeProductGroupList
     * @return array
     */
    public function getMarketingCodeByMarketingCodeProductGroup( $EntityMarketingCodeProductGroupList ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeByMarketingCodeProductGroup( $EntityMarketingCodeProductGroupList );
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
     * @param $EntityPartMarketingCodeList
     * @return array|null
     */
    public function getPartByPartMarketingCode( $EntityPartMarketingCodeList ) {
        return ( new Data( $this->getBinding() ) )->getPartByPartMarketingCode( $EntityPartMarketingCodeList );
    }

    /**
     * @return null|TblReporting_Section[]|Element[]
     */
    public function getSectionAll() {
        return ( new Data( $this->getBinding() ) )->getSectionAll();
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
     * @param $Number
     * @return null|TblReporting_DiscountGroup|Element
     */
    public function getDiscountGroupByNumber( $Number ) {
        return ( new Data( $this->getBinding() ) )->getDiscountGroupByNumber( $Number );
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

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @return null|array
     */
    public function getSalesByPart( TblReporting_Part $TblReporting_Part ) {
        return ( new Data( $this->getBinding() ) )->getSalesByGroup( $TblReporting_Part, null, null );
    }

    /**
     * @param TblReporting_MarketingCode $TblReporting_MarketingCode
     * @return array|null
     */
    public function getSalesByMarketingCode( TblReporting_MarketingCode $TblReporting_MarketingCode ) {
        return ( new Data( $this->getBinding() ) )->getSalesByGroup( null, $TblReporting_MarketingCode, null );
    }

    /**
     * @param TblReporting_ProductManager $TblReporting_ProductManager
     * @return array|null
     */
    public function getSalesByProductManager( TblReporting_ProductManager $TblReporting_ProductManager ) {
        return ( new Data( $this->getBinding() ) )->getSalesByGroup( null, null, $TblReporting_ProductManager );
    }

    /**
     * @param null|string $PartNumber
     * @param null|string $MarketingCodeNumber
     * @param null|int $ProductManagerId
     * @param null|string $PeriodFrom
     * @param null|string $PeriodTo
     * @return array|null
     */
    public function getSalesGroupPart($PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null, $PeriodFrom = null, $PeriodTo = null ) {
        return ( new Data( $this->getBinding() ) ) ->getSearchByGroup('Part', $PartNumber, $MarketingCodeNumber, $ProductManagerId, $PeriodFrom, $PeriodTo );
    }

    /**
     * @param null|string $PartNumber
     * @param null|string $MarketingCodeNumber
     * @param null|int $ProductManagerId
     * @param null|string $PeriodFrom
     * @param null|string $PeriodTo
     * @return array|null
     */
    public function getSalesGroupMarketingCode($PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null, $PeriodFrom = null, $PeriodTo = null ) {
        return ( new Data( $this->getBinding() ) ) ->getSearchByGroup('MarketingCode', $PartNumber, $MarketingCodeNumber, $ProductManagerId, $PeriodFrom, $PeriodTo );
    }

    /**
     * @param null|string $PartNumber
     * @param null|string $MarketingCodeNumber
     * @param null|int $ProductManagerId
     * @param null|string $PeriodFrom
     * @param null|string $PeriodTo
     * @return array|null
     */
    public function getSalesGroupProductManager($PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null, $PeriodFrom = null, $PeriodTo = null ) {
        return ( new Data( $this->getBinding() ) ) ->getSearchByGroup('ProductManager', $PartNumber, $MarketingCodeNumber, $ProductManagerId, $PeriodFrom, $PeriodTo );
    }

    /**
     * @param null|string $PartNumber
     * @param null|string $MarketingCodeNumber
     * @param null|int $ProductManagerId
     * @param null|string $PeriodFrom
     * @param null|string $PeriodTo
     * @return array|null
     */
    public function getSalesGroupCompetition($PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null, $PeriodFrom = null, $PeriodTo = null ) {
        return ( new Data( $this->getBinding() ) ) ->getSearchByGroup('Competition', $PartNumber, $MarketingCodeNumber, $ProductManagerId, $PeriodFrom, $PeriodTo );
    }

    /**
     * @return null|int
     */
    public function getYearCurrentFromSales() {
        return ( new Data( $this->getBinding() ) ) ->getYearCurrentFromSales();
    }

    /**
     * @return null|int
     */
    public function getMaxMonthCurrentYearFromSales() {
        return ( new Data( $this->getBinding() ) ) ->getMaxMonthCurrentYearFromSales();
    }

    /**
     * @param string $PartNumber
     * @param string $MarketingCodeNumber
     * @param integer $ProductManagerId
     * @return null
     */
    public function getExtrapolationFactor( $PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null ) {
        return ( new Data( $this->getBinding() ) ) ->getExtrapolationFactor( $PartNumber, $MarketingCodeNumber, $ProductManagerId );
    }

    /**
     * @param null|string $PartNumber
     * @param null|string $MarketingCodeNumber
     * @param null|int $ProductManagerId
     * @param null|string $ProductGroupNumber
     * @return array|null
     */
    public function getMonthlyTurnover( $PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null, $ProductGroupNumber = null ) {
        return ( new Data( $this->getBinding() ) ) ->getMonthlyTurnoverByGroup( $PartNumber, $MarketingCodeNumber, $ProductManagerId, $ProductGroupNumber );
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @param int $Restriction
     * @return null|array
     *
     * $Restriction z.B. für Top 50 bzw. geplant Filter über Zeitraum
     */
    public function getPriceDevelopmentByPartNumber( TblReporting_Part $TblReporting_Part, $Restriction ) {
        $PriceDevelopmentData = ( new Data( $this->getBinding() ) ) ->getPriceDevelopmentByPartNumber( $TblReporting_Part, $Restriction );

        if($PriceDevelopmentData) {
            $CalcPriceDevelopmentData = null;
            array_walk($PriceDevelopmentData, function($Row) use(&$CalcPriceDevelopmentData) {

                $PriceNet = $this->getCalculationRules()->calcNetPrice($Row['PriceGross'], $Row['Discount'], $Row['BackValue']);

                /** @var DateTime $DateValidFrom */
                $DateValidFrom = $Row['ValidFrom'];


                $CalcPriceDevelopmentData[] = array (
                    'ValidFrom' => $DateValidFrom->format('d.m.Y'),
                    'Data_PriceGross' => $Row['PriceGross'],
                    'Data_PriceNet' => $PriceNet,
                    'DiscountGroupNumber' => $Row['DiscountGroupNumber'],
                    'Discount' => $Row['Discount'],
                    'Data_BackValue' => $Row['BackValue'],
                    'Data_CostsVariable' => $Row['CostsVariable'],
//                    'Data_CoverageContribution' => $this->getCalculationRules()->calcCoverageContribution($PriceNet, $Row['CostsVariable']),
                );
            });
            return $CalcPriceDevelopmentData;
        }
        else {
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function getProductManagerMarketingCodeCurrent() {
        return ( new Data( $this->getBinding() ) ) ->getProductManagerMarketingCodeCurrent();
    }

    //Update bzw. Delete

    /**
     * @param int $ProductManagerId
     * @return null|Service\Entity\TblReporting_ProductManager
     */
    public function deleteProductManager( $ProductManagerId ) {
        return ( new Data( $this->getBinding() ) ) ->deleteProductManager( $ProductManagerId );
    }

    /**
     * @param string $Name
     * @param string $SectionNumber
     * @param string $Department
     */
    public function createProductManager( $Name, $SectionNumber, $Department ) {
        return ( new Data( $this->getBinding() ) ) ->createProductManager($Name, $SectionNumber, $Department);
    }

    /**
     * @param $Number
     * @param $Name
     * @return null|TblReporting_MarketingCode
     */
    public function createMarketingCode( $Number, $Name) {
        return ( new Data( $this->getBinding() ) ) ->createMarketingCode( $Number, $Name);
    }

    /**
     * @param TblReporting_ProductManager $EntityProductManager
     * @param TblReporting_MarketingCode $EntityMarketingCode
     * @return null|TblReporting_MarketingCode
     */
    public function createProductManagerMarketingcode(TblReporting_ProductManager $EntityProductManager, TblReporting_MarketingCode $EntityMarketingCode ) {
        return ( new Data( $this->getBinding() ) ) ->createProductManagerMarketingcode($EntityProductManager, $EntityMarketingCode );
    }

    /**
     * @param $Number
     * @param $Name
     * @return null|TblReporting_MarketingCode
     */
    public function updateMarketingCode( $Number, $Name ) {
        return ( new Data( $this->getBinding() ) ) ->updateMarketingCode( $Number, $Name );
    }

    /**
     * @param $ProductManagerMarketingCodeId
     * @return null|TblReporting_ProductManager_MarketingCode
     */
    public function deleteProductManagerMarketingCode( $ProductManagerMarketingCodeId ) {
        return (new Data($this->getBinding()))->deleteProductManagerMarketingCode( $ProductManagerMarketingCodeId );
    }
}