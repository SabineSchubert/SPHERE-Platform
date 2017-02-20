<?php
namespace SPHERE\Application\Platform\Utility\Favorite\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\System\Database\Binding\AbstractSetup;

/**
 * Class Setup
 * @package SPHERE\Application\Platform\Utility\Favorite\Service
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

        $this->setTableFavorite($Schema);

        return $this->saveSchema($Schema, $Simulate);
    }

    /**
     * @param Schema $Schema
     *
     * @return Table
     */
    private function setTableFavorite(Schema $Schema)
    {

        $Table = $this->createTable($Schema, 'TblFavorite');
        $this->createColumn($Table, 'Route', self::FIELD_TYPE_STRING);
        $this->createColumn($Table, 'Title', self::FIELD_TYPE_STRING);
        $this->createColumn($Table, 'Description', self::FIELD_TYPE_STRING);
        $this->createServiceKey( $Table, (new TblAccount())->getEntityShortName() );

        return $Table;
    }
}