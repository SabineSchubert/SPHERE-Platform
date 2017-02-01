<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\System\Database\Binding\AbstractSetup;

/**
 * Class Setup
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service
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

        $TblGroup = $this->setTableGroup($Schema);
        $this->setTableGroupRole($Schema, $TblGroup);
        $this->setTableGroupAccount($Schema, $TblGroup);

        return $this->saveSchema($Schema, $Simulate);
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableGroup(Schema $Schema)
    {
        $Table = $this->createTable($Schema, 'TblGroup');
        $this->createColumn($Table, 'Name', self::FIELD_TYPE_STRING);
        $this->createColumn($Table, 'Description', self::FIELD_TYPE_TEXT);
        $this->createServiceKey($Table, 'TblConsumer');
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TblGroup
     *
     * @return Table
     */
    private function setTableGroupRole(Schema &$Schema, Table $TblGroup)
    {

        $Table = $this->createTable($Schema, 'TblGroupRole');
        $this->createForeignKey($Table, $TblGroup);
        $this->createServiceKey($Table, 'TblRole');
        return $Table;
    }

    /**
     * @param Schema $Schema
     * @param Table $TblGroup
     *
     * @return Table
     */
    private function setTableGroupAccount(Schema &$Schema, Table $TblGroup)
    {

        $Table = $this->createTable($Schema, 'TblGroupAccount');
        $this->createForeignKey($Table, $TblGroup);
        $this->createServiceKey($Table, 'TblAccount');
        return $Table;
    }
}
