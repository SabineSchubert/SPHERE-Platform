<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.07.2017
 * Time: 13:42
 */

namespace SPHERE\Application\Api\Reporting\Excel;


use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Component\Parameter\Repository\PaperOrientationParameter;
use MOC\V\Component\Document\Document;
use MOC\V\Core\FileSystem\FileSystem;
use Nette\DateTime;
use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_PartsMore;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Section;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Supplier;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class ExcelDirectSearch extends Extension implements IApiInterface
{
    use ApiTrait;

    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('getExcel');

        return $Dispatcher->callMethod($Method);
    }

    public function getExcel($PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId = null) {
        $FilePointer = new FilePointer('xlsx');
        $FileLocation = $FilePointer->getFileLocation();
        /**
         * @var PhpExcel $Document
         */
        $Document = Document::getDocument( $FileLocation );


        $Document->setPaperOrientationParameter( new PaperOrientationParameter( PaperOrientationParameter::ORIENTATION_LANDSCAPE ) );

        if( $MarketingCodeNumber ) {

            $Document->renameWorksheet('Direktsuche');

            $EntityMarketingCode = DataWareHouse::useService()->getMarketingCodeByNumber( $MarketingCodeNumber );
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

            //Umsatzdaten
            $SalesData = DataWareHouse::useService()->getSalesByMarketingCode( $EntityMarketingCode );

            if( $SalesData ) {

                //Hochrechnungsfaktor
                $HR = DataWareHouse::useService()->getExtrapolationFactor( $PartNumber );

                $WalkSalesData = array();
                array_walk( $SalesData, function( &$Row, $Key, $HR ) use (&$WalkSalesData) {

                    //Hochrechnung hinzufügen
                    if($Row['Year'] == date('Y') ) {
                        if(DataWareHouse::useService()->getMaxMonthCurrentYearFromSales() != '12') {
                            array_push(
                                $WalkSalesData, array(
                                    'Year' => 'HR '.$Row['Year'],
                                    'Data_SumSalesGross' => $Row['Data_SumSalesGross']*$HR,
                                    'Data_SumSalesNet' => $Row['Data_SumSalesNet']*$HR,
                                    'Data_SumQuantity' => $Row['Data_SumQuantity']
                                )
                            );
                        }

                        $Row['Year'] = 'per '.DataWareHouse::useService()->getMaxMonthCurrentYearFromSales().'/'.$Row['Year'];
                     }

                }, $HR );

                $SalesData = array_merge($WalkSalesData,$SalesData);
            }


            $i = 0;
            $Document->setValue( $Document->getCell(0,$i), 'Allgemeine Informationen' )->setStyle( $Document->getCell(0,$i) )->setFontBold();
            $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(3,$i) )->mergeCells()->setBorderAll(1);
            $i++;
            $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
            $Document->setValue( $Document->getCell(0,$i++), 'Marketingcode' );
            $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
            $Document->setValue( $Document->getCell(0,$i++), 'Warengruppe' );
            $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
            $Document->setValue( $Document->getCell(0,$i++), 'P+M' );
            $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
            $Document->setValue( $Document->getCell(0,$i++), 'Anzahl TNR' );
            $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
            $Document->setValue( $Document->getCell(0,$i++), 'Produktmanager' );

            $Document->setStyle( $Document->getCell(1,0),$Document->getCell(3,5) )->setBorderAll(1);

            $i = 1;
            $Document->setValue( $Document->getCell(1,$i), $EntityMarketingCode->getNumber() . ' - '. $EntityMarketingCode->getName() );
            $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
            $i++;

            $Document->setValue( $Document->getCell(1,$i), $ProductGroupText );
            $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
            $i++;
            $Document->setValue( $Document->getCell(1,$i), (( $EntityPartsMore )? (( $EntityPartsMore->getType() == 'Prozent' )? number_format( $EntityPartsMore->getValue(), 2, ',', '.' ).' %': number_format( $EntityPartsMore->getValue(), 2, ',', '.' ).' €') : '' ) );
            $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
            $i++;
            $Document->setValue( $Document->getCell(1,$i), count($EntityPartList) );
            $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
            $i++;
            $Document->setValue( $Document->getCell(1,$i), ( ( $EntityProductManager )? $EntityProductManager->getName().' ('.$EntityProductManager->getDepartment().')' : '' ) );
            $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();

            $i++;
            $i++;

            //Umsatzdaten
            if( $SalesData ) {

                $Document->setValue($Document->getCell(0, $i), 'Umsatz')->setStyle($Document->getCell(0,
                    $i))->setFontBold();;
                $Document->setStyle($Document->getCell(0, $i), $Document->getCell(3, $i))->mergeCells()->setBorderAll(1);
                $i++;

                $Document->setValue($Document->getCell(0, $i), '');
                $Document->setStyle($Document->getCell(0, $i))->setBorderAll(1);
                $Document->setValue($Document->getCell(1, $i), 'Umsatz');
                $Document->setStyle($Document->getCell(1, $i), $Document->getCell(2, $i))->mergeCells()->setBorderAll(1);
                $Document->setValue($Document->getCell(3, $i), 'Anzahl effektiv');
                $Document->setStyle($Document->getCell(3, $i))->setBorderAll(1);

                $Document->setValue($Document->getCell(0, $i), '');
                $Document->setStyle($Document->getCell(0, $i))->setBorderAll(1);

                $i++;
                $Document->setValue($Document->getCell(0, $i), '');
                $Document->setStyle($Document->getCell(0, $i))->setBorderAll(1);
                $Document->setValue($Document->getCell(1, $i), 'Brutto');
                $Document->setStyle($Document->getCell(1, $i))->setColumnWidth(15)->setBorderAll(1);
                $Document->setValue($Document->getCell(2, $i), 'Netto');
                $Document->setStyle($Document->getCell(2, $i))->setColumnWidth(15)->setBorderAll(1);
                $Document->setValue($Document->getCell(3, $i), 'Anzahl effektiv');
                $Document->setStyle($Document->getCell(3, $i))->setColumnWidth(15)->setBorderAll(1);

                $i++;

                foreach( (array)$SalesData as $RowIndex => $RowList ) {
                   $ColIndex = 0;
                   foreach( (array)$RowList as $ColName => $ColValue ) {
                        //Zahl
                        if( is_int( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
                            $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                            $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
                        }
                        elseif( is_float( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
                            $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                            $Document->setValue( $Document->getCell( ($ColIndex++), ( $i ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
                        }
                        else {
                            $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                            $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), $ColValue, PhpExcel::TYPE_STRING );
                        }
                    }
                    $i++;
                }

            }

        }
        elseif( $ProductManagerId ) {
            $Document->renameWorksheet('Direktsuche');

            $EntityProductManager = DataWareHouse::useService()->getProductManagerById( $ProductManagerId );
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
                      $MarketingCodeText .= "\n";
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
                          $ProductGroupText .= "\n";
                      }
                      $Z++;
                      $ProductGroupText .= $MarketingCode . ': ';
                      if (count((array)$ProductGroupList) == 0) {
                          $ProductGroupText .= 'unbekannt';
                      } else {
                          /** @var TblReporting_ProductGroup $ProductGroup */
                          foreach ((array)$ProductGroupList AS $Index => $ProductGroup) {
                              if ($Index !== 0) {
                                  $ProductGroupText .= ", ";
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
                              $PartsMoreText .= "\n";
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
                          $PartText .= "\n";
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

            //Umsatzdaten
            $SalesData = DataWareHouse::useService()->getSalesByProductManager( $EntityProductManager );

            if( $SalesData ) {

                //Hochrechnungsfaktor
                $HR = DataWareHouse::useService()->getExtrapolationFactor( $PartNumber );

                $WalkSalesData = array();
                array_walk( $SalesData, function( &$Row, $Key, $HR ) use (&$WalkSalesData) {

                    //Hochrechnung hinzufügen
                    if($Row['Year'] == date('Y') ) {
                        if(DataWareHouse::useService()->getMaxMonthCurrentYearFromSales() != '12') {
                            array_push(
                                $WalkSalesData, array(
                                    'Year' => 'HR '.$Row['Year'],
                                    'Data_SumSalesGross' => $Row['Data_SumSalesGross']*$HR,
                                    'Data_SumSalesNet' => $Row['Data_SumSalesNet']*$HR,
                                    'Data_SumQuantity' => $Row['Data_SumQuantity']
                                )
                            );
                        }

                        $Row['Year'] = 'per '.DataWareHouse::useService()->getMaxMonthCurrentYearFromSales().'/'.$Row['Year'];
                     }

                }, $HR );

                $SalesData = array_merge($WalkSalesData,$SalesData);
            }

            $i = 0;
            $Document->setValue( $Document->getCell(0,$i), 'Allgemeine Informationen' )->setStyle( $Document->getCell(0,$i) )->setFontBold()->setFontColor( PhpExcel\Style::COLOR_BLACK );
            $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(3,$i) )->mergeCells()->setBorderAll(1);
            $i++;
            $Document->setValue( $Document->getCell(0,$i), 'Marketingcode' )->setStyle($Document->getCell(0,$i++))->setAlignmentTop()->setColumnWidth(-1)->setBorderAll(1);
            $Document->setValue( $Document->getCell(0,$i), 'Warengruppe' )->setStyle($Document->getCell(0,$i++))->setAlignmentTop()->setBorderAll(1);
            $Document->setValue( $Document->getCell(0,$i), 'P+M' )->setStyle($Document->getCell(0,$i++))->setAlignmentTop()->setBorderAll(1);
            $Document->setValue( $Document->getCell(0,$i), 'Anzahl TNR' )->setStyle($Document->getCell(0,$i++))->setAlignmentTop()->setBorderAll(1);
            $Document->setValue( $Document->getCell(0,$i), 'Produktmanager' )->setStyle($Document->getCell(0,$i++))->setAlignmentTop()->setBorderAll(1);

            $i = 1;
            $Document->setStyle( $Document->getCell(1,$i) )->setRowHeight(15*count($EntityMarketingCodeList));
            $Document->setValue( $Document->getCell(1,$i), $MarketingCodeText )->setStyle( $Document->getCell(1,$i) )->setWrapText();
            $Document->setStyle( $Document->getCell(1,$i), $Document->getCell(3,$i) )->mergeCells()->setColumnWidth(-1)->setBorderAll(1);

            $i++;

            $Document->setStyle( $Document->getCell(1,$i) )->setRowHeight(15*count($EntityMarketingCodeList));
            $Document->setValue( $Document->getCell(1,$i), $ProductGroupText )->setStyle( $Document->getCell(1,$i) )->setWrapText();
            $Document->setStyle( $Document->getCell(1,$i), $Document->getCell(3,$i) )->mergeCells()->setBorderAll(1);
            $i++;
            //$Document->setStyle( $Document->getCell(1,$i) )->setRowHeight(15*count($EntityMarketingCodeList));
            $Document->setValue( $Document->getCell(1,$i), $PartsMoreText );
            $Document->setStyle( $Document->getCell(1,$i), $Document->getCell(3,$i) )->mergeCells()->setBorderAll(1);
            $i++;
            $Document->setStyle( $Document->getCell(1,$i) )->setRowHeight(15*count($EntityMarketingCodeList));
            $Document->setValue( $Document->getCell(1,$i), $PartText );
//            $Document->setStyle( $Document->getCell(1,$i) )->setRowHeight(-1)->setColumnWidth(-1);
            $Document->setStyle( $Document->getCell(1,$i), $Document->getCell(3,$i) )->mergeCells()->setBorderAll(1);
            $i++;
            $Document->setValue( $Document->getCell(1,$i), $EntityProductManager->getName() . ' (' . $EntityProductManager->getDepartment() . ')' );
            $Document->setStyle( $Document->getCell(1,$i), $Document->getCell(3,$i) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);

            $i++;
            //Umsatzdaten
            if( $SalesData ) {

                $Document->setValue($Document->getCell(0, $i), 'Umsatz')->setStyle($Document->getCell(0,
                    $i))->setFontBold();;
                $Document->setStyle($Document->getCell(0, $i), $Document->getCell(3, $i))->mergeCells()->setBorderAll(1);
                $i++;

                $Document->setValue($Document->getCell(0, $i), '');
                $Document->setStyle($Document->getCell(0, $i))->setBorderAll(1);
                $Document->setValue($Document->getCell(1, $i), 'Umsatz');
                $Document->setStyle($Document->getCell(1, $i), $Document->getCell(2, $i))->mergeCells()->setBorderAll(1);
                $Document->setValue($Document->getCell(3, $i), 'Anzahl effektiv');
                $Document->setStyle($Document->getCell(3, $i))->setBorderAll(1);

                $Document->setValue($Document->getCell(0, $i), '');
                $Document->setStyle($Document->getCell(0, $i))->setBorderAll(1);

                $i++;
                $Document->setValue($Document->getCell(0, $i), '');
                $Document->setStyle($Document->getCell(0, $i))->setBorderAll(1);
                $Document->setValue($Document->getCell(1, $i), 'Brutto');
                $Document->setStyle($Document->getCell(1, $i))->setColumnWidth(15)->setBorderAll(1);
                $Document->setValue($Document->getCell(2, $i), 'Netto');
                $Document->setStyle($Document->getCell(2, $i))->setColumnWidth(15)->setBorderAll(1);
                $Document->setValue($Document->getCell(3, $i), 'Anzahl effektiv');
                $Document->setStyle($Document->getCell(3, $i))->setColumnWidth(15)->setBorderAll(1);

                $i++;

                foreach( (array)$SalesData as $RowIndex => $RowList ) {
                   $ColIndex = 0;
                   foreach( (array)$RowList as $ColName => $ColValue ) {
                        //Zahl
                        if( is_int( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
                            $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                            $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
                        }
                        elseif( is_float( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
                            $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                            $Document->setValue( $Document->getCell( ($ColIndex++), ( $i ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
                        }
                        else {
                            $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                            $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), $ColValue, PhpExcel::TYPE_STRING );
                        }
                    }
                    $i++;
                }

            }

        }
        elseif($PartNumber) {

            $Document->renameWorksheet('Direktsuche');

            $EntityMarketingCode = null;
            $EntityProductManager = null;
            $EntityAssortmentGroup = null;
            $EntitySupplierList = null;

            $EntityPart = DataWareHouse::useService()->getPartByNumber( $PartNumber );

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
                    if ($EntityProductGroupList) {
                        /** @var TblReporting_ProductGroup $ProductGroup */
                        foreach ($EntityProductGroupList AS $Index => $ProductGroup) {
                            if ($Index != 0) {
                                $ProductGroupText .= "\n";
                            }
                            $ProductGroupText .= $ProductGroup->getNumber() . ' - ' . $ProductGroup->getName();
                        }
                    }
                }
                else {
                    $ProductGroupText = '';
                }

                //Sparte
                $EntitySectionList = $EntityPart->fetchSectionListCurrent();
                $SectionText = '';
                if ($EntitySectionList) {
                    /** @var TblReporting_Section $Section */
                    foreach ($EntitySectionList AS $Index => $Section) {
                        if ($Index != 0) {
                            $SectionText .= "\n";
                        }
                        $SectionText .= $Section->getNumber() . ' - ' . $Section->getName();
                    }
                }

                //Lieferanten
                $EntitySupplierList = $EntityPart->fetchSupplierListCurrent();
                $SuppliertText = '';
                if ($EntitySupplierList) {
                    /** @var TblReporting_Supplier $Supplier */
                    foreach ($EntitySupplierList AS $Index => $Supplier) {
                        if ($Index != 0) {
                            $SuppliertText .= "\n";
                        }
                        $SuppliertText .= $Supplier->getNumber() . ' - ' . $Supplier->getName();
                    }
                }

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

                $Rw = $EntityPrice->getBackValue();
                $GrossPrice = $EntityPrice->getPriceGross();
                $DiscountNumber = $EntityDiscountGroup->getNumber();
                $Discount = $EntityDiscountGroup->getDiscount();
                $Costs = $EntityPrice->getCostsVariable();
                $CalcRules = $this->getCalculationRules();

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

                //Preisentwicklung
                $PriceHistory = DataWareHouse::useService()->getPriceDevelopmentByPartNumber( $EntityPart, 500 );

//                Debugger::screenDump($PriceHistory);

                //Umsätze
                $SalesData = DataWareHouse::useService()->getSalesByPart( $EntityPart );

                $CompetitionData = \SPHERE\Application\Competition\DataWareHouse\DataWareHouse::useService()->getCompetitionDirectSearchByPartNumber( $EntityPart->getNumber() );

                $CompetitionQ440Data = array();
                if( substr($EntityPart->getNumber(),0,4) == 'Q440' ) {
                    $CompetitionQ440Data = \SPHERE\Application\Competition\DataWareHouse\DataWareHouse::useService()->getCompetitionAdditionalInfoDirectSearchByPartNumber( $EntityPart->getNumber() );
                }

                if( $SalesData ) {

                    //Hochrechnungsfaktor
                    $HR = DataWareHouse::useService()->getExtrapolationFactor( $PartNumber );

                    $WalkSalesData = array();
                    array_walk( $SalesData, function( &$Row, $Key, $HR ) use (&$WalkSalesData) {

                        //Hochrechnung hinzufügen
                        if($Row['Year'] == date('Y') ) {
                            if(DataWareHouse::useService()->getMaxMonthCurrentYearFromSales() != '12') {
                                array_push(
                                    $WalkSalesData, array(
                                        'Year' => 'HR '.$Row['Year'],
                                        'Data_SumSalesGross' => $Row['Data_SumSalesGross']*$HR,
                                        'Data_SumSalesNet' => $Row['Data_SumSalesNet']*$HR,
                                        'Data_SumQuantity' => $Row['Data_SumQuantity']
                                    )
                                );
                            }

                            $Row['Year'] = 'per '.DataWareHouse::useService()->getMaxMonthCurrentYearFromSales().'/'.$Row['Year'];
                         }

                    }, $HR );

                    $SalesData = array_merge($WalkSalesData,$SalesData);
                }


                $i = 0;
                $Document->setValue( $Document->getCell(0,$i), 'Allgemeine Informationen' )->setStyle( $Document->getCell(0,$i) )->setFontBold();
                $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(3,$i) )->mergeCells()->setBorderAll(1);
                $i++;
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'Teilenummer' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'ET-Baumuster' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'Vorgänger' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'Nachfolger' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'Wahlweise' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'Sortimentsgruppe' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'Marketingcode' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'Warengruppe' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'Sparte' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i++), 'Produktmanager' );
                $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(0,$i), 'Lieferant(en)' );

                $Document->setStyle( $Document->getCell(1,0),$Document->getCell(3,$i) )->setBorderAll(1);
                $i++;

                $i = 1;
                $Document->setValue( $Document->getCell(1,$i), $EntityPart->getNumber() . ' - ' . $EntityPart->getName() );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;

                $Document->setValue( $Document->getCell(1,$i), $EntityPart->getSparePartDesign() );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;

                $Document->setValue( $Document->getCell(1,$i), '' );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;
                $Document->setValue( $Document->getCell(1,$i), '' );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;
                $Document->setValue( $Document->getCell(1,$i), '' );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;
                $Document->setValue( $Document->getCell(1,$i), (( $EntityAssortmentGroup ) ? $EntityAssortmentGroup->getNumber().' - '. $EntityAssortmentGroup->getName() : '' ) );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;
                $Document->setValue( $Document->getCell(1,$i), (( $EntityMarketingCode )? $EntityMarketingCode->getNumber() . ' - ' . $EntityMarketingCode->getName(): '' ) );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;
                $Document->setValue( $Document->getCell(1,$i), $ProductGroupText );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;
                $Document->setValue( $Document->getCell(1,$i), $SectionText );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;
                $Document->setValue( $Document->getCell(1,$i), (( $EntityProductManager )? $EntityProductManager->getName() . ' - ' . $EntityProductManager->getDepartment(): '' ) );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();
                $i++;
                $Document->setValue( $Document->getCell(1,$i), $SuppliertText );
                $Document->setStyle( $Document->getCell(1,$i),$Document->getCell(3,$i) )->mergeCells();

                //$Document->setStyle( $Document->getCell(0,13), $Document->getCell(3,15) )->setBorderAll(1);
                $i++;
                $i++;


                //Preisdaten
                $z = 0;
                $Document->setValue( $Document->getCell(5,$z), 'Preis- und Kosteninformationen' )->setStyle( $Document->getCell(5,$z) )->setFontBold();
                $Document->setStyle( $Document->getCell(5,$z),$Document->getCell(7,$z) )->mergeCells()->setBorderAll(1);
                $z++;

                if( $Rw != 0 ) {
                    $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                    $Document->setValue( $Document->getCell(5,$z++), 'BLP/VP' );
                    $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                    $Document->setValue( $Document->getCell(5,$z++), 'BLP / TP' );
                    $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                    $Document->setValue( $Document->getCell(5,$z++), 'NLP / VP' );
                    $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                    $Document->setValue( $Document->getCell(5,$z++), 'NLP / TP' );
                }
                else {

                    $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                    $Document->setValue( $Document->getCell(5,$z++), 'BLP/VP' );
                    $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                    $Document->setValue( $Document->getCell(5,$z++), 'NLP / VP' );
                }
                $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(5,$z++), $PartsMoreDescription );
                $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(5,$z++), 'Rabattgruppe' );
                $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(5,$z++), 'variable Kosten' );
                $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(5,$z++), 'Preis gültig ab' );
                $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(5,$z++), 'TNR-Status' );
                $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(5,$z++), 'Konzern-DB' );
                $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(5,$z++), 'FC-Grenze ohne P+M' );
                // if()
                $Document->setStyle( $Document->getCell(5,$z), $Document->getCell(6,$z) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell(5,$z++), 'FC-Grenze mit P+M' );

                $z=1;
                if( $Rw != 0 ) {
                    $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1);
                    $Document->setValue($Document->getCell(7, $z++), $CalcRules->calcGrossPrice( 0, 0, $Rw, 0, 0, 0, $GrossPrice ), PhpExcel::TYPE_NUMERIC );
                    $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1);
                    $Document->setValue($Document->getCell(7, $z++), $GrossPrice, PhpExcel::TYPE_NUMERIC );
                    $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1);
                    $Document->setValue($Document->getCell(7, $z++), $CalcRules->calcNetPrice( $GrossPrice, $Discount, $Rw ), PhpExcel::TYPE_NUMERIC );
                    $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1);
                    $Document->setValue($Document->getCell(7, $z++), $CalcRules->calcNetPrice( $GrossPrice, $Discount ), PhpExcel::TYPE_NUMERIC );
                }
                else {
                    $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1);
                    $Document->setValue($Document->getCell(7, $z++), $GrossPrice, PhpExcel::TYPE_NUMERIC );
                    $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1);
                    $Document->setValue($Document->getCell(7, $z++), $CalcRules->calcNetPrice( $GrossPrice, $Discount ), PhpExcel::TYPE_NUMERIC );
                }
                $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue($Document->getCell(7, $z++), $PartsMoreValue, (($PartsMoreValue == 'nicht vorhanden')? PhpExcel::TYPE_STRING:PhpExcel::TYPE_NUMERIC) );

                $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue($Document->getCell(7, $z++), $DiscountNumber.' - '.$Discount. '%', PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue($Document->getCell(7, $z++), $Costs, PhpExcel::TYPE_NUMERIC );

                $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue($Document->getCell(7, $z++), $DateValidFrom->format('d.m.Y'), PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue($Document->getCell(7, $z++), (($EntityPart->getStatusActive() == '1')? 'aktiv':'inaktiv'), PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue($Document->getCell(7, $z++), $CalcRules->calcCoverageContribution( $CalcRules->calcNetPrice( $GrossPrice, $Discount, $Rw, $PartsMoreDiscount, 0, 0  ) , $Costs ), PhpExcel::TYPE_NUMERIC );

                $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue($Document->getCell(7, $z++), $CalcRules->calcFinancialManagementLimit( $CalcRules->calcGrossPrice( 0, 0, 0, $PartsMoreDiscount, 0, 0, $GrossPrice ), $Costs ), PhpExcel::TYPE_NUMERIC );

                $Document->setStyle( $Document->getCell(7,$z) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue($Document->getCell(7, $z++), $CalcRules->calcFinancialManagementLimit( $GrossPrice, $Costs ), PhpExcel::TYPE_NUMERIC );


                //Preisentwicklung
                if( $PriceHistory ) {
                    $i++;
                    $Document->setValue( $Document->getCell(0,$i), 'Preisentwicklung' )->setStyle( $Document->getCell(0,$i), $Document->getCell(7,$i) )->mergeCells()->setFontBold()->setBorderAll(1);
                    $i++;
                    $Document->setValue( $Document->getCell(0,$i), 'Gueltig an' )->setStyle( $Document->getCell(0,$i) )->setFontBold()->setBorderAll(1);
                    $Document->setValue( $Document->getCell(1,$i), 'BLP' )->setStyle( $Document->getCell(1,$i) )->setFontBold()->setBorderAll(1);
                    $Document->setValue( $Document->getCell(2,$i), 'NLP' )->setStyle( $Document->getCell(2,$i) )->setFontBold()->setBorderAll(1);
                    $Document->setValue( $Document->getCell(3,$i), 'RG' )->setStyle( $Document->getCell(3,$i) )->setFontBold()->setBorderAll(1);
                    $Document->setValue( $Document->getCell(4,$i), 'RS' )->setStyle( $Document->getCell(4,$i) )->setFontBold()->setBorderAll(1);
                    $Document->setValue( $Document->getCell(5,$i), 'RW' )->setStyle( $Document->getCell(5,$i) )->setFontBold()->setBorderAll(1)->setColumnWidth(10);
                    $Document->setValue( $Document->getCell(6,$i), 'VK' )->setStyle( $Document->getCell(6,$i) )->setFontBold()->setBorderAll(1)->setColumnWidth(10);
                    $Document->setValue( $Document->getCell(7,$i), 'DB' )->setStyle( $Document->getCell(7,$i++) )->setFontBold()->setBorderAll(1);

                    foreach((array)$PriceHistory AS $KeyInt => $ValueArray) {
                        $DateValidFrom = new \DateTime( $ValueArray['ValidFrom'] );

                        $PriceNet = $this->getCalculationRules()->calcNetPrice($ValueArray['Data_PriceGross'], $ValueArray['Discount'], $ValueArray['Data_BackValue']);

                        $Document->setValue( $Document->getCell(0,$i), $DateValidFrom->format('d.m.Y'), PHPExcel::TYPE_STRING)->setStyle( $Document->getCell(0,$i) )->setBorderAll(1);
                        $Document->setValue( $Document->getCell(1,$i), $ValueArray['Data_PriceGross'], PHPExcel::TYPE_NUMERIC )->setStyle( $Document->getCell(1,$i) )->setBorderAll(1);
                        $Document->setValue( $Document->getCell(2,$i), $PriceNet, PHPExcel::TYPE_NUMERIC )->setStyle( $Document->getCell(2,$i) )->setBorderAll(1);
                        $Document->setValue( $Document->getCell(3,$i), $ValueArray['DiscountGroupNumber'] )->setStyle( $Document->getCell(3,$i) )->setBorderAll(1);
                        $Document->setValue( $Document->getCell(4,$i), $ValueArray['Discount'] )->setStyle( $Document->getCell(4,$i) )->setBorderAll(1);
                        $Document->setValue( $Document->getCell(5,$i), $ValueArray['Data_BackValue'], PHPExcel::TYPE_NUMERIC )->setStyle( $Document->getCell(5,$i) )->setBorderAll(1);
                        $Document->setValue( $Document->getCell(6,$i), $ValueArray['Data_CostsVariable'], PHPExcel::TYPE_NUMERIC )->setStyle( $Document->getCell(6,$i) )->setBorderAll(1);
                        $Document->setValue( $Document->getCell(7,$i), $this->getCalculationRules()->calcCoverageContribution($PriceNet, $ValueArray['Data_CostsVariable']), PHPExcel::TYPE_NUMERIC )->setStyle( $Document->getCell(7,$i) )->setBorderAll(1);
                        $i++;
                    }
                    $i++;
                }

                //Umsatzdaten
                if( $SalesData ) {

                    $Document->setValue($Document->getCell(0, $i), 'Umsatz')->setStyle($Document->getCell(0,
                        $i))->setFontBold();;
                    $Document->setStyle($Document->getCell(0, $i), $Document->getCell(3, $i))->mergeCells()->setBorderAll(1);
                    $i++;

                    $Document->setValue($Document->getCell(0, $i), '');
                    $Document->setStyle($Document->getCell(0, $i))->setBorderAll(1);
                    $Document->setValue($Document->getCell(1, $i), 'Umsatz');
                    $Document->setStyle($Document->getCell(1, $i), $Document->getCell(2, $i))->mergeCells()->setBorderAll(1);
                    $Document->setValue($Document->getCell(3, $i), 'Anzahl effektiv');
                    $Document->setStyle($Document->getCell(3, $i))->setBorderAll(1);

                    $Document->setValue($Document->getCell(0, $i), '');
                    $Document->setStyle($Document->getCell(0, $i))->setBorderAll(1);

                    $i++;
                    $Document->setValue($Document->getCell(0, $i), '');
                    $Document->setStyle($Document->getCell(0, $i))->setBorderAll(1);
                    $Document->setValue($Document->getCell(1, $i), 'Brutto');
                    $Document->setStyle($Document->getCell(1, $i))->setColumnWidth(15)->setBorderAll(1);
                    $Document->setValue($Document->getCell(2, $i), 'Netto');
                    $Document->setStyle($Document->getCell(2, $i))->setColumnWidth(15)->setBorderAll(1);
                    $Document->setValue($Document->getCell(3, $i), 'Anzahl effektiv');
                    $Document->setStyle($Document->getCell(3, $i))->setColumnWidth(15)->setBorderAll(1);

                    $i++;

                    foreach( (array)$SalesData as $RowIndex => $RowList ) {
                       $ColIndex = 0;
                       foreach( (array)$RowList as $ColName => $ColValue ) {
                            //Zahl
                            if( is_int( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
                                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
                            }
                            elseif( is_float( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
                                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                                $Document->setValue( $Document->getCell( ($ColIndex++), ( $i ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
                            }
                            else {
                                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), $ColValue, PhpExcel::TYPE_STRING );
                            }
                        }
                        $i++;
                    }

                }

            }

            //Wettbewerbsdaten

            $i = 0;
//            $i++;
//    	    $OldI = $i++;

            if($CompetitionData or $CompetitionQ440Data) {
                $Document->createWorksheet('Angebotsdaten')->setPaperOrientationParameter(new PaperOrientationParameter( PaperOrientationParameter::ORIENTATION_LANDSCAPE ));
                $Document->setValue( $Document->getCell(0,$i), 'Angebotsdaten' );
                $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(8,$i) )->mergeCells()->setBorderAll(1)->setFontBold(true);
                $i++;
            }

            if($CompetitionQ440Data) {
                array_walk( $CompetitionQ440Data, array( '\SPHERE\Application\Api\Reporting\Excel\ExcelDirectSearch','WalkE1' ) );

                switch($CompetitionQ440Data[0]['Season']) {
                    case 'S': $Season = 'Sommer';
                        break;
                    case 'W': $Season = 'Winter';
                        break;
                    case 'G': $Season = 'Ganzjahr';
                        break;
                    default: $Season = '';
                        break;
                }

                $Document->setValue( $Document->getCell(0,$i), 'Saison / Sortiment / Sparte' );
                $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(1,$i) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $i++;

                $Document->setValue( $Document->getCell(0,$i), 'Dimension' );
                $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(1,$i) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $i++;

                $Document->setValue( $Document->getCell(0,$i), 'Profil / Hersteller' );
                $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(1,$i) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                $i++;

                if($CompetitionQ440Data[0]['Assortment'] == 'Komplettrad') {
                    $Document->setValue( $Document->getCell(0,$i), 'Rad' );
                    $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(1,$i) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                    $i++;
                }

                if($CompetitionQ440Data[0]['Series'] != '') {
                    $Document->setValue( $Document->getCell(0,$i), 'Baureihe' );
                    $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(1,$i) )->mergeCells()->setBorderAll(1)->setColumnWidth(-1);
                    $i++;
                }


                $i = 1;
                //$i = $OldI;

                $Document->setValue( $Document->getCell(2,$i), $Season . ' / ' .$CompetitionQ440Data[0]['Assortment'] . ' / ' . $CompetitionQ440Data[0]['Section'] );
                $Document->setStyle( $Document->getCell(2,$i),$Document->getCell(7,$i) )->mergeCells()->setBorderAll(1);
                $i++;

                $Document->setValue( $Document->getCell(2,$i), $CompetitionQ440Data[0]['DimensionTyre'] );
                $Document->setStyle( $Document->getCell(2,$i),$Document->getCell(7,$i) )->mergeCells()->setBorderAll(1);
                $i++;

                $Document->setValue( $Document->getCell(2,$i), utf8_decode($CompetitionQ440Data[0]['Profil']) . ' / '.utf8_decode($CompetitionQ440Data[0]['Manufacturer']) );
                $Document->setStyle( $Document->getCell(2,$i),$Document->getCell(7,$i) )->mergeCells()->setBorderAll(1);
                $i++;

                if($CompetitionQ440Data[0]['Assortment'] == 'Komplettrad') {
                    $Document->setValue($Document->getCell(2, $i),
                        utf8_decode($CompetitionQ440Data[0]['DesignRim']) . '<br/>' . $CompetitionQ440Data[0]['DimensionRim'] .
                        (($CompetitionQ440Data[0]['NumberRim'] != '') ? '(' . $CompetitionQ440Data[0]['NumberRim'] . ')' : ''));
                    $Document->setStyle($Document->getCell(2, $i),
                        $Document->getCell(7, $i))->mergeCells()->setBorderAll(1);
                    $i++;
                }

                if($CompetitionQ440Data[0]['Series'] != '') {
                    $Document->setValue($Document->getCell(2, $i), $CompetitionQ440Data[0]['Series'] );
                    $Document->setStyle($Document->getCell(2, $i),
                        $Document->getCell(7, $i))->mergeCells()->setBorderAll(1);
                    $i++;
                }
            }

            if($CompetitionData) {
                array_walk( $CompetitionData, array( '\SPHERE\Application\Api\Reporting\Excel\ExcelDirectSearch','WalkE1' ) );

    	        $i++;
                $ColIndex = 0;

                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), 'Wettbewerber', PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1)->setColumnWidth(15);
                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), 'Hersteller', PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), 'Zeitraum', PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1)->setColumnWidth(10);
                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), 'NLP', PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1)->setColumnWidth(10);
                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), 'BLP', PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1)->setColumnWidth(10);
                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), 'Rabatt', PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1)->setColumnWidth(10);
                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), 'WV / EA', PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1)->setColumnWidth(15);
                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), 'Kommentar', PhpExcel::TYPE_STRING );

                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1)->setColumnWidth(-1);
                $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), 'VF', PhpExcel::TYPE_STRING );


                $i++;
                $ColIndex = 0;
                foreach((array)$CompetitionData AS $RowIndex => $RowList) {
                    $ColIndex = 0;
                    foreach( (array)$RowList as $ColName => $ColValue ) {
                        if($ColName != 'PositionId') {
                            if( is_float( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
                                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                                $Document->setValue( $Document->getCell( ($ColIndex++), ( $i ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
                            }
                            else {
                                $Document->setStyle( $Document->getCell($ColIndex,$i) )->setBorderAll(1);
                                if($ColName == 'CreationDate') {
                                    $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), (new \DateTime( $ColValue ))->format('d.m.Y'), PhPExcel::TYPE_STRING );
                                }
                                else {
                                    $Document->setValue( $Document->getCell( $ColIndex++, ( $i ) ), $ColValue, PhpExcel::TYPE_STRING );
                                }
                            }
                        }
                    }
                    $i++;
                }

            }
        }

        $Document->selectWorksheetByName('Direktsuche');


