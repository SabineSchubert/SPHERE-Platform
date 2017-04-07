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
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_PartsMore;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Price;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductLevel;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManagerGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Section;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Supplier;
use SPHERE\Application\Reporting\DataWareHouse\Service\Setup;
use SPHERE\System\Database\Binding\AbstractService;
use SPHERE\System\Database\Fitting\Element;

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
     * @param $Id
     * @return null|TblReporting_ProductManager|Element
     */
    public function getProductManagerById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getProductManagerById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_MarketingCode|Element
     */
    public function getMarketingCodeById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getMarketingCodeById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_ProductGroup|Element
     */
    public function getProductGroupById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getProductGroupById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_ProductLevel|Element
     */
    public function getProductLevelById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getProductLevelById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_PartsMore|Element
     */
    public function getPartsMoreById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getPartsMoreById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_Part|Element
     */
    public function getPartById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getPartById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_Section|Element
     */
    public function getSectionById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getSectionById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_AssortmentGroup|Element
     */
    public function getAssortmentGroupById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getAssortmentGroupById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_Brand|Element
     */
    public function getBrandById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getBrandById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_Price|Element
     */
    public function getPriceById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getPriceById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_DiscountGroup|Element
     */
    public function getDiscountGroupById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getDiscountGroupById( $Id );
    }

    /**
     * @param $Id
     * @return null|TblReporting_Supplier|Element
     */
    public function getSupplierById( $Id ) {
        return ( new Data( $this->getBinding() ) )->getSupplierById( $Id );
    }

}