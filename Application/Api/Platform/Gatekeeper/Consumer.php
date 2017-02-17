<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer as ConsumerModule;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Service\Entity\TblConsumer;
use SPHERE\Common\Frontend\Ajax\Emitter\ClientEmitter;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Close;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Edit;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\ProgressBar;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Table\Structure\TableData;
use SPHERE\Common\Frontend\Text\Repository\Muted;

/**
 * Class Consumer
 * @package SPHERE\Application\Api\Platform\Gatekeeper
 */
class Consumer implements IApiInterface
{
    use ApiTrait;

    public static function receiverTableConsumer()
    {

        $Receiver = new BlockReceiver();

        $Receiver->initContent(
            new Panel(
                'Mandanten werden geladen...',
                array(
                    new ProgressBar(0, 100, 0, 6),
                    new Muted('Daten werden vom Server abgerufen')
                ),
                Panel::PANEL_TYPE_DEFAULT
            )
        );

        return $Receiver;
    }

    public static function receiverCreateConsumer()
    {
        $Receiver = new ModalReceiver('Neuen Mandanten anlegen', new Close());

        $Receiver->initContent(
            new Panel(
                'Formular wird geladen...',
                array(
                    new ProgressBar(0, 100, 0, 6),
                    new Muted('Daten werden vom Server abgerufen')
                ),
                Panel::PANEL_TYPE_DEFAULT
            )
        );

        return $Receiver;
    }

    public static function pipelineCreateConsumer(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Consumer::getEndpoint());
        $Emitter->setGetPayload(array(
            Consumer::API_TARGET => 'formConsumer',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new Pipeline();
        $LoadingEmitter = new ClientEmitter($Receiver,
            $Content = new Panel(
                'Formular wird geladen...',
                array(
                    new ProgressBar(0, 100, 0, 6),
                    new Muted('Daten werden vom Server abgerufen')
                ),
                Panel::PANEL_TYPE_DEFAULT
            )
        );
        $Receiver->initContent($Content);
        $Pipeline->addEmitter($LoadingEmitter);
        $Pipeline->addEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('tableConsumer');
        $Dispatcher->registerMethod('formConsumer');

        return $Dispatcher->callMethod($Method);
    }

    public function tableConsumer($Receiver)
    {
        $TblConsumerAll = ConsumerModule::useService()->getConsumerAll();

        $TableList = array();
        if ($TblConsumerAll) {
            array_walk($TblConsumerAll, function (TblConsumer $TblConsumer) use (&$TableList) {
                $TableList[] = array_merge($TblConsumer->__toArray(), array(
                    'Option' => new Standard('', '', new Edit())
                ));
            });
        }
        return new Panel(
            'Bestehende Mandanten',
            new TableData($TableList, null, array(
                'Acronym' => 'Kürzel des Mandanten',
                'Name' => 'Name des Mandanten',
                'Option' => ''
            ), true, true),
            Panel::PANEL_TYPE_DEFAULT,
            (new Link('Neu laden', '#'))->ajaxPipelineOnClick(
                Consumer::pipelineTableConsumer((new BlockReceiver())->setIdentifier($Receiver))
            )
        );
    }

    public static function pipelineTableConsumer(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Consumer::getEndpoint());
        $Emitter->setGetPayload(array(
            Consumer::API_TARGET => 'tableConsumer',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new Pipeline();
        $LoadingEmitter = new ClientEmitter($Receiver,
            $Content = new Panel(
                'Mandanten werden geladen...',
                array(
                    new ProgressBar(0, 100, 0, 6),
                    new Muted('Daten werden vom Server abgerufen')
                ),
                Panel::PANEL_TYPE_DEFAULT
            )
        );
        $Receiver->initContent($Content);
        $Pipeline->addEmitter($LoadingEmitter);
        $Pipeline->addEmitter($Emitter);

        return $Pipeline;
    }

    public function formConsumer()
    {

        return (string)new Form(new FormGroup(
            new FormRow(array(
                new FormColumn(
                    new Panel('Mandanten Informationen',array(
                    (new TextField(
                        'Acronym', 'Kürzel des Mandanten', 'Kürzel des Mandanten'
                    ))->setRequired(),
                    new TextField(
                        'Name', 'Name des Mandanten', 'Name des Mandanten'
                    )
                    ))
                )
            ))
        ), new Primary('Hinzufügen')
        );
    }
}
