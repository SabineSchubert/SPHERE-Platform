<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 04.04.2017
 * Time: 10:08
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
 * @Table(name="TblReporting_Part_Section")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part_Section extends Element
{
    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Section;

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
    public function getTblReportingSection()
    {
        return $this->TblReporting_Section;
    }

    /**
     * @param mixed $TblReporting_Section
     */
    public function setTblReportingSection($TblReporting_Section)
    {
        $this->TblReporting_Section = $TblReporting_Section;
    }
}