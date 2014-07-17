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
    //var mObj = jQuery(".ui-selected", "#selectable").first().data("attributes");
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
 * @param {OpenLayers.Pixel} pPoint
 * @returns {void}
 */
function handlerClickMap(pPoint) {
    if (mDatasource != null && jQuery("#hdnTableId").val() != "") {
        var mLonLat = map.getLonLatFromPixel(pPoint);
        addProposedIcon(mLonLat.clone());
        var mTargetLonLat = mLonLat.transform(p900913, pDatasource)
        jQuery("#tbLongitude").val(roundCoordinates(mTargetLonLat.lon, mDatasource.ds_coord_prec));
        jQuery("#tbLatitude").val(roundCoordinates(mTargetLonLat.lat, mDatasource.ds_coord_prec));
    }
}

/**
 * Function that is triggered when the user clicks the save geocoding button
 * in the geocoding form
 * 
 * @returns {void}
 */
function handlerBtnSaveGeocoding() {
    var mId = jQuery("#hdnTableId").val();
    var mTable = jQuery("#hdnAutoPkId").val();
    var mLon = jQuery("#tbLongitude").val();
    var mLat = jQuery("#tbLatitude").val();
    var mLonLat = new OpenLayers.LonLat(mLon, mLat).transform(pDatasource, p4326);
    var mItemName = jQuery("#tbItemName").val();
    var mFieldChanges = getFieldChangesAsText();
    var mProbability = jQuery("input[name=rbProbability]:checked", "#gc").val();
    console.log(mFieldChanges);
    saveGeocoding(mTable, mId, mLonLat.lon, mLonLat.lat, mProbability, mItemName, mFieldChanges);
    jQuery("#divForm").effect("transfer", {
        to: jQuery(".ui-selected", "#selectable").first()
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