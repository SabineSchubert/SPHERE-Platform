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

    public function getExcel($PartNumber = null, $MarketingCodeNumber = null, $ProductManagerId) {
        $FilePointer = new FilePointer('xlsx');
        $FileLocation = $FilePointer->getFileLocation();
        /**
         * @var PhpExcel $Document
         */
        $Document = Document::getDocument( $FileLocation );


        $Document->setPaperOrientationParameter( new PaperOrientationParameter( PaperOrientationParameter::ORIENTATION_LANDSCAPE ) );


        $Document->setValue( $Document->getCell(0,0), $PartNumber );
        $Document->setValue( $Document->getCell(0,1), $MarketingCodeNumber );
        $Document->setValue( $Document->getCell(0,2), $ProductManagerId );

        if( $MarketingCodeNumber ) {
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

            $Document->setStyle( $Document->getCell(0,7),$Document->getCell(3,10) )->setBorderAll(1);

            $Document->setValue( $Document->getCell(0,$i), 'Umsatz' )->setStyle( $Document->getCell(0,$i) )->setFontBold();;
            $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(3,$i) )->mergeCells();
            $i++;

            $Document->setValue( $Document->getCell(0,$i), '' );
            $Document->setValue( $Document->getCell(1,$i), 'Umsatz' );
            $Document->setStyle( $Document->getCell( 1, $i ), $Document->getCell( 2, $i ) )->mergeCells();
            $Document->setValue( $Document->getCell(3,$i), 'Anzahl effektiv' );

            $Document->setValue( $Document->getCell(0,$i++), '' );

            $i++;
            $Document->setValue( $Document->getCell(0,$i), '' );
            $Document->setValue( $Document->getCell(1,$i), 'Brutto' );
            $Document->setValue( $Document->getCell(2,$i), 'Netto' );
            $Document->setValue( $Document->getCell(3,$i), 'Anzahl effektiv' );
            $Document->setStyle( $Document->getCell(3,$i) )->setColumnWidth(-1);

            //foreach((array))

        }
        elseif( $ProductManagerId ) {
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
            $i++;

            $Document->setValue( $Document->getCell(0,$i), 'Umsatz' )->setStyle( $Document->getCell(0,$i) )->setFontBold();;
            $Document->setStyle( $Document->getCell(0,$i),$Document->getCell(3,$i) )->mergeCells()->setBorderAll(1);
            $i++;

            $Document->setValue( $Document->getCell(0,$i), '' );
            $Document->setValue( $Document->getCell(1,$i), 'Umsatz' );
            $Document->setStyle( $Document->getCell( 1, $i ), $Document->getCell( 2, $i ) )->mergeCells()->setBorderAll(1);
            $Document->setValue( $Document->getCell(3,$i), 'Anzahl effektiv' );
            $Document->setStyle( $Document->getCell(3,$i) )->setBorderAll(1);

            $Document->setValue( $Document->getCell(0,$i), '' );
            $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1);

            $i++;
            $Document->setValue( $Document->getCell(0,$i), '' );
            $Document->setStyle( $Document->getCell(0,$i) )->setBorderAll(1);
            $Document->setValue( $Document->getCell(1,$i), 'Brutto' );
            $Document->setStyle( $Document->getCell(1,$i) )->setBorderAll(1);
            $Document->setValue( $Document->getCell(2,$i), 'Netto' );
            $Document->setStyle( $Document->getCell(2,$i) )->setBorderAll(1);
            $Document->setValue( $Document->getCell(3,$i), 'Anzahl effektiv' );
            $Document->setStyle( $Document->getCell(3,$i) )->setBorderAll(1);
        }
        elseif($PartNumber) {
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

                $i++;
                $i++;
                $Document->setStyle( $Document->getCell(0,13), $Document->getCell(3,15) )->setBorderAll(1);
                $i++;
                $i++;


                $Document->setValue( $Document->getCell(0,$i), 'Umsatz' )->setStyle( $Document->getCell(0,$i) )->setFontBold();;
                $Document->setStyle( $Document->getCell(0,$i), $Document->getCell(3,$i) )->mergeCells();
                $i++;

                $Document->setValue( $Document->getCell(0,$i), '' );
                $Document->setValue( $Document->getCell(1,$i), 'Umsatz' );
                $Document->setStyle( $Document->getCell( 1, $i ), $Document->getCell( 2, $i ) )->mergeCells();
                $Document->setValue( $Document->getCell(3,$i), 'Anzahl effektiv' );

                $Document->setValue( $Document->getCell(0,$i), '' );

                $i++;
                $Document->setValue( $Document->getCell(0,$i), '' );
                $Document->setValue( $Document->getCell(1,$i), 'Brutto' );
                $Document->setValue( $Document->getCell(2,$i), 'Netto' );
                $Document->setValue( $Document->getCell(3,$i), 'Anzahl effektiv' );
                $Document->setStyle( $Document->getCell(3,$i) )->setColumnWidth(-1);
            }
        }


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
}