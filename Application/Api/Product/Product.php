<?php
namespace SPHERE\Application\Api\Product;

use SPHERE\Application\Api\Product\Filter\Filter;
use SPHERE\Application\IApplicationInterface;

/**
 * Class Product
 *
 * @package SPHERE\Application\Api\Product
 */
class Product implements IApplicationInterface
{
    public static function registerApplication()
    {
        Filter::registerApi();
    }
}