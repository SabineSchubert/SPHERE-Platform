<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\Reporting\Controlling\Search;


use SPHERE\Application\Api\Platform\Gatekeeper\Access;
use SPHERE\Application\Api\Reporting\Utility\ScenarioCalculator\ScenarioCalculator;
use SPHERE\Application\Api\TestAjax\TestAjax;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
	public function frontendSearch() {
		$Stage = new Stage('Suche');
		$Stage->setMessage('');
		$Stage->hasUtilityFavorite(true);
		$Table = TestAjax::receiverShow();
		$ReceiverTestButton = TestAjax::receiverTest();
		$ReceiverTestButton = ScenarioCalculator::ReceiverSc();

		$Stage->setContent( $ReceiverTestButton .
			(new Form(
		            new FormGroup(array(
		                new FormRow(array(
		                    new FormColumn(array(
			                    (new TextField('test','test', 'Textfeld')),
		                        //($Table = Access::receiverTableRole()),
//		                        ($ReceiverTestButton = TestAjax::receiverTest()),
//		                        (new Standard('Click', TestAjax::getEndpoint(), null/*, array(
//		                            'TableReceiver' => $Table->getIdentifier()
//		                        )*/))->ajaxPipelineOnClick(TestAjax::pipelineTest($ReceiverTestButton, null, 'Test-Title', 'TestDescription')),
		                        ////Access::pipelineTableRole($Table)
		                    )),
		                )),
		            )
		        ), new Primary('anzeigen'), '', array(
					            'Route' => null,
					            'Title' => 'Test-Title',
					            'Description' => 'TestDescription'
				)
//			(new Standard( 'Click mich', TestAjax::getEndpoint(), null, array(
//	            'TableReceiver' => $Table->getIdentifier()
//	        )  ))
//				->ajaxPipelineOnClick(  TestAjax::pipelineTest($ReceiverTestButton, $this->getRequest()->getPathInfo(), 'Test-Title', 'TestDescription') ),
//			TestAjax::pipelineTest($Table)
//			$ReceiverTestButton
//		$Stage->setContent( $ReceiverTestButton
//			. TestAjax::pipelineTest($ReceiverTestButton, $this->getRequest()->getPathInfo(), 'Test-Title', 'TestDescription')
		//))->ajaxPipelineOnSubmit(TestAjax::pipelineTest($ReceiverTestButton, null, 'Test-Title', 'TestDescription')));
		))->ajaxPipelineOnSubmit(ScenarioCalculator::pipelineScenarioCalculator($ReceiverTestButton/*, null, 'Test-Title', 'TestDescription'*/)));

		return $Stage;
	}
}