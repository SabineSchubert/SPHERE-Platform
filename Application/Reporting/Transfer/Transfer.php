<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:29
 */

namespace SPHERE\Application\Reporting\Transfer;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Reporting\Transfer\Import\SalesData\SalesData;
use SPHERE\Common\Frontend\Icon\Repository\Upload;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

class Transfer implements IApplicationInterface
{
	public static function registerApplication()
	{
		Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Administration'), new Link\Icon(new Upload()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendDashboard')
        );
		SalesData::registerModule();
	}

	public function frontendDashboard() {
		$Stage = new Stage('Administration');
		$Stage->setMessage('');
		return $Stage;
	}

}