/**
 * Enumeration for named web services
 * @class Ws
 */
var WsUrl = {
    getDsAdm0Areas: "./ws/ws-adm0.php",
    getDsAdm1Areas: "./ws/ws-adm1.php",
    doLogin: "./ws/ws-auth.php",
    getDsCategories: "./ws/ws-cat.php",
    getLatestVersion: "./ws/ws-check-updates.php",
    createConfig: "./ws/ws-create-config.php",
    handleExcelUpload: "./ws/ws-excel-upload.php",
    getGeoJSON: "./ws/ws-geojson.php",
    getCharacterEncodings: "./ws/ws-get-encodings.php",
    getDataSources: "./ws/ws-load-datasources.php",
    getSearchDBs: "./ws/ws-load-search-dbs.php",
    getItemsForDataSource: "./ws/ws-load-source-items3.php",
    createDataSource: "./ws/ws-new-datasource.php",
    createUser: "./ws/ws-new-user.php",
    parseTextFile: "./ws/ws-parse-textfile.php",
    prepareTables: "./ws/ws-prepare-tables.php",
    sendPwdReminder: "./ws/ws-pwd-reminder.php",
    searchGeonames: "./ws/ws-search-geonames.php",
    updateAttributes: "./ws/ws-update-attrib.php",
    upgradeInstallation: "./ws/ws-upgrade.php",
    uploadTextFile: "./ws/ws-upload-textfile.php"
};

/**
 * Enumeration for different web service status codes
 * @class WsStatus
 */
var WsStatus = {
    /**
     * Success
     * @type {Number}
     */
    success: 1,
    /**
     * Failure
     * @type {Number}
     */
    failure: -1
};

/**
 * Object returned from Web Service Requests
 * @class WsRetObj
 */
function WsRetObj() {

    /**
     * Status message. WsStatus.success (1) on success, WsStatus.failure (-1)
     * on error
     * 
     * @property Number v
     */
    this.v = WsStatus.success;

    /**
     * An object containing named or indexed properties specific to each web
     * service
     * 
     * @property {Object} d
     */
    this.d = new Object()

    /**
     * An array of information or error message strings
     * 
     * @property Array m
     */
    this.m = new Array();

    return this;
}