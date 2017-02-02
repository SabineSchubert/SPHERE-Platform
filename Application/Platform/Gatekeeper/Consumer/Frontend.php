<?php
namespace SPHERE\Application\Platform\Gatekeeper\Consumer;

use SPHERE\Application\Api\Platform\Gatekeeper\Consumer;
use SPHERE\Common\Frontend\Icon\Repository\Cluster;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Window\Stage;

/**
 * Class Frontend
 *
 * @package SPHERE\Application\System\Gatekeeper\Consumer
 */
class Frontend
{

    /**
     * @return Stage
     */
    public static function frontendConsumer()
    {

        $Stage = new Stage('Mandantenverwaltung', '', '');

        $TableReceiver = Consumer::receiverTableConsumer();
        $CreateReceiver = Consumer::receiverCreateConsumer();

        $CreatePipeline = Consumer::pipelineCreateConsumer($CreateReceiver);
        $TablePipeline = Consumer::pipelineTableConsumer($TableReceiver);

        $Stage->addButton(
            (new Standard('Mandant hinzufügen', Consumer::getEndpoint(), new Cluster()))
                ->ajaxPipelineOnClick($CreatePipeline)
        );

        $Stage->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(array(
                            $TableReceiver,
                            $CreateReceiver,
                            $TablePipeline
                        ))
                    )
                )
            )
        );

        return $Stage;
    }
}
