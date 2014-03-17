/**
 * Function that is triggered when selecting a category in the data source panel
 * 
 * @returns {void}
 */
function handlerSelectFilterCategory() {
    listSrcDataStartItem = 0;
    loadSourceItems2(listSrcDataStartItem, listSrcDataNumItems);
    clearIcons();
    resetGeocodingForm();
    hideGeocodingForm();
}

/**
 * Function that is triggered when selecting a 1st level area division in the
 * data source panel
 * 
 * @returns {void}
 */
function handlerSelectFilterAdm0() {
    var mSbDatasourceId = jQuery("#sbDatasource").val();
    var mThisVal = jQuery("#sbFilterAdm0").val();
    loadAdm1(mSbDatasourceId, mThisVal);
    listSrcDataStartItem = 0;
    loadSourceItems2(listSrcDataStartItem, listSrcDataNumItems);
    clearIcons();
    resetGeocodingForm();
    hideGeocodingForm();
}

/**
 * Function that is triggered when selection a second level area in the
 * data source panel
 * 
 * @returns {void}
 */
function handlerSelectFilterAdm1() {
    listSrcDataStartItem = 0;
    loadSourceItems2(listSrcDataStartItem, listSrcDataNumItems);
    clearIcons();
    resetGeocodingForm();
    hideGeocodingForm();
}

/**
 * Function that is triggered when selection a probability filter in the
 * data source panel
 * 
 * @returns {void}
 */
function handlerSelectFilterProbability() {
    loadSourceItems2();
    clearIcons();
    resetGeocodingForm();
    hideGeocodingForm();
}

/**
 * Load the previous page of data source items
 * 
 * @returns {void}
 */
function handlerBtnPrevSrc() {
    if (listSrcDataStartItem >= 10) {
        listSrcDataStartItem -= listSrcDataNumItems;
        clearSourceItemListSelection();
        hideGeocodingForm();
        loadSourceItems2(listSrcDataStartItem, listSrcDataNumItems);
        clearIcons();
    } else {
        listSrcDataStartItem = 0;
    }
}

/**
 * Load the next page of data source items
 * 
 * @returns {void}
 */
function handlerBtnNextSrc() {
    listSrcDataStartItem += listSrcDataNumItems;
    clearSourceItemListSelection();
    hideGeocodingForm();
    loadSourceItems2(listSrcDataStartItem, listSrcDataNumItems);
    clearIcons();
}

/**
 * Function that is triggered when the users selects a source item from the
 * item list on the bottom right hand side of the screen
 * 
 * @returns {void}
 */
function handlerSelectSourceItem() {
    jQuery("#selectable li").removeClass("ui-state-highlight");
    getSelectedSourceItem().addClass("ui-state-highlight")
            .each(function() {
        var data = getSelectedSourceItem().data("attributes");
        //console.log(data);

        jQuery("#tbItemName").val(data._nc);
        jQuery("#hdnTableId").val(data._id);
        jQuery("#hdnTableName").val(mDatasource.ds_table);

        jQuery("#hdnFieldChanges").val(data.gc_fieldchanges);
        
        jQuery("#tbLongitude").val(null);
        jQuery("#tbLatitude").val(null);

        // Initialize button to show URL
        if (data._uc === null) {
            jQuery("#btnViewUrl").hide();
        } else {
            jQuery("#hdnUrl").val(data._uc);
            jQuery("#btnViewUrl").show();
        }

        // Initialize button to show image
        if (data._ic === null) {
            jQuery("#btnViewImage").hide();
        } else {
            jQuery("#hdnImage").val(data._ic);
            jQuery("#btnViewImage").show();
        }

        clearIcons();

        if (data.gc_probability != null) {
            jQuery("#radio input:radio[value=" + data.gc_probability + "]").prop("checked", true).button("refresh");
        }

        /*
         * Remove alternate markers from map
         */
        jQuery.each(alternateMarkers, function(key2, val2) {
            markers.removeMarker(val2);
        });

        /*
         * Add alternate markers to map
         */
        jQuery.each(data.gc_alternates, function(key3, val3) {
            var mLonLat = new OpenLayers.LonLat(val3.gc_lon, val3.gc_lat).transform(p4326, p900913);
            addAlternateIcons(mLonLat.clone());
            mLonLat = null;
        });

        if (data.gc_lon != null && data.gc_lat != null) {
            var mLonLat2 = new OpenLayers.LonLat(data.gc_lon, data.gc_lat).transform(p4326, pDatasource);
            jQuery("#tbLongitude").val(roundCoordinates(mLonLat2.lon, mDatasource.ds_coord_prec));
            jQuery("#tbLatitude").val(roundCoordinates(mLonLat2.lat, mDatasource.ds_coord_prec));

            var mLonLat = new OpenLayers.LonLat(data.gc_lon, data.gc_lat).transform(p4326, p900913);
            addProposedIcon(mLonLat.clone());
            map.setCenter(mLonLat, defaultZoomTo);

        }

        if (data._x != null && data._y != null) {
            //console.log('add original coordinates');
            mLonLat = new OpenLayers.LonLat(data._x, data._y).transform(pDatasource, p900913);
            addExistingIcon(mLonLat.clone());

            if (jQuery("#tbLongitude").val() == "" || (data.gc_lon == null || data.gc_lat == null)) {
                jQuery("#tbLongitude").val(data._x);
                jQuery("#tbLatitude").val(data._y);
                map.setCenter(mLonLat, defaultZoomTo);
            }

        }

        showGeocodingForm();

        jQuery(".ui-selected", "#selectable").first().effect("transfer", {
            to: jQuery("#gcForm")
        }, 500);

    });
}

