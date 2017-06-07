<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 22.05.2017
 * Time: 11:07
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Sales;


use SPHERE\Application\Reporting\DataWareHouse\Sales\Service\Data;
use SPHERE\Application\Reporting\DataWareHouse\Sales\Service\Entity\TblImport_Sales;
use SPHERE\Application\Reporting\DataWareHouse\Sales\Service\Setup;
use SPHERE\System\Database\Binding\AbstractService;

class Service extends AbstractService
{
    /**
     * @param bool $doSimulation
     * @param bool $withData
     *
     * @return string
     */
    public function setupService($doSimulation, $withData)
    {
        $Protocol = (new Setup($this->getStructure()))->setupDatabaseSchema($doSimulation);
        if (!$doSimulation && $withData) {
            (new Data($this->getBinding()))->setupDatabaseContent();
        }
        return $Protocol;
    }

    /**
     * @param TblImport_Sales $TblImport_Sales
     */
    public function insertImportSales( TblImport_Sales $TblImport_Sales )
    {
        (new Data($this->getBinding()))->insertImportSales( $TblImport_Sales );
    }

    /**
     *
     */
    public function flushImportSales()
    {
        (new Data($this->getBinding()))->flushImportSales();
    }

}