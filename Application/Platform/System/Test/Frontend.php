<?php
namespace SPHERE\Application\Platform\System\Test;

use MOC\V\Core\FileSystem\FileSystem;
use SPHERE\Application\Platform\System\Protocol\Service\Entity\TblProtocol;
use SPHERE\Common\Frontend\Ajax\Emitter\ClientEmitter;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\FieldValueReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\InlineReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Chart\Repository\BarChart;
use SPHERE\Common\Frontend\Chart\Repository\LineChart;
use SPHERE\Common\Frontend\Form\Repository\Button\Danger;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Button\Standard as BtnStandard;
use SPHERE\Common\Frontend\Form\Repository\Button\Success;
use SPHERE\Common\Frontend\Form\Repository\Field\AutoCompleter;
use SPHERE\Common\Frontend\Form\Repository\Field\CheckBox;
use SPHERE\Common\Frontend\Form\Repository\Field\DatePicker;
use SPHERE\Common\Frontend\Form\Repository\Field\FileUpload;
use SPHERE\Common\Frontend\Form\Repository\Field\NumberField;
use SPHERE\Common\Frontend\Form\Repository\Field\PasswordField;
use SPHERE\Common\Frontend\Form\Repository\Field\RadioBox;
use SPHERE\Common\Frontend\Form\Repository\Field\SelectBox;
use SPHERE\Common\Frontend\Form\Repository\Field\Slider;
use SPHERE\Common\Frontend\Form\Repository\Field\TextArea;
use SPHERE\Common\Frontend\Form\Repository\Field\TextCaptcha;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Time;
use SPHERE\Common\Frontend\IFrontendInterface;
use SPHERE\Common\Frontend\Layout\Repository\Badge;
use SPHERE\Common\Frontend\Layout\Repository\Container;
use SPHERE\Common\Frontend\Layout\Repository\Header;
use SPHERE\Common\Frontend\Layout\Repository\Label;
use SPHERE\Common\Frontend\Layout\Repository\Listing;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\Paragraph;
use SPHERE\Common\Frontend\Layout\Repository\PullLeft;
use SPHERE\Common\Frontend\Layout\Repository\PullRight;
use SPHERE\Common\Frontend\Layout\Repository\Thumbnail;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Repository\Well;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Layout\Structure\LayoutSocial;
use SPHERE\Common\Frontend\Layout\Structure\LayoutTab;
use SPHERE\Common\Frontend\Layout\Structure\LayoutTabs;
use SPHERE\Common\Frontend\Layout\Structure\Slick;
use SPHERE\Common\Frontend\Link\Repository\External;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Message\Repository\Info;
use SPHERE\Common\Frontend\Message\Repository\Warning;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

/**
 * Class Frontend
 *
 * @package SPHERE\Application\System\Platform\Test
 */
class Frontend extends Extension implements IFrontendInterface
{

