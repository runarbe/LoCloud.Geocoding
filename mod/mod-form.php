<div id="gcFormContainer" class="rounded ui-state-highlight">
    <div id="gcForm">
        <form id="gc" class="pure-form pure-form-stacked">
            <fieldset>
                <div class="pure-g">
                    <div class="pure-u-1 pure-u-md-3-5">
                        <label for="tbItemName">Item name</label>
                        <input id="tbItemName" name="gc_name" type="text" class="pure-input-1 resetable"/>
                    </div>
                    <div class="pure-u-1 pure-u-md-1-5">
                        <label>X|Longitude</label>
                        <input id="tbLongitude" name="tbLongitude" type="text" class="pure-input-1 resetable"/>
                    </div>
                    <div class="pure-u-1 pure-u-md-1-5">
                        <label>Y|Latitude</label>
                        <input id="tbLatitude" name="tbLatitude" type="text" class="pure-input-1 resetable"/>
                    </div>
                    <div class="pure-u-1 pure-u-md-2-5">
                        <label>Linked persistent URI</label>
                        <input id="tbLinkedPersistentURI" type="text" class="pure-input-1" readonly="true"/>
                    </div>
                    <div class="pure-u-1 pure-u-md-1-5">
                        <label>&nbsp;</label>
                        <button id="btnClearPersistentURI">Clear URI</button>
                    </div>
                    <div class="pure-u-1 pure-u-md-1-5">
                        <label>Map resolution</label>
                        <input id="tbMapResolution" type="text" class="pure-input-1" readonly="true"/>
                    </div>
                    <div class="pure-u-1 pure-u-md-1-5">
                        <label>Confidence (%)</label>
                        <input id="tbConfidence" type="number" min="0" max="100" step="10" value="100" class="pure-input-1"/>
                    </div>
                    <div class="pure-u-1 pure-u-md-2-3">
                        <label>More information</label>
                        <button id="btnViewAttributes">View info</button>
                        <button id="btnViewImage">View image</button>
                        <button id="btnViewUrl">View link</button>
                    </div>
                    <div class="pure-u-1 pure-u-md-1-3" style="text-align: right;">
                        <label>&nbsp;</label>
                        <button id="btnCancel">Cancel</button>
                        <button id="btnSaveGeocoding">Save</button>
                    </div>
                </div>
                <input type="hidden" id="hdnImage" name="ds_image" class="resetable" value=""/>
                <input type="hidden" id="hdnUrl" name="ds_url" class="resetable" value=""/>
                <input type="hidden" id="hdnTableId" name="id" class="resetable" value=""/>
                <input type="hidden" id="hdnAutoPkId" name="id" class="resetable" value=""/>
                <input type="hidden" id="hdnFieldChanges" name="fieldChanges" class="resetable" value=""/>
                <input type="hidden" id="hdnTableName" name="table" class="resetable" value=""/>
            </fieldset>
        </form>
    </div>
</div>
