<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 01.03.2017
 * Time: 15:39
 */

namespace SPHERE\Application\Api\Reporting;


use SPHERE\Application\Api\Reporting\Controlling\DirectSearch\CompetitionPositionDelete;
use SPHERE\Application\Api\Reporting\Controlling\DirectSearch\CompetitionTable;
use SPHERE\Application\Api\Reporting\Excel\ExcelDefault;
use SPHERE\Application\Api\Reporting\Excel\ExcelDirectSearch;
use SPHERE\Application\Api\Reporting\Excel\ExcelMultiplyCalculation;
use SPHERE\Application\Api\Reporting\Excel\ExcelScenarioCalculator;
use SPHERE\Application\Api\Reporting\Utility\MultiplyCalculation\MultiplyCalculation;
use SPHERE\Application\Api\Reporting\Utility\MultiplyCalculation\Pipeline;
use SPHERE\Application\Api\Reporting\Utility\ScenarioCalculator\ScenarioCalculator;
use SPHERE\Application\IApplicationInterface;

class Reporting implements IApplicationInterface
{
	public static function registerApplication() {
		MultiplyCalculation::registerApi();
		ScenarioCalculator::registerApi();
		ExcelDefault::registerApi();
		ExcelDirectSearch::registerApi();
		ExcelMultiplyCalculation::registerApi();
		ExcelScenarioCalculator::registerApi();
		Pipeline::registerApi();
		CompetitionTable::registerApi();
		CompetitionPositionDelete::registerApi();
	}
}