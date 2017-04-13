<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 29.03.2017
 * Time: 11:00
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Fitting\Element;

/**
 * @Entity
 * @Table(name="TblReporting_MarketingCode")
 * @Cache(usage="READ_ONLY")
 */
class TblReporting_MarketingCode extends Element
{
	const ATTR_NUMBER = 'Number';
	const ATTR_NAME = 'Name';

	/**
     * @Column(type="string")
     */
    protected $Number;
	/**
     * @Column(type="string")
     */
    protected $Name;

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
     * @return null|TblReporting_PartsMore|Element
     */
    public function fetchPartsMoreCurrent() {
        $MarketingCodePartsMore = DataWareHouse::useService()->getMarketingCodePartsMoreByMarketingCode( $this );
        if($MarketingCodePartsMore) {
            return DataWareHouse::useService()->getPartsMoreByMarketingCodePartsMore( $MarketingCodePartsMore );
        }
        else {
            return null;
        }
    }

    /**
     * @return null|TblReporting_ProductManager|Element
     */
    public function fetchProductManagerCurrent() {
        $ProductManagerMarketingCode = DataWareHouse::useService()->getProductManagerMarketingCodeByMarketingCode( $this );
        if($ProductManagerMarketingCode) {
            return DataWareHouse::useService()->getProductManagerByProductManagerMarketingCode( $ProductManagerMarketingCode );
        }
        else {
            return null;
        }
    }

    /**
     * @return array ProductGroup|null
     */
    public function fetchProductGroupListCurrent() {
        $MarketingCodeProductGroup = DataWareHouse::useService()->getMarketingCodeProductGroupByMarketingCode( $this );
        if( $MarketingCodeProductGroup ) {
            return DataWareHouse::useService()->getProductGroupByMarketingCodeProductGroup( $MarketingCodeProductGroup );
        }
        else {
            return null;
        }
    }

    /**
     * @return array $PartList|null
     */
    public function fetchPartListCurrent() {
        $PartMarketingCode = DataWareHouse::useService()->getPartMarketingCodeByMarketingCode( $this );
        if( $PartMarketingCode ) {
            return DataWareHouse::useService()->getPartByPartMarketingCode( $PartMarketingCode );
        }
        else {
            return null;
        }
    }

}