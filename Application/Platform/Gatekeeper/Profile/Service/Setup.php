<?php

namespace SPHERE\Application\Platform\Gatekeeper\Profile\Service;

use Doctrine\DBAL\Schema\Schema;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Gatekeeper\Profile\Service\Entity\TblProfileUser;
use SPHERE\System\Database\Binding\AbstractSetup;

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

        $this->setTableProfileUser($Schema);

        return $this->saveSchema($Schema);
    }

    private function setTableProfileUser(Schema $Schema)
    {
        $Table = $this->createTable($Schema, new TblProfileUser());
        $this->createServiceKey($Table, 'TblAccount');
        return $Table;
    }
}