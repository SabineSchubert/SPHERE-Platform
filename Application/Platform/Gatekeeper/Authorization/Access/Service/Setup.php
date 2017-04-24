<?php

namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblLevel;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblLevelPrivilege;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblPrivilege;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblPrivilegeRight;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRight;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRoleLevel;
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

        $Table = $this->createTable($Schema, (new TblRight())->getEntityShortName());
        $this->createColumn($Table, TblRight::ATTR_ROUTE, self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array(TblRight::ATTR_ROUTE, Element::ENTITY_REMOVE));
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTablePrivilege(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, (new TblPrivilege())->getEntityShortName());
        $this->createColumn($Table, TblPrivilege::ATTR_NAME, self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array(TblPrivilege::ATTR_NAME, Element::ENTITY_REMOVE));
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableLevel(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, (new TblLevel())->getEntityShortName());
        $this->createColumn($Table, TblLevel::ATTR_NAME, self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array(TblLevel::ATTR_NAME, Element::ENTITY_REMOVE));
        return $Table;
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableRole(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, (new TblRole())->getEntityShortName());
        $this->createColumn($Table, TblRole::ATTR_NAME, self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array(TblRole::ATTR_NAME, Element::ENTITY_REMOVE));

        $this->createColumn($Table, TblRole::ATTR_IS_INTERNAL, self::FIELD_TYPE_BOOLEAN);
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

        $Table = $this->createTable($Schema, (new TblPrivilegeRight())->getEntityShortName());
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

        $Table = $this->createTable($Schema, (new TblLevelPrivilege())->getEntityShortName());
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

        $Table = $this->createTable($Schema, (new TblRoleLevel())->getEntityShortName());
        $this->createForeignKey($Table, $TblRole);
        $this->createForeignKey($Table, $TblLevel);
        return $Table;
    }
}
