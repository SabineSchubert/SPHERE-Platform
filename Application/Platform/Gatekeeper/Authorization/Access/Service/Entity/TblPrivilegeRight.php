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
 * @Table(name="TblPrivilegeRight")
 * @Cache(usage="READ_ONLY")
 */
class TblPrivilegeRight extends Element
{

    const ATTR_TBL_PRIVILEGE = 'TblPrivilege';
    const ATTR_TBL_RIGHT = 'TblRight';

    /**
     * @Column(type="bigint")
     */
    protected $TblPrivilege;
    /**
     * @Column(type="bigint")
     */
    protected $TblRight;

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
     * @return bool|TblRight
     */
    public function getTblRight()
    {

        if (null === $this->TblRight) {
            return false;
        } else {
            return Access::useService()->getRightById($this->TblRight);
        }
    }

    /**
     * @param null|TblRight $TblRight
     */
    public function setTblRight(TblRight $TblRight = null)
    {

        $this->TblRight = (null === $TblRight ? null : $TblRight->getId());
    }
}
