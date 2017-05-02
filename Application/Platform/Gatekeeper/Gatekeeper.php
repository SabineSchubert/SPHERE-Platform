<?php
namespace SPHERE\Application\Platform\Gatekeeper;

use SPHERE\Application\AppTrait;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Platform\Gatekeeper\Authentication\Authentication;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Authorization;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Application\Platform\Gatekeeper\Profile\Profile;
use SPHERE\Common\Frontend\Icon\Repository\Shield;
use SPHERE\Common\Window\Stage;

/**
 * Class Gatekeeper
 *
 * @package SPHERE\Application\System\Gatekeeper
 */
class Gatekeeper implements IApplicationInterface
{
    use AppTrait;

    public static function registerApplication()
    {

        Consumer::registerModule();
        Authorization::registerModule();
        Authentication::registerModule();
        Profile::registerModule();

        self::createApplication(__NAMESPACE__,__CLASS__,'frontendGatekeeper', 'Gatekeeper', new Shield());
    }

    /**
     * @return Stage
     */
    public function frontendGatekeeper()
    {

        $Stage = new Stage('Plattform', 'Gatekeeper');

        return $Stage;
    }
}
