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
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_ProductManager_MarketingCode")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_ProductManager_MarketingCode extends Element
{
    const Reporting_PRODUCT_MANAGER = 'TblReporting_ProductManager';
    const Reporting_MARKETING_CODE = 'TblReporting_MarketingCode';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductManager;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_MarketingCode;

    /**
     * @return mixed
     */
    public function getTblReportingProductManager()
    {
        return $this->TblReporting_ProductManager;
    }

    /**
     * @param mixed $TblReporting_ProductManager
     */
    public function setTblReportingProductManager($TblReporting_ProductManager)
    {
        $this->TblReporting_ProductManager = $TblReporting_ProductManager;
    }

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


}