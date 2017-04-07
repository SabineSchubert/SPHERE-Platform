<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 05.04.2017
 * Time: 09:07
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
 * @Table(name="TblReporting_MarketingCode_PartsMore")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_MarketingCode_PartsMore extends Element
{
    const TBL_REPORTING_MARKETING_CODE = 'TblReporting_MarketingCode';
    const TBL_REPORTING_PARTS_MORE = 'TblReporting_PartsMore';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_MarketingCode;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_PartsMore;

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
        $this->TblReporting_MarketingCode = ( $TblReporting_MarketingCode ? $TblReporting_MarketingCode->getId() : null );
    }

    /**
     * @return null|TblReporting_PartsMore
     */
    public function getTblReportingPartsMore()
    {
        return ( $this->TblReporting_PartsMore ? DataWareHouse::useService()->getPartsMoreById( $this->TblReporting_PartsMore ) : null ) ;
    }

    /**
     * @param null|TblReporting_PartsMore $TblReporting_PartsMore
     */
    public function setTblReportingPartsMore(TblReporting_PartsMore $TblReporting_PartsMore)
    {
        $this->TblReporting_PartsMore = ( $TblReporting_PartsMore ? $TblReporting_PartsMore : null );
    }


}