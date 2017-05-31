<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\Product\Car;


use SPHERE\Application\Api\Product\Filter\Filter;
use SPHERE\Application\Platform\Utility\Translation\TranslateTrait;
use SPHERE\Application\Platform\Utility\Translation\TranslationTrait;
use SPHERE\Common\Frontend\Icon\Repository\Cog;
use SPHERE\Common\Frontend\Icon\Repository\CogWheels;
use SPHERE\Common\Frontend\Icon\Repository\FACircleO;
use SPHERE\Common\Frontend\Icon\Repository\Wrench;
use SPHERE\Common\Frontend\Layout\Repository\Container;
use SPHERE\Common\Frontend\Layout\Repository\PullClear;
use SPHERE\Common\Frontend\Layout\Repository\PullLeft;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Muted;
use SPHERE\Common\Frontend\Text\Repository\Small;
use SPHERE\Common\Frontend\Text\Repository\Tooltip;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\Slider;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Icon\Repository\Disable;
use SPHERE\Common\Frontend\Icon\Repository\Leaf;
use SPHERE\Common\Frontend\Icon\Repository\Snowflake;
use SPHERE\Common\Frontend\Icon\Repository\Sun;
use SPHERE\Common\Frontend\Icon\Repository\Wheel;
use SPHERE\Common\Frontend\Layout\Repository\Badge;
use SPHERE\Common\Frontend\Layout\Repository\Dropdown;
use SPHERE\Common\Frontend\Layout\Repository\Header;
use SPHERE\Common\Frontend\Layout\Repository\Listing;
use SPHERE\Common\Frontend\Layout\Repository\Paragraph;
use SPHERE\Common\Frontend\Layout\Repository\Ruler;
use SPHERE\Common\Frontend\Layout\Repository\Scrollable;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Text\Repository\Danger;

class Frontend extends Extension
{
    use TranslateTrait;

