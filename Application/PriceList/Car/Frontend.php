<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\PriceList\Car;


use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Primary;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
	public function frontendCar() {
		$Stage = new Stage('Preisliste', 'Pkw und smart');
		$Stage->hasUtilityFavorite(true);

		$Stage->setContent(
		    new Layout(
		        new LayoutGroup(
		            new LayoutRow(array(
		                new LayoutColumn(array(
		                    new Title('Filter'),
                            new TextField('x','Fahrgestellnummer'),
//                            new Primary('Suchen','#'),
                            '<hr/>',
                            new Panel('', array(
		                        new CheckBox('a','Komplettrad',1),
		                        new CheckBox('b','Reifen',1),
                            )),
		                    new Panel('', array(
		                        new CheckBox('a','Sommer',1),
		                        new CheckBox('b','Winter',1),
		                        new CheckBox('b','AllSeason',1),
                            )),
		                    new Panel('', array(
		                        new CheckBox('a','Continental',1),
		                        new CheckBox('b','Michelin',1),
		                        new CheckBox('b','Pirelli',1),
		                        new CheckBox('b','Dunlop',1),
		                        new CheckBox('b','Bridgestone',1),
                            )),
                        ),2),
		                new LayoutColumn(array(
                            new Table(array(), null, array('Spalte 1','Spalte 2','Spalte 3','Spalte 4','Spalte 5'))
                        ),10),
                    ))
                )
            )
        );

		return $Stage;
	}
}
