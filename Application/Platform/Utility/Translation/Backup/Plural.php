<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

use SPHERE\Application\Platform\Utility\Translation\IComponentInterface;

class Plural extends Singular implements IComponentInterface
{
    /** @var null|Pattern $Pattern */
    private $Pattern = null;

    public function __construct($Content, Pattern $Pattern )
    {
        $this->Pattern = $Pattern;
        parent::__construct( $Content );
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return parent::getIdentifier() . ' (Pattern: ' . implode(', ',$this->Pattern->getExpressionList()).')';
    }
}
