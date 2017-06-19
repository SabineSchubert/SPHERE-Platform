<?php
namespace SPHERE\Common\Window\Navigation;

use MOC\V\Component\Template\Component\IBridgeInterface;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Window\Navigation\Link\Icon;
use SPHERE\Common\Window\Navigation\Link\Name;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\System\Extension\Extension;

/**
 * Class Link
 *
 * @package SPHERE\Common\Window\Navigation
 */
class Link extends Extension
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var Route $Route */
    private $Route = null;
    /** @var Name $Name */
    private $Name = null;
    /** @var Icon|null $Icon */
    private $Icon = null;
    /** @var bool $Active */
    private $Active = false;
    /** @var string $Hash */
    protected $Hash = '';

    /**
     * @return string
     */
    public function getHash()
    {
        if (empty( $this->Hash )) {
            $this->Hash = 'Window-Navigation-'.crc32( uniqid(__CLASS__, true) );
        }
        return $this->Hash;
    }
    /**
     * @param Route $Route
     * @param Name $Name
     * @param Icon|null $Icon
     * @param bool $Active
     * @param string|bool $ToolTip
     */
    public function __construct(Route $Route, Name $Name, Icon $Icon = null, $Active = false, $ToolTip = false)
    {

        $this->Route = $Route;
        $this->Name = $Name;
        $this->Icon = $Icon;
        $this->Active = $Active || $this->getActive($Route);

        $this->Template = $this->getTemplate(__DIR__.'/Link.twig');
        $this->Template->setVariable('ElementHash', $this->getHash());
        $this->Template->setVariable('Route', $Route->getValue());
        $this->Template->setVariable('Name', $Name->getValue());
        if (null === $Icon) {
            $this->Template->setVariable('Icon', '');
        } else {
            $this->Template->setVariable('Icon', $Icon->getValue());
        }
        if ($this->Active) {
            $this->Template->setVariable('ActiveClass', 'active');
        } else {
            $this->Template->setVariable('ActiveClass', '');
        }
        if ($ToolTip) {
            if (is_string($ToolTip)) {
                $this->Template->setVariable('ElementToolTip', $ToolTip);
            } else {
                $this->Template->setVariable('ElementToolTip', $Name->getValue());
            }
        }
    }

    /**
     * @param Route $Route
     *
     * @return bool
     */
    private function getActive(Route $Route)
    {

        return 0 === strpos($this->getRequest()->getUrlBase().$this->getRequest()->getPathInfo(), $Route->getValue());
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

        return $this->Template->getContent();
    }

    /**
     * @return boolean
     */
    public function isActive()
    {

        return $this->Active;
    }

    /**
     * @return Route
     */
    public function getRoute()
    {

        return $this->Route;
    }

    /**
     * @return Name
     */
    public function getName()
    {

        return $this->Name;
    }

    /**
     * @return Icon|null
     */
    public function getIcon()
    {

        return $this->Icon;
    }

    /**
     * @param Pipeline|Pipeline[] $Pipeline
     * @return $this
     */
    public function ajaxPipelineOnClick( $Pipeline )
    {
        $Script = '';
        if( is_array( $Pipeline ) ) {
            foreach( $Pipeline as $Element ) {
                $Script .= $Element->parseScript($this);
            }
        } else {
            $Script = $Pipeline->parseScript($this);
        }

        $this->Template->setVariable('AjaxEventClick', $Script);
        return $this;
    }
}
