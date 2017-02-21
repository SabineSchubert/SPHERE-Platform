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
 * @Table(name="TblFavorite")
 * @Cache(usage="READ_ONLY")
 */
class TblFavorite extends Element
{
    const SERVICE_TBL_ACCOUNT = 'serviceTblAccount';
    const ATTR_ROUTE = 'Route';
    const ATTR_TITLE = 'Title';

    /**
     * @Column(type="string")
     */
    protected $Title;
    /**
     * @Column(type="string")
     */
    protected $Description;
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
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     * @param string $Title
     */
    public function setTitle($Title)
    {
        $this->Title = $Title;
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param string $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }
}