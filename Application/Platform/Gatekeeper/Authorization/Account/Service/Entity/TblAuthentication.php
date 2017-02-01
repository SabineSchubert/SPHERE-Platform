<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblAuthentication")
 * @Cache(usage="READ_ONLY")
 */
class TblAuthentication extends Element
{

    const ATTR_TBL_ACCOUNT = 'TblAccount';
    const ATTR_TBL_IDENTIFICATION = 'TblIdentification';

    /**
     * @Column(type="bigint")
     */
    protected $TblAccount;
    /**
     * @Column(type="bigint")
     */
    protected $TblIdentification;

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
     * @return bool|TblIdentification
     */
    public function getTblIdentification()
    {

        if (null === $this->TblIdentification) {
            return false;
        } else {
            return Account::useService()->getIdentificationById($this->TblIdentification);
        }
    }

    /**
     * @param null|TblIdentification $TblIdentification
     */
    public function setTblIdentification(TblIdentification $TblIdentification = null)
    {

        $this->TblIdentification = (null === $TblIdentification ? null : $TblIdentification->getId());
    }
}
