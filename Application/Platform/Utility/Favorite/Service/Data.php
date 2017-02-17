<?php
namespace SPHERE\Application\Platform\Utility\Favorite\Service;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\Application\Platform\Utility\Favorite\Service\Entity\TblFavoriteNavigation;
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
     * @return null|Element|TblFavoriteNavigation
     */
    public function getFavoriteById($Id, TblAccount $TblAccount = null)
    {
        return $this->getCachedEntityBy(
            __METHOD__, $this->getEntityManager(), (new TblFavoriteNavigation())->getEntityShortName(), array(
                TblFavoriteNavigation::ENTITY_ID => $Id,
                TblFavoriteNavigation::SERVICE_TBL_ACCOUNT => ($TblAccount ? $TblAccount->getId() : $TblAccount)
            )
        );
    }

    /**
     * @param string $Route
     * @param TblAccount|null $TblAccount
     * @return null|Element|TblFavoriteNavigation
     */
    public function getFavoriteByRoute($Route, TblAccount $TblAccount = null)
    {
        return $this->getCachedEntityBy(
            __METHOD__, $this->getEntityManager(), (new TblFavoriteNavigation())->getEntityShortName(), array(
                TblFavoriteNavigation::ATTR_ROUTE => $Route,
                TblFavoriteNavigation::SERVICE_TBL_ACCOUNT => ($TblAccount ? $TblAccount->getId() : $TblAccount)
            )
        );
    }

    /**
     * @param string $Route
     * @param string $Name
     * @param TblAccount|null $TblAccount
     * @return TblFavoriteNavigation
     */
    public function insertFavorite($Route, $Name, TblAccount $TblAccount = null)
    {
        $Entity = new TblFavoriteNavigation();
        $Entity->setServiceTblAccount($TblAccount);
        $Entity->setName($Name);
        $Entity->setRoute($Route);

        $this->getEntityManager()->saveEntity($Entity);
        Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        return $Entity;
    }

    public function updateFavorite()
    {

    }

    public function deleteFavorite()
    {

    }

    public function queryFavoriteByRoute($Route, TblAccount $TblAccount = null)
    {
        $Manager = $this->getEntityManager();
        $Builder = $Manager->getQueryBuilder();

        $Builder
            ->select('E')
            ->from((new TblFavoriteNavigation())->getEntityFullName(), 'E')
            ->where(
                $Builder->expr()->andX(
                    $Builder->expr()->eq(TblFavoriteNavigation::ATTR_ROUTE, $Route),
                    $Builder->expr()->eq(TblFavoriteNavigation::ENTITY_ID, ($TblAccount ? $TblAccount->getId() : $TblAccount))
                )
            )
            ->setMaxResults(1);

        $Query = $Builder->getQuery();

        return $Query->getOneOrNullResult();
    }

    /**
     * @param TblAccount $TblAccount
     * @param $Route
     * @param $Name
     * @return null|TblFavoriteNavigation|\SPHERE\System\Database\Fitting\Element
     */
    public function createFavorite(TblAccount $TblAccount, $Route, $Name)
    {
        $Entity = $this->getForceEntityBy(__METHOD__, $this->getEntityManager(),
            (new TblFavoriteNavigation())->getEntityShortName(), array(
                TblFavoriteNavigation::SERVICE_TBL_ACCOUNT => $TblAccount->getId(),
                TblFavoriteNavigation::ATTR_ROUTE => $Route
            )
        );

        if (!$Entity) {

            $Entity = new TblFavoriteNavigation();
            $Entity->setServiceTblAccount($TblAccount);
            $Entity->setName($Name);
            $Entity->setRoute($Route);

            $this->getEntityManager()->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }

        return $Entity;
    }
}