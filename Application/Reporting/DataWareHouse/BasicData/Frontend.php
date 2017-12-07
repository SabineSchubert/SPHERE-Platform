<?php
namespace SPHERE\Application\Reporting\DataWareHouse\BasicData;

use SPHERE\Application\Api\Import\BasicData\ProductManagerTable;
use SPHERE\Application\Api\Reporting\Excel\ExcelDefault;
use SPHERE\Application\Platform\Utility\Storage\FilePointer;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\Converter\LoadImportPmMc;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\Converter\ReadImportPmMc;
use SPHERE\Application\Reporting\DataWareHouse\BasicData\Service\Entity\TblImport_PmMc;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\FileUpload;
use SPHERE\Common\Frontend\Form\Repository\Field\SelectBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Select;
use SPHERE\Common\Frontend\Layout\Repository\Accordion;
use SPHERE\Common\Frontend\Layout\Repository\Badge;
use SPHERE\Common\Frontend\Layout\Repository\Label;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\External;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Frontend\Text\Repository\Center;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;
use SPHERE\System\Extension\Repository\Debugger;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Frontend extends Extension
{
    /**
     * @return Stage
     */
    public function frontendProductManager($Pm = null, $Transfer = null) {
        $Stage = new Stage('Produktmanager');

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

            if( (new LoadImportPmMc( $File->getRealPath() ))->isValid() ) {
//                Debugger::screenDump('Valid');

                new ReadImportPmMc( $File->getRealPath() );

            } else {
//                Debugger::screenDump('Not Valid');
            }

//            $this->loadFile($File->getRealPath());
        }

        if( $Pm['Name'] != '' && $Pm['Section'] != '' && $Pm['Department'] ) {
            $EntityProductManager = DataWareHouse::useService()->createProductManager( $Pm['Name'], $Pm['Section'], $Pm['Department'] );
        }

        $EntitySelection = DataWareHouse::useService()->getSectionAll();
//        $EntityProductManager = DataWareHouse::useService()->getProductManagerAll();

        $LayoutExcel = '<br/>'.(new External('ExcelDownload', ExcelDefault::getEndpoint(), null, array(
            ExcelDefault::API_TARGET => 'getExcelProductManagerMarketingCode',
            'FileName' => 'Zuordnung PM MC',
            'FileTyp' => 'xlsx'
        ) ));

        $Stage->setContent(
            new Layout(
                new LayoutGroup(
                    array(
                        new LayoutRow(
                            array(
                                new LayoutColumn(
                                    new Panel('Zuordnung PM zu MC',
                                        new Layout(
                                            new LayoutGroup(
                                                array(
                                                    new LayoutRow(
                                                        new LayoutColumn(
                                                            array(
                                                                new Center(new Badge('letzte Änderung am: Test')),
                                                                $LayoutExcel
                                                            )
                                                        ), 4
                                                    )
                                                )
                                            )
                                        )
                                    ), 4
                                ),
                                new LayoutColumn(
                                    new Panel('neue Zuordnung hochladen',
                                        new Form(
                                            new FormGroup(
                                                new FormRow(
                                                    new FormColumn(
                                                        new FileUpload('Transfer[File]', null, null, null, array())
                                                    )
                                                )
                                            ), new Primary('Hochladen')
                                        )
                                    ), 8
                                )
                            )
                        ),
                        new LayoutRow(
                            new LayoutColumn(
                                '&nbsp;'
                            )
                        )
                    )
                )
            ).
            (new Accordion('Test'))->addItem('neuen Produktmanager anlegen',
                new Form(
                    new FormGroup(
                        new FormRow(
                            array(
                                new FormColumn(
                                    new TextField('Pm[Name]', 'Produktmanager-Name','Produktmanager-Name'), 4
                                ),
                                new FormColumn(
                                    new SelectBox('Pm[Section]', 'Sparte',  array( '{{Number}} - {{Name}}' => $EntitySelection )), 2
                                ),
//                                                new FormColumn(
//                                                    new SelectBox('Pm[ProductManagerId]', 'Produktmanager-Nummer', array( '{{Number}}' => $EntityProductManager )), 2
//                                                ),
                                new FormColumn(
                                    new TextField('Pm[Department]', 'Produktmanager-Bereich','Bereich'), 6
                                )
                            )
                        )
                    ),
                    array(
                        new Primary('Produktmanager anlegen')
                    )
                )
            , false)
            ->addItem('Produktmanager löschen',
                ProductManagerTable::TableBlockReceiver(ProductManagerTable::pipelineProductManagerTable())
            , false)
        );
        return $Stage;
    }

}