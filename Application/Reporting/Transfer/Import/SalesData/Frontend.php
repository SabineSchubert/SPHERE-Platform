<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 26.10.2016
 * Time: 09:06
 */

namespace SPHERE\Application\Reporting\Transfer\Import\SalesData;


use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
	public function frontendSalesData() {
		$Stage = new Stage('Umsatzdaten importieren');
		$Stage->setMessage('');
		return $Stage;
	}
}