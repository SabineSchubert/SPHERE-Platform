<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 01.03.2017
 * Time: 15:42
 */

namespace SPHERE\Application\Api\Reporting\Utility\MultiplyCalculation;


use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\Api\Reporting\Excel\ExcelMultiplyCalculation;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Form\Repository\AbstractField;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Link\Repository\External;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Bold;
use SPHERE\Common\Frontend\Text\Repository\Danger;
use SPHERE\Common\Frontend\Text\Repository\Success;
use SPHERE\Common\Frontend\Layout\Repository\Label\Danger as DangerMessage;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class MultiplyCalculation extends Extension implements IApiInterface
{
	use ApiTrait;

	public static function pipelineMultiplyCalculation( AbstractReceiver $Receiver, AbstractField $Field = null, TblReporting_Part $EntityPart, AbstractReceiver $ExcelReceiver ) {
		$Emitter = new ServerEmitter( $Receiver, MultiplyCalculation::getEndpoint() );
		$Emitter->setGetPayload(array(
			MultiplyCalculation::API_TARGET => 'calcMultiplyCalculation',
			'Receiver' => $Receiver->getIdentifier(),
            'PartId' => $EntityPart->getId()
		));

		$Pipeline = new Pipeline(false);
		$Pipeline->appendEmitter($Emitter);

		if( $Field != null ) {
			$Receiver2 = MultiplyCalculation::Receiver()->setIdentifier($Field->getName());
			$Emitter2 = new ServerEmitter( $Receiver2, MultiplyCalculation::getEndpoint() );
			$Emitter2->setGetPayload(array(
				MultiplyCalculation::API_TARGET => 'calcMultiplyCalculation',
				'Receiver'  => $Receiver2->getIdentifier(),
                'PartId' => $EntityPart->getId()
			));
			$Pipeline->appendEmitter($Emitter2);
		}

		$ExcelEmitter = new ServerEmitter( $ExcelReceiver, MultiplyCalculation::getEndpoint() );
        $ExcelEmitter->setGetPayload(array(
            MultiplyCalculation::API_TARGET => 'calcMultiplyCalculation',
            'Receiver'  => $ExcelReceiver->getIdentifier(),
            'PartId' => $EntityPart->getId(),
        ));
        $Pipeline->appendEmitter($ExcelEmitter);


		return $Pipeline;
	}

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

	public function calcMultiplyCalculation( $Receiver, $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId, $AllPriceData ) {

        //in berechenbare Zahl wandeln
        $GrossPrice = str_replace( ',', '.', str_replace('.','', $GrossPrice) );
        $NetSale = str_replace( ',', '.', str_replace('.','', $NetSale) );
        $CoverageContribution = str_replace( ',', '.', str_replace('.','', $CoverageContribution) );

		$PriceData = $this->calcPriceData( $Receiver, $DiscountNumber, $GrossPrice, $NetSale, $CoverageContribution, $PartId );

		switch ($Receiver) {
			case 'DiscountNumber':
                $AllPriceData['DiscountNumber'] = $PriceData;
				return MultiplyCalculation::tableAllocationChanceDiscount( $PriceData );
				break;
			case 'GrossPrice':
                $AllPriceData['GrossPrice'] = $PriceData;
				return MultiplyCalculation::tableAllocationChanceGrossPrice( $PriceData );
				break;
			case 'NetSale':
                $AllPriceData['NetSale'] = $PriceData;
				return MultiplyCalculation::tableBalancingChanceDiscount( $PriceData );
				break;
			case 'CoverageContribution':
                $AllPriceData['CoverageContribution'] = $PriceData;
				return MultiplyCalculation::tableBalancingChanceGrossPrice( $PriceData );
				break;
			case 'ExcelDownload':
                $ExcelReceiver = new External('ExcelDownload', ExcelMultiplyCalculation::getEndpoint(), null, array(
                    ExcelMultiplyCalculation::API_TARGET => 'getExcel',
                    'DataList' => $AllPriceData
                ) );
				return $ExcelReceiver;
				break;
		}
	}

	public static function Receiver( $Content = null ) {
		$Receiver = new BlockReceiver( $Content );
		return $Receiver;
	}

	public function exportApi($Method = '')
	{
		$Dispatcher = new Dispatcher(__CLASS__);

		$Dispatcher->registerMethod('calcMultiplyCalculation');

		return $Dispatcher->callMethod($Method);
	}

	private function tableAllocationChanceDiscount( $PriceData )
	{
		return
			new Table(array(
				array(
					'New1' => '&nbsp;',
					'Delta1' => '&nbsp;'
				),
				array(
					'New1' => '&nbsp;',
					'Delta1' => '&nbsp;'
				),
				array(
					'New1' => '&nbsp;',
					'Delta1' => '&nbsp;'
				),
				array(
					'New1' => new PullRight(number_format($PriceData['New']['Discount'], 2, ',', '.') . ' %'),
					'Delta1' => ''
				),
				array(
					'New1' => new PullRight(number_format($PriceData['New']['NetPrice'], 2, ',', '.') . ' €'),
					'Delta1' => (($PriceData['Delta']['NetPrice'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['NetPrice'], 2, ',', '.') . ' €' ) ) ):
						new PullRight( number_format($PriceData['Delta']['NetPrice'], 2, ',', '.') . ' €' )
					)
				),
				array(
					'New1' => new PullRight(number_format($PriceData['New']['Costs'], 2, ',', '.') . ' €'),
					'Delta1' => '',
				),
				array(
					'New1' => new PullRight(number_format($PriceData['New']['GrossSales'], 2, ',', '.') . ' €'),
					'Delta1' => (($PriceData['Delta']['GrossSales'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['GrossSales'], 2, ',', '.') . ' €' ) ) ):
						new PullRight( number_format($PriceData['Delta']['GrossSales'], 2, ',', '.') . ' €')
					)
				),
				array(
					'New1' => new PullRight(number_format($PriceData['New']['NetSales'], 2, ',', '.') . ' €'),
					'Delta1' => (($PriceData['Delta']['NetSales'] < 0)?
						new PullRight( new Bold( new Bold( new Danger( number_format($PriceData['Delta']['NetSales'], 2, ',', '.') . ' €' ) ) ) ):
						new PullRight( number_format($PriceData['Delta']['NetSales'], 2, ',', '.') . ' €')
					)
				),
				array(
					'New1' => new PullRight($PriceData['New']['Quantity'] . ' Stk.'),
					'Delta1' => '',
				),
				array(
					'New1' => new PullRight(number_format($PriceData['New']['TotalCoverageContribution'], 2, ',', '.') . ' €'),
					'Delta1' => (($PriceData['Delta']['TotalCoverageContribution'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['TotalCoverageContribution'], 2, ',', '.') . ' €' ) ) ):
						new PullRight( number_format($PriceData['Delta']['TotalCoverageContribution'], 2, ',', '.') . ' €' )
					)
				),
				array(
					'New1' => new PullRight(number_format($PriceData['New']['TotalCoverageContributionProportionNetSales'], 2, ',', '.') . ' %'),
					'Delta1' => (($PriceData['Delta']['TotalCoverageContributionProportionNetSales'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['TotalCoverageContributionProportionNetSales'], 2, ',', '.') . ' %' ) ) ):
						new PullRight( number_format($PriceData['Delta']['TotalCoverageContributionProportionNetSales'], 2, ',', '.') . ' %' )
					)
				),
				array(
					'New1' => new PullRight(number_format($PriceData['New']['CoverageContribution'], 2, ',', '.') . ' €'),
					'Delta1' => (($PriceData['Delta']['CoverageContribution'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['CoverageContribution'], 2, ',', '.') . ' €' ) ) ):
						new PullRight(number_format($PriceData['Delta']['CoverageContribution'], 2, ',', '.') . ' €')
					)
				)
			),
				null,
				array('New1' => 'Retaileingang', 'Delta1' => 'Delta'),
				array(
					"columnDefs" => array(
						array('width' => '1%', 'targets' => 0),
						array('width' => '1%', 'targets' => 1)
					),
					"paging" => false, // Deaktivieren Blättern
					"iDisplayLength" => -1,    // Alle Einträge zeigen
					"searching" => false, // Deaktivieren Suchen
					"info" => false,  // Deaktivieren Such-Info
					"sort" => false   //Deaktivierung Sortierung der Spalten
				)
			);
	}

	private function tableAllocationChanceGrossPrice( $PriceData )
	{
		return
			new Table(array(
				array(
					'New2' => '&nbsp;',
					'Delta2' => '&nbsp;'
				),
				array(
					'New2' => '&nbsp;',
					'Delta2' => '&nbsp;'
				),
				array(
					'New2' => new PullRight( $PriceData['Old']['DiscountNumber'] ),
					'Delta2' => '&nbsp;'
				),
				array(
					'New2' => new PullRight(number_format($PriceData['Old']['Discount'], 2, ',', '.') . ' %'),
					'Delta2' => ''
				),
				array(
					'New2' => new PullRight(number_format($PriceData['New']['NetPrice'], 2, ',', '.') . ' €'),
					'Delta2' => (($PriceData['Delta']['NetPrice'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['NetPrice'], 2, ',', '.') . ' €' ) ) ):
						new PullRight( number_format($PriceData['Delta']['NetPrice'], 2, ',', '.') . ' €' )
					)
				),
				array(
					'New2' => new PullRight(number_format($PriceData['Old']['Costs'], 2, ',', '.') . ' €'),
					'Delta2' => ''
				),
				array(
					'New2' => new PullRight(number_format($PriceData['New']['GrossSales'], 2, ',', '.') . ' €'),
					'Delta2' => (($PriceData['Delta']['GrossSales'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['GrossSales'], 2, ',', '.') . ' €' ) ) ):
						new PullRight( number_format($PriceData['Delta']['GrossSales'], 2, ',', '.') . ' €')
					)
				),
				array(
					'New2' => new PullRight(number_format($PriceData['New']['NetSales'], 2, ',', '.') . ' €'),
					'Delta2' => (($PriceData['Delta']['NetSales'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['NetSales'], 2, ',', '.') . ' €' ) ) ):
						new PullRight( number_format($PriceData['Delta']['NetSales'], 2, ',', '.') . ' €')
					)
				),
				array(
					'New2' => new PullRight($PriceData['Old']['Quantity'] . ' Stk.'),
					'Delta2' => ''
				),
				array(
					'New2' => new PullRight(number_format($PriceData['New']['TotalCoverageContribution'], 2, ',', '.') . ' €'),
					'Delta2' => (($PriceData['Delta']['TotalCoverageContribution'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['TotalCoverageContribution'], 2, ',', '.') . ' €' ) ) ):
						new PullRight( number_format($PriceData['Delta']['TotalCoverageContribution'], 2, ',', '.') . ' €' )
					)
				),
				array(
					'New2' => new PullRight(number_format($PriceData['New']['TotalCoverageContributionProportionNetSales'], 2, ',', '.') . ' %'),
					'Delta2' => (($PriceData['Delta']['TotalCoverageContributionProportionNetSales'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['TotalCoverageContributionProportionNetSales'], 2, ',', '.') . ' %' ) ) ):
						new PullRight( number_format($PriceData['Delta']['TotalCoverageContributionProportionNetSales'], 2, ',', '.') . ' %' )
					)
				),
				array(
					'New2' => new PullRight(number_format($PriceData['New']['CoverageContribution'], 2, ',', '.') . ' €'),
					'Delta2' => (($PriceData['Delta']['CoverageContribution'] < 0)?
						new PullRight( new Bold( new Danger( number_format($PriceData['Delta']['CoverageContribution'], 2, ',', '.') . ' €' ) ) ):
						new PullRight(number_format($PriceData['Delta']['CoverageContribution'], 2, ',', '.') . ' €')
					)
				)
			),
				null,
				array('New2' => '&nbsp;', 'Delta2' => 'Delta'),
				array(
					"columnDefs" => array(
						array('width' => '1%', 'targets' => 0),
						array('width' => '1%', 'targets' => 1)
					),
					"paging" => false, // Deaktivieren Blättern
					"iDisplayLength" => -1,    // Alle Einträge zeigen
					"searching" => false, // Deaktivieren Suchen
					"info" => false,  // Deaktivieren Such-Info
					"sort" => false   //Deaktivierung Sortierung der Spalten
				)
			);
	}

	private function tableBalancingChanceDiscount( $PriceData ) {

		return
				new Table(array(
					array(
						'NU1' => new PullRight( number_format( $PriceData['NU']['MultiplyQuantityNetSales'], 0, '', '.' ). ' Stk.' ),
						'DB1' => new PullRight( number_format( $PriceData['DB']['MultiplyQuantityCoverageContribution'], 0, '', '.' ). ' Stk.' )
					),
					array(
						'NU1' => new PullRight( number_format( $PriceData['NU']['TotalMultiplyQuantity'], 0, '', '.' ). ' Stk.' ),
						'DB1' => new PullRight( number_format( $PriceData['DB']['TotalMultiplyQuantity'], 0, '', '.' ). ' Stk.' ),
					),
					array(
						'NU1' => new PullRight( number_format( $PriceData['NU']['IncreaseAdditionalSales'], 0, '', '.' ) ),
						'DB1' => new PullRight( number_format( $PriceData['DB']['IncreaseAdditionalSales'], 0, '', '.' ) )
					),
					array(
						'NU1' => new PullRight( number_format( $PriceData['NU']['GrossSales'], 2, ',', '.' ). ' €' ),
						'DB1' => new PullRight( number_format( $PriceData['DB']['GrossSales'], 2, ',', '.' ). ' €' )
					),
					array(
						'NU1' => new PullRight( number_format( $PriceData['NU']['NetSales'], 2, ',', '.' ). ' €' ),
						'DB1' => new PullRight( number_format( $PriceData['DB']['NetSales'], 2, ',', '.' ). ' €' )
					),
					array(
						'NU1' => new Bold( new PullRight( number_format( $PriceData['NU']['MultiplyQuantityAfterNetSales'], 0, '', '.' ).' Stk.' ) ),
						'DB1' => new Bold( new PullRight( number_format( $PriceData['DB']['MultiplyQuantityAfterCoverageContribution'], 0, '', '.' ).' Stk.' ) )
					)
				),
				null,
				array('NU1'=>'Nettoumsatz ', 'DB1'=>'DB-Konzern'),
				array(
					"columnDefs" => array(
						array( 'width' => '1%', 'targets' => 0 ),
						array( 'width' => '1%', 'targets' => 1 )
					),
					"paging"         => false, // Deaktivieren Blättern
				    "iDisplayLength" => -1,    // Alle Einträge zeigen
				    "searching"      => false, // Deaktivieren Suchen
				    "info"           => false,  // Deaktivieren Such-Info
					"sort"           => false   //Deaktivierung Sortierung der Spalten
				)
			);
	}

	private function tableBalancingChanceGrossPrice( $PriceData ) {

		return
			new Table(array(
				array(
					'NU2' => new PullRight( number_format( $PriceData['NU']['MultiplyQuantityNetSales'], 0, '', '.' ). ' Stk.' ),
					'DB2' => new PullRight( number_format( $PriceData['DB']['MultiplyQuantityCoverageContribution'], 0, '', '.' ). ' Stk.' )
				),
				array(
					'NU2' => new PullRight( number_format( $PriceData['NU']['TotalMultiplyQuantity'], 0, '', '.' ). ' Stk.' ),
					'DB2' => new PullRight( number_format( $PriceData['DB']['TotalMultiplyQuantity'], 0, '', '.' ). ' Stk.' ),
				),
				array(
					'NU2' => new PullRight( number_format( $PriceData['NU']['IncreaseAdditionalSales'], 0, '', '.' ) ),
					'DB2' => new PullRight( number_format( $PriceData['DB']['IncreaseAdditionalSales'], 0, '', '.' ) )
				),
				array(
					'NU2' => new PullRight( number_format( $PriceData['NU']['GrossSales'], 2, ',', '.' ). ' €' ),
					'DB2' => new PullRight( number_format( $PriceData['DB']['GrossSales'], 2, ',', '.' ). ' €' )
				),
				array(
					'NU2' => new PullRight( number_format( $PriceData['NU']['NetSales'], 2, ',', '.' ). ' €' ),
					'DB2' => new PullRight( number_format( $PriceData['DB']['NetSales'], 2, ',', '.' ). ' €' )
				),
				array(
					'NU2' => new Bold( new PullRight( number_format( $PriceData['NU']['MultiplyQuantityAfterNetSales'], 0, '', '.' ).' Stk.' ) ),
					'DB2' => new Bold( new PullRight( number_format( $PriceData['DB']['MultiplyQuantityAfterCoverageContribution'], 0, '', '.' ).' Stk.' ) )
				)
			),
			null,
			array('NU2'=>'Nettoumsatz', 'DB2'=>'DB-Konzern'),
			array(
				"columnDefs" => array(
					array( 'width' => '1%', 'targets' => 0 ),
					array( 'width' => '1%', 'targets' => 1 )
				),
				"paging"         => false, // Deaktivieren Blättern
			    "iDisplayLength" => -1,    // Alle Einträge zeigen
			    "searching"      => false, // Deaktivieren Suchen
			    "info"           => false,  // Deaktivieren Such-Info
				"sort"           => false   //Deaktivierung Sortierung der Spalten
			)
		);
	}
}