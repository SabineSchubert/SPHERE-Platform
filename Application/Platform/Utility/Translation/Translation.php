<?php
namespace SPHERE\Application\Platform\Utility\Translation;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\Utility\Translation\Component\Translate;
use SPHERE\Common\Frontend\Icon\Repository\Conversation;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Repository\Debugger;

/**
 * Class Translation
 * @package SPHERE\Application\Platform\Utility\Translation
 */
class Translation implements IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        self::createModule(__NAMESPACE__, __CLASS__, 'frontendDashboard', 'Mehrsprachigkeit', new Conversation());
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service();
    }

    /**
     * @return IFrontendInterface
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }

    /**
     * @return Stage
     */
    public function frontendDashboard()
    {
        Debugger::screenDump(
            $T = new Translate(
                new Translate\Group(__METHOD__,
                    new Translate\Group('Identifier.1',
                        new Translate\Group('Identifier2')
                    )
                ),
                new Translate\Preset(Translate\Preset::LOCALE_DE_DE)
            ),
            $T->getPath()
        );
        return new Stage(

//            'Normal',
//            new Localization(new Group('Stage', new Group('Headline', new Singular('Normal'))), 'Seite 1')

        /*
            new Localization(new Group('Stage',
                new Plural('Small', new Pattern(array('!^0$!', '!^[0-9]{1}$!', '!^[0-9]{2,}$!'))))
            )
        ,             new Localization(new Group('Stage', new Group('Headline', new Singular('Small'))))
        */
        );
    }
}
