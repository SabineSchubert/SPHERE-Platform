<?php
namespace SPHERE\Application\Api\Platform;

use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Application\Api\Platform\Gatekeeper\Consumer;
use SPHERE\Application\Api\Platform\Utility\Favorite;
use SPHERE\Application\IApplicationInterface;

/**
 * Class Platform
 * @package SPHERE\Application\Api\Platform
 */
class Platform implements IApplicationInterface
{
    public static function registerApplication()
    {
        Consumer::registerApi();
        Access::registerApi();
        Favorite::registerApi();
    }
}
