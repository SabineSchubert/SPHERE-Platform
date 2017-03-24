<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 01.03.2017
 * Time: 15:45
 */

namespace SPHERE\Application\Api\Reporting\Utility\ScenarioCalculator;


use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\FieldValueReceiver;
use SPHERE\Common\Frontend\Form\Repository\AbstractField;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\System\Extension\Extension;

class ScenarioCalculator extends Extension implements IApiInterface
{
	use ApiTrait;

	public static function pipelineScenarioCalculator($Search, $preloadPriceData = false, $PriceData = null) {
		$ReceiverForm = ScenarioCalculator::BlockReceiver()->setIdentifier('FormReceiver');

		$Emitter = new ServerEmitter( $ReceiverForm, ScenarioCalculator::getEndpoint() );
		$Emitter->setGetPayload(array(
			ScenarioCalculator::API_TARGET => 'FormContent',
			'Receiver' => $ReceiverForm->getIdentifier(),
			'Search' => $Search,
			'Preload' => $preloadPriceData,
			'PriceData' => $PriceData
		));

		$Pipeline = new Pipeline();
		$Pipeline->appendEmitter($Emitter);

		return $Pipeline;
	}

	public function calcScenarioCalculator($Receiver) {
		$PriceData = array(
			array('BLP' => 400.00, 'NLP' => 300.00, 'DiscountNumber' => 5, 'Discount' => 10.00, 'Costs' => 40.00, 'PartsAndMore' => 5.00, 'Quantity' => 20, 'SalesGross' => 500, 'SalesNet' => 400)
		);
		$PriceData = $PriceData[0];


		$Return = null;
		//var_dump($_GET['Receiver']);
		switch($Receiver) {
			case 'PriceData[Discount]':
				$Return = $PriceData['Discount'];
				break;
			case 'PriceData[BLP]':
				$Return = $PriceData['BLP'];
				break;
			case 'PriceData[NLP]':
				$Return = $PriceData['NLP'];
				break;
		}

		return $Return;
	}

	public static function FieldValueReceiverScenarioCalculator( AbstractField $Field ) {
		$FieldValueReceiverScenarioCalculator = (new FieldValueReceiver( $Field ))->setIdentifier($Field->getName());
		return $FieldValueReceiverScenarioCalculator;
	}

	public static function BlockReceiver() {
		return new BlockReceiver();
	}

	public function preloadPriceData( $PriceData )
	{
		$Global = $this->getGlobal();

		$Global->POST['PriceData']['BLP'] = $PriceData['BLP'];
		$Global->POST['PriceData']['NLP'] = $PriceData['NLP'];
		$Global->POST['PriceData']['DiscountNumber'] = $PriceData['DiscountNumber'];
		$Global->POST['PriceData']['Discount'] = $PriceData['Discount'];
		$Global->POST['PriceData']['Costs'] = $PriceData['Costs'];
		$Global->POST['PriceData']['PartsAndMore'] = $PriceData['PartsAndMore'];
		$Global->POST['PriceData']['Quantity'] = $PriceData['Quantity'];
		$Global->POST['PriceData']['SalesGross'] = $PriceData['SalesGross'];
		$Global->POST['PriceData']['SalesNet'] = $PriceData['SalesNet'];

		$Global->savePost();
	}

	public function setPriceData($PriceData) {
		$CalcRules = $this->getCalculationRules();

		//Berechnungen
		$PriceDataNew['NLP'] = $CalcRules->calcNetPrice( $PriceData['BLP'], $PriceData['Discount'], 0, 0, 0, 0 );
		$PriceDataNew['SalesGross'] = $CalcRules->calcGrossSales( $PriceData['BLP'], $PriceData['Quantity'] );
		$PriceDataNew['SalesNet'] = $CalcRules->calcNetSales( $PriceDataNew['NLP'], $PriceData['Quantity'] );

		//var_dump($PriceData);

		$PriceDataExpanded = array_merge($PriceData, $PriceDataNew);
		return $PriceDataExpanded;
	}

