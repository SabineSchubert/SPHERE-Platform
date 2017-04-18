<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component\Translate;

/**
 * Class Parameter
 * @package SPHERE\Application\Platform\Utility\Translation\Component\Translate
 */
class Parameter
{
    /** @var array $Parameter */
    private $Parameter = array();

    /**
     * Parameter constructor.
     * @param array $Parameter Key-Value pairs
     */
    public function __construct($Parameter = array())
    {
        $this->Parameter = $Parameter;
    }

    /**
     * @param string $Key
     * @param mixed $Value
     * @return $this
     */
    public function setParameter($Key, $Value)
    {
        $this->Parameter[$Key] = $Value;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->Parameter;
    }
}
