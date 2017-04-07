<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 15:15
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
 * @Table(name="TblReporting_Part_MarketingCode")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part_MarketingCode extends Element
{
    const TBL_REPORTING_PART = 'TblReporting_Part';
    const TBL_REPORTING_MARKETING_CODE = 'TblReporting_MarketingCode';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_MarketingCode;

    /**
     * @return null|TblReporting_Part
     */
    public function getTblReportingPart()
    {
        return ( $this->TblReporting_Part ? DataWareHouse::useService()->getPartById( $this->TblReporting_Part ) : null );
    }

    /**
     * @param null|TblReporting_Part $TblReporting_Part
     */
    public function setTblReportingPart(TblReporting_Part $TblReporting_Part)
    {
        $this->TblReporting_Part = ( $TblReporting_Part ? $TblReporting_Part->getId() : null );
    }

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

}