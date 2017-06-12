<?php

namespace SPHERE\Application\Api\Platform\Gatekeeper\Access;

use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Common\Frontend\Ajax\Emitter\ClientEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Template\CloseModal;
use SPHERE\Common\Frontend\Layout\Repository\Container;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\ProgressBar;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Text\Repository\Muted;
use SPHERE\System\Extension\Extension;

/**
 * Class Common
 * @package SPHERE\Application\Api\Platform\Gatekeeper\Access
 */
class Common extends Extension
{

    /**
     * @param AbstractReceiver $Receiver
     * @param string $Title
     * @param string $Description
     * @param int $Progress 0-100
     * @return ClientEmitter
     */
    protected static function inProgressEmitter(
        AbstractReceiver $Receiver,
        $Title,
        $Description = 'Daten werden vom Server abgerufen',
        $Progress = 0
    ) {
        return new ClientEmitter($Receiver,
            new Panel(
                $Title,
                array(
                    new ProgressBar($Progress, 100 - $Progress, 0, 6),
                    new Muted($Description)
                ),
                Panel::PANEL_TYPE_DEFAULT
            ));
    }

    /**
     * @param AbstractReceiver $CloseReceiver
     * @param Pipeline $ReloadPipeline
     * @param string $Message
     * @param string $Button
     * @return string
     */
    protected static function isMissingWarning(
        AbstractReceiver $CloseReceiver,
        Pipeline $ReloadPipeline,
        $Message,
        $Button = ''
    ) {

        return new Warning($Message)
            . new Container(
                (new Standard($Button ? $Button : 'Aktualisieren', Access::getEndpoint()))
                    ->ajaxPipelineOnClick(
                        $ReloadPipeline
                            ->appendEmitter((new CloseModal($CloseReceiver))->getEmitter())
                    )
            );
    }
}