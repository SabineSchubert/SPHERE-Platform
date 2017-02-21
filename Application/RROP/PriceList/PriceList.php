<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:19
 */

namespace SPHERE\Application\RROP\PriceList;


use SPHERE\Application\IApplicationInterface;
//use SPHERE\Application\Reporting\Controlling\DirectSearch\DirectSearch;
use SPHERE\Application\RROP\PriceList\Car\Car;
use SPHERE\Application\RROP\PriceList\Classic\Classic;
use SPHERE\Application\RROP\PriceList\Trapo\Trapo;
use SPHERE\Application\RROP\PriceList\Truck\Truck;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

class PriceList implements IApplicationInterface
{
	public static function registerApplication()
	{
		Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Preisliste'), new Link\Icon(new SearchIcon()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );
        Car::registerModule();
        Classic::registerModule();
        Trapo::registerModule();
        Truck::registerModule();
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