    private function filterAdvancedCheck( $Title, $Data = array(), $Muted = false, $Internal = false ) {
        foreach( $Data as $Name => $Label ) {
            $LabelDescription = explode( ' ', $Label );
            if( isset( $LabelDescription[1] ) && $Muted ) {
                $Label = '<span class="search-result">'.$LabelDescription[0].'</span>'
                    .' '. new Muted(
                        '<span class="search-result">'.implode( '</span> <span class="search-result">',array_slice( $LabelDescription, 1 ) ).'</span>'
                    );
            }
            $Data[$Name] = new CheckBox( $Name, $Label, 1 );
        }

        $Dropdown = array(
            new Scrollable(
                $Receiver = (new BlockReceiver(
                    new Listing($Data)
                ))
            )
        );

        if( count( $Data) > 5 ) {
            array_unshift($Dropdown,'
            
<script type="text/javascript">
    //noinspection JSUnresolvedFunction
    executeScript(function()
    {
        Client.Use(\'ModAlways\', function()
        {
            var Option = {
                listClass: "list-group",
                indexAsync: true,
                valueNames: [
                    "sphere-label",
                    "search-result"
                ]
            };
            var Search = new List(jQuery(".'.$Receiver->getIdentifier().'")[0], Option);
            jQuery("input.search-'.crc32($Title).'").on("keyup", function() {
                var searchString = jQuery(this).val();
                Search.search(searchString);
            });
        });
    });
</script>
            ');
            array_unshift($Dropdown, (new TextField('Search', 'Liste eingrenzen'))->setCustomClass('search-'.crc32($Title)));
        }
        if( $Internal ) {
            return new Container($Dropdown);
        } else {
            return new Container(new Dropdown($Title, $Dropdown));
        }
    }

    private function filterAdvancedSlider( $Title ) {

        return new Container(new Dropdown($Title, new Listing(array(
            (new Slider('Slider1', 'Slider', 'Nasshaftung mind:'))
                ->configureLibrary(Slider::LIBRARY_SLIDER, array(
                    'formatter' => "function(value) { var Letter = ['A','B','C','D','E','F','G']; return Letter[value]; }",
                    'min' => 0,
                    'max' => 6,
                    'value' => 6,
                    'reversed' => true
                )),
            (new Slider('Slider2', 'Slider', 'Rollwiderstand mind:'))
                ->configureLibrary(Slider::LIBRARY_SLIDER, array(
                    'formatter' => "function(value) { var Letter = ['A','B','C','D','E','F','G']; return Letter[value]; }",
                    'min' => 0,
                    'max' => 6,
                    'value' => 6,
                    'reversed' => true
                )),
            (new Slider('Slider3', 'Slider', 'Abrollgeräusch max:'))
                ->configureLibrary(Slider::LIBRARY_SLIDER, array(
                    'formatter' => "function(value) { var Letter = ['1','2','3']; return Letter[value]; }",
                    'min' => 0,
                    'max' => 2,
                    'value' => 2
                ))
        ))));
    }

    public function frontendCar()
    {

        $_POST['2'] = 1;

        $Stage = new Stage('Preisliste', 'Pkw & smart');
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
                                                            new CheckBox('2', new Sun() . ' Sommer', 1),
                                                            new CheckBox('2', new Snowflake() . ' Winter', 1),
                                                            new CheckBox('2', new Leaf() . ' Ganzjahr', 1),
                                                        )),
                                                    )),
                                                )),
                                            )),

                                            new Ruler(),

                                            new Layout(array(
                                                new LayoutGroup(array(
                                                    new LayoutRow(array(
                                                        new LayoutColumn(array(
                                                            new CheckBox('2', new FACircleO() . ' Reifen', 1),
                                                            new CheckBox('2', new Wheel() . ' Räder', 1),
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
                                            $this->filterAdvancedCheck( 'Hersteller', array(
                                                'Hersteller[1]' => 'Continental - Premium',
                                                'Hersteller[2]' => 'Pirelli - Premium',
                                                'Hersteller[3]' => 'Michelin - Premium',
                                                'Hersteller[4]' => 'Bridgestone - Premium',
                                                'Hersteller[5]' => 'Dunlop - Premium',
                                                'Hersteller[6]' => 'Goodyear - Premium',
                                                'Hersteller[7]' => 'Kumho - Premium',
                                                'Hersteller[8]' => 'Yokohama - Premium',
                                                'Hersteller[9]' => 'Hankook - Premium',
                                                'Hersteller[10]' => 'Semperit - Quality',
                                                'Hersteller[11]' => 'Fulda - Quality',
                                                'Hersteller[12]' => 'Barum - Budget',
                                            ), true, true),

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

//                                            new Ruler(),
//                                            new Header('Bestand'),
//                                            new Listing(array(
//                                                new Slider('Slider1', 'Slider', 'VK'),
//                                                new Slider('Slider2', 'Slider', 'EK'),
//                                                new Slider('Slider4', 'Slider', 'VK inkl Montage'),
//                                            )),

                                        )),
                                    )),
                                ), new Title('Filter', new PullRight(new Link('', '#', new CogWheels())))),
                            ))

                        ), 2),
                        new LayoutColumn(array(
                            '<div id="FilterAdditional" class="collapse">' .
//                            new Listing(array(
                            new Layout(array(
                                new LayoutGroup(array(
                                    new LayoutRow(array(
                                        new LayoutColumn($this->filterAdvancedCheck( 'Sortiment', array(
                                            'Sortiment[1]' => 'Aktionssortiment',
                                            'Sortiment[2]' => 'Reguläres Sortiment',
                                            'Sortiment[3]' => 'DOT-Angebote',
                                            'Sortiment[4]' => 'Geprüfte Junge Räder',
                                            'Sortiment[5]' => 'Flottensortiment'
                                        )), 3),
                                        new LayoutColumn($this->filterAdvancedCheck( 'Baureihe', array(
                                            '176' => '176 A-Klasse',
                                            '169' => '169 A-Klasse',
                                            '168' => '168 A-Klasse',

                                            '246' => '246 B-Klasse',
                                            '242' => '242 B-Klasse',
                                            '245' => '245 B-Klasse',
                                            '117' => '117 CLA',

                                            '205' => '205 C-Klasse',
                                            '204' => '204 C-Klasse',
                                            '203' => '203 C-Klasse',
                                            'CLC' => 'CLC C-Klasse',
                                            '202' => '202 C-Klasse',

                                            '213AT' => '213AT E-Klasse',
                                            '213' => '213 E-Klasse',
                                            '212' => '212 E-Klasse',
                                            '238' => '238 E-Klasse',
                                            '207' => '207 E-Klasse',
                                            '211' => '211 E-Klasse',
                                            '210' => '210 E-Klasse',
                                            '222' => '222 S-Klasse',
                                            '221' => '221 S-Klasse',
                                            '220' => '220 S-Klasse',
                                            '217' => '217 CL',
                                            '216' => '216 CL',
                                            '215' => '215 C',
                                            '209' => '209 CLK',
                                            '208' => '208 CLK',
                                            '172' => '172 SLC',
                                            '171' => '171 SLK',
                                            '170' => '170 SLK',

                                            '218' => '218 CLS',
                                            'X218' => 'X218 CLS SB',
                                            '219' => '219 CLS',
                                            '231' => '231 SL',
                                            '230' => '230 SL',

                                            '292' => '292 GLE',
                                            '166' => '166 M-Klasse',
                                            '164' => '164 M-Klasse',
                                            '163' => '163 M-Klasse',

                                            '251' => '251 R-Klasse',

                                            'X156' => 'X156 GLA',
                                            'X164' => 'X164 GL',
                                            'X166' => 'X166 GL',
                                            'X204' => 'X204 GLK',
                                            'X253' => 'X253 GLC',
                                            'C253' => 'C253 GLC',

                                            '463' => '463 G-Klasse',

                                            '190' => '190 GT',
                                            '197' => '197 SLS',
                                            '199' => '199 SLR',

                                            '240' => '240 Maybach',

                                            '453' => '453 smart',
                                            '451' => '451 smart',
                                            '454' => '454 smart',
                                            '452' => '452 smart',
                                            '450' => '450 smart',

                                            '447' => '447 V-Klasse',
                                            '639' => '639 Vito / Viano',
                                            '638' => '638 Vito / Viano',

                                            '415' => '415 Citan',
                                            '414' => '414 Vaneo',

                                            '906' => '906 Sprinter',
                                            '904' => '904 Sprinter ',
                                            '903' => '903 Sprinter',
                                            '902' => '902 Sprinter',
                                            '901' => '901 Sprinter'
                                        ), true), 3),
                                        new LayoutColumn($this->filterAdvancedCheck( 'RDK', array(
                                            'RDK' =>'RDK'
                                        )), 3),
                                        new LayoutColumn($this->filterAdvancedCheck( 'Notlaufeigenschaften', array(
                                            'Notlaufeigenschaften[1]' => 'MOE - MercedesBenzExtended',
                                            'Notlaufeigenschaften[2]' => 'sonstige',
                                            'Notlaufeigenschaften[3]' => 'keine'
                                        )), 3),
                                    )),
                                    new LayoutRow(array(
                                        new LayoutColumn(array(
                                            new Container(new Dropdown( 'Teilenummern', array(new Listing(array(
                                                new TextField('Komplettrad', 'Komplettrad'),
                                                new TextField('Reifen', 'Reifen'),
                                                new TextField('Felge', 'Felge'),
                                                new TextField('EAN', 'EAN'),
                                                new TextField('SA-Code', 'SA-Code'),
                                            )))))
                                        ), 3),
                                        new LayoutColumn($this->filterAdvancedCheck( 'Felgenart', array(
                                            'Felgenart[1]' => 'Alufelge',
                                            'Felgenart[2]' => 'Stahlfelge',
                                        )), 3),
                                        new LayoutColumn(array(
                                            new Container(new Dropdown( 'Felgendesign', array(new Listing(array(
                                                new TextField( 'Felgendesign', 'Felgendesign' )
                                            )))))
                                        ), 3),
                                        new LayoutColumn($this->filterAdvancedSlider( 'EU-Label' ), 3),

                                    )),
                                ), new Title('Weitere Filteroptionen'))
                            )).
                            new Ruler().
                            new PullClear(new PullRight( new Standard('Suche speichern', '#')))

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
                                            new PullRight('<button href="#FilterAdditional" class="btn btn-default" data-toggle="collapse">Weitere Filteroptionen</button>')

                                        ), 2),
                                    )),

                                    new LayoutRow(array(
                                        new LayoutColumn(array(
                                            new Paragraph(''),
                                            new Table(array(), null, array('A', 'B', 'C')),
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

//    public function frontendCar()
//    {
//        $Stage = new Stage('Preisliste', 'Pkw & smart');
//        $Stage->hasUtilityFavorite(true);
//
//        $Stage->setContent(
//            new Layout(array(
//                new LayoutGroup(array(
//                    new LayoutRow(array(
//                        new LayoutColumn(array(
//                            $ReceiverFilterSetup = Filter::receiverFilterSetup(),
//                            new Title(
//                                new PullClear(
//                                    new PullLeft('Filter ')
//                                    .new PullRight(
//                                        ( new Link('', Filter::getEndpoint(), new CogWheels()) )
////                                        ->ajaxPipelineOnClick(Filter::pipelineFilterSetup($ReceiverFilterSetup))
//                                    )
//                                )
//                            ),
//                        ))
//                    )),
//                    new LayoutRow(array(
//                        new LayoutColumn(array(
//                            (new Filter())->layoutProductFilter()
////                            $ReceiverProductFilter = Filter::receiverProductFilter(),
////                            Filter::pipelineProductFilter( $ReceiverProductFilter ),
//                        )),
//                    )),
//                )),
//                new LayoutGroup(
//                    new LayoutRow(array(
////                        new LayoutColumn(array(
////                            $ReceiverFilterSetup = Filter::receiverFilterSetup(),
////                            new Title(
////                                new PullClear(
////                                    new PullLeft('Filter ')
////                                    .new PullRight(
////                                        ( new Link(' ', Filter::getEndpoint(), new Cog()) )
////                                        ->ajaxPipelineOnClick(Filter::pipelineFilterSetup($ReceiverFilterSetup))
////                                    )
////                                )
////                            ),
////                            $ReceiverProductFilter = Filter::receiverProductFilter(),
////                            Filter::pipelineProductFilter( $ReceiverProductFilter ),
////                        ), 2),
//                        new LayoutColumn(array(
//                            '<br/>',
//                            new Title('Produkte'),
//                            (new Filter())->layoutProductList()
////                            $ReceiverProductList = Filter::receiverProductList(),
////                            Filter::pipelineProductList( $ReceiverProductList ),
//                        ), 12),
//                    ))
//                ),
//                new LayoutGroup(
//                    new LayoutRow(array(
//                        new LayoutColumn(array(
//                            new Container('&nbsp;'),
//                            new Table(array(),null,array('A','B','C'))
//                        ))
//                    ))
//                ),
//        )));
//
//        return $Stage;
//    }
}
