<?php
namespace SPHERE\Application\Api\Platform\Gatekeeper;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\Api\Platform\Gatekeeper\Access\Receiver;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access as AccessApp;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Common\Frontend\Ajax\Template\CloseModal;
use SPHERE\Common\Frontend\Form\IFormInterface;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Edit;
use SPHERE\Common\Frontend\Icon\Repository\Globe;
use SPHERE\Common\Frontend\Icon\Repository\Lock;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Success;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Danger;
use SPHERE\Common\Frontend\Text\Repository\Muted;
use SPHERE\Common\Window\Redirect;


/**
 * Class Access
 *
 * - Role
 * - Level
 * - Privilege
 * - Right / Route
 *
 * @package SPHERE\Application\Api\Platform\Gatekeeper
 */
class Access extends Receiver implements IApiInterface
{
    use ApiTrait;

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('stageRole');
        $Dispatcher->registerMethod('tableRole');
        $Dispatcher->registerMethod('formRole');
        $Dispatcher->registerMethod('insertRole');

        $Dispatcher->registerMethod('stageLevel');
        $Dispatcher->registerMethod('tableLevel');

        return $Dispatcher->callMethod($Method);
    }

    public function tableRole()
    {
        if (($TblRoleAll = AccessApp::useService()->getRoleAll())) {
            $TableContent = array();
            $Edit = Access::receiverEditRole();
            array_walk($TblRoleAll, function (TblRole $TblRole) use (&$TableContent, $Edit) {
                $Content = $TblRole->__toArray();
                $Content['IsInternal'] = ($Content['IsInternal'] ? new Danger(new Lock()) : new Muted(new Globe()));

                $Content['Option'] = (new Standard('', '#', new Edit()))
                    ->ajaxPipelineOnClick(Access::pipelineEditRole($Edit));
                $TableContent[] = $Content;
            });
            return $Edit . new Table($TableContent, null, array(
                    'IsInternal' => '',
                    'Name' => 'Rolle',
                    'Option' => ''
                ), array(
                    'order' => array(1, 'asc'),
                    'columnDefs' => array(
                        array('width' => '1%', 'targets' => array(0, -1)),
                        array('searchable' => false, 'orderable' => false, 'targets' => array(0, -1))
                    )
                ));
        } else {
            return new Warning('Keine Rollen vorhanden');
        }
    }

    public function tableLevel()
    {
        if (($TblRoleAll = AccessApp::useService()->getLevelAll())) {
            return new Table($TblRoleAll);
        } else {
            return new Warning('Keine Zugriffslevel vorhanden');
        }
    }

    /**
     * @param string $Receiver
     * @return IFormInterface|Redirect
     */
    public function formRole($Receiver, $TableReceiver)
    {
        return (new Form(
            new FormGroup(
                new FormRow(array(
                    new FormColumn(array(
                        (new TextField('Name', '', 'Name der Rolle'))->setRequired()->setAutoFocus(),
                        (new CheckBox('IsInternal', 'Interne Rolle', 1))
                    )),
                ))
            )
            , new Primary('Rolle anlegen'), '', array(
                'TableReceiver' => $TableReceiver
            )
        ))->ajaxPipelineOnSubmit(
            Access::pipelineInsertRole((Access::receiverCreateRole())->setIdentifier($Receiver))
        );
    }

    public function insertRole($Receiver, $TableReceiver, $Name = null, $IsInternal = null)
    {
        // TODO: Insert Route
        $Error = false;
        $Form = $this->formRole($Receiver, $TableReceiver);

        if( !$Name ) {
            $Error = true;
            $Form->setError('Name', 'Bitte geben Sie einen Namen an');
        }

        if( $Error ) {
            // on Error
            return $Form;
        } else {
            // on Success
            $TableReceiver = Access::receiverTableRole()->setIdentifier($TableReceiver);
            $Receiver = Access::receiverCreateRole()->setIdentifier($Receiver);

            if( AccessApp::useService()->createRole( $Name, ( $IsInternal ? true : false ) ) ) {
                // on Success
                return new Success('Rolle erfolgreich angelegt')
                    . (Access::pipelineTableRole($TableReceiver))
                        ->appendEmitter((new CloseModal($Receiver))->getEmitter());
            } else {
                // on Error
                return $Form->setError('Name', 'Die Rolle konnte nicht angelegt werden');
            }
        }
    }

}
