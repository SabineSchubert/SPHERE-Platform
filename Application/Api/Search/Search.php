<?php
namespace SPHERE\Application\Api\Search;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Search\Search as SearchApp;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Close;

class Search implements IApplicationInterface, IApiInterface
{
    use ApiTrait;

    public static function registerApplication()
    {
        self::registerApi();
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('formSearch');

        return $Dispatcher->callMethod($Method);
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverSearchModal()
    {
        return (new ModalReceiver('Suche', new Close()))->setIdentifier( 'SearchModal' );
    }

    public static function pipelineOpenSearch()
    {
        $Emitter = new ServerEmitter( self::receiverSearchModal(), self::getEndpoint());
        $Emitter->setGetPayload(array(
            self::API_TARGET => 'formSearch'
        ));
        $Pipeline = new Pipeline(false);
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    public function formSearch()
    {
        return SearchApp::useFrontend()->formSearch();
    }
}