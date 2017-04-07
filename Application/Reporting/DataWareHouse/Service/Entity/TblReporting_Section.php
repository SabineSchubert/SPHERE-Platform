<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 08:50
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Cache;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReportingSection")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Section extends Element
{
    const ATTR_ALIAS = 'Alias';
    const ATTR_NUMBER = 'Number';
    const ATTR_NAME = 'Name';
    /**
     * @Column(type="string")
     */
    protected $Alias;

    /**
     * @Column(type="string")
     */
    protected $Number;

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
    public function getNumber()
    {
        return $this->Number;
    }

    /**
     * @param string $Number
     */
    public function setNumber($Number)
    {
        $this->Number = $Number;
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