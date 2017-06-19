<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:33
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Presto\Service;


use SPHERE\Application\Reporting\DataWareHouse\Presto\Service\Entity\TblImport_Presto;
use SPHERE\System\Database\Binding\AbstractSetup;
use Doctrine\DBAL\Schema\Schema;

class Setup extends AbstractSetup
{

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema($Simulate = true)
    {
        $Schema = $this->loadSchema();

       $this->setTableImportPresto( $Schema );

       return $this->saveSchema( $Schema, $Simulate );
    }


    private function setTableImportPresto( Schema $Schema )
    {
        $TableSales = new TblImport_Presto();
        $Table = $this->createTable( $Schema, $TableSales->getEntityShortName() );

        $this->createColumn( $Table, $TableSales::PRODUCT_GROUP_NUMBER, self::FIELD_TYPE_STRING, true);
//        $this->createColumn( $Table, $TableSales::PRODUCT_GROUP_NAME, self::FIELD_TYPE_STRING, true);

        $this->createColumn( $Table, $TableSales::MARKETING_CODE_NUMBER, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::MARKETING_CODE_NAME, self::FIELD_TYPE_STRING, true);

        $this->createColumn( $Table, $TableSales::ASSORTMENT_GROUP_NUMBER, self::FIELD_TYPE_STRING, true);
//        $this->createColumn( $Table, $TableSales::ASSORTMENT_GROUP_NAME, self::FIELD_TYPE_STRING, true);

//        $this->createColumn( $Table, $TableSales::SECTION_ALIAS, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::SECTION_NUMBER, self::FIELD_TYPE_STRING, true);
//        $this->createColumn( $Table, $TableSales::SECTION_NAME, self::FIELD_TYPE_STRING, true);


        $this->createColumn( $Table, $TableSales::PART_NUMBER, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_NAME, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_NUMBER_DISPLAY, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_ES_1, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_ES_2, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_SUCCESSOR, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_PREDECESSOR, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_OPTIONAL, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_SPARE_PART_DESIGN, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_STATUS_ACTIVE, self::FIELD_TYPE_BOOLEAN, true);

        $this->createColumn( $Table, $TableSales::PRICE_PRICE_GROSS, self::FIELD_TYPE_FLOAT, true);
        $this->createColumn( $Table, $TableSales::PRICE_PRICE_NET, self::FIELD_TYPE_FLOAT, true);
        $this->createColumn( $Table, $TableSales::PRICE_BACK_VALUE, self::FIELD_TYPE_FLOAT, true);
        $this->createColumn( $Table, $TableSales::PRICE_COSTS_VARIABLE, self::FIELD_TYPE_FLOAT, true);
        $this->createColumn( $Table, $TableSales::PRICE_VALID_FROM, self::FIELD_TYPE_STRING, true);

        $this->createColumn( $Table, $TableSales::DISCOUNT_GROUP_NUMBER, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::DISCOUNT_DISCOUNT, self::FIELD_TYPE_FLOAT, true);

        $this->createColumn( $Table, $TableSales::SUPPLIER_NUMBER_1, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::SUPPLIER_NAME_1, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::SUPPLIER_NUMBER_2, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::SUPPLIER_NAME_2, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::SUPPLIER_NUMBER_3, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::SUPPLIER_NAME_3, self::FIELD_TYPE_STRING, true);


        return $Table;
    }
}