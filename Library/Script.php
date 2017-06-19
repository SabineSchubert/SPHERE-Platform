<?php
namespace SPHERE\Library;

use SPHERE\Common\Frontend\Icon\Repository\ChevronRight;
use SPHERE\Common\Frontend\Icon\Repository\CogWheels;
use SPHERE\Common\Frontend\Icon\Repository\FolderClosed;
use SPHERE\Common\Frontend\Icon\Repository\Tag;
use SPHERE\Common\Frontend\Layout\Repository\Listing;
use SPHERE\Common\Frontend\Table\Repository\Title;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Danger;
use SPHERE\Common\Frontend\Text\Repository\Info;
use SPHERE\Common\Frontend\Text\Repository\Muted;
use SPHERE\Common\Frontend\Text\Repository\Small;
use SPHERE\Common\Frontend\Text\Repository\Success;
use SPHERE\Common\Frontend\Text\Repository\Warning;
use SPHERE\Library\Script\Library;

/**
 * Class Script
 *
 * @package SPHERE\Library
 */
class Script
{

    /** @var array $Register */
    private static $Register = array();
    /** @var null|Library $Library */
    private $Library = null;

    /**
     * Script constructor.
     *
     * @param string|null $Library
     * @param string|null $Version
     * @return null|Script
     * @throws \Exception
     */
    public function __construct($Library, $Version = null)
    {
        if (empty(self::$Register)) {
            $this->setupRegister();
        }
        if ($Library !== null) {
            if (!($LibraryDefinition = $this->getLibraryVersion($Library, $Version))) {
                if (!($LibraryDefinition = $this->getLibraryLatest($Library))) {
                    throw new \Exception('Library ' . $Library . ' (' . $Version . ') does not exist');
                }
            }
            $this->setLibrary($LibraryDefinition);
        }
    }

