<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 02.05.2017
 * Time: 10:55
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Binding\AbstractService;
use SPHERE\System\Database\Binding\AbstractView;

/**
* @Entity
* @Table(name="ViewPrice")
* @Cache(usage="READ_ONLY")
 */
class ViewPrice extends AbstractView
{
    const TBL_REPORTING_PART = 'TblReporting_Price_TblReporting_Part';
    const TBL_REPORTING_PRICE_PRICE_GROSS = 'TblReporting_Price_PriceGross';
    const TBL_REPORTING_PRICE_BACK_VALUE = 'TblReporting_Price_BackValue';
    const TBL_REPORTING_PRICE_COSTS_VARIABLE = 'TblReporting_Price_CostsVariable';
    const TBL_REPORTING_PRICE_VALID_FROM = 'TblReporting_Price_ValidFrom';
    const TBL_REPORTING_DISCOUNT_GROUP_NUMBER = 'TblReporting_DiscountGroup_Number';
    const TBL_REPORTING_DISCOUNT_GROUP_DISCOUNT = 'TblReporting_DiscountGroup_Discount';

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Price_TblReporting_Part;
    /**
    * @Column(type="float")
    */
    protected $TblReporting_Price_PriceGross;
    /**
    * @Column(type="float")
    */
    protected $TblReporting_Price_BackValue;
    /**
    * @Column(type="float")
    */
    protected $TblReporting_Price_CostsVariable;
    /**
    * @Column(type="datetime")
    */
    protected $TblReporting_Price_ValidFrom;
    /**
    * @Column(type="string")
    */
    protected $TblReporting_DiscountGroup_Number;
    /**
    * @Column(type="float")
    */
    protected $TblReporting_DiscountGroup_Discount;

    /**
     * Use this method to set PropertyName to DisplayName conversions with "setNameDefinition()"
     *
     * @return void
     */
    public function loadNameDefinition()
    {
        // TODO: Implement loadNameDefinition() method.
    }

    /**
     * Use this method to add ForeignViews to Graph with "addForeignView()"
     *
     * @return void
     */
    public function loadViewGraph()
    {
        // TODO: Implement loadViewGraph() method.
    }

    /**
     * @return AbstractService
     */
    public function getViewService()
    {
        return DataWareHouse::useService();
    }
}