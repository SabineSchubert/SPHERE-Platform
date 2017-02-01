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

        $TblAccount = $this->setTableAccount($Schema);
        $TblIdentification = $this->setTableIdentification($Schema);
        $this->setTableSession($Schema, $TblAccount);
        $this->setTableAuthorization($Schema, $TblAccount);
        $this->setTableAuthentication($Schema, $TblAccount, $TblIdentification);
        $this->setTableSetting($Schema, $TblAccount);

        return $this->saveSchema($Schema, $Simulate);
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableAccount(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'TblAccount');
        $this->createColumn($Table, 'Username', self::FIELD_TYPE_STRING);

        $this->removeIndex($Table, array('Username'));
        $this->removeIndex($Table, array('Username', Element::ENTITY_REMOVE));
        $this->createIndex($Table, array('Username'));

        $this->createColumn($Table, 'Password', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Username', 'Password'));

        $this->createServiceKey($Table, 'TblConsumer');
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableIdentification(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'TblIdentification');
        $this->createColumn($Table, 'Name', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Name'), true);
        $this->createColumn($Table, 'Description', self::FIELD_TYPE_STRING);
        $this->createColumn($Table, 'SessionTimeout', self::FIELD_TYPE_INTEGER);
        $this->createColumn($Table, 'IsActive', self::FIELD_TYPE_BOOLEAN, false, 1);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TblAccount
     *
     * @return Table
     */
    private function setTableSession(Schema &$Schema, Table $TblAccount)
    {

        $Table = $this->createTable($Schema, 'TblSession');
        $this->createColumn($Table, 'Session', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Session'), false);
        $this->createColumn($Table, 'Timeout', self::FIELD_TYPE_INTEGER);
        $this->createForeignKey($Table, $TblAccount);
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @param Table $TblAccount
     *
     * @return Table
     */
    private function setTableAuthorization(Schema &$Schema, Table $TblAccount)
    {

        $Table = $this->createTable($Schema, 'TblAuthorization');
        $this->createServiceKey($Table, 'TblRole');
        $this->createForeignKey($Table, $TblAccount);
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @param Table $TblAccount
     * @param Table $TblIdentification
     *
     * @return Table
     */
    private function setTableAuthentication(Schema &$Schema, Table $TblAccount, Table $TblIdentification)
    {

        $Table = $this->createTable($Schema, 'TblAuthentication');
        $this->createForeignKey($Table, $TblAccount);
        $this->createForeignKey($Table, $TblIdentification);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TblAccount
     *
     * @return Table
     */
    private function setTableSetting(Schema &$Schema, Table $TblAccount)
    {

        $Table = $this->createTable($Schema, 'TblSetting');
        $this->createColumn($Table, 'Identifier', self::FIELD_TYPE_STRING);
        $this->createColumn($Table, 'Value', self::FIELD_TYPE_STRING);
        $this->createForeignKey($Table, $TblAccount);
        return $Table;
    }
}
