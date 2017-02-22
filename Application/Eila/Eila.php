<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:19
 */

namespace SPHERE\Application\Eila;


use SPHERE\Application\Eila\Administration\Administration;
use SPHERE\Application\Eila\InOut\InOut;
use SPHERE\Application\Eila\Reporting\Reporting;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IClusterInterface;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Frontend\Icon\Repository\Transfer;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

//use SPHERE\Application\Reporting\Controlling\DirectSearch\DirectSearch;

class Eila implements IClusterInterface
{
	public static function registerCluster()
	{
		Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Einlagerung'), new Link\Icon(new Transfer()), false, 'Einlagerungssoftware')
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );
        InOut::registerApplication();
        Reporting::registerApplication();
        Administration::registerApplication();
//		Search::registerModule();
//		DirectSearch::registerModule();
//		MonthlyTurnover::registerModule();
	}

	/**
	  * @return Stage
	  */
	 public function frontendDashboard()
	 {
	     $Stage = new Stage('Einlagerungssoftware');
	     $Stage->setMessage('');
	     return $Stage;
	 }

}
