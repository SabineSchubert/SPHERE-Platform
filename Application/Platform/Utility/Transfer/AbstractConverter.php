<?php

namespace SPHERE\Application\Platform\Utility\Transfer;

use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Document;
use MOC\V\Component\Document\Exception\DocumentTypeException;

/**
 * Class AbstractConverter
 * @package SPHERE\Application\Platform\Utility\Transfer
 */
abstract class AbstractConverter extends Sanitizer
{

    const DEFAULT_CHAIN = '#';

    /** @var PhpExcel $Document */
    private $Document = null;
    /** @var int $SizeWidth */
    private $SizeWidth = 0;
    /** @var int $SizeHeight */
    private $SizeHeight = 0;
    /** @var array $FieldPointer */
    private $FieldPointer = array();
    /** @var array $SanitizePointer */
    private $SanitizePointer = array();
    /** @var array $SanitizeChain */
    private $SanitizeChain = array();

    /**
     *
     * @param FieldPointer $FieldPointer
     *
     * @return self
     */
    final public function setPointer(FieldPointer $FieldPointer)
    {

        $this->FieldPointer[$FieldPointer->getColumn()][] = $FieldPointer;
        return $this;
    }

    /**
     *
     * @param FieldSanitizer $FieldSanitizer
     *
     * @return self
     * @throws \Exception
     */
    final public function setSanitizer(FieldSanitizer $FieldSanitizer)
    {

        if (is_callable($FieldSanitizer->getCallback())) {
            $this->SanitizePointer[$FieldSanitizer->getColumn()][$FieldSanitizer->getField()]
                = $FieldSanitizer->getCallback();
        } else {
            /** @var array $Callback */
            $Callback = $FieldSanitizer->getCallback();
            throw new \Exception(end($Callback) . ' nicht verfügbar');
        }
        return $this;
    }

    /**
     * @param int $Offset
     * @param null|int $Length
     */
    final public function scanFile($Offset, $Length = null)
    {

        $ScanLength = ($Length ? ($Offset + $Length) : $this->SizeHeight);
        for ($RunHeight = (1 + $Offset); $RunHeight <= $ScanLength; $RunHeight++) {

            $Payload = array();
            for ($RunWidth = 0; $RunWidth < $this->SizeWidth; $RunWidth++) {
                $Column = \PHPExcel_Cell::stringFromColumnIndex($RunWidth);
                $Value = $this->Document->getValue($this->Document->getCell($Column . $RunHeight));
                if (isset($this->FieldPointer[$Column])) {
                    $PointerList = $this->FieldPointer[$Column];
                    /** @var FieldPointer $Pointer */
                    foreach ((array)$PointerList as $Pointer) {
                        $Field = $Pointer->getField();
                        if (!isset($this->SanitizePointer[$Column][$Field]) && !isset($this->SanitizeChain[$Field])) {
                            // Chain Sanitizer
                            $SanitizedValue = $Value;
                            // Always-Default
                            if (isset($this->SanitizeChain[self::DEFAULT_CHAIN])) {
                                foreach ((array)$this->SanitizeChain[self::DEFAULT_CHAIN] as $Sanitizer) {
                                    $SanitizedValue = $Sanitizer($SanitizedValue);
                                }
                            }
                        } else {
                            // Chain Sanitizer
                            $SanitizedValue = $Value;
                            // Always-Default
                            if (isset($this->SanitizeChain[self::DEFAULT_CHAIN])) {
                                foreach ((array)$this->SanitizeChain[self::DEFAULT_CHAIN] as $Sanitizer) {
                                    $SanitizedValue = $Sanitizer($SanitizedValue);
                                }
                            }
                            // Field-Bound
                            if (isset($this->SanitizeChain[$Field])) {
                                foreach ((array)$this->SanitizeChain[$Field] as $Sanitizer) {
                                    $SanitizedValue = $Sanitizer($SanitizedValue);
                                }
                            }
                            // Current Sanitizer
                            if (isset($this->SanitizePointer[$Column][$Field])) {
                                $Sanitize = $this->SanitizePointer[$Column][$Field];
                                $SanitizedValue = $Sanitize($SanitizedValue);
                            }
                        }
                        $Payload[$Pointer->getColumn()][$Pointer->getField()] = $SanitizedValue;
                    }
                }
            }
            $this->runConvert($Payload);
        }
    }

    /**
     * @param array $Row
     *
     * @return mixed|void
     */
    abstract public function runConvert($Row);

    /**
     * @param string $File
     *
     * @return self
     * @throws DocumentTypeException
     * @throws \Exception
     */
    final protected function loadFile($File)
    {

        $this->Document = Document::getDocument($File);
        if( !$this->Document instanceof PhpExcel ) {
            throw new \Exception( 'Native file support for spreadsheet only!' );
        }
        $this->SizeHeight = $this->Document->getSheetRowCount();
        $this->SizeWidth = $this->Document->getSheetColumnCount();
        return $this;
    }

    /**
     * @param array|callable|\Closure $Callback
     * @param string $Chain
     *
     * @return self
     * @throws \Exception
     */
    final protected function addSanitizer($Callback, $Chain = AbstractConverter::DEFAULT_CHAIN)
    {
        if (is_callable($Callback)) {
            $this->SanitizeChain[$Chain][] = $Callback;
        } else {
            throw new \Exception('Sanitizer not available: ' . end($Callback));
        }
        return $this;
    }
}
