<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper\Access;

use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline as AjaxPipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;

/**
 * Class Pipeline
 * @package SPHERE\Application\Api\Platform\Gatekeeper\Access
 */
class Pipeline
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
    public static function pipelineTableLevel(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'tableLevel',
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
    public static function pipelineStageLevel(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'stageLevel',
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
    public static function pipelineEditRole(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'editRole',
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
     * @param AbstractReceiver $Receiver
     * @return AjaxPipeline
     */
    public static function pipelineCreateLevel(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'createLevel',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new AjaxPipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }
}
