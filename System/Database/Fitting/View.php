<?php

namespace SPHERE\System\Database\Fitting;

use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Class View
 *
 * @package SPHERE\System\Database\Fitting
 */
class View
{

    /** @var string $Pattern */
    private $Pattern = '|^[a-z\söäüß\-&\(\)]+$|is';
    /** @var string $Name */
    private $Name;
    /** @var Structure $Structure */
    private $Structure;

    /** @var array $LinkList */
    private $LinkList = array();
    /** @var array $AllowRemovedList */
    private $AllowRemovedList = array();

    /**
     * View constructor.
     *
     * @param string $Name DB-UNIQUE!
     * @param Structure $Structure
     *
     * @throws \Exception
     */
    public function __construct(Structure $Structure, $Name)
    {

        if (!preg_match($this->Pattern, $Name)) {
            throw new \Exception(__CLASS__ . ' > Pattern mismatch: (' . $Name . ') [' . $this->Pattern . ']');
        }
        $this->Name = $Name;
        $this->Structure = $Structure;
    }

    /**
     * Add ORM-Node-Link
     *
     * @param Element $From
     * @param string $FromKey
     * @param Element $To
     * @param string $ToKey Default: "Id"
     *
     * @return $this
     */
    public function addLink(Element $From, $FromKey, Element $To = null, $ToKey = 'Id')
    {

        array_push($this->LinkList, array('From' => $From, 'FromKey' => $FromKey, 'To' => $To, 'ToKey' => $ToKey));
        return $this;
    }

    /**
     * Include rows containing 'EntityRemoved' !== null in result set for this table
     *
     * @param Element $Entity
     * @return $this
     */
    public function allowRemovedEntities(Element $Entity)
    {
        array_push($this->AllowRemovedList, $Entity->getEntityShortName());
        return $this;
    }

    /**
     * Get Doctrine-View Object
     *
     * @return \Doctrine\DBAL\Schema\View
     */
    public function getView()
    {

        return new \Doctrine\DBAL\Schema\View($this->getName(), $this->buildView()->getSQL());
    }

    /**
     * Get View-Name
     *
     * @return string
     */
    public function getName()
    {

        return $this->Name;
    }

    /**
     * @return QueryBuilder
     */
    private function buildView()
    {

        $TableList = $this->LinkList;
        $QueryBuilder = $this->Structure->getQueryBuilder();

        // SELECT
        $Select = array();
        /**
         * @var int $Index
         * @var Element[]|string $Link
         */
        foreach ($TableList as $Index => $Link) {

            if ($Index === 0) {
                $Select[] = $this->convertPropertyList($Link['From'], false);
                $Select[] = $this->convertPropertyList($Link['From']);
                if ($Link['To']) {
                    $Select[] = $this->convertPropertyList($Link['To']);
                }
            } else {
                if ($Link['To']) {
                    $Select[] = $this->convertPropertyList($Link['To']);
                }
            }
        }
        $Select = implode(', ', $Select);
        $QueryBuilder->select($Select);
        // FROM
        /** @var Element $FromSource */
        $FromSource = $TableList[0]['From'];
        $QueryBuilder->from(
            $this->convertWordCase($FromSource->getEntityShortName()),
            $FromSource->getEntityShortName()
        );
        // JOIN
        foreach ($TableList as $Link) {
            /** @var Element $From */
            $From = $Link['From'];
            /** @var Element $To */
            $To = $Link['To'];
            // Condition
            if( in_array( $To->getEntityShortName(), $this->AllowRemovedList ) ) {
                $Condition = $QueryBuilder->expr()->eq($From->getEntityShortName() . '.' . $Link['FromKey'],
                        $To->getEntityShortName() . '.' . $Link['ToKey']);

            } else {
                $Condition = $QueryBuilder->expr()->andX(
                    $QueryBuilder->expr()->eq($From->getEntityShortName() . '.' . $Link['FromKey'],
                        $To->getEntityShortName() . '.' . $Link['ToKey']),
                    $QueryBuilder->expr()->isNull($To->getEntityShortName() . '.EntityRemove')
                );
            }


            if ($Link['To']) {
                $QueryBuilder->leftJoin(
                    $From->getEntityShortName(),
                    $this->convertWordCase($To->getEntityShortName()),
                    $To->getEntityShortName(),
                    $Condition
                );
            }
        }

        if( !in_array( $FromSource->getEntityShortName(), $this->AllowRemovedList ) ) {
            $QueryBuilder->where(
                $QueryBuilder->expr()->isNull($FromSource->getEntityShortName() . '.EntityRemove')
            );
        }

        return $QueryBuilder;
    }

    /**
     * Prepare Property-Selector
     *
     * @param Element $Entity
     * @param bool $Prefix
     *
     * @return string
     */
    private function convertPropertyList(Element $Entity, $Prefix = true)
    {

        if ($Prefix) {
            $PropertyList = (new \ReflectionClass($Entity))->getProperties(\ReflectionProperty::IS_PROTECTED);
        } else {
            $PropertyList = (new \ReflectionClass('SPHERE\System\Database\Fitting\Element'))->getProperties(\ReflectionProperty::IS_PROTECTED);
        }

        array_walk($PropertyList, function (\ReflectionProperty &$Property) use ($Entity, $Prefix) {

            $Property = $this->convertSelectAlias($Property->getName(), $Entity->getEntityShortName(), $Prefix);

        });
        $PropertyList = array_filter( $PropertyList );

        return implode(', ', $PropertyList);
    }

    /**
     * Prepend Table-Alias
     *
     * @param string $Field
     * @param string $Table
     * @param bool $Prefix
     * @return string
     *
     * @throws \Exception
     */
    private function convertSelectAlias($Field, $Table, $Prefix = true)
    {

        if (false === $Prefix) {
            return $Table . '.' . $Field . ' ' . $Field;
        } else {
            $Alias = $Table . '_' . $Field;
            if (strlen($Alias) > $this->Structure->getPlatform()->getMaxIdentifierLength()) {
                // remove n:m id columns, because we got id of both entities and alias name will be long ( > 64 )
                if (preg_match('!^tbl.*_tbl.*$!is', $Alias)) {
                    return null;
                }
            }
            if (strlen($Alias) > $this->Structure->getPlatform()->getMaxIdentifierLength()) {
                throw new \Exception(
                    'Alias "'.$Alias.'" exceeds max length of '
                    .$this->Structure->getPlatform()->getMaxIdentifierLength().' characters!'
                );
            }
            return $Table . '.' . $Field . ' ' . $Alias;
        }
    }

    /**
     * TODO: Replace with Entity-Annotation-Tag-Reader (ORM:Table)
     *
     * @param $TableName
     *
     * @return string
     */
    private function convertWordCase($TableName)
    {

        return $TableName;
        // TODO: Bypass: return preg_replace('!^Tbl!', 'tbl', $TableName);
    }

    /**
     * Get VIEW SELECT
     *
     * @internal Usage: Debug
     * @return string
     */
    public function getSQL()
    {

        return $this->buildView()->getSQL();
    }
}
