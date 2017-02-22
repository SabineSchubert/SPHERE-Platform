<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:19
 */

namespace SPHERE\Application\OtherApplication;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IClusterInterface;
use SPHERE\Application\OtherApplication\Combination\Combination;
use SPHERE\Application\OtherApplication\Documentation\Documentation;
use SPHERE\Application\OtherApplication\TnrSearch\TnrSearch;
use SPHERE\Application\OtherApplication\Warranty\Warranty;
use SPHERE\Common\Frontend\Icon\Repository\CogWheels;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

//use SPHERE\Application\Reporting\Controlling\DirectSearch\DirectSearch;

class OtherApplication implements IClusterInterface
{
	public static function registerCluster()
	{
		Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Weitere Anwendungen'), new Link\Icon(new CogWheels()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );
        TnrSearch::registerApplication();
        Combination::registerApplication();
        Documentation::registerApplication();
        Warranty::registerApplication();
//		Search::registerModule();
//		DirectSearch::registerModule();
//		MonthlyTurnover::registerModule();
	}

	/**
	  * @return Stage
	  */
	 public function frontendDashboard()
	 {
	     $Stage = new Stage('Weitere Anwendungen');
	     $Stage->setMessage('');
	     return $Stage;
	 }

}
