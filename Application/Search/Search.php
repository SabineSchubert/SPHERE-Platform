<?php
namespace SPHERE\Application\Search;

use SPHERE\Application\Api\Search\Search as SearchApi;
use SPHERE\Application\AppTrait;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\IClusterInterface;
use SPHERE\Application\IModuleInterface;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Main;

class Search implements IClusterInterface, IApplicationInterface, IModuleInterface
{
use AppTrait;

    public static function registerApplication()
    {
        self::registerModule();
    }

    public static function registerCluster()
    {
        self::registerApplication();

        self::createRouting( __NAMESPACE__, __NAMESPACE__.'\Frontend', 'frontendSearch' );
        $Link = self::createNavigation( __NAMESPACE__, 'Suche', new SearchIcon() );

        $Link->ajaxPipelineOnClick( SearchApi::pipelineOpenSearch() );

        Main::getDisplay()->addClusterNavigation( $Link );
    }

    public static function registerModule()
    {

    }

    /**
     * @return Service
     */
    public static function useService()
    {
        // TODO: Implement useService() method.
    }

    /**
     * @return Frontend
     */
    public static function useFrontend()
    {
        return new Frontend();
    }

}