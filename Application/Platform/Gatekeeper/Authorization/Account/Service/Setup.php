<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\System\Database\Binding\AbstractSetup;
use SPHERE\System\Database\Fitting\Element;

/**
 * Class Setup
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service
 */
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

        $tblAccount = $this->setTableAccount($Schema);
        $tblIdentification = $this->setTableIdentification($Schema);
        $this->setTableSession($Schema, $tblAccount);
        $this->setTableAuthorization($Schema, $tblAccount);
        $this->setTableAuthentication($Schema, $tblAccount, $tblIdentification);
        $this->setTableSetting($Schema, $tblAccount);

        return $this->saveSchema( $Schema, $Simulate );
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableAccount(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'tblAccount');
        $this->createColumn($Table, 'Username', self::FIELD_TYPE_STRING);

        $this->removeIndex($Table, array('Username'));
        $this->removeIndex($Table, array('Username', Element::ENTITY_REMOVE));
        $this->createIndex($Table, array('Username'));

        $this->createColumn($Table, 'Password', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Username', 'Password'));

        $this->createServiceKey( $Table, 'TblConsumer' );
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableIdentification(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'tblIdentification');
        $this->createColumn($Table, 'Name', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Name'), true);
        $this->createColumn($Table, 'Description', self::FIELD_TYPE_STRING);
        $this->createColumn($Table, 'SessionTimeout', self::FIELD_TYPE_INTEGER);
        $this->createColumn($Table, 'IsActive', self::FIELD_TYPE_BOOLEAN, false, 1);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblAccount
     *
     * @return Table
     */
    private function setTableSession(Schema &$Schema, Table $tblAccount)
    {

        $Table = $this->createTable($Schema, 'tblSession');
        $this->createColumn($Table, 'Session', self::FIELD_TYPE_STRING);
        $this->createIndex($Table,array('Session'),false);
        $this->createColumn($Table, 'Timeout', self::FIELD_TYPE_INTEGER);
        $this->createForeignKey($Table, $tblAccount);
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @param Table $tblAccount
     *
     * @return Table
     */
    private function setTableAuthorization(Schema &$Schema, Table $tblAccount)
    {

        $Table = $this->createTable($Schema, 'tblAuthorization');
        $this->createServiceKey($Table, 'TblRole');
        $this->createForeignKey($Table, $tblAccount);
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @param Table $tblAccount
     * @param Table $tblIdentification
     *
     * @return Table
     */
    private function setTableAuthentication(Schema &$Schema, Table $tblAccount, Table $tblIdentification)
    {

        $Table = $this->createTable($Schema, 'tblAuthentication');
        $this->createForeignKey($Table, $tblAccount);
        $this->createForeignKey($Table, $tblIdentification);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblAccount
     *
     * @return Table
     */
    private function setTableSetting(Schema &$Schema, Table $tblAccount)
    {

        $Table = $this->createTable($Schema, 'tblSetting');
        $this->createColumn($Table,'Identifier',self::FIELD_TYPE_STRING);
        $this->createColumn($Table,'Value',self::FIELD_TYPE_STRING);
        $this->createForeignKey($Table, $tblAccount);
        return $Table;
    }
}
