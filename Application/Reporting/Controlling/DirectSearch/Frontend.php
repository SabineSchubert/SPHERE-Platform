<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:15
 */

namespace SPHERE\Application\Reporting\Controlling\DirectSearch;


use Doctrine\Common\Util\Debug;
use Nette\DateTime;
use SPHERE\Application\Api\Reporting\Controlling\DirectSearch\CompetitionTable;
use SPHERE\Application\Api\Reporting\Excel\ExcelDirectSearch;
use SPHERE\Application\Platform\Utility\Translation\LocaleTrait;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_Supplier;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_PartsMore;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Price;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Section;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Supplier;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\ViewPart;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\ViewPrice;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Field\AutoCompleter;
use SPHERE\Common\Frontend\Form\Repository\Field\SelectBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Info;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\External;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Repository\Title as TableTitle;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Table\Structure\TableColumn;
use SPHERE\Common\Frontend\Table\Structure\TableHead;
use SPHERE\Common\Frontend\Table\Structure\TableRow;
use SPHERE\Common\Frontend\Text\Repository\Bold;
use SPHERE\Common\Frontend\Text\Repository\Tooltip;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Database\Filter\Link\Pile;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class Frontend extends Extension
{

    use LocaleTrait;

	private function buttonStageDirectSearch(Stage $Stage)
	{
		$Stage->addButton(
			new Standard('Teilenummer', new Route(__NAMESPACE__ . '/PartNumber'))
		);
        $Stage->addButton(
            new Standard('Marketingcode', new Route(__NAMESPACE__ . '/MarketingCode'))
        );
		$Stage->addButton(
			new Standard('Produktmanager', new Route(__NAMESPACE__ . '/ProductManager'))
		);
	}

	/**
	 * @param null|array $Search
	 * @return Stage
	 */
	public function frontendSearchPartNumber( $Search = null )
	{
		$Stage = new Stage('Direktsuche', 'Teilenummer');
		$this->buttonStageDirectSearch($Stage);
        $Stage->hasUtilityFavorite(true);

		$LayoutGroupDirectSearch = '';
		$LayoutGroupCompetition = '';
		$LayoutExcelDownload = '';
        $ErrorPartNumber = '';
        $EntityPart = null;
		if( $Search ) {

            $EntityPart = DataWareHouse::useService()->getPartByNumber( $Search['PartNumber'] );

			if (!empty($EntityPart)) {

				$LayoutGroupDirectSearch =
					new LayoutGroup(
						array(
							new LayoutRow(
								array(
									new LayoutColumn(
										$this->tableMasterDataPartNumber( $EntityPart )
										, 6
									),
									new LayoutColumn( '&nbsp;', 1 ),
									new LayoutColumn( $this->tablePriceDataPartNumber($EntityPart), 5)
								)
							),
							new LayoutRow(
								new LayoutColumn(
									$this->tablePriceDevelopmentPartNumber( $EntityPart ), 12
								)
							),
							new LayoutRow(
								new LayoutColumn(
									$this->tableSalesDataPartNumber( $EntityPart )
									, 12
								)
							),
							new LayoutRow(
								new LayoutColumn(
									'&nbsp;'
								)
							),
							new LayoutRow(
								new LayoutColumn(
									'Diagramme', 12
								)
							)
						),
						new Title('Direktsuche')
					);

				//Zusatzinformationen, wenn Q440-Teilenummer
				if( substr( $Search['PartNumber'], 0, 4) == 'Q440' ) {
					$ExtraPartNumber =
						new LayoutRow(
							new LayoutColumn(
								$this->tableCompetitionExtraPartNumber( $Search['PartNumber'] ), 6
							)
						);
				} else {
					$ExtraPartNumber = '';
				}

				//Wettbewerbsdaten
				$LayoutGroupCompetition =
					new LayoutGroup(
						array(
							$ExtraPartNumber,
							new LayoutRow(
								new LayoutColumn(
									$this->tableCompetitionDataPartNumber( $EntityPart->getNumber() ), 12
								)
							)
						),
						new Title('Angebotsdaten')
					);

				//Excel-Download
                $LayoutExcelDownload = new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(
                            new External( 'Excel-Download', ExcelDirectSearch::getEndpoint(), null, array(
                                ExcelDirectSearch::API_TARGET => 'getExcel',
                                'PartNumber' => $Search['PartNumber']
                            ) ), 12
                        )
                    ),
                    new Title('')
                );
			} else {
				$ErrorPartNumber = new Warning('Die Teilenummer konnte nicht gefunden werden.');
			}
		}

		$Stage->setContent(
			new Layout(array(
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(
							$this->formSearchPartNumber()
						,4)
					)
				),
				new LayoutGroup(
				    new LayoutRow(
				        new LayoutColumn($ErrorPartNumber)
                    )
                ),
				$LayoutGroupDirectSearch,
				$LayoutGroupCompetition,
                $LayoutExcelDownload
			))
		);



