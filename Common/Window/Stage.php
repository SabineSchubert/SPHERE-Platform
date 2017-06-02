<?php
namespace SPHERE\Common\Window;

use MOC\V\Component\Template\Component\IBridgeInterface;
use SPHERE\Application\Api\Platform\Utility\Favorite;
use SPHERE\Application\Api\Search\Search;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access;
use SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account;
use SPHERE\Common\Frontend\Ajax\Template\CloseModal;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary as PrimaryButtonForm;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Repository\Field\TokenField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Search as SearchIcon;
use SPHERE\Common\Frontend\ITemplateInterface;
use SPHERE\Common\Frontend\Layout\Structure\Teaser;
use SPHERE\Common\Frontend\Link\ILinkInterface;
use SPHERE\Common\Frontend\Link\Repository\AbstractLink;
use SPHERE\Common\Frontend\Text\Repository\Muted;
use SPHERE\Common\Frontend\Text\Repository\Primary;
use SPHERE\System\Extension\Extension;

/**
 * Class Stage
 *
 * @package SPHERE\Common\Window
 */
class Stage extends Extension implements ITemplateInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /** @var string $Title */
    private $Title = '';
    /** @var string $Description */
    private $Description = '';
    /** @var string $Message */
    private $Message = '';
    /** @var Teaser $Teaser */
    private $Teaser = null;
    /** @var string $Content */
    private $Content = '';
    /** @var ILinkInterface[] $Menu */
    private $Menu = array();
    /** @var array $MaskMenu Highlight current Path-Button if only one exists */
    private $MaskMenu = array();
    /** @var bool $hasUtilityFavorite */
    private $hasUtilityFavorite = false;
    /** @var bool $hasUtilitySearch */
    private $hasUtilitySearch = false;
    /**
     * @param null|string $Title
     * @param null|string $Description
     * @param null|string $Message
     */
    public function __construct($Title = null, $Description = null, $Message = null)
    {

        $this->Template = $this->getTemplate(__DIR__.'/Stage.twig');
        if (null !== $Title) {
            $this->setTitle($Title);
        }
        if (null !== $Description) {
            $this->setDescription($Description);
        }
        if (null !== $Message) {
            $this->setMessage($Message);
        }
    }

    /**
     * @param string $Value
     *
     * @return Stage
     */
    public function setTitle($Value)
    {

        $this->Title = $Value;
        return $this;
    }

    /**
     * @param string $Value
     *
     * @return Stage
     */
    public function setDescription($Value)
    {

        $this->Description = $Value;
        return $this;
    }

    /**
     * @param string $Message
     *
     * @return Stage
     */
    public function setMessage($Message)
    {

        $this->Message = new Muted($Message);
        return $this;
    }

    /**
     * @param $Teaser
     *
     * @return Stage
     */
    public function setTeaser($Teaser)
    {

        $this->Teaser = $Teaser;
        return $this;
    }

    /**
     * @param ILinkInterface $Button
     *
     * @return Stage
     */
    public function addButton(ILinkInterface $Button)
    {

        if ($Button instanceof AbstractLink) {
            $this->MaskMenu[] = $Button->getLink();
        } else {
            $this->MaskMenu[] = '';
        }
        $this->Menu[] = $Button;//->__toString();
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {

        return $this->getContent();
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable('StageTitle', $this->Title);
        $this->Template->setVariable('StageDescription', $this->Description);
        $this->Template->setVariable('StageMessage', $this->Message);
        $this->Template->setVariable('StageTeaser', $this->Teaser);

        /**
         * Add Ajax Frontend Modal-Search Receiver
         */
        $this->Content .= Search::receiverSearchModal();
        /**
         * Add Ajax Frontend Modal-Close Receiver
         */
        $this->Content .= CloseModal::CloseModalReceiver();

        $this->Template->setVariable('StageContent', $this->Content);

        if($this->hasUtilitySearch) {
            $this->Template->setVariable('StageSearch',
                new Form(
                    new FormGroup(
                        new FormRow(array(
                            new FormColumn(
                                new TextField('Search[Text]', 'Ich suche ..')
                            ,11),
                            new FormColumn(
                                new PrimaryButtonForm('', new SearchIcon())
                            ,1),
                        ))
                    )
                , null, '/Search')
            );
        }

        if((
            $this->hasUtilityFavorite
            && Account::useService()->getAccountBySession()
            && Access::useService()->hasAuthorization(Favorite::getEndpoint())
        )) {

            $ReceiverFavoriteButton = Favorite::receiverFavorite();
            $this->Template->setVariable('StageFavorite', $ReceiverFavoriteButton
                . Favorite::pipelineGetFavorite($ReceiverFavoriteButton, $this->getRequest()->getPathInfo(), $this->Title, $this->Description )
            );
        }

        // Highlight current Route-Stage-Button
        if (!empty( $this->Menu )) {
            $HighlightButton = array_keys($this->MaskMenu, $this->getRequest()->getUrl());
            if (count($HighlightButton) == 1) {
                switch ($this->Menu[current($HighlightButton)]->getType()) {
                    case AbstractLink::TYPE_PRIMARY:
                    case AbstractLink::TYPE_DANGER:
                    case AbstractLink::TYPE_WARNING:
                    case AbstractLink::TYPE_SUCCESS:
                    case AbstractLink::TYPE_LINK:
                        $this->Menu[current($HighlightButton)]->setName(
                            (($this->Menu[current($HighlightButton)]->getName()))
                        );
                        break;
                    default:
                        $this->Menu[current($HighlightButton)]->setName(
                            new Primary(($this->Menu[current($HighlightButton)]->getName()))
                        );
                }
            }
        }
        $this->Template->setVariable('StageMenu', $this->Menu);

        return $this->Template->getContent();
    }

    /**
     * @param string $Content
     *
     * @return Stage
     */
    public function setContent($Content)
    {

        $this->Content = $Content;
        return $this;
    }

    /**
     * @param bool $Toggle
     */
    public function hasUtilityFavorite($Toggle = true)
    {
        $this->hasUtilityFavorite = (bool)$Toggle;
    }

    /**
     * @param bool $Toggle
     */
    public function hasUtilitySearch($Toggle = true)
    {
        $this->hasUtilitySearch = (bool)$Toggle;
    }
}
