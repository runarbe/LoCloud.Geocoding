/**
 * Function that is triggered when clicking view image button in geocoding form
 * 
 * @returns {void}
 */
function handlerBtnViewImage() {
    var mImage = jQuery("#hdnImage").val();
    if (mImage !== null) {
        showImagePopup(mImage);
    }
}

/**
 * Function that is triggered when clicking visit url button in geocoding form
 * 
 * @returns {void}
 */
function handlerBtnViewUrl() {
    var mUrl = jQuery("#hdnUrl").val();
    if (mUrl !== null) {
        openPopup(mUrl);
    }
}

/**
 * Function that is triggered when clicking view attributes button in the
 * geocoding form
 * 
 * @returns {undefined}
 */
function handlerBtnViewAttributes() {

    var mSelectedItemAttributes = getSelectedSourceItem().data("attributes");

    /*
     * Empty the existin field list
     */
    jQuery("#attributeTable").empty();

    /*
     * Get current changed fields (if any)
     * @type <JSON object>
     */
    var mChangedFields = getFieldChanges();

    /*
     * Create table header
     */
    jQuery("#attributeTable").html("<tr><th class=\"ui-widget-header pct-30\">Field</th><th class=\"ui-widget-header pct-30\">Current Value</th><th class=\"ui-widget-header pct-40\">Edited Value</th></tr>");

    /*
     * Iterate through all attributes for the selected object
     */
    jQuery.each(mSelectedItemAttributes, function(key, val) {
        if (key.substring(0, 1) !== '_' && key.substring(0, 3) !== 'gc_' && key.substring(0, 3) !== 'fk_') {
            /*
             * Create row
             */
            var mRow = jQuery("<tr></tr>");

            /* 
             * Create html elements for table cells
             */
            var mLabel = jQuery("<td></td>");
            var mOldValue = mLabel.clone();
            var mEditedValue = mLabel.clone();

            /*
             * Assign values to table cells
             */
            jQuery(mLabel).html(key);
            jQuery(mOldValue).html(val);

            /*
             * Check if value exists in changed fields
             */
            var mTmpValue = '';

            jQuery.each(mChangedFields, function(pKey, pVal) {
                if (pKey == key) {
                    mTmpValue = pVal;
                }
            });

            /*
             * Add edit field
             */
            jQuery(mEditedValue).html("<input type=\"text\" class=\"attrFormField\" id=\"" + key + "\" value=\"" + mTmpValue + "\"/>");

            /*
             * Add table cells to row
             */
            mRow.append(mLabel).append(mOldValue).append(mEditedValue);

            /*
             * Add rows to table
             */
            jQuery("#attributeTable").append(mRow);

            /*
             * Next field
             */
        }
        jQuery("#attributeTable tr:even").css("backgroundColor", "#ffffff");
    });
    jQuery("#dlgViewAttributes").dialog({
        hide: "fade",
        show: "fade",
        closeOnEscape: true,
        modal: true,
        height: 480,
        width: 640
    });
}

/**
 * Function that is triggered when clicking the cancel button in the geocoding
 * form
 * 
 * @returns {void}
 */
function handlerBtnCancel() {
    clearSourceItemListSelection();
    clearIcons();
    resetGeocodingForm();
    hideGeocodingForm();
}

/**
 * Function that is triggered when the user clicks in the map
 * 
 * @param {OpenLayers.Pixel} pFeature
 * @returns {void}
 */
function handlerPreAddFeatureToMap(pFeature) {
    if (mDatasource != null && jQuery("#hdnTableId").val() != "") {
        // Add code here to distinguish between different geometry types
        var mCentroid = pFeature.geometry.getCentroid();

        var mTargetPoint = mCentroid.clone().transform(p900913, projDatasource)
        var mFormat = new OpenLayers.Format.WKT();
        jQuery('#tbLongitude', '#gc').val(roundCoordinates(mTargetPoint.x, mDatasource.ds_coord_prec));
        jQuery('#tbLatitude', '#gc').val(roundCoordinates(mTargetPoint.y, mDatasource.ds_coord_prec));
        jQuery('#tbMapResolution', '#gc').val(map.getResolution());
        jQuery('#tbGeom', '#gc').val(mFormat.write(pFeature));

        var mLonLat = new OpenLayers.LonLat(mCentroid.x, mCentroid.y);
        addProposedIcon(mLonLat.clone());

    }
}

/**
 * Function that is triggered when the user clicks the save geocoding button
 * in the geocoding form
 * 
 * @returns {void}
 */
function handlerBtnSaveGeocoding() {

    //var mId = jQuery("#hdnTableId").val();
    var mItemID = jQuery('#hdnAutoPkId', '#gc').val();
    var mItemName = jQuery('#tbItemName', '#gc').val();
    var mConfidence = jQuery('#tbConfidence', '#gc').val();

    var mX = jQuery('#tbLongitude', '#gc').val();
    var mY = jQuery('#tbLatitude', '#gc').val();
    var mLonLat = new OpenLayers.Geometry.Point(mX, mY).transform(projDatasource, p4326);

    var mFieldChanges = getFieldChangesAsText();
    var mLinkedPURI = jQuery("#tbLinkedPURI", '#gc').val();
    var mMapResolution = jQuery('#tbMapResolution', '#gc').val();
    var mGeom = jQuery('#tbGeom', '#gc').val();

    saveGeocoding(mItemID, mLonLat.x, mLonLat.y, mConfidence, mItemName, mFieldChanges, mLinkedPURI, mMapResolution, mGeom);

    // Show animation
    jQuery("#divForm").effect('transfer', {
        to: jQuery('.ui-selected', '#selectable').first()
    }, 500);
}

function handlerBtnCancelEditsClose() {
    jQuery("#dlgViewAttributes").dialog("close");
}

function handlerBtnSaveAttrEdits() {
    var mFieldEdits = new Object();
    jQuery("#attributeTable tr td input.attrFormField").each(function(index) {
        var mVal = jQuery(this).val();
        if (mVal !== "") {
            jQuery(mFieldEdits).data(jQuery(this).attr("id"), jQuery(this).val());
        }
    });
    setFieldChanges(JSON.stringify(jQuery(mFieldEdits).data()));
    jQuery("#dlgViewAttributes").dialog("close");
}