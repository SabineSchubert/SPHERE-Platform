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
        $this->Identifier = preg_replace( '![^\w\d]!is','_', $Identifier );
        $this->Group = $Child;
    }

    public function getPath()
    {
        if( $this->Group ) {
            return array( $this->Identifier => $this->Group->getPath() );
//            return $this->Identifier.'-'.$this->Group->getPath();
        } else {
            return $this->Identifier;
        }
    }
}