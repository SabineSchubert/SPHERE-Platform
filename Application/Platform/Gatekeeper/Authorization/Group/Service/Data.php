<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Consumer;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Service\Entity\TblConsumer;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Entity\TblGroup;
use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\System\Database\Binding\AbstractData;

/**
 * Class Data
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service
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
     * @param string $Name
     * @param string $Description
     * @param null|TblConsumer $tblConsumer
     *
     * @return TblGroup
     */
    public function createGroup($Name, $Description, TblConsumer $tblConsumer = null)
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity('TblGroup')->findOneBy(array(TblGroup::ATTR_NAME => $Name));
        if (null === $Entity) {
            $Entity = new TblGroup();
            $Entity->setName($Name);
            $Entity->setDescription($Description);
            $Entity->setServiceTblConsumer($tblConsumer);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createInsertEntry($this->getConnection()->getDatabase(), $Entity);
        }
        return $Entity;
    }

    /**
     * @param TblGroup $tblGroup
     * @param string $Name
     * @param string $Description
     * @param TblConsumer|null $tblConsumer
     *
     * @return false|TblGroup
     */
    public function changeGroup(TblGroup $tblGroup, $Name, $Description, TblConsumer $tblConsumer = null)
    {

        if (null === $tblConsumer) {
            $tblConsumer = Consumer::useService()->getConsumerBySession();
        }
        $Manager = $this->getEntityManager();

        /** @var TblGroup $Entity */
        $Entity = $Manager->getEntityById('TblGroup', $tblGroup->getId());
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setName($Name);
            $Entity->setDescription($Description);
            $Entity->setServiceTblConsumer($tblConsumer);
            $Manager->saveEntity($Entity);
            Protocol::useService()->createUpdateEntry($this->getConnection()->getDatabase(), $Protocol, $Entity);
            return $Entity;
        }
        return false;
    }

    /**
     * @param TblGroup $tblGroup
     *
     * @return bool
     */
    public function destroyGroup(TblGroup $tblGroup)
    {

        $Manager = $this->getEntityManager();
        /** @var TblGroup $Entity */
        $Entity = $Manager->getEntityById('TblGroup', $tblGroup->getId());
        if (null !== $Entity) {
            Protocol::useService()->createDeleteEntry($this->getConnection()->getDatabase(), $Entity);
            $Manager->killEntity($Entity);
            return true;
        }
        return false;
    }

    /**
     * @param TblConsumer $tblConsumer
     * @return bool|TblGroup[]
     */
    public function getGroupAll(TblConsumer $tblConsumer = null)
    {
        if ($tblConsumer) {
            return $this->getCachedEntityListBy(__METHOD__, $this->getEntityManager(), 'TblGroup', array(
                TblGroup::SERVICE_TBL_CONSUMER => $tblConsumer->getId()
            ));
        } else {
            return $this->getCachedEntityList(__METHOD__, $this->getEntityManager(), 'TblGroup');
        }
    }

    /**
     * @param int $Id
     *
     * @return bool|TblGroup
     */
    public function getGroupById($Id)
    {

        return $this->getCachedEntityById(__METHOD__, $this->getEntityManager(), 'TblGroup', $Id);
    }

    /**
     * @param string $Name
     *
     * @return bool|TblGroup
     */
    public function getGroupByName($Name)
    {

        return $this->getCachedEntityBy(__METHOD__, $this->getEntityManager(), 'TblGroup', array(
            TblGroup::ATTR_NAME => $Name
        ));
    }

}