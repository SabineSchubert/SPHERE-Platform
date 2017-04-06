<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 05.04.2017
 * Time: 09:42
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Cache;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Supplier")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Supplier extends Element
{
    const ATTR_NUMBER = 'Number';
    const ATTR_NAME = 'Name';

    /**
     * @Column(type="string")
     */
    protected $Number;

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->Number;
    }

    /**
     * @param mixed $Number
     */
    public function setNumber($Number)
    {
        $this->Number = $Number;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param mixed $Name
     */
    public function setName($Name)
    {
        $this->Name = $Name;
    }


}