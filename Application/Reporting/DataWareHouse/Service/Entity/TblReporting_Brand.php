<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 08:45
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Brand")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Brand extends Element
{
    const ATTR_ALIAS = 'Alias';
    const ATTR_NAME = 'Name';

    /**
     * @Column(type="string")
     */
    protected $Alias;

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->Alias;
    }

    /**
     * @param string $Alias
     */
    public function setAlias($Alias)
    {
        $this->Alias = $Alias;
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
}