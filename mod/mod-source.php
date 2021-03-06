<div id="dsOptions" class="collapsible">
    <h3>Data source</h3>
    <div class="sh">
        <form id="frmSelectDatasource">
            <p>Please select a data source that you would like to geocode.</p>
            <select id="sbDatasource" name="sbDatasource" class="rounded wide">
                <option value="">Please select a source</option>
            </select>
        </form>
        <?php if (isLoggedIn(UserLevels::Editor)) { ?>
            <a id="btnNewDatasource" class="wide">Add new data source</a>        
            <a id="btnManageDatasources" class="wide">Manage data sources</a>
        <?php } ?>
    </div>
    <h3>Map settings</h3>
    <div class="sh">
        <div>
            <p>Please select default zoom</p>
            <div id="defaultZoomTo"></div>
        </div>
    </div>
    <h3>Filter items</h3>
    <div class="sh">
        <form id="frmFilterByArea" class="hidden">
            <p>Filter by area</p>
            <select id="sbFilterAdm0" name="sbFilterAdm0" class="rounded wide hidden">
                <option value="">all areas</option>
            </select>
            <select id="sbFilterAdm1" name="sbFilterAdm1" class="rounded wide hidden">
                <option value="">all sub-areas</option>
            </select>
        </form>
        <form id="frmFilterByProbability">
            <p>Filter by probability</P>
            <select id="sbFilterProbability" name="sbFilterProbability" class="rounded wide">
                <option value="">all probabilities</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="9999">errors</option>
            </select>
        </form>
        <form id="frmFilterByCategory" class="hidden">
            <p>Filter by category</P>
            <select id="sbFilterCategory" name="sbFilterCategory" class="rounded wide">
                <option value="">all categories</option>
            </select>
        </form>
    </div>
</div>
<p>Select an item</p>
<div>
    <ol id="selectable"></ol>
    <button id="btnPrevSrc" class="align-right">Previous</button>
    <button id="btnNextSrc" class="align-right">Next</button>  
</div>
