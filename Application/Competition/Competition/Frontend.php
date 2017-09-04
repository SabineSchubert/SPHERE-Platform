<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 27.03.2017
 * Time: 15:47
 */

namespace SPHERE\Application\Competition\Competition;


use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\AutoCompleter;
use SPHERE\Common\Frontend\Form\Repository\Field\DatePicker;
use SPHERE\Common\Frontend\Form\Repository\Field\SelectBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Time;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Repository\Debugger;

class Frontend
{
	public function frontendCompetition( $Search = null ) {
		$Stage = new Stage('Angebotsdaten-Suche');
		$Stage->setMessage('');

        Debugger::screenDump($Search);

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


        $EntityMarketingCode = DataWareHouse::useService()->getMarketingCodeAll();
        $EntityProductGroup = DataWareHouse::useService()->getProductGroupAll();

		$Stage->setContent(
			new Form(
				new FormGroup(
					array(
						new FormRow(
							array(
								new FormColumn(
									new Panel('Teilenummer', new TextField('Search[PartNumber]', 'Teilenummer', '') ), 3
								),
								new FormColumn(
                                    new Panel('Marketingcode',
                                        ( new AutoCompleter('Search[MarketingCode]', '', 'Marketingcode eingeben',
                                            array( 'Number' => $EntityMarketingCode )
                                        ) )
                                    ), 4
								),
                                new FormColumn(
                                    new Panel('Warengruppe',
                                        ( new AutoCompleter('Search[ProductGroupNumber]', '', 'Warengruppe eingeben',
                                            array( 'Number' => $EntityProductGroup )
                                        ) )
                                    ), 4
								),
							)
						),
						new FormRow(
						    array(
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
                            ),
                            new FormColumn(
                                new Panel('Gruppierung',
                                    new SelectBox('Search[Grouping]', '&nbsp;', array( '', 'Teilenummer', 'Marketingcode', 'Warengruppe'))
                                ), 4
                            )
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

        $SearchData = \SPHERE\Application\Competition\DataWareHouse\DataWareHouse::useService()->getCompetitionSearch( 'A028250150180', null, null, '01.08.2016', '30.08.2017', 1 );

		var_dump($SearchData);


		$ReplaceArray = array(
			'PartNumber' => 'Teilenummer',
			'MarketingCode' => 'Marketingcode',
			'ProductGroupNumber' => 'Warengruppe',
			'Competitor' => 'Wettbewerber',
			'Data_CountQuantity' => 'Anzahl'
		);


        array_walk( $SearchData, function( &$Row ) {
           if( isset($Row['Data_CountQuantity']) ) {
               $Row['Data_CountQuantity'] = new PullRight( $Row['Data_CountQuantity']);
           }
           if( isset( $Row['PartNumber'] ) ) {
               $Row['PartNumber'] = new Link( $Row['PartNumber'], '/../Reporting/Controlling/DirectSearch/PartNumber', null, array( /*'Search' => array( 'PartNumber' => $Row['PartNumber'] )*/ ) );
           }
        } );

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