<?php
namespace SPHERE\Application\Platform\Assistance;

use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Platform\Assistance\Error\Error;

/**
 * Class Assistance
 *
 * @package SPHERE\Application\System\Assistance
 */
class Assistance implements IApplicationInterface
{

    public static function registerApplication()
    {

        /**
         * Register Module
         */
        Error::registerModule();
    }
}
