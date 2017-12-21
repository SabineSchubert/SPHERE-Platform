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
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
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

    public function getImportPmMc() {
        return (new Data($this->getBinding()))->getImportPmMc();
    }

    /**
     * @param string $ProductManagerNumber
     * @return array|null
     */
    public function getImportPmMcByProductManager( $ProductManagerNumber ) {
        return (new Data($this->getBinding()))->getImportPmMcByProductManager( $ProductManagerNumber );
    }

    /**
     * @param string $ProductManagerNumber
     * @param string $MarketincCodeNumber
     * @return array|null
     */
    public function getImportPmMcByProductManagerMarketingCode( $ProductManagerNumber, $MarketincCodeNumber ) {
        return (new Data($this->getBinding()))->getImportPmMcByProductManagerMarketingCode( $ProductManagerNumber, $MarketincCodeNumber );
    }

    /**
    * Prüfen auf neue MarketingCodes (hinzufügen oder Name updaten)
    */
    public function getNewMarketingCodeFromImport() {
        $ImportPmMcList = BasicData::useService()->getImportPmMc();

        if($ImportPmMcList) {

            foreach ($ImportPmMcList AS $EntityPmMc) {
                //Prüfung, ob es den Marketingcode bereits gibt
                if (null === DataWareHouse::useService()->getMarketingCodeByNumber($EntityPmMc['McNumber'])) {
                    //Insert
                    DataWareHouse::useService()->createMarketingCode($EntityPmMc['McNumber'], $EntityPmMc['McName']);
                } else {
                    //Update
                    DataWareHouse::useService()->updateMarketingCode($EntityPmMc['McNumber'], $EntityPmMc['McName']);
                }
            }
        }
    }

    /**
     * @param $EntityList
     */
    public function updateProductManagerMarketingCodeByList( $EntityList ) {
        BasicData::useService()->getNewMarketingCodeFromImport();
        return (new Data($this->getBinding()))->updateProductManagerMarketingCodeByList( $EntityList );
    }
}