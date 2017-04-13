<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 29.03.2017
 * Time: 10:32
 */

namespace SPHERE\Application\Reporting\DataWareHouse;

use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\System\Database\Link\Identifier;

class DataWareHouse implements IApplicationInterface, IModuleInterface
{

	public static function registerApplication()
	{
        self::registerModule();
	}

    public static function registerModule()
    {
        // TODO: Implement registerModule() method.
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Reporting', 'DataWareHouse', null, null, Consumer::useService()->getConsumerBySession()),
            __DIR__ . '/Service/Entity', __NAMESPACE__ . '\Service\Entity'
        );
    }

    /**
     * @return mixed
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }
}