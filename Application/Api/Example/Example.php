<?php

namespace SPHERE\Application\Api\Example;

use SPHERE\Application\Api\Example\Upload\Upload;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access;
use SPHERE\System\Extension\Repository\Debugger;

class Example implements IApplicationInterface
{

    public static function registerApplication()
    {

        if (($TblPrivilege = Access::useService()->getPrivilegeByName('Sphere-System'))) {
            if (!($TblRight = Access::useService()->getRightByName(Upload::getEndpoint()))) {
                $TblRight = Access::useService()->insertRight(Upload::getEndpoint());
                Access::useService()->addPrivilegeRight($TblPrivilege, $TblRight);
            }
        }

        Upload::registerApi();
    }
}