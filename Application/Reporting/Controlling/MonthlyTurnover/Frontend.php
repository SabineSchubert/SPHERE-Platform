<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\Reporting\Controlling\MonthlyTurnover;


use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Field\AutoCompleter;
use SPHERE\Common\Frontend\Form\Repository\Field\SelectBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Table\Structure\TableColumn;
use SPHERE\Common\Frontend\Table\Structure\TableHead;
use SPHERE\Common\Frontend\Table\Structure\TableRow;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class Frontend extends Extension
{

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
	}

	/**
	 * @param null|array $Search
	 * @return Stage
	 */
	public function frontendSearchPartNumber( $Search = null )
	{
		$Stage = new Stage('Geschäftsentwicklung', 'Teilenummer');
		$this->buttonStageDirectSearch($Stage);

        if( $Search ) {
            $MonthlyTurnoverResult = DataWareHouse::useService()->getMonthlyTurnover( $Search['PartNumber'], null, null );
            $LayoutTable = $this->tableMonthlyTurnover($MonthlyTurnoverResult);
        }
        else {
            $LayoutTable = '';
        }

        $Stage->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(
                            $this->formSearchPartNumber()
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

	public function frontendSearchProductManager( $Search = null )
	{
		$Stage = new Stage('Geschäftsentwicklung', 'Produktmanager');
		$this->buttonStageDirectSearch($Stage);

        if( $Search ) {
            $MonthlyTurnoverResult = DataWareHouse::useService()->getMonthlyTurnover( null, null, $Search['ProductManager'] );
            $LayoutTable = $this->tableMonthlyTurnover($MonthlyTurnoverResult);
            Debugger::screenDump($MonthlyTurnoverResult);
        }
        else {
            $LayoutTable = '';
        }

        $Stage->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(
                            $this->formSearchProductManager()
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

	public function frontendSearchMarketingCode( $Search = null )
	{
		$Stage = new Stage('Geschäftsentwicklung', 'Marketingcode');
		$this->buttonStageDirectSearch($Stage);

        if( $Search ) {
            $MonthlyTurnoverResult = DataWareHouse::useService()->getMonthlyTurnover( null, $Search['MarketingCode'], null );
            $LayoutTable = $this->tableMonthlyTurnover($MonthlyTurnoverResult);
            Debugger::screenDump($MonthlyTurnoverResult);
        }
        else {
            $LayoutTable = '';
        }

        $Stage->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(
                            $this->formSearchMarketingCode()
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


	/**
	 * @return Form
	 */
	private function formSearchPartNumber()
	{
		return new Form(
			new FormGroup(
				new FormRow(
					array(
						new FormColumn(
							new Panel('Suche', array(
								(new TextField('Search[PartNumber]', 'Teilenummer', 'Teilenummer eingeben', new Search()))
								->setRequired()
							), Panel::PANEL_TYPE_DEFAULT), 4
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

	private function formSearchProductManager()
	{
	    $EntityProductManager = DataWareHouse::useService()->getProductManagerAll();
		return new Form(
			new FormGroup(
				new FormRow(
                    new FormColumn(
                        new Panel('Suche', array(
                            new SelectBox('Search[ProductManager]', 'Produktmanager',
                                array( '{{Name}} {{Department}}' => $EntityProductManager )
                            )
                        )), 3
                    )
			    )
            ), array(
				new Primary('anzeigen', new Search()),
				new Reset('zurücksetzen')
			)
		);
	}

	private function formSearchMarketingCode()
	{
	    $EntityMarketingCode = DataWareHouse::useService()->getMarketingCodeAll();

		return new Form(
			new FormGroup(
				new FormRow(
					array(
						new FormColumn(
							new Panel('Suche', array(
								(new AutoCompleter('Search[MarketingCode]', 'Marketingcode', 'Marketingcode eingeben', array( 'Number' => $EntityMarketingCode)))
								->setRequired()
							), Panel::PANEL_TYPE_DEFAULT), 2
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

	private function tableMonthlyTurnover( $MonthlyTurnoverData ) {
        if( $MonthlyTurnoverData ) {
           $ReplaceArray = array(
               'Month' => 'Monat',
               'SumSalesGross' => 'Bruttoumsatz',
               'SumSalesNet' => 'Nettoumsatz',
               'SumQuantity' => 'Menge',
               'Discount' => 'Rabatt',
               '_AJ' => '',
               '_VJ' => '',
               '_VVJ' => '',
           );

           $MonthArray = array(
               '10' => 'Oktober',
               '11' => 'November',
               '12' => 'Dezember',
               '1' => 'Januar',
               '2' => 'Februar',
               '3' => 'März',
               '4' => 'April',
               '5' => 'Mai',
               '6' => 'Juni',
               '7' => 'Juli',
               '8' => 'August',
               '9' => 'September',
           );

            array_walk( $MonthlyTurnoverData, function( &$Row, $K, $MonthArray ) {
                $Row['Month'] = str_replace( array_keys( $MonthArray ), $MonthArray, $Row['Month']);
            }, $MonthArray);

           $Keys = array_keys($MonthlyTurnoverData[0]);
           $TableHead = array_combine( $Keys, str_replace( array_keys( $ReplaceArray ), $ReplaceArray, $Keys) );

           $SalesYearCurrent = DataWareHouse::useService()->getYearCurrentFromSales();

           //ToDo: Sortierung der DataTable, wie SQL-Array
           $tableMonthlyTurnover = new Table(
               $MonthlyTurnoverData, null, $TableHead, array(
                    "order" => [], //Initial Sortierung
                    "columnDefs" => array( //Definition der Spalten
                        array(
                            "orderable" => false, //Sortierung aus
                            "targets" => 0 //Spalte 1 (Monat)
                        )
                    ),
                   //"sort" => false, //Deaktivierung Sortierung aller Spalten
                   "paging" => false, //Deaktivieren Blättern
                   "responsive" => false,
               ), false
           );

           $tableMonthlyTurnover->prependHead(
               new TableHead(
                   new TableRow(
                       array(
                           new TableColumn('Monat', 1),
                           new TableColumn($SalesYearCurrent, 4),
                           new TableColumn(($SalesYearCurrent-1), 4),
                           new TableColumn(($SalesYearCurrent-2), 4),
                       )
                   )
               )
           );
           return $tableMonthlyTurnover;
        }
        else {
           return new Warning( 'Keine Daten vorhanden' );
        }
	}
}
