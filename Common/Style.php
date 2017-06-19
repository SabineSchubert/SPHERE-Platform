<?php
namespace SPHERE\Common;

use MOC\V\Core\FileSystem\FileSystem;
use MOC\V\Core\HttpKernel\Vendor\Universal\Request;
use SPHERE\Library\Script as ScriptLibrary;
use SPHERE\Library\Style as StyleLibrary;
use SPHERE\Library\Style\Library;
use SPHERE\System\Cache\Handler\APCuHandler;
use SPHERE\System\Debugger\Logger\ErrorLogger;
use SPHERE\System\Extension\Extension;

/**
 * Class Style
 *
 * @package SPHERE\Common
 */
class Style extends Extension
{

    /** @var array $SourceList */
    private static $SourceList = array();

    /** @var array $CombinedList */
    private static $CombinedList = array();

    /** @var array $AdditionalList */
    private static $AdditionalList = array();

    /**
     * Default
     */
    private function __construct()
    {

        try {
            $this->setLibrary((new StyleLibrary('Bootstrap', '3.3.7'))->getLibrary());
//            $this->setLibrary((new StyleLibrary('Bootstrap.Theme', '3.3.7'))->getLibrary());
            $this->setSource('/Common/Style/Bootstrap.css');
            $this->setSource('/Common/Style/Cover.css');
            $this->setSource('/Common/Style/Resource.css');
            $this->setLibrary((new StyleLibrary('Bootstrap.Glyphicons.Glyphicons', '1.9.2'))->getLibrary());
            $this->setLibrary((new StyleLibrary('Bootstrap.Glyphicons.Halflings', '1.9.2'))->getLibrary());
            $this->setLibrary((new StyleLibrary('Bootstrap.Glyphicons.Filetypes', '1.9.2'))->getLibrary());
            $this->setLibrary((new StyleLibrary('Bootstrap.Glyphicons.Social', '1.9.2'))->getLibrary());
            $this->setLibrary((new StyleLibrary('Foundation.Icons', '3.0'))->getLibrary());
            $this->setLibrary((new StyleLibrary('FontAwesome', '4.7.0'))->getLibrary());
            $this->setLibrary((new StyleLibrary('Slick', '1.6.0'))->getLibrary());
            $this->setLibrary((new StyleLibrary('Slick.Theme', '1.6.0'))->getLibrary());

            $this->setLibrary((new StyleLibrary('Bootstrap.Slider', '9.8.0'))->getLibrary());
//            $this->setLibrary((new StyleLibrary('Bootstrap.MagicSuggest','2.1.4'))->getLibrary());
            $this->setLibrary((new StyleLibrary('Bootstrap.TokenField','master'))->getLibrary());
//            $this->setLibrary((new StyleLibrary('Bootstrap.TokenField.TypeAHead','0.12.1'))->getLibrary());

            $this->setLibrary((new StyleLibrary('jQuery.Formstone.Selecter', '3.2.4'))->getLibrary(), false, true);
            $this->setLibrary((new StyleLibrary('jQuery.Formstone.Stepper', '3.0.8'))->getLibrary(), false, true);

            $this->setLibrary((new StyleLibrary('jQuery.Select2', '4.0.3'))->getLibrary(), false, true);
            $this->setLibrary((new StyleLibrary('jQuery.Select2.Theme', '0.1.0.9'))->getLibrary(), false, true);


            $this->setSource((new ScriptLibrary('jQuery.Gridster', '0.6.10'))->getLibrary()->getLocation() . '/dist/jquery.gridster.min.css', false, true);
            $this->setSource((new StyleLibrary('Bootstrap.Checkbox', '0.3.3'))->getLibrary()->getLocation() . '/awesome-bootstrap-checkbox.css', false, true);

            $this->setSource((new StyleLibrary('Morris.js', '0.5.1'))->getLibrary()->getSource(), false, true);

        } catch (\Exception $Exception) {
            Main::getDisplay()->setException($Exception, 'Style Library');
        }

        //        <link rel="stylesheet" type="text/css" href="Bootstrap-3.3.6/css/bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="DataTables-1.10.12/css/dataTables.bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="AutoFill-2.1.2/css/autoFill.bootstrap.min.css"/>
        //        <link rel="stylesheet" type="text/css" href="Buttons-1.2.2/css/buttons.bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="ColReorder-1.3.2/css/colReorder.bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="FixedColumns-3.2.2/css/fixedColumns.bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="FixedHeader-3.1.2/css/fixedHeader.bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="KeyTable-2.1.3/css/keyTable.bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="Responsive-2.1.0/css/responsive.bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="RowReorder-1.1.2/css/rowReorder.bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="Scroller-1.4.2/css/scroller.bootstrap.css"/>
        //        <link rel="stylesheet" type="text/css" href="Select-1.2.0/css/select.bootstrap.css"/>
        //

        $this->setSource('/Library/DataTables/Responsive-2.1.0/css/responsive.bootstrap.min.css', false, true);
        $this->setSource('/Library/DataTables/RowReorder-1.1.2/css/rowReorder.bootstrap.min.css', false, true);
        $this->setSource('/Library/DataTables/FixedHeader-3.1.2/css/fixedHeader.bootstrap.min.css', false, true);
////        $this->setSource( '/Library/jQuery.DataTables/1.10.7/media/css/jquery.dataTables.min.css' );
//        $this->setSource('/Library/jQuery.DataTables/1.10.7/extensions/Responsive/css/dataTables.responsive.css', false,
//            true);
//        $this->setSource('/Library/jQuery.DataTables.Plugins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css',
//            false, true);

        $this->setSource('/Library/Bootstrap.DateTimePicker/4.14.30/build/css/bootstrap-datetimepicker.min.css', false,
            true);
        $this->setSource('/Library/Bootstrap.FileInput/4.1.6/css/fileinput.min.css', false, true);
        $this->setSource('/Library/Bootstrap.Select/1.6.4/dist/css/bootstrap-select.min.css', false, true);
        $this->setSource('/Library/Twitter.Typeahead.Bootstrap/1.0.1/typeaheadjs.css', false, true);

        $this->setSource('/Library/jQuery.jCarousel/0.3.3/examples/responsive/jcarousel.responsive.css', false, true);
        $this->setSource('/Library/jQuery.FlowPlayer/6.0.3/skin/functional.css', false, true);
//        $this->setSource('/Library/Highlight.js/9.10.0/styles/docco.css', false, true);
        $this->setLibrary((new StyleLibrary('Highlight.js-Darcula','9.10.0'))->getLibrary(),false, true);
// 3.3.5

//        $this->setSource('/Common/Style/Correction.css', false, true);

        $this->setSource('/Common/Style/NavBar.Correction.css', false, true);
        $this->setSource('/Common/Style/Stage.Correction.css', false, true);
        $this->setSource('/Common/Style/DataTable.Correction.css', false, true);
        $this->setSource('/Common/Style/Panel.Correction.css', false, true);
        $this->setSource('/Common/Style/CheckBox.Correction.css', false, true);
        $this->setSource('/Common/Style/RadioBox.Correction.css', false, true);
        $this->setSource('/Common/Style/SelectBox.Correction.css', false, true);
        $this->setSource('/Common/Style/Select2.Correction.css', false, true);
        $this->setSource('/Common/Style/TypeAHead.Correction.css', false, true);
        $this->setSource('/Common/Style/Button.Correction.css', false, true);
        $this->setSource('/Common/Style/Teaser.Correction.css', false, true);
        $this->setSource('/Common/Style/Slider.Correction.css', false, true);

        $this->setSource('/Common/Style/MBComIcon.css');
        $this->setSource('/Common/Style/MBComFont.css');

//        $this->setSource('/Common/Style/CleanSlate/0.10.1/cleanslate.css', false, true);

        $this->setSource('/Common/Style/PhpInfo.css', false, true);
        $this->setSource('/Common/Style/Addition.css');
        $this->setSource('/Common/Style/Animate.css');
    }

