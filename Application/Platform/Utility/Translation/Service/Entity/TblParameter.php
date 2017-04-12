<?php

namespace SPHERE\Application\Platform\Utility\Translation\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblParameter
 *
 * @package SPHERE\Application\Platform\Utility\Translation\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblParameter")
 * @ORM\Cache(usage="READ_ONLY")
 */
class TblParameter extends AbstractEntity
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
     * @return TblParameter
     */
    public function setName($Name)
    {
        $this->Name = (string)$Name;
        return $this;
    }
}
