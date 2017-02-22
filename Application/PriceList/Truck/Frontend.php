<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\PriceList\Truck;


use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
	public function frontendTruck() {
		$Stage = new Stage('Preisliste', 'Lkw');
        $Stage->hasUtilityFavorite(true);
        $Stage->setContent(
            new Table(array(), null, array('Spalte 1','Spalte 2','Spalte 3','Spalte 4','Spalte 5'))
        );
		return $Stage;
	}
}
