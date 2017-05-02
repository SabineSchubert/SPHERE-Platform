<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 25.10.2016
 * Time: 14:58
 */

namespace SPHERE\Application\Reporting\Controlling\MonthlyTurnover;


use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
use SPHERE\Common\Frontend\Form\Repository\Button\Primary;
use SPHERE\Common\Frontend\Form\Repository\Button\Reset;
use SPHERE\Common\Frontend\Form\Repository\Field\AutoCompleter;
use SPHERE\Common\Frontend\Form\Repository\Field\SelectBox;
use SPHERE\Common\Frontend\Form\Repository\Field\TextField;
use SPHERE\Common\Frontend\Form\Structure\Form;
use SPHERE\Common\Frontend\Form\Structure\FormColumn;
use SPHERE\Common\Frontend\Form\Structure\FormGroup;
use SPHERE\Common\Frontend\Form\Structure\FormRow;
use SPHERE\Common\Frontend\Icon\Repository\Search;
use SPHERE\Common\Frontend\Icon\Repository\Warning;
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
	}

	/**
	 * @param null|array $Search
	 * @return Stage
	 */
	public function frontendSearchPartNumber( $Search = null )
	{
		$Stage = new Stage('Geschäftsentwicklung', 'Teilenummer');
		$this->buttonStageDirectSearch($Stage);

//		$this->getDebugger()->screenDump( $Search );

		$Table = '';
		if( $Search ) {
			if (empty($Result)) {
				$Table = new Table(
					array(
						array( 'A' => ':)' )
					), null, array(
						'A' => 'Lach ne'
					)
				, false);
			} else {
				$Table = new Warning('Die Teilenummer konnte nicht gefunden werden.');
			}
		}

		$Stage->setContent(
			new Layout(array(
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(
							$this->fromSearchPartNumber()
						,4)
					)
				),
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(

							new Layout(
								new LayoutGroup(
									new LayoutRow(
										new LayoutColumn(
											$Table
										)
									)
								)
							)

						)
					)
				, new Title( 'Ergebnis' ))
			))
		);

		return $Stage;
	}

	public function frontendSearchProductManager( $Search = null )
	{
		$Stage = new Stage('Geschäftsentwicklung', 'Produktmanager');
		$this->buttonStageDirectSearch($Stage);

		$Table = '';
		if( $Search ) {
			if (empty($Result)) {
				$Table = new Table(
					array(
						array( 'A' => ':)' )
					), null, array(
						'A' => 'Lach ne'
					)
				, false);
			} else {
				$Table = new Warning('Der Produktmanager konnte nicht gefunden werden.');
			}
		}

		$Stage->setContent(
			new Layout(array(
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(
							$this->fromSearchProductManager()
						,4)
					)
				),
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(

							new Layout(
								new LayoutGroup(
									new LayoutRow(
										new LayoutColumn(
											$Table
										)
									)
								)
							)

						)
					)
				, new Title( 'Ergebnis' ))
			))
		);
		return $Stage;
	}

	public function frontendSearchMarketingCode( $Search = null )
	{
		$Stage = new Stage('Geschäftsentwicklung', 'Marketingcode');
		$this->buttonStageDirectSearch($Stage);

		$Table = '';
		if( $Search ) {
			if (empty($Result)) {
				$Table = new Table(
					array(
						array( 'A' => ':)' )
					), null, array(
						'A' => 'Lach ne'
					)
				, false);
			} else {
				$Table = new Warning('Der Marketingcode konnte nicht gefunden werden!');
			}
		}

		$Stage->setContent(
			new Layout(array(
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(
							$this->fromSearchMarketingCode()
						,4)
					)
				),
				new LayoutGroup(
					new LayoutRow(
						new LayoutColumn(

							new Layout(
								new LayoutGroup(
									new LayoutRow(
										new LayoutColumn(
											$Table
										)
									)
								)
							)

						)
					)
				, new Title( 'Ergebnis' ))
			))
		);

		return $Stage;
	}


	/**
	 * @return Form
	 */
	private function fromSearchPartNumber()
	{
		return new Form(
			new FormGroup(
				new FormRow(
					array(
						new FormColumn(
							new Panel('Suche', array(
								(new TextField('Search[PartNumber]', 'Teilenummer', 'Teilenummer eingeben', new Search()))
								->setRequired()
							), Panel::PANEL_TYPE_DEFAULT)
						),
					)
				)
			)
			, array(
				new Primary('anzeigen', new Search()),
				new Reset('zurücksetzen')
			)
		);
	}

	private function fromSearchProductManager()
	{
	    $EntityProductManager = DataWareHouse::useService()->getProductManagerAll();
		return new Form(
			new FormGroup(
				new FormRow(
                    new FormColumn(
                        new Panel('Suche', array(
                            new SelectBox('ProductManager', 'Produktmanager',
                                array( '{{Name}} {{Department}}' => $EntityProductManager )
                            )
                        ))
                    )
			    )
            ), array(
				new Primary('anzeigen', new Search()),
				new Reset('zurücksetzen')
			)
		);
	}

	private function fromSearchMarketingCode()
	{
		return new Form(
			new FormGroup(
				new FormRow(
					array(
						new FormColumn(
							new Panel('Suche', array(
								(new AutoCompleter('MarketingCode', 'Marketingcode', 'Marketingcode eingeben', array('1P123')))
								->setRequired()
							), Panel::PANEL_TYPE_DEFAULT)
						),
					)
				)
			)
			, array(
				new Primary('anzeigen', new Search()),
				new Reset('zurücksetzen')
			)
		);
	}

	private function tableMonthlyTurnover( $MonthlyTurnoverData ) {
//		$MonthlyTurnoverData = array(
//			array(
//				'Month' =>
//			)
//		);
	}
}
