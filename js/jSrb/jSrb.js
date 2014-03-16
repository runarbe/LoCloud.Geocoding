/**
 * The name of the root script
 * @type String
 */
var scriptName = "jSrb.js";

/**
 * @namespace This is the toplevel namespace for the LoCloud geocoding micro
 * service
 */
var jSrb = {
    /**
     * Internal function to get the location of the currently executed
     * script
     */
    _getScriptLocation: function() {
        var r = new RegExp("(^|(.*?\\/))(" + scriptName + ")(\\?|$)"),
                s = document.getElementsByTagName('script'),
                src, m, l = "";
        for (var i = 0, len = s.length; i < len; i++) {
            src = s[i].getAttribute('src');
            if (src) {
                m = src.match(r);
                if (m) {
                    l = m[1];
                    break;
                }
            }
        }
        return l;
    },
    /**
     * @namespace This namespace provides functions for initializing the
     * application, laying out elements and attaching handlers to intereactive
     * controls
     */
    AppSetup: {},
    /**
     * @namespace Utility functions
     */
    Utilities: {},
    /**
     * @namespace Interactive elements handler functions
     */
    Handlers: {},
    /**
     * @namespace Map functions
     */
    Map: {},
    /**
     * @namespace Configuration values
     */
    Configuration: {}
};

/**
 * Array of JavaScript files to be included into the main script
 * @type Array
 */
jSrb.jsFiles = [
    "Code/JavaScriptClasses.js",
    "Code/Utility.js",
    "Code/Utility.Map.js",
    "Code/Utility.Source.js",
    "Code/Utility.Form.js",
    "Code/Utility.NewDatasource.js",
    "Code/ApplicationSetup.js",
    "Code/CustomControls.js",
    "Code/ClickHandlers.Source.js",
    "Code/ClickHandlers.Search.js",
    "Code/ClickHandlers.Form.js",
    "Code/ClickHandlers.Header.js",
    "Code/ClickHandlers.NewDatasource.js",
    "Code/MapLayers.js",
    "Code/Main.js" //Always at end
];

jSrb.scriptTags = new Array(jSrb.jsFiles.length);
jSrb.host = jSrb._getScriptLocation();
for (var i = 0, len = jSrb.jsFiles.length; i < len; i++) {
    jSrb.scriptTags[i] = "<script src='" + jSrb.host + jSrb.jsFiles[i] +
            "'></script>";
}
if (jSrb.scriptTags.length > 0) {
    document.write(jSrb.scriptTags.join(""));
}