<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 22.11.2016
 * Time: 15:56
 */

namespace SPHERE\System\Extension\Repository;


class CalculationRules
{
	//Stückpreise

	/**
	 * GrossPrice * ( Discount / 100 )
	 *
	 * @param float $GrossPrice
	 * @param float $Discount
	 * @return float
	 */
	public function calcDiscount($GrossPrice, $Discount ) {
		$GrossPrice = (float)$GrossPrice;
		$Discount = (float)$Discount;
		return $GrossPrice * ( $Discount / 100 );
	}

	/**
	 * Preis mit Rückwert, aber Preis exkl. Rückwert = Tauschpreis (3. Parameter = null), Preis inkl. Rückwert = Verkaufspreis (3. Parameter != null)
	 *
	 * Preis ohne Rückwert = Verkaufspreis
	 *
	 * ( NetPrice + $Rw ) / ( 1 - ( Discount / 100 ) - ( PartsMoreDiscount / 100) )
	 *
	 * Oder ( GrossPrice + Rw ) / ( 1 - ( PartsMoreDiscount / 100)  - ( PromoDiscount / 100)  - ( KamDiscount / 100) )
	 *
	 * wenn mehrere Promorabatte, dann MAX(Promo)
	 *
	 * @param float $NetPrice
	 * @param float $Discount
	 * @param float $Rw
	 * @param float $PartsMoreDiscount
	 * @param float $PromoDiscount
	 * @param float $KamDiscount
	 * @param float $GrossPrice
	 * @return float
	 */
	public function calcGrossPrice($NetPrice = 0, $Discount = 0, $Rw = 0, $PartsMoreDiscount = 0, $PromoDiscount = 0, $KamDiscount = 0, $GrossPrice = 0 ) {
		$NetPrice = (float)$NetPrice;
		$Discount = (float)$Discount;
		$Rw = (float)$Rw;
		$PartsMoreDiscount = (float)$PartsMoreDiscount;
		$PromoDiscount = (float)$PromoDiscount;
		$KamDiscount = (float)$KamDiscount;
		$GrossPrice = (float)$GrossPrice;
		//Preis mit Rückwert, aber Preis exkl. Rückwert = Tauschpreis (3. Parameter = null), Preis inkl. Rückwert = Verkaufspreis (3. Parameter != null)
		//Preis ohne Rückwert = Verkaufspreis
		if( $NetPrice != 0 ) {
			return ( $NetPrice + $Rw ) / ( 1 - ( $Discount / 100 ) - ( $PartsMoreDiscount / 100)  - ( $PromoDiscount / 100)  - ( $KamDiscount / 100) );
		}
		elseif( $GrossPrice != 0 ) {
			return ( $GrossPrice + $Rw ) / ( 1 - ( $PartsMoreDiscount / 100)  - ( $PromoDiscount / 100)  - ( $KamDiscount / 100) );
		}
		else {
			return 0;
		}
	}

	/**
	 * Preis mit Rückwert, aber Preis exkl. Rückwert = Tauschpreis (3. Parameter = null), Preis inkl. Rückwert = Verkaufspreis (3. Parameter != null)
	 *
	 * ( GrossPrice + Rw ) * ( 1 - ( Discount / 100 ) - ( PartsMoreDiscount / 100 ) )
	 *
	 * Preis ohne Rückwert = Verkaufspreis
	 *
	 * wenn mehrere Promorabatte, dann MAX(Promo)
	 *
	 * @param $GrossPrice
	 * @param $Discount
	 * @param int $Rw
	 * @param int $PartsMoreDiscount
	 * @return mixed
	 */
	public function calcNetPrice($GrossPrice, $Discount, $Rw = 0, $PartsMoreDiscount = 0, $PromoDiscount = 0, $KamDiscount = 0 ) {
		$GrossPrice = (float)$GrossPrice;
		$Discount = (float)$Discount;
		$Rw = (float)$Rw;
		$PartsMoreDiscount = (float)$PartsMoreDiscount;
		$PromoDiscount = (float)$PromoDiscount;
		$KamDiscount = (float)$KamDiscount;
		//Preis mit Rückwert, aber Preis exkl. Rückwert = Tauschpreis (3. Parameter = null), Preis inkl. Rückwert = Verkaufspreis (3. Parameter != null)
		//Preis ohne Rückwert = Verkaufspreis
		return (( $GrossPrice + $Rw ) * ( 1 - ( $Discount / 100 ) - ( $PartsMoreDiscount / 100 ) - ( $PromoDiscount / 100)  - ( $KamDiscount / 100) ));
	}

//	public function calcNetPriceWithPartsMore( $NetPrice, $PartsMoreDiscount ) {
//		return $NetPrice - ( ( $PartsMoreDiscount / 100 ) * $NetPrice );
//	}

//	/**
//	 * Variable Kosten inkl. PartsMore
//	 *
//	 * @param float $VariableCosts
//	 * @param float $PartsMoreDiscount
//	 * @return float
//	 */
//	public function VariableCostsWithPartsMore($VariableCosts, $PartsMoreDiscount ) {
//		return $VariableCosts * ( 1 - ( $PartsMoreDiscount / 100 ) );
//	}

