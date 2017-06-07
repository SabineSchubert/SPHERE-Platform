<?php

namespace SPHERE\Application\Reporting\DataWareHouse\Sales\Converter;

use SPHERE\Application\Platform\Utility\Transfer\AbstractConverter;
use SPHERE\Application\Platform\Utility\Transfer\FieldPointer;
use SPHERE\Application\Platform\Utility\Transfer\FieldSanitizer;
use SPHERE\Application\Reporting\DataWareHouse\Sales\Sales;
use SPHERE\Application\Reporting\DataWareHouse\Sales\Service\Entity\TblImport_Sales;
use SPHERE\System\Extension\Repository\Debugger;

class ReadImportSales extends AbstractConverter
{
    public function __construct($File)
    {
        ini_set('memory_limit', '2G');

        $this->loadFile($File);

        $TableImportSales = new TblImport_Sales();

        $this->setPointer( new FieldPointer( 'A', $TableImportSales::PART_NUMBER ) );
        $this->setPointer( new FieldPointer( 'A', $TableImportSales::PART_DESCRIPTION ) );
        $this->setPointer( new FieldPointer( 'B', $TableImportSales::MARKETING_CODE_NUMBER ) );
        $this->setPointer( new FieldPointer( 'B', $TableImportSales::MARKETING_CODE_DESCRIPTION ) );
        $this->setPointer( new FieldPointer( 'C', $TableImportSales::MONTH ) );
        $this->setPointer( new FieldPointer( 'C', $TableImportSales::YEAR ) );
        $this->setPointer( new FieldPointer( 'D', $TableImportSales::QUANTITY ) );
        $this->setPointer( new FieldPointer( 'E', $TableImportSales::SALES_GROSS ) );
        $this->setPointer( new FieldPointer( 'F', $TableImportSales::SALES_NET ) );

        //Teilenummern-Spalte zerlegen
        $this->setSanitizer(new FieldSanitizer('A',$TableImportSales::PART_NUMBER, function( $Value ){
            return str_replace(' ','', explode(' - ',$Value)[0]);
        }));
        $this->setSanitizer(new FieldSanitizer('A',$TableImportSales::PART_DESCRIPTION, function( $Value ){
            return explode(' - ',$Value)[1];
        }));

        //Marketingcode-Spalte zerlegen
        $this->setSanitizer(new FieldSanitizer('B',$TableImportSales::MARKETING_CODE_NUMBER, function( $Value ){
            return explode(' - ',$Value)[0];
        }));
        $this->setSanitizer(new FieldSanitizer('B',$TableImportSales::MARKETING_CODE_DESCRIPTION, function( $Value ){
            return explode(' - ',$Value)[1];
        }));

        //Zeitraum-Spalte zerlegen
        $this->setSanitizer(new FieldSanitizer('C',$TableImportSales::MONTH, function( $Value ){
            $SplittValue = explode('/',$Value);
            $Month = 0;
            switch ($SplittValue[1]) {
                case 'Jan':
                    $Month = 1;
                    break;
                case 'Feb':
                    $Month = 2;
                    break;
                case 'Mär':
                case 'MÃ¤r':
                    $Month = 3;
                    break;
                case 'Apr':
                    $Month = 4;
                    break;
                case 'Mai':
                    $Month = 5;
                    break;
                case 'Jun':
                    $Month = 6;
                    break;
                case 'Jul':
                    $Month = 7;
                    break;
                case 'Aug':
                    $Month = 8;
                    break;
                case 'Sep':
                    $Month = 9;
                    break;
                case 'Okt':
                    $Month = 10;
                    break;
                case 'Nov':
                    $Month = 11;
                    break;
                case 'Dez':
                    $Month = 12;
                    break;
            }
            return $Month;
        }));
        $this->setSanitizer(new FieldSanitizer('C',$TableImportSales::YEAR, function( $Value ){
            return explode('/',$Value)[0];
        }));

        //Mengen-Spalte
        $this->setSanitizer(new FieldSanitizer('D',$TableImportSales::QUANTITY, function( $Value ){
            return (int)$Value;
        }));

        $this->setPointer( new FieldPointer( 'E', $TableImportSales::SALES_GROSS ) );
        $this->setPointer( new FieldPointer( 'F', $TableImportSales::SALES_NET ) );

        // TODO: Length
        $this->scanFile(2);

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

       $Entity = new TblImport_Sales();
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


        if( count( $this->EntityList ) > 5 ) {
            $this->insertData();
        }
    }

    private function insertData()
    {
        foreach( $this->EntityList as $TblImport_Sales ) {
            Sales::useService()->insertImportSales( $TblImport_Sales );
        }
        Sales::useService()->flushImportSales();
        $this->EntityList = array();
    }

}