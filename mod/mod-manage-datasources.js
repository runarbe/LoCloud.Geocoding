/**
 * Load values from selected data source into form for editing
 * @returns {void}
 */
function loadDatasourceForm() {

    // If no datasource is selected
    if (!jQuery("#hdSelectedDatasourceID").val().length != 0) {
        jQuery("#meta_datasources *").filter(":input").each(function() {
            jQuery(this).val("");
        });
        return;
    }

    // Otherwise
    jQuery.post(WsUrl.manageDatasources, {
        "cmd": "get-datasource-field-names",
        "id": jQuery("#hdSelectedDatasourceID").val()
    }, function(pResponse) {

        if (pResponse.status === "success") {

            // Remove existing options from field-list drop-downs
            jQuery("select.field-list > option").remove();

            // Add not options to field-list drop-downs 
            jQuery("select.field-list")
                    .append("<option value=null>&lt;not set&gt;</option>");
            for (var i = 0, j = pResponse.records.length; i < j; i++) {
                jQuery("select.field-list").append("<option value='" + pResponse.records[i] + "'>" + pResponse.records[i] + "</option>");
            }

            // Set value from grid into form fields
            var mDatasource = getW2UIGridRecByID("grdDatasources", jQuery("#hdSelectedDatasourceID").val());
            for (key in mDatasource) {
                jQuery("#meta_datasources #" + key).val(mDatasource[key]);
            }

            // Check if the update button should be activated
            pollActivateBtnDatasourceUpdate();
            logMessage("Done", false);

        } else {
            logMessage("An error occured while loading field names, see JavaScript console for details.");
            console.log(pResponse);
        }
    }, 'json').fail(function(pResponse) {
        logMessage("Request failed, see JavaScript console for details.");
        console.log(pResponse);
    });
    return;
}

/**
 * Handler function invoked when a user selects a record in the acl grid
 * @param {Object} pEvent
 * @param {w2ui} pObj
 * @returns {void}
 */
function handlerGrdDatasourcesAclSelect(pEvent, pObj) {
    pEvent.onComplete = function(pEvent) {
        var mSelection = this.getSelection();
        if (mSelection.length >= 1) {
            jQuery("#hdSelectedDatasourcesAclEntryID").val(mSelection[0]);
        } else {
            jQuery("#hdSelectedDatasourcesAclEntryID").val(null);
        }
        pollActivateBtnDatasourcesAclDelete();
    };
    return;
}

/**
 * Handler function that is invoked when a datasource is unselected in the grid
 * @param {Object} pEvent
 * @param {w2ui} pObj
 * @returns {void|void}
 */
function handlerGrdDatasourcesUnselect(pEvent, pObj) {
    pEvent.onComplete = function(pEvent) {
        jQuery("#hdSelectedDatasourceID").val(null);
        loadDatasourceForm();
        pollActivateBtnAddDatasourcesAclEntry();
        reloadSelectedGrdDatasourcesAcl();

    };
}
/**
 * Handler function that is invoked when a datasource is selected in the grid
 * @param {Object} pEvent
 * @param {w2ui} pObj
 * @returns {void}
 */
function handlerGrdDatasourcesSelect(pEvent, pObj) {
    pEvent.onComplete = function(pEvent) {
        jQuery("#hdSelectedDatasourceID").val(pEvent.recid);
        loadDatasourceForm();
        pollActivateBtnAddDatasourcesAclEntry();
        reloadSelectedGrdDatasourcesAcl();
    };
    return;
}

/**
 * Reload the datasource grid with the access control entries corresponding to
 * the currently selected data source ID
 * @returns {void}
 */
function reloadSelectedGrdDatasourcesAcl() {
    var mDatasourceID = jQuery("#hdSelectedDatasourceID").val();
    if (!jQuery.isNumeric(mDatasourceID)) {
        w2ui["grdDatasourcesAcl"].postData = {
            "datasourceid": -1
        };
    } else {
        w2ui["grdDatasourcesAcl"].postData = {
            "datasourceid": mDatasourceID
        };        
    }
    w2ui["grdDatasourcesAcl"].reload();
}

/**
 * Adds a new entry to the data source access control list
 * @param {String} pٍSrcType The table name of the source entity
 * @param {Number} pٍSrcID The ID of the source entity record
 * @param {String} pTgtType the table name of the target entity
 * @param {Number} pTgtID the ID of the target entity record
 * @param {Number} pAccess The access level (10 = Owner, 20 = Editor, 30 = Viewer)
 * @returns {void}
 */
