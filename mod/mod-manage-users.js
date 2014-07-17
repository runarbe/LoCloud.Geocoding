function handlerGrdUsrSelect(pEvent) {
    loadUsr(pEvent.recid);
}

function handlerBtnUsrAdd(pEvent) {
    newUsr();
    grdUsrSelectNone();
}

function handlerBtnUsrInsertUpdate(pEvent) {
    pEvent.preventDefault();
    jQuery.post(WsUrl.manageUsr, {
        cmd: jQuery("#hdCmd").val(),
        id: jQuery("#hdSelectedUsrID").val(),
        usr: jQuery("#meta_usr #usr").val(),
        pwd: jQuery("#meta_usr #pwd").val(),
        level: jQuery("#meta_usr #level").val()
    }, function(data) {
        if (data.status === "success") {
            grdUsrReload();
            resetUsr();
            logMessage("The insert/update operation completed successfully.", false);
        } else {
            logMessage("The insert/update operation failed.");
            console.log(data);
        }
    }, 'json').fail(function(pResponse) {
        logMessage("The insert/update request failed.");
        console.log(pResponse.responseText);
    });
}

function handlerBtnUsrDelete(evt) {
    evt.preventDefault();
    jQuery.post(WsUrl.manageUsr, {
        cmd: "delete-records",
        selected: [jQuery("#hdSelectedUsrID").val()]
    }, function(data) {
        if (data.status === "success") {
            grdUsrReload();
            resetUsr();
            logMessage("Deleted user", false);
        } else {
            logMessage("Error, could not delete user.");
            console.log(data);
        }
    }, 'json').fail(function(pResponse) {
        logMessage("Error, delete user request failed.");
        console.log(pResponse.responseText);
    });
}

function resetUsr() {
    jQuery("#meta_usr *").filter(":input").val("");
    jQuery("#meta_usr #level").val(UsrLevel.Editor);
    jQuery("#hdCmd").val("add-record");
}

function loadUsr(pUsrID) {
    var mUsr = getW2UIGridRecByID("grdUsr", pUsrID);
    for (key in mUsr) {
        jQuery("#meta_usr #"+key).val(mUsr[key]);
    }
    jQuery("#hdSelectedUsrID").val(mUsr.id);
    jQuery("#hdCmd").val("save-records");
    jQuery("#btnUsrInsertUpdate").html("Modify");
}

function newUsr() {
    resetUsr();
    jQuery("#btnUsrInsertUpdate").html("Create");
}

function grdUsrSelectNone() {
    w2ui["grdUsr"].selectNone();
}

function grdUsrReload() {
    w2ui["grdUsr"].reload();
}