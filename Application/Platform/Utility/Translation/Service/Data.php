<?php
namespace SPHERE\Application\Platform\Utility\Translation\Service;

use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\Application\Platform\Utility\Translation\Service\Entity\TblTranslationLocale;
use SPHERE\System\Database\Binding\AbstractData;
use SPHERE\System\Database\Binding\AbstractEntity;
use SPHERE\System\Database\Fitting\Element;

/**
 * Class Data
 * @package SPHERE\Application\Platform\Utility\Translation\Service
 */
class Data extends AbstractData
{
    /**
     * @return void
     */
    public function setupDatabaseContent()
    {
        if (!$this->getLocaleByIdentifier(TblTranslationLocale::LOCALE_EN_US)) {
            $this->insertLocale(TblTranslationLocale::LOCALE_EN_US, 'United States - English', 'Default');
        }
        if (!$this->getLocaleByIdentifier(TblTranslationLocale::LOCALE_DE_DE)) {
            $this->insertLocale(TblTranslationLocale::LOCALE_DE_DE, 'Germany - German', 'Default');
        }
    }

    /**
     * @param string $Identifier
     * @return null|AbstractEntity|Element
     */
    public function getLocaleByIdentifier($Identifier)
    {
        return $this->getCachedEntityBy(
            __METHOD__, $this->getEntityManager(), (new TblTranslationLocale())->getEntityShortName(), array(
                TblTranslationLocale::ATTR_IDENTIFIER => (string)$Identifier
            )
        );
    }

    /**
     * @param string $Identifier
     * @param string $Name
     * @param string $Description
     * @return TblTranslationLocale
     */
    public function insertLocale($Identifier, $Name, $Description = '')
    {
        $Entity = new TblTranslationLocale();
        $Entity->setIdentifier($Identifier);
        $Entity->setName($Name);
        $Entity->setDescription($Description);

        $this->getEntityManager()->saveEntity($Entity);
        Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        return $Entity;
    }

    /**
     * @param int $Id
     * @return null|AbstractEntity|Element
     */
    public function getLocaleById($Id)
    {
        return $this->getCachedEntityById(
            __METHOD__, $this->getEntityManager(), (new TblTranslationLocale())->getEntityShortName(), (int)$Id
        );
    }
}
