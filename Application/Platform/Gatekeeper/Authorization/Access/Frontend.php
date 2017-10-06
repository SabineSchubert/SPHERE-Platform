<?php
namespace SPHERE\Application\Platform\Gatekeeper\Authorization\Access;

use SPHERE\Application\Api\Platform\Gatekeeper\Access as AccessApi;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Frontend\Summary;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblLevel;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblPrivilege;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRight;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Service\Entity\TblRole;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Minus;
use SPHERE\Common\Frontend\Icon\Repository\Plus;
use SPHERE\Common\Frontend\Icon\Repository\Save;
use SPHERE\Common\Frontend\Icon\Repository\Tag;
use SPHERE\Common\Frontend\Icon\Repository\TagList;
use SPHERE\Common\Frontend\Icon\Repository\TileBig;
use SPHERE\Common\Frontend\Icon\Repository\TileList;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Danger;
use SPHERE\Common\Frontend\Link\Repository\Exchange;
use SPHERE\Common\Frontend\Link\Repository\External;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Info;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Repository\Title;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Redirect;
use SPHERE\Common\Window\Stage;

/**
 * Class Frontend
 *
 * @package SPHERE\Application\System\Gatekeeper\Authorization\Access
 */
class Frontend
{

    /**
     * @return Stage
     */
    public function frontendAccess()
    {
        $Stage = new Stage( 'Rechteverwaltung' );
        $Stage->hasUtilityFavorite(true);

        $ReceiverStage = AccessApi::receiverStage();

        $Stage->addButton((new Standard('Rollen', new Link\Route(__NAMESPACE__), new TagList(), array(),
            'Zusammenstellung von Berechtigungen'))
            //->ajaxPipelineOnClick( AccessApi::pipelineRoleStage( $ReceiverStage ) )
        );

                $Stage->addButton((new Standard('Zugriffslevel', new Link\Route(__NAMESPACE__ . '/Level'), new Tag(), array(),
                    'Gruppen von Privilegien'))
                    //->ajaxPipelineOnClick( AccessApi::pipelineStageLevel( $ReceiverStage ) )
                );

                $Stage->addButton((new Standard('Privilegien', new Link\Route(__NAMESPACE__ . '/Privilege'), new TileBig(),
                    array(), 'Gruppen von Rechten'))
                );

                $Stage->addButton((new Standard('Rechte', new Link\Route(__NAMESPACE__ . '/Right'), new TileList(), array(),
                    'Geschützte Routen'))
                );

        $Stage->setContent(
            $ReceiverStage.AccessApi::pipelineRoleStage()
        );

        return $Stage;
    }

    /**
     * @return Stage
     */
    public function frontendWelcome()
    {

        $Stage = new Stage('Rechteverwaltung');
        $this->menuButton($Stage);

        $TwigData = array();
        $TblRoleList = Access::useService()->getRoleAll();
        /** @var TblRole $TblRole */
        foreach ((array)$TblRoleList as $TblRole) {
            if (!$TblRole) {
                continue;
            }
            $TwigData[$TblRole->getId()] = array('Role' => $TblRole);
            $TblLevelList = Access::useService()->getLevelAllByRole($TblRole);
            /** @var TblLevel $TblLevel */
            foreach ((array)$TblLevelList as $TblLevel) {
                if (!$TblLevel) {
                    continue;
                }
                $TwigData[$TblRole->getId()]['LevelList'][$TblLevel->getId()] = array('Level' => $TblLevel);
                $TblPrivilegeList = Access::useService()->getPrivilegeAllByLevel($TblLevel);
                /** @var TblPrivilege $TblPrivilege */
                foreach ((array)$TblPrivilegeList as $TblPrivilege) {
                    if (!$TblPrivilege) {
                        continue;
                    }
                    $TwigData[$TblRole->getId()]['LevelList'][$TblLevel->getId()]['PrivilegeList'][$TblPrivilege->getId()] = array('Privilege' => $TblPrivilege);
                    $TblRightList = Access::useService()->getRightAllByPrivilege($TblPrivilege);
                    foreach ((array)$TblRightList as $TblRight) {
                        if (!$TblRight) {
                            continue;
                        }
                        if (!isset($TwigData[$TblRole->getId()]['LevelList'][$TblLevel->getId()]['PrivilegeList'][$TblPrivilege->getId()]['RightList'])) {
                            $TwigData[$TblRole->getId()]['LevelList'][$TblLevel->getId()]['PrivilegeList'][$TblPrivilege->getId()]['RightList'] = array();
                        }
                        $TwigData[$TblRole->getId()]['LevelList'][$TblLevel->getId()]['PrivilegeList'][$TblPrivilege->getId()]['RightList'][] = $TblRight;
                    }
                }
            }

        }
        $Stage->setContent(
            new Summary($TwigData)
        );

        return $Stage;
    }

