<?php
namespace SPHERE\Application\Platform\Utility\Translation\Service\Entity;


use Doctrine\ORM\Mapping as ORM;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblLocale
 *
 * @package SPHERE\Application\Platform\Utility\Translation\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblLocale")
 * @ORM\Cache(usage="READ_ONLY")
 */
class TblLocale extends AbstractEntity
{

    const LOCALE_DE_DE = 'de_DE';
    const LOCALE_EN_US = 'en_US';

    const ATTR_IDENTIFIER = 'Identifier';
    const ATTR_NAME = 'Name';
    const ATTR_DESCRIPTION = 'Description';

    /**
     * @var string $Identifier
     * @ORM\Column(type="string")
     */
    protected $Identifier;
    /**
     * @var string $Identifier
     * @ORM\Column(type="string")
     */
    protected $Name;
    /**
     * @var string $Identifier
     * @ORM\Column(type="text")
     */
    protected $Description;

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return (string)$this->Identifier;
    }

    /**
     * @param string $Identifier e.g. en_US
     * @return TblLocale
     */
    public function setIdentifier($Identifier)
    {
        $this->Identifier = (string)$Identifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->Name;
    }

    /**
     * @param string $Name e.g. English (US)
     * @return TblLocale
     */
    public function setName($Name)
    {
        $this->Name = (string)$Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return (string)$this->Description;
    }

    /**
     * @param string $Description
     * @return TblLocale
     */
    public function setDescription($Description)
    {
        $this->Description = (string)$Description;
        return $this;
    }
}
