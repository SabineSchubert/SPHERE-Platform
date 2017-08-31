<?php
namespace SPHERE\Application\Platform\System;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Platform\System\Cache\Cache;
use SPHERE\Application\Platform\System\Database\Database;
use SPHERE\Application\Platform\System\Library\Library;
use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\Application\Platform\System\Session\Session;
use SPHERE\Application\Platform\System\Test\Test;
use SPHERE\Common\Frontend\Icon\Repository\Cog;
use SPHERE\Common\Frontend\Icon\Repository\Shield;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\ProgressBar;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

/**
 * Class System
 *
 * @package SPHERE\Application\Platform\System
 */
class System implements IApplicationInterface
{
    use AppTrait;

    public static function registerApplication()
    {

        /**
         * Register Module
         */
        Session::registerModule();
        Cache::registerModule();
        Database::registerModule();
        Protocol::registerModule();
        Library::registerModule();
        Test::registerModule();

        /**
         * Register Navigation
         */
        Main::getDisplay()->addApplicationNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('System'), new Link\Icon(new Cog()))
        );
        Main::getDispatcher()->registerRoute(Main::getDispatcher()->createRoute(
            __NAMESPACE__, __CLASS__ . '::frontendDashboard'
        ));

        //self::createApplication(__NAMESPACE__,__CLASS__,'frontendGatekeeper', 'System', new Shield());
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {

        $Stage = new Stage('Dashboard', 'System');

        $Stage->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(
                            self::widgetDrive()
                            , 4),
                        new LayoutColumn(
                            self::widgetMemory()
                            , 4),
                        new LayoutColumn(
                            self::widgetLoad()
                            , 4),
                    ))
                )
            )
        );

        return $Stage;
    }

    /**
     * @return Panel
     */
    public static function widgetDrive()
    {
        $Value = 100 / disk_total_space(__DIR__) * disk_free_space(__DIR__);

        return new Panel('Festplattenkapazität', array(
            (new ProgressBar($Value, (100 - $Value), 0))->setColor(ProgressBar::BAR_COLOR_SUCCESS,
                ProgressBar::BAR_COLOR_DANGER),
            'Gesamt: ' . number_format(disk_total_space(__DIR__), 0, ',', '.'),
            'Frei: ' . number_format(disk_free_space(__DIR__), 0, ',', '.')
        ));
    }

    /**
     * @return Panel
     */
    public static function widgetMemory()
    {
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        if (count($free_arr) > 1) {
            $mem = explode(" ", $free_arr[1]);
            $mem = array_filter($mem);
            $mem = array_merge($mem);
            $Value = $mem[2] / $mem[1] * 100;

            return new Panel('Speicherkapazität', array(
                (new ProgressBar($Value, (100 - $Value), 0))->setColor(ProgressBar::BAR_COLOR_SUCCESS,
                    ProgressBar::BAR_COLOR_DANGER),
                'Gesamt: ' . number_format($mem[1], 0, ',', '.'),
                'Frei: ' . number_format($mem[2], 0, ',', '.')
            ));
        } else {
            return new Panel('Speicherkapazität', array(
                (new ProgressBar(0, 0, 100))->setColor(ProgressBar::BAR_COLOR_SUCCESS, ProgressBar::BAR_COLOR_DANGER),
                'Genutzt: -NA-',
                'Frei: -NA-'
            ));
        }
    }

    /**
     * @return Panel
     */
    public static function widgetLoad()
    {

        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();

            return new Panel('Rechenkapazität', array(
                (new ProgressBar((50 * (2 - $load[0])), (50 * ($load[0])),
                    0))->setColor(ProgressBar::BAR_COLOR_SUCCESS, ProgressBar::BAR_COLOR_DANGER),
                'Genutzt: ' . number_format($load[0], 5, ',', '.'),
                'Frei: ' . number_format(2 - $load[0], 5, ',', '.')
            ));
        } else {
            return new Panel('Rechenkapazität', array(
                (new ProgressBar(0, 0, 100))->setColor(ProgressBar::BAR_COLOR_SUCCESS, ProgressBar::BAR_COLOR_DANGER),
                'Genutzt: -NA-',
                'Frei: -NA-'
            ));
        }
    }
}
