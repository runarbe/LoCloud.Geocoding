
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
    if (mDatasource !== undefined) {
        window.location.href = "export.php?ds=" + mDatasource.ds_table + "&format=kml";
    }
}

/*
 * Handler for button to download geocoding as RDF
 */
function handlerDownloadRDF() {
    var mDatasource = getSelectedDatasource();
    if (mDatasource !== undefined) {
        window.location.href = "export.php?ds=" + mDatasource.ds_table + "&format=rdf";
    }
}

/*
 * Handler for button to download geocoding as RDF
 */
function handlerDownloadCSV() {
    var mDatasource = getSelectedDatasource();
    if (mDatasource !== undefined) {
        window.location.href = "export.php?ds=" + mDatasource.ds_table + "&format=csv";
    }
}

/*
 * Handler for button to download geocoding as GeoJSON
 */
function handlerDownloadGeoJSON() {
    var mDatasource = getSelectedDatasource();
    if (mDatasource !== undefined) {
        window.location.href = "export.php?ds=" + mDatasource.ds_table + "&format=geojson";
    }
}

/**
 * Handler for button to download geocoding as GeoJSON JavaScript
 */
function handlerDownloadGeoJSONJavaScript() {
    var mDatasource = getSelectedDatasource();
    if (mDatasource !== undefined) {
        window.location.href = "export.php?ds=" + mDatasource.ds_table + "&format=geojson.js";
    }
}