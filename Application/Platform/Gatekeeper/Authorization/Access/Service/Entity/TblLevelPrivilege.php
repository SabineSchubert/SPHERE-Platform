<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblLevelPrivilege")
 * @Cache(usage="READ_ONLY")
 */
class TblLevelPrivilege extends Element
{

    const ATTR_TBL_LEVEL = 'TblLevel';
    const ATTR_TBL_PRIVILEGE = 'TblPrivilege';

    /**
     * @Column(type="bigint")
     */
    protected $TblLevel;
    /**
     * @Column(type="bigint")
     */
    protected $TblPrivilege;

    /**
     * @return bool|TblPrivilege
     */
    public function getTblPrivilege()
    {

        if (null === $this->TblPrivilege) {
            return false;
        } else {
            return Access::useService()->getPrivilegeById($this->TblPrivilege);
        }
    }

    /**
     * @param null|TblPrivilege $TblPrivilege
     */
    public function setTblPrivilege(TblPrivilege $TblPrivilege = null)
    {

        $this->TblPrivilege = (null === $TblPrivilege ? null : $TblPrivilege->getId());
    }

    /**
     * @return bool|TblLevel
     */
    public function getTblLevel()
    {

        if (null === $this->TblLevel) {
            return false;
        } else {
            return Access::useService()->getLevelById($this->TblLevel);
        }
    }

    /**
     * @param null|TblLevel $TblLevel
     */
    public function setTblLevel(TblLevel $TblLevel = null)
    {

        $this->TblLevel = (null === $TblLevel ? null : $TblLevel->getId());
    }
}
