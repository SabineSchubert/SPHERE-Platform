<?php
namespace SPHERE\Application\Platform\System\Database;

use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer;
use SPHERE\Application\Platform\Gatekeeper\Consumer\Service\Entity\TblConsumer;
use SPHERE\Common\Frontend\Icon\Repository\Flash;
use SPHERE\Common\Frontend\Icon\Repository\Ok;
use SPHERE\Common\Frontend\Icon\Repository\Question;
use SPHERE\Common\Frontend\Icon\Repository\Repeat;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\Icon\Repository\Warning;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\Layout\Repository\Listing;
use SPHERE\Common\Frontend\Layout\Repository\PullClear;
use SPHERE\Common\Frontend\Layout\Repository\PullLeft;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Link\Repository\Primary;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Table\Structure\TableBody;
use SPHERE\Common\Frontend\Table\Structure\TableColumn;
use SPHERE\Common\Frontend\Table\Structure\TableHead;
use SPHERE\Common\Frontend\Table\Structure\TableRow;
use SPHERE\Common\Frontend\Text\ITextInterface;
use SPHERE\Common\Frontend\Text\Repository\Danger;
use SPHERE\Common\Frontend\Text\Repository\Success;
use SPHERE\Common\Main;
use SPHERE\Common\Window\Navigation\Link;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Authenticator\Authenticator;
use SPHERE\System\Authenticator\Type\Get;
use SPHERE\System\Database\Link\Identifier;
use SPHERE\System\Extension\Extension;

/**
 * Class Database
 *
 * @package SPHERE\Application\System\Platform\Database
 */
class Database extends Extension implements IModuleInterface
{

    /** @var array $ServiceRegister */
    private static $ServiceRegister = array();
    /** @var array $SetupRegister */
    private static $SetupRegister = array();

