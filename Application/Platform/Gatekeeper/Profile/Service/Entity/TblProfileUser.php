<?php

namespace SPHERE\Application\Platform\Gatekeeper\Profile\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblProfileUser
 * @package SPHERE\Application\Platform\Gatekeeper\Profile\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblProfileUser")
 * @ORM\Cache(usage="READ_ONLY")
 */
class TblProfileUser extends AbstractEntity
{
    /**
     * @ORM\Column(type="bigint")
     */
    protected $serviceTblAccount;

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
    public function setServiceTblAccount(TblAccount $TblAccount = null)
    {

        $this->serviceTblAccount = (null === $TblAccount ? null : $TblAccount->getId());
    }
}