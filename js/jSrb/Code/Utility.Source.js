function clearSourceItemListSelection() {
    jQuery("#selectable li").removeClass("ui-state-highlight");
    jQuery("#selectable li").removeClass("ui-selected");
    return true;
}

function loadCategories(pDatasourceId) {
    var currentCatTable = "ds" + pDatasourceId + "_cat";
    jQuery.getJSON("./ws/ws-cat.php", {
        t: currentCatTable
    },
    /**
     * @param {WsRetObj} data
     * @ignore
     */
    function(data) {
        /*
         * Reset category filter
         */
        jQuery("#sbFilterCategory").empty();
        var opt = jQuery("<option></option>");
        opt.html("all categories");
        opt.val("");
        jQuery("#sbFilterCategory").append(opt);
        if (data.v === WsStatus.success) {
            if (data.d.length > 0) {
                jQuery("#frmFilterByCategory").show();
                /*
                 * Add options to #sbFilterCategory control
                 */
                jQuery.each(data.d, function(mKey, mVal) {
                    var mOpt = jQuery("<option></option>");
                    mOpt.html(mVal.category);
                    mOpt.val(mVal.category);
                    mOpt.data("attributes", mVal);
                    jQuery("#sbFilterCategory").append(mOpt);
                });
            } else {
                console.log("No categories: " + data);
            }
        } else {
            showMsgBox(data.m, true);
        }
    });
}

function loadAdm0(pDatasourceId) {
    var currentAdm0Table = "ds" + pDatasourceId + "_adm0";
    jQuery.post(WsUrl.getDsAdm0Areas,
            {
                t: currentAdm0Table
            },
    function(pData) {
        /*
         * Reset adm1 filter
         */
        jQuery("#sbFilterAdm0").empty();
        var opt = jQuery("<option></option>");
        opt.html("all areas");
        opt.val("");
        jQuery("#sbFilterAdm0").append(opt);

        if (pData.status === 'success') {
            if (pData.records.length === 0) {
                console.log(pData.message);
            } else {
                jQuery("#frmFilterByArea").show();
                jQuery("#sbFilterAdm0").show();
                /*
                 * Add options to adm0
                 */
                jQuery.each(pData.records, function(key, val) {
                    var opt = jQuery("<option></option>");
                    opt.html(val.adm0);
                    opt.val(val.adm0);
                    opt.data("attributes", val);
                    jQuery("#sbFilterAdm0").append(opt);
                });
            }
        } else {
            console.log(pData.message);
        }
    }).fail(function(pResponse) {
        showMsgBox(pResponse.responseText);
    });
}

function loadAdm1(pDatasourceId, pCurrentAdm0) {
    /*
     * Construct adm0 table name based on id of datasource
     */
    var currentAdm0Table = "ds" + pDatasourceId + "_adm1";

    /*
     * Issue ajax request
     */
    jQuery.post(WsUrl.getDsAdm1Areas,
            {
                t: currentAdm0Table,
                a0: pCurrentAdm0
            },
    function(pData) {
        /*
         * Reset adm1 filter
         */
        jQuery('#sbFilterAdm1').empty();
        var opt = jQuery('<option></option>');
        opt.html('all areas');
        opt.val('');
        jQuery("#sbFilterAdm1").append(opt);
        if (pData.status === 'success') {
            if (pData.records.length > 0) {

                var mFilterAdm1 = jQuery("#sbFilterAdm1");
                mFilterAdm1.show();
                
                jQuery.each(pData.records, function(key, val) {
                    var opt = jQuery("<option></option>");
                    opt.html(val.adm1);
                    opt.val(val.adm1);
                    opt.data("attributes", val);
                    mFilterAdm1.append(opt);
                });
            }
        } else {
            console.log(pData.message);
        }
    });
}

