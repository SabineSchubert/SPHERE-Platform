<?php
namespace SPHERE\Application\Platform\System\Session;

use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblSession;
use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\Application\Platform\System\Protocol\Service\Entity\TblProtocol;
use SPHERE\Common\Frontend\Icon\Repository\Group;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Table\Structure\TableData;
use SPHERE\Common\Frontend\Text\Repository\Muted;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Redirect;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

/**
 * Class Session
 *
 * @package SPHERE\Application\Platform\System\Session
 */
class Session extends Extension implements IModuleInterface
{

    public static function registerModule()
    {

        Main::getDisplay()->addModuleNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name(' Aktive Sessions'), new Link\Icon(new Group()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__,
                __CLASS__ . '::frontendSession'
            )
        );
    }

    /**
     * @return IServiceInterface
     */
    public static function useService()
    {

    }

    /**
     * @return IFrontendInterface
     */
    public static function useFrontend()
    {

    }

    /**
     * @return Stage
     */
    public function frontendSession()
    {

        $Stage = new Stage('Aktive Sessions', 'der aktuell angemeldete Benutzer');

        $Result = array();

        $TblSessionAll = Account::useService()->getSessionAll();
        if ($TblSessionAll) {
            array_walk($TblSessionAll, function (TblSession $TblSession) use (&$Result) {

                $TblAccount = $TblSession->getTblAccount();

                if ($TblSession->getEntityUpdate() && $TblSession->getEntityCreate()) {
                    $Interval = $TblSession->getEntityUpdate()->getTimestamp() - $TblSession->getEntityCreate()->getTimestamp();
                } else {
                    if (!$TblSession->getEntityUpdate() && $TblSession->getEntityCreate()) {
                        $Interval = time() - $TblSession->getEntityCreate()->getTimestamp();
                    } else {
                        $Interval = 0;
                    }
                }

                if (($Activity = Protocol::useService()->getProtocolLastActivity($TblAccount))) {
                    $Activity = current($Activity)->getEntityCreate();
                } else {
                    $Activity = '-NA-';
                }


                array_push($Result, array(
                    'Id' => $TblSession->getId(),
                    'Consumer' => ($TblAccount->getServiceTblConsumer() ?
                        $TblAccount->getServiceTblConsumer()->getAcronym()
                        . '&nbsp;' . new Muted($TblAccount->getServiceTblConsumer()->getName())
                        : '-NA-'
                    ),
                    'Account' => ($TblAccount ? $TblAccount->getUsername() : '-NA-'),
                    'TTL' => gmdate("H:i:s", $TblSession->getTimeout() - time()),
                    'ActiveTime' => gmdate('H:i:s', $Interval),
                    'LoginTime' => $TblSession->getEntityCreate(),
                    'LastAction' => $Activity,
                    'Identifier' => strtoupper($TblSession->getSession())
                ));

            });
        }

        $History = array();

        $TblProtocolAll = Protocol::useService()->getProtocolAllCreateSession();
        if ($TblProtocolAll) {
            array_walk($TblProtocolAll, function (TblProtocol $TblProtocol) use (&$History) {

                array_push($History, array(
                    'Consumer' => $TblProtocol->getConsumerAcronym() . '&nbsp;' . new Muted($TblProtocol->getConsumerName()),
                    'LoginTime' => $TblProtocol->getEntityCreate(),
                    'Account' => $TblProtocol->getAccountUsername(),
                    'AccountId' => ($TblProtocol->getServiceTblAccount() ? $TblProtocol->getServiceTblAccount()->getId() : '-NA-')
                ));

            });
        }

        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(array(
                            new TableData($Result, null, array(
                                'Id' => '#',
                                'Consumer' => 'Mandant',
                                'Account' => 'Benutzer',
                                'LoginTime' => 'Anmeldung',
                                'LastAction' => 'Aktivität',
                                'ActiveTime' => 'Dauer',
                                'TTL' => 'Timeout',
                                'Identifier' => 'Session'
                            )),
                        ))
                    ), new Title('Aktive Benutzer')
                ),
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(array(
                            new TableData($History, null, array(
                                'LoginTime' => 'Zeitpunkt',
                                'AccountId' => 'Account',
                                'Account' => 'Benutzer',
                                'Consumer' => 'Mandant',
                            ), array(
                                'order' => array(array(0, 'desc')),
                                'columnDefs' => array(
                                    array('type' => 'de_datetime', 'width' => '10%', 'targets' => 0),
                                    array('width' => '45%', 'targets' => 2),
                                    array('width' => '45%', 'targets' => 3)
                                )
                            )),
                            new Redirect(
                                '/Platform/System/Session', 30
                            )
                        ))
                    ), new Title('Protokoll der Anmeldungen')
                )
            ))
        );

        return $Stage;
    }
}
