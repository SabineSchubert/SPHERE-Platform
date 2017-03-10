<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component\Translate;

class Group
{
    /** @var string $Identifier */
    private $Identifier = '';
    /** @var null|Group $Group */
    private $Group = null;

    public function __construct($Identifier, Group $Child = null)
    {
        $this->Identifier = preg_replace( '![^:\w\d]!is','_', $Identifier );
        $this->Group = $Child;
    }

    public function getDefinition( Preset $Preset )
    {
        if( $this->Group ) {
            return array( $this->Identifier => $this->Group->getDefinition( $Preset ) );
        } else {
            return array( $this->Identifier => array(
                'Default' => array(
                    'Locale' => $Preset->getDefaultLocale(),
                    'Pattern' => $Preset->getDefaultPattern(),
                ),
                'Pattern' => $Preset->getPatternList(),
                'Parameter' => $Preset->getParameter()->getParameterList(),
                'Switch' => $Preset->getParameter()->getSwitch()
            ) );
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if( $this->Group ) {
            return $this->Identifier.' > '.$this->Group->__toString();
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
