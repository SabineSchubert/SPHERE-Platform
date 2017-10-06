<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 06.10.2017
 * Time: 09:30
 */

namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Destroy;


use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAuthorization;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblSession;
use SPHERE\Common\Frontend\Icon\Repository\Disable;
use SPHERE\Common\Frontend\Icon\Repository\Lock;
use SPHERE\Common\Frontend\Icon\Repository\Nameplate;
use SPHERE\Common\Frontend\Icon\Repository\Ok;
use SPHERE\Common\Frontend\Icon\Repository\PersonKey;
use SPHERE\Common\Frontend\Icon\Repository\Question;
use SPHERE\Common\Frontend\Icon\Repository\Success;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Danger;
use SPHERE\Common\Window\Redirect;
use SPHERE\Common\Window\Stage;

class Frontend
{

    /**
    * @param int  $Id
    * @param bool $Confirm
    *
    * @return Stage
    */
    public function frontendDestroyAccount($Id, $Confirm = false)
    {

        $Stage = new Stage('Benutzerkonto', 'Löschen');
        if ($Id) {
            $tblAccount = Account::useService()->getAccountById($Id);
            if (!$Confirm) {

                $Content = array(
                    $tblAccount->getUsername(),
                    ( $tblAccount->getServiceTblIdentification() ? new Lock().' '.$tblAccount->getServiceTblIdentification()->getDescription() : '' )
                );

                $tblAuthorizationAll = Account::useService()->getAuthorizationAllByAccount($tblAccount);
                if ($tblAuthorizationAll) {
                    array_walk($tblAuthorizationAll, function (TblAuthorization &$tblAuthorization) {

                        if ($tblAuthorization->getServiceTblRole()) {
                            $tblAuthorization = new Nameplate().' '.$tblAuthorization->getServiceTblRole()->getName();
                        } else {
                            $tblAuthorization = false;
                        }
                    });
                    $tblAuthorizationAll = array_filter(( $tblAuthorizationAll ));
                    $Content = array_merge($Content, $tblAuthorizationAll);
                }

                $Stage->setContent(
                    new Layout(new LayoutGroup(new LayoutRow(new LayoutColumn(array(
                        new Panel(new PersonKey().' Benutzerkonto', $Content, Panel::PANEL_TYPE_SUCCESS),
                        new Panel(new Question().' Dieses Benutzerkonto wirklich löschen?', array(),
                            Panel::PANEL_TYPE_DANGER,
                            new Standard(
                                'Ja', '/Platform/Gatekeeper/Authorization/Account/Destroy', new Ok(),
                                array('Id' => $Id, 'Confirm' => true)
                            )
                            .new Standard(
                                'Nein', '/Platform/Gatekeeper/Authorization/Account', new Disable()
                            )
                        )
                    )))))
                );
            } else {

                // Remove Session
                $tblSessionAll = Account::useService()->getSessionAllByAccount($tblAccount);
                if (!empty( $tblSessionAll )) {
                    /** @var TblSession $tblSession */
                    foreach ($tblSessionAll as $tblSession) {
                        Account::useService()->destroySession(null, $tblSession->getSession());
                    }
                }

                // Remove Authentication
                $tblAuthentication = Account::useService()->getAuthenticationByAccount($tblAccount);
                if (!empty( $tblAuthentication )) {
                    Account::useService()->removeAccountAuthentication($tblAccount,
                        $tblAuthentication->getTblIdentification());
                }

                // Remove Authorization
                $tblAuthorizationAll = Account::useService()->getAuthorizationAllByAccount($tblAccount);
                if (!empty( $tblAuthorizationAll )) {
                    /** @var TblAuthorization $tblAuthorization */
                    foreach ($tblAuthorizationAll as $tblAuthorization) {
                        if ($tblAuthorization->getServiceTblRole()) {
                            Account::useService()->removeAccountAuthorization($tblAccount,
                                $tblAuthorization->getServiceTblRole());
                        }
                    }
                }

                $Stage->setContent(
                    new Layout(new LayoutGroup(array(
                        new LayoutRow(new LayoutColumn(array(
                            ( Account::useService()->destroyAccount($tblAccount)
                                ? new \SPHERE\Common\Frontend\Message\Repository\Success('Das Benutzerkonto wurde gelöscht')
                                : new Danger('Das Benutzerkonto konnte nicht gelöscht werden')
                            ),
                            new Redirect('/Platform/Gatekeeper/Authorization/Account', Redirect::TIMEOUT_SUCCESS)
                        )))
                    )))
                );
            }
        } else {
            $Stage->setContent(
                new Layout(new LayoutGroup(array(
                    new LayoutRow(new LayoutColumn(array(
                        new Danger('Das Benutzerkonto konnte nicht gefunden werden'),
                        new Redirect('/Platform/Gatekeeper/Authorization/Account', Redirect::TIMEOUT_ERROR)
                    )))
                )))
            );
        }
        return $Stage;
    }

}