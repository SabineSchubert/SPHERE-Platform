<?php

namespace SPHERE\Application\Api\Platform\Gatekeeper\Access;

use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\InlineReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Close;

/**
 * Class RoleReceiver
 * @package SPHERE\Application\Api\Platform\Gatekeeper\Access
 */
class RoleReceiver extends Level
{
    /**
     * @return BlockReceiver
     */
    public static function receiverRoleTable()
    {

        $Receiver = new BlockReceiver();
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }


    /**
     * @return ModalReceiver
     */
    public static function receiverRoleInsert()
    {

        $Receiver = new ModalReceiver('Neue Rolle anlegen', new Close());
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverRoleEdit()
    {

        $Receiver = new ModalReceiver('Rolle bearbeiten', new Close());
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverRoleSetup()
    {

        $Receiver = new ModalReceiver('Zugriffslevel zuweisen', new Close().self::receiverRoleSetupSwitch());
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }

    /**
     * @return InlineReceiver
     */
    public static function receiverRoleSetupSwitch()
    {

        $Receiver = new InlineReceiver();
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverRoleDelete()
    {

        $Receiver = new ModalReceiver('Rolle löschen', new Close());
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }
}