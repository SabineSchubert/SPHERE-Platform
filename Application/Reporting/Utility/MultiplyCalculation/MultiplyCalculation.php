<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 26.10.2016
 * Time: 09:14
 */

namespace SPHERE\Application\Reporting\Utility\MultiplyCalculation;


use SPHERE\Application\Document\Storage\Frontend;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Common\Frontend\Icon\Repository\Calendar;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;

class MultiplyCalculation implements IModuleInterface
{
	public static function registerModule()
	{
		Main::getDisplay()->addModuleNavigation(
            new Link(new Link\Route('SPHERE\Application\Reporting\Controlling\MultiplyCalculation'), new Link\Name('Mehrmengenberechnung'), new Link\Icon(new Calendar()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute('SPHERE\Application\Reporting\Controlling\MultiplyCalculation', __NAMESPACE__ . '\Frontend::frontendMultiplyCalculation')
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