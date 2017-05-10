<?php
namespace SPHERE\Common\Frontend\Layout\Repository;

use SPHERE\Common\Frontend\ITemplateInterface;
use SPHERE\System\Extension\Extension;

/**
 * Class Scrollable
 * @package SPHERE\Common\Frontend\Layout\Repository
 */
class Scrollable extends Extension implements ITemplateInterface
{

    /** @var string $Content */
    private $Content = '';

    /**
     * @param string|array $Content
     */
    public function __construct($Content)
    {

        if( is_array($Content) ) {
            $Content = implode( '', $Content);
        }
        $this->Content = $Content;
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

        return '<div class="pre-scrollable" style="overflow-y: auto;">'.$this->Content.'</div>';
    }
}
