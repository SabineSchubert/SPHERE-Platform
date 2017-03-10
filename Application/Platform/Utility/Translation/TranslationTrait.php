<?php
namespace SPHERE\Application\Platform\Utility\Translation;

use SPHERE\Application\Platform\Utility\Translation\Component\Translate;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Group;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate\Preset;

/**
 * Class TranslationTrait
 * @package SPHERE\Application\Platform\Utility\Translation
 */
trait TranslationTrait
{
    /**
     * @param array $IdentifierList
     * @param string $DefaultPattern
     * @param string $DefaultLocale
     * @return Translate
     */
    public function translateSimple($IdentifierList, $DefaultPattern, $DefaultLocale = Preset::LOCALE_EN_US)
    {

        krsort($IdentifierList);
        $Category = null;
        foreach ($IdentifierList as $Identifier) {
            $Category = new Group($Identifier, $Category);
        }
        return new Translate($Category, new Preset($DefaultPattern, null, $DefaultLocale));
    }
}