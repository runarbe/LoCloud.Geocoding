<div>
    <div class="collapsible">
        <h3>Search</h3>
        <div class="sh">
            <p>Please select a database and search.</p>
            <form id="frmSelectSearchDB">
                <select id="sbSelectSearchDB" class="rounded wide" name="sbAdm0">
                    <option value="">Please select a database</option>
                </select>
            </form>
            <form id="frmSearch">
                <input id="tbSearchTerm" name="q" type="text" class="rounded wide"/>
                <input id="cbLimitToBbox" type="checkbox" checked="true"/><label class="label" for="cbLimitToBbox">within map</label>
                <button id="btnSearch" class="float-right">Search</button>
            </form>
        </div>
        <h3>Useful location sources</h3>
        <div class="sh">
            <div>
                <button id="btnOpenWikimapia" class="wide">Open Wikimapia</button>
                <button id="btnOpenGeonames" class="wide">Open Geonames</button>
                <button id="btnOpenGoogleMapmaker" class="wide">Open Google Mapmaker</button>
                <button id="btnOpenBookingCom" class="wide">Open Booking.com</button>
                <button id="btnOpenPanoramio" class="wide">Open Panoramio</button>
                <button id="btnOpenNokiaHere" class="wide">Open Here</button>
                <button id="btnSearchGoogle" class="wide">Search Google</button>
            </div>
        </div>
    </div>
    <h3>Select a search result</h3>
    <div>
        <ol id="listSearchResults">
        </ol>
        <p id="txtSearchHint"></p>
    </div>
</div>
