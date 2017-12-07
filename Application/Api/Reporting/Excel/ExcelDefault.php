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
use SPHERE\Common\Frontend\Message\Repository\Warning;
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
        $Dispatcher->registerMethod('getExcelSearch');
        $Dispatcher->registerMethod('getExcelProductManagerMarketingCode');

        return $Dispatcher->callMethod($Method);
    }

    public static function getExcel( $FileName, $FileTyp, $DataList, $ReplaceArray ) {

        $FilePointer = new FilePointer($FileTyp);
        $FileLocation = $FilePointer->getFileLocation();
        /**
         * @var PhpExcel $Document
         */
        $Document = Document::getDocument( $FileLocation );

//         Head
        $HeaderList = array_keys( (array)$DataList[0] );
        foreach( (array)$HeaderList as $HeaderIndex => $HeaderText ) {
            if(stripos($HeaderText,';HIDE') == false) {
                if(strstr($HeaderText,'Group_')) {
                    $HeaderText = trim(implode('#',array_slice(explode('#',$HeaderText),0,-1)));
                }
//                Excel::CellStyle(
//                    Excel::CellIndex2Name( $HeaderIndex,0 ),
//                    array('width'=>'auto','text-align'=>'center')
//                );
                $Document->setStyle( $Document->getCell( $HeaderIndex, 0 ) )->setWrapText()->setColumnWidth(-1);
                $Document->setValue( $Document->getCell($HeaderIndex,0), trim(str_replace(array_keys($ReplaceArray),$ReplaceArray,$HeaderText)) );
                //new PhpExcel\Style()
//                Excel::CellWrap(
//                    Excel::CellIndex2Name( $HeaderIndex, 0 )
//                    , true
//                );

            }
        }

        // Body
        foreach( (array)$DataList as $RowIndex => $RowList ) {
            $ColIndex = 0;
            foreach( (array)$RowList as $ColName => $ColValue ) {

                if(stripos($ColName,';HIDE') == false) {
                    //Zahl
                    if( is_int( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
                        $Document->setValue( $Document->getCell( $ColIndex++, ( $RowIndex +1 ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
                        //Debugger::screenDump($ColName.'Test'.$ColValue);
                    }
                    elseif( is_float( self::ConvertNumeric($ColValue,$ColName) ) == true ) {

                        //$Document->setStyle( $Document->getCell( ($ColIndex++), ( $RowIndex + 1 ) ) )->setFormatCode('#.##0,00');// array('number-format'=>'#,##0.00') );
                        //Debugger::screenDump($ColName, $ColIndex);
                        $Document->setValue( $Document->getCell( ($ColIndex++), ( $RowIndex + 1 ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
                        //Debugger::screenDump($ColName.$ColIndex);
                    }
                    else {
                        $Document->setValue( $Document->getCell( $ColIndex++, ( $RowIndex +1 ) ), $ColValue, PhpExcel::TYPE_STRING );
                        //Debugger::screenDump($ColName.'Test2');
                    }
                }
            }
        }

        $Document->saveFile();
        $FilePointer->loadFile();
        //exit();
        print FileSystem::getDownload($FilePointer->getRealPath(), $FileName.'.'.$FileTyp);
    }

    private static function ConvertNumeric( $Parameter, $ColName ) {
        if ( substr($ColName,0,5) == "Data_" or substr($ColName,0,6) == "Group_" ) {
            $Parameter = (preg_match('!^[0-9|-]+$!is',$Parameter )?(integer)$Parameter:$Parameter);
            $Parameter = (preg_match('!(^[0-9|-]+\.([0-9]+(E-)[0-9]|[0-9]+)+$|^\.[0]{1,})!is',$Parameter )?(float)$Parameter:$Parameter);
            // done
            return $Parameter;
        }
    }

    public function getExcelMonthlyTurnover( $FileName, $FileTyp, $PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null, $ProductGroupNumber = null ) {
        if( $PartNumber or $MarketingCodeNumber or $ProductManagerId or $ProductGroupNumber ) {
            $DataList = DataWareHouse::useService()->getMonthlyTurnover( $PartNumber, $MarketingCodeNumber, $ProductManagerId, $ProductGroupNumber );
            $YearCurrent = DataWareHouse::useService()->getYearCurrentFromSales();

            $ReplaceArray = array(
                'Data_' => '',
                'Group_' => '',
                'Month' => 'Monat',
                'SumSalesGross' => 'Bruttoumsatz',
                'SumSalesNet' => 'Nettoumsatz',
                'SumQuantity' => 'Menge',
                'Discount' => 'Rabatt',
                '_' => ' ',
                'VVJ' => "\n".($YearCurrent-2),
                'VJ' => "\n".($YearCurrent-1),
                'AJ' => "\n".$YearCurrent,
            );
            return self::getExcel( $FileName, $FileTyp, $DataList, $ReplaceArray );
        }
        else {
            return new Warning('Es sind keine Daten vorhanden!');
        }
    }

    public function getExcelSearch( $FileName, $FileTyp, $GroupBy, $PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null, $PeriodFrom = null, $PeriodTo = null ) {
        if($GroupBy) {
            switch( $GroupBy ) {
                case 'Part':
                    $DataList = DataWareHouse::useService()->getSalesGroupPart( $PartNumber, $MarketingCodeNumber, $ProductManagerId, $PeriodFrom, $PeriodTo );
                    break;
                case 'MarketingCode':
                    $DataList = DataWareHouse::useService()->getSalesGroupMarketingCode( $PartNumber, $MarketingCodeNumber, $ProductManagerId, $PeriodFrom, $PeriodTo );
                    break;
                case 'ProductManager':
                    $DataList = DataWareHouse::useService()->getSalesGroupProductManager( $PartNumber, $MarketingCodeNumber, $ProductManagerId, $PeriodFrom, $PeriodTo );
                    break;
                case 'Competition':
                    $DataList = array();
                    break;
                default:
                    $DataList = array();
            }

            if( count($DataList) > 0 ) {

                $ReplaceArray = array(
                    'Data_' => '',
                    'Group_' => '',
                    'ProductManagerName' => 'Produktmanager',
                    'ProductManagerDepartment' => 'Bereich',
                    'PartNumber' => 'Teilenummer',
                    'PartName' => 'Bezeichnung',
                    'PriceGross' => 'BLP',
                    'PriceNet' => 'NLP',
                    'MarketingCodeNumber' => 'Marketingcode',
                    'MarketingCodeName' => 'Marketingcode-Bezeichnung',
                    'SumSalesGross' => 'Bruttoumsatz',
                    'SumSalesNet' => 'Nettoumsatz',
                    'SumQuantity' => 'Menge',
                );
                return self::getExcel( $FileName, $FileTyp, $DataList, $ReplaceArray );
            }
            else {
                return new Warning('Es sind keine Daten vorhanden!');
            }
        }
        else {
            return new Warning('Es sind keine Daten vorhanden!');
        }
    }

    public function getExcelProductManagerMarketingCode( $FileName, $FileTyp )
    {
        $DataList = DataWareHouse::useService()->getProductManagerMarketingCodeCurrent();
        array_walk( $DataList, array( 'SPHERE\Application\Api\Reporting\Excel\ExcelDefault', 'WalkE1' ) );
        if (count($DataList) > 0) {
            return self::getExcel($FileName, $FileTyp, $DataList, array());
        } else {
            return new Warning('Es sind keine Daten vorhanden!');
        }
    }

    private function WalkE1( &$Row ) {
        if( is_array( $Row ) ) {
            array_walk( $Row, array( 'SPHERE\Application\Api\Reporting\Excel\ExcelDefault', 'WalkE1' ) );
        } else {
            $Row = (!$this->detectUTF8($Row))?utf8_encode($Row):$Row;
        }
    }

    private function detectUTF8( $Value ) {
   		return preg_match('%(?:
           [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
           |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
           |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
           |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
           |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
           |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
           |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
           )+%xs', $Value);
   	}
}