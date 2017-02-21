<?php
namespace SPHERE\Application\Platform\Utility\Favorite\Service;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\Application\Platform\Utility\Favorite\Service\Entity\TblFavorite;
use SPHERE\System\Database\Binding\AbstractData;
use SPHERE\System\Database\Fitting\Element;

/**
 * Class Data
 * @package SPHERE\Application\Platform\Utility\Favorite\Service
 */
class Data extends AbstractData
{
    /**
     * @return void
     */
    public function setupDatabaseContent()
    {
        // TODO: Implement setupDatabaseContent() method.
    }

    /**
     * @param int $Id
     * @param TblAccount|null $TblAccount
     * @return null|Element|TblFavorite
     */
    public function getFavoriteById($Id, TblAccount $TblAccount = null)
    {
        return $this->getCachedEntityBy(
            __METHOD__, $this->getEntityManager(), (new TblFavorite())->getEntityShortName(), array(
                TblFavorite::ENTITY_ID => $Id,
                TblFavorite::SERVICE_TBL_ACCOUNT => ($TblAccount ? $TblAccount->getId() : $TblAccount)
            )
        );
    }

    /**
     * @param TblAccount|null $TblAccount
     * @return null|Element[]|TblFavorite[]
     */
    public function getFavoriteAll(TblAccount $TblAccount = null)
    {
        return $this->getCachedEntityListBy(
            __METHOD__, $this->getEntityManager(), (new TblFavorite())->getEntityShortName(), array(
                TblFavorite::SERVICE_TBL_ACCOUNT => ($TblAccount ? $TblAccount->getId() : $TblAccount)
            )
        , array( TblFavorite::ATTR_TITLE => self::ORDER_ASC ) );
    }

    /**
     * @param string $Route
     * @param TblAccount|null $TblAccount
     * @return null|Element|TblFavorite
     */
    public function getFavoriteByRoute($Route, TblAccount $TblAccount = null)
    {
        return $this->getCachedEntityBy(
            __METHOD__, $this->getEntityManager(), (new TblFavorite())->getEntityShortName(), array(
                TblFavorite::ATTR_ROUTE => $Route,
                TblFavorite::SERVICE_TBL_ACCOUNT => ($TblAccount ? $TblAccount->getId() : $TblAccount)
            )
        );
    }

    /**
     * @param string $Route
     * @param string $Title
     * @param string $Description
     * @param TblAccount|null $TblAccount
     * @return TblFavorite
     */
    public function insertFavorite($Route, $Title, $Description, TblAccount $TblAccount = null)
    {
        $Entity = new TblFavorite();
        $Entity->setServiceTblAccount($TblAccount);
        $Entity->setTitle($Title);
        $Entity->setDescription($Description);
        $Entity->setRoute($Route);

        $this->getEntityManager()->saveEntity($Entity);
        Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        return $Entity;
    }

    public function updateFavorite()
    {

    }

    /**
     * @param TblFavorite $TblFavorite
     * @return bool
     */
    public function deleteFavorite( TblFavorite $TblFavorite )
    {
        $Entity = $this->getForceEntityById(
            __METHOD__, $this->getEntityManager(), (new TblFavorite())->getEntityShortName(), $TblFavorite->getId()
        );
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $this->getEntityManager()->killEntity($Entity);
            return true;
        }
        return false;
    }

    public function queryFavoriteByRoute($Route, TblAccount $TblAccount = null)
    {
        $Manager = $this->getEntityManager();
        $Builder = $Manager->getQueryBuilder();

        $Builder
            ->select('E')
            ->from((new TblFavorite())->getEntityFullName(), 'E')
            ->where(
                $Builder->expr()->andX(
                    $Builder->expr()->eq(TblFavorite::ATTR_ROUTE, $Route),
                    $Builder->expr()->eq(TblFavorite::ENTITY_ID, ($TblAccount ? $TblAccount->getId() : $TblAccount))
                )
            )
            ->setMaxResults(1);

        $Query = $Builder->getQuery();

        return $Query->getOneOrNullResult();
    }
}