<?php
namespace SPHERE\Application\Example;

use SPHERE\Application\AppTrait;
use SPHERE\Application\Example\Download\Download;
use SPHERE\Application\Example\Upload\Upload;
use SPHERE\Application\IClusterInterface;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Window\Stage;

class Example implements IClusterInterface
{
    use AppTrait;

    public static function registerCluster()
    {
        self::createCluster(__NAMESPACE__,__CLASS__, 'frontendExample', 'Example', new Blackboard(), 'Beispiele');

        Upload::registerApplication();
        Download::registerApplication();
    }

    public function frontendExample()
    {
        $Stage = new Stage('Example', 'Beispiele');

        return $Stage;
    }
}