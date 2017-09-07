<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 05.09.2017
 * Time: 15:52
 */

namespace SPHERE\Application\Competition\DataWareHouse\Service\Entity;


use Doctrine\ORM\Mapping as ORM;
use SPHERE\System\Database\Fitting\Element;

class TblCompetition_Part extends Element
{


    const ATTR_SEGMENT = 'Segment';
    const ATTR_SEASON = 'Season';
    const ATTR_ASSORTMENT = 'Assortment';
    const ATTR_SECTION = 'Section';
    const TBL_REPORTING_PART = 'TblReporting_Part';
    const ATTR_WIDTH = 'Width';
    const ATTR_ASPECT_RATIO = 'AspectRatio';
    const ATTR_RIM_INCH = 'RimInch';
    const ATTR_LOAD_INDEX = 'LoadIndex';
    const ATTR_SPEED_INDEX = 'SpeedIndex';
    const ATTR_DIMENSION_TYRE = 'DimensionTyre';
    const ATTR_PROFIL = 'Profil';
    const ATTR_MANUFACTURER = 'Manufacturer';
    const ATTR_NUMBER_TYRE = 'NumberTyre';
    const ATTR_DIRECTION = 'Direction';
    const ATTR_AXLE = 'Axle';
    const ATTR_DIMENSION_RIM = 'DimensionRim';
    const ATTR_DESIGN_RIM = 'DesignRim';
    const ATTR_NUMBER_RIM = 'NumberRim';
    const ATTR_SERIES = 'Series';

    /**
     * @ORM\Column(type="string")
     */
    protected $Segment;
    /**
     * @ORM\Column(type="string")
     */
    protected $Season;
    /**
     * @ORM\Column(type="string")
     */
    protected $Assortment;
    /**
     * @ORM\Column(type="string")
     */
    protected $Section;
    /**
     * @ORM\Column(type="bigint")
     */
    protected $TblReporting_Part;
    /**
     * @ORM\Column(type="string")
     */
    protected $Width;
    /**
     * @ORM\Column(type="string")
     */
    protected $AspectRatio;
    /**
     * @ORM\Column(type="string")
     */
    protected $RimInch;
    /**
     * @ORM\Column(type="string")
     */
    protected $LoadIndex;
    /**
     * @ORM\Column(type="string")
     */
    protected $SpeedIndex;
    /**
     * @ORM\Column(type="string")
     */
    protected $DimensionTyre;
    /**
     * @ORM\Column(type="string")
     */
    protected $Profil;
    /**
     * @ORM\Column(type="string")
     */
    protected $Manufacturer;
    /**
     * @ORM\Column(type="string")
     */
    protected $NumberTyre;
    /**
     * @ORM\Column(type="string")
     */
    protected $Direction;
    /**
     * @ORM\Column(type="string")
     */
    protected $Axle;
    /**
     * @ORM\Column(type="string")
     */
    protected $DimensionRim;
    /**
     * @ORM\Column(type="string")
     */
    protected $DesignRim;
    /**
     * @ORM\Column(type="string")
     */
    protected $NumberRim;
    /**
     * @ORM\Column(type="string")
     */
    protected $Series;

    /**
     * @return mixed
     */
    public function getSegment()
    {
        return $this->Segment;
    }

    /**
     * @param mixed $Segment
     */
    public function setSegment($Segment)
    {
        $this->Segment = $Segment;
    }

    /**
     * @return string
     */
    public function getSeason()
    {
        return $this->Season;
    }

    /**
     * @param string $Season
     */
    public function setSeason($Season)
    {
        $this->Season = $Season;
    }

    /**
     * @return string
     */
    public function getAssortment()
    {
        return $this->Assortment;
    }

    /**
     * @param string $Assortment
     */
    public function setAssortment($Assortment)
    {
        $this->Assortment = $Assortment;
    }

    /**
     * @return string
     */
    public function getSection()
    {
        return $this->Section;
    }

    /**
     * @param string $Section
     */
    public function setSection($Section)
    {
        $this->Section = $Section;
    }

