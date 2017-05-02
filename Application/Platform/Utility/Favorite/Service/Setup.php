<?php
namespace SPHERE\Application\Platform\Utility\Favorite\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Utility\Favorite\Service\Entity\TblFavorite;
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

        $Table = $this->createTable($Schema, (new TblFavorite())->getEntityShortName());
        $this->createColumn($Table, TblFavorite::ATTR_ROUTE, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, TblFavorite::ATTR_TITLE, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, 'Description', self::FIELD_TYPE_STRING);
        $this->createServiceKey( $Table, (new TblAccount())->getEntityShortName() );

        return $Table;
    }
}