    /**
     * Register All Available Libraries
     */
    private function setupRegister()
    {
        $Location = '/Library/Script/Repository';

        $this->addLibrary(new Library('jQuery', '1.11.3',
            $Location . '/jQuery/1.11.3/jquery-1.11.3.min.js',
            "'undefined' !== typeof jQuery"
        ));

        $Location = '/Library';

        $this->addLibrary(new Library('jQuery.deparam', '1.3pre',
            $Location . '/jQuery.BBQ/1.3pre/jQuery.deparam.js',
            "'undefined' !== typeof jQuery.deparam"
        ));

        $this->addLibrary(new Library('jQuery.Ui', '1.11.4',
            $Location . '/jQuery.Ui/1.11.4/jquery-ui.min.js',
            "'undefined' !== typeof jQuery.ui"
        ));
        $this->addLibrary(new Library('jQuery.iCheck', '1.0.2',
            $Location . '/jQuery.iCheck/1.0.2/icheck.min.js',
            "'undefined' !== typeof jQuery.fn.iCheck"
        ));
        $this->addLibrary(new Library('Moment.Js', '2.8.4',
            $Location . '/Moment.Js/2.8.4/min/moment-with-locales.min.js',
            "'undefined' !== typeof moment"
        ));
        $this->addLibrary(new Library('List.Js', '1.1.1',
            $Location . '/List.Js/1.1.1/dist/list.js',
            "'undefined' !== typeof List"
        ));
        $this->addLibrary(new Library('List.Js', '1.5.0',
            $Location . '/List.Js/1.5.0/dist/list.js',
            "'undefined' !== typeof List"
        ));
        $this->addLibrary(new Library('Bootstrap', '3.3.7',
            $Location . '/Bootstrap/3.3.7/dist/js/bootstrap.min.js',
            "'function' === typeof jQuery().emulateTransitionEnd"
        ));
        $this->addLibrary(new Library('Bootstrap', '3.3.5',
            $Location . '/Bootstrap/3.3.5/dist/js/bootstrap.min.js',
            "'function' === typeof jQuery().emulateTransitionEnd"
        ));
        $this->addLibrary(new Library('Tether', '1.4.0',
            $Location . '/Tether/1.4.0/dist/js/tether.min.js',
            "'function' === typeof Tether"
        ));
        $this->addLibrary(new Library('Bootstrap', '4.0.0-Alpha-6',
            $Location . '/Bootstrap/4.0.0-a6/dist/js/bootstrap.min.js',
            "'function' === typeof jQuery().emulateTransitionEnd"
        ));
        $this->addLibrary(new Library('jQuery.Selecter', '3.2.4',
            $Location . '/jQuery.Selecter/3.2.4/jquery.fs.selecter.min.js',
            "'undefined' !== typeof jQuery.fn.selecter"
        ));
        $this->addLibrary(new Library('jQuery.Stepper', '3.0.8',
            $Location . '/jQuery.Stepper/3.0.8/jquery.fs.stepper.min.js',
            "'undefined' !== typeof jQuery.fn.stepper"
        ));
        $this->addLibrary(new Library('jQuery.CountDown', '2.0.5',
            $Location . '/jQuery.CountDown/2.0.5/dist/jquery.countdown.min.js',
            "'undefined' !== typeof jQuery.fn.countdown"
        ));
        $this->addLibrary(new Library('jQuery.Sisyphus', '1.1.2',
            $Location . '/jQuery.Sisyphus/1.1.2/sisyphus.min.js',
            "'undefined' !== typeof jQuery.fn.sisyphus"
        ));
        $this->addLibrary(new Library('jQuery.CheckBox', '1.0.2',
            $Location . '/jQuery.iCheck/1.0.2/icheck.min.js',
            "'undefined' !== typeof jQuery.fn.iCheck"
        ));
        $this->addLibrary(new Library('jQuery.StorageApi', '1.7.4',
            $Location . '/jQuery.StorageApi/1.7.4/jquery.storageapi.min.js',
            "'undefined' !== typeof jQuery.localStorage"
        ));
        $this->addLibrary(new Library('jQuery.Gridster', '0.6.10',
            $Location . '/jQuery.Gridster/0.6.10/dist/jquery.gridster.with-extras.min.js',
            "'undefined' !== typeof jQuery.fn.gridster"
        ));
        $this->addLibrary(new Library('jQuery.Mask', '3.1.63',
            $Location . '/jQuery.InputMask/3.1.63/dist/jquery.inputmask.bundle.min.js',
            "'undefined' !== typeof jQuery.fn.inputmask"
        ));
        $this->addLibrary(new Library('jQuery.DataTable', '1.10.12',
            $Location . '/DataTables/DataTables-1.10.12/js/jquery.dataTables.min.js',
            "'undefined' !== typeof jQuery.fn.DataTable"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.Bootstrap', '1.10.12',
            $Location . '/DataTables/DataTables-1.10.12/js/dataTables.bootstrap.min.js',
            "'undefined' !== typeof jQuery.fn.DataTable.ext.renderer.pageButton.bootstrap"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.Responsive', '2.1.0',
            $Location . '/DataTables/Responsive-2.1.0/js/dataTables.responsive.min.js',
            "'undefined' !== typeof jQuery.fn.DataTable.Responsive"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.RowReorder', '1.1.2',
            $Location . '/DataTables/RowReorder-1.1.2/js/dataTables.rowReorder.min.js',
            "'undefined' !== typeof jQuery.fn.DataTable.RowReorder"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.FixedHeader','3.1.2',
            $Location . '/DataTables/FixedHeader-3.1.2/js/dataTables.fixedHeader.min.js',
            "'undefined' !== typeof jQuery.fn.DataTable.FixedHeader"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.Buttons', '1.2.2',
            $Location . '/DataTables/Buttons-1.2.2/js/dataTables.buttons.min.js',
            "'undefined' !== typeof jQuery.fn.DataTable.Buttons"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.Buttons.Bootstrap', '1.2.2',
            $Location . '/DataTables/Buttons-1.2.2/js/buttons.bootstrap.min.js',
            "'dt-buttons btn-group' == jQuery.fn.dataTable.Buttons.defaults.dom.container.className"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.Buttons.ColVis', '1.2.2',
            $Location . '/DataTables/Buttons-1.2.2/js/buttons.colVis.min.js',
            "'undefined' !== typeof jQuery.fn.dataTableExt.buttons.colvis"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.Buttons.HtmlExport', '1.2.2',
            $Location . '/DataTables/Buttons-1.2.2/js/buttons.html5.min.js',
            "'undefined' !== typeof jQuery.fn.dataTable.ext.buttons.excelHtml5"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.Buttons.FlashExport', '1.2.2',
            $Location . '/DataTables/Buttons-1.2.2/js/buttons.flash.min.js',
            "'undefined' !== typeof jQuery.fn.dataTable.ext.buttons.excelFlash"
        ));

        $this->addLibrary(new Library('jQuery.DataTable.Plugin.Sorting.DateTime', '1.10.7',
            $Location . '/jQuery.DataTables.Plugins/1.10.7/sorting/date-de.js',
            "'undefined' !== typeof jQuery.fn.dataTable.ext.type.order['de_datetime-asc']"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.Plugin.Sorting.GermanString', '1.10.7',
            $Location . '/jQuery.DataTables.Plugins/1.10.7/sorting/german-string.js',
            "'undefined' !== typeof jQuery.fn.dataTable.ext.type.order['german-string-asc']"
        ));
        $this->addLibrary(new Library('jQuery.DataTable.Plugin.Sorting.Natural', '1.10.7',
            $Location . '/jQuery.DataTables.Plugins/1.10.7/sorting/natural.js',
            "'undefined' !== typeof jQuery.fn.dataTable.ext.type.order['natural-asc']"
        ));

        $this->addLibrary(new Library('Bootstrap.DatetimePicker', '4.14.30',
            $Location . '/Bootstrap.DateTimePicker/4.14.30/build/js/bootstrap-datetimepicker.min.js',
            "'undefined' !== typeof jQuery.fn.datetimepicker"
        ));
        $this->addLibrary(new Library('Bootstrap.FileInput', '4.1.6',
            $Location . '/Bootstrap.FileInput/4.1.6/js/fileinput.min.js',
            "'undefined' !== typeof jQuery.fn.fileinput"
        ));
        $this->addLibrary(new Library('Bootstrap.Select', '1.6.4',
            $Location . '/Bootstrap.Select/1.6.4/dist/js/bootstrap-select.min.js',
            "'undefined' !== typeof jQuery.fn.selectpicker"
        ));
        $this->addLibrary(new Library('Bootstrap.Notify', '3.1.3',
            $Location . '/Bootstrap.Notify/3.1.3/dist/bootstrap-notify.min.js',
            "'undefined' !== typeof jQuery.notify"
        ));
        $this->addLibrary(new Library('Bootstrap.Validator', '0.11.x',
            $Location . '/Bootstrap.Validator/master-0.11.x/dist/validator.min.js',
            "'undefined' !== typeof jQuery.fn.validator"
        ));
        $this->addLibrary(new Library('Bootstrap.Validator', '0.11.9',
            $Location . '/Bootstrap.Validator/0.11.9/dist/validator.min.js',
            "'undefined' !== typeof jQuery.fn.validator"
        ));
        $this->addLibrary(new Library('Twitter.Typeahead', '0.11.1',
            $Location . '/Twitter.Typeahead/0.11.1/dist/typeahead.bundle.min.js',
            "'undefined' !== typeof jQuery.fn.typeahead"
        ));
        $this->addLibrary(new Library('MathJax', '2.5.0',
            $Location . '/MathJax/2.5.0/MathJax.js?config=TeX-MML-AM_HTMLorMML-full',
            "'undefined' !== typeof MathJax"
        ));
        $this->addLibrary(new Library('jQuery.Carousel', '0.3.3',
            $Location . '/jQuery.jCarousel/0.3.3/dist/jquery.jcarousel.min.js',
            "'undefined' !== typeof jQuery.fn.jcarousel"
        ));
        $this->addLibrary(new Library('jQuery.FlowPlayer', '6.0.3',
            $Location . '/jQuery.FlowPlayer/6.0.3/flowplayer.min.js',
            "'undefined' !== typeof jQuery.fn.flowplayer"
        ));
        $this->addLibrary(new Library('jQuery.DetectElementResize', '0.5.3',
            $Location . '/DetectElementResize/0.5.3/jquery.resize.js',
            "'undefined' !== typeof jQuery.fn.removeResize"
        ));
        $this->addLibrary(new Library('jQuery.Select2', '4.0.3',
            $Location . '/jQuery.Select2/4.0.3/dist/js/select2.full.min.js',
            "'undefined' !== typeof jQuery.fn.select2"
        ));

        $this->addLibrary(new Library('Highlight.js', '8.8.0',
            $Location . '/Highlight.js/8.8.0/highlight.pack.js',
            "'undefined' !== typeof hljs"
        ));
        $this->addLibrary(new Library('Highlight.js', '9.10.0',
            $Location . '/Highlight.js/9.10.0/highlight.pack.js',
            "'undefined' !== typeof hljs"
        ));

        $this->addLibrary(new Library('Raphael.js', '2.2.0',
            '/Library/Raphael.Js/2.2.0/raphael.min.js',
            "'undefined' !== typeof Raphael"
        ));
        $this->addLibrary(new Library( 'Morris.js', '0.5.1',
            $Location . '/Morris.Js/0.5.1/morris.min.js',
            "'undefined' !== typeof Morris"
        ));
        $this->addLibrary(new Library( 'Chart.js', '2.5.0',
            $Location . '/Chart.js/2.5.0/Chart.bundle.min.js',
            "'undefined' !== typeof Chart"
        ));
        $this->addLibrary(new Library( 'D3.js', '4.7.3',
            $Location . '/D3/4.7.3/d3.min.js',
            "'undefined' !== typeof d3"
        ));

        $this->addLibrary(new Library('Bootbox.js', '4.4.0',
            $Location . '/Bootbox.js/4.4.0/js/bootbox.min.js',
            "'undefined' !== typeof bootbox"
        ));
        $this->addLibrary(new Library('Slick', '1.6.0',
            $Location . '/Slick/1.6.0/slick/slick.min.js',
            "'undefined' !== typeof jQuery.fn.slick"
        ));

        $this->addLibrary(new Library('Baron.js', '2.2.9',
            $Location . '/Baron.js/2.2.9/baron.min.js',
            "'undefined' !== typeof jQuery.fn.baron"
        ));

        $this->addLibrary(new Library('JsTimeZoneDetect', '1.0.6',
            $Location . '/jQuery.TimeZoneDetect/1.0.6/dist/jstz.min.js',
            "'undefined' !== typeof jstz"
        ));
        $this->addLibrary(new Library('jQuery.Cookie', '2.1.4',
            $Location . '/jQuery.Cookie/2.1.4/src/js.cookie.js',
            "'undefined' !== typeof Cookies"
        ));
        $this->addLibrary(new Library('Bootstrap.Slider', '9.8.0',
            $Location . '/Bootstrap.Slider/9.8.0/dist/bootstrap-slider.min.js',
            "'undefined' !== typeof jQuery.fn.bootstrapSlider"
        ));
        $this->addLibrary(new Library('Bootstrap.MagicSuggest', '2.1.4',
            $Location . '/Bootstrap.MagicSuggest/2.1.4/magicsuggest.js',
            "'undefined' !== typeof jQuery.fn.magicSuggest"
        ));
        $this->addLibrary(new Library('Bootstrap.TokenField', '0.12.1',
            $Location . '/Bootstrap.TokenField/0.12.1/dist/bootstrap-tokenfield.min.js',
            "'undefined' !== typeof jQuery.fn.tokenfield"
        ));
        $this->addLibrary(new Library('Bootstrap.TokenField', 'master',
            $Location . '/Bootstrap.TokenField/master/dist/bootstrap-tokenfield.min.js',
            "'undefined' !== typeof jQuery.fn.tokenfield"
        ));
    }

