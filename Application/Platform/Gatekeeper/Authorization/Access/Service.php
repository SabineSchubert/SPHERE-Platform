<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Access;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Data;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblLevel;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblLevelPrivilege;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblPrivilege;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblPrivilegeRight;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRight;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRoleLevel;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Setup;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Common\Frontend\Form\IFormInterface;
use SPHERE\Common\Window\Redirect;
use SPHERE\System\Cache\Handler\MemcachedHandler;
use SPHERE\System\Database\Binding\AbstractService;

/**
 * Class Service
 *
 * @package SPHERE\Application\System\Gatekeeper\Authorization\Access
 */
class Service extends AbstractService
{

    /** @var array $AuthorizationRequest */
    private static $AuthorizationRequest = array();
    /** @var array $AuthorizationCache */
    private static $AuthorizationCache = array();

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
     * @param string $Route
     *
     * @return bool
     */
    public function hasAuthorization($Route)
    {

        // Sanitize Route
        $Route = '/' . trim($Route, '/');

        // Cache
        $this->hydrateAuthorization();
        if (in_array($Route, self::$AuthorizationCache) || in_array($Route, self::$AuthorizationRequest)) {
            return true;
        }
        if ($this->existsRightByName($Route) || preg_match('!^/Api/!is', $Route)) {
            // MUST BE protected -> Access denied
            return false;
        } else {
            // Access valid PUBLIC -> Access granted
            self::$AuthorizationRequest[] = $Route;
            return true;
        }
    }

