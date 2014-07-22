function resetGeocodingForm() {
    jQuery("input.resetable, select.resetable", "form#gc").val("");
    return true;
}

function getFieldChanges() {
    var mFieldChanges = jQuery("#gc input#hdnFieldChanges").val();

    if (mFieldChanges !== '') {
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

function hideGeocodingForm() {
    jQuery("#gcFormContainer").css("display", "none");
}


function showGeocodingForm() {
    jQuery("#gcFormContainer").css("display", "block");
}

/**
 * Open a popup window and show a URL
 * 
 * @param {type} pImageUrl
 * @returns {undefined}
 */
function showImagePopup(pImageUrl) {
    var mImgHtml = "<img src=\"" + pImageUrl + "\"/>";
    jQuery("#dlgHelp").empty().html(mImgHtml);
    jQuery("#dlgHelp").dialog(
            {
                hide: "fade",
                show: "fade",
                title: jsConf.app_title + " - Image Viewer",
                closeOnEscape: true,
                modal: true,
                height: 480,
                width: 640
            });
}

/**
 * Insert or update an item match record
 * @param {Number} pItemID The value of the autopk_id column from the datasource table for this item
 * @param {Number} pX X-coordinate or longitude
 * @param {Number} pY Y-coordinate or latitude
 * @param {Number} pConfidence A %-value from 0-100 that indicates the confidence of the match
 * @param {String} pItemName The optionally edited name of the item
 * @param {String} pFieldChanges JSON object string with modified values
 * @param {String} pLinkedPURI Universal Resource Identifier
 * @param {Number} pMapResolution A floating point value indicating the number of meters per pixel of the zoom level where the geometry was digitized for the item
 * @param {String} pGeom WKT geometry string
 * @returns {void}
 */
function saveGeocoding(pItemID, pX, pY, pConfidence, pItemName, pFieldChanges, pLinkedPURI, pMapResolution, pGeom) {
    var mDatasourceID = getSelectedDatasource().id;
    console.log(pFieldChanges);
    jQuery.post(WsUrl.updateItem,
            {
                datasourceID: mDatasourceID,
                itemID: pItemID,
                x: pX,
                y: pY,
                confidence: pConfidence,
                pURI: pLinkedPURI,
                mapResolution: pMapResolution,
                name: pItemName,
                fieldChanges: pFieldChanges,
                geom: pGeom
            },
    function(pData) {

        if (pData.status === 'success') {

            jQuery("#selectable li.ui-selected")
                    .removeClass()
                    .addClass("ui-state-default")
                    .addClass("ui-state-highlight")
                    .addClass("ui-selected");

            var mConfidence = jQuery('#tbConfidence', '#gc').val();

            if (jQuery.isNumeric(mConfidence)) {
                var mSourceItem = getSelectedSourceItem();
                var mProbability = confidenceToProbability(pConfidence);
                mSourceItem.addClass('prob' + mProbability);

                var mItemAttribs = mSourceItem.data('attributes');
                mItemAttribs.gc_probability = mProbability;
                mItemAttribs.gc_confidence = pConfidence;
                mItemAttribs.gc_fieldchanges = getFieldChangesAsText();
                mItemAttribs.gc_dbsearch_puri = pLinkedPURI;
                mItemAttribs.gc_mapresolution = pMapResolution;

                var mItemName = jQuery('#tbItemName').val();
                mItemAttribs.gc_name = pItemName;
                mItemAttribs._nc = pItemName;
                mSourceItem.html(pItemName);

                // Add geometries back into object
                var mLonLat = new OpenLayers.LonLat(jQuery('#tbLongitude', '#gc').val(), jQuery('#tbLatitude', '#gc')
                        .val())
                        .transform(projDatasource, p4326);
                mItemAttribs.gc_lon = mLonLat.lon;
                mItemAttribs.gc_lat = mLonLat.lat;
                mItemAttribs.gc_geom = pGeom;
            }
        } else {
            showMsgBox(pData.message);
            console.log(pData);
        }
    }, 'json').fail(function(pResponse) {
        showMsgBox(jSrb.ErrMsg.ajaxRequestError);
        console.log(pResponse.responseText);
    });

    return;
}

