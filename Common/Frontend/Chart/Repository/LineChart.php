<?php
namespace SPHERE\Common\Frontend\Chart\Repository;

use MOC\V\Component\Template\Component\IBridgeInterface;
use SPHERE\Common\Frontend\Chart\IChartInterface;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

/**
 * Class LineChart
 *
 * @package SPHERE\Common\Frontend\Chart\Repository
 */
class LineChart extends Extension implements IChartInterface
{

    /** @var string $Hash */
    protected $Hash = '';
    /** @var IBridgeInterface $Template */
    private $Template = null;

    public function __construct(
        $Data = array(),
        $Labels = array()
    )
    {
        $Keys = array_keys($Data[0]);
        $xkey = current($Keys);
        $ykeys = array_values(array_diff($Keys, (array)$xkey));

        $this->Template = $this->getTemplate(__DIR__.'/LineChart.twig');
        $this->Template->setVariable('ElementData', $Data);
        $this->Template->setVariable('ElementLabels', $Labels);
        $this->Template->setVariable('ElementxKey', $xkey);
        $this->Template->setVariable('ElementyKeys', $ykeys);
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
