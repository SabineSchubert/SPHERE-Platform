<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 23.05.2017
 * Time: 10:32
 */

namespace SPHERE\Application\Reporting\DataWareHouse\Sales;


use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Application\Reporting\DataWareHouse\Sales\Converter\LoadImportSales;
use SPHERE\Application\Reporting\DataWareHouse\Sales\Converter\ReadImportSales;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\FileUpload;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Frontend extends Extension
{
    public function frontendImportSales($Transfer = null)
    {
        $Stage = new Stage('Umsatzdaten importieren', '');

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

            if( (new LoadImportSales( $File->getRealPath() ))->isValid() ) {
                Debugger::screenDump('Valid');

                new ReadImportSales( $File->getRealPath() );

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
                    ), new Title('Umsatzdaten importieren')
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
}