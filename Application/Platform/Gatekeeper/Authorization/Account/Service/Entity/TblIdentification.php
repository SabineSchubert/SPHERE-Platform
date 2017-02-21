<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblIdentification")
 * @Cache(usage="READ_ONLY")
 */
class TblIdentification extends Element
{

    const NAME_CREDENTIAL = 'Credential';
    const NAME_SAML = 'Saml';

    const ATTR_NAME = 'Name';
    const ATTR_SESSION_TIMEOUT = 'SessionTimeout';

    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="string")
     */
    protected $Description;
    /**
     * @Column(type="boolean")
     */
    protected $IsActive;
    /**
     * @Column(type="integer")
     */
    protected $SessionTimeout = 3600;

    /**
     * @return int
     */
    public function getSessionTimeout()
    {
        return $this->SessionTimeout;
    }

    /**
     * @param int $SessionTimeout
     */
    public function setSessionTimeout($SessionTimeout)
    {
        $this->SessionTimeout = (int)$SessionTimeout;
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

    /**
     * @return bool
     */
    public function isActive()
    {

        return (bool)$this->IsActive;
    }

    /**
     * @param bool $IsActive
     */
    public function setActive($IsActive)
    {

        $this->IsActive = (bool)$IsActive;
    }
}
