<?php
namespace SPHERE\Application\Platform\Utility\Translation;

use SPHERE\Application\Platform\Utility\Translation\Component\Localize;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Group;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Parameter;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Preset;

/**
 * Class TranslationTrait
 * @package SPHERE\Application\Platform\Utility\Translation
 */
trait LocaleTrait
{
    /**
     * @param array $Group
     * @param string $Pattern
     * @param array $Parameter
     * @param string $Locale
     * @return Translate
     */
    public function doTranslate($Group, $Pattern, $Parameter = array(), $Locale = Preset::LOCALE_EN_US)
    {
        if (empty($Parameter)) {
            return new Translate(
                $this->getGroupPath($Group), new Preset($Pattern, null, $Locale)
            );
        } else {
            return new Translate(
                $this->getGroupPath($Group), new Preset($Pattern, new Parameter($Parameter), $Locale)
            );
        }
    }

    /**
     * @param array $List
     * @return null|Group
     */
    private function getGroupPath($List)
    {
        krsort($List);
        $Group = null;
        foreach ($List as $Identifier) {
            $Group = new Group($Identifier, $Group);
        }
        return $Group;
    }

    /**
     * @param $Value
     * @return Localize
     */
    public function doLocalize($Value)
    {
        return new Localize($Value);
    }
}