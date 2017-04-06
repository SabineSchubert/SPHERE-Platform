<?php
namespace SPHERE\Application\Api\Product\Filter;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\AutoCompleter;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\RadioBox;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\PlusSign;
use SPHERE\Common\Frontend\Icon\Repository\Remove;
use SPHERE\Common\Frontend\Layout\Repository\Accordion;
use SPHERE\Common\Frontend\Layout\Repository\Badge;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullClear;
use SPHERE\Common\Frontend\Layout\Repository\Ruler;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Table\Structure\Table;

/**
 * Class Filter
 * @package SPHERE\Application\Api\Product\Filter
 */
class Filter implements IApiInterface
{
    use ApiTrait;

    public static function receiverProductFilter()
    {
        return new BlockReceiver(
            new Form(
                new FormGroup(array(
                    new FormRow(
                        new FormColumn(array(
                            new PullClear(new AutoCompleter('Filter[VIN]', '', 'Fahrgestellnummer', array('1'))),
                            new Ruler()
                        ))
                    ),
                    new FormRow(array(
                        new FormColumn(array(
                            new Badge( new Remove(). ' Continental', 1),
                            new RadioBox('Filter[Manufacturer][2]', 'Michelin', 1),
                        ), 10),
                    )),
                    new FormRow(array(
                        new FormColumn(array(
                            new PullClear(new AutoCompleter('Filter[HSN]', '', 'HSN', array('1'))),
                        ), 6),
                        new FormColumn(array(
                            new PullClear(new AutoCompleter('Filter[TSN]', '', 'TSN', array('1'))),
                        ), 6)
                    )),
                    new FormRow(
                        new FormColumn(array(
                            new Ruler(),
                            (new Accordion())->addItem('Typ',
                            new Panel('', array(
                                new CheckBox('Filter[Type][Wheel]', 'Komplettrad', 1),
                                new CheckBox('Filter[Type][Tyre]', 'Reifen', 1),
                            ))
                            )->addItem('Season',
                            new Panel('', array(
                                new CheckBox('Filter[Season][Summer]', 'Sommer', 1),
                                new CheckBox('Filter[Season][Winter]', 'Winter', 1),
                                new CheckBox('Filter[Season][All]', 'AllSeason', 1),
                            ))
                            )->addItem('Manufacturer',
                            new Panel('', array(
                                new Standard('Hersteller','#', new PlusSign()),
                                new CheckBox('Filter[Manufacturer][Continental]', 'Continental', 1),
                                new CheckBox('Filter[Manufacturer][Michelin]', 'Michelin', 1),
                                new CheckBox('Filter[Manufacturer][Pirelli]', 'Pirelli', 1),
                                new CheckBox('Filter[Manufacturer][Dunlop]', 'Dunlop', 1),
                                new CheckBox('Filter[Manufacturer][Bridgestone]', 'Bridgestone', 1),
                            ))
                            )
                        ))
                    ),
                )), new Primary('Suchen')
            )
        );
    }

    public static function receiverProductList()
    {
        return new BlockReceiver();
    }

    public static function pipelineProductList(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Filter::getEndpoint());
        $Emitter->setGetPayload(array(
            Filter::API_TARGET => 'targetProductList',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new Pipeline(false);
        $Pipeline->appendEmitter($Emitter);
        return $Pipeline;
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('targetProductList');

        return $Dispatcher->callMethod($Method);
    }

    public function targetProductList($Filter)
    {
        return new Table(array(), null, array($Filter), true, false);
    }
}