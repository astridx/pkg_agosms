document.addEventListener('DOMContentLoaded', function () {
	var leafletmaps = document.querySelectorAll('.agosmsaddressmarkerleafletmap');

	// For all maps [start]
	[].forEach.call(leafletmaps, function (element) {

		var unique = element.getAttribute('data-unique');
		var lat = element.getAttribute('data-lat');
		var lon = element.getAttribute('data-lon');
		var mapboxkey = element.getAttribute('data-mapboxkey');
		console.log(mapboxkey);

		map = new L.Map('map' + unique);
		var osmUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=' + mapboxkey;
		var osmAttrib = 'Map data &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors, ' +
						'<a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, ' +
						'Imagery © <a href=\"http://mapbox.com\">Mapbox</a>';
		var osm = new L.TileLayer(osmUrl, {minZoom: 8, maxZoom: 12, attribution: osmAttrib, id: 'mapbox.streets'});
		map.addLayer(osm);	// For all maps [end]

		try {
			map.setView(new L.LatLng(lat, lon), 13);
			var marker = L.marker([lat, lon]).addTo(map);
		} catch (e) {
			map.setView(new L.LatLng(0, 0), 13);
			var marker = L.marker([0, 0]).addTo(map);
			console.log(e);
		}
	});

}, false);