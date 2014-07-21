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
 * @param {Number} pItemID
 * @param {Number} pX
 * @param {Number} pY
 * @param {Number} pConfidence
 * @param {String} pItemName
 * @param {String} pFieldChanges JSON string
 * @param {String} pLinkedPURI Universal Resource Identifier
 * @param {Number} pMapResolution
 * @returns {void}
 */
function saveGeocoding(pItemID, pX, pY, pConfidence, pItemName, pFieldChanges, pLinkedPURI, pMapResolution) {
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
                fieldChanges: pFieldChanges
            },
    function(pData) {
        if (pData.status === 'success') {
            jQuery("#selectable li.ui-selected").removeClass().addClass("ui-state-default").addClass("ui-state-highlight").addClass("ui-selected");
            var mConfidence = jQuery('#tbConfidence', '#gc').val();
            if (jQuery.isNumeric(mConfidence)) {

                var mSourceItem = getSelectedSourceItem();
                var mItemAttribs = mSourceItem.data('attributes');
                var mProbability = confidenceToProbability(pConfidence);
                mSourceItem.addClass('prob' + mProbability);
                mItemAttribs.gc_probability = mProbability;
                mItemAttribs.gc_confidence = pConfidence;
                
                mItemAttribs.gc_fieldchanges = getFieldChangesAsText();
                mItemAttribs.gc_dbsearch_puri = pLinkedPURI;
                mItemAttribs.gc_mapresolution = pMapResolution;
                
                var mItemName = jQuery('#tbItemName').val();

                mItemAttribs.gc_name = pItemName;
                mItemAttribs._nc = pItemName;
                mSourceItem.html(pItemName);

                var mLonLat = new OpenLayers.LonLat(jQuery('#tbLongitude', '#gc').val(), jQuery('#tbLatitude', '#gc')
                        .val())
                        .transform(projDatasource, p4326);
                mItemAttribs.gc_lon = mLonLat.lon;
                mItemAttribs.gc_lat = mLonLat.lat;
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

