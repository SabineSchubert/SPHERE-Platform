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
        $this->Identifier = preg_replace('![^:\w\d]!is', '_', $Identifier);
        $this->Group = $Child;
    }

    public function getIdentifier()
    {
        if ($this->Group) {
            return $this->Identifier . self::GROUP_SEPARATOR . $this->Group->getIdentifier();
        } else {
            return $this->Identifier;
        }
    }

    /**
     * @param Preset $Preset
     * @return array
     */
    public function getDefinition(Preset $Preset)
    {
        if ($this->Group) {
            return $this->Group->getDefinition($Preset);
        } else {
            return array(
                $Preset->getDefaultLocale() => $Preset->getDefaultPattern(),
                'Pattern' => $Preset->getPatternList(),
                'Parameter' => $Preset->getParameter()->getParameterList(),
                'Switch' => $Preset->getParameter()->getSwitch()
            );
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->Group) {
            return $this->Identifier . self::GROUP_SEPARATOR . $this->Group->__toString();
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
