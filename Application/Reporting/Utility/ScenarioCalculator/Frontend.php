<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 26.10.2016
 * Time: 09:13
 */

namespace SPHERE\Application\Reporting\Utility\ScenarioCalculator;

use SPHERE\Application\Api\Reporting\Excel\ExcelScenarioCalculator;
use SPHERE\Application\Api\Reporting\Utility\ScenarioCalculator\ScenarioCalculator As ApiScenarioCalculator;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Common\Frontend\Ajax\Receiver\FieldValueReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Link\Repository\External;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Repository\Title as TableTitle;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class Frontend extends Extension
{
	/** @var null|FieldValueReceiver $ReceiverGrossPrice */
	public $ReceiverGrossPrice = null;

	public function frontendScenarioCalculator($Search = null, $PriceData = null)
	{
		$Stage = new Stage('Szenario-Rechner', 'Simulation von RG-, NLP-, BLP-, Netto- und Brutto-Änderungen');
		$Stage->setMessage('');

		$LayoutGroupPartNumberInformation = '';
		$LayoutGroupScenarioCalculatorForm = '';
		$LayoutGroupScenarioCalculator = '';
		$Excel = '';
		$ErrorPart = '';
		if ($Search) {

		    $EntityPart = DataWareHouse::useService()->getPartByNumber( $Search['PartNumber'] );

			if (!empty($EntityPart)) {

				$LayoutGroupPartNumberInformation = //$this->tablePartNumberInformation();
					new LayoutGroup(
						new LayoutRow(
							new LayoutColumn(
								$this->tablePartNumberInformation( $EntityPart ), 4
							)
						)
						, new Title('Teilenummer-Informationen')
					);


				$Global = $this->getGlobal();

//				if( $PriceData == null ) {
//                    $EntityPrice = $EntityPart->fetchPriceCurrent();
//                    $EntityDiscountGroup = $EntityPrice->getTblReportingDiscountGroup();
//                    $EntityMarketingCode = $EntityPart->fetchMarketingCodeCurrent();
//                    $EntityPartsMore = $EntityMarketingCode->fetchPartsMoreCurrent();
//
//                    $PriceDataOld = array(
//                        'BLP' => $EntityPrice->getPriceGross(),
//                        'DiscountNumber' => $EntityDiscountGroup->getNumber(),
//                        'Discount' => $EntityDiscountGroup->getDiscount(),
//                        'Costs' => $EntityPrice->getCostsVariable(),
//                        'PartsAndMore' => $EntityPartsMore->getValue(),
//                        'PartsAndMoreType' => $EntityPartsMore->getType(),
//                        'Quantity' => 1
//                    );
//                    $PriceData = $PriceDataOld;
//                }

				$Pipeline = ApiScenarioCalculator::pipelineScenarioCalculator($Search, ((!isset($Global->POST['PriceData']))? true:'0'), $PriceData, $EntityPart);
				$FormReceiver = (ApiScenarioCalculator::BlockReceiver())->setIdentifier('FormReceiver');

				$LayoutGroupScenarioCalculatorForm = new LayoutColumn(
					$FormReceiver.$Pipeline, 3
				);



				if($PriceData !== null) {

					$PriceDataSum = $this->setPriceData( $PriceData, $EntityPart );
					Debugger::screenDump($PriceDataSum);

					$LayoutViewingColumn1 =
						new LayoutColumn(
							$this->tablePriceViewing($PriceDataSum), 7
						);
					$LayoutViewingColumn2 =
						new LayoutColumn(
							$this->tableTotalViewing($PriceDataSum), 5
						);

					$Excel = new LayoutColumn(
					    new External('Excel-Download', ExcelScenarioCalculator::getEndpoint(), null, array(
					        ExcelScenarioCalculator::API_TARGET => 'getExcel',
                            'PriceData' => $PriceDataSum
                        )), 8
                    );


				}
				else {
					$LayoutViewingColumn1 = '';
					$LayoutViewingColumn2 = '';
				}

				$LayoutGroupScenarioCalculator =
					new LayoutGroup(
                        new LayoutRow(
                            array(
                                $LayoutGroupScenarioCalculatorForm,
                                new LayoutColumn(
                                    new Layout(
                                        new LayoutGroup(
                                            array(
                                                new LayoutRow(
                                                    array(
                                                        $LayoutViewingColumn1,
                                                        $LayoutViewingColumn2,
                                                    )
                                                ),
                                                new LayoutRow(
                                                    $Excel
                                                )
                                            )
                                        )
                                    ), 9
                                )
                            )
                        )
						, new Title('')
					);


			} else {
                $ErrorPart = new Warning('Die Teilenummer konnte nicht gefunden werden.');
			}
		}



		$Stage->setContent(
			new Layout(array(
				new LayoutGroup(
					new LayoutRow(
						array(
							new LayoutColumn(
								$this->fromSearchPartNumber()
							, 4)
						)
					)
				),
				new LayoutGroup(
				    new LayoutRow(
				        new LayoutColumn( $ErrorPart )
                    )
                ),
				$LayoutGroupPartNumberInformation,
				$LayoutGroupScenarioCalculatorForm,
				$LayoutGroupScenarioCalculator
			))
		);

		return $Stage;
	}

	private function tablePartNumberInformation( TblReporting_Part $EntityPart )
	{
	    $EntityMarketingCode = $EntityPart->fetchMarketingCodeCurrent();

		return new Table(
			array(
				array(
					'Description' => 'Teilenummer',
					'Value' => $EntityPart->getNumber()
				),
				array(
					'Description' => 'Bezeichnung',
					'Value' => $EntityPart->getName()
				),
				array(
					'Description' => 'Marketingcode',
					'Value' => ( ($EntityMarketingCode)? $EntityMarketingCode->getNumber(). ' - ' . $EntityMarketingCode->getName() : '' )
				)
			),
			null,
			array( 'Description' => 'Bezeichnung', 'Value' => '' ),
			array(
				"columnDefs" => array(
			        array('width' => '40%', 'targets' => '0' ),
					array('width' => '60%', 'targets' => '1' )
				),
				"paging"         => false, // Deaktivieren Blättern
			    "iDisplayLength" => -1,    // Alle Einträge zeigen
			    "searching"      => false, // Deaktivieren Suchen
			    "info"           => false,  // Deaktivieren Such-Info
				"sort"           => false   //Deaktivierung Sortierung der Spalten
			)
		);
	}

	private function fromSearchPartNumber()
	{
		return new Form(
			new FormGroup(
				new FormRow(
					array(
						new FormColumn(
							new Panel('Suche', array(
								(new TextField('Search[PartNumber]', 'Teilenummer', 'Teilenummer eingeben', new Search()))
									->setRequired()
							), Panel::PANEL_TYPE_DEFAULT)
						),
					)
				)
			)
			, array(
				new Primary('anzeigen', new Search()),
				new Reset('zurücksetzen')
			)
		);
	}

	private function setPriceData( $PriceData, TblReporting_Part $EntityPart ) {

		$CalcRules = $this->getCalculationRules();
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

		//Berechnungen
		$PriceDataOld['NLP'] = $CalcRules->calcNetPrice( $PriceDataOld['BLP'], $PriceDataOld['Discount'], 0, 0, 0, 0 );
		$PriceDataOld['GrossSales'] = $CalcRules->calcGrossSales( $PriceDataOld['BLP'], $PriceDataOld['Quantity'] );
		$PriceDataOld['NetSales'] = $CalcRules->calcNetSales( $PriceDataOld['NLP'], $PriceDataOld['Quantity'] );

		//var_dump($PriceDataOld);


		$PriceDataNew = null;
		$NetPriceNew = $PriceData['NLP'];//$CalcRules->calcNetPrice( $PriceData['BLP'], $PriceData['Discount'], 0, 0, 0, 0 );
		$QuantityNew = $PriceData['Quantity'];
		$GrossSalesNew = $PriceData['SalesGross'];//$CalcRules->calcGrossSales( $PriceData['BLP'], $QuantityNew);
		$NetSalesNew = $GrossSalesNew - ( $CalcRules->calcDiscount( $PriceData['BLP'], $PriceData['Discount'] )*$PriceData['Quantity'] );

		//var_dump($PriceData);

		$PriceDataNew = array_merge(
			$PriceData,  // aus der DB
			array(       // berechnete Werte
				'NLP' => $NetPriceNew,
				'Quantity' => $QuantityNew,
				'GrossSales' => $GrossSalesNew,
				'NetSales' => $NetSalesNew
			)
		);

		var_dump($PriceDataNew['NetSales'],$PriceDataOld['NetSales'],$PriceDataNew['NetSales']-$PriceDataOld['NetSales']);

		//### Berechnungen ###

        //Delta
        $BLP['Delta'] = $CalcRules->calcDelta( $PriceDataNew['BLP'], $PriceDataOld['BLP'] );
        $Discount['Delta'] = $CalcRules->calcDelta( $PriceDataNew['Discount'], $PriceDataOld['Discount'] );
        $NLP['Delta'] = $CalcRules->calcDelta( $PriceDataNew['NLP'], $PriceDataOld['NLP'] );
        $Costs['Delta'] = $CalcRules->calcDelta( $PriceDataNew['Costs'], $PriceDataOld['Costs'] );
        $GrossSales['Delta'] = $CalcRules->calcDelta( $PriceDataNew['GrossSales'], $PriceDataOld['GrossSales'] );
        $Quantity['Delta'] = $CalcRules->calcDelta( $PriceDataNew['Quantity'], $PriceDataOld['Quantity'] );
        $NetSales['Delta'] = $CalcRules->calcDelta( $PriceDataNew['NetSales'], $PriceDataOld['NetSales'] );

		//Rabatt in Euro
		$DiscountEuro['Old'] = $CalcRules->calcDiscount( $PriceDataOld['BLP'], $PriceDataOld['Discount'] );
		$DiscountEuro['New'] =  $CalcRules->calcDiscount( $PriceDataNew['BLP'], $PriceDataNew['Discount'] );
		$DiscountEuro['Delta'] = $CalcRules->calcDelta( $DiscountEuro['New'], $DiscountEuro['Old'] );

		//NLP abzüglich P+M
		$NetPricePartsMore['Old'] = $CalcRules->calcNetPrice( $PriceDataOld['BLP'], $PriceDataOld['Discount'], 0, $PriceDataOld['PartsAndMore'] );
		$NetPricePartsMore['New'] = $CalcRules->calcNetPrice( $PriceDataNew['BLP'], $PriceDataNew['Discount'], 0, $PriceDataNew['PartsAndMore'] );
		$NetPricePartsMore['Delta'] = $CalcRules->calcDelta( $NetPricePartsMore['New'], $NetPricePartsMore['Old'] );

		//Konzerndeckungsbeitrag ohne Rw, ohne P+M
		$CoverageContribution['Old'] = $CalcRules->calcCoverageContribution( $PriceDataOld['NLP'], $PriceDataOld['Costs'] );
		$CoverageContribution['New'] = $CalcRules->calcCoverageContribution( $PriceDataNew['NLP'], $PriceDataNew['Costs'] );
		$CoverageContribution['Delta'] = $CalcRules->calcDelta( $CoverageContribution['New'], $CoverageContribution['Old'] );

		//Konzerndeckungsbeitrag ohne Rw mit P+M
		$CoverageContributionPartsMore['Old'] = $CalcRules->calcCoverageContribution( $NetPricePartsMore['Old'], $PriceDataOld['Costs'] );
		$CoverageContributionPartsMore['New'] = $CalcRules->calcCoverageContribution( $NetPricePartsMore['New'], $PriceDataNew['Costs'] );
		$CoverageContributionPartsMore['Delta'] = $CalcRules->calcDelta( $CoverageContributionPartsMore['New'], $CoverageContributionPartsMore['Old'] );

		//Nettoumsatz abzüglich P+M
		$NetSalesPartsMore['Old'] = $NetPricePartsMore['Old'] * $PriceDataOld['Quantity'];
		$NetSalesPartsMore['New'] = $NetPricePartsMore['New'] * $PriceDataNew['Quantity'];
		$NetSalesPartsMore['Delta'] = $CalcRules->calcDelta( $NetSalesPartsMore['New'], $NetSalesPartsMore['Old'] );

		//Gesamt-Rabatt in Euro
		$TotalDiscountEuro['Old'] = $DiscountEuro['Old'] * $PriceDataOld['Quantity'];
		$TotalDiscountEuro['New'] = $DiscountEuro['New'] * $PriceDataNew['Quantity'];
		$TotalDiscountEuro['Delta'] = $CalcRules->calcDelta( $TotalDiscountEuro['New'], $TotalDiscountEuro['Old'] );

		//Konzerndeckungsbeitrag ohne Rw, ohne P+M
		$TotalCoverageContribution['Old'] = $CoverageContribution['Old'] * $PriceDataOld['Quantity'];
		$TotalCoverageContribution['New'] = $CoverageContribution['New'] * $PriceDataNew['Quantity'];
		$TotalCoverageContribution['Delta'] = $CalcRules->calcDelta( $TotalCoverageContribution['New'], $TotalCoverageContribution['Old'] );

		//Konzerndeckungsbeitrag ohne Rw mit P+M
		$TotalCoverageContributionPartsMore['Old'] = $CoverageContributionPartsMore['Old'] * $PriceDataOld['Quantity'];
		$TotalCoverageContributionPartsMore['New'] = $CoverageContributionPartsMore['New'] * $PriceDataNew['Quantity'];
		$TotalCoverageContributionPartsMore['Delta'] = $CalcRules->calcDelta( $TotalCoverageContributionPartsMore['New'], $TotalCoverageContributionPartsMore['Old'] );

		$PriceDataNew = array_merge(
			$PriceDataNew,
			array(
				'DiscountEuro' => $DiscountEuro['New'],
				'NetPricePartsMore' => $NetPricePartsMore['New'],
				'CoverageContribution' => $CoverageContribution['New'],
				'CoverageContributionPartsMore' => $CoverageContributionPartsMore['New'],
				'TotalDiscountEuro' => $TotalDiscountEuro['New'],
				'NetSalesPartsMore' => $NetSalesPartsMore['New'],
				'TotalCoverageContribution' => $TotalCoverageContribution['New'],
				'TotalCoverageContributionPartsMore' => $TotalCoverageContributionPartsMore['New']
			)
		);

		$PriceDataOld = array_merge(
			$PriceDataOld,
			array(
				'DiscountEuro' => $DiscountEuro['Old'],
				'NetPricePartsMore' => $NetPricePartsMore['Old'],
				'CoverageContribution' => $CoverageContribution['Old'],
				'CoverageContributionPartsMore' => $CoverageContributionPartsMore['Old'],
				'TotalDiscountEuro' => $TotalDiscountEuro['Old'],
				'NetSalesPartsMore' => $NetSalesPartsMore['Old'],
				'TotalCoverageContribution' => $TotalCoverageContribution['Old'],
				'TotalCoverageContributionPartsMore' => $TotalCoverageContributionPartsMore['Old']
			)
		);

		$PriceDataSum = array_merge(
			array( 'Old' => $PriceDataOld),
			array( 'New' => $PriceDataNew ),
			array( 'Delta' => array(
			        'BLP' => $BLP['Delta'],
			        'Discount' => $Discount['Delta'],
                    'NLP' => $NLP['Delta'],
					'Costs' => $Costs['Delta'],
                    'GrossSales' => $GrossSales['Delta'],
                    'Quantity' => $Quantity['Delta'],
                    'NetSales' => $NetSales['Delta'],
					'DiscountEuro' => $DiscountEuro['Delta'],
					'NetPricePartsMore' => $NetPricePartsMore['Delta'],
					'CoverageContribution' => $CoverageContribution['Delta'],
					'CoverageContributionPartsMore' => $CoverageContributionPartsMore['Delta'],
					'TotalDiscountEuro' => $TotalDiscountEuro['Delta'],
					'NetSalesPartsMore' => $NetSalesPartsMore['Delta'],
					'TotalCoverageContribution' => $TotalCoverageContribution['Delta'],
					'TotalCoverageContributionPartsMore' => $TotalCoverageContributionPartsMore['Delta']
				)
			)
		);

		return $PriceDataSum;
	}


	private function tablePriceViewing( $PriceData ) {
		$CalcRules = $this->getCalculationRules();

		return
			new Table(
				array(
					array(
						'Bezeichnung' => 'BLP in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['BLP'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['BLP'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['BLP'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'RG-Satz in %',
						'Alt' => new PullRight( number_format($PriceData['Old']['Discount'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['Discount'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['Discount'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'RG-Satz in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['DiscountEuro'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['DiscountEuro'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['DiscountEuro'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'NLP in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['NLP'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['NLP'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['NLP'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'NLP abzügl. P&M in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['NetPricePartsMore'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['NetPricePartsMore'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['NetPricePartsMore'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Variable Kosten in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['Costs'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['Costs'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['Costs'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Konzern-DB in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['CoverageContribution'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['CoverageContribution'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['CoverageContribution'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Konzern-DB abzügl. P&M in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['CoverageContributionPartsMore'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['CoverageContributionPartsMore'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['CoverageContributionPartsMore'], 2, ',', '.') )
					)
				),
				new TableTitle('Stückbetrachtung'),
				array('Bezeichnung'=>'Bezeichnung', 'Alt'=>'Alt', 'Neu' => 'Neu', 'Delta' => 'Delta'),
				array(
					"columnDefs" => array(
				        array('width' => '40%', 'targets' => '0' ),
						array('width' => '20%', 'targets' => '1' ),
						array('width' => '20%', 'targets' => '2' ),
						array('width' => '20%', 'targets' => '3' )
					),
					"paging"         => false, // Deaktivieren Blättern
				    "iDisplayLength" => -1,    // Alle Einträge zeigen
				    "searching"      => false, // Deaktivieren Suchen
				    "info"           => false,  // Deaktivieren Such-Info
					"sort"           => false   //Deaktivierung Sortierung der Spalten
				)
		);
	}

	private function tableTotalViewing( $PriceData ) {
		$CalcRules = $this->getCalculationRules();
		$DiscountEuro = array('Old' => 0, 'New' => 0, 'Delta' => 0);
		return
			new Table(
				array(
					array(
						'Bezeichnung' => 'Bruttoumsatz in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['GrossSales'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['GrossSales'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['GrossSales'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Anzeff in Stück',
						'Alt' => new PullRight( number_format($PriceData['Old']['Quantity'], 0, '', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['Quantity'], 0, '', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['Quantity'], 0, '', '.') )
					),
					array(
						'Bezeichnung' => 'RG-Satz in %',
						'Alt' => new PullRight( number_format($PriceData['Old']['Discount'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['Discount'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['Discount'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'RG-Satz in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['TotalDiscountEuro'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['TotalDiscountEuro'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['TotalDiscountEuro'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Nettoumsatz in €',
						'Alt' => new PullRight( number_format($PriceData['Old']['NetSales'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['NetSales'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['NetSales'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Nettoumsatz abzügl. P&M',
						'Alt' => new PullRight( number_format($PriceData['Old']['NetSalesPartsMore'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['NetSalesPartsMore'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['NetSalesPartsMore'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Variable Kosten',
						'Alt' => new PullRight( number_format($PriceData['Old']['Costs'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['Costs'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($CalcRules->calcDelta( $PriceData['New']['Costs'], $PriceData['Old']['Costs'] ), 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Konzern-DB',
						'Alt' => new PullRight( number_format($PriceData['Old']['TotalCoverageContribution'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['TotalCoverageContribution'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['TotalCoverageContribution'], 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Konzern-DB abzügl. P&M',
						'Alt' => new PullRight( number_format($PriceData['Old']['TotalCoverageContributionPartsMore'], 2, ',', '.') ),
						'Neu' => new PullRight( number_format($PriceData['New']['TotalCoverageContributionPartsMore'], 2, ',', '.') ),
						'Delta' => new PullRight( number_format($PriceData['Delta']['TotalCoverageContributionPartsMore'], 2, ',', '.') )
					)
				),
				new TableTitle('Gesamtbetrachtung'),
				array('Bezeichnung'=>'Bezeichnung', 'Alt'=>'Alt ', 'Neu' => 'Neu ', 'Delta' => 'Delta '),
				array(
					"columnDefs" => array(
				        array('width' => '40%', 'targets' => '0' ),
						array('width' => '20%', 'targets' => '1' ),
						array('width' => '20%', 'targets' => '2' ),
						array('width' => '20%', 'targets' => '3' )
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
