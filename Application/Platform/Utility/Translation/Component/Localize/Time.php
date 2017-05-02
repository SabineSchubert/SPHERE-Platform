<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component\Localize;

use SPHERE\Application\Platform\Utility\Translation\Component\AbstractComponent;

/**
 * Class Time
 * @package SPHERE\Application\Platform\Utility\Translation\Component\Localize
 */
class Time extends AbstractComponent
{
    /** @var null|int|string|\DateTime $Value */
    private $Value = null;
    /** @var int $Style */
    private $DateStyle = 0;
    /** @var int $Style */
    private $TimeStyle = 0;

    /**
     * Time constructor.
     * @param $Value
     * @param $DateStyle
     * @param $TimeStyle
     */
    public function __construct($Value, $DateStyle, $TimeStyle)
    {
        $this->Value = $Value;
        $this->DateStyle = $DateStyle;
        $this->TimeStyle = $TimeStyle;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->createDateFormatter()->format($this->Value);
    }

    /**
     * @return \IntlDateFormatter
     */
    private function createDateFormatter()
    {
        return new \IntlDateFormatter(
            $this->getClientLocale(),
            $this->DateStyle,
            $this->TimeStyle,
            $this->getClientTimezone()
        );
    }
}
