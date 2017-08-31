<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 30.03.2017
 * Time: 09:48
 */

namespace SPHERE\Application\Competition\DataWareHouse\Service;


use Doctrine\DBAL\Schema\Schema;
use SPHERE\Application\Competition\DataWareHouse\Service\Entity\TblCompetition_Competitor;
use SPHERE\Application\Competition\DataWareHouse\Service\Entity\TblCompetition_Main;
use SPHERE\Application\Competition\DataWareHouse\Service\Entity\TblCompetition_Manufacturer;
use SPHERE\Application\Competition\DataWareHouse\Service\Entity\TblCompetition_Position;
use SPHERE\Application\Competition\DataWareHouse\Service\Entity\TblCompetition_Ranking;
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

        $TableCompetitor = $this->setTableCompetitor( $Schema );
        $TableCompetitionMain = $this->setTableCompetitionMain( $Schema );
        $TableCompetitionPosition = $this->setTableCompetitionPosition( $Schema );
        $TableManufacturer = $this->setTableManufacturer( $Schema );
//        $TableRanking = $this->setTableRanking( $Schema );

        return $this->saveSchema($Schema, $Simulate);
    }

    /**
     * @param Schema $Schema
     * @return \Doctrine\DBAL\Schema\Table
     */
    public function setTableCompetitor( $Schema ) {
        $TableCompetitor = new TblCompetition_Competitor();
        $Table = $this->createTable( $Schema, $TableCompetitor->getEntityShortName() );
        $this->createColumn( $Table, $TableCompetitor::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableCompetitor::ATTR_SORTING, self::FIELD_TYPE_STRING, false );
        return $Table;

    }

    /**
         * @param Schema $Schema
         * @return \Doctrine\DBAL\Schema\Table
         */
    public function setTableCompetitionMain( $Schema ) {
        $TableCompetitionMain = new TblCompetition_Main();
        $Table = $this->createTable( $Schema, $TableCompetitionMain->getEntityShortName() );
        $this->createColumn( $Table, $TableCompetitionMain::ATTR_TRANSACTION_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableCompetitionMain::ATTR_RETAIL_NUMBER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableCompetitionMain::ATTR_COMMENT, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

    /**
         * @param Schema $Schema
         * @return \Doctrine\DBAL\Schema\Table
         */
    public function setTableCompetitionPosition( $Schema ) {
        $TableCompetitionPosition = new TblCompetition_Position();
        $Table = $this->createTable( $Schema, $TableCompetitionPosition->getEntityShortName() );
        $this->createColumn( $Table, $TableCompetitionPosition::TBL_COMPETITION_MAIN, self::FIELD_TYPE_BIGINT, false );
        $this->createColumn( $Table, $TableCompetitionPosition::TBL_REPORTING_PART, self::FIELD_TYPE_BIGINT, false );
        $this->createColumn( $Table, $TableCompetitionPosition::TBL_COMPETITION_COMPETITOR, self::FIELD_TYPE_INTEGER, false );
        $this->createColumn( $Table, $TableCompetitionPosition::ATTR_COMPETITOR, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableCompetitionPosition::TBL_COMPETITION_MANUFACTURER, self::FIELD_TYPE_BIGINT, false );
        $this->createColumn( $Table, $TableCompetitionPosition::ATTR_PRICE_NET, self::FIELD_TYPE_FLOAT, false );
        $this->createColumn( $Table, $TableCompetitionPosition::ATTR_PRICE_GROSS, self::FIELD_TYPE_FLOAT, false );
        $this->createColumn( $Table, $TableCompetitionPosition::ATTR_VAT, self::FIELD_TYPE_BOOLEAN, false );
        $this->createColumn( $Table, $TableCompetitionPosition::ATTR_DISTRIBUTOR_OR_CUSTOMER, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableCompetitionPosition::ATTR_COMMENT, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableCompetitionPosition::ATTR_PACKING_UNIT, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableCompetitionPosition::ATTR_DOT, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableCompetitionPosition::ATTR_ACTION_PART, self::FIELD_TYPE_BOOLEAN, false );

        return $Table;
    }

    /**
         * @param Schema $Schema
         * @return \Doctrine\DBAL\Schema\Table
         */
    public function setTableManufacturer( $Schema ) {
        $TableManufacturer = new TblCompetition_Manufacturer();
        $Table = $this->createTable( $Schema, $TableManufacturer->getEntityShortName() );
        $this->createColumn( $Table, $TableManufacturer::ATTR_NAME, self::FIELD_TYPE_STRING, false );
        $this->createColumn( $Table, $TableManufacturer::ATTR_SORTING, self::FIELD_TYPE_STRING, false );
        return $Table;
    }

//    /**
//         * @param Schema $Schema
//         * @return \Doctrine\DBAL\Schema\Table
//         */
//    public function setTableRanking( $Schema ) {
//        $TableRanking = new TblCompetition_Ranking();
//        $Table = $this->createTable( $Schema, $TableRanking->getEntityShortName() );
//        $this->createColumn( $Table, $TableRanking::ATTR_NAME, self::FIELD_TYPE_STRING, false );
//        $this->createColumn( $Table, $TableRanking::ATTR_DEPARTMENT, self::FIELD_TYPE_STRING, false );
//        return $Table;
//    }
}