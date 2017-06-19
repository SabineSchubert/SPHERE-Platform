<?php
namespace SPHERE\Common\Frontend\Form\Repository\Field;

use SPHERE\Common\Frontend\Form\IFieldInterface;
use SPHERE\Common\Frontend\Form\Repository\AbstractTextField;
use SPHERE\Common\Frontend\Icon\IIconInterface;

/**
 * Class Slider
 *
 * @package SPHERE\Common\Frontend\Form\Repository\Field
 */
class Slider extends AbstractTextField implements IFieldInterface
{
    const LIBRARY_SLIDER = 0;

    /** @var int $Library */
    private $Library = 0;
    /** @var array $Configuration */
    private $Configuration = array();

    /**
     * @param string         $Name
     * @param null|string    $Placeholder
     * @param null|string    $Label
     * @param IIconInterface $Icon
     */
    public function __construct(
        $Name,
        $Placeholder = '',
        $Label = '',
        IIconInterface $Icon = null
    ) {

        $this->Name = $Name;
        $this->Configuration = json_encode( array(), JSON_FORCE_OBJECT );

        $this->Template = $this->getTemplate(__DIR__.'/Slider.twig');
        $this->Template->setVariable('ElementName', $Name);
        $this->Template->setVariable('ElementLabel', $Label);
        $this->Template->setVariable('ElementPlaceholder', $Placeholder);
        if (null !== $Icon) {
            $this->Template->setVariable('ElementIcon', $Icon);
        }
    }

    /**
     * @param int $Library LIBRARY_SLIDER
     * @param array $Configuration
     * @return Slider
     */
    public function configureLibrary($Library = self::LIBRARY_SLIDER, $Configuration = array())
    {
        $this->Library = $Library;
        $this->Configuration = $this->convertLibraryConfiguration( $Configuration );
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $this->Template->setVariable('ElementConfiguration', $this->Configuration);
        return parent::getContent();
    }
}
