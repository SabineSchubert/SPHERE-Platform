<?php

namespace SPHERE\Application\Platform\Utility\Transfer;

use SPHERE\System\Extension\Extension;

/**
 * Class Sanitizer
 *
 * @package SPHERE\Application\Transfer\Gateway\Converter
 */
class Sanitizer extends Extension
{

    /**
     * @param $Value
     *
     * @return string
     */
    protected function sanitizeFullTrim($Value)
    {

        return trim($Value);
    }

    /**
     * @param $Value
     *
     * @return string
     */
    protected function sanitizeAddressCityCode($Value)
    {

        return str_pad($Value, 5, '0', STR_PAD_LEFT);
    }

    /**
     * @param $Value
     *
     * @return string
     */
    protected function sanitizeAddressCityDistrict($Value)
    {

        $Value = explode('OT', $Value);
        return (count($Value) > 1 ? trim(end($Value)) : '');
    }

    /**
     * @param $Value
     *
     * @return string
     * @throws \Exception
     */
    protected function sanitizeAddressCityName($Value)
    {

        $Value = explode('OT', $Value);
        return trim(current($Value));
    }

    /**
     * @param $Value
     *
     * @return bool|string
     * @throws \Exception
     */
    protected function sanitizeDate($Value)
    {

        return date('d.m.Y', \PHPExcel_Shared_Date::ExcelToPHP($Value));
    }

    /**
     * @param $Value
     *
     * @return bool|string
     * @throws \Exception
     */
    protected function sanitizeDateTime($Value)
    {
//        return date('d.m.Y H:i:s', \PHPExcel_Shared_Date::ExcelToPHP($Value));
        /** @var \DateTime $DateTime */
        $DateTime = \PHPExcel_Shared_Date::ExcelToPHPObject($Value);
        return $DateTime->format('d.m.Y H:i:s');
    }

    /**
     * @param $Value
     *
     * @return bool|string
     * @throws \Exception
     */
    protected function sanitizeMailAddress($Value)
    {

        if (!empty($Value)) {
            $Value = $this->validateMailAddress($Value);

            if (empty($Value)) {
                throw new \Exception('Email: Adresse muss ein gültiges Format haben');
            }

            return $Value;
        }
        return '';
    }

}
