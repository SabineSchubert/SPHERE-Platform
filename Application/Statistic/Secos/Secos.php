<?php
/**
 * Created by PhpStorm.
 * User: Kunze
 * Date: 22.02.2017
 * Time: 00:41
 */

namespace SPHERE\Application\Statistic\Secos;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Statistic\Secos\Eigene\Eigene;
use SPHERE\Application\Statistic\Secos\Individual\Individual;
use SPHERE\Application\Statistic\Secos\Standard\Standard;
use SPHERE\Common\Frontend\Icon\Repository\Wrench;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;

class Secos implements IApplicationInterface
{
    public static function registerApplication()
    {
        Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('SeCoS'), new Link\Icon(new Wrench()))
        );
        Main::getDispatcher()->registerRoute(Main::getDispatcher()->createRoute(
            __NAMESPACE__, __CLASS__ . '::frontendDashboard'
        ));

        Standard::registerModule();
        Individual::registerModule();
        Eigene::registerModule();
    }

}
