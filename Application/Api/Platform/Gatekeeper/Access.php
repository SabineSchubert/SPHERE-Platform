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

        $Dispatcher->registerMethod('callRoleStage');
        $Dispatcher->registerMethod('callRoleTable');

        $Dispatcher->registerMethod('callRoleFormInsert');
        $Dispatcher->registerMethod('callRoleFormEdit');
        $Dispatcher->registerMethod('callRoleFormSetup');
        $Dispatcher->registerMethod('callRoleFormDelete');

        $Dispatcher->registerMethod('callRoleActionInsert');
        $Dispatcher->registerMethod('callRoleActionEdit');
//        $Dispatcher->registerMethod('callRoleActionSetup');
        $Dispatcher->registerMethod('callRoleActionDelete');

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