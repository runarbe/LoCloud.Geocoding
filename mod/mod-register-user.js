function handlerBtnUsrRegister() {
    jQuery.post(WsUrl.manageUsr, {
        cmd: "add-record",
        usr: jQuery("#meta_usr #usr").val(),
        pwd: jQuery("#meta_usr #pwd").val(),
        level: jSrb.UsrLevel.User
    }, function(pResponse) {
        if (pResponse.status === "success") {
            logMessage("Successfully added user.", false);
            setTimeout(function() {
                window.location.href = GcMods.login;
            }, 1000);
        } else {
            logMessage("Error adding user.");
            console.log(pResponse);
        }
    }, 'json').fail(function(pResponse) {
        logMessage("Add user request failed.");
        console.log(pResponse.responseText);
    });
}