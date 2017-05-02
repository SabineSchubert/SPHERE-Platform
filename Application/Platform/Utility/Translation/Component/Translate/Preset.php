<?php

namespace SPHERE\Application\Platform\Utility\Translation\Component\Translate;

use MOC\V\Component\Template\Template;
use SPHERE\Application\Platform\Utility\Translation\Component\AbstractComponent;
use SPHERE\Application\Platform\Utility\Translation\TranslationInterface;

/**
 * Class Preset
 * @package SPHERE\Application\Platform\Utility\Translation\Component\Translate
 */
class Preset extends AbstractComponent
{

    /** @var string $Locale */
    private $Locale = TranslationInterface::LOCALE_EN_US;
    /** @var string $Template */
    private $Template = '';
    /** @var null|Parameter $Parameter */
    private $Parameter = null;

    /**
     * Preset constructor.
     * @param string $Template
     * @param Parameter $Parameter
     * @param string $Locale
     */
    public function __construct(
        $Template,
        Parameter $Parameter = null,
        $Locale = TranslationInterface::LOCALE_EN_US
    ) {
        $this->setTemplate($Template);
        if (null === $Parameter) {
            $Parameter = new Parameter();
        }
        $this->setParameter($Parameter);
        $this->setLocale($Locale);
    }

    /**
     * @return string
     */
    public function __toString()
    {

        if(($Parameter = $this->getParameter())) {
            $ParameterList = $Parameter->getParameter();
        } else {
            $ParameterList = array();
        }

        $Template = Template::getTwigTemplateString( $this->getTemplate() );
        foreach ($ParameterList as $Key => $Value) {
            $Template->setVariable($Key, $Value);
        }

        if ($this->getLocale() != $this->getClientLocale()) {
            // TODO: Point Translation Access if Language is missing
            return $Template->getContent() . ' (Missing translation ' . $this->getLocale() . ' to ' . $this->getClientLocale() . ')';
        } else {
            return $Template->getContent();
        }
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
    public function getLocale()
    {
        return (string)$this->Locale;
    }

    /**
     * @param string $Locale
     */
    public function setLocale($Locale)
    {
        $this->Locale = (string)$Locale;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return (string)$this->Template;
    }

    /**
     * @param string $Template
     */
    public function setTemplate($Template)
    {
        $this->Template = (string)trim(strip_tags($Template));
    }
}
