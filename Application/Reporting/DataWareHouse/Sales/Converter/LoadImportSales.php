<?php

namespace SPHERE\Application\Reporting\DataWareHouse\Sales\Converter;

use SPHERE\Application\Platform\Utility\Transfer\AbstractConverter;
use SPHERE\Application\Platform\Utility\Transfer\FieldPointer;
use SPHERE\Application\Platform\Utility\Transfer\FieldSanitizer;
use SPHERE\System\Extension\Repository\Debugger;

class LoadImportSales extends AbstractConverter
{

    /** @var bool $Valid */
    private $Valid = true;

    public function __construct($File)
    {
        //ToDo: csv-Import
        $this->loadFile($File);

        //$this->addSanitizer( array($this, 'sanitizeFullTrim') );

        $this->setPointer( new FieldPointer( 'A', 'A' ) );
        $this->setPointer( new FieldPointer( 'B', 'B' ) );
        $this->setPointer( new FieldPointer( 'C', 'C' ) );
        $this->setPointer( new FieldPointer( 'D', 'D' ) );
        $this->setPointer( new FieldPointer( 'E', 'E' ) );
        $this->setPointer( new FieldPointer( 'F', 'F' ) );

        $this->scanFile(1, 1);
    }

    /**
     * @param array $Row
     *
     * @return mixed|void
     */
    public function runConvert($Row)
    {
        $Header = array(
            'A' => 'TNR - Bez',
            'B' => 'Marketingcode - Bez',
            'C' => 'Monat',
            'D' => 'Anzahl effektiv',
            'E' => 'Bruttoumsatz',
            'F' => 'Nettoumsatz',
        );

        foreach( $Header as $Column => $Name ) {
            if($Row[$Column][$Column] != $Name) {
                    $this->Valid = false;
                    break;
            }
        }
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->Valid;
    }
}