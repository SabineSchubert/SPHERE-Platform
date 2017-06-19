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
use SPHERE\Library\Style\Library;

/**
 * Class Style
 *
 * @package SPHERE\Library
 */
class Style
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
     * @return null|Style
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
        $Location = '/Library';

        $this->addLibrary(new Library('Bootstrap.Reboot', '4.0.0-Alpha-6',
            $Location . '/Bootstrap/4.0.0-a6/dist/css/bootstrap-reboot.css'
        ));
        $this->addLibrary(new Library('Bootstrap.Grid', '4.0.0-Alpha-6',
            $Location . '/Bootstrap/4.0.0-a6/dist/css/bootstrap-grid.css'
        ));
        $this->addLibrary(new Library('Bootstrap', '4.0.0-Alpha-6',
            $Location . '/Bootstrap/4.0.0-a6/dist/css/bootstrap.css'
        ));
        $this->addLibrary(new Library('Bootstrap.Theme', '3.3.7',
            $Location . '/Bootstrap/3.3.7/dist/css/bootstrap-theme.css'
        ));
        $this->addLibrary(new Library('Bootstrap.Theme', '3.3.5',
            $Location . '/Bootstrap/3.3.5/dist/css/bootstrap-theme.css'
        ));
        $this->addLibrary(new Library('Bootstrap', '3.3.7',
            $Location . '/Bootstrap/3.3.7/dist/css/bootstrap.css'
        ));
        $this->addLibrary(new Library('Bootstrap', '3.3.5',
            $Location . '/Bootstrap/3.3.5/dist/css/bootstrap.css'
        ));
        $this->addLibrary(new Library('Bootstrap.Glyphicons.Glyphicons', '1.9.2',
            $Location.'/Bootstrap.Glyphicons/1.9.2/glyphicons/web/html_css/css/glyphicons.css'
        ));
        $this->addLibrary(new Library('Bootstrap.Glyphicons.Halflings', '1.9.2',
            $Location.'/Bootstrap.Glyphicons/1.9.2/glyphicons-halflings/web/html_css/css/glyphicons-halflings.css'
        ));
        $this->addLibrary(new Library('Bootstrap.Glyphicons.Filetypes', '1.9.2',
            $Location.'/Bootstrap.Glyphicons/1.9.2/glyphicons-filetypes/web/html_css/css/glyphicons-filetypes.css'
        ));
        $this->addLibrary(new Library('Bootstrap.Glyphicons.Social', '1.9.2',
            $Location.'/Bootstrap.Glyphicons/1.9.2/glyphicons-social/web/html_css/css/glyphicons-social.css'
        ));
        $this->addLibrary(new Library('Bootstrap.Checkbox', '0.3.3',
            $Location . '/Bootstrap.Checkbox/0.3.3/awesome-bootstrap-checkbox.css'
        ));

        $this->addLibrary(new Library('Foundation.Icons', '3.0',
            $Location . '/Foundation.Icons/3.0/foundation-icons.css'
        ));
        $this->addLibrary(new Library('FontAwesome', '4.7.0',
            $Location . '/FontAwesome/4.7.0/css/font-awesome.min.css'
        ));

        $this->addLibrary(new Library('jQuery.Formstone.Selecter', '3.2.4',
            $Location . '/jQuery.Selecter/3.2.4/jquery.fs.selecter.min.css'
        ));
        $this->addLibrary(new Library('jQuery.Formstone.Stepper', '3.0.8',
            $Location . '/jQuery.Stepper/3.0.8/jquery.fs.stepper.css'
        ));

        $this->addLibrary(new Library('Slick', '1.6.0',
            $Location . '/Slick/1.6.0/slick/slick.css'
        ));
        $this->addLibrary(new Library('Slick.Theme', '1.6.0',
            $Location . '/Slick/1.6.0/slick/slick-theme.css'
        ));

        $this->addLibrary(new Library('Morris.js', '0.5.1',
            $Location . '/Morris.Js/0.5.1/morris.css'
        ));
        $this->addLibrary(new Library('Highlight.js-Darcula', '9.10.0',
            $Location . '/Highlight.js/9.10.0/styles/darcula.css'
        ));

        $this->addLibrary(new Library('jQuery.Select2', '4.0.3',
            $Location . '/jQuery.Select2/4.0.3/dist/css/select2.min.css'
        ));
        $this->addLibrary(new Library('jQuery.Select2.Theme', '0.1.0.9',
            $Location . '/jQuery.Select2.Theme/0.1.0.9/dist/select2-bootstrap.min.css'
        ));
        $this->addLibrary(new Library('Bootstrap.Slider', '9.8.0',
            $Location . '/Bootstrap.Slider/9.8.0/dist/css/bootstrap-slider.min.css'
        ));
        $this->addLibrary(new Library('Bootstrap.MagicSuggest', '2.1.4',
            $Location . '/Bootstrap.MagicSuggest/2.1.4/magicsuggest-min.css'
        ));
        $this->addLibrary(new Library('Bootstrap.TokenField', '0.12.1',
            $Location . '/Bootstrap.TokenField/0.12.1/dist/css/bootstrap-tokenfield.min.css'
        ));
        $this->addLibrary(new Library('Bootstrap.TokenField.TypeAHead', '0.12.1',
            $Location . '/Bootstrap.TokenField/0.12.1/dist/css/tokenfield-typeahead.min.css'
        ));
        $this->addLibrary(new Library('Bootstrap.TokenField', 'master',
            $Location . '/Bootstrap.TokenField/master/dist/css/bootstrap-tokenfield.min.css'
        ));
        $this->addLibrary(new Library('Bootstrap.TokenField.TypeAHead', 'master',
            $Location . '/Bootstrap.TokenField/master/dist/css/tokenfield-typeahead.min.css'
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

        return new Table($ShowList, new Title('Style Library', 'Content'), array(), false, true);
    }
}
