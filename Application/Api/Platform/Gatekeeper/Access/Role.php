<?php

namespace SPHERE\Application\Api\Platform\Gatekeeper\Access;

use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access as AccessApp;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Common\Frontend\Ajax\Emitter\ClientEmitter;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Ajax\Template\CloseModal;
use SPHERE\Common\Frontend\Form\IFormInterface;
use SPHERE\Common\Frontend\Form\Repository\Button\Close;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\CogWheels;
use SPHERE\Common\Frontend\Icon\Repository\Disable;
use SPHERE\Common\Frontend\Icon\Repository\Edit;
use SPHERE\Common\Frontend\Icon\Repository\Enable;
use SPHERE\Common\Frontend\Icon\Repository\Globe;
use SPHERE\Common\Frontend\Icon\Repository\Lock;
use SPHERE\Common\Frontend\Icon\Repository\Remove;
use SPHERE\Common\Frontend\Icon\Repository\Warning as WarningIcon;
use SPHERE\Common\Frontend\Layout\Repository\Container;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\ProgressBar;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Danger;
use SPHERE\Common\Frontend\Link\Repository\Primary;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Success;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Bold;
use SPHERE\Common\Frontend\Text\Repository\Danger as DangerText;
use SPHERE\Common\Frontend\Text\Repository\Muted as MutedText;

