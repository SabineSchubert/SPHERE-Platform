<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 29.03.2017
 * Time: 08:32
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_AssortmentGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Brand;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_AssortmentGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_Section;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_PartsMore;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_Brand;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_ProductManagerGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManagerGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Sales;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Section;
use SPHERE\System\Database\Binding\AbstractSetup;

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

        $TableBrand = $this->setTableBrand( $Schema );
        $TableProductManager = $this->setTableProductManager( $Schema );
        $this->setTableProductManagerBrand( $Schema, $TableProductManager, $TableBrand );

        $TableProductManagerGroup = $this->setTableProductManagerGroup( $Schema );
        $this->setTableProductManagerProductManagerGroup( $Schema, $TableProductManager, $TableProductManagerGroup );


        $TableProductGroup = $this->setTableProductGroup( $Schema );
        $this->setTableProductManagerProductGroup( $Schema, $TableProductManager, $TableProductGroup );

        $TableMarketingCode = $this->setTableMarketingCode( $Schema );
        $this->setTableProductManagerMarketingCode( $Schema, $TableProductManager, $TableMarketingCode );

        $TablePart = $this->setTablePart( $Schema );
        $this->setTablePartMarketingCode( $Schema, $TablePart, $TableMarketingCode );

        $TableSection = $this->setTableSection( $Schema );
        $this->setTablePartSection( $Schema, $TablePart, $TableSection );

        $TableAssortmentGroup = $this->setTableAssortmentGroup( $Schema );
        $this->setTablePartAssortmentGroup( $Schema, $TablePart, $TableAssortmentGroup );

        $TablePartsMore = $this->setTablePartsMore( $Schema );


        $this->setTableSales( $Schema, $TablePart );
        return $this->saveSchema($Schema, $Simulate);
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableBrand( Schema &$Schema ) {
        $TableBrand = new TblReporting_Brand();
        $Table = $this->createTable( $Schema, $TableBrand->getEntityShortName() );
        $this->createColumn( $Table, $TableBrand::ATTR_ALIAS, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableBrand::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableProductManager( Schema &$Schema ) {
        $TableProductManager = new TblReporting_ProductManager();
        $Table = $this->createTable( $Schema, $TableProductManager->getEntityShortName() );
        $this->createColumn( $Table, $TableProductManager::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableProductManager::ATTR_DEPARTMENT, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TableProductManager
     * @param Table $TableBrand
     * @return Table
     */
    private function setTableProductManagerBrand( Schema &$Schema, Table $TableProductManager, Table $TableBrand ) {
        $TableProductManagerBrand = new TblReporting_ProductManager_Brand();
        $Table = $this->createTable( $Schema, $TableProductManagerBrand->getEntityShortName() );
        $this->createForeignKey( $Table, $TableBrand );
        $this->createForeignKey( $Table, $TableProductManager );
        return $Table;
    }

    private function setTableProductManagerGroup( Schema &$Schema ) {
        $TableProductManagerGroup = new TblReporting_ProductManagerGroup();
        $Table = $this->createTable( $Schema, $TableProductManagerGroup->getEntityShortName() );
        $this->createColumn( $Table, $TableProductManagerGroup::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableProductManagerGroup::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    private function setTableProductGroup( Schema &$Schema ) {
        $TableProductGroup = new TblReporting_ProductGroup();
        $Table = $this->createTable( $Schema, $TableProductGroup->getEntityShortName() );
        $this->createColumn( $Table, $TableProductGroup::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableProductGroup::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    private function setTableProductManagerProductGroup( Schema &$Schema, Table $TableProductManager, Table $TableProductGroup ) {
        $TableProductManagerProductGroup = new TblReporting_ProductManager_ProductGroup();
        $Table = $this->createTable( $Schema, $TableProductManagerProductGroup->getEntityShortName() );
        $this->createForeignKey( $Table, $TableProductManager );
        $this->createForeignKey( $Table, $TableProductGroup );
        return $Table;
    }

    private function setTableProductManagerProductManagerGroup( Schema &$Schema, Table $TableProductManager, Table $TableProductManagerGroup ) {
        $TableProductManagerProductManagerGroup = new TblReporting_ProductManager_ProductManagerGroup();
        $Table = $this->createTable( $Schema, $TableProductManagerProductManagerGroup->getEntityShortName() );
        $this->createForeignKey( $Table, $TableProductManager );
        $this->createForeignKey( $Table, $TableProductManagerGroup );
        return $Table;
    }

    //ToDO: Produktebene ProductLevel Beziehung

    private function setTableMarketingCode( Schema &$Schema ) {
        $TableMarketingCode = new TblReporting_MarketingCode();
        $Table = $this->createTable( $Schema, $TableMarketingCode->getEntityShortName() );
        $this->createColumn( $Table, $TableMarketingCode::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableMarketingCode::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    private function setTableProductManagerMarketingCode( Schema &$Schema, Table $TableProductManager, Table $TableMarketingCode ) {
        $TableProductManagerMarketingCode = new TblReporting_ProductManager_MarketingCode();
        $Table = $this->createTable( $Schema, $TableProductManagerMarketingCode->getEntityShortName() );
        $this->createForeignKey( $Table, $TableProductManager );
        $this->createForeignKey( $Table, $TableMarketingCode );
        return $Table;
    }

    private function setTablePart( Schema &$Schema ) {
        $TablePart = new TblReporting_Part();
        $Table = $this->createTable( $Schema, $TablePart->getEntityShortName() );
        $this->createColumn( $Table, $TablePart::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePart::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    private function setTablePartMarketingCode( Schema &$Schema, Table $TablePart, Table $TableMarketingCode ) {
        $TablePartMarketingCode = new TblReporting_Part_MarketingCode();
        $Table = $this->createTable( $Schema, $TablePartMarketingCode->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart );
        $this->createForeignKey( $Table, $TableMarketingCode );
        return $Table;
    }

    private function setTableSection( Schema &$Schema ) {
        $TableSection = new TblReporting_Section();
        $Table = $this->createTable( $Schema, $TableSection->getEntityShortName() );
        $this->createColumn( $Table, $TableSection::ATTR_ALIAS, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableSection::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableSection::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    private function setTablePartSection( Schema &$Schema, Table $TablePart, Table $TableSection ) {
        $TablePartSection = new TblReporting_Part_Section();
        $Table = $this->createTable( $Schema, $TablePartSection->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart );
        $this->createForeignKey( $Table, $TableSection );
        return $Table;
    }

    private function setTableAssortmentGroup( Schema &$Schema ) {
        $TableAssortmentGroup = new TblReporting_AssortmentGroup();
        $Table = $this->createTable( $Schema, $TableAssortmentGroup->getEntityShortName() );
        $this->createColumn( $Table, $TableAssortmentGroup::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableAssortmentGroup::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    private function setTablePartAssortmentGroup( Schema &$Schema, Table $TablePart, Table $TableAssortmentGroup ) {
        $TablePartAssortmentGroup = new TblReporting_Part_AssortmentGroup();
        $Table = $this->createTable( $Schema, $TablePartAssortmentGroup->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart );
        $this->createForeignKey( $Table, $TableAssortmentGroup );
        return $Table;
    }

    private function setTablePartsMore( Schema &$Schema ) {
        $TablePartsMore = new TblReporting_PartsMore();
        $Table = $this->createTable( $Schema, $TablePartsMore->getEntityShortName() );
        $this->createColumn( $Table, $TablePartsMore::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_DESCRIPTION, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_TYPE, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_VALUE, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_DATE_FROM, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_DATE_TO, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    private function setTableSales( Schema &$Schema, Table $TablePart ) {
        $TableSales = new TblReporting_Sales();
        $Table = $this->createTable( $Schema, $TableSales->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart );
        $this->createColumn( $Table, $TableSales::ATTR_MONTH, self::FIELD_TYPE_INTEGER, false);
        $this->createColumn( $Table, $TableSales::ATTR_YEAR, self::FIELD_TYPE_INTEGER, false);
        $this->createColumn( $Table, $TableSales::ATTR_QUANTITY, self::FIELD_TYPE_INTEGER, false);
        $this->createColumn( $Table, $TableSales::ATTR_SALES_GROSS, self::FIELD_TYPE_FLOAT, false);
        $this->createColumn( $Table, $TableSales::ATTR_SALES_NET, self::FIELD_TYPE_FLOAT, false);
        return $Table;
    }


}