	public function FormContent($ReceiverForm, $Search, $Preload, $PriceData)
	{
		$PriceDataOld = array(
			array('BLP' => 150.00, 'DiscountNumber' => 5, 'Discount' => 17.00, 'Costs' => 40.00, 'PartsAndMore' => 5.00, 'Quantity' => 1)
		);
		$PriceDataOld = $PriceDataOld[0];
		$PriceDataOld = ScenarioCalculator::setPriceData($PriceDataOld);

		//var_dump($PriceData);

		if($Preload != '0') {
			ScenarioCalculator::preloadPriceData($PriceDataOld);
		}
		elseif($PriceData) {
			$PriceData = ScenarioCalculator::setPriceData($PriceData);


			//var_dump($PriceData);

			$BLP = $PriceData['BLP'];
			$NLP =  $PriceData['NLP'];
			$DiscountNumber =  $PriceData['DiscountNumber'];
			$Discount =  $PriceData['Discount'];
			$Costs = $PriceData['Costs'];
			$PartsAndMore = $PriceData['PartsAndMore'];
			$Quantity = $PriceData['Quantity'];
			$SaleGross = $PriceData['SalesGross'];
			$SaleNet = $PriceData['SalesNet'];

			//CalculationRules::

			$PriceData = array(
				'BLP' => $BLP,
				'NLP' => $NLP,
				'DiscountNumber' => $DiscountNumber,
				'Discount' => $Discount,
				'Costs' => $Costs,
				'PartsAndMore' => $PartsAndMore,
				'Quantity' => $Quantity,
				'SalesGross' => $SaleGross,
				'SalesNet' => $SaleNet
			);
			ScenarioCalculator::preloadPriceData($PriceData);

		}



		$FieldGrossPrice = new TextField( 'PriceData[BLP]', null, 'BLP (Alt: '. number_format($PriceDataOld['BLP'],2,',','.').' € )' );
		$FieldNetPrice = new TextField( 'PriceData[NLP]', null, 'NLP (Alt: '. number_format($PriceDataOld['NLP'],2,',','.').' € )' );
		$FieldDiscountNumber = new TextField( 'PriceData[DiscountNumber]', null, 'RG (Alt: '. $PriceDataOld['DiscountNumber'].' )' );
		$FieldDiscount = new TextField( 'PriceData[Discount]', null, 'Rabattsatz (Alt: '. $PriceDataOld['DiscountNumber'].' )' );
		$FieldCosts = new TextField( 'PriceData[Costs]', null, 'variable Kosten (Alt: '. $PriceDataOld['Costs'].' € )' );
		$FieldPartsAndMore = new TextField( 'PriceData[PartsAndMore]', null, 'P&M (Alt: '. $PriceDataOld['PartsAndMore'].' % )' );
		$FieldQuantity = new TextField( 'PriceData[Quantity]', null, 'Menge (Alt: '. $PriceDataOld['Quantity'].' Stk. )' );
		$FieldGrossSale = new TextField( 'PriceData[SalesGross]', null, 'Bruttoumsatz (Alt: '. $PriceDataOld['SalesGross'].' € )' );
		$FieldNetSale = new TextField( 'PriceData[SalesNet]', null, 'Nettoumsatz (Alt: '. $PriceDataOld['SalesNet'].' € )' );

		$ReceiverGrossPrice = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldGrossPrice );
		$ReceiverNetPrice = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldNetPrice );
		$ReceiverDiscount = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldDiscount );
		$ReceiverDiscountNumber = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldDiscountNumber );
		$ReceiverCosts = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldCosts );
		$ReceiverPartsAndMore = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldDiscountNumber );
		$ReceiverQuantity = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldQuantity );
		$ReceiverGrossSale = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldGrossSale );
		$ReceiverNetSale = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldNetSale );


		$PipelineGrossPrice = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0' );
		$PipelineDiscount = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0' );
		$PipelineNetPrice = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0' );
		$PipelineDiscountNumber = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0' );
		$PipelineCosts = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0' );
		$PipelinePartsAndMore = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0' );
		$PipelineQuantity = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0' );
		$PipelineGrossSale = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0' );
		$PipelineNetSale = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0' );

		$Per = '03/2017';

		$FormContent =
			(new Form(
				new FormGroup(
					new FormRow(
						new FormColumn(
							new Panel( 'Szenario-Rechner '.$Per, array(
								(
									new Layout(
										new LayoutGroup(
											new LayoutRow(
												array(
													new LayoutColumn('Auswahl der Berechnungsmethode'),
													new LayoutColumn(new CheckBox('SelectionCalc[NLP]', 'NLP', 'NLP'), 3),
													new LayoutColumn(new CheckBox('SelectionCalc[NetSale]', 'Nettolistenpreis', 'NetSale'), 3)
												)
											)
										)
									)
								),
									$FieldGrossPrice->ajaxPipelineOnChange( $PipelineGrossPrice ),
									$FieldNetPrice->ajaxPipelineOnChange( $PipelineNetPrice ),
									$FieldDiscountNumber->ajaxPipelineOnChange( $PipelineDiscountNumber ),
									$FieldDiscount->ajaxPipelineOnChange( $PipelineDiscount ),
									$FieldCosts->ajaxPipelineOnChange( $PipelineCosts ),
									$FieldPartsAndMore->ajaxPipelineOnChange( $PipelinePartsAndMore ),
									$FieldQuantity->ajaxPipelineOnChange( $PipelineQuantity ),
									$FieldGrossSale->ajaxPipelineOnChange( $PipelineGrossSale ),
									$FieldNetSale->ajaxPipelineOnChange( $PipelineNetSale ),
								), Panel::PANEL_TYPE_DEFAULT
							)
						)
					)
				),
				array(
					new Primary('berechnen'),
					new Reset('zurücksetzen')
				),
				new Route('SPHERE\Application\Reporting\Controlling\ScenarioCalculator'),
				array('Search' => $Search)
			)
		);
		return $FormContent;
	}


	public function exportApi($Method = '')
	{
		$Dispatcher = new Dispatcher(__CLASS__);

		$Dispatcher->registerMethod('calcScenarioCalculator');
		$Dispatcher->registerMethod('FormContent');
		//$Dispatcher->registerMethod('preloadPriceData');

		return $Dispatcher->callMethod($Method);
	}

}