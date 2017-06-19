<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\Api\Platform\Gatekeeper\Access\Role;
use SPHERE\Application\IApiInterface;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;

class Access extends Role implements IApiInterface
{
    use ApiTrait;

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('loadRoleStage');
        $Dispatcher->registerMethod('loadRoleTable');

        $Dispatcher->registerMethod('formRoleInsert');
        $Dispatcher->registerMethod('formRoleEdit');
        $Dispatcher->registerMethod('formRoleSetup');
        $Dispatcher->registerMethod('formRoleDelete');

        $Dispatcher->registerMethod('actionRoleInsert');
        $Dispatcher->registerMethod('actionRoleEdit');
        $Dispatcher->registerMethod('actionRoleSetupEnable');
        $Dispatcher->registerMethod('actionRoleSetupDisable');
        $Dispatcher->registerMethod('actionRoleDelete');

        return $Dispatcher->callMethod($Method);
    }

    /**
     * @return BlockReceiver
     */
    public static function receiverStage()
    {

        $Receiver = new BlockReceiver();
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }
}