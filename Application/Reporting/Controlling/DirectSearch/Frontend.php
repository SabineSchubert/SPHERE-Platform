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
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
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
		$Stage = new Stage('Direktsuche', 'Teilenummer');
		$this->buttonStageDirectSearch($Stage);



		$LayoutGroupDirectSearch = '';
		$LayoutGroupCompetition = '';
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
								$this->tableCompetitionExtraPartNumber(), 6
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
									$this->tableCompetitionDataPartNumber(), 12
								)
							)
						),
						new Title('Angebotsdaten')
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
				$LayoutGroupCompetition
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

		$LayoutGroupDirectSearch = '';
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
									, 6
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
				$LayoutGroupDirectSearch
			))
		);
		return $Stage;
	}

	public function frontendSearchMarketingCode( $Search = null )
	{
		$Stage = new Stage('Direktsuche', 'Marketingcode');
		$this->buttonStageDirectSearch($Stage);

		$LayoutGroupDirectSearch = '';
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
				$LayoutGroupDirectSearch
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
                                ))//->configureLibrary( SelectBox::LIBRARY_SELECT2 )
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

            //Sparte
            $EntitySectionList = $EntityPart->fetchSectionListCurrent();
            $SectionText = '';
            if( $EntitySectionList ) {
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
                        'Value' => ''
                    ),
                    array(
                        'Description' => 'Sortimentsgruppe',
                        'Value' => (( $EntityAssortmentGroup ) ? $EntityAssortmentGroup->getNumber().' - '. $EntityAssortmentGroup->getName() : '' )
                    ),
                    array(
                        'Description' => 'Marketingcode',
                        'Value' => (( $EntityMarketingCode )? $EntityMarketingCode->getNumber() . ' - ' . $EntityMarketingCode->getName(): '' )
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

                    $EntityPartList[$MarketingCode->getNumber()] = $MarketingCode->fetchPartListCurrent();

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
                            if ($Z !== 0) {
                                $PartsMoreText .= '<br/>';
                            }
                            $Z++;
                            $PartsMoreText .= $MarketingCode . ': ' . (($EntityPartsMore->getType() == 'Prozent') ? number_format($EntityPartsMore->getValue(),
                                        2, ',', '.') . ' %' : number_format($EntityPartsMore->getValue(), 2, ',',
                                        '.') . ' €');
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
            $EntityDiscountGroup = $EntityPrice->getTblReportingDiscountGroup();
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
                new TableTitle('Preis- und Kosteninformationen'),
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
            return '';
        }
	}

	private function tablePriceDevelopmentPartNumber( TblReporting_Part $EntityPart ) {

        $PriceDevData = DataWareHouse::useService()->getPriceDevelopmentByPartNumber( $EntityPart, 50 );

        if($PriceDevData) {

            array_walk( $PriceDevData, function( &$Row ) {
                 if( isset($Row['Data_PriceGross']) ) {
                     $Row['Data_PriceGross'] = $this->doLocalize($Row['Data_PriceGross'])->getCurrency();
                 }
                 if( isset($Row['Data_PriceNet']) ) {
                     $Row['Data_PriceNet'] = $this->doLocalize($Row['Data_PriceNet'])->getCurrency();
                 }
                 if( isset($Row['Data_BackValue']) && $Row['Data_BackValue'] !== 0.00 ) {
                     $Row['Data_BackValue'] = $this->doLocalize($Row['Data_BackValue'])->getCurrency();
                 }
                 if( isset($Row['Discount'])) {
                     $Row['Discount'] = $Row['Discount'].' %';
                 }
                 if( isset($Row['Data_CostsVariable']) ) {
                     $Row['Data_CostsVariable'] = $this->doLocalize($Row['Data_CostsVariable'])->getCurrency();
                 }
                 if( isset($Row['Data_CoverageContribution']) ) {
                     $Row['Data_CoverageContribution'] = $this->doLocalize($Row['Data_CoverageContribution'])->getCurrency();
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
            array_walk( $SalesData, function( &$Row ) {
                 if( isset($Row['Data_SumSalesGross']) ) {
                     $Row['Data_SumSalesGross'] = $this->doLocalize($Row['Data_SumSalesGross'])->getCurrency();
                 }
                 if( isset($Row['Data_SumSalesNet']) ) {
                     $Row['Data_SumSalesNet'] = $this->doLocalize($Row['Data_SumSalesNet'])->getCurrency();
                 }
            } );

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
            $Table = new Table(
                $SalesData,
                new TableTitle('Controlling-Informationen'),
                array( 'Year' => '&nbsp;', 'SumSalesGross' => 'Brutto', 'SumSalesNet' => 'Netto', 'SumQuantity' => 'Anzahl effektiv' ),
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
            $Table = new Table(
                $SalesData,
                new TableTitle('Controlling-Informationen'),
                array(
                    'Year' => '&nbsp;',
                    'SumSalesGross' => /*new Tooltip('Brutto', 'Test2', new Info())*/'Brutto',
                    'SumSalesNet' => 'Netto',
                    'SumQuantity' => 'Anzahl effektiv'
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

	private function tableCompetitionExtraPartNumber() {
		return new Table(
			array(
				array(
					'Description' => new Bold( 'Dimension' ),
					'Value' => 'A1234'
				),
				array(
					'Description' => 'Profil',
					'Value' => '123'
				),
				array(
					'Description' => 'Rad',
					'Value' => '1P23'
				),
				array(
					'Description' => 'Warengruppe',
					'Value' => '1P23'
				),
				array(
					'Description' => 'Sparte',
					'Value' => 'Pkw'
				),
				array(
					'Description' => 'Produktmanager',
					'Value' => 'Andreas Schneider'
				),
				array(
					'Description' => 'Hauptlieferant',
					'Value' => 'unbekannt'
				),
			),
			new TableTitle('Zusätzliche Informationen'),
			array( 'Description ' => 'Bezeichnung', 'Value' => '' ),
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

	private function tableCompetitionDataPartNumber() {
		return new Table(
			array(
				array(
					'Description' => 'Wettbewerber',
					'Manufacturer' => 'Hersteller',
					'PeriodOfTime' => 'Zeitraum',
					'NetPrice' => 'NLP',
					'GrossPrice' => 'BLP',
					'Discount' => 'Rabatt',
					'WV' => 'WV',
					'EA' => 'EA',
					'VP' => 'VP',
					'Comments' => 'Kommentar',
					'RetailNumber' => 'VF',
					'DeleteButton' => 'Löschen',
				),
				array(
					'Description' => 'Wettbewerber',
					'Manufacturer' => 'Hersteller',
					'PeriodOfTime' => 'Zeitraum',
					'NetPrice' => 'NLP',
					'GrossPrice' => 'BLP',
					'Discount' => 'Rabatt',
					'WV' => 'WV',
					'EA' => 'EA',
					'VP' => 'VP',
					'Comments' => 'Kommentar',
					'RetailNumber' => 'VF',
					'DeleteButton' => 'Löschen',
				),
				array(
					'Description' => 'Wettbewerber',
					'Manufacturer' => 'Hersteller',
					'PeriodOfTime' => 'Zeitraum',
					'NetPrice' => 'NLP',
					'GrossPrice' => 'BLP',
					'Discount' => 'Rabatt',
					'WV' => 'WV',
					'EA' => 'EA',
					'VP' => 'VP',
					'Comments' => 'Kommentar',
					'RetailNumber' => 'VF',
					'DeleteButton' => 'Löschen',
				),
			),
			new TableTitle('Wettbewerbsdaten'),
			array(
				'Description' => 'Wettbewerber',
				'Manufacturer' => 'Hersteller',
				'PeriodOfTime' => 'Zeitraum',
				'NetPrice' => 'NLP',
				'GrossPrice' => 'BLP',
				'Discount' => 'Rabatt',
				'WV' => 'WV',
				'EA' => 'EA',
				'VP' => 'VP',
				'Comments' => 'Kommentar',
				'RetailNumber' => 'VF',
				'DeleteButton' => 'Löschen',
			),
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
