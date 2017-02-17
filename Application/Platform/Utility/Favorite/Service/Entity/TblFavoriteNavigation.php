<?php
namespace SPHERE\Application\Platform\Utility\Favorite\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblFavoriteNavigation")
 * @Cache(usage="READ_ONLY")
 */
class TblFavoriteNavigation extends Element
{
    const SERVICE_TBL_ACCOUNT = 'serviceTblAccount';
    const ATTR_ROUTE = 'Route';

    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="string")
     */
    protected $Route;
    /**
     * @Column(type="bigint")
     */
    protected $serviceTblAccount;

    /**
     * @return bool|TblAccount
     */
    public function getServiceTblAccount()
    {

        if (null === $this->serviceTblAccount) {
            return false;
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     */
    public function setName($Name)
    {
        $this->Name = $Name;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->Route;
    }

    /**
     * @param string $Route
     */
    public function setRoute($Route)
    {
        $this->Route = $Route;
    }
}