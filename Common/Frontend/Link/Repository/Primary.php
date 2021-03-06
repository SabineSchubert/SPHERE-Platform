<?php
namespace SPHERE\Common\Frontend\Link\Repository;

use SPHERE\Common\Frontend\Icon\IIconInterface;
use SPHERE\Common\Frontend\Link\ILinkInterface;

/**
 * Class Primary
 *
 * @package SPHERE\Common\Frontend\Link\Repository
 */
class Primary extends AbstractLink implements ILinkInterface
{

    /**
     * AbstractLink constructor.
     *
     * @param string              $Name
     * @param string              $Path
     * @param IIconInterface|null $Icon
     * @param array               $Data
     * @param bool|string         $ToolTip
     */
    public function __construct($Name, $Path, IIconInterface $Icon = null, $Data = array(), $ToolTip = false)
    {

        $this->setType(self::TYPE_PRIMARY);
        parent::__construct($Name, $Path, $Icon, $Data, $ToolTip);
    }

}
