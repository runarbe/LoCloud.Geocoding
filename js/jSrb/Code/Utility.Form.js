function resetGeocodingForm() {
    jQuery("input.resetable, select.resetable", "form#gc").val("");
    return true;
}

function getFieldChanges() {
    var mFieldChanges = jQuery("#gc input#hdnFieldChanges").val();
    if (mFieldChanges != '') {
        return JSON.parse(jQuery("#gc input#hdnFieldChanges").val());
    } else {
        return JSON.parse('{}');
    }
}

function getFieldChangesAsText() {
    return jQuery("#gc input#hdnFieldChanges").val();
}

function setFieldChanges(pChangedFields) {
    jQuery("#gc input#hdnFieldChanges").val(pChangedFields);
    return true;
}

function hideGeocodingForm() {
    jQuery("#gcFormContainer").css("display", "none");
}


function showGeocodingForm() {
    jQuery("#gcFormContainer").css("display", "block");
}

/**
 * Open a popup window and show a URL
 * 
 * @param {type} pImageUrl
 * @returns {undefined}
 */
function showImagePopup(pImageUrl) {
    var mImgHtml = "<img src=\"" + pImageUrl + "\"/>";
    jQuery("#dlgHelp").empty().html(mImgHtml);
    jQuery("#dlgHelp").dialog(
            {
                hide: "fade",
                show: "fade",
                title: jsConf.app_title + " - Image Viewer",
                closeOnEscape: true,
                modal: true,
                height: 480,
                width: 640
            });
}