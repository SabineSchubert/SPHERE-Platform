<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Entity\TblGroup;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Entity\TblGroupAccount;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Entity\TblGroupRole;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Service\Entity\TblConsumer;
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
        $Table = $this->createTable($Schema, (new TblGroup())->getEntityShortName());
        $this->createColumn($Table, TblGroup::ATTR_NAME, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, TblGroup::ATTR_DESCRIPTION, self::FIELD_TYPE_TEXT);
        $this->createServiceKey($Table, (new TblConsumer())->getEntityShortName());
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

        $Table = $this->createTable($Schema, (new TblGroupRole())->getEntityShortName());
        $this->createForeignKey($Table, $TblGroup);
        $this->createServiceKey($Table, (new TblRole())->getEntityShortName());
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

        $Table = $this->createTable($Schema, (new TblGroupAccount())->getEntityShortName());
        $this->createForeignKey($Table, $TblGroup);
        $this->createServiceKey($Table, (new TblAccount())->getEntityShortName());
        return $Table;
    }
}
