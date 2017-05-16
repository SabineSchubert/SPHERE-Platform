<?php
namespace SPHERE\Application\Example\Download;

use SPHERE\Application\AppTrait;
use SPHERE\Application\Example\Download\Excel\Excel;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;

class Download implements IApplicationInterface
{
    use AppTrait;

    public static function registerApplication()
    {
        self::createApplication(__NAMESPACE__, __CLASS__, 'frontendDownload', 'Download', new Blackboard(), 'Herunterladen');
        Excel::registerModule();
    }
}