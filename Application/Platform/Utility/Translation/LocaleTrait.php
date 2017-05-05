<?php
namespace SPHERE\Application\Platform\Utility\Translation;

use SPHERE\Application\Platform\Utility\Translation\Component\Localize;

/**
 * Class TranslationTrait
 * @package SPHERE\Application\Platform\Utility\Translation
 */
trait LocaleTrait
{

    /**
     * @param $Value
     * @return Localize
     */
    public static function doLocalize($Value)
    {
        return new Localize($Value);
    }
}