	//Deckungsbeiträge und Finanzgrenze

	/**
	 * Deckungsbeitrag = NetPrice - VariableCosts
	 *
	 * Konzerndeckungsbeitrag = ( NLP (VP) inkl. Rückwert, abzüglich PartsMore ) - vk (ohne PartsMore)
	 * @param float $NetPrice
	 * @param float $VariableCosts
	 * @return float
	 */
	public function calcCoverageContribution($NetPrice, $VariableCosts ) {
		$NetPrice = (float)$NetPrice;
		$VariableCosts = (float)$VariableCosts;
//		if( $PartsMoreDiscount != 0 ) {
//			$this->VariableCostsWithPartsMore( $VariableCosts, $PartsMoreDiscount );
//		}
		//Konzerndeckungsbeitrag = ( NLP (VP) inkl. Rückwert abzüglich PartsMore ) - vk (ohne PartsMore)
		return $NetPrice - $VariableCosts;
	}

	/**
	 * Finanz-Controlling-Faktor (eigentlich je Sparte)
	 *
	 * @return float
	 */
	public function calcFinancialManagementFactor() {
		return 0.1;
	}

	/**
	 * Finanz-Grenze = vk + ( BLP [RW vorhanden, dann TP sonst VP, d.h. immer ohne Rückwert, mit UND ohne PartsMore ausweisen] * FinancialManagementFactor )
	 *
	 * Preis mit Rückwert, aber Preis exkl. Rückwert = Tauschpreis (3. Parameter = null), Preis inkl. Rückwert = Verkaufspreis (3. Parameter != null)
	 *
	 * Preis ohne Rückwert = Verkaufspreis
	 *
	 * @param float $GrossPrice
	 * @param float $VariableCosts
	 * @return float
	 */
	public function calcFinancialManagementLimit($GrossPrice, $VariableCosts ) {
		$GrossPrice = (float)$GrossPrice;
		$VariableCosts = (float)$VariableCosts;
		//vk + ( BLP [RW vorhanden, dann TP sonst VP, d.h. immer ohne Rückwert, mit UND ohne PartsMore ausweisen] * FinancialManagementFactor )
		return $VariableCosts + ( $GrossPrice * $this->calcFinancialManagementFactor() );
	}

//	public function calcCoverageContributionWithPartsMore( $CoverageContribution, $PartsMoreDiscount ) {
//		return $CoverageContribution - ( ( $PartsMoreDiscount / 100 ) * $CoverageContribution );
//	}

	//Gesamtbetrachtung

	/**
	 * PartsMore in Euro = GrossPrice * ( PartsMoreDiscount / 100 )
	 *
	 * @param $GrossPrice
	 * @param $PartsMoreDiscount
	 * @return mixed
	 */
	public function calcPartsMoreEuro($GrossPrice, $PartsMoreDiscount ) {
		$GrossPrice = (float)$GrossPrice;
		$PartsMoreDiscount = (float)$PartsMoreDiscount;
		return $GrossPrice * ( $PartsMoreDiscount / 100 );
	}

	/**
	 * Bruttoumsatz = GrossPrice * Quantity;
	 *
	 * @param float $GrossPrice
	 * @param int $Quantity
	 * @return mixed
	 */
	public function calcGrossSales($GrossPrice, $Quantity ) {
		$GrossPrice = (float)$GrossPrice;
		$Quantity = (int)$Quantity;
		return $GrossPrice * $Quantity;
	}

