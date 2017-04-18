<?php
namespace SPHERE\Application\Platform\Utility\Translation\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use SPHERE\Application\Platform\Utility\Translation\Service\Entity\TblTranslationGroup;
use SPHERE\Application\Platform\Utility\Translation\Service\Entity\TblTranslationLocale;
use SPHERE\Application\Platform\Utility\Translation\Service\Entity\TblTranslationParameter;
use SPHERE\System\Database\Binding\AbstractSetup;

/**
 * Class Setup
 * @package SPHERE\Application\Platform\Utility\Translation\Service
 */
class Setup extends AbstractSetup
{
    /**
     * @param bool $Simulate
     *
     * @return string
     */
    public function setupDatabaseSchema($Simulate = true)
    {
        $Schema = $this->loadSchema();

        $this->setTableLocale($Schema);
        $this->setTableGroup($Schema);
        $this->setTableParameter($Schema);

        return $this->saveSchema($Schema, $Simulate);
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableLocale(Schema $Schema)
    {
        $Table = $this->createTable($Schema, (new TblTranslationLocale())->getEntityShortName());
        $this->createColumn($Table, TblTranslationLocale::ATTR_IDENTIFIER, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, TblTranslationLocale::ATTR_NAME, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, TblTranslationLocale::ATTR_DESCRIPTION, self::FIELD_TYPE_TEXT);

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableGroup(Schema $Schema)
    {
        $Table = $this->createTable($Schema, (new TblTranslationGroup())->getEntityShortName());
        $this->createColumn($Table, TblTranslationGroup::ATTR_IDENTIFIER, self::FIELD_TYPE_STRING);

        return $Table;
    }

    /**
     * @param Schema $Schema
     * @return Table
     */
    private function setTableParameter(Schema $Schema)
    {
        $Table = $this->createTable($Schema, (new TblTranslationParameter())->getEntityShortName());
        $this->createColumn($Table, TblTranslationParameter::ATTR_IDENTIFIER, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, TblTranslationParameter::ATTR_TYPE, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, TblTranslationParameter::ATTR_NAME, self::FIELD_TYPE_STRING);
        $this->createColumn($Table, TblTranslationParameter::ATTR_DESCRIPTION, self::FIELD_TYPE_STRING);

        return $Table;
    }
}
