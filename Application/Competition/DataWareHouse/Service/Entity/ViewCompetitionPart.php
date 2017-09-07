<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 06.09.2017
 * Time: 09:47
 */

namespace SPHERE\Application\Competition\DataWareHouse\Service\Entity;


use Doctrine\ORM\Mapping as ORM;
use SPHERE\Application\Competition\DataWareHouse\DataWareHouse;
use SPHERE\System\Database\Binding\AbstractService;
use SPHERE\System\Database\Binding\AbstractView;

class ViewCompetitionPart extends AbstractView
{
    const TBL_REPORTING_PART_NUMBER = 'TblReporting_Part_Number';
    const TBL_REPORTING_PART_NUMBER_DISPLAY = 'TblReporting_Part_NumberDisplay';
    const TBL_REPORTING_PART_NAME = 'TblReporting_Part_Name';
    const TBL_REPORTING_PART_SPARE_PART_DESIGN = 'TblReporting_Part_SparePartDesign';
    const TBL_REPORTING_PART_STATUS_ACTIVE = 'TblReporting_Part_StatusActive';
    const TBL_REPORTING_PART_PREDECESSOR = 'TblReporting_Part_Predecessor';
    const TBL_REPORTING_PART_SUCCESSOR = 'TblReporting_Part_Successor';
    const TBL_REPORTING_PART_OPTIONAL_NUMBER = 'TblReporting_Part_OptionalNumber';
    const TBL_REPORTING_PART_ID = 'TblReporting_Part_Id';

    const TBL_COMPETITION_PART_SEGMENT = 'TblCompetition_Part_Segment';
    const TBL_COMPETITION_PART_SEASON = 'TblCompetition_Part_Season';
    const TBL_COMPETITION_PART_ASSORTMENT = 'TblCompetition_Part_Assortment';
    const TBL_COMPETITION_PART_SECTION = 'TblCompetition_Part_Section';
    const TBL_COMPETITION_PART_TBL_REPORTING_PART = 'TblCompetition_Part_TblReporting_Part';
    const TBL_COMPETITION_PART_WIDTH = 'TblCompetition_Part_Width';
    const TBL_COMPETITION_PART_ASPECT_RATIO = 'TblCompetition_Part_AspectRatio';
    const TBL_COMPETITION_PART_RIM_INCH = 'TblCompetition_Part_RimInch';
    const TBL_COMPETITION_PART_LOAD_INDEX = 'TblCompetition_Part_LoadIndex';
    const TBL_COMPETITION_PART_SPEED_INDEX = 'TblCompetition_Part_SpeedIndex';
    const TBL_COMPETITION_PART_DIMENSION_TYRE = 'TblCompetition_Part_DimensionTyre';
    const TBL_COMPETITION_PART_PROFIL = 'TblCompetition_Part_Profil';
    const TBL_COMPETITION_PART_MANUFACTURER = 'TblCompetition_Part_Manufacturer';
    const TBL_COMPETITION_PART_NUMBER_TYRE = 'TblCompetition_Part_NumberTyre';
    const TBL_COMPETITION_PART_DIRECTION = 'TblCompetition_Part_Direction';
    const TBL_COMPETITION_PART_AXLE = 'TblCompetition_Part_Axle';
    const TBL_COMPETITION_PART_DIMENSION_RIM = 'TblCompetition_Part_DimensionRim';
    const TBL_COMPETITION_PART_DESIGN_RIM = 'TblCompetition_Part_DesignRim';
    const TBL_COMPETITION_PART_NUMBER_RIM = 'TblCompetition_Part_NumberRim';
    const TBL_COMPETITION_PART_SERIES = 'TblCompetition_Part_Series';
    const TBL_COMPETITION_PART_ID = 'TblCompetition_Part_Id';


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
    protected $TblCompetition_Part_Segment;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_Season;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_Assortment;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_Section;
    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblCompetition_Part_TblReporting_Part;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_Width;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_AspectRatio;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_RimInch;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_LoadIndex;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_SpeedIndex;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_DimensionTyre;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_Profil;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_Manufacturer;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_NumberTyre;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_Direction;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_Axle;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_DimensionRim;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_DesignRim;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_NumberRim;
    /**
     * @ORM\Column(type="string")
     */
    protected $TblCompetition_Part_Series;
    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblCompetition_Part_Id;

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