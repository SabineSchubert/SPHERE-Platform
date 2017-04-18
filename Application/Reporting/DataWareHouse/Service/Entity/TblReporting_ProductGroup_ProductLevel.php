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
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_ProductGroup_ProductLevel")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_ProductGroup_ProductLevel extends Element
{
    const TBL_REPORTING_PRODUCT_GROUP = 'TblReporting_ProductGroup';
    const TBL_REPORTING_PRODUCT_LEVEL = 'TblReporting_ProductLevel';

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductGroup;

    /**
     * @Column(type="bigint")
     */
    protected $TblReporting_ProductLevel;

    /**
     * @return null|TblReporting_ProductGroup
     */
    public function getTblReportingProductGroup()
    {
        return ( $this->TblReporting_ProductGroup ? DataWareHouse::useService()->getProductGroupById( $this->TblReporting_ProductGroup ) : null );
    }

    /**
     * @param null|TblReporting_ProductGroup $TblReporting_ProductGroup
     */
    public function setTblReportingProductGroup($TblReporting_ProductGroup)
    {
        $this->TblReporting_ProductGroup = ( $TblReporting_ProductGroup ? $TblReporting_ProductGroup->getId() : null );
    }

    /**
     * @return null|TblReporting_ProductLevel
     */
    public function getTblReportingProductLevel()
    {
        return ( $this->TblReporting_ProductLevel ? DataWareHouse::useService()->getProductLevelById( $this->TblReporting_ProductLevel ) : null );
    }

    /**
     * @param null|TblReporting_ProductLevel $TblReporting_ProductLevel
     */
    public function setTblReportingProductLevel($TblReporting_ProductLevel)
    {
        $this->TblReporting_ProductLevel = ( $TblReporting_ProductLevel ? $TblReporting_ProductLevel->getId() : null );
    }

}