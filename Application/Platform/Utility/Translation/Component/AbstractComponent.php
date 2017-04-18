<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use MOC\V\Core\HttpKernel\HttpKernel;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Utility\Translation\TranslationInterface;
use SPHERE\System\Cache\CacheFactory;
use SPHERE\System\Cache\Handler\MemoryHandler;

/**
 * Class AbstractComponent
 * @package SPHERE\Application\Platform\Utility\Translation\Component
 */
abstract class AbstractComponent implements TranslationInterface
{
    /**
     * @return string
     */
    protected function getClientLocale()
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

    protected function getClientTimezone() {

        $Cache = (new CacheFactory())->getCache(new MemoryHandler());

        if (!($Timezone = $Cache->getValue('Timezone', __METHOD__))) {

            $TblAccount = Account::useService()->getAccountBySession();
            if ($TblAccount) {
                // TODO Replace with fetch Timezone Setting from Account
                $Timezone = $this->getBrowserTimezone();
            } else {
                $Timezone = $this->getBrowserTimezone();
            }

            $Cache->setValue('Timezone', $Timezone, 0, __METHOD__);
        }

        return $Timezone;
    }

    /**
     * @return string
     */
    private function getBrowserTimezone()
    {
        if( isset( $_COOKIE['ClientTimeZone'] ) ) {
            if( preg_match('![a-z//_]!is', $_COOKIE['ClientTimeZone']) ) {
                if( in_array(  $_COOKIE['ClientTimeZone'], timezone_identifiers_list() ) ) {
                    return $_COOKIE['ClientTimeZone'];
                }
            }
        }
        return $this->getFallbackTimezone();
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
    private function getFallbackTimezone()
    {
        return date_default_timezone_get();
    }

    /**
     * @return string
     */
    private function getFallbackLocale()
    {
        return 'en_US';
    }
}
