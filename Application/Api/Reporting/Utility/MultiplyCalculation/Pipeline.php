<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 16.06.2017
 * Time: 10:06
 */

namespace SPHERE\Application\Api\Reporting\Utility\MultiplyCalculation;


use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Document;
use MOC\V\Component\Documentation\Component\Bridge\Repository\ApiGen;
use MOC\V\Core\FileSystem\FileSystem;
use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\Api\Reporting\Excel\ExcelMultiplyCalculation;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Link\Repository\External;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;
use SPHERE\System\Proxy\Proxy;

class Pipeline extends Extension implements IApiInterface
{
    use ApiTrait;

    public static function pipelineExcel( AbstractReceiver $ReceiverExcel, $DiscountNumber = null, $GrossPrice = null, $NetSale = null, $CoverageContribution = null, $PartId = null) {
        $ReceiverForm = self::BlockReceiver(null,'FormReceiver');

        $Emitter = new ServerEmitter( self::BlockReceiver(null, 'Content'), self::getEndpoint() );
        //$Emitter = new ServerEmitter( self::BlockReceiver()->getIdentifier('Excel'), self::getEndpoint() );

        $Emitter->setGetPayload(array(
            self::API_TARGET => 'getExcel',
            'ReceiverForm' => $ReceiverForm->getIdentifier(),
            'DiscountNumber' => $DiscountNumber,
            'GrossPrice' => $GrossPrice,
            'NetSale' => $NetSale,
            'CoverageContribution' => $CoverageContribution,
            'PartId' => $PartId
        ));

        $Pipeline = new \SPHERE\Common\Frontend\Ajax\Pipeline();
        $Pipeline->appendEmitter($Emitter);


        return $Pipeline;
    }

    public function getExcel($ReceiverForm, $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId)
    {
        $PriceData = $this->calcPriceData('DiscountNumber', $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId);

//        $FileTyp = 'xlsx';
//        $FileName = 'Mehrmengenberechnung';
//
//        $FilePointer = new FilePointer($FileTyp);
//        $FileLocation = $FilePointer->getFileLocation();
//        /**
//         * @var PhpExcel $Document
//         */
//        $Document = Document::getDocument($FileLocation);
//
//        $x = 0;
//        $y = 0;
//
//        $Document->setValue( $Document->getCell($x++,$y), 'Mehrmengenberechnung bei Vergabe von Zusatzrabatten bzw. Änderung des BLP' );
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
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['DiscountNumber'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Rabattsatz' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Discount'] );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['New']['RG']['Discount'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'NLP/TP' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NetPrice'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Kosten' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Costs'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'BU' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['GrossSales'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'NU' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['NetSales'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'Menge aktuell' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['Quantity'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'DB Konzern gesamt' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['TotalCoverageContribution'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'DB Konzern in %' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['TotalCoverageContributionProportionNetSales'] );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//        $Document->setValue( $Document->getCell($x++,$y), '' );
//
//        $x = 0;
//        $y++;
//        $Document->setValue( $Document->getCell($x++,$y), 'DB Konzern pro Stück' );
//        $Document->setValue( $Document->getCell($x++,$y), $PriceData['Old']['CoverageContribution'] );
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


        //$DataList['New']['NetPrice']



//        $Document->saveFile();
//        $FilePointer->loadFile();

        //exit();
        //Debugger::screenDump($FilePointer->getFileLocation());
        //return new External('Test', $FilePointer);
//        print FileSystem::getDownload($FilePointer->getRealPath(), $FileName.'.'.$FileTyp)->__toString();
        return new External('Excel-Download', ExcelMultiplyCalculation::getEndpoint(), null, array(
            ExcelMultiplyCalculation::API_TARGET => 'getExcel'
        ) );
    }

