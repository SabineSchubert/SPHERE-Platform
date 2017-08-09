<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 10.05.2017
 * Time: 11:13
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
 * @Table(name="TblReporting_Main")
 * @Cache(usage="READ_ONLY")
 */
class TblCompetition_Main extends Element
{
    /**
     * @Column(type="string")
     */
    protected $TransactionNumber;

    /**
     * @Column(type="string")
     */
    protected $RetailNumber;

    /**
     * @Column(type="text")
     */
    protected $Comment;

    /**
     * @return string
     */
    public function getTransactionNumber()
    {
        return $this->TransactionNumber;
    }

    /**
     * @param string $TransactionNumber
     */
    public function setTransactionNumber($TransactionNumber)
    {
        $this->TransactionNumber = $TransactionNumber;
    }

    /**
     * @return string
     */
    public function getRetailNumber()
    {
        return $this->RetailNumber;
    }

    /**
     * @param string $RetailNumber
     */
    public function setRetailNumber($RetailNumber)
    {
        $this->RetailNumber = $RetailNumber;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->Comment;
    }

    /**
     * @param string $Comment
     */
    public function setComment($Comment)
    {
        $this->Comment = $Comment;
    }


}