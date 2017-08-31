<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 30.03.2017
 * Time: 09:35
 */

namespace SPHERE\Application\Competition\DataWareHouse;


use SPHERE\Application\AppTrait;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\System\Database\Link\Identifier;

class DataWareHouse implements IApplicationInterface, IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        // TODO: Implement registerModule() method.
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Competition', 'DataWareHouse', null, null, Consumer::useService()->getConsumerBySession()),
            __DIR__ . '/Service/Entity', __NAMESPACE__ . '\Service\Entity'
        );
    }

    /**
     *
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }

    public static function registerApplication()
    {
        //self::createApplication(__NAMESPACE__, __CLASS__, 'frontendImport', 'Import', new Blackboard(), 'WB');
        self::registerModule();
    }
}