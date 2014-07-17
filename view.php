<?php
require_once("functions.php");
$mPage = filter_input(INPUT_GET,
        "mod");
$mModulePhpFile = "mod/mod-" . $mPage . ".php";
$mModuleCssFile = "mod/mod-" . $mPage . ".css";
$mModuleJavaScriptFile = "mod/mod-" . $mPage . ".js";
$mModuleJavaScriptHandlerFile = "mod/mod-" . $mPage . ".setup.js";
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>LoCloud Geocoding Micro Service</title>
        <link rel="stylesheet" href="css/style.css" type="text/css" title="main"/>
        <link rel="stylesheet" href="css/cupertino/jquery-ui.css"/>
        <link rel="stylesheet" href="css/pure/pure.css"/>
        <link rel="stylesheet" href="js/w2ui-1.3/w2ui-1.3.css"/>
        <?php
        if (file_exists($mModuleCssFile)) {
            echo "<link rel=\"stylesheet\" href=\"$mModuleJavaScriptFile\" type=\"text/css\"/>";
        }
        ?>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.10.2.custom.min.js" type="text/javascript"></script>
        <script src="js/w2ui-1.3/w2ui-1.3.min.js" type="text/javascript"></script>
        <script src="js/jSrb/jSrb.js" type="text/javascript"></script>
        <?php
        if (file_exists($mModuleJavaScriptFile)) {
            echo "<script src=\"$mModuleJavaScriptFile\" type=\"text/javascript\"></script>";
        }
        if (file_exists($mModuleJavaScriptHandlerFile)) {
            echo "<script src=\"$mModuleJavaScriptHandlerFile\" type=\"text/javascript\"></script>";
        }
        ?>
        <script>
            jQuery("document").ready(function() {
                jQuery("#gc-mod").dialog({
                    title: null,
                    width: "85%",
                    minWidth: "640",
                    minHeight: "480",
                    height: "auto",
                    close: function(evt) {
                        window.location.href = GcMods.mainWindow;
                    },
                    buttons: [{text: "Close", click: function() {
                                jQuery(this).dialog("close");
                            }}]
                });
            });
        </script>
    </head>
    <body>
        <?php
        if (file_exists($mModulePhpFile)) {
            include($mModulePhpFile);
        } else {
            ?>
            <p>Module <?php echo $mModulePhpFile; ?> does not exist...</p>
            <?php
        }
        ?>
    </body>
</html>