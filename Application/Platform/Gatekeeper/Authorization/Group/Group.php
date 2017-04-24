<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Group;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Common\Frontend\Icon\Repository\Share;
use SPHERE\System\Database\Link\Identifier;

/**
 * Class Group
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Group
 */
class Group implements IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        self::createModule(__NAMESPACE__,__CLASS__,'', 'Benutzergruppen', new Share());
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Platform', 'Gatekeeper', 'Authorization'),
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
