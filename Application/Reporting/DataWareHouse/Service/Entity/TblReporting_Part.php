<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 09:16
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Part")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_Part extends Element
{
    const ATTR_NUMBER = 'Number';
    const ATTR_NUMBER_DISPLAY = 'NumberDisplay';
    const ATTR_NAME = 'Name';
    const ATTR_STATUS_ACTIVE = 'StatusActive';

    /**
     * @Column(type="string")
     */
    protected $Number;

    /**
     * @Column(type="string")
     */
    protected $NumberDisplay;

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @Column(type="boolean")
     */
    protected $StatusActive;

    //Vorgänger
    //Nachfolger
    //Wahlweise

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
    public function getNumberDisplay()
    {
        return $this->NumberDisplay;
    }

    /**
     * @param mixed $NumberDisplay
     */
    public function setNumberDisplay($NumberDisplay)
    {
        $this->NumberDisplay = $NumberDisplay;
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

    /**
     * @return mixed
     */
    public function getStatusActive()
    {
        return $this->StatusActive;
    }

    /**
     * @param mixed $StatusActive
     */
    public function setStatusActive($StatusActive)
    {
        $this->StatusActive = $StatusActive;
    }



}