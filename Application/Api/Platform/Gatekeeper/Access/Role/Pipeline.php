<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper\Access\Role;

use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline as AjaxPipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;

/**
 * Class Pipeline
 * @package SPHERE\Application\Api\Platform\Gatekeeper\Access\Role
 */
class Pipeline extends Receiver
{
    /**
     * @param AbstractReceiver $Receiver
     * @return AjaxPipeline
     */
    public static function pipelineStageRole(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET_CLASS => 'Access\Role',
            Access::API_TARGET_METHOD => 'stageRole',
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
            Access::API_TARGET_METHOD => 'tableRole',
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
            Access::API_TARGET_METHOD => 'editRole',
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
            Access::API_TARGET_METHOD => 'formRole',
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
            Access::API_TARGET_METHOD => 'insertRole',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new AjaxPipeline();
        $Pipeline->appendEmitter($Emitter);
        return $Pipeline;
    }
}