<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 13:05
 */

namespace SPHERE\Application\Reporting\DataWareHouse\BasicData\Converter;


use SPHERE\Application\Platform\Utility\Transfer\AbstractConverter;
use SPHERE\Application\Platform\Utility\Transfer\FieldPointer;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\BasicData;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Entity\TblImport_PmMc;
use SPHERE\System\Extension\Repository\Debugger;

class ReadImportPmMc extends AbstractConverter
{
    public function __construct($File)
    {
        ini_set('memory_limit', '2G');

        $this->loadFile($File);

        $TableImportPmMc = new TblImport_PmMc();

        $this->setPointer( new FieldPointer( 'A', $TableImportPmMc::PM_NUMBER ) );
        $this->setPointer( new FieldPointer( 'B', $TableImportPmMc::PM_NAME ) );
        $this->setPointer( new FieldPointer( 'C', $TableImportPmMc::MC_NUMBER ) );
        $this->setPointer( new FieldPointer( 'D', $TableImportPmMc::MC_NAME ) );

        // TODO: Length
        $this->scanFile(1);

        //$this->insertData();
    }

    private $EntityList = array();

    /**
     * @param array $Row
     *
     * @return mixed|void
     */
    public function runConvert($Row)
    {

       $Entity = new TblImport_PmMc();
       foreach ($Row as $Column => $Payload) {
            //$Entity->__set( $Column, $Payload[$Column] );
           if(count($Payload) > 1) {
               foreach((array)$Payload AS $ColumnDB => $Value ) {
                    $Entity->__set( $ColumnDB, $Value );
               }
           }
           else {
               if( key($Payload) == 'PmNumber' && $Payload[key($Payload)] === null) {
                   $Value = '';
               }
               else {
                   $Value = $Payload[key($Payload)];
               }
               $Entity->__set( key($Payload), $Value );
           }
       }
       $this->EntityList[] = $Entity;


        if( count( $this->EntityList ) > 0 ) {
            $this->insertData();
        }
    }

    private function insertData()
    {

        foreach( $this->EntityList as $TblImport_PmMc ) {
            BasicData::useService()->insertImportPmMc( $TblImport_PmMc );
        }
        BasicData::useService()->flushImportPmMc();
        $this->EntityList = array();
    }
}