<?php
namespace SPHERE\Application\Platform\Assistance\Error;

use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Common\Main;

/**
 * Class Error
 *
 * @package SPHERE\Application\System\Assistance\Error
 */
class Error implements IModuleInterface
{

    public static function registerModule()
    {

        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__ . '/Authenticator',
                __NAMESPACE__ . '\Frontend::frontendAuthenticator'
            )
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__ . '/Authorization',
                __NAMESPACE__ . '\Frontend::frontendRoute'
            )
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__ . '/Shutdown',
                __NAMESPACE__ . '\Frontend::frontendShutdown'
            )
        );
    }

    /**
     * @return IServiceInterface
     */
    public static function useService()
    {
        // TODO: Implement useService() method.
    }

    /**
     * @return Frontend
     */
    public static function useFrontend()
    {

        return new Frontend();
    }
}
