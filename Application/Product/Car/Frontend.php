<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\Product\Car;


use SPHERE\Application\Api\Product\Filter\Filter;
use SPHERE\Application\Platform\Utility\Translation\TranslateTrait;
use SPHERE\Application\Platform\Utility\Translation\TranslationTrait;
use SPHERE\Common\Frontend\Icon\Repository\Cog;
use SPHERE\Common\Frontend\Icon\Repository\CogWheels;
use SPHERE\Common\Frontend\Icon\Repository\Wrench;
use SPHERE\Common\Frontend\Layout\Repository\Container;
use SPHERE\Common\Frontend\Layout\Repository\PullClear;
use SPHERE\Common\Frontend\Layout\Repository\PullLeft;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Small;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
    use TranslateTrait;

    public function frontendCar()
    {
        $Stage = new Stage('Preisliste', 'Pkw & smart');
        $Stage->hasUtilityFavorite(true);

        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(array(
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            $ReceiverFilterSetup = Filter::receiverFilterSetup(),
                            new Title(
                                new PullClear(
                                    new PullLeft('Filter ')
                                    .new PullRight(
                                        ( new Link('', Filter::getEndpoint(), new CogWheels()) )
//                                        ->ajaxPipelineOnClick(Filter::pipelineFilterSetup($ReceiverFilterSetup))
                                    )
                                )
                            ),
                        ))
                    )),
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            (new Filter())->layoutProductFilter()
//                            $ReceiverProductFilter = Filter::receiverProductFilter(),
//                            Filter::pipelineProductFilter( $ReceiverProductFilter ),
                        )),
                    )),
                )),
                new LayoutGroup(
                    new LayoutRow(array(
//                        new LayoutColumn(array(
//                            $ReceiverFilterSetup = Filter::receiverFilterSetup(),
//                            new Title(
//                                new PullClear(
//                                    new PullLeft('Filter ')
//                                    .new PullRight(
//                                        ( new Link(' ', Filter::getEndpoint(), new Cog()) )
//                                        ->ajaxPipelineOnClick(Filter::pipelineFilterSetup($ReceiverFilterSetup))
//                                    )
//                                )
//                            ),
//                            $ReceiverProductFilter = Filter::receiverProductFilter(),
//                            Filter::pipelineProductFilter( $ReceiverProductFilter ),
//                        ), 2),
                        new LayoutColumn(array(
                            '<br/>',
                            new Title('Produkte'),
                            (new Filter())->layoutProductList()
//                            $ReceiverProductList = Filter::receiverProductList(),
//                            Filter::pipelineProductList( $ReceiverProductList ),
                        ), 12),
                    ))
                ),
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            new Container('&nbsp;'),
                            new Table(array(),null,array('A','B','C'))
                        ))
                    ))
                ),
        )));

        return $Stage;
    }
}
