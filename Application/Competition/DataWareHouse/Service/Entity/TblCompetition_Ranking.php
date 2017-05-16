<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 10.05.2017
 * Time: 15:18
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
 * @Table(name="TblReporting_Ranking")
 * @Cache(usage="READ_ONLY")
 */
class TblCompetition_Ranking extends Element
{

    /**
    * @Column(type="integer")
    */
    protected $Year;

    /**
     * @Column(type="bigint")
     */
    protected $TblRetailHB;

}