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
    var mSbDatasourceId = jQuery('#sbDatasource').val();
    var mThisVal = jQuery('#sbFilterAdm0').val();

    if (mThisVal === null || mThisVal === undefined || mThisVal === '') {
        jQuery('#sbFilterAdm1').val(null).hide();
    } else {
        loadAdm1(mSbDatasourceId, mThisVal);
    }
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
 * Handler to open the metadata source module
 * @returns {void}
 */
function handlerManageDatasources() {
    var mDatasource = getSelectedDatasource();
    console.log(mDatasource);
    if (mDatasource !== undefined) {
        window.location.href = GcMods.manageDatasources + "id=" + mDatasource.id;
    } else {
        window.location.href = GcMods.manageDatasources;
    }
    return;
}

/**
 * Function that is triggered when the users selects a source item from the
 * item list on the bottom right hand side of the screen
 * 
 * @returns {void}
 */
function handlerSelectSourceItem() {
    jQuery('#selectable li').removeClass('ui-state-highlight');
    getSelectedSourceItem().addClass('ui-state-highlight')
            .each(function() {
                var mItemAttr = getSelectedSourceItem().data('attributes');

                // Set form values
                jQuery('#tbItemName', '#gc').val(mItemAttr._nc);
                jQuery('#tbMapResolution', '#gc').val(mItemAttr.gc_mapresolution);
                jQuery('#tbLinkedPURI', '#gc').val(mItemAttr.gc_dbsearch_puri);
                jQuery('#tbConfidence').val(mItemAttr.gc_confidence);
                jQuery('#hdnAutoPkId', '#gc').val(mItemAttr.autopk_id);
                jQuery('#hdnFieldChanges', '#gc').val(mItemAttr.gc_fieldchanges);

                // Set show URL button visibility
                if (mItemAttr._uc === null) {
                    jQuery('#btnViewUrl').hide();
                } else {
                    jQuery('#hdnUrl').val(mItemAttr._uc);
                    jQuery('#btnViewUrl').show();
                }

                // Set show image button visibility
                if (mItemAttr._ic === null) {
                    jQuery('#btnViewImage').hide();
                } else {
                    jQuery('#hdnImage').val(mItemAttr._ic);
                    jQuery('#btnViewImage').show();
                }

                // Clear any existing icons from map
                clearIcons();

                // Add alternate markers to map
                jQuery.each(mItemAttr.gc_alternates, function(key3, val3) {
                    var mLonLat = new OpenLayers.LonLat(val3.gc_lon, val3.gc_lat).transform(p4326, p900913);
                    addAlternateIcons(mLonLat.clone());
                    mLonLat = null;
                });
                
                // Add proposed markers to map
                if (mItemAttr.gc_lon !== null && mItemAttr.gc_lat !== null) {
                    var mLonLat2 = new OpenLayers.LonLat(mItemAttr.gc_lon, mItemAttr.gc_lat).transform(p4326, projDatasource);
                    jQuery("#tbLongitude").val(roundCoordinates(mLonLat2.lon, mDatasource.ds_coord_prec));
                    jQuery("#tbLatitude").val(roundCoordinates(mLonLat2.lat, mDatasource.ds_coord_prec));

                    var mLonLat = new OpenLayers.LonLat(mItemAttr.gc_lon, mItemAttr.gc_lat).transform(p4326, p900913);
                    addProposedIcon(mLonLat.clone());
                    map.setCenter(mLonLat, defaultZoomTo);

                } else {
                    jQuery('#tbLongitude').val(null);
                    jQuery('#tbLatitude').val(null);
                }

                // Add original location marker to map
                if (mItemAttr._x !== null && mItemAttr._y !== null) {

                    mLonLat = new OpenLayers.LonLat(mItemAttr._x, mItemAttr._y).transform(projDatasource, p900913);
                    addExistingIcon(mLonLat.clone());

                    if (jQuery('#tbLongitude').val() === null || (mItemAttr.gc_lon === null || mItemAttr.gc_lat === null)) {
                        jQuery('#tbLongitude').val(mItemAttr._x);
                        jQuery('#tbLatitude').val(mItemAttr._y);
                        map.setCenter(mLonLat, defaultZoomTo);
                    }

                }

                jQuery('#tbGeom', '#gc').val(mItemAttr.gc_geom);
                
                if (mItemAttr.gc_geom !== null) {
                    var mWktReader = new OpenLayers.Format.WKT();
                    var mFeature = mWktReader.read(mItemAttr.gc_geom);
                    jSrb.map.featureLayer.addFeatures([mFeature]);
                }
                // Show transfer effect from list to form
                jQuery(".ui-selected", "#selectable").first().effect("transfer", {
                    to: jQuery("#divForm")
                }, 500);

                // Show geocoding form
                showGeocodingForm();

            });
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
        projDatasource = new OpenLayers.Projection("EPSG:" + mDatasource.ds_srs);
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
        projDatasource = null;
        hideGeocodingForm();
    }
}