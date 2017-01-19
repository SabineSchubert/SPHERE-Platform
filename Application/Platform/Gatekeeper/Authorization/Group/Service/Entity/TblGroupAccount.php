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
 * @Table(name="tblGroupAccount")
 * @Cache(usage="READ_ONLY")
 */
class TblGroupAccount extends Element
{

    /**
     * @Column(type="bigint")
     */
    protected $tblGroup;
    /**
     * @Column(type="bigint")
     */
    protected $serviceTblAccount;

    /**
     * @return null|TblGroup
     */
    public function getTblGroup()
    {

        if (null === $this->tblGroup) {
            return null;
        } else {
            return Group::useService()->getGroupById($this->tblGroup);
        }
    }

    /**
     * @param null|TblGroup $tblGroup
     */
    public function setTblGroup(TblGroup $tblGroup = null)
    {

        $this->tblGroup = ( null === $tblGroup ? null : $tblGroup->getId() );
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
     * @param null|TblAccount $tblAccount
     */
    public function setTblAccount(TblAccount $tblAccount = null)
    {

        $this->serviceTblAccount = ( null === $tblAccount ? null : $tblAccount->getId() );
    }
}
