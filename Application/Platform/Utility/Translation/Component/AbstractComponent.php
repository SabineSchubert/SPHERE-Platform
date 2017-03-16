<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use MOC\V\Core\HttpKernel\HttpKernel;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\System\Cache\CacheFactory;
use SPHERE\System\Cache\Handler\MemoryHandler;

/**
 * Class AbstractComponent
 * @package SPHERE\Application\Platform\Utility\Translation\Component
 */
abstract class AbstractComponent
{
    /**
     * @return string
     */
    protected function getLocale()
    {
        $Cache = (new CacheFactory())->getCache(new MemoryHandler());

        if (!($Locale = $Cache->getValue('Locale', __METHOD__))) {

            $TblAccount = Account::useService()->getAccountBySession();
            if ($TblAccount) {
                // TODO Replace with fetch Locale Setting from Account
                $Locale = $this->getBrowserLocale();
            } else {
                $Locale = $this->getBrowserLocale();
            }

            $Cache->setValue('Locale', $Locale, 0, __METHOD__);
        }

        return $Locale;
    }

    /**
     * @return string
     */
    private function getBrowserLocale()
    {
        $LanguageList = HttpKernel::getRequest()->getLanguageList();
        if (!empty($LanguageList)) {
            reset($LanguageList);
            return current($LanguageList);
        }
        return $this->getFallbackLocale();
    }

    /**
     * @return string
     */
    private function getFallbackLocale()
    {
        return 'en_US';
    }
}