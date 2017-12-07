<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\Reporting\Controlling\MonthlyTurnover;


use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Document;
use MOC\V\Core\FileSystem\FileSystem;
use SPHERE\Application\Api\Reporting\Excel\ExcelDefault;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Application\Platform\Utility\Translation\LocaleTrait;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Data;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
use SPHERE\Common\Frontend\Chart\Repository\LineChart;
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
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\External;
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

    use LocaleTrait;

	private function buttonStageDirectSearch(Stage $Stage)
	{
        $Stage->addButton(
            new Standard('Produktmanager', new Route(__NAMESPACE__ . '/ProductManager'))
        );
        $Stage->addButton(
            new Standard('Marketingcode', new Route(__NAMESPACE__ . '/MarketingCode'))
        );
        $Stage->addButton(
            new Standard('Warengruppe', new Route(__NAMESPACE__ . '/ProductGroup'))
        );
        $Stage->addButton(
			new Standard('Teilenummer', new Route(__NAMESPACE__ . '/PartNumber'))
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
        $Stage->hasUtilityFavorite(true);
        $LayoutExcel = '';
        $MonthlyTurnoverLineChart = array();
        $LayoutLineChart = '';

        if( $Search ) {
            $MonthlyTurnoverResult = DataWareHouse::useService()->getMonthlyTurnover( $Search['PartNumber'], null, null );
            $LayoutTable = $this->tableMonthlyTurnover($MonthlyTurnoverResult);

            if($MonthlyTurnoverResult) {
                array_walk($MonthlyTurnoverResult, function(&$Row) use(&$MonthlyTurnoverLineChart) {

                    $MonthArray = array(
                        12 => 'Dez.',
                        11 => 'Nov.',
                        10 => 'Okt.',
                        1 => 'Jan.',
                        2 => 'Feb.',
                        3 => 'Mrz.',
                        4 => 'Apr.',
                        5 => 'Mai',
                        6 => 'Jun.',
                        7 => 'Jul.',
                        8 => 'Aug.',
                        9 => 'Sep.',
                    );

                    array_push($MonthlyTurnoverLineChart, array(
                        'Month' => str_replace(array_keys($MonthArray), $MonthArray, $Row['Month']),
                        'Data_SumSalesGross_AJ' => $Row['Data_SumSalesGross_AJ'],
                        'Data_SumSalesGross_VJ' => $Row['Data_SumSalesGross_VJ'],
                        'Data_SumSalesGross_VVJ' => $Row['Data_SumSalesGross_VVJ'],
                    ));
                });

                $LayoutExcel = '<br/>'.(new External('ExcelDownload', ExcelDefault::getEndpoint(), null, array(
                    ExcelDefault::API_TARGET => 'getExcelMonthlyTurnover',
                    'FileName' => 'Geschaeftsentwicklung',
                    'FileTyp' => 'xlsx',
                    'ProductManagerId' => $Search['PartNumber']
                ) ));

                $SalesYearCurrent = DataWareHouse::useService()->getYearCurrentFromSales();
                $LayoutLineChart = new LineChart($MonthlyTurnoverLineChart, array($SalesYearCurrent,($SalesYearCurrent-1),($SalesYearCurrent-2)));
            }
            else {
                $LayoutLineChart = '';
            }

            if($MonthlyTurnoverResult) {
                $LayoutExcel = '<br/>'.(new External('ExcelDownload', ExcelDefault::getEndpoint(), null, array(
                    ExcelDefault::API_TARGET => 'getExcelMonthlyTurnover',
                    'FileName' => 'Geschaeftsentwicklung',
                    'FileTyp' => 'xlsx',
                    'PartNumber' => $Search['PartNumber']
                ) ));
            }
        }
        else {
            $LayoutTable = '';
            $LayoutLineChart = '';
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
                        ),
                        new LayoutColumn(
                            $LayoutLineChart
                        ),
                        new LayoutColumn(
                            $LayoutExcel
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
        $LayoutExcel = '';
        $MonthlyTurnoverLineChart = array();
        $LayoutLineChart = '';

        if( $Search ) {
            $MonthlyTurnoverResult = DataWareHouse::useService()->getMonthlyTurnover( null, null, $Search['ProductManager'] );
            $LayoutTable = $this->tableMonthlyTurnover($MonthlyTurnoverResult);

            if($MonthlyTurnoverResult) {
                $LayoutExcel = '<br/>'.(new External('ExcelDownload', ExcelDefault::getEndpoint(), null, array(
                    ExcelDefault::API_TARGET => 'getExcelMonthlyTurnover',
                    'FileName' => 'Geschaeftsentwicklung',
                    'FileTyp' => 'xlsx',
                    'ProductManagerId' => $Search['ProductManager']
                ) ));

                array_walk($MonthlyTurnoverResult, function(&$Row) use(&$MonthlyTurnoverLineChart) {

                    $MonthArray = array(
                        12 => 'Dez.',
                        11 => 'Nov.',
                        10 => 'Okt.',
                        1 => 'Jan.',
                        2 => 'Feb.',
                        3 => 'Mrz.',
                        4 => 'Apr.',
                        5 => 'Mai',
                        6 => 'Jun.',
                        7 => 'Jul.',
                        8 => 'Aug.',
                        9 => 'Sep.',
                    );

                    array_push($MonthlyTurnoverLineChart, array(
                        'Month' => str_replace(array_keys($MonthArray), $MonthArray, $Row['Month']),
                        'Data_SumSalesGross_AJ' => $Row['Data_SumSalesGross_AJ'],
                        'Data_SumSalesGross_VJ' => $Row['Data_SumSalesGross_VJ'],
                        'Data_SumSalesGross_VVJ' => $Row['Data_SumSalesGross_VVJ'],
                    ));
                });

                $SalesYearCurrent = DataWareHouse::useService()->getYearCurrentFromSales();
                $LayoutLineChart = new LineChart($MonthlyTurnoverLineChart, array($SalesYearCurrent,($SalesYearCurrent-1),($SalesYearCurrent-2)));

            }
            else {
                $LayoutLineChart = '';
            }


        }
        else {
            $LayoutTable = '';
            $LayoutLineChart = '';
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
                        ),
                        new LayoutColumn(
                            $LayoutLineChart
                        ),
                        new LayoutColumn(
                            $LayoutExcel
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
		$LayoutExcel = '';
		$TableMasterData = '';
        $MonthlyTurnoverLineChart = array();
        $LayoutLineChart = '';

        if( $Search ) {
            $MonthlyTurnoverResult = DataWareHouse::useService()->getMonthlyTurnover( null, $Search['MarketingCode'], null );
            $LayoutTable = $this->tableMonthlyTurnover($MonthlyTurnoverResult);

            if($MonthlyTurnoverResult) {

                array_walk($MonthlyTurnoverResult, function(&$Row) use(&$MonthlyTurnoverLineChart) {

                    $MonthArray = array(
                        12 => 'Dez.',
                        11 => 'Nov.',
                        10 => 'Okt.',
                        1 => 'Jan.',
                        2 => 'Feb.',
                        3 => 'Mrz.',
                        4 => 'Apr.',
                        5 => 'Mai',
                        6 => 'Jun.',
                        7 => 'Jul.',
                        8 => 'Aug.',
                        9 => 'Sep.',
                    );

                    array_push($MonthlyTurnoverLineChart, array(
                        'Month' => str_replace(array_keys($MonthArray), $MonthArray, $Row['Month']),
                        'Data_SumSalesGross_AJ' => $Row['Data_SumSalesGross_AJ'],
                        'Data_SumSalesGross_VJ' => $Row['Data_SumSalesGross_VJ'],
                        'Data_SumSalesGross_VVJ' => $Row['Data_SumSalesGross_VVJ'],
                    ));
                });

                $SalesYearCurrent = DataWareHouse::useService()->getYearCurrentFromSales();
                $LayoutLineChart = new LineChart($MonthlyTurnoverLineChart, array($SalesYearCurrent,($SalesYearCurrent-1),($SalesYearCurrent-2)));

                $LayoutExcel = '<br/>'.(new External('Excel-Download', ExcelDefault::getEndpoint(), null, array(
                    ExcelDefault::API_TARGET => 'getExcelMonthlyTurnover',
                    'FileName' => 'Geschaeftsentwicklung',
                    'FileTyp' => 'xlsx',
                    'MarketingCodeNumber' => $Search['MarketingCode'],
                 ) ));
            }
            else {
                $LayoutLineChart = '';
            }

            $EntityMarketingCode = DataWareHouse::useService()->getMarketingCodeByNumber( $Search['MarketingCode'] );
            if($EntityMarketingCode) {
                $EntityMarketingCodeProductGroup = DataWareHouse::useService()->getMarketingCodeProductGroupByMarketingCode($EntityMarketingCode);
                if($EntityMarketingCodeProductGroup) {
                    $ProductGroupText = '';
                    $EntityProductGroupList = DataWareHouse::useService()->getProductGroupByMarketingCodeProductGroup($EntityMarketingCodeProductGroup);

                    /** @var TblReporting_ProductGroup $ProductGroup */
                    foreach($EntityProductGroupList AS $Index => $ProductGroup) {
                        if( $Index != 0 ) {
                           $ProductGroupText .= '<br/>';
                        }
                        $ProductGroupText .= $ProductGroup->getNumber().' - '.$ProductGroup->getName();
                    }

                    if($EntityProductGroupList) {
                        $TableMasterData =
                            new Table(
                                array(
                                    array(
                                        'Description' => 'MarketingCode',
                                        'Value' => $EntityMarketingCode->getNumber().' - '.$EntityMarketingCode->getName()
                                    ),
                                    array(
                                        'Description' => 'Warengruppe',
                                        'Value' => $ProductGroupText
                                    )
                                ),
                                null,
                                array( 'Description' => 'Bezeichnung ', 'Value' => '' ),
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
                }
            }
        }
        else {
            $LayoutTable = '';
        }

        $Stage->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(
                            $this->formSearchMarketingCode(), 3
                        ),
                        new LayoutColumn(
                            '&nbsp;',1
                        ),
                        new LayoutColumn(
                            $TableMasterData, 6
                        ),
                        new LayoutColumn(
                            $LayoutTable
                        ),
                        new LayoutColumn(
                            $LayoutLineChart
                        ),
                        new LayoutColumn(
                            $LayoutExcel
                        )
                    ))
                )
            )
        );

		return $Stage;
	}

	public function frontendSearchProductGroup( $Search = null )
	{
		$Stage = new Stage('Geschäftsentwicklung', 'Warengruppe');
		$this->buttonStageDirectSearch($Stage);
		$LayoutExcel = '';
		$TableMasterData = '';
        $MonthlyTurnoverLineChart = array();
        $LayoutLineChart = '';

        if( $Search ) {
            $MonthlyTurnoverResult = DataWareHouse::useService()->getMonthlyTurnover( null, null, null, $Search['ProductGroupNumber'] );
            $LayoutTable = $this->tableMonthlyTurnover($MonthlyTurnoverResult);

            if($MonthlyTurnoverResult) {

                array_walk($MonthlyTurnoverResult, function(&$Row) use(&$MonthlyTurnoverLineChart) {

                    $MonthArray = array(
                        12 => 'Dez.',
                        11 => 'Nov.',
                        10 => 'Okt.',
                        1 => 'Jan.',
                        2 => 'Feb.',
                        3 => 'Mrz.',
                        4 => 'Apr.',
                        5 => 'Mai',
                        6 => 'Jun.',
                        7 => 'Jul.',
                        8 => 'Aug.',
                        9 => 'Sep.',
                    );

                    array_push($MonthlyTurnoverLineChart, array(
                        'Month' => str_replace(array_keys($MonthArray), $MonthArray, $Row['Month']),
                        'Data_SumSalesGross_AJ' => $Row['Data_SumSalesGross_AJ'],
                        'Data_SumSalesGross_VJ' => $Row['Data_SumSalesGross_VJ'],
                        'Data_SumSalesGross_VVJ' => $Row['Data_SumSalesGross_VVJ'],
                    ));
                });

                $SalesYearCurrent = DataWareHouse::useService()->getYearCurrentFromSales();
                $LayoutLineChart = new LineChart($MonthlyTurnoverLineChart, array($SalesYearCurrent,($SalesYearCurrent-1),($SalesYearCurrent-2)));

                $LayoutExcel = '<br/>'.(new External('Excel-Download', ExcelDefault::getEndpoint(), null, array(
                    ExcelDefault::API_TARGET => 'getExcelMonthlyTurnover',
                    'FileName' => 'Geschaeftsentwicklung',
                    'FileTyp' => 'xlsx',
                    'ProductGroupNumber' => $Search['ProductGroupNumber'],
                 ) ));
            }
            else {
                $LayoutLineChart = '';
            }

            $EntityProductGroup = DataWareHouse::useService()->getProductGroupByNumber( $Search['ProductGroupNumber'] );
            if($EntityProductGroup) {
                $EntityMarketingCodeProductGroup = DataWareHouse::useService()->getMarketingCodeProductGroupByProductGroup($EntityProductGroup);
                if($EntityMarketingCodeProductGroup) {
                    $MarketingCodeText = '';
                    $EntityMarketingCodeList = DataWareHouse::useService()->getMarketingCodeByMarketingCodeProductGroup($EntityMarketingCodeProductGroup);

                    if($EntityMarketingCodeList) {
                        /** @var TblReporting_MarketingCode $MarketingCode*/
                        foreach($EntityMarketingCodeList AS $Index => $MarketingCode) {
                            if( $Index != 0 ) {
                               $MarketingCodeText .= '<br/>';
                            }
                            $MarketingCodeText .= $MarketingCode->getNumber().' - '.$MarketingCode->getName();
                        }
                        $TableMasterData =
                            new Table(
                                array(
                                    array(
                                        'Description' => 'Warengruppe',
                                        'Value' => $EntityProductGroup->getNumber().' - '.$EntityProductGroup->getName()
                                    ),
                                    array(
                                        'Description' => 'Marketingcode',
                                        'Value' => $MarketingCodeText
                                    ),
                                ),
                                null,
                                array( 'Description' => 'Bezeichnung ', 'Value' => '' ),
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
                }
            }
        }
        else {
            $LayoutTable = '';
        }

        $Stage->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(
                            $this->formSearchProductGroup(), 3
                        ),
                        new LayoutColumn(
                            '&nbsp;',1
                        ),
                        new LayoutColumn(
                            $TableMasterData, 6
                        ),
                        new LayoutColumn(
                            $LayoutTable
                        ),
                        new LayoutColumn(
                            $LayoutLineChart
                        ),
                        new LayoutColumn(
                            $LayoutExcel
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
								->setRequired()->setAutoFocus()
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

	private function formSearchProductGroup()
	{
	    $EntityProductGroup = DataWareHouse::useService()->getProductGroupAll();

		return new Form(
			new FormGroup(
				new FormRow(
					array(
						new FormColumn(
							new Panel('Suche', array(
								(new AutoCompleter('Search[ProductGroupNumber]', 'Warengruppe', 'Warengruppe eingeben', array( 'Number' => $EntityProductGroup)))
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

	private function tableMonthlyTurnover( $MonthlyTurnoverData ) {
        if( $MonthlyTurnoverData ) {

            $ReplaceArray = array(
               'Data_' => '',
               'Group_' => '',
               'Month' => 'Monat',
               'SumSalesGross' => 'Bruttoumsatz',
               'SumSalesNet' => 'Nettoumsatz',
               'SumQuantity' => 'Anzahl effektiv',
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

                //AJ
                 //if( $Row['Data_SumSalesGross_AJ'] != 0 ) {
                     $Row['Data_SumSalesGross_AJ'] = new PullRight( $this->doLocalize($Row['Data_SumSalesGross_AJ'])->getCurrency() );
                 //}
                 //if( $Row['Data_SumSalesNet_AJ'] != 0 ) {
                     $Row['Data_SumSalesNet_AJ'] = new PullRight( $this->doLocalize($Row['Data_SumSalesNet_AJ'])->getCurrency() );
                 //}
                 //if( $Row['Data_SumQuantity_AJ'] != 0 ) {
                     $Row['Data_SumQuantity_AJ'] = new PullRight( number_format($Row['Data_SumQuantity_AJ'],0,'','.') );
                 //}
                 //if( $Row['Data_Discount_AJ'] !== 0 ) {
                     $Row['Data_Discount_AJ'] = new PullRight( number_format($Row['Data_Discount_AJ'],2,',','.').' %' );
                 //}

                //VJ
                 //if( $Row['Data_SumSalesGross_VJ'] != 0 ) {
                     $Row['Data_SumSalesGross_VJ'] = new PullRight( $this->doLocalize($Row['Data_SumSalesGross_VJ'])->getCurrency() );
                 //}
                 //if( $Row['Data_SumSalesNet_VJ'] != 0 ) {
                     $Row['Data_SumSalesNet_VJ'] = new PullRight( $this->doLocalize($Row['Data_SumSalesNet_VJ'])->getCurrency() );
                 //}
                 //if( $Row['Data_SumQuantity_VJ'] != 0 ) {
                     $Row['Data_SumQuantity_VJ'] = new PullRight( number_format($Row['Data_SumQuantity_VJ'],0,'','.') );
                 //}
                 //if( $Row['Data_Discount_VJ'] != 0 ) {
                     $Row['Data_Discount_VJ'] = new PullRight( number_format($Row['Data_Discount_VJ'],2,',','.').' %' );
                 //}

                //VVJ
                 //if( $Row['Data_SumSalesGross_VVJ'] != 0 ) {
                     $Row['Data_SumSalesGross_VVJ'] = new PullRight( $this->doLocalize($Row['Data_SumSalesGross_VVJ'])->getCurrency() );
                 //}
                 //if( $Row['Data_SumSalesNet_VVJ'] != 0 ) {
                     $Row['Data_SumSalesNet_VVJ'] = new PullRight( $this->doLocalize($Row['Data_SumSalesNet_VVJ'])->getCurrency() );
                 //}
                 //if( $Row['Data_SumQuantity_VVJ'] != 0 ) {
                     $Row['Data_SumQuantity_VVJ'] = new PullRight( number_format($Row['Data_SumQuantity_VVJ'],0,'','.') );
                 //}
                 //if( $Row['Data_Discount_VVJ'] != 0 ) {
                     $Row['Data_Discount_VVJ'] = new PullRight( number_format($Row['Data_Discount_VVJ'],2,',','.').' %' );
                 //}
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
