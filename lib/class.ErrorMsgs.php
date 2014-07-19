<?php

/**
 * Enumeration class with constants for error messages
 */
class ErrorMsgs {

    const summary = "Error, please review these error messages and correct your web service request";
    const couldNotConnectToDatabase = "Error, could not connect to database";
    const database = "Error, database error";
    const dbErrorSelectCheckLog = "Error during SELECT, please check log for details.";
    const dbErrorInsertCheckLog = "Error during INSERT, please check log for details.";
    const dbErrorUpdateCheckLog = "Error during UPDATE, please check log for details.";
    const dbErroDeleteCheckLog = "Error during DELETE, please check log for details.";
    const sqlError = "SQL error";
    const sqlStatement = "SQL statement";
    const noResults = "Info, the search did not yield results.";
    const authSuccess = "Authentication successful.";
    const success = "Info, operation completed successfully.";
    const failure = "Error, the operation could not be completed";
    const reqParamMissing = "Error, missing mandatory parameter:";
    const optParamMissing = "Info, no value for optional parameter:";
    const invalidParam = "Info, invalid value specified for parameter:";
    const usingDefaultParamValue = "Info, Using default value for parameter:";
    const includedFilter = "Info, included filter";
    const noSuchUserOrWrongAuth = "Info, no such user or wrong username/password combination";
    const adminRequired = "This operation requires administrator privileges";
    const updateAvailable = "An update is available";
    const installationUpToDate = "The installation is up to date";
    const fileMissingOrInaccessible = "The file is missing or could not be opened";
    const fileAlreadyExists = "The file already exists";
    const overwritingFile = "Overwriting existing file";
    const couldNotWriteToFile = "Could not write to file";
    
    /**
     * Get error message for insufficient rights
     * @param String $pOperation
     * @param String $pTgtType
     * @return String
     */
    public static function insufficientRights ($pOperation, $pTgtType) {
        return sprintf("Not sufficient rights to %s %s", $pOperation, $pTgtType);
    }
    
    /**
     * Return error message for unsupported operations
     * @param String $pOperation
     * @return String
     */
    public static function operationNotSupported($pOperation) {
        return sprintf("Operation '%s' not supported by this Web Service", $pOperation);
    }
    
}