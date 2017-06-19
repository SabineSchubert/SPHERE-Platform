<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 14.06.2017
 * Time: 14:33
 */

namespace SPHERE\Application\Api\Reporting\Excel;

use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Document;
use MOC\V\Core\FileSystem\FileSystem;
use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class ExcelScenarioCalculator extends Extension implements IApiInterface
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

        return $Dispatcher->callMethod($Method);
    }

    public static function getExcel($PriceData)
    {

        $FileTyp = 'xls';
        $FileName = 'SzenarioRechner';

        $FilePointer = new FilePointer($FileTyp);
        $FileLocation = $FilePointer->getFileLocation();
        /**
         * @var PhpExcel $Document
         */
        $Document = Document::getDocument($FileLocation);

        $x = 0;
        $y = 0;

        $Document->setStyle($Document->getCell('A0'))->setColumnWidth(30);
        $Document->setStyle($Document->getCell('B0'))->setColumnWidth(10);
        $Document->setStyle($Document->getCell('C0'))->setColumnWidth(10);
        $Document->setStyle($Document->getCell('D0'))->setColumnWidth(10);
        $Document->setStyle($Document->getCell('F0'))->setColumnWidth(30);
        $Document->setStyle($Document->getCell('G0'))->setColumnWidth(10);
        $Document->setStyle($Document->getCell('H0'))->setColumnWidth(10);
        $Document->setStyle($Document->getCell('I0'))->setColumnWidth(10);

        $Document->setValue( $Document->getCell($x,$y), 'Bezeichnung' )->setStyle($Document->getCell($x,$y))->setFontBold();$x++;
        $Document->setValue( $Document->getCell($x,$y), 'Alt' )->setStyle($Document->getCell($x,$y))->setFontBold();$x++;
        $Document->setValue( $Document->getCell($x,$y), 'Neu' )->setStyle($Document->getCell($x,$y))->setFontBold();$x++;
        $Document->setValue( $Document->getCell($x,$y), 'Delta' )->setStyle($Document->getCell($x,$y))->setFontBold();$x++;

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x,$y), 'Bezeichnung' )->setStyle($Document->getCell($x,$y))->setFontBold();$x++;
        $Document->setValue( $Document->getCell($x,$y), 'Alt' )->setStyle($Document->getCell($x,$y))->setFontBold();$x++;
        $Document->setValue( $Document->getCell($x,$y), 'Neu' )->setStyle($Document->getCell($x,$y))->setFontBold();$x++;
        $Document->setValue( $Document->getCell($x,$y), 'Delta' )->setStyle($Document->getCell($x,$y))->setFontBold();$x++;

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'BLP in €' );
        $Document->setStyle($Document->getCell('B2'))->setFormatCode( \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
        $Document->setValue( $Document->getCell($x++,$y), number_format($PriceData['Old']['BLP'],2,',','.') );//->setStyle($Document->getCell('B2'))->setFontBold();//setFormatCode(  '#.##0,00' );
//        $Document->setStyle($Document->getCell('B2'))->setFormatCode( \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);


        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['BLP'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['BLP'] );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Bruttoumsatz in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['GrossSales'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['GrossSales'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['GrossSales'] );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'RG-Satz in %' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['BLP'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Discount'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Discount'] );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Anzahl effektiv in Stück' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Quantity'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Quantity'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Quantity'] );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'RG-Satz in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['DiscountEuro'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['DiscountEuro'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['DiscountEuro'] );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'RG-Satz in %' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Discount'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Discount'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Discount'] );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'NLP in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NLP'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['NLP'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['NLP'] );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'RG-Satz in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['TotalDiscountEuro'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['TotalDiscountEuro'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['TotalDiscountEuro'] );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'NLP abzügl. P&M in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NetPricePartsMore'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['NetPricePartsMore'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['NetPricePartsMore'] );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Nettoumsatz in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NetSales'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['NetSales'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['NetSales'] );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'Variable Kosten in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Costs'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Costs'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Costs'] );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Nettoumsatz abzügl. P&M ' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NetSalesPartsMore'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['NetSalesPartsMore'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['NetSalesPartsMore'] );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'Konzern-DB in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['CoverageContribution'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['CoverageContribution'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['CoverageContribution'] );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Variable Kosten' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Costs'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Costs'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Costs'] );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'Konzern-DB abzügl. P&M in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['CoverageContributionPartsMore'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['CoverageContributionPartsMore'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['CoverageContributionPartsMore'] );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Konzern-DB' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['TotalCoverageContribution'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['TotalCoverageContribution'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['TotalCoverageContribution'] );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Konzern-DB abzügl. P&M' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['TotalCoverageContributionPartsMore'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['TotalCoverageContributionPartsMore'] );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['TotalCoverageContributionPartsMore'] );


        $Document->saveFile();
        $FilePointer->loadFile();
        print FileSystem::getDownload($FilePointer->getRealPath(), $FileName.'.'.$FileTyp);
    }
}