/**
 * Calls the upgrade Web Service
 */
jSrb.Handlers.handlerBtnUpgrade = function() {
    showMsgBox("Please wait while upgrade commences. This operation may take several minutes");
    jQuery.getJSON(WsUrl.upgradeInstallation, {
        pVersion: jQuery("#btnUpgrade").data("version")
    }, function(pData) {
        if (pData.v === 1) {
            showMsgBox(pData.m, false);
            jQuery("#btnUpgrade").hide();
        } else {
            showMsgBox(pData.m, true);
        }
    }).fail(function() {
        showMsgBox("Web service request failed", true);
    });
};

/**
 * Opens a popup window and displays help text
 */
jSrb.Handlers.handlerBtnHelp = function() {
    jQuery.ajax({
        url: "./res/help.html"
    }).done(function(data) {
        jQuery("#dlgHelp").html(data);
    });
    jQuery("#dlgHelp").dialog(
            {
                buttons: [
                    {
                        text: "Close",
                        click: function() {
                            jQuery("#dlgHelp").empty().dialog("close");
                        }
                    }],
                title: "Help for " + jSrb.Configuration.app_title,
                hide: "fade",
                show: "fade",
                closeOnEscape: true,
                modal: true,
                height: 480,
                width: 640
            }
    );
};

/**
 * Opens a popup and shows the "about" text for the application
 */
jSrb.Handlers.handlerBtnAbout = function() {
    jQuery.ajax({
        url: "./res/about.html"
    }).done(function(data) {
        jQuery("#dlgHelp").empty().html(data);
    });
    jQuery("#dlgHelp").dialog(
            {
                buttons: [
                    {
                        text: "Close",
                        click: function() {
                            jQuery("#dlgHelp").dialog("close");
                        }
                    }],
                hide: "fade",
                show: "fade",
                title: "About the " + jSrb.Configuration.app_title,
                closeOnEscape: true,
                modal: true,
                height: 480,
                width: 640
            }
    );
};

/**
 * Triggers download of the currently selected data source as KML
 */
jSrb.Handlers.handlerDownloadKML = function() {
    var mDatasource = getSelectedDatasource();
    window.location.replace("export.php?ds=" + mDatasource.ds_table + "&format=kml");
};

/**
 * Triggers download of the currently selected data source as GeoJSON
 */
jSrb.Handlers.handlerDownloadGeoJSON = function() {
    var mDatasource = getSelectedDatasource();
    window.location.replace("export.php?ds=" + mDatasource.ds_table + "&format=geojson");
};

/**
 * Triggers download of the currently selected data source as GeoJSON assigned
 * to a JavaScript variable
 */
jSrb.Handlers.handlerDownloadGeoJSONJavaScript = function() {
    // Add logic to define name of JS variable
    var mDatasource = getSelectedDatasource();
    window.location.replace("export.php?ds=" + mDatasource.ds_table + "&format=geojson.js");
};