/**
 * Function that is triggered when the users selects a source item from the
 * item list on the bottom right hand side of the screen
 * 
 * @returns {void}
 */
function handlerSelectSourceItem2(data) {
   
        //data

        jQuery("#tbItemName").val(data._nc);
        jQuery("#hdnTableId").val(data._id);
        jQuery("#hdnTableName").val(mDatasource.ds_table);

        jQuery("#hdnFieldChanges").val(data.gc_fieldchanges);
        
        jQuery("#tbLongitude").val(null);
        jQuery("#tbLatitude").val(null);

        // Initialize button to show URL
        if (data._uc === null) {
            jQuery("#btnViewUrl").hide();
        } else {
            jQuery("#hdnUrl").val(data._uc);
            jQuery("#btnViewUrl").show();
        }

        // Initialize button to show image
        if (data._ic === null) {
            jQuery("#btnViewImage").hide();
        } else {
            jQuery("#hdnImage").val(data._ic);
            jQuery("#btnViewImage").show();
        }

        clearIcons();

        if (data.gc_probability != null) {
            jQuery("#radio input:radio[value=" + data.gc_probability + "]").prop("checked", true).button("refresh");
        }

        /*
         * Remove alternate markers from map
         */
        jQuery.each(alternateMarkers, function(key2, val2) {
            markers.removeMarker(val2);
        });

        /*
         * Add alternate markers to map
         */
        jQuery.each(data.gc_alternates, function(key3, val3) {
            var mLonLat = new OpenLayers.LonLat(val3.gc_lon, val3.gc_lat).transform(p4326, p900913);
            addAlternateIcons(mLonLat.clone());
            mLonLat = null;
        });

        if (data.gc_lon != null && data.gc_lat != null) {
            var mLonLat2 = new OpenLayers.LonLat(data.gc_lon, data.gc_lat).transform(p4326, pDatasource);
            jQuery("#tbLongitude").val(roundCoordinates(mLonLat2.lon, mDatasource.ds_coord_prec));
            jQuery("#tbLatitude").val(roundCoordinates(mLonLat2.lat, mDatasource.ds_coord_prec));

            var mLonLat = new OpenLayers.LonLat(data.gc_lon, data.gc_lat).transform(p4326, p900913);
            addProposedIcon(mLonLat.clone());
            map.setCenter(mLonLat, defaultZoomTo);

        }

        if (data._x != null && data._y != null) {
            //console.log('add original coordinates');
            mLonLat = new OpenLayers.LonLat(data._x, data._y).transform(pDatasource, p900913);
            addExistingIcon(mLonLat.clone());

            if (jQuery("#tbLongitude").val() == "" || (data.gc_lon == null || data.gc_lat == null)) {
                jQuery("#tbLongitude").val(data._x);
                jQuery("#tbLatitude").val(data._y);
                map.setCenter(mLonLat, defaultZoomTo);
            }

        }

        showGeocodingForm();

        jQuery(".ui-selected", "#selectable").first().effect("transfer", {
            to: jQuery("#gcForm")
        }, 500);
        
}


/**
 * Function that is triggered when a user selects a datasource in the datasource
 * panel
 * 
 * @returns {void}
 */
function handlerSelectDatasource() {

    /*
     * Hide datasource filters
     */
    jQuery("#frmFilterByArea").hide();
    jQuery("#sbFilterAdm0").val(null).hide();
    jQuery("#sbFilterAdm1").val(null).hide();
    jQuery("#frmFilterByCategory").val(null).hide();

    clearDatasourceFilters();

    /*
     * Get the selected value of the datasource dropdown
     */
    var mDatasourceVal = jQuery("#sbDatasource option:selected").val();

    /*
     * If a value is selected, proceed
     */
    if (mDatasourceVal != "") {

        /*
         * Assign datasource to global variable
         */
        mDatasource = getSelectedDatasource();
        pDatasource = new OpenLayers.Projection("EPSG:" + mDatasource.ds_srs);
        listSrcDataStartItem = 0;

        if (mDatasource.ds_col_adm0 != null && mDatasource.ds_col_adm0 != "") {
            loadAdm0(mDatasourceVal);
        }

        if (mDatasource.ds_col_cat != null && mDatasource.ds_col_cat != "") {
            //console.log(mDatasource.ds_col_cat)
            loadCategories(mDatasourceVal);
        }

        loadSourceItems2(listSrcDataStartItem, listSrcDataNumItems);
        clearIcons();
        resetGeocodingForm();
        hideGeocodingForm();
    } else {
        jQuery("#selectable").empty();
        clearDatasourceFilters();
        pDatasource = null;
        hideGeocodingForm();
    }
}