<?php
namespace SPHERE\Common\Frontend\Text\Repository;

use SPHERE\Common\Frontend\Text\ITextInterface;

/**
 * Class Muted
 *
 * @package SPHERE\Common\Frontend\Text\Repository
 */
class Muted implements ITextInterface
{

    /** @var string $Value */
    private $Value = '';

    /**
     * @param $Value
     */
    public function __construct($Value)
    {

        $this->Value = $Value;
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
    public function getContent()
    {

        return '<span class="text-muted">'.$this->getValue().'</span>';
    }

    /**
     * @return string
     */
    public function getValue()
    {

        return $this->Value;
    }
}
