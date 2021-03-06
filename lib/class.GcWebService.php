<?php

/**
 * Parent class for LoCloud geocoding web services
 * @author runarbe
 */
abstract class GcWebService {

    /**
     * They keyword requested by the user
     * @var String
     */
    protected $_operation;

    /**
     * Shared return object
     * @var W2UIRetObj 
     */
    protected $_result;

    /**
     * Main function body - will be executed if operation parameter is present
     */
    abstract protected function _execute();

    /**
     * User-defined string filter
     * @param String $pString
     * @return boolean
     */
    public static function LC_STRING_FILTER($pString) {
        if ($pString === '' || $pString === null || !isset($pString)) {
            return false;
        } else {
            return $pString;
        }
    }

    /**
     * Default constructor - called if not implemented on child objects
     */
    public function __construct() {
        $this->_result = new W2UIRetObj();

        $mCheck = array(
            "cmd" => new ParamOpt(false,
                    WsDataTypes::mString),
            "action" => new ParamOpt(false,
                    WsDataTypes::mString),
            "method" => new ParamOpt(false,
                    WsDataTypes::mString)
        );

        $mActions = $this->_getParams($mCheck);

        if (!count($mActions) > 1) {
            $this->_result->setFailure(ErrorMsgs::reqParamMissing,
                    "cmd|action|method");
        } else {
            $this->_result->setSuccess();
            $this->_operation = reset($mActions);
        }
    }

    /**
     * Get the names of all methods supported by this web service
     * @return void
     */
    public function getCapabilities() {
        $mRet = array();
        $mReflector = new ReflectionClass(get_class($this));
        foreach ($mReflector->getMethods() as $mMethod) {
            $mRet[] = var_export($mMethod->name,
                    true);
        }
        $this->_result->addMsg(implode(" * ",
                        $mRet));
        return;
    }

    /**
     * Returns error when operation is not supported
     * @return void
     */
    protected function _notSupported() {
        $this->_result->setFailure(ErrorMsgs::operationNotSupported($this->_operation));
        return;
    }

    /**
     * Checks the current state of the web serviec
     * @return bool True if success, false if error
     */
    protected function _isSuccess() {
        return $this->_result->getStatus();
    }

    /**
     * Runs the web service, optionally ignoring the command parameter
     * @param bool $pIgnoreOperation
     */
    public function run($pIgnoreOperation = false) {
        if ($this->_isSuccess() || $pIgnoreOperation) {
            $this->_execute();
        } else {
            $this->_result->echoJson();
        }
    }

    /**
     * Validate and get Web Service parameters or their defaults
     * @param Array $pParamArray
     */
    protected function _getParams($pParamArray) {

        $mReturn = array();
        $mErrCount = 0;

        foreach ($pParamArray as $mParam => $mAttr) {

            /* @var $mAttr ParamOpt */

            $mFilter = null;
            $mFlag = null;
            $mVal = null;

            switch ($mAttr->mDataType) {
                case WsDataTypes::mNull:
                    $mFilter = FILTER_DEFAULT;
                    break;
                case WsDataTypes::mString:
                    $mFilter = FILTER_CALLBACK;
                    $mFlag = array("options"=>array(__CLASS__, "LC_STRING_FILTER"));
                    break;
                case WsDataTypes::mInteger:
                    $mFilter = FILTER_VALIDATE_INT;
                    break;
                case WsDataTypes::mDouble:
                    $mFilter = FILTER_VALIDATE_FLOAT;
                    break;
                case WsDataTypes::mBool:
                    $mFilter = FILTER_VALIDATE_BOOLEAN;
                    break;
                case WsDataTypes::mEmail:
                    $mFilter = FILTER_VALIDATE_EMAIL;
                    break;
                case WsDataTypes::mUrl:
                    $mFilter = FILTER_VALIDATE_URL;
                    break;
                case WsDataTypes::mArray:
                    $mFilter = FILTER_DEFAULT;
                    $mFlag = FILTER_REQUIRE_ARRAY;
                    break;
                default:
                    $mFilter = FILTER_DEFAULT;
            }

            if ($mFlag !== null) {
                $mVal = filter_input(INPUT_POST,
                        $mParam,
                        $mFilter,
                        $mFlag);
            } else {
                $mVal = filter_input(INPUT_POST,
                        $mParam,
                        $mFilter);
            }

            if ($mVal === false && $mAttr->mDataType !== WsDataTypes::mBool) {
                $this->_result->addMsg(ErrorMsgs::invalidParam,
                        $mParam);
            }

            if ($mVal !== null && ($mVal !== false || $mAttr->mDataType === WsDataTypes::mBool)) {
                $mReturn[$mParam] = $mVal;
            } else if (($mVal === false || $mVal === null) && $mAttr->mMandatory && $mAttr->mDefaultValue !== null) {
                $mReturn[$mParam] = $mAttr->mDefaultValue;
                $this->_result->addMsg(ErrorMsgs::optParamMissing,
                        $mParam);
            } else if (($mVal === false || $mVal === null) && $mAttr->mMandatory && $mAttr->mDefaultValue === null) {
                $this->_result->addMsg(ErrorMsgs::reqParamMissing,
                        $mParam);
                $mErrCount++;
            }
        }

        if ($mErrCount === 0) {
            $this->_result->setSuccess();
        } else {
            $this->_result->setFailure();
        }
        return $mReturn;
    }
    
    /**
     * If user is not authenticated, exits with error message
     * @return void
     */
    protected function requireSession() {
        if (!isLoggedIn()) {
            $this->_result->setFailure(ErrorMsgs::sessionRequired, "Web Service");
            $this->_result->echoJson();
            exit;
        }
        return;
    }

    /**
     * Execute a HttpGet request towards the specified URI adding the values
     * contained in $pData to the QueryString
     * @param String $pUrl URI, if contains query string, please omit $pData
     * @param String $pData Key/value pairs formatted as key=value&key2=value2
     * @return String JSON string
     */
    protected function _curlGetJSON($pUrl,
            $pData = null) {

        $mCurl = curl_init();

        if ($pData !== null) {
            $pUrl = sprintf("%s?%s",
                    $pUrl,
                    http_build_query($pData));
        }

        curl_setopt($mCurl,
                CURLOPT_URL,
                $pUrl);
        curl_setopt($mCurl,
                CURLOPT_RETURNTRANSFER,
                true);

        curl_setopt($mCurl,
                CURLOPT_HEADER,
                "Content-type: application/json");
        curl_setopt($mCurl,
                CURLOPT_HEADER,
                "Accept: application/json");
        curl_setopt($mCurl,
                CURLOPT_POSTFIELDS,
                "{}");

        return curl_exec($mCurl);
    }

}