	/**
	 * Nettoumsatz = NetPrice * Quantity
	 *
	 * @param float $NetPrice
	 * @param int $Quantity
	 * @return float
	 */
	public function calcNetSales($NetPrice, $Quantity ) {
		$NetPrice = (float)$NetPrice;
		$Quantity = (int)$Quantity;
		return $NetPrice * $Quantity;
	}

	/**
	 * Gesamte variable Kosten evtl. inkl. PartsMore = VariableCosts * Quantity
	 *
	 * ruft $this->VariableCostsWithPartsMore( $VariableCosts, $PartsMoreDiscount ) auf und berechnet bei Bedarf die variablen Kosten je Stück aus
	 *
	 * @param float $VariableCosts
	 * @param int $Quantity
	 * @param float $PartsMoreDiscount
	 * @return float
	 */
	public function calcTotalVariableCosts($VariableCosts, $Quantity ) {
		$VariableCosts = (float)$VariableCosts;
		$Quantity = (int)$Quantity;
//		if( $PartsMoreDiscount != 0 ) {
//			$this->VariableCostsWithPartsMore( $VariableCosts, $PartsMoreDiscount );
//		}
		return $VariableCosts * $Quantity;
	}

//	public function calcNetSalesWithPartsMore( $NetSales, $PartsMoreDiscount ) {
//		return $NetSales - ( ( $PartsMoreDiscount / 100 ) * $NetSales );
//	}

	/**
	 * Gesamtrabatt in Euro
	 *
	 * @param float $DiscountEuro
	 * @param int $Quantity
	 * @return float
	 */
	public function calcTotalDiscount($DiscountEuro, $Quantity ) {
		$DiscountEuro = (float)$DiscountEuro;
		$Quantity = (int)$Quantity;
		return $DiscountEuro * $Quantity;
	}

//	/**
//	 * Gesamtdeckungsbeitrag = NetSales - TotalVariableCosts
//	 *
//	 * @param float $NetSales
//	 * @param float $TotalVariableCosts
//	 * @return float
//	 */
//	public function calcTotalCoverageContribution($NetSales, $TotalVariableCosts ) {
//		return $NetSales - $TotalVariableCosts;
//	}

	/**
	 * Gesamtdeckungsbeitrag = Deckungsbeitrag * Menge
	 *
	 * @param float $CoverageContribution
	 * @param int $Quantity
	 * @return float
	 */
	public function calcTotalCoverageContribution($CoverageContribution, $Quantity ) {
		$CoverageContribution = (float)$CoverageContribution;
		$Quantity = (int)$Quantity;
		return $CoverageContribution * $Quantity;
	}

//	public function calcTotalCoverageContributionWithPartsMore( $TotalCoverageContribution, $PartsMoreDiscount ) {
//		return $TotalCoverageContribution - ( ( $PartsMoreDiscount / 100 ) * $TotalCoverageContribution );
//	}

	//Hochrechnung

	/**
	 * Hochrechnung = ( SumQuantityVVJ + SumQuantityVJ ) / ( SumQuantityVVJx + SumQuantityVJx )
	 *
	 * @param int $SumQuantityVVJ = Summe Anzahl effektiv vom gesamten Vorvorjahr
	 * @param int $SumQuantityVJ = Summe Anzahl effektiv vom gesamten Vorjahr
	 * @param int $SumQuantityVVJx = Summe Anzahl effektiv vom 1. Monat bis x. Monat des Vorvorjahres
	 * @param int $SumQuantityVJx = Summe Anzahl effektiv vom 1. Monat bis x. Monat des Vorjahres
	 * @return float
	 */
	public function calcExpansionFactor($SumQuantityVVJ, $SumQuantityVJ, $SumQuantityVVJx, $SumQuantityVJx ) {
		$SumQuantityVVJ = (float)$SumQuantityVVJ;
		$SumQuantityVJ = (float)$SumQuantityVJ;
		$SumQuantityVVJx = (float)$SumQuantityVVJx;
		$SumQuantityVJx = (float)$SumQuantityVJx;
		return ( $SumQuantityVVJ + $SumQuantityVJ ) / ( $SumQuantityVVJx + $SumQuantityVJx );
	}

	/**
	 * Bruttoumsatz-Hochrechnung = GrossSales * ExpansionFactor
	 *
	 * @param float $GrossSales
	 * @param float $ExpansionFactor
	 * @return float
	 */
	public function calcExpansionGrossSales($GrossSales, $ExpansionFactor ) {
		$GrossSales = (float)$GrossSales;
		$ExpansionFactor = (float)$ExpansionFactor;
		return $GrossSales * $ExpansionFactor;
	}

