<?php

namespace SPHERE\Application\Api\Platform\Gatekeeper\Access;

use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\InlineReceiver;

/**
 * Class RolePipeline
 * @package SPHERE\Application\Api\Platform\Gatekeeper\Access
 */
class RolePipeline extends RoleReceiver
{
    /**
     * @return Pipeline
     */
    public static function pipelineRoleStage()
    {
        $Emitter = new ServerEmitter(Access::receiverStage(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'loadRoleStage'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter(self::inProgressEmitter(Access::receiverStage(), 'Inhalte werden geladen...'));
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @param bool $Loader
     * @return Pipeline
     */
    public static function pipelineRoleTable( $Loader = false )
    {
        $Emitter = new ServerEmitter(Access::receiverRoleTable(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'loadRoleTable'
        ));
        $Pipeline = new Pipeline(false);
        if( $Loader ) {
            $Pipeline->appendEmitter(self::inProgressEmitter(Access::receiverRoleTable(), 'Rollen werden geladen...'));
        }
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleSetupEnable()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleSetupSwitch(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'actionRoleSetupEnable'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);
        $Pipeline->appendForeignEmitter( Access::pipelineRoleTable() );

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleSetupDisable()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleSetupSwitch(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'actionRoleSetupDisable'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);
        $Pipeline->appendForeignEmitter( Access::pipelineRoleTable() );

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleFormInsert()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleInsert(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'formRoleInsert'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter(self::inProgressEmitter(Access::receiverRoleInsert(), 'Rolle wird geladen...'));
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleFormEdit()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleEdit(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'formRoleEdit'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter(self::inProgressEmitter(Access::receiverRoleEdit(), 'Rolle wird geladen...'));
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @param bool $Loader
     * @param null|int $Id
     * @return Pipeline
     */
    public static function pipelineRoleFormSetup( $Loader = false, $Id = null)
    {
        $Emitter = new ServerEmitter(Access::receiverRoleSetup(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'formRoleSetup'
        ));
        if( $Id ) {
            $Emitter->setPostPayload(array(
                'Id' => $Id
            ));
        }
        $Pipeline = new Pipeline(false);
        if( $Loader ) {
            $Pipeline->appendEmitter(self::inProgressEmitter(Access::receiverRoleSetup(),
                'Rolle wird geladen...',
                'Daten werden vom Server abgerufen'
            ));
        }
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleFormDelete()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleDelete(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'formRoleDelete'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleActionDelete()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleDelete(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'actionRoleDelete'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleActionInsert()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleInsert(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'actionRoleInsert'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }


    /**
     * @return Pipeline
     */
    public static function pipelineRoleActionEdit()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleEdit(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'actionRoleEdit'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }
}