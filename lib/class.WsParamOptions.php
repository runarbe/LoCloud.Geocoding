<?php

/**
 * Class to define a web service parameter
 */
class WsParamOptions {

    public $mMandatory;
    public $mDataType;
    public $mDefaultValue;

    function __construct($pMandatory = true, $pDataType = WsDataTypes::mNull, $pDefaultValue = "null") {
        $this->mMandatory = $pMandatory;
        $this->mDataType = $pDataType;
        $this->mDefaultValue = $pDefaultValue;
    }

}
?>
