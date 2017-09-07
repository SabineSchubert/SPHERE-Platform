<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 01.09.2017
 * Time: 14:28
 */

namespace SPHERE\Application\Competition\DataWareHouse\Service\Entity;


use Doctrine\ORM\Mapping as ORM;
use SPHERE\Application\Competition\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Binding\AbstractService;
use SPHERE\System\Database\Binding\AbstractView;

/**
* @ORM\Entity()
* @ORM\Table(name="ViewCompetition")
* @ORM\Cache(usage="READ_ONLY")
 */
class ViewCompetition extends AbstractView
{
    const TBL_COMPETITION_MAIN_TRANSACTION_NUMBER = 'TblCompetition_Main_TransactionNumber';
    const TBL_COMPETITION_MAIN_RETAIL_NUMBER = 'TblCompetition_Main_RetailNumber';
    const TBL_COMPETITION_MAIN_COMMENT = 'TblCompetition_Main_Comment';

    const TBL_COMPETITION_MAIN_CREATION_DATE = 'TblCompetition_Main_CreationDate';

    const TBL_COMPETITION_MAIN_ID = 'TblCompetition_Main_Id';
    const TBL_COMPETITION_POSITION_TBL_COMPETITION_COMPETITOR = 'TblCompetition_Position_TblCompetition_Competitor';

    const TBL_COMPETITION_POSITION_COMPETITOR = 'TblCompetition_Position_Competitor';
    const TBL_COMPETITION_POSITION_TBL_COMPETITION_MANUFACTURER = 'TblCompetition_Position_TblCompetition_Manufacturer';
    const TBL_COMPETITION_POSITION_MANUFACTURER = 'TblCompetition_Position_Manufacturer';
    const TBL_COMPETITION_POSITION_PRICE_NET = 'TblCompetition_Position_PriceNet';
    const TBL_COMPETITION_POSITION_PRICE_GROSS = 'TblCompetition_Position_PriceGross';
    const TBL_COMPETITION_POSITION_DISCOUNT = 'TblCompetition_Position_Discount';
    const TBL_COMPETITION_POSITION_VAT = 'TblCompetition_Position_Vat';
    const TBL_COMPETITION_POSITION_DISTRIBUTOR_OR_CUSTOMER = 'TblCompetition_Position_DistributorOrCustomer';
    const TBL_COMPETITION_POSITION_COMMENT = 'TblCompetition_Position_Comment';
    const TBL_COMPETITION_POSITION_PACKING_UNIT = 'TblCompetition_Position_PackingUnit';
    const TBL_COMPETITION_POSITION_DOT = 'TblCompetition_Position_Dot';
    const TBL_COMPETITION_POSITION_ACTION_PART = 'TblCompetition_Position_ActionPart';
    const TBL_COMPETITION_POSITION_ID = 'TblCompetition_Position_Id';
    const TBL_REPORTING_PART_NUMBER = 'TblReporting_Part_Number';
    const TBL_REPORTING_PART_NUMBER_DISPLAY = 'TblReporting_Part_NumberDisplay';
    const TBL_REPORTING_PART_NAME = 'TblReporting_Part_Name';
    const TBL_REPORTING_PART_SPARE_PART_DESIGN = 'TblReporting_Part_SparePartDesign';
    const TBL_REPORTING_PART_STATUS_ACTIVE = 'TblReporting_Part_StatusActive';
    const TBL_REPORTING_PART_PREDECESSOR = 'TblReporting_Part_Predecessor';
    const TBL_REPORTING_PART_SUCCESSOR = 'TblReporting_Part_Successor';
    const TBL_REPORTING_PART_OPTIONAL_NUMBER = 'TblReporting_Part_OptionalNumber';
    const TBL_REPORTING_PART_ID = 'TblReporting_Part_Id';

    const TBL_REPORTING_MARKETING_CODE_NUMBER = 'TblReporting_MarketingCode_Number';
    const TBL_REPORTING_MARKETING_CODE_NAME = 'TblReporting_MarketingCode_Name';
    const TBL_REPORTING_MARKETING_CODE_ID = 'TblReporting_MarketingCode_Id';
    const TBL_REPORTING_PRODUCT_GROUP_NUMBER = 'TblReporting_ProductGroup_Number';
    const TBL_REPORTING_PRODUCT_GROUP_NAME = 'TblReporting_ProductGroup_Name';
    const TBL_REPORTING_PRODUCT_GROUP_ID = 'TblReporting_ProductGroup_Id';

    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Main_TransactionNumber;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Main_RetailNumber;
    /**
     * @ORM\Column(type="text")
     */
    protected $TblCompetition_Main_Comment;

    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Main_CreationDate;

    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblCompetition_Main_Id;
    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblCompetition_Position_TblCompetition_Competitor;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Position_Competitor;
    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblCompetition_Position_TblCompetition_Manufacturer;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Position_Manufacturer;
    /**
      * @ORM\Column(type="float")
      */
    protected $TblCompetition_Position_PriceNet;
    /**
     * @ORM\Column(type="float")
     */
    protected $TblCompetition_Position_PriceGross;
    /**
     * @ORM\Column(type="float")
     */
    protected $TblCompetition_Position_Discount;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $TblCompetition_Position_Vat;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Position_DistributorOrCustomer;
    /**
     * @ORM\Column(type="text")
     */
    protected $TblCompetition_Position_Comment;
    /**
     * @ORM\Column(type="integer")
     */
    protected $TblCompetition_Position_PackingUnit;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Position_Dot;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $TblCompetition_Position_ActionPart;
    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblCompetition_Position_Id;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_Part_Number;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_Part_NumberDisplay;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_Part_Name;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_Part_SparePartDesign;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $TblReporting_Part_StatusActive;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_Part_Predecessor;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_Part_Successor;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_Part_OptionalNumber;
    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblReporting_Part_Id;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_MarketingCode_Number;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_MarketingCode_Name;
    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblReporting_MarketingCode_Id;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_ProductGroup_Number;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblReporting_ProductGroup_Name;
    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblReporting_ProductGroup_Id;


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