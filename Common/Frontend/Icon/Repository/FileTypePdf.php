<?php
namespace SPHERE\Common\Frontend\Icon\Repository;

use SPHERE\Common\Frontend\Icon\IIconInterface;

/**
 * Class FileTypePdfIcon
 *
 * @package SPHERE\Common\Frontend\Icon\Repository
 */
class FileTypePdf implements IIconInterface
{

    /** @var string $Value */
    private $Value = 'filetypes filetypes-pdf';

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
    public function getContent()
    {

        return '<span class="'.$this->getValue().'" aria-hidden="true"></span>';
    }

    /**
     * @return string
     */
    public function getValue()
    {

        return $this->Value;
    }
}
