<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:33
 */

namespace SPHERE\Application\Reporting\DataWareHouse\BasicData\Service;


use SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Entity\TblImport_PmMc;
use SPHERE\System\Database\Binding\AbstractSetup;
use Doctrine\DBAL\Schema\Schema;

class Setup extends AbstractSetup
{

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema($Simulate = true)
    {
        $Schema = $this->loadSchema();

       $this->setTableImportPmMc( $Schema );

       return $this->saveSchema( $Schema, $Simulate );
    }


    private function setTableImportPmMc( Schema $Schema )
    {
        $TablePmMc = new TblImport_PmMc();
        $Table = $this->createTable( $Schema, $TablePmMc->getEntityShortName() );

        $this->createColumn( $Table, $TablePmMc::PM_NUMBER, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TablePmMc::PM_NAME, self::FIELD_TYPE_STRING, true);

        $this->createColumn( $Table, $TablePmMc::MC_NUMBER, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TablePmMc::MC_NAME, self::FIELD_TYPE_STRING, true);

        return $Table;
    }
}