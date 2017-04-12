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
    const ATTR_NAME = 'Name';

    /**
     * @var string $Identifier
     * @ORM\Column(type="string")
     */
    protected $Name;

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
}
