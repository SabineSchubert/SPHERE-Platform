<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 01.03.2017
 * Time: 15:39
 */

namespace SPHERE\Application\Api\Reporting;


use SPHERE\Application\Api\Reporting\Excel\ExcelDefault;
use SPHERE\Application\Api\Reporting\Utility\MultiplyCalculation\MultiplyCalculation;
use SPHERE\Application\Api\Reporting\Utility\ScenarioCalculator\ScenarioCalculator;
use SPHERE\Application\IApplicationInterface;

class Reporting implements IApplicationInterface
{
	public static function registerApplication() {
		MultiplyCalculation::registerApi();
		ScenarioCalculator::registerApi();
		ExcelDefault::registerApi();
	}
}