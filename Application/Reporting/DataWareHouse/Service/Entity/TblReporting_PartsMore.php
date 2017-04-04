<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 04.04.2017
 * Time: 13:26
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
 * @Table(name="TblReporting_PartsMore")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_PartsMore extends Element
{
    const ATTR_NAME = 'Name';
    const ATTR_DESCRIPTION = 'Description';
    const ATTR_TYPE = 'Type';
    const ATTR_VALUE = 'Value';
    const ATTR_DATE_FROM = 'DateFrom';
    const ATTR_DATE_TO = 'DateTo';

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @Column(type="string")
     */
    protected $Description;

    /**
     * @Column(type="string")
     */
    protected $Type;

    /**
     * @Column(type="float")
     */
    protected $Value;

    /**
     * @Column(type="datetime")
     */
    protected $DateFrom;

    /**
     * @Column(type="datetime")
     */
    protected $DateTo;

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
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param mixed $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->Type;
    }

    /**
     * @param mixed $Type
     */
    public function setType($Type)
    {
        $this->Type = $Type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->Value;
    }

    /**
     * @param mixed $Value
     */
    public function setValue($Value)
    {
        $this->Value = $Value;
    }

    /**
     * @return mixed
     */
    public function getDateFrom()
    {
        return $this->DateFrom;
    }

    /**
     * @param mixed $DateFrom
     */
    public function setDateFrom($DateFrom)
    {
        $this->DateFrom = $DateFrom;
    }

    /**
     * @return mixed
     */
    public function getDateTo()
    {
        return $this->DateTo;
    }

    /**
     * @param mixed $DateTo
     */
    public function setDateTo($DateTo)
    {
        $this->DateTo = $DateTo;
    }


}