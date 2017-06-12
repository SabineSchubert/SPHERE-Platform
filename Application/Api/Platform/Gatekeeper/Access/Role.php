<?php

namespace SPHERE\Application\Api\Platform\Gatekeeper\Access;

use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access as AccessApp;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Common\Frontend\Ajax\Template\CloseModal;
use SPHERE\Common\Frontend\Form\IFormInterface;
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

class Role extends RolePipeline
{

    /**
     * @return Layout
     */
    public function loadRoleStage()
    {
        return new Layout(array(
            new LayoutGroup(array(
                new LayoutRow(array(
                    new LayoutColumn(array(
                        Access::receiverRoleTable(),
                        Access::pipelineRoleTable(true),
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


    public function actionRoleSetupEnable($Id = null, $TblLevel = null)
    {
        if (
            ($TblRole = AccessApp::useService()->getRoleById( $Id ))
            && ($TblLevel = AccessApp::useService()->getLevelById( $TblLevel ))
        ) {
            AccessApp::useService()->addRoleLevel( $TblRole, $TblLevel );
        }
        return Access::pipelineRoleFormSetup(false, $Id);
    }

    public function actionRoleSetupDisable($Id = null, $TblLevel = null)
    {
        if (
            ($TblRole = AccessApp::useService()->getRoleById( $Id ))
            && ($TblLevel = AccessApp::useService()->getLevelById( $TblLevel ))
        ) {
            AccessApp::useService()->removeRoleLevel( $TblRole, $TblLevel );
        }
        return Access::pipelineRoleFormSetup(false, $Id);
    }

    /**
     * @return Warning|string
     */
    public function loadRoleTable()
    {
        if (($TblRoleAll = AccessApp::useService()->getRoleAll())) {
            $TableContent = array();
            array_walk($TblRoleAll, function (TblRole $TblRole) use (&$TableContent) {
                $Content = $TblRole->__toArray();
                $Content['IsInternal'] = ($Content['IsInternal'] ? new DangerText(new Lock()) : new MutedText(new Globe()));

                $Count = $TblRole->countTblLevelAll();
                $Content['Count'] = $Count ? $Count : '';

                $Content['Option'] = new PullRight(implode(array(
                    (new Standard('', Access::getEndpoint(), new Edit(), array('Id' => $TblRole->getId())))
                        ->ajaxPipelineOnClick(Access::pipelineRoleFormEdit()),
                    (new Standard('', Access::getEndpoint(), new CogWheels(), array('Id' => $TblRole->getId())))
                        ->ajaxPipelineOnClick(Access::pipelineRoleFormSetup(true)),
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
                    array('width' => '1%', 'targets' => array(0, -2)),
                    array('width' => '15%', 'targets' => array(-1)),
                    array('searchable' => false, 'orderable' => false, 'targets' => array(0, -1))
                )
            ), true))->setHash(crc32(__METHOD__));
        } else {
            return new Warning('Keine Rollen vorhanden');
        }
    }

    /**
     * @param null|int $Id
     * @return IFormInterface|string
     */
    public function formRoleSetup($Id = null)
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
                        )))->ajaxPipelineOnClick(array(
                            Access::pipelineRoleSetupDisable()
                        ));

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
                        (new \SPHERE\Common\Frontend\Link\Repository\Success('', Access::getEndpoint(), new Enable(),
                            array(
                                'Id' => $TblRole->getId(),
                                'TblLevel' => $TblLevel->getId()
                            )))->ajaxPipelineOnClick(array(
                            Access::pipelineRoleSetupEnable()
                        ));
                    $AvailableLevelList[] = $Content;
                }
            } else {
                $TblLevelListAvailable = array();
            }

            return new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(
                            (new Table($CurrentLevelList, null, array('Name' => 'Zugriffslevel', 'Option' => ''),
                                array(
                                    'fixedHeader' => false,
                                    'order' => array(0, 'asc'),
                                    'columnDefs' => array(
                                        array('width' => '1%', 'targets' => array(-1)),
                                        array('searchable' => false, 'orderable' => false, 'targets' => array(-1))
                                    )
                                )
                            ))->setHash(crc32(__METHOD__.__LINE__)), 6),
                        new LayoutColumn(
                            (new Table($AvailableLevelList, null, array('Name' => 'Zugriffslevel', 'Option' => ''),
                                array(
                                    'fixedHeader' => false,
                                    'order' => array(0, 'asc'),
                                    'columnDefs' => array(
                                        array('width' => '1%', 'targets' => array(-1)),
                                        array('searchable' => false, 'orderable' => false, 'targets' => array(-1))
                                    )
                                )
                            ))->setHash(crc32(__METHOD__.__LINE__)), 6),
                    ))
                )
            );
        } else {
            return self::isMissingWarning(
                Access::receiverRoleSetup(),
                Access::pipelineRoleTable(),
                'Die Rolle konnte nicht gefunden werden.',
                'Rollen neu laden'
            );
        }
    }

    /**
     * @param null|int $Id
     * @return IFormInterface|string
     */
    public function actionRoleDelete($Id = null)
    {
        $Error = false;
        $Form = $this->formRoleDelete($Id);

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
    public function formRoleDelete($Id = null)
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
            return self::isMissingWarning(
                Access::receiverRoleDelete(),
                Access::pipelineRoleTable(),
                'Die Rolle konnte nicht gefunden werden.',
                'Rollen neu laden'
            );
        }
    }

    /**
     * @param null|string $Name
     * @param null|int $IsInternal (0|1)
     * @return IFormInterface|string
     */
    public function actionRoleInsert($Name = null, $IsInternal = null)
    {
        $Error = false;
        $Form = $this->formRoleInsert();
        if (!$Name) {
            $Error = true;
            $Form->setError('Name', 'Bitte geben Sie einen Namen an');
        } else {
            if (AccessApp::useService()->getRoleByName($Name)) {
                $Error = true;
                $Form->setError('Name', 'Dieser Name wird bereits verwendet');
            }
        }
        if (!$Error) {
            if (AccessApp::useService()->createRole($Name, ($IsInternal ? true : false))) {
                // on Success
                return new Success('Rolle erfolgreich angelegt') . (Access::pipelineRoleTable())
                        ->appendEmitter((new CloseModal(Access::receiverRoleInsert()))->getEmitter());
            } else {
                // on Error
                return $Form->setError('Name', 'Die Rolle konnte nicht angelegt werden');
            }
        }
        return $Form;
    }

    /**
     * @return IFormInterface
     */
    public function formRoleInsert()
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
     * @param null|int $Id
     * @param null|string $Name
     * @param null|int $IsInternal (0|1)
     * @return IFormInterface|string
     */
    public function actionRoleEdit($Id = null, $Name = null, $IsInternal = null)
    {
        $Error = false;
        $Form = $this->formRoleEdit($Id, $Name, $IsInternal);

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
            if (!$Error) {
                if (AccessApp::useService()->updateRole($TblRole, $Name, ($IsInternal ? true : false))) {
                    // on Success
                    return new Success('Rolle erfolgreich geändert') . (Access::pipelineRoleTable())
                            ->appendEmitter((new CloseModal(Access::receiverRoleEdit()))->getEmitter());
                } else {
                    // on Error
                    return $Form->setError('Name', 'Die Rolle konnte nicht geändert werden');
                }
            }
        }
        return $Form;
    }

    /**
     * @param null|int $Id
     * @param null|string $Name
     * @param null|int $IsInternal (0|1)
     * @return IFormInterface|string
     */
    public function formRoleEdit($Id = null, $Name = null, $IsInternal = null)
    {
        if (($TblRole = AccessApp::useService()->getRoleById($Id))) {

            $Global = $this->getGlobal();

            if ($Name === null) {
                $Global->POST['Name'] = $TblRole->getName();
                $Global->savePost();
            }
            if ($IsInternal === null) {
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
            return self::isMissingWarning(
                Access::receiverRoleEdit(),
                Access::pipelineRoleTable(),
                'Die Rolle konnte nicht gefunden werden.',
                'Rollen neu laden'
            );
        }
    }

}