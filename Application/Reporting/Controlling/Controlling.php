<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:19
 */

namespace SPHERE\Application\Reporting\Controlling;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Reporting\Controlling\DirectSearch\DirectSearch;
use SPHERE\Application\Reporting\Controlling\MonthlyTurnover\MonthlyTurnover;
use SPHERE\Application\Reporting\Controlling\Search\Search;
use SPHERE\Application\Reporting\Utility\ScenarioCalculator\ScenarioCalculator;
use SPHERE\Application\Reporting\Utility\Utility;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

class Controlling implements IApplicationInterface
{
	public static function registerApplication()
	{
		Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Suchen'), new Link\Icon(new SearchIcon()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );
        DirectSearch::registerModule();
        Search::registerModule();
		MonthlyTurnover::registerModule();
		//Utility::registerApplication();
		//ScenarioCalculator::registerModule();
	}

	/**
	  * @return Stage
	  */
	 public function frontendDashboard()
	 {
	     $Stage = new Stage('Suchen');
	     $Stage->setMessage('');
	     return $Stage;
	 }

}