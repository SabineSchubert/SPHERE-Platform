<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Service\Entity\TblConsumer;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblAccount")
 * @Cache(usage="READ_ONLY")
 */
class TblAccount extends Element
{

    const ATTR_USERNAME = 'Username';
    const ATTR_PASSWORD = 'Password';
    const SERVICE_TBL_CONSUMER = 'serviceTblConsumer';
    /**
     * @Column(type="string")
     */
    protected $Username;
    /**
     * @Column(type="string")
     */
    protected $Password;
    /**
     * @Column(type="bigint")
     */
    protected $serviceTblConsumer;

    /**
     * @return string
     */
    public function getPassword()
    {

        return $this->Password;
    }

    /**
     * @param string $Password
     */
    public function setPassword($Password)
    {

        $this->Password = $Password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {

        return $this->Username;
    }

    /**
     * @param string $Username
     */
    public function setUsername($Username)
    {

        $this->Username = $Username;
    }

    /**
     * @return bool|TblConsumer
     */
    public function getServiceTblConsumer()
    {

        if (null === $this->serviceTblConsumer) {
            return false;
        } else {
            return Consumer::useService()->getConsumerById($this->serviceTblConsumer);
        }
    }

    /**
     * @param null|TblConsumer $TblConsumer
     */
    public function setServiceTblConsumer(TblConsumer $TblConsumer = null)
    {

        $this->serviceTblConsumer = (null === $TblConsumer ? null : $TblConsumer->getId());
    }

    /**
     * @return bool|TblIdentification
     */
    public function getServiceTblIdentification()
    {

        $Authentication = Account::useService()->getAuthenticationByAccount($this);
        if ($Authentication) {
            return $Authentication->getTblIdentification();
        } else {
            return false;
        }
    }
}
