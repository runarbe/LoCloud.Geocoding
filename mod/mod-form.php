<div id="gcFormContainer" class="rounded ui-state-highlight">
    <div id="gcForm">
        <form id="gc">
            <table id="gcFormTable" class="wide">
                <tr>
                    <td colspan="4" class="label">
                        Name of item
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <input id="tbItemName" name="gc_name" class="rounded wide resetable"/>
                    </td>
                </tr>
                <tr>
                    <td class="label">X-coordinate (or longitude)</td>
                    <td class="label">Y-coordinate (or latitude)</td>
                    <td colspan="2" class="align-right label">Probability (1=certain)</td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="tbLongitude" name="tbLongitude" class="rounded wide resetable"/>
                    </td>
                    <td>
                        <input type="text" id="tbLatitude" name="tbLatitude" class="rounded wide resetable"/>
                    </td>
                    <td colspan="2" class="align-right">
                        <div id="radio">
                            <input type="radio" id="radio1" name="rbProbability" value="1"/><label for="radio1">1</label>
                            <input type="radio" id="radio2" name="rbProbability" value="2"/><label for="radio2">2</label>
                            <input type="radio" id="radio3" name="rbProbability" value="3"/><label for="radio3">3</label>
                            <input type="radio" id="radio4" name="rbProbability" value="9999"/><label for="radio4">error</label>
                        </div>                
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="align-left">
                        <button id="btnViewAttributes">View attributes</button>
                        <button id="btnViewImage">View image</button>
                        <button id="btnViewUrl">View link</button>
                    </td>
                    <td class="align-right">
                        <button id="btnCancel">Cancel</button>
                        <button id="btnSaveGeocoding">Save</button>
                    </td>
                </tr>
            </table>
            <input type="hidden" id="hdnImage" name="ds_image" class="resetable" value=""/>
            <input type="hidden" id="hdnUrl" name="ds_url" class="resetable" value=""/>
            <input type="hidden" id="hdnTableId" name="id" class="resetable" value=""/>
            <input type="hidden" id="hdnFieldChanges" name="fieldChanges" class="resetable" value=""/>
            <input type="hidden" id="hdnTableName" name="table" class="resetable" value=""/>
        </form>
    </div>
</div>