function handlerBtnDatasourcesAclAdd(pSrcType, pSrcID, pTgtType, pTgtID, pAccess) {
    jQuery.post("./ws/ws-grd-datasourcesacl.php", {
        "cmd": "add-record",
        "src_type": pSrcType,
        "src_id": pSrcID,
        "tgt_type": pTgtType,
        "tgt_id": pTgtID,
        "access": pAccess
    }, function(pData) {
        if (pData.status === "success") {
            w2ui["grdDatasourcesAcl"].reload();
            logMessage("Added user successfully", false);
        } else {
            logMessage("Could not add user");
            console.log(pData);
        }
    }, 'json').fail(function(pResponse) {
        logMessage("Error during request");
    });
    return;
}

/**
 * Delete selected entry from the data source access control list
 * @returns {void}
 */
function handlerBtnDatasourcesAclDelete() {
    if (w2ui["grdDatasourcesAcl"].getSelection().length >= 1) {
        w2ui["grdDatasourcesAcl"].postData = {
          datasourceid: jQuery("#hdSelectedDatasourceID").val()
        };
        w2ui["grdDatasourcesAcl"].delete(true);
        jQuery("#hdSelectedDatasourcesAclEntryID").val(null);
        pollActivateBtnDatasourcesAclDelete();
        logMessage("Access control list entry deleted successfully.", false);
    } else {
        logMessage("Error, no access control list entry selected.");
    }
    return;
}

/**
 * Check when to activate delete acl entry button
 * @returns {void}
 */
function pollActivateBtnDatasourcesAclDelete() {
    if (isEVNumeric(jQuery("#hdSelectedDatasourcesAclEntryID")))
    {
        jQuery("#btnDatasourcesAclDelete").removeClass("pure-button-disabled");
    } else {
        jQuery("#btnDatasourcesAclDelete").addClass("pure-button-disabled");
    }
    return;
}

/**
 * Check when to activate the update button of the form
 * @returns {void}
 */
function pollActivateBtnDatasourceUpdate() {
    if (isEVNumeric(jQuery("#hdSelectedDatasourceID")))
    {
        jQuery("#btnDatasourceUpdate").removeClass("pure-button-disabled");
        jQuery("#btnDatasourceDelete").removeClass("pure-button-disabled");
    } else {
        jQuery("#btnDatasourceUpdate").addClass("pure-button-disabled");
        jQuery("#btnDatasourceDelete").addClass("pure-button-disabled");
    }
    return;
}

/**
 * Check when to activate the add button for the acl entries
 * @returns {void}
 */
function pollActivateBtnAddDatasourcesAclEntry() {
    if (isEVNumeric(jQuery("#hdSelectedUsrID"))
            && isEVNumeric(jQuery("#hdSelectedDatasourceID")))
    {
        jQuery("#btnDatasourcesAclAdd").removeClass("pure-button-disabled");
    } else {
        jQuery("#btnDatasourcesAclAdd").addClass("pure-button-disabled");
    }
    return;
}

/**
 * Delete selected data source
 * @returns {void}
 */
function handlerBtnDatasourceDelete() {
    if (confirm("Are you sure you want to delete the selected data source?")) {
        jQuery.post(WsUrl.manageDatasources, {
            "cmd": "delete-records",
            "selected": [jQuery("#hdSelectedDatasourceID").val()]
        }, function (pData) {
           if (pData.status === "success") {
               logMessage("Deleted data source", false);
               w2ui["grdDatasources"].reload();
               console.log(pData);
           } else {
               logMessage(pData.message);
               console.log(pData);
           }
        }, 'json').fail(function(pResponse) {
            logMessage("Error, request failed");
            console.log(pResponse.responseText);
        });
    } else {
        logMessage("Cancelled operation", false);
    };
    return;
}

/**
 * Update datasource as appropriate
 * @returns {void}
 */
function handlerBtnDatasourceUpdate() {

    // Setup and collect values to submit to web service
    var mReqP = {};
    mReqP.id = jQuery("#hdSelectedDatasourceID").val();
    jQuery("#meta_datasources *").filter(":input").each(function() {
        if (jQuery(this).val() !== null) {
            mReqP[this.id] = jQuery(this).val();
        }
    });

    // Issue post request
    jQuery.post(WsUrl.manageDatasources,
            {
                "cmd": "save-records",
                "changed": [mReqP]
            }, function(pData) {
        if (pData.status === "success") {
            w2ui["grdDatasources"].reload();
            logMessage("Updated data source", false);
        } else {
            logMessage(pData.message);
            console.log(pData);
        }
    }, 'json')
            .fail(function(pError) {
                logMessage("The request to the server failed. See JavaScript console for details.");
                console.log(pError.responseText);
            });
    return;
}