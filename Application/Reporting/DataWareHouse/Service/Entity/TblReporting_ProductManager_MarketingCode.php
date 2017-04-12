<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 15:13
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Entity;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_ProductManager_MarketingCode")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_ProductManager_MarketingCode extends Element
{
    const TBL_REPORTING_PRODUCT_MANAGER = 'TblReporting_ProductManager';
    const TBL_REPORTING_MARKETING_CODE = 'TblReporting_MarketingCode';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductManager;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_MarketingCode;

    /**
     * @return null|TblReporting_ProductManager
     */
    public function getTblReportingProductManager()
    {
        return ( $this->TblReporting_ProductManager ? DataWareHouse::useService()->getProductManagerById( $this->TblReporting_ProductManager ) : null );
    }

    /**
     * @param null| TblReporting_ProductManager $TblReporting_ProductManager
     */
    public function setTblReportingProductManager(TblReporting_ProductManager $TblReporting_ProductManager)
    {
        $this->TblReporting_ProductManager = ( $TblReporting_ProductManager ? $TblReporting_ProductManager->getId() : null );
    }

    /**
     * @return null|TblReporting_MarketingCode
     */
    public function getTblReportingMarketingCode()
    {
        return ( $this->TblReporting_MarketingCode ? DataWareHouse::useService()->getMarketingCodeById( $this->TblReporting_MarketingCode ) : null );
    }

    /**
     * @param TblReporting_MarketingCode $TblReporting_MarketingCode
     */
    public function setTblReportingMarketingCode(TblReporting_MarketingCode $TblReporting_MarketingCode)
    {
        $this->TblReporting_MarketingCode = ( $TblReporting_MarketingCode ? $TblReporting_MarketingCode->getId() : null );
    }


}