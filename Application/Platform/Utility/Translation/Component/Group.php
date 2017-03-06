<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use SPHERE\Application\Platform\Utility\Translation\IComponentInterface;

class Group extends AbstractComponent implements IComponentInterface
{
    public function __construct($Identifier, IComponentInterface $Component)
    {
        $this->setIdentifier( $Identifier );
        $this->setComponent( $Component );
    }
}
