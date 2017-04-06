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
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_MarketingCode_ProductGroup")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_MarketingCode_ProductGroup extends Element
{

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_MarketingCode;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductGroup;

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
    public function getTblReportingProductGroup()
    {
        return $this->TblReporting_ProductGroup;
    }

    /**
     * @param mixed $TblReporting_ProductGroup
     */
    public function setTblReportingProductGroup($TblReporting_ProductGroup)
    {
        $this->TblReporting_ProductGroup = $TblReporting_ProductGroup;
    }

}