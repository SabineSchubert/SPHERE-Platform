<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component\Translate;

class Preset
{

    const LOCALE_EN_US = 'en_US';
    const LOCALE_DE_DE = 'de_DE';

    private $Locale = 'en_US';

    public function __construct( $Locale = self::LOCALE_EN_US )
    {
        $this->Locale = $Locale;
    }
}