    public function calcPriceData( $Receiver, $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId ) {

        $EntityPart = DataWareHouse::useService()->getPartById( $PartId );
        $EntityPrice = $EntityPart->fetchPriceCurrent();
        $EntityDiscountGroup = $EntityPrice->getTblReportingDiscountGroup();

        $PriceData['Old'] = array(
            'GrossPrice' => $EntityPrice->getPriceGross(),
            'DiscountNumber' => $EntityDiscountGroup->getNumber(),
            'Discount' => $EntityDiscountGroup->getDiscount(),
            'Costs' => $EntityPrice->getCostsVariable(),
            'Quantity' => 1
        );

        $PriceData['New']['RG'] = $PriceData['Old'];
        $PriceData['New']['Preis'] = $PriceData['Old'];

        $CalcRules = $this->getCalculationRules();
        $PriceData['Old']['NetPrice'] = $CalcRules->calcNetPrice( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Discount'] );
        $PriceData['Old']['GrossSales'] = $CalcRules->calcGrossSales( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Quantity'] );
        $PriceData['Old']['NetSales'] = $CalcRules->calcNetSales( $PriceData['Old']['NetPrice'], $PriceData['Old']['Quantity'] );
        $PriceData['Old']['CoverageContribution'] = $CalcRules->calcCoverageContribution( $PriceData['Old']['NetPrice'], $PriceData['Old']['Costs'] );
        $PriceData['Old']['TotalCoverageContribution'] = $CalcRules->calcTotalCoverageContribution( $PriceData['Old']['CoverageContribution'], $PriceData['Old']['Quantity'] );
        $PriceData['Old']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcTotalCoverageContributionProportionNetSales($PriceData['Old']['TotalCoverageContribution'], $PriceData['Old']['NetSales'] );

        if ( $DiscountNumber != $PriceData['Old']['DiscountNumber'] && $DiscountNumber != '' ) {
            $EntityDiscountGroupNew = DataWareHouse::useService()->getDiscountGroupByNumber( $DiscountNumber );
            $PriceData['New']['RG']['DiscountNumber'] = $DiscountNumber;
            $PriceData['New']['RG']['Discount'] = $EntityDiscountGroupNew->getDiscount();
        }
        else {
            $PriceData['New']['RG']['DiscountNumber'] = $PriceData['Old']['DiscountNumber'];
            $PriceData['New']['RG']['Discount'] = $PriceData['Old']['Discount'];
        }

        $PriceData['New']['RG']['NetPrice'] = $CalcRules->calcNetPrice( $PriceData['New']['RG']['GrossPrice'], $PriceData['New']['RG']['Discount'] );
        $PriceData['New']['RG']['GrossSales'] = $CalcRules->calcGrossSales( $PriceData['New']['RG']['GrossPrice'], $PriceData['New']['RG']['Quantity'] );
        $PriceData['New']['RG']['NetSales'] = $CalcRules->calcNetSales( $PriceData['New']['RG']['NetPrice'], $PriceData['New']['RG']['Quantity'] );
        $PriceData['New']['RG']['CoverageContribution'] = $CalcRules->calcCoverageContribution( $PriceData['New']['RG']['NetPrice'], $PriceData['New']['RG']['Costs'] );
        $PriceData['New']['RG']['TotalCoverageContribution'] = $CalcRules->calcTotalCoverageContribution( $PriceData['New']['RG']['CoverageContribution'], $PriceData['New']['RG']['Quantity'] );
        $PriceData['New']['RG']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcTotalCoverageContributionProportionNetSales($PriceData['New']['RG']['TotalCoverageContribution'], $PriceData['New']['RG']['NetSales'] );

        //Delta ...
        $PriceData['Delta']['RG']['NetPrice'] = $CalcRules->calcDelta( $PriceData['New']['RG']['NetPrice'], $PriceData['Old']['NetPrice'] );
        $PriceData['Delta']['RG']['GrossSales'] = $CalcRules->calcDelta( $PriceData['New']['RG']['GrossSales'], $PriceData['Old']['GrossSales'] );
        $PriceData['Delta']['RG']['NetSales'] = $CalcRules->calcDelta( $PriceData['New']['RG']['NetSales'], $PriceData['Old']['NetSales'] );
        $PriceData['Delta']['RG']['CoverageContribution'] = $CalcRules->calcDelta( $PriceData['New']['RG']['CoverageContribution'], $PriceData['Old']['CoverageContribution'] );
        $PriceData['Delta']['RG']['TotalCoverageContribution'] = $CalcRules->calcDelta( $PriceData['New']['RG']['TotalCoverageContribution'], $PriceData['Old']['TotalCoverageContribution'] );
        $PriceData['Delta']['RG']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcDelta( $PriceData['New']['RG']['TotalCoverageContributionProportionNetSales'], $PriceData['Old']['TotalCoverageContributionProportionNetSales'] );

        if ( $GrossPrice != $PriceData['New']['RG']['DiscountNumber'] && $GrossPrice != '' ) {
           $PriceData['New']['Preis']['GrossPrice'] = $GrossPrice;
        }

        $PriceData['New']['Preis']['NetPrice'] = $CalcRules->calcNetPrice( $PriceData['New']['Preis']['GrossPrice'], $PriceData['New']['Preis']['Discount'] );
        $PriceData['New']['Preis']['GrossSales'] = $CalcRules->calcGrossSales( $PriceData['New']['Preis']['GrossPrice'], $PriceData['New']['Preis']['Quantity'] );
        $PriceData['New']['Preis']['NetSales'] = $CalcRules->calcNetSales( $PriceData['New']['Preis']['NetPrice'], $PriceData['New']['Preis']['Quantity'] );
        $PriceData['New']['Preis']['CoverageContribution'] = $CalcRules->calcCoverageContribution( $PriceData['New']['Preis']['NetPrice'], $PriceData['New']['Preis']['Costs'] );
        $PriceData['New']['Preis']['TotalCoverageContribution'] = $CalcRules->calcTotalCoverageContribution( $PriceData['New']['Preis']['CoverageContribution'], $PriceData['New']['Preis']['Quantity'] );
        $PriceData['New']['Preis']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcTotalCoverageContributionProportionNetSales($PriceData['New']['Preis']['TotalCoverageContribution'], $PriceData['New']['Preis']['NetSales'] );

        //Delta ...
        $PriceData['Delta']['Preis']['NetPrice'] = $CalcRules->calcDelta( $PriceData['New']['Preis']['NetPrice'], $PriceData['Old']['NetPrice'] );
        $PriceData['Delta']['Preis']['GrossSales'] = $CalcRules->calcDelta( $PriceData['New']['Preis']['GrossSales'], $PriceData['Old']['GrossSales'] );
        $PriceData['Delta']['Preis']['NetSales'] = $CalcRules->calcDelta( $PriceData['New']['Preis']['NetSales'], $PriceData['Old']['NetSales'] );
        $PriceData['Delta']['Preis']['CoverageContribution'] = $CalcRules->calcDelta( $PriceData['New']['Preis']['CoverageContribution'], $PriceData['Old']['CoverageContribution'] );
        $PriceData['Delta']['Preis']['TotalCoverageContribution'] = $CalcRules->calcDelta( $PriceData['New']['Preis']['TotalCoverageContribution'], $PriceData['Old']['TotalCoverageContribution'] );
        $PriceData['Delta']['Preis']['TotalCoverageContributionProportionNetSales'] = $CalcRules->calcDelta( $PriceData['New']['Preis']['TotalCoverageContributionProportionNetSales'], $PriceData['Old']['TotalCoverageContributionProportionNetSales'] );

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

        return $PriceData;
    }

    /**
     * @param null $Content
     * @param $Identifier
     * @return BlockReceiver
     */
    public static function BlockReceiver($Content = null,$Identifier) {
   		return (new BlockReceiver($Content))->setIdentifier($Identifier);
   	}

    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('getExcel');

        return $Dispatcher->callMethod($Method);
    }
}