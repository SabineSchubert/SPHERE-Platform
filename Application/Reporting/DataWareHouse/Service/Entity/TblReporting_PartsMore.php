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
    const ATTR_VALID_FROM = 'ValidFrom';
    const ATTR_VALID_TO = 'ValidTo';

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
    protected $ValidFrom;

    /**
     * @Column(type="datetime")
     */
    protected $ValidTo;

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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param string $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
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
     */
    public function setType($Type)
    {
        $this->Type = $Type;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->Value;
    }

    /**
     * @param float $Value
     */
    public function setValue($Value)
    {
        $this->Value = $Value;
    }

    /**
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->ValidFrom;
    }

    /**
     * @param \DateTime $ValidFrom
     */
    public function setValidFrom($ValidFrom)
    {
        $this->ValidFrom = $ValidFrom;
    }

    /**
     * @return \DateTime
     */
    public function getValidTo()
    {
        return $this->ValidTo;
    }

    /**
     * @param \DateTime $ValidTo
     */
    public function setValidTo($ValidTo)
    {
        $this->ValidTo = $ValidTo;
    }


}