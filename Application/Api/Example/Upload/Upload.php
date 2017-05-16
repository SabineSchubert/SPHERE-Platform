<?php

namespace SPHERE\Application\Api\Example\Upload;

use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Application\Platform\Utility\Transfer\AbstractConverter;
use SPHERE\Common\Frontend\Ajax\Emitter\ClientEmitter;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Layout\Repository\Headline;
use SPHERE\Common\Frontend\Layout\Repository\ProgressBar;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Redirect;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Upload extends AbstractConverter implements IApiInterface
{
    use ApiTrait;

    /**
     * @return AbstractReceiver
     */
    public static function receiverUpload()
    {
        return new BlockReceiver();
    }

    /**
     * @param AbstractReceiver $Receiver
     * @return Pipeline
     */
    public static function pipelineUploadFile(AbstractReceiver $Receiver)
    {
        $Pipeline = new Pipeline();

        $Emitter = new ClientEmitter($Receiver,
            new Headline( 'Datei wird hochgeladen', 'Bitte warten ...' )
            .new ProgressBar(0,100,0, 10)
        );
        $Pipeline->appendEmitter($Emitter);


        $Emitter = new ServerEmitter($Receiver, Upload::getEndpoint());
        $Emitter->setGetPayload(array(
            Upload::API_TARGET => 'uploadFile'
        ));
        $Pipeline->appendEmitter($Emitter);


        // TODO: Remove
//        $Emitter = new ClientEmitter($Receiver, new Redirect( '/Example/Upload/Excel/Dynamic' ));
//        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
    }

    public static function pipelineImportFile(AbstractReceiver $Receiver)
    {

    }

    /**
     * @param string $Method
     * @return string
     */
    public function exportApi($Method = '')
    {

        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('uploadFile');
        $Dispatcher->registerMethod('importFile');

        $Dispatcher->registerMethod('tableBasket');

        return $Dispatcher->callMethod($Method);
    }

    public function uploadFile($Transfer = null)
    {

        sleep(2);
        $File = null;

        if ($Transfer) {
            if (!$Transfer['File'] instanceof UploadedFile) {
                $Transfer = null;
            } else {
                $File = new FilePointer($Transfer['File']->getClientOriginalExtension(), 'TransferTestFile');
                $File->setFileContent(file_get_contents($Transfer['File']->getRealPath()));
                $File->saveFile();
            }
        }

        if ($File) {
            $this->loadFile($File->getRealPath());

            $this->scanFile( 0, 2 );
        }

        return $this->ConvertScanResult;
    }

    private $ConvertScanResult = array();

    /**
     * @param array $Row
     *
     * @return mixed|void
     */
    public function runConvert($Row)
    {
        $this->ConvertScanResult[] = $Row;
    }

    public function importFile()
    {

    }


    public static function pipelinePlus()
    {
        $Pipeline = new Pipeline();
        $Emitter = new ServerEmitter( self::receiverBasket(), self::getEndpoint() );
        $Emitter->setPostPayload(array(
            self::API_TARGET => 'tableBasket',
            'Type' => 1
        ));
        $Pipeline->appendEmitter( $Emitter );

        return $Pipeline;
    }

    public static function pipelineMinus()
    {
        $Pipeline = new Pipeline();
        $Emitter = new ServerEmitter( self::receiverBasket(), self::getEndpoint() );
        $Emitter->setPostPayload(array(
            self::API_TARGET => 'tableBasket',
            'Type' => 0
        ));
        $Pipeline->appendEmitter( $Emitter );

        return $Pipeline;
    }

    public static function receiverBasket( $Content = '' )
    {
        return (new BlockReceiver( $Content ))->setIdentifier( 'BasketReceiver' );
    }

    public static function tableBasket( $Id = null, $Type = null ) {

        $Ids = array();


        // Service
        // Plus
        if( $Type == 1) {
            $Ids[$Id] = $Id;
        }
        // Minus
        if( $Type == 0 && isset( $Ids[$Id] ) ) {
            unset( $Ids[$Id] );
        }

        // Select
        $Table = array();
        foreach( $Ids as $IdEntity ) {
            $Table[] = array(
                'Artikel' => $IdEntity, 'Option' => (new Standard('-', '#', null, array( 'Id' => $IdEntity )))->ajaxPipelineOnClick( Upload::pipelineMinus() )
            );
        }

        // Anzeige
        return new Table($Table, null, array( 'Artikel' => 'Artikel', 'Option' => 'Option' ));
    }
}