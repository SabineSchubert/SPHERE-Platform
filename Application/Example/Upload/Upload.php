<?php

namespace SPHERE\Application\Example\Upload;

use SPHERE\Application\Api\Example\Upload\Upload as UploadApi;
use SPHERE\Application\AppTrait;
use SPHERE\Application\Example\Upload\Excel\Excel;
use SPHERE\Application\IApplicationInterface;
use SPHERE\Common\Frontend\Form\Repository\Field\SelectBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Blackboard;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Link\Repository\Link;
use SPHERE\Common\Window\Stage;

class Upload implements IApplicationInterface
{
    use AppTrait;

    public static function registerApplication()
    {
        self::createApplication(__NAMESPACE__, __CLASS__, 'frontendUpload', 'Upload', new Blackboard(), 'Hochladen');
        Excel::registerModule();
    }

    public function frontendUpload()
    {
        $Stage = new Stage('Upload', 'Hochladen');


        $Stage->setContent(
            (new Form(
                new FormGroup(
                    new FormRow(
//                        new FormColumn(new Panel('', array(
                        new FormColumn(array(
                            UploadApi::receiverField((
                            $Field = new SelectBox('Feld[1]', 'SelectBox-Test', array(1 => 'A', 2 => 'B'))
                            )),
                            UploadApi::receiverModal($Field),
                            new PullRight((new Link('Ändern',
                                UploadApi::getEndpoint(), null, array('Service' => 'A')))->ajaxPipelineOnClick(
                                UploadApi::pipelineOpen($Field)
                            )),
                            UploadApi::receiverField((
                            $Field = new TextField('Feld[2]', 'Placeholder', 'TextField-Test')
                            )),
                            UploadApi::receiverModal($Field),
                            new PullRight((new Link('Ändern',
                                UploadApi::getEndpoint(), null, array('Service' => 'B')))->ajaxPipelineOnClick(
                                UploadApi::pipelineOpen($Field)
                            )),
                        ))
                    )
                )
            ))->setError('Feld[2]', ':/')
        );

        return $Stage;
    }
}