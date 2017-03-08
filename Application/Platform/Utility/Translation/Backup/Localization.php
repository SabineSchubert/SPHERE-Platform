<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use MOC\V\Component\Template\Template;
use SPHERE\Application\Platform\Utility\Translation\IComponentInterface;
use SPHERE\System\Config\ConfigFactory;
use SPHERE\System\Config\Reader\ArrayReader;

class Localization extends AbstractComponent
{
    /**
     * Locale constructor.
     * @param IComponentInterface $Component
     * @param string $Domain
     */
    public function __construct(IComponentInterface $Component, $Domain = __CLASS__)
    {
        $this->setIdentifier($Domain);
        $this->setComponent($Component);
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->getIdentifier();
    }

    /**
     * @param string $Domain
     */
    public function setDomain($Domain)
    {
        $this->setIdentifier($Domain);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $Identifier = parent::__toString();

        $de = (new ConfigFactory())->createReader(array(
            'SPHERE\Application\Platform\Utility\Translation\Localization.Stage.Headline.Singular,Big' => ' :P ',
            'Seite 1.Stage.Headline.Normal' => Template::getTwigTemplateString(' :P öäüß {{ Number|join(\', ^^ -> \') }}' )->setVariable('Number',array('abc'))->getContent()
        ), new ArrayReader());

        if (($config = $de->getConfig())) {
            if (($container = $config->getContainer($Identifier))) {

                return $container->getValue();
            }
        }
        return $Identifier;
    }


}
