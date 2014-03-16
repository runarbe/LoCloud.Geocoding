function addExistingIcon(pLonLat) {
    if (currentMarker != null) {
        markers.removeMarker(currentMarker);
    }
    var size = new OpenLayers.Size(27,27);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('images/white01.png',size,offset);
    currentMarker = new OpenLayers.Marker(pLonLat,icon);
    markers.addMarker(currentMarker);    
}

function addProposedIcon(pLonLat) {
    if (proposedMarker != null) {
        markers.removeMarker(proposedMarker);
    }
    var size = new OpenLayers.Size(27,27);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('images/green01.png',size,offset);
    proposedMarker = new OpenLayers.Marker(pLonLat,icon);
    markers.addMarker(proposedMarker);
}

function addAlternateIcons(pLonLat) {
    var size = new OpenLayers.Size(27,27);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('images/yellow01.png',size,offset);
    var tmpMarker = new OpenLayers.Marker(pLonLat,icon);
    alternateMarkers.push(tmpMarker);
    markers.addMarker(tmpMarker);
}

function clearIcons() {
    if (currentMarker != null) {
        markers.removeMarker(currentMarker);
    }
    if (proposedMarker != null) {
        markers.removeMarker(proposedMarker);
    }    
}