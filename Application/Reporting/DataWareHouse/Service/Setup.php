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
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_DiscountGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode_PartsMore;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_AssortmentGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_Section;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_Supplier;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_PartsMore;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Price;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup_ProductLevel;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductLevel;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part_Brand;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode_ProductGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager_ProductManagerGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManagerGroup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Sales;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Section;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Supplier;
use SPHERE\System\Database\Binding\AbstractSetup;
use SPHERE\System\Database\Fitting\View;
use SPHERE\System\Extension\Repository\Debugger;

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

        $TableProductManager = $this->setTableProductManager( $Schema );

        $TableProductManagerGroup = $this->setTableProductManagerGroup( $Schema );
        $this->setTableProductManagerProductManagerGroup( $Schema, $TableProductManager, $TableProductManagerGroup );

        $TableMarketingCode = $this->setTableMarketingCode( $Schema );
        $this->setTableProductManagerMarketingCode( $Schema, $TableProductManager, $TableMarketingCode );

        $TableProductGroup = $this->setTableProductGroup( $Schema );
        $this->setTableMarketingCodeProductGroup( $Schema, $TableMarketingCode, $TableProductGroup );

        $TableProductLevel = $this->setTableProductLevel( $Schema );
        $this->setTableProductGroupProductLevel( $Schema, $TableProductGroup, $TableProductLevel );

        $TablePartsMore = $this->setTablePartsMore( $Schema );
        $this->setTableMarketingCodePartsMore( $Schema, $TableMarketingCode, $TablePartsMore );

        $TablePart = $this->setTablePart( $Schema );
        $this->setTablePartMarketingCode( $Schema, $TablePart, $TableMarketingCode );

        $TableSection = $this->setTableSection( $Schema );
        $this->setTablePartSection( $Schema, $TablePart, $TableSection );

        $TableBrand = $this->setTableBrand( $Schema );
        $this->setTablePartBrand( $Schema, $TablePart, $TableBrand );

        $TableAssortmentGroup = $this->setTableAssortmentGroup( $Schema );
        $this->setTablePartAssortmentGroup( $Schema, $TablePart, $TableAssortmentGroup );

        $TableDiscountGroup = $this->setTableDiscountGroup( $Schema );

        $TablePrice = $this->setTablePrice( $Schema, $TablePart, $TableDiscountGroup );

        $TableSupplier = $this->setTableSupplier( $Schema );
        $this->setTablePartSupplier( $Schema, $TablePart, $TableSupplier );

        $this->setTableSales( $Schema, $TablePart );

//        $this->getConnection()->createView(


