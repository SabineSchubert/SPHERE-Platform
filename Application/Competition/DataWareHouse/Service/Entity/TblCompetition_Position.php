<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 10.05.2017
 * Time: 11:14
 */

namespace SPHERE\Application\Competition\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_Position")
 * @Cache(usage="READ_ONLY")
 */
class TblCompetition_Position extends Element
{

}