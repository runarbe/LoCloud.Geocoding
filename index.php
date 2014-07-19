<?php
require_once("functions.php");

if (checkConfigPHP() === false) {
    goFirstRun();
}

if (!isLoggedIn()) {
    goAuth();
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title><?php echo LgmsConfig::app_title; ?></title>
        <link rel="stylesheet" href="js/OpenLayers/theme/default/style.css"/>
        <link rel="stylesheet" href="css/cupertino/jquery-ui.css"/>
        <link rel="stylesheet" href="css/pure/pure.css"/>
        <link rel="stylesheet" href="css/style.css" title="main"/>
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-old-ie-min.css">
        <![endif]-->
        <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css">
        <!--<![endif]-->        

        <script src="http://maps.google.com/maps/api/js?v=3&sensor=false"></script>
        <script src="js/proj4js/proj4js-compressed.js" type="text/javascript"></script>
        <script src="js/OpenLayers/google-v3.js" type="text/javascript"></script>
        <script src="js/OpenLayers/OpenLayers.js" type="text/javascript"></script>
        <script src="js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.10.2.custom.min.js" type="text/javascript"></script>

        <!-- Plugins for jquery form -->
        <script src="js/jQueryForm/jquery.form.min.js"></script>        

        <!-- Plugins for main custom Javascript -->
        <script src="js/jSrb/jSrb.js" type="text/javascript"></script>

    </head>
    <body>
        <div id="divHeader">
            <div class="innerPadding">
                <?php include("./mod/mod-header.php"); ?>
            </div>
        </div>
        <div id="divSource" class="scrollable">
            <div class="innerPadding">
                <?php include("./mod/mod-source.php"); ?>
            </div>
        </div>
        <div id="divSearch">
            <div class="innerPadding">
                <?php include("./mod/mod-search.php"); ?>
            </div>
        </div>
        <div id="divMap">
            <div class="innerPadding">
                <div id="theMap"></div>
            </div>
        </div>
        <div id="divForm">
            <div class="innerPadding">
                <?php include("./mod/mod-form.php"); ?>
            </div>            
        </div>
        <div id="divStatusBar">
            <div class="innerPadding">
                <?php include("./mod/mod-statusbar.php"); ?>
            </div>
        </div>
        <div id="dialog" title="Dialog">
        </div>        
        <?php include("./mod/mod-new-datasource.php"); ?>        
        <?php include("./mod/mod-popup-window.php"); ?>        
        <?php include("./mod/mod-view-attributes.php"); ?>        
    </body>
</html>