document.addEventListener('DOMContentLoaded', function () {
	var leafletmaps = document.querySelectorAll('.agosmsaddressmarkerleafletmap');

	// For all maps [start]
	[].forEach.call(leafletmaps, function (element) {

		var addprivacybox = element.getAttribute('data-addprivacybox');
		if (addprivacybox === '1')
		{
			return;
		}
		var uriroot = element.getAttribute('data-uriroot');
		var unique = element.getAttribute('data-unique');
		var maptype = element.getAttribute('data-maptype');
		var lat = element.getAttribute('data-lat');
		var lon = element.getAttribute('data-lon');
		var scrollwheelzoom = element.getAttribute('data-scrollwheelzoom');
		var owngooglegesturetext = element.getAttribute('data-owngooglegesturetext');
		var touch = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_TOUCH');
		var scroll = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLL');
		var scrollmac = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLLMAC');
		var specialicon = element.getAttribute('data-specialicon');
		var popup = element.getAttribute('data-popup');
		var showroutingcontrol = element.getAttribute('data-showroutingcontrol');
		if (showroutingcontrol === '1')
		{
			var routingprofile = element.getAttribute('data-routingprofile');
			var routinglanguage = element.getAttribute('data-routinglanguage');
			var routingmetric = element.getAttribute('data-routingmetric');
			var routewhiledragging = element.getAttribute('data-routewhiledragging');
			var routing_position = element.getAttribute('data-routing_position');
			var routing_router = element.getAttribute('data-routing_router');
			var fitSelectedRoutes = element.getAttribute('data-fitSelectedRoutes');
			var reverseWaypoints = (element.getAttribute('data-reverseWaypoints') === "true");
			var collapsible = element.getAttribute('data-collapsible');
			var showAlternatives = element.getAttribute('data-showAlternatives');
		}
		var iconcolor = element.getAttribute('data-iconcolor');
		var markercolor = element.getAttribute('data-markercolor');
		var icon = element.getAttribute('data-icon');
		var popuptext = element.getAttribute('data-popuptext');
		var mapboxkey = element.getAttribute('data-mapboxkey');

		var container = L.DomUtil.get('map' + unique);

		if (maptype == 'mapbox') {
			var mapboxkey = element.getAttribute('data-mapboxkey');

			if (!container.children.length > 0) {
				if (scrollwheelzoom === "0")
				{
					window['map' + unique] = new L.Map('map' + unique, {scrollWheelZoom: false});
				} else
				{
					window['map' + unique] = new L.Map('map' + unique, {scrollWheelZoom: true});
				}
			}

			var osmUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=' + mapboxkey;
			var osmAttrib = 'Map data &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors, ' +
				'<a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, ' +
				'Imagery © <a href=\"http://mapbox.com\">Mapbox</a>';
			var osm = new L.TileLayer(osmUrl, {attribution: osmAttrib, id: 'mapbox.streets'});
			window['map' + unique].addLayer(osm);

		} else if (maptype == 'google')
		{
			if (!container.children.length > 0) {
				if (scrollwheelzoom === "0")
				{
					window['map' + unique] = new L.Map('map' + unique, {scrollWheelZoom: false});
				} else
				{
					window['map' + unique] = new L.Map('map' + unique, {scrollWheelZoom: true});
				}
			}

			var googleLayer = L.gridLayer.googleMutant({
				type: 'roadmap'
			}).addTo(window['map' + unique]);

		} else
		{
			[].forEach.call(leafletmaps, function (element) {


				// Initialize the Map if needed
				if (!container.children.length > 0) {
					if (scrollwheelzoom === "0")
					{
						window['map' + unique] = new L.Map('map' + unique, {scrollWheelZoom: false});
					} else
					{
						window['map' + unique] = new L.Map('map' + unique, {scrollWheelZoom: true});
					}
				}

				var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
				var osmAttrib = 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
				var osm = new L.TileLayer(osmUrl, {attribution: osmAttrib});
				window['map' + unique].addLayer(osm);
			});

		}

		if (!container.children.length > 0) {
			if (scrollwheelzoom === "0")
			{
				window['map' + unique] = new L.Map('map' + unique, {scrollWheelZoom: false});
				// Add Google Cooperative Gesture Handling 
			} else if (scrollwheelzoom === "2")
			{
				if (owngooglegesturetext === "1") {
					window['map' + unique] = new L.Map('map' + unique, {
						gestureHandling: true,
						gestureHandlingText: {
							touch: touch,
							scroll: scroll,
							scrollMac: scrollmac
						}
					});
				} else
				{
					window['map' + unique] = new L.Map('map' + unique, {
						gestureHandling: true
					});
				}
			} else
			{
				window['map' + unique] = new L.Map('map' + unique, {scrollWheelZoom: true});
			}
		}

		// Add Scrollwheele Listener, so that you can activate it on mouse click
		if (scrollwheelzoom === "0") {
			window['map' + unique].on('click', function () {
				if (window['map' + unique].scrollWheelZoom.enabled()) {
					window['map' + unique].scrollWheelZoom.disable();
				} else
				{
					window['map' + unique].scrollWheelZoom.enable();
				}
			});
		}


		// If routing control 
		if (showroutingcontrol === "1") {

			var routingcontrol;

			if (routing_router === 'mapbox')
			{
				routingcontrol = L.Routing.control(L.extend({
					fitSelectedRoutes: fitSelectedRoutes,
					position: routing_position,
					units: routingmetric,
					router: L.Routing.mapbox(mapboxkey,
						{
							profile: routingprofile,
							language: routinglanguage,
						}),
					waypoints: [
						L.latLng(lat, lon),
					],
					geocoder: L.Control.Geocoder.nominatim(),
					routeWhileDragging: routewhiledragging,
					reverseWaypoints: reverseWaypoints,
					collapsible: collapsible,
					showAlternatives: showAlternatives,
					altLineOptions: {
						styles: [
							{color: 'black', opacity: 0.15, weight: 9},
							{color: 'white', opacity: 0.8, weight: 6},
							{color: 'blue', opacity: 0.5, weight: 2}
						]
					}
				})).addTo(window['map' + unique]);
			} else {
				routingcontrol = L.Routing.control(L.extend({
					fitSelectedRoutes: fitSelectedRoutes,
					position: routing_position,
					units: routingmetric,
					router: L.Routing.osrmv1({language: routinglanguage}),
					waypoints: [
						L.latLng(lat, lon),
					],
					geocoder: L.Control.Geocoder.nominatim(),
					routeWhileDragging: routewhiledragging,
					reverseWaypoints: reverseWaypoints,
					collapsible: collapsible,
					showAlternatives: showAlternatives,
					altLineOptions: {
						styles: [
							{color: 'black', opacity: 0.15, weight: 9},
							{color: 'white', opacity: 0.8, weight: 6},
							{color: 'blue', opacity: 0.5, weight: 2}
						]
					}
				})).addTo(window['map' + unique]);

			}

			L.Routing.errorControl(routingcontrol).addTo(window['map' + unique]);
		}

		// Add Marker if possible - fallback cords 0,0
		try {
			window['map' + unique].setView(new L.LatLng(lat, lon), 13);

			var marker = L.marker([lat, lon]);

			// If special Icon
			if (specialicon === "1") {
				var AwesomeIcon = new L.AwesomeMarkers.icon(
					{
						icon: icon,
						markerColor: markercolor,
						iconColor: iconcolor,
						prefix: 'fa',
						spin: false,
						extraClasses: 'agosmsaddressmarkericonclass',
					})
				marker.setIcon(AwesomeIcon);
			}


			marker.addTo(window['map' + unique]);

			// If popup
			if (popup === "1") {
				marker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images'));
			}
			if (popup === "2") {
				marker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images')).openPopup();
			}

		} catch (e) {
			window['map' + unique].setView(new L.LatLng(0, 0), 13);
			var marker = L.marker([0, 0]).addTo(window['map' + unique]);
			console.log(e);
		}
	});
	// For all maps [end]

}, false);