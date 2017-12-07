<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 01.03.2017
 * Time: 15:39
 */

namespace SPHERE\Application\Api\Import;


use SPHERE\Application\Api\Import\BasicData\ProductManagerDelete;
use SPHERE\Application\Api\Import\BasicData\ProductManagerTable;
use SPHERE\Application\IApplicationInterface;

class Import implements IApplicationInterface
{
	public static function registerApplication() {
		ProductManagerTable::registerApi();
		ProductManagerDelete::registerApi();
	}
}