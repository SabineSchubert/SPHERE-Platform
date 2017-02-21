<?php
namespace SPHERE\Application\RROP;

use SPHERE\Application\IClusterInterface;
use SPHERE\Application\RROP\OtherApplication\OtherApplication;
use SPHERE\Application\RROP\PriceList\PriceList;
use SPHERE\Application\RROP\Statistic\Statistic;
use SPHERE\Application\RROP\Training\Training;
use SPHERE\Application\RROP\Basket\Basket;
use SPHERE\Common\Frontend\Icon\Repository\Calculator;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

/**
 * Class RROP
 * @package SPHERE\Application\RROP
 */
class RROP implements IClusterInterface
{
    public static function registerCluster()
    {

        Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('RROP'), new Link\Icon(new Calculator()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );
	    PriceList::registerApplication();
	    Statistic::registerApplication();
	    Training::registerApplication();
	    OtherApplication::registerApplication();
	    Basket::registerApplication();
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {
        $Stage = new Stage('Räder/Reifen-Onlineportal');
        $Stage->setMessage('');
        return $Stage;
    }
}