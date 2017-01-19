<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer;

use SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Service\Data;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Service\Entity\TblConsumer;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Consumer\Service\Setup;
use SPHERE\Common\Frontend\Form\IFormInterface;
use SPHERE\Common\Window\Redirect;
use SPHERE\System\Cache\Handler\MemoryHandler;
use SPHERE\System\Database\Binding\AbstractService;

/**
 * Class Service
 *
 * @package SPHERE\Application\System\Gatekeeper\Authorization\Consumer
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
     * @param integer $Id
     *
     * @return null|TblConsumer
     */
    public function getConsumerById($Id)
    {

        return (new Data($this->getBinding()))->getConsumerById($Id);
    }

    /**
     * @param string $Name
     *
     * @return bool|TblConsumer
     */
    public function getConsumerByName($Name)
    {

        return (new Data($this->getBinding()))->getConsumerByName($Name);
    }

    /**
     * @param null|string $Session
     *
     * @return bool|TblConsumer
     */
    public function getConsumerBySession($Session = null)
    {

        $Cache = $this->getCache(new MemoryHandler());
        if (null === ( $tblConsumer = $Cache->getValue($Session, __METHOD__) )) {
            $tblConsumer = (new Data($this->getBinding()))->getConsumerBySession($Session);
            if ($tblConsumer) {
                $Cache->setValue($Session, $tblConsumer, 0, __METHOD__);
            } else {
                $tblConsumer = (new Data($this->getBinding()))->getConsumerById(1);
                $Cache->setValue($Session, $tblConsumer, 0, __METHOD__);
            }
            return $tblConsumer;
        } else {
            return $tblConsumer;
        }
    }

    /**
     * @return bool|TblConsumer[]
     */
    public function getConsumerAll()
    {

        return (new Data($this->getBinding()))->getConsumerAll();
    }

    /**
     * @param IFormInterface $Form
     * @param string         $ConsumerAcronym
     * @param string         $ConsumerName
     *
     * @return IFormInterface|Redirect
     */
    public function createConsumer(
        IFormInterface $Form,
        $ConsumerAcronym,
        $ConsumerName
    ) {

        if (null === $ConsumerName
            && null === $ConsumerAcronym
        ) {
            return $Form;
        }

        $Error = false;
        if (null !== $ConsumerAcronym && empty( $ConsumerAcronym )) {
            $Form->setError('ConsumerAcronym', 'Bitte geben Sie ein Mandantenkürzel an');
            $Error = true;
        }
        if ($this->getConsumerByAcronym($ConsumerAcronym)) {
            $Form->setError('ConsumerAcronym', 'Das Mandantenkürzel muss einzigartig sein');
            $Error = true;
        }
        if (null !== $ConsumerName && empty( $ConsumerName )) {
            $Form->setError('ConsumerName', 'Bitte geben Sie einen gültigen Mandantenname ein');
            $Error = true;
        }

        if ($Error) {
            return $Form;
        } else {
            (new Data($this->getBinding()))->createConsumer($ConsumerAcronym, $ConsumerName);
            return new Redirect('/Platform/Gatekeeper/Authorization/Consumer', 0);
        }
    }

    /**
     * @param string $Acronym
     *
     * @return null|TblConsumer
     */
    public function getConsumerByAcronym($Acronym)
    {

        return (new Data($this->getBinding()))->getConsumerByAcronym($Acronym);
    }
}
