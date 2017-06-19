<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:32
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Presto;


use SPHERE\Application\Reporting\DataWareHouse\Presto\Service\Data;
use SPHERE\Application\Reporting\DataWareHouse\Presto\Service\Entity\TblImport_Presto;
use SPHERE\Application\Reporting\DataWareHouse\Presto\Service\Setup;
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
     * @param TblImport_Presto $TblImport_Presto
     */
    public function insertImportPresto( TblImport_Presto $TblImport_Presto )
    {
        (new Data($this->getBinding()))->insertImportPresto( $TblImport_Presto );
    }

    /**
     *
     */
    public function flushImportPresto()
    {
        (new Data($this->getBinding()))->flushImportPresto();
    }
}