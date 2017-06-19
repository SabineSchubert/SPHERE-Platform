<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 13:05
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Presto\Converter;


use SPHERE\Application\Platform\Utility\Transfer\AbstractConverter;
use SPHERE\Application\Platform\Utility\Transfer\FieldPointer;
use SPHERE\Application\Reporting\DataWareHouse\Presto\Presto;
use SPHERE\Application\Reporting\DataWareHouse\Presto\Service\Entity\TblImport_Presto;
use SPHERE\System\Extension\Repository\Debugger;

class ReadImportPresto extends AbstractConverter
{
    public function __construct($File)
    {
        ini_set('memory_limit', '2G');

        $this->loadFile($File);

        $TableImportPresto = new TblImport_Presto();

        $this->setPointer( new FieldPointer( 'A', $TableImportPresto::PRODUCT_GROUP_NUMBER ) );
        $this->setPointer( new FieldPointer( 'B', $TableImportPresto::MARKETING_CODE_NUMBER ) );
        $this->setPointer( new FieldPointer( 'C', $TableImportPresto::MARKETING_CODE_NAME ) );
        $this->setPointer( new FieldPointer( 'D', $TableImportPresto::ASSORTMENT_GROUP_NUMBER ) );
        $this->setPointer( new FieldPointer( 'E', $TableImportPresto::SECTION_NUMBER ) );
        $this->setPointer( new FieldPointer( 'F', $TableImportPresto::PART_NUMBER ) );
        $this->setPointer( new FieldPointer( 'G', $TableImportPresto::PART_NAME ) );
        $this->setPointer( new FieldPointer( 'H', $TableImportPresto::PART_NUMBER_DISPLAY ) );
        $this->setPointer( new FieldPointer( 'I', $TableImportPresto::PART_ES_1 ) );
        $this->setPointer( new FieldPointer( 'J', $TableImportPresto::PART_ES_2 ) );
        $this->setPointer( new FieldPointer( 'K', $TableImportPresto::PART_SUCCESSOR ) );
        $this->setPointer( new FieldPointer( 'L', $TableImportPresto::PART_PREDECESSOR ) );
        $this->setPointer( new FieldPointer( 'M', $TableImportPresto::PART_OPTIONAL ) );
        $this->setPointer( new FieldPointer( 'N', $TableImportPresto::PART_SPARE_PART_DESIGN ) );
        $this->setPointer( new FieldPointer( 'O', $TableImportPresto::PART_STATUS_ACTIVE ) );
        $this->setPointer( new FieldPointer( 'P', $TableImportPresto::PRICE_PRICE_GROSS ) );
        $this->setPointer( new FieldPointer( 'Q', $TableImportPresto::PRICE_PRICE_NET ) );
        $this->setPointer( new FieldPointer( 'R', $TableImportPresto::PRICE_BACK_VALUE ) );
        $this->setPointer( new FieldPointer( 'S', $TableImportPresto::PRICE_COSTS_VARIABLE ) );
        $this->setPointer( new FieldPointer( 'T', $TableImportPresto::PRICE_VALID_FROM ) );
        $this->setPointer( new FieldPointer( 'U', $TableImportPresto::DISCOUNT_GROUP_NUMBER ) );
        $this->setPointer( new FieldPointer( 'V', $TableImportPresto::DISCOUNT_DISCOUNT ) );
        $this->setPointer( new FieldPointer( 'W', $TableImportPresto::SUPPLIER_NUMBER_1 ) );
        $this->setPointer( new FieldPointer( 'X', $TableImportPresto::SUPPLIER_NAME_1 ) );
        $this->setPointer( new FieldPointer( 'Y', $TableImportPresto::SUPPLIER_NUMBER_2 ) );
        $this->setPointer( new FieldPointer( 'Z', $TableImportPresto::SUPPLIER_NAME_2 ) );
        $this->setPointer( new FieldPointer( 'AA', $TableImportPresto::SUPPLIER_NUMBER_3 ) );
        $this->setPointer( new FieldPointer( 'AB', $TableImportPresto::SUPPLIER_NAME_3 ) );

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

        Debugger::screenDump($Row);

       $Entity = new TblImport_Presto();
       foreach ($Row as $Column => $Payload) {
            //$Entity->__set( $Column, $Payload[$Column] );
           if(count($Payload) > 1) {
               foreach((array)$Payload AS $ColumnDB => $Value ) {
                    $Entity->__set( $ColumnDB, $Value );
               }
           }
           else {
               $Entity->__set( key($Payload), $Payload[key($Payload)] );
           }
       }
       $this->EntityList[] = $Entity;


        if( count( $this->EntityList ) > 0 ) {
            $this->insertData();
        }
    }

    private function insertData()
    {
        foreach( $this->EntityList as $TblImport_Presto ) {
            Presto::useService()->insertImportPresto( $TblImport_Presto );
        }
        Presto::useService()->flushImportPresto();
        $this->EntityList = array();
    }
}