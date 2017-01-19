<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Group;

use SPHERE\Application\IModuleInterface;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\System\Database\Link\Identifier;

/**
 * Class Group
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Group
 */
class Group implements IModuleInterface
{
    public static function registerModule()
    {
        Main::getDisplay()->addModuleNavigation(new Link(new Link\Route(__NAMESPACE__),
            new Link\Name('Benutzergruppen')),
            new Link\Route('/Platform/Gatekeeper/Authorization')
        );
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Platform', 'Gatekeeper', 'Authorization', 'Group'),
            __DIR__.'/Service/Entity', __NAMESPACE__.'\Service\Entity'
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