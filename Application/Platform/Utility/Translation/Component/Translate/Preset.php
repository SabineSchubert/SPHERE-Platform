<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component\Translate;

use MOC\V\Component\Template\Template;
use SPHERE\Application\Platform\Utility\Translation\Component\AbstractComponent;

/**
 * Class Preset
 * @package SPHERE\Application\Platform\Utility\Translation\Component\Translate
 */
class Preset extends AbstractComponent
{

    const LOCALE_EN_US = 'en_US';
    const LOCALE_DE_DE = 'de_DE';

    /** @var string $DefaultLocale */
    private $DefaultLocale = 'en_US';
    /** @var array $PatternList */
    private $PatternList = array();
    private $DefaultPattern = '';
    /** @var null|Parameter $Parameter */
    private $Parameter = null;
    /** @var string $BreadCrumb */
    private $BreadCrumb = '-NA-';

    /**
     * Preset constructor.
     * @param string $DefaultPattern
     * @param Parameter $Parameter
     * @param string $DefaultLocale
     */
    public function __construct($DefaultPattern, Parameter $Parameter = null, $DefaultLocale = self::LOCALE_EN_US)
    {
        $this->DefaultPattern = trim($DefaultPattern);
        if (null === $Parameter) {
            $Parameter = new Parameter();
        }
        $this->setParameter($Parameter);
        $this->setDefaultLocale($DefaultLocale);
    }

    /**
     * @return string
     */
    public function __toString()
    {

        $this->appendPattern('!.*!is', $this->DefaultPattern);
        $Template = null;
        if (key_exists(
            ($Switch = $this->getParameter()->getSwitch()),
            ($ParameterList = $this->getParameter()->getParameterList())
        )) {
            $Match = $ParameterList[$Switch];
            foreach ($this->PatternList as $RegEx => $Preset) {
                if (preg_match($RegEx, $Match)) {
                    $Template = Template::getTwigTemplateString($Preset);
                    foreach ($ParameterList as $Key => $Value) {
                        $Template->setVariable($Key, $Value);
                    }
                    break;
                }
            }
        }

        if (!$Template) {
            $Template = Template::getTwigTemplateString(end($this->PatternList));
            foreach ($ParameterList as $Key => $Value) {
                $Template->setVariable($Key, $Value);
            }
        }

        // TODO: TR Mod
        if ($this->getDefaultLocale() != $this->getLocale()) {
            return '{'.$Template->getContent().':'.$this->getLocale().'}';
//            $Translate = new Paragraph(new Link('Translation (' . $this->getDefaultLocale() . ' => ' . $this->getLocale() . ')',
//                '#', new Conversation(), array(), 'Missing (' . $this->getLocale() . ') ' . $this->getBreadCrumb()));
        } else {
            return $Template->getContent();
        }
    }

    /**
     * @param $RegEx
     * @param $Template
     * @return $this
     */
    public function appendPattern($RegEx, $Template)
    {
        $this->PatternList[$RegEx] = strip_tags($Template);
        return $this;
    }

    /**
     * @return null|Parameter
     */
    public function getParameter()
    {
        return $this->Parameter;
    }

    /**
     * @param null|Parameter $Parameter
     */
    public function setParameter($Parameter)
    {
        $this->Parameter = $Parameter;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->DefaultLocale;
    }

    /**
     * @param string $DefaultLocale
     */
    public function setDefaultLocale($DefaultLocale)
    {
        $this->DefaultLocale = $DefaultLocale;
    }

    /**
     * @return string
     */
    public function getBreadCrumb()
    {
        return $this->BreadCrumb;
    }

    /**
     * @param string $BreadCrumb
     */
    public function setBreadCrumb($BreadCrumb)
    {
        $this->BreadCrumb = (string)$BreadCrumb;
    }

    /**
     * @return array
     */
    public function getPatternList()
    {
        return $this->PatternList;
    }

    /**
     * @return string
     */
    public function getDefaultPattern()
    {
        return $this->DefaultPattern;
    }
}
