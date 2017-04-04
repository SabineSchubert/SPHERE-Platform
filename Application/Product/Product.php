<?php
namespace SPHERE\Application\Product;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IClusterInterface;
use SPHERE\Application\Product\Car\Car;
use SPHERE\Application\Product\Classic\Classic;
use SPHERE\Application\Product\Truck\Truck;
use SPHERE\Common\Frontend\Icon\Repository\Wheel;
use SPHERE\Common\Window\Stage;

class Product implements IClusterInterface
{
    use AppTrait;

    public static function registerCluster()
    {
        self::createCluster(__NAMESPACE__, __CLASS__, 'frontendDashboard', 'Produkte', new Wheel(),
            'Disposition<br/>Preisliste<br/>Angebotsblatt'
        );

        Car::registerApplication();
        Classic::registerApplication();
        Truck::registerApplication();
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {
        $Stage = new Stage('Preisliste');
        $Stage->setMessage('');
        return $Stage;
    }

}
