<?php

namespace SPHERE\Application\Example\Upload\Excel\Converter;

use SPHERE\Application\Example\Upload\Excel\Excel;
use SPHERE\Application\Example\Upload\Excel\Service\Entity\TblExampleUploadExcel;
use SPHERE\Application\Platform\Utility\Transfer\AbstractConverter;
use SPHERE\Application\Platform\Utility\Transfer\FieldPointer;
use SPHERE\Application\Platform\Utility\Transfer\FieldSanitizer;

class ReadExampleUploadExcel extends AbstractConverter
{
    public function __construct($File)
    {
        ini_set('memory_limit', '2G');

        $this->loadFile($File);

        $this->addSanitizer( array($this, 'sanitizeFullTrim') );
        $this->addSanitizer( function( $Value ){
            return str_replace(',', ', ', $Value);
        });

        $this->setPointer( new FieldPointer( 'A', 'A' ) );
        $this->setPointer( new FieldPointer( 'A', 'B' ) );
        $this->setPointer( new FieldPointer( 'B', 'B' ) );
        $this->setPointer( new FieldPointer( 'F', 'F' ) );
        $this->setSanitizer(new FieldSanitizer('F','F', function( $Value ){
            return number_format($Value, 4);
        }));

        // TODO: Length
        $this->scanFile(1);

//        Debugger::screenDump( $this->EntityList );
        $this->insertData();
    }

    private $EntityList = array();

    /**
     * @param array $Row
     *
     * @return mixed|void
     */
    public function runConvert($Row)
    {
//        Debugger::screenDump( $Row );
        $Entity = new TblExampleUploadExcel();
        foreach ($Row as $Column => $Payload) {
            $Entity->__set( 'Column_'.$Column, $Payload[$Column] );
        }
        $this->EntityList[] = $Entity;

        if( count( $this->EntityList ) > 200 ) {
            $this->insertData();
        }
    }

    private function insertData()
    {
        foreach( $this->EntityList as $TblExampleUploadExcel ) {
            Excel::useService()->insertExampleUploadExcel( $TblExampleUploadExcel );
        }
        Excel::useService()->flushExampleUploadExcel();
        $this->EntityList = array();
    }

}