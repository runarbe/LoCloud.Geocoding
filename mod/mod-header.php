<div id="topBanner">
    <img src="images/locloud-logo-50px.png" align="left"/>
    <h1 style="line-height: 50px; text-align: right;"><?php echo LgmsConfig::app_title; ?></h1>    
</div>
<div id="topMenu">
    <ul class="menu">
        <li><a id="btnHome" class="topMenuBtn nav" href=".">Home</a></li>
        <li><a id="btnHelp" class="topMenuBtn nav" href="#">Help</a></li>

        <li><a id="btnNewDatasource" class="topMenuBtn nav" href="#">New data source</a></li>
        <li><a href="#">Download</a>
            <ul>
                <li><a id="btnDownloadGeoJSON" href="#" title="Download as GeoJSON file. Can be used in various GIS applications and as a dataset in Web Map Solutions like Google Maps, Open Layers and Leaflet.">*.geojson</a></li>
                <li><a id="btnDownloadGeoJSONJavaScript" href="#" title="Download as a Javascript file where a valid GeoJSON object is assigned to a variable.">*.js</a></li>
                <li><a id="btnDownloadKML" class="topMenuBtn download" href="#" title="Download as Keyhole Markup Language (KML) format. Can be used in Google Earth, Google Maps etc.">*.kml</a></li>
                <li><a id="btnDownloadSQL" class="topMenuBtn download" href="#" title="Download as SQL update statements, can be used to load coordinates back into the source database.">*.sql</a></li>
            </ul>
        </li>
        <li><a id="btnAbout" href="#">About</a></li>
        <li><a href="#">Admin</a>
            <ul>
                <li><a id="btnUpgrade" href="#">Upgrade</a></li>
                <li><a id="btnLogout" href="./auth.php?action=logout">Logout</a></li>
            </ul>
        </li>
    </ul>

</div>