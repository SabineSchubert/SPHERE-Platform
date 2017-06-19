<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:33
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Presto\Service;


use SPHERE\Application\Reporting\DataWareHouse\Presto\Service\Entity\TblImport_Presto;
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
      * @param TblImport_Presto $TblImport_Presto
      */
     public function insertImportPresto( TblImport_Presto $TblImport_Presto )
     {
         $this->getEntityManager()->bulkSaveEntity( $TblImport_Presto );
     }

    /**
     *
     */
     public function flushImportPresto()
     {
         $this->getEntityManager()->flushCache();
     }
}