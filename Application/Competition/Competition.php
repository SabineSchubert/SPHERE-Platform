<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 28.03.2017
 * Time: 15:45
 */

namespace SPHERE\Application\Competition;

use SPHERE\Application\IClusterInterface;
use SPHERE\Application\Competition\Competition\Competition as AppCompetition;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Common\Frontend\Icon\Repository\Equalizer;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

class Competition implements IClusterInterface
{
	public static function registerCluster() {

		Main::getDisplay()->addClusterNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Angebotsdaten'), new Link\Icon(new Equalizer()))
        );

		Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );

		AppCompetition::registerApplication();
		DataWareHouse::registerApplication();
	}

	/**
     * @return Stage
     */
    public function frontendDashboard()
    {
        $Stage = new Stage('Angebotsdaten');
        $Stage->setMessage('');
        return $Stage;
    }
}