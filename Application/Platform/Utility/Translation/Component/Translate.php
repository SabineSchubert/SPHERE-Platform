<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Group;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Parameter;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Preset;

class Translate extends AbstractComponent
{

    /** @var null|Group $Group */
    private $Group = null;

    public function __construct(Group $Group, Preset $Preset, Parameter $Parameter = null)
    {
        $this->Group = $Group;
    }

    public function getPath()
    {
        return $this->Group->getPath();
    }
}