<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Group;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblGroupAccount")
 * @Cache(usage="READ_ONLY")
 */
class TblGroupAccount extends Element
{

    /**
     * @Column(type="bigint")
     */
    protected $TblGroup;
    /**
     * @Column(type="bigint")
     */
    protected $serviceTblAccount;

    /**
     * @return null|TblGroup
     */
    public function getTblGroup()
    {

        if (null === $this->TblGroup) {
            return null;
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
     * @return null|TblAccount
     */
    public function getServiceTblAccount()
    {

        if (null === $this->serviceTblAccount) {
            return null;
        } else {
            return Account::useService()->getAccountById($this->serviceTblAccount);
        }
    }

    /**
     * @param null|TblAccount $TblAccount
     */
    public function setTblAccount(TblAccount $TblAccount = null)
    {

        $this->serviceTblAccount = (null === $TblAccount ? null : $TblAccount->getId());
    }
}
