<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper\Access\Role;

use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Close;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\ProgressBar;
use SPHERE\Common\Frontend\Text\Repository\Muted;

/**
 * Class Receiver
 * @package SPHERE\Application\Api\Platform\Gatekeeper\Access\Role
 */
class Receiver
{
    /**
     * @return ModalReceiver
     */
    public static function receiverCreateRole()
    {

        $Receiver = new ModalReceiver('Neue Rolle anlegen', new Close());
        return $Receiver;
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverEditRole()
    {

        $Receiver = new ModalReceiver('Rolle bearbeiten', new Close());
        return $Receiver;
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
}