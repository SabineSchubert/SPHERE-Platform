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
 * @Table(name="TblReporting_Part_Brand")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part_Brand extends Element
{
    const REPORTING_PART = 'TblReporting_Part';
    const REPORTING_BRAND = 'TblReporting_Brand';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Brand;

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

}