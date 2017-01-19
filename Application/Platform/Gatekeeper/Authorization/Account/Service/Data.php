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
use SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Consumer;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Service\Entity\TblConsumer;
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
        $this->createIdentification( TblIdentification::NAME_CREDENTIAL, 'Benutzername / Passwort', true);

        $tblConsumer = Consumer::useService()->getConsumerById(1);
        // Choose the right Identification for Authentication
        $tblIdentification = $this->getIdentificationByName(TblIdentification::NAME_CREDENTIAL);
        $tblRole = Access::useService()->getRoleByName('Administrator');

        // Install Administrator
        $tblAccount = $this->createAccount('root', 'sphere', $tblConsumer);
        $this->addAccountAuthentication($tblAccount, $tblIdentification);
        $this->addAccountAuthorization($tblAccount, $tblRole);
        if (!$this->getSettingByAccount($tblAccount, 'Surface')) {
            $this->setSettingByAccount($tblAccount, 'Surface', 1);
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
     * @param null|TblConsumer $tblConsumer
     *
     * @return TblAccount
     */
    public function createAccount($Username, $Password, TblConsumer $tblConsumer = null)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAccount', array(
            TblAccount::ATTR_USERNAME => $Username
        ));
        if (null === $Entity) {
            $Entity = new TblAccount();
            $Entity->setUsername($Username);
            $Entity->setPassword(hash('sha256', $Password));
            $Entity->setServiceTblConsumer($tblConsumer);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblAccount $tblAccount
     * @param TblIdentification $tblIdentification
     *
     * @return TblAuthentication
     */
    public function addAccountAuthentication(TblAccount $tblAccount, TblIdentification $tblIdentification)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthentication', array(
            TblAuthentication::ATTR_TBL_ACCOUNT => $tblAccount->getId(),
            TblAuthentication::ATTR_TBL_IDENTIFICATION => $tblIdentification->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblAuthentication();
            $Entity->setTblAccount($tblAccount);
            $Entity->setTblIdentification($tblIdentification);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblAccount $tblAccount
     * @param TblRole $tblRole
     *
     * @return TblAuthorization
     */
    public function addAccountAuthorization(TblAccount $tblAccount, TblRole $tblRole)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthorization', array(
            TblAuthorization::ATTR_TBL_ACCOUNT => $tblAccount->getId(),
            TblAuthorization::SERVICE_TBL_ROLE => $tblRole->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblAuthorization();
            $Entity->setTblAccount($tblAccount);
            $Entity->setServiceTblRole($tblRole);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblAccount $tblAccount
     * @param string $Identifier
     *
     * @return bool|TblSetting
     */
    public function getSettingByAccount(TblAccount $tblAccount, $Identifier)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblSetting', array(
            TblSetting::ATTR_TBL_ACCOUNT => $tblAccount->getId(),
            TblSetting::ATTR_IDENTIFIER => $Identifier
        ));
    }

    /**
     * @param TblAccount $tblAccount
     * @param string $Identifier
     * @param string $Value
     *
     * @return bool|TblSetting
     */
    public function setSettingByAccount(TblAccount $tblAccount, $Identifier, $Value)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblSetting', array(
            TblSetting::ATTR_TBL_ACCOUNT => $tblAccount->getId(),
            TblSetting::ATTR_IDENTIFIER => $Identifier
        ));
        if (null === $Entity) {
            $Entity = new TblSetting();
            $Entity->setTblAccount($tblAccount);
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
     * @param TblConsumer $tblConsumer
     *
     * @return bool|TblAccount[]
     */
    public function getAccountAllByConsumer(TblConsumer $tblConsumer)
    {

        $EntityList = $this->getEntityManager()->getEntity('TblAccount')->findBy(array(
            TblAccount::SERVICE_TBL_CONSUMER => $tblConsumer->getId()
        ));
        return (empty($EntityList) ? false : $EntityList);
    }

    /**
     * @param string $Username
     * @param string $Password
     * @param TblIdentification $tblIdentification
     *
     * @return bool|TblAccount
     */
    public function getAccountByCredential($Username, $Password, TblIdentification $tblIdentification = null)
    {

        $Manager = $this->getEntityManager();

        $tblAccount = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAccount', array(
            TblAccount::ATTR_USERNAME => $Username,
            TblAccount::ATTR_PASSWORD => hash('sha256', $Password)
        ));
        // Account not available
        if (null === $tblAccount) {
            return false;
        }

        $tblAuthentication = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthentication', array(
            TblAuthentication::ATTR_TBL_ACCOUNT => $tblAccount->getId(),
            TblAuthentication::ATTR_TBL_IDENTIFICATION => $tblIdentification->getId()
        ));
        // Identification not valid
        if (null === $tblAuthentication) {
            return false;
        }

        return $tblAccount;
    }

    /**
     * @param TblAccount $tblAccount
     * @param null|string $Session
     * @param integer $Timeout
     *
     * @return TblSession
     */
    public function createSession(TblAccount $tblAccount, $Session = null, $Timeout = 1800)
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
        $Entity->setTblAccount($tblAccount);
        $Entity->setTimeout(time() + $Timeout);
        $Manager->saveEntity($Entity);
        Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        return $Entity;
    }

    /**
     * @param TblAccount $tblAccount
     *
     * @return bool
     */
    public function destroyAccount(TblAccount $tblAccount)
    {

        $Manager = $this->getEntityManager();
        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById('TblAccount', $tblAccount->getId());
        if (null !== $Entity) {
            // Remove Identification
            $tblAuthentication = $this->getAuthenticationByAccount($Entity);
            if ($tblAuthentication) {
                if ($tblAuthentication->getTblAccount() && $tblAuthentication->getTblIdentification()) {
                    $this->removeAccountAuthentication(
                        $tblAuthentication->getTblAccount(), $tblAuthentication->getTblIdentification()
                    );
                }
            }
            // Remove Role
            $tblAuthorizationList = $this->getAuthorizationAllByAccount($Entity);
            if ($tblAuthorizationList) {
                /** @var TblAuthorization $tblAuthorization */
                foreach ($tblAuthorizationList as $tblAuthorization) {
                    if ($tblAuthorization->getTblAccount() && $tblAuthorization->getServiceTblRole()) {
                        $this->removeAccountAuthorization(
                            $tblAuthorization->getTblAccount(), $tblAuthorization->getServiceTblRole()
                        );
                    }
                }
            }
            // Remove Setting
            $tblSettingList = $this->getSettingAllByAccount($Entity);
            if ($tblSettingList) {
                /** @var TblSetting $tblSetting */
                foreach ($tblSettingList as $tblSetting) {
                    $this->destroySetting($tblSetting);
                }
            }
            // Remove Session
            $tblSessionList = $this->getSessionAllByAccount($Entity);
            if ($tblSessionList) {
                /** @var TblSession $tblSession */
                foreach ($tblSessionList as $tblSession) {
                    $this->destroySession($tblSession->getSession());
                }
            }
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $tblAccount
     *
     * @return null|TblAuthentication
     */
    public function getAuthenticationByAccount(TblAccount $tblAccount)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblAuthentication', array(
            TblAuthentication::ATTR_TBL_ACCOUNT => $tblAccount->getId()
        ));
    }

    /**
     * @param TblAccount $tblAccount
     * @param TblIdentification $tblIdentification
     *
     * @return bool
     */
    public function removeAccountAuthentication(TblAccount $tblAccount, TblIdentification $tblIdentification)
    {

        $Manager = $this->getEntityManager();
        /** @var TblAuthentication $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthentication', array(
            TblAuthentication::ATTR_TBL_ACCOUNT => $tblAccount->getId(),
            TblAuthentication::ATTR_TBL_IDENTIFICATION => $tblIdentification->getId()
        ));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $tblAccount
     *
     * @return bool|TblAuthorization[]
     */
    public function getAuthorizationAllByAccount(TblAccount $tblAccount)
    {

        return $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(), 'TblAuthorization',
            array(
                TblAuthorization::ATTR_TBL_ACCOUNT => $tblAccount->getId()
            ));
    }

    /**
     * @param TblAccount $tblAccount
     * @param TblRole $tblRole
     *
     * @return bool
     */
    public function removeAccountAuthorization(TblAccount $tblAccount, TblRole $tblRole)
    {

        $Manager = $this->getEntityManager();
        /** @var TblAuthorization $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblAuthorization', array(
            TblAuthorization::ATTR_TBL_ACCOUNT => $tblAccount->getId(),
            TblAuthorization::SERVICE_TBL_ROLE => $tblRole->getId()
        ));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $tblAccount
     *
     * @return null|TblSetting[]
     */
    public function getSettingAllByAccount(TblAccount $tblAccount)
    {

        return $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(), 'TblSetting', array(
            TblSetting::ATTR_TBL_ACCOUNT => $tblAccount->getId()
        ));
    }

    /**
     * @param TblSetting $tblSetting
     *
     * @return bool
     */
    public function destroySetting(TblSetting $tblSetting)
    {

        $Manager = $this->getEntityManager();
        /** @var TblSetting $Entity */
        $Entity = $this->getForceEntityById(__METHOD__, $Manager, 'TblSetting', $tblSetting->getId());
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblAccount $tblAccount
     *
     * @return bool|TblSession[]
     */
    public function getSessionAllByAccount(TblAccount $tblAccount)
    {

        return $this->getForceEntityListBy(__METHOD__, $this->getEntityManager(), 'TblSession', array(
            TblSession::ATTR_TBL_ACCOUNT => $tblAccount->getId()
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
     * @param TblAccount $tblAccount
     * @param string $Password
     *
     * @return bool
     */
    public function changePassword($Password, TblAccount $tblAccount = null)
    {

        if (null === $tblAccount) {
            $tblAccount = $this->getAccountBySession();
        }
        $Manager = $this->getEntityManager();
        /**
         * @var TblAccount $Protocol
         * @var TblAccount $Entity
         */
        $Entity = $Manager->getEntityById('TblAccount', $tblAccount->getId());
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
     * @param TblSession $tblSession
     * @param int $Timeout
     *
     * @return bool
     */
    private function changeTimeout(TblSession $tblSession, $Timeout)
    {

        $Manager = $this->getEntityManager();
        /**
         * @var TblSession $Entity
         */
        $Entity = $this->getForceEntityById(__METHOD__, $Manager, 'TblSession', $tblSession->getId());

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
     * @param TblConsumer $tblConsumer
     * @param TblAccount $tblAccount
     *
     * @return bool
     */
    public function changeConsumer(TblConsumer $tblConsumer, TblAccount $tblAccount = null)
    {

        if (null === $tblAccount) {
            $tblAccount = $this->getAccountBySession();
        }
        $Manager = $this->getEntityManager();
        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById('TblAccount', $tblAccount->getId());
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setServiceTblConsumer($tblConsumer);
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
