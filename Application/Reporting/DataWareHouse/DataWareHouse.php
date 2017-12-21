<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 29.03.2017
 * Time: 10:32
 */

namespace SPHERE\Application\Reporting\DataWareHouse;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\Abcx;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\BasicData;
use SPHERE\Application\Reporting\DataWareHouse\MasterData\MasterData;
use SPHERE\Application\Reporting\DataWareHouse\Presto\Presto;
use SPHERE\Application\Reporting\DataWareHouse\Sales\Sales;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Database\Link\Identifier;

class DataWareHouse implements IApplicationInterface, IModuleInterface
{
    use AppTrait;

	public static function registerApplication()
	{
	    self::createApplication(__NAMESPACE__, __CLASS__, 'frontendImport', 'Import', new Blackboard(), 'Stammdaten');
//        self::registerModule();
        Presto::registerModule();
        Sales::registerModule();
        BasicData::registerModule();
	}

    public static function registerModule()
    {
        // TODO: Implement registerModule() method.
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Reporting', 'DataWareHouse', null, null, Consumer::useService()->getConsumerBySession()),
            __DIR__ . '/Service/Entity', __NAMESPACE__ . '\Service\Entity'
        );
    }

    /**
     * @return mixed
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }

    public function frontendImport()
    {
      $Stage = new Stage('Import', 'importieren');

      return $Stage;
    }
}