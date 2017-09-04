<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 30.03.2017
 * Time: 09:37
 */

namespace SPHERE\Application\Competition\DataWareHouse;



use SPHERE\Application\Competition\DataWareHouse\Service\Data;
use SPHERE\Application\Competition\DataWareHouse\Service\Setup;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
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

    /**
     * @param string $PartNumber
     * @param string $MarketingCode
     * @param string $ProductGroupNumber
     * @param string $PeriodFrom
     * @param string $PeriodTo
     * @param $GroupBy
     * @return array|null
     */
    public function getCompetitionSearch( $PartNumber = null, $MarketingCode = null, $ProductGroupNumber = null, $PeriodFrom = null, $PeriodTo = null, $GroupBy ) {
       return ( new Data( $this->getBinding() ) ) ->getCompetitionSearch( $PartNumber, $MarketingCode, $ProductGroupNumber, $PeriodFrom, $PeriodTo, $GroupBy );
    }
}