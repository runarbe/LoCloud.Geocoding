/**
 * Function that is triggered when the new data source button is pressed. Allows
 * the user to upload a file, then parses the file, inserts the contents into the
 * database, populates the dropdowns with field names and advances to step 2 in the
 * wizard.
 * 
 * @returns void
 */
function handlerBtnNewDatasource() {

    var ctlBar = jQuery('.ctlBar');
    var ctlPercent = jQuery('.ctlPercent');
    var ctlStatus = jQuery('#ctlStatus');

    jQuery("#btnNdswNext3").click(function() {
        jQuery("#dlgNewDatasource").dialog("close");
    });

    SetNewDataSourceTab(0);
    // Get character encodigs
    jQuery.getJSON(WsUrl.getCharacterEncodings,
            {},
            /**
             * @param {WsRetObj} data
             * @ignore
             */
                    function(data) {
                        if (data.v === WsStatus.success) {
                            var ds_encoding = jQuery("select#ds_encoding");
                            jQuery.each(data.d, function(pKey, pVal) {
                                if (pVal === "ISO-8859-1") {
                                    ds_encoding.append(jQuery("<option/>").val(pVal).text(pVal).attr("selected", "selected"));
                                } else {
                                    ds_encoding.append(jQuery("<option/>").val(pVal).text(pVal))
                                }
                            });
                        }

                    }).fail(function() {
                showMsgBox("Error loading character encodings");
            });
            //jQuery("select#ds_encoding").val("ISO-8859-1");
            jQuery("select#ds_encoding option:contains('ISO-8859-1')").attr("selected", "selected");

            /*
             * Initialize the first step of the wizard
             */
            jQuery("#frmNewDataSourceWizardStep1").ajaxForm({
                dataType: "json",
                beforeSend: function() {
                    ctlStatus.empty();
                    ctlStatus.html("Please wait while uploading file...");
                    var percentVal = '0%';
                    ctlBar.width(percentVal)
                    ctlPercent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    ctlBar.width(percentVal)
                    ctlPercent.html(percentVal);
                },
                success: function(data) {
                    ctlStatus.html("<p>Successfully uploaded file: " + data.files[0].name + ".</p><p>Please wait while parsing the file and inserting into database. Once completed, the wizard will automatically proceed to the next step.</p>");
                    jQuery("#btnNdswNext1").addClass("pure-button-disabled");
                    var percentVal = '100%';
                    ctlBar.width(percentVal);
                    ctlPercent.html(percentVal);
                    var mSepChar = jQuery("select#ds_sepchar option:selected").val();
                    var mCharEncoding = jQuery("select#ds_encoding option:selected").val();
                    var mFnFirstRow = jQuery("input#ds_fnfirstrow").prop("checked");
                    jQuery.getJSON(WsUrl.parseTextFile,
                            {
                                fn: data.files[0].name,
                                delimiter: mSepChar,
                                encoding: mCharEncoding,
                                fnfirstrow: mFnFirstRow
                            },
                    /**
                     * @param {WsRetObj} data
                     * @ignore
                     */
                    function(data) {
                        if (data.v === WsStatus.success) {
                            /*
                             * Select all dropdowns to be populated with file names from the
                             * selected table and clear any current options.
                             */
                            var mFNDropDowns = jQuery("select.FND");
                            mFNDropDowns.empty();
                            /*
                             * Add blank option to the top of the dropdowns
                             */
                            mFNDropDowns.append(jQuery("<option/>").attr({"value": ""}).text("<not applicable>"));
                            mFNDropDowns.append(jQuery("<option/>").attr({"value": "autopk_id"}).text("<system generated number>"));
                            jQuery.each(data.d.fields, function(pIdx, pVal) {
                                mOpt = jQuery("<option/>").attr({"value": pVal}).text(pVal);
                                mFNDropDowns.append(mOpt);
                            });
                            jQuery("#ds_table").attr({"value": data.d.table});
                            SetNewDataSourceTab(1);
                        } else {
                            showMsgBox(data.m, true);
                        }
                    }).fail(function() {
                        showMsgBox("Could not parse text file");
                    });
                }

            }); // End Step 1

            /*
             * Initialize the second step of the wizard 
             */
            jQuery("#frmNewDataSourceWizardStep2").ajaxForm({
                dataType: "json",
                /**
                 * @param {WsRetObj} data
                 * @ignore
                 */
                success: function(data) {
                    if (data.v === WsStatus.success) {

// Call prepare tables web service
                        jQuery.getJSON(WsUrl.prepareTables,
                                {
                                    t: data.d.table
                                },
                        /**
                         * @param {WsRetObj} data
                         * @ignore
                         */
                        function(data) {

                            if (data.v === WsStatus.success) {

                                SetNewDataSourceTab(2);
                                loadDatasources(null);
                            } else {
                                showMsgBox(data.m, true);
                            }

                        }).fail(function() {
                            showMsgBox("Error preparing tables");
                        });
                    } else {
// Show error message
                        showMsgBox(data.m, true);
                    }
                }

            }); // End step 2

            /*
             * Open and style the the actual dialog
             */
            jQuery("#dlgNewDatasource").dialog(
                    {
                        hide: "fade",
                        show: "fade",
                        closeOnEscape: true,
                        modal: true,
                        height: 480,
                        width: 640
                    }
            );
        }

/**
 * Insert new data source
 * @returns void
 */
function handlerBtnNewDatasourceOk() {
    alert("Not yet implemented");
}