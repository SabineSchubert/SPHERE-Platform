<?php

namespace SPHERE\Application\Api\Product\Filter;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Close;
use SPHERE\Common\Frontend\Form\Repository\Field\AutoCompleter;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Icon\Repository\Leaf;
use SPHERE\Common\Frontend\Icon\Repository\Remove;
use SPHERE\Common\Frontend\Icon\Repository\Snowflake;
use SPHERE\Common\Frontend\Icon\Repository\Sun;
use SPHERE\Common\Frontend\Layout\Repository\Badge;
use SPHERE\Common\Frontend\Layout\Repository\Dropdown;
use SPHERE\Common\Frontend\Layout\Repository\Label;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Text\Repository\Danger;
use SPHERE\Common\Frontend\Text\Repository\Info;
use SPHERE\Common\Frontend\Text\Repository\Muted;
use SPHERE\Common\Frontend\Text\Repository\Small;

/**
 * Class Filter
 * @package SPHERE\Application\Api\Product\Filter
 */
class Filter implements IApiInterface
{
    use ApiTrait;

    /**
     * @return BlockReceiver
     */
    public static function receiverProductFilter()
    {
        $Receiver = new BlockReceiver();
        $Receiver->setIdentifier(crc32(__METHOD__));
        return $Receiver;
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverFilterSetup()
    {
        $Receiver = new ModalReceiver('Filter - Einstellungen', new Close());
        $Receiver->setIdentifier(crc32(__METHOD__));
        return $Receiver;
    }

    public static function receiverProductList()
    {
        $Receiver = new BlockReceiver();
        $Receiver->setIdentifier(crc32(__METHOD__));
        return $Receiver;
    }

    public static function pipelineProductList(AbstractReceiver $Receiver, $Filter = array())
    {
        $Pipeline = new Pipeline(false);

        $Emitter = new ServerEmitter($Receiver, Filter::getEndpoint());
        $Emitter->setGetPayload(array(
            Filter::API_TARGET => 'layoutProductList',
        ));
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    public static function pipelineProductFilter(AbstractReceiver $Receiver)
    {
        $Pipeline = new Pipeline(false);

        $Emitter = new ServerEmitter($Receiver, Filter::getEndpoint());
        $Emitter->setGetPayload(array(
            Filter::API_TARGET => 'layoutProductFilter',
        ));
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    public function layoutProductFilter()
    {
        return new Layout(array(
            new LayoutGroup(
                new LayoutRow(array(
                    new LayoutColumn(
                        $this->formSeason()
                        , 2),
                    new LayoutColumn(
                        $this->formType()
                        , 2),
                    new LayoutColumn(
                        $this->formDimension()
                        , 3),
                    new LayoutColumn(
                        new Dropdown('Auswahl', new Panel('', array(
                                new CheckBox('Filter[Manufacturer][1]', 'Continental', 1),
                                new CheckBox('Filter[Manufacturer][2]', 'Michelin', 1),
                                new CheckBox('Filter[Manufacturer][3]', 'Pirelli', 1),
                                new CheckBox('Filter[Manufacturer][4]', 'Dunlop', 1),
                                new CheckBox('Filter[Manufacturer][5]', 'Bridgestone', 1),
                            ), Panel::PANEL_TYPE_DEFAULT, null, true)
                            , 'Hersteller')
                        , 2),
                    new LayoutColumn(
                        new TextField('Filter[VIN]', '', 'FIN')
                        , 1),
                    new LayoutColumn(
                        new Dropdown('Auswahl', new Panel('',
                                $this->formStock()
                                , Panel::PANEL_TYPE_DEFAULT, null, true)
                            , 'Bestand')
                        , 2),
                ))
            ),
            new LayoutGroup(
                new LayoutRow(array(
                    new LayoutColumn(
                        '<div id="FilterAdditional" class="collapse"><hr/>' .

                        new Layout(array(
                            new LayoutGroup(array(
                                new LayoutRow(array(
                                    new LayoutColumn(
                                        new Dropdown('Hersteller', new Panel('', array(
                                                new CheckBox('Filter[Manufacturer][1]', 'Continental', 1),
                                                new CheckBox('Filter[Manufacturer][2]', 'Michelin', 1),
                                                new CheckBox('Filter[Manufacturer][3]', 'Pirelli', 1),
                                                new CheckBox('Filter[Manufacturer][4]', 'Dunlop', 1),
                                            ), Panel::PANEL_TYPE_DEFAULT, null, true)
                                            , '')
                                        , 3),
                                    new LayoutColumn(
                                        new Dropdown('Auswahl', new Panel('', array(
                                                new CheckBox('Filter[Manufacturer][1]', 'Continental', 1),
                                                new CheckBox('Filter[Manufacturer][2]', 'Michelin', 1),
                                                new CheckBox('Filter[Manufacturer][3]', 'Pirelli', 1),
                                                new CheckBox('Filter[Manufacturer][4]', 'Bridgestone', 1),
                                            ), Panel::PANEL_TYPE_DEFAULT, null, true)
                                            , 'Hersteller')
                                        , 3),
                                    new LayoutColumn(
                                        new Dropdown('Auswahl', new Panel('', array(
                                                new CheckBox('Filter[Manufacturer][1]', 'Continental', 1),
                                                new CheckBox('Filter[Manufacturer][2]', 'Michelin', 1),
                                                new CheckBox('Filter[Manufacturer][3]', 'Dunlop', 1),
                                                new CheckBox('Filter[Manufacturer][4]', 'Bridgestone', 1),
                                            ), Panel::PANEL_TYPE_DEFAULT, null, true)
                                            , 'Hersteller')
                                        , 3),
                                    new LayoutColumn(
                                        new Dropdown('Auswahl', new Panel('', array(
                                                new CheckBox('Filter[Manufacturer][0]', 'Continental', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Pirelli', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Dunlop', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Bridgestone', 1),
                                            ), Panel::PANEL_TYPE_DEFAULT, null, true)
                                            , 'Hersteller')
                                        , 3),
                                )),
                                new LayoutRow(array(
                                    new LayoutColumn(
                                        new Dropdown('Auswahl', new Panel('', array(
                                                new CheckBox('Filter[Manufacturer][0]', 'Michelin', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Pirelli', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Dunlop', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Bridgestone', 1),
                                            ), Panel::PANEL_TYPE_DEFAULT, null, true)
                                            , 'Hersteller')
                                        , 3),
                                    new LayoutColumn(
                                        new Dropdown('Auswahl', new Panel('', array(
                                                new CheckBox('Filter[Manufacturer][0]', 'Continental', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Michelin', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Pirelli', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Dunlop', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'Bridgestone', 1),
                                                new CheckBox('Filter[Manufacturer][0]', 'China', 1),
                                            ), Panel::PANEL_TYPE_DEFAULT, null, true)
                                            , 'Hersteller')
                                        , 3),
                                )),
                            ))
                        ))

                        . '<br/></div>'
                        . '<button href="#FilterAdditional" class="btn btn-default" data-toggle="collapse">Weitere Filter</button>
                    '
                    )
                ))
            )
        ));
    }

    private function formSeason()
    {
        return new Layout(new LayoutGroup(new LayoutRow(array(
            new LayoutColumn(
                new Standard('', '#', new Sun(), array('Filter' => array('Season' => 1)), 'Sommer')
                , 4),
            new LayoutColumn(
                new Standard('', '#', new Snowflake(), array('Filter' => array('Season' => 2)), 'Winter')
                , 4),
            new LayoutColumn(
                new Standard('', '#', new Leaf(), array('Filter' => array('Season' => 3)), 'Ganzjahr')
                , 4),
        ))));
    }

    private function formType()
    {
        return new Layout(new LayoutGroup(new LayoutRow(array(
            new LayoutColumn(array(
                new CheckBox('Filter[Type][0]', 'Reifen', 1),
                new CheckBox('Filter[Type][1]', 'Komplettrad', 1)
            )),
        ))));
        return array(
            new CheckBox('Filter[Type][0]', 'Reifen', 1),
            new CheckBox('Filter[Type][1]', 'Komplettrad', 1),
        );
    }

    private function formDimension()
    {
        return new Layout(new LayoutGroup(new LayoutRow(array(
            new LayoutColumn(
                new AutoCompleter('Filter[Dimension][0]', 'Breite', '', array('205', '215', '225'))
                , 4),
            new LayoutColumn(
                new AutoCompleter('Filter[Dimension][1]', 'Querschnitt', '', array('45','50','55'))
                , 4),
            new LayoutColumn(
                new AutoCompleter('Filter[Dimension][2]', 'Durchmesser', '', array('16','17','18'))
                , 4),
        ))));
    }

    private function formStock()
    {
        return array(
            new CheckBox('Filter[Stock][0]', 'Autohaus', 1),
            new CheckBox('Filter[Stock][1]', 'LC', 1),
            new CheckBox('Filter[Stock][2]', 'Zwickau', 1),
        );
    }

    public function layoutProductList()
    {
        return implode( ' ', array(
            new Badge(new Link( new Small(new Remove()),'#' ) . ' Continental', Badge::BADGE_TYPE_DEFAULT),
            new Badge(new Link( new Small(new Remove()),'#' ) . ' B 185', Badge::BADGE_TYPE_DEFAULT),
            new Badge(new Link( new Small( new Remove() ),'#' ) . ' Q 60', Badge::BADGE_TYPE_DEFAULT),
            new Badge(new Link( new Small( new Remove() ),'#' ) . ' D 16', Badge::BADGE_TYPE_DEFAULT),
            new Badge(new Link( new Small( new Remove() ),'#' ) . ' Sommer', Badge::BADGE_TYPE_DEFAULT),
            new Badge(new Link( new Small( new Remove() ),'#' ) . ' FIN 1234567890123', Badge::BADGE_TYPE_DEFAULT),
        ));
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('layoutProductFilter');
        $Dispatcher->registerMethod('layoutProductList');

        return $Dispatcher->callMethod($Method);
    }
}