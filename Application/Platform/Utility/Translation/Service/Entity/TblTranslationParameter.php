<?php

namespace SPHERE\Application\Platform\Utility\Translation\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblTranslationParameter
 *
 * @package SPHERE\Application\Platform\Utility\Translation\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblTranslationParameter")
 * @ORM\Cache(usage="READ_ONLY")
 */
class TblTranslationParameter extends AbstractEntity
{
    const ATTR_IDENTIFIER = 'Identifier';
    const ATTR_TYPE = 'Type';
    const ATTR_NAME = 'Name';
    const ATTR_DESCRIPTION = 'Description';

    /**
     * @var string $Identifier
     * @ORM\Column(type="string")
     */
    protected $Identifier;
    /**
     * @var string $Type
     * @ORM\Column(type="string")
     */
    protected $Type;
    /**
     * @var string $Name
     * @ORM\Column(type="string")
     */
    protected $Name;
    /**
     * @var string $Description
     * @ORM\Column(type="string")
     */
    protected $Description;

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->Name;
    }

    /**
     * @param string $Name
     * @return TblTranslationParameter
     */
    public function setName($Name)
    {
        $this->Name = (string)$Name;
        return $this;
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
     * @return TblTranslationParameter
     */
    public function setIdentifier($Identifier)
    {
        $this->Identifier = $Identifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->Type;
    }

    /**
     * @param string $Type
     * @return TblTranslationParameter
     */
    public function setType($Type)
    {
        $this->Type = $Type;
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
     * @return TblTranslationParameter
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
        return $this;
    }
}
