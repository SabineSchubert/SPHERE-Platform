<?php

namespace SPHERE\Application\Product\Car;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\Utility\Translation\TranslateTrait;
use SPHERE\Common\Frontend\Icon\Repository\Cars;

class Car implements IApplicationInterface
{
    use AppTrait, TranslateTrait;

    public static function registerApplication()
    {
        self::createApplication(
            __NAMESPACE__, __NAMESPACE__ . '\Frontend', 'frontendCar',
            'Pkw & smart',//                self::doTranslate(__METHOD__,'Pkw & smart'),
                new Cars()
        );

    }

    /**
     * @return IServiceInterface
     */
    public static function useService()
    {
        // TODO: Implement useService() method.
    }

    /**
     * @return Frontend
     */
    public static function useFrontend()
    {
        return new Frontend();
    }

}
