if (typeof (OpenLayers) !== 'undefined') {

    /*
     * JavaScript datatypes
     * String, Number, Boolean, Array, Object, Null, Undefined.
     */

    /**
     * Global configuration settings for the application
     * @class JsConf
     */
    var jsConf = {
        /**
         * The title of the application
         * @type String
         */
        app_title: "LoCloud Geocoding Microservice",
        /**
         * Application version
         * @type String
         */
        app_version: "1.0"
    };

    /**
     * Name of default source table
     * @type String
     */
    var sourceTable = "";

    /**
     * The first item to show in the list
     * @type Number
     * @default 0
     */
    var listSrcDataStartItem = 0;

    /**
     * The number of items to show in the list
     * @type Number
     * @default 10
     */
    var listSrcDataNumItems = 10;

    /**
     * The icon that will show the current position of an item
     * @type OpenLayers.Icon
     */
    var mapIconCurrentPosition;

    /**
     * The icon that will show the users proposed position for an item
     * of the aplplication
     * @type OpenLayers.Icon
     */
    var mapIconNewPosition;

    /**
     * The layer that will hold markers
     * @type OpenLayers.MarkerLayer
     */
    var markers;

    /**
     * 
     * @type OpenLayers.Marker
     */
    var currentMarker;

    /**
     * 
     * @type OpenLayers.Marker
     */
    var proposedMarker;

    /**
     * An array of OpenLayers.Marker objects specifying existing proposed locations
     * for an item as read from the database.
     * @type Array
     */
    var alternateMarkers = new Array();

    /**
     * The default level that the application will zoom to when clicking an item
     * in the source data to be geocoded
     * 
     * @type Number
     * @default 13
     */
    var defaultZoomTo = 13;

    /**
     * An object holding information about the selected data source.
     * @type jSrb.DataSource
     * @default null
     */
    var mDatasource = null;

    /**
     * The number of significant digits for decimal numbers
     * @type Number
     * @default 2
     */
    var coordinatePrecision = 2;

    /**
     * The map layers to show by default at start-up
     * @type Array
     */
    var defaultMapLayers = new Array();

    /**
     * Wheter or not to include Google Maps as background map
     * @type Boolean
     */
    var useGoogleMaps = true;

    /**
     * Wheter or not to include Bing Maps as backgorund map
     * @type Boolean
     */
    var useBingMaps = false;

    /**
     * Wheter or not to include local map tiles as background map
     * @type Boolean
     */
    var useLocalTiles = false;

    /**
     * Bing MAP API Key
     * @type String
     * @description Sign up for an API-key with BING. This must be specific to the
     * web site URL that the map is being hosted from.
     * 
     * The key 'ApH6SQ0ZxiJAIv7_uGCBvKhmz-P9t4fVt3-gElJrcPQb2tIlHOEenjTRZPa5_4oE' is
     * for a localhost address in the name of (Stein) Runar Bergheim.
     */
    var keyBingMapsApi = 'ApH6SQ0ZxiJAIv7_uGCBvKhmz-P9t4fVt3-gElJrcPQb2tIlHOEenjTRZPa5_4oE';

    /**
     * Main map object
     * @type OpenLayers.Map
     */
    var map;

    /**
     * The layer to hold selection
     * @type OpenLayers.Layer
     */
    var select;

// Declare constants

    /**
     * Longitude: The longitude that the map will be centered on at startup
     * @type Number 
     * @default 15 (Europe)
     */
    //var lon = 56.835; // Oman
    //var lon = 15; // Europe
    var lon = 20; // Norge

    /**
     * Latitude: The latitude that the map will be centered on at startup
     * @type Number
     * @default 50 (Europe)
     */
    //var lat = 21.34; // Oman
    //var lat = 50; // Europe
    var lat = 65; // Norge

    /**
     * The zoom level of the map at startup
     * @type Double 
     * @default 3 (Europe)
     */
    //var zoom = 5; // Oman
    //var zoom = 3; // Europe
    var zoom = 4; // Europe

    /*
     * Initialize default projections that will be available in the applications
     */

    /**
     * The built-in Google Projection object of OpenLayers
     * @type OpenLayers.Projection
     */
    var p900913 = new OpenLayers.Projection("EPSG:900913");

    /**
     * The built-in WGS 84 Lat/Lon Projection object of OpenLayers
     * @type OpenLayers.Projection
     */
    var p4326 = new OpenLayers.Projection("EPSG:4326");

    /**
     * A data source used for ???
     * @type jSrb.DataSource
     */
    var projDatasource = null;

    /**
     * Set default extent in lon-lat.
     * @type OpenLayers.Bounds
     */
    var mExtent = new OpenLayers.Bounds(51, 15, 61, 27);
    mExtent = mExtent.transform(p4326, p900913);

    /**
     * OpenLayers configuration option
     * 
     * Set proxy host for cross-domain requests from JavaScript
     * @type String
     */
    OpenLayers.ProxyHost = "./proxy.php?url=";

    /**
     * OpenLayers configuration option
     * 
     * Set number of reload attempts for retrieving tiles
     * 
     * @type Number
     * @default 3
     */
    OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;


    /**
     * Run once all has been completed
     * @function onDocumentReady
     */
    jQuery("document").ready(function() {

        initMapLayers();

        /* 
         * Define marker-layer to hold temporary points
         */
        markers = new OpenLayers.Layer.Markers("Geocoding markers");
        var mFeatureLayer = new OpenLayers.Layer.Vector("Geocoding features");

        // Attach handler function to 
        mFeatureLayer.preFeatureInsert = function(pFeature) {
            // Remove any existing features before processing new
            mFeatureLayer.destroyFeatures();
            // Execute handler function
            handlerPreAddFeatureToMap(pFeature);
        };

        /*
         * Create map control
         */
        map = new OpenLayers.Map('theMap', {
            numZoomLevels: 20,
            units: 'm',
            projection: p900913,
            displayProjection: p900913,
            controls: [
                new OpenLayers.Control.LayerSwitcher(),
                new OpenLayers.Control.Navigation(),
                new OpenLayers.Control.Zoom(),
                new OpenLayers.Control.ScaleLine(),
                new OpenLayers.Control.ZoomToMaxExtent(),
                new OpenLayers.Control.MousePosition({
                    autoActivate: true
                }),
                new OpenLayers.Control.EditingToolbar(mFeatureLayer)]
        });

        /*
         * Custom local map from OSM tiles stored in directory hierarchy
         */
        var localTiles = new OpenLayers.Layer.OSM("OSM base map (local)", "./tiles/${z}/${x}/${y}.png", {
            numZoomLevels: 13,
            alpha: true,
            isBaseLayer: true
        });

        /*
         * Add markers to default map layers
         */
        defaultMapLayers.push(mFeatureLayer);
        defaultMapLayers.push(markers);

        /*
         * Add local tile layer to the start of default map layers, making it the default basemap
         */
        //defaultMapLayers.unshift(localTiles);

        /*
         * Add default map layers to map
         */
        map.addLayers(defaultMapLayers);

        /*
         * Set Google Hybrid to default basemap if present in default map layers
         */
        for (var i = 0; i < map.layers.length; i++) {
            // console.log(map.layers[i].name); // Debug
            if (map.layers[i].name === 'Google Hybrid') {
                map.setBaseLayer(map.layers[i]);
            }
        }

        /*
         * Zoom and center the map on the predefined center lat long coordinates
         */
        if (!map.getCenter()) {
            var lonLat = new OpenLayers.LonLat(lon, lat).transform(p4326, p900913);
            map.setCenter(lonLat, zoom);
        }

        /*
         * Update the default zoom to so that it is always less than the max zoom level of the map
         */
        setDefaultZoomTo(map.baseLayer.getZoomForResolution(map.baseLayer.minResolution) - 3);
    });
}
