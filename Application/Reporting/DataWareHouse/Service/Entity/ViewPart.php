<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 26.04.2017
 * Time: 10:36
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
* @Table(name="ViewPart")
* @Cache(usage="READ_ONLY")
 */
class ViewPart extends AbstractView
{
    const TBL_REPORTING_PARTS_MORE_VALUE = "TblReporting_PartsMore_Value";
    const TBL_REPORTING_PARTS_MORE_VALID_TO = "TblReporting_PartsMore_ValidTo";
    const TBL_REPORTING_PARTS_MORE_VALID_FROM = "TblReporting_PartsMore_ValidFrom";
    const TBL_REPORTING_PARTS_MORE_TYPE = "TblReporting_PartsMore_Type";
    const TBL_REPORTING_PARTS_MORE_NAME = "TblReporting_PartsMore_Name";
    const TBL_REPORTING_PARTS_MORE_ID = "TblReporting_PartsMore_Id";
    const TBL_REPORTING_PARTS_MORE_DESCRIPTION = "TblReporting_PartsMore_Description";
    const TBL_REPORTING_PART_STATUS_ACTIVE = "TblReporting_Part_StatusActive";
    const TBL_REPORTING_PART_SPARE_PART_DESIGN = "TblReporting_Part_SparePartDesign";
    const TBL_REPORTING_PART_SECTION_TBL_REPORTING_SECTION = "TblReporting_Part_Section_TblReporting_Section";
    const TBL_REPORTING_PART_SECTION_TBL_REPORTING_PART = "TblReporting_Part_Section_TblReporting_Part";
    const TBL_REPORTING_PART_SECTION_ID = "TblReporting_Part_Section_Id";
    const TBL_REPORTING_PART_NUMBER_DISPLAY = "TblReporting_Part_NumberDisplay";
    const TBL_REPORTING_PART_NUMBER = "TblReporting_Part_Number";
    const TBL_REPORTING_PART_NAME = "TblReporting_Part_Name";
    const TBL_REPORTING_PART_MARKETING_CODE_TBL_REPORTING_PART = "TblReporting_Part_MarketingCode_TblReporting_Part";
    const TBL_REPORTING_PART_MARKETING_CODE_TBL_REPORTING_MARKETING_CODE = "TblReporting_Part_MarketingCode_TblReporting_MarketingCode";
    const TBL_REPORTING_PART_MARKETING_CODE_ID = "TblReporting_Part_MarketingCode_Id";
    const TBL_REPORTING_PART_ID = "TblReporting_Part_Id";
    const TBL_REPORTING_PART_BRAND_TBL_REPORTING_PART = "TblReporting_Part_Brand_TblReporting_Part";
    const TBL_REPORTING_PART_BRAND_TBL_REPORTING_BRAND = "TblReporting_Part_Brand_TblReporting_Brand";
    const TBL_REPORTING_PART_BRAND_ID = "TblReporting_Part_Brand_Id";
    const TBL_REPORTING_MARKETING_CODE_PARTS_MORE_TBL_REPORTING_PARTS_MORE = "TblReporting_MarketingCode_PartsMore_TblReporting_PartsMore";
    const TBL_REPORTING_MARKETING_CODE_PARTS_MORE_TBL_REPORTING_MARKETING_CODE = "TblReporting_MarketingCode_PartsMore_TblReporting_MarketingCode";
    const TBL_REPORTING_MARKETING_CODE_PARTS_MORE_ID = "TblReporting_MarketingCode_PartsMore_Id";
    const TBL_REPORTING_MARKETING_CODE_NUMBER = "TblReporting_MarketingCode_Number";
    const TBL_REPORTING_MARKETING_CODE_NAME = "TblReporting_MarketingCode_Name";
    const TBL_REPORTING_MARKETING_CODE_ID = "TblReporting_MarketingCode_Id";
    const TBL_REPORTING_BRAND_NAME = "TblReporting_Brand_Name";
    const TBL_REPORTING_BRAND_ID = "TblReporting_Brand_Id";
    const TBL_REPORTING_BRAND_ALIAS = "TblReporting_Brand_Alias";

    /**
    * @Column(type="string")
    */
    protected $TblReporting_PartsMore_Value;

    /**
    * @Column(type="datetime")
    */
    protected $TblReporting_PartsMore_ValidTo;

    /**
    * @Column(type="datetime")
    */
    protected $TblReporting_PartsMore_ValidFrom;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_PartsMore_Type;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_PartsMore_Name;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_PartsMore_Id;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_PartsMore_Description;

    /**
    * @Column(type="boolean")
    */
    protected $TblReporting_Part_StatusActive;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_Part_SparePartDesign;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_Section_TblReporting_Section;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_Section_TblReporting_Part;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_Section_Id;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_Part_NumberDisplay;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_Part_Number;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_Part_Name;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_MarketingCode_TblReporting_Part;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_MarketingCode_TblReporting_MarketingCode;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_MarketingCode_Id;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_Id;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_Brand_TblReporting_Part;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_Brand_TblReporting_Brand;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Part_Brand_Id;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_MarketingCode_PartsMore_TblReporting_PartsMore;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_MarketingCode_PartsMore_TblReporting_MarketingCode;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_MarketingCode_PartsMore_Id;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_MarketingCode_Number;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_MarketingCode_Name;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_MarketingCode_Id;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_Brand_Name;

    /**
    * @Column(type="bigint")
    */
    protected $TblReporting_Brand_Id;

    /**
    * @Column(type="string")
    */
    protected $TblReporting_Brand_Alias;

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
        // TODO: Implement loadNameDefinition() method.
    }

    /**
     * @return AbstractService
     */
    public function getViewService()
    {
        return DataWareHouse::useService();
    }
}