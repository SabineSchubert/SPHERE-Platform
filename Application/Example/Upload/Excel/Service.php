<?php

namespace SPHERE\Application\Example\Upload\Excel;

use SPHERE\Application\Example\Upload\Excel\Service\Data;
use SPHERE\Application\Example\Upload\Excel\Service\Setup;
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