    /**
     * @param Library $Library
     * @param bool $Combined
     * @param bool $Additional
     */
    public function setLibrary(Library $Library, $Combined = false, $Additional = false)
    {
        $this->setSource($Library->getSource(), $Combined, $Additional);
    }

    /**
     * @param string $Location
     * @param bool $Combined
     * @param bool $Additional
     */
    public function setSource($Location, $Combined = false, $Additional = false)
    {

        $PathBase = $this->getRequest()->getPathBase();
        if ($Combined) {
            if (!in_array(md5($Location), self::$CombinedList)) {
                self::$CombinedList[md5($Location)] = $PathBase . $Location;
            }
        } elseif ($Additional) {
            if (!in_array(md5($Location), self::$AdditionalList)) {
                self::$AdditionalList[md5($Location)] = $PathBase . $Location;
            }
        } else {
            if (!in_array(md5($Location), self::$SourceList)) {
                self::$SourceList[md5($Location)] = $PathBase . $Location;
            }
        }
    }

    /**
     * @return Style
     */
    public static function getManager()
    {

        return new Style();
    }

    /**
     * @param bool $withTag
     *
     * @return string
     */
    public function getCombinedStyle($withTag = true)
    {

        if ($withTag) {
            return $this->getCombinedStyleTag(
                implode("\n", array(
                    $this->parseCombinedStyle(self::$CombinedList),
                    $this->parseCombinedStyle(self::$SourceList)
                ))
            );
        } else {
            return implode("\n", array(
                $this->parseCombinedStyle(self::$CombinedList),
                $this->parseCombinedStyle(self::$SourceList)
            ));
        }
    }

