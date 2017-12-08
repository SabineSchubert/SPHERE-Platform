<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:32
 */

namespace SPHERE\Application\Reporting\DataWareHouse\BasicData;

use SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Data;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Entity\TblImport_PmMc;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Setup;
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
     * @param TblImport_PmMc $TblImport_PmMc
     */
    public function insertImportPmMc( TblImport_PmMc $TblImport_PmMc )
    {
        (new Data($this->getBinding()))->insertImportPmMc( $TblImport_PmMc );
    }

    /**
     * @param TblImport_PmMc $TblImport_PmMc
     */
    public function killImportPmMc( TblImport_PmMc $TblImport_PmMc )
    {
        (new Data($this->getBinding()))->killImportPmMc( $TblImport_PmMc );
    }

    /**
     *
     */
    public function flushImportPmMc()
    {
        (new Data($this->getBinding()))->flushImportPmMc();
    }

    /**
     *
     */
    public function doublePmMc() {
        return (new Data($this->getBinding()))->doublePmMc();
    }
}