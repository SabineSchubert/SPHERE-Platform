<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 22.05.2017
 * Time: 11:06
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Sales\Service;


use SPHERE\Application\Reporting\DataWareHouse\Sales\Service\Entity\TblImport_Sales;
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

        $this->setTableImportSales( $Schema );

        return $this->saveSchema( $Schema, $Simulate );
    }

    private function setTableImportSales( Schema $Schema )
    {
        $TableSales = new TblImport_Sales();
        $Table = $this->createTable( $Schema, $TableSales->getEntityShortName() );
        $this->createColumn( $Table, $TableSales::PART_NUMBER, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::PART_DESCRIPTION, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::MARKETING_CODE_NUMBER, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::MARKETING_CODE_DESCRIPTION, self::FIELD_TYPE_STRING, true);
        $this->createColumn( $Table, $TableSales::MONTH, self::FIELD_TYPE_INTEGER, true);
        $this->createColumn( $Table, $TableSales::YEAR, self::FIELD_TYPE_INTEGER, true);
        $this->createColumn( $Table, $TableSales::QUANTITY, self::FIELD_TYPE_INTEGER, true);
        $this->createColumn( $Table, $TableSales::SALES_GROSS, self::FIELD_TYPE_FLOAT, true);
        $this->createColumn( $Table, $TableSales::SALES_NET, self::FIELD_TYPE_FLOAT, true);

        return $Table;
    }

}