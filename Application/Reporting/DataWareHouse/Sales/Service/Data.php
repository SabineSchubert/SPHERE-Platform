<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 22.05.2017
 * Time: 11:06
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Sales\Service;


use SPHERE\Application\Reporting\DataWareHouse\Sales\Service\Entity\TblImport_Sales;
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
      * @param TblImport_Sales $TblImport_Sales
      */
     public function insertImportSales( TblImport_Sales $TblImport_Sales )
     {
         $this->getEntityManager()->bulkSaveEntity( $TblImport_Sales );
     }

    /**
     *
     */
     public function flushImportSales()
     {
         $this->getEntityManager()->flushCache();
     }
}