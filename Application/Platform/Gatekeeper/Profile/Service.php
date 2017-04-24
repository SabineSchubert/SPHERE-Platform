<?php

namespace SPHERE\Application\Platform\Gatekeeper\Profile;

use SPHERE\Application\Platform\Gatekeeper\Profile\Service\Data;
use SPHERE\Application\Platform\Gatekeeper\Profile\Service\Setup;
use SPHERE\System\Database\Binding\AbstractService;

class Service extends AbstractService
{
    /**
     * @param bool $doSimulation
     * @param bool $withData
     *
     * @return string
     */
    public function setupService($doSimulation, $withData)
    {
        $Protocol = (new Setup($this->getStructure()))->setupDatabaseSchema($doSimulation);
        if (!$doSimulation && $withData) {
            (new Data($this->getBinding()))->setupDatabaseContent();
        }
        return $Protocol;
    }
}