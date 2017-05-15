<?php

namespace SPHERE\Application\Product\Classic;

use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\Slider;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Icon\Repository\CogWheels;
use SPHERE\Common\Frontend\Icon\Repository\Disable;
use SPHERE\Common\Frontend\Icon\Repository\Leaf;
use SPHERE\Common\Frontend\Icon\Repository\Snowflake;
use SPHERE\Common\Frontend\Icon\Repository\Sun;
use SPHERE\Common\Frontend\Icon\Repository\Wheel;
use SPHERE\Common\Frontend\Layout\Repository\Badge;
use SPHERE\Common\Frontend\Layout\Repository\Container;
use SPHERE\Common\Frontend\Layout\Repository\Dropdown;
use SPHERE\Common\Frontend\Layout\Repository\Header;
use SPHERE\Common\Frontend\Layout\Repository\Listing;
use SPHERE\Common\Frontend\Layout\Repository\Paragraph;
use SPHERE\Common\Frontend\Layout\Repository\PullClear;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Ruler;
use SPHERE\Common\Frontend\Layout\Repository\Scrollable;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Danger;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

/**
 * Class Frontend
 * @package SPHERE\Application\Product\Classic
 */
class Frontend extends Extension
{
    public function frontendClassic()
    {

        $_POST['2'] = 1;

        $Stage = new Stage('Preisliste', 'Classic');
        $Stage->hasUtilityFavorite(true);
        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(array(
                    new LayoutRow(array(
                        new LayoutColumn(array(

                            new Layout(array(
                                new LayoutGroup(array(
                                    new LayoutRow(array(
                                        new LayoutColumn(array(

                                            new Layout(array(
                                                new LayoutGroup(array(
                                                    new LayoutRow(array(
//                                                        new LayoutColumn(array(
//                                                            new Standard('', '', new Sun(), array(), 'Sommer'),
//                                                        ), 4, array(LayoutColumn::GRID_OPTION_HIDDEN_SM)),
//                                                        new LayoutColumn(array(
//                                                            new Standard('', '', new Snowflake(), array(), 'Winter'),
//                                                        ), 4, array(LayoutColumn::GRID_OPTION_HIDDEN_SM)),
//                                                        new LayoutColumn(array(
//                                                            new Standard('', '', new Leaf(), array(), 'Ganzjahr'),
//                                                        ), 4, array(LayoutColumn::GRID_OPTION_HIDDEN_SM)),

                                                        new LayoutColumn(array(
                                                            new CheckBox('2', new Sun() . '&nbsp;Sommer', 1),
                                                            new CheckBox('2', new Snowflake() . '&nbsp;Winter', 1),
                                                            new CheckBox('2', new Leaf() . '&nbsp;Ganzjahr', 1),
                                                        )),
                                                    )),
                                                )),
                                            )),

                                            new Ruler(),

                                            new Layout(array(
                                                new LayoutGroup(array(
                                                    new LayoutRow(array(
                                                        new LayoutColumn(array(
                                                            new CheckBox('2', new Wheel() . '&nbsp;Reifen', 1),
                                                            new CheckBox('2', new Wheel() . '&nbsp;Räder', 1),
                                                        )),
                                                    )),
                                                )),
                                            )),


                                            new Ruler(),

                                            new Header('Dimension'),
                                            new Layout(array(
                                                new LayoutGroup(array(
                                                    new LayoutRow(array(
                                                        new LayoutColumn(array(
                                                            new TextField('1', '205'),
                                                        ), 4),
                                                        new LayoutColumn(array(
                                                            new TextField('1', '55'),
                                                        ), 4),
                                                        new LayoutColumn(array(
                                                            new TextField('1', '16'),
                                                        ), 4),
                                                    )),
                                                )),
                                            )),
                                            new Ruler(),
                                            new Header('Hersteller'),

                                            new Container( array(
                                                new TextField('1', 'Liste eingrenzen'),
                                                new Scrollable(
                                                    new BlockReceiver(
                                                        new Listing(array(
                                                            new CheckBox('10', 'Continental', 1),
                                                            new CheckBox('12', 'Michelin', 1),
                                                            new CheckBox('13', 'Pirelli', 1),
                                                            new CheckBox('14', 'Pirelli', 1),
                                                            new CheckBox('15', 'Pirelli', 1),
                                                            new CheckBox('16', 'Pirelli', 1),
                                                            new CheckBox('17', 'Pirelli', 1),
                                                            new CheckBox('18', 'Pirelli', 1),
                                                        ))
                                                    ))
                                            )),

                                            new Ruler(),
                                            new Header('Fahrgestellnummer'),
                                            new TextField('1', 'Suchen'),

                                            new Ruler(),
                                            new Header('Bestand'),
                                            new Listing(array(
                                                new CheckBox('2', 'Autohaus', 1),
                                                new CheckBox('3', 'Logistics Center', 1),
                                                new CheckBox('4', 'Zwickau', 1),
                                            )),

                                            new Ruler(),
                                            new Header('Bestand'),
                                            new Listing(array(
                                                new Slider('Slider1', 'Slider', 'VK'),
                                                new Slider('Slider2', 'Slider', 'EK'),
                                                new Slider('Slider4', 'Slider', 'VK inkl Montage'),
                                            )),

                                        )),
                                    )),
                                ), new Title('Filter', new PullRight(new Link('', '#', new CogWheels())))),
                            ))

                        ), 2),
                        new LayoutColumn(array(
                            '<div id="FilterAdditional" class="collapse in">' .
//                            new Listing(array(
                            new Layout(array(
                                new LayoutGroup(array(
                                    new LayoutRow(array(
                                        new LayoutColumn(array(
                                            new Container(new Dropdown('Hersteller1', array(
                                                new TextField('1', 'Liste eingrenzen'),
                                                new Scrollable(
                                                    new BlockReceiver(
                                                        new Listing(array(
                                                            new CheckBox('10', 'Continental', 1),
                                                            new CheckBox('12', 'Michelin', 1),
                                                            new CheckBox('13', 'Pirelli', 1),
                                                            new CheckBox('14', 'Pirelli', 1),
                                                            new CheckBox('15', 'Pirelli', 1),
                                                            new CheckBox('16', 'Pirelli', 1),
                                                            new CheckBox('17', 'Pirelli', 1),
                                                            new CheckBox('18', 'Pirelli', 1),
                                                        ))
                                                    ))
                                            ))),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Container(new Dropdown('Hersteller2', array(
                                                new TextField('1', 'Liste eingrenzen'),
                                                new Scrollable(
                                                    new BlockReceiver(
                                                        new Listing(array(
                                                            new CheckBox('10', 'Continental', 1),
                                                            new CheckBox('12', 'Michelin', 1),
                                                            new CheckBox('13', 'Pirelli', 1),
                                                            new CheckBox('14', 'Pirelli', 1),
                                                            new CheckBox('15', 'Pirelli', 1),
                                                            new CheckBox('16', 'Pirelli', 1),
                                                            new CheckBox('17', 'Pirelli', 1),
                                                            new CheckBox('18', 'Pirelli', 1),
                                                        ))
                                                    ))
                                            ))),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Container(new Dropdown('Hersteller3', array(
                                                new TextField('1', 'Liste eingrenzen'),
                                                new Scrollable(
                                                    new BlockReceiver(
                                                        new Listing(array(
                                                            new CheckBox('10', 'Continental', 1),
                                                            new CheckBox('12', 'Michelin', 1),
                                                            new CheckBox('13', 'Pirelli', 1),
                                                            new CheckBox('14', 'Pirelli', 1),
                                                            new CheckBox('15', 'Pirelli', 1),
                                                            new CheckBox('16', 'Pirelli', 1),
                                                            new CheckBox('17', 'Pirelli', 1),
                                                            new CheckBox('18', 'Pirelli', 1),
                                                        ))
                                                    ))
                                            ))),
                                        ), 4),
                                    )),
                                    new LayoutRow(array(
                                        new LayoutColumn(array(
                                            new Container(new Dropdown('Hersteller4', array(
                                                new TextField('1', 'Liste eingrenzen'),
                                                new Scrollable(
                                                    new BlockReceiver(
                                                        new Listing(array(
                                                            new CheckBox('10', 'Continental', 1),
                                                            new CheckBox('12', 'Michelin', 1),
                                                            new CheckBox('13', 'Pirelli', 1),
                                                            new CheckBox('14', 'Pirelli', 1),
                                                            new CheckBox('15', 'Pirelli', 1),
                                                            new CheckBox('16', 'Pirelli', 1),
                                                            new CheckBox('17', 'Pirelli', 1),
                                                            new CheckBox('18', 'Pirelli', 1),
                                                        ))
                                                    ))
                                            ))),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Container(new Dropdown('Hersteller5', array(
                                                new TextField('1', 'Liste eingrenzen'),
                                                new Scrollable(
                                                    new BlockReceiver(
                                                        new Listing(array(
                                                            new CheckBox('10', 'Continental', 1),
                                                            new CheckBox('12', 'Michelin', 1),
                                                            new CheckBox('13', 'Pirelli', 1),
                                                            new CheckBox('14', 'Pirelli', 1),
                                                            new CheckBox('15', 'Pirelli', 1),
                                                            new CheckBox('16', 'Pirelli', 1),
                                                            new CheckBox('17', 'Pirelli', 1),
                                                            new CheckBox('18', 'Pirelli', 1),
                                                        ))
                                                    ))
                                            ))),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Container(new Dropdown('Hersteller6', array(
                                                new TextField('1', 'Liste eingrenzen'),
                                                new Scrollable(
                                                    new BlockReceiver(
                                                        new Listing(array(
                                                            new CheckBox('10', 'Continental', 1),
                                                            new CheckBox('12', 'Michelin', 1),
                                                            new CheckBox('13', 'Pirelli', 1),
                                                            new CheckBox('14', 'Pirelli', 1),
                                                            new CheckBox('15', 'Pirelli', 1),
                                                            new CheckBox('16', 'Pirelli', 1),
                                                            new CheckBox('17', 'Pirelli', 1),
                                                            new CheckBox('18', 'Pirelli', 1),
                                                        ))
                                                    ))
                                            ))),
                                        ), 4),
                                    )),
                                ), new Title('Weitere Filter'))
                            ))
                            . '</div>',

                            new Layout(array(
                                new LayoutGroup(array(
                                    new LayoutRow(array(
//                                        new LayoutColumn(array(
//                                            new Listing(array( new Link( new Danger(new Disable()),'#' ) . ' Continental' )),
//                                            new Listing(array( new Link( new Danger(new Disable()),'#' ) . ' Continental' )),
//                                        ),3),
//                                        new LayoutColumn(array(
//                                            new Listing(array( new Link( new Danger(new Disable()),'#' ) . ' Continental' )),
//                                            new Listing(array( new Link( new Danger(new Disable()),'#' ) . ' Continental' )),
//                                        ),3),
//                                        new LayoutColumn(array(
//                                            new Listing(array( new Link( new Danger(new Disable()),'#' ) . ' Continental' )),
//                                        ),3),
//                                        new LayoutColumn(array(
//                                            new Listing(array( new Link( new Danger(new Disable()),'#' ) . ' Continental' )),
//                                        ),3),
//                                        new LayoutColumn(array(
//                                            new Listing(array( new Link( new Danger(new Disable()),'#' ) . ' Continental' )),
//                                        ),3),
//                                        new LayoutColumn(array(
//                                            new Listing(array( new Link( new Danger(new Disable()),'#' ) . ' Continental' )),
//                                        ),3),
//                                        new LayoutColumn(array(
//                                            new Listing(array( new Link( new Danger(new Disable()),'#' ) . ' Continental' )),
//                                        ),3),
                                        new LayoutColumn(array(
                                            new PullClear(
                                                implode(' ', array(
                                                        new Badge(new Link(new Danger(new Disable()),
                                                                '#') . ' Continental', Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()), '#') . ' B 185',
                                                            Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()), '#') . ' Q 60',
                                                            Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()), '#') . ' D 16',
                                                            Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()), '#') . ' Sommer',
                                                            Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()),
                                                                '#') . ' FIN 1234567890123', Badge::BADGE_TYPE_PRIMARY),

                                                        new Badge(new Link(new Danger(new Disable()), '#') . ' D 16',
                                                            Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()), '#') . ' Sommer',
                                                            Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()),
                                                                '#') . ' FIN 1234567890123', Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()), '#') . ' D 16',
                                                            Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()), '#') . ' Sommer',
                                                            Badge::BADGE_TYPE_PRIMARY),
                                                        new Badge(new Link(new Danger(new Disable()),
                                                                '#') . ' FIN 1234567890123', Badge::BADGE_TYPE_PRIMARY),
                                                    )
                                                )
                                            ),
                                        ), 10),
                                        new LayoutColumn(array(
                                            new PullRight('<button href="#FilterAdditional" class="btn btn-default" data-toggle="collapse">Weitere Filter</button>')

                                        ), 2),
                                    )),

                                    new LayoutRow(array(
                                        new LayoutColumn(array(
                                            new Paragraph(''),
                                            new Table(array(), null, array('A', 'B', 'C')),
                                            new Standard('Suche speichern', '#')
                                        )),
                                    )),
                                ), new Title('Produkte', new PullRight(new Link('', '#', new CogWheels())))),
                            ))

                        ), 10),
                    )),
                )),
            ))
        );
        return $Stage;
    }
}
