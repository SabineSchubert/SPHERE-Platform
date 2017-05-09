<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 04.05.2017
 * Time: 13:30
 */

namespace SPHERE\Application\Api\Reporting\Excel;


use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Component\Parameter\Repository\FileParameter;
use MOC\V\Component\Document\Document;
use MOC\V\Core\FileSystem\FileSystem;
use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Application\Reporting\Controlling\MonthlyTurnover\MonthlyTurnover;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Extension\Repository\Debugger;

class ExcelDefault implements IApiInterface
{

    use ApiTrait;

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('getExcel');
        $Dispatcher->registerMethod('getExcelMonthlyTurnover');

        return $Dispatcher->callMethod($Method);
    }

    public static function getExcel( $FileName, $FileTyp, $DataList, $ReplaceArray ) {

        return 'noch in Entwicklung von Gerd';

        $FilePointer = new FilePointer($FileTyp);
        $FileLocation = $FilePointer->getFileLocation();
        /**
         * @var PhpExcel $Document
         */
        $Document = Document::getDocument( $FileLocation );
//      $Document->setValue( new PhpExcel\Cell(1,1), 'Test' );

        $Excel = new PhpExcel;

        // Head
        $HeaderList = array_keys( (array)$DataList[0] );
        foreach( (array)$HeaderList as $HeaderIndex => $HeaderText ) {
            if(stripos($HeaderText,';HIDE') == false) {
                if(strstr($HeaderText,'Data_')) {
                    $HeaderText = trim(implode('#',array_slice(explode('#',$HeaderText),0,-1)));
                }
//                Excel::CellStyle(
//                    Excel::CellIndex2Name( $HeaderIndex,0 ),
//                    array('width'=>'auto','text-align'=>'center')
//                );
                $Document->setValue( $Excel->getCell($HeaderIndex,0), trim(str_replace(array_keys($ReplaceArray),$ReplaceArray,$HeaderText)) );
                //new PhpExcel\Style()
//                Excel::CellWrap(
//                    Excel::CellIndex2Name( $HeaderIndex, 0 )
//                    , true
//                );

            }
        }

        $Document->saveFile(new FileParameter($FileLocation));

        return (string)FileSystem::getDownload($FileLocation, $FileName.'.'.$FileTyp);
    }

    private function ConvertNumeric( $Parameter, $ColName ) {
        if ( substr($ColName,0,5) == "Data_" or substr($ColName,0,6) == "Group_" ) {
            $Parameter = (preg_match('!^[0-9|-]+$!is',$Parameter )?(integer)$Parameter:$Parameter);
            $Parameter = (preg_match('!(^[0-9|-]+\.([0-9]+(E-)[0-9]|[0-9]+)+$|^\.[0]{1,})!is',$Parameter )?(float)$Parameter:$Parameter);
            // done
            return $Parameter;
        }
    }

    public function getExcelMonthlyTurnover( $FileName, $FileTyp, $PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null ) {
        if( $PartNumber or $MarketingCodeNumber or $ProductManagerId ) {
            $DataList = DataWareHouse::useService()->getMonthlyTurnover( $PartNumber, $MarketingCodeNumber, $ProductManagerId );
            $ReplaceArray = array(
                'Month' => 'Monat',
                'SumSalesGross' => 'Bruttoumsatz',
                'SumSalesNet' => 'Nettoumsatz',
                'SumQuantity' => 'Menge',
                'Discount' => 'Rabatt',
                '_AJ' => '',
                '_VJ' => '',
                '_VVJ' => '',
            );
            return self::getExcel( $FileName, $FileTyp, $DataList, $ReplaceArray );
        }
        return null;
    }
}