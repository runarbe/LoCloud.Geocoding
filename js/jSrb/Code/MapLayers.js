function initMapLayers() {

    if (useLocalTiles == true) {

        /*
         * Utility function to get tiles
         * @param <BBoxObject> bounds The area that you'd like to return tiles for...
         * @returns <URL> the URL to the image to be loaded '
         * See: http://www.maptiler.org/google-maps-coordinates-tile-bounds-projection
         */
        function mbtilesURL(bounds) {
            var db = "OSMBright.mbtiles";
            var res = this.map.getResolution();
            var x = Math.round((bounds.left - this.maxExtent.left) / (res * this.tileSize.w));
            var y = Math.round((this.maxExtent.top - bounds.top) / (res * this.tileSize.h));
            var z = this.map.getZoom();
            // Deal with Bing layers zoom difference...
            if (this.map.baseLayer.CLASS_NAME == 'OpenLayers.Layer.VirtualEarth' || this.map.baseLayer.CLASS_NAME == 'OpenLayers.Layer.Bing') {
                z = z + 1;
            }
            return this.url + "?db=" + db + "&z=" + z + "&x=" + x + "&y=" + ((1 << z) - y - 1);
        }

        /*
         * Local tile layer from MBTiles sqlite database
         * Created using TileMill and free data
         */
        var localTileLayer = new OpenLayers.Layer.TMS("Musandam tiles (local)", "tiles/mbtiles.php", {
            getURL: mbtilesURL,
            numZoomLevels: 13,
            isBaseLayer: true
                    //displayOutsideMaxExtent: false,
                    //maxExtent: new OpenLayers.Bounds(55.0415,24.0365,56.8872,26.5394).transform(wgs84proj,gproj),
                    //opacity: 0.7
        });

        defaultMapLayers.push(localTileLayer);

    }

    /*
     * Google layers
     */
    if (typeof google !== 'undefined' && useGoogleMaps == true) {
        var gphy = new OpenLayers.Layer.Google(
                "Google Physical",
                {
                    type: google.maps.MapTypeId.TERRAIN,
                    numZoomLevels: 17
                }
        );
        defaultMapLayers.push(gphy);

        var gmap = new OpenLayers.Layer.Google(
                "Google Streets", // the default
                {
                    numZoomLevels: 20
                }
        );
        defaultMapLayers.push(gmap);

        var ghyb = new OpenLayers.Layer.Google(
                "Google Hybrid",
                {
                    type: google.maps.MapTypeId.HYBRID,
                    numZoomLevels: 20
                }
        );
        defaultMapLayers.push(ghyb);
        var gsat = new OpenLayers.Layer.Google(
                "Google Satellite",
                {
                    type: google.maps.MapTypeId.SATELLITE,
                    numZoomLevels: 22
                }
        );
        //defaultMapLayers.push(gsat);
    }

    /*
     * Bing Layers
     */
    if (useBingMaps == true) {
        var broad = new OpenLayers.Layer.Bing({
            name: "Bing Roads",
            key: keyBingMapsApi,
            type: "Road"
        });
        //defaultMapLayers.push(broad);

        var bhybrid = new OpenLayers.Layer.Bing({
            name: "Bing Hybrid",
            key: keyBingMapsApi,
            type: "AerialWithLabels"
        });
        //defaultMapLayers.push(bhybrid);

        var baerial = new OpenLayers.Layer.Bing({
            name: "Bing Aerial",
            key: keyBingMapsApi,
            type: "Aerial"
        });
        defaultMapLayers.push(baerial);

    }

    /*
     * Kartverket layers
     */
    //Statens Kartverk layers
    var skTopo2 = new OpenLayers.Layer.WMS(
            "Topografisk norgeskart2", "http://opencache.statkart.no/gatekeeper/gk/gk.open?",
            {
                layers: 'topo2',
                format: 'image/jpeg'
            }, {
        attribution: ''
    });
    defaultMapLayers.push(skTopo2);

    /*
     * Other basemaps
     */
    var wmsVmap0 = new OpenLayers.Layer.WMS
            (
                    "Vmap 0 base map (WMS)",
                    "http://vmap0.tiles.osgeo.org/wms/vmap0",
                    {
                        layers: 'basic'
                    }
            );
    //defaultMapLayers.push(wmsVmap0);       

}
    