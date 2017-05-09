<?php

namespace SPHERE\Application\Platform\Utility\Translation;

use SPHERE\Application\Platform\Utility\Translation\Component\Translate;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Group;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Parameter;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Preset;

/**
 * Class TranslateTrait
 * @package SPHERE\Application\Platform\Utility\Translation
 */
trait TranslateTrait
{
    /**
     * @param array $Group
     * @param string $Pattern
     * @param array $Parameter
     * @param string $Locale
     * @return Translate
     */
    public static function doTranslate($Group, $Pattern, $Parameter = array(), $Locale = TranslationInterface::LOCALE_EN_US)
    {
        if( !is_array( $Group ) ) {
            $Group = array( $Group );
        }

        if (empty($Parameter)) {
            return new Translate(
                self::getGroupPath($Group), new Preset($Pattern, null, $Locale)
            );
        } else {
            return new Translate(
                self::getGroupPath($Group), new Preset($Pattern, new Parameter($Parameter), $Locale)
            );
        }
    }

    /**
     * @param array $List
     * @return null|Group
     */
    private static function getGroupPath($List)
    {
        krsort($List);
        $Group = null;
        foreach ($List as $Identifier) {
            $Group = new Group($Identifier, $Group);
        }
        return $Group;
    }
}
