<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:41
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Presto\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblImport_Presto
 * @package SPHERE\Application\Reporting\DataWareHouse\Presto\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblImport_Presto")
 * @ORM\Cache(usage="READ_WRITE")
 */
class TblImport_Presto extends AbstractEntity
{
    const PRODUCT_GROUP_NUMBER = 'ProductGroupNumber';
//    const PRODUCT_GROUP_NAME = 'ProductGroupName';

    const MARKETING_CODE_NUMBER = 'MarketingCodeNumber';
    const MARKETING_CODE_NAME = 'MarketingCodeName';

    const ASSORTMENT_GROUP_NUMBER = 'AssortmentGroupNumber';
//    const ASSORTMENT_GROUP_NAME = 'AssortmentGroupName';

//    const SECTION_ALIAS = 'SectionAlias';
    const SECTION_NUMBER = 'SectionNumber';
//    const SECTION_NAME = 'SectionName';

    const PART_NUMBER = 'PartNumber';
    const PART_NAME = 'PartName';
    const PART_NUMBER_DISPLAY = 'PartNumberDisplay';
    const PART_ES_1 = 'PartEs1';
    const PART_ES_2 = 'PartEs2';
    const PART_SUCCESSOR = 'PartSuccessor';
    const PART_PREDECESSOR = 'PartPredecessor';
    const PART_OPTIONAL = 'PartOptional';
    const PART_SPARE_PART_DESIGN = 'PartSparePartDesign';
    const PART_STATUS_ACTIVE = 'PartStatusActive';

    const PRICE_PRICE_GROSS = 'PricePriceGross';
    const PRICE_PRICE_NET = 'PricePriceNet';
    const PRICE_BACK_VALUE = 'PriceBackValue';
    const PRICE_COSTS_VARIABLE = 'PriceCostsVariable';
    const PRICE_VALID_FROM = 'PriceValidFrom';

    const DISCOUNT_GROUP_NUMBER = 'DiscountGroupNumber';
    const DISCOUNT_DISCOUNT = 'DiscountDiscount';

    const SUPPLIER_NUMBER_1 = 'SupplierNumber1';
    const SUPPLIER_NAME_1 = 'SupplierName1';
    const SUPPLIER_NUMBER_2 = 'SupplierNumber2';
    const SUPPLIER_NAME_2 = 'SupplierName2';
    const SUPPLIER_NUMBER_3 = 'SupplierNumber3';
    const SUPPLIER_NAME_3 = 'SupplierName3';


    /**
     * @ORM\Column(type="string")
     */
    protected $ProductGroupNumber;
    /**
     * @ORM\Column(type="string")
     */
//    protected $ProductGroupName;



    /**
     * @ORM\Column(type="string")
     */
    protected $MarketingCodeNumber;
    /**
     * @ORM\Column(type="string")
     */
    protected $MarketingCodeName;




    /**
     * @ORM\Column(type="string")
     */
    protected $AssortmentGroupNumber;
    /**
     * @ORM\Column(type="string")
     */
//    protected $AssortmentGroupName;




    /**
     * @ORM\Column(type="string")
     */
//    protected $SectionAlias;
    /**
     * @ORM\Column(type="string")
     */
    protected $SectionNumber;
    /**
     * @ORM\Column(type="string")
     */
//    protected $SectionName;





    /**
     * @ORM\Column(type="string")
     */
    protected $PartNumber;
    /**
     * @ORM\Column(type="string")
     */
    protected $PartName;
    /**
     * @ORM\Column(type="string")
     */
    protected $PartNumberDisplay;
    /**
     * @ORM\Column(type="string")
     */
    protected $PartEs1;
    /**
     * @ORM\Column(type="string")
     */
    protected $PartEs2;
    /**
     * @ORM\Column(type="string")
     */
    protected $PartSuccessor;
    /**
     * @ORM\Column(type="string")
     */
    protected $PartPredecessor;
    /**
     * @ORM\Column(type="string")
     */
    protected $PartOptional;
    /**
     * @ORM\Column(type="string")
     */
    protected $PartSparePartDesign;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $PartStatusActive;



    /**
    * @ORM\Column(type="float")
    */
    protected $PricePriceGross;
    /**
    * @ORM\Column(type="float")
    */
    protected $PricePriceNet;
    /**
    * @ORM\Column(type="float")
    */
    protected $PriceBackValue;
    /**
    * @ORM\Column(type="float")
    */
    protected $PriceCostsVariable;
    /**
    * @ORM\Column(type="string")
    */
    protected $PriceValidFrom;



    /**
   * @ORM\Column(type="string")
   */
    protected $DiscountGroupNumber;
    /**
    * @ORM\Column(type="float")
    */
    protected $DiscountDiscount;



    /**
      * @ORM\Column(type="string")
      */
    protected $SupplierNumber1;
    /**
      * @ORM\Column(type="string")
      */
    protected $SupplierName1;
    /**
      * @ORM\Column(type="string")
      */
    protected $SupplierNumber2;
    /**
      * @ORM\Column(type="string")
      */
    protected $SupplierName2;
    /**
      * @ORM\Column(type="string")
      */
    protected $SupplierNumber3;
    /**
      * @ORM\Column(type="string")
      */
    protected $SupplierName3;

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
        //$value = new \DateTime($value);
    }

}