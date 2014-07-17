<?php
require_once("functions.php");
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Install LoCloud Geocoding Micro Service</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" title="main"/>
        <link rel="stylesheet" href="css/cupertino/jquery-ui.css"/>
        <link rel="stylesheet" href="css/pure/pure.css"/>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.10.2.custom.min.js" type="text/javascript"></script>
        <script src="js/jSrb/jSrb.js" type="text/javascript"></script>
        <script>
            jQuery(document).ready(function() {
                jQuery("#dlgAuth").dialog({width: 480});
                jQuery(".ui-dialog-titlebar-close").hide();
                jQuery("#btnLoadDefaults").click(function(evt) {
                    evt.preventDefault();
                    jQuery("#tbAppTitle").val("Geocoding Micro Service");
                    jQuery("#tbDb").val("locloud_test");
                    jQuery("#tbDbHost").val("localhost");
                    jQuery("#tbDbUsr").val("root");
                    jQuery("#tbDbPwd").val("root");
                });

<?php
if (file_exists("config.php")) {
    echo 'jQuery("#tbAppTitle").val("' . LgmsConfig::app_title . '");';
    echo 'jQuery("#tbDb").val("' . LgmsConfig::db . '");';
    echo 'jQuery("#tbDbHost").val("' . LgmsConfig::db_host . '");';
    echo 'jQuery("#tbDbUsr").val("' . LgmsConfig::db_usr . '");';
    echo 'jQuery("#tbDbPwd").val("' . LgmsConfig::db_pwd . '");';
    echo 'jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-highlight").html("Config file exists, database not initialized");';
}
?>
                jQuery("#btnConfigure").click(function(evt) {
                    var app_title = jQuery("#tbAppTitle").val();
                    var db = jQuery("#tbDb").val();
                    var db_host = jQuery("#tbDbHost").val();
                    var db_usr = jQuery("#tbDbUsr").val();
                    var db_pwd = jQuery("#tbDbPwd").val();
                    var basedir = jQuery("#tbBaseDir").val();
                    var overwrite;
                    if (jQuery("#chkOverwrite").is(":checked")) {
                        overwrite = 1;
                    } else {
                        overwrite = 0;
                    }
                    evt.preventDefault();
                    jQuery.getJSON(WsUrl.createConfig, {
                        app_title: app_title,
                        db: db,
                        db_host: db_host,
                        db_usr: db_usr,
                        db_pwd: db_pwd,
                        basedir: basedir,
                        overwrite: overwrite
                    },
                    /**
                     * @param {WsRetObj} data
                     * @ignore
                     */
                    function(data) {
                        if (data.v === WsStatus.success) {
                            jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-highlight").html("Successfully created config file");

                            jQuery.getJSON(WsUrl.initDb, {}, function(data) {
                                if (data.v === WsStatus.success) {
                                    jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-highlight").html("Successfully initialized database");
                                    window.location.replace("./index.php");
                                } else {
                                    jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-error").html("Error initializing database: " + data.m);
                                }
                            }).fail(function(data) {
                                jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-error").html("An error occured while invoking database initialization web service: " + data);
                            });

                        } else {
                            jQuery("#notification").removeClass("ui-state-error ui-state-highlight").addClass("ui-state-error").html("Error creating config file: " + data.m);
                        }

                    }).fail(function(data) {
                        console.log("An error occured while trying to invoke the create config file web service" + data);
                    });
                });
            });
        </script>
    </head>
    <body>
        <div id="dlgAuth" title="Initialize Geocoding Microservice">
            <img src="images/locloud-logo-50px.png"/>
            <form id="ftmAuth" class="pure-form pure-form-aligned">
                <p class="pure-form-message">
                    By filling in the forms below and clicking the "Initialize" button, a new configuration file will be created. If you already have installed the application you should be careful to change these values as it may result in your application becoming unusable.
                </p>
                <div class="pure-control-group">
                    <label>Application title</label>
                    <input type="text" class="pure-input-2-3" id="tbAppTitle" value="LoCloud Geocoding Microservice" placeholder="Enter desired title for your application" required/>
                </div>
                <div class="pure-control-group">
                    <label>Database</label>
                    <input type="text" class="pure-input-2-3" id="tbDb" placeholder="Enter name of database (will be created if it doesn't exist)" required/>
                </div>
                <div class="pure-control-group">
                    <label>Host name</label>
                    <input type="text" class="pure-input-2-3" id="tbDbHost" value="" placeholder="Enter hostname of server running MySQL, typically 'localhost'" required/>
                </div>
                <div class="pure-control-group">
                    <label>Database user name</label>
                    <input type="text" class="pure-input-2-3" id="tbDbUsr" placeholder="Enter username of existing MySQL database user" required/>
                </div>
                <div class="pure-control-group">
                    <label>Database password</label>
                    <input class="pure-input-2-3" type="password" placeholder="Enter password of existing MySQL database user" id="tbDbPwd" required/>
                </div>
                <div class="pure-control-group">
                    <label>Installation directory</label>
                    <input class="pure-input-2-3 pure-" type="text" value="<?php echo dirname(__FILE__); ?>" id="tbBaseDir" disabled/>
                </div>
                <?php
                if (file_exists("config.php")) {
                    ?>
                    <div class="pure-controls">
                        <label class="pure-radio">                   
                            <input type="checkbox" id="chkOverwrite"/>
                            Overwrite existing configuration file
                        </label>
                    </div>
                    <?php
                }
                ?>
                <div class="pure-controls">
                    <button id="btnLoadDefaults" class="pure-button">Load default values</button>
                    <button id="btnConfigure" class="pure-button pure-button-primary">Initialize</button>
                </div>
                <p id="notification"></p>
            </form>
        </div>
    </body>
</html>