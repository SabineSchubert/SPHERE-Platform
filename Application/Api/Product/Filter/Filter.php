<?php

namespace SPHERE\Application\Api\Product\Filter;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Utility\Translation\TranslateTrait;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Close;
use SPHERE\Common\Frontend\Form\Repository\Field\AutoCompleter;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Layout\Repository\Header;
use SPHERE\Common\Frontend\Layout\Repository\Listing;
use SPHERE\Common\Frontend\Layout\Repository\PullClear;
use SPHERE\Common\Frontend\Layout\Repository\Ruler;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Small;

/**
 * Class Filter
 * @package SPHERE\Application\Api\Product\Filter
 */
class Filter implements IApiInterface
{
    use ApiTrait, TranslateTrait;

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

    public static function pipelineProductFilter(AbstractReceiver $Receiver)
    {
        $Pipeline = new Pipeline(false);

        $Emitter = new ServerEmitter($Receiver, Filter::getEndpoint());
        $Emitter->setGetPayload(array(
            Filter::API_TARGET => 'formProductFilter',
        ));
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    public static function pipelineFilterSetup(AbstractReceiver $Receiver)
    {
        $Pipeline = new Pipeline(false);

        $Emitter = new ServerEmitter($Receiver, Filter::getEndpoint());
        $Emitter->setGetPayload(array(
            Filter::API_TARGET => 'formFilterSetup',
        ));
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    public function contentFilterSetup() {

    }
    // TODO: !!! Prevent Link-Hash twins !!! Bug!
    public function contentFilterSelector($Receiver, $Data = array(), $Title = '', $Toggle = false, $Filter = array())
    {

        $Receiver = Filter::receiverFilterSelector($Receiver);
        $ReceiverProductList = Filter::receiverProductList();

        $VisibleOptions = 2;

        if( isset($Filter[$Receiver->getIdentifier()] ) ) {
            $SelectedOptions = count( $Filter[$Receiver->getIdentifier()] );
            } else {
            $SelectedOptions = 0;
        }

        $Content = array();

        if ($Title) {
            $Content[] = new Header($Title);
        }

        if ($Toggle) {
            if( count( $Data ) > $VisibleOptions +1 && $SelectedOptions < count( $Data ) ) {
                $Content[] = (new Link(new Small('Weniger '.$Title.' anzeigen'), Filter::getEndpoint()))
                    ->ajaxPipelineOnClick(Filter::pipelineFilterSelector($Receiver, $Data, $Title, false));
            }
            foreach ($Data as $Id => $Label) {
                $Content[] = (new CheckBox('Filter[' . $Receiver->getIdentifier() . '][' . $Id . ']', $Label, 1))
                    ->ajaxPipelineOnClick(array(
                        Filter::pipelineFilterSelector($Receiver, $Data, $Title, $Toggle),
                        Filter::pipelineProductList($ReceiverProductList)
                    ));
            }
        } else {
            $VisibleOptionRun = $VisibleOptions;
            foreach ($Data as $Id => $Label) {
                if ($VisibleOptionRun > 0 || isset($Filter[$Receiver->getIdentifier()][$Id])) {
                    $Content[] = (new CheckBox('Filter[' . $Receiver->getIdentifier() . '][' . $Id . ']', $Label, 1))
                        ->ajaxPipelineOnClick(array(
                            Filter::pipelineFilterSelector($Receiver, $Data, $Title, $Toggle),
                            Filter::pipelineProductList($ReceiverProductList)
                        ));
                    $VisibleOptionRun--;
                } else if( count( $Data ) == $VisibleOptions +1 ) {
                    $Content[] = (new CheckBox('Filter[' . $Receiver->getIdentifier() . '][' . $Id . ']', $Label, 1))
                        ->ajaxPipelineOnClick(array(
                            Filter::pipelineFilterSelector($Receiver, $Data, $Title, $Toggle),
                            Filter::pipelineProductList($ReceiverProductList)
                        ));
                }
            }
            if( count( $Data ) > $VisibleOptions +1 && $SelectedOptions < count( $Data ) ) {
                $Content[] = (new Link(new Small('Mehr '.$Title.' anzeigen'), Filter::getEndpoint()))
                    ->ajaxPipelineOnClick(Filter::pipelineFilterSelector($Receiver, $Data, $Title, true));
            }
        }

        return new Listing($Content);
    }

    public static function receiverFilterSelector($Identifier)
    {
        $Receiver = new BlockReceiver();
        $Receiver->setIdentifier($Identifier);
        return $Receiver;
    }

    public static function receiverProductList()
    {
        $Receiver = new BlockReceiver();
        $Receiver->setIdentifier(crc32(__METHOD__));
        return $Receiver;
    }

    public static function pipelineFilterSelector(
        AbstractReceiver $Receiver,
        $Data = array(),
        $Title = '',
        $Toggle = false
    ) {
        $Pipeline = new Pipeline(false);

        $Emitter = new ServerEmitter($Receiver, Filter::getEndpoint());
        $Emitter->setGetPayload(array(
            Filter::API_TARGET => 'contentFilterSelector',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Emitter->setPostPayload(array(
            'Data' => $Data,
            'Title' => $Title,
            'Toggle' => $Toggle ? 1 : 0
        ));
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    public static function pipelineProductList(AbstractReceiver $Receiver, $Filter = array())
    {
        $Pipeline = new Pipeline(false);

        $Emitter = new ServerEmitter($Receiver, Filter::getEndpoint());
        $Emitter->setGetPayload(array(
            Filter::API_TARGET => 'tableProductList',
        ));
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    public function formFilterSetup()
    {
        $Form = (new Form(array(
            new FormGroup(
                new FormRow(array(
                    new FormColumn(array(
                        new CheckBox('Setup[VIN]', 'Fahrgestellnummer', 1),
                        new CheckBox('Setup[HSNTSN]', 'HSN / TSN', 1),
                        new CheckBox('Setup[Season]', 'Saison', 1),
                        new CheckBox('Setup[Manufacturer]', 'Hersteller', 1),
                    ))
                ))
            ),
        )));

        return $Form;
    }

    public function formProductFilter()
    {
        $Form = (new Form(array(
            new FormGroup(
                new FormRow(
                    new FormColumn(array(
                        new PullClear(
                            (new AutoCompleter('Filter[VIN]', '', 'Fahrgestellnummer', array('1')))
                                ->ajaxPipelineOnKeyUp(Filter::pipelineProductList(Filter::receiverProductList()))
                        ),
                        new Ruler()
                    ))
                )
            ),
            new FormGroup(
                new FormRow(array(
                    new FormColumn(array(
                        new PullClear(
                            (new AutoCompleter('Filter[HSN]', '', 'HSN', array('1')))
                                ->ajaxPipelineOnKeyUp(Filter::pipelineProductList(Filter::receiverProductList()))
                        ),
                    ), 6),
                    new FormColumn(array(
                        new PullClear(
                            (new AutoCompleter('Filter[TSN]', '', 'TSN', array('1')))
                                ->ajaxPipelineOnKeyUp(Filter::pipelineProductList(Filter::receiverProductList()))
                        ),
                    ), 6)
                ))
            ),
            new FormGroup(
                new FormRow(
                    new FormColumn(
                        new Ruler()
                    )
                )
            )
        )))->ajaxPipelineOnSubmit(Filter::pipelineProductList(Filter::receiverProductList()));

        $Form->appendGridGroup(
            $this->formGroupFilterSelector('Season', array(
                0 => 'Sommer',
                1 => 'Winter',
                2 => 'Ganzjahr'
            ), 'Saison')
        );
        $Form->appendGridGroup(
            $this->formGroupFilterSelector('Manufacturer', array(
                0 => 'Continental',
                1 => 'Michelin',
                2 => 'Pirelli',
                3 => 'Dunlop',
                4 => 'Bridgestone'
            ), 'Hersteller')
        );

        return $Form;
    }

    private function formGroupFilterSelector($Identifier, $Data = array(), $Title = '')
    {
        return new FormGroup(
            new FormRow(array(
                new FormColumn(array(
                    $Receiver = Filter::receiverFilterSelector($Identifier),
                    Filter::pipelineFilterSelector($Receiver, $Data, $Title, false)
                )),
            ))
        );
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('formProductFilter');
        $Dispatcher->registerMethod('tableProductList');

        $Dispatcher->registerMethod('contentFilterSelector');

        $Dispatcher->registerMethod('contentFilterSetup');
        $Dispatcher->registerMethod('formFilterSetup');

        return $Dispatcher->callMethod($Method);
    }

    public function tableProductList($Filter)
    {

        $TableHeader = array();
        $TableData = array();
        $MaxRowCount = 0;

        if (!empty($Filter)) {

            foreach ($Filter as $Group => $Content) {
                $TableHeader[$Group] = $Group;
                if (is_array($Content)) {
                    $MaxRowCount = count($Content) > $MaxRowCount ? count($Content) : $MaxRowCount;
                } elseif (!empty($Content)) {
                    $MaxRowCount = 1 > $MaxRowCount ? 1 : $MaxRowCount;
                }
            }

            for ($Run = 0; $Run < $MaxRowCount; $Run++) {
                foreach ($Filter as $Group => $Content) {
                    if (is_array($Content)) {
                        if (count($Content) >= $Run) {
                            $TableData[$Run][$Group] = current(array_slice($Content, $Run, 1));
                        } else {
                            $TableData[$Run][$Group] = '';
                        }
                    } else {
                        $TableData[$Run][$Group] = $Content;
                    }
                }
            }

            return (new Table($TableData, null, $TableHeader, true, false));//->setHash('tableProductList');
        }

        return new Warning('Bitte Filtern');
    }
}