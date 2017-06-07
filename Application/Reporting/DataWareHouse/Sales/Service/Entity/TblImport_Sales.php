<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 22.05.2017
 * Time: 11:02
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Sales\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblImport_Sales
 * @package SPHERE\Application\Reporting\DataWareHouse\Sales\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblImport_Sales")
 * @ORM\Cache(usage="READ_WRITE")
 */
class TblImport_Sales extends AbstractEntity
{
    const PART_NUMBER = 'PartNumber';
    const PART_DESCRIPTION = 'PartDescription';
    const MARKETING_CODE_NUMBER = 'MarketingCodeNumber';
    const MARKETING_CODE_DESCRIPTION = 'MarketingCodeDescription';
    const MONTH = 'Month';
    const YEAR = 'Year';
    const QUANTITY = 'Quantity';
    const SALES_GROSS = 'SalesGross';
    const SALES_NET = 'SalesNet';

    /**
     * @ORM\Column(type="string")
     */
    protected $PartNumber;

    /**
     * @ORM\Column(type="string")
     */
    protected $PartDescription;

    /**
     * @ORM\Column(type="string")
     */
    protected $MarketingCodeNumber;

    /**
     * @ORM\Column(type="string")
     */
    protected $MarketingCodeDescription;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Month;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Year;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Quantity;

    /**
     * @ORM\Column(type="float")
     */
    protected $SalesGross;

    /**
     * @ORM\Column(type="float")
     */
    protected $SalesNet;

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }
}