//
////         Head
//        $HeaderList = array_keys( (array)$DataList[0] );
//        foreach( (array)$HeaderList as $HeaderIndex => $HeaderText ) {
//            if(stripos($HeaderText,';HIDE') == false) {
//                if(strstr($HeaderText,'Group_')) {
//                    $HeaderText = trim(implode('#',array_slice(explode('#',$HeaderText),0,-1)));
//                }
////                Excel::CellStyle(
////                    Excel::CellIndex2Name( $HeaderIndex,0 ),
////                    array('width'=>'auto','text-align'=>'center')
////                );
//                $Document->setStyle( $Document->getCell( $HeaderIndex, 0 ) )->setWrapText();
//                $Document->setValue( $Document->getCell($HeaderIndex,0), trim(str_replace(array_keys($ReplaceArray),$ReplaceArray,$HeaderText)) );
//                //new PhpExcel\Style()
////                Excel::CellWrap(
////                    Excel::CellIndex2Name( $HeaderIndex, 0 )
////                    , true
////                );
//
//            }
//        }
//
//        // Body
//        foreach( (array)$DataList as $RowIndex => $RowList ) {
//            $ColIndex = 0;
//            foreach( (array)$RowList as $ColName => $ColValue ) {
//
//                if(stripos($ColName,';HIDE') == false) {
//                    //Zahl
//                    if( is_int( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
//                        $Document->setValue( $Document->getCell( $ColIndex++, ( $RowIndex +1 ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
//                        //Debugger::screenDump($ColName.'Test'.$ColValue);
//                    }
//                    elseif( is_float( self::ConvertNumeric($ColValue,$ColName) ) == true ) {
//
//                        //$Document->setStyle( $Document->getCell( ($ColIndex++), ( $RowIndex + 1 ) ) )->setFormatCode('#.##0,00');// array('number-format'=>'#,##0.00') );
//                        //Debugger::screenDump($ColName, $ColIndex);
//                        $Document->setValue( $Document->getCell( ($ColIndex++), ( $RowIndex + 1 ) ), $ColValue, PhpExcel::TYPE_NUMERIC );
//                        //Debugger::screenDump($ColName.$ColIndex);
//                    }
//                    else {
//                        $Document->setValue( $Document->getCell( $ColIndex++, ( $RowIndex +1 ) ), $ColValue, PhpExcel::TYPE_STRING );
//                        //Debugger::screenDump($ColName.'Test2');
//                    }
//                }
//            }
//        }

        $Document->saveFile();
        $FilePointer->loadFile();
        //exit();
        print FileSystem::getDownload($FilePointer->getRealPath(), 'Direktsuche.xlsx');
        //Debugger::screenDump($PartNumber, $MarketingCodeNumber, $ProductManagerId);
    }

    private static function ConvertNumeric( $Parameter, $ColName ) {
       if ( substr($ColName,0,5) == "Data_" or substr($ColName,0,6) == "Group_" ) {
           $Parameter = (preg_match('!^[0-9|-]+$!is',$Parameter )?(integer)$Parameter:$Parameter);
           $Parameter = (preg_match('!(^[0-9|-]+\.([0-9]+(E-)[0-9]|[0-9]+)+$|^\.[0]{1,})!is',$Parameter )?(float)$Parameter:$Parameter);
           // done
           return $Parameter;
       }
    }

    private function WalkE1( &$Row ) {
        if( is_array( $Row ) ) {
            array_walk( $Row, array( '\SPHERE\Application\Api\Reporting\Excel\ExcelDirectSearch','WalkE1' ) );
        } else {
            $Row = (!$this->detectUTF8($Row))?utf8_encode($Row):$Row;
        }
    }

    private function detectUTF8( $Value ) {
   		return preg_match('%(?:
           [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
           |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
           |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
           |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
           |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
           |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
           |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
           )+%xs', $Value);
   	}
}