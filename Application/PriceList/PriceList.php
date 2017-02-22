<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:19
 */

namespace SPHERE\Application\PriceList;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IClusterInterface;
use SPHERE\Application\PriceList\Car\Car;
use SPHERE\Application\PriceList\Classic\Classic;
use SPHERE\Application\PriceList\Trapo\Trapo;
use SPHERE\Application\PriceList\Truck\Truck;
use SPHERE\Common\Frontend\Icon\Repository\Calendar;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

//use SPHERE\Application\Reporting\Controlling\DirectSearch\DirectSearch;

class PriceList implements IClusterInterface
{
	public static function registerCluster()
	{
		Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Preisliste'), new Link\Icon(new Calendar()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );
        Car::registerApplication();
        Classic::registerApplication();
//        Trapo::registerApplication();
        Truck::registerApplication();
//		Search::registerModule();
//		DirectSearch::registerModule();
//		MonthlyTurnover::registerModule();
	}

	/**
	  * @return Stage
	  */
	 public function frontendDashboard()
	 {
	     $Stage = new Stage('Preisliste');
	     $Stage->setMessage('');
	     return $Stage;
	 }

}
