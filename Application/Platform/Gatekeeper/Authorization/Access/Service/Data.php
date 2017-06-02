<?php

namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblLevel;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblLevelPrivilege;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblPrivilege;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblPrivilegeRight;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRight;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRoleLevel;
use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\System\Cache\Handler\MemcachedHandler;
use SPHERE\System\Cache\Handler\MemoryHandler;
use SPHERE\System\Database\Binding\AbstractData;
use SPHERE\System\Database\Binding\AbstractEntity;
use SPHERE\System\Database\Fitting\Element;

/**
 * Class Data
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service
 */
class Data extends AbstractData
{

    public function setupDatabaseContent()
    {

        /**
         * CLOUD
         * Administrator (Setup Role)
         */
        $TblRoleCloud = $this->createRole('Administrator', true);

        // Level: Sphere-Platform
        $TblLevel = $this->createLevel('Sphere-Platform');
        $this->addRoleLevel($TblRoleCloud, $TblLevel);

        // Privilege: Sphere-System
        $TblPrivilege = $this->createPrivilege('Sphere-System');
        $this->addLevelPrivilege($TblLevel, $TblPrivilege);
        $TblRight = $this->createRight('/Platform');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/System');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Platform/System/Protocol');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/System/Archive');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Platform/System/Database');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/System/Database/Setup/Simulation');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/System/Database/Setup/Execution');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/System/Database/Setup/Upgrade');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Api/Platform/Database/Upgrade');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Platform/System/Cache');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Platform/System/Test');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/System/Test/Frontend');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/System/Test/Upload');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/System/Test/Upload/Delete');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/System/Test/Upload/Delete/Check');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        // Privilege: Sphere-Gatekeeper
        $TblPrivilege = $this->createPrivilege('Sphere-Gatekeeper');
        $this->addLevelPrivilege($TblLevel, $TblPrivilege);
        $TblRight = $this->createRight('/Platform');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/Gatekeeper');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/Role');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/RoleGrantLevel');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/Level');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/LevelGrantPrivilege');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/Privilege');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/PrivilegeGrantRight');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/Right');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Api/Platform/Gatekeeper/Authorization/Access/PrivilegeGrantRight');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Consumer');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Api/Platform/Gatekeeper/Consumer');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Account');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        // Level: Sphere-Setting
        $TblLevel = $this->createLevel('Sphere-Setting');
        $this->addRoleLevel($TblRoleCloud, $TblLevel);

        // Privilege: Sphere-MyAccount
        $TblPrivilege = $this->createPrivilege('Sphere-MyAccount');
        $this->addLevelPrivilege($TblLevel, $TblPrivilege);
        $TblRight = $this->createRight('/Setting');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Setting/MyAccount');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Setting/MyAccount/Password');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Setting/MyAccount/Consumer');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        /**
         * SERVER
         * Benutzer Einstellungen (No Role Setup)
         */

        // Level: Benutzer - Einstellungen
        $toRoleUserSetup = $TblLevel = $this->createLevel('Benutzer - Einstellungen');

        // Privilege: Benutzer - Mein Benutzerkonto
        $TblPrivilege = $this->createPrivilege('Benutzer - Mein Benutzerkonto');
        $this->addLevelPrivilege($TblLevel, $TblPrivilege);
        $TblRight = $this->createRight('/Setting');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Setting/MyAccount');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Setting/MyAccount/Password');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        /**
         * SERVER
         * Administrator Einstellungen (No Role Setup)
         */

        // Level: Administrator - Einstellungen
        $toRoleAdminSetup = $TblLevel = $this->createLevel('Administrator - Einstellungen');
        // !!! Add To CLOUD Administrator
        $this->addRoleLevel($TblRoleCloud, $TblLevel);

        // Privilege: Administrator - Mein Benutzerkonto
        $TblPrivilege = $this->createPrivilege('Administrator - Mein Benutzerkonto');
        $this->addLevelPrivilege($TblLevel, $TblPrivilege);
        $TblRight = $this->createRight('/Setting');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Setting/MyAccount');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Setting/MyAccount/Password');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        // Privilege: Administrator - Benutzerverwaltung
        $TblPrivilege = $this->createPrivilege('Administrator - Benutzerverwaltung');
        $this->addLevelPrivilege($TblLevel, $TblPrivilege);
        $TblRight = $this->createRight('/Setting');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Setting/Authorization');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        $TblRight = $this->createRight('/Setting/Authorization/Account');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $TblRight = $this->createRight('/Setting/Authorization/Account/Create');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);
        $this->createRight('/Setting/Authorization/Account/Edit');
        $TblRight = $this->createRight('/Setting/Authorization/Account/Destroy');
        $this->addPrivilegeRight($TblPrivilege, $TblRight);

        /**
         * Role: Einstellungen: Administrator
         */
        $TblRole = $this->createRole('Einstellungen: Administrator');
        $this->addRoleLevel($TblRole, $toRoleAdminSetup);

        /**
         * Role: Einstellungen: Benutzer
         */
        $TblRole = $this->createRole('Einstellungen: Benutzer');
        $this->addRoleLevel($TblRole, $toRoleUserSetup);

    }

    /**
     * @param string $Name
     * @param bool $IsInternal
     * @return TblRole
     * @internal param bool $IsSecure
     */
    public function createRole($Name, $IsInternal = false)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblRole', array(
            TblRole::ATTR_NAME => $Name
        ));
        if (null === $Entity) {
            $Entity = new TblRole();
            $Entity->setName($Name);
            $Entity->setInternal($IsInternal);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblLevel
     */
    public function createLevel($Name)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblLevel', array(
            TblLevel::ATTR_NAME => $Name
        ));
        if (null === $Entity) {
            $Entity = new TblLevel();
            $Entity->setName($Name);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblRole $TblRole
     * @param TblLevel $TblLevel
     *
     * @return TblRoleLevel
     */
    public function addRoleLevel(TblRole $TblRole, TblLevel $TblLevel)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblRoleLevel', array(
            TblRoleLevel::ATTR_TBL_ROLE => $TblRole->getId(),
            TblRoleLevel::ATTR_TBL_LEVEL => $TblLevel->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblRoleLevel();
            $Entity->setTblRole($TblRole);
            $Entity->setTblLevel($TblLevel);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblPrivilege
     */
    public function createPrivilege($Name)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblPrivilege', array(
            TblPrivilege::ATTR_NAME => $Name
        ));
        if (null === $Entity) {
            $Entity = new TblPrivilege();
            $Entity->setName($Name);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblLevel $TblLevel
     * @param TblPrivilege $TblPrivilege
     *
     * @return TblLevelPrivilege
     */
    public function addLevelPrivilege(TblLevel $TblLevel, TblPrivilege $TblPrivilege)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblLevelPrivilege', array(
            TblLevelPrivilege::ATTR_TBL_LEVEL => $TblLevel->getId(),
            TblLevelPrivilege::ATTR_TBL_PRIVILEGE => $TblPrivilege->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblLevelPrivilege();
            $Entity->setTblLevel($TblLevel);
            $Entity->setTblPrivilege($TblPrivilege);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param string $Route
     *
     * @return TblRight
     */
    public function createRight($Route)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblRight', array(
            TblRight::ATTR_ROUTE => $Route
        ));
        if (null === $Entity) {
            $Entity = new TblRight();
            $Entity->setRoute($Route);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblPrivilege $TblPrivilege
     * @param TblRight $TblRight
     *
     * @return TblPrivilegeRight
     */
    public function addPrivilegeRight(TblPrivilege $TblPrivilege, TblRight $TblRight)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblPrivilegeRight', array(
            TblPrivilegeRight::ATTR_TBL_PRIVILEGE => $TblPrivilege->getId(),
            TblPrivilegeRight::ATTR_TBL_RIGHT => $TblRight->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblPrivilegeRight();
            $Entity->setTblPrivilege($TblPrivilege);
            $Entity->setTblRight($TblRight);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param string $Name
     * @param bool $IsInternal
     * @return TblRole
     */
    public function insertRole($Name, $IsInternal = false)
    {
        $Entity = new TblRole();
        $Entity->setName($Name);
        $Entity->setInternal($IsInternal);

        $this->getEntityManager()->saveEntity($Entity);
        Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        return $Entity;
    }

    /**
     * @param TblRole $TblRole
     * @param string $Name
     * @param bool $IsInternal
     * @return TblRole|null
     */
    public function updateRole( TblRole $TblRole, $Name, $IsInternal = false)
    {

        $Manager = $this->getEntityManager();
        /** @var AbstractEntity|Element|TblRole $Entity */
        $Entity = $Manager->getEntityById($TblRole->getEntityShortName(), $TblRole->getId());
        if (null !== $Entity) {
            $Protocol = clone $Entity;
            $Entity->setName($Name);
            $Entity->setInternal($IsInternal);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createUpdateEntry($this->getConnection()->getDatabase(), $Protocol, $Entity);
            return $Entity;
        }
        return null;
    }

    /**
     * @param TblRole $TblRole
     * @param TblLevel $TblLevel
     *
     * @return bool
     */
    public function removeRoleLevel(TblRole $TblRole, TblLevel $TblLevel)
    {

        $Manager = $this->getEntityManager();
        /** @var TblRoleLevel $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblRoleLevel', array(
            TblRoleLevel::ATTR_TBL_ROLE => $TblRole->getId(),
            TblRoleLevel::ATTR_TBL_LEVEL => $TblLevel->getId()
        ));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblPrivilege $TblPrivilege
     * @param TblRight $TblRight
     *
     * @return bool
     */
    public function removePrivilegeRight(TblPrivilege $TblPrivilege, TblRight $TblRight)
    {

        $Manager = $this->getEntityManager();
        /** @var TblPrivilegeRight $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblPrivilegeRight', array(
            TblPrivilegeRight::ATTR_TBL_PRIVILEGE => $TblPrivilege->getId(),
            TblPrivilegeRight::ATTR_TBL_RIGHT => $TblRight->getId()
        ));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblLevel $TblLevel
     * @param TblPrivilege $TblPrivilege
     *
     * @return bool
     */
    public function removeLevelPrivilege(TblLevel $TblLevel, TblPrivilege $TblPrivilege)
    {

        $Manager = $this->getEntityManager();
        /** @var TblLevelPrivilege $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblLevelPrivilege', array(
            TblLevelPrivilege::ATTR_TBL_LEVEL => $TblLevel->getId(),
            TblLevelPrivilege::ATTR_TBL_PRIVILEGE => $TblPrivilege->getId()
        ));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param integer $Id
     *
     * @return null|TblRight
     */
    public function getRightById($Id)
    {

        return $this->getCachedEntityById(__METHOD__, $this->getEntityManager(), 'TblRight', $Id);
    }

    /**
     * @param string $Name
     *
     * @return null|TblRight
     */
    public function getRightByName($Name)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblRight', array(
            TblRight::ATTR_ROUTE => $Name
        ));
    }

    /**
     * @param string $Name
     *
     * @return bool
     */
    public function existsRightByName($Name)
    {

        // 1. Level Cache
        $Memory = $this->getCache(new MemoryHandler());
        if (null === ($RouteList = $Memory->getValue(__METHOD__, __METHOD__))) {
            // 2. Level Cache
            $Cache = $this->getCache(new MemcachedHandler());
            if (null === ($RouteList = $Cache->getValue(__METHOD__, __METHOD__))) {
                $RouteList = $this->getEntityManager()->getQueryBuilder()
                    ->select('R.Route')
                    ->from(__NAMESPACE__ . '\Entity\TblRight', 'R')
                    ->distinct()
                    ->getQuery()
                    ->getResult("COLUMN_HYDRATOR");
                $Cache->setValue(__METHOD__, $RouteList, 0, __METHOD__);
            }
            $Memory->setValue(__METHOD__, $RouteList, 0, __METHOD__);
        }
        return in_array($Name, $RouteList);
    }

    /**
     * @return null|TblRight[]
     */
    public function getRightAll()
    {

        return $this->getCachedEntityList(__METHOD__, $this->getEntityManager(), 'TblRight');
    }

    /**
     * @param integer $Id
     *
     * @return null|TblLevel
     */
    public function getLevelById($Id)
    {

        return $this->getCachedEntityById(__METHOD__, $this->getEntityManager(), 'TblLevel', $Id);
    }

    /**
     * @param string $Name
     *
     * @return null|TblLevel
     */
    public function getLevelByName($Name)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblLevel', array(
            TblLevel::ATTR_NAME => $Name
        ));
    }

    /**
     * @param integer $Id
     *
     * @return null|TblPrivilege
     */
    public function getPrivilegeById($Id)
    {

        return $this->getCachedEntityById(__METHOD__, $this->getEntityManager(), 'TblPrivilege', $Id);
    }

    /**
     * @param string $Name
     *
     * @return null|TblPrivilege
     */
    public function getPrivilegeByName($Name)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblPrivilege', array(
            TblPrivilege::ATTR_NAME => $Name
        ));
    }

    /**
     *
     * @param TblLevel $TblLevel
     *
     * @return null|TblPrivilege[]
     */
    public function getPrivilegeAllByLevel(TblLevel $TblLevel)
    {
        /** @var TblLevelPrivilege[] $EntityList */
        $EntityList = $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(), 'TblLevelPrivilege', array(
            TblLevelPrivilege::ATTR_TBL_LEVEL => $TblLevel->getId()
        ));
        if ($EntityList) {
            array_walk($EntityList, function (TblLevelPrivilege &$V) {

                $V = $V->getTblPrivilege();
            });
            $EntityList = array_filter($EntityList);
        }
        /** @var TblPrivilege[] $EntityList */
        return (empty($EntityList) ? null : $EntityList);
    }

    /**
     *
     * @param TblPrivilege $TblPrivilege
     *
     * @return null|TblRight[]
     */
    public function getRightAllByPrivilege(TblPrivilege $TblPrivilege)
    {

        /** @var TblPrivilegeRight[] $EntityList */
        $EntityList = $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(), 'TblPrivilegeRight', array(
            TblPrivilegeRight::ATTR_TBL_PRIVILEGE => $TblPrivilege->getId()
        ));
        if ($EntityList) {
            array_walk($EntityList, function (TblPrivilegeRight &$V) {

                $V = $V->getTblRight();
            });
            $EntityList = array_filter($EntityList);
        }
        /** @var TblRight[] $EntityList */
        return (empty($EntityList) ? null : $EntityList);
    }

    /**
     * @return null|TblPrivilege[]
     */
    public function getPrivilegeAll()
    {

        return $this->getCachedEntityList(__METHOD__, $this->getEntityManager(), 'TblPrivilege');
    }

    /**
     * @return null|TblLevel[]
     */
    public function getLevelAll()
    {

        return $this->getCachedEntityList(__METHOD__, $this->getEntityManager(), 'TblLevel');
    }

    /**
     * @param integer $Id
     *
     * @return null|TblRole|AbstractEntity|Element
     */
    public function getRoleById($Id)
    {

        return $this->getCachedEntityById(
            __METHOD__, $this->getEntityManager(), (new TblRole())->getEntityShortName(), $Id
        );
    }

    /**
     * @param string $Name
     *
     * @return null|TblRole|AbstractEntity|Element
     */
    public function getRoleByName($Name)
    {

        return $this->getCachedEntityBy(
            __METHOD__, $this->getEntityManager(), (new TblRole())->getEntityShortName(), array(
            TblRole::ATTR_NAME => $Name
        )
        );
    }

    /**
     * @return null|TblRole[]
     */
    public function getRoleAll()
    {

        return $this->getCachedEntityList(__METHOD__, $this->getEntityManager(), 'TblRole');
    }

    /**
     *
     * @param TblRole $TblRole
     *
     * @return null|TblLevel[]
     */
    public function getLevelAllByRole(TblRole $TblRole)
    {

        $EntityList = $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(),
            'TblRoleLevel',
            array(
                TblRoleLevel::ATTR_TBL_ROLE => $TblRole->getId()
            )
        );
        if ($EntityList) {
            array_walk($EntityList, function (TblRoleLevel &$V) {

                $V = $V->getTblLevel();
            });
            $EntityList = array_filter($EntityList);
        }
        /** @var TblLevel[] $EntityList */
        return (empty($EntityList) ? null : $EntityList);
    }
}
