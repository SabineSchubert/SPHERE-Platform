<?php
namespace SPHERE\Application\Reporting\Controlling\DirectSearch;


use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;

class DirectSearch implements IModuleInterface
{
    use AppTrait;

	public static function registerModule()
	{

	    self::createModule(__NAMESPACE__, __NAMESPACE__.'\Frontend', 'frontendSearchPartNumber', 'Direktsuche', new Search() );

		Main::getDispatcher()->registerRoute(
			Main::getDispatcher()->createRoute(__NAMESPACE__.'/PartNumber', __NAMESPACE__ . '\Frontend::frontendSearchPartNumber')
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__.'/MarketingCode', __NAMESPACE__ . '\Frontend::frontendSearchMarketingCode')
        );
		Main::getDispatcher()->registerRoute(
			Main::getDispatcher()->createRoute(__NAMESPACE__.'/ProductManager', __NAMESPACE__ . '\Frontend::frontendSearchProductManager')
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