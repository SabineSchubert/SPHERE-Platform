<?php

namespace SPHERE\Application\Api;

use SPHERE\Application\Api\Example\Example;
use SPHERE\Application\Api\Platform\Platform;
use SPHERE\Application\Api\Product\Product;
use SPHERE\Application\Api\Reporting\Reporting;
use SPHERE\Application\Api\Search\Search;
use SPHERE\Application\IClusterInterface;

/**
 * Class Api
 *
 * @package SPHERE\Application\Api
 */
class Api implements IClusterInterface
{

    public static function registerCluster()
    {
        Platform::registerApplication();
        Product::registerApplication();
        Reporting::registerApplication();
        Example::registerApplication();
        Search::registerApplication();
    }
}