    private function hydrateAuthorization()
    {

        if (empty(self::$AuthorizationCache)) {
            if (($tblAccount = Account::useService()->getAccountBySession())) {
                $Cache = $this->getCache(new MemcachedHandler());
                if (!($AuthorizationCache = $Cache->getValue($tblAccount->getId(), __METHOD__))) {
                    if (($tblAuthorizationAll = Account::useService()->getAuthorizationAllByAccount($tblAccount))) {
                        /** @var \SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAuthorization $tblAuthorization */
                        foreach ($tblAuthorizationAll as $tblAuthorization) {
                            $tblRole = $tblAuthorization->getServiceTblRole();
                            if ($tblRole && (false !== ($tblLevelAll = $tblRole->getTblLevelAll()))) {
                                /** @var TblLevel $tblLevel */
                                foreach ($tblLevelAll as $tblLevel) {
                                    $tblPrivilegeAll = $tblLevel->getTblPrivilegeAll();
                                    if ($tblPrivilegeAll) {
                                        /** @var TblPrivilege $tblPrivilege */
                                        foreach ($tblPrivilegeAll as $tblPrivilege) {
                                            $tblRightAll = $tblPrivilege->getTblRightAll();
                                            if ($tblRightAll) {
                                                /** @var TblRight $tblRight */
                                                foreach ($tblRightAll as $tblRight) {
                                                    if (!in_array($tblRight->getRoute(), self::$AuthorizationCache)) {
                                                        array_push(self::$AuthorizationCache, $tblRight->getRoute());
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $Cache->setValue($tblAccount->getId(), self::$AuthorizationCache, 0, __METHOD__);
                } else {
                    self::$AuthorizationCache = $AuthorizationCache;
                }
            }
        }
    }

    /**
     * @param string $Name
     *
     * @return bool
     */
    public function existsRightByName($Name)
    {

        return (new Data($this->getBinding()))->existsRightByName($Name);
    }

    /**
     * @param string $Name
     *
     * @return null|TblRight
     */
    public function getRightByName($Name)
    {

        return (new Data($this->getBinding()))->getRightByName($Name);
    }

    /**
     * @param integer $Id
     *
     * @return null|TblRight
     */
    public function getRightById($Id)
    {

        return (new Data($this->getBinding()))->getRightById($Id);
    }

    /**
     * @return null|TblRight[]
     */
    public function getRightAll()
    {

        return (new Data($this->getBinding()))->getRightAll();
    }

    /**
     * @param IFormInterface $Form
     * @param null|string $Name
     *
     * @return IFormInterface|Redirect
     */
    public function createRight(IFormInterface $Form, $Name)
    {

        if (null !== $Name && empty($Name)) {
            $Form->setError('Name', 'Bitte geben Sie einen Namen ein');
        }
        if (!empty($Name)) {
            $Form->setSuccess('Name', 'Das Recht wurde hinzugefügt');
            (new Data($this->getBinding()))->createRight($Name);
            return new Redirect('/Platform/Gatekeeper/Authorization/Access/Right', 0);
        }
        return $Form;
    }

    /**
     * @param IFormInterface $Form
     * @param null|string $Name
     *
     * @return IFormInterface|Redirect
     */
    public function createPrivilege(IFormInterface $Form, $Name)
    {

        if (null !== $Name && empty($Name)) {
            $Form->setError('Name', 'Bitte geben Sie einen Namen ein');
        }
        if (!empty($Name)) {
            $Form->setSuccess('Name', 'Das Privileg wurde hinzugefügt');
            (new Data($this->getBinding()))->createPrivilege($Name);
            return new Redirect('/Platform/Gatekeeper/Authorization/Access/Privilege', 0);
        }
        return $Form;
    }

    /**
     * @param integer $Id
     *
     * @return null|TblPrivilege
     */
    public function getPrivilegeById($Id)
    {

        return (new Data($this->getBinding()))->getPrivilegeById($Id);
    }

    /**
     * @param string $Name
     *
     * @return null|TblPrivilege
     */
    public function getPrivilegeByName($Name)
    {

        return (new Data($this->getBinding()))->getPrivilegeByName($Name);
    }

    /**
     * @return null|TblPrivilege[]
     */
    public function getPrivilegeAll()
    {

        return (new Data($this->getBinding()))->getPrivilegeAll();
    }

    /**
     * @param IFormInterface $Form
     * @param null|string $Name
     *
     * @return IFormInterface|Redirect
     */
    public function createLevel(IFormInterface $Form, $Name)
    {

        if (null !== $Name && empty($Name)) {
            $Form->setError('Name', 'Bitte geben Sie einen Namen ein');
        }
        if (!empty($Name)) {
            $Form->setSuccess('Name', 'Das Zugriffslevel wurde hinzugefügt');
            (new Data($this->getBinding()))->createLevel($Name);
            return new Redirect('/Platform/Gatekeeper/Authorization/Access/Level', 0);
        }
        return $Form;
    }

    /**
     * @param integer $Id
     *
     * @return null|TblLevel
     */
    public function getLevelById($Id)
    {

        return (new Data($this->getBinding()))->getLevelById($Id);
    }

    /**
     * @param string $Name
     *
     * @return null|TblLevel
     */
    public function getLevelByName($Name)
    {

        return (new Data($this->getBinding()))->getLevelByName($Name);
    }

    /**
     * @return null|TblLevel[]
     */
    public function getLevelAll()
    {

        return (new Data($this->getBinding()))->getLevelAll();
    }

    /**
     * @param IFormInterface $Form
     * @param null|string $Name
     *
     * @param bool $IsSecure
     *
     * @return IFormInterface|Redirect
     */
    public function createRole(IFormInterface $Form, $Name, $IsSecure = false)
    {

        if (null !== $Name && empty($Name)) {
            $Form->setError('Name', 'Bitte geben Sie einen Namen ein');
        }
        if (!empty($Name)) {
            $Form->setSuccess('Name', 'Die Rolle wurde hinzugefügt');
            (new Data($this->getBinding()))->createRole($Name);
            return new Redirect('/Platform/Gatekeeper/Authorization/Access/Role', Redirect::TIMEOUT_SUCCESS);
        }
        return $Form;
    }

    /**
     * @param integer $Id
     *
     * @return null|TblRole
     */
    public function getRoleById($Id)
    {

        return (new Data($this->getBinding()))->getRoleById($Id);
    }

    /**
     * @param string $Name
     *
     * @return null|TblRole
     */
    public function getRoleByName($Name)
    {

        return (new Data($this->getBinding()))->getRoleByName($Name);
    }

    /**
     * @return null|TblRole[]
     */
    public function getRoleAll()
    {

        return (new Data($this->getBinding()))->getRoleAll();
    }

    /**
     *
     * @param TblRole $tblRole
     *
     * @return null|TblLevel[]
     */
    public function getLevelAllByRole(TblRole $tblRole)
    {

        return (new Data($this->getBinding()))->getLevelAllByRole($tblRole);
    }

    /**
     *
     * @param TblPrivilege $tblPrivilege
     *
     * @return null|TblRight[]
     */
    public function getRightAllByPrivilege(TblPrivilege $tblPrivilege)
    {

        return (new Data($this->getBinding()))->getRightAllByPrivilege($tblPrivilege);
    }

    /**
     *
     * @param TblLevel $tblLevel
     *
     * @return null|TblPrivilege[]
     */
    public function getPrivilegeAllByLevel(TblLevel $tblLevel)
    {

        return (new Data($this->getBinding()))->getPrivilegeAllByLevel($tblLevel);
    }

    /**
     * @param TblRole $tblRole
     * @param TblLevel $tblLevel
     *
     * @return TblRoleLevel
     */
    public function addRoleLevel(TblRole $tblRole, TblLevel $tblLevel)
    {

        return (new Data($this->getBinding()))->addRoleLevel($tblRole, $tblLevel);
    }

    /**
     * @param TblRole $tblRole
     * @param TblLevel $tblLevel
     *
     * @return bool
     */
    public function removeRoleLevel(TblRole $tblRole, TblLevel $tblLevel)
    {

        return (new Data($this->getBinding()))->removeRoleLevel($tblRole, $tblLevel);
    }

    /**
     * @param TblLevel $tblLevel
     * @param TblPrivilege $tblPrivilege
     *
     * @return bool
     */
    public function removeLevelPrivilege(TblLevel $tblLevel, TblPrivilege $tblPrivilege)
    {

        return (new Data($this->getBinding()))->removeLevelPrivilege($tblLevel, $tblPrivilege);
    }

    /**
     * @param TblPrivilege $tblPrivilege
     * @param TblRight $tblRight
     *
     * @return bool
     */
    public function removePrivilegeRight(TblPrivilege $tblPrivilege, TblRight $tblRight)
    {

        return (new Data($this->getBinding()))->removePrivilegeRight($tblPrivilege, $tblRight);
    }

    /**
     * @param TblPrivilege $tblPrivilege
     * @param TblRight $tblRight
     *
     * @return TblPrivilegeRight
     */
    public function addPrivilegeRight(TblPrivilege $tblPrivilege, TblRight $tblRight)
    {

        return (new Data($this->getBinding()))->addPrivilegeRight($tblPrivilege, $tblRight);
    }

    /**
     * @param TblLevel $tblLevel
     * @param TblPrivilege $tblPrivilege
     *
     * @return TblLevelPrivilege
     */
    public function addLevelPrivilege(TblLevel $tblLevel, TblPrivilege $tblPrivilege)
    {

        return (new Data($this->getBinding()))->addLevelPrivilege($tblLevel, $tblPrivilege);
    }
}