    public static function registerModule()
    {

        /**
         * Register Navigation
         */
        Main::getDisplay()->addModuleNavigation(
            new Link(new Link\Route(__NAMESPACE__), new Link\Name('Datenbank'),
                new Link\Icon(new \SPHERE\Common\Frontend\Icon\Repository\Database()))
        );
        /**
         * Register Route
         */
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__,
                'Database::frontendStatus'
            )
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__ . '/Setup/Simulation',
                __CLASS__ . '::frontendSetup'
            )->setParameterDefault('Simulation', true)
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__ . '/Setup/Execution',
                __CLASS__ . '::frontendSetup'
            )->setParameterDefault('Simulation', false)
        );
        Main::getDispatcher()->registerRoute(
            Main::getDispatcher()->createRoute(__NAMESPACE__ . '/Setup/Upgrade',
                __CLASS__ . '::frontendSetupUpgrade'
            )
        );
    }

    /**
     * @return IServiceInterface
     */
    public static function useService()
    {

    }

    /**
     * @return IFrontendInterface
     */
    public static function useFrontend()
    {

    }

    /**
     * Determines the order of the services that are created in the database if a dependency exists
     *
     * @param $__CLASS__
     */
    public static function registerService($__CLASS__)
    {

        if (!in_array($__CLASS__, self::$ServiceRegister)) {
            array_push(self::$ServiceRegister, trim($__CLASS__, '\\'));
        }
    }

    /**
     * @return Stage
     */
    public function frontendStatus()
    {

        $Stage = new Stage('Database', 'Status');
        $Stage->setMessage('Konfigurierte Datenbank-Verbindungen');
        $this->menuButton($Stage);
        $Configuration = parse_ini_file(__DIR__ . '/../../../../System/Database/Configuration.ini', true);
        $Result = array();
        foreach ((array)$Configuration as $Service => $Parameter) {
            $Service = explode(':', $Service);

            // Force Consumer ?
            if (!isset($Service[4]) && (!isset($Parameter['Consumer']) || $Parameter['Consumer'])) {
                $TblConsumerAll = Consumer::useService()->getConsumerAll();
                /** @var TblConsumer $TblConsumer */
                foreach ((array)$TblConsumerAll as $TblConsumer) {
                    $Connection = null;
                    $Service[4] = $TblConsumer->getAcronym();
                    try {
                        $Connection = new \SPHERE\System\Database\Database(
                            new Identifier(
                                $Service[0],
                                $Service[1],
                                (isset($Service[2]) ? $Service[2] : null),
                                (isset($Service[3]) ? $Service[3] : null),
                                (isset($Service[4]) ? $Service[4] : null)
                            )
                        );
                        $Status = new Success('Verbunden', new Ok());
                    } catch (\Exception $E) {
                        $Status = new Danger('Fehler', new Warning());
                    }
                    $Result[] = $this->statusRow($Status, $Service, $Parameter, $Connection);
                }
            } else {
                $Connection = null;
                try {
                    $Connection = new \SPHERE\System\Database\Database(
                        new Identifier(
                            $Service[0],
                            $Service[1],
                            (isset($Service[2]) ? $Service[2] : null),
                            (isset($Service[3]) ? $Service[3] : null),
                            (isset($Service[4]) ? $Service[4] : null)
                        )
                    );
                    $Status = new Success('Verbunden', new Ok());
                } catch (\Exception $E) {
                    $Status = new Danger('Fehler', new Warning());
                }
                $Result[] = $this->statusRow($Status, $Service, $Parameter, $Connection);
            }
        }

        $Stage->setContent(
            new Table(
                new TableHead(
                    new TableRow(array(
                        new TableColumn('Status'),
                        new TableColumn('Cluster'),
                        new TableColumn('Application'),
                        new TableColumn('Module'),
                        new TableColumn('Service'),
                        new TableColumn('Consumer'),
                        new TableColumn('Driver'),
                        new TableColumn('Server'),
                        new TableColumn('Port'),
                        new TableColumn('Database')
                    ))
                ),
                new TableBody(
                    $Result
                ), null, true
            )
        );

        return $Stage;
    }

    /**
     * @param Stage $Stage
     */
    private function menuButton(Stage $Stage)
    {

        $Stage->addButton(new Primary('Status', new Link\Route(__NAMESPACE__), new Question(),
            array(), 'Datenbankverbindungen'
        ));
        $Stage->addButton(new \SPHERE\Common\Frontend\Link\Repository\Success('Simulation',
            new Link\Route(__NAMESPACE__ . '/Setup/Simulation'), new Search(),
            array(), 'Anzeige von Strukturänderungen'
        ));
        $Stage->addButton(new \SPHERE\Common\Frontend\Link\Repository\Warning('Durchführung',
            new Link\Route(__NAMESPACE__ . '/Setup/Execution'), new Flash(),
            array(), 'Durchführen von Strukturänderungen und einspielen zugehöriger Daten'
        ));
//        $Stage->addButton(new \SPHERE\Common\Frontend\Link\Repository\Danger('Durchführung: Alle Mandanten', new Link\Route(__NAMESPACE__ . '/Setup/Upgrade'),
//            new Flash(),
//            array(), 'Durchführen von Strukturänderungen und einspielen zugehöriger Daten'
//        ));
    }

    /**
     * @param ITextInterface $Status
     * @param array $Service
     * @param array $Parameter
     * @param \SPHERE\System\Database\Database|null $Connection
     *
     * @return TableRow
     */
    private function statusRow(
        ITextInterface $Status,
        $Service,
        $Parameter,
        \SPHERE\System\Database\Database $Connection = null
    ) {

        return new TableRow(array(
            new TableColumn($Status),
            new TableColumn($Service[0]),
            new TableColumn($Service[1]),
            new TableColumn((isset($Service[2]) ? $Service[2] : null)),
            new TableColumn((isset($Service[3]) ? $Service[3] : null)),
            new TableColumn((isset($Service[4]) ? $Service[4] : null)),
            new TableColumn($Parameter['Driver']),
            new TableColumn($Parameter['Host']),
            new TableColumn((isset($Parameter['Port']) ? $Parameter['Port'] : 'Default')),
            new TableColumn(isset($Connection) ? $Connection->getDatabase() : '-NA-')
        ));
    }

    /**
     * @return Stage
     */
    public function frontendSetupUpgrade()
    {

        $Stage = new Stage('Database', 'Setup aller Mandanten');
        $this->menuButton($Stage);

        $TblConsumerAll = Consumer::useService()->getConsumerAll();
        $ConsumerRequestList = array();
        if ($TblConsumerAll) {
            $Authenticator = (new Authenticator(new Get()))->getAuthenticator();

            array_walk($TblConsumerAll,
                function (TblConsumer $TblConsumer) use (&$ConsumerRequestList, $Authenticator) {

                    $ConsumerRequestList[$TblConsumer->getAcronym()] =
                        'https://' . $this->getRequest()->getHost()
                        . '/Api/Platform/Database/Upgrade'
                        . '?' . http_build_query($Authenticator->createSignature(array(
                            'Consumer' => $TblConsumer->getAcronym()
                        ), '/Api/Platform/Database/Upgrade'));

                });

            ksort($ConsumerRequestList);

            $Api = $ConsumerRequestList['DEMO'];
            unset($ConsumerRequestList['DEMO']);
            $ConsumerRequestList['DEMO'] = $Api;
        }

        $Stage->setContent(
            new Title('Mandanten werden aktualisiert...')
            . '<div id="ConsumerProgress" class="progress" style="height: 15px; margin: 0;">
                <div class="progress-bar progress-bar-success" style="width: 0%;"></div>
                <div class="progress-bar progress-bar-info progress-bar-striped active" style="width: 0%;"></div>
                <div class="progress-bar progress-bar-warning" style="width: 100%;"></div>
            </div>'
            . '<div id="ConsumerProtocol" class="small"></div>'
            . '<script language=javascript>
            //noinspection JSUnresolvedFunction
            executeScript(function()
            {
                Client.Use(\'ModAlways\', function()
                {
                    (function($)
                    {
                        \'use strict\';
                        $.fn.ModDatabaseUpgrade = function(
                            options
                        )
                        {

                            // This is the easiest way to have default options.
                            var settings = $.extend({
                                consumer: []
                            }, options);

                            var Size = Object.keys(settings.consumer).length;

                            var getConsumer = function pop(obj) {
                              for (var key in obj) {
                                // Uncomment below to fix prototype problem.
                                //if (!Object.hasOwnProperty.call(obj, key)) continue;
                                var result = obj[key];
                                // If the property can\'t be deleted fail with an error.
                                if (!delete obj[key]) { throw new Error(); }
                                return result;
                              }
                            };

                            var ResultConsumer = [];
                            var ErrorConsumer = false;

                            var Progress = 100 / Size * Object.keys(settings.consumer).length;
                            var Info = 100 / Size;
                            var Bar = jQuery("#ConsumerProgress");
                            Bar.find(".progress-bar-info").css("width",(Info)+"%");
                            Bar.find(".progress-bar-warning").css("width",(Progress-Info)+"%");

                            var runConsumer = function( Api ){
                                Progress = 100 / Size * Object.keys(settings.consumer).length;
                                Info = 100 / Size;

                                Bar.find(".progress-bar-info").css("width",(Info)+"%");

                                if( Api ) {
                                    jQuery.get( Api, function( Result ){

                                        Bar.find(".progress-bar-success").css("width",(100-Progress)+"%").html( (Size-Object.keys(settings.consumer).length)+" / "+Size );
                                        Bar.find(".progress-bar-warning").css("width",(Progress-Info)+"%");

                                        var Consumer = Result.substr(0,Result.indexOf(\' \'));
                                        Result = Result.substr(Result.indexOf(\' \'),Result.length);
                                        jQuery("#ConsumerProtocol").append( Result );

                                        if( -1 == jQuery.inArray( Consumer, ResultConsumer ) ) {
                                            ResultConsumer.push(Consumer);
                                        } else {
                                            ErrorConsumer = true;
                                        }

                                        Api = getConsumer( settings.consumer );
                                        runConsumer( Api );
                                    }, "json" );
                                } else {
                                    if(ErrorConsumer) {
                                        Bar.find(".progress-bar-success").removeClass("progress-bar-success").addClass("progress-bar-danger").html("ERROR")
                                        } else {
                                        Bar.find(".progress-bar-success").html("DONE")
                                        }
                                        Bar.find(".progress-bar-success").removeClass("active");
                                }
                            };

                            var Api = getConsumer( settings.consumer );
                            runConsumer( Api );

                            return this;
                        };

                    }(jQuery));

                    jQuery().ModDatabaseUpgrade({consumer:' . json_encode($ConsumerRequestList) . ' });
                });
            });
        </script>'
        );

        return $Stage;
    }

    /**
     * @param bool $Simulation
     * @param bool $Heal
     *
     * @return Stage
     */
    public function frontendSetup($Simulation = true, $Heal = false)
    {

        $ClassList = get_declared_classes();
        self::$ServiceRegister = array_merge(
            self::$ServiceRegister, array_diff($ClassList, self::$ServiceRegister)
        );

        // Strict Dependencies (Foreign-Constrains)
        array_unshift(self::$ServiceRegister, 'SPHERE\Application\Platform\Gatekeeper\Authorization\Group\Group');
        array_unshift(self::$ServiceRegister, 'SPHERE\Application\Platform\Gatekeeper\Authorization\Account\Account');
        array_unshift(self::$ServiceRegister, 'SPHERE\Application\Platform\Gatekeeper\Authorization\Access\Access');
        array_unshift(self::$ServiceRegister, 'SPHERE\Application\Platform\Gatekeeper\Consumer\Consumer');

        self::$ServiceRegister = array_unique(self::$ServiceRegister);

        $Stage = new Stage('Database', 'Setup');
        if (!$Heal) {
            $this->menuButton($Stage);
        }
        if ($Simulation) {
            $Stage->setMessage('Simulation');
            self::$SetupRegister = array();
            $ClassList = self::$ServiceRegister;
            array_walk($ClassList, function (&$Class) {

                $Inspection = new \ReflectionClass($Class);
                if ($Inspection->isInternal()) {
                    $Class = false;
                } else {
                    if ($Inspection->implementsInterface('\SPHERE\Application\IModuleInterface')) {
                        /** @var IModuleInterface $Class */
                        if (!$Inspection->isAbstract()) {
                            $Class = $Inspection->newInstance();
                            $Class = $Class->useService();
                        }
                        /** @var IServiceInterface $Class */
                        if ($Class instanceof IServiceInterface) {
                            if (!array_key_exists(get_class($Class), self::$SetupRegister)) {
                                $Result = $Class->setupService(true, false);
                                self::$SetupRegister[get_class($Class)] = $Result;
                                $Class = $Result;
                            } else {
                                $Class = new PullClear(new PullLeft(new Repeat()) . self::$SetupRegister[get_class($Class)]);
                            }
                        } else {
                            $Class = false;
                        }
                    } else {
                        $Class = false;
                    }
                }
            });
            $ClassList = array_filter($ClassList);

        } else {
            $Stage->setMessage('Durchführung');
            self::$SetupRegister = array();
            $ClassList = self::$ServiceRegister;
            array_walk($ClassList, function (&$Class) {

                $Inspection = new \ReflectionClass($Class);
                if ($Inspection->isInternal()) {
                    $Class = false;
                } else {
                    if ($Inspection->implementsInterface('\SPHERE\Application\IModuleInterface')) {
                        /** @var IModuleInterface $Class */
                        if (!$Inspection->isAbstract()) {
                            $Class = $Inspection->newInstance();
                            $Class = $Class->useService();
                        }
                        /** @var IServiceInterface $Class */
                        if ($Class instanceof IServiceInterface) {
                            if (!array_key_exists(get_class($Class), self::$SetupRegister)) {
                                $Result = $Class->setupService(false, false);
                                self::$SetupRegister[get_class($Class)] = $Result;
                                $Class = $Result;
                            } else {
                                $Class = new PullClear(new PullLeft(new Repeat()) . self::$SetupRegister[get_class($Class)]);
                            }
                        } else {
                            $Class = false;
                        }
                    } else {
                        $Class = false;
                    }
                }
            });
            $ClassList = array_filter($ClassList);

            self::$SetupRegister = array();
            $DataList = self::$ServiceRegister;
            array_walk($DataList, function (&$Class) {

                $Inspection = new \ReflectionClass($Class);
                if ($Inspection->isInternal()) {
                    $Class = false;
                } else {
                    if ($Inspection->implementsInterface('\SPHERE\Application\IModuleInterface')) {
                        /** @var IModuleInterface $Class */
                        if (!$Inspection->isAbstract()) {
                            $Class = $Inspection->newInstance();
                            $Class = $Class->useService();
                        }
                        /** @var IServiceInterface $Class */
                        if ($Class instanceof IServiceInterface) {
                            if (!array_key_exists(get_class($Class), self::$SetupRegister)) {
                                $Result = $Class->setupService(false, true);
                                self::$SetupRegister[get_class($Class)] = $Result;
                                $Class = $Result;
                            } else {
                                $Class = new PullClear(new PullLeft(new Repeat()) . self::$SetupRegister[get_class($Class)]);
                            }
                        } else {
                            $Class = false;
                        }
                    } else {
                        $Class = false;
                    }
                }
            });
        }

        $Stage->setContent(
            new Listing($ClassList)
        );
        return $Stage;
    }
}
