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
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\FieldValueReceiver;
use SPHERE\Common\Frontend\Form\Repository\AbstractField;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Button\Standard;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Calculator;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\External;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class ScenarioCalculator extends Extension implements IApiInterface
{
	use ApiTrait;

    /**
     * @param $Search
     * @param bool $preloadPriceData
     * @param null $PriceData
     * @param TblReporting_Part $EntityPart
     * @return Pipeline
     */
	public static function pipelineScenarioCalculator($Search, $preloadPriceData = false, $PriceData = null, TblReporting_Part $EntityPart, $LoadElement = null) {
		$ReceiverForm = ScenarioCalculator::BlockReceiver()->setIdentifier('FormReceiver');

		$Emitter = new ServerEmitter( $ReceiverForm, ScenarioCalculator::getEndpoint() );

		$Emitter->setGetPayload(array(
			ScenarioCalculator::API_TARGET => 'FormContent',
			'ReceiverForm' => $ReceiverForm->getIdentifier(),
			'Search' => $Search,
			'Preload' => $preloadPriceData,
			'PriceData' => $PriceData,
            'PartId' => $EntityPart->getId(),
            'LoadElement' => $LoadElement
		));

		$Pipeline = new Pipeline();
		$Pipeline->appendEmitter($Emitter);

		return $Pipeline;
	}

//	public function calcScenarioCalculator($Receiver) {
//
//        $EntityPrice = $EntityPart->fetchPriceCurrent();
//        $EntityDiscountGroup = $EntityPrice->getTblReportingDiscountGroup();
//        $EntityMarketingCode = $EntityPart->fetchMarketingCodeCurrent();
//        $EntityPartsMore = $EntityMarketingCode->fetchPartsMoreCurrent();
//
//        $PriceData = array(
//            'BLP' => $EntityPrice->getPriceGross(),
//            'DiscountNumber' => $EntityDiscountGroup->getNumber(),
//            'Discount' => $EntityDiscountGroup->getDiscount(),
//            'Costs' => $EntityPrice->getCostsVariable(),
//            'PartsAndMore' => $EntityPartsMore->getValue(),
//            'PartsAndMoreType' => $EntityPartsMore->getType(),
//            'Quantity' => 1
//        );
//
//		$PriceData = array(
//			array('BLP' => 400.00, 'NLP' => 300.00, 'DiscountNumber' => 5, 'Discount' => 10.00, 'Costs' => 40.00, 'PartsAndMore' => 5.00, 'Quantity' => 20, 'SalesGross' => 500, 'SalesNet' => 400)
//		);
//		$PriceData = $PriceData[0];
//
//
//		$Return = null;
//		//var_dump($_GET['Receiver']);
//		switch($Receiver) {
//			case 'PriceData[Discount]':
//				$Return = $PriceData['Discount'];
//				break;
//			case 'PriceData[BLP]':
//				$Return = $PriceData['BLP'];
//				break;
//			case 'PriceData[NLP]':
//				$Return = $PriceData['NLP'];
//				break;
//		}
//
//		return $Return;
//	}

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

	public function setPriceData($PriceData, $LoadElement = null, $PriceDataOld = null) {
		$CalcRules = $this->getCalculationRules();
        $PriceDataNew = array();

		switch ($LoadElement) {
            case 'LoadQuantity':
                $PriceDataNew['Quantity'] = $PriceData['SalesGross']/$PriceData['BLP'];
                break;
            case 'LoadBlpBasePrice':
                if( ((100-$PriceData['Discount'])/100) != 0 ) {
                    $PriceDataNew['BLP'] = $PriceData['NLP']/((100-$PriceData['Discount'])/100);
                }
                break;
            case 'LoadBlpBaseSales':
                if( $PriceData['Quantity'] != 0 ) {
                    $PriceDataNew['BLP'] = $PriceData['SalesGross']/ $PriceData['Quantity'];
                    if( $PriceData['Discount'] == $PriceDataOld['Discount'] ) {
                        $PriceDataNew['NLP'] =  $PriceData['SalesNet'] / $PriceData['Quantity'];
                    }
                    else {
                        $PriceDataNew['NLP'] =  ($PriceDataNew['BLP'] - ( $PriceDataNew['BLP'] * ($PriceData['Discount'] / 100) ));
                    }
                }
                break;
            case 'LoadNlpBasePrice':
                $PriceDataNew['NLP'] = $PriceData['SalesNet'] / $PriceData['Quantity'];
                break;
            case 'LoadNlpBaseSales':
                $PriceDataNew['NLP'] = $PriceData['BLP'] * ( 1 - ($PriceData['Discount'] / 100 ) );
                break;
            case 'LoadNuBasePrice':
                $PriceDataNew['SalesNet'] = $PriceData['NLP'] * $PriceData['Quantity'];
                break;
            case 'LoadNuBaseSales':
                $PriceDataNew['SalesNet'] = $PriceData['SalesGross'] - (( $PriceData['BLP'] * ( $PriceData['Discount'] / 100 ) )) * $PriceData['Quantity'];
                break;
            case 'LoadBuBasePrice':
                $PriceDataNew['SalesGross'] = $PriceData['BLP'] * $PriceData['Quantity'];
                if( $PriceData['Discount'] == $PriceDataOld['Discount'] ) {
                    $PriceDataNew['SalesNet'] = $PriceData['NLP'] * $PriceData['Quantity'];
                }
                else {
                    $PriceDataNew['SalesNet'] = $PriceData['SalesGross'] - (( $PriceData['BLP'] * ( $PriceData['Discount'] / 100 ) )) * $PriceData['Quantity'];
                }
                break;
            case 'LoadBuBaseSales':
                $PriceDataNew['SalesGross'] = $PriceData['SalesNet'] + (( $PriceData['BLP'] * ( $PriceData['Discount'] / 100 ) )) * $PriceData['Quantity'];
                break;
            case 'Start':
                //Berechnungen
                $PriceDataNew['NLP'] = $CalcRules->calcNetPrice( $PriceData['BLP'], $PriceData['Discount'], 0, 0, 0, 0 );
                $PriceDataNew['SalesGross'] = $CalcRules->calcGrossSales( $PriceData['BLP'], $PriceData['Quantity'] );
                $PriceDataNew['SalesNet'] = $CalcRules->calcNetSales( $PriceDataNew['NLP'], $PriceData['Quantity'] );
                break;
        }

		$PriceDataExpanded = array_merge($PriceData, $PriceDataNew);
		return $PriceDataExpanded;
	}

    /**
     * @param $ReceiverForm
     * @param $Search
     * @param $Preload
     * @param $PriceData
     * @param TblReporting_Part $EntityPart
     * @return Form
     */
	public function FormContent($ReceiverForm, $Search, $Preload, $PriceData, $PartId, $LoadElement)
	{
        $EntityPart = DataWareHouse::useService()->getPartById( $PartId );
        $EntityPrice = $EntityPart->fetchPriceCurrent();
        $EntityDiscountGroup = $EntityPrice->getTblReportingDiscountGroup();
        $EntityMarketingCode = $EntityPart->fetchMarketingCodeCurrent();
        $EntityPartsMore = $EntityMarketingCode->fetchPartsMoreCurrent();

        $PriceDataOld = array(
            'BLP' => $EntityPrice->getPriceGross(),
            'DiscountNumber' => $EntityDiscountGroup->getNumber(),
            'Discount' => $EntityDiscountGroup->getDiscount(),
            'Costs' => $EntityPrice->getCostsVariable(),
            'PartsAndMore' => $EntityPartsMore->getValue(),
            'PartsAndMoreType' => $EntityPartsMore->getType(),
            'Quantity' => 1
        );

		$PriceDataOld = ScenarioCalculator::setPriceData($PriceDataOld, 'Start');

		//var_dump($PriceData);

		if($Preload != '0') {
			ScenarioCalculator::preloadPriceData($PriceDataOld);
		}
		elseif($PriceData) {
            ScenarioCalculator::preloadPriceData($PriceDataOld);
			$PriceData = ScenarioCalculator::setPriceData($PriceData, $LoadElement, $PriceDataOld);


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

        $LinkQuantity = new \SPHERE\Common\Frontend\Link\Repository\Standard('berechnen', ScenarioCalculator::getEndpoint() , new Calculator(), array(
            'Receiver' => $ReceiverForm
        ) );

		$LinkBlp['Price'] = new \SPHERE\Common\Frontend\Link\Repository\Standard('Basis Preis', ScenarioCalculator::getEndpoint() , new Calculator(), array(
            'Receiver' => $ReceiverForm
        ) );
		$LinkBlp['Sales'] = new \SPHERE\Common\Frontend\Link\Repository\Standard('Basis Umsatz', ScenarioCalculator::getEndpoint() , new Calculator(), array(
            'Receiver' => $ReceiverForm
        ) );

		$LinkNlp['Price'] = new \SPHERE\Common\Frontend\Link\Repository\Standard('Basis Preis', ScenarioCalculator::getEndpoint() , new Calculator(), array(
            'Receiver' => $ReceiverForm
        ) );
		$LinkNlp['Sales'] = new \SPHERE\Common\Frontend\Link\Repository\Standard('Basis Umsatz', ScenarioCalculator::getEndpoint() , new Calculator(), array(
            'Receiver' => $ReceiverForm
        ) );

		$LinkNu['Price'] = new \SPHERE\Common\Frontend\Link\Repository\Standard('Basis Preis', ScenarioCalculator::getEndpoint() , new Calculator(), array(
            'Receiver' => $ReceiverForm
        ) );
        $LinkNu['Sales'] = new \SPHERE\Common\Frontend\Link\Repository\Standard('Basis Umsatz', ScenarioCalculator::getEndpoint() , new Calculator(), array(
            'Receiver' => $ReceiverForm
        ) );

        $LinkBu['Price'] = new \SPHERE\Common\Frontend\Link\Repository\Standard('Basis Preis', ScenarioCalculator::getEndpoint() , new Calculator(), array(
            'Receiver' => $ReceiverForm
        ) );
        $LinkBu['Sales'] = new \SPHERE\Common\Frontend\Link\Repository\Standard('Basis Umsatz', ScenarioCalculator::getEndpoint() , new Calculator(), array(
            'Receiver' => $ReceiverForm
        ) );

		$FieldGrossPrice = new TextField( 'PriceData[BLP]', null, 'BLP (Alt: '. number_format($PriceDataOld['BLP'],2,',','.').' € )' );
		$FieldNetPrice = new TextField( 'PriceData[NLP]', null, 'NLP (Alt: '. number_format($PriceDataOld['NLP'],2,',','.').' € )' );
		$FieldDiscountNumber = new TextField( 'PriceData[DiscountNumber]', null, 'RG (Alt: '. $PriceDataOld['DiscountNumber'].' )' );
		$FieldDiscount = new TextField( 'PriceData[Discount]', null, 'Rabattsatz (Alt: '. $PriceDataOld['DiscountNumber'].' )' );
		$FieldCosts = new TextField( 'PriceData[Costs]', null, 'variable Kosten (Alt: '. $PriceDataOld['Costs'].' € )' );
		$FieldPartsAndMore = new TextField( 'PriceData[PartsAndMore]', null, 'P&M (Alt: '. $PriceDataOld['PartsAndMore'].' % )' );
		$FieldQuantity = new TextField( 'PriceData[Quantity]', null, 'Menge (Alt: '. $PriceDataOld['Quantity'].' Stk. )' );
		$FieldGrossSale = new TextField( 'PriceData[SalesGross]', null, 'Bruttoumsatz (Alt: '. $PriceDataOld['SalesGross'].' € )' );
		$FieldNetSale = new TextField( 'PriceData[SalesNet]', null, 'Nettoumsatz (Alt: '. $PriceDataOld['SalesNet'].' € )' );

//		$ReceiverGrossPrice = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldGrossPrice );
//		$ReceiverNetPrice = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldNetPrice );
//		$ReceiverDiscount = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldDiscount );
//		$ReceiverDiscountNumber = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldDiscountNumber );
//		$ReceiverCosts = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldCosts );
//		$ReceiverPartsAndMore = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldDiscountNumber );
//		$ReceiverQuantity = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldQuantity );
//		$ReceiverGrossSale = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldGrossSale );
//		$ReceiverNetSale = ScenarioCalculator::FieldValueReceiverScenarioCalculator( $FieldNetSale );


		$PipelineGrossPrice['Price'] = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart, 'LoadBlpBasePrice' );
		$PipelineGrossPrice['Sales'] = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart, 'LoadBlpBaseSales' );
		$PipelineDiscount = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart );
		$PipelineNetPrice['Price'] = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart, 'LoadNlpBasePrice' );
		$PipelineNetPrice['Sales'] = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart, 'LoadNlpBaseSales' );
		$PipelineDiscountNumber = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart );
		$PipelineCosts = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart );
		$PipelinePartsAndMore = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart );
		$PipelineQuantity = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart, 'LoadQuantity' );
		$PipelineGrossSale['Price'] = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart, 'LoadBuBasePrice' );
		$PipelineGrossSale['Sales'] = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart,'LoadBuBaseSales' );
		$PipelineNetSale['Price'] = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart, 'LoadNuBasePrice' );
		$PipelineNetSale['Sales'] = ScenarioCalculator::pipelineScenarioCalculator( $Search, '0', null, $EntityPart,'LoadNuBaseSales' );

		$Per = '03/2017';

		$FormContent =
			(new Form(
				new FormGroup(
					new FormRow(
						new FormColumn(
							new Panel( 'Szenario-Rechner '.$Per, array(
//								(
//									new Layout(
//										new LayoutGroup(
//											new LayoutRow(
//												array(
//													new LayoutColumn('Auswahl der Berechnungsmethode'),
//													new LayoutColumn(new CheckBox('SelectionCalc[NLP]', 'NLP', 'NLP'), 3),
//													new LayoutColumn(new CheckBox('SelectionCalc[NetSale]', 'Nettolistenpreis', 'NetSale'), 3)
//												)
//											)
//										)
//									)
//								),

                                    new Layout(
                                        new LayoutGroup(
                                            new LayoutRow(
                                                array(
                                                    new LayoutColumn(
                                                        $FieldGrossPrice/*->ajaxPipelineOnChange( $PipelineGrossPrice )*/, 7
                                                    ),
                                                    new LayoutColumn(
                                                        array(
                                                            $LinkBlp['Price']->ajaxPipelineOnClick( $PipelineGrossPrice['Price'] ),
                                                            $LinkBlp['Sales']->ajaxPipelineOnClick( $PipelineGrossPrice['Sales'] )
                                                        ), 5
                                                    )
                                                )
                                            )
                                        )
                                    ),
                                    new Layout(
                                        new LayoutGroup(
                                            new LayoutRow(
                                                array(
                                                    new LayoutColumn(
                                                        $FieldNetPrice/*->ajaxPipelineOnChange( $PipelineNetPrice )*/, 7
                                                    ),
                                                    new LayoutColumn(
                                                        array(
                                                            $LinkNlp['Price']->ajaxPipelineOnClick( $PipelineNetPrice['Price'] ),
                                                            $LinkNlp['Sales']->ajaxPipelineOnClick( $PipelineNetPrice['Sales'] )
                                                        ), 5
                                                    )
                                                )
                                            )
                                        )
                                    ),
									$FieldDiscountNumber/*->ajaxPipelineOnChange( $PipelineDiscountNumber )*/,
									$FieldDiscount/*->ajaxPipelineOnChange( $PipelineDiscount )*/,
									$FieldCosts/*->ajaxPipelineOnChange( $PipelineCosts )*/,
									$FieldPartsAndMore/*->ajaxPipelineOnChange( $PipelinePartsAndMore )*/,
                                    new Layout(
                                        new LayoutGroup(
                                            new LayoutRow(
                                                array(
                                                    new LayoutColumn(
                                                        $FieldQuantity/*->ajaxPipelineOnChange( $PipelineQuantity )*/, 7
                                                    ),
                                                    new LayoutColumn(
                                                        $LinkQuantity->ajaxPipelineOnClick( $PipelineQuantity ), 5
                                                    )
                                                )
                                            )
                                        )
                                    ),
                                    new Layout(
                                        new LayoutGroup(
                                            new LayoutRow(
                                                array(
                                                    new LayoutColumn(
                                                        $FieldGrossSale/*->ajaxPipelineOnChange( $PipelineGrossSale )*/, 7
                                                    ),
                                                    new LayoutColumn(
                                                        array(
                                                            $LinkBu['Price']->ajaxPipelineOnClick( $PipelineGrossSale['Price'] ),
                                                            $LinkBu['Sales']->ajaxPipelineOnClick( $PipelineGrossSale['Sales'] )
                                                        ), 5
                                                    )
                                                )
                                            )
                                        )
                                    )
                                    ,new Layout(
                                        new LayoutGroup(
                                            new LayoutRow(
                                                array(
                                                    new LayoutColumn(
                                                        $FieldNetSale/*->ajaxPipelineOnChange( $PipelineNetSale )*/, 7
                                                    ),
                                                    new LayoutColumn(
                                                        array(
                                                            $LinkNu['Price']->ajaxPipelineOnClick( $PipelineNetSale['Price'] ),
                                                            $LinkNu['Sales']->ajaxPipelineOnClick( $PipelineNetSale['Sales'] )
                                                        ), 5
                                                    )
                                                )
                                            )
                                        )
                                    ),
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

//		$Dispatcher->registerMethod('calcScenarioCalculator');
		$Dispatcher->registerMethod('FormContent');
		//$Dispatcher->registerMethod('preloadPriceData');

		return $Dispatcher->callMethod($Method);
	}

}