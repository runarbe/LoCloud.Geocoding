/**
 * Handler function that executes when search button is clicked
 * @returns {void}
 */
function handlerBtnSearch() {
    jSrb.search.loadSearchResults();
}

/**
 * Handler function that loads the user management module
 * @returns {void}
 */
function handlerManageUsers() {
    document.location.href = GcMods.manageUsers;
}

/**
 * Handler function that executes when the upgrade button is clicked
 * @returns {void}
 */
function handlerBtnUpgrade() {
    showMsgBox("Please wait while upgrade commences. This operation may take several minutes");
    jQuery.getJSON(WsUrl.upgradeInstallation, {
        pVersion: jQuery("#btnUpgrade").data("version")
    }, function(pData) {
        if (pData.v === 1) {
            showMsgBox(pData.m, false);
            jQuery("#btnUpgrade").hide();
        } else {
            showMsgBox(pData.m, true);
        }
    }).fail(function(pResponse) {
        showMsgBox(jSrb.ErrMsg.ajaxRequestError, true);
        console.log(pResponse.responseText);
    });
}

/**
 * Handler function that is executed when a user selects an item from the search
 * results
 * @returns {void}
 */
function handlerSelectSearchResultItem() {
    jSrb.search.selectSearchResultItem();
}


/**
 * Hm?
 * @returns {void}
 */
function handlerSelectSearchDBChange() {
}

/**
 * Opens a popup window of Wikimapia zoomed to the same extent and level as the
 * currently displayed map in the geocoding application
 * @returns {void}
 */
function handlerBtnOpenWikimapia() {
    var mLonLat = map.getCenter().transform(p900913, p4326);
    var mUrl = "http://wikimapia.org/#lang=en&lat=" + mLonLat.lat + "&lon=" + mLonLat.lon + "&z=17&m=b";
    openPopup(mUrl);
}

/**
 * Opens a popup window of Panoramio zoomed to the same extent and level as the
 * currently displayed map in the geocoding application
 * @returns {void}
 */
function handlerBtnOpenPanoramio() {
    var mLonLat = map.getCenter().transform(p900913, p4326);
    var mUrl = "http://www.panoramio.com/map#lt=" + mLonLat.lat + "&ln=" + mLonLat.lon + "&z=3&k=1&a=1&tab=1&pl=all";
    openPopup(mUrl);
}

/**
 * Opens a popup window of Nokia HERE zoomed to the same extent and level as the
 * currently displayed map in the geocoding application
 * @returns {void}
 */

function handlerBtnOpenNokiaHere() {
    var mLonLat = map.getCenter().transform(p900913, p4326);
    var mUrl = "http://here.com/" + mLonLat.lat + "," + mLonLat.lon + ",16,0,0,hybrid.day";
    openPopup(mUrl);
}

/**
 * Opens a popup window with a Google search for the currently selected text in the
 * of the selected item currently visible in the form below the map
 * @returns {void}
 */

function handlerBtnSearchGoogle() {
    var mUrl = "https://www.google.com/search?q=" + jQuery("#tbItemName").val();
    openPopup(mUrl);
}

/**
 * Opens a popup window of Geonames zoomed to the same extent and level as the
 * currently displayed map in the geocoding application
 * @returns {void}
 */
function handlerBtnOpenGeonames() {
    var mLonLat = map.getCenter().transform(p900913, p4326);
    mUrl = 'http://www.geonames.org/maps/google_' + mLonLat.lat + '_' + mLonLat.lon + '.html'
    openPopup(mUrl);
}

/**
 * Opens a popup window of Google Map Maker zoomed to the same extent and level as the
 * currently displayed map in the geocoding application
 * @returns {void}
 */
function handlerBtnOpenGoogleMapmaker() {
    var mLonLat = map.getCenter().transform(p900913, p4326);
    mUrl = 'http://www.google.com/mapmaker?&gw=30&ll=' + mLonLat.lat + ',' + mLonLat.lon + '&spn=0.025608,0.043173&z=15&vpid=1372083798749&lyt=large_map';
    openPopup(mUrl);
}