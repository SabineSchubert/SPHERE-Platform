<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblAuthorization")
 * @Cache(usage="READ_ONLY")
 */
class TblAuthorization extends Element
{

    const ATTR_TBL_ACCOUNT = 'TblAccount';
    const SERVICE_TBL_ROLE = 'serviceTblRole';

    /**
     * @Column(type="bigint")
     */
    protected $TblAccount;
    /**
     * @Column(type="bigint")
     */
    protected $serviceTblRole;

    /**
     * @return bool|TblAccount
     */
    public function getTblAccount()
    {

        if (null === $this->TblAccount) {
            return false;
        } else {
            return Account::useService()->getAccountById($this->TblAccount);
        }
    }

    /**
     * @param null|TblAccount $TblAccount
     */
    public function setTblAccount(TblAccount $TblAccount = null)
    {

        $this->TblAccount = (null === $TblAccount ? null : $TblAccount->getId());
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
