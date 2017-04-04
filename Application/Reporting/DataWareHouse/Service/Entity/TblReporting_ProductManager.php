<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 08:46
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Entity;
use SPHERE\System\Database\Fitting\Element;


/**
 * @Entity
 * @Table(name="TblReporting_ProductManager")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_ProductManager extends Element
{
    const ATTR_NAME = 'Name';
    const ATTR_DEPARTMENT = 'Department';

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @Column(type="string")
     */
    protected $Department;

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

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->Department;
    }

    /**
     * @param mixed $Department
     */
    public function setDepartment($Department)
    {
        $this->Department = $Department;
    }

}