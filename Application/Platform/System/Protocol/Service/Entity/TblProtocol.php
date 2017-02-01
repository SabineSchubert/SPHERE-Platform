<?php
namespace SPHERE\Application\Platform\System\Protocol\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Service\Entity\TblConsumer;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblProtocol")
 * @Cache(usage="READ_ONLY")
 */
class TblProtocol extends Element
{

    /**
     * @Column(type="string")
     */
    protected $ProtocolDatabase;
    /**
     * @Column(type="integer")
     */
    protected $ProtocolTimestamp;
    /**
     * @Column(type="bigint")
     */
    protected $serviceTblAccount;
    /**
     * @Column(type="string")
     */
    protected $AccountUsername;
    /**
     * @Column(type="bigint")
     */
    protected $serviceTblConsumer;
    /**
     * @Column(type="string")
     */
    protected $ConsumerName;
    /**
     * @Column(type="string")
     */
    protected $ConsumerAcronym;
    /**
     * @Column(type="text")
     */
    protected $EntityFrom;
    /**
     * @Column(type="text")
     */
    protected $EntityTo;

    /**
     * @return string
     */
    public function getProtocolDatabase()
    {

        return $this->ProtocolDatabase;
    }

    /**
     * @param string $ProtocolDatabase
     */
    public function setProtocolDatabase($ProtocolDatabase)
    {

        $this->ProtocolDatabase = $ProtocolDatabase;
    }

    /**
     * @return integer
     */
    public function getProtocolTimestamp()
    {

        return $this->ProtocolTimestamp;
    }

    /**
     * @param integer $ProtocolTimestamp
     */
    public function setProtocolTimestamp($ProtocolTimestamp)
    {

        $this->ProtocolTimestamp = $ProtocolTimestamp;
    }

    /**
     * @return bool|\SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount
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
     * @param null|\SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount $TblAccount
     */
    public function setServiceTblAccount(TblAccount $TblAccount = null)
    {

        $this->serviceTblAccount = (null === $TblAccount ? null : $TblAccount->getId());
    }

    /**
     * @return string
     */
    public function getAccountUsername()
    {

        return $this->AccountUsername;
    }

    /**
     * @param string $AccountUsername
     */
    public function setAccountUsername($AccountUsername)
    {

        $this->AccountUsername = $AccountUsername;
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
    public function setServiceTblConsumer(
        TblConsumer $TblConsumer = null
    ) {

        $this->serviceTblConsumer = (null === $TblConsumer ? null : $TblConsumer->getId());
    }

    /**
     * @return string
     */
    public function getConsumerName()
    {

        return $this->ConsumerName;
    }

    /**
     * @param string $ConsumerName
     */
    public function setConsumerName($ConsumerName)
    {

        $this->ConsumerName = $ConsumerName;
    }

    /**
     * @return string
     */
    public function getConsumerAcronym()
    {

        return $this->ConsumerAcronym;
    }

    /**
     * @param string $ConsumerAcronym
     */
    public function setConsumerAcronym($ConsumerAcronym)
    {

        $this->ConsumerAcronym = $ConsumerAcronym;
    }

    /**
     * @return string
     */
    public function getEntityFrom()
    {

        return $this->EntityFrom;
    }

    /**
     * @param string $EntityFrom
     */
    public function setEntityFrom($EntityFrom)
    {

        $this->EntityFrom = $EntityFrom;
    }

    /**
     * @return string
     */
    public function getEntityTo()
    {

        return $this->EntityTo;
    }

    /**
     * @param string $EntityTo
     */
    public function setEntityTo($EntityTo)
    {

        $this->EntityTo = $EntityTo;
    }
}
