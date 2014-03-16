<?php
require_once("functions.php");

if (!checkConfigPHP()) {
    goFirstRun();
}

if ($_GET["action"] == "logout" || $_GET["action"] == "login") {
    session_start();
    unset($_SESSION["usr_id"]);
    unset($_SESSION["usr_level"]);
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
        <script>
            jQuery(document).ready(function() {
                jQuery("#dlgAuth").dialog();

                jQuery("#btnRemind").click(function(evt) {
                    evt.preventDefault();
                });

                jQuery("#btnLogin").click(function(evt) {
                    var u = jQuery("#tbUsr").val();
                    var p = jQuery("#pwPwd").val();
                    evt.preventDefault();
                    jQuery.getJSON("./ws/ws-auth.php",
                            {
                                u: u,
                                p: p
                            },
                    function(data) {
                        if (data.s == 1) {
                            jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-highlight").html("Success");
                            window.location.replace("./index.php");
                        } else {
                            jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-error").html("Error: " + data.m);
                        }
                    }).fail(function(data) {
                        console.log("Failed to connect: " + data);
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
                        <button id="btnRemind" class="pure-button">Forgot</button>
                        <button id="btnLogin" class="pure-button pure-button-primary">Log in</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </body>
</html>