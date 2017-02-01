<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\System\Database\Binding\AbstractSetup;
use SPHERE\System\Database\Fitting\Element;

/**
 * Class Setup
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service
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

        $TblRight = $this->setTableRight($Schema);
        $TblPrivilege = $this->setTablePrivilege($Schema);
        $TblLevel = $this->setTableLevel($Schema);
        $TblRole = $this->setTableRole($Schema);

        $this->setTablePrivilegeRight($Schema, $TblPrivilege, $TblRight);
        $this->setTableLevelPrivilege($Schema, $TblLevel, $TblPrivilege);
        $this->setTableRoleLevel($Schema, $TblRole, $TblLevel);

        return $this->saveSchema($Schema, $Simulate);
    }


    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableRight(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'TblRight');
        $this->createColumn($Table, 'Route', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Route', Element::ENTITY_REMOVE));
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTablePrivilege(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'TblPrivilege');
        $this->createColumn($Table, 'Name', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Name', Element::ENTITY_REMOVE));
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableLevel(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'TblLevel');
        $this->createColumn($Table, 'Name', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Name', Element::ENTITY_REMOVE));
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableRole(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'TblRole');
        $this->createColumn($Table, 'Name', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Name', Element::ENTITY_REMOVE));

        $this->createColumn($Table, 'IsInternal', self::FIELD_TYPE_BOOLEAN);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TblPrivilege
     * @param Table $TblRight
     *
     * @return Table
     */
    private function setTablePrivilegeRight(
        Schema &$Schema,
        Table $TblPrivilege,
        Table $TblRight
    ) {

        $Table = $this->createTable($Schema, 'TblPrivilegeRight');
        $this->createForeignKey($Table, $TblPrivilege);
        $this->createForeignKey($Table, $TblRight);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TblLevel
     * @param Table $TblPrivilege
     *
     * @return Table
     */
    private function setTableLevelPrivilege(
        Schema &$Schema,
        Table $TblLevel,
        Table $TblPrivilege
    ) {

        $Table = $this->createTable($Schema, 'TblLevelPrivilege');
        $this->createForeignKey($Table, $TblLevel);
        $this->createForeignKey($Table, $TblPrivilege);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TblRole
     * @param Table $TblLevel
     *
     * @return Table
     */
    private function setTableRoleLevel(
        Schema &$Schema,
        Table $TblRole,
        Table $TblLevel
    ) {

        $Table = $this->createTable($Schema, 'TblRoleLevel');
        $this->createForeignKey($Table, $TblRole);
        $this->createForeignKey($Table, $TblLevel);
        return $Table;
    }
}
