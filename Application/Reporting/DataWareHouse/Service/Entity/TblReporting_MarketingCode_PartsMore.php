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
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_MarketingCode_PartsMore")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_MarketingCode_PartsMore extends Element
{
    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_MarketingCode;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_PartsMore;

    /**
     * @return mixed
     */
    public function getTblReportingMarketingCode()
    {
        return $this->TblReporting_MarketingCode;
    }

    /**
     * @param mixed $TblReporting_MarketingCode
     */
    public function setTblReportingMarketingCode($TblReporting_MarketingCode)
    {
        $this->TblReporting_MarketingCode = $TblReporting_MarketingCode;
    }

    /**
     * @return mixed
     */
    public function getTblReportingPartsMore()
    {
        return $this->TblReporting_PartsMore;
    }

    /**
     * @param mixed $TblReporting_PartsMore
     */
    public function setTblReportingPartsMore($TblReporting_PartsMore)
    {
        $this->TblReporting_PartsMore = $TblReporting_PartsMore;
    }


}