    /**
     * @param Library $Library
     * @throws \Exception
     */
    private function addLibrary(Library $Library)
    {
        if (!is_array(self::$Register)) {
            self::$Register = array();
        }
        if (!$this->existsLibrary($Library->getName())) {
            self::$Register[$Library->getName()] = array();
        }
        if (!$this->existsVersion($Library->getName(), $Library->getVersion())) {
            if (file_exists(getcwd() . current(explode('?', $Library->getSource())))) {
                self::$Register[$Library->getName()][$Library->getVersion()] = $Library;
                ksort(self::$Register[$Library->getName()], SORT_NATURAL);
                ksort(self::$Register, SORT_NATURAL);
            } else {
                throw new \Exception('Library-Source ' . getcwd() . $Library->getSource() . ' (' . $Library->getVersion() . ') does not exist');
            }
        }
    }

    /**
     * @param string $Name
     * @return bool
     */
    private function existsLibrary($Name)
    {
        return isset(self::$Register[$Name]);
    }

    /**
     * @param string $Name
     * @param string $Version
     * @return bool
     */
    private function existsVersion($Name, $Version)
    {
        if ($this->existsLibrary($Name)) {
            return isset(self::$Register[$Name][$Version]);
        }
        return false;
    }

    /**
     * @param string $Name
     * @param string $Version
     *
     * @return null|Library
     */
    private function getLibraryVersion($Name, $Version)
    {
        if ($this->existsVersion($Name, $Version)) {
            return self::$Register[$Name][$Version];
        }
        return null;
    }

