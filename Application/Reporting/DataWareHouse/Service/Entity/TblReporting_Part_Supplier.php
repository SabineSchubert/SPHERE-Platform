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
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Part_Supplier")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part_Supplier extends Element
{
    const TBL_REPORTING_PART = 'TblReporting_Part';
    const TBL_REPORTING_SUPPLIER = 'TblReporting_Supplier';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Part;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_Supplier;

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
        $this->TblReporting_Part = ( $TblReporting_Part ? $TblReporting_Part : null );
    }

    /**
     * @return null|TblReporting_Supplier
     */
    public function getTblReportingSupplier()
    {
        return ( $this->TblReporting_Supplier ? DataWareHouse::useService()->getSupplierById( $this->TblReporting_Supplier ) : null );
    }

    /**
     * @param null|TblReporting_Supplier $TblReporting_Supplier
     */
    public function setTblReportingSupplier(TblReporting_Supplier $TblReporting_Supplier)
    {
        $this->TblReporting_Supplier = ( $TblReporting_Supplier ? $TblReporting_Supplier->getId() : null );
    }


}