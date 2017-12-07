<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 05.12.2017
 * Time: 11:50
 */

namespace SPHERE\Application\Api\Import\BasicData;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\System\Extension\Extension;

class ProductManagerDelete extends Extension implements IApiInterface
{
    use ApiTrait;

    /**
     * @param $ProductManagerId
     * @return Pipeline
     */
    public static function pipelineProductManagerDelete( $ProductManagerId ) {
        $ServerEmitter = new ServerEmitter( ProductManagerTable::TableBlockReceiver(), self::getEndpoint() );
        $ServerEmitter->setGetPayload(array(
            self::API_TARGET => 'deleteProductManager',
            'ProductManagerId' => $ProductManagerId
        ));

        $Pipeline = new Pipeline(false);
        $Pipeline->appendEmitter($ServerEmitter);

        return $Pipeline;
    }

    /**
     * @param $ProductManagerId
     * @return Pipeline
     */
    public function deleteProductManager($ProductManagerId) {

        //löschen
        if($ProductManagerId) {
            DataWareHouse::useService()->deleteProductManager( $ProductManagerId );
        }

        return ProductManagerTable::pipelineProductManagerTable();
    }

    /**
     * @param null $Content
     * @return BlockReceiver
     */
    public static function BlockReceiver( $Content = null ) {
        return (new BlockReceiver($Content))->setIdentifier('PositionDelete');
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('deleteProductManager');

        return $Dispatcher->callMethod($Method);
    }
}