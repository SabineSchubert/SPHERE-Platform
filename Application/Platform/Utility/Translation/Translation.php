<?php
namespace SPHERE\Application\Platform\Utility\Translation;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\Utility\Translation\Component\Group;
use SPHERE\Application\Platform\Utility\Translation\Component\Pattern;
use SPHERE\Application\Platform\Utility\Translation\Component\Plural;
use SPHERE\Application\Platform\Utility\Translation\Component\Singular;
use SPHERE\Common\Frontend\Icon\Repository\Conversation;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Window\Stage;

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
     * @return IServiceInterface
     */
    public static function useService()
    {
        // TODO: Implement useService() method.
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


        return new Stage(
            'Normal',
            new Localization(new Group('Stage', new Group('Headline', new Singular('Normal'))), 'Seite 1')

        /*
            new Localization(new Group('Stage',
                new Plural('Small', new Pattern(array('!^0$!', '!^[0-9]{1}$!', '!^[0-9]{2,}$!'))))
            )
        ,             new Localization(new Group('Stage', new Group('Headline', new Singular('Small'))))
        */
        );
    }
}
