function roundCoordinates(mDouble, mNumDigits) {
    var mFact = Math.pow(10, mNumDigits);
    var mPoint = (Math.round(mDouble * mFact)) / mFact
    return mPoint;
}

function openPopup(pUrl) {
    var left = (jQuery(window).width() / 2) - (900 / 2);
    var top = (jQuery(window).height() / 2) - (600 / 2);
    popup = window.open(pUrl, "popup", "width=900, height=600, top=" + top + ", left=" + left);
}

function setDefaultZoomTo(zoomLevel) {
    defaultZoomTo = zoomLevel;
    jQuery("#defaultZoomTo").slider("value", zoomLevel);
    return true;
}

function resetGeocodingForm() {
    jQuery("input.resetable, select.resetable", "form#gc").val("");
    return true;
}

function getSelectedSearchDB() {
    return jQuery("#sbSelectSearchDB option:selected");
}

function getFieldChanges() {
    var mFieldChanges = jQuery("#gc input#hdnFieldChanges").val();
    if (mFieldChanges != '') {
        return JSON.parse(jQuery("#gc input#hdnFieldChanges").val());
    } else {
        return JSON.parse('{}');
    }
}

function getFieldChangesAsText() {
    return jQuery("#gc input#hdnFieldChanges").val();
}

function setFieldChanges(pChangedFields) {
    jQuery("#gc input#hdnFieldChanges").val(pChangedFields);
    return true;
}

function getSelectedSearchResultItem() {
    return jQuery("#listSearchResults li.ui-selected").first();
}

function loadSearchDBs() {
    jQuery.getJSON("./ws/ws-load-search-dbs.php",
            {
            },
            function(data) {
                if (data.s == 1) {
                    jQuery.each(data.d, function(key, val) {
                        var opt = jQuery("<option value=\"" + val.id + "\">" + val.sch_title + "</option>");
                        opt.data("attributes", val);
                        jQuery("#sbSelectSearchDB").append(opt);
                    })
                    //console.log("Success loading search datbases");
                } else {
                    console.log("Loading search databases unsuccessful: " + d.m);
                }
            }).fail(function() {
        console.log('Error loading search databases.')
    });
}

function loadSearchResults() {
    var bbox = null;
    if (jQuery("#cbLimitToBbox").prop("checked")) {
        var bbox = map.getExtent().transform(p900913, p4326).toBBOX();
    }

    var mWebService = getSelectedSearchDB().data("attributes").sch_webservice;
    var mTable = getSelectedSearchDB().data("attributes").sch_table;
    var mSearchTerm = jQuery("#tbSearchTerm").val();
    jQuery.getJSON("./ws/" + mWebService + ".php",
            {
                q: mSearchTerm,
                bbox: bbox,
                t: mTable
            },
    function(data) {
        /*
         * Empty search result list
         */
        jQuery("#listSearchResults").empty();
        jQuery("#txtSearchHint").empty();

        if (data.s == 1) {
            if (data.d.length > 0) {
                /*
                 * Add options to adm0
                 */
                jQuery.each(data.d, function(key, val) {
                    var li = jQuery("<li class=\"ui-state-default\"></option>");
                    li.html(val.asciiname);
                    li.val(val.geonameid);
                    li.data("attributes", val);
                    jQuery("#listSearchResults").append(li);
                });
            } else {
                jQuery("#txtSearchHint").empty().html("No hits...");
            }
        } else {
            showMsgBox(data.m, true);
        }
    }).fail(function() {
        showMsgBox("Error while loading search results", true);
    });
}

function saveGeocoding(pTable, pId, pLon, pLat, pProbability, pItemName, pFieldChanges) {
    var dsID = getSelectedDatasource().id;
    console.log(pFieldChanges);
    jQuery.getJSON('./ws/ws-update2.php',
            {
                //table:pTable,
                dsID: dsID,
                id: pId,
                lon: pLon,
                lat: pLat,
                probability: pProbability,
                name: pItemName,
                fieldChanges: pFieldChanges
            },
    function(data) {
        if (data.s == "1") {
            jQuery("#selectable li.ui-selected").removeClass().addClass("ui-state-default").addClass("ui-state-highlight").addClass("ui-selected");
            var mProbability = jQuery('input[name=rbProbability]:checked', '#gc').val();
            if (mProbability != null) {
                getSelectedSourceItem().addClass("prob" + mProbability);
                getSelectedSourceItem().data("attributes").gc_probability = mProbability;
                getSelectedSourceItem().data("attributes").gc_name = jQuery("#tbItemName").val();
                getSelectedSourceItem().data("attributes").gc_fieldchanges = getFieldChangesAsText();
                getSelectedSourceItem().data("attributes")._nc = jQuery("#tbItemName").val();
                getSelectedSourceItem().html(jQuery("#tbItemName").val());
                var mLonLat = new OpenLayers.LonLat(jQuery("#tbLongitude", "#gc").val(), jQuery("#tbLatitude", "#gc").val()).transform(pDatasource, p4326);
                getSelectedSourceItem().data("attributes").gc_lon = mLonLat.lon;
                getSelectedSourceItem().data("attributes").gc_lat = mLonLat.lat;
            }
        } else {
            jQuery("#dialog").html(data.m);
            jQuery("#dialog").dialog();
        }
    }).fail(function() {
        console.log("Error during save.");
    });

}

function showGeocodingForm() {
    jQuery("#gcFormContainer").css("display", "block");
}

function clearDatasourceFilters() {
    jQuery("#sbFilterAdm0").empty().val("");
    jQuery("#sbFilterAdm1").empty().val("");
    jQuery("#sbFilterProbability").val("");
    jQuery("#sbFilterCategory").empty().val("");
}

/**
 * Creates a set of html paragraphs from an array
 * 
 * @param {Array} pMessageArray
 * @returns {String}
 */
function getErrorMessages(pMessageArray) {
    var mHtml;
    for (var i = 0; i < pMessageArray.length; i++) {
        mHtml += "<p>" + pMessageArray[i] + "</p>";
    }
    return mHtml;
}

function showMsgBox(pMsg, pError) {
    var mCssClass = "ui-state-notification";
    var mContent = jQuery("<ul></ul>");
    var mTitle = "Notification!";
    if (pError == true) {
        mCssClass = "ui-state-highlight";
        mTitle = "Error!";
    }
    /*
     * Ig pMsg is a string, simply add the string to a list element
     */
    if (typeof pMsg == "string") {
        mContent.append("<li>" + pMsg + "</li>");
    } else {
        /*
         * Otherwise, iterate through the array, object and add each value to a list
         */
        jQuery.each(pMsg, function(key, val) {
            mContent.append("<li>" + key + ": " + val + "</li>");
        });
    }

    jQuery("#dialog").empty()
            .append("<p></p>")
            .addClass(mCssClass)
            .html(mContent).dialog({
        title: mTitle,
        modal: true,
        width: 480,
        height: 200,
        resizeable: true,
        minwidth: 480,
        maxWidth: 640,
        minHeight: 200,
        maxHeight: 480
    });
}

function hideGeocodingForm() {
    jQuery("#gcFormContainer").css("display", "none");
}