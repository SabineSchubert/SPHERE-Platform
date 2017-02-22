<?php
/**
 * Created by PhpStorm.
 * User: Kunze
 * Date: 22.02.2017
 * Time: 00:57
 */

namespace SPHERE\Application\Training\Youtube;


use SPHERE\Application\IApplicationInterface;
use SPHERE\Common\Frontend\Icon\Repository\Wrench;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Layout\Structure\Slick;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

class Youtube implements IApplicationInterface
{
    public static function registerApplication()
    {
        Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Youtube Channel'), new Link\Icon(new Wrench()))
        );
        Main::getDispatcher()->registerRoute(Main::getDispatcher()->createRoute(
            __NAMESPACE__, __CLASS__ . '::frontendDashboard'
        ));
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {
        $Stage = new Stage();

        $Stage->setTeaser(
            (new Slick())
                ->addContent(
                    '<div class="container-fluid">'
                    . '<div class="col-xs-1"></div>'
                    . '<div class="col-xs-10">'

                    .'<div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" src="//www.youtube.com/embed/P3y8vc-3iVU" allowfullscreen></iframe>
                    </div>'
                    .'</div>'
                    . '<div class="col-xs-1"></div>'
                    . '</div>'
                )
                ->addContent(
                    '<div class="container-fluid">'
                    . '<div class="col-xs-1"></div>'
                    . '<div class="col-xs-10">'

                    .'<div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" src="//www.youtube.com/embed/ZpCl5O6tTv8" allowfullscreen></iframe>
                    </div>'
                    .'</div>'
                    . '<div class="col-xs-1"></div>'
                    . '</div>'
                )

        );

        $Stage->setContent(
            new Layout(
                new LayoutGroup(array(
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            new Title('Bed Sheets', 'Simons Cat' ),
                            '<div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/P3y8vc-3iVU" allowfullscreen></iframe>
                            </div>'
                        ), 6),
                        new LayoutColumn(array(
                            new Title('Box Clever', 'Simons Cat' ),
                            '<div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="//www.youtube.com/embed/ZpCl5O6tTv8" allowfullscreen></iframe>
                            </div>'
                        ), 6),
                    ))
                ))
            )
        );

        return $Stage;
    }

}
