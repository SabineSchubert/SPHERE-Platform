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
     * @return Localize\Number
     */
    public function getCurrency()
    {
        return new Number($this->Value, \NumberFormatter::CURRENCY);
    }

    /**
     * @return Localize\Time
     */
    public function getDateTime()
    {
        return new Time($this->Value, \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT);
    }

    /**
     * @return Localize\Time
     */
    public function getDate()
    {
        return new Time($this->Value, \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE);
    }

    /**
     * @return Localize\Time
     */
    public function getTime()
    {
        return new Time($this->Value, \IntlDateFormatter::NONE, \IntlDateFormatter::SHORT);
    }
}
