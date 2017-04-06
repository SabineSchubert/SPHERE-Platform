<?php

namespace SPHERE\Application\Platform\Utility\Transfer;

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
        $this->TestFilePayload[] = $Row;
    }

    public function frontendDashboard($Transfer = null)
    {
        $Stage = new Stage('Transfer-Converter');

        $Result = array();
        if (null !== $Transfer) {

            if( !$Transfer['File'] instanceof UploadedFile ) {
                $Transfer = null;
            } else {

                $File = new FilePointer($Transfer['File']->getClientOriginalExtension(), 'TransferTestFile');
                $File->setFileContent(file_get_contents($Transfer['File']->getRealPath()));
                $File->saveFile();

                $this->loadFile($File->getRealPath());

                // Default (All)
                $this->addSanitizer(array($this, 'sanitizeFullTrim'));

                // Specific (Column)
                foreach( range('A','ZZ') as $Column ) {
                    $this->setPointer(new FieldPointer($Column, $Column));
                }
                $this->scanFile(0);

                foreach ( $this->TestFilePayload as $Row => $Payload ) {
                    foreach ( $Payload as $Column => $Content ) {
                        $Result[$Row][$Column] = $Content[$Column];
                    }
                }
            }
        }


        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(new LayoutRow(new LayoutColumn(
                    new Form(new FormGroup(new FormRow(
                        new FormColumn(
                            new FileUpload('Transfer[File]')
                        )
                    )), new Primary('Hochladen'))

                )), new Title( 'Test-Datei' )),
                new LayoutGroup(new LayoutRow(new LayoutColumn(
                    ($Transfer
                        ? new Table($Result,null,array(),false, true)
                        : new Warning('Bitte eine Datei hochladen')
                    )

                )), new Title( 'Converter-Ergebnis' )),
            ))
        );

        return $Stage;
    }
}