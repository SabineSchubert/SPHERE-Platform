<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use SPHERE\Application\Platform\Utility\Translation\IComponentInterface;
use SPHERE\System\Extension\Extension;

abstract class AbstractComponent extends Extension
{

    /** @var string $Delimiter */
    private $Delimiter = '.';
    /** @var string $Identifier */
    private $Identifier = '';
    /** @var null|IComponentInterface $Component */
    private $Component = null;

    /**
     * @return string
     */
    public function __toString()
    {

        return $this->getPath();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        if ($this->getComponent() instanceof Group) {
            return implode(
                $this->getDelimiter(), array(
                $this->getIdentifier(),
                $this->getComponent()->getPath()
            ));
        } else {
            if ($this->getComponent() instanceof Singular) {
                return implode(
                    $this->getDelimiter(), array(
                    $this->getIdentifier(),
                    $this->getComponent()->getIdentifier()
                ));
            } else {
                if ($this->getComponent() instanceof Plural) {
                    return implode(
                        $this->getDelimiter(), array(
                        $this->getIdentifier(),
                        $this->getComponent()->getIdentifier()
                    ));
                } else {
                    return $this->getIdentifier();
                }
            }
        }
    }

    /**
     * @return null|IComponentInterface
     */
    protected function getComponent()
    {
        return $this->Component;
    }

    /**
     * @param null|IComponentInterface $Component
     */
    protected function setComponent($Component)
    {
        $this->Component = $Component;
    }

    /**
     * @return string
     */
    protected function getDelimiter()
    {
        return $this->Delimiter;
    }

    /**
     * @param string $Delimiter
     */
    protected function setDelimiter($Delimiter)
    {
        $this->Delimiter = $Delimiter;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->Identifier;
    }

    /**
     * @param string $Identifier
     */
    protected function setIdentifier($Identifier)
    {
        $Identifier = preg_replace('!' . preg_quote($this->getDelimiter()) . '!is', ',', $Identifier);
        $this->Identifier = $Identifier;
    }
}
