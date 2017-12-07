<?php
namespace SPHERE\Application\Reporting\DataWareHouse\BasicData;


use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Database\Link\Identifier;
use SPHERE\System\Extension\Repository\Debugger;

class BasicData implements IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        self::createModule(__NAMESPACE__, __NAMESPACE__.'\Frontend', 'frontendProductManager', 'Produktmanager', new Blackboard(), 'Produktmanager anlegen');
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Reporting', 'DataWareHouse', 'BasicData', null, null),
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