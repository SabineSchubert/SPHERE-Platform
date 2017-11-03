<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 14.06.2017
 * Time: 14:33
 */

namespace SPHERE\Application\Api\Reporting\Excel;


use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Component\Parameter\Repository\PaperOrientationParameter;
use MOC\V\Component\Document\Document;
use MOC\V\Core\FileSystem\FileSystem;
use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\Api\Reporting\Utility\MultiplyCalculation\MultiplyCalculation;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class ExcelMultiplyCalculation extends Extension implements IApiInterface
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

    public function getExcel($DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId)
    {
        //Debugger::screenDump($_POST);


        $PriceDataAll = array();
        $PartData = DataWareHouse::useService()->getPartById( $PartId );
        $PriceDataAll['DiscountNumber'] = $this->calcPriceData('DiscountNumber', $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId);
        $PriceDataAll['GrossPrice'] = $this->calcPriceData('GrossPrice', $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId);
        $PriceDataAll['NetSale'] = $this->calcPriceData('NetSale', $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId);
        $PriceDataAll['CoverageContribution'] = $this->calcPriceData('CoverageContribution', $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId);


//        Debugger::screenDump($PriceDataAll['NetSale'], $PriceDataAll['CoverageContribution']);
//

        $FileTyp = 'xlsx';
        $FileName = 'Mehrmengenberechnung';
//
        $FilePointer = new FilePointer($FileTyp);
        $FileLocation = $FilePointer->getFileLocation();
        /**
         * @var PhpExcel $Document
         */
        $Document = Document::getDocument($FileLocation);

        $Document->setPaperOrientationParameter( new PaperOrientationParameter( PaperOrientationParameter::ORIENTATION_LANDSCAPE ) );

        $x = 0;
        $y = 0;

        $Document->setStyle( $Document->getCell($x,$y), $Document->getCell(5,$y) )->mergeCells()->setFontBold()->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Mehrmengenberechnung bei Vergabe von Zusatzrabatten bzw. Änderung des BLP' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Bezeichnung' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Retaileingang' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Delta' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Delta' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Bezeichnung' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PartData->getName() );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'BLP/TP' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['GrossPrice'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'RG' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['DiscountNumber'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Rabattsatz' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['Discount'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['New']['Discount'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['Old']['Discount'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'NLP/TP' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['NetPrice'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['New']['NetPrice'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Delta']['NetPrice'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['New']['NetPrice'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['Delta']['NetPrice'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Kosten' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['Costs'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['New']['Costs'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['New']['Costs'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'BU' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['GrossSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['New']['GrossSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Delta']['GrossSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['New']['GrossSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['Delta']['GrossSales'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'NU' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['NetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['New']['NetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Delta']['NetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['New']['NetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['Delta']['NetSales'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Menge aktuell' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['Quantity'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['New']['Quantity'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['New']['Quantity'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), '' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'DB Konzern gesamt' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['TotalCoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['New']['TotalCoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Delta']['TotalCoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['New']['TotalCoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['Delta']['TotalCoverageContribution'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'DB Konzern in %' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['TotalCoverageContributionProportionNetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['New']['TotalCoverageContributionProportionNetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Delta']['TotalCoverageContributionProportionNetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['New']['TotalCoverageContributionProportionNetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['Delta']['TotalCoverageContributionProportionNetSales'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'DB Konzern pro Stück' );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Old']['CoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['New']['CoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['DiscountNumber']['Delta']['CoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['New']['CoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['GrossPrice']['Delta']['CoverageContribution'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y), $Document->getCell(5,$y) )->mergeCells()->setFontBold()->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Mehrmengenberechnung zum Ausgleich des Zusatzrabattes auf Ebene' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y), $Document->getCell(1,$y) )->mergeCells()->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Bezeichnung' );
        $Document->setStyle( $Document->getCell($x,$y) )->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Nettoumsatz' );
        $Document->setStyle( $Document->getCell($x,$y) )->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'DB-Konzern' );
        $Document->setStyle( $Document->getCell($x,$y) )->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Nettoumsatz' );
        $Document->setStyle( $Document->getCell($x,$y) )->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'DB-Konzern' );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y), $Document->getCell(1,$y) )->mergeCells()->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Mehrmenge absolut' );
        $x++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['NU']['MultiplyQuantityNetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['DB']['MultiplyQuantityCoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['NU']['MultiplyQuantityNetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['DB']['MultiplyQuantityCoverageContribution'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y), $Document->getCell(1,$y) )->mergeCells()->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Menge gesamt' );
        $x++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['NU']['TotalMultiplyQuantity'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['DB']['TotalMultiplyQuantity'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['NU']['TotalMultiplyQuantity'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['DB']['TotalMultiplyQuantity'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y), $Document->getCell(1,$y) )->mergeCells()->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Steigerung im Zusatzabsatz' );
        $x++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['NU']['IncreaseAdditionalSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['DB']['IncreaseAdditionalSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['NU']['IncreaseAdditionalSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['DB']['IncreaseAdditionalSales'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y), $Document->getCell(1,$y) )->mergeCells()->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'BU neu' );
        $x++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['NU']['GrossSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['DB']['GrossSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['NU']['GrossSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['DB']['GrossSales'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y), $Document->getCell(1,$y) )->mergeCells()->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'NU neu' );
        $x++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['NU']['NetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['DB']['NetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['NU']['NetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['DB']['NetSales'], PhpExcel::TYPE_NUMERIC );

        $x = 0;
        $y++;
        $Document->setStyle( $Document->getCell($x,$y), $Document->getCell(1,$y) )->mergeCells()->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), 'Mehrmenge absolut' );
        $x++;
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['NU']['MultiplyQuantityAfterNetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['NetSale']['DB']['MultiplyQuantityAfterCoverageContribution'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['NU']['MultiplyQuantityAfterNetSales'], PhpExcel::TYPE_NUMERIC );
        $Document->setStyle( $Document->getCell($x,$y) )->setColumnWidth(-1)->setBorderAll(-1);
        $Document->setValue( $Document->getCell($x++,$y), $PriceDataAll['CoverageContribution']['DB']['MultiplyQuantityAfterCoverageContribution'], PhpExcel::TYPE_NUMERIC );


        //$DataList['New']['NetPrice']



        $Document->saveFile();
        $FilePointer->loadFile();
        //exit();
        print FileSystem::getDownload($FilePointer->getRealPath(), $FileName.'.'.$FileTyp);
    }

//    public static function calcPriceData( $Receiver, $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId ) {
//
////		$PriceData = array(
////			array('GrossPrice' => 150.00, 'DiscountNumber' => 5, 'Discount' => 17.00, 'Costs' => 140.00, 'PartsMore' => 5.00, 'Quantity' => 2)
////		);
//
//        $EntityPart = DataWareHouse::useService()->getPartById( $PartId );
//        $EntityPrice = $EntityPart->fetchPriceCurrent();
//        $EntityDiscountGroup = $EntityPrice->getTblReportingDiscountGroup();
////        $EntityMarketingCode = $EntityPart->fetchMarketingCodeCurrent();
////        $EntityPartsMore = $EntityMarketingCode->fetchPartsMoreCurrent();
//
//        $PriceData['Old'] = array(
//            'GrossPrice' => $EntityPrice->getPriceGross(),
//            'DiscountNumber' => $EntityDiscountGroup->getNumber(),
//            'Discount' => $EntityDiscountGroup->getDiscount(),
//            'Costs' => $EntityPrice->getCostsVariable(),
//            //         'PartsAndMore' => $EntityPartsMore->getValue(),
//            //         'PartsAndMoreType' => $EntityPartsMore->getType(),
//            'Quantity' => 1
//        );
//
//        $PriceData['New'] = $PriceData['Old'];
//
//        $CalcRules = self::getCalculationRules();
//        $PriceData['Old']['NetPrice'] = $CalcRules->calcNetPrice( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Discount'] );
//        $PriceData['Old']['GrossSales'] = $CalcRules->calcGrossSales( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Quantity'] );
//        $PriceData['Old']['NetSales'] = $CalcRules->calcNetSales( $PriceData['Old']['NetPrice'], $PriceData['Old']['Quantity'] );
//        $PriceData['Old']['CoverageContribution'] = $CalcRules->calcCoverageContribution( $PriceData['Old']['NetPrice'], $PriceData['Old']['Costs'] );
//        $PriceData['Old']['TotalCoverageContribution'] = $CalcRules->calcTotalCoverageContribution( $PriceData['Old']['CoverageContribution'], $PriceData['Old']['Quantity'] );
//        $PriceData['Old']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcTotalCoverageContributionProportionNetSales($PriceData['Old']['TotalCoverageContribution'], $PriceData['Old']['NetSales'] );
//
//        switch ($Receiver) {
//            case 'DiscountNumber':
//                if ( $DiscountNumber != $PriceData['Old']['DiscountNumber'] && $DiscountNumber != '' ) {
//                    $EntityDiscountGroupNew = DataWareHouse::useService()->getDiscountGroupByNumber( $DiscountNumber );
//                    $PriceData['New']['DiscountNumber'] = $DiscountNumber;
//                    $PriceData['New']['Discount'] = $EntityDiscountGroupNew->getDiscount();
//                }
//                break;
//            case 'GrossPrice':
//                if ( $GrossPrice != $PriceData['New']['DiscountNumber'] && $GrossPrice != '' ) {
//                    $PriceData['New']['GrossPrice'] = $GrossPrice;
//                }
//                break;
//            case 'NetSale':
//                if ( $DiscountNumber != $PriceData['Old']['DiscountNumber'] && $DiscountNumber != '' ) {
//                    $EntityDiscountGroupNew = DataWareHouse::useService()->getDiscountGroupByNumber( $DiscountNumber );
//                    $PriceData['New']['DiscountNumber'] = $DiscountNumber;
//                    $PriceData['New']['Discount'] = $EntityDiscountGroupNew->getDiscount();
//                }
//                break;
//            case 'CoverageContribution':
////				if ( $DiscountNumber != $PriceData['Old']['DiscountNumber'] && $DiscountNumber != '' ) {
////					$PriceData['New']['DiscountNumber'] = $DiscountNumber;
////					$PriceData['New']['Discount'] = '20';
////				}
//                if ( $GrossPrice != $PriceData['New']['DiscountNumber'] && $GrossPrice != '' ) {
//                    $PriceData['New']['GrossPrice'] = $GrossPrice;
//                }
//                break;
//        }
//
//        $PriceData['New']['NetPrice'] = $CalcRules->calcNetPrice( $PriceData['New']['GrossPrice'], $PriceData['New']['Discount'] );
//        $PriceData['New']['GrossSales'] = $CalcRules->calcGrossSales( $PriceData['New']['GrossPrice'], $PriceData['New']['Quantity'] );
//        $PriceData['New']['NetSales'] = $CalcRules->calcNetSales( $PriceData['New']['NetPrice'], $PriceData['New']['Quantity'] );
//        $PriceData['New']['CoverageContribution'] = $CalcRules->calcCoverageContribution( $PriceData['New']['NetPrice'], $PriceData['New']['Costs'] );
//        $PriceData['New']['TotalCoverageContribution'] = $CalcRules->calcTotalCoverageContribution( $PriceData['New']['CoverageContribution'], $PriceData['New']['Quantity'] );
//        $PriceData['New']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcTotalCoverageContributionProportionNetSales($PriceData['New']['TotalCoverageContribution'], $PriceData['New']['NetSales'] );
//
//        //Delta ...
//        $PriceData['Delta']['NetPrice'] = $CalcRules->calcDelta( $PriceData['New']['NetPrice'], $PriceData['Old']['NetPrice'] );
//        $PriceData['Delta']['GrossSales'] = $CalcRules->calcDelta( $PriceData['New']['GrossSales'], $PriceData['Old']['GrossSales'] );
//        $PriceData['Delta']['NetSales'] = $CalcRules->calcDelta( $PriceData['New']['NetSales'], $PriceData['Old']['NetSales'] );
//        $PriceData['Delta']['CoverageContribution'] = $CalcRules->calcDelta( $PriceData['New']['CoverageContribution'], $PriceData['Old']['CoverageContribution'] );
//        $PriceData['Delta']['TotalCoverageContribution'] = $CalcRules->calcDelta( $PriceData['New']['TotalCoverageContribution'], $PriceData['Old']['TotalCoverageContribution'] );
//        $PriceData['Delta']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcDelta( $PriceData['New']['TotalCoverageContributionProportionNetSales'], $PriceData['Old']['TotalCoverageContributionProportionNetSales'] );
//
//        //auf Basis Nettoumsatz ...
//        $PriceData['NU']['MultiplyQuantityNetSales'] = $CalcRules->calcMultiplyQuantityNetSales( $PriceData['Delta']['NetSales'], $PriceData['New']['NetPrice'] );
//        $PriceData['NU']['TotalMultiplyQuantity'] = $CalcRules->calcTotalMultiplyQuantityNetSale( $PriceData['NU']['MultiplyQuantityNetSales'], $PriceData['New']['Quantity'] );
//        $PriceData['NU']['IncreaseAdditionalSales'] = $CalcRules->calcIncreaseAdditionalSales( $PriceData['NU']['MultiplyQuantityNetSales'], $PriceData['Old']['Quantity'] );
//        $PriceData['NU']['GrossSales'] = $CalcRules->calcGrossSalesWithMultiplyQuantity( $PriceData['New']['GrossPrice'], $PriceData['NU']['TotalMultiplyQuantity'] );
//        $PriceData['NU']['NetSales'] = $CalcRules->calcNetSalesWithMultiplyQuantity( $PriceData['New']['NetPrice'], $PriceData['NU']['TotalMultiplyQuantity'] );
//
//        //Mehrmenge nach NU-Veränderung
//        $PriceData['NU']['MultiplyQuantityAfterNetSales'] = $CalcRules->calcMultiplyQuantityAfterNetSales( $PriceData['NU']['NetSales'], $NetSale, $PriceData['New']['NetPrice'], $PriceData['NU']['MultiplyQuantityNetSales'] );
//
//
//        //auf Basis Deckungsbeitrag ...
//        $PriceData['DB']['MultiplyQuantityCoverageContribution'] = $CalcRules->calcMultiplyQuantityCoverageContribution( $PriceData['Old']['TotalCoverageContribution'], $PriceData['New']['CoverageContribution'], $PriceData['Old']['Quantity'] );
//        $PriceData['DB']['TotalMultiplyQuantity'] = $CalcRules->calcTotalMultiplyQuantityCoverageContribution( $PriceData['Old']['TotalCoverageContribution'], $PriceData['New']['CoverageContribution'] );
//        $PriceData['DB']['IncreaseAdditionalSales'] = $CalcRules->calcIncreaseAdditionalSales( $PriceData['DB']['MultiplyQuantityCoverageContribution'], $PriceData['Old']['Quantity'] );
//        $PriceData['DB']['GrossSales'] = $CalcRules->calcGrossSalesWithMultiplyQuantity( $PriceData['New']['GrossPrice'], $PriceData['DB']['TotalMultiplyQuantity'] );
//        $PriceData['DB']['NetSales'] = $CalcRules->calcNetSalesWithMultiplyQuantity( $PriceData['New']['NetPrice'], $PriceData['DB']['TotalMultiplyQuantity'] );
//
//        //Mehrmenge nach DB-Veränderung
//        $PriceData['DB']['MultiplyQuantityAfterCoverageContribution'] = $CalcRules->calcMultiplyQuantityAfterCoverageContribution( $PriceData['Old']['TotalCoverageContribution'], $CoverageContribution, $PriceData['New']['CoverageContribution'], $PriceData['DB']['MultiplyQuantityCoverageContribution'] );
//
//        return $PriceData;
//    }

    public function calcPriceData( $Receiver, $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId ) {

   //		$PriceData = array(
   //			array('GrossPrice' => 150.00, 'DiscountNumber' => 5, 'Discount' => 17.00, 'Costs' => 140.00, 'PartsMore' => 5.00, 'Quantity' => 2)
   //		);
           $EntityPart = DataWareHouse::useService()->getPartById( $PartId );
           $EntityPrice = $EntityPart->fetchPriceCurrent();
           $EntityDiscountGroup = $EntityPrice->getTblReportingDiscountGroup();
   //        $EntityMarketingCode = $EntityPart->fetchMarketingCodeCurrent();
   //        $EntityPartsMore = $EntityMarketingCode->fetchPartsMoreCurrent();

           $PriceData['Old'] = array(
               'GrossPrice' => $EntityPrice->getPriceGross(),
               'DiscountNumber' => $EntityDiscountGroup->getNumber(),
               'Discount' => $EntityDiscountGroup->getDiscount(),
               'Costs' => $EntityPrice->getCostsVariable(),
               //         'PartsAndMore' => $EntityPartsMore->getValue(),
               //         'PartsAndMoreType' => $EntityPartsMore->getType(),
   //            'Quantity' => 1
           );

   		$PriceData['New'] = $PriceData['Old'];

   		$CalcRules = $this->getCalculationRules();
   		$DataSales = DataWareHouse::useService()->getSalesByPart( $EntityPart );
   //		Debugger::screenDump($DataSales[0]['Data_SumQuantity']);

   		$PriceData['Old']['NetPrice'] = $CalcRules->calcNetPrice( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Discount'] );
           $PriceData['Old']['Quantity'] = $DataSales[0]['Data_SumQuantity'];
           $PriceData['New']['Quantity'] = $DataSales[0]['Data_SumQuantity'];
   		$PriceData['Old']['GrossSales'] = $CalcRules->calcGrossSales( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Quantity'] );
   		$PriceData['Old']['NetSales'] = $CalcRules->calcNetSales( $PriceData['Old']['NetPrice'], $PriceData['Old']['Quantity'] );
   		$PriceData['Old']['CoverageContribution'] = $CalcRules->calcCoverageContribution( $PriceData['Old']['NetPrice'], $PriceData['Old']['Costs'] );
   		$PriceData['Old']['TotalCoverageContribution'] = $CalcRules->calcTotalCoverageContribution( $PriceData['Old']['CoverageContribution'], $PriceData['Old']['Quantity'] );
   		$PriceData['Old']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcTotalCoverageContributionProportionNetSales($PriceData['Old']['TotalCoverageContribution'], $PriceData['Old']['NetSales'] );

   		switch ($Receiver) {
   			case 'DiscountNumber':
   				if ( $DiscountNumber != $PriceData['Old']['DiscountNumber'] && $DiscountNumber != '' ) {
                       $EntityDiscountGroupNew = DataWareHouse::useService()->getDiscountGroupByNumber( $DiscountNumber );
                       if($EntityDiscountGroupNew) {
                           $PriceData['New']['DiscountNumber'] = $DiscountNumber;
                           $PriceData['New']['Discount'] = $EntityDiscountGroupNew->getDiscount();
                       }
                       else {
                           $PriceData['New']['DiscountNumber'] = $PriceData['Old']['DiscountNumber'];
                           $PriceData['New']['Discount'] = $PriceData['Old']['Discount'];
                       }
   				}
   				break;
   			case 'GrossPrice':
   				if ( $GrossPrice != $PriceData['New']['DiscountNumber'] && $GrossPrice != '' ) {
   					$PriceData['New']['GrossPrice'] = $GrossPrice;
   				}
   				break;
   			case 'NetSale':
   				if ( $DiscountNumber != $PriceData['Old']['DiscountNumber'] && $DiscountNumber != '' ) {
                       $EntityDiscountGroupNew = DataWareHouse::useService()->getDiscountGroupByNumber( $DiscountNumber );
                       if($EntityDiscountGroupNew) {
                           $PriceData['New']['DiscountNumber'] = $DiscountNumber;
                           $PriceData['New']['Discount'] = $EntityDiscountGroupNew->getDiscount();
                       }
                       else {
                           $PriceData['New']['DiscountNumber'] = $PriceData['Old']['DiscountNumber'];
                           $PriceData['New']['Discount'] = $PriceData['Old']['Discount'];
                       }
   				}
   				break;
   			case 'CoverageContribution':
   //				if ( $DiscountNumber != $PriceData['Old']['DiscountNumber'] && $DiscountNumber != '' ) {
   //					$PriceData['New']['DiscountNumber'] = $DiscountNumber;
   //					$PriceData['New']['Discount'] = '20';
   //				}
   				if ( $GrossPrice != $PriceData['New']['DiscountNumber'] && $GrossPrice != '' ) {
   					$PriceData['New']['GrossPrice'] = $GrossPrice;
   				}
   				break;
   		}

   //		Debugger::screenDump($PriceData['New']['GrossPrice']);

   		$PriceData['New']['NetPrice'] = $CalcRules->calcNetPrice( $PriceData['New']['GrossPrice'], $PriceData['New']['Discount'] );
   		$PriceData['New']['GrossSales'] = $CalcRules->calcGrossSales( $PriceData['New']['GrossPrice'], $PriceData['Old']['Quantity'] );

   //		Debugger::screenDump($PriceData['New']['GrossSales']);
   		$PriceData['New']['NetSales'] = $CalcRules->calcNetSales( $PriceData['New']['NetPrice'], $PriceData['Old']['Quantity'] );
   		$PriceData['New']['CoverageContribution'] = $CalcRules->calcCoverageContribution( $PriceData['New']['NetPrice'], $PriceData['New']['Costs'] );
   		$PriceData['New']['TotalCoverageContribution'] = $CalcRules->calcTotalCoverageContribution( $PriceData['New']['CoverageContribution'], $PriceData['Old']['Quantity'] );
   		$PriceData['New']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcTotalCoverageContributionProportionNetSales($PriceData['New']['TotalCoverageContribution'], $PriceData['New']['NetSales'] );

   		//Delta ...
   		$PriceData['Delta']['NetPrice'] = $CalcRules->calcDelta( $PriceData['New']['NetPrice'], $PriceData['Old']['NetPrice'] );
   		$PriceData['Delta']['GrossSales'] = $CalcRules->calcDelta( $PriceData['New']['GrossSales'], $PriceData['Old']['GrossSales'] );
   		$PriceData['Delta']['NetSales'] = $CalcRules->calcDelta( $PriceData['New']['NetSales'], $PriceData['Old']['NetSales'] );
   		$PriceData['Delta']['CoverageContribution'] = $CalcRules->calcDelta( $PriceData['New']['CoverageContribution'], $PriceData['Old']['CoverageContribution'] );
   		$PriceData['Delta']['TotalCoverageContribution'] = $CalcRules->calcDelta( $PriceData['New']['TotalCoverageContribution'], $PriceData['Old']['TotalCoverageContribution'] );
   		$PriceData['Delta']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcDelta( $PriceData['New']['TotalCoverageContributionProportionNetSales'], $PriceData['Old']['TotalCoverageContributionProportionNetSales'] );

   		//auf Basis Nettoumsatz ...
   		$PriceData['NU']['MultiplyQuantityNetSales'] = $CalcRules->calcMultiplyQuantityNetSales( $PriceData['Delta']['NetSales'], $PriceData['New']['NetPrice'] );
   		$PriceData['NU']['TotalMultiplyQuantity'] = $CalcRules->calcTotalMultiplyQuantityNetSale( $PriceData['NU']['MultiplyQuantityNetSales'], $PriceData['New']['Quantity'] );
   		$PriceData['NU']['IncreaseAdditionalSales'] = $CalcRules->calcIncreaseAdditionalSales( $PriceData['NU']['MultiplyQuantityNetSales'], $PriceData['Old']['Quantity'] );
   		$PriceData['NU']['GrossSales'] = $CalcRules->calcGrossSalesWithMultiplyQuantity( $PriceData['New']['GrossPrice'], $PriceData['NU']['TotalMultiplyQuantity'] );
   		$PriceData['NU']['NetSales'] = $CalcRules->calcNetSalesWithMultiplyQuantity( $PriceData['New']['NetPrice'], $PriceData['NU']['TotalMultiplyQuantity'] );

   		//Mehrmenge nach NU-Veränderung
   		$PriceData['NU']['MultiplyQuantityAfterNetSales'] = $CalcRules->calcMultiplyQuantityAfterNetSales( $PriceData['NU']['NetSales'], $NetSale, $PriceData['New']['NetPrice'], $PriceData['NU']['MultiplyQuantityNetSales'] );


   		//auf Basis Deckungsbeitrag ...
   		$PriceData['DB']['MultiplyQuantityCoverageContribution'] = $CalcRules->calcMultiplyQuantityCoverageContribution( $PriceData['Old']['TotalCoverageContribution'], $PriceData['New']['CoverageContribution'], $PriceData['Old']['Quantity'] );
   		$PriceData['DB']['TotalMultiplyQuantity'] = $CalcRules->calcTotalMultiplyQuantityCoverageContribution( $PriceData['Old']['TotalCoverageContribution'], $PriceData['New']['CoverageContribution'] );
   		$PriceData['DB']['IncreaseAdditionalSales'] = $CalcRules->calcIncreaseAdditionalSales( $PriceData['DB']['MultiplyQuantityCoverageContribution'], $PriceData['Old']['Quantity'] );
   		$PriceData['DB']['GrossSales'] = $CalcRules->calcGrossSalesWithMultiplyQuantity( $PriceData['New']['GrossPrice'], $PriceData['DB']['TotalMultiplyQuantity'] );
   		$PriceData['DB']['NetSales'] = $CalcRules->calcNetSalesWithMultiplyQuantity( $PriceData['New']['NetPrice'], $PriceData['DB']['TotalMultiplyQuantity'] );

   		//Mehrmenge nach DB-Veränderung
   		$PriceData['DB']['MultiplyQuantityAfterCoverageContribution'] = $CalcRules->calcMultiplyQuantityAfterCoverageContribution( $PriceData['Old']['TotalCoverageContribution'], $CoverageContribution, $PriceData['New']['CoverageContribution'], $PriceData['DB']['MultiplyQuantityCoverageContribution'] );

   		return $PriceData;
   	}
}