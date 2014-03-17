if (typeof(OpenLayers) !== "undefined") {
    
    /*
     * Variable naming conventions
     * sb[Name] = select/combo box control
     * tb[Name] = textbox
     */

// Define global variables 

    /**
     * The new data source wizard
     * @type Object
     */
    var ctlNewDataSourceWizard;

    /**
     * The search form
     * @type Object
     */
    var ctlSearchForm;

    /**
     * Setup, place and size window panes
     *
     * @returns void
     */
    function reflow() {
        var appWidth = jQuery(window).width();
        var appHeight = jQuery(window).height();
        var hdrHeight = 100;
        var stbHeight = 30;
        var srcHeight = appHeight - (hdrHeight + stbHeight);
        var srcWidth = 250;
        var schHeight = appHeight - (hdrHeight + stbHeight);
        var schWidth = 250;
        var frmHeight = 165;
        var frmBottom = stbHeight;
        var mapWidth = appWidth - (srcWidth + schWidth);
        var mapHeight = appHeight - (hdrHeight + stbHeight + frmHeight);
        jQuery("#divHeader").css("width", appWidth).css("height", hdrHeight);
        jQuery("#divSource").css("width", srcWidth).css("height", srcHeight).css("top", hdrHeight).css("left", 0);
        jQuery("#divSearch").css("width", schWidth).css("height", schHeight).css("top", hdrHeight).css("right", 0);
        jQuery("#divMap").css("width", mapWidth).css("height", mapHeight).css("top", hdrHeight).css("left", srcWidth);
        jQuery("#theMap").css("width", mapWidth).css("height", mapHeight);
        jQuery("#divStatusBar").css("width", appWidth).css("height", stbHeight).css("bottom", 0).css("left", 0);
        jQuery("#divForm").css("width", mapWidth).css("height", frmHeight).css("bottom", frmBottom).css("left", srcWidth);
    }

    /**
     * Once all HTML markup and linked files have been loaded, this
     * function is executed 
     * 
     * @returns void
     */
    function onDocumentReady() {

        /*
         * Add collapsible sections
         */
        jQuery(".collapsible").accordion(
                {
                    heightStyle: "content",
                    collapsible: true,
                    animate: false,
                    icons: {
                        header: "ui-icon-circle-arrow-e",
                        activeHeader: "ui-icon-circle-arrow-s"
                    }
                });

        /*
         * Add widget sections to search and source item lists
         */
        jQuery(".openWidget").accordion(
                {
                    heightStyle: "content",
                    collapsible: false
                });

        /*
         * Add styling to form elements
         */
        jQuery('.rounded').addClass("ui-corner-all");

        /*
         * Add zoom level slider
         */
        jQuery("#defaultZoomTo").slider(
                {
                    min: 1,
                    max: 20,
                    value: defaultZoomTo,
                    step: 1,
                    stop: function() {
                        defaultZoomTo = jQuery(this).slider("value");
                    }
                });

        /*
         * Add navigation buttons to topMenu
         */
        jQuery(".topMenuBtn.nav").button();

        /*
         * Add download buttons to topMenu
         */
        jQuery(".topMenuBtn.download").button({
            icons: {
                primary: 'ui-icon-disk'
            }
        });

        /*
         * Add buttons to attribute edit form
         */
        jQuery(".attribFormBtn").button();

        /*
         * Declare function for help button in top-menu
         */

        jQuery("#btnHelp").click(function(evt) {
            evt.preventDefault();
            handlerBtnHelp();
        });

        /**
         * Attach handler function to upgrade button in top-menu
         * Hide upgrade button in top-menu by default
         */
        jQuery("#btnUpgrade").click(function(evt) {
            evt.preventDefault();
            handlerBtnUpgrade();
        });
        jQuery("#btnUpgrade").hide();


        /*
         * Declare function for about button in top-menu
         */
        jQuery("#btnAbout").click(function(evt) {
            evt.preventDefault();
            handlerBtnAbout();
        });

        /*
         * Declare function for download GeoJSON button in top-menu
         */
        jQuery("#btnDownloadGeoJSON").click(function(evt) {
            evt.preventDefault();
            handlerDownloadGeoJSON();
        });

        /*
         * Declare function for download GeoJSON JavaScript button in top-menu
         */
        jQuery("#btnDownloadGeoJSONJavaScript").click(function(evt) {
            evt.preventDefault();
            handlerDownloadGeoJSONJavaScript();
        });

        /*
         * Declare function for download KML button in top-menu
         */
        jQuery("#btnDownloadKML").click(function(evt) {
            evt.preventDefault();
            handlerDownloadKML();
        });

        /*
         * Make source item list selectable
         */
        jQuery("#selectable").selectable({
            stop: function() {
                handlerSelectSourceItem();
            }
        }).css("cursor", "pointer");

        /*
         * Make search result list selectable
         */
        jQuery("#listSearchResults").selectable({
            stop: function() {
                handlerSelectSearchResultItem();
            }
        }).css("cursor", "pointer");

        /*
         * Previous source set button
         */
        jQuery("#btnPrevSrc").button({
            disabled: true,
            icons: {
                primary: 'ui-icon-circle-arrow-w'
            }
        }).click(function() {
            handlerBtnPrevSrc();
        });

        /*
         * Next source set button
         */
        jQuery("#btnNextSrc").button({
            disabled: true,
            icons: {
                secondary: 'ui-icon-circle-arrow-e'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnNextSrc()
        });

        /*
         * Cancel changes
         */
        jQuery("#btnCancel").button({
            icons: {
                primary: "ui-icon-cancel"
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnCancel();
        })

        /**
         * Save changes button
         */
        jQuery("#btnSaveGeocoding").button({
            icons: {
                primary: 'ui-icon-disk'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnSaveGeocoding();
        })


        /*
         * Save attribute edits button
         */
        jQuery("#btnSaveAttrEdits").button({
            icons: {
                primary: 'ui-icon-disk'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnSaveAttrEdits();
        });

        /*
         * Cancel attribute edits, close form button
         */
        jQuery("#btnCancelEditsClose").button({
            icons: {
                primary: 'ui-icon-cancel'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnCancelEditsClose();
        });

        /*
         * Setup toggle list
         */
        jQuery("#radio").buttonset();

        /*
         * Search button
         */
        jQuery("#btnSearch").button({
            icons: {
                primary: 'ui-icon-search'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnSearch();
        });

        /*
         * Open Wikimapia button
         */
        jQuery("#btnOpenWikimapia").button({
            icons: {
                secondary: 'ui-icon-link'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnOpenWikimapia();
        });

        /*
         * Open Geonames button
         */
        jQuery("#btnOpenGeonames").button({
            icons: {
                secondary: 'ui-icon-link'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnOpenGeonames();
        });

        /*
         * Open Google Map Maker button
         */
        jQuery("#btnOpenGoogleMapmaker").button({
            icons: {
                secondary: 'ui-icon-link'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnOpenGoogleMapmaker();
        });

        /*
         * Open Panoramio button
         */
        jQuery("#btnOpenPanoramio").button({
            icons: {
                secondary: 'ui-icon-link'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnOpenPanoramio();
        });

        /*
         * Open Booking.com button
         */

        jQuery("#btnOpenBookingCom").button({
            icons: {
                secondary: 'ui-icon-link'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnOpenBookingCom();
        });

        /*
         * Open Nokia HERE button
         */

        jQuery("#btnOpenNokiaHere").button({
            icons: {
                secondary: 'ui-icon-link'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnOpenNokiaHere();
        });

        /*
         * Open Nokia HERE button
         */

        jQuery("#btnSearchGoogle").button({
            icons: {
                secondary: 'ui-icon-link'
            }
        }).click(function(evt) {
            evt.preventDefault();
            handlerBtnSearchGoogle();
        });

        /*
         * Select datasource control
         */
        jQuery("#sbDatasource").change(function() {
            handlerSelectDatasource();
        });

        /*
         * Select probability filter
         */
        jQuery("#sbFilterProbability").change(function() {
            handlerSelectFilterProbability();
        });

        /*
         * Select adm0 filter
         */
        jQuery("#sbFilterAdm0").change(function() {
            handlerSelectFilterAdm0();
        });

        /*
         * Select adm1 filter
         */
        jQuery("#sbFilterAdm1").change(function() {
            handlerSelectFilterAdm1();
        });


        /*
         * Select category filter
         */
        jQuery("#sbFilterCategory").change(function() {
            handlerSelectFilterCategory();
        });

        /*
         * Add new datasource button
         */
        jQuery("#btnNewDatasource").button(
                {
                    icons: {
                        primary: 'ui-icon-circle-plus'
                    }
                }).click(function(evt) {
            evt.preventDefault();
            handlerBtnNewDatasource();
        });

        /*
         * View all attributes
         */
        jQuery("#btnViewAttributes").button(
                {
                    icons: {
                        primary: 'ui-icon-circle-plus'
                    }
                }).click(function(evt) {
            evt.preventDefault();
            handlerBtnViewAttributes();
        });

        /*
         * View image if present
         */
        jQuery("#btnViewImage").button(
                {
                    icons: {
                        primary: 'ui-icon-folder-open'
                    }
                }).click(function(evt) {
            evt.preventDefault();
            handlerBtnViewImage();
        });

        /*
         * View URL link if present
         */
        jQuery("#btnViewUrl").button(
                {
                    icons: {
                        primary: 'ui-icon-folder-open'
                    }
                }).click(function(evt) {
            evt.preventDefault();
            handlerBtnViewUrl();
        });

        /*
         * Select search database
         */
        jQuery("#sbSelectSearchDB").change(function() {
            handlerSelectSearchDBChange();
        });

        /**
         * Make tabs in file upload wizard
         */
        ctlNewDataSourceWizard = jQuery("div.tabs").tabs();

        /**
         * Set options for file upload wizard
         */
        ctlNewDataSourceWizard.tabs("option", {
            disabled: [1, 2],
            active: 0,
            heightStyle: "fill"
        });


    }
    ;

    /**
     * Show the upgrade button on the header menu if an update is available for
     * download. This function is only available for users with a elevated access
     * privileges
     * 
     * @return void
     */
    function checkUpgrade() {
        jQuery.getJSON("./ws/ws-check-updates.php", null, function(pData) {
            if (pData.v === -1) {
                jQuery("#btnUpgrade").show().button('option', 'label', 'Upgrade to v' + pData.d);
                jQuery("#btnUpgrade").data("version", pData.d[0]);
            }
        });
    }

    /* 
     * When the document is loaded, layout elements
     */
    jQuery("document").ready(function() {
        reflow(); // Setup the applicatin panes
        onDocumentReady();
        loadDatasources(null); // Null important not to pass any argument to function
        loadSearchDBs();
        checkUpgrade();
    });

    /*
     * Whenever the window resizes, reflow elements
     */
    jQuery(window).resize(function() {
        reflow();
    });
}