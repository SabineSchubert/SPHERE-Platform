<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Group;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Preset;

/**
 * Class Translate
 * @package SPHERE\Application\Platform\Utility\Translation\Component
 */
class Translate extends AbstractComponent
{

    /** @var null|Group $Group */
    private $Group = null;
    /** @var null|Preset $Preset */
    private $Preset = null;

    /**
     * Translate constructor.
     * @param Group $Group
     * @param Preset $Preset
     */
    public function __construct(Group $Group, Preset $Preset)
    {
        $this->setGroup($Group);
        $this->Preset = $Preset;
        $this->Preset->setBreadCrumb($this->getGroup());
    }

    /**
     * @return null|Group
     */
    public function getGroup()
    {
        return $this->Group;
    }

    /**
     * @param null|Group $Group
     */
    public function setGroup($Group)
    {
        $this->Group = $Group;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->Group->getIdentifier();
    }

    /**
     * @return array
     */
    public function getDefinition()
    {
        return array_merge(
            array( 'Identifier' => $this->getIdentifier() ),
            $this->Group->getDefinition($this->Preset)
        );
    }

    /**
     * @return null|Preset
     */
    public function getPreset()
    {
        return $this->Preset;
    }

    /**
     * @param null|Preset $Preset
     */
    public function setPreset($Preset)
    {
        $this->Preset = $Preset;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->Preset;
    }
}
