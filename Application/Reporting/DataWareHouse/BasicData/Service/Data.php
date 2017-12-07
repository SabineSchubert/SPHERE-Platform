<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:33
 */

namespace SPHERE\Application\Reporting\DataWareHouse\BasicData\Service;


use SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Entity\TblImport_PmMc;
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
      * @param TblImport_PmMc $TblImport_PmMc
      */
     public function insertImportPmMc( TblImport_PmMc $TblImport_PmMc )
     {
         $this->getEntityManager()->bulkSaveEntity( $TblImport_PmMc );
     }

    /**
     * @param TblImport_PmMc $TblImport_PmMc
     */
     public function killImportPmMc( TblImport_PmMc $TblImport_PmMc )
     {
         $this->getEntityManager()->bulkKillEntity( $TblImport_PmMc );
     }

    /**
     *
     */
     public function flushImportPmMc()
     {
         $this->getEntityManager()->flushCache();
     }
}