    /**
     * @param string $Content
     *
     * @return string
     */
    private function getCombinedStyleTag($Content)
    {

        if (empty($Content)) {
            return '';
        } else {
            return '<style type="text/css">' . $Content . '</style>';
        }
    }

    /**
     * @param array $FileList
     *
     * @return string
     */
    private function parseCombinedStyle($FileList)
    {

        $Result = '';
        array_walk($FileList, function ($Location) use (&$Result) {

            $Path = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . $Location);
            if ($Path) {
                $Content = $this->compactStyle(file_get_contents($Path));
                preg_match_all('!url\(([^\)]*?)\)!is', $Content, $Match);
                if (!empty($Match[0])) {
                    array_walk($Match[0], function ($Item, $Index) use ($Match, $Path, &$Content) {

                        $Match[1][$Index] = trim($Match[1][$Index], ' \'"');
                        if (
                            false === strpos($Item, 'http')
                            && false === strpos($Item, 'data:')
                        ) {
                            $Directory = dirname($Path);
                            $File = $Match[1][$Index];
                            if (false !== strpos($File, '?')) {
                                $Parts = explode('?', $Match[1][$Index]);
                                $Location = realpath($Directory . DIRECTORY_SEPARATOR . array_shift($Parts));
                                if (!empty($Parts)) {
                                    $Parts = '?' . implode('?', $Parts);
                                }
                            } elseif (false !== strpos($File, '#')) {
                                $Parts = explode('#', $Match[1][$Index]);
                                $Location = realpath($Directory . DIRECTORY_SEPARATOR . array_shift($Parts));
                                if (!empty($Parts)) {
                                    $Parts = '#' . implode('#', $Parts);
                                }
                            } else {
                                $Location = realpath($Directory . DIRECTORY_SEPARATOR . $File);
                                $Parts = '';
                            }
                            if ($Location) {
                                $Target = preg_replace('!' . preg_quote($_SERVER['DOCUMENT_ROOT'], '!') . '!is', '',
                                        $Location) . $Parts;
                                $Request = new Request();
                                $Replacement = $Request->getSymfonyRequest()->getUriForPath($Target);
                                $Content = str_replace($Match[0][$Index], "url('" . $Replacement . "')", $Match[0][$Index],
                                    $Content);
                            }
                        }
                    });
                }
                $Result .= "\n\n" . $Content;
            } else {
                $this->getLogger(new ErrorLogger())->addLog('Style not found ' . $Location);
            }
        });
        return $Result;
    }

    /**
     * @param string $Content
     *
     * @return string
     */
    private function compactStyle($Content)
    {

        /* remove comments */
        $Content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $Content);
        /* remove tabs, spaces, newlines, etc. */
        $Content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $Content);

        return $Content;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $Key = serialize( self::$CombinedList + self::$SourceList + self::$AdditionalList );
        $Cache = $this->getCache( new APCuHandler() );
        if( !($Result = $Cache->getValue( $Key, __METHOD__ )) ) {

            $Content = $this->parseCombinedStyle(self::$CombinedList);
            $StyleList = array_merge(self::$SourceList, self::$AdditionalList);
            array_walk($StyleList, function (&$Location) {

                $RealPath = FileSystem::getFileLoader($Location)->getRealPath();
                if (!empty($RealPath)) {
                    $cTag = '?cTAG-' . hash_file('md5', $RealPath);
                } else {
                    $cTag = '?cTAG-' . 'MISS-' . time();
                }

                $Location = '<link rel="stylesheet" href="' . $Location . $cTag . '">';
            });
            array_unshift($StyleList, $this->getCombinedStyleTag($Content));
            $Result = implode("\n", $StyleList);

            $Cache->setValue( $Key, $Result, 0, __METHOD__);
        }
        return $Result;
    }
}
