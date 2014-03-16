<?php

class FieldDefSet {

    /**
     * Field definition
     * @var Array[FieldDef] Field definition 
     */
    public $FieldDefs;
    
    /**
     * Create new set of field definitions
     */
    public function __construct() {
        $this->FieldDefs = array();
    }
    
    /**
     * Add a new field definition to the field definition set
     * @param FieldDef $pFieldDef Field definition
     */
    public function addFieldDef($pFieldDef) {
        $this->FieldDefs[] = $pFieldDef;
    }

}

?>
