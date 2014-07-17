<?php

/**
 * A shared interface for return objects from web services - currently two
 * implementations exist.
 */
interface iRetObj {
    
    /**
     * Add a data object to the records section of the object
     * @param Mixed $pObject
     * @param String $pDataObjKey
     */
    public function addData($pObject,
            $pDataObjKey = null);

    /**
     * Add an (error) message to the object
     * @param String $pMessage
     * @param String $pAdditionalMessage
     */
    public function addMsg($pMessage,
            $pAdditionalMessage = null);

    /**
     * Set the status of the object to failure and optionally
     * add a message
     * @param String $pMessage
     * @param String $pAdditionalMessage
     */
    public function setFailure($pMessage = null,
            $pAdditionalMessage = null);

    /**
     * Set the status of the object to success and optionally
     * add a message
     * @param String $pMessage
     * @param String $pAdditionalMessage
     */
    public function setSuccess($pMessage = null,
            $pAdditionalMessage = null);
    
    /**
     * Get the status of the current request
     * @return String status
     */
    public function getStatus();
    
    /**
     * Return the object as a JSON string
     */
    public function getResult();
    
    /**
     * Output the object as JSON with a HTTP Content-Type header
     * application/json
     */
    public function echoJson();
    
}