<?php
namespace SPHERE\Application\Platform;

use SPHERE\Application\IClusterInterface;
use SPHERE\Application\Platform\Assistance\Assistance;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Gatekeeper\Gatekeeper;
use SPHERE\Application\Platform\System\System;
use SPHERE\Common\Frontend\Icon\Repository\CogWheels;
use SPHERE\Common\Frontend\Layout\Repository\Label;
use SPHERE\Common\Frontend\Text\Repository\Bold;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;

/**
 * Class System
 *
 * @package SPHERE\Application\System
 */
class Platform implements IClusterInterface
{

    public static function registerCluster()
    {

        $TblAccount = Account::useService()->getAccountBySession();
        $TblIdentification = Account::useService()->getIdentificationByName('System');
        if ($TblAccount && $TblIdentification) {
            if ($TblAccount->getServiceTblIdentification()
                && $TblAccount->getServiceTblIdentification()->getId() == $TblIdentification->getId()
            ) {
                Main::getDisplay()->addServiceNavigation(
                    new Link(
                        new Link\Route('/Setting/MyAccount/Consumer'),
                        new Link\Name(
                            new Bold(new Label(
                                'Mandant '
                                . ($TblAccount->getServiceTblConsumer() ? $TblAccount->getServiceTblConsumer()->getAcronym() : '')
                                , Label::LABEL_TYPE_DANGER))
                        )
                    )
                );
            }
        }

        System::registerApplication();
        Gatekeeper::registerApplication();
        Assistance::registerApplication();

        Main::getDisplay()->addServiceNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Plattform'), new Link\Icon(new CogWheels()))
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__, __CLASS__ . '::frontendPlatform')
        );
    }

    /**
     * @return Stage
     */
    public function frontendPlatform()
    {

        $Stage = new Stage('Plattform', 'Systemkonfiguration');

        ob_start();
        phpinfo();
        $PhpInfo = ob_get_clean();

        $Stage->setContent(
            '<div id="phpinfo">'
            . preg_replace('!,!', ', ',
                preg_replace('!<th>(enabled)\s*</th>!i',
                    '<th><span class="badge badge-success">$1</span></th>',
                    preg_replace('!<td class="v">(On|enabled|active|Yes)\s*</td>!i',
                        '<td class="v"><span class="badge badge-success">$1</span></td>',
                        preg_replace('!<td class="v">(Off|disabled|No)\s*</td>!i',
                            '<td class="v"><span class="badge badge-danger">$1</span></td>',
                            preg_replace('!<i>no value</i>!',
                                '<span class="label label-warning">no value</span>',
                                preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $PhpInfo)
                            )
                        )
                    )
                )
            )
            . '</div>'
        );
        return $Stage;
    }
}
