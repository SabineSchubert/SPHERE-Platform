<?php
namespace SPHERE\Application\Platform\Gatekeeper\Consumer;

use SPHERE\Application\Api\Platform\Gatekeeper\Consumer as ConsumerApi;
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

        $Stage = new Stage('Mandantenverwaltung');

        $TableReceiver = ConsumerApi::receiverTableConsumer();
        $CreateReceiver = ConsumerApi::receiverCreateConsumer();

        $CreatePipeline = ConsumerApi::pipelineCreateConsumer($CreateReceiver);
        $TablePipeline = ConsumerApi::pipelineTableConsumer($TableReceiver);

        $Stage->addButton(
            (new Standard('Mandant hinzufügen', ConsumerApi::getEndpoint(), new Cluster()))
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
