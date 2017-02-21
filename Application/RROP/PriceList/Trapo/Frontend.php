<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\RROP\PriceList\Trapo;


use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
	public function frontendSearch() {
		$Stage = new Stage('Preisliste Transporter');
		$Stage->setMessage('');
		return $Stage;
	}
}