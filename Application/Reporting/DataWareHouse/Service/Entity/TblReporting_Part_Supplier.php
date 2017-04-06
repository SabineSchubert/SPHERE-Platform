<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 05.04.2017
 * Time: 11:42
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
 * @Table(name="TblReporting_Part_Supplier")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part_Supplier extends Element
{
    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Supplier;

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
    public function getTblReportingSupplier()
    {
        return $this->TblReporting_Supplier;
    }

    /**
     * @param mixed $TblReporting_Supplier
     */
    public function setTblReportingSupplier($TblReporting_Supplier)
    {
        $this->TblReporting_Supplier = $TblReporting_Supplier;
    }


}