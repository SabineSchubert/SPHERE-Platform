<?php

namespace SPHERE\Application\Example\Upload\Excel\Converter;

use SPHERE\Application\Platform\Utility\Transfer\AbstractConverter;
use SPHERE\Application\Platform\Utility\Transfer\FieldPointer;

class LoadExampleUploadExcel extends AbstractConverter
{

    /** @var bool $Valid */
    private $Valid = true;

    public function __construct($File)
    {
        $this->loadFile($File);

        $this->addSanitizer( array($this, 'sanitizeFullTrim') );

        $this->setPointer( new FieldPointer( 'A', 'A' ) );
        $this->setPointer( new FieldPointer( 'B', 'B' ) );


        $this->scanFile(0, 1);
    }

    /**
     * @param array $Row
     *
     * @return mixed|void
     */
    public function runConvert($Row)
    {

        $Header = array(
            'A' => 'Artikelnummer',
            'B' => 'Artikelbezeichnung'
        );

        foreach( $Header as $Column => $Name ) {
            if ($Row[$Column][$Column] != $Name) {
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