<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:32
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Presto;


use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Reporting\DataWareHouse\Presto\Service;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\System\Database\Link\Identifier;

class Presto implements IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        self::createModule(__NAMESPACE__, __NAMESPACE__.'\Frontend', 'frontendImportPresto', 'Presto-Import', new Blackboard(), 'Prestodaten-Import');
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Reporting', 'DataWareHouse', 'Presto', null, null), //Consumer::useService()->getConsumerBySession()
            __DIR__ . '/Service/Entity', __NAMESPACE__ . '\Service\Entity'
        );
    }

    /**
     * @return Frontend
     */
    public static function useFrontend()
    {
        return new Frontend();
    }
}