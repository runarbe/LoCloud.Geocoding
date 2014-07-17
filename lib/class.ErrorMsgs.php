<?php

/**
 * Enumeration class with constants for error messages
 */
class WsErrors {

    const summary = "Error, please review these error messages and correct your web service request";
    const couldNotConnectToDatabase = "Error, could not connect to database";
    const database = "Error, database error";
    const sqlError = "SQL error";
    const sqlStatement = "SQL statement";
    const noResults = "Info, the search did not yield results.";
    const success = "Info, operation completed successfully.";
    const failure = "Error, the operation could not be completed";
    const reqParamMissing = "Error, missing mandatory parameter:";
    const optParamMissing = "Info, no value for optional parameter:";
    const includedFilter = "Info, included filter";
    const adminRequired = "This operation requires administrator privileges";
    const updateAvailable = "An update is available";
    const installationUpToDate = "The installation is up to date";
    const fileMissingOrInaccessible = "The file is missing or could not be opened";
    const fileAlreadyExists = "The file already exists";
    const overwritingFile = "Overwriting existing file";
    const couldNotWriteToFile = "Could not write to file";
}
?>
