<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper\Access;

use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;

/**
 * Class Stage
 * @package SPHERE\Application\Api\Platform\Gatekeeper\Access
 */
class Stage extends Pipeline
{

    /**
     * @return Layout
     */
    public function stageRole()
    {
        return new Layout(array(
            new LayoutGroup(array(
                new LayoutRow(array(
                    new LayoutColumn(array(
                        ($Table = Access::receiverTableRole()),
                        ($Modal = Access::receiverCreateRole()),
                        (new Standard('Rolle anlegen', Access::getEndpoint(), null, array(
                            'TableReceiver' => $Table->getIdentifier()
                        )))->ajaxPipelineOnClick(Access::pipelineFormRole($Modal)),
                        Access::pipelineTableRole($Table)
                    )),
                )),
            ), new Title('Rollen')),
        ));
    }

    /**
     * @return Layout
     */
    public function stageLevel()
    {
        return new Layout(array(
            new LayoutGroup(array(
                new LayoutRow(array(
                    new LayoutColumn(array(
                        ($Table = Access::receiverTableLevel()),
                        ($Modal = Access::receiverCreateLevel()),
                        (new Standard('Zugriffslevel anlegen', Access::getEndpoint(), null, array(
                            'TableReceiver' => $Table->getIdentifier()
                        )))->ajaxPipelineOnClick(Access::pipelineCreateLevel($Modal)),
                        Access::pipelineTableLevel($Table)
                    )),
                )),
            ), new Title('Zugriffslevel')),
        ));
    }
}
