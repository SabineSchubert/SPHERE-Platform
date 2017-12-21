<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:33
 */

namespace SPHERE\Application\Reporting\DataWareHouse\BasicData\Service;


use SPHERE\Application\Reporting\DataWareHouse\BasicData\BasicData;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Entity\TblImport_PmMc;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_MarketingCode;
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
     public function doublePmMc()
     {
         $Manager = $this->getConnection()->getEntityManager();
         $QueryBuilder = $Manager->getQueryBuilder();

         $TableTmpPmMc = new TblImport_PmMc();
         $TableTmpPmMcAlias = $TableTmpPmMc->getEntityShortName();

         $DoubleMc = $QueryBuilder
             ->select($TableTmpPmMcAlias . '.' . $TableTmpPmMc::MC_NUMBER . ' AS MC')
             //->addSelect( 'COUNT( '.$TableTmpPmMcAlias.'.'.$TableTmpPmMc::PM_NUMBER.' ) AS CountPm' )
             ->from($TableTmpPmMc->getEntityFullName(), $TableTmpPmMcAlias)
             ->groupBy($TableTmpPmMcAlias . '.' . $TableTmpPmMc::MC_NUMBER)
             ->having('COUNT( ' . $TableTmpPmMcAlias . '.' . $TableTmpPmMc::PM_NUMBER . ' ) > 1 ')
             ->getQuery()->getResult();

         if ($DoubleMc) {

             $McList = array();
             array_walk($DoubleMc, function(&$Row) use (&$McList) {
                 array_push($McList, $Row['MC']);
             });

             $QueryBuilder2 = $Manager->getQueryBuilder();

             $SqlDoublePmMc = $QueryBuilder2
                 ->select($TableTmpPmMcAlias . '.' . $TableTmpPmMc::MC_NUMBER . ' AS Marketingcode')
                 ->addSelect($TableTmpPmMcAlias . '.' . $TableTmpPmMc::PM_NUMBER . ' AS ProduktmanagerNummer')
                 ->addSelect($TableTmpPmMcAlias . '.' . $TableTmpPmMc::PM_NAME . ' AS Produktmanager')
                 ->from($TableTmpPmMc->getEntityFullName(), $TableTmpPmMcAlias)
                 ->where($TableTmpPmMcAlias . '.' . $TableTmpPmMc::MC_NUMBER . ' in (:'.$TableTmpPmMc::MC_NUMBER.') ')
                 ->setParameter($TableTmpPmMc::MC_NUMBER, $McList )
                 ->getQuery();

             return $SqlDoublePmMc->getResult();
         } else {
             return null;
         }
     }

    /**
     * @return array|null
     */
     public function getImportPmMc() {
         $Manager = $this->getEntityManager();
         $EntityList = $Manager->getEntity( 'TblImport_PmMc' )->findAll();
         if($EntityList) {
            return $EntityList;
         }
         else {
             return null;
         }
     }

    /**
     * @param string $ProductManagerNumber
     * @return array|null
     */
     public function getImportPmMcByProductManager( $ProductManagerNumber ) {
         $Manager = $this->getEntityManager();
         $EntityList = $Manager->getEntity( 'TblImport_PmMc' )->findBy(array(
             TblImport_PmMc::PM_NUMBER => $ProductManagerNumber
         ));
         if($EntityList) {
            return $EntityList;
         }
         else {
            return null;
         }
     }

    /**
     * @param string $ProductManagerNumber
     * @param string $MarketincCodeNumber
     * @return array|null
     */
     public function getImportPmMcByProductManagerMarketingCode( $ProductManagerNumber, $MarketincCodeNumber ) {
         $Manager = $this->getEntityManager();
         $EntityList = $Manager->getEntity( 'TblImport_PmMc' )->findBy(array(
             TblImport_PmMc::PM_NUMBER => $ProductManagerNumber,
             TblImport_PmMc::MC_NUMBER => $MarketincCodeNumber
         ));
         if($EntityList) {
            return $EntityList;
         }
         else {
            return null;
         }
     }

    /**
     * @param array[] $EntityList
     */
     public function updateProductManagerMarketingCodeByList( $EntityList ) {
         $EntityProductManager = DataWareHouse::useService()->getProductManagerAll();
         foreach($EntityProductManager AS $PM) {
             //Prüfung, ob PM in der Import-Liste noch enthalten ist, wenn nicht
             if( null === BasicData::useService()->getImportPmMcByProductManager( $PM->getNumber() ) ) {
                 //Alle Kombinationen PM - MC über PM selektieren
                 $EntityProductManagerMarketingCode = DataWareHouse::useService()->getProductManagerMarketingCodeByProductManager( $PM );

                 if( null !== $EntityProductManagerMarketingCode ) {
                    //DELETE: alle gefundenen PM-MC löschen
                    foreach($EntityProductManagerMarketingCode AS $ProductManagerMarketingCode) {
                        DataWareHouse::useService()->deleteProductManagerMarketingCode( $ProductManagerMarketingCode->getId() );
                    }
                 }

             }
             //alle MC-Zuordnungen überprüfen
             else {

              //1. Prüfung, gibt es Marketingcodes zum Löschen

                 //Reporting: Ermittlung alle bisherigen MC zum PM
                 $ReportingPmMcList = DataWareHouse::useService()->getProductManagerMarketingCodeByProductManager( $PM );

                 /** @var TblReporting_ProductManager_MarketingCode $ReportingPmMc */
                 foreach($ReportingPmMcList AS $ReportingPmMc) {
                     $EntityReportingMarketingCode = DataWareHouse::useService()->getMarketingCodeById( $ReportingPmMc->getId() );
                     //Prüfung, ob PM-MC Kombi in der Import-Liste ist
                     $IsPmMcReportingInPmMcImport = BasicData::useService()->getImportPmMcByProductManagerMarketingCode( $PM->getNumber(), $EntityReportingMarketingCode->getNumber() );
                     if( null !== $IsPmMcReportingInPmMcImport ) {
                         //Reporting: Datensatz löschen
                         DataWareHouse::useService()->deleteProductManagerMarketingCode( $ReportingPmMc->getId() );
                     }
                     else {
                        //belassen, Import = Reporting
                     }
                 }


                 //Import: Marketingcode-Liste zu entsprechenden Produktmanager
                 $MarketingCodeList = BasicData::useService()->getImportPmMcByProductManager( $PM->getNumber() );
                 foreach($MarketingCodeList AS $Row) {
                      //insert neue Kombi
                      $EntityMarketingCode = DataWareHouse::useService()->getMarketingCodeByNumber($Row['McNumber']);
                      if( null === DataWareHouse::useService()->getProductManagerMarketingCodeByProductManagerMarketingCode( $PM, $EntityMarketingCode ) ) {
                          DataWareHouse::useService()->createProductManagerMarketingcode($PM, $EntityMarketingCode );
                      }
                 }

             }
         }

     }
}