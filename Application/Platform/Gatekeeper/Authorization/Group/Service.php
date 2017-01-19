<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Group;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Consumer;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Service\Entity\TblConsumer;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Data;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Entity\TblGroup;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Service\Setup;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Template\Notify;
use SPHERE\Common\Frontend\Form\IFormInterface;
use SPHERE\System\Database\Binding\AbstractService;

/**
 * Class Service
 *
 * @package SPHERE\Application\Platform\Gatekeeper\Authorization\Group
 */
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
     * @param TblConsumer $tblConsumer
     * @return bool|TblGroup[]
     */
    public function getGroupAll(TblConsumer $tblConsumer = null)
    {

        return (new Data($this->getBinding()))->getGroupAll($tblConsumer);
    }

    /**
     * @param int $Id
     *
     * @return bool|TblGroup
     */
    public function getGroupById($Id)
    {

        return (new Data($this->getBinding()))->getGroupById($Id);
    }

    /**
     * @param string $Name
     *
     * @return bool|TblGroup
     */
    public function getGroupByName($Name)
    {
        return (new Data($this->getBinding()))->getGroupByName($Name);
    }

    /**
     * @param TblGroup $tblGroup
     *
     * @return bool
     */
    public function destroyGroup(TblGroup $tblGroup)
    {

        return (new Data($this->getBinding()))->destroyGroup($tblGroup);
    }

    /**
     * @param IFormInterface $Form
     * @param array $Group
     * @param Pipeline|null $pipelineSuccess
     * @return IFormInterface|string
     */
    public function createGroup(
        IFormInterface $Form,
        $Group,
        Pipeline $pipelineSuccess = null
    )
    {

        /**
         * Service
         */
        if ($Group === null) {
            return $Form;
        }

        $Error = false;

        if (!isset($Group['Name']) || empty($Group['Name'])) {
            $Form->setError('Group[Name]', 'Bitte geben Sie einen Namen ein');
            $Error = true;
        } else {
            if (($this->getGroupByName($Group['Name']))) {
                $Form->setError('Group[Name]', 'Der angegebene Name wird bereits verwendet');
                $Error = true;
            }
        }
        if (!isset($Group['Description'])) {
            $Form->setError('Group[Description]', 'Bitte geben Sie eine Beschreibung ein');
            $Error = true;
        }

        if ($Error) {
            return $Form . new Notify(
                    'Benutzergruppe konnte nicht angelegt werden',
                    'Bitte füllen Sie die benötigten Felder korrekt aus',
                    Notify::TYPE_WARNING,
                    5000
                );
        } else {

            $tblConsumer = Consumer::useService()->getConsumerBySession();
            if ($tblConsumer) {
                if ($this->insertGroup($Group['Name'], $Group['Description'], $tblConsumer)) {
                    return $Form. new Notify(
                            'Benutzergruppe ' . $Group['Name'],
                            'Erfolgreich angelegt',
                            Notify::TYPE_SUCCESS
                        ).($pipelineSuccess ? $pipelineSuccess : '');
                }
            }
            return $Form . new Notify(
                    'Benutzergruppe ' . $Group['Name'],
                    'Konnte nicht angelegt werden',
                    Notify::TYPE_DANGER,
                    5000
                );
        }
    }

    /**
     * @param IFormInterface $Form
     * @param TblGroup $tblGroup
     * @param array $Group
     * @param Pipeline|null $pipelineSuccess
     * @return IFormInterface|string
     */
    public function editGroup(
        IFormInterface $Form,
        TblGroup $tblGroup,
        $Group,
        Pipeline $pipelineSuccess = null
    )
    {

        /**
         * Service
         */
        if ($Group === null) {
            return $Form;
        }

        $Error = false;

        if (!isset($Group['Name']) || empty($Group['Name'])) {
            $Form->setError('Group[Name]', 'Bitte geben Sie einen Namen ein');
            $Error = true;
        } else {
            if (
                ($tblGroupExists = $this->getGroupByName($Group['Name']))
                && $tblGroupExists->getId() != $tblGroup->getId()
            ) {
                $Form->setError('Group[Name]', 'Der angegebene Name wird bereits verwendet');
                $Error = true;
            }
        }
        if (!isset($Group['Description'])) {
            $Form->setError('Group[Description]', 'Bitte geben Sie eine Beschreibung ein');
            $Error = true;
        }

        if ($Error) {
            return $Form . new Notify(
                    'Benutzergruppe konnte nicht geändert werden',
                    'Bitte füllen Sie die benötigten Felder korrekt aus',
                    Notify::TYPE_WARNING,
                    5000
                );
        } else {
            $tblConsumer = Consumer::useService()->getConsumerBySession();
            if ($tblConsumer) {
                if ($this->changeGroup( $tblGroup, $Group['Name'], $Group['Description'], $tblConsumer)) {
                    return new Notify(
                            'Benutzergruppe ' . $Group['Name'],
                            'Erfolgreich geändert',
                            Notify::TYPE_SUCCESS
                        ).($pipelineSuccess ? $pipelineSuccess : '');
                }
            }
            return $Form . new Notify(
                    'Benutzergruppe ' . $Group['Name'],
                    'Konnte nicht geändert werden',
                    Notify::TYPE_DANGER,
                    5000
                );
        }
    }

    /**
     * @param string $Name
     * @param string $Description
     * @param null|TblConsumer $tblConsumer
     *
     * @return TblGroup
     */
    public function insertGroup($Name, $Description, TblConsumer $tblConsumer = null)
    {

        return (new Data($this->getBinding()))->createGroup($Name, $Description, $tblConsumer);
    }

    /**
     * @param TblGroup $tblGroup
     * @param string $Name
     * @param string $Description
     * @param null|TblConsumer $tblConsumer
     *
     * @return false|TblGroup
     */
    public function changeGroup( TblGroup $tblGroup, $Name, $Description, TblConsumer $tblConsumer = null)
    {

        return (new Data($this->getBinding()))->changeGroup( $tblGroup, $Name, $Description, $tblConsumer);
    }
}