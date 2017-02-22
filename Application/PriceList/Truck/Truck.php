<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 13:33
 */

namespace SPHERE\Application\PriceList\Truck;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Common\Frontend\Icon\Repository\Calculator;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;

class Truck implements IApplicationInterface
{
	public static function registerApplication()
	{
		Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Lkw'), new Link\Icon(new \SPHERE\Common\Frontend\Icon\Repository\Truck()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __NAMESPACE__ . '\Frontend::frontendTruck')
        );
	}

	/**
	 * @return IServiceInterface
	 */
	public static function useService()
	{
		// TODO: Implement useService() method.
	}

	/**
	 * @return Frontend
	 */
	public static function useFrontend()
	{
		return new Frontend();
	}

}
