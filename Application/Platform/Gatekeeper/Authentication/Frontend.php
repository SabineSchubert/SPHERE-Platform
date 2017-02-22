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
use SPHERE\Common\Frontend\Icon\Repository\FAAngleRight;
use SPHERE\Common\Frontend\Icon\Repository\Hospital;
use SPHERE\Common\Frontend\Icon\Repository\Lock;
use SPHERE\Common\Frontend\Icon\Repository\Person;
use SPHERE\Common\Frontend\Icon\Repository\Shield;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Thumbnail;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Layout\Structure\Slick;
use SPHERE\Common\Frontend\Link\Repository\External;
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
                ->addContent('<div style="background-repeat: no-repeat; background-size: cover; background-position: center center; width: 100%; height: 500px; background-image: url(\'/Common/Style/Resource/Teaser/000-Mercedes-Benz-Fahrzeuge-A-Klasse-W-176-MoPf-1280x636-1-1280x636.jpg\');">'
                    . '<div class="container-fluid">'
                    . '<div class="col-xs-1"></div>'
                    . '<div class="col-xs-10" style="text-shadow: 1px 1px 1px rgba(0, 0, 0, 1);">'
                    . '<h1>Willkommen <small>im Räder/Reifen Online Portal</small></h1>'
                    . '<br/>'
                    . '<div class="">Jemand musste Josef K. verleumdet haben, denn ohne dass er etwas Böses getan hätte, wurde er eines Morgens verhaftet. »Wie ein Hund!« sagte er, es war, als sollte die Scham ihn überleben. Als Gregor Samsa eines Morgens aus unruhigen Träumen erwachte, fand er sich in seinem Bett zu einem ungeheueren Ungeziefer verwandelt.</div>'
                    . '<br/>'
                    . '<br/>'
                    . new Standard('Erfahren Sie mehr...', '#', new FAAngleRight())
                    . '</div>'
                    . '<div class="col-xs-1"></div>'
                    . '</div>'
                    . '</div>')
                ->addContent('<div style="background-repeat: no-repeat; background-size: cover; background-position: center center; width: 100%; height: 500px; background-image: url(\'/Common/Style/Resource/Teaser/00-Mercedes-Benz-Mercedes-AMG-GLC-43-4MATIC-NYIAS-2016-1180x559-1180x559.jpg\');">'
                    . '<div class="container-fluid">'
                    . '<div class="col-xs-1"></div>'
                    . '<div class="col-xs-10">'
                    . '<h1 class="black" style="color: #000;">Thema 1</h1>'
                    . '<br/>'
                    . '<div style="color: #000; text-shadow: 1px 1px 1px rgba(254, 254, 254, 1);">Und es war ihnen wie eine Bestätigung ihrer neuen Träume und guten Absichten, als am Ziele ihrer Fahrt die Tochter als erste sich erhob und ihren jungen Körper dehnte. »Es ist ein eigentümlicher Apparat«, sagte der Offizier zu dem Forschungsreisenden und überblickte mit einem gewissermaßen bewundernden Blick den ihm doch wohlbekannten Apparat.</div>'
                    . '<br/>'
                    . '<br/>'
                    . new Standard('Erfahren Sie mehr...', '#', new FAAngleRight())
                    . '</div>'
                    . '<div class="col-xs-1"></div>'
                    . '</div>'
                    . '</div>')
                ->addContent('<div style="background-repeat: no-repeat; background-size: cover; background-position: center center; width: 100%; height: 500px; background-image: url(\'/Common/Style/Resource/Teaser/000-mercedes-benz-fahrzeuge-e-klasse-coupe-c-238-1280x636-1280x636.jpg\');">'
                    . '<div class="container-fluid">'
                    . '<div class="col-xs-1"></div>'
                    . '<div class="col-xs-10">'
                    . '<h1 class="black" style="color: #000;">Thema 2</h1>'
                    . '<br/>'
                    . '<div style="color: #000; text-shadow: 1px 1px 1px rgba(254, 254, 254, 1);">Sie hätten noch ins Boot springen können, aber der Reisende hob ein schweres, geknotetes Tau vom Boden, drohte ihnen damit und hielt sie dadurch von dem Sprunge ab. In den letzten Jahrzehnten ist das Interesse an Hungerkünstlern sehr zurückgegangen. Aber sie überwanden sich, umdrängten den Käfig und wollten sich gar nicht fortrühren.</div>'
                    . '<br/>'
                    . '<br/>'
                    . new Standard('Erfahren Sie mehr...', '#', new FAAngleRight())
                    . '</div>'
                    . '<div class="col-xs-1"></div>'
                    . '</div>'
                    . '</div>')
                ->addContent('<div style="background-repeat: no-repeat; background-size: cover; background-position: center center; width: 100%; height: 500px; background-image: url(\'/Common/Style/Resource/Teaser/000-mercedes-benz-fahrzeuge-mercedes-amg-gt-r-c-190-nordschleife-1280x636-1280x636.jpg\');">'
                    . '<div class="container-fluid">'
                    . '<div class="col-xs-1"></div>'
                    . '<div class="col-xs-10" style="text-shadow: 1px 1px 1px rgba(0, 0, 0, 1);">'
                    . '<h1>Thema 3 <small>inklusive Subline</small></h1>'
                    . '<br/>'
                    . '<div class="">Sie hätten noch ins Boot springen können, aber der Reisende hob ein schweres, geknotetes Tau vom Boden, drohte ihnen damit und hielt sie dadurch von dem Sprunge ab. In den letzten Jahrzehnten ist das Interesse an Hungerkünstlern sehr zurückgegangen. Aber sie überwanden sich, umdrängten den Käfig und wollten sich gar nicht fortrühren.</div>'
                    . '<br/>'
                    . '<br/>'
                    . new External('Suchen Sie selbst...', 'https://www.google.de/', new FAAngleRight())
                    . '</div>'
                    . '<div class="col-xs-1"></div>'
                    . '</div>'
                    . '</div>')
        );

        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(array(
//                    new LayoutRow(array(
//                        new LayoutColumn(array(
//                            new Title('Spotlight'),
//                        ))
//                    )),
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-design-aesthetics-a-1280-686-848x454.jpg'),
                                'Titel',
                                'Beschreibung',
                                array(
                                    new Link('Erfahren Sie mehr...', '#')
                                )
                            )
                        ), 3),
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
                        new LayoutColumn(array(
                            new Title('Hinter den Wortbergen', 'Lorem ipsum'),
                            'Weit hinten, hinter den Wortbergen, fern der Länder Vokalien und Konsonantien leben die Blindtexte. Abgeschieden wohnen sie in Buchstabhausen an der Küste des Semantik, eines großen Sprachozeans. Ein kleines Bächlein namens Duden fließt durch ihren Ort und versorgt sie mit den nötigen Regelialien. Es ist ein paradiesmatisches Land, in dem einem gebratene Satzteile in den Mund fliegen. Nicht einmal von der allmächtigen Interpunktion werden die Blindtexte beherrscht – ein geradezu unorthographisches Leben.',
                        ),6),
                        new LayoutColumn(array(
                            new Title('Typoblindtext ', 'Lorem ipsum'),
                            'Dies ist ein Typoblindtext. An ihm kann man sehen, ob alle Buchstaben da sind und wie sie aussehen. Manchmal benutzt man Worte wie Hamburgefonts, Rafgenduks oder Handgloves, um Schriften zu testen. Manchmal Sätze, die alle Buchstaben des Alphabets enthalten - man nennt diese Sätze »Pangrams«. Sehr bekannt ist dieser: The quick brown fox jumps over the lazy old dog. Oft werden in Typoblindtexte auch fremdsprachige Satzteile eingebaut (AVAIL® and Wefox™ are testing aussi la Kerning), um die Wirkung in anderen Sprachen zu testen. In Lateinisch sieht zum Beispiel fast jede Schrift gut aus. Quod erat demonstrandum. Seit 1975 fehlen in den meisten Testtexten die Zahlen, weswegen nach TypoGb. 204 § ab dem Jahr 2034 Zahlen in 86 der Texte zur Pflicht werden. Nichteinhaltung wird mit bis zu 245 € oder 368 $ bestraft. Genauso wichtig in sind mittlerweile auch Âçcèñtë, die in neueren Schriften aber fast immer enthalten sind. Ein wichtiges aber schwierig zu integrierendes Feld sind OpenType-Funktionalitäten. Je nach Software und Voreinstellungen können eingebaute Kapitälchen, Kerning oder Ligaturen (sehr pfiffig) nicht richtig dargestellt werden.',
                        ),6),
                    )),
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
                            ), Panel::PANEL_TYPE_DEFAULT)
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
                        $FormService
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
