<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 04.04.2017
 * Time: 11:48
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Cache;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Part_AssortmentGroup")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part_AssortmentGroup extends Element
{
    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_AssortmentGroup;

    /**
     * @return mixed
     */
    public function getTblReportingPart()
    {
        return $this->TblReporting_Part;
    }

    /**
     * @param mixed $TblReporting_Part
     */
    public function setTblReportingPart($TblReporting_Part)
    {
        $this->TblReporting_Part = $TblReporting_Part;
    }

    /**
     * @return mixed
     */
    public function getTblReportingAssortmentGroup()
    {
        return $this->TblReporting_AssortmentGroup;
    }

    /**
     * @param mixed $TblReporting_AssortmentGroup
     */
    public function setTblReportingAssortmentGroup($TblReporting_AssortmentGroup)
    {
        $this->TblReporting_AssortmentGroup = $TblReporting_AssortmentGroup;
    }
}