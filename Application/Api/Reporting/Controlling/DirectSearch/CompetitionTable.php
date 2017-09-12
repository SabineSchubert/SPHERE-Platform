<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 06.09.2017
 * Time: 15:36
 */

namespace SPHERE\Application\Api\Reporting\Controlling\DirectSearch;


use Doctrine\DBAL\Types\DecimalType;
use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\Competition\DataWareHouse\DataWareHouse;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Utility\Translation\LocaleTrait;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class CompetitionTable extends Extension implements IApiInterface
{
    use ApiTrait;
    use LocaleTrait;

    /**
     * @param AbstractReceiver $Receiver
     * @param string $PartNumber
     * @return Pipeline
     */
    public static function pipelineCompetitionTable( $PartNumber ) {
        $ServerEmitter = new ServerEmitter( self::TableBlockReceiver(), CompetitionTable::getEndpoint() );
        $ServerEmitter->setGetPayload(array(
            CompetitionTable::API_TARGET => 'showCompetitionTable',
            'PartNumber' => $PartNumber
        ));

        $ServerEmitter->setLoadingMessage('Daten werden geladen','bitte warten');

        $Pipeline = new Pipeline(false);
        $Pipeline->appendEmitter($ServerEmitter);

        return $Pipeline;
    }

    /**
     * @param null $Content
     * @return BlockReceiver
     */
    public static function TableBlockReceiver($Content = null) {
        return (new BlockReceiver($Content))->setIdentifier('BlockReceiver');
    }

    public function showCompetitionTable( $PartNumber ) {

        $SearchData = DataWareHouse::useService()->getCompetitionDirectSearchByPartNumber( $PartNumber );
//        Debugger::screenDump($SearchData);

        $ReplaceArray = array(
            'Competitor' => 'Wettbewerber',
            'Manufacturer' => 'Hersteller',
            'CreationDate' => 'Zeitraum',
            'Data_PriceNet' => 'NLP',
            'Data_PriceGross' => 'BLP',
            'Data_Discount' => 'Rabatt',
            'DistributorOrCustomer' => 'WV / EA',
            'Comment' => 'Kommentar',
            'RetailNumber' => 'VF',
            'PositionId' => 'Option'
        );

        //Definition Spaltenkopf
        if( count($SearchData) > 0 ) {
             array_walk( $SearchData, function( &$Row, $Key, $PartNumber ) {

                if( isset($Row['Data_PriceNet']) ) {
                    $Row['Data_PriceNet'] = new PullRight( $this->doLocalize($Row['Data_PriceNet'])->getCurrency() );
                }
                if( isset($Row['Data_PriceGross']) ) {
                    $Row['Data_PriceGross'] = new PullRight( $this->doLocalize($Row['Data_PriceGross'])->getCurrency() );
                }
                if( isset($Row['Data_Discount']) ) {
                    $Row['Data_Discount'] = new PullRight( $this->doLocalize($Row['Data_Discount'])->getCurrency() );
                }
                if( isset($Row['Comment']) ) {
                    $Row['Comment'] = utf8_decode($Row['Comment']);
                }
                if( isset($Row['Manufacturer']) ) {
                    $Row['Manufacturer'] = utf8_decode($Row['Manufacturer']);
                }

                $Row['PositionId'] = (new Standard('löschen', self::getEndpoint(), null, array( 'CompetitionId' => $Row['PositionId']) ))->ajaxPipelineOnClick( CompetitionPositionDelete::pipelineCompetitionPositionDelete(  $Row['PositionId'], $PartNumber ) );
             }, $PartNumber );

            $Keys = array_keys($SearchData[0]);
            $TableHead = array_combine( $Keys, str_replace( array_keys( $ReplaceArray ) , $ReplaceArray, $Keys) );

            return (new Table(
                $SearchData, null, $TableHead, array(
                    "order" => [], //Initial Sortierung
                    "columnDefs" => array( //Definition der Spalten
                        array(
                            "orderable" => false, //Sortierung aus
                            "targets" => 2 //Spalte 2 (Zeitraum)
                        )
                    ),
                   //"sort" => false, //Deaktivierung Sortierung aller Spalten
                   "paging" => true, //Deaktivieren Blättern
                   "responsive" => false,
                )
            ));
        }
        else {
            return new Warning('Es sind keine Datensätze vorhanden.');
        }
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('showCompetitionTable');

        return $Dispatcher->callMethod($Method);
    }
}