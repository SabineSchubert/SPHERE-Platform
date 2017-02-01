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
 * @Table(name="TblSetting")
 * @Cache(usage="READ_ONLY")
 */
class TblSetting extends Element
{

    const ATTR_TBL_ACCOUNT = 'TblAccount';
    const ATTR_IDENTIFIER = 'Identifier';

    /**
     * @Column(type="bigint")
     */
    protected $TblAccount;
    /**
     * @Column(type="string")
     */
    protected $Identifier;
    /**
     * @Column(type="text")
     */
    protected $Value;

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
     * @return string
     */
    public function getIdentifier()
    {

        return $this->Identifier;
    }

    /**
     * @param string $Identifier
     */
    public function setIdentifier($Identifier)
    {

        $this->Identifier = $Identifier;
    }

    /**
     * @return string
     */
    public function getValue()
    {

        return $this->Value;
    }

    /**
     * @param string $Value
     */
    public function setValue($Value)
    {

        $this->Value = $Value;
    }
}
