<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 06.09.2017
 * Time: 16:10
 */

namespace SPHERE\Application\Api\Reporting\Controlling\DirectSearch;


use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\Competition\DataWareHouse\DataWareHouse;
use SPHERE\Application\IApiInterface;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\System\Extension\Extension;

class CompetitionPositionDelete extends Extension implements IApiInterface
{
    use ApiTrait;

    /**
     * @param int $CompetitionId
     * @param string $PartNumber
     * @return Pipeline
     */
    public static function pipelineCompetitionPositionDelete( $CompetitionId, $PartNumber ) {
        $ServerEmitter = new ServerEmitter( CompetitionTable::TableBlockReceiver(), CompetitionPositionDelete::getEndpoint() );
        $ServerEmitter->setGetPayload(array(
            CompetitionPositionDelete::API_TARGET => 'deleteCompetitionPosition',
            'CompetitionId' => $CompetitionId,
            'PartNumber' => $PartNumber
        ));

        $Pipeline = new Pipeline(false);
        $Pipeline->appendEmitter($ServerEmitter);

        return $Pipeline;
    }

    /**
     * @param int $CompetitionId
     * @param string $PartNumber
     * @return Pipeline
     */
    public function deleteCompetitionPosition($CompetitionId, $PartNumber) {

        //löschen
        if($CompetitionId) {
            DataWareHouse::useService()->deleteCompetitionPosition( $CompetitionId );
        }

        return CompetitionTable::pipelineCompetitionTable( $PartNumber );
    }

    /**
     * @param null $Content
     * @return BlockReceiver
     */
    public static function BlockReceiver( $Content = null ) {
        return (new BlockReceiver($Content))->setIdentifier('PositionDelete');
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('deleteCompetitionPosition');

        return $Dispatcher->callMethod($Method);
    }
}