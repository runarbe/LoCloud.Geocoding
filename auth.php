<?php
require_once("functions.php");

if (!checkConfigPHP()) {
    goFirstRun();
}

if ($_GET["action"] == "logout" || $_GET["action"] == "login") {
    session_start();
    unset($_SESSION["usr_id"]);
    unset($_SESSION["usr_level"]);
    unset($_SESSION["usr_usr"]);
}
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title><?php echo LgmsConfig::app_title; ?></title>
        <link href="css/style.css" rel="stylesheet" type="text/css" title="main"/>
        <link rel="stylesheet" href="css/cupertino/jquery-ui.css"/>
        <link rel="stylesheet" href="css/pure/pure.css"/>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.10.2.custom.min.js" type="text/javascript"></script>
        <script src="js/jSrb/jSrb.js" type="text/javascript"></script>
        <script>

            function setAuthSuccess(pMessage) {
                jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-highlight").html(pMessage);
                return;
            }

            function setAuthError(pMessage) {
                jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-error").html(pMessage);
                return;
            }

            jQuery(document).ready(function() {
                jQuery("#dlgAuth").dialog();

                jQuery("#btnRemind").click(function(evt) {
                    evt.preventDefault();
                    return false;
                });

                jQuery("#btnUsrRegisterNew").click(function(pEvent) {
                    pEvent.preventDefault();
                    window.location.href = GcMods.registerUser;
                    return false;
                });

                jQuery("#btnLogin").click(function(evt) {
                    var u = jQuery("#tbUsr").val();
                    var p = jQuery("#pwPwd").val();
                    evt.preventDefault();
                    jQuery.post(WsUrl.doLogin,
                            {
                                cmd: 'auth',
                                u: u,
                                p: p
                            },
                    function(pResponse) {
                        if (pResponse.status === "success") {
                            setAuthSuccess(pResponse.message);
                            setTimeout(function() {                              
                                window.location.replace(GcMods.mainWindow);
                            }, 1000);
                        } else {
                            setAuthError(pResponse.message);
                        }
                    }, 'json').fail(function(pResponse) {
                        setAuthError("Authentication request error.");
                        console.log("Request error: " + pResponse.responseText);
                    });
                });
            });

        </script>
    </head>
    <body>
        <div id="dlgAuth" title="Authenticate">
            <img src="./images/locloud-logo-50px.png"/>
            <form id="ftmAuth" class="pure-form pure-form-aligned">
                <fieldset>
                    <div class="pure-control-group">
                        <label>Username</label>
                        <input class="pure-input-1-2" type="text" id="tbUsr" required/>                </div>

                    <div class="pure-control-group">
                        <label>Password</label>
                        <input class="pure-input-1-2" type="password" id="pwPwd" required/>
                    </div>

                    <div class="pure-controls">
                        <p id="notification" class="pure-form-message"></p>
                        <button id="btnLogin" class="pure-button pure-button-primary">Log in</button>
                    </div>
                    <div class="pure-controls">
                        <button id="btnRemind" class="pure-button">Forgot...</button>
                        <button id="btnUsrRegisterNew" class="pure-button pure-button">Register</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>