<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\System\Database\Binding\AbstractSetup;
use SPHERE\System\Database\Fitting\Element;

/**
 * Class Setup
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Service
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

        $this->setTableConsumer($Schema);

        return $this->saveSchema($Schema, $Simulate);
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableConsumer(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'tblConsumer');
        $this->createColumn($Table, 'Acronym', self::FIELD_TYPE_STRING);
        $this->createIndex($Table, array('Acronym', Element::ENTITY_REMOVE));

        $this->createColumn($Table, 'Name', self::FIELD_TYPE_STRING);
        return $Table;
    }
}