//
//		$Stage->setContent(
//			new Layout(
//				new LayoutGroup(
//					new LayoutRow(
//						array(
//							new LayoutColumn(
//
//								new Form(
//									new FormGroup(
//										new FormRow(
//											array(
//												new FormColumn(
//													new Panel('Suche', array(
//														new TextField('PartNumber', 'Teilenummer', 'Teilenummer eingeben', new Search()),
//														new TextField('PartNumber', 'Teilenummer', 'Teilenummer eingeben', new Search())
//													)), 4
//												),
//												new FormColumn(
//													(new SelectBox(
//														'ProductManager', 'Produktmanager', array(
//														0 => '',
//														'AS' => 'Andreas Schneider',
//														'SK' => 'Stefan Klinke',
//														'SH' => 'Stefan Hahn'
//													)))->setDefaultValue('AS'), 4
//												),
//												new FormColumn(
//													new AutoCompleter('MarketingCode', 'Marketingcode', 'Marketingcode eingeben', array('1P123')), 4
//												)
//											)
//										)
//									)
//									, array(
//										new Primary('anzeigen', new Search()),
//										new Reset('zurücksetzen')
//									)
//								)
//							)
//						)
//					)
//				)
//			)
//		);

		return $Stage;
	}

	public function frontendSearchProductManager( $Search = null )
	{
		$Stage = new Stage('Direktsuche', 'Produktmanager');
		$this->buttonStageDirectSearch($Stage);
        $Stage->hasUtilityFavorite(true);

		$LayoutGroupDirectSearch = '';
		$LayoutGroupExcel = '';
		$ErrorProductManager = '';
		if( $Search ) {

		    $EntityProductManager = DataWareHouse::useService()->getProductManagerById( $Search['ProductManager'] );

			if (!empty($EntityProductManager)) {
				$LayoutGroupDirectSearch =
					new LayoutGroup(
						array(
							new LayoutRow(
								new LayoutColumn(
									$this->tableMasterDataProductManager( $EntityProductManager )
									, 12
								)
							),
							new LayoutRow(
								new LayoutColumn(
									$this->tableSalesDataProductManager( $EntityProductManager )
									, 12
								)
							),
							new LayoutRow(
								new LayoutColumn(
									'&nbsp;'
								)
							),
							new LayoutRow(
								new LayoutColumn(
									'Diagramme', 12
								)
							)
						),
						new Title('Direktsuche')
					);
                $LayoutGroupExcel =
                    new LayoutGroup(
                        new LayoutRow(
                            new LayoutColumn(
                                new External( 'Excel-Download', ExcelDirectSearch::getEndpoint(), null, array(
                                    ExcelDirectSearch::API_TARGET => 'getExcel',
                                    'ProductManagerId' => $Search['ProductManager']
                                ) ), 12
                            )
                        ),
                        new Title('')
                    );
			} else {
				$ErrorProductManager = new Warning('Es wurde kein gültiger Produktmanager ausgewählt.');
			}
		}

		$Stage->setContent(
			new Layout(array(
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(
							$this->formSearchProductManager()
							,4
						)
					)
				),
				new LayoutGroup(
				    new LayoutRow(
				        new LayoutColumn(
				            $ErrorProductManager
                        )
                    )
                ),
				$LayoutGroupDirectSearch,
                $LayoutGroupExcel
			))
		);
		return $Stage;
	}


	public function frontendSearchMarketingCode( $Search = null )
	{
		$Stage = new Stage('Direktsuche', 'Marketingcode');
		$this->buttonStageDirectSearch($Stage);
        $Stage->hasUtilityFavorite(true);

		$LayoutGroupDirectSearch = '';
        $LayoutGroupExcel = '';
		$ErrorMarketingCode = '';

		if( $Search ) {

		    $EntityMarketingCode = DataWareHouse::useService()->getMarketingCodeByNumber( $Search['MarketingCode'] );

			if (!empty($EntityMarketingCode)) {
				$LayoutGroupDirectSearch =
					new LayoutGroup(
						array(
							new LayoutRow(
								new LayoutColumn(
									$this->tableMasterDataMarketingCode( $EntityMarketingCode )
									, 6
								)
							),
							new LayoutRow(
								new LayoutColumn(
									$this->tableSalesDataMarketingCode( $EntityMarketingCode )
									, 12
								)
							),
							new LayoutRow(
								new LayoutColumn(
									'&nbsp;'
								)
							),
							new LayoutRow(
								new LayoutColumn(
									'Diagramme', 12
								)
							)
						),
						new Title('Direktsuche')
					);
				$LayoutGroupExcel =
                    new LayoutGroup(
                        new LayoutRow(
                            new LayoutColumn(
                                new External( 'Excel-Download', ExcelDirectSearch::getEndpoint(), null, array(
                                    ExcelDirectSearch::API_TARGET => 'getExcel',
                                    'MarketingCodeNumber' => $Search['MarketingCode']
                                ) ), 12
                            )
                        ),
                        new Title('')
                    );
			} else {
                $ErrorMarketingCode = new Warning('Der Marketingcode konnte nicht gefunden werden!');
			}
		}

		$Stage->setContent(
			new Layout(array(
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(
							$this->formSearchMarketingCode()
						,4)
					)
				),
				new LayoutGroup(
				    new LayoutRow(
				        new LayoutColumn(
                            $ErrorMarketingCode
                        )
                    )
                ),
				$LayoutGroupDirectSearch,
                $LayoutGroupExcel
			))
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

	private function formSearchProductManager()
	{
		$EntityProductManager = DataWareHouse::useService()->getProductManagerAll();

		return new Form(
			new FormGroup(
				new FormRow(
					array(
						new FormColumn(
							new Panel('Suche', array(
								(new SelectBox('Search[ProductManager]', 'Produktmanager',
//                                  array(0 => '-[ Nicht ausgewählt ]-', 'AS' => 'Andreas Schneider', 'SK' => 'Stefan Klinke', 'SH' => 'Stefan Hahn')
                                    array( '{{Name}} {{Department}}' => $EntityProductManager )
//                                    array( 'Name' => $EntityProductManager )
                                ,null, true, null))//->configureLibrary( SelectBox::LIBRARY_SELECT2 )
//								->setRequired() //ToDo: das gibt es bei der SelectBox noch nicht
							), Panel::PANEL_TYPE_INFO)
						),
					)
				)
			)
			, array(
				new Primary('anzeigen', new Search()),
//				new Reset('zurücksetzen')
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
								(new AutoCompleter('Search[MarketingCode]', 'Marketingcode', 'Marketingcode eingeben',
//                                  array('1P123')
                                    array( 'Number' => $EntityMarketingCode )
                                ))

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

	private function tableMasterDataPartNumber(TblReporting_Part $EntityPart) {

        $EntityMarketingCode = null;
        $EntityProductManager = null;
        $EntityAssortmentGroup = null;
        $EntitySupplierList = null;
	    if($EntityPart) {
            $EntityMarketingCode = $EntityPart->fetchMarketingCodeCurrent();

            if ($EntityMarketingCode) {
                $EntityProductManager = $EntityMarketingCode->fetchProductManagerCurrent();
            }

            $EntityAssortmentGroup = $EntityPart->fetchAssortmentGroupCurrent();

            //Warengruppe
            if($EntityMarketingCode) {
                $EntityProductGroupList = $EntityMarketingCode->fetchProductGroupListCurrent();
                $ProductGroupText = '';
                if($EntityProductGroupList) {
                    /** @var TblReporting_ProductGroup $ProductGroup */
                    foreach($EntityProductGroupList AS $Index => $ProductGroup) {
                        if( $Index != 0 ) {
                            $ProductGroupText .= '<br/>';
                        }
                        $ProductGroupText .= $ProductGroup->getNumber().' - '.$ProductGroup->getName();
                    }
                }
            }
            else {
                $ProductGroupText = '';
            }

            //Sparte
            $EntitySectionList = $EntityPart->fetchSectionListCurrent();

            $SectionText = '';
            if( count($EntitySectionList) > 0 ) {
                /** @var TblReporting_Section $Section */
                foreach($EntitySectionList AS $Index => $Section) {
                    if( $Index != 0 ) {
                        $SectionText .= '<br/>';
                    }
                    $SectionText .= $Section->getNumber().' - '.$Section->getName();
                }
            }

            //Lieferanten
            $EntitySupplierList = $EntityPart->fetchSupplierListCurrent();
            $SuppliertText = '';
            if( $EntitySupplierList ) {
                /** @var TblReporting_Supplier $Supplier */
                foreach($EntitySupplierList AS $Index => $Supplier) {
                    if( $Index != 0 ) {
                        $SuppliertText .= '<br/>';
                    }
                    $SuppliertText .= $Supplier->getNumber().' - '.$Supplier->getName();
                }
            }

            return new Table(
                array(
                    array(
                        'Description' => 'Teilenummer',
                        'Value' => $EntityPart->getNumber() . ' - ' . $EntityPart->getName()
                    ),
                    array(
                        'Description' => 'ET-Baumuster',
                        'Value' => $EntityPart->getSparePartDesign()
                    ),
                    array(
                        'Description' => 'Vorgänger<br/>Nachfolger<br/>Wahlweise',
                        'Value' => (new Link( $EntityPart->getPredecessor(), __NAMESPACE__.'/PartNumber', null, array( 'Search' => array( 'PartNumber' => $EntityPart->getPredecessor() ) ) ))
                            .(($EntityPart->getSuccessor() != '')? '<br/>'.(new Link( $EntityPart->getSuccessor(), __NAMESPACE__.'/PartNumber', null, array( 'Search' => array( 'PartNumber' => $EntityPart->getSuccessor() ) ) )):'').(($EntityPart->getOptionalNumber() != '')? '<br/>'.(new Link( $EntityPart->getOptionalNumber(), __NAMESPACE__.'/PartNumber', null, array( 'Search' => array( 'PartNumber' => $EntityPart->getOptionalNumber() ) ) )):'')
                    ),
                    array(
                        'Description' => 'Sortimentsgruppe',
                        'Value' => (( $EntityAssortmentGroup ) ? $EntityAssortmentGroup->getNumber().' - '. $EntityAssortmentGroup->getName() : '' )
                    ),
                    array(
                        'Description' => 'Marketingcode',
                        'Value' => (( $EntityMarketingCode )? (new Link( $EntityMarketingCode->getNumber(), __NAMESPACE__.'/MarketingCode', null, array( 'Search' => array( 'MarketingCode' => $EntityMarketingCode->getNumber() ) ) )) . ' - ' . $EntityMarketingCode->getName(): '' )
                    ),
                    array(
                        'Description' => 'Warengruppe',
                        'Value' => $ProductGroupText
                    ),
                    array(
                        'Description' => 'Sparte',
                        'Value' => $SectionText
                    ),
                    array(
                        'Description' => 'Produktmanager',
                        'Value' => (( $EntityProductManager )? $EntityProductManager->getName() . ' - ' . $EntityProductManager->getDepartment(): '' )
                    ),
                    array(
                        'Description' => 'Lieferant(en)',
                        'Value' => $SuppliertText
                    ),
                ),
                new TableTitle('Allgemeine Informationen'),
                array('Description' => 'Bezeichnung', 'Value' => ''),
                array(
                    "columnDefs" => array(
                        array('width' => '40%', 'targets' => '0'),
                        array('width' => '60%', 'targets' => '1')
                    ),
                    "paging" => false, // Deaktivieren Blättern
                    "iDisplayLength" => -1,    // Alle Einträge zeigen
                    "searching" => false, // Deaktivieren Suchen
                    "info" => false,  // Deaktivieren Such-Info
                    "sort" => false   //Deaktivierung Sortierung der Spalten
                )
            );
        }
        else {
	        return '';
        }
	}

	private function tableMasterDataMarketingCode( TblReporting_MarketingCode $EntityMarketingCode ) {

	    if($EntityMarketingCode) {

            $EntityProductManager = $EntityMarketingCode->fetchProductManagerCurrent();
            $EntityPartsMore = $EntityMarketingCode->fetchPartsMoreCurrent();

            //Warengruppe
            $EntityProductGroupList = $EntityMarketingCode->fetchProductGroupListCurrent();
            $ProductGroupText = '';
            if($EntityProductGroupList) {
                /** @var TblReporting_ProductGroup $ProductGroup */
                foreach($EntityProductGroupList AS $Index => $ProductGroup) {
                    if( $Index != 0 ) {
                        $ProductGroupText .= '<br/>';
                    }
                    $ProductGroupText .= $ProductGroup->getNumber().' - '.$ProductGroup->getName();
                }
            }

            $EntityPartList = $EntityMarketingCode->fetchPartListCurrent();

            return new Table(
                array(
                    array(
                        'Description' => 'Marketingcode',
                        'Value' => $EntityMarketingCode->getNumber() . ' - '. $EntityMarketingCode->getName()
                    ),
                    array(
                        'Description' => 'Warengruppe',
                        'Value' => $ProductGroupText
                    ),
                    array(
                        'Description' => 'P+M',
                        'Value' => (( $EntityPartsMore )? (( $EntityPartsMore->getType() == 'Prozent' )? number_format( $EntityPartsMore->getValue(), 2, ',', '.' ).' %': number_format( $EntityPartsMore->getValue(), 2, ',', '.' ).' €') : '' )
                    ),
                    array( //ToDo: ersetzen
                        'Description' => 'Anzahl TNR',
                        'Value' => count($EntityPartList)
                    ),
                    array(
                        'Description' => 'Produktmanager',
                        'Value' => ( ( $EntityProductManager )? $EntityProductManager->getName().' ('.$EntityProductManager->getDepartment().')' : '' )
                    ),
                ),
                new TableTitle('Allgemeine Informationen'),
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
        else {
	        return false;
        }
	}

	private function tableMasterDataProductManager( TblReporting_ProductManager $EntityProductManager ) {

	    if( $EntityProductManager ) {

            //Marketingcode
            $EntityMarketingCodeList = $EntityProductManager->fetchMarketingCodeListCurrent();
            $EntityProductGroupList = null;
            $EntityPartsMoreList = null;
            $EntityPartList = null;
            $MarketingCodeText = '';
            $ProductGroupText = '';
            $PartsMoreText = '';
            $PartText = '';
            if ($EntityMarketingCodeList) {

                //ToDo: optimieren (zuviel foreach)

                /** @var TblReporting_MarketingCode $MarketingCode */
                foreach ($EntityMarketingCodeList AS $Index => $MarketingCode) {
                    if ($Index != 0) {
                        $MarketingCodeText .= ', ';
                    }
                    $MarketingCodeText .= $MarketingCode->getNumber() . ' - ' . $MarketingCode->getName();

                    $EntityProductGroupList[$MarketingCode->getNumber()] = $MarketingCode->fetchProductGroupListCurrent();

                    $EntityPartsMoreList[$MarketingCode->getNumber()] = $MarketingCode->fetchPartsMoreCurrent();

//                    $EntityPartList[$MarketingCode->getNumber()] = $MarketingCode->fetchPartListCurrent();
               //     $EntityPartList[$MarketingCode->getNumber()] = DataWareHouse::useService()->getPartByMarketingCode( $MarketingCode );

                }

                //ProductGruppe
                if ($EntityProductGroupList) {
                    $Z = 0;
                    foreach ($EntityProductGroupList AS $MarketingCode => $ProductGroupList) {
                        if ($Z !== 0) {
                            $ProductGroupText .= '<br/>';
                        }
                        $Z++;
                        $ProductGroupText .= $MarketingCode . ': ';
                        if (count((array)$ProductGroupList) == 0) {
                            $ProductGroupText .= 'unbekannt';
                        } else {
                            /** @var TblReporting_ProductGroup $ProductGroup */
                            foreach ((array)$ProductGroupList AS $Index => $ProductGroup) {
                                if ($Index !== 0) {
                                    $ProductGroupText .= ', ';
                                }
                                $ProductGroupText .= $ProductGroup->getNumber() . ' - ' . $ProductGroup->getName();
                            }
                        }
                    }
                }

                //PartsMore
                if ($EntityPartsMoreList) {
                    $Z = 0;
                    /** @var TblReporting_PartsMore $EntityPartsMore */
                    foreach ($EntityPartsMoreList AS $MarketingCode => $EntityPartsMore) {
                        if ($EntityPartsMore) {
//                            if ($Z !== 0) {
//                                $PartsMoreText .= '<br/>';
//                            }
//                            $Z++;
                            $PartsMoreText .= $MarketingCode.', ';
                            // . ': ' . (($EntityPartsMore->getType() == 'Prozent') ? number_format($EntityPartsMore->getValue(),
//                                        2, ',', '.') . ' %' : number_format($EntityPartsMore->getValue(), 2, ',',
//                                        '.') . ' €');
                        }
                    }
                }

                //Anzahl Teilenummern
                if ($EntityPartList) {
                    $Z = 0;
                    /** @var TblReporting_Part[] $EntityPart */
                    foreach ($EntityPartList AS $MarketingCode => $EntityPart) {
                        if ($Z !== 0) {
                            $PartText .= '<br/>';
                        }
                        $Z++;

                        if ($EntityPart) {
                            $PartText .= $MarketingCode . ': ' . count($EntityPart);
                        } else {
                            $PartText .= $MarketingCode . ': 0';
                        }
                    }
                }
            }
        }
        else {
            $MarketingCodeText = '';
            $ProductGroupText = '';
            $PartsMoreText = '';
            $PartText = '';
	    }

		return new Table(
			array(
				array(
					'Description' => 'Marketingcode',
					'Value' => $MarketingCodeText
				),
				array(
					'Description' => 'Warengruppe',
					'Value' => $ProductGroupText
				),
				array(
					'Description' => 'P+M',
					'Value' => $PartsMoreText
				),
				array(
					'Description' => 'Anzahl TNR',
					'Value' => $PartText
				),
				array(
					'Description' => 'Produktmanager',
					'Value' => $EntityProductManager->getName() . ' (' . $EntityProductManager->getDepartment() . ')'
				),
			),
			new TableTitle('Allgemeine Informationen'),
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

	private function tablePriceDataPartNumber( TblReporting_Part $EntityPart ) {

        $EntityPrice = null;
        $EntityDiscountGroup = null;
        $EntityMarketingCode = null;
        $EntityPartsMore = null;
        $PartsMoreDiscount = 0;
        $PartsMoreDescription = 'P+M';
        $PartsMoreValue = 'nicht vorhanden';

        if( $EntityPart ) {
            $EntityPrice = $EntityPart->fetchPriceCurrent();
            if($EntityPrice) {
                $EntityDiscountGroup = $EntityPrice->getTblReportingDiscountGroup();
            }
            $EntityMarketingCode = $EntityPart->fetchMarketingCodeCurrent();

            if($EntityMarketingCode) {
                $EntityPartsMore = $EntityMarketingCode->fetchPartsMoreCurrent();
                if($EntityPartsMore) {
                    $PartsMoreDiscount = $EntityPartsMore->getValue();
                }
            }
        }

        //Debugger::screenDump($EntityMarketingCode, $EntityPartsMore);

        if( $EntityPrice ) {
            $Rw = $EntityPrice->getBackValue();
            $GrossPrice = $EntityPrice->getPriceGross();
            $DiscountNumber = $EntityDiscountGroup->getNumber();
            $Discount = $EntityDiscountGroup->getDiscount();
            $Costs = $EntityPrice->getCostsVariable();
            $CalcRules = $this->getCalculationRules();

            if( $Rw != 0 ) {
                $PriceDescription = 'BLP / VP<br/>BLP / TP<br/>NLP / VP<br/>NLP / TP';
                $PriceValue = number_format( $CalcRules->calcGrossPrice( 0, 0, $Rw, 0, 0, 0, $GrossPrice ), 2, ',', '.' ).' €<br/>'.$this->doLocalize($GrossPrice)->getCurrency().' <br/>'
                    .number_format( $CalcRules->calcNetPrice( $GrossPrice, $Discount, $Rw ), 2, ',', '.').' €<br/>'.number_format( $CalcRules->calcNetPrice( $GrossPrice, $Discount ), 2, ',', '.').' €';
            }
            else {
                $PriceDescription = 'BLP / VP<br/>NLP / VP';
                $PriceValue = number_format( $GrossPrice, 2, ',', '.').' €<br/>'.number_format( $CalcRules->calcNetPrice( $GrossPrice, $Discount ), 2, ',', '.').' €';
            }

            if( $EntityPartsMore ) {
                if( $EntityPartsMore->getType() == 'Prozent' ) {
                    $PartsMoreDescription = 'P+M '.number_format($PartsMoreDiscount, 2, ',', '.').'%<br>NLP / P+M';
                    $PartsMoreValue = number_format($CalcRules->calcPartsMoreEuro($GrossPrice, $PartsMoreDiscount), 2,',', '.') . ' €<br/>'
                        . number_format($CalcRules->calcNetPrice($GrossPrice, $Discount, 0, $PartsMoreDiscount, 0, 0), 2, ',', '.') . ' €';
                }
                elseif( $EntityPartsMore->getType() == 'Euro' ) {
                    $PartsMoreDescription = 'P+M '.number_format($PartsMoreDiscount, 2, ',', '.').'€<br>NLP / P+M';
                    $PartsMoreValue = number_format(($GrossPrice + $PartsMoreDiscount), 2,',', '.') . ' €<br/>'
                        . number_format($CalcRules->calcNetPrice($GrossPrice, $Discount, 0, $PartsMoreDiscount, 0, 0)) . ' €';
                }
            }

            /** @var DateTime $DateValidFrom */
            $DateValidFrom = $EntityPrice->getValidFrom();
            //ToDo: Preisstand
            return new Table(
                array(
                    array(
                        'Description' => $PriceDescription,
                        'Value' => $PriceValue
                    ),
                    array(//PartsMoreProzent'
                        'Description' => $PartsMoreDescription,
                        'Value' => $PartsMoreValue
                    ),
                    array(
                        'Description' => 'Rabattgruppe',
                        'Value' => $DiscountNumber.'<br/>'.$Discount. '%'
                    ),
                    array(
                        'Description' => 'variable Kosten',
                        'Value' => number_format( $Costs, 2, ',', '.' ).' €'
                    ),
                    array(
                        'Description' => 'Preis gültig ab<br/>TNR-Status',
                        'Value' => $DateValidFrom->format('d.m.Y').'<br/>'.(($EntityPart->getStatusActive() == '1')? 'aktiv':'inaktiv')
                    ),
                    array(
                        'Description' => 'Konzern-DB',
                        'Value' => number_format( $CalcRules->calcCoverageContribution(
                                $CalcRules->calcNetPrice( $GrossPrice, $Discount, $Rw, $PartsMoreDiscount, 0, 0  )
                                , $Costs
                            ) , 2, ',', '.' ).' €'
                    ),
                    array(
                        'Description' => 'FC-Grenze ohne P+M<br/>FC-Grenze mit P+M',
                        'Value' => number_format( $CalcRules->calcFinancialManagementLimit( $CalcRules->calcGrossPrice( 0, 0, 0, $PartsMoreDiscount, 0, 0, $GrossPrice ), $Costs ), 2, ',', '.' ).' €<br/>'
                            .number_format( $CalcRules->calcFinancialManagementLimit( $GrossPrice, $Costs ), 2, ',', '.' ).' €'
                    ),
                ),
                new TableTitle('Preis- und Kosteninformationen (Preisstand: dd.mm.yyyy)'),
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
        else {
            return new Warning( 'Kein Preis vorhanden' );
        }
	}

	private function tablePriceDevelopmentPartNumber( TblReporting_Part $EntityPart ) {

        $PriceDevData = DataWareHouse::useService()->getPriceDevelopmentByPartNumber( $EntityPart, 50 );

        if($PriceDevData) {

            array_walk( $PriceDevData, function( &$Row ) {
                 if( isset($Row['Data_PriceGross']) ) {
                     $Row['Data_PriceGross'] = new PullRight( $this->doLocalize($Row['Data_PriceGross'])->getCurrency() );
                 }
                 if( isset($Row['Data_PriceNet']) ) {
                     $Row['Data_PriceNet'] = new PullRight( $this->doLocalize($Row['Data_PriceNet'])->getCurrency() );
                 }
                 if( isset($Row['Data_BackValue']) && $Row['Data_BackValue'] !== 0.00 ) {
                     $Row['Data_BackValue'] = new PullRight( $this->doLocalize($Row['Data_BackValue'])->getCurrency() );
                 }
                 if( isset($Row['Discount'])) {
                     $Row['Discount'] = new PullRight( $Row['Discount'].' %' );
                 }
                 if( isset($Row['Data_CostsVariable']) ) {
                     $Row['Data_CostsVariable'] = new PullRight( $this->doLocalize($Row['Data_CostsVariable'])->getCurrency() );
                 }
                 if( isset($Row['Data_CoverageContribution']) ) {
                     $Row['Data_CoverageContribution'] = new PullRight( $this->doLocalize($Row['Data_CoverageContribution'])->getCurrency() );
                 }
            } );

            $ReplaceArray = array(
                'Data_' => '',
                'Group_' => '',
                'ValidFrom' => 'Gültig ab',
                'PriceGross' => 'BLP',
                'PriceNet' => 'NLP',
                'DiscountGroupNumber' => 'RG',
                'Discount' => 'RS',
                'BackValue' => 'RW',
                'CostsVariable' => 'VK',
                'CoverageContribution' => 'DB'
            );

            $Keys = array_keys($PriceDevData[0]);
            $TableHead = array_combine( $Keys, str_replace( array_keys( $ReplaceArray ) , $ReplaceArray, $Keys) );

            return new Table(
                $PriceDevData,
                new TableTitle('Preis- und Kostenentwicklung'),
                $TableHead,
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
        else {
            return new Warning( 'Keine Preisentwicklung vorhanden' );
        }
	}

	private function tableSalesDataPartNumber( TblReporting_Part $EntityPart  ) {

	    $SalesData = DataWareHouse::useService()->getSalesByPart( $EntityPart );

	    if( $SalesData ) {

	        //Hochrechnungsfaktor
	        $HR = DataWareHouse::useService()->getExtrapolationFactor( $EntityPart->getNumber() );

            $WalkSalesData = array();
            array_walk( $SalesData, function( &$Row, $Key, $HR ) use (&$WalkSalesData) {

                //Hochrechnung hinzufügen
                if($Row['Year'] == date('Y') ) {
                    if(DataWareHouse::useService()->getMaxMonthCurrentYearFromSales() != '12') {
                        array_push(
                            $WalkSalesData, array(
                                'Year' => 'HR '.$Row['Year'],
                                'Data_SumSalesGross' => new PullRight( $this->doLocalize( ($Row['Data_SumSalesGross']*$HR) )->getCurrency() ),
                                'Data_SumSalesNet' => new PullRight( $this->doLocalize( ($Row['Data_SumSalesNet']*$HR) )->getCurrency() ),
                                'Data_SumQuantity' => new PullRight( $Row['Data_SumQuantity'] )
                            )
                        );
                    }

                    $Row['Year'] = 'per '.DataWareHouse::useService()->getMaxMonthCurrentYearFromSales().'/'.$Row['Year'];
                 }
                 if( isset($Row['Data_SumSalesGross']) ) {
                     $Row['Data_SumSalesGross'] = new PullRight( $this->doLocalize($Row['Data_SumSalesGross'])->getCurrency() );
                 }
                 if( isset($Row['Data_SumSalesNet']) ) {
                     $Row['Data_SumSalesNet'] = new PullRight( $this->doLocalize($Row['Data_SumSalesNet'])->getCurrency() );
                 }
                 if( isset($Row['Data_SumQuantity']) ) {
                     $Row['Data_SumQuantity'] = new PullRight( $Row['Data_SumQuantity'] );
                 }

            }, $HR );

            $SalesData = array_merge($WalkSalesData,$SalesData);

            $Table = new Table(
                $SalesData,
                new TableTitle('Controlling-Informationen'),
                array(
                    'Year' => '&nbsp;',
                    'Data_SumSalesGross' => /*new Tooltip('Brutto', 'Tooltipp-Info', new Info())*/'Brutto',
                    'Data_SumSalesNet' => 'Netto',
                    'Data_SumQuantity' => 'Anzahl effektiv'
                ),
                array(
                    "columnDefs" => array(
//			        array('width' => '40%', 'targets' => '0' ),
//					array('width' => '60%', 'targets' => '1' )
                        array(
                            "orderable" => true,
                            "targets" => '_all'
                        )
                    ),
                    "paging" => false, // Deaktivieren Blättern
                    "iDisplayLength" => -1,    // Alle Einträge zeigen
                    "searching" => false, // Deaktivieren Suchen
                    "info" => false, // Deaktivieren Such-Info
                    "sort" => false, //Deaktivierung Sortierung der Spalten
//              "responsive"     => false  //Deaktivierung Responsiv-Design
                )
            );

            $Table->prependHead(
                new TableHead(
                    array(
                        new TableRow(
                            array(
                                new TableColumn('', 1),
                                new TableColumn('Umsatz', 2),
                                new TableColumn('Anzahl effektiv', 1)
                            )
                        )
                    )
                )
            );
            return $Table;
        }
        else {
            return new Warning( 'Keine Umsatz-Daten vorhanden' );
        }
	}

	private function tableSalesDataMarketingCode( TblReporting_MarketingCode $EntityMarketingCode ) {
        $SalesData = DataWareHouse::useService()->getSalesByMarketingCode( $EntityMarketingCode );

        ///*new Tooltip('Brutto','Test2', new Info())*/
        if($SalesData) {

            //Hochrechnungsfaktor
   	        $HR = DataWareHouse::useService()->getExtrapolationFactor(null, $EntityMarketingCode->getNumber() );

            $WalkSalesData = array();
            array_walk( $SalesData, function( &$Row, $Key, $HR ) use (&$WalkSalesData) {

               //Hochrechnung hinzufügen
                if($Row['Year'] == date('Y') ) {
                    if(DataWareHouse::useService()->getMaxMonthCurrentYearFromSales() != '12') {
                        array_push(
                            $WalkSalesData, array(
                                'Year' => 'HR '.$Row['Year'],
                                'Data_SumSalesGross' => new PullRight( $this->doLocalize( ($Row['Data_SumSalesGross']*$HR) )->getCurrency() ),
                                'Data_SumSalesNet' => new PullRight( $this->doLocalize( ($Row['Data_SumSalesNet']*$HR) )->getCurrency() ),
                                'Data_SumQuantity' => new PullRight( $Row['Data_SumQuantity'] )
                            )
                        );
                    }
                    $Row['Year'] = 'per '.DataWareHouse::useService()->getMaxMonthCurrentYearFromSales().'/'.$Row['Year'];
                }
                if( isset($Row['Data_SumSalesGross']) ) {
                    $Row['Data_SumSalesGross'] = new PullRight( $this->doLocalize($Row['Data_SumSalesGross'])->getCurrency() );
                }
                if( isset($Row['Data_SumSalesNet']) ) {
                    $Row['Data_SumSalesNet'] = new PullRight( $this->doLocalize($Row['Data_SumSalesNet'])->getCurrency() );
                }
                if( isset($Row['Data_SumQuantity']) ) {
                    $Row['Data_SumQuantity'] = new PullRight( $Row['Data_SumQuantity'] );
                }

            }, $HR );

            $SalesData = array_merge($WalkSalesData, $SalesData);

            $Table = new Table(
                $SalesData,
                new TableTitle('Controlling-Informationen'),
                array( 'Year' => '&nbsp;', 'Data_SumSalesGross' => 'Brutto', 'Data_SumSalesNet' => 'Netto', 'Data_SumQuantity' => 'Anzahl effektiv' ),
                array(
                    "columnDefs" => array(
    //			        array('width' => '40%', 'targets' => '0' ),
    //					array('width' => '60%', 'targets' => '1' )
                        array(
                            "orderable" => true,
                            "targets" => '_all'
                        )
                    ),
                    "paging"         => false, // Deaktivieren Blättern
                    "iDisplayLength" => -1,    // Alle Einträge zeigen
                    "searching"      => false, // Deaktivieren Suchen
                    "info"           => false, // Deaktivieren Such-Info
                    "sort"           => false, //Deaktivierung Sortierung der Spalten
    //              "responsive"     => false  //Deaktivierung Responsiv-Design
                )
            );

            $Table->prependHead(
                new TableHead(
                    array(
                        new TableRow(
                            array(
                                new TableColumn('', 1),
                                new TableColumn('Umsatz',2),
                                new TableColumn('Anzahl effektiv', 1)
                            )
                        )
                    )
                )
            );
            return $Table;
        }
        else {
            return new Warning( 'Keine Umsatz-Daten vorhanden' );
        }
	}

	private function tableSalesDataProductManager( TblReporting_ProductManager $EntityProductManager ) {
        $SalesData = DataWareHouse::useService()->getSalesByProductManager( $EntityProductManager );

        if( $SalesData ) {

            //Hochrechnungsfaktor
   	        $HR = (float)1;

            $WalkSalesData = array();
            array_walk( $SalesData, function( &$Row, $Key, $HR ) use (&$WalkSalesData) {

               //Hochrechnung hinzufügen
               if(isset($Row['Year']) == date('Y') && DataWareHouse::useService()->getMaxMonthCurrentYearFromSales() != '12' ) {
                   array_push(
                       $WalkSalesData, array(
                           'Year' => 'HR '.$Row['Year'],
                           'Data_SumSalesGross' => new PullRight( $this->doLocalize( ($Row['Data_SumSalesGross']*$HR) )->getCurrency() ),
                           'Data_SumSalesNet' => new PullRight( $this->doLocalize( ($Row['Data_SumSalesNet']*$HR) )->getCurrency() ),
                           'Data_SumQuantity' => new PullRight( $Row['Data_SumQuantity'] )
                       )
                   );

                    $Row['Year'] = 'per '.DataWareHouse::useService()->getMaxMonthCurrentYearFromSales().'/'.$Row['Year'];
                }
                if( isset($Row['Data_SumSalesGross']) ) {
                    $Row['Data_SumSalesGross'] = new PullRight( $this->doLocalize($Row['Data_SumSalesGross'])->getCurrency() );
                }
                if( isset($Row['Data_SumSalesNet']) ) {
                    $Row['Data_SumSalesNet'] = new PullRight( $this->doLocalize($Row['Data_SumSalesNet'])->getCurrency() );
                }
                if( isset($Row['Data_SumQuantity']) ) {
                    $Row['Data_SumQuantity'] = new PullRight( $Row['Data_SumQuantity'] );
                }

            }, $HR );

            $SalesData = array_merge($SalesData,$WalkSalesData);

            $Table = new Table(
                $SalesData,
                new TableTitle('Controlling-Informationen'),
                array(
                    'Year' => '&nbsp;',
                    'Data_SumSalesGross' => /*new Tooltip('Brutto', 'Test2', new Info())*/'Brutto',
                    'Data_SumSalesNet' => 'Netto',
                    'Data_SumQuantity' => 'Anzahl effektiv'
                ),
                array(
                    "columnDefs" => array(
//			        array('width' => '40%', 'targets' => '0' ),
//					array('width' => '60%', 'targets' => '1' )
                        array(
                            "orderable" => true,
                            "targets" => '_all'
                        )
                    ),
                    "paging" => false, // Deaktivieren Blättern
                    "iDisplayLength" => -1,    // Alle Einträge zeigen
                    "searching" => false, // Deaktivieren Suchen
                    "info" => false, // Deaktivieren Such-Info
                    "sort" => false, //Deaktivierung Sortierung der Spalten
//              "responsive"     => false  //Deaktivierung Responsiv-Design
                )
            );

            $Table->prependHead(
                new TableHead(
                    array(
                        new TableRow(
                            array(
                                new TableColumn('', 1),
                                new TableColumn('Umsatz', 2),
                                new TableColumn('Anzahl effektiv', 1)
                            )
                        )
                    )
                )
            );
            return $Table;
        }
        else {
            return new Warning( 'Keine Umsatz-Daten vorhanden' );
        }
	}

	private function tableCompetitionExtraPartNumber( $PartNumber ) {

	    $SearchData = \SPHERE\Application\Competition\DataWareHouse\DataWareHouse::useService()->getCompetitionAdditionalInfoDirectSearchByPartNumber( $PartNumber );

	    switch($SearchData[0]['Season']) {
            case 'S': $Season = 'Sommer';
                break;
            case 'W': $Season = 'Winter';
                break;
            case 'G': $Season = 'Ganzjahr';
                break;
            default: $Season = '';
                break;
        }

		return new Table(
			array(
				array(
					'Description' => new Bold( 'Saison / Sortiment / Sparte' ),
					'Value' => $Season . ' / ' . $SearchData[0]['Assortment'] . ' / ' . $SearchData[0]['Section']
				),
                array(
					'Description' => new Bold( 'Dimension' ),
					'Value' => $SearchData[0]['DimensionTyre']
				),
				array(
					'Description' => new Bold( 'Profil / Hersteller' ),
					'Value' => utf8_decode($SearchData[0]['Profil']) . ' / '.utf8_decode($SearchData[0]['Manufacturer'])
				),
				array(
					'Description' => new Bold( 'Rad' ),
					'Value' => utf8_decode($SearchData[0]['DesignRim']) .'<br/>'.$SearchData[0]['DimensionRim'] . (($SearchData[0]['NumberRim']!= '')? '(' .$SearchData[0]['NumberRim']. ')':'')
				),
				array(
					'Description' => new Bold( 'Baureihe' ),
					'Value' => $SearchData[0]['Series']
				)
			),
			new TableTitle('Zusätzliche Informationen'),
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

	private function tableCompetitionDataPartNumber( $PartNumber ) {

//	    $SearchData = \SPHERE\Application\Competition\DataWareHouse\DataWareHouse::useService()->getCompetitionDirectSearchByPartNumber( $PartNumber );
//
//        $ReplaceArray = array(
// 			'Competitor' => 'Wettbewerber',
// 			'Manufacturer' => 'Hersteller',
// 			'CreationDate' => 'Zeitraum',
// 			'Data_PriceNet' => 'NLP',
// 			'Data_PriceGross' => 'BLP',
// 			'Data_Discount' => 'Rabatt',
// 			'DistributorOrCustomer' => 'WV / EA',
// 			'Comment' => 'Kommentar',
// 			'RetailNumber' => 'VF'
// 		);
//
// 		//Definition Spaltenkopf
// 		if( count($SearchData) > 0 ) {
//             array_walk( $SearchData, function( &$Row ) {
//                if( isset($Row['Data_PriceNet']) ) {
//                    $Row['Data_PriceNet'] = new PullRight( $this->doLocalize($Row['Data_PriceNet'])->getCurrency() );
//                }
//                if( isset($Row['Data_PriceGross']) ) {
//                    $Row['Data_PriceGross'] = new PullRight( $this->doLocalize($Row['Data_PriceGross'])->getCurrency() );
//                }
//                if( isset($Row['Data_Discount']) ) {
//                    $Row['Data_Discount'] = new PullRight( $this->doLocalize($Row['Data_Discount'])->getCurrency() );
//                }
//                if( isset($Row['Comment']) ) {
//                    $Row['Comment'] = utf8_decode($Row['Comment']);
//                }
//                if( isset($Row['Manufacturer']) ) {
//                    $Row['Manufacturer'] = utf8_decode($Row['Manufacturer']);
//                }
//                $Row['Option'] = new Standard('löschen', __NAMESPACE__, null, array( 'CompetitionId' => $Row['PositionId']) );
//             } );
//
// 			$Keys = array_keys($SearchData[0]);
// 			$TableHead = array_combine( $Keys, str_replace( array_keys( $ReplaceArray ) , $ReplaceArray, $Keys) );
//
// 			$ReceiverTable = new BlockReceiver('');
//
// 			return new Table(
// 				$SearchData, null, $TableHead
// 			);
// 		}
// 		else {
// 			return new Warning('Es sind keine Datensätze vorhanden.');
// 		}

        return CompetitionTable::TableBlockReceiver(CompetitionTable::pipelineCompetitionTable( $PartNumber ));
	}
}
