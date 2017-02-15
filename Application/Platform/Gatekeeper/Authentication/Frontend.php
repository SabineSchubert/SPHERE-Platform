<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authentication;

use MOC\V\Core\FileSystem\FileSystem;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\System\Database\Database;
use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\PasswordField;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\ChevronRight;
use SPHERE\Common\Frontend\Icon\Repository\FAAngleRight;
use SPHERE\Common\Frontend\Icon\Repository\Hospital;
use SPHERE\Common\Frontend\Icon\Repository\Lock;
use SPHERE\Common\Frontend\Icon\Repository\Person;
use SPHERE\Common\Frontend\Icon\Repository\Shield;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Thumbnail;
use SPHERE\Common\Frontend\Layout\Repository\Well;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Layout\Structure\Slick;
use SPHERE\Common\Frontend\Layout\Structure\Teaser;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Info;
use SPHERE\Common\Frontend\Message\Repository\Success;
use SPHERE\Common\Frontend\Text\Repository\Danger;
use SPHERE\Common\Window\Error;
use SPHERE\Common\Window\Redirect;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Saml\Saml;
use SPHERE\System\Saml\Type\SamlEnvironment;

/**
 * Class Frontend
 *
 * @package SPHERE\Application\System\Gatekeeper\Authentication
 */
class Frontend extends Extension implements IFrontendInterface
{

