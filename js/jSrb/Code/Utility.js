/**
 * Return a rounded number
 * @param {Number} mDouble
 * @param {Number} mNumDigits
 * @returns {Number}
 */
function roundCoordinates(mDouble, mNumDigits) {
    var mFact = Math.pow(10, mNumDigits);
    var mPoint = (Math.round(mDouble * mFact)) / mFact;
    return mPoint;
}

/**
 * Output a log message
 * @param {String} pMessage
 * @param {Boolean} pError If false, outputs a highlighted message, otherwise an error
 * @returns {void}
 */
function logMessage(pMessage, pError) {
    pError = pError !== undefined ? pError : true;
    if (pError === false) {
        jQuery("#lblLog")
                .text(pMessage)
                .removeClass("ui-state-error")
                .addClass("ui-state-highlight");
    } else {
        jQuery("#lblLog")
                .text(pMessage)
                .removeClass("ui-state-highlight")
                .addClass("ui-state-error");
    }
    return;
}

/**
 * Checks if the value of an HTML element is numeric
 * @param {DOM} pElement jQuery element
 * @returns {Boolean} True if numeric, false if not
 */
function isEVNumeric(pElement) {
    return jQuery.isNumeric(pElement.val());
}

/**
 * Try to get the name of this function
 * @returns {String} Name of calling function
 */
function getThisFunctionName() {
    var mName = arguments.callee.toString();
    mName = mName.substr('function '.length);
    mName = mName.substr(0, mName.indexOf('('));
    return mName;
}

/**
 * Get a W2UI grid record
 * @param {String} pW2UIGridName
 * @param {Number} pRecID
 * @returns {Object} JSON object representing the data values of the W2UI record
 */
function getW2UIGridRecByID(pW2UIGridName, pRecID) {
    var mRecord = null;
    if (pW2UIGridName in w2ui) {
        var mRecords = w2ui[pW2UIGridName].records;
        for (var i = 0; i < mRecords.length; i++) {
            if (mRecords[i].recid.toString() === pRecID.toString()) {
                mRecord = mRecords[i];
                break;
            }
        }
    }
    return mRecord;
}

/**
 * Open a popup window loaded with the specified URL
 * @param {String} pUrl The URL to load
 * @returns {void}
 */
function openPopup(pUrl) {
    var left = (jQuery(window).width() / 2) - (900 / 2);
    var top = (jQuery(window).height() / 2) - (600 / 2);
    popup = window.open(pUrl, "popup", "width=900, height=600, top=" + top + ", left=" + left);
}

function setDefaultZoomTo(zoomLevel) {
    defaultZoomTo = zoomLevel;
    jQuery("#defaultZoomTo").slider("value", zoomLevel);
    return true;
}

function confidenceToProbability(pConfidence) {
    if (jQuery.isNumeric(pConfidence) && pConfidence >=0 && pConfidence <=100) {
        if (pConfidence > 90) {
            return 1
        } else if (pConfidence > 75) {
            return 2
        } else if (pConfidence > 20) {
            return 3
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function probabilityToConfidence(pProbability) {
    if (jQuery.isNumeric(pProbability) && pProbability >=0 && pProbability <=3) {
        if (pProbability === 1) {
            return 90
        } else if (pProbability === 2) {
            return 75
        } else if (pProbability === 3) {
            return 20
        } else {
            return 0;
        }
    } else {
        return 0;
    }    
}

function clearDatasourceFilters() {
    jQuery("#sbFilterAdm0").empty().val("");
    jQuery("#sbFilterAdm1").empty().val("");
    jQuery("#sbFilterProbability").val("");
    jQuery("#sbFilterCategory").empty().val("");
}

/**
 * Creates a set of html paragraphs from an array
 * 
 * @param {Array} pMessageArray
 * @returns {String}
 */
function getErrorMessages(pMessageArray) {
    var mHtml;
    for (var i = 0; i < pMessageArray.length; i++) {
        mHtml += "<p>" + pMessageArray[i] + "</p>";
    }
    return mHtml;
}

function showMsgBox(pMsg, pError) {
    var mCssClass = "ui-state-notification";
    var mContent = jQuery("<ul></ul>");
    var mTitle = "Notification!";
    if (pError == true) {
        mCssClass = "ui-state-highlight";
        mTitle = "Error!";
    }
    /*
     * Ig pMsg is a string, simply add the string to a list element
     */
    if (typeof pMsg == "string") {
        mContent.append("<li>" + pMsg + "</li>");
    } else {
        /*
         * Otherwise, iterate through the array, object and add each value to a list
         */
        jQuery.each(pMsg, function(key, val) {
            mContent.append("<li>" + key + ": " + val + "</li>");
        });
    }

    jQuery("#dialog").empty()
            .append("<p></p>")
            .addClass(mCssClass)
            .html(mContent).dialog({
        title: mTitle,
        modal: true,
        width: 480,
        height: 200,
        resizeable: true,
        minwidth: 480,
        maxWidth: 640,
        minHeight: 200,
        maxHeight: 480
    });
}