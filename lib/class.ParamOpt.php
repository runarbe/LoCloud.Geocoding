<?php

/**
 * Class to define a web service parameter
 */
class ParamOpt {

    /**
     * A flag indicating whether the parameter is required or not
     * @var bool 
     */
    public $mMandatory;
    
    /**
     * The data type of the parameter
     * @var WsDataTypes One of the constants from <WsDataTypes>
     */
    public $mDataType;
    
    /**
     * The default value to use if the parameter is missing or does not validate
     * @var Mixed 
     */
    public $mDefaultValue;

    /**
     * Constructor
     * @param bool $pMandatory Whether the field is mandatory (true) or not (false)
     * @param WsDataTypes $pDataType The data type of the field
     * @param Mixed $pDefaultValue The default value for the field
     */
    function __construct($pMandatory = true, $pDataType = WsDataTypes::mNull, $pDefaultValue = null) {
        $this->mMandatory = $pMandatory;
        $this->mDataType = $pDataType;
        $this->mDefaultValue = $pDefaultValue;
    }

}
