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
        $tblRoleCloud = $this->createRole('Administrator', true);

        // Level: Sphere-Platform
        $tblLevel = $this->createLevel('Sphere-Platform');
        $this->addRoleLevel($tblRoleCloud, $tblLevel);

        // Privilege: Sphere-System
        $tblPrivilege = $this->createPrivilege('Sphere-System');
        $this->addLevelPrivilege($tblLevel, $tblPrivilege);
        $tblRight = $this->createRight('/Platform');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/System');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Platform/System/Protocol');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/System/Archive');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Platform/System/Database');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/System/Database/Setup/Simulation');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/System/Database/Setup/Execution');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/System/Database/Setup/Upgrade');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Api/Platform/Database/Upgrade');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Platform/System/Cache');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Platform/System/Test');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/System/Test/Frontend');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/System/Test/Upload');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/System/Test/Upload/Delete');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/System/Test/Upload/Delete/Check');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        // Privilege: Sphere-Gatekeeper
        $tblPrivilege = $this->createPrivilege('Sphere-Gatekeeper');
        $this->addLevelPrivilege($tblLevel, $tblPrivilege);
        $tblRight = $this->createRight('/Platform');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/Gatekeeper');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/Role');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/RoleGrantLevel');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/Level');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/LevelGrantPrivilege');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/Privilege');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/PrivilegeGrantRight');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Access/Right');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Api/Platform/Gatekeeper/Authorization/Access/PrivilegeGrantRight');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Consumer');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Platform/Gatekeeper/Authorization/Account');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        // Level: Sphere-Setting
        $tblLevel = $this->createLevel('Sphere-Setting');
        $this->addRoleLevel($tblRoleCloud, $tblLevel);

        // Privilege: Sphere-MyAccount
        $tblPrivilege = $this->createPrivilege('Sphere-MyAccount');
        $this->addLevelPrivilege($tblLevel, $tblPrivilege);
        $tblRight = $this->createRight('/Setting');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Setting/MyAccount');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Setting/MyAccount/Password');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Setting/MyAccount/Consumer');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        /**
         * SERVER
         * Benutzer Einstellungen (No Role Setup)
         */

        // Level: Benutzer - Einstellungen
        $toRoleUserSetup = $tblLevel = $this->createLevel('Benutzer - Einstellungen');

        // Privilege: Benutzer - Mein Benutzerkonto
        $tblPrivilege = $this->createPrivilege('Benutzer - Mein Benutzerkonto');
        $this->addLevelPrivilege($tblLevel, $tblPrivilege);
        $tblRight = $this->createRight('/Setting');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Setting/MyAccount');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Setting/MyAccount/Password');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        /**
         * SERVER
         * Administrator Einstellungen (No Role Setup)
         */

        // Level: Administrator - Einstellungen
        $toRoleAdminSetup = $tblLevel = $this->createLevel('Administrator - Einstellungen');
        // !!! Add To CLOUD Administrator
        $this->addRoleLevel($tblRoleCloud, $tblLevel);

        // Privilege: Administrator - Mein Benutzerkonto
        $tblPrivilege = $this->createPrivilege('Administrator - Mein Benutzerkonto');
        $this->addLevelPrivilege($tblLevel, $tblPrivilege);
        $tblRight = $this->createRight('/Setting');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Setting/MyAccount');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Setting/MyAccount/Password');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        // Privilege: Administrator - Benutzerverwaltung
        $tblPrivilege = $this->createPrivilege('Administrator - Benutzerverwaltung');
        $this->addLevelPrivilege($tblLevel, $tblPrivilege);
        $tblRight = $this->createRight('/Setting');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Setting/Authorization');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        $tblRight = $this->createRight('/Setting/Authorization/Account');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $tblRight = $this->createRight('/Setting/Authorization/Account/Create');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);
        $this->createRight('/Setting/Authorization/Account/Edit');
        $tblRight = $this->createRight('/Setting/Authorization/Account/Destroy');
        $this->addPrivilegeRight($tblPrivilege, $tblRight);

        /**
         * Role: Einstellungen: Administrator
         */
        $tblRole = $this->createRole('Einstellungen: Administrator');
        $this->addRoleLevel($tblRole, $toRoleAdminSetup);

        /**
         * Role: Einstellungen: Benutzer
         */
        $tblRole = $this->createRole('Einstellungen: Benutzer');
        $this->addRoleLevel($tblRole, $toRoleUserSetup);

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
            $Entity = new TblLevel($Name);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblRole $tblRole
     * @param TblLevel $tblLevel
     *
     * @return TblRoleLevel
     */
    public function addRoleLevel(TblRole $tblRole, TblLevel $tblLevel)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblRoleLevel', array(
            TblRoleLevel::ATTR_TBL_ROLE => $tblRole->getId(),
            TblRoleLevel::ATTR_TBL_LEVEL => $tblLevel->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblRoleLevel();
            $Entity->setTblRole($tblRole);
            $Entity->setTblLevel($tblLevel);
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
            $Entity = new TblPrivilege($Name);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblLevel $tblLevel
     * @param TblPrivilege $tblPrivilege
     *
     * @return TblLevelPrivilege
     */
    public function addLevelPrivilege(TblLevel $tblLevel, TblPrivilege $tblPrivilege)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblLevelPrivilege', array(
            TblLevelPrivilege::ATTR_TBL_LEVEL => $tblLevel->getId(),
            TblLevelPrivilege::ATTR_TBL_PRIVILEGE => $tblPrivilege->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblLevelPrivilege();
            $Entity->setTblLevel($tblLevel);
            $Entity->setTblPrivilege($tblPrivilege);
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
            $Entity = new TblRight($Route);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblPrivilege $tblPrivilege
     * @param TblRight $tblRight
     *
     * @return TblPrivilegeRight
     */
    public function addPrivilegeRight(TblPrivilege $tblPrivilege, TblRight $tblRight)
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblPrivilegeRight', array(
            TblPrivilegeRight::ATTR_TBL_PRIVILEGE => $tblPrivilege->getId(),
            TblPrivilegeRight::ATTR_TBL_RIGHT => $tblRight->getId()
        ));
        if (null === $Entity) {
            $Entity = new TblPrivilegeRight();
            $Entity->setTblPrivilege($tblPrivilege);
            $Entity->setTblRight($tblRight);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblRole $tblRole
     * @param TblLevel $tblLevel
     *
     * @return bool
     */
    public function removeRoleLevel(TblRole $tblRole, TblLevel $tblLevel)
    {

        $Manager = $this->getEntityManager();
        /** @var TblRoleLevel $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblRoleLevel', array(
            TblRoleLevel::ATTR_TBL_ROLE => $tblRole->getId(),
            TblRoleLevel::ATTR_TBL_LEVEL => $tblLevel->getId()
        ));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblPrivilege $tblPrivilege
     * @param TblRight $tblRight
     *
     * @return bool
     */
    public function removePrivilegeRight(TblPrivilege $tblPrivilege, TblRight $tblRight)
    {

        $Manager = $this->getEntityManager();
        /** @var TblPrivilegeRight $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblPrivilegeRight', array(
            TblPrivilegeRight::ATTR_TBL_PRIVILEGE => $tblPrivilege->getId(),
            TblPrivilegeRight::ATTR_TBL_RIGHT => $tblRight->getId()
        ));
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblLevel $tblLevel
     * @param TblPrivilege $tblPrivilege
     *
     * @return bool
     */
    public function removeLevelPrivilege(TblLevel $tblLevel, TblPrivilege $tblPrivilege)
    {

        $Manager = $this->getEntityManager();
        /** @var TblLevelPrivilege $Entity */
        $Entity = $this->getForceEntityBy(__METHOD__, $Manager, 'TblLevelPrivilege', array(
            TblLevelPrivilege::ATTR_TBL_LEVEL => $tblLevel->getId(),
            TblLevelPrivilege::ATTR_TBL_PRIVILEGE => $tblPrivilege->getId()
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
     * @param TblLevel $tblLevel
     *
     * @return null|TblPrivilege[]
     */
    public function getPrivilegeAllByLevel(TblLevel $tblLevel)
    {
        /** @var TblLevelPrivilege[] $EntityList */
        $EntityList = $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(), 'TblLevelPrivilege', array(
            TblLevelPrivilege::ATTR_TBL_LEVEL => $tblLevel->getId()
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
     * @param TblPrivilege $tblPrivilege
     *
     * @return null|TblRight[]
     */
    public function getRightAllByPrivilege(TblPrivilege $tblPrivilege)
    {

        /** @var TblPrivilegeRight[] $EntityList */
        $EntityList = $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(), 'TblPrivilegeRight', array(
            TblPrivilegeRight::ATTR_TBL_PRIVILEGE => $tblPrivilege->getId()
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
     * @return null|TblRole
     */
    public function getRoleById($Id)
    {

        return $this->getCachedEntityById(__METHOD__, $this->getEntityManager(), 'TblRole', $Id);
    }

    /**
     * @param string $Name
     *
     * @return null|TblRole
     */
    public function getRoleByName($Name)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblRole', array(
            TblRole::ATTR_NAME => $Name
        ));
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
     * @param TblRole $tblRole
     *
     * @return null|TblLevel[]
     */
    public function getLevelAllByRole(TblRole $tblRole)
    {

        $EntityList = $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(),
            'TblRoleLevel',
            array(
                TblRoleLevel::ATTR_TBL_ROLE => $tblRole->getId()
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
