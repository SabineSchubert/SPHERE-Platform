<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 26.10.2016
 * Time: 09:14
 */

namespace SPHERE\Application\Reporting\Utility\MultiplyCalculation;


use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Field\HiddenField;
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
use SPHERE\Common\Frontend\Form\Repository\Title as FormTitle;
use SPHERE\Common\Frontend\Layout\Repository\Well;
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
use SPHERE\Common\Frontend\Table\Structure\TableVertical;
use SPHERE\Common\Frontend\Text\Repository\Bold;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
	public function frontendMultiplyCalculation( $Search = null ) {
		$Stage = new Stage('Mehrmengenberechnung');
		$Stage->setMessage('');

		$LayoutGroupPartNumberInformation = '';
				$FormGroupMultiCalcAllocation = '';
				$FormGroupMultiCalcBalancing = '';
				$FormMultiplyQuantity = '';
				if ($Search) {
					if (empty($Result)) {

						//$this->preloadPriceData();

						$FormGroupMultiCalcAllocation =
							new FormGroup(
								array(
									new FormRow(array(
										new LayoutColumn(
											'&nbsp;', 4
										),
										new FormColumn(
											new Panel( 'Rabatt-Änderung', array(
												new TextField('DiscountNumber','Rabattnummer')
											), Panel::PANEL_TYPE_INFO)
											, 4
										),
										new FormColumn(
											new Panel( 'BLP-Änderung', array(
													new TextField('BLP','BLP')
											), Panel::PANEL_TYPE_INFO)
											, 4
										)
									)),
									new FormRow(array(
										new FormColumn(
											$this->tableAllocationBasicData(), 4
										),
										new FormColumn(
											$this->tableAllocationChanceDiscount(), 4
										),
										new FormColumn(
											$this->tableAllocationChanceGrossPrice(), 4
										)
									))
								)
								, new FormTitle('Mehrmengenberechung bei Vergabe von Zusatzrabatten bzw. Änderung des BLP')
							);

						$FormGroupMultiCalcBalancing =
							new FormGroup(
								new FormRow(
									array(
										new FormColumn(
											$this->tableBalancingBasicData(), 4
										),
										new FormColumn(
											$this->tableBalancingChanceDiscount(), 4
										),
										new FormColumn(
											$this->tableBalancingChanceGrossPrice(), 4
										)
									)
								)
								, new FormTitle('Mehrmengenberechnung zum Ausgleich des Zusatzrabattes auf Ebene:')
							);

						$FormMultiplyQuantity =
							new FormGroup(array(
								new FormRow(
									array(
										new FormColumn(
											new Panel( 'Steigerung NU', array(
												new TextField('Test', 'Test')
											), Panel::PANEL_TYPE_INFO)
											, 4
										),
										new FormColumn(
											$this->tableIncreaseNuChanceDiscount(), 4
										),
										new FormColumn(
											$this->tableIncreaseNuChanceGrossPrice(), 4
										)
									)
								),
								new FormRow(
									array(
										new FormColumn(
											new Panel( 'Steigerung DB', array(
												new TextField('Test', 'Test')
											), Panel::PANEL_TYPE_INFO)
											, 4
										),
										new FormColumn(
											$this->tableIncreaseDbChanceDiscount(), 4
										),
										new FormColumn(
											$this->tableIncreaseDbChanceGrossPrice(), 4
										)
									)
								)
							));

					} else {
						$Table = new Warning('Die Teilenummer konnte nicht gefunden werden.');
					}
				}

				$Stage->setContent(
					new Layout(
						new LayoutGroup(
							new LayoutRow(
								new LayoutColumn(
									$this->fromSearchPartNumber(), 4
								)
							)
						)
					)
					.
					(new Form (array(
						//Vergabe
						$FormGroupMultiCalcAllocation,
						//Ausgleich
						$FormGroupMultiCalcBalancing,
						//Mehrmenge
						$FormMultiplyQuantity
					)))
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
								->setRequired()
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

	private function tableAllocationBasicData() {
		return
			new TableData(array(
				array(
					'Description' => 'Bezeichnung',
					'Value' => 'Abschneider'
				),
				array(
					'Description' => 'BLP/TP',
					'Value' => new PullRight( number_format(26.03,2,',','.').' €' )
				),
				array(
					'Description' => 'RG',
					'Value' => new PullRight( '20' )
				),
				array(
					'Description' => 'Rabattsatz',
					'Value' => new PullRight( number_format(33.00,2,',','.').' %' )
				),
				array(
					'Description' => 'NLP/TP',
					'Value' => new PullRight( number_format(17.44,2,',','.').' €' )
				),
				array(
					'Description' => 'Kosten',
					'Value' => new PullRight( number_format(14.11,2,',','.').' €' )
				),
				array(
					'Description' => 'BU',
					'Value' => new PullRight( number_format(26.03,2,',','.').' €' )
				),
				array(
					'Description' => 'NU',
					'Value' => new PullRight( number_format(17.44,2,',','.').' €' )
				),
				array(
					'Description' => 'Menge aktuell',
					'Value' => new PullRight( '1'.' Stk.' )
				),
				array(
					'Description' => 'DB Konzern gesamt',
					'Value' => new PullRight( number_format(3.33,2,',','.').' €' )
				),
				array(
					'Description' => 'DB Konzern in %',
					'Value' => new PullRight( number_format(19.09,2,',','.').' %' )
				),
				array(
					'Description' => 'DB Konzern pro Stück',
					'Value' => new PullRight( number_format(3.33,2,',','.').' €' )
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

	private function tableAllocationChanceDiscount() {
		return
			new TableData(array(
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				),
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				),
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				),
				array(
					'Description' => new PullRight( number_format(33.00,2,',','.').' %' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(17.44,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(14.11,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(26.03,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(17.44,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( '1'.' Stk.' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(3.33,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(19.09,2,',','.').' %' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(3.33,2,',','.').' €' ),
					'Value' => ''
				)
			),
			null,
			array('Description'=>'Retaileingang', 'Value'=>'Delta'),
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

	private function tableAllocationChanceGrossPrice() {
		return
			new TableData(array(
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				),
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				),
				array(
					'Description' => new PullRight( '20' ),
					'Value' => '&nbsp;'
				),
				array(
					'Description' => new PullRight( number_format(33.00,2,',','.').' %' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(17.44,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(14.11,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(26.03,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(17.44,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( '1'.' Stk.' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(3.33,2,',','.').' €' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(19.09,2,',','.').' %' ),
					'Value' => ''
				),
				array(
					'Description' => new PullRight( number_format(3.33,2,',','.').' €' ),
					'Value' => ''
				)
			),
			null,
			array('Description'=>'&nbsp;', 'Value'=>'Delta'),
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

	private function tableBalancingBasicData() {
		return
			new TableData(array(
					array(
						'Description' => 'Menge absolut'
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

	private function tableBalancingChanceDiscount() {
		return
				new TableData(array(
					array(
						'Description' => '&nbsp;',
						'Value' => '&nbsp;'
					),
					array(
						'Description' => '&nbsp;',
						'Value' => '&nbsp;'
					),
					array(
						'Description' => '&nbsp;',
						'Value' => '&nbsp;'
					),
					array(
						'Description' => '&nbsp;',
						'Value' => '&nbsp;'
					),
					array(
						'Description' => '&nbsp;',
						'Value' => '&nbsp;'
					)
				),
				null,
				array('Description'=>'Nettoumsatz ', 'Value'=>'DB-Konzern'),
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

	private function tableBalancingChanceGrossPrice() {
		return
			new TableData(array(
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				),
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				),
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				),
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				),
				array(
					'Description' => '&nbsp;',
					'Value' => '&nbsp;'
				)
			),
			null,
			array('Description'=>'Nettoumsatz', 'Value'=>'DB-Konzern'),
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

	private function tableIncreaseNuChanceDiscount() {
		return
			new TableData(array(
				array(
					'Description' => '0 St.',
					'Value' => 'Mehrmenge absolut '
				)
			),
				null,
				array('Description' => 'Nettoumsatz', 'Value' => 'DB-Konzern'),
				array(
					"columnDefs" => array(
						array('width' => '1%', 'targets' => 0),
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

	private function tableIncreaseNuChanceGrossPrice() {
		return
			new TableData(array(
				array(
					'Description' => '0 St.',
					'Value' => 'Mehrmenge absolut '
				)
			),
				null,
				array('Description' => 'Nettoumsatz', 'Value' => 'DB-Konzern'),
				array(
					"columnDefs" => array(
						array('width' => '1%', 'targets' => 0),
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

	private function tableIncreaseDbChanceDiscount() {
		return
			new TableData(array(
				array(
					'Description' => 'Mehrmenge absolut',
					'Value' => '0 St.'
				)
			),
				null,
				array('Description' => 'Nettoumsatz', 'Value' => 'DB-Konzern '),
				array(
					"columnDefs" => array(
						array('width' => '1%', 'targets' => 0),
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

	private function tableIncreaseDbChanceGrossPrice() {
		return
			new TableData(array(
				array(
					'Description' => 'Mehrmenge absolut',
					'Value' => '0 St.'
				)
			),
				null,
				array('Description' => 'Nettoumsatz ', 'Value' => 'DB-Konzern'),
				array(
					"columnDefs" => array(
						array('width' => '1%', 'targets' => 0),
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
}