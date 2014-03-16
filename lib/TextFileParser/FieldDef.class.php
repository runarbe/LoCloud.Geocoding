<?php

/**
 * Field Definitions
 */
class FieldDef {
    public $FieldName;
    public $StartOffset;
    public $EndOffset;
    public $DataType;
    public $ZeroBasedStart;
    public $Length;
    
    /**
     * Constructor
     * @param string $pFieldName The name of the field to add
     * @param integer $pStartOffset The start offset of the field to add
     * @param integer $pEndOffset The end offset of the field to add
     * @param TextFieldDataType $pDataType The data type of the field to add
     */
    public function __construct($pFieldName, $pStartOffset = null, $pEndOffset=null, $pDataType = TextFileDataType::String) {
        $this->FieldName = $pFieldName;
        $this->StartOffset = $pStartOffset;
        $this->EndOffset = $pEndOffset;
        $this->DataType = $pDataType;
        $this->Length = $pEndOffset - $pStartOffset;
        $this->ZeroBasedStart = $pStartOffset - 1;
    }
}

?>
