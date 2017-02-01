<?php
namespace SPHERE\Application\Platform\System\Protocol\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\System\Database\Binding\AbstractSetup;

/**
 * Class Setup
 *
 * @package SPHERE\Application\Platform\System\Protocol\Service
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

        $this->setTableProtocol($Schema);

        return $this->saveSchema($Schema, $Simulate);
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableProtocol(Schema &$Schema)
    {

        $Table = $this->createTable($Schema, 'TblProtocol');
        /**
         * Upgrade
         */
        // System
        if (!$this->getConnection()->hasColumn('TblProtocol', 'ProtocolDatabase')) {
            $Table->addColumn('ProtocolDatabase', 'string', array('notnull' => false));
        }
        if (!$this->getConnection()->hasIndex($Table, array('ProtocolDatabase'))) {
            $Table->addIndex(array('ProtocolDatabase'));
        }
        if (!$this->getConnection()->hasColumn('TblProtocol', 'ProtocolTimestamp')) {
            $Table->addColumn('ProtocolTimestamp', 'integer', array('notnull' => false));
        }
        if (!$this->getConnection()->hasIndex($Table, array('ProtocolTimestamp'))) {
            $Table->addIndex(array('ProtocolTimestamp'));
        }
        // Editor
        if (!$this->getConnection()->hasColumn('TblProtocol', 'serviceTblAccount')) {
            $Table->addColumn('serviceTblAccount', 'bigint', array('notnull' => false));
        }
        if (!$this->getConnection()->hasColumn('TblProtocol', 'AccountUsername')) {
            $Table->addColumn('AccountUsername', 'string', array('notnull' => false));
        }
        if (!$this->getConnection()->hasIndex($Table, array('AccountUsername'))) {
            $Table->addIndex(array('AccountUsername'));
        }
        // Consumer
        if (!$this->getConnection()->hasColumn('TblProtocol', 'serviceTblConsumer')) {
            $Table->addColumn('serviceTblConsumer', 'bigint', array('notnull' => false));
        }
        if (!$this->getConnection()->hasColumn('TblProtocol', 'ConsumerName')) {
            $Table->addColumn('ConsumerName', 'string', array('notnull' => false));
        }
        if (!$this->getConnection()->hasIndex($Table, array('ConsumerName'))) {
            $Table->addIndex(array('ConsumerName'));
        }
        if (!$this->getConnection()->hasColumn('TblProtocol', 'ConsumerAcronym')) {
            $Table->addColumn('ConsumerAcronym', 'string', array('notnull' => false));
        }
        if (!$this->getConnection()->hasIndex($Table, array('ConsumerAcronym'))) {
            $Table->addIndex(array('ConsumerAcronym'));
        }
        // Data
        if (!$this->getConnection()->hasColumn('TblProtocol', 'EntityFrom')) {
            $Table->addColumn('EntityFrom', 'text', array('notnull' => false));
        }
        if (!$this->getConnection()->hasColumn('TblProtocol', 'EntityTo')) {
            $Table->addColumn('EntityTo', 'text', array('notnull' => false));
        }

        return $Table;
    }
}
