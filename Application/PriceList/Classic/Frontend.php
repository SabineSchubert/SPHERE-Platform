<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\PriceList\Classic;


use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
	public function frontendClassic() {
		$Stage = new Stage('Preisliste Classic');
        $Stage->hasUtilityFavorite(true);
        $Stage->setMessage('');
		return $Stage;
	}
}
