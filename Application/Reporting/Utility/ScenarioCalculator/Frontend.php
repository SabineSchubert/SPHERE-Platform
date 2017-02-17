<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 26.10.2016
 * Time: 09:13
 */

namespace SPHERE\Application\Reporting\Utility\ScenarioCalculator;


use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\MoneyEuro;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\Icon\Repository\Warning;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Table\Repository\Title as TableTitle;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Table\Structure\TableBody;
use SPHERE\Common\Frontend\Table\Structure\TableColumn;
use SPHERE\Common\Frontend\Table\Structure\TableData;
use SPHERE\Common\Frontend\Table\Structure\TableHead;
use SPHERE\Common\Frontend\Table\Structure\TableRow;
use SPHERE\Common\Frontend\Text\Repository\Bold;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
	public function frontendScenarioCalculator($Search = null, $PriceData = null)
	{
		$Stage = new Stage('Szenario-Rechner', 'Simulation von RG-, NLP-, BLP-, Netto- und Brutto-Änderungen');
		$Stage->setMessage('');

		$LayoutGroupPartNumberInformation = '';
		$LayoutGroupScenarioCalculatorForm = '';
		$LayoutGroupScenarioCalculator = '';
		if ($Search) {
			if (empty($Result)) {

				$this->preloadPriceData();

				$LayoutGroupPartNumberInformation =
					new LayoutGroup(
						new LayoutRow(
							new LayoutColumn(
								$this->tablePartNumberInformation(), 4
							)
						)
						, new Title('Teilenummer-Informationen')
					);

				$LayoutGroupScenarioCalculatorForm =
					new LayoutGroup(
						new LayoutRow(
							new LayoutColumn(
								$this->formScenarioCalculator($Search), 12
							)
						)
						, new Title('Szenario-Rechner')
					);

				if($PriceData !== null) {
					$LayoutGroupScenarioCalculator =
						new LayoutGroup(
							new LayoutRow(
								array(
									new LayoutColumn(
										$this->tablePriceViewing(), 6
									),
									new LayoutColumn(
										$this->tableTotalViewing(), 6
									)
								)
							)
							, new Title('')
						);
				}

			} else {
				$Table = new Warning('Die Teilenummer konnte nicht gefunden werden.');
			}
		}



		$Stage->setContent(
			new Layout(array(
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(
							array(
								$this->fromSearchPartNumber()
							)
							, 4)
					)
				),
				$LayoutGroupPartNumberInformation,
				$LayoutGroupScenarioCalculatorForm,
				$LayoutGroupScenarioCalculator
//              ,
//				new LayoutGroup(
//					new LayoutRow(
//						new LayoutColumn(
//							$this->tablePartNumberInformation()
//						), new Title( 'Teilenummer-Informationen' )
//					)
//				)
			))
		);

		return $Stage;
	}

	private function preloadPriceData()
	{
		$PriceData = array(
			array('BLP' => 150.00, 'NLP' => 90.00, 'DiscountNumber' => 5, 'Discount' => 17.00, 'Costs' => 40.00, 'PartsAndMore' => 5.00, 'Quantity' => 20, 'SalesGross' => 500, 'SalesNet' => 400)
		);
		$PriceData = $PriceData[0];

		$Global = $this->getGlobal();
		if (!isset($Global->POST['PriceData']['BLP'])) {
			$Global->POST['PriceData']['BLP'] = $PriceData['BLP'];
		}
		if (!isset($Global->POST['PriceData']['NLP'])) {
			$Global->POST['PriceData']['NLP'] = $PriceData['NLP'];
		}
		if (!isset($Global->POST['PriceData']['DiscountNumber'])) {
			$Global->POST['PriceData']['DiscountNumber'] = $PriceData['DiscountNumber'];
		}
		if (!isset($Global->POST['PriceData']['Discount'])) {
			$Global->POST['PriceData']['Discount'] = $PriceData['Discount'];
		}
		if (!isset($Global->POST['PriceData']['Costs'])) {
			$Global->POST['PriceData']['Costs'] = $PriceData['Costs'];
		}
		if (!isset($Global->POST['PriceData']['PartsAndMore'])) {
			$Global->POST['PriceData']['PartsAndMore'] = $PriceData['PartsAndMore'];
		}
		if (!isset($Global->POST['PriceData']['Quantity'])) {
			$Global->POST['PriceData']['Quantity'] = $PriceData['Quantity'];
		}
		if (!isset($Global->POST['PriceData']['SalesGross'])) {
			$Global->POST['PriceData']['SalesGross'] = $PriceData['SalesGross'];
		}
		if (!isset($Global->POST['PriceData']['SalesNet'])) {
			$Global->POST['PriceData']['SalesNet'] = $PriceData['SalesNet'];
		}
		$Global->savePost();
	}

	private function tablePartNumberInformation()
	{

//		new Panel('', array(
//
//		), Panel::PANEL_TYPE_INFO);

		//Tabelle: Teilenummer-Informationen
//		print '<table class="list" style="width: 220px; position: absolute; left: 400px; top: 80px;">
//					<tr class="head"><td colspan="2">Teilenummer-Informationen</td></tr>
//					<tr><td><b>Teilenummer:</b></td><td>'.$SQLTeilenummer[0]["tnr"].'</td></tr>
//					<tr><td><b>Bezeichnung:</b></td><td>'.$SQLTeilenummer[0]["tnr_name"].'</td></tr>
//					<tr><td><b>Marketingcode:</b></td><td>'.$SQLTeilenummer[0]["mc_nummer"].'</td></tr>
//				</table>';
//		print '</div>';
//
//		$Table = new TableData(
//			array(
//				array(
//					'A' => 'Teilenummer',
//					'B' => '123'
//				),
//				array(
//					'A' => 'Bezeichnung',
//					'B' => 'abc'
//				),
//				array(
//					'A' => 'Marketingcode',
//					'B' => 'xyz'
//				)
//			)
//		, null, array(
//			'A' => '',
//			'B' => ''
//		), false);

//		$Table = new Table(
//			new TableHead(),
//			new TableBody(array(
//				new TableRow(array(
//					new TableColumn(new Bold('Teilenummer'), 1, '1%'),
//					new TableColumn('A432323123'),
//				)),
//				new TableRow(array(
//					new TableColumn(new Bold('Bezeichnung')),
//					new TableColumn('Motor'),
//				)),
//				new TableRow(array(
//					new TableColumn(new Bold('Marketingcode')),
//					new TableColumn('1P123'),
//				))
//			))
//		);
		/*
				$Table = new Panel('',
					new Layout(
						new LayoutGroup(array(
							new LayoutRow(array(
								new LayoutColumn('Teilenummer', 7),
								new LayoutColumn('123', 5),
							)),
							new LayoutRow(array(
								new LayoutColumn('Bezeichnung', 7),
								new LayoutColumn('abc', 5),
							)),
							new LayoutRow(array(
								new LayoutColumn('Marketingcode', 7),
								new LayoutColumn('xyz', 5),
							))
						))
					)
				);
		*/
//		$Table = '';
//		$Table = new TableData(
//							array(
//								array( 'A' => ':)' )
//							), null, array(
//								'A' => 'Lach ne'
//							)
//						, false);
//		return $Table;
		return new TableData(
			array(
				array(
					'Description' => 'Teilenummer',
					'Value' => 'A1234'
				),
				array(
					'Description' => 'Bezeichnung',
					'Value' => '123'
				),
				array(
					'Description' => 'Marketingcode',
					'Value' => '1P23'
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

	private function formScenarioCalculator($Search)
	{
//		print '<pre>';
//		var_dump($PriceData);
//		print '</pre>';
		$PriceData = array(
			array('BLP' => 150.00, 'NLP' => 90.00, 'DiscountNumber' => 5, 'Discount' => 17.00, 'Costs' => 40.00, 'PartsAndMore' => 5.00, 'Quantity' => 20, 'SalesGross' => 500, 'SalesNet' => 400)
		);
		$PriceData = $PriceData[0];
		$Per = '10/2016';

		return new Form(
			new FormGroup(
				new FormRow(
					new FormColumn(

						new TableData(
							array(
								array(
									'' => 'Alt:',
									'BLP' => new PullRight( number_format($PriceData['BLP'],2,',','.').' €' ),
									'NLP' => new PullRight( $PriceData['NLP'].' €' ),
									'DiscountNumber' => new PullRight( $PriceData['DiscountNumber'] ),
									'Discount' => new PullRight( $PriceData['Discount'].' %' ),
									'Costs' => new PullRight( $PriceData['Costs'].' €' ),
									'PartsAndMore' => new PullRight( $PriceData['PartsAndMore'].' %' ),
									'Quantity' => new PullRight( $PriceData['Quantity'].' Stk.' ),
									'SalesGross' => new PullRight( $PriceData['SalesGross'].' €' ),
									'SalesNet' => new PullRight( $PriceData['SalesNet'].' €' )
								),
								array(
									'' => 'Neu:',
									'BLP' => new Layout(
												new LayoutGroup(
													new LayoutRow(array(
														new LayoutColumn(
															new Standard('&nbsp;', '', new Search()), 2
														),
														new LayoutColumn(
															new TextField( 'PriceData[BLP]', 'BLP'), 10
														)
													))
												)
											),  //->setDefaultValue('12,35')
									'NLP' => new Layout(
												new LayoutGroup(
													new LayoutRow(array(
														new LayoutColumn(
															new Standard('&nbsp;', '', new Search()), 2
														),
														new LayoutColumn(
															new TextField('PriceData[NLP]', 'NLP'), 10
														)
													))
												)
											),
									'DiscountNumber' => new TextField('PriceData[DiscountNumber]', 'RG-Nr'),
									'Discount' => new TextField('PriceData[Discount]', 'Rabattsatz'),
									'Costs' => new TextField('PriceData[Costs]', 'variable Kosten'),
									'PartsAndMore' => new TextField('PriceData[PartsAndMore]', 'PartsAndMore'),
									'Quantity' => new Layout(
													new LayoutGroup(
														new LayoutRow(array(
															new LayoutColumn(
																new Standard('&nbsp;', '', new Search()), 2
															),
															new LayoutColumn(
																new TextField('PriceData[NLP]', 'NLP'), 10
															)
														))
													)
												),
									'SalesGross' => new Layout(
														new LayoutGroup(
															new LayoutRow(array(
																new LayoutColumn(
																	new Standard('&nbsp;', '', new Search()), 2
																),
																new LayoutColumn(
																	new TextField('PriceData[NLP]', 'NLP'), 10
																)
															))
														)
													),
									'SalesNet' => new Layout(
													new LayoutGroup(
														new LayoutRow(array(
															new LayoutColumn(
																new Standard('&nbsp;', '', new Search()), 2
															),
															new LayoutColumn(
																new TextField('PriceData[NLP]', 'NLP'), 10
															)
														))
													)
												)
								)
							),
							null,

							array(
								'' => '',
								'BLP' => 'BLP',
								'NLP' => 'NLP',
								'DiscountNumber' => 'RG-Nr',
								'Discount' => 'Rabattsatz',
								'Costs' => 'variable Kosten',
								'PartsAndMore' => 'P&M',
								'Quantity' => 'Menge',
								'SalesGross' => 'Bruttoumsaatz <br/>'.$Per,
								'SalesNet' => 'Nettoumsaatz <br/>'.$Per
							),
							array(
								//'order' => array(array(0, 'asc'), array(1, 'asc'), array(2, 'asc')),
							    'columnDefs' => array(
							        array('width' => '5%', 'targets' => array(0,3)),
							    ),
								"paging"         => false, // Deaktivieren Blättern
							    "iDisplayLength" => -1,    // Alle Einträge zeigen
							    "searching"      => false, // Deaktivieren Suchen
							    "info"           => false,  // Deaktivieren Such-Info
								"sort"           => false   //Deaktivierung Sortierung der Spalten
							)
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
		);
//		<td></td>
//					<td>BLP</td>
//					<td>NLP</td>
//					<td>RG-Nr</td>
//					<td>RG-Satz</td>
//					<td>Variable<br> Kosten</td>
//					<td>P&M</td>
//					<td>Menge per<br> '.$per.'</td>
//					<td>Bruttoumsatz<br>per '.$per.'</td>
//					<td>Nettoumsatz<br>per '.$per.'</td>
//					<td></td>
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
							), Panel::PANEL_TYPE_INFO)
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

	private function tablePriceViewing() {
		return
			new TableData(
				array(
					array(
						'Bezeichnung' => 'BLP in €',
						'Alt' => new PullRight( number_format('100', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('120', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('20', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'RG-Satz in %',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'RG-Satz in €',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'NLP in €',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'NLP abzügl. P&M in €',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Variable Kosten in €',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Konzern-DB in €',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Konzern-DB abzügl. P&M in €',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
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

	private function tableTotalViewing() {
		return
			new TableData(
				array(
					array(
						'Bezeichnung' => 'Bruttoumsatz in €',
						'Alt' => new PullRight( number_format('100', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('120', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('20', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Anzeff in Stück',
						'Alt' => new PullRight( number_format('100', 0, '', '.') ),
						'Neu' => new PullRight( number_format('120', 0, '', '.') ),
						'Delta' => new PullRight( number_format('20', 0, '', '.') )
					),
					array(
						'Bezeichnung' => 'RG-Satz in %',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'RG-Satz in €',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Nettoumsatz in €',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Nettoumsatz abzügl. P&M',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Variable Kosten',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Konzern-DB',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					),
					array(
						'Bezeichnung' => 'Konzern-DB abzügl. P&M',
						'Alt' => new PullRight( number_format('17', 2, ',', '.') ),
						'Neu' => new PullRight( number_format('10', 2, ',', '.') ),
						'Delta' => new PullRight( number_format('7', 2, ',', '.') )
					)
				),
				new TableTitle('Gesamtbetrachtung'),
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
}