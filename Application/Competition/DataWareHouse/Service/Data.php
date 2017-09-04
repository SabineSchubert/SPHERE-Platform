<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 30.03.2017
 * Time: 09:48
 */

namespace SPHERE\Application\Competition\DataWareHouse\Service;


use SPHERE\Application\Competition\DataWareHouse\Service\Entity\ViewCompetition;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_MarketingCode;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_Part;
use SPHERE\Application\Reporting\DataWareHouse\Service\Entity\TblReporting_ProductGroup;
use SPHERE\System\Database\Binding\AbstractData;
use SPHERE\System\Extension\Repository\Debugger;

class Data extends AbstractData
{

    /**
     * @return void
     */
    public function setupDatabaseContent()
    {
        // TODO: Implement setupDatabaseContent() method.
    }

    /**
     * @param string $PartNumber
     * @param string $MarketingCode
     * @param $ProductGroupNumber
     * @param string $PeriodFrom
     * @param string $PeriodTo
     * @param int $GroupBy
     */
    public function getCompetitionSearch( $PartNumber = null, $MarketingCode = null, $ProductGroupNumber = null, $PeriodFrom = null, $PeriodTo = null, $GroupBy ) {
        Debugger::screenDump($GroupBy);
            $Manager = $this->getEntityManager();
            $QueryBuilder = $Manager->getQueryBuilder();

            $ViewCompetition = new ViewCompetition();
            $ViewCompetitionAlias = $ViewCompetition->getEntityShortName();

            switch ($GroupBy) {
                case '1':
                    $SqlCompetitionSearch = $QueryBuilder
                        ->select( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_REPORTING_PART_NUMBER.' AS PartNumber' )
                        ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_REPORTING_MARKETING_CODE_NUMBER.' AS MarketingCode' )
                        ->addSelect(  'COUNT('.$ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_POSITION_COMPETITOR.') AS Data_CountQuantity' );
                    break;
                case '2':
                    $SqlCompetitionSearch = $QueryBuilder
                        ->select( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_REPORTING_MARKETING_CODE_NUMBER.' AS MarketingCode' )
                        ->addSelect(  'COUNT( distinct'.$ViewCompetitionAlias.'.'.$ViewCompetition::TBL_REPORTING_PART_NUMBER.') AS Data_CountQuantity' );
                    break;
                case '3':
                    $SqlCompetitionSearch = $QueryBuilder
                        ->select( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_REPORTING_PRODUCT_GROUP_NUMBER.' AS ProductGroupNumber' )
                        ->addSelect(  'COUNT( distinct '.$ViewCompetitionAlias.'.'.$ViewCompetition::TBL_REPORTING_PART_NUMBER.') AS Data_CountQuantity' );
                    break;
            }

            $SqlCompetitionSearch = $QueryBuilder
                ->from($ViewCompetition->getEntityFullName(), $ViewCompetitionAlias)
                ->where( ' 1=1 ' );

            if( $PartNumber ) {
                $SqlCompetitionSearch = $QueryBuilder
                    ->andWhere($ViewCompetitionAlias . '.' . $ViewCompetition::TBL_REPORTING_PART_NUMBER . ' = :' . $ViewCompetition::TBL_REPORTING_PART_NUMBER)
                    ->setParameter($ViewCompetition::TBL_REPORTING_PART_NUMBER, $PartNumber );
            }
            if( $MarketingCode ) {
                $SqlCompetitionSearch = $QueryBuilder
                    ->andWhere($ViewCompetitionAlias . '.' . $ViewCompetition::TBL_REPORTING_MARKETING_CODE_NUMBER . ' = :' . $ViewCompetition::TBL_REPORTING_MARKETING_CODE_NUMBER)
                    ->setParameter($ViewCompetition::TBL_REPORTING_MARKETING_CODE_NUMBER, $MarketingCode );
            }
            if( $ProductGroupNumber ) {
                $SqlCompetitionSearch = $QueryBuilder
                    ->andWhere($ViewCompetitionAlias . '.' . $ViewCompetition::TBL_REPORTING_PRODUCT_GROUP_NUMBER . ' = :' . $ViewCompetition::TBL_REPORTING_PRODUCT_GROUP_NUMBER)
                    ->setParameter($ViewCompetition::TBL_REPORTING_PRODUCT_GROUP_NUMBER, $ProductGroupNumber );
            }
            if($PeriodFrom != '') {
                $SqlCompetitionSearch = $QueryBuilder
                    ->andWhere(
                        $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_MAIN_CREATION_DATE.' >= :'.$ViewCompetition::TBL_COMPETITION_MAIN_CREATION_DATE.'_FROM'
                    )
                    ->setParameter( $ViewCompetition::TBL_COMPETITION_MAIN_CREATION_DATE.'_FROM', date('Y-m-d',strtotime($PeriodFrom)) );
            }

            if($PeriodTo != '') {
                $SqlCompetitionSearch = $QueryBuilder
                    ->andWhere(
                        $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_MAIN_CREATION_DATE.' <= :'.$ViewCompetition::TBL_COMPETITION_MAIN_CREATION_DATE.'_To'
                    )
                    ->setParameter( $ViewCompetition::TBL_COMPETITION_MAIN_CREATION_DATE.'_To', date('Y-m-d',strtotime($PeriodTo)) );
            }

            switch ($GroupBy) {
                case '1':
                    $SqlCompetitionSearch = $QueryBuilder
                          ->groupBy( $ViewCompetitionAlias . '.' . $ViewCompetition::TBL_REPORTING_PART_NUMBER )
                          ->addGroupBy( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_REPORTING_MARKETING_CODE_NUMBER );
                    break;
                case '2':
                    $SqlCompetitionSearch = $QueryBuilder
                        ->groupBy( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_REPORTING_MARKETING_CODE_NUMBER );
                    break;
                case '3':
                    $SqlCompetitionSearch = $QueryBuilder
                        ->groupBy( $ViewCompetitionAlias . '.' . $ViewCompetition::TBL_REPORTING_PRODUCT_GROUP_NUMBER );
                    break;
            }

            $SqlCompetitionSearch = $QueryBuilder->getQuery();//->getSQL();


            if( $SqlCompetitionSearch->getResult() ) {
                return $SqlCompetitionSearch->getResult();
            }
            else {
                return null;
            }

        }
}