	/**
	 * Nettoumsatz-Hochrechnung = NetSales * ExpansionFactor
	 *
	 * @param $NetSales
	 * @param $ExpansionFactor
	 * @return mixed
	 */
	public function calcExpansionNetSales($NetSales, $ExpansionFactor ) {
		$NetSales = (float)$NetSales;
		$ExpansionFactor = (float)$ExpansionFactor;
		return $NetSales * $ExpansionFactor;
	}

	/**
	 * Anzahl effektiv-Hochrechnung = Quantity * ExpansionFactor
	 *
	 * @param $Quantity
	 * @param $ExpansionFactor
	 * @return mixed
	 */
	public function calcExpansionQuantity($Quantity, $ExpansionFactor ) {
		$Quantity = (int)$Quantity;
		$ExpansionFactor = (float)$ExpansionFactor;
		return $Quantity * $ExpansionFactor;
	}

	//Rabattänderungen

	/**
	 * Gesamt-Deckungsbeitrag anteilig am Nettoumsatz = ( Gesamt-DB * 100 ) / Nettoumsatz
	 *
	 * @param float $TotalCoverageContribution
	 * @param float $NetSales
	 * @return float
	 */
	public function calcTotalCoverageContributionProportionNetSales($TotalCoverageContribution, $NetSales ) {
		$TotalCoverageContribution = (float)$TotalCoverageContribution;
		$NetSales = (float)$NetSales;
		return ( $TotalCoverageContribution * 100 ) / $NetSales;
	}

	/**
	 * Berechnung Delta zweier Werte
	 *
	 * @param float|int $ValueNew
	 * @param float|int $ValueOld
	 * @return float|int
	 */
	public function calcDelta($ValueNew, $ValueOld ) {
		$ValueNew = (float)$ValueNew;
		$ValueOld = (float)$ValueOld;
		return $ValueNew - $ValueOld;
	}

	/**
	 * Mehrmenge je Nettoumsatz = ( DeltaNetSales / NLP[Neu] ) * -1
	 *
	 * DeltaNetSales = Nettoumsatz[Neu] - Nettoumsatz[Alt]
	 *
	 * @param float $DeltaNetSales
	 * @param float $NetPriceNew
	 * @return float
	 */
	public function calcMultiplyQuantityNetSales($DeltaNetSales, $NetPriceNew ) {
		$DeltaNetSales = (float)$DeltaNetSales;
		$NetPriceNew = (float)$NetPriceNew;
		return ( $DeltaNetSales / $NetPriceNew ) * -1;
	}


	/**
	 * Mehrmenge je Deckungsbeitrag = ( Gesamt-Deckungsbeitrag[alt] / Declungsbeitrag[neu] ) - Menge
	 *
	 * @param float $TotalCoverageContributionOld
	 * @param float $CoverageContributionNew
	 * @param int $Quantity
	 * @return float
	 */
	public function calcMultiplyQuantityCoverageContribution($TotalCoverageContributionOld, $CoverageContributionNew, $Quantity ) {
		$TotalCoverageContributionOld = (float)$TotalCoverageContributionOld;
		$CoverageContributionNew = (float)$CoverageContributionNew;
		$Quantity = (int)$Quantity;
		return ( $TotalCoverageContributionOld / $CoverageContributionNew ) - $Quantity;
	}

	/**
	 * Gesamt-Mehrmenge je Nettoumsat = Mehrmenge + Menge
	 *
	 * @param int $MultiplyQuantity
	 * @param int $Quantity
	 * @return int
	 */
	public function calcTotalMultiplyQuantityNetSale($MultiplyQuantity, $Quantity ) {
		$MultiplyQuantity = (int)$MultiplyQuantity;
		$Quantity = (int)$Quantity;
		return $MultiplyQuantity + $Quantity;
	}

	/**
	 * Gesamt-Mehrmenge je Deckungsbeitrag = ( Gesamt-Deckungsbeitrag[alt] / Declungsbeitrag[neu] )
	 *
	 * @param float $TotalCoverageContributionOld
	 * @param float $CoverageContributionNew
	 * @return int
	 */
	public function calcTotalMultiplyQuantityCoverageContribution($TotalCoverageContributionOld, $CoverageContributionNew ) {
		$TotalCoverageContributionOld = (float)$TotalCoverageContributionOld;
		$CoverageContributionNew = (float)$CoverageContributionNew;
		return ( $TotalCoverageContributionOld / $CoverageContributionNew );
	}

