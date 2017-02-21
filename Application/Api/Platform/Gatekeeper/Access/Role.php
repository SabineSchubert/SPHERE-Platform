<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper\Access;

use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline as AjaxPipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;

/**
 * Class Role
 * @package SPHERE\Application\Api\Platform\Gatekeeper\Access
 */
class Role
{
    /**
     * @param AbstractReceiver $Receiver
     * @return AjaxPipeline
     */
    public static function pipelineStageRole(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'stageRole',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new AjaxPipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @param AbstractReceiver $Receiver
     * @return AjaxPipeline
     */
    public static function pipelineTableRole(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'tableRole',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new AjaxPipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @param AbstractReceiver $Receiver
     * @return AjaxPipeline
     */
    public static function pipelineFormRole(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'formRole',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new AjaxPipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @param AbstractReceiver $Receiver
     * @return AjaxPipeline
     */
    public static function pipelineInsertRole(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'insertRole',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new AjaxPipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return BlockReceiver
     */
    public static function receiverTableRole()
    {

        $Receiver = new BlockReceiver();
        $Receiver->initContent(
            new Panel(
                'Rollen werden geladen...',
                array(
                    new ProgressBar(0, 100, 0, 6),
                    new Muted('Daten werden vom Server abgerufen')
                ),
                Panel::PANEL_TYPE_DEFAULT
            )
        );
        return $Receiver;
    }
    /**
     * @return Layout
     */
    public function stageRole()
    {
        return new Layout(array(
            new LayoutGroup(array(
                new LayoutRow(array(
                    new LayoutColumn(array(
                        ($Table = Access::receiverTableRole()),
                        ($Modal = Access::receiverCreateRole()),
                        (new Standard('Rolle anlegen', Access::getEndpoint(), null, array(
                            'TableReceiver' => $Table->getIdentifier()
                        )))->ajaxPipelineOnClick(Access::pipelineFormRole($Modal)),
                        Access::pipelineTableRole($Table)
                    )),
                )),
            ), new Title('Rollen')),
        ));
    }
}
