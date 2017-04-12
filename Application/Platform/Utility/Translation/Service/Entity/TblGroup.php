<?php

namespace SPHERE\Application\Platform\Utility\Translation\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblGroup
 *
 * @package SPHERE\Application\Platform\Utility\Translation\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblGroup")
 * @ORM\Cache(usage="READ_ONLY")
 */
class TblGroup extends AbstractEntity
{
    const ATTR_IDENTIFIER = 'Identifier';

    /**
     * @var string $Identifier
     * @ORM\Column(type="string")
     */
    protected $Identifier;

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return (string)$this->Identifier;
    }

    /**
     * @param string $Identifier
     * @return TblGroup
     */
    public function setIdentifier($Identifier)
    {
        $this->Identifier = (string)$Identifier;
        return $this;
    }
}
