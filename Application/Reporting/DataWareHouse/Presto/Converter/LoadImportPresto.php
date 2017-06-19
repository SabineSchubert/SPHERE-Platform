<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 13:05
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Presto\Converter;


use SPHERE\Application\Platform\Utility\Transfer\AbstractConverter;
use SPHERE\Application\Platform\Utility\Transfer\FieldPointer;
use SPHERE\System\Extension\Repository\Debugger;

class LoadImportPresto extends AbstractConverter
{
    /** @var bool $Valid */
    private $Valid = true;

    public function __construct($File)
    {
       //ToDo: csv-Import
       $this->loadFile($File);

       $this->setPointer( new FieldPointer( 'A', 'A' ) );
       $this->setPointer( new FieldPointer( 'B', 'B' ) );
       $this->setPointer( new FieldPointer( 'C', 'C' ) );
       $this->setPointer( new FieldPointer( 'D', 'D' ) );
       $this->setPointer( new FieldPointer( 'E', 'E' ) );
       $this->setPointer( new FieldPointer( 'F', 'F' ) );
       $this->setPointer( new FieldPointer( 'G', 'G' ) );
       $this->setPointer( new FieldPointer( 'H', 'H' ) );
       $this->setPointer( new FieldPointer( 'I', 'I' ) );
       $this->setPointer( new FieldPointer( 'J', 'J' ) );
       $this->setPointer( new FieldPointer( 'K', 'K' ) );
       $this->setPointer( new FieldPointer( 'L', 'L' ) );
       $this->setPointer( new FieldPointer( 'M', 'M' ) );
       $this->setPointer( new FieldPointer( 'N', 'N' ) );
       $this->setPointer( new FieldPointer( 'O', 'O' ) );
       $this->setPointer( new FieldPointer( 'P', 'P' ) );
       $this->setPointer( new FieldPointer( 'Q', 'Q' ) );
       $this->setPointer( new FieldPointer( 'R', 'R' ) );
       $this->setPointer( new FieldPointer( 'S', 'S' ) );
       $this->setPointer( new FieldPointer( 'T', 'T' ) );
       $this->setPointer( new FieldPointer( 'U', 'U' ) );
       $this->setPointer( new FieldPointer( 'V', 'V' ) );
       $this->setPointer( new FieldPointer( 'W', 'W' ) );
       $this->setPointer( new FieldPointer( 'X', 'X' ) );
       $this->setPointer( new FieldPointer( 'Y', 'Y' ) );
       $this->setPointer( new FieldPointer( 'Z', 'Z' ) );
       $this->setPointer( new FieldPointer( 'AA', 'AA' ) );
       $this->setPointer( new FieldPointer( 'AB', 'AB' ) );

       $this->scanFile(0, 1);
    }

    /**
    * @param array $Row
    *
    * @return mixed|void
    */
    public function runConvert($Row)
    {
        $Header = array(
           'A' => 'ProductGroupNumber',
           'B' => 'MarketingCodeNumber',
           'C' => 'MarketingCodeName',
           'D' => 'AssortmentGroupNumber',
           'E' => 'SectionNumber',
           'F' => 'PartNumber',
           'G' => 'PartName',
           'H' => 'PartNumberDisplay',
           'I' => 'PartEs1',
           'J' => 'PartEs2',
           'K' => 'PartSuccessor',
           'L' => 'PartPredecessor',
           'M' => 'PartOptional',
           'N' => 'PartSparePartDesign',
           'O' => 'PartStatusActive',
           'P' => 'PricePriceGross',
           'Q' => 'PricePriceNet',
           'R' => 'PriceBackValue',
           'S' => 'PriceCostsVariable',
           'T' => 'PriceValidFrom',
           'U' => 'DiscountGroupNumber',
           'V' => 'DiscountDiscount',
           'W' => 'SupplierNumber1',
           'X' => 'SupplierName1',
           'Y' => 'SupplierNumber2',
           'Z' => 'SupplierName2',
           'AA' => 'SupplierNumber3',
           'AB' => 'SupplierName3',
       );

//       $Header = array(
//           'A' => 'MarketingCodeNumber',
//           'B' => 'MarketingCodeName',
//           'C' => 'SectionAlias',
//           'D' => 'SectionNumber',
//           'E' => 'SectionName',
//           'F' => 'ProductGroupNumber',
//           'G' => 'ProductGroupName',
//           'H' => 'AssortmentGroupNumber',
//           'I' => 'AssortmentGroupName',
//           'J' => 'PartNumber',
//           'K' => 'PartName',
//           'L' => 'PartNumberDisplay',
//           'M' => 'PartEs1',
//           'N' => 'PartEs2',
//           'O' => 'PartSuccessor',
//           'P' => 'PartPredecessor',
//           'Q' => 'PartOptional',
//           'R' => 'PartSparePartDesign',
//           'S' => 'PartStatusActive',
//           'T' => 'PricePriceGross',
//           'U' => 'PricePriceNet',
//           'V' => 'PriceBackValue',
//           'W' => 'PriceCostsVariable',
//           'X' => 'PriceValidFrom',
//           'Y' => 'DiscountGroupNumber',
//           'Z' => 'DiscountDiscount',
//           'AA' => 'SupplierNumber1',
//           'AB' => 'SupplierName1',
//           'AC' => 'SupplierNumber2',
//           'AD' => 'SupplierName2',
//           'AE' => 'SupplierNumber3',
//           'AF' => 'SupplierName3',
//       );

       foreach( $Header as $Column => $Name ) {
           if($Row[$Column][$Column] != $Name) {
                   $this->Valid = false;
                   break;
           }
       }
    }

    /**
    * @return bool
    */
    public function isValid()
    {
       return $this->Valid;
    }
}