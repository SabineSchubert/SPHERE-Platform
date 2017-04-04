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
use SPHERE\Common\Frontend\Form\Repository\Field\SelectBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Layout\Repository\Panel;
use SPHERE\Common\Frontend\Layout\Repository\Title;
use SPHERE\Common\Frontend\Layout\Structure\Layout;
use SPHERE\Common\Frontend\Layout\Structure\LayoutColumn;
use SPHERE\Common\Frontend\Layout\Structure\LayoutGroup;
use SPHERE\Common\Frontend\Layout\Structure\LayoutRow;
use SPHERE\Common\Frontend\Link\Repository\Standard;
use SPHERE\Common\Frontend\Table\Structure\Table;
use SPHERE\Common\Window\Navigation\Link\Route;
use SPHERE\Common\Window\Stage;
use SPHERE\System\Extension\Extension;

class Frontend extends Extension
{
	public function frontendSearchPartNumber() {
		$Stage = new Stage('Suche');
		$Stage->setMessage('Teilenummer');
		$this->buttonStageDirectSearch($Stage);

		$SearchData = array(array('Teilenummer'));
		$LayoutTable = $this->tableSearchData($SearchData);

		$Stage->setContent(
			new Layout(
				new LayoutGroup(
					new LayoutRow(array(
						new LayoutColumn(
							'&nbsp;'
						),
						new LayoutColumn(
							$LayoutTable
						)
					))
				)
			)
		);

		return $Stage;
	}

	public function frontendSearchMarketingCode() {
		$Stage = new Stage('Suche');
		$Stage->setMessage('Marketingcode');
		$this->buttonStageDirectSearch($Stage);

		$SearchData = array(array('Marketingcode'));
		$LayoutTable = $this->tableSearchData($SearchData);

		$Stage->setContent(
			new Layout(
				new LayoutGroup(
					new LayoutRow(array(
						new LayoutColumn(
							'&nbsp;'
						),
						new LayoutColumn(
							$LayoutTable
						)
					))
				)
			)
		);

		return $Stage;
	}

	public function frontendSearchProductManager() {
		$Stage = new Stage('Suche');
		$Stage->setMessage('Produktmanager');
		$this->buttonStageDirectSearch($Stage);

		$SearchData = array(array('Produktmanager'));
		$LayoutTable = $this->tableSearchData($SearchData);

		$Stage->setContent(
			new Layout(
				new LayoutGroup(
					new LayoutRow(array(
						new LayoutColumn(
							'&nbsp;'
						),
						new LayoutColumn(
							$LayoutTable
						)
					))
				)
			)
		);

		return $Stage;
	}

	public function frontendSearchCompetition() {
		$Stage = new Stage('Suche');
		$Stage->setMessage('Angebotsdaten');
		$this->buttonStageDirectSearch($Stage);

		$SearchData = array(array('Angebotsdaten'));
		$LayoutTable = $this->tableSearchData($SearchData);

		$Stage->setContent(
			new Layout(
				new LayoutGroup(
					new LayoutRow(array(
						new LayoutColumn(
							'&nbsp;'
						),
						new LayoutColumn(
							$LayoutTable
						)
					))
				)
			)
		);

		return $Stage;
	}

	private function buttonStageDirectSearch(Stage $Stage)
	{
		$Stage->addButton(
			new Standard('Teilenummer', new Route(__NAMESPACE__ . '/PartNumber'))
		);
		$Stage->addButton(
			new Standard('Produktmanager', new Route(__NAMESPACE__ . '/ProductManager'))
		);
		$Stage->addButton(
			new Standard('Marketingcode', new Route(__NAMESPACE__ . '/MarketingCode'))
		);
		$Stage->addButton(
			new Standard('Angebotsdaten', new Route(__NAMESPACE__ . '/Competition'))
		);
	}

	private function tableSearchData( $SearchData ) {

//		switch ($SelectionStatistic) {
//			case '1':
//				$SearchData = array(
//					array( 'PartNumber' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 ),
//					array( 'PartNumber' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 )
//				);
//				break;
//			case '2':
//				$SearchData = array(
//					array( 'McCode' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 ),
//					array( 'McCode' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 )
//				);
//				break;
//			case '3':
//				$SearchData = array(
//					array( 'ProductManager' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 ),
//					array( 'ProductManager' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 )
//				);
//				break;
//			case '4':
//				$SearchData = array(
//					array( 'Competitor' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 ),
//					array( 'Competitor' => 'dsakj', 'GrossPrice' => 250.00, 'Discount' => 10, 'Quantity' => 5 )
//				);
//				break;
//			default:
//				$SearchData = array();
//				break;
//		}

		$ReplaceArray = array(
			'PartNumber' => 'Teilenummer',
			'McCode' => 'Marketingcode',
			'ProductManager' => 'Produktmanager',
			'Competitor' => 'Wettbewerber',
			'GrossPrice' => 'BLP',
			'NetPrice' => 'NLP',
			'Discount' => 'RG',
			'Quantity' => 'Menge'
		);

		$Keys = array_keys($SearchData[0]);
		$TableHead = array_combine( $Keys, str_replace( array_keys( $ReplaceArray ) , $ReplaceArray, $Keys) );

		return new Table(
			$SearchData, null, $TableHead
		);
	}
}