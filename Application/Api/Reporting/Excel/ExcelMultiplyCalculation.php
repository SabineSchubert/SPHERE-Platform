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

    public static function getExcel($DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId)
    {
        //Debugger::screenDump($_POST);
//        Debugger::screenDump($DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution);

//        $PriceDataAll = array();
//        $PriceDataAll['DiscountNumber'] = self::calcPriceData('DiscountNumber', $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId);
//        $PriceDataAll['GrossPrice'] = self::calcPriceData('GrossPrice', $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId);
//        $PriceDataAll['NetSale'] = self::calcPriceData('NetSale', $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId);
//        $PriceDataAll['CoverageContribution'] = self::calcPriceData('CoverageContribution', $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId);
//
//        Debugger::screenDump($PriceDataAll);

        $FileTyp = 'xlsx';
        $FileName = 'Mehrmengenberechnung';
//
        $FilePointer = new FilePointer($FileTyp);
        $FileLocation = $FilePointer->getFileLocation();
        /**
         * @var PhpExcel $Document
         */
        $Document = Document::getDocument($FileLocation);

        $x = 0;
        $y = 0;

        $Document->setValue( $Document->getCell($x++,$y), 'Mehrmengenberechnung bei Vergabe von Zusatzrabatten bzw. Änderung des BLP' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Bezeichnung' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), 'Retaileingang' );
//        $Document->setValue( $Document->getCell($x++,$y), 'Delta' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), 'Delta' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Bezeichnung' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'BLP/TP' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['GrossPrice'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'RG' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['DiscountNumber'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Rabattsatz' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['Discount'] );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['New']['Discount'] );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Delta']['Discount'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'NLP/TP' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['NetPrice'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Kosten' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['Costs'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'BU' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['GrossSales'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'NU' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['NetSales'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Menge aktuell' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['Quantity'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'DB Konzern gesamt' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['TotalCoverageContribution'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'DB Konzern in %' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['TotalCoverageContributionProportionNetSales'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'DB Konzern pro Stück' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['DiscountNumber']['Old']['CoverageContribution'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Mehrmengenberechnung zum Ausgleich des Zusatzrabattes auf Ebene' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Bezeichnung' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), 'Nettoumsatz' );
//        $Document->setValue( $Document->getCell($x++,$y), 'DB-Konzern' );
//        $Document->setValue( $Document->getCell($x++,$y), 'Nettoumsatz' );
//        $Document->setValue( $Document->getCell($x++,$y), 'DB-Konzern' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Mehrmenge absolut' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Menge gesamt' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Steigerung im Zusatzabsatz' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'BU neu' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'NU neu' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Mehrmenge absolut' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//
//        //$DataList['New']['NetPrice']
//
//
//
        $Document->saveFile();
        $FilePointer->loadFile();
        //exit();
        print FileSystem::getDownload($FilePointer->getRealPath(), $FileName.'.'.$FileTyp);
    }

    public static function calcPriceData( $Receiver, $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId ) {

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
            'Quantity' => 1
        );

        $PriceData['New'] = $PriceData['Old'];

        $CalcRules = self::getCalculationRules();
        $PriceData['Old']['NetPrice'] = $CalcRules->calcNetPrice( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Discount'] );
        $PriceData['Old']['GrossSales'] = $CalcRules->calcGrossSales( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Quantity'] );
        $PriceData['Old']['NetSales'] = $CalcRules->calcNetSales( $PriceData['Old']['NetPrice'], $PriceData['Old']['Quantity'] );
        $PriceData['Old']['CoverageContribution'] = $CalcRules->calcCoverageContribution( $PriceData['Old']['NetPrice'], $PriceData['Old']['Costs'] );
        $PriceData['Old']['TotalCoverageContribution'] = $CalcRules->calcTotalCoverageContribution( $PriceData['Old']['CoverageContribution'], $PriceData['Old']['Quantity'] );
        $PriceData['Old']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcTotalCoverageContributionProportionNetSales($PriceData['Old']['TotalCoverageContribution'], $PriceData['Old']['NetSales'] );

        switch ($Receiver) {
            case 'DiscountNumber':
                if ( $DiscountNumber != $PriceData['Old']['DiscountNumber'] && $DiscountNumber != '' ) {
                    $EntityDiscountGroupNew = DataWareHouse::useService()->getDiscountGroupByNumber( $DiscountNumber );
                    $PriceData['New']['DiscountNumber'] = $DiscountNumber;
                    $PriceData['New']['Discount'] = $EntityDiscountGroupNew->getDiscount();
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
                    $PriceData['New']['DiscountNumber'] = $DiscountNumber;
                    $PriceData['New']['Discount'] = $EntityDiscountGroupNew->getDiscount();
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

        $PriceData['New']['NetPrice'] = $CalcRules->calcNetPrice( $PriceData['New']['GrossPrice'], $PriceData['New']['Discount'] );
        $PriceData['New']['GrossSales'] = $CalcRules->calcGrossSales( $PriceData['New']['GrossPrice'], $PriceData['New']['Quantity'] );
        $PriceData['New']['NetSales'] = $CalcRules->calcNetSales( $PriceData['New']['NetPrice'], $PriceData['New']['Quantity'] );
        $PriceData['New']['CoverageContribution'] = $CalcRules->calcCoverageContribution( $PriceData['New']['NetPrice'], $PriceData['New']['Costs'] );
        $PriceData['New']['TotalCoverageContribution'] = $CalcRules->calcTotalCoverageContribution( $PriceData['New']['CoverageContribution'], $PriceData['New']['Quantity'] );
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