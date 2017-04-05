<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 29.03.2017
 * Time: 08:32
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service;


use SPHERE\Application\Reporting\DataWareHouse\Sales\Service\Entity\TblReporting_Sales;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\System\Database\Binding\AbstractData;

/**
 * Class Data
 * @package SPHERE\Application\Reporting\DataWareHouse\Service
 */
class Data extends AbstractData
{

    /**
     * @return void
     */
    public function setupDatabaseContent()
    {
        // TODO: Implement setupDatabaseContent() method.
    }

    /**
     * @param TblReporting_Part $TblReporting_Part
     * @return array|null|\SPHERE\System\Database\Fitting\Element[]
     */
    public function getSalesByPartNumber( TblReporting_Part $TblReporting_Part ) {
        return $this->getCachedEntityListBy( __METHOD__, $this->getEntityManager(), 'TblReporting_Sales', array(
            TblReporting_Sales::ATTR_Part_Id => $TblReporting_Part->getId()
        ) );
    }

    /**
     * @param $Year
     * @return \SPHERE\System\Database\Fitting\Element[]
     */
    public function getSalesByYear( $Year ) {
        return $this->getCachedEntityListBy( __METHOD__, $this->getEntityManager(), 'TblReporting_Sales', array(
            TblReporting_Sales::ATTR_Year => $Year
        ) );
    }
}