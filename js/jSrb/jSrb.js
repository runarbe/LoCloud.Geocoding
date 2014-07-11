(function () {

    var scriptName = "jSrb.js";
        
    window.jSrb = {
        _getScriptLocation: (function() {
            var r = new RegExp("(^|(.*?\\/))(" + scriptName + ")(\\?|$)"),
            s = document.getElementsByTagName('script'),
            src, m, l = "";
            for(var i=0, len=s.length; i<len; i++) {
                src = s[i].getAttribute('src');
                if(src) {
                    m = src.match(r);
                    if(m) {
                        l = m[1];
                        break;
                    }
                }
            }
            return (function() {
                return l;
            });
        })()
    };

    var jsFiles = [
        "Code/JavaScriptClasses.js", 
        "Code/Utility.js", 
        "Code/Utility.Map.js", 
        "Code/Utility.Source.js", 
        "Code/Utility.Form.js", 
        "Code/Utility.NewDatasource.js", 
        "Code/Utility.Manager.js", 
        "Code/ApplicationSetup.js", 
        "Code/CustomControls.js", 
        "Code/ClickHandlers.Source.js",
        "Code/ClickHandlers.Search.js",
        "Code/ClickHandlers.Form.js",
        "Code/ClickHandlers.Header.js",
        "Code/ClickHandlers.NewDatasource.js",
        "Code/ClickHandlers.Manager.js",
        "Code/MapLayers.js", 
        "Code/Main.js" //Always at end
    ];

    var scriptTags = new Array(jsFiles.length);
    var host = jSrb._getScriptLocation();
    for (var i=0, len=jsFiles.length; i<len; i++) {
        scriptTags[i] = "<script src='" + host + jsFiles[i] +
        "'></script>"; 
    }
    if (scriptTags.length > 0) {
        document.write(scriptTags.join(""));
    }
})();