    /**
     * @param Stage $Stage
     */
    private function menuButton(Stage $Stage)
    {

        $Stage->addButton(new Standard('Rollen', new Link\Route(__NAMESPACE__), new TagList(), array(),
            'Zusammenstellung von Berechtigungen'));
        $Stage->addButton(new Standard('Zugriffslevel', new Link\Route(__NAMESPACE__ . '/Level'), new Tag(), array(),
            'Gruppen von Privilegien'));
        $Stage->addButton(new Standard('Privilegien', new Link\Route(__NAMESPACE__ . '/Privilege'), new TileBig(),
            array(), 'Gruppen von Rechten'));
        $Stage->addButton(new Standard('Rechte', new Link\Route(__NAMESPACE__ . '/Right'), new TileList(), array(),
            'Geschützte Routen'));
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public function frontendLevel($Name)
    {

        $Stage = new Stage('Berechtigungen', 'Zugriffslevel');
        $this->menuButton($Stage);
        $TblLevelAll = Access::useService()->getLevelAll();
        if ($TblLevelAll) {
            array_walk($TblLevelAll, function (TblLevel &$TblLevel) {

                $TblPrivilege = Access::useService()->getPrivilegeAllByLevel($TblLevel);
                if (empty($TblPrivilege)) {
                    /** @noinspection PhpUndefinedFieldInspection */
                    $TblLevel->Option = new Warning('Keine Privilegien vergeben')
                        . new PullRight(new Danger('Privilegien hinzufügen',
                            '/Platform/Gatekeeper/Authorization/Access/LevelGrantPrivilege',
                            null, array('Id' => $TblLevel->getId())
                        ));
                } else {
                    array_walk($TblPrivilege, function (TblPrivilege &$TblPrivilege) {

                        $TblPrivilege = $TblPrivilege->getName();
                    });
                    array_unshift($TblPrivilege, '');
                    /** @noinspection PhpUndefinedFieldInspection */
                    $TblLevel->Option = new Panel('Privilegien', $TblPrivilege)
                        . new PullRight(new Danger('Privilegien bearbeiten',
                            '/Platform/Gatekeeper/Authorization/Access/LevelGrantPrivilege',
                            null, array('Id' => $TblLevel->getId())
                        ));
                }
            });
        }

        $Stage->setContent(
            ($TblLevelAll
                ? new Table($TblLevelAll, new Title('Bestehende Zugriffslevel'), array(
                    'Name' => 'Name',
                    'Option' => 'Optionen'
                ))
                : new Warning('Keine Zugriffslevel vorhanden')
            )
            . Access::useService()->createLevel(
                new Form(new FormGroup(
                        new FormRow(
                            new FormColumn(
                                new TextField('Name', 'Name', 'Name')
                            )
                        ), new \SPHERE\Common\Frontend\Form\Repository\Title('Zugriffslevel anlegen'))
                    , new Primary('Hinzufügen')
                ), $Name
            )
        );
        return $Stage;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public function frontendPrivilege($Name)
    {

        $Stage = new Stage('Berechtigungen', 'Privilegien');
        $this->menuButton($Stage);
        $TblPrivilegeAll = Access::useService()->getPrivilegeAll();
        if ($TblPrivilegeAll) {
            array_walk($TblPrivilegeAll, function (TblPrivilege &$TblPrivilege) {

                $TblRight = Access::useService()->getRightAllByPrivilege($TblPrivilege);
                if (empty($TblRight)) {
                    /** @noinspection PhpUndefinedFieldInspection */
                    $TblPrivilege->Option = new Warning('Keine Rechte vergeben')
                        . new PullRight(new Danger('Rechte hinzufügen',
                            '/Platform/Gatekeeper/Authorization/Access/PrivilegeGrantRight',
                            null, array('Id' => $TblPrivilege->getId())
                        ));
                } else {
                    array_walk($TblRight, function (TblRight &$TblRight) {

                        $TblRight = $TblRight->getRoute();
                    });
                    array_unshift($TblRight, '');
                    /** @noinspection PhpUndefinedFieldInspection */
                    $TblPrivilege->Option = new Panel('Rechte (Routen)', $TblRight)
                        . new PullRight(new Danger('Rechte bearbeiten',
                            '/Platform/Gatekeeper/Authorization/Access/PrivilegeGrantRight',
                            null, array('Id' => $TblPrivilege->getId())
                        ));
                }
            });
        }

        $Stage->setContent(
            ($TblPrivilegeAll
                ? new Table($TblPrivilegeAll, new Title('Bestehende Privilegien'), array(
                    'Name' => 'Name',
                    'Option' => ''
                ))
                : new Warning('Keine Privilegien vorhanden')
            )
            . Access::useService()->createPrivilege(
                new Form(new FormGroup(
                        new FormRow(
                            new FormColumn(
                                new TextField('Name', 'Name', 'Name')
                            )
                        ), new \SPHERE\Common\Frontend\Form\Repository\Title('Privileg anlegen'))
                    , new Primary('Speichern', new Save())
                ), $Name
            )
        );
        return $Stage;
    }

    /**
     * @param null|string $Name
     *
     * @return Stage
     */
    public function frontendRight($Name)
    {

        $Stage = new Stage('Berechtigungen', 'Rechte');
        $this->menuButton($Stage);
        $TblRightAll = Access::useService()->getRightAll();

        $PublicRouteAll = Main::getDispatcher()->getPublicRoutes();
        if (!empty($PublicRouteAll)) {
            array_walk($PublicRouteAll, function (&$Route) {

                $Route = array(
                    'Route' => $Route,
                    'Option' => new External(
                            'Öffnen', $Route, null, array(), false
                        ) .
                        new Danger(
                            'Hinzufügen', '/Platform/Gatekeeper/Authorization/Access/Right', null,
                            array('Name' => $Route)
                        )
                );
            });
        } else {
            $PublicRouteAll = array();
        }
        $Stage->setContent(
            ($TblRightAll
                ? new Table($TblRightAll, new Title('Bestehende Rechte', 'Routen'), array(
                    'Route' => 'Name'
                ))
                : new Warning('Keine Routen vorhanden')
            )
            . Access::useService()->createRight(
                new Form(
                    new FormGroup(
                        new FormRow(
                            new FormColumn(
                                new TextField('Name', 'Name', 'Name')
                            )
                        ), new \SPHERE\Common\Frontend\Form\Repository\Title('Recht anlegen', 'Route'))
                    , new Primary('Hinzufügen')
                ), $Name
            )
            . new Table($PublicRouteAll, new Title('Öffentliche Routen', 'PUBLIC ACCESS'))
        );
        return $Stage;
    }

    /**
     * @param null|string $Name
     *
     * @param bool $IsSecure
     *
     * @return Stage
     */
    public function frontendRole($Name, $IsSecure = false)
    {

        $Stage = new Stage('Berechtigungen', 'Rollen');
        $this->menuButton($Stage);
        $TblRoleAll = Access::useService()->getRoleAll();
        if ($TblRoleAll) {
            array_walk($TblRoleAll, function (TblRole &$TblRole) {

                $TblLevel = Access::useService()->getLevelAllByRole($TblRole);

                if (empty($TblLevel)) {
                    /** @noinspection PhpUndefinedFieldInspection */
                    $TblRole->Option = new Warning('Keine Zugriffslevel vergeben')
                        . new PullRight(new Danger('Zugriffslevel hinzufügen',
                            '/Platform/Gatekeeper/Authorization/Access/RoleGrantLevel',
                            null, array('Id' => $TblRole->getId())
                        ));
                } else {
                    array_walk($TblLevel, function (TblLevel &$TblLevel) {

                        $TblLevel = $TblLevel->getName();
                    });
                    array_unshift($TblLevel, '');
                    /** @noinspection PhpUndefinedFieldInspection */
                    $TblRole->Option = new Panel('Zugriffslevel', $TblLevel)
                        . new PullRight(new Danger('Zugriffslevel bearbeiten',
                            '/Platform/Gatekeeper/Authorization/Access/RoleGrantLevel',
                            null, array('Id' => $TblRole->getId())
                        ));
                }
            });
        }
        $Stage->setContent(
            ($TblRoleAll
                ? new Table($TblRoleAll, new Title('Bestehende Rollen'), array(
                    'Name' => 'Name',
                    'Option' => 'Optionen'
                ))
                : new Warning('Keine Rollen vorhanden')
            )
            . Access::useService()->createRole(
                new Form(new FormGroup(
                        new FormRow(
                            new FormColumn(
                                new Panel('Rolle anlegen', array(
                                    new TextField('Name', 'Name', 'Name'),
                                ), Panel::PANEL_TYPE_INFO)
                            )
                        ), new \SPHERE\Common\Frontend\Form\Repository\Title('Neue Rolle anlegen'))
                    , new Primary('Hinzufügen')
                ), $Name, $IsSecure
            )
        );
        return $Stage;
    }

    /**
     * @param integer $Id
     * @param null|integer $TblLevel
     * @param null|bool $Remove
     *
     * @return Stage
     */
    public function frontendRoleGrantLevel($Id, $TblLevel, $Remove = null)
    {

        $Stage = new Stage('Berechtigungen', 'Rolle');
        $this->menuButton($Stage);

        $TblRole = Access::useService()->getRoleById($Id);
        if ($TblRole && null !== $TblLevel && ($TblLevel = Access::useService()->getLevelById($TblLevel))) {
            if ($Remove) {
                Access::useService()->removeRoleLevel($TblRole, $TblLevel);
                $Stage->setContent(
                    new Redirect('/Platform/Gatekeeper/Authorization/Access/RoleGrantLevel', 0, array('Id' => $Id))
                );
                return $Stage;
            } else {
                Access::useService()->addRoleLevel($TblRole, $TblLevel);
                $Stage->setContent(
                    new Redirect('/Platform/Gatekeeper/Authorization/Access/RoleGrantLevel', 0, array('Id' => $Id))
                );
                return $Stage;
            }
        }
        $TblAccessList = Access::useService()->getLevelAllByRole($TblRole);
        if (!$TblAccessList) {
            $TblAccessList = array();
        }

        $TblAccessListAvailable = array_udiff(Access::useService()->getLevelAll(), $TblAccessList,
            function (TblLevel $ObjectA, TblLevel $ObjectB) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        /** @noinspection PhpUnusedParameterInspection */
        array_walk($TblAccessListAvailable, function (TblLevel &$Entity, $Index, $Id) {

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = new PullRight(
                new \SPHERE\Common\Frontend\Link\Repository\Primary('Hinzufügen',
                    '/Platform/Gatekeeper/Authorization/Access/RoleGrantLevel', new Plus(),
                    array(
                        'Id' => $Id,
                        'TblLevel' => $Entity->getId()
                    ))
            );
        }, $Id);

        /** @noinspection PhpUnusedParameterInspection */
        array_walk($TblAccessList, function (TblLevel &$Entity, $Index, $Id) {

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = new PullRight(
                new \SPHERE\Common\Frontend\Link\Repository\Primary('Entfernen',
                    '/Platform/Gatekeeper/Authorization/Access/RoleGrantLevel', new Minus(), array(
                        'Id' => $Id,
                        'TblLevel' => $Entity->getId(),
                        'Remove' => true
                    ))
            );
        }, $Id);

        $Stage->setContent(
            new Info($TblRole->getName())
            .
            new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            new \SPHERE\Common\Frontend\Layout\Repository\Title('Zugriffslevel', 'Zugewiesen'),
                            (empty($TblAccessList)
                                ? new Warning('Keine Zugriffslevel vergeben')
                                : new Table($TblAccessList, null,
                                    array('Name' => 'Name', 'Option' => ''))
                            )
                        ), 6),
                        new LayoutColumn(array(
                            new \SPHERE\Common\Frontend\Layout\Repository\Title('Zugriffslevel', 'Verfügbar'),
                            (empty($TblAccessListAvailable)
                                ? new Info('Keine weiteren Zugriffslevel verfügbar')
                                : new Table($TblAccessListAvailable, null,
                                    array('Name' => 'Name ', 'Option' => ' '))
                            )
                        ), 6)
                    ))
                )
            )
        );

        return $Stage;
    }

    /**
     * @param integer $Id
     * @param null|integer $TblPrivilege
     * @param null|bool $Remove
     *
     * @return Stage
     */
    public function frontendLevelGrantPrivilege($Id, $TblPrivilege, $Remove = null)
    {

        $Stage = new Stage('Berechtigungen', 'Zugriffslevel');
        $this->menuButton($Stage);

        $TblLevel = Access::useService()->getLevelById($Id);
        if ($TblLevel && null !== $TblPrivilege && ($TblPrivilege = Access::useService()->getPrivilegeById($TblPrivilege))) {
            if ($Remove) {
                Access::useService()->removeLevelPrivilege($TblLevel, $TblPrivilege);
                $Stage->setContent(
                    new Redirect('/Platform/Gatekeeper/Authorization/Access/LevelGrantPrivilege', 0,
                        array('Id' => $Id))
                );
                return $Stage;
            } else {
                Access::useService()->addLevelPrivilege($TblLevel, $TblPrivilege);
                $Stage->setContent(
                    new Redirect('/Platform/Gatekeeper/Authorization/Access/LevelGrantPrivilege', 0,
                        array('Id' => $Id))
                );
                return $Stage;
            }
        }
        $TblAccessList = Access::useService()->getPrivilegeAllByLevel($TblLevel);
        if (!$TblAccessList) {
            $TblAccessList = array();
        }

        $TblAccessListAvailable = array_udiff(Access::useService()->getPrivilegeAll(), $TblAccessList,
            function (TblPrivilege $ObjectA, TblPrivilege $ObjectB) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        /** @noinspection PhpUnusedParameterInspection */
        array_walk($TblAccessListAvailable, function (TblPrivilege &$Entity, $Index, $Id) {

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = new PullRight(
                new \SPHERE\Common\Frontend\Link\Repository\Primary('Hinzufügen',
                    '/Platform/Gatekeeper/Authorization/Access/LevelGrantPrivilege', new Plus(),
                    array(
                        'Id' => $Id,
                        'TblPrivilege' => $Entity->getId()
                    ))
            );
        }, $Id);

        /** @noinspection PhpUnusedParameterInspection */
        array_walk($TblAccessList, function (TblPrivilege &$Entity, $Index, $Id) {

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Option = new PullRight(
                new \SPHERE\Common\Frontend\Link\Repository\Primary('Entfernen',
                    '/Platform/Gatekeeper/Authorization/Access/LevelGrantPrivilege', new Minus(),
                    array(
                        'Id' => $Id,
                        'TblPrivilege' => $Entity->getId(),
                        'Remove' => true
                    ))
            );
        }, $Id);

        $Stage->setContent(
            new Info($TblLevel->getName())
            .
            new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            new \SPHERE\Common\Frontend\Layout\Repository\Title('Privilegien', 'Zugewiesen'),
                            (empty($TblAccessList)
                                ? new Warning('Keine Privilegien vergeben')
                                : new Table($TblAccessList, null,
                                    array('Name' => 'Name', 'Option' => ''))
                            )
                        ), 6),
                        new LayoutColumn(array(
                            new \SPHERE\Common\Frontend\Layout\Repository\Title('Privilegien', 'Verfügbar'),
                            (empty($TblAccessListAvailable)
                                ? new Info('Keine weiteren Privilegien verfügbar')
                                : new Table($TblAccessListAvailable, null,
                                    array('Name' => 'Name ', 'Option' => ' '))
                            )
                        ), 6)
                    ))
                )
            )
        );

        return $Stage;
    }

    /**
     * @param integer $Id
     *
     * @return Stage
     */
    public function frontendPrivilegeGrantRight($Id)
    {

        $Stage = new Stage('Berechtigungen', 'Privileg');
        $this->menuButton($Stage);

        $TblPrivilege = Access::useService()->getPrivilegeById($Id);
        $TblAccessList = Access::useService()->getRightAllByPrivilege($TblPrivilege);
        if (!$TblAccessList) {
            $TblAccessList = array();
        }

        $TblAccessListAvailable = array_udiff(Access::useService()->getRightAll(), $TblAccessList,
            function (TblRight $ObjectA, TblRight $ObjectB) {

                return $ObjectA->getId() - $ObjectB->getId();
            }
        );

        /** @noinspection PhpUnusedParameterInspection */
        array_walk($TblAccessListAvailable, function (TblRight &$Entity, $Index, $Id) {

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Exchange = new Exchange(Exchange::EXCHANGE_TYPE_PLUS, array(
                'Id' => $Id,
                'TblRight' => $Entity->getId()
            ));
        }, $Id);

        /** @noinspection PhpUnusedParameterInspection */
        array_walk($TblAccessList, function (TblRight &$Entity, $Index, $Id) {

            /** @noinspection PhpUndefinedFieldInspection */
            $Entity->Exchange = new Exchange(Exchange::EXCHANGE_TYPE_MINUS, array(
                'Id' => $Id,
                'TblRight' => $Entity->getId()
            ));
        }, $Id);

        $Stage->setContent(
            new Info($TblPrivilege->getName())
            .
            new Layout(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            new \SPHERE\Common\Frontend\Layout\Repository\Title('Rechte', 'Zugewiesen'),
                            new Table($TblAccessList, null,
                                array('Exchange' => '', 'Route' => 'Route'), array(
                                    'order' => array(array(1, 'asc')),
                                    'columnDefs' => array(
                                        array('orderable' => false, 'width' => '1%', 'targets' => 0)
                                    ),
                                    'ExtensionRowExchange' => array(
                                        'Enabled' => true,
                                        'Url' => '/Api/Platform/Gatekeeper/Authorization/Access/PrivilegeGrantRight',
                                        'Handler' => array(
                                            'From' => 'glyphicon-minus-sign',
                                            'To' => 'glyphicon-plus-sign',
                                            'All' => 'TableRemoveAll'
                                        ),
                                        'Connect' => array(
                                            'From' => 'TableCurrent',
                                            'To' => 'TableAvailable',
                                        )
                                    )
                                )
                            ),
                            new Exchange(Exchange::EXCHANGE_TYPE_MINUS, array(), 'Alle entfernen', 'TableRemoveAll')
                        ), 6),
                        new LayoutColumn(array(
                            new \SPHERE\Common\Frontend\Layout\Repository\Title('Rechte', 'Verfügbar'),
                            new Table($TblAccessListAvailable, null,
                                array('Exchange' => ' ', 'Route' => 'Route '), array(
                                    'order' => array(array(1, 'asc')),
                                    'columnDefs' => array(
                                        array('orderable' => false, 'width' => '1%', 'targets' => 0)
                                    ),
                                    'ExtensionRowExchange' => array(
                                        'Enabled' => true,
                                        'Url' => '/Api/Platform/Gatekeeper/Authorization/Access/PrivilegeGrantRight',
                                        'Handler' => array(
                                            'From' => 'glyphicon-plus-sign',
                                            'To' => 'glyphicon-minus-sign',
                                            'All' => 'TableAddAll'
                                        ),
                                        'Connect' => array(
                                            'From' => 'TableAvailable',
                                            'To' => 'TableCurrent',
                                        ),
                                    )
                                )
                            ),
                            new Exchange(Exchange::EXCHANGE_TYPE_PLUS, array(), 'Alle hinzufügen', 'TableAddAll')
                        ), 6)
                    ))
                )
            )
        );

        return $Stage;
    }
}
