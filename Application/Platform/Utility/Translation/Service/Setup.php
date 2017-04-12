<?php
namespace SPHERE\Application\Platform\Utility\Translation\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\Application\Platform\Utility\Translation\Service\Entity\TblGroup;
use SPHERE\Application\Platform\Utility\Translation\Service\Entity\TblLocale;
use SPHERE\System\Database\Binding\AbstractSetup;

/**
 * Class Setup
 * @package SPHERE\Application\Platform\Utility\Translation\Service
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

        $this->setTableLocale($Schema);
        $this->setTableGroup($Schema);

        return $this->saveSchema($Schema, $Simulate);
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableLocale(Schema $Schema)
    {
        $Table = $this->createTable($Schema, (new TblLocale())->getEntityShortName());
        $this->createColumn($Table, TblLocale::ATTR_IDENTIFIER, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, TblLocale::ATTR_NAME, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, TblLocale::ATTR_DESCRIPTION, self::FIELD_TYPE_TEXT);

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableGroup(Schema $Schema)
    {
        $Table = $this->createTable($Schema, (new TblGroup())->getEntityShortName());
        $this->createColumn($Table, TblGroup::ATTR_IDENTIFIER, self::FIELD_TYPE_STRING);

        return $Table;
    }
}
