<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 10.05.2017
 * Time: 15:15
 */

namespace SPHERE\Application\Competition\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Competitor")
 * @Cache(usage="READ_ONLY")
 */
class TblCompetition_Competitor extends Element
{
    const ATTR_NAME = 'Name';
    const ATTR_SORTING = 'Sorting';

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @Column(type="integer")
     */
    protected $Sorting;

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
     * @return integer
     */
    public function getSorting()
    {
        return $this->Sorting;
    }

    /**
     * @param integer $Sorting
     */
    public function setSorting($Sorting)
    {
        $this->Sorting = $Sorting;
    }


}