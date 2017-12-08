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

class LoadImportPmMc extends AbstractConverter
{
    /** @var bool $Valid */
    private $Valid = true;

    public function __construct($File)
    {

        $TableImportPmMc = new TblImport_PmMc();
        //alte Daten löschen
        BasicData::useService()->killImportPmMc($TableImportPmMc);

       $this->loadFile($File);

       $this->setPointer( new FieldPointer( 'A', 'A' ) );
       $this->setPointer( new FieldPointer( 'B', 'B' ) );
       $this->setPointer( new FieldPointer( 'C', 'C' ) );
       $this->setPointer( new FieldPointer( 'D', 'D' ) );

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
           'A' => 'PmNumber',
           'B' => 'PmName',
           'C' => 'McNumber',
           'D' => 'McName',
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