<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 15:11
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
 * @Table(name="TblReporting_ProductManager_Brand")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_ProductManager_Brand extends Element
{
    const REPORTING_BRAND = 'TblReporting_Brand';
    const REPORTING_PRODUCT_MANAGER = 'TblReporting_ProductManager';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Brand;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductManager;

    /**
     * @return mixed
     */
    public function getTblReportingBrand()
    {
        return $this->TblReporting_Brand;
    }

    /**
     * @param mixed $TblReporting_Brand
     */
    public function setTblReportingBrand($TblReporting_Brand)
    {
        $this->TblReporting_Brand = $TblReporting_Brand;
    }

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


}