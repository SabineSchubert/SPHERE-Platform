<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:19
 */

namespace SPHERE\Application\Statistic;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IClusterInterface;
use SPHERE\Application\Statistic\Secos\Secos;
use SPHERE\Application\Statistic\Stock\Stock;
use SPHERE\Application\Statistic\Tapss\Tapss;
use SPHERE\Application\Statistic\Vororder\Vororder;
use SPHERE\Common\Frontend\Icon\Repository\Equalizer;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Frontend\Icon\Repository\Signal;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

//use SPHERE\Application\Reporting\Controlling\DirectSearch\DirectSearch;

class Statistic implements IClusterInterface
{
	public static function registerCluster()
	{
		Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Statistik'), new Link\Icon(new Signal()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );
        Vororder::registerApplication();
        Tapss::registerApplication();
        Secos::registerApplication();
        Stock::registerApplication();
//		Search::registerModule();
//		DirectSearch::registerModule();
//		MonthlyTurnover::registerModule();
	}

	/**
	  * @return Stage
	  */
	 public function frontendDashboard()
	 {
	     $Stage = new Stage('Statistik');
	     $Stage->setMessage('');
	     return $Stage;
	 }

}
