<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use SPHERE\Application\Platform\Utility\Translation\IComponentInterface;

class Singular extends AbstractComponent  implements IComponentInterface
{
    public function __construct($Content)
    {
        $this->setIdentifier( $Content );
    }
}
