<?php
namespace SPHERE\Application\Api\Platform\Utility;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Common\Frontend\Ajax\Emitter\ClientEmitter;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\InlineBlockReceiver;
use SPHERE\Common\Frontend\Icon\Repository\EyeOpen;
use SPHERE\Common\Frontend\Icon\Repository\Heart;
use SPHERE\Common\Frontend\Icon\Repository\More;
use SPHERE\Common\Frontend\Icon\Repository\Star;
use SPHERE\Common\Frontend\Icon\Repository\StarEmpty;
use SPHERE\Common\Frontend\Link\Repository\Primary;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\System\Extension\Extension;

/**
 * Class Favorite
 * @package SPHERE\Application\Api\Platform\Utility
 */
class Favorite extends Extension implements IApiInterface
{
    use ApiTrait;

    public static function receiverFavoriteButton()
    {

        $Receiver = new InlineBlockReceiver();
        $Receiver->initContent(
            new Standard('', '#', new More())
        );
        return $Receiver;
    }

    public static function pipelineFavoriteButton(AbstractReceiver $Receiver)
    {
        $Emitter = new ServerEmitter($Receiver, Favorite::getEndpoint());
        $Emitter->setGetPayload(array(
            Favorite::API_TARGET => 'FavoriteButton',
            'Receiver' => $Receiver->getIdentifier()
        ));
        $Pipeline = new Pipeline();
        $LoadingEmitter = new ClientEmitter($Receiver, new Standard('', '#', new More()));
        $Pipeline->addEmitter($LoadingEmitter);
        $Pipeline->addEmitter($Emitter);

        return $Pipeline;
    }

    public function FavoriteButton()
    {
        // TODO: ;)
        $TblAccount = Account::useService()->getAccountBySession();
        if ($Tbl = (\SPHERE\Application\Platform\Utility\Favorite\Favorite::useService()->getFavoriteByRoute(
            $this->getRequest()->getPathInfo(), $TblAccount
        ))
        ) {
            return new Primary('',Favorite::getEndpoint(), new Star(), array(), 'Favorit&nbsp;entfernen');
        } else {
            return new Standard('',Favorite::getEndpoint(), new StarEmpty(), array(), 'Zu&nbsp;Favoriten');
        }
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('FavoriteButton');
//        $Dispatcher->registerMethod('addFavorite');
//        $Dispatcher->registerMethod('removeFavorite');

        return $Dispatcher->callMethod($Method);
    }

}