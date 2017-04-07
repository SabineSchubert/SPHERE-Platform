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
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Part_AssortmentGroup")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part_AssortmentGroup extends Element
{
    const TBL_REPORTING_PART = 'TblReporting_Part';
    const TBL_REPORTING_ASSORTMENT_GROUP = 'TblReporting_AssortmentGroup';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_AssortmentGroup;

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
        $this->TblReporting_Part = ($TblReporting_Part ? $TblReporting_Part->getId() : null );
    }

    /**
     * @return null|TblReporting_AssortmentGroup
     */
    public function getTblReportingAssortmentGroup()
    {
        return ( $this->TblReporting_AssortmentGroup ? DataWareHouse::useService()->getPartById( $this->TblReporting_AssortmentGroup ) : null );
    }

    /**
     * @param null|TblReporting_AssortmentGroup $TblReporting_AssortmentGroup
     */
    public function setTblReportingAssortmentGroup(TblReporting_AssortmentGroup $TblReporting_AssortmentGroup)
    {
        $this->TblReporting_AssortmentGroup = ( $TblReporting_AssortmentGroup ? $TblReporting_AssortmentGroup->getId() : null );
    }
}