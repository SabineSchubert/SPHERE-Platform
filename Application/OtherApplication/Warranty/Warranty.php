<?php
/**
 * Created by PhpStorm.
 * User: Kunze
 * Date: 22.02.2017
 * Time: 01:16
 */

namespace SPHERE\Application\OtherApplication\Warranty;

use SPHERE\Application\IApplicationInterface;
use SPHERE\Common\Frontend\Icon\Repository\Wrench;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;

class Warranty implements IApplicationInterface
{
    public static function registerApplication()
    {
        Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Reifengarantie'), new Link\Icon(new Wrench()))
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

    }

}
