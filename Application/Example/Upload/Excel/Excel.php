<?php

namespace SPHERE\Application\Example\Upload\Excel;


use SPHERE\Application\Api\Example\Upload\Upload;
use SPHERE\Application\AppTrait;
use SPHERE\Application\Example\Upload\Excel\Converter\LoadExampleUploadExcel;
use SPHERE\Application\Example\Upload\Excel\Converter\ReadExampleUploadExcel;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Application\Platform\Utility\Transfer\AbstractConverter;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\FileUpload;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\Layout\Repository\ProgressBar;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Database\Link\Identifier;
use SPHERE\System\Extension\Repository\Debugger;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Excel extends AbstractConverter implements IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        self::createModule(__NAMESPACE__, __CLASS__, 'frontendExcel', 'Excel', new Blackboard(), 'Excel');
        self::createModule(__NAMESPACE__.'/Dynamic', __CLASS__, 'frontendExcelDynamic', 'Excel', new Blackboard(), 'Excel');
    }

    /**
     * @return Service
     */
    public static function useService()
    {
        return new Service(new Identifier('Platform', 'Utility', 'Example'),
            __DIR__ . '/Service/Entity', __NAMESPACE__ . '\Service\Entity'
        );
    }

    /**
     * @return IFrontendInterface
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }

    /**
     * @param array $Row
     *
     * @return mixed|void
     */
    public function runConvert($Row)
    {
        // TODO: Implement runConvert() method.
    }

    public function frontendExcel($Transfer = null)
    {
        $Stage = new Stage('Excel', 'Excel');

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

            if( (new LoadExampleUploadExcel( $File->getRealPath() ))->isValid() ) {
                Debugger::screenDump('Valid');

                new ReadExampleUploadExcel( $File->getRealPath() );

            } else {
                Debugger::screenDump('Not Valid');
            }

//            $this->loadFile($File->getRealPath());
        }

        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(
                            new Form(
                                new FormGroup(
                                    new FormRow(
                                        new FormColumn(
                                            new FileUpload('Transfer[File]', null, null, null, array())
                                        )
                                    )
                                ), new Primary('Hochladen')
                            )
                        )
                    ), new Title('Test-Datei')
                ),
//                new LayoutGroup(
//                    new LayoutRow(
//                        new LayoutColumn(
//                            new ProgressBar(0,100,0, 10)
//                        )
//                    ), new Title('Import der Datei...')
//                )
            ))
        );

        return $Stage;
    }

    public function frontendExcelDynamic()
    {
        $Stage = new Stage('Excel', 'Excel');
//
//        $Receiver = Upload::receiverUpload();
//
//        $Form = new Form(
//            new FormGroup(
//                new FormRow(
//                    new FormColumn(
//                        new FileUpload('Transfer[File]', null, null, null, array())
//                    )
//                )
//            ), new Primary('Hochladen')
//        );
//        $Form->ajaxPipelineOnSubmit( Upload::pipelineUploadFile( $Receiver ) );
//
//        $Stage->setContent(
//            new Layout(array(
//                new LayoutGroup(
//                    new LayoutRow(
//                        new LayoutColumn(
//                            $Receiver->initContent( $Form )
//                        )
//                    ), new Title('Test-Datei')
//                )
//            ))
//        );

        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(
                            new Table(array(
                                array( 'Artikel' => '1', 'Option' => (new Standard('+', '#', null, array( 'Id' => 1 )))->ajaxPipelineOnClick( Upload::pipelinePlus() ) ),
                                array( 'Artikel' => '2', 'Option' => (new Standard('+', '#', null, array( 'Id' => 2 )))->ajaxPipelineOnClick( Upload::pipelinePlus() ) ),
                                array( 'Artikel' => '3', 'Option' => (new Standard('+', '#', null, array( 'Id' => 3 )))->ajaxPipelineOnClick( Upload::pipelinePlus() ) )
                            ), null, array( 'Artikel' => 'Artikel', 'Option' => 'Option' ))
                        ,6 ),
                        new LayoutColumn(
                            Upload::receiverBasket( Upload::tableBasket() )
                        , 6),
                    )), new Title('Test-Datei')
                )
            ))
        );

        return $Stage;
    }
}