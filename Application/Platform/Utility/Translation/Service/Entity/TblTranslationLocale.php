<?php

namespace SPHERE\Application\Platform\Utility\Translation\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use SPHERE\Application\Platform\Utility\Translation\TranslationInterface;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblTranslationLocale
 *
 * @package SPHERE\Application\Platform\Utility\Translation\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblTranslationLocale")
 * @ORM\Cache(usage="READ_ONLY")
 */
class TblTranslationLocale extends AbstractEntity implements TranslationInterface
{
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
     * @return TblTranslationLocale
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
     * @return TblTranslationLocale
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
     * @return TblTranslationLocale
     */
    public function setDescription($Description)
    {
        $this->Description = (string)$Description;
        return $this;
    }
}
