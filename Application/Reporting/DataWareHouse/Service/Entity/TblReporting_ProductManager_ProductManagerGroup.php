<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 04.04.2017
 * Time: 10:36
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Cache;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_ProductManager_ProductManagerGroup")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_ProductManager_ProductManagerGroup extends Element
{
    /**
     * @Column(type="bigint")
     */
    protected $TblReportingProductManager;

    /**
     * @Column(type="bigint")
     */
    protected $TblReportingProductManagerGroup;

    /**
     * @return mixed
     */
    public function getTblReportingProductManager()
    {
        return $this->TblReportingProductManager;
    }

    /**
     * @param mixed $TblReportingProductManager
     */
    public function setTblReportingProductManager($TblReportingProductManager)
    {
        $this->TblReportingProductManager = $TblReportingProductManager;
    }

    /**
     * @return mixed
     */
    public function getTblReportingProductManagerGroup()
    {
        return $this->TblReportingProductManagerGroup;
    }

    /**
     * @param mixed $TblReportingProductManagerGroup
     */
    public function setTblReportingProductManagerGroup($TblReportingProductManagerGroup)
    {
        $this->TblReportingProductManagerGroup = $TblReportingProductManagerGroup;
    }
}