    /**
     * @param string $Name
     *
     * @return null|Library
     */
    private function getLibraryLatest($Name)
    {
        if ($this->existsLibrary($Name)) {
            $VersionList = array_keys(self::$Register[$Name]);
            sort($VersionList, SORT_NATURAL);
            $Latest = end($VersionList);
            return self::$Register[$Name][$Latest];
        }
        return null;
    }

    /**
     * @param null|Library $Library
     */
    private function setLibrary($Library)
    {
        $this->Library = $Library;
    }

    /**
     * @return null|Library
     */
    public function getLibrary()
    {
        return $this->Library;
    }

    /**
     * @return Table
     */
    public function getShow()
    {
        $ShowList = array();
        foreach (self::$Register as $Name => $VersionList) {
            $LibraryList = '';
            /**
             * @var string $Version
             * @var Library $Library
             */
            $LibraryListContent = array();
            foreach ($VersionList as $Version => $Library) {
                $LibraryListContent[] = new Info(new Tag() . ' ' . $Library->getVersion());
                $LibraryListContent[] = new Small(new Muted(new FolderClosed() . ' ' . $Library->getSource()));
            }
            $LibraryList .= new Listing( $LibraryListContent );

            $Name = explode('.', $Name);
            foreach ($Name as $Index => $Part) {
                switch ($Index) {
                    case 0:
                        $Name[$Index] = new Success($Part);
                        break;
                    case 1:
                        $Name[$Index] = new Info($Part);
                        break;
                    case 2:
                        $Name[$Index] = new Danger($Part);
                        break;
                    case 3:
                        $Name[$Index] = new Warning($Part);
                        break;
                    case 4:
                        $Name[$Index] = new Muted($Part);
                        break;
                }
            }

            $ShowList[] = array(
                'Name' => new Listing(array(
                    new Muted(new CogWheels()) . '&nbsp;&nbsp;' . implode(' ' . new Muted(new Small(new ChevronRight())) . ' ',
                        $Name)
                )),
                'Available' => $LibraryList
            );
        }

        return new Table($ShowList, new Title('Script Library', 'Content'), array(), false, true);
    }
}
