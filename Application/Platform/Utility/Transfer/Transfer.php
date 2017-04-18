<?php

namespace SPHERE\Application\Platform\Utility\Transfer;

use MOC\V\Component\Document\Component\Bridge\Repository\DomPdf;
use MOC\V\Component\Document\Document;
use MOC\V\Component\Template\Template;
use MOC\V\Core\FileSystem\FileSystem;
use SPHERE\Application\AppTrait;
use SPHERE\Application\IModuleInterface;
use SPHERE\Application\IServiceInterface;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\FileUpload;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Transfer as TransferIcon;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Repository\Debugger;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Transfer extends AbstractConverter implements IModuleInterface
{
    use AppTrait;

    public static function registerModule()
    {
        self::createModule(__NAMESPACE__, __CLASS__, 'frontendDashboard', 'Transfer-Converter', new TransferIcon());
    }

    /**
     * @return IServiceInterface
     */
    public static function useService()
    {
        // TODO: Implement useService() method.
    }

    /**
     * @return IFrontendInterface
     */
    public static function useFrontend()
    {
        // TODO: Implement useFrontend() method.
    }

    private $TestFilePayload = array();

    /**
     * @param array $Row
     *
     * @return mixed|void
     */
    public function runConvert($Row)
    {
        $this->TestFilePayload[] = array(
            'Artikelnummer' => $Row['A']['A'],
            'ArtikelBeschreibung' => $Row['B']['B']
        );
    }

    private function setFieldPointer() {
        for ($Column = 'A'; $Column !== 'ZZ'; $Column++){
            $this->setPointer(new FieldPointer($Column, $Column));
        }
    }

    private function getPdfImage($Location)
    {

        if (!defined("DOMPDF_ENABLE_REMOTE")) {
            define("DOMPDF_ENABLE_REMOTE", true);
        }

        $PathBase = $this->getRequest()->getPathBase();
        if (empty( $PathBase )) {
            $PathBase = 'http://'.$_SERVER['SERVER_NAME'];
        }
        $Calc = $PathBase.'/'.trim($Location, '/\\');

//        var_dump( DOMPDF_ENABLE_REMOTE );

//        Debugger::screenDump( $Calc );

        return '<img src="'.$Calc.'" style="width: 100px !important; height: 100px !important;"/>';

    }

    public function frontendDashboard($Transfer = null)
    {
        $Stage = new Stage('Transfer-Converter');

        ini_set('memory_limit','-1');
        set_time_limit(3600);

        $Result = array();
        if (null !== $Transfer) {

            if( !$Transfer['File'] instanceof UploadedFile ) {
                $Transfer = null;
            } else {

                $File = new FilePointer($Transfer['File']->getClientOriginalExtension(), 'TransferTestFile');
                $File->setFileContent(file_get_contents($Transfer['File']->getRealPath()));
                $File->saveFile();

//                $File = new FilePointer('pdf', 'PDFCase');
//                $File->saveFile();

//                $Bild = $this->getPdfImage( '/Common/Style/Resource/Teaser/00-mercedes-benz-design-aesthetics-a-1280-686-848x454.jpg' );

//                /** @var DomPdf $Document */
//                $Document = Document::getDocument( $File->getRealPath() );
//                $Document->setContent( Template::getTwigTemplateString(':) {% if(1>0) %} ;) {% endif %} '.$Bild  ) );
//                $Document->saveFile();

//                $File->loadFile();

//                print FileSystem::getDownload( $File->getRealPath() );
//                Debugger::screenDump( $File->getFileContent() );
//                exit();

//                Debugger::screenDump( $File );

//                $this->loadFile($File->getRealPath());
//                $this->addSanitizer(array($this, 'sanitizeFullTrim'));
//                $this->setFieldPointer();
//                $this->scanFile(0,1);
//
                $Header = array();
//                foreach ( $this->TestFilePayload as $Row => $Payload ) {
//                    foreach ( $Payload as $Column => $Content ) {
//                        $Header[$Column] = $Content[$Column].' ('.$Column.')';
//                    }
//                }
//                $this->getDebugger()->screenDump( $this->TestFilePayload );
                $this->TestFilePayload = array();

                $this->loadFile($File->getRealPath());
                $this->addSanitizer(array($this, 'sanitizeFullTrim'));
//                $this->setSanitizer(new FieldSanitizer( 'F', 'F',array($this, 'sanitizeDateTime')));
//                $this->setSanitizer(new FieldSanitizer( 'W', 'W',array($this, 'sanitizeDateTime')));
                $this->setFieldPointer();
                $this->scanFile(1,10);

                Debugger::screenDump( $this->TestFilePayload );

                foreach ( $this->TestFilePayload as $Row => $Payload ) {
                    $Payload['Artikelnummer'];
                    $Payload['ArtikelBeschreibung'];
                }


//                foreach ( $this->TestFilePayload as $Row => $Payload ) {
//                    foreach ( $Payload as $Column => $Content ) {
//                        $Result[$Row][$Column] = $Content[$Column];
//                    }
//                }
            }
        }


        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(new LayoutRow(new LayoutColumn(
                    new Form(new FormGroup(new FormRow(
                        new FormColumn(
                            new FileUpload('Transfer[File]',null, null, null, array( ))
                        )
                    )), new Primary('Hochladen'))

                )), new Title( 'Test-Datei' )),
//                new LayoutGroup(new LayoutRow(new LayoutColumn(
//                    ($Transfer
//                        ? new Table($Result,null,$Header,array('responsive' => false), false)
//                        : new Warning('Bitte eine Datei hochladen')
//                    )
//
//                )), new Title( 'Converter-Ergebnis' )),
            ))
        );

        return $Stage;
    }
}