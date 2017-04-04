<?php
namespace SPHERE\Application\Platform\Utility;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Platform\Utility\Favorite\Favorite;
use SPHERE\Application\Platform\Utility\Transfer\Transfer;
use SPHERE\Application\Platform\Utility\Translation\Translation;
use SPHERE\Common\Frontend\Icon\Repository\Wrench;
use SPHERE\Common\Window\Stage;

/**
 * Class Utility
 * @package SPHERE\Application\Platform\Utility
 */
class Utility implements IApplicationInterface
{
    use AppTrait;

    public static function registerApplication()
    {
        Translation::registerModule();
        Favorite::registerModule();
        Transfer::registerModule();

        self::createApplication( __NAMESPACE__, __CLASS__, 'frontendDashboard', 'Dienstprogramm', new Wrench() );
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {

        $Stage = new Stage('Dashboard', 'Dienstprogramm');

        return $Stage;
    }
}