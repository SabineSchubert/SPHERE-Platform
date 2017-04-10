<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 29.03.2017
 * Time: 08:32
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service;


use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_AssortmentGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Brand;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_DiscountGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_PartsMore;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Price;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductLevel;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_ProductManagerGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManagerGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Sales;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Section;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Supplier;
use SPHERE\System\Database\Binding\AbstractData;
use SPHERE\System\Database\Fitting\Element;

/**
 * Class Data
 * @package SPHERE\Application\Reporting\DataWareHouse\Service
 */
class Data extends AbstractData
{

    /**
     * @return void
     */
    public function setupDatabaseContent()
    {
        // TODO: Implement setupDatabaseContent() method.
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @return array|null|Element[]
     */
    public function getSalesAll( TblReporting_Part $TblReporting_Part = null ) {
        $TableSales = new TblReporting_Sales();
        if( !$TblReporting_Part ) {
            return $this->getCachedEntityList( __METHOD__, $this->getEntityManager(), $TableSales->getEntityShortName() );
        }
        return $this->getCachedEntityListBy( __METHOD__, $this->getEntityManager(), $TableSales->getEntityShortName(), array(
            TblReporting_Sales::TBL_REPORTING_PART => $TblReporting_Part->getId()
        ) );
    }

    /**
     * @param int $Year
     * @return null|Element[]|TblReporting_Sales[]
     */
    public function getSalesAllByYear( $Year ) {
        $TableSales = new TblReporting_Sales();
        return $this->getCachedEntityListBy( __METHOD__, $this->getEntityManager(), $TableSales->getEntityShortName(), array(
            TblReporting_Sales::ATTR_YEAR => (int)$Year
        ) );
    }






    /**
     * @param int $Id
     * @return null|Element|TblReporting_ProductManagerGroup
     */
    public function getProductManagerGroupById( $Id ) {
        $TableProductManagerGroup = new TblReporting_ProductManagerGroup();
        return $this->getCachedEntityById(  __METHOD__, $this->getEntityManager(), $TableProductManagerGroup->getEntityShortName(), (int)$Id );
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_ProductManager
     */
    public function getProductManagerById( $Id ) {
        $TableProductManager = new TblReporting_ProductManager();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableProductManager->getEntityShortName(), (int)$Id );
    }

    /**
     * @param int $Id
     * @return null|Element[]|TblReporting_ProductManager[]
     */
    public function getProductManagerAllByProductManagerGroup( TblReporting_ProductManagerGroup $Id = null ) {
        $TableProductManagerGroup = new TblReporting_ProductManagerGroup();
        return $this->getCachedEntityListBy( __METHOD__, $this->getEntityManager(), $TableProductManagerGroup->getEntityShortName(),
            array(
                $TableProductManagerGroup::ENTITY_ID => (int)$Id
            )
        );
    }

    /**
     * @param TblReporting_ProductManager $TblReporting_ProductManager
     * @return null|Element[]|TblReporting_ProductManagerGroup[]
     */
    public function getProductManagerGroupAllByProductManager( TblReporting_ProductManager $TblReporting_ProductManager = null ) {
        $TableProductManagerProductManagerGroup = new TblReporting_ProductManager_ProductManagerGroup();
        $EntityList = $this->getCachedEntityListBy( __METHOD__, $this->getEntityManager(), $TableProductManagerProductManagerGroup->getEntityShortName(),
            array(
                $TableProductManagerProductManagerGroup::TBL_REPORTING_PRODUCT_MANAGER => $TblReporting_ProductManager->getId()
            )
        );
        if( $EntityList ) {
            /**
             * @var TblReporting_ProductManager_ProductManagerGroup $TblReporting_ProductManager_ProductManagerGroup
             */
            $EntityProductManagerGroup = null;
            foreach((array)$EntityList AS $TblReporting_ProductManager_ProductManagerGroup ) {
                $EntityProductManagerGroup[] = $TblReporting_ProductManager_ProductManagerGroup->getTblReportingProductManagerGroup();
            }
            return $EntityProductManagerGroup;
        }
        else {
            return null;
        }
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_MarketingCode
     */
    public function getMarketingCodeById( $Id ) {
        $TableMarktingCode = new TblReporting_MarketingCode();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableMarktingCode->getEntityShortName(), (int)$Id );
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_MarketingCode
     */
    public function getMarketingCodeByPartMarketingCode( TblReporting_Part_MarketingCode $TblReporting_Part_MarketingCode ) {
        if( $TblReporting_Part_MarketingCode->getTblReportingMarketingCode() ) {
            return $this->getMarketingCodeById( $TblReporting_Part_MarketingCode->getTblReportingMarketingCode()->getId() );
        }
        else {
            return null;
        }
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_ProductGroup
     */
    public function getProductGroupById( $Id ) {
        $TableProductGroup = new TblReporting_ProductGroup();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableProductGroup->getEntityShortName(), (int)$Id );
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_ProductLevel
     */
    public function getProductLevelById( $Id ) {
        $TableProductLevel = new TblReporting_ProductLevel();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableProductLevel->getEntityShortName(), (int)$Id );
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_PartsMore
     */
    public function getPartsMoreById( $Id ) {
        $TablePartsMore = new TblReporting_PartsMore();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TablePartsMore->getEntityShortName(), (int)$Id );
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_Part
     */
    public function getPartById( $Id ) {
        $TablePart = new TblReporting_Part();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TablePart->getEntityShortName(), (int)$Id );
    }

    /**
     * @param string $Number
     * @return null|Element|TblReporting_Part
     */
    public function getPartByNumber( $Number ) {
        $TablePart = new TblReporting_Part();
        return $this->getCachedEntityBy( __METHOD__, $this->getEntityManager(), $TablePart->getEntityShortName(), array(
            $TablePart::ATTR_NUMBER => $Number
        ) );
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_Section
     */
    public function getSectionById( $Id ) {
        $TableSection = new TblReporting_Section();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableSection->getEntityShortName(), (int)$Id );
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_Brand
     */
    public function getBrandById( $Id ) {
        $TableBrand = new TblReporting_Brand();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableBrand->getEntityShortName(), (int)$Id );
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_AssortmentGroup
     */
    public function getAssortmentGroupById( $Id ) {
        $TableAssortmentGroup = new TblReporting_AssortmentGroup();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableAssortmentGroup->getEntityShortName(), (int)$Id );
    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_Price
     */
    public function getPriceById( $Id ) {
        $TablePrice = new TblReporting_Price();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TablePrice->getEntityShortName(), (int)$Id );
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @return null|Element|TblReporting_Price
     */
    public function getPriceByPart( TblReporting_Part $TblReporting_Part ) {
        $TablePrice = new TblReporting_Price();
        $EntityPriceList = $this->getCachedEntityListBy( __METHOD__, $this->getEntityManager(), $TablePrice->getEntityShortName(), array(
            $TablePrice::TBL_REPORTING_PART => $TblReporting_Part->getId()
        ), array( $TablePrice::ENTITY_CREATE => self::ORDER_DESC ) );
        if($EntityPriceList) {
            return $EntityPriceList[0];
        }
        else {
            return null;
        }
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @return null|Element|TblReporting_Part_MarketingCode
     */
    public function getPartMarketingCodeByPart( TblReporting_Part $TblReporting_Part ) {
        $TablePartMarketingCode = new TblReporting_Part_MarketingCode();
        $EntityPartMarketingCode = $this->getCachedEntityListBy( __METHOD__, $this->getEntityManager(), $TablePartMarketingCode->getEntityShortName(), array(
            $TablePartMarketingCode::TBL_REPORTING_PART => $TblReporting_Part->getId()
        ), array( $TablePartMarketingCode::ENTITY_CREATE => self::ORDER_DESC ) );
        if($EntityPartMarketingCode) {
            return $EntityPartMarketingCode[0];
        }
        else {
            return null;
        }
    }

    /**
     * @param int|TblReporting_Price $Id
     * @return null|Element|TblReporting_DiscountGroup
     */
    public function getDiscountGroupById( $Id ) {
        $TableDiscountGroup = new TblReporting_DiscountGroup();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableDiscountGroup->getEntityShortName(), (int)$Id );
    }

//    /**
//     * @param int $Id
//     * @return null|Element|TblReporting_DiscountGroup
//     */
//    public function getDiscountGroupById(  ) {
//        $TableDiscountGroup = new TblReporting_DiscountGroup();
//        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableDiscountGroup->getEntityShortName(), (int)$Id );
//    }

    /**
     * @param int $Id
     * @return null|Element|TblReporting_Supplier
     */
    public function getSupplierById( $Id ) {
        $TableSupplier = new TblReporting_Supplier();
        return $this->getCachedEntityById( __METHOD__, $this->getEntityManager(), $TableSupplier->getEntityShortName(), (int)$Id );
    }


}