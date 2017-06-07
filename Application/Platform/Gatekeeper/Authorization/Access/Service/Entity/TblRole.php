<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblRole")
 * @Cache(usage="READ_ONLY")
 */
class TblRole extends Element
{

    const ATTR_NAME = 'Name';
    const ATTR_IS_INTERNAL = 'IsInternal';

    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="boolean")
     */
    protected $IsInternal;

    /**
     * @return string
     */
    public function getName()
    {

        return $this->Name;
    }

    /**
     * @param string $Name
     * @return $this
     */
    public function setName($Name)
    {

        $this->Name = (string)$Name;
        return $this;
    }

    /**
     * @return bool|TblLevel[]
     */
    public function getTblLevelAll()
    {

        return Access::useService()->getLevelAllByRole($this);
    }

    /**
     * @return int
     */
    public function countTblLevelAll()
    {
        $Count = Access::useService()->countLevelAllByRole($this);

        return (int)($Count ? $Count : 0 );
    }

    /**
     * @return bool
     */
    public function isInternal()
    {

        return (bool)$this->IsInternal;
    }

    /**
     * @param bool $IsInternal
     * @return $this
     */
    public function setInternal($IsInternal)
    {

        $this->IsInternal = (bool)$IsInternal;
        return $this;
    }
}
