<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 29.03.2017
 * Time: 08:28
 */

namespace SPHERE\Application\Reporting\DataWareHouse;


use SPHERE\Application\Reporting\DataWareHouse\Service\Data;
use SPHERE\Application\Reporting\DataWareHouse\Service\Setup;
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

//	/**
//	 * @param $Id
//	 * @return null|TblReporting_MarketingCode
//	 */
//	public function getMarketingCodeById($Id)
//	{
//		return (new Data($this->getBinding()))->getMarketingCodeById($Id);
//	}

//	public function getMarketingCodeById($Id)
//	{
//		return (new Data($this->getBinding()))->getMarketingCodeById($Id);
//	}

}