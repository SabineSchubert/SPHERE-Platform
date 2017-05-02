<?php

namespace SPHERE\Application\Platform\Gatekeeper\Profile;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\System\Database\Database;
use SPHERE\Common\Frontend\Icon\Repository\Nameplate;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\System\Database\Link\Identifier;

class Profile implements IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        Database::registerService(__CLASS__);

        self::createModule(__NAMESPACE__,__CLASS__,'', 'Profile', new Nameplate());

    }

    /**
     * @return IServiceInterface
     */
    public static function useService()
    {
        return new Service(new Identifier('Platform', 'Gatekeeper', 'Profile'),
            __DIR__ . '/Service/Entity', __NAMESPACE__ . '\Service\Entity'
        );
    }

    /**
     * @return IFrontendInterface
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }
}