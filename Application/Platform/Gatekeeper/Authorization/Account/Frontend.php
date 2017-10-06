<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Account;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAuthorization;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblIdentification;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\PasswordField;
use SPHERE\Common\Frontend\Form\Repository\Field\RadioBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Exclamation;
use SPHERE\Common\Frontend\Icon\Repository\Lock;
use SPHERE\Common\Frontend\Icon\Repository\Person;
use SPHERE\Common\Frontend\Icon\Repository\Remove;
use SPHERE\Common\Frontend\Icon\Repository\Repeat;
use SPHERE\Common\Frontend\Layout\Repository\Listing;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Link\Repository\Danger;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Repository\Title;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Small;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Repository\Debugger;

/**
 * Class Frontend
 *
 * @package SPHERE\Application\System\Gatekeeper\Authorization\Account
 */
class Frontend
{

    /**
     * @param null $Account
     * @return Stage
     */
    public function frontendAccount($Account = null)
    {

        $Stage = new Stage('Benutzerkonten');
        $Stage->hasUtilityFavorite(true);

        $TblAccount = Account::useService()->getAccountBySession();
        if ($TblAccount) {
            $isSystem = Account::useService()->hasAuthorization(
                $TblAccount, Access::useService()->getRoleByName('Administrator')
            );
        } else {
            $isSystem = false;
        }
        $TblConsumer = Consumer::useService()->getConsumerBySession();

        // Identification
        $TblIdentificationAll = Account::useService()->getIdentificationAll();
        if ($TblIdentificationAll) {
            /** @noinspection PhpUnusedParameterInspection */
            array_walk($TblIdentificationAll, function (TblIdentification &$TblIdentification, $Index, $isSystem) {

                if ($TblIdentification->getName() == 'System' && !$isSystem) {
                    $TblIdentification = false;
                } else {
                    $TblIdentification = new RadioBox(
                        'Account[Identification]', $TblIdentification->getDescription(), $TblIdentification->getId()
                    );
                }
            }, $isSystem);
            $TblIdentificationAll = array_filter($TblIdentificationAll);
        } else {
            $TblIdentificationAll = array();
        }

        // Role
        $TblRoleAll = Access::useService()->getRoleAll();
        if ($TblRoleAll) {
            /** @noinspection PhpUnusedParameterInspection */
            array_walk($TblRoleAll, function (TblRole &$TblRole, $Index, $isSystem) {

                if($TblRole->isInternal()) {
                    $TblRole = false;
                }
                else {

                    if ($TblRole->getName() == 'Administrator' && !$isSystem) {
                        $TblRole = false;
                    } else {
                        $TblRole = new CheckBox('Account[Role][' . $TblRole->getId() . ']', $TblRole->getName(),
                            $TblRole->getId());
                    }
                }
            }, $isSystem);
            $TblRoleAll = array_filter($TblRoleAll);
        } else {
            $TblRoleAll = array();
        }

        $FormAccount = new Form(array(
            new FormGroup(array(
                new FormRow(array(
                    new FormColumn(
                        (new TextField('Account[Name]', 'Benutzername', 'Benutzername', new Person()))
                            ->setPrefixValue($TblConsumer->getAcronym())->setAutoFocus()->setRequired()
                        , 4),
                    new FormColumn(
                        (new PasswordField(
                            'Account[Password]', 'Passwort', 'Passwort', new Lock()
                        ))->setRequired(), 4),
                    new FormColumn(
                        (new PasswordField(
                            'Account[PasswordSafety]', 'Passwort wiederholen', 'Passwort wiederholen',
                            new Repeat()
                        ))->setRequired(), 4),
                )),
            ), new \SPHERE\Common\Frontend\Form\Repository\Title('Benutzerkonto anlegen')),
            new FormGroup(array(
                new FormRow(array(
                    new FormColumn(array(
                        new Panel('Authentifizierungstyp', $TblIdentificationAll)
                    ), 4),
                    new FormColumn(array(
                        new Panel('Berechtigungsstufe', $TblRoleAll)
                    ), 4),
                ))

            ), new \SPHERE\Common\Frontend\Form\Repository\Title('Berechtigungen zuweisen')),
        ), new Primary('Hinzufügen'));

        if($Account) {
            $FormAccount = Account::useService()->createAccount($FormAccount, $Account);
        }

        // Account
        $TblAccountAll = Account::useService()->getAccountAll();
        $TableContent = array();
        if ($TblAccountAll) {
            array_walk($TblAccountAll, function (TblAccount &$TblAccount) use(&$TableContent) {

                if (
                    ( $TblAccount->getServiceTblIdentification()
                        && ( $TblAccount->getServiceTblIdentification()->getId() == Account::useService()->getIdentificationByName('Saml')->getId()
                            or $TblAccount->getUsername() == 'schnand' or $TblAccount->getUsername() == 'Test'
                        )
                    )
                    && $TblAccount->getServiceTblConsumer()
                    && $TblAccount->getServiceTblConsumer()->getId() == Consumer::useService()->getConsumerBySession()->getId()
                ) {

                    $TblAuthorizationAll = Account::useService()->getAuthorizationAllByAccount($TblAccount);
                    if ($TblAuthorizationAll) {
                        array_walk($TblAuthorizationAll, function (TblAuthorization &$tblAuthorization) {

                            if ($tblAuthorization->getServiceTblRole()) {
                                $tblAuthorization = $tblAuthorization->getServiceTblRole()->getName();
                            } else {
                                $tblAuthorization = false;
                            }
                        });
                        $tblAuthorizationAll = array_filter($TblAuthorizationAll);
                    }

                    $Item['Authentication'] = new Listing(array($TblAccount->getServiceTblIdentification() ? $TblAccount->getServiceTblIdentification()->getDescription() : ''));
                    $Item['Authorization'] = new Listing(!empty($tblAuthorizationAll) ? $tblAuthorizationAll
                        : array(new \SPHERE\Common\Frontend\Text\Repository\Danger(new Exclamation() . new Small(' Keine Berechtigungen vergeben')))
                    );


                    /** @noinspection PhpUndefinedFieldInspection */
                    $Item['Option'] = new Danger('Löschen',
                        '/Platform/Gatekeeper/Authorization/Account/Destroy',
                        new Remove(), array('Id' => $TblAccount->getId()), 'Löschen'
                    );
                    $Item['UserName'] = $TblAccount->getUsername();
                    array_push($TableContent, $Item);
                }
            });
        }

        $Stage->setContent(
            //Account::useService()->createAccount(
            $FormAccount.'<br/>'
            //, $Account)
            .((!empty($TableContent))? new Table($TableContent, new Title('Bestehende Benutzerkonten'), array(
                                'UserName' => 'Benutzername',
                                'Authentication' => 'Login-Typ',
                                'Authorization' => 'Berechtigung',
                                'Option' => 'Optionen'
                            )
                        )
                            : new Warning('Keine Benutzerkonten vorhanden')
                        )
        );
        return $Stage;
    }
}
