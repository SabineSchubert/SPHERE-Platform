<?php
namespace SPHERE\Common\Frontend\Layout\Structure;

use MOC\V\Component\Template\Component\IBridgeInterface;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\ITemplateInterface;
use SPHERE\System\Extension\Extension;

/**
 * Class Slick
 * @package SPHERE\Common\Frontend\Layout\Structure
 */
class Slick extends Extension implements IFrontendInterface, ITemplateInterface
{
    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * Slick constructor.
     */
    public function __construct()
    {

        $this->Template = $this->getTemplate(__DIR__.'/Slick.twig');
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->Template->getContent();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getContent();
    }


}