    /**
     * @return integer
     */
    public function getTblReportingPart()
    {
        return $this->TblReporting_Part;
    }

    /**
     * @param integer $TblReporting_Part
     */
    public function setTblReportingPart($TblReporting_Part)
    {
        $this->TblReporting_Part = $TblReporting_Part;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->Width;
    }

    /**
     * @param string $Width
     */
    public function setWidth($Width)
    {
        $this->Width = $Width;
    }

    /**
     * @return string
     */
    public function getAspectRatio()
    {
        return $this->AspectRatio;
    }

    /**
     * @param string $AspectRatio
     */
    public function setAspectRatio($AspectRatio)
    {
        $this->AspectRatio = $AspectRatio;
    }

    /**
     * @return string
     */
    public function getRimInch()
    {
        return $this->RimInch;
    }

    /**
     * @param string $RimInch
     */
    public function setRimInch($RimInch)
    {
        $this->RimInch = $RimInch;
    }

    /**
     * @return string
     */
    public function getLoadIndex()
    {
        return $this->LoadIndex;
    }

    /**
     * @param string $LoadIndex
     */
    public function setLoadIndex($LoadIndex)
    {
        $this->LoadIndex = $LoadIndex;
    }

    /**
     * @return string
     */
    public function getSpeedIndex()
    {
        return $this->SpeedIndex;
    }

    /**
     * @param string $SpeedIndex
     */
    public function setSpeedIndex($SpeedIndex)
    {
        $this->SpeedIndex = $SpeedIndex;
    }

    /**
     * @return string
     */
    public function getDimensionTyre()
    {
        return $this->DimensionTyre;
    }

    /**
     * @param string $DimensionTyre
     */
    public function setDimensionTyre($DimensionTyre)
    {
        $this->DimensionTyre = $DimensionTyre;
    }

    /**
     * @return string
     */
    public function getProfil()
    {
        return $this->Profil;
    }

    /**
     * @param string $Profil
     */
    public function setProfil($Profil)
    {
        $this->Profil = $Profil;
    }

    /**
     * @return string
     */
    public function getManufacturer()
    {
        return $this->Manufacturer;
    }

    /**
     * @param string $Manufacturer
     */
    public function setManufacturer($Manufacturer)
    {
        $this->Manufacturer = $Manufacturer;
    }

    /**
     * @return string
     */
    public function getNumberTyre()
    {
        return $this->NumberTyre;
    }

    /**
     * @param string $NumberTyre
     */
    public function setNumberTyre($NumberTyre)
    {
        $this->NumberTyre = $NumberTyre;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->Direction;
    }

    /**
     * @param string $Direction
     */
    public function setDirection($Direction)
    {
        $this->Direction = $Direction;
    }

    /**
     * @return string
     */
    public function getAxle()
    {
        return $this->Axle;
    }

    /**
     * @param string $Axle
     */
    public function setAxle($Axle)
    {
        $this->Axle = $Axle;
    }

    /**
     * @return string
     */
    public function getDimensionRim()
    {
        return $this->DimensionRim;
    }

    /**
     * @param string $DimensionRim
     */
    public function setDimensionRim($DimensionRim)
    {
        $this->DimensionRim = $DimensionRim;
    }

    /**
     * @return string
     */
    public function getDesignRim()
    {
        return $this->DesignRim;
    }

    /**
     * @param string $DesignRim
     */
    public function setDesignRim($DesignRim)
    {
        $this->DesignRim = $DesignRim;
    }

    /**
     * @return string
     */
    public function getNumberRim()
    {
        return $this->NumberRim;
    }

    /**
     * @param string $NumberRim
     */
    public function setNumberRim($NumberRim)
    {
        $this->NumberRim = $NumberRim;
    }

    /**
     * @return string
     */
    public function getSeries()
    {
        return $this->Series;
    }

    /**
     * @param string $Series
     */
    public function setSeries($Series)
    {
        $this->Series = $Series;
    }









}