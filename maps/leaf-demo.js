
// See post: http://asmaloney.com/2015/06/code/clustering-markers-on-leaflet-mapsreplace('leaf-demo.js', '');

var myIcon = L.icon({
  iconUrl: 'maps/images/pin24.png',
  iconRetinaUrl: 'maps/images/pin48.png',
  iconSize: [29, 24],
  iconAnchor: [9, 21],
  popupAnchor: [0, -14],
});

var markerClusters = L.markerClusterGroup();

// Add all the data to the airport popups and the options tab
for (var i = 0; i < markers.length; ++i) {
  var popup =
    markers[i].name +
    '<br/>' +
    markers[i].city +
    '<br/><b>IATA/FAA:</b> ' +
    markers[i].iata_faa +
    '<br/><b>ICAO:</b> ' +
    markers[i].icao +
    '<br/><b>Altitude:</b> ' +
    Math.round(markers[i].alt * 0.3048) +
    ' m' +
    '<br/><b>Timezone:</b> ' +
    markers[i].tz +
    '<br/><b>Passengers:</b> ' +
    markers[i].airportpop.toLocaleString("en-UK") +
    '<br/><b>Population:</b> ' +
    markers[i].citypop.toLocaleString("en-UK") +
    '<hr /><a class="btn btn-info" onmouseover="createAirportWindowExcl(\'' + markers[i].icao + '\')" id="buttonExcl15">Options</a>';
    
    var m = L.marker([markers[i].lat, markers[i].lng], {
        icon: myIcon,
    }).bindPopup(popup);

    markerClusters.addLayer(m);
}

// Clusters are added to map outside of file


function getAirportInfoByICAO(icao) {
    for (let item of markers) {
        if (item['icao'] == icao) return item;
    }
    return null;
}

function customAirOpenModal(name, city, iata_faa, icao, alt, tz, lat, lng, airportpop, citypop) {
	document.getElementById("airport-modal-title").innerHTML = name + " - Options";
	
	document.getElementById("airport-modal-body").innerHTML = "" + 
	"<table border='0' class='table'>" +
	"<tr><td>" +
	"<b>City</b>" +
	"</td><td>" +
	city +
	"</td></tr>" +
	"<tr><td>" +
	"<b>IATA/FAA</b>" +
	"</td><td>" +
	iata_faa +
	"</td></tr>" +
	"<tr><td>" +
	"<b>ICAO</b>" +
	"</td><td>" +
	icao +
	"</td></tr>" +
	"<tr><td>" +
	"<b>Altitude</b>" +
	"</td><td>" +
	alt +
	"m</td></tr>" +
	"<tr><td>" +
	"<b>Timezone</b>" +
	"</td><td>" +
	tz +
	"</td></tr>" +
	"<tr><td>" +
	"<b>Latitude</b>" +
	"</td><td>" +
	lat +
	"</td></tr>" +
	"<tr><td>" +
	"<b>Longitude</b>" +
	"</td><td>" +
	lng +
	"</td></tr>" +
	
	
	"<tr><td>" +
	"<b>Passengers</b>" +
	"</td><td>" +
	airportpop.toLocaleString("en-UK") +
	"</td></tr>" +
	"<tr><td>" +
	"<b>Population</b>" +
	"</td><td>" +
	citypop.toLocaleString("en-UK") +
	"</td></tr>" +
	
	
	"</table>";
	
	if (localStorage.getItem("departure-airport-lat") === null) {
	    document.getElementById("airport-modal-depart").innerHTML = "Set Departure";
    	document.getElementById("airport-modal-depart").onclick = () => {
    	    localStorage.setItem("departure-airport-lat", lat);
    	    localStorage.setItem("departure-airport-lng", lng);
    	    document.getElementById("airport-modal-depart").onclick = null;
    	}
	} else {
	    document.getElementById("airport-modal-depart").innerHTML = "Set Arrival";
	    document.getElementById("airport-modal-depart").onclick = () => {
	        var lat2 = localStorage.getItem("departure-airport-lat");
	        var lng2 = localStorage.getItem("departure-airport-lng");
	        
	        var rangeOfAircraft = 0;
	        $.ajax({
                url: "getAircraftRangeFromDB.php",
                success: function(result){
                rangeOfAircraft = result;
            }});
	        
	        if (calcDistFromLatLong(lat, lng, lat2, lng2) <= rangeOfAircraft) temp = true;
	        L.polyline([[lat, lng], [lat2, lng2]], {"color": "blue"}).addTo(map);
	        document.getElementById("airport-modal-depart").onclick = null;
	        localStorage.removeItem("departure-airport-lat");
	        localStorage.removeItem("departure-airport-lng");
	    }
	}
}


function calcDistFromLatLong(lat1, lng1, lat2, lng2) {
    const R = 6371; //metres
    var radLat1 = lat1 * Math.PI/180;
    var radLat2 = lat2 * Math.PI/180;
    var deltaLat = (lat2 - lat1) * Math.PI/180;
    var deltaLon = (lng2 - lng1) * Math.PI/180;
    
    var a = Math.sin(deltaLat/2) * Math.sin(deltaLat/2) + 
            Math.cos(radLat1) * Math.cos(radLat2) *
            Math.sin(deltaLon/2) * Math.sin(deltaLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}
