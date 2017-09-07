<?php
/**
 * Created by PhpStorm.
 * User: schubert
 * Date: 30.03.2017
 * Time: 09:48
 */

namespace SPHERE\Application\Competition\DataWareHouse\Service;


use SPHERE\Application\Competition\DataWareHouse\Service\Entity\TblCompetition_Position;
use SPHERE\Application\Competition\DataWareHouse\Service\Entity\ViewCompetition;
use SPHERE\Application\Competition\DataWareHouse\Service\Entity\ViewCompetitionPart;
use SPHERE\Application\Platform\System\Protocol\Protocol;
use SPHERE\Application\Reporting\DataWareHouse\DataWareHouse;
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
     * @param $Id
     * @return null|\SPHERE\System\Database\Binding\AbstractEntity|\SPHERE\System\Database\Fitting\Element
     */
    public function getCompetitionPositionById( $Id ) {
        $TableCompetitionPosition = new TblCompetition_Position();
        return $this->getCachedEntityById(  __METHOD__, $this->getEntityManager(), $TableCompetitionPosition->getEntityShortName(), $Id );
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

            $SqlCompetitionSearch = $QueryBuilder->getQuery()/*->getSQL()*/;

            //Debugger::screenDump($SqlCompetitionSearch);


            if( $SqlCompetitionSearch->getResult() ) {
                return $SqlCompetitionSearch->getResult();
            }
            else {
                return null;
            }

        }

    /**
     * @param string $PartNumber
     * @return array|null
     */
    public function getCompetitionDirectSearchByPartNumber( $PartNumber ) {

        if( $PartNumber ) {
            $Manager = $this->getEntityManager();
            $QueryBuilder = $Manager->getQueryBuilder();

            $ViewCompetition = new ViewCompetition();
            $ViewCompetitionAlias = $ViewCompetition->getEntityShortName();

            $EntityPart = DataWareHouse::useService()->getPartByNumber( $PartNumber );
            $EntitySection = $EntityPart->fetchSectionListCurrent();

            $SqlCompetitionDirectSearch = $QueryBuilder
                ->select( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_POSITION_COMPETITOR.' AS Competitor' )
                ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_POSITION_MANUFACTURER.' AS Manufacturer' )
                ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_MAIN_CREATION_DATE.' AS CreationDate' )
                ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_POSITION_PRICE_NET.' AS Data_PriceNet' )
                ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_POSITION_PRICE_GROSS.' AS Data_PriceGross' )
                ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_POSITION_DISCOUNT.' AS Data_Discount' )
                ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_POSITION_DISTRIBUTOR_OR_CUSTOMER.' AS DistributorOrCustomer' )
                ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_POSITION_COMMENT.' AS Comment' )
                ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_MAIN_RETAIL_NUMBER.' AS RetailNumber' )
                ->addSelect( $ViewCompetitionAlias.'.'.$ViewCompetition::TBL_COMPETITION_POSITION_ID.' AS PositionId' )
                ->from($ViewCompetition->getEntityFullName(), $ViewCompetitionAlias)
                ->Where($ViewCompetitionAlias . '.' . $ViewCompetition::TBL_REPORTING_PART_NUMBER . ' = :' . $ViewCompetition::TBL_REPORTING_PART_NUMBER)
                ->setParameter($ViewCompetition::TBL_REPORTING_PART_NUMBER, $PartNumber );



            switch($EntitySection['Name']) {
                case 'Lkw':
                    break;
                default:
                    $SqlCompetitionDirectSearch = $QueryBuilder
                        ->andWhere(
                            $QueryBuilder->expr()->gte(
                                $ViewCompetitionAlias . '.' . $ViewCompetition::TBL_COMPETITION_MAIN_CREATION_DATE, ':CREATION_DATE'
                            )
                        )
                        ->setParameter( 'CREATION_DATE', new \DateTime( '01.01.'.(date('Y',time())-1).' 00:00:00' ) );
                    break;
            }


            $QueryBuilder->orderBy( $QueryBuilder->expr()->desc( $ViewCompetitionAlias . '.' . $ViewCompetition::TBL_COMPETITION_MAIN_CREATION_DATE ) );


            $SqlCompetitionDirectSearch = $QueryBuilder
                ->getQuery()->useQueryCache(true)->useResultCache(true,5184000);

            //Debugger::screenDump($SqlCompetitionDirectSearch->getSQL());

            if( $SqlCompetitionDirectSearch->getResult() ) {
                return $SqlCompetitionDirectSearch->getResult();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    /**
     * @param string $PartNumber
     * @return array|null
     */
    public function getCompetitionAdditionalInfoDirectSearchByPartNumber( $PartNumber ) {

        if( $PartNumber ) {

            $Manager = $this->getEntityManager();
            $QueryBuilder = $Manager->getQueryBuilder();

            $ViewCompetitionPart = new ViewCompetitionPart();
            $ViewCompetitionPartAlias = $ViewCompetitionPart->getEntityShortName();
            $SqlCompetitionAdditionalInfoDirectSearch = $QueryBuilder
                ->select( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_SEGMENT.' AS Segment' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_SEASON .' AS Season' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_ASSORTMENT.' AS Assortment' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_SECTION.' AS Section' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_WIDTH.' AS Width' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_ASPECT_RATIO.' AS AspectRatio' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_RIM_INCH.' AS RimInch' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_LOAD_INDEX.' AS LoadIndex' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_SPEED_INDEX.' AS SpeedIndex' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_DIMENSION_TYRE.' AS DimensionTyre' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_PROFIL.' AS Profil' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_MANUFACTURER.' AS Manufacturer' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_NUMBER_TYRE.' AS NumberTyre' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_DIRECTION.' AS Direction' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_AXLE.' AS Axle' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_DIMENSION_RIM.' AS DimensionRim' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_DESIGN_RIM.' AS DesignRim' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_NUMBER_RIM.' AS NumberRim' )
                    ->addSelect( $ViewCompetitionPartAlias.'.'.$ViewCompetitionPart::TBL_COMPETITION_PART_SERIES.' AS Series' )
                ->from($ViewCompetitionPart->getEntityFullName(), $ViewCompetitionPartAlias)
                ->Where($ViewCompetitionPartAlias . '.' . $ViewCompetitionPart::TBL_REPORTING_PART_NUMBER . ' = :' . $ViewCompetitionPart::TBL_REPORTING_PART_NUMBER)
                ->setParameter($ViewCompetitionPart::TBL_REPORTING_PART_NUMBER, $PartNumber )
                ->getQuery();

            if( $SqlCompetitionAdditionalInfoDirectSearch->getResult() ) {
                return $SqlCompetitionAdditionalInfoDirectSearch->getResult();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //Update bzw. Delete

    /**
     * @param int $CompetitionPositionId
     * @return null|TblCompetition_Position
     */
    public function deleteCompetitionPosition( $CompetitionPositionId ){
        if($CompetitionPositionId) {
            $Manager = $this->getEntityManager();
            /** @var TblCompetition_Position $Entity */
            $Entity = $Manager->getEntityById( 'TblCompetition_Position', $CompetitionPositionId );
            $Protocol = clone $Entity;
            if (null !== $Entity) {
                $Entity->setEntityRemove(true);
                $Manager->saveEntity($Entity);
                Protocol::useService()->createUpdateEntry($this->getConnection()->getDatabase(), $Protocol, $Entity);
                return $Entity;
            }
            return null;
        }
        return null;
    }
}