<?php
namespace SPHERE\Application\Platform\Utility\Favorite;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Service\Entity\TblAccount;
use SPHERE\Application\Platform\Utility\Favorite\Service\Data;
use SPHERE\Application\Platform\Utility\Favorite\Service\Entity\TblFavoriteNavigation;
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
     * @return null|TblFavoriteNavigation|Element
     */
    public function getFavoriteById($Id, TblAccount $TblAccount = null)
    {
        return (new Data($this->getBinding()))->getFavoriteById($Id, $TblAccount);
    }

    /**
     * @param string $Route
     * @param TblAccount|null $TblAccount
     * @return null|TblFavoriteNavigation|Element
     */
    public function getFavoriteByRoute($Route, TblAccount $TblAccount = null)
    {
        return (new Data($this->getBinding()))->getFavoriteByRoute($Route, $TblAccount);
    }

    /**
     * Create TblFavoriteNavigation if not exists
     *
     * @param string $Route
     * @param string $Name
     * @param TblAccount|null $TblAccount
     * @return null|TblFavoriteNavigation|Element
     */
    public function createFavorite($Route, $Name, TblAccount $TblAccount = null)
    {
        if(!($Entity = $this->getFavoriteByRoute($Route, $TblAccount))) {
            if(($Entity = (new Data($this->getBinding()))->insertFavorite($Route, $Name, $TblAccount))) {
                return $Entity;
            }
            return null;
        }
        return $Entity;
    }
}