class Role extends Level
{
    /**
     * @return Pipeline
     */
    public static function pipelineRoleStage()
    {
        $Emitter = new ServerEmitter(Access::receiverStage(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleStage'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Layout
     */
    public function callRoleStage()
    {
        return new Layout(array(
            new LayoutGroup(array(
                new LayoutRow(array(
                    new LayoutColumn(array(
                        Access::receiverRoleTable(),
                        Access::pipelineRoleTable(),
                        Access::receiverRoleInsert(),
                        Access::receiverRoleEdit() .
                        Access::receiverRoleSetup(),
                        Access::receiverRoleDelete(),
                        (new Standard('Rolle anlegen', Access::getEndpoint()))
                            ->ajaxPipelineOnClick(Access::pipelineRoleFormInsert()),
                    )),
                )),
            ), new Title('Rollen')),
        ));
    }

    /**
     * @return BlockReceiver
     */
    public static function receiverRoleTable()
    {

        $Receiver = new BlockReceiver();
        $Receiver->setIdentifier(__METHOD__);
        $Receiver->initContent(
            new Panel(
                'Rollen werden geladen...',
                array(
                    new ProgressBar(0, 100, 0, 6),
                    new MutedText('Daten werden vom Server abgerufen')
                ),
                Panel::PANEL_TYPE_DEFAULT
            )
        );
        return $Receiver;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleTable()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleTable(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleTable'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleSetupEnable()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleSetup(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleFormSetup'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);
        $Pipeline->setLoadingMessage('Level wird aktiviert');

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleSetupDisable()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleSetup(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleFormSetup'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);
        $Pipeline->setLoadingMessage('Level wird deaktiviert');

        return $Pipeline;
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverRoleInsert()
    {

        $Receiver = new ModalReceiver('Neue Rolle anlegen', new Close());
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverRoleEdit()
    {

        $Receiver = new ModalReceiver('Rolle bearbeiten', new Close());
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverRoleSetup()
    {

        $Receiver = new ModalReceiver('Zugriffslevel zuweisen', new Close());
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }

    /**
     * @return ModalReceiver
     */
    public static function receiverRoleDelete()
    {

        $Receiver = new ModalReceiver('Rolle löschen', new Close());
        $Receiver->setIdentifier(__METHOD__);
        return $Receiver;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleFormInsert()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleInsert(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleFormInsert'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Warning|string
     */
    public function callRoleTable()
    {
        if (($TblRoleAll = AccessApp::useService()->getRoleAll())) {
            $TableContent = array();
            array_walk($TblRoleAll, function (TblRole $TblRole) use (&$TableContent) {
                $Content = $TblRole->__toArray();
                $Content['IsInternal'] = ($Content['IsInternal'] ? new DangerText(new Lock()) : new MutedText(new Globe()));

                $Content['Count'] = $TblRole->countTblLevelAll();

                $Content['Option'] = new PullRight(implode(array(
                    (new Standard('', Access::getEndpoint(), new Edit(), array('Id' => $TblRole->getId())))
                        ->ajaxPipelineOnClick(Access::pipelineRoleFormEdit()),
                    (new Standard('', Access::getEndpoint(), new CogWheels(), array('Id' => $TblRole->getId())))
                        ->ajaxPipelineOnClick(Access::pipelineRoleFormSetup()),
                    (new Danger('', Access::getEndpoint(), new Remove(), array('Id' => $TblRole->getId())))
                        ->ajaxPipelineOnClick(Access::pipelineRoleFormDelete())
                )));
                $TableContent[] = $Content;
            });
            return (new Table($TableContent, null, array(
                'IsInternal' => '',
                'Name' => 'Rolle',
                'Count' => 'Zugriffslevel',
                'Option' => ''
            ), array(
                'order' => array(1, 'asc'),
                'columnDefs' => array(
                    array('width' => '1%', 'targets' => array(0,-2)),
                    array('width' => '15%', 'targets' => array(-1)),
                    array('searchable' => false, 'orderable' => false, 'targets' => array(0, -1))
                )
            )))->setHash(crc32(__METHOD__));
        } else {
            return new Warning('Keine Rollen vorhanden');
        }
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleFormEdit()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleEdit(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleFormEdit'
        ));
        $Pipeline = new Pipeline();

        $Client = new ClientEmitter(Access::receiverRoleEdit(),
            new Panel(
                'Rolle wird geladen...',
                array(
                    new ProgressBar(0, 100, 0, 6),
                    new MutedText('Daten werden vom Server abgerufen')
                ),
                Panel::PANEL_TYPE_DEFAULT
            ));
        $Pipeline->appendEmitter($Client);

        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleFormSetup()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleSetup(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleFormSetup'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleFormDelete()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleDelete(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleFormDelete'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @param null|int $Id
     * @return IFormInterface|string
     */
    public function callRoleFormSetup($Id = null)
    {
        if (($TblRole = AccessApp::useService()->getRoleById($Id))) {

            // Current Level
            $CurrentLevelList = array();
            $TblLevelListCurrent = AccessApp::useService()->getLevelAllByRole($TblRole);
            if ($TblLevelListCurrent) {
                foreach ($TblLevelListCurrent as $TblLevel) {
                    $Content = $TblLevel->__toArray();
                    $Content['Option'] =
                        (new Danger('', Access::getEndpoint(), new Disable(), array(
                            'Id' => $TblRole->getId(),
                            'TblLevel' => $TblLevel->getId()
                        )))->ajaxPipelineOnClick(Access::pipelineRoleSetupDisable());

                    $CurrentLevelList[] = $Content;
                }
            } else {
                $TblLevelListCurrent = array();
            }

            // Available Level
            $AvailableLevelList = array();
            $TblLevelListAvailable = AccessApp::useService()->getLevelAll();
            if ($TblLevelListAvailable) {
                $TblLevelListAvailable = array_diff($TblLevelListAvailable, $TblLevelListCurrent);
                foreach ($TblLevelListAvailable as $TblLevel) {
                    $Content = $TblLevel->__toArray();
                    $Content['Option'] =
                        (new \SPHERE\Common\Frontend\Link\Repository\Success('', Access::getEndpoint(), new Enable(), array(
                            'Id' => $TblRole->getId(),
                            'TblLevel' => $TblLevel->getId()
                        )))->ajaxPipelineOnClick(Access::pipelineRoleSetupEnable());
                    $AvailableLevelList[] = $Content;
                }
            } else {
                $TblLevelListAvailable = array();
            }

            return new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(
                            new Table($CurrentLevelList, null, array('Name' => 'Zugriffslevel', 'Option' => ''),
                                array(
                                    'order' => array(0, 'asc'),
                                    'columnDefs' => array(
                                        array('width' => '1%', 'targets' => array(-1)),
                                        array('width' => '15%', 'targets' => array(0)),
                                        array('searchable' => false, 'orderable' => false, 'targets' => array(0, -1))
                                    )
                                )
                            ), 6),
                        new LayoutColumn(
                            new Table($AvailableLevelList, null, array('Name' => 'Zugriffslevel', 'Option' => ''),
                                array(
                                    'order' => array(0, 'asc'),
                                    'columnDefs' => array(
                                        array('width' => '1%', 'targets' => array(-1)),
                                        array('width' => '15%', 'targets' => array(0)),
                                        array('searchable' => false, 'orderable' => false, 'targets' => array(0, -1))
                                    )
                                )
                            ), 6),
                    ))
                )
            );
        } else {
            return new Warning('Die Rolle konnte nicht gefunden werden.', new WarningIcon())
                . new Container(
                    (new Standard('Rollen neu laden', Access::getEndpoint()))
                        ->ajaxPipelineOnClick(
                            Access::pipelineRoleTable()
                                ->prependEmitter((new CloseModal(Access::receiverRoleSetup()))->getEmitter())
                        )
                );
        }
    }

    /**
     * @param null|int $Id
     * @return IFormInterface|string
     */
    public function callRoleActionDelete($Id = null)
    {
        $Error = false;
        $Form = $this->callRoleFormDelete($Id);

        if ($Error) {
            // on Error
            return $Form;
        }
        return $Form;
    }

    /**
     * @param null|int $Id
     * @return IFormInterface|string
     */
    public function callRoleFormDelete($Id = null)
    {
        if (($TblRole = AccessApp::useService()->getRoleById($Id))) {

            return (new Form(
                new FormGroup(
                    new FormRow(array(
                        new FormColumn(array(
                            (new CheckBox('Confirm',
                                'Ja, ich möchte die Rolle ' . new Bold($TblRole->getName()) . ' löschen', 1))
                        )),
                    ))
                )
                , new \SPHERE\Common\Frontend\Form\Repository\Button\Danger('Rolle löschen', new WarningIcon())
                , '', array(
                'Id' => $Id
            )))->ajaxPipelineOnSubmit(Access::pipelineRoleActionDelete());
        } else {
            return new Warning('Die Rolle konnte nicht gefunden werden.', new WarningIcon())
                . new Container(
                    (new Standard('Rollen neu laden', Access::getEndpoint()))
                        ->ajaxPipelineOnClick(
                            Access::pipelineRoleTable()
                                ->prependEmitter((new CloseModal(Access::receiverRoleEdit()))->getEmitter())
                        )
                );
        }
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleActionDelete()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleDelete(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleActionDelete'
        ));
        $Pipeline = new Pipeline();
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @param null|string $Name
     * @param null|int $IsInternal (0|1)
     * @return IFormInterface|string
     */
    public function callRoleActionInsert($Name = null, $IsInternal = null)
    {
        $Error = false;
        $Form = $this->callRoleFormInsert();
        if (!$Name) {
            $Error = true;
            $Form->setError('Name', 'Bitte geben Sie einen Namen an');
        } else {
            if (AccessApp::useService()->getRoleByName($Name)) {
                $Error = true;
                $Form->setError('Name', 'Dieser Name wird bereits verwendet');
            }
        }
        if ($Error) {
            // on Error
            return $Form;
        } else {
            // on Success
            if (AccessApp::useService()->createRole($Name, ($IsInternal ? true : false))) {
                // on Success
                return new Success('Rolle erfolgreich angelegt') . (Access::pipelineRoleTable())
                        ->appendEmitter((new CloseModal(Access::receiverRoleInsert()))->getEmitter());
            } else {
                // on Error
                return $Form->setError('Name', 'Die Rolle konnte nicht angelegt werden');
            }
        }
    }

    /**
     * @return IFormInterface
     */
    public function callRoleFormInsert()
    {
        return (new Form(
            new FormGroup(
                new FormRow(array(
                    new FormColumn(array(
                        (new TextField('Name', '', 'Name der Rolle'))->setRequired()->setAutoFocus(),
                        (new CheckBox('IsInternal', 'Interne Rolle', 1)),
                        (new Primary('Rolle anlegen', Access::getEndpoint()))
                            ->ajaxPipelineOnClick(Access::pipelineRoleActionInsert())
                    )),
                ))
            )
        ))->disableSubmitAction();
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleActionInsert()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleInsert(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleActionInsert'
        ));
        $Pipeline = new Pipeline();

        $Client = new ClientEmitter(Access::receiverRoleEdit(),
            new Panel(
                'Rolle wird angelegt...',
                array(
                    new ProgressBar(0, 100, 0, 6),
                    new MutedText('Daten werden vom Server abgerufen')
                ),
                Panel::PANEL_TYPE_DEFAULT
            ));
        $Pipeline->appendEmitter($Client);

        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    /**
     * @param null|int $Id
     * @param null|string $Name
     * @param null|int $IsInternal (0|1)
     * @return IFormInterface|string
     */
    public function callRoleActionEdit($Id = null, $Name = null, $IsInternal = null)
    {
        $Error = false;
        $Form = $this->callRoleFormEdit($Id, $Name, $IsInternal);

        $TblRole = AccessApp::useService()->getRoleById($Id);

        if ($Form instanceof Form) {
            if (!$Name) {
                $Error = true;
                $Form->setError('Name', 'Bitte geben Sie einen Namen an');
            } else {
                $Entity = AccessApp::useService()->getRoleByName($Name);
                if ($Entity && $TblRole && $Entity->getId() != $TblRole->getId()) {
                    $Error = true;
                    $Form->setError('Name', 'Dieser Name wird bereits verwendet');
                }
            }
            if ($Error) {
                // on Error
                return $Form;
            } else {
                // on Success
                if (AccessApp::useService()->updateRole($TblRole, $Name, ($IsInternal ? true : false))) {
                    // on Success
                    return new Success('Rolle erfolgreich geändert') . (Access::pipelineRoleTable())
                            ->appendEmitter((new CloseModal(Access::receiverRoleEdit()))->getEmitter());
                } else {
                    // on Error
                    return $Form->setError('Name', 'Die Rolle konnte nicht geändert werden');
                }
            }
        } else {
            return $Form;
        }
    }

    /**
     * @param null|int $Id
     * @return IFormInterface|string
     */
    public function callRoleFormEdit($Id = null, $Name = null, $IsInternal = null)
    {
        if (($TblRole = AccessApp::useService()->getRoleById($Id))) {

            if ($Name === null) {
                $Global = $this->getGlobal();
                $Global->POST['Name'] = $TblRole->getName();
                $Global->POST['IsInternal'] = ($TblRole->isInternal() ? 1 : 0);
                $Global->savePost();
            }

            return (new Form(
                new FormGroup(
                    new FormRow(array(
                        new FormColumn(array(
                            (new TextField('Name', '', 'Name der Rolle'))->setRequired()->setAutoFocus(),
                            (new CheckBox('IsInternal', 'Interne Rolle', 1)),
                            (new Primary('Rolle ändern', Access::getEndpoint(), null, array('Id' => $Id)))
                                ->ajaxPipelineOnClick(Access::pipelineRoleActionEdit())
                        )),
                    ))
                )
            ))->disableSubmitAction();
        } else {
            return new Warning('Die Rolle konnte nicht gefunden werden.', new WarningIcon())
                . new Container(
                    (new Standard('Rollen neu laden', Access::getEndpoint()))
                        ->ajaxPipelineOnClick(
                            Access::pipelineRoleTable()
                                ->appendEmitter((new CloseModal(Access::receiverRoleEdit()))->getEmitter())
                        )
                );
        }
    }

    /**
     * @return Pipeline
     */
    public static function pipelineRoleActionEdit()
    {
        $Emitter = new ServerEmitter(Access::receiverRoleEdit(), Access::getEndpoint());
        $Emitter->setGetPayload(array(
            Access::API_TARGET => 'callRoleActionEdit'
        ));
        $Pipeline = new Pipeline();

        $Client = new ClientEmitter(Access::receiverRoleEdit(),
            new Panel(
                'Rolle wird geändert...',
                array(
                    new ProgressBar(0, 100, 0, 6),
                    new MutedText('Daten werden vom Server abgerufen')
                ),
                Panel::PANEL_TYPE_DEFAULT
            ));
        $Pipeline->appendEmitter($Client);

        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }
}