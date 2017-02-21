<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper\Access;

use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Close;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\ProgressBar;
use SPHERE\Common\Frontend\Text\Repository\Muted;

/**
 * Class Receiver
 * @package SPHERE\Application\Api\Platform\Gatekeeper\Access
 */
class Receiver extends Stage
{

    /**
     * @return BlockReceiver
     */
    public static function receiverStage()
    {

        $Receiver = new BlockReceiver();
        return $Receiver;
    }

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
    public static function receiverCreateLevel()
    {

        $Receiver = new ModalReceiver('Neues Zugriffslevel anlegen', new Close());
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

    /**
     * @return BlockReceiver
     */
    public static function receiverTableLevel()
    {

        $Receiver = new BlockReceiver();
        $Receiver->initContent(
            new Panel(
                'Zugriffslevel werden geladen...',
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
     * @return BlockReceiver
     */
    public static function receiverTablePrivilege()
    {

        $Receiver = new BlockReceiver();
        $Receiver->initContent(
            new Panel(
                'Privilegien werden geladen...',
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
     * @return BlockReceiver
     */
    public static function receiverTableRight()
    {

        $Receiver = new BlockReceiver();
        $Receiver->initContent(
            new Panel(
                'Rechte werden geladen...',
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
