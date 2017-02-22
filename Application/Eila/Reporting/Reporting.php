<?php
/**
 * Created by PhpStorm.
 * User: Kunze
 * Date: 22.02.2017
 * Time: 01:04
 */

namespace SPHERE\Application\Eila\Reporting;


use SPHERE\Application\Eila\Reporting\Customer\Customer;
use SPHERE\Application\Eila\Reporting\Individual\Individual;
use SPHERE\Application\Eila\Reporting\Stock\Stock;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Common\Frontend\Icon\Repository\Wrench;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;

class Reporting implements IApplicationInterface
{
    public static function registerApplication()
    {
        Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Auswertungen'), new Link\Icon(new Wrench()))
        );
        Main::getDispatcher()->registerRoute(Main::getDispatcher()->createRoute(
            __NAMESPACE__, __CLASS__ . '::frontendDashboard'
        ));

        Customer::registerModule();
        Stock::registerModule();
        Individual::registerModule();
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {

    }

}
