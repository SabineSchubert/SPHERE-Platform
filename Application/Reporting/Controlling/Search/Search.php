<?php
namespace SPHERE\Application\Reporting\Controlling\Search;


use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;

class Search implements IModuleInterface
{
	public static function registerModule()
	{
		Main::getDisplay()->addModuleNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Suche'), new Link\Icon(new SearchIcon()))
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
		Main::getDispatcher()->registerRoute(
			Main::getDispatcher()->createRoute(__NAMESPACE__.'/Competition', __NAMESPACE__ . '\Frontend::frontendSearchCompetition')
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