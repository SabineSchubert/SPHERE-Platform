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
use SPHERE\System\Extension\Repository\Debugger;

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
         $Manager = $this->getEntityManager();
         $EntityList = $Manager->getEntity( 'TblImport_PmMc' )->findAll();
         if($EntityList !== null) {
             foreach ($EntityList as $Entity) {
                 $this->getEntityManager()->bulkKillEntity( $Entity );
             }
             $this->flushImportPmMc();
         }
     }

    /**
     *
     */
     public function flushImportPmMc()
     {
         $this->getEntityManager()->flushCache();
     }

    /**
     * @return array|null
     */
     public function doublePmMc() {
         $Manager = $this->getConnection()->getEntityManager();
         $QueryBuilder = $Manager->getQueryBuilder();

         $TableTmpPmMc = new TblImport_PmMc();
         $TableTmpPmMcAlias = $TableTmpPmMc->getEntityShortName();

         $DoublePmMc = $QueryBuilder
             ->select( $TableTmpPmMcAlias.'.'.$TableTmpPmMc::MC_NUMBER.' AS MC')
                 ->addSelect( 'COUNT( '.$TableTmpPmMcAlias.'.'.$TableTmpPmMc::PM_NUMBER.' ) AS CountPm' )
             ->from( $TableTmpPmMc->getEntityFullName(), $TableTmpPmMcAlias )
             ->groupBy( $TableTmpPmMcAlias.'.'.$TableTmpPmMc::MC_NUMBER )
             ->having( 'COUNT( '.$TableTmpPmMcAlias.'.'.$TableTmpPmMc::PM_NUMBER.' ) > 1 ' )
             ->getQuery();

         if( $DoublePmMc->getResult() ) {
            return $DoublePmMc->getResult();
         }
         else {
             return null;
         }
     }
}