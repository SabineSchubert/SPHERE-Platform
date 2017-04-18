<?php
namespace SPHERE\Application\Platform\Utility\Translation;

use SPHERE\Application\Platform\Utility\Translation\Service\Data;
use SPHERE\Application\Platform\Utility\Translation\Service\Setup;
use SPHERE\System\Database\Binding\AbstractService;

/**
 * Class Service
 * @package SPHERE\Application\Platform\Utility\Translation
 */
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
