<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 05.12.2017
 * Time: 11:50
 */

namespace SPHERE\Application\Api\Import\BasicData;


use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductManager;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Repository\Title;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;

class ProductManagerTable extends Extension implements IApiInterface
{
    use ApiTrait;

    /**
     * @param AbstractReceiver $Receiver
     * @return Pipeline
     */
    public static function pipelineProductManagerTable() {
        $ServerEmitter = new ServerEmitter( self::TableBlockReceiver(), self::getEndpoint() );
        $ServerEmitter->setGetPayload(array(
            self::API_TARGET => 'showProductManagerTable'
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

    public function showProductManagerTable() {

        $EntityProductManager = DataWareHouse::useService()->getProductManagerAll();
        $ProductManagerData = array();

        /** @var TblReporting_ProductManager $Row */
        foreach($EntityProductManager AS $Row) {

            $EntityMarktingCodeProductManager = DataWareHouse::useService()->getProductManagerMarketingCodeByProductManager( $Row );
            if(!$EntityMarktingCodeProductManager) {
                $Option = (new Standard('löschen', self::getEndpoint(), null, array( 'ProductManagerId' => $Row->getId()) ))->ajaxPipelineOnClick( ProductManagerDelete::pipelineProductManagerDelete(  $Row->getId() ) );
            }
            else {
                $Option = new \SPHERE\Common\Frontend\Icon\Repository\Warning().new \SPHERE\Common\Frontend\Text\Repository\Warning('&nbsp;&nbsp;&nbsp;Marketingcode vorhanden, löschen nicht erlaubt!');
            }

            $ProductManagerData[] = array(
                'Alias' => $Row->getNumber(),
                'Name' => $Row->getName(),
                'PositionId' => $Option
            );
        }

        $DataTable = new Table($ProductManagerData, new Title('&nbsp;'), array('Alias' => 'Alias', 'Name' => 'Produktmanager', 'PositionId' => 'Option'));

        return $DataTable;
    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('showProductManagerTable');

        return $Dispatcher->callMethod($Method);
    }

//    private function WalkE1( &$Row ) {
//        if( is_array( $Row ) ) {
//            array_walk( $Row, array( 'SPHERE\Application\Api\Reporting\Controlling\DirectSearch\CompetitionTable', 'WalkE1' ) );
//        } else {
//            $Row = (!$this->detectUTF8($Row))?utf8_encode($Row):$Row;
//        }
//    }
//
//    private function detectUTF8( $Value ) {
//        return preg_match('%(?:
//           [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
//           |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
//           |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
//           |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
//           |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
//           |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
//           |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
//           )+%xs', $Value);
//    }
}