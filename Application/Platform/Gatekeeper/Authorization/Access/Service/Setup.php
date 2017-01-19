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

        $tblRight = $this->setTableRight($Schema);
        $tblPrivilege = $this->setTablePrivilege($Schema);
        $tblLevel = $this->setTableLevel($Schema);
        $tblRole = $this->setTableRole($Schema);

        $this->setTablePrivilegeRight($Schema, $tblPrivilege, $tblRight);
        $this->setTableLevelPrivilege($Schema, $tblLevel, $tblPrivilege);
        $this->setTableRoleLevel($Schema, $tblRole, $tblLevel);

        return $this->saveSchema($Schema, $Simulate);
    }


    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableRight(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'tblRight');
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

        $Table = $this->createTable($Schema, 'tblPrivilege');
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

        $Table = $this->createTable($Schema, 'tblLevel');
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

        $Table = $this->createTable($Schema, 'tblRole');
        $this->createColumn($Table, 'Name', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Name', Element::ENTITY_REMOVE));

        $this->createColumn($Table, 'IsInternal', self::FIELD_TYPE_BOOLEAN);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblPrivilege
     * @param Table $tblRight
     *
     * @return Table
     */
    private function setTablePrivilegeRight(
        Schema &$Schema,
        Table $tblPrivilege,
        Table $tblRight
    )
    {

        $Table = $this->createTable($Schema, 'tblPrivilegeRight');
        $this->createForeignKey($Table, $tblPrivilege);
        $this->createForeignKey($Table, $tblRight);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblLevel
     * @param Table $tblPrivilege
     *
     * @return Table
     */
    private function setTableLevelPrivilege(
        Schema &$Schema,
        Table $tblLevel,
        Table $tblPrivilege
    )
    {

        $Table = $this->createTable($Schema, 'tblLevelPrivilege');
        $this->createForeignKey($Table, $tblLevel);
        $this->createForeignKey($Table, $tblPrivilege);
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $tblRole
     * @param Table $tblLevel
     *
     * @return Table
     */
    private function setTableRoleLevel(
        Schema &$Schema,
        Table $tblRole,
        Table $tblLevel
    )
    {

        $Table = $this->createTable($Schema, 'tblRoleLevel');
        $this->createForeignKey($Table, $tblRole);
        $this->createForeignKey($Table, $tblLevel);
        return $Table;
    }
}