    /**
     * @return Stage
     */
    public function frontendWelcome()
    {

        $Stage = new Stage();

        $Stage->setTeaser(
            (new Slick())
                ->addImage('/Common/Style/Resource/Teaser/4260479090780-irgendwas-ist-immer.jpg')
                ->addImage('/Common/Style/Resource/Teaser/00-mercedes-benz-design-aesthetics-a-1280-686-848x454.jpg')
                ->addImage('/Common/Style/Resource/Teaser/00-mercedes-benz-design-e-klasse-coupe-c-238-edition-1-amg-line-1280x686-1-848x454.jpg')
                ->addImage('/Common/Style/Resource/Teaser/00-mercedes-benz-design-skizze-van-nutzfahrzeug-truck-1280x686-848x454.jpg')
                ->addImage('/Common/Style/Resource/Teaser/00-mercedes-benz-fahrzeuge-50-jahre-amg-mercedes-amg-gt-c-190-1280x686-2-848x454.jpg')
            /*
            (new Teaser())
                ->addItem(
                    '/Common/Style/Resource/Teaser/00-mercedes-benz-design-aesthetics-a-1280-686-848x454.jpg', 'Überschrift',
                    new Standard('Link', '#', new FAAngleRight()), 'Beschreibung', 'Titel', true
                )
                ->addItem(
                    '/Common/Style/Resource/Teaser/00-mercedes-benz-design-e-klasse-coupe-c-238-edition-1-amg-line-1280x686-1-848x454.jpg', 'Überschrift',
                    new Standard('Link', '#'), 'Beschreibung', 'Titel'
                )
                ->addItem(
                    '/Common/Style/Resource/Teaser/00-mercedes-benz-design-skizze-van-nutzfahrzeug-truck-1280x686-848x454.jpg', 'Überschrift',
                    new Standard('Link', '#'), 'Beschreibung', 'Titel'
                )
                ->addItem(
                    '/Common/Style/Resource/Teaser/00-mercedes-benz-fahrzeuge-50-jahre-amg-mercedes-amg-gt-c-190-1280x686-2-848x454.jpg', 'Überschrift',
                    new Standard('Link', '#'), 'Beschreibung', 'Titel'
                )*/
        );

        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(array(
                    new LayoutRow(array(
                        new LayoutColumn(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-design-aesthetics-a-1280-686-848x454.jpg'),
                                'Titel',
                                'Beschreibung',
                                array(
                                    new Link('Erfahren Sie mehr...', '#')
                                )
                            )
                            , 3),
                        new LayoutColumn(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-design-e-klasse-coupe-c-238-edition-1-amg-line-1280x686-1-848x454.jpg'),
                                'Titel',
                                'Beschreibung',
                                array(
                                    new Link('Erfahren Sie mehr...', '#')
                                )
                            )
                            , 3),
                        new LayoutColumn(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-design-skizze-van-nutzfahrzeug-truck-1280x686-848x454.jpg'),
                                'Titel',
                                'Beschreibung',
                                array(
                                    new Link('Erfahren Sie mehr...', '#')
                                )
                            )
                            , 3),
                        new LayoutColumn(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-fahrzeuge-50-jahre-amg-mercedes-amg-gt-c-190-1280x686-2-848x454.jpg'),
                                'Titel',
                                'Beschreibung',
                                array(
                                    new Link('Erfahren Sie mehr...', '#')
                                )
                            )
                            , 3),
                    )),
                    new LayoutRow(array(
                        new LayoutColumn(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-design-aesthetics-a-1280-686-848x454.jpg'),
                                'Titel',
                                'Beschreibung',
                                array(
                                    new Standard('Link', '#')
                                )
                            )
                            , 3),
                        new LayoutColumn(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-design-e-klasse-coupe-c-238-edition-1-amg-line-1280x686-1-848x454.jpg'),
                                'Titel',
                                'Beschreibung',
                                array(
                                    new Standard('Link', '#')
                                )
                            )
                            , 3),
                        new LayoutColumn(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-design-skizze-van-nutzfahrzeug-truck-1280x686-848x454.jpg'),
                                'Titel',
                                'Beschreibung',
                                array(
                                    new Standard('Link', '#')
                                )
                            )
                            , 3),
                        new LayoutColumn(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-fahrzeuge-50-jahre-amg-mercedes-amg-gt-c-190-1280x686-2-848x454.jpg'),
                                'Titel',
                                'Beschreibung',
                                array(
                                    new Standard('Link', '#')
                                )
                            )
                            , 3),
                    ))
                )),
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(
                            $this->getCleanLocalStorage()
                        )
                    )
                )
            ))
        );

        return $Stage;
    }

    /**
     * @return string
     */
    private function getCleanLocalStorage()
    {

        return '<script language=javascript>
            //noinspection JSUnresolvedFunction
            executeScript(function()
            {
                Client.Use("ModCleanStorage", function()
                {
                    jQuery().ModCleanStorage();
                });
            });
        </script>';
    }

    /**
     * @return Stage
     */
    public function frontendIdentificationSaml()
    {
        $Stage = new Stage('Anmeldung', 'SAML');

        ob_start();
        $SamlAuth = (new Saml(new SamlEnvironment()))->getSaml()->getEnvironment();

        $Global = $this->getGlobal();

        if (isset($Global->POST['SAMLResponse'])) {
            $SamlAuth->processResponse();
            if (!$SamlAuth->isAuthenticated()) {
                $Stage->setContent(new Error(1, 'SSO-Validation fails. SAMLResponse available but not valid.', false));
                return $Stage;
            }
        }

        if (!$SamlAuth->isAuthenticated()) {
            // redirect to daimler
            $SamlAuth->login();
        } else {
            $SamlAccountName = $SamlAuth->getNameId();
            $TblAccount = Account::useService()->getAccountByUsername($SamlAccountName);
            if (!$TblAccount) {
                $Stage->setContent(new Error(2, 'SSO-Validation fails. SAMLResponse available but not valid.', false));
                return $Stage;
            } else {
                // Login/Create session
                Account::useService()->createSession($TblAccount);
                $Stage->setContent(
                    new Success('Anmeldung erfolgreich', new \SPHERE\Common\Frontend\Icon\Repository\Success())
                    . new Redirect('/', Redirect::TIMEOUT_SUCCESS)
                );
                return $Stage;
            }
        }

        return $Stage;
    }

    /**
     * @param null|string $CredentialName
     * @param null|string $CredentialLock
     * @param null|string $CredentialKey
     *
     * @return Stage
     */
    public function frontendIdentification($CredentialName = null, $CredentialLock = null, $CredentialKey = null)
    {

        if ($CredentialName !== null) {
            Protocol::useService()->createLoginAttemptEntry($CredentialName, $CredentialLock, $CredentialKey);
        }

        $Stage = new Stage('Anmeldung');
        $Stage->setMessage('Bitte geben Sie Ihre Benutzerdaten ein');

        // Get Identification-Type (Credential,..)
        $Identification = Account::useService()->getIdentificationByName('Credential');

        if (!$Identification) {
            $Protocol = (new Database())->frontendSetup(false, true);

            $Stage = new Stage(new Danger(new Hospital()) . ' Installation', 'Erster Aufruf der Anwendung');
            $Stage->setMessage('Dieser Schritt wird automatisch ausgeführt wenn die Datenbank nicht die notwendigen Einträge aufweist. Üblicherweise beim ersten Aufruf.');
            $Stage->setContent(
                new Layout(
                    new LayoutGroup(
                        new LayoutRow(
                            new LayoutColumn(array(
                                new Panel('Was ist das?', array(
                                    (new Info(new Shield() . ' Es wird eine automatische Installation der Datenbank und eine Überprüfung der Daten durchgeführt')),
                                ), Panel::PANEL_TYPE_PRIMARY,
                                    new PullRight(strip_tags((new Redirect(self::getRequest()->getPathInfo(), 110)),
                                        '<div><a><script><span>'))
                                ),
                                new Panel('Protokoll', array(
                                    $Protocol
                                ))
                            ))
                        )
                    )
                )
            );
            return $Stage;
        }

        // Create Form
        $Form = new Form(
            new FormGroup(array(
                    new FormRow(
                        new FormColumn(
                            new Panel('Benutzername & Passwort', array(
                                (new TextField('CredentialName', 'Benutzername', 'Benutzername', new Person()))
                                    ->setRequired(),
                                (new PasswordField('CredentialLock', 'Passwort', 'Passwort', new Lock()))
                                    ->setRequired()->setDefaultValue($CredentialLock, true)
                            ), Panel::PANEL_TYPE_INFO)
                        )
                    )
                )
            ), new Primary('Anmelden')
        );

        $FormService = Account::useService()->createSessionCredential(
            $Form, $CredentialName, $CredentialLock, $Identification
        );

        $Stage->setContent(
            new Layout(new LayoutGroup(array(
                new LayoutRow(array(
                    new LayoutColumn(
                        ''
                        , 3),
                    new LayoutColumn(
                        new Well($FormService)
                        , 6),
                    new LayoutColumn(
                        ''
                        , 3),
                )),
            )))
        );
        return $Stage;
    }

    /**
     * @return Stage
     */
    public function frontendDestroySession()
    {

        $View = new Stage('Abmelden', 'Bitte warten...');
        $View->setContent(Account::useService()->destroySession(
                new Redirect('/Platform/Gatekeeper/Authentication', Redirect::TIMEOUT_SUCCESS)
            ) . $this->getCleanLocalStorage());
        return $View;
    }
}
