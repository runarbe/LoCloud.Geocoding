<?php
/**
 * Object to return from web service
 * @author (Stein) Runar Bergheim
 */
class WsRetObj {

    /**
     * Result status
     * 
     * @var WsStatus Default value is <wsStatus::success>, set to <wsStatus::error> in course of execution to change
     */
    public $v = WsStatus::success;

    /**
     * Error messages
     * 
     * @var Array Set of error messages to be returned on failure
     */
    public $m = array();

    /**
     * Data to be returned to client
     * 
     * @var Array Set of data to be returned on success 
     */
    public $d = array();

    /**
     * Add a message to the return object
     * 
     * @param String $pMsg An error message. Typically a constant from <wsErrors> concatenated with information specific to the operation that is being executed.
     */
    public function addMsg
    ($pMsg, $pAdditional = false) {
        if (!$pAdditional) {
            $this->m[
                    ] = $pMsg;
        } else {
            $this->m[
                    ] = $pMsg . " (" . $pAdditional . ")";
        }
    }

    /**
     * Add a row of data to the return object
     * 
     * @param Object $pDataObj Any object that can be serialized into a JSON
     * string, typically an associative array
     * @param String $pDataObjKey If specified, the object will be added to the
     * associative array under this key.
     * @return void
     */
    public function addData($pDataObj, $pDataObjKey = null) {
        if ($pDataObjKey === null) {
            $this->d[] = $pDataObj;
        } else {
            $this->d[$pDataObjKey] = $pDataObj;
        }
        return;
    }

    /**
     * Return the result object as a JSON string
     * 
     * @return String JSON
     */
    public function getResult() {
        $mResults = array();
        $mResults["v"] = $this->v;
        if (count($this->d) >= 1) {
            $mResults["d"] = $this->d;
        }
        $mResults["m"] = $this->m;
        return json_encode(
                $mResults
        );
    }

    /**
     * Outputs the result object as a JSON string
     * 
     * @return void
     */
    public function outputResult() {
        echo $this->getResult();
        return;
    }

    /**
     * When called, this function sets the state of the result object to success
     * 
     * @param WsErrors $pMsg
     */
    public function setSuccess($pMsg = null, $pAddMsg = null) {
        $this->v = WsStatus::success;
        if ($pMsg != null) {
            $this->addMsg($pMsg, $pAddMsg);
        }
    }

    /**
     * When called, this function sets the state of the result object to failure
     * 
     * @param WsErrors $pMsg
     */
    public function setFailure($pMsg = null, $pAddMsg = null) {
        $this->v = WsStatus::failure;
        if ($pMsg != null) {
            $this->addMsg($pMsg, $pAddMsg);
        }
    }

}

?>
