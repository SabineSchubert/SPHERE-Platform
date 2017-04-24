<?php

namespace SPHERE\Application\Example\Upload\Excel\Service;


use Doctrine\DBAL\Schema\Schema;
use SPHERE\Application\Example\Upload\Excel\Service\Entity\TblExampleUploadExcel;
use SPHERE\System\Database\Binding\AbstractSetup;

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

        $this->setTableExampleUploadExcel( $Schema );

        return $this->saveSchema( $Schema, $Simulate );
    }

    private function setTableExampleUploadExcel( Schema $Schema )
    {
        $Table = $this->createTable( $Schema, (new TblExampleUploadExcel())->getEntityShortName() );

        for ($Column = 'A'; $Column !== 'Z'; $Column++){
            $this->createColumn( $Table, 'Column_'.$Column, self::FIELD_TYPE_STRING, true);
        }
        $this->createColumn( $Table, 'Column_'.$Column, self::FIELD_TYPE_STRING, true);

        return $Table;
    }
}