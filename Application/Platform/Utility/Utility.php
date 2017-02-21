<?php
namespace SPHERE\Application\Platform\Utility;

use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Platform\Utility\Favorite\Favorite;
use SPHERE\Common\Frontend\Icon\Repository\Wrench;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

/**
 * Class Utility
 * @package SPHERE\Application\Platform\Utility
 */
class Utility implements IApplicationInterface
{
    public static function registerApplication()
    {
        Favorite::registerModule();

        Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Dienstprogramm'), new Link\Icon(new Wrench()))
        );
        Main::getDispatcher()->registerRoute(Main::getDispatcher()->createRoute(
            __NAMESPACE__, __CLASS__ . '::frontendDashboard'
        ));
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {

        $Stage = new Stage('Dashboard', 'Dienstprogramm');

        return $Stage;
    }
}