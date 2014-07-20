if (typeof (OpenLayers) !== "undefined") {

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


    function setupGeneral() {
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
         * Add button formatting to top-menu buttons
         */
        jQuery(".topMenuBtn.nav").button();

        /**
         * Add button formatting for user interface buttons
         */
        jQuery(".uxBtn.act").button();


    }

    function setupSource() {
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
         * Make source item list selectable
         */
        jQuery("#selectable").selectable({
            stop: function() {
                handlerSelectSourceItem();
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
            handlerBtnNextSrc();
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
                        primary: 'ui-icon-document'
                    }
                }).click(function(pEvent) {
            handlerBtnNewDatasource();
        });

        /**
         * Attach handler to manage data sources button
         */
        jQuery("#btnManageDatasources").button(
                {
                    icons: {
                        primary: 'ui-icon-folder-open'
                    }}).click(function(pEvent) {
            handlerManageDatasources();
        });

    }

    function setupForm() {
        /*
         * Add buttons to attribute edit form
         */
        jQuery(".attribFormBtn").button();

        /*
         * Add change handler to tbConfidence
         */
        jQuery('#tbConfidence').change(function() {
            jQuery('#sliderConfidence').slider("value", this.value);
        });

        /*
         * Clear persistent URI from form
         */
        jQuery("#btnClearLinkedPURI").button(
                {
                    icons: {
                        primary: 'ui-icon-close'
                    }
                }).click(function(evt) {
            evt.preventDefault();
            jQUery('#tbLinkedPURI').val(null);
        });

        /*
         * View all attributes
         */
        jQuery("#btnViewAttributes").button(
                {
                    icons: {
                        primary: 'ui-icon-info'
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
                        primary: 'ui-icon-image'
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
                        primary: 'ui-icon-extlink'
                    }
                }).click(function(evt) {
            evt.preventDefault();
            handlerBtnViewUrl();
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
        });
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
        });
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
        //jQuery("#radio").buttonset();

    }

    function setupSearch() {
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
         * Select search database
         */
        jQuery("#sbSelectSearchDB").change(function() {
            handlerSelectSearchDBChange();
        });

        /**
         * Attach handler function to upgrade button in top-menu
         * Hide upgrade button in top-menu by default
         */
        jQuery("#btnUpgrade").button(
                {
                    icons: {
                        primary: 'ui-icon-refresh'
                    }
                }).click(function(evt) {
            evt.preventDefault();
            handlerBtnUpgrade();
        }).hide();

        /**
         * Attach handler to manage users button
         */
        jQuery("#btnManageUsers").button(
                {
                    icons: {
                        primary: 'ui-icon-person'
                    }
                }).click(function(evt) {
            evt.preventDefault();
            handlerManageUsers();
            return false;
        });

        /**
         * Add formatting and add icon to logout button
         */
        jQuery("#btnLogout").button(
                {
                    icons: {
                        primary: 'ui-icon-power'
                    }
                });
        /*
         * Make search result list selectable
         */
        jQuery("#listSearchResults").selectable({
            stop: function() {
                handlerSelectSearchResultItem();
            }
        }).css("cursor", "pointer");

    }

    function setupHeader() {
        /*
         * Add download buttons to topMenu
         */
        jQuery(".topMenuBtn.download").button({
            icons: {
                primary: 'ui-icon-disk'
            }
        });
        /*
         * Declare function for help button in top-menu
         */
        jQuery("#btnHelp").click(function(evt) {
            evt.preventDefault();
            handlerBtnHelp();
        });
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
            return false;
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
         * Declare function for download RDF button in top-menu
         */
        jQuery("#btnDownloadRDF").click(function(evt) {
            evt.preventDefault();
            handlerDownloadRDF();
            return false;
        });

        /*
         * Declare function for download RDF button in top-menu
         */
        jQuery("#btnDownloadCSV").click(function(evt) {
            evt.preventDefault();
            handlerDownloadCSV();
            return false;
        });

    }

    function setupNewDatasource() {
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

    /**
     * Once all HTML markup and linked files have been loaded, this
     * function is executed 
     * 
     * @returns void
     */
    function onDocumentReady() {
        setupGeneral();
        setupHeader();
        setupSource();
        setupForm();
        setupSearch();
        setupNewDatasource();

    }

    /**
     * Show the upgrade button on the header menu if an update is available for
     * download. This function is only available for users with a elevated access
     * privileges
     * 
     * @return void
     */
    function checkUpgrade() {
        jQuery.post("./ws/ws-check-updates.php", null, function(pData) {
            if (pData.status === 'error' && pData.records !== undefined) {
                jQuery("#btnUpgrade").show().button('option', 'label', 'Upgrade to v' + pData.records[0]);
                jQuery("#btnUpgrade").data("version", pData.records[0]);
            } else {
                console.log(jSrb.ErrMsg.installationUpToDate);
            }
        }, 'json').fail(function(pResponse) {
            showMsgBox(jSrb.ErrMsg.ajaxRequestError, true);
            console.log(pResponse.responseText);
        });
    }

    /* 
     * When the document is loaded, layout elements
     */
    jQuery("document").ready(function() {
        reflow(); // Setup the applicatin panes
        onDocumentReady();
        loadDatasources(null); // Null important not to pass any argument to function
        jSrb.search.loadSearchDBs();
        checkUpgrade();
    });

    /*
     * Whenever the window resizes, reflow elements
     */
    jQuery(window).resize(function() {
        reflow();
    });
}