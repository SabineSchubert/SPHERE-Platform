<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 05.04.2017
 * Time: 11:53
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
 * @Table(name="TblReporting_ProductGroup_ProductLevel")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_ProductGroup_ProductLevel extends Element
{
    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductGroup;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductLevel;

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

    /**
     * @return mixed
     */
    public function getTblReportingProductLevel()
    {
        return $this->TblReporting_ProductLevel;
    }

    /**
     * @param mixed $TblReporting_ProductLevel
     */
    public function setTblReportingProductLevel($TblReporting_ProductLevel)
    {
        $this->TblReporting_ProductLevel = $TblReporting_ProductLevel;
    }

}