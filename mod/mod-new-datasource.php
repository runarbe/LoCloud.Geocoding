<div id="dlgNewDatasource" title="New datasource" class="hidden">
    <div class="tabs wide" style="height: 95%;">
        <ul>
            <li><a href="#tabs-1">Select a file to upload</a></li>
            <li><a href="#tabs-2">Add metadata</a></li>
            <li><a href="#tabs-3">Finish</a></li>
        </ul>            
        <div id="tabs-1">
            <form id="frmNewDataSourceWizardStep1" class="pure-form pure-form-aligned" action="ws/ws-upload-textfile.php" method="post">
                <p class="pure-form-message">You can upload any CSV text files including files created in Microsoft Excel by choosing "File-->Save as..." and then select one of the formats starting with "CSV..." in the dropdown box "Save as type".</p>
                <fieldset>

                    <div class="pure-control-group">
                        <label for="ds_sepchar">Delimiter character</label>
                        <select class="pure-input-2-3" title="Please select the delimiter character used in the text file" id="ds_sepchar" name="ds_sepchar">
                            <option value="comma">Comma</option>
                            <option value="semicolon">Semicolon</option>
                            <option value="tab">Tabulator</option>
                        </select>
                    </div>

                    <div class="pure-control-group">
                        <label for="ds_encoding">Character encoding</label>
                        <select class="pure-input-2-3" title="Please select the character encoding of the uploaded file" id="ds_encoding" name="ds_encoding">
                        </select>
                    </div>


                    <div class="pure-controls">
                        <label id="lbl_ds_file" for="ds_file" class="pure-button">Select file to upload...</label>
                        <input id="ds_file" style="display:none;" type="file" name="files[]"/>
                        <label for="ds_fnfirstrow" class="pure-radio" >
                            <input type="checkbox" id="ds_fnfirstrow" name="ds_fnfirstrow" checked="checked"/> Field names in first row
                        </label>

                        <p id="ctlStatus" class="pure-form-message"></p>
                        <div class="ctlProgress">
                            <div class="ctlBar"></div >
                            <div class="ctlPercent">0%</div >
                        </div>

                        <button id="btnNdzwNext1" type="submit" class="pure-button pure-button-primary">Next</button>
                    </div>
                </fieldset>
            </form>
        </div>
        <div id="tabs-2">
            <form id="frmNewDataSourceWizardStep2" class="pure-form pure-form-aligned" action="ws/ws-new-datasource.php" method="post">
                <fieldset>
                    <div class="pure-control-group">                    
                        <label for="ds_title">Data source name</label>
                        <input title="Please enter the name of the data source" id="ds_title" name="ds_title" value="" placeholder="Enter name of dataset" class="pure-input-2-3" required/>
                    </div>

                    <div class="pure-control-group">                    
                        <label for="ds_col_pk">Unique identifier</label>
                        <select title="The field that contains the unique identifier" class="pure-input-2-3 FND" id="ds_col_pk" name="ds_col_pk" required></select>
                    </div>

                    <div class="pure-control-group">                    
                        <label for="ds_col_name">Display field</label>
                        <select title="The field that contains a name or title" class="pure-input-2-3 FND" id="ds_col_name" name="ds_col_name" required></select>
                    </div>

                    <div class="pure-control-group">                    
                        <label for="ds_srs">Spatial reference system</label>
                        <select title="Must be the same as existing coordinates if present in the uploaded table - otherwise points will not display in the correct location." class="pure-input-2-3" id="ds_srs" name="ds_srs" required>
                            <option value="4326">LatLong, WGS 84</option>
                            <option value="32632">UTM 32N, WGS 84</option>
                            <option value="32633">UTM 33N, WGS 84</option>
                            <option value="32634">UTM 34N, WGS 84</option>
                            <option value="32640">UTM 40N, WGS 84</option>
                            <option value="900913">Google Projection</option>
                        </select>
                    </div>
                    <div class="pure-control-group">                    
                        <label for="ds_coord_prec">Coordinate precision <span class="required">*</span></label>
                        <select title="For metric coordinate systems 0-2 decimals are suitable. For long/lat coordinate systems 5-6 decimals are suitable. The precision you will get from screen digitizing is anyway limited by the resolution of the base maps. For sources like Google Maps, the radial distortion accounts for meters of positional inaccuracy." class="pure-input-2-3" id="ds_coord_prec" name="ds_coord_prec" required>
                            <option>6</option>
                            <option>5</option>
                            <option>4</option>
                            <option>3</option>
                            <option>2</option>
                            <option>1</option>
                            <option>0</option>
                        </select>
                    </div>
                </fieldset>
                <fieldset>
                    <div class="pure-control-group">                    
                        <label for="ds_col_x">X-coordinate</label>
                        <select title="The field that contains existing X-coordinate" class="pure-input-2-3 FND" id="ds_col_x" name="ds_col_x"></select>                        
                    </div>

                    <div class="pure-control-group">                    
                        <label for="ds_col_y">Y-coordinate</label>
                        <select title="The field that contains existing Y-coordinate" class="pure-input-2-3 FND" id="ds_col_y" name="ds_col_y"></select>
                    </div>

                    <div class="pure-control-group">                    
                        <label for="ds_col_cat">Category</label>
                        <select title="e.g. a column containing the type of item described by the record, i.e. photo, video, monument, painting etc." class="pure-input-2-3 FND" id="ds_col_cat" name="ds_col_cat"></select>
                    </div>

                    <div class="pure-control-group">                    
                        <label for="ds_col_adm0">Area division level 1</label>
                        <select title="e.g. province, county" class="pure-input-2-3 FND" id="ds_col_adm0" name="ds_col_adm0"></select>
                    </div>

                    <div class="pure-control-group">                    
                        <label for="ds_col_adm1">Area division level 2</label>
                        <select title="e.g. municipality, commune" class="pure-input-2-3 FND" id="ds_col_adm1" name="ds_col_adm1"></select>
                    </div>

                    <div class="pure-control-group">                    
                        <label for="ds_col_url">Web link field</label>
                        <select title="http://.../file.html" class="pure-input-2-3 FND" id="ds_col_url" name="ds_col_url"></select>
                    </div>

                    <div class="pure-control-group">                    
                        <label for="ds_col_image">Image link field</label>
                        <select title="http://.../image.png" class="pure-input-2-3 FND" id="ds_col_image" name="ds_col_image"></select>
                    </div>
                    <input type="hidden" id="ds_table" name="ds_table" value=""/>
                    <input type="submit" id="btnNdswNext2" class="pure-button pure-button-primary" value="Next" title="Save changes, parse text file to database and complete wizard."/>
                </fieldset>
            </form>
        </div>
        <div id="tabs-3">
            <h2>Success</h2>
            <p>You have successfully completed the wizard and added a new dataset to your geocoding application</p>
            <p>Please close this dialog and refresh your browser to start working with the new data source.</p>
            <p><button class="pure-button pure-button-primary" title="Finish and close" id="btnNdswNext3">Finish</button></p>
        </div>
    </div> <!-- div.tabs -->
</div>