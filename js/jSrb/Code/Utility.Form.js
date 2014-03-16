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