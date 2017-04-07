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
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_ProductManager_ProductManagerGroup")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_ProductManager_ProductManagerGroup extends Element
{
    const TBL_REPORTING_PRODUCT_MANAGER = 'TblReporting_ProductManager';
    const TBL_REPORTING_PRODUCT_MANAGER_PRODUCT_MANAGER_GROUP = 'TblReporting_ProductManager_ProductManagerGroup';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductManager;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductManagerGroup;

    /**
     * @return null|TblReporting_ProductManager|Element
     */
    public function getTblReportingProductManager()
    {
        $Service = DataWareHouse::useService();
        return ( $this->TblReporting_ProductManager ? $Service->getProductManagerById( $this->TblReporting_ProductManager ) : null);
    }

    /**
     * @param mixed $TblReportingProductManager
     */
    public function setTblReportingProductManager(TblReporting_ProductManager $TblReporting_ProductManager = null)
    {
        $this->TblReporting_ProductManager = ( $TblReporting_ProductManager ? $TblReporting_ProductManager->getId() : $TblReporting_ProductManager );
    }

    /**
     * @return mixed
     */
    public function getTblReportingProductManagerGroup()
    {
        return ( $this->TblReporting_ProductManagerGroup ? DataWareHouse::useService()->getProductManagerGroupById( $this->TblReporting_ProductManagerGroup ) : null );
    }

    /**
     * @param mixed $TblReportingProductManagerGroup
     */
    public function setTblReportingProductManagerGroup( TblReporting_ProductManagerGroup $TblReporting_ProductManagerGroup)
    {
        $this->TblReporting_ProductManagerGroup = ( $TblReporting_ProductManagerGroup ? $TblReporting_ProductManagerGroup->getId() : null );
    }
}