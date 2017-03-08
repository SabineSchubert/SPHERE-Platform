<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use SPHERE\Application\Platform\Utility\Translation\Component\Localize\Number;
use SPHERE\Application\Platform\Utility\Translation\Component\Localize\Time;

/**
 * Class Localize
 * @package SPHERE\Application\Platform\Utility\Translation\Component
 */
class Localize extends AbstractComponent
{
    /** @var int|string|\DateTime|float $Value */
    private $Value = null;

    /**
     * Localize constructor.
     * @param $Value
     */
    public function __construct($Value)
    {
        $this->Value = $Value;
    }

    /**
     * @return Number
     */
    public function getCurrency()
    {
        return new Number($this->Value, \NumberFormatter::CURRENCY);
    }

    /**
     * @return Time
     */
    public function getDateTime()
    {
        return new Time($this->Value, \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT);
    }

    /**
     * @return Time
     */
    public function getDate()
    {
        return new Time($this->Value, \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
    }

    /**
     * @return Time
     */
    public function getTime()
    {
        return new Time($this->Value, \IntlDateFormatter::NONE, \IntlDateFormatter::SHORT);
    }
}
