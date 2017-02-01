<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Group;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblGroupRole")
 * @Cache(usage="READ_ONLY")
 */
class TblGroupRole extends Element
{

    const ATTR_TBL_GROUP = 'TblGroup';
    const SERVICE_TBL_ROLE = 'serviceTblRole';

    /**
     * @Column(type="bigint")
     */
    protected $TblGroup;
    /**
     * @Column(type="bigint")
     */
    protected $serviceTblRole;

    /**
     * @return bool|TblGroup
     */
    public function getTblGroup()
    {

        if (null === $this->TblGroup) {
            return false;
        } else {
            return Group::useService()->getGroupById($this->TblGroup);
        }
    }

    /**
     * @param null|TblGroup $TblGroup
     */
    public function setTblGroup(TblGroup $TblGroup = null)
    {

        $this->TblGroup = (null === $TblGroup ? null : $TblGroup->getId());
    }

    /**
     * @return bool|TblRole
     */
    public function getServiceTblRole()
    {

        if (null === $this->serviceTblRole) {
            return false;
        } else {
            return Access::useService()->getRoleById($this->serviceTblRole);
        }
    }

    /**
     * @param null|TblRole $TblRole
     */
    public function setServiceTblRole(TblRole $TblRole = null)
    {

        $this->serviceTblRole = (null === $TblRole ? null : $TblRole->getId());
    }
}
