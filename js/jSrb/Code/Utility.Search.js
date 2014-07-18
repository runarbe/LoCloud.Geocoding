function getSelectedSearchDB() {
    return jQuery("#sbSelectSearchDB option:selected");
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
    var mSearchDB = getSelectedSearchDB();
    
    if (mSearchDB.data("attributes") === undefined) {
        showMsgBox(jSrb.ErrMsg.selectDatabaseFirst);
        return;
    }
    var bbox = null;
    if (jQuery("#cbLimitToBbox").prop("checked")) {
        bbox = map.getExtent().transform(p900913, p4326).toBBOX();
    }

    var mWebService = mSearchDB.data("attributes").sch_webservice;
    var mTable = mSearchDB.data("attributes").sch_table;
    var mSearchTerm = jQuery("#tbSearchTerm").val();
    jQuery.post("./ws/" + mWebService + ".php",
            {
                q: mSearchTerm,
                bbox: bbox,
                t: mTable
            },
    function(pResponse) {
        /*
         * Empty search result list
         */
        jQuery('#listSearchResults').empty();
        jQuery('#txtSearchHint').empty();

        if (pResponse.status === 'success') {
            if (pResponse.records.length > 0) {
                /*
                 * Add options to adm0
                 */
                jQuery.each(pResponse.records, function(key, val) {
                    var mLi = jQuery('<li class=\"ui-state-default\"></li>')
                            .html(val.asciiname)
                            .val(val.geonameid)
                            .data('attributes', val);
                    jQuery('#listSearchResults').append(mLi);
                });
            } else {
                jQuery('#txtSearchHint').empty().html(jSrb.ErrMsg.noResults);
            }
        } else {
            showMsgBox(pResponse.message, true);
        }
    }).fail(function(pResponse) {
        showMsgBox(jSrb.ErrMsg.ajaxRequestError, true);
        console.log(pResponse.responseText);
    });

}
