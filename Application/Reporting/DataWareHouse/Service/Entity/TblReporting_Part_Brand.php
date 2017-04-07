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
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Part_Brand")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part_Brand extends Element
{
    const TBL_REPORTING_PART = 'TblReporting_Part';
    const TBL_REPORTING_BRAND = 'TblReporting_Brand';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Brand;

    /**
     * @return null|TblReporting_Part
     */
    public function getTblReportingPart()
    {
        return ( $this->TblReporting_Part ? DataWareHouse::useService()->getPartById( $this->TblReporting_Part ) : null );
    }

    /**
     * @param null|TblReporting_Part $TblReporting_Part
     */
    public function setTblReportingPart(TblReporting_Part $TblReporting_Part)
    {
        $this->TblReporting_Part = ( $TblReporting_Part ? $TblReporting_Part->getId() : null );
    }

    /**
     * @return null|TblReporting_Brand
     */
    public function getTblReportingBrand()
    {
        return ( $this->TblReporting_Brand ? DataWareHouse::useService()->getBrandById( $this->TblReporting_Brand ) : null );
    }

    /**
     * @param null|TblReporting_Brand $TblReporting_Brand
     */
    public function setTblReportingBrand(TblReporting_Brand $TblReporting_Brand)
    {
        $this->TblReporting_Brand = ( $TblReporting_Brand ? $TblReporting_Brand->getId() : null );
    }

}