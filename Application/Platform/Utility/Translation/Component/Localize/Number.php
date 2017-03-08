<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component\Localize;

use SPHERE\Application\Platform\Utility\Translation\Component\AbstractComponent;

/**
 * Class Number
 * @package SPHERE\Application\Platform\Utility\Translation\Component\Localize
 */
class Number extends AbstractComponent
{
    /** @var null|int|string|float $Value */
    private $Value = null;
    /** @var int $Style */
    private $Style = 0;

    /**
     * Time constructor.
     * @param $Value
     * @param $Style
     */
    public function __construct($Value, $Style)
    {
        $this->Value = $Value;
        $this->Style = $Style;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->createNumberFormatter()->format($this->Value);
    }

    /**
     * @return \NumberFormatter
     */
    private function createNumberFormatter()
    {
        return new \NumberFormatter(
            $this->getLocale(), $this->Style
        );
    }
}