function loadDatasources(pDatasourceId) {

    jQuery.getJSON("./ws/ws-load-datasources.php", {
        id: pDatasourceId
    },
    /**
     * @param {WsRetObj} data
     * @ignore
     */
    function(data) {
        if (data.v === 1) {
            if (data.d !== undefined && data.d.length === 0) {
                //console.log(data);
            } else {
                jQuery("#sbDatasource").empty();
                jQuery("#sbDatasource").append(jQuery("<option/>").val("").text("Please select a data source"));
                if (data.d !== undefined) {
                    jQuery.each(data.d, function(key, val) {
                        jQuery("#sbDatasource").append(jQuery("<option/>").val(val.id).text(val.ds_title).data("attributes", val));
                    });
                }
            }
        } else {
            console.log(data.m);
        }
    }).fail(function() {
        showMsgBox(new Array("Could not load datasources"), true);
    });
}

function getSelectedSourceItem() {
    return jQuery(".ui-selected", "#selectable").first();
}

function getSelectedDatasource() {
    return jQuery("#sbDatasource option:selected").data("attributes");
}

function clearSourceItemList() {
    jQuery('#selectable').html("");
}

/**
 * Load a source items for the specified data source
 * 
 * @param {Number} pOffset
 * @param {Number} pLimit
 * @returns {void}
 */
function loadSourceItems2(pOffset, pLimit) {

    var a0 = jQuery("#sbFilterAdm0").val();
    var a1 = jQuery("#sbFilterAdm1").val();
    var p = jQuery("#sbFilterProbability").val();
    var c = jQuery("#sbFilterCategory").val();
    var dsID = getSelectedDatasource().id;
    jQuery.getJSON(WsUrl.getItemsForDataSource,
            {
                t: mDatasource.ds_table,
                dsID: dsID,
                idc: mDatasource.ds_col_pk,
                nc: mDatasource.ds_col_name,
                xc: mDatasource.ds_col_x,
                yc: mDatasource.ds_col_y,
                ic: mDatasource.ds_col_image,
                uc: mDatasource.ds_col_url,
                a0_c: mDatasource.ds_col_adm0,
                a0: a0,
                p: p,
                a1_c: mDatasource.ds_col_adm1,
                a1: a1,
                c_c: mDatasource.ds_col_cat,
                c: c,
                offset: pOffset,
                limit: pLimit
            },
    /**
     * @param {WsRetObj} data
     * @ignore
     */
    function(data) {
        if (data.v === WsStatus.success) {
            //console.log(data);
            clearSourceItemList();
            jQuery.each(data.d, function(key, val) {
                /*
                 * Create new list item and assign default value if empty
                 */
                var listElement = jQuery('<li></li>');
                if (jQuery.trim(val._nc) == '') {
                    val._nc = 'Item #' + val._id;
                }
                listElement.html(val._nc);

                if (val.gc_probability != null) {
                    listElement.addClass("prob" + val.gc_probability);
                }
                listElement.addClass("ui-state-default");
                listElement.data("attributes", val)
                jQuery('#selectable').append(listElement);
            });
            /*
             * If the load source item does not yield any results, provide this notification.
             */
            if (data.d.length == 0) {
                var mNotification = jQuery("<div></div>");
                mNotification.addClass("ui-state-highlight");
                mNotification.text("You have completed all the items in the current selection or the current filters you have selected above excludes all items.");
                jQuery('#selectable').append(mNotification);
            }

            if (listSrcDataStartItem == 0) {
                jQuery("#btnPrevSrc").button("option", "disabled", true);
            } else {
                jQuery("#btnPrevSrc").button("option", "disabled", false);
            }
            if (data.d.length < listSrcDataNumItems) {
                jQuery("#btnNextSrc").button("option", "disabled", true);
            } else {
                jQuery("#btnNextSrc").button("option", "disabled", false);
            }
        }
        else {
            showMsgBox(data.m, true);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        showMsgBox(new Array(textStatus, errorThrown), false);
    });
}