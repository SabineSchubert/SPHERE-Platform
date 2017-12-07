<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 07.06.2017
 * Time: 10:41
 */

namespace SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblImport_PmMc
 * @package SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblImport_PmMc")
 * @ORM\Cache(usage="READ_WRITE")
 */
class TblImport_PmMc extends AbstractEntity
{

    const PM_NUMBER = 'PmNumber';
    const PM_NAME = 'PmName';
    const MC_NUMBER = 'McNumber';
    const MC_NAME = 'McName';

    /**
     * @ORM\Column(type="string")
     */
    protected $PmNumber;

    /**
     * @ORM\Column(type="string")
     */
    protected $PmName;

    /**
     * @ORM\Column(type="string")
     */
    protected $McNumber;

    /**
     * @ORM\Column(type="string")
     */
    protected $McName;

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
        //$value = new \DateTime($value);
    }

}