<?php
namespace SPHERE\Application\Platform\Gatekeeper;

use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Platform\Gatekeeper\Authentication\Authentication;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Authorization;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Common\Frontend\Icon\Repository\Shield;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

/**
 * Class Gatekeeper
 *
 * @package SPHERE\Application\System\Gatekeeper
 */
class Gatekeeper implements IApplicationInterface
{

    public static function registerApplication()
    {

        Consumer::registerModule();
        Authorization::registerModule();
        Authentication::registerModule();

        Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Gatekeeper'), new Link\Icon(new Shield()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendGatekeeper')
        );
    }

    /**
     * @return Stage
     */
    public function frontendGatekeeper()
    {

        $Stage = new Stage('Plattform', 'Gatekeeper');

        return $Stage;
    }
}
