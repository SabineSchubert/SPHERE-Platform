<?php
namespace SPHERE\Application\Platform\Utility\Translation\Component;

class Pattern
{
    /** @var array $ExpressionList */
    private $ExpressionList = array();

    public function __construct( $RegEx = array() )
    {
        $this->ExpressionList = $RegEx;
    }

    public function addExpression($RegEx)
    {
        $this->ExpressionList[] = $RegEx;
    }

    public function getExpressionList()
    {
        return $this->ExpressionList;
    }
}
