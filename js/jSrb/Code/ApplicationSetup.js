/*
 * Variable naming conventions
 * sb[Name] = select/combo box control
 * tb[Name] = textbox
 */

/*
 * This code will only be run if the OpenLayers library is previously loaded
 */
if (typeof(OpenLayers) !== "undefined") {

// Define global variables 

    /**
     * The new data source wizard
     * @type Object
     */
    jSrb.ctlNewDataSourceWizard;

    /**
     * The search form
     * @type Object
     */
    jSrb.ctlSearchForm;

    /**
     * Setup, place and size window panes
     */
    /*
     jSrb.AppSetup.reflow = function() {
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
     var mapWidth = (appWidth - (srcWidth + schWidth)) - 4;
     var mapHeight = (appHeight - (hdrHeight + stbHeight + frmHeight)) - 4;
     jQuery("#divHeader").css("width", appWidth).css("height", hdrHeight);
     jQuery("#divSource").css("width", srcWidth).css("height", srcHeight).css("top", hdrHeight).css("left", 0);
     jQuery("#divSearch").css("width", schWidth).css("height", schHeight).css("top", hdrHeight).css("right", 0);
     jQuery("#divMap").css("width", mapWidth).css("height", mapHeight).css("top", hdrHeight).css("left", srcWidth);
     jQuery("#theMap").css("width", mapWidth).css("height", mapHeight);
     jQuery("#divStatusBar").css("width", appWidth).css("height", stbHeight).css("bottom", 0).css("left", 0);
     jQuery("#divForm").css("width", mapWidth).css("height", frmHeight).css("bottom", frmBottom).css("left", srcWidth);
     };
     */

    jSrb.AppSetup.reflow = function() {

        var appWidth = jQuery(window).width();
        var appHeight = jQuery(window).height();
        jQuery("#mainLayout").width(appWidth).height(appHeight);

        var pstyle = 'background-color: #F5F6F7; border: 1px solid #dfdfdf; padding: 5px;';

        jQuery("#mainLayout").w2layout({
            name: 'jSrb',
            panels: [
                {
                    type: 'top', size: 30, resizable: true, style: pstyle, toolbar:
                            {
                                items: [
                                    {type: 'button', id: 'mnuHome', caption: 'Home', img: 'icon-page'},
                                    {type: 'break'},
                                    {type: 'menu', id: 'Admin', caption: 'Admin', img: 'icon-folder',
                                        items: [
                                            {text: 'Add new user', id: "mnuAddUsers", img: 'icon-page'},
                                            {text: 'Manage users', id: "mnuManageUsers", img: 'icon-page'}
                                        ]
                                    },
                                    {type: 'menu', id: 'mnuImport', caption: 'Import', img: 'icon-folder',
                                        items: [
                                            {text: 'Import dataset', id: "mnuImportDataSet", img: 'icon-page'},
                                            {text: 'Manage datasets', id: "mnuManageDataSets", img: 'icon-page'}
                                        ]
                                    },
                                    {type: 'menu', id: 'mnuItemExport', caption: 'Export', img: 'icon-save',
                                        items: [
                                            {text: 'Export as GeoJson assigned to variable', id: 'mnuExportAsGeoJsonVar', img: 'icon-save'},
                                            {text: 'Export as GeoJson', id: 'mnuExportAsGeoJson', img: 'icon-save'},
                                            {text: 'Export as KML', id: 'mnuExportAsKML', img: 'icon-save'},
                                            {text: 'Export as RDF', id: 'mnuExportAsRDF', img: 'icon-save'}
                                        ]
                                    },
                                    {type: 'break'},
                                    {type: 'spacer'},
                                    {type: 'button', id: 'mnuAbout', caption: 'About', img: 'icon-page'},
                                    {type: 'button', id: 'mnuHelp', caption: 'Help', img: 'icon-page'},
                                    {type: 'break'},
                                    {type: 'button', id: 'mnuCheckForUpdate', caption: 'Upgrade', img: 'icon-page'},
                                    {type: 'button', id: 'mnuLogout', caption: 'Logout', img: 'icon-page'}
                                ],
                                onClick: function(event) {
                                    jSrb.AppSetup.executedHeaderHandlers(
                                            (event.subItem !== undefined) ? event.subItem.id : event.target
                                            );
                                    return;
                                }
                            }
                    , content: ''},
                {type: 'left', size: 250, resizable: true, style: pstyle},
                {type: 'main', style: pstyle},
                {type: 'preview', size: '25%', resizable: true, style: pstyle},
                {type: 'right', size: 250, resizable: true, style: pstyle},
                {type: 'bottom', size: 50, resizable: false, style: pstyle}
            ]
        });

        // Setup nested source layout
        jQuery().w2layout({
            name: 'sourceLayout',
            panels: [
                {type: 'main', style: pstyle},
                {type: 'preview', size: '50%', resizable: true, style: pstyle}
            ]
        });
        w2ui['jSrb'].html('left', w2ui['sourceLayout']);

        // Set content of left.main
        w2ui['sourceLayout'].html('main', jQuery("#divSource").detach().html());

        // Set content of left.preview
        w2ui['sourceLayout'].content('preview', jQuery().w2grid({
            name: 'sourceGrid',
            selectType: 'row',
            limit: 100,
            url: 'http://localhost:81/locloudgc/ws/ws-load-source-items3.php?t=ds33&dsID=33&idc=id&nc=namn&xc=null&yc=null&ic=null&uc=&a0_c=null&a0=&p=&a1_c=null&a1=&c_c=null&c=&offset=0&limit=10',
            columns: [
                {field: 'recid', caption: 'ID', size: '25px'},
                {field: 'gc_probability', caption: '?', size: '25px'},
                {field: '_nc', caption: 'Name', size: '100%'}
            ],
            onSelect: function(event) {
                event.onComplete = function() {
                    var mRecId = w2ui['sourceGrid'].getSelection()[0];
                    var mRec = w2ui['sourceGrid'].get(mRecId);
                    handlerSelectSourceItem2(mRec);
                };
            }
        }));

        // Setup nested search layout
        jQuery().w2layout({
            name: 'searchLayout',
            panels: [
                {type: 'main', style: pstyle},
                {type: 'preview', size: '50%', resizable: true, style: pstyle}
            ]
        });
        w2ui['jSrb'].html('right', w2ui['searchLayout']);

        // Set content of right.main
        w2ui['searchLayout'].html('main', jQuery("#divSearch").detach().html());

        // Set content of main
        w2ui['jSrb'].html('main', jQuery("#divMap").detach().html());
        jQuery("#theMap").width("100%").height("100%");

        w2ui['jSrb'].html('preview', jQuery("#divForm").detach().html());
        w2ui['jSrb'].html('bottom', jQuery("#divStatusBar").detach().html());

    };

    /**
     * Style controls
     * 
     * @static
     */
    jSrb.AppSetup.styleControls = function() {

        /*
         * Style top menu
         */
        //jQuery("ul.menu").dropit();

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
         * Hide upgrade button by default (will be shown only if upgrade is
         * available
         */
        jQuery("#btnUpgrade").hide();


        /*
         * Add zoom level slider
         */
        jQuery("#defaultZoomTo").slider(
                {
                    min: 1,
                    max: 20,
                    value: jSrb.Map.defaultZoomTo,
                    step: 1,
                    stop: function() {
                        jSrb.Map.defaultZoomTo = jQuery(this).slider("value");
                    }
                });
    }

    /**
     * Attach handlers to interactive elements in the geocoding form
     * @return {void} description
     */
    jSrb.AppSetup.attachFormHandlers = function() {

        /*
         * Add buttons to attribute edit form
         */
        jQuery(".attribFormBtn").button();

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

    };

    /**
     * 
     * @param {String} pKeyWord
     * @returns {void}
     */
    jSrb.AppSetup.executedHeaderHandlers = function(pKeyWord) {
        switch (pKeyWord) {
            case "mnuHome":
                break;
            case "mnuImportDataSet":
                jSrb.Handlers.handlerBtnNewDatasource();
                break;
            case "mnuHelp":
                jSrb.Handlers.handlerBtnHelp();
                break;
            case "mnuCheckForUpdate":
                jSrb.Handlers.handlerBtnUpgrade();
                break;
            case "mnuAbout":
                jSrb.Handlers.handlerBtnAbout();
                break;
            case "mnuExportAsGeoJson":
                jSrb.Handlers.handlerDownloadGeoJSON();
                break;
            case "mnuExportAsGeoJsonVar":
                jSrb.Handlers.handlerDownloadGeoJSONJavaScript();
                break;
            case "mnuExportAsKML":
                jSrb.Handlers.handlerDownloadKML();
                break;
            case "mnuExportAsRDF":
                console.log("to be implemented");
                break;
            default:
                console.log("Event not implemented: " + pKeyWord);
                break;
        }

    }
    /**
     * Attach handlers to interactive elements in the page header
     */
    //jSrb.AppSetup.attachHeaderHandlers = function() {

    /*
     * Add handler function for new datasource button
     */
    /*jQuery("#btnNewDatasource").click(function(evt) {
     evt.preventDefault();
     jSrb.Handlers.handlerBtnNewDatasource();
     });*/

    /*
     * Declare function for help button in top-menu
     */
    /*jQuery("#btnHelp").click(function(evt) {
     evt.preventDefault();
     jSrb.Handlers.handlerBtnHelp();
     });*/

    /**
     * Attach handler function to upgrade button in top-menu
     * Hide upgrade button in top-menu by default
     */
    /*jQuery("#btnUpgrade").click(function(evt) {
     evt.preventDefault();
     jSrb.Handlers.handlerBtnUpgrade();
     });*/

    /*
     * Declare function for about button in top-menu
     */
    /*jQuery("#btnAbout").click(function(evt) {
     evt.preventDefault();
     jSrb.Handlers.handlerBtnAbout();
     });*/

    /*
     * Declare function for download GeoJSON button in top-menu
     */
    /*jQuery("#btnDownloadGeoJSON").click(function(evt) {
     evt.preventDefault();
     jSrb.Handlers.handlerDownloadGeoJSON();
     });*/

    /*
     * Declare function for download GeoJSON JavaScript button in top-menu
     */
    /*jQuery("#btnDownloadGeoJSONJavaScript").click(function(evt) {
     evt.preventDefault();
     jSrb.Handlers.handlerDownloadGeoJSONJavaScript();
     });*/

    /*
     * Declare function for download KML button in top-menu
     */
    /*jQuery("#btnDownloadKML").click(function(evt) {
     evt.preventDefault();
     jSrb.Handlers.handlerDownloadKML();
     });*/

    //};

    /**
     * Attach handler functions to interactive elements in the source panel
     */
    jSrb.AppSetup.attachSourceHandlers = function() {
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
            handlerBtnNextSrc();
        });

    };

    /**
     * Once all HTML markup and linked files have been loaded, this
     * function is executed 
     */
    jSrb.AppSetup.onDocumentReady = function() {

        jSrb.AppSetup.styleControls();
        //jSrb.AppSetup.attachHeaderHandlers();
        jSrb.AppSetup.attachSourceHandlers();
        jSrb.AppSetup.attachFormHandlers();

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
        jSrb.ctlNewDataSourceWizard = jQuery("div.tabs").tabs();

        /**
         * Set options for file upload wizard
         */
        jSrb.ctlNewDataSourceWizard.tabs("option", {
            disabled: [1, 2],
            active: 0,
            heightStyle: "fill"
        });

    };

    /**
     * Show the upgrade button on the header menu if an update is available for
     * download. This function is only available for users with a elevated access
     * privileges
     */
    jSrb.AppSetup.checkUpgrade = function() {
        jQuery.getJSON(WsUrl.getLatestVersion, null, function(pData) {
            if (pData.v === -1) {
                jQuery("#btnUpgrade").show().button('option', 'label', 'Upgrade to v' + pData.d);
                jQuery("#btnUpgrade").data("version", pData.d[0]);
            }
        });
    };

    /* 
     * When the document is loaded, layout elements
     */
    jQuery("document").ready(function() {
        jSrb.AppSetup.reflow(); // Setup the application panes, layout
        jSrb.AppSetup.onDocumentReady();
        loadDatasources(null); // Null important not to pass any argument to function
        loadSearchDBs();
        jSrb.AppSetup.checkUpgrade();
    });

    /*
     * Whenever the window resizes, reflow elements
     */
    /*jQuery(window).resize(function() {
     jSrb.AppSetup.reflow();
     });*/
}