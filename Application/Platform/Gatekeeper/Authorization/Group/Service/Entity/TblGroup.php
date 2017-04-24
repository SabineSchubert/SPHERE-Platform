<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Service\Entity\TblConsumer;
use SPHERE\System\Database\Fitting\Element;


/**
 * @Entity
 * @Table(name="TblGroup")
 * @Cache(usage="READ_ONLY")
 */
class TblGroup extends Element
{

    const ATTR_NAME = 'Name';
    const ATTR_DESCRIPTION = 'Description';

    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="text")
     */
    protected $Description;

    /**
     * @Column(type="bigint")
     */
    protected $serviceTblConsumer;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return TblGroup
     */
    public function setName($Name)
    {
        $this->Name = $Name;
        return $this;
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
     * @return TblGroup
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
        return $this;
    }

    /**
     * @return null|TblConsumer
     */
    public function getServiceTblConsumer()
    {

        if (null === $this->serviceTblConsumer) {
            return null;
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
}
