<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\Reporting\Controlling\Search;


use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Application\Api\Reporting\Utility\ScenarioCalculator\ScenarioCalculator;
use SPHERE\Application\Api\TestAjax\TestAjax;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Data;
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
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;

class Frontend extends Extension
{
	public function frontendSearchPartNumber( $Search = null ) {
		$Stage = new Stage('Suche');
		$Stage->setMessage('Teilenummer');
		$this->buttonStageDirectSearch($Stage);

        Debugger::screenDump($Search);

		if( $Search ) {
            $SearchData = DataWareHouse::useService()->getViewPartGroupPart( $Search['PartNumber'], $Search['MarketingCode'], $Search['ProductManager'], $Search['PeriodFrom'], $Search['PeriodTo'] );

            //Debugger::screenDump($SearchData);

            $LayoutTable = $this->tableSearchData($SearchData);
        }
        else {
            $LayoutTable = '';
        }

		$Stage->setContent(
			new Layout(
				new LayoutGroup(
					new LayoutRow(array(
						new LayoutColumn(
                            $this->formSearch()
						),
                        new LayoutColumn(
                            '&nbsp;'
						),
						new LayoutColumn(
							$LayoutTable
						)
					))
				)
			)
		);

		return $Stage;
	}

	public function frontendSearchMarketingCode( $Search = null ) {
		$Stage = new Stage('Suche');
		$Stage->setMessage('Marketingcode');
		$this->buttonStageDirectSearch($Stage);

        if( $Search ) {
            $SearchData = DataWareHouse::useService()->getViewPartGroupMarketingCode( $Search['PartNumber'], $Search['MarketingCode'], $Search['ProductManager'] );
            $LayoutTable = $this->tableSearchData($SearchData);
        }
        else {
            $LayoutTable = '';
        }

		$Stage->setContent(
			new Layout(
				new LayoutGroup(
					new LayoutRow(array(
						new LayoutColumn(
                            $this->formSearch()
						),
						new LayoutColumn(
							$LayoutTable
						)
					))
				)
			)
		);

        Debugger::screenDump( $Search );

		return $Stage;
	}

	public function frontendSearchProductManager( $Search = null ) {
		$Stage = new Stage('Suche');
		$Stage->setMessage('Produktmanager');
		$this->buttonStageDirectSearch($Stage);

        if( $Search ) {
            $SearchData = DataWareHouse::useService()->getViewPartGroupProductManager( $Search['PartNumber'], $Search['MarketingCode'], $Search['ProductManager'] );
            $LayoutTable = $this->tableSearchData($SearchData);
        }
        else {
            $LayoutTable = '';
        }

		$Stage->setContent(
			new Layout(
				new LayoutGroup(
					new LayoutRow(array(
						new LayoutColumn(
                            $this->formSearch()
						),
						new LayoutColumn(
							$LayoutTable
						)
					))
				)
			)
		);

		return $Stage;
	}

	public function frontendSearchCompetition( $Search = null ) {
		$Stage = new Stage('Suche');
		$Stage->setMessage('Angebotsdaten');
		$this->buttonStageDirectSearch($Stage);

        if( $Search ) {
            $SearchData = DataWareHouse::useService()->getViewPartGroupCompetition( $Search['PartNumber'], $Search['MarketingCode'], $Search['ProductManager'] );
            $LayoutTable = $this->tableSearchData($SearchData);
        }
        else {
            $LayoutTable = '';
        }

		$Stage->setContent(
			new Layout(
				new LayoutGroup(
					new LayoutRow(array(
						new LayoutColumn(
							$this->formSearch()
						),
						new LayoutColumn(
							$LayoutTable
						)
					))
				)
			)
		);

		return $Stage;
	}

	private function formSearch() {
        $EntityProductManager = DataWareHouse::useService()->getProductManagerAll();
        $EntityMarketingCode = DataWareHouse::useService()->getMarketingCodeAll();

	    return new Form(
	        new FormGroup(
	            array(
                    new FormRow(
                        array(
                            new FormColumn(
                                new Panel('Teilenummer',
                                    new TextField( 'Search[PartNumber]', 'Teilenummer', '' )
                                ), 4
                            ),
                            new FormColumn(
                                new Panel('Marketingcode',
                                    new AutoCompleter('Search[MarketingCode]', '', 'Marketingcode eingeben',
                                        array( 'Number' => $EntityMarketingCode )
                                    )
                                ), 4
                            ),
                            new FormColumn(
                                new Panel('Produktmanager',
                                    new SelectBox('Search[ProductManager]', '',
                                        array( '{{Name}} {{Department}}' => $EntityProductManager )
                                    )
                                ), 4
                            ),
                        )
                    ),
                    new FormRow(
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
                )
            ), array(
                new Primary( 'suchen', new SearchIcon() )
            )
        );
    }

	private function buttonStageDirectSearch(Stage $Stage)
	{
		$Stage->addButton(
			new Standard('Teilenummer', new Route(__NAMESPACE__ . '/PartNumber'))
		);
		$Stage->addButton(
			new Standard('Produktmanager', new Route(__NAMESPACE__ . '/ProductManager'))
		);
		$Stage->addButton(
			new Standard('Marketingcode', new Route(__NAMESPACE__ . '/MarketingCode'))
		);
		$Stage->addButton(
			new Standard('Angebotsdaten', new Route(__NAMESPACE__ . '/Competition'))
		);
	}

	private function tableSearchData( $SearchData ) {
        if( $SearchData ) {
            $ReplaceArray = array(
                'PartNumber' => 'Teilenummer',
                'PartName' => 'Bezeichnung',
                'MarketingCodeNumber' => 'Marketingcode',
                'MarketingCodeName' => 'Marketingcode-Bezeichnung',
                'ProductManagerName' => 'Produktmanager',
                'ProductManagerDepartment' => 'Bereich',
                'Competitor' => 'Wettbewerber',
                'PriceGross' => 'BLP',
                'PriceNet' => 'NLP',
                'Discount' => 'RG',
                'SumSalesGross' => 'Bruttoumsatz',
                'SumSalesNet' => 'Nettoumsatz',
                'SumQuantity' => 'Menge',
                //'TblReporting_Sales_Quantity' => 'Menge'
            );

            $Keys = array_keys($SearchData[0]);
            $TableHead = array_combine( $Keys, str_replace( array_keys( $ReplaceArray ) , $ReplaceArray, $Keys) );

            return new Table(
                $SearchData, null, $TableHead
            );
        }
        else {
            return new Warning( 'Keine Daten vorhanden' );
        }
	}
}