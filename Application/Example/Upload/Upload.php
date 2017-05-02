<?php

namespace SPHERE\Application\Example\Upload;

use SPHERE\Application\AppTrait;
use SPHERE\Application\Example\Upload\Excel\Excel;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Window\Stage;

class Upload implements IApplicationInterface
{
    use AppTrait;

    public static function registerApplication()
    {
        self::createApplication(__NAMESPACE__, __CLASS__, 'frontendUpload', 'Upload', new Blackboard(), 'Hochladen');
        Excel::registerModule();
    }

    public function frontendUpload()
    {
        $Stage = new Stage('Upload', 'Hochladen');

        return $Stage;
    }
}