<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Account;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Data;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAuthentication;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAuthorization;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblIdentification;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblSession;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblSetting;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Setup;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Service\Entity\TblConsumer;
use SPHERE\Common\Frontend\Form\IFormInterface;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Window\Redirect;
use SPHERE\System\Cache\Handler\MemcachedHandler;
use SPHERE\System\Database\Binding\AbstractService;

/**
 * Class Service
 *
 * @package SPHERE\Application\System\Gatekeeper\Authorization\Account
 */
class Service extends AbstractService
{

    /**
     * @param bool $doSimulation
     * @param bool $withData
     *
     * @return string
     */
    public function setupService($doSimulation, $withData)
    {

        $Protocol = (new Setup($this->getStructure()))->setupDatabaseSchema($doSimulation);
        if (!$doSimulation && $withData) {
            (new Data($this->getBinding()))->setupDatabaseContent();
        }
        return $Protocol;
    }

    /**
     * @param null|string $Session
     *
     * @return null|TblAccount
     */
    public function getAccountBySession($Session = null)
    {

        return (new Data($this->getBinding()))->getAccountBySession($Session);
    }

    /**
     * @param string $Username
     *
     * @return null|TblAccount
     */
    public function getAccountByUsername($Username)
    {

        return (new Data($this->getBinding()))->getAccountByUsername($Username);
    }

    /**
     * @param integer $Id
     *
     * @return null|TblAccount
     */
    public function getAccountById($Id)
    {

        return (new Data($this->getBinding()))->getAccountById($Id);
    }

    /**
     * @param integer $Id
     *
     * @return null|TblIdentification
     */
    public function getIdentificationById($Id)
    {

        return (new Data($this->getBinding()))->getIdentificationById($Id);
    }

    /**
     * @param string $Name
     *
     * @return null|TblIdentification
     */
    public function getIdentificationByName($Name)
    {

        return (new Data($this->getBinding()))->getIdentificationByName($Name);
    }

    /**
     * @return TblIdentification[]|null
     */
    public function getIdentificationAll()
    {

        return (new Data($this->getBinding()))->getIdentificationAll();
    }

    /**
     * @param null|Redirect $Redirect
     * @param null|string $Session
     *
     * @return bool|Redirect
     */
    public function destroySession(Redirect $Redirect = null, $Session = null)
    {

        if (null === $Session) {
            (new Data($this->getBinding()))->destroySession($Session);
            if (!headers_sent()) {
                // Destroy Cookie
                $params = session_get_cookie_params();
                setcookie(session_name(), '', 0, $params['path'], $params['domain'], $params['secure'],
                    isset($params['httponly']));
                session_start();
                // Generate New Id
                session_regenerate_id(true);
            }
            $this->getCache(new MemcachedHandler())->clearSlot('PUBLIC');
            return $Redirect;
        } else {
            return (new Data($this->getBinding()))->destroySession($Session);
        }
    }

    /**
     * @param IFormInterface $Form
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param TblIdentification $TblIdentification
     *
     * @return IFormInterface|Redirect
     */
    public function createSessionCredential(
        IFormInterface $Form,
        $CredentialName,
        $CredentialLock,
        TblIdentification $TblIdentification
    ) {

        if ($TblIdentification->isActive()) {
            switch ($this->isCredentialValid($CredentialName, $CredentialLock, $TblIdentification)) {
                case false: {
                    if (null !== $CredentialName && empty($CredentialName)) {
                        $Form->setError('CredentialName', 'Bitte geben Sie einen gültigen Benutzernamen ein');
                    }
                    if (null !== $CredentialName && !empty($CredentialName)) {
                        $Form->setError('CredentialName', 'Bitte geben Sie einen gültigen Benutzernamen ein');
                    }
                    if (null !== $CredentialLock && empty($CredentialLock)) {
                        $Form->setError('CredentialLock', 'Bitte geben Sie ein gültiges Passwort ein');
                    }
                    if (null !== $CredentialLock && !empty($CredentialLock)) {
                        $Form->setError('CredentialLock', 'Bitte geben Sie ein gültiges Passwort ein');
                    }
                    break;
                }
                case true: {
                    return new Redirect('/', Redirect::TIMEOUT_SUCCESS);
                    break;
                }
            }
        } else {
            if ($CredentialName || $CredentialLock) {
                return new Warning('Die Anmeldung mit Benutzername und Passwort ist derzeit leider deaktiviert')
                    . new Redirect('/', Redirect::TIMEOUT_ERROR);
            }
        }
        return $Form;
    }

    /**
     * @param string $Username
     * @param string $Password
     * @param TblIdentification $TblIdentification
     * @return bool|null
     */
    private function isCredentialValid($Username, $Password, TblIdentification $TblIdentification)
    {

        if (!($TblAccount = $this->getAccountByCredential($Username, $Password, $TblIdentification))) {
            return false;
        } else {
            if (session_status() == PHP_SESSION_ACTIVE) {
                session_regenerate_id();
            }
            $this->createSession($TblAccount, session_id());
            return true;
        }
    }

    /**
     * @param string $Username
     * @param string $Password
     * @param TblIdentification $TblIdentification
     *
     * @return bool|TblAccount
     */
    public function getAccountByCredential($Username, $Password, TblIdentification $TblIdentification = null)
    {

        return (new Data($this->getBinding()))->getAccountByCredential($Username, $Password, $TblIdentification);
    }

    /**
     * @param TblAccount $TblAccount
     * @param null|string $Session
     * @param integer $Timeout
     *
     * @return Service\Entity\TblSession
     */
    public function createSession(TblAccount $TblAccount, $Session = null, $Timeout = 1800)
    {

        return (new Data($this->getBinding()))->createSession($TblAccount, $Session, $Timeout);
    }

