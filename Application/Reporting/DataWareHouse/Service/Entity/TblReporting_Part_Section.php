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
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Part_Section")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part_Section extends Element
{
    const TBL_REPORTING_PART = 'TblReporting_Part';
    const TBL_REPORTING_SECTION = 'TblReporting_Section';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Section;

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
     * @return null|TblReporting_Section
     */
    public function getTblReportingSection()
    {
        return ( $this->TblReporting_Section ? DataWareHouse::useService()->getSectionById( $this->TblReporting_Section ) : null );
    }

    /**
     * @param null|TblReporting_Section $TblReporting_Section
     */
    public function setTblReportingSection(TblReporting_Section $TblReporting_Section)
    {
        $this->TblReporting_Section = ( $TblReporting_Section ? $TblReporting_Section->getId() : null );
    }
}