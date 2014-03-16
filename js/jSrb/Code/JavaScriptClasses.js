/**
 * This is an enumeration class that contains one static entry for each of the
 * web services that are implemented in the application. These keys are used
 * by all the classes to make it easy to change the filename of a web service
 * without having to change all files where it is referenced. 
 * @namespace Enumeration of Web Service URLs
 */
var WsUrl = {
    /**
     * Web service to retrieve 1st level area division for a data source
     * @type String
     */
    getDsAdm0Areas: "./ws/ws-adm0.php",
    /**
     * Web service to retrieve 2nd level area division for a data source
     * @type String
     */
    getDsAdm1Areas: "./ws/ws-adm1.php",
    /**
     * Web service to perform authentication
     * @type String
     */
    doLogin: "./ws/ws-auth.php",
    /**
     * Web service to retrieve categories and sub-categories for a data source
     * @type String
     */
    getDsCategories: "./ws/ws-cat.php",
    /**
     * Web service to check if there are any updates available for the current
     * installed version of the application
     * 
     * @type String
     */
    getLatestVersion: "./ws/ws-check-updates.php",
    /**
     * Web service to generate a new configuration file at the first run of the
     * application after a fresh install.
     * 
     * @type String
     */
    createConfig: "./ws/ws-create-config.php",
    /**
     * Web service to handle uploads of tables from Microsoft Excel
     * 
     * @type String
     */
    handleExcelUpload: "./ws/ws-excel-upload.php",
    /**
     * Web service to retrieve geocoded results from the database as GeoJSON
     * this service is used to visualize the geocoded results in the map
     * 
     * @type String
     */
    getGeoJSON: "./ws/ws-geojson.php",
    /**
     * Web service to retrieve all available character set encodings
     * 
     * @type String
     */
    getCharacterEncodings: "./ws/ws-get-encodings.php",
    /**
     * Web service to retrieve all available data sources for the logged in user
     * 
     * @type String
     */
    getDataSources: "./ws/ws-load-datasources.php",
    /**
     * Web service to retrieve all available search databases for the logged in
     * user
     * 
     * @type String
     */
    getSearchDBs: "./ws/ws-load-search-dbs.php",
    /**
     * Web service to retrieve a set of items from the currently selected data
     * source
     * 
     * @type String
     */
    getItemsForDataSource: "./ws/ws-load-source-items3.php",
    /**
     * Web service to create a new data source
     * 
     * @type String
     */
    createDataSource: "./ws/ws-new-datasource.php",
    /**
     * Web service to create a new user
     * 
     * @type String
     */
    createUser: "./ws/ws-new-user.php",
    /**
     * Web service to parse an uploaded text file and insert it into MySQL
     * 
     * @type String
     */
    parseTextFile: "./ws/ws-parse-textfile.php",
    prepareTables: "./ws/ws-prepare-tables.php",
    sendPwdReminder: "./ws/ws-pwd-reminder.php",
    searchGeonames: "./ws/ws-search-geonames.php",
    updateAttributes: "./ws/ws-update-attrib.php",
    upgradeInstallation: "./ws/ws-upgrade.php",
    uploadTextFile: "./ws/ws-upload-textfile.php"
};

/**
 * This is an enumeration class that contains the corresponding values for
 * success and failure as returned by Web Services in the application.
 * @namespace Defines keys for success and failure
 */
var WsStatus = {
    /**
     * Success
     * @type {Number}
     * @static
     */
    success: 1,
    /**
     * Failure
     * @type {Number}
     * @static
     */
    failure: -1
};

/**
 * Object returned from Web Service Requests
 * @public
 * @class WsRetObj
 * @constructor
 */
function WsRetObj() {

    /**
     * Status message. WsStatus.success (1) on success, WsStatus.failure (-1)
     * on error
     * 
     * @public
     * @property Number v
     */
    this.v = WsStatus.success;

    /**
     * An object containing named or indexed properties specific to each web
     * service
     * 
     * @public
     * @property Object d
     */
    this.d = new Object()

    /**
     * An array of information or error message strings
     * 
     * @property Array m
     * @public
     */
    this.m = new Array();

    return this;
}