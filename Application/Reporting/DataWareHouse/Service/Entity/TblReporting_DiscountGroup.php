<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 03.04.2017
 * Time: 08:51
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
 * @Table(name="TblReporting_DiscountGroup")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_DiscountGroup extends Element
{
    const ATTR_NUMBER = 'Number';
    const ATTR_NAME = 'Name';

    /**
     * @Column(type="string")
     */
    protected $Number;

    /**
     * @Column(type="float")
     */
    protected $Discount;

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
     * @return float
     */
    public function getDiscount()
    {
        return $this->Discount;
    }

    /**
     * @param float $Discount
     */
    public function setDiscount($Discount)
    {
        $this->Discount = $Discount;
    }


}