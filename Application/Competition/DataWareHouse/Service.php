<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 30.03.2017
 * Time: 09:37
 */

namespace SPHERE\Application\Competition\DataWareHouse\Competition;


use SPHERE\Application\Competition\DataWareHouse\Competition\Service\Data;
use SPHERE\Application\Competition\DataWareHouse\Competition\Service\Setup;
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