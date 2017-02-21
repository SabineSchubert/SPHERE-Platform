<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 26.10.2016
 * Time: 09:31
 */

namespace SPHERE\Application\Reporting\Utility;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Reporting\Utility\MultiplyCalculation\MultiplyCalculation;
use SPHERE\Application\Reporting\Utility\ScenarioCalculator\ScenarioCalculator;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

class Utility implements IApplicationInterface
{
	public static function registerApplication()
	{
//		Main::getDisplay()->addApplicationNavigation(
//            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Szenario-Rechner'), new Link\Icon(new Search()))
//        );
//        Main::getDispatcher()->registerRoute(
//            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
//        );

		ScenarioCalculator::registerModule();
		MultiplyCalculation::registerModule();
	}


	public function frontendDashboard()
	{
	   $Stage = new Stage('Test');
	   $Stage->setMessage('');
	   return $Stage;
	}
}