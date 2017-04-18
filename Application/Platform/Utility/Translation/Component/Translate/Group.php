<?php

namespace SPHERE\Application\Platform\Utility\Translation\Component\Translate;

/**
 * Class Group
 * @package SPHERE\Application\Platform\Utility\Translation\Component\Translate
 */
class Group
{
    const GROUP_SEPARATOR = '.';

    /** @var string $Identifier */
    private $Identifier = '';
    /** @var null|Group $Group */
    private $Group = null;

    /**
     * Group constructor.
     * @param string $Identifier
     * @param Group|null $Child
     */
    public function __construct($Identifier, Group $Child = null)
    {
        $this->Identifier = preg_replace('![^a-zA-Z-0-9]!is', '_', $Identifier);
        $this->Group = $Child;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getIdentifier();
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        if ($this->Group) {
            return $this->Identifier . self::GROUP_SEPARATOR . $this->Group->getIdentifier();
        } else {
            return $this->Identifier;
        }
    }

    /**
     * @param Group|null $Child
     * @return $this
     */
    public function setChild(Group $Child = null)
    {
        $this->Group = $Child;
        return $this;
    }
}
