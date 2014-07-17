<div id="gc-mod" title="Manage Data Sources">
    <div>
        <div id="mShareToolbar"></div>
    </div>
    <div id="dlgManager" class="pure-g">
        <div class="pure-u-1">
            <p>1. Select a data source</p>
            <div id="grdDatasources" style="height:150px;"></div>
        </div>
        <div class="pure-u-1-2">
            <p>2a) Edit data source (owners only)</p>
            <form id="meta_datasources" class="pure-form pure-form-aligned">
                <fieldset>
                    <div class="pure-control-group">
                        <label>Title</label>
                        <input type="text" id="ds_title" class="pure-input-1-2" placeholder="Data source name" required/>
                    </div>
                    <div class="pure-control-group">
                        <label>Primary key col.</label>
                        <select id="ds_col_pk" class="field-list pure-input-1-2"></select>
                    </div>
                    <div class="pure-control-group">
                        <label>Persist URI col.</label>
                        <select id="ds_col_puri" class="field-list pure-input-1-2"></select>
                    </div>
                    <div class="pure-control-group">
                        <label>Name column</label>
                        <select id="ds_col_name" class="field-list pure-input-1-2"></select>
                    </div>
                    <div class="pure-control-group">
                        <label>Image column</label>
                        <select id="ds_col_image" class="field-list pure-input-1-2"></select>
                    </div>
                    <div class="pure-control-group">
                        <label>URL column</label>
                        <select id="ds_col_url" class="field-list pure-input-1-2"></select>
                    </div>
                    <div class="pure-control-group">
                        <label>X/Longitude</label>
                        <select id="ds_col_x" class="field-list pure-input-1-2"></select>
                    </div>
                    <div class="pure-control-group">
                        <label>Y/Latitude</label>
                        <select id="ds_col_y" class="field-list pure-input-1-2"></select>
                    </div>
                    <div class="pure-controls">
                        <button id="btnDatasourceUpdate" class="pure-button pure-input-2-3 pure-button-primary pure-button-disabled">Update data source</button>
                    </div>
                    <div class="pure-controls">
                        <button id="btnDatasourceDelete" class="pure-button pure-input-2-3 pure-button-disabled">Delete data source</button>
                    </div>
                </fieldset>
                <input type="hidden" id="hdDatasourceAction"/>
                <input type="hidden" id="hdSelectedDatasourceID"/>            
            </form>                
        </div>
        <div class="pure-u-1-2">
            <p>2b) Edit access control (owners, editors)</p>
            <form id="frmDatasourcesAcl" class="pure-form pure-form-aligned">            
                <fieldset>
                    <div class="pure-control-group">
                        <label>Access level</label>
                        <select id="selAccess" class="pure-input-1-2">
                            <option value="20" selected="selected">Editor</option>
                            <option value="30">Contributor</option>                        
                        </select>
                    </div>
                    <div class="pure-control-group">
                        <label>User</label>
                        <input id="tbAddUsers" type="text" placeholder="Type a couple of letters of the user name..." class="pure-input-1-2"></input>
                    </div>
                    <div class="pure-controls">
                        <button id="btnDatasourcesAclAdd" class="pure-button pure-input-2-3 pure-button-primary pure-button-disabled">Add to access control list</button>
                    </div>
                    <br/>
                    <div class="pure-control-group">
                        <div id="grdDatasourcesAcl" style="height:150px;"></div>
                    </div>
                    <div class="pure-control-group">
                        <button id="btnDatasourcesAclDelete" class="pure-button pure-input-1 pure-button-primary pure-button-disabled">Remove selected user from access control list</button>
                    </div>
                </fieldset>
                <input type="hidden" id="hdSelectedDatasourcesAclEntryID"/>
                <input type="hidden" id="hdSelectedUsrID"/>
            </form>
        </div>
        <div class="pure-u-1">
            <div id="lblLog"></div>
        </div>
    </div>
</div>