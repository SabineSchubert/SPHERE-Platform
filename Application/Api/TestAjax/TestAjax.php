<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 27.02.2017
 * Time: 11:20
 */

namespace SPHERE\Application\Api\TestAjax;


use SPHERE\Application\Api\ApiTrait;
use SPHERE\Application\Api\Dispatcher;
use SPHERE\Application\IApiInterface;
use SPHERE\Common\Frontend\Ajax\Emitter\ClientEmitter;
use SPHERE\Common\Frontend\Ajax\Emitter\ServerEmitter;
use SPHERE\Common\Frontend\Ajax\Pipeline;
use SPHERE\Common\Frontend\Ajax\Receiver\AbstractReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\BlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\InlineBlockReceiver;
use SPHERE\Common\Frontend\Ajax\Receiver\ModalReceiver;
use SPHERE\Common\Frontend\Form\Repository\Button\Close;
use SPHERE\Common\Frontend\Icon\Repository\More;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\ProgressBar;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Text\Repository\Muted;

class TestAjax implements IApiInterface
{
	use ApiTrait;

	public static function pipelineTest( AbstractReceiver $Receiver, $Route, $Title, $Description ) {
		$Emitter = new ServerEmitter($Receiver, TestAjax::getEndpoint());
		$Emitter->setGetPayload(array(
			TestAjax::API_TARGET => 'getTest',
			'Receiver' => $Receiver->getIdentifier()
		));
//		$Emitter->setPostPayload(array(
//            'Route' => $Route,
//            'Title' => $Title,
//            'Description' => $Description
//		));
		$Pipeline = new Pipeline();
        //$Pipeline = new Pipeline(false);
//		$LoadingEmitter = new ClientEmitter($Receiver, $Content = new Panel(
//            'Formular wird geladen...',
//            array(
//                new ProgressBar(0, 100, 0, 6),
//                new Muted('Daten werden vom Server abgerufen')
//            ),
//            Panel::PANEL_TYPE_DEFAULT
//        ));
//		$Receiver->initContent($Content);
        //$Pipeline->appendEmitter($LoadingEmitter);
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
	}

	public static function pipelineAktion( AbstractReceiver $Receiver ) {
		$Emitter = new ServerEmitter($Receiver, TestAjax::getEndpoint());
		$Emitter->setGetPayload(array(
			TestAjax::API_TARGET => 'getTest',
			'Receiver' => $Receiver->getIdentifier()
		));
		$Pipeline = new Pipeline();
        //$Pipeline = new Pipeline(false);
//		$LoadingEmitter = new ClientEmitter($Receiver, $Content = new Panel(
//            'Formular wird geladen...',
//            array(
//                new ProgressBar(0, 100, 0, 6),
//                new Muted('Daten werden vom Server abgerufen')
//            ),
//            Panel::PANEL_TYPE_DEFAULT
//        ));
//		$Receiver->initContent($Content);
        //$Pipeline->appendEmitter($LoadingEmitter);
        $Pipeline->appendEmitter($Emitter);

        return $Pipeline;
	}

	public function getTest() {
		return new Layout(
			new LayoutGroup(
				new LayoutRow(
					new LayoutColumn(
						'sfsf'
					)
				)
			)
		);
	}

	public static function receiverTest()
    {
//		$Receiver = new BlockReceiver();
//	    $Receiver->initContent(
//            new Panel(
//                'Privilegien werden geladen...',
//                array(
//                    new ProgressBar(0, 100, 0, 6),
//                    new Muted('Daten werden vom Server abgerufen')
//                ),
//                Panel::PANEL_TYPE_DEFAULT
//            )
//        );
		$Receiver = new ModalReceiver( 'Dialog', new Close() );
        return $Receiver;
    }

    public static function receiverShow()
    {
	    $Receiver = new BlockReceiver();
	    $Receiver->initContent(
		    new Panel(
			    'Rollen werden geladen...',
			    array(
				    new ProgressBar(0, 100, 0, 6),
				    new Muted('Daten werden vom Server abgerufen')
			    ),
			    Panel::PANEL_TYPE_DEFAULT
		    )
	    );
	    return $Receiver;
    }

	public function exportApi($Method = '')
    {
        $Dispatcher = new Dispatcher(__CLASS__);

        $Dispatcher->registerMethod('getTest');

        return $Dispatcher->callMethod($Method);
    }
}