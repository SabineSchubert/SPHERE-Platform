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
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['BLP'], PhpExcel::TYPE_NUMERIC );//->setStyle($Document->getCell('B2'))->setFontBold();//setFormatCode(  '#.##0,00' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['BLP'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['BLP'], PhpExcel::TYPE_NUMERIC );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Bruttoumsatz in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['GrossSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['GrossSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['GrossSales'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'RG-Satz in %' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['BLP'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Discount'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Discount'], PhpExcel::TYPE_NUMERIC );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Anzahl effektiv in Stück' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Quantity'], PHPExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Quantity'], PHPExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Quantity'], PHPExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'RG-Satz in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['DiscountEuro'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['DiscountEuro'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['DiscountEuro'], PhpExcel::TYPE_NUMERIC );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'RG-Satz in %' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Discount'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Discount'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Discount'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'NLP in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NLP'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['NLP'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['NLP'], PhpExcel::TYPE_NUMERIC );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'RG-Satz in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['TotalDiscountEuro'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['TotalDiscountEuro'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['TotalDiscountEuro'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'NLP abzügl. P&M in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NetPricePartsMore'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['NetPricePartsMore'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['NetPricePartsMore'], PhpExcel::TYPE_NUMERIC );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Nettoumsatz in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['NetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['NetSales'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'Variable Kosten in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Costs'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Costs'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Costs'], PhpExcel::TYPE_NUMERIC );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Nettoumsatz abzügl. P&M ' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NetSalesPartsMore'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['NetSalesPartsMore'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['NetSalesPartsMore'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'Konzern-DB in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['CoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['CoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['CoverageContribution'], PhpExcel::TYPE_NUMERIC );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Variable Kosten' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Costs'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['Costs'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['Costs'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), 'Konzern-DB abzügl. P&M in €' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['CoverageContributionPartsMore'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['CoverageContributionPartsMore'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['CoverageContributionPartsMore'], PhpExcel::TYPE_NUMERIC );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Konzern-DB' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['TotalCoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['TotalCoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['TotalCoverageContribution'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), '' );

        $Document->setValue( $Document->getCell($x++,$y), 'Konzern-DB abzügl. P&M' );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['TotalCoverageContributionPartsMore'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['TotalCoverageContributionPartsMore'], PhpExcel::TYPE_NUMERIC );
        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Delta']['TotalCoverageContributionPartsMore'], PhpExcel::TYPE_NUMERIC );


        $Document->saveFile();
        $FilePointer->loadFile();
        print FileSystem::getDownload($FilePointer->getRealPath(), $FileName.'.'.$FileTyp);
    }
}