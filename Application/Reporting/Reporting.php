<?php
namespace SPHERE\Application\Reporting;

use SPHERE\Application\IClusterInterface;
use SPHERE\Common\Frontend\Icon\Repository\Calendar;
use SPHERE\Common\Frontend\Icon\Repository\CogWheels;
use SPHERE\Common\Frontend\Icon\Repository\Dice;
use SPHERE\Common\Frontend\Icon\Repository\Equalizer;
use SPHERE\Common\Frontend\Icon\Repository\Globe;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;

class Reporting implements IClusterInterface
{
    public static function registerCluster()
    {
        Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Kalkulation'), new Link\Icon(new Dice()))
        );
        Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Berichtswesen'), new Link\Icon(new Equalizer()))
        );
        Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Tagfahrlicht'), new Link\Icon(new CogWheels()))
        );
        Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Preisliste'), new Link\Icon(new Calendar()))
        );
        Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Langer Knopf Name'), new Link\Icon(new Globe()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendPlatform')
        );
    }
}
