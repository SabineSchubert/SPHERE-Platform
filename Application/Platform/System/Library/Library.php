<?php
namespace SPHERE\Application\Platform\System\Library;

use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Common\Frontend\Icon\Repository\Server;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;
use SPHERE\Library\Script;
use SPHERE\Library\Style;
use SPHERE\System\Cache\Handler\CookieHandler;
use SPHERE\System\Extension\Extension;

/**
 * Class Library
 *
 * @package SPHERE\Application\Platform\System\Library
 */
class Library extends Extension implements IModuleInterface
{
    public static function registerModule()
    {
        Main::getDisplay()->addModuleNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Bibliotheken'), new Link\Icon(new Server()))
        );

        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__,
                __CLASS__ . '::frontendLibrary'
            )
        );
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
     * @param int $Lib
     * @return Stage
     */
    public function frontendLibrary($Lib = null)
    {
        $Stage = new Stage('Bibliotheken', 'Style / Javascript');

        $Cache = $this->getCache(new CookieHandler());
        if( !$Lib ) {
            if (!($Lib = $Cache->getValue(sha1(__METHOD__)))) {
                $Lib = 1;
            }
        } else {
            $Cache->setValue(sha1(__METHOD__), $Lib, 3600);
        }

        $Stage->addButton(
            new Standard('Style Library', new Link\Route(__NAMESPACE__), null, array('Lib' => 1))
        );
        $Stage->addButton(
            new Standard('Script Library', new Link\Route(__NAMESPACE__), null, array('Lib' => 2))
        );

        switch ($Lib) {
            case 2:
                $Lib = (new Script(null))->getShow();
                break;
            case 1:
            default:
                $Lib = (new Style(null))->getShow();
                break;
        }

        $Stage->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn($Lib)
                    )
                )
            )
        );
        return $Stage;
    }
}
