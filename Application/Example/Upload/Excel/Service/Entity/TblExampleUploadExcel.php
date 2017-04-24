<?php

namespace SPHERE\Application\Example\Upload\Excel\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use SPHERE\System\Database\Binding\AbstractEntity;

/**
 * Class TblExampleUploadExcel
 * @package SPHERE\Application\Example\Upload\Excel\Service\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="TblExampleUploadExcel")
 * @ORM\Cache(usage="READ_WRITE")
 */
class TblExampleUploadExcel extends AbstractEntity
{
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_A;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_B;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_C;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_D;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_E;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_F;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_G;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_H;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_I;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_J;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_K;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_L;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_M;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_N;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_O;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_P;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_Q;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_R;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_S;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_T;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_U;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_V;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_W;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_X;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_Y;
    /**
     * @ORM\Column(type="string")
     */
    protected $Column_Z;

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