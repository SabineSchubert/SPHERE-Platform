<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;

/**
 * Class Account
 * @package SPHERE\Application\Api\Platform\Gatekeeper
 */
class Account implements IApiInterface
{
    use ApiTrait;

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        // TODO: Implement exportApi() method.

        return $Dispatcher->callMethod($Method);
    }
}
