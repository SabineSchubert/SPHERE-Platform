<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAuthentication;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAuthorization;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblIdentification;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblSession;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblSetting;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Service\Entity\TblConsumer;
use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\System\Cache\Handler\MemcachedHandler;
use SPHERE\System\Cache\Handler\MemoryHandler;
use SPHERE\System\Database\Binding\AbstractData;
use SPHERE\System\Database\Fitting\ColumnHydrator;
use SPHERE\System\Debugger\DebuggerFactory;
use SPHERE\System\Debugger\Logger\CacheLogger;

/**
 * Class Data
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service
 */
class Data extends AbstractData
{

    public function setupDatabaseContent()
    {

        // Identification
        $this->createIdentification(TblIdentification::NAME_CREDENTIAL, 'Benutzername / Passwort', true);
        $this->createIdentification(TblIdentification::NAME_SAML, 'SAML Auth', true);

        $TblConsumer = Consumer::useService()->getConsumerById(1);

        // Choose the right Identification for Authentication
        $TblIdentificationCredential = $this->getIdentificationByName(TblIdentification::NAME_CREDENTIAL);
        $TblIdentificationSaml = $this->getIdentificationByName(TblIdentification::NAME_SAML);

        $TblRole = Access::useService()->getRoleByName('Administrator');

        // Install Administrator
        $TblAccount = $this->createAccount('root', 'sphere', $TblConsumer);

        $this->addAccountAuthentication($TblAccount, $TblIdentificationCredential);
        $this->addAccountAuthentication($TblAccount, $TblIdentificationSaml);

        $this->addAccountAuthorization($TblAccount, $TblRole);
        if (!$this->getSettingByAccount($TblAccount, 'Surface')) {
            $this->setSettingByAccount($TblAccount, 'Surface', 1);
        }
    }

