/**
 * Declar main namespace
 * @type Object
 */
var jSrb = jSrb || {};

/**
 * Enumeration for named modules
 * @class GcMods
 */
var GcMods = {
    mainWindow: "./index.php",
    manageDatasources: "./view.php?mod=manage-datasources&",
    manageUsers: "./view.php?mod=manage-users",
    registerUser: "./view.php?mod=register-user",
    manageGroups: "./view.php?mod=manage-groups",
    firstRun: ".firstrun.php",
    login: "./auth.php?action=login"
};

/**
 * Enumeration for named web services
 * @class WsUrl
 */
var WsUrl = {
    getDsAdm0Areas: "./ws/ws-adm0.php",
    getDsAdm1Areas: "./ws/ws-adm1.php",
    doLogin: "./ws/ws-auth.php",
    getDsCategories: "./ws/ws-cat.php",
    getLatestVersion: "./ws/ws-check-updates.php",
    createConfig: "./ws/ws-create-config.php",
    initDb: "./ws/ws-init-db.php",
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
    uploadTextFile: "./ws/ws-upload-textfile.php",
    manageUsr: "./ws/ws-grd-usr.php",
    manageDatasources: "./ws/ws-grd-datasources.php",
    manageDatasourcesACL: "./ws/ws-grd-datasourcesacl.php",
    manageAcl: "./ws/ws-acl.php"
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
    this.d = new Object();
    /**
     * An array of information or error message strings
     * 
     * @property Array m
     */
    this.m = new Array();
    return this;
}

/**
 * User levels
 * @class UsrLevel
 */
jSrb.UsrLevel = {
    /**
     * The super administrator can upgrade the application, change and delete all
     * data sources and change and delete all users
     * @type Number
     */
    "SuperAdmin": 1,
    
    /**
     * The can delete all data sources that he or she
     * has access to regardless 
     * @type Number
     */
    "Admin": 2,
    /**
     * The Editor can edit the access control list of data sources that he or she
     * has access to
     * @type Number
     */
    "Editor": 4,
    /**
     * The User is a regular 
     * @type Number
     */
    "User": 6,
    /**
     * This is a placeholder for anonymous users
     * @type Number
     */
    "GuestUser": 99
};

/**
 * Access control list levels
 * @class AccessLevels
 */
jSrb.AccessLevels = {
    "Owner": 10,
    "Editor": 20,
    "Contributor": 30
};

jSrb.ErrMsg =  {
    "selectDatabaseFirst": "You must select a database before you can do a search.",
    "noResults": "No results returned",
    "ajaxRequestError": "Error during Ajax request, check JavaScript console for details."
};