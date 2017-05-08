<?php
namespace SPHERE\Common\Frontend\Table\Structure;

use SPHERE\Common\Frontend\Table\Repository\Title;

/**
 * Class TableData
 * @deprecated use new Table()
 * @package SPHERE\Common\Frontend\Table\Structure
 */
class TableData
{
    /**
     * @deprecated use new Table()
     *
     * @param string|Object[] $DataList
     * @param Title $TableTitle
     * @param array $ColumnDefinition
     * @param bool|array $Interactive
     * @param bool $useHtmlRenderer false JS, true DOM
     */
    public function __construct(
        $DataList,
        Title $TableTitle = null,
        $ColumnDefinition = array(),
        $Interactive = true,
        $useHtmlRenderer = false
    ) {
        return new Table( $DataList, $TableTitle, $ColumnDefinition, $Interactive, $useHtmlRenderer );
    }
}
