<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:54
 */

namespace SPHERE\Application\Reporting\Controlling\MonthlyTurnover;


use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;

class MonthlyTurnover implements IModuleInterface
{
	public static function registerModule()
	{
		Main::getDisplay()->addModuleNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Geschäftsentwicklung'), new Link\Icon(new Search()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __NAMESPACE__ . '\Frontend::frontendSearchPartNumber')
        );

		Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__.'/PartNumber', __NAMESPACE__ . '\Frontend::frontendSearchPartNumber')
        );
		Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__.'/ProductManager', __NAMESPACE__ . '\Frontend::frontendSearchProductManager')
        );
		Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__.'/MarketingCode', __NAMESPACE__ . '\Frontend::frontendSearchMarketingCode')
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
	 * @return IFrontendInterface
	 */
	public static function useFrontend()
	{
		return new Frontend();
	}

}