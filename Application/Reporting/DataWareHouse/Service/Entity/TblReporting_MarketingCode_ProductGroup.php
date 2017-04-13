<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 04.04.2017
 * Time: 10:03
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
 * @Table(name="TblReporting_MarketingCode_ProductGroup")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_MarketingCode_ProductGroup extends Element
{
    const TBL_REPORTING_MARKETING_CODE = 'TblReporting_MarketingCode';
    const TBL_REPORTING_PRODUCT_GROUP = 'TblReporting_ProductGroup';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_MarketingCode;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductGroup;

    /**
     * @return null|TblReporting_MarketingCode
     */
    public function getTblReportingMarketingCode()
    {
        return ( $this->TblReporting_MarketingCode ? DataWareHouse::useService()->getMarketingCodeById( $this->TblReporting_MarketingCode ) : null );
    }

    /**
     * @param null|TblReporting_MarketingCode $TblReporting_MarketingCode
     */
    public function setTblReportingMarketingCode(TblReporting_MarketingCode $TblReporting_MarketingCode)
    {
        $this->TblReporting_MarketingCode = ( $TblReporting_MarketingCode ? $TblReporting_MarketingCode : null );
    }

    /**
     * @return null|TblReporting_ProductGroup
     */
    public function getTblReportingProductGroup()
    {
        return ( $this->TblReporting_ProductGroup ? DataWareHouse::useService()->getProductGroupById( $this->TblReporting_ProductGroup ) : null );
    }

    /**
     * @param null|TblReporting_ProductGroup $TblReporting_ProductGroup
     */
    public function setTblReportingProductGroup(TblReporting_ProductGroup $TblReporting_ProductGroup)
    {
        $this->TblReporting_ProductGroup = ( $TblReporting_ProductGroup ? $TblReporting_ProductGroup : null );
    }

}