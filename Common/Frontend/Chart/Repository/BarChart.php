<?php
namespace SPHERE\Common\Frontend\Chart\Repository;

use MOC\V\Component\Template\Component\IBridgeInterface;
use SPHERE\Common\Frontend\Chart\IChartInterface;
use SPHERE\System\Extension\Extension;

/**
 * Class BarChart
 *
 * @package SPHERE\Common\Frontend\Chart\Repository
 */
class BarChart extends Extension implements IChartInterface
{

    /** @var string $Hash */
    protected $Hash = '';
    /** @var IBridgeInterface $Template */
    private $Template = null;

    public function __construct()
    {

        $this->Template = $this->getTemplate(__DIR__.'/BarChart.twig');
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

        $this->Template->setVariable('Hash', $this->getHash());
        return $this->Template->getContent();
    }

    /**
     * @return string
     */
    public function getHash()
    {

        if (empty( $this->Hash )) {
            $this->Hash = md5(json_encode(microtime()));
        }
        return $this->Hash;
    }
}
