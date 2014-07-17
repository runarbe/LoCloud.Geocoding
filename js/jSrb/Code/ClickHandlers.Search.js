function handlerBtnSearch() {
    loadSearchResults();
}

/**
 * Loads the user management form
 * @returns {void}
 */
function handlerManageUsers() {
    document.location.href = GcMods.manageUsers;
}

function handlerBtnUpgrade() {
    showMsgBox("Please wait while upgrade commences. This operation may take several minutes");
    jQuery.getJSON("./ws/ws-upgrade.php", {
        pVersion: jQuery("#btnUpgrade").data("version")
    }, function(pData) {
        if (pData.v === 1) {
            showMsgBox(pData.m, false);
            jQuery("#btnUpgrade").hide();
        } else {
            showMsgBox(pData.m, true);
        }
    }).fail(function() {
        showMsgBox("Web service request failed", true);
    });
}

function handlerSelectSearchResultItem() {
    /*
     * No function implemented yet
     */
    jQuery("#listSearchResults li").removeClass("ui-state-highlight");
    getSelectedSearchResultItem().addClass("ui-state-highlight");
    var mData = getSelectedSearchResultItem().data("attributes");
    var mLonLat = new OpenLayers.LonLat(mData.longitude, mData.latitude).transform(p4326,p900913);
    map.setCenter(mLonLat, defaultZoomTo);
}

function handlerSelectSearchDBChange() {
/*
     * No function implemented yet.
     */
    //console.log(); // Debug
}

function handlerBtnOpenWikimapia() {
    var mLonLat = map.getCenter().transform(p900913,p4326);
    var mUrl = "http://wikimapia.org/#lang=en&lat="+mLonLat.lat+"&lon="+mLonLat.lon+"&z=17&m=b";
    openPopup(mUrl);
}

function handlerBtnOpenPanoramio() {
    var mLonLat = map.getCenter().transform(p900913,p4326);
    var mUrl = "http://www.panoramio.com/map#lt="+mLonLat.lat+"&ln="+mLonLat.lon+"&z=3&k=1&a=1&tab=1&pl=all";
    openPopup(mUrl);
}

function handlerBtnOpenNokiaHere() {
    var mLonLat = map.getCenter().transform(p900913,p4326);
    var mUrl = "http://here.com/"+mLonLat.lat+","+mLonLat.lon+",16,0,0,hybrid.day";
    openPopup(mUrl);
}

function handlerBtnSearchGoogle() {
    var mUrl = "https://www.google.com/search?q=" + jQuery("#tbItemName").val();
    openPopup(mUrl);
}

function handlerBtnOpenGeonames() {
    var mLonLat = map.getCenter().transform(p900913,p4326);
    mUrl='http://www.geonames.org/maps/google_'+mLonLat.lat+'_'+mLonLat.lon+'.html'
    openPopup(mUrl);
}

function handlerBtnOpenGoogleMapmaker() {
    var mLonLat = map.getCenter().transform(p900913,p4326);
    mUrl='http://www.google.com/mapmaker?&gw=30&ll='+mLonLat.lat+','+mLonLat.lon+'&spn=0.025608,0.043173&z=15&vpid=1372083798749&lyt=large_map';
    openPopup(mUrl);
}

function handlerBtnOpenBookingCom() {
    openPopup("http://booking.com");
}