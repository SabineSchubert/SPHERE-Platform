<?php

namespace SPHERE\Application\Example\Upload\Excel\Service;


use SPHERE\Application\Example\Upload\Excel\Service\Entity\TblExampleUploadExcel;
use SPHERE\System\Database\Binding\AbstractData;

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
     * @param TblExampleUploadExcel $TblExampleUploadExcel
     */
    public function insertExampleUploadExcel( TblExampleUploadExcel $TblExampleUploadExcel )
    {
        $this->getEntityManager()->bulkSaveEntity( $TblExampleUploadExcel );
    }

    public function flushExampleUploadExcel()
    {
        $this->getEntityManager()->flushCache();
    }
}