	/**
	 * Steigerung im Zusatzabsatz je Nettoumsatz oder Deckungsbeitrag = ( Mehrmenge / $Quantity ) * 100
	 *
	 * @param $MultiplyQuantity
	 * @param $Quantity
	 * @return float
	 */
	public function calcIncreaseAdditionalSales($MultiplyQuantity, $Quantity ) {
		$MultiplyQuantity = (int)$MultiplyQuantity;
		return ( $MultiplyQuantity / $Quantity ) * 100;
	}

	/**
	 * Bruttoumsatz inkl. Mehrmenge = BLP[neu] * Gesamt-Mehrmenge (je Nettoumsatz oder Deckungsbeitrag)
	 *
	 * @param float $GrossPriceNew
	 * @param $TotalMultiplyQuantity
	 * @return float
	 */
	public function calcGrossSalesWithMultiplyQuantity($GrossPriceNew, $TotalMultiplyQuantity ) {
		$GrossPriceNew = (float)$GrossPriceNew;
		$TotalMultiplyQuantity = (int)$TotalMultiplyQuantity;
		return $GrossPriceNew * $TotalMultiplyQuantity;
	}

	/**
	 * NLP inkl. Mehrmenge = NLP[neu] * Gesamt-Mehrmenge (je Nettoumsatz oder Deckungsbeitrag)
	 *
	 * @param float $NetPriceNew
	 * @param $TotalMultiplyQuantity
	 * @return float
	 */
	public function calcNetSalesWithMultiplyQuantity($NetPriceNew, $TotalMultiplyQuantity ) {
		$NetPriceNew = (float)$NetPriceNew;
		$TotalMultiplyQuantity = (int)$TotalMultiplyQuantity;
		return $NetPriceNew * $TotalMultiplyQuantity;
	}

	/**
	 * Mehrmenge absolut Nettoumsatz nach Nettoumsatz-Steigerung = ( ( Nettoumsatz[NU, neu] * $Nettoumsatz[Steigerung (Form)] ) / NLP[neu] ) + Mehrmenge je Nettoumsatz
	 *
	 * @param float $NetSalesNew
	 * @param float $IncreaseNetSales
	 * @param float $NetPriceNew
	 * @param $MultiplyQuantityNetSales
	 * @return float
	 */
	public function calcMultiplyQuantityAfterNetSales($NetSalesNew, $IncreaseNetSales, $NetPriceNew, $MultiplyQuantityNetSales ) {
		$NetSalesNew = (float)$NetSalesNew;
		$IncreaseNetSales = (float)$IncreaseNetSales;
		$NetPriceNew = (float)$NetPriceNew;
		$MultiplyQuantityNetSales = $MultiplyQuantityNetSales;
		return ( ( $NetSalesNew * ( $IncreaseNetSales / 100 ) ) / $NetPriceNew ) + $MultiplyQuantityNetSales;
	}

	/**
	 * Mehrmenge absolut Deckungsbeitrag nach Deckungsbeitrags-Steigerung = ( ( Gesamt-Deckungsbeitrag[alt] * Deckungsbeitrag[Steigerung (Form)] ) / Deckungsbeitrag[neu] ) + Mehrmenge absolut je Deckungsbeitrag
	 *
	 * @param float $TotalCoverageContributionOld
	 * @param float $IncreaseCoverageContribution
	 * @param float $CoverageContributionNew
	 * @param $MultiplyQuantityCoverageContribution
	 * @return float
	 */
	public function calcMultiplyQuantityAfterCoverageContribution($TotalCoverageContributionOld, $IncreaseCoverageContribution, $CoverageContributionNew, $MultiplyQuantityCoverageContribution ) {
		$TotalCoverageContributionOld = (float)$TotalCoverageContributionOld;
		$IncreaseCoverageContribution = (float)$IncreaseCoverageContribution;
		$CoverageContributionNew = (float)$CoverageContributionNew;
		$MultiplyQuantityCoverageContribution = $MultiplyQuantityCoverageContribution;
		return ( ( $TotalCoverageContributionOld * $IncreaseCoverageContribution ) / $CoverageContributionNew ) + $MultiplyQuantityCoverageContribution;
	}

}