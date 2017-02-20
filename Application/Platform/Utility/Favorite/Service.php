<?php
namespace SPHERE\Application\Platform\Utility\Favorite;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Utility\Favorite\Service\Data;
use SPHERE\Application\Platform\Utility\Favorite\Service\Entity\TblFavorite;
use SPHERE\Application\Platform\Utility\Favorite\Service\Setup;
use SPHERE\System\Database\Binding\AbstractService;
use SPHERE\System\Database\Fitting\Element;

class Service extends AbstractService
{
    /**
     * @param bool $doSimulation
     * @param bool $withData
     *
     * @return string
     */
    public function setupService($doSimulation, $withData)
    {
        $Protocol = (new Setup($this->getStructure()))->setupDatabaseSchema($doSimulation);
        if (!$doSimulation && $withData) {
            (new Data($this->getBinding()))->setupDatabaseContent();
        }
        return $Protocol;
    }

    /**
     * @param int $Id
     * @param TblAccount|null $TblAccount
     * @return null|TblFavorite|Element
     */
    public function getFavoriteById($Id, TblAccount $TblAccount = null)
    {
        return (new Data($this->getBinding()))->getFavoriteById($Id, $TblAccount);
    }

    /**
     * @param TblAccount|null $TblAccount
     * @return null|Element[]|TblFavorite[]
     */
    public function getFavoriteAll(TblAccount $TblAccount = null)
    {
        return (new Data($this->getBinding()))->getFavoriteAll($TblAccount);
    }

    /**
     * @param string $Route
     * @param TblAccount|null $TblAccount
     * @return null|TblFavorite|Element
     */
    public function getFavoriteByRoute($Route, TblAccount $TblAccount = null)
    {
        return (new Data($this->getBinding()))->getFavoriteByRoute($Route, $TblAccount);
    }

    /**
     * Insert TblFavorite, If not exists
     *
     * @param string $Route
     * @param string $Title
     * @param string $Description
     * @param TblAccount|null $TblAccount
     * @return null|TblFavorite|Element
     */
    public function createFavorite($Route, $Title, $Description, TblAccount $TblAccount = null)
    {
        if(!($Entity = $this->getFavoriteByRoute($Route, $TblAccount))) {
            if(($Entity = (new Data($this->getBinding()))->insertFavorite($Route, $Title, $Description, $TblAccount))) {
                return $Entity;
            }
            return null;
        }
        return $Entity;
    }

    /**
     * Delete TblFavorite, If exists
     *
     * @param string $Route
     * @param TblAccount|null $TblAccount
     * @return null|bool
     */
    public function destroyFavorite($Route, TblAccount $TblAccount = null)
    {
        if(($Entity = $this->getFavoriteByRoute($Route, $TblAccount))) {
            return (new Data($this->getBinding()))->deleteFavorite( $Entity );
        }
        return null;
    }
}