    /**
     * @return Stage
     * @throws \MOC\V\Core\FileSystem\Exception\FileSystemException
     */
    public function frontendPlatform()
    {

        $Stage = new Stage('Test', 'Frontend');

        $Stage->setMessage(
            'Message: Red alert. Processor of a distant x-ray vision, lower the death! Make it so, chemical
             wind! Fantastic nanomachines, to the alpha quadrant.Boldly sonic showers lead to the understanding. The 
             death is a ship-wide cosmonaut. Wobble nosily like a post-apocalyptic space suit.Cosmonauts are the 
             emitters of the fantastic ionic cannon. Where is the strange teleporter?'
        );

        $Stage->addButton(
            new Standard('Link', new Route(__NAMESPACE__), null, array(), true)
        );
        $Stage->addButton(
            new External('Link', 'http://www.google.de')
        );

        $D1 = new TblProtocol();
        $D1->setId(1);
        $D2 = new TblProtocol();
        $D2->setId(2);
        $Check = array($D1, $D2);

        $IconList = array();
        if (false !== ($Path = realpath(__DIR__ . '/../../../../Common/Frontend/Icon/Repository'))) {
            $Iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($Path, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            /** @var \SplFileInfo $FileInfo */
            foreach ($Iterator as $FileInfo) {
                $Namespace = '\SPHERE\Common\Frontend\Icon\Repository';
                $Class = $FileInfo->getBasename('.php');
                $Loader = $Namespace . '\\' . $Class;

                $IconList[$Class] = new PullLeft(
                    '<div style="margin: 5px; border: 1px solid silver; width: 100px;">'
                    . '<div style="font-size: large; border-bottom: 1px dotted silver;" class="text-center">' . new $Loader() . '</div>'
                    . '<div class="text-center">' . $Class . '</div>'
                    . '</div>'
                );
            }
            ksort($IconList);
        }

        $Stage->setContent(
            (new Form(
                new FormGroup(array(
                    new FormRow(array(
                        new FormColumn(
                            new AutoCompleter('AutoCompleter', 'AutoCompleter', 'AutoCompleter',
                                array('123', '234', '345'))
                            , 3),
                        new FormColumn(array(
                            new CheckBox('CheckBox1', 'CheckBox', 'c1'),
                            new CheckBox('CheckBox2', 'CheckBox', 'c2'),
                            new RadioBox('RadioBox1', 'RadioBox1a', '1a'),
                        ), 3),
                        new FormColumn(
                            new DatePicker('DatePicker', 'DatePicker', 'DatePicker')
                            , 3),
                        new FormColumn(
                            new FileUpload('FileUpload', 'FileUpload', 'FileUpload')
                            , 3),
                    )),
                    new FormRow(array(
                        new FormColumn(
                            new NumberField('NumberField', 'NumberField', 'NumberField')
                            , 3),
                        new FormColumn(
                            new PasswordField('PasswordField', 'PasswordField', 'PasswordField')
                            , 3),
                        new FormColumn(array(
                            new RadioBox('RadioBox1', 'RadioBox1b', '1b'),
                            new RadioBox('RadioBox2', 'RadioBox2', '2'),
                            new RadioBox('RadioBox3', 'RadioBox3', '3'),
                        ), 3),
                    )),
                    new FormRow(array(
                        new FormColumn(array(
                            new SelectBox('SelectBox1', 'SelectBox - Bootstrap Default',
                                array('0' => 'A', '2' => '1', '3' => '2', '4' => '3')
                            ),
                            (new SelectBox('SelectBox2', 'SelectBox - jQuery Select2',
                                array('{{ Id }}{{ Name }}{{ Name }} {{ Id }}{{ Name }}{{ Name }}' => $Check)
                            ))->configureLibrary( SelectBox::LIBRARY_SELECT2 ),
                        ), 3),
                        new FormColumn(
                            new TextArea('TextArea', 'TextArea', 'TextArea')
                            , 3),
                        new FormColumn(
                            new TextCaptcha('TextCaptcha', 'TextCaptcha', 'TextCaptcha')
                            , 3),
                        new FormColumn(array(
                            new TextField('TextField', 'TextField', 'TextField'),
                            new Slider('Slider', 'Slider', 'Slider')
                        ), 3),
                    )),
//                    new FormRow( array(
//                        new FormColumn(
//                            new \SPHERE\Common\Frontend\Form\Repository\Title('Title')
//                        ,3),
//                        new FormColumn(
//                            new Aspect('Aspect')
//                        ,3),
//                    ) )
                ), new \SPHERE\Common\Frontend\Form\Repository\Title('Form-Title')),
                array(
                    new Primary('Primary'),
                    new Danger('Danger'),
                    new Success('Success'),
                    new Reset('Reset'),
                    new BtnStandard('Standard')
                )
            ))->setConfirm('Wirklich?')
            . new Layout(array(
                new LayoutGroup(array(
                    new LayoutRow(array(
//                        new LayoutColumn( array(
//                            new Address( null )
//                        ), 3 ),
                        new LayoutColumn(array(
                            new Badge('Badge')
                        ), 3),
                        new LayoutColumn(array(
                            new Container('Container'),
                        ), 3),
                        new LayoutColumn(array(
                            new Header('Header')
                        ), 3),
                    )),
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            new Label('Label')
                        ), 3),
                        new LayoutColumn(array(
                            new Listing(array('Listing', 'Listing 2')),
                            new LineChart(),
                            new BarChart()
                        ), 3),
                        new LayoutColumn(array(
                            new Panel('Panel', array('Content 1', 'Content 2', 'Content 3'),
                                Panel::PANEL_TYPE_DEFAULT, 'Footer'),
                            new Panel('Panel', array('Conten 1', 'Content 2', 'Content 3'),
                                Panel::PANEL_TYPE_DANGER, 'Footer'),
                            new Panel('Panel', array(new TextField(''), new TextField(''), new TextField('')),
                                Panel::PANEL_TYPE_PRIMARY, 'Footer'),
                        ), 3),
                        new LayoutColumn(array(
                            new PullRight('PullRight')
                        ), 3),
                    )),
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            new Thumbnail(
                                FileSystem::getFileLoader('/Common/Style/Resource/Teaser/00-mercedes-benz-design-aesthetics-a-1280-686-848x454.jpg'),
                                'Title', 'Description',
                                array(new \SPHERE\Common\Frontend\Link\Repository\Primary('Primary', ''))
                            )
                        ), 3),
                        new LayoutColumn(array(
                            new Well('Well', array())
                        ), 3),
                        new LayoutColumn(
                            new Table(array(
                                array('A' => 1, 'B' => '2'),
                                array('A' => 2, 'B' => '34567890')
                            ))
                            , 6),

                    )),
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            '<hr/>'
                        )),

                    )),

                ), new Title('Layout-Title')),
                new LayoutGroup(array(
                    new LayoutRow(array(
                        new LayoutColumn(new LayoutTabs(array(
                            new LayoutTab('Name1', 0),
                            new LayoutTab('Name2', 1),
                            new LayoutTab('Name3', 2),
                        )), 3),
                        new LayoutColumn(
                            (new LayoutSocial())
                                ->addMediaItem('Head1', new Paragraph('Content') . new Paragraph('Content'), new Time())
                                ->addMediaItem('Head2', 'Content',
                                    '<img src="/Common/Style/Resource/loading.gif" class="image-responsive" style="width:20px;"/>',
                                    '', LayoutSocial::ALIGN_BOTTOM)
                                ->addMediaList(
                                    (new LayoutSocial())
                                        ->addMediaItem('Head2.1',
                                            new Well(new Paragraph('Content') . new Paragraph('Content')),
                                            '<img src="/Common/Style/Resource/loading.gif" class="image-responsive" style="width:20px;"/>',
                                            '', LayoutSocial::ALIGN_TOP)
                                        ->addMediaItem('', new Well('Content'),
                                            '<img src="/Common/Style/Resource/loading.gif" class="image-responsive" style="width:20px;"/>',
                                            '', LayoutSocial::ALIGN_MIDDLE)
                                )
                            , 4),
                    )),

                ), new Title('Layout Development')),
                new LayoutGroup(array(
                    new LayoutRow(array(
                        new LayoutColumn(implode($IconList)),
                    )),
                ), new Title('Icons')),
                new LayoutGroup(array(
                    new LayoutRow(array(
                        new LayoutColumn(
                            (new Slick())
                                ->addImage('/Common/Style/Resource/Teaser/4260479090780-irgendwas-ist-immer.jpg')
                                ->addImage('/Common/Style/Resource/Teaser/00-mercedes-benz-design-aesthetics-a-1280-686-848x454.jpg')
                        ),
                    )),
                ), new Title('Slick')),
            ))
        );

        return $Stage;
    }

    /**
     * @return Stage
     */
    public function frontendSandbox()
    {
//        $this->getCache(new TwigHandler())->clearCache();

        $Stage = new Stage('SandBox');

//        $Stage->setContent( $this->getTemplate( __DIR__.'/Test.twig' ) );

        $R1 = new ModalReceiver();
        $R2 = new FieldValueReceiver((new NumberField('NUFF'))->setDefaultValue(9));
        $R3 = new BlockReceiver(new Warning(':/'));
        $R4 = new InlineReceiver(new Warning(':P'));

        $P = new Pipeline();
        $P->setLoadingMessage('Bitte warten', 'Interface wird geladen..');
        $P->setSuccessMessage('Erfolgreich', 'Daten wurden geladen');

        $P->appendEmitter($E2 = new ClientEmitter($R2, 0));
        $P->appendEmitter($E4 = new ClientEmitter(array($R1, $R4), new Info(':)')));

        $P->appendEmitter($E3 = new ServerEmitter(array($R4, $R3),
            new Route('SPHERE\Application\Api\Corporation/Similar')));
        $E3->setGetPayload(array(
            'MethodName' => 'ajaxContent'
        ));
        $E3->setLoadingMessage('Bitte warten', 'Interface wird geladen..');
        $E3->setSuccessMessage('Erfolgreich', 'Daten wurden geladen');

        $P->appendEmitter($E1 = new ServerEmitter($R1, new Route('SPHERE\Application\Api\Corporation/Similar')));
        $E1->setGetPayload(array(
            'MethodName' => 'ajaxLayoutSimilarPerson'
//            'MethodName' => 'ajaxFormDingens'
        ));
        $E1->setPostPayload(array(
            'Reload' => (string)$R1->getIdentifier(),
            'E4' => (string)$R4->getIdentifier()
        ));
        $E1->setLoadingMessage('Bitte warten', 'Inhalte werden geladen..');
        $E1->setSuccessMessage('Erfolgreich', 'Daten wurden geladen');

        $P2 = new Pipeline();
        $P2->setLoadingMessage('Bitte warten', 'Interface wird geladen..');
        $P2->setSuccessMessage('Erfolgreich', 'Daten wurden geladen');

        $P2->appendEmitter($E1 = new ServerEmitter($R1, new Route('SPHERE\Application\Api\Corporation/Similar')));
        $E1->setGetPayload(array(
            'MethodName' => 'ajaxFormDingens'
        ));


        $Stage->setContent(
            new Layout(array(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(array(
                            (new Standard('Call', '#'))->ajaxPipelineOnClick($P),
                            (new Form(
                                new FormGroup(
                                    new FormRow(
                                        new FormColumn(array(
                                            $R2
                                        ))
                                    )
                                )
                                , new Primary('Ajax-Form?')))->ajaxPipelineOnSubmit($P2)->setConfirm('Test with Ajax')
                        ))
                    )
                ),
                new LayoutGroup(
                    new LayoutRow(array(
                        new LayoutColumn(array(
                            $R1,
                            $R4
                        ), 4),
                        new LayoutColumn(
                            $R2
                            , 4),
                        new LayoutColumn(
                            $R3
                            , 4)
                    ))
                )
            ))
        );

        return $Stage;
    }
}
