<?php
namespace SPHERE\Common\Frontend\Text\Repository;

use SPHERE\Common\Frontend\Icon\IIconInterface;
use SPHERE\Common\Frontend\Text\ITextInterface;
use SPHERE\System\Extension\Extension;

/**
 * Class Standard
 * @package SPHERE\Common\Frontend\Text\Repository
 */
class Tooltip extends Extension implements ITextInterface
{

    /** @var string $Value */
    private $Value = '';
    /** @var string $Value */
    private $Tooltip = '';
    /** @var null|IIconInterface $Icon */
    private $Icon = null;

    /**
     * @param string|ITextInterface $Value
     * @param string $Tooltip
     * @param IIconInterface $Icon
     */
    public function __construct($Value, $Tooltip, IIconInterface $Icon = null)
    {

        $this->Value = $Value;
        $this->Tooltip = preg_replace( '!"!is', "'", $Tooltip );
        $this->Icon = $Icon;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $Template = $this->getTemplate( __DIR__.'/Tooltip.twig' );

        $Template->setVariable('ElementHash', sha1(uniqid('Text-Tooltip', true)));
        $Template->setVariable('ElementContent', $this->Value);
        $Template->setVariable('ElementTooltip', $this->Tooltip);
        $Template->setVariable('ElementIcon', $this->Icon);

        return $Template->getContent();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getContent();
    }

    /**
     * @return string
     */
    public function getValue()
    {

        return $this->Value;
    }
}