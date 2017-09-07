<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 13.03.2017
 * Time: 09:32
 */

namespace SPHERE\Application\Reporting\Utility\MultiplyCalculation;

use SPHERE\Application\Api\Reporting\Excel\ExcelMultiplyCalculation;
use SPHERE\Application\Api\Reporting\Utility\MultiplyCalculation\MultiplyCalculation as ApiMultiplyCalculation;
use SPHERE\Application\Api\Reporting\Utility\MultiplyCalculation\Pipeline;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Button\Standard;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\External;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Bold;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class Frontend extends Extension
{
	public function frontendMultiplyCalculation( $Search = null )
	{
		$Stage = new Stage('Mehrmengenberechnung');
		$Stage->setMessage('');

		$Form = '';
		$LayoutAllocation = '';
		$LayoutBalancing = '';
		$LayoutExcel = '';
        $ErrorPart = '';
		if ($Search) {

		    $EntityPart = DataWareHouse::useService()->getPartByNumber( $Search['PartNumber'] );

			if (!empty($EntityPart)) {

				//$this->preloadPriceData();

				//FormField
				$DiscountNumberField = new TextField('DiscountNumber', 'Rabattnummer', 'Rabatt-Änderung');
				$GrossPriceField = new TextField('GrossPrice', 'BLP', 'BLP-Änderung');
				$NetSaleField = new TextField('NetSale', 'Steigerung NU', 'Steigerung NU');
				$CoverageContributionField = new TextField('CoverageContribution', 'Steigerung DB', 'Steigerung DB');

				//Receiver
				$TableAllocationChanceDiscountReceiver = (ApiMultiplyCalculation::Receiver())->setIdentifier( $DiscountNumberField->getName() );
				$TableAllocationChanceGrossPriceReceiver = (ApiMultiplyCalculation::Receiver()->setIdentifier($GrossPriceField->getName()));
				$TableBalancingChanceDiscountReceiver = (ApiMultiplyCalculation::Receiver())->setIdentifier($NetSaleField->getName());
				$TableBalancingChanceGrossPriceReceiver = (ApiMultiplyCalculation::Receiver())->setIdentifier($CoverageContributionField->getName());

				//Excel
                $ReceiverExcel = Pipeline::BlockReceiver()->setIdentifier('ExcelDownload');
                $ReceiverForm = Pipeline::BlockReceiver()->setIdentifier('FormReceiver');
                $LinkBtn = new \SPHERE\Common\Frontend\Link\Repository\Standard('Excel-Download', Pipeline::getEndpoint() , null, array(
                    'Receiver' => $ReceiverForm,
                    'PartId' => $EntityPart->getId()
                ) );



				//Pipeline
				$DiscountNumberPipeline = ApiMultiplyCalculation::pipelineMultiplyCalculation($TableAllocationChanceDiscountReceiver, $NetSaleField, $EntityPart, $ReceiverExcel);
				$GrossPricePipeline = ApiMultiplyCalculation::pipelineMultiplyCalculation($TableAllocationChanceGrossPriceReceiver, $CoverageContributionField, $EntityPart, $ReceiverExcel);
				$NetSalePipeline = ApiMultiplyCalculation::pipelineMultiplyCalculation($TableBalancingChanceDiscountReceiver, $CoverageContributionField, $EntityPart, $ReceiverExcel);
				$CoverageContributionPipeline = ApiMultiplyCalculation::pipelineMultiplyCalculation($TableBalancingChanceGrossPriceReceiver, $NetSaleField, $EntityPart, $ReceiverExcel);

				$LayoutContent = new Form(
					new FormGroup(
						array(
							new FormRow(
								array(
                                    new FormColumn(
                                        $LinkBtn->ajaxPipelineOnClick( Pipeline::pipelineExcel($ReceiverExcel) ), 4
                                    ),
									new FormColumn(
										new Panel( '', array(
											$DiscountNumberField->ajaxPipelineOnChange( $DiscountNumberPipeline )
										), Panel::PANEL_TYPE_DEFAULT), 4
									),
									new FormColumn(
										new Panel( '', array(
												$GrossPriceField->ajaxPipelineOnChange( $GrossPricePipeline )
										), Panel::PANEL_TYPE_DEFAULT), 4
									)
								)
							),
							new FormRow(
								array(
                                    new FormColumn(
                                        new Layout(
                                            new LayoutGroup(
                                                new LayoutRow(
                                                    new LayoutColumn( '' )
                                                )
                                            )
                                        ), 4
                                    ),
									new FormColumn(
										new Panel( '', array(
											$NetSaleField->ajaxPipelineOnChange( $NetSalePipeline )
										), Panel::PANEL_TYPE_DEFAULT), 4
									),
									new FormColumn(
										new Panel( '', array(
											$CoverageContributionField->ajaxPipelineOnChange( $CoverageContributionPipeline )
										), Panel::PANEL_TYPE_DEFAULT), 4
									)
								)
							)
						)
					)
				);

				$Form =
					new Layout(
						new LayoutGroup(
							new LayoutRow(
								array(
//									new LayoutColumn('', 4),
									new LayoutColumn($LayoutContent, 12)
								)
							)
							, new Title('Mehrmengenberechung bei Vergabe von Zusatzrabatten bzw. Änderung des BLP')
						)
					);

				$LayoutAllocation = new Layout(
					array(
						new LayoutGroup(
							new LayoutRow(
								array(
									new LayoutColumn(
										$this->tableAllocationBasicData( $EntityPart ), 4
									),
									new LayoutColumn(
										$TableAllocationChanceDiscountReceiver, 4
									),
									new LayoutColumn(
										$TableAllocationChanceGrossPriceReceiver, 4
									)
								)
							)
						)
					)
				).$DiscountNumberPipeline.$GrossPricePipeline;

				$LayoutBalancing = new Layout(
					new LayoutGroup(
						new LayoutRow(
							array(
								new LayoutColumn(
									$this->tableBalancingBasicData(), 4
								),
								new LayoutColumn(
									$TableBalancingChanceDiscountReceiver, 4
								),
								new LayoutColumn(
									$TableBalancingChanceGrossPriceReceiver, 4
								)
							)
						)
						, new Title('Mehrmengenberechnung zum Ausgleich des Zusatzrabattes auf Ebene')
					)
				);

//				$LayoutExcel = new Layout(
//                    new LayoutGroup(
//                        new LayoutRow(
//                            new LayoutColumn(
//                                $ReceiverExcel
//                            )
//                        )
//                    )
//                );

			} else {
				$ErrorPart = new Warning('Die Teilenummer konnte nicht gefunden werden.');
			}
		}


		$Stage->setContent(
			new Layout(array(
                    new LayoutGroup(
                        new LayoutRow(
                            new LayoutColumn(
                                $this->fromSearchPartNumber(), 4
                            )
                        )
                    ),
                    new LayoutGroup(
                        new LayoutRow(
                            new LayoutColumn(
                                $ErrorPart
                            )
                        )
                    )
                )
			)
			.$Form
			.$LayoutAllocation
			.$LayoutBalancing
           // .$LayoutExcel
		);
		return $Stage;
	}

	private function fromSearchPartNumber()
	{
		return new Form(
			new FormGroup(
				new FormRow(
					new FormColumn(
						new Panel('Suche', array(
							(new TextField('Search[PartNumber]', 'Teilenummer', 'Teilenummer eingeben', new Search()))
								->setRequired()->setAutoFocus()
						), Panel::PANEL_TYPE_INFO)
					)
				)
			)
			, array(
				new Primary('anzeigen', new Search()),
				new Reset('zurücksetzen')
			)
		);
	}

	private function tableAllocationBasicData( TblReporting_Part $EntityPart ) {

        $EntityPrice = $EntityPart->fetchPriceCurrent();
        $EntityDiscountGroup = $EntityPrice->getTblReportingDiscountGroup();

        $PriceData['Old'] = array(
            'GrossPrice' => $EntityPrice->getPriceGross(),
            'DiscountNumber' => $EntityDiscountGroup->getNumber(),
            'Discount' => $EntityDiscountGroup->getDiscount(),
            'Costs' => $EntityPrice->getCostsVariable(),
            'Quantity' => 2
        );

		$CalcRules = $this->getCalculationRules();

		$NetPrice['Old'] = $CalcRules->calcNetPrice( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Discount'] );
		$GrossSales['Old'] = $CalcRules->calcGrossSales( $PriceData['Old']['GrossPrice'], $PriceData['Old']['Quantity'] );
		$NetSales['Old'] = $CalcRules->calcNetSales( $NetPrice['Old'], $PriceData['Old']['Quantity'] );
		$CoverageContribution['Old'] = $CalcRules->calcCoverageContribution( $NetPrice['Old'], $PriceData['Old']['Costs'] );
		$TotalCoverageContribution['Old'] = $CalcRules->calcTotalCoverageContribution( $CoverageContribution['Old'], $PriceData['Old']['Quantity'] );
		$TotalCoverageContributionProportionNetSales['Old'] = $CalcRules->calcTotalCoverageContributionProportionNetSales($TotalCoverageContribution['Old'], $NetSales['Old'] );

		return
			new Table(array(
				array(
					'Description' => 'Bezeichnung',
					'Value' => $EntityPart->getName()
				),
				array(
					'Description' => 'BLP/TP',
					'Value' => new PullRight( number_format( $PriceData['Old']['GrossPrice'] ,2,',','.').' €' )
				),
				array(
					'Description' => 'RG',
					'Value' => new PullRight( $PriceData['Old']['DiscountNumber'] )
				),
				array(
					'Description' => 'Rabattsatz',
					'Value' => new PullRight( number_format($PriceData['Old']['Discount'],2,',','.').' %' )
				),
				array(
					'Description' => 'NLP/TP',
					'Value' => new PullRight( number_format($NetPrice['Old'] ,2,',','.').' €' )
				),
				array(
					'Description' => 'Kosten',
					'Value' => new PullRight( number_format($PriceData['Old']['Costs'],2,',','.').' €' )
				),
				array(
					'Description' => 'BU',
					'Value' => new PullRight( number_format($GrossSales['Old'],2,',','.').' €' )
				),
				array(
					'Description' => 'NU',
					'Value' => new PullRight( number_format($NetSales['Old'],2,',','.').' €' )
				),
				array(
					'Description' => 'Menge aktuell',
					'Value' => new PullRight( $PriceData['Old']['Quantity'].' Stk.' )
				),
				array(
					'Description' => 'DB Konzern gesamt',
					'Value' => new PullRight( number_format($TotalCoverageContribution['Old'],2,',','.').' €' )
				),
				array(
					'Description' => 'DB Konzern in %',
					'Value' => new PullRight( number_format($TotalCoverageContributionProportionNetSales['Old'],2,',','.').' %' )
				),
				array(
					'Description' => 'DB Konzern pro Stück',
					'Value' => new PullRight( number_format($CoverageContribution['Old'],2,',','.').' €' )
				)
			),
			null,
			array('Description'=>'Bezeichnung', 'Value'=>'&nbsp;'),
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

//	private function tableAllocationChanceDiscount() {
//		return
//			new Table(array(
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => new PullRight( number_format(33.00,2,',','.').' %' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(17.44,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(14.11,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(26.03,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(17.44,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( '1'.' Stk.' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(3.33,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(19.09,2,',','.').' %' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(3.33,2,',','.').' €' ),
//					'Value' => ''
//				)
//			),
//			null,
//			array('Description'=>'Retaileingang', 'Value'=>'Delta'),
//			array(
//				"columnDefs" => array(
//					array( 'width' => '1%', 'targets' => 0 ),
//					array( 'width' => '1%', 'targets' => 1 )
//				),
//				"paging"         => false, // Deaktivieren Blättern
//			    "iDisplayLength" => -1,    // Alle Einträge zeigen
//			    "searching"      => false, // Deaktivieren Suchen
//			    "info"           => false,  // Deaktivieren Such-Info
//				"sort"           => false   //Deaktivierung Sortierung der Spalten
//			)
//		);
//	}
//
//	private function tableAllocationChanceGrossPrice() {
//		return
//			new Table(array(
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => new PullRight( '20' ),
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => new PullRight( number_format(33.00,2,',','.').' %' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(17.44,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(14.11,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(26.03,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(50.50,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( '1'.' Stk.' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(3.33,2,',','.').' €' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(19.09,2,',','.').' %' ),
//					'Value' => ''
//				),
//				array(
//					'Description' => new PullRight( number_format(3.33,2,',','.').' €' ),
//					'Value' => ''
//				)
//			),
//			null,
//			array('Description'=>'&nbsp;', 'Value'=>'Delta'),
//			array(
//				"columnDefs" => array(
//					array( 'width' => '1%', 'targets' => 0 ),
//					array( 'width' => '1%', 'targets' => 1 )
//				),
//				"paging"         => false, // Deaktivieren Blättern
//			    "iDisplayLength" => -1,    // Alle Einträge zeigen
//			    "searching"      => false, // Deaktivieren Suchen
//			    "info"           => false,  // Deaktivieren Such-Info
//				"sort"           => false   //Deaktivierung Sortierung der Spalten
//			)
//		);
//	}

	private function tableBalancingBasicData() {
		return
			new Table(array(
					array(
						'Description' => 'Mehrmenge absolut'
					),
					array(
						'Description' => 'Menge gesamt'
					),
					array(
						'Description' => 'Steigerung im Zusatzabsatz'
					),
					array(
						'Description' => 'BU neu'
					),
					array(
						'Description' => 'Neu neu'
					),array(
						'Description' => new Bold( 'Mehrmenge absolut' )
					)
				),
				null,
				array('Description'=>'Bezeichnung'),
				array(
					"columnDefs" => array(
						array( 'width' => '1%', 'targets' => 0 )
					),
					"paging"         => false, // Deaktivieren Blättern
				    "iDisplayLength" => -1,    // Alle Einträge zeigen
				    "searching"      => false, // Deaktivieren Suchen
				    "info"           => false,  // Deaktivieren Such-Info
					"sort"           => false   //Deaktivierung Sortierung der Spalten
				)
			);
	}

//	private function tableBalancingChanceDiscount() {
//		return
//				new Table(array(
//					array(
//						'Description' => '&nbsp;',
//						'Value' => '&nbsp;'
//					),
//					array(
//						'Description' => '&nbsp;',
//						'Value' => '&nbsp;'
//					),
//					array(
//						'Description' => '&nbsp;',
//						'Value' => '&nbsp;'
//					),
//					array(
//						'Description' => '&nbsp;',
//						'Value' => '&nbsp;'
//					),
//					array(
//						'Description' => '&nbsp;',
//						'Value' => '&nbsp;'
//					),
//					array(
//						'Description' => '&nbsp;',
//						'Value' => '&nbsp;'
//					)
//				),
//				null,
//				array('Description'=>'Nettoumsatz&nbsp;', 'Value'=>'DB-Konzern'),
//				array(
//					"columnDefs" => array(
//						array( 'width' => '1%', 'targets' => 0 ),
//						array( 'width' => '1%', 'targets' => 1 )
//					),
//					"paging"         => false, // Deaktivieren Blättern
//				    "iDisplayLength" => -1,    // Alle Einträge zeigen
//				    "searching"      => false, // Deaktivieren Suchen
//				    "info"           => false,  // Deaktivieren Such-Info
//					"sort"           => false   //Deaktivierung Sortierung der Spalten
//				)
//			);
//	}
//
//	private function tableBalancingChanceGrossPrice() {
//		return
//			new Table(array(
//				array(
//					'Description' => '&nbsp;&nbsp;52',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				),
//				array(
//					'Description' => '&nbsp;',
//					'Value' => '&nbsp;'
//				)
//			),
//			null,
//			array('Description'=>'Nettoumsatz', 'Value'=>'DB-Konzern'),
//			array(
//				"columnDefs" => array(
//					array( 'width' => '1%', 'targets' => 0 ),
//					array( 'width' => '1%', 'targets' => 1 )
//				),
//				"paging"         => false, // Deaktivieren Blättern
//			    "iDisplayLength" => -1,    // Alle Einträge zeigen
//			    "searching"      => false, // Deaktivieren Suchen
//			    "info"           => false,  // Deaktivieren Such-Info
//				"sort"           => false   //Deaktivierung Sortierung der Spalten
//			)
//		);
//	}

}