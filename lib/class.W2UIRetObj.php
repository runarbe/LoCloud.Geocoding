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

    
    public function addRecord($pObject) {
        $this->records[] = $pObject;
    }

    public function setRecords($pArray) {
        $this->records = $pArray;
    }

    public function setSuccess($pMessage = null,
            $pAdditionalMessage = null) {
        $this->status = "success";
        if ($pMessage !== null || $pAdditionalMessage !== null) {
            $this->addMsg($pMessage,
                    $pAdditionalMessage);
        }
    }

    public function setFailure($pMessage = null,
            $pAdditionalMessage = null) {
        $this->status = "error";
        if ($pMessage !== null || $pAdditionalMessage !== null) {
            $this->addMsg($pMessage,
                    $pAdditionalMessage);
        }
    }

    public function addMsg($pMessage,
            $pAdditionalMessage = null) {
        if ($pAdditionalMessage === null) {
            $this->message .= $pMessage . ". ";
        } else {
            $this->message .= $pMessage . " (" . $pAdditionalMessage . "). ";
        }
    }

    public function getResult() {
        return json_encode($this);
    }

    public function addData($pDataObj,
            $pDataObjKey = null) {
        $this->records[] = $pDataObj;
        return;
    }

    public function echoJson() {
        header("Content-Type:application/json;");
        echo $this->getResult();
    }

    public function getStatus() {
        if ($this->status === "error") {
            return false;
        } else {
            return true;
        }
    }

}
