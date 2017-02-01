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
 * @Table(name="TblRoleLevel")
 * @Cache(usage="READ_ONLY")
 */
class TblRoleLevel extends Element
{

    const ATTR_TBL_ROLE = 'TblRole';
    const ATTR_TBL_LEVEL = 'TblLevel';

    /**
     * @Column(type="bigint")
     */
    protected $TblRole;
    /**
     * @Column(type="bigint")
     */
    protected $TblLevel;

    /**
     * @return bool|TblRole
     */
    public function getTblRole()
    {

        if (null === $this->TblRole) {
            return false;
        } else {
            return Access::useService()->getRoleById($this->TblRole);
        }
    }

    /**
     * @param null|TblRole $TblRole
     */
    public function setTblRole(TblRole $TblRole = null)
    {

        $this->TblRole = (null === $TblRole ? null : $TblRole->getId());
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