    /**
     * @return TblAccount[]|bool
     */
    public function getAccountAll()
    {

        return (new Data($this->getBinding()))->getAccountAll();
    }

    /**
     * @return TblSession[]|bool
     */
    public function getSessionAll()
    {

        return (new Data($this->getBinding()))->getSessionAll();
    }

    /**
     * @param TblAccount $TblAccount
     * @param TblRole $TblRole
     *
     * @return bool
     */
    public function hasAuthorization(TblAccount $TblAccount, TblRole $TblRole)
    {

        $TblAuthorization = $this->getAuthorizationAllByAccount($TblAccount);
        /** @noinspection PhpUnusedParameterInspection */
        array_walk($TblAuthorization, function (TblAuthorization &$TblAuthorization) use ($TblRole) {

            if ($TblAuthorization->getServiceTblRole()
                && $TblAuthorization->getServiceTblRole()->getId() != $TblRole->getId()
            ) {
                $TblAuthorization = false;
            }
        });
        $TblAuthorization = array_filter($TblAuthorization);
        if (!empty($TblAuthorization)) {
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $TblAccount
     *
     * @return bool|TblAuthorization[]
     */
    public function getAuthorizationAllByAccount(TblAccount $TblAccount)
    {

        return (new Data($this->getBinding()))->getAuthorizationAllByAccount($TblAccount);
    }

    /**
     * @param TblAccount $TblAccount
     *
     * @return bool|TblAuthentication
     */
    public function getAuthenticationByAccount(TblAccount $TblAccount)
    {

        return (new Data($this->getBinding()))->getAuthenticationByAccount($TblAccount);

    }


    /**
     * @param TblAccount $TblAccount
     * @param TblIdentification $TblIdentification
     *
     * @return TblAuthentication
     */
    public function addAccountAuthentication(TblAccount $TblAccount, TblIdentification $TblIdentification)
    {

        return (new Data($this->getBinding()))->addAccountAuthentication($TblAccount, $TblIdentification);
    }

    /**
     * @param TblAccount $TblAccount
     * @param TblRole $TblRole
     *
     * @return TblAuthorization
     */
    public function addAccountAuthorization(TblAccount $TblAccount, TblRole $TblRole)
    {

        return (new Data($this->getBinding()))->addAccountAuthorization($TblAccount, $TblRole);
    }

    /**
     * @param TblAccount $TblAccount
     *
     * @return bool|TblSession[]
     */
    public function getSessionAllByAccount(TblAccount $TblAccount)
    {

        return (new Data($this->getBinding()))->getSessionAllByAccount($TblAccount);
    }

    /**
     * @param TblAccount $TblAccount
     * @param TblRole $TblRole
     *
     * @return bool
     */
    public function removeAccountAuthorization(TblAccount $TblAccount, TblRole $TblRole)
    {

        return (new Data($this->getBinding()))->removeAccountAuthorization($TblAccount, $TblRole);
    }

    /**
     * @param TblAccount $TblAccount
     * @param TblIdentification $TblIdentification
     *
     * @return bool
     */
    public function removeAccountAuthentication(TblAccount $TblAccount, TblIdentification $TblIdentification)
    {

        return (new Data($this->getBinding()))->removeAccountAuthentication($TblAccount, $TblIdentification);
    }

    /**
     * @param TblAccount $TblAccount
     *
     * @return bool
     */
    public function destroyAccount(TblAccount $TblAccount)
    {

        return (new Data($this->getBinding()))->destroyAccount($TblAccount);
    }

    /**
     * @param string $Password
     * @param TblAccount $TblAccount
     *
     * @return bool
     */
    public function changePassword($Password, TblAccount $TblAccount = null)
    {

        return (new Data($this->getBinding()))->changePassword($Password, $TblAccount);
    }

    /**
     * @param TblConsumer $TblConsumer
     * @param TblAccount $TblAccount
     *
     * @return bool
     */
    public function changeConsumer(TblConsumer $TblConsumer, TblAccount $TblAccount = null)
    {

        return (new Data($this->getBinding()))->changeConsumer($TblConsumer, $TblAccount);
    }

    /**
     * @param TblAccount $TblAccount
     * @param string $Identifier
     *
     * @return bool|TblSetting
     */
    public function getSettingByAccount(TblAccount $TblAccount, $Identifier)
    {

        return (new Data($this->getBinding()))->getSettingByAccount($TblAccount, $Identifier);
    }

    /**
     * @param TblAccount $TblAccount
     * @param string $Identifier
     * @param string $Value
     *
     * @return bool|TblSetting
     */
    public function setSettingByAccount(TblAccount $TblAccount, $Identifier, $Value)
    {

        return (new Data($this->getBinding()))->setSettingByAccount($TblAccount, $Identifier, $Value);
    }

    /**
     * @param TblSetting $TblSetting
     *
     * @return bool
     */
    public function destroySetting(TblSetting $TblSetting)
    {

        return (new Data($this->getBinding()))->destroySetting($TblSetting);
    }

    /**
     * @param TblAccount $TblAccount
     *
     * @return bool|TblSetting[]
     */
    public function getSettingAllByAccount(TblAccount $TblAccount)
    {

        return (new Data($this->getBinding()))->getSettingAllByAccount($TblAccount);
    }

    /**
     * @return int
     */
    public function countSessionAll()
    {

        return (new Data($this->getBinding()))->countSessionAll();
    }
}
