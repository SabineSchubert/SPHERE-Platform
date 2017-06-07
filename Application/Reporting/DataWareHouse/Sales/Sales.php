<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 22.05.2017
 * Time: 11:07
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Sales;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\System\Database\Link\Identifier;

class Sales implements IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        self::createModule(__NAMESPACE__, __NAMESPACE__.'\Frontend', 'frontendImportSales', 'Umsatzdaten-Import', new Blackboard(), 'Umsatzdaten-Import');
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Reporting', 'DataWareHouse', 'Sales', null, null), //Consumer::useService()->getConsumerBySession()
            __DIR__ . '/Service/Entity', __NAMESPACE__ . '\Service\Entity'
        );
    }

    /**
     * @return Frontend
     */
    public static function useFrontend()
    {
        return new Frontend();
    }
}