    /**
     * @param string $Name
     * @param string $Description
     * @param bool $IsActive
     *
     * @return TblIdentification
     */
    public function createIdentification($Name, $Description = '', $IsActive = true)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblIdentification', array(
            TblIdentification::ATTR_NAME => $Name
        ));
        if (null === $Entity) {
            $Entity = new TblIdentification();
            $Entity->setName($Name);
            $Entity->setDescription($Description);
            $Entity->setActive($IsActive);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return bool|TblIdentification
     */
    public function getIdentificationByName($Name)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblIdentification', array(
            TblIdentification::ATTR_NAME => $Name
        ));
    }

    /**
     * @param string $Username
     * @param string $Password
     * @param null|TblConsumer $TblConsumer
     *
     * @return TblAccount
     */
    public function createAccount($Username, $Password, TblConsumer $TblConsumer = null)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAccount', array(
            TblAccount::ATTR_USERNAME => $Username
        ));
        if (null === $Entity) {
            $Entity = new TblAccount();
            $Entity->setUsername($Username);
            $Entity->setPassword(hash('sha256', $Password));
            $Entity->setServiceTblConsumer($TblConsumer);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblAccount $TblAccount
     * @param TblIdentification $TblIdentification
     *
     * @return TblAuthentication
     */
    public function addAccountAuthentication(TblAccount $TblAccount, TblIdentification $TblIdentification)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthentication', array(
            TblAuthentication::ATTR_TBL_ACCOUNT => $TblAccount->getId(),
            TblAuthentication::ATTR_TBL_IDENTIFICATION => $TblIdentification->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblAuthentication();
            $Entity->setTblAccount($TblAccount);
            $Entity->setTblIdentification($TblIdentification);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblAccount $TblAccount
     * @param TblRole $TblRole
     *
     * @return TblAuthorization
     */
    public function addAccountAuthorization(TblAccount $TblAccount, TblRole $TblRole)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthorization', array(
            TblAuthorization::ATTR_TBL_ACCOUNT => $TblAccount->getId(),
            TblAuthorization::SERVICE_TBL_ROLE => $TblRole->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblAuthorization();
            $Entity->setTblAccount($TblAccount);
            $Entity->setServiceTblRole($TblRole);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblAccount $TblAccount
     * @param string $Identifier
     *
     * @return bool|TblSetting
     */
    public function getSettingByAccount(TblAccount $TblAccount, $Identifier)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblSetting', array(
            TblSetting::ATTR_TBL_ACCOUNT => $TblAccount->getId(),
            TblSetting::ATTR_IDENTIFIER => $Identifier
        ));
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

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblSetting', array(
            TblSetting::ATTR_TBL_ACCOUNT => $TblAccount->getId(),
            TblSetting::ATTR_IDENTIFIER => $Identifier
        ));
        if (null === $Entity) {
            $Entity = new TblSetting();
            $Entity->setTblAccount($TblAccount);
            $Entity->setIdentifier($Identifier);
            $Entity->setValue($Value);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        } else {
            /**
             * @var TblSetting $Protocol
             * @var TblSetting $Entity
             */
            $Protocol = clone $Entity;
            $Entity->setValue($Value);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createUpdateEntry($this->getConnection()->getDatabase(), $Protocol, $Entity);
        }
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @return TblIdentification[]|null
     */
    public function getIdentificationAll()
    {

        return $this->getCachedEntityList(__METHOD__, $this->getEntityManager(), 'TblIdentification');
    }

    /**
     * @param string $Username
     *
     * @return bool|TblAccount
     */
    public function getAccountByUsername($Username)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblAccount', array(
            TblAccount::ATTR_USERNAME => $Username
        ));
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAccount
     */
    public function getAccountById($Id)
    {

        return $this->getCachedEntityById(__METHOD__, $this->getEntityManager(), 'TblAccount', $Id);
    }

    /**
     * @return TblAccount[]|bool
     */
    public function getAccountAll()
    {

        return $this->getCachedEntityList(__METHOD__, $this->getEntityManager(), 'TblAccount');
    }

    /**
     * @return TblSession[]|bool
     */
    public function getSessionAll()
    {

        // MUST NOT USE Cache
        return $this->getForceEntityList(__METHOD__, $this->getEntityManager(), 'TblSession');
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblIdentification
     */
    public function getIdentificationById($Id)
    {

        return $this->getCachedEntityById(__METHOD__, $this->getEntityManager(), 'TblIdentification', $Id);
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblSession
     */
    public function getSessionById($Id)
    {

        return $this->getCachedEntityById(__METHOD__, $this->getEntityManager(), 'TblSession', $Id);
    }

    /**
     * @param TblConsumer $TblConsumer
     *
     * @return bool|TblAccount[]
     */
    public function getAccountAllByConsumer(TblConsumer $TblConsumer)
    {

        $EntityList = $this->getEntityManager()->getEntity('TblAccount')->findBy(array(
            TblAccount::SERVICE_TBL_CONSUMER => $TblConsumer->getId()
        ));
        return (empty($EntityList) ? false : $EntityList);
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

        $Manager = $this->getEntityManager();

        $TblAccount = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAccount', array(
            TblAccount::ATTR_USERNAME => $Username,
            TblAccount::ATTR_PASSWORD => hash('sha256', $Password)
        ));
        // Account not available
        if (null === $TblAccount) {
            return false;
        }

        $TblAuthentication = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthentication', array(
            TblAuthentication::ATTR_TBL_ACCOUNT => $TblAccount->getId(),
            TblAuthentication::ATTR_TBL_IDENTIFICATION => $TblIdentification->getId()
        ));
        // Identification not valid
        if (null === $TblAuthentication) {
            return false;
        }

        return $TblAccount;
    }

    /**
     * @param TblAccount $TblAccount
     * @param null|string $Session
     * @param integer $Timeout
     *
     * @return TblSession
     */
    public function createSession(TblAccount $TblAccount, $Session = null, $Timeout = 1800)
    {

        if (null === $Session) {
            $Session = session_id();
        }
        $Manager = $this->getEntityManager();
        /** @var TblSession $Entity */
        $Entity = $Manager->getEntity('TblSession')->findOneBy(array(TblSession::ATTR_SESSION => $Session));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
        }
        $Entity = new TblSession();
        $Entity->setSession($Session);
        $Entity->setTblAccount($TblAccount);
        $Entity->setTimeout(time() + $Timeout);
        $Manager->saveEntity($Entity);
        Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        return $Entity;
    }

    /**
     * @param TblAccount $TblAccount
     *
     * @return bool
     */
    public function destroyAccount(TblAccount $TblAccount)
    {

        $Manager = $this->getEntityManager();
        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById('TblAccount', $TblAccount->getId());
        if (null !== $Entity) {
            // Remove Identification
            $TblAuthentication = $this->getAuthenticationByAccount($Entity);
            if ($TblAuthentication) {
                if ($TblAuthentication->getTblAccount() && $TblAuthentication->getTblIdentification()) {
                    $this->removeAccountAuthentication(
                        $TblAuthentication->getTblAccount(), $TblAuthentication->getTblIdentification()
                    );
                }
            }
            // Remove Role
            $TblAuthorizationList = $this->getAuthorizationAllByAccount($Entity);
            if ($TblAuthorizationList) {
                /** @var TblAuthorization $TblAuthorization */
                foreach ($TblAuthorizationList as $TblAuthorization) {
                    if ($TblAuthorization->getTblAccount() && $TblAuthorization->getServiceTblRole()) {
                        $this->removeAccountAuthorization(
                            $TblAuthorization->getTblAccount(), $TblAuthorization->getServiceTblRole()
                        );
                    }
                }
            }
            // Remove Setting
            $TblSettingList = $this->getSettingAllByAccount($Entity);
            if ($TblSettingList) {
                /** @var TblSetting $TblSetting */
                foreach ($TblSettingList as $TblSetting) {
                    $this->destroySetting($TblSetting);
                }
            }
            // Remove Session
            $TblSessionList = $this->getSessionAllByAccount($Entity);
            if ($TblSessionList) {
                /** @var TblSession $TblSession */
                foreach ($TblSessionList as $TblSession) {
                    $this->destroySession($TblSession->getSession());
                }
            }
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $TblAccount
     *
     * @return null|TblAuthentication
     */
    public function getAuthenticationByAccount(TblAccount $TblAccount)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblAuthentication', array(
            TblAuthentication::ATTR_TBL_ACCOUNT => $TblAccount->getId()
        ));
    }

    /**
     * @param TblAccount $TblAccount
     * @param TblIdentification $TblIdentification
     *
     * @return bool
     */
    public function removeAccountAuthentication(TblAccount $TblAccount, TblIdentification $TblIdentification)
    {

        $Manager = $this->getEntityManager();
        /** @var TblAuthentication $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthentication', array(
            TblAuthentication::ATTR_TBL_ACCOUNT => $TblAccount->getId(),
            TblAuthentication::ATTR_TBL_IDENTIFICATION => $TblIdentification->getId()
        ));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
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

        return $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(), 'TblAuthorization',
            array(
                TblAuthorization::ATTR_TBL_ACCOUNT => $TblAccount->getId()
            ));
    }

    /**
     * @param TblAccount $TblAccount
     * @param TblRole $TblRole
     *
     * @return bool
     */
    public function removeAccountAuthorization(TblAccount $TblAccount, TblRole $TblRole)
    {

        $Manager = $this->getEntityManager();
        /** @var TblAuthorization $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthorization', array(
            TblAuthorization::ATTR_TBL_ACCOUNT => $TblAccount->getId(),
            TblAuthorization::SERVICE_TBL_ROLE => $TblRole->getId()
        ));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $TblAccount
     *
     * @return null|TblSetting[]
     */
    public function getSettingAllByAccount(TblAccount $TblAccount)
    {

        return $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(), 'TblSetting', array(
            TblSetting::ATTR_TBL_ACCOUNT => $TblAccount->getId()
        ));
    }

    /**
     * @param TblSetting $TblSetting
     *
     * @return bool
     */
    public function destroySetting(TblSetting $TblSetting)
    {

        $Manager = $this->getEntityManager();
        /** @var TblSetting $Entity */
        $Entity = $this->getForceEntityById(__METHOD__, $Manager, 'TblSetting', $TblSetting->getId());
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $TblAccount
     *
     * @return bool|TblSession[]
     */
    public function getSessionAllByAccount(TblAccount $TblAccount)
    {

        return $this->getForceEntityListBy(__METHOD__, $this->getEntityManager(), 'TblSession', array(
            TblSession::ATTR_TBL_ACCOUNT => $TblAccount->getId()
        ));
    }

    /**
     * @param null|string $Session
     *
     * @return bool
     */
    public function destroySession($Session = null)
    {

        if (null === $Session) {
            $Session = session_id();
        }

        $Manager = $this->getEntityManager();
        /** @var TblSession $Entity */
        $Entity = $Manager->getEntity('TblSession')->findOneBy(array(TblSession::ATTR_SESSION => $Session));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $TblAccount
     * @param string $Password
     *
     * @return bool
     */
    public function changePassword($Password, TblAccount $TblAccount = null)
    {

        if (null === $TblAccount) {
            $TblAccount = $this->getAccountBySession();
        }
        $Manager = $this->getEntityManager();
        /**
         * @var TblAccount $Protocol
         * @var TblAccount $Entity
         */
        $Entity = $Manager->getEntityById('TblAccount', $TblAccount->getId());
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setPassword(hash('sha256', $Password));
            $Manager->saveEntity($Entity);
            Protocol::useService()->createUpdateEntry($this->getConnection()->getDatabase(), $Protocol, $Entity);
            return true;
        }
        return false;
    }

    /**
     * @param null|string $Session
     *
     * @return null|TblAccount
     */
    public function getAccountBySession($Session = null)
    {

        if (null === $Session) {
            $Session = session_id();
        }

        // 1. Level Cache
        $Memory = $this->getCache(new MemoryHandler());
        if (null === ($Entity = $Memory->getValue($Session, __METHOD__))) {
            // Kill stalled Sessions
            $this->removeSessionByTimeout();

            /** @var null|TblSession $Entity */
            $Entity = $this->getForceEntityBy(__METHOD__, $this->getEntityManager(), 'TblSession', array(
                TblSession::ATTR_SESSION => $Session
            ));

            if ($Entity) {
                $Account = $Entity->getTblAccount();
                // Reset Timeout on Current Session (within time)
                $Identification = $this->getAuthenticationByAccount($Account)->getTblIdentification();
                $this->changeTimeout($Entity, $Identification->getSessionTimeout());
                /** @var null|TblAccount $Entity */
                $Entity = $Account;
            }
            $Memory->setValue($Session, $Entity, 0, __METHOD__);
        }
        return ($Entity ? $Entity : null);
    }

    /**
     *
     */
    private function removeSessionByTimeout()
    {

        $Manager = $this->getEntityManager();

        $Builder = $Manager->getQueryBuilder();
        $Query = $Builder->select('S.Id')
            ->from(__NAMESPACE__ . '\Entity\TblSession', 'S')
            ->where($Builder->expr()->lte('S.Timeout', '?1'))
            ->setParameter(1, time())
            ->getQuery();
        $Query->useQueryCache(true);
        $IdList = $Query->getResult(ColumnHydrator::HYDRATION_MODE);

        if (!empty($IdList)) {
            $Builder = $Manager->getQueryBuilder();
            $Query = $Builder->delete()
                ->from(__NAMESPACE__ . '\Entity\TblSession', 'S')
                ->where($Builder->expr()->in('S.Id', '?1'))
                ->setParameter(1, $IdList)
                ->getQuery();
            $Query->useQueryCache(true);
            $Query->getResult();

            /** @var MemcachedHandler $Public */
            $Public = $this->getCache(new MemcachedHandler());
            $Public->clearSlot('PUBLIC');
        }
    }

    /**
     * @param TblSession $TblSession
     * @param int $Timeout
     *
     * @return bool
     */
    private function changeTimeout(TblSession $TblSession, $Timeout)
    {

        $Manager = $this->getEntityManager();
        /**
         * @var TblSession $Entity
         */
        $Entity = $this->getForceEntityById(__METHOD__, $Manager, 'TblSession', $TblSession->getId());

        if ($Entity) {
            // Skip permanent Timeout
            $Gap = ((time() + $Timeout) - ($Timeout / 100));

            if ($Gap > $Entity->getTimeout()) {
                $Entity->setTimeout(time() + $Timeout);
                $Manager->saveEntity($Entity);
            } else {
                (new DebuggerFactory())->createLogger(new CacheLogger())->addLog('Session Update in ' . ($Gap - $Entity->getTimeout()));
            }
            return true;
        }
        return false;
    }

    /**
     * @param TblConsumer $TblConsumer
     * @param TblAccount $TblAccount
     *
     * @return bool
     */
    public function changeConsumer(TblConsumer $TblConsumer, TblAccount $TblAccount = null)
    {

        if (null === $TblAccount) {
            $TblAccount = $this->getAccountBySession();
        }
        $Manager = $this->getEntityManager();
        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById('TblAccount', $TblAccount->getId());
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setServiceTblConsumer($TblConsumer);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createUpdateEntry($this->getConnection()->getDatabase(), $Protocol, $Entity);
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    public function countSessionAll()
    {

        return $this->getEntityManager()->getEntity('TblSession')->count();
    }
}