//        Debugger::screenDump(
        $this->getConnection()->createView(
            (new View( $this->getConnection(), 'ViewPart' ))
                    ->addLink(
                        new TblReporting_Part_MarketingCode(), 'TblReporting_Part',
                        new TblReporting_Part(), 'Id'
                    )
                    ->addLink(
                        new TblReporting_Part_MarketingCode(), 'TblReporting_MarketingCode',
                        new TblReporting_MarketingCode(), 'Id'
                    )
                    ->addLink(
                        new TblReporting_MarketingCode(), 'Id',
                        new TblReporting_ProductManager_MarketingCode(), 'TblReporting_MarketingCode'
                    )
                    ->addLink(
                        new TblReporting_ProductManager_MarketingCode(), 'TblReporting_MarketingCode',
                        new TblReporting_ProductManager(), 'Id'
                    )
                    ->addLink(
                        new TblReporting_MarketingCode(), 'Id',
                        new TblReporting_MarketingCode_ProductGroup(), 'TblReporting_MarketingCode'
                    )
                    ->addLink(
                        new TblReporting_MarketingCode_ProductGroup(), 'TblReporting_ProductGroup',
                        new TblReporting_ProductGroup(), 'Id'
                    )
                    ->addLink(
                        new TblReporting_ProductGroup(), 'Id',
                        new TblReporting_ProductGroup_ProductLevel(), 'TblReporting_ProductGroup'
                    )
                    ->addLink(
                        new TblReporting_ProductGroup_ProductLevel(), 'TblReporting_ProductLevel',
                        new TblReporting_ProductLevel(), 'Id'
                    )
                    ->addLink(
                        new TblReporting_Part(), 'Id',
                        new TblReporting_Part_Section(), 'TblReporting_Part'
                    )
                    ->addLink(
                        new TblReporting_Part(), 'Id',
                        new TblReporting_Part_Brand(), 'TblReporting_Part'
                    )
                    ->addLink(
                        new TblReporting_Part_Brand(), 'TblReporting_Part',
                        new TblReporting_Brand(), 'Id'
                    )
                    ->addLink(
                        new TblReporting_MarketingCode(), 'Id',
                        new TblReporting_MarketingCode_PartsMore(), 'TblReporting_MarketingCode'
                    )
                    ->addLink(
                        new TblReporting_MarketingCode_PartsMore(), 'TblReporting_MarketingCode',
                        new TblReporting_PartsMore(), 'Id'
                    )//->getView()->getSql()
//                );
        );

        $this->getConnection()->createView(
            ( new View( $this->getConnection(), 'ViewPrice' ) )
                ->addLink(
                    new TblReporting_Price(), 'TblReporting_DiscountGroup',
                    new TblReporting_DiscountGroup(), 'Id'
                )
        );

        return $this->saveSchema($Schema, $Simulate);
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
     * @return Table
     */
    private function setTableProductManagerGroup( Schema &$Schema ) {
        $TableProductManagerGroup = new TblReporting_ProductManagerGroup();
        $Table = $this->createTable( $Schema, $TableProductManagerGroup->getEntityShortName() );
        $this->createColumn( $Table, $TableProductManagerGroup::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableProductManagerGroup::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableProductLevel( Schema &$Schema ) {
        $TableProductLevel = new TblReporting_ProductLevel();
        $Table = $this->createTable( $Schema, $TableProductLevel->getEntityShortName() );
        $this->createColumn( $Table, $TableProductLevel::ATTR_NUMBER );
        $this->createColumn( $Table, $TableProductLevel::ATTR_NAME );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TableProductGroup
     * @param Table $TableProductLevel
     * @return Table
     */
    private function setTableProductGroupProductLevel( Schema &$Schema, Table $TableProductGroup, Table $TableProductLevel ) {
        $TableProductGroupProductLevel = new TblReporting_ProductGroup_ProductLevel();
        $Table = $this->createTable( $Schema, $TableProductGroupProductLevel->getEntityShortName() );
        $this->createForeignKey( $Table, $TableProductGroup );
        $this->createForeignKey( $Table, $TableProductLevel );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TableProductManager
     * @param Table $TableProductManagerGroup
     * @return Table
     */
    private function setTableProductManagerProductManagerGroup( Schema &$Schema, Table $TableProductManager, Table $TableProductManagerGroup ) {
        $TableProductManagerProductManagerGroup = new TblReporting_ProductManager_ProductManagerGroup();
        $Table = $this->createTable( $Schema, $TableProductManagerProductManagerGroup->getEntityShortName() );
        $this->createForeignKey( $Table, $TableProductManager );
        $this->createForeignKey( $Table, $TableProductManagerGroup );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableMarketingCode( Schema &$Schema ) {
        $TableMarketingCode = new TblReporting_MarketingCode();
        $Table = $this->createTable( $Schema, $TableMarketingCode->getEntityShortName() );
        $this->createColumn( $Table, $TableMarketingCode::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableMarketingCode::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TableProductManager
     * @param Table $TableMarketingCode
     * @return Table
     */
    private function setTableProductManagerMarketingCode( Schema &$Schema, Table $TableProductManager, Table $TableMarketingCode ) {
        $TableProductManagerMarketingCode = new TblReporting_ProductManager_MarketingCode();
        $Table = $this->createTable( $Schema, $TableProductManagerMarketingCode->getEntityShortName() );
        $this->createForeignKey( $Table, $TableProductManager );
        $this->createForeignKey( $Table, $TableMarketingCode );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableProductGroup( Schema &$Schema ) {
        $TableProductGroup = new TblReporting_ProductGroup();
        $Table = $this->createTable( $Schema, $TableProductGroup->getEntityShortName() );
        $this->createColumn( $Table, $TableProductGroup::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableProductGroup::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TableMarketingCode
     * @param Table $TableProductGroup
     * @return Table
     */
    private function setTableMarketingCodeProductGroup( Schema &$Schema, Table $TableMarketingCode, Table $TableProductGroup ) {
        $TableMarketingCodeProductGroup = new TblReporting_MarketingCode_ProductGroup();
        $Table = $this->createTable( $Schema, $TableMarketingCodeProductGroup->getEntityShortName() );
        $this->createForeignKey( $Table, $TableMarketingCode );
        $this->createForeignKey( $Table, $TableProductGroup );
        return $Table;
    }

    private function setTablePartsMore( Schema &$Schema ) {
        $TablePartsMore = new TblReporting_PartsMore();
        $Table = $this->createTable( $Schema, $TablePartsMore->getEntityShortName() );
        $this->createColumn( $Table, $TablePartsMore::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_DESCRIPTION, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_TYPE, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_VALUE, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_VALID_FROM, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePartsMore::ATTR_VALID_TO, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TableMarketingCode
     * @param Table $TablePartsMore
     * @return Table
     */
    private function setTableMarketingCodePartsMore( Schema &$Schema, Table $TableMarketingCode, Table $TablePartsMore ) {
        $TableMarketingCodePartsMore = new TblReporting_MarketingCode_PartsMore();
        $Table = $this->createTable( $Schema, $TableMarketingCodePartsMore->getEntityShortName() );
        $this->createForeignKey( $Table, $TableMarketingCode );
        $this->createForeignKey( $Table, $TablePartsMore );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTablePart( Schema &$Schema ) {
        $TablePart = new TblReporting_Part();
        $Table = $this->createTable( $Schema, $TablePart->getEntityShortName() );
        $this->createColumn( $Table, $TablePart::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePart::ATTR_NUMBER_DISPLAY, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePart::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePart::ATTR_SPARE_PART_DESIGN, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePart::ATTR_STATUS_ACTIVE, self::FIELD_TYPE_BOOLEAN, false );

        $this->createColumn( $Table, $TablePart::ATTR_PREDECESSOR, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePart::ATTR_SUCCESSOR, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TablePart::ATTR_OPTIONAL_NUMBER, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TablePart
     * @param Table $TableMarketingCode
     * @return Table
     */
    private function setTablePartMarketingCode( Schema &$Schema, Table $TablePart, Table $TableMarketingCode ) {
        $TablePartMarketingCode = new TblReporting_Part_MarketingCode();
        $Table = $this->createTable( $Schema, $TablePartMarketingCode->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart );
        $this->createForeignKey( $Table, $TableMarketingCode );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableSection( Schema &$Schema ) {
        $TableSection = new TblReporting_Section();
        $Table = $this->createTable( $Schema, $TableSection->getEntityShortName() );
        $this->createColumn( $Table, $TableSection::ATTR_ALIAS, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableSection::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableSection::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TablePart
     * @param Table $TableSection
     * @return Table
     */
    private function setTablePartSection( Schema &$Schema, Table $TablePart, Table $TableSection ) {
        $TablePartSection = new TblReporting_Part_Section();
        $Table = $this->createTable( $Schema, $TablePartSection->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart );
        $this->createForeignKey( $Table, $TableSection );
        return $Table;
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
     * @param Table $TablePart
     * @param Table $TableBrand
     * @return Table
     */
    private function setTablePartBrand( Schema &$Schema, Table $TablePart, Table $TableBrand ) {
        $TablePartBrand = new TblReporting_Part_Brand();
        $Table = $this->createTable( $Schema, $TablePartBrand->getEntityShortName() );
        $this->createForeignKey( $Table, $TableBrand );
        $this->createForeignKey( $Table, $TablePart );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableAssortmentGroup( Schema &$Schema ) {
        $TableAssortmentGroup = new TblReporting_AssortmentGroup();
        $Table = $this->createTable( $Schema, $TableAssortmentGroup->getEntityShortName() );
        $this->createColumn( $Table, $TableAssortmentGroup::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableAssortmentGroup::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TablePart
     * @param Table $TableAssortmentGroup
     * @return Table
     */
    private function setTablePartAssortmentGroup( Schema &$Schema, Table $TablePart, Table $TableAssortmentGroup ) {
        $TablePartAssortmentGroup = new TblReporting_Part_AssortmentGroup();
        $Table = $this->createTable( $Schema, $TablePartAssortmentGroup->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart );
        $this->createForeignKey( $Table, $TableAssortmentGroup );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableDiscountGroup( Schema &$Schema ) {
        $TableDiscountGroup = new TblReporting_DiscountGroup();
        $Table = $this->createTable( $Schema, $TableDiscountGroup->getEntityShortName() );
        $this->createColumn( $Table, $TableDiscountGroup::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableDiscountGroup::ATTR_DISCOUNT, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TablePart
     * @param Table $TableDiscountGroup
     * @return Table
     */
    private function setTablePrice( Schema &$Schema, Table $TablePart, Table $TableDiscountGroup ) {
        $TablePrice = new TblReporting_Price();
        $Table = $this->createTable( $Schema, $TablePrice->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart ); //To: ausgelagert
        $this->createForeignKey( $Table, $TableDiscountGroup );
        $this->createColumn( $Table, $TablePrice::ATTR_PRICE_GROSS, 'float', false );
        $this->createColumn( $Table, $TablePrice::ATTR_BACK_VALUE, 'float', false );
        $this->createColumn( $Table, $TablePrice::ATTR_COSTS_VARIABLE, 'float', false );
        $this->createColumn( $Table, $TablePrice::ATTR_VALID_FROM, 'date', true );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableSupplier( Schema &$Schema ) {
        $TableSupplier = new TblReporting_Supplier();
        $Table = $this->createTable( $Schema, $TableSupplier->getEntityShortName() );
        $this->createColumn( $Table, $TableSupplier::ATTR_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableSupplier::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TablePart
     * @param Table $TableSupplier
     * @return Table
     */
    private function setTablePartSupplier( Schema &$Schema, Table $TablePart, Table $TableSupplier ) {
        $TablePartSupplier = new TblReporting_Part_Supplier();
        $Table = $this->createTable( $Schema, $TablePartSupplier->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart );
        $this->createForeignKey( $Table, $TableSupplier );
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TablePart
     * @return Table
     */
    private function setTableSales( Schema &$Schema, Table $TablePart ) {
        $TableSales = new TblReporting_Sales();
        $Table = $this->createTable( $Schema, $TableSales->getEntityShortName() );
        $this->createForeignKey( $Table, $TablePart );
        $this->createColumn( $Table, $TableSales::ATTR_MONTH, self::FIELD_TYPE_INTEGER, false);
        $this->createColumn( $Table, $TableSales::ATTR_YEAR, self::FIELD_TYPE_INTEGER, false);
        $this->createColumn( $Table, $TableSales::ATTR_QUANTITY, self::FIELD_TYPE_INTEGER, false);
        $this->createColumn( $Table, $TableSales::ATTR_SALES_GROSS, 'decimal', false);
        $this->createColumn( $Table, $TableSales::ATTR_SALES_NET, self::FIELD_TYPE_FLOAT, false);
        return $Table;
    }

}