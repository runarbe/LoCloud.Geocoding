<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of W2UIRetObj
 *
 * @author runarbe
 */
class W2UIRetObj implements iRetObj {

    /**
     * The status of the Web Service result
     * @var String One of 'success' or 'error' 
     */
    public $status = "error";

    /**
     * The total number of records - not limited to the subset returned by the query
     * if using limit and offset
     * @var Int
     */
    public $total = 0;

    /**
     * An array of data elements returned by the Web Service
     * @var Array 
     */
    public $records = array();

    /**
     * Any error messages or other returned from the Web Service
     * @var String 
     */
    public $message = "";

    /**
     * Add the supplied object to the records array
     * @param Mixed $pObject
     */
    public function addRecord($pObject) {
        $this->records[] = $pObject;
    }

    /**
     * Set the value of the records property to the supplied array
     * @param Array $pArray
     */
    public function setRecords($pArray) {
        $this->records = $pArray;
    }

    /**
     * Set the status of the result object to success
     * @param String $pMessage Optional message to be logged
     * @param String $pAdditionalMessage Optional additional message to be appended to message in parenthesis
     */
    public function setSuccess($pMessage = null,
            $pAdditionalMessage = null) {
        $this->status = "success";
        if ($pMessage !== null || $pAdditionalMessage !== null) {
            $this->addMsg($pMessage,
                    $pAdditionalMessage);
        }
    }

    /**
     * Set the status of the result object to failure
     * @param String $pMessage Optional message to be logged
     * @param String $pAdditionalMessage Optional additional message to be appended to message in parenthesis
     */
    public function setFailure($pMessage = null,
            $pAdditionalMessage = null) {
        $this->status = "error";
        if ($pMessage !== null || $pAdditionalMessage !== null) {
            $this->addMsg($pMessage,
                    $pAdditionalMessage);
        }
    }

    /**
     * Add a message to the result object message parameter
     * @param String $pMessage The message to be added to the result object
     * @param String $pAdditionalMessage Will be added to the main message in parenthesis if present
     */
    public function addMsg($pMessage,
            $pAdditionalMessage = null) {
        if ($pAdditionalMessage === null) {
            $this->message .= $pMessage . ". ";
        } else {
            $this->message .= $pMessage . " (" . $pAdditionalMessage . "). ";
        }
    }

    /**
     * Get the result object as a JSON string
     * @return String JSON string
     */
    public function getResult() {
        return json_encode($this);
    }
    
    /**
     * Adds a value to the records variable of the result object
     * @param Mixed $pDataObj
     * @param String $pDataObjKey
     * @return void
     */
    public function addData($pDataObj,
            $pDataObjKey = null) {
        if ($pDataObjKey !== null) {
            $this->records[$pDataObjKey] = $pDataObj;
        } else {
            $this->records[] = $pDataObj;
        }
        return;
    }

    /**
     * Output the result object as JSON
     */
    public function echoJson() {
        header("Content-type: application/json;");
        echo $this->getResult();
    }

    /**
     * Return the status of the result object as a boolean value
     * @return boolean True on success, false on failure
     */
    public function getStatus() {
        if ($this->status === "error") {
            return false;
        } else {
            return true;
        }
    }

}