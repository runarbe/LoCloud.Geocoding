<div id="topBanner">
    <img src="images/locloud-logo-50px.png" align="left"/>
    <h1 style="line-height: 50px; text-align: right;"><?php echo LgmsConfig::app_title; ?></h1>    
</div>
<div id="topMenu" class="ui-widget-header ui-corner-all">
    <a id="btnHome" class="topMenuBtn nav" href=".">Home</a>
    <a id="btnHelp" class="topMenuBtn nav" href="#">Help</a>
    <a id="btnNewDatasource">New data source</a>
    <a id="btnDownloadGeoJSON" class="topMenuBtn download" href="#" title="Download as GeoJSON file. Can be used in various GIS applications and as a dataset in Web Map Solutions like Google Maps, Open Layers and Leaflet.">*.geojson</a>
    <!--a id="btnDownloadGeoJSONJavaScript" class="topMenuBtn download" href="#" title="Download as a Javascript file where a valid GeoJSON object is assigned to a variable.">*.js</a-->
    <a id="btnDownloadKML" class="topMenuBtn download" href="#" title="Download as Keyhole Markup Language (KML) format. Can be used in Google Earth, Google Maps etc.">*.kml</a>
    <!--a id="btnDownloadSQL" class="topMenuBtn download" href="#" title="Download as SQL update statements, can be used to load coordinates back into the source database.">*.sql</a-->
    <a id="btnAbout" class="topMenuBtn nav" href="#">About</a>
    <a id="btnUpgrade" class="topMenuBtn nav" href="#">Upgrade</a>
    <a id="btnLogout" class="topMenuBtn nav" href="./auth.php?action=logout">Logout</a>
</div>