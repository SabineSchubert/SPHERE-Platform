<?php
namespace SPHERE\Application\Platform\Utility\Translation;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Common\Frontend\Icon\Repository\Conversation;
use SPHERE\Common\Frontend\IFrontendInterface;

/**
 * Class Translation
 * @package SPHERE\Application\Platform\Utility\Translation
 */
class Translation implements IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        self::createModule( __NAMESPACE__, __CLASS__, '', 'Mehrsprachigkeit', new Conversation() );
    }

    /**
     * @return IServiceInterface
     */
    public static function useService()
    {
        // TODO: Implement useService() method.
    }

    /**
     * @return IFrontendInterface
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }
}