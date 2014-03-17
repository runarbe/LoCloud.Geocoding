function handlerBtnUpgrade() {
    showMsgBox("Please wait while upgrade commences. This operation may take several minutes");
    jQuery.getJSON("./ws/ws-upgrade.php", {
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
}

function handlerBtnHelp() {
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
                title: "Help for " + jsConf.app_title,
                hide: "fade",
                show: "fade",
                closeOnEscape: true,
                modal: true,
                height: 480,
                width: 640
            }
    );
}

function handlerBtnAbout() {
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
                title: "About the " + jsConf.app_title,
                closeOnEscape: true,
                modal: true,
                height: 480,
                width: 640
            }
    );
}

/*
 * Handler for button to download geocoding as KML
 */
function handlerDownloadKML() {
    var mDatasource = getSelectedDatasource();
    window.location.replace("export.php?ds=" + mDatasource.ds_table + "&format=kml");
}

/*
 * Handler for button to download geocoding as RDF
 */
function handlerDownloadRDF() {
    var mDatasource = getSelectedDatasource();
    window.location.replace("export.php?ds=" + mDatasource.ds_table + "&format=rdf");
}

/*
 * Handler for button to download geocoding as GeoJSON
 */
function handlerDownloadGeoJSON() {
    var mDatasource = getSelectedDatasource();
    window.location.replace("export.php?ds=" + mDatasource.ds_table + "&format=geojson");
}

/**
 * Handler for button to download geocoding as GeoJSON JavaScript
 */
function handlerDownloadGeoJSONJavaScript() {
    // Add logic to define name of JS variable
    var mDatasource = getSelectedDatasource();
    window.location.replace("export.php?ds=" + mDatasource.ds_table + "&format=geojson.js");
}