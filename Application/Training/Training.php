<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:19
 */

namespace SPHERE\Application\Training;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IClusterInterface;
use SPHERE\Application\Training\Akubis\Akubis;
use SPHERE\Application\Training\Offensive\Offensive;
use SPHERE\Application\Training\Youtube\Youtube;
use SPHERE\Common\Frontend\Icon\Repository\Education;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

//use SPHERE\Application\Reporting\Controlling\DirectSearch\DirectSearch;

class Training implements IClusterInterface
{
	public static function registerCluster()
	{
		Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Schulungen'), new Link\Icon(new Education()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );
        Offensive::registerApplication();
        Youtube::registerApplication();
        Akubis::registerApplication();
//		Search::registerModule();
//		DirectSearch::registerModule();
//		MonthlyTurnover::registerModule();
	}

	/**
	  * @return Stage
	  */
	 public function frontendDashboard()
	 {
	     $Stage = new Stage('Schulungen');
	     $Stage->setMessage('');
	     return $Stage;
	 }

}
