<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component\Translate;
/**
 * Class Parameter
 * @package SPHERE\Application\Platform\Utility\Translation\Component\Translate
 */
class Parameter
{
    /** @var array $Register */
    private $Register = array();

    /**
     * Parameter constructor.
     * @param array $ParameterList Key-Value pairs
     */
    public function __construct($ParameterList = array())
    {
        $this->Register = $ParameterList;
    }

    /**
     * @param string $Key
     * @param mixed $Value
     * @return $this
     */
    public function addValue($Key, $Value)
    {
        $this->Register[$Key] = $Value;
        return $this;
    }
}