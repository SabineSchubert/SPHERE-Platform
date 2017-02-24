<?php
namespace SPHERE\Application\Training;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IClusterInterface;
use SPHERE\Application\Training\Akubis\Akubis;
use SPHERE\Application\Training\Offensive\Offensive;
use SPHERE\Application\Training\Youtube\Youtube;
use SPHERE\Common\Frontend\Icon\Repository\Education;
use SPHERE\Common\Window\Stage;

/**
 * Class Training
 * @package SPHERE\Application\Training
 */
class Training implements IClusterInterface
{
    use AppTrait;

    public static function registerCluster()
    {
        self::createCluster(
            __NAMESPACE__, __CLASS__, 'frontendDashboard', 'Schulungen', new Education()
        );
        Offensive::registerApplication();
        Youtube::registerApplication();
        Akubis::registerApplication();
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {
        $Stage = new Stage('Schulungen');
        $Stage->setMessage('');
        return $Stage;
    }

}
