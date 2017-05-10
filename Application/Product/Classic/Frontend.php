<?php

namespace SPHERE\Application\Product\Classic;

use SPHERE\Common\Frontend\Form\Repository\Field\AutoCompleter;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Icon\Repository\CogWheels;
use SPHERE\Common\Frontend\Icon\Repository\Disable;
use SPHERE\Common\Frontend\Icon\Repository\Leaf;
use SPHERE\Common\Frontend\Icon\Repository\Question;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\Icon\Repository\Snowflake;
use SPHERE\Common\Frontend\Icon\Repository\Sun;
use SPHERE\Common\Frontend\Icon\Repository\Wheel;
use SPHERE\Common\Frontend\Layout\Repository\Badge;
use SPHERE\Common\Frontend\Layout\Repository\Header;
use SPHERE\Common\Frontend\Layout\Repository\Listing;
use SPHERE\Common\Frontend\Layout\Repository\Paragraph;
use SPHERE\Common\Frontend\Layout\Repository\PullClear;
use SPHERE\Common\Frontend\Layout\Repository\PullLeft;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Ruler;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Repository\Well;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Link\Structure\LinkGroup;
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
                                                        new LayoutColumn(array(
                                                            new Standard('', '', new Sun(), array(), 'Sommer'),
                                                        ), 4, array(LayoutColumn::GRID_OPTION_HIDDEN_SM)),
                                                        new LayoutColumn(array(
                                                            new Standard('', '', new Snowflake(), array(), 'Winter'),
                                                        ), 4, array(LayoutColumn::GRID_OPTION_HIDDEN_SM)),
                                                        new LayoutColumn(array(
                                                            new Standard('', '', new Leaf(), array(), 'Ganzjahr'),
                                                        ), 4, array(LayoutColumn::GRID_OPTION_HIDDEN_SM)),

                                                        new LayoutColumn(array(
                                                            new CheckBox('2', new Sun() . '&nbsp;Sommer', 1),
                                                            new CheckBox('2', new Snowflake() . '&nbsp;Winter', 1),
                                                            new CheckBox('2', new Leaf() . '&nbsp;Ganzjahr', 1),
                                                        ), 12, array(LayoutColumn::GRID_OPTION_HIDDEN_XS)),
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
                                            new Listing(array(
                                                new TextField('1', 'Suchen'),
                                                new CheckBox('2', 'Continental', 1),
                                                new CheckBox('3', 'Pirelli', 1),
                                                new CheckBox('4', 'Dunlop', 1),
                                                new Link('Mehr anzeigen', '#')
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


                                        )),
                                    )),
                                ), new Title('Filter', new PullRight(new Link('', '#', new CogWheels())))),
                            ))

                        ), 2),
                        new LayoutColumn(array(
                            '<div id="FilterAdditional" class="collapse in">' .


                            new Listing(array(
                            new Layout(array(
                                new LayoutGroup(array(
                                    new LayoutRow(array(
                                        new LayoutColumn(array(
                                            '<div class="dropdown">'
                                                .'<button class="btn btn-default dropdown-toggle" style="width: 100%;" data-toggle="dropdown">'
                                                    .new PullLeft( 'Hersteller' ).new PullRight( '<span class="caret"></span>' )
                                                .'</button>'
                                                .'<ul class="dropdown-menu" style="background: #fff; margin-top: 0; padding: 5px;">'
                                                    .new TextField('1', '', '', new Search())
                                                    .'<div class="pre-scrollable" style="height: 100px !important; margin: 3px 0 3px 3px;">'
                                                    .new CheckBox('2', 'Continental', 1)
                                                    .new CheckBox('2', 'Michelin', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .'</div>'
                                                .'</li></ul>'
                                            .'</div>'
                                            .'<br/>',

                                            '<div class="dropdown">'
                                                .'<button class="btn btn-default dropdown-toggle" style="width: 100%;" data-toggle="dropdown">'
                                                    .new PullLeft( 'Hersteller' ).new PullRight( '<span class="caret"></span>' )
                                                .'</button>'
                                                .'<ul class="dropdown-menu" style="background: #fff; margin-top: 0; padding: 5px;">'
                                                    .new TextField('1', '', '', new Search())
                                                    .'<div class="pre-scrollable" style="height: 100px !important; margin: 3px 0 3px 3px;">'
                                                    .new CheckBox('2', 'Continental', 1)
                                                    .new CheckBox('2', 'Michelin', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .new CheckBox('2', 'Pirelli', 1)
                                                    .'</div>'
                                                .'</li></ul>'
                                            .'</div>'

                                            .new Listing(array(
                                                new CheckBox('2', 'Continental', 1)
                                                )),


                                            '<div class="dropdown">'
                                            .'<button class="btn btn-default dropdown-toggle" style="width: 100%;" data-toggle="dropdown">'
                                            .new PullLeft( 'Hersteller' ).new PullRight( '<span class="caret"></span>' )
                                            .'</button>'
                                            .'<ul class="dropdown-menu" style="background: #fff; margin-top: 0; padding: 5px;">'
                                            .new TextField('1', '', '', new Search())
                                            .'<div class="pre-scrollable" style="height: 100px !important; margin: 3px 0 3px 3px;">'
                                            .new CheckBox('2', 'Continental', 1)
                                            .new CheckBox('2', 'Michelin', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .'</div>'
                                            .'</li></ul>'
                                            .'</div>'
                                            .'<br/>',


                                        ),4),

                                        new LayoutColumn(array(
                                            '<div class="dropdown">'
                                            .'<button class="btn btn-default dropdown-toggle" style="width: 100%;" data-toggle="dropdown">'
                                            .new PullLeft( 'Hersteller' ).new PullRight( '<span class="caret"></span>' )
                                            .'</button>'
                                            .'<ul class="dropdown-menu" style="background: #fff; margin-top: 0; padding: 5px;">'
                                            .new TextField('1', '', '', new Search())
                                            .'<div class="pre-scrollable" style="height: 100px !important; margin: 3px 0 3px 3px;">'
                                            .new CheckBox('2', 'Continental', 1)
                                            .new CheckBox('2', 'Michelin', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .'</div>'
                                            .'</li></ul>'
                                            .'</div>'
                                            .'<br/>',

                                            '<div class="dropdown">'
                                            .'<button class="btn btn-default dropdown-toggle" style="width: 100%;" data-toggle="dropdown">'
                                            .new PullLeft( 'Hersteller' ).new PullRight( '<span class="caret"></span>' )
                                            .'</button>'
                                            .'<ul class="dropdown-menu" style="background: #fff; margin-top: 0; padding: 5px;">'
                                            .new TextField('1', '', '', new Search())
                                            .'<div class="pre-scrollable" style="height: 100px !important; margin: 3px 0 3px 3px;">'
                                            .new CheckBox('2', 'Continental', 1)
                                            .new CheckBox('2', 'Michelin', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .'</div>'
                                            .'</li></ul>'
                                            .'</div>'

                                            .'<br/>',

                                            '<div class="dropdown">'
                                            .'<button class="btn btn-default dropdown-toggle" style="width: 100%;" data-toggle="dropdown">'
                                            .new PullLeft( 'Hersteller' ).new PullRight( '<span class="caret"></span>' )
                                            .'</button>'
                                            .'<ul class="dropdown-menu" style="background: #fff; margin-top: 0; padding: 5px;">'
                                            .new TextField('1', '', '', new Search())
                                            .'<div class="pre-scrollable" style="height: 100px !important; margin: 3px 0 3px 3px;">'
                                            .new CheckBox('2', 'Continental', 1)
                                            .new CheckBox('2', 'Michelin', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .'</div>'
                                            .'</li></ul>'
                                            .'</div>'
                                            .'<br/>',


                                        ),4),

                                        new LayoutColumn(array(
                                            '<div class="dropdown">'
                                            .'<button class="btn btn-default dropdown-toggle" style="width: 100%;" data-toggle="dropdown">'
                                            .new PullLeft( 'Hersteller' ).new PullRight( '<span class="caret"></span>' )
                                            .'</button>'
                                            .'<ul class="dropdown-menu" style="background: #fff; margin-top: 0; padding: 5px;">'
                                            .new TextField('1', '', '', new Search())
                                            .'<div class="pre-scrollable" style="height: 100px !important; margin: 3px 0 3px 3px;">'
                                            .new CheckBox('2', 'Continental', 1)
                                            .new CheckBox('2', 'Michelin', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .'</div>'
                                            .'</li></ul>'
                                            .'</div>'
                                            .'<br/>',

                                            '<div class="dropdown">'
                                            .'<button class="btn btn-default dropdown-toggle" style="width: 100%;" data-toggle="dropdown">'
                                            .new PullLeft( 'Hersteller' ).new PullRight( '<span class="caret"></span>' )
                                            .'</button>'
                                            .'<ul class="dropdown-menu" style="background: #fff; margin-top: 0; padding: 5px;">'
                                            .new TextField('1', '', '', new Search())
                                            .'<div class="pre-scrollable" style="height: 100px !important; margin: 3px 0 3px 3px;">'
                                            .new CheckBox('2', 'Continental', 1)
                                            .new CheckBox('2', 'Michelin', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .'</div>'
                                            .'</li></ul>'
                                            .'</div>'

                                            .'<br/>',

                                            '<div class="dropdown">'
                                            .'<button class="btn btn-default dropdown-toggle" style="width: 100%;" data-toggle="dropdown">'
                                            .new PullLeft( 'Hersteller' ).new PullRight( '<span class="caret"></span>' )
                                            .'</button>'
                                            .'<ul class="dropdown-menu" style="background: #fff; margin-top: 0; padding: 5px;">'
                                            .new TextField('1', '', '', new Search())
                                            .'<div class="pre-scrollable" style="height: 100px !important; margin: 3px 0 3px 3px;">'
                                            .new CheckBox('2', 'Continental', 1)
                                            .new CheckBox('2', 'Michelin', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .new CheckBox('2', 'Pirelli', 1)
                                            .'</div>'
                                            .'</li></ul>'
                                            .'</div>'
                                            .'<br/>',


                                        ),4),

                                        )),

/*
                                    new LayoutRow(array(
                                        new LayoutColumn(array(

                                            new Layout(array(
                                                new LayoutGroup(array(
                                                    new LayoutRow(array(
                                                        new LayoutColumn(array(

                                            new TextField('1', 'conti 205 autohaus', '', new Search()),
                                                            ),10),
                                                        new LayoutColumn(array(
                                                            (new LinkGroup())->addLink(
                                                                new Standard('Filter setzen', '#')
                                                            )->addLink(
                                                                new Standard('', '#', new Question())
                                                            )
                                                            ), 2),
                                                        )),
                                                    )),
                                                ))


                                        ))
                                    )),
                                    new LayoutRow(array(
                                        new LayoutColumn(array(
                                            new Ruler(),
                                            new Header('Hersteller'),
                                            new Listing(array(
                                                new CheckBox('2', 'Continental', 1),
                                                new Link('Mehr anzeigen', '#')
                                            )),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Ruler(),
                                            new Header('Baureihe'),
                                            new Listing(array(
                                                new CheckBox('2', '205 C Klasse Limousine', 1),
                                                new CheckBox('3', '205 C Klasse Kombi', 1),
                                                new CheckBox('4', '205 C Klasse Cabrio', 1),
                                                new Link('Mehr anzeigen', '#')
                                            )),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Ruler(),
                                            new Header('Bestand'),
                                            new Listing(array(
                                                new CheckBox('2', 'Autohaus', 1),
                                            )),
                                        ), 4),
                                    )),

                                    new LayoutRow(array(
                                        new LayoutColumn(array(
                                            new Ruler(),
                                            new Header('Hersteller'),
                                            new Listing(array(
                                                new TextField('1', '', '', new Search()),
                                                new CheckBox('2', 'Continental', 1),
                                                new CheckBox('2', 'Michelin', 1),
                                                new CheckBox('2', 'Pirelli', 1),
                                                new Link('Mehr anzeigen', '#')
                                            )),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Ruler(),
                                            new Header('Baureihe'),
                                            new Listing(array(
                                                new TextField('1', '', '', new Search()),
                                                new CheckBox('2', '205 C Klasse Limousine', 1),
                                                new CheckBox('3', '205 C Klasse Kombi', 1),
                                                new CheckBox('4', '205 C Klasse Cabrio', 1),
                                                new Link('Mehr anzeigen', '#')
                                            )),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Ruler(),
                                            new Header('Bestand'),
                                            new Listing(array(
                                                new TextField('1', '', '', new Search()),
                                                new Link('Weniger anzeigen', '#'),
                                                new CheckBox('2', 'Autohaus', 1),
                                                new CheckBox('2', 'Logistic Center', 1),
                                                new CheckBox('2', 'Zwickau', 1),
                                            )),
                                        ), 4),

                                    )),

                                    new LayoutRow(array(
                                        new LayoutColumn(array(
                                            new Ruler(),
                                            new Header('Hersteller'),
                                            new Listing(array(
                                                new TextField('1', '', '', new Search()),
                                                new CheckBox('2', 'Continental', 1),
                                            )),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Ruler(),
                                            new Header('Baureihe'),
                                                new TextField('1', '', '', new Search()),
                                        ), 4),
                                        new LayoutColumn(array(
                                            new Ruler(),
                                            new Header('Bestand'),
                                                new TextField('1', '', '', new Search()),
                                        ), 4),

                                    )),
*/
                                ), new Title('Erweiterter Filter', new PullRight(new Link('', '#', new CogWheels()))))
                            ))

                            ))
.'<script type="text/javascript">
        //noinspection JSUnresolvedFunction
        executeScript(function()
        {
            Client.Use(\'ModAlways\', function()
        {
            jQuery(document).on(\'click\', \'.dropdown .dropdown-menu\',function(Event){
                Event.stopPropagation();
            });
        });
    });
</script>'
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
