<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 27.03.2017
 * Time: 15:47
 */

namespace SPHERE\Application\Competition\Competition;


use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\DatePicker;
use SPHERE\Common\Frontend\Form\Repository\Field\SelectBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Time;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Stage;

class Frontend
{
	public function frontendCompetition( $Search = null ) {
		$Stage = new Stage('Angebotsdaten-Suche');
		$Stage->setMessage('');


		$LayoutTable = '';
		if( $Search ) {
			$LayoutTable = new Layout(
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn( $this->tableSearchData( $Search['Grouping'] ) )
					)
				)
			);
		}


		$Stage->setContent(
			new Form(
				new FormGroup(
					array(
						new FormRow(
							array(
								new FormColumn(
									new Panel('Teilenummer', new TextField('Search[PartNumber]', 'Teilenummer', '&nbsp;') ), 3
								),
								new FormColumn(
									new Panel('Marketingcode', new SelectBox('Search[McCode]', '&nbsp;', array( '', '1P123', '1L522')) ), 3
								),
								new FormColumn(
									new Panel('Zeitraum',
										new Layout(
											new LayoutGroup(
												new LayoutRow(
													array(
														new LayoutColumn(
															new DatePicker('Search[PeriodFrom]', date('d.m.Y'), 'von', new Time()), 6
														),
														new LayoutColumn(
															new DatePicker('Search[PeriodTo]', date('d.m.Y'), 'bis', new Time()), 6
														)
													)
												)
											)
										)
									), 4
								)
							)
						),
						new FormRow(
							new FormColumn(
								new Panel('',
									new SelectBox('Search[Grouping]', 'Gruppierung', array( '', 'Teilenummer', 'Marketingcode')), Panel::PANEL_TYPE_DEFAULT
								), 3
							)
						)
					)
				),
				new Primary('Suchen')
			)
			. $LayoutTable
		);
		return $Stage;
	}

	private function tableSearchData( $SelectionStatistic ) {

		var_dump($SelectionStatistic);

		switch ($SelectionStatistic) {
			case '1':
				$SearchData = array(
					array( 'PartNumber' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 ),
					array( 'PartNumber' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 )
				);
				break;
			case '2':
				$SearchData = array(
					array( 'McCode' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 ),
					array( 'McCode' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 )
				);
				break;
			case '3':
				$SearchData = array(
					array( 'ProductManager' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 ),
					array( 'ProductManager' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 )
				);
				break;
			case '4':
				$SearchData = array(
					array( 'Competitor' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 ),
					array( 'Competitor' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 )
				);
				break;
			default:
				$SearchData = array();
				break;
		}

		$ReplaceArray = array(
			'PartNumber' => 'Teilenummer',
			'McCode' => 'Marketingcode',
			'ProductManager' => 'Produktmanager',
			'Competitor' => 'Wettbewerber',
			'GrossPrice' => 'BLP',
			'NetPrice' => 'NLP',
			'Discount' => 'RG',
			'Quantity' => 'Menge'
		);

		//Definition Spaltenkopf
		if( count($SearchData) > 0 ) {
			$Keys = array_keys($SearchData[0]);
			$TableHead = array_combine( $Keys, str_replace( array_keys( $ReplaceArray ) , $ReplaceArray, $Keys) );

			return new Table(
				$SearchData, null, $TableHead
			);
		}
		else {
			return new Warning('Es sind keine Datensätze vorhanden.');
		}
	}
}