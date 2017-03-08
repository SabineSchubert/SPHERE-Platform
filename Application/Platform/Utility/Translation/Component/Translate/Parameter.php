<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component\Translate;
/**
 * Class Parameter
 * @package SPHERE\Application\Platform\Utility\Translation\Component\Translate
 */
class Parameter
{
    /** @var array $ParameterList */
    private $ParameterList = array();
    /** @var string $Switch */
    private $Switch = '';

    /**
     * Parameter constructor.
     * @param array $ParameterList Key-Value pairs
     * @param string $Switch
     */
    public function __construct($ParameterList = array(), $Switch = '')
    {
        $this->ParameterList = $ParameterList;
        $this->Switch = $Switch;
    }

    /**
     * @param string $Key
     * @param mixed $Value
     * @return $this
     */
    public function addValue($Key, $Value)
    {
        $this->ParameterList[$Key] = $Value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSwitch()
    {
        return $this->Switch;
    }

    /**
     * @return array
     */
    public function getParameterList()
    {
        return $this->ParameterList;
    }
}
