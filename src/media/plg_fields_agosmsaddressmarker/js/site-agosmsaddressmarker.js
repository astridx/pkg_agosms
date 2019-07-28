document.addEventListener('DOMContentLoaded', function () {
	var leafletmaps = document.querySelectorAll('.agosmsaddressmarkerleafletmap');

	// For all maps [start]
	[].forEach.call(leafletmaps, function (element) {

		var unique = element.getAttribute('data-unique');
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
		var iconcolor = element.getAttribute('data-iconcolor');
		var markercolor = element.getAttribute('data-markercolor');
		var icon = element.getAttribute('data-icon');
		var popuptext = element.getAttribute('data-popuptext');
		var mapboxkey = element.getAttribute('data-mapboxkey');
		var routing_simple_position = element.getAttribute('data-routing_simple_position');
		var routing_simple_router = element.getAttribute('data-routing_simple_router');

		// Initialize the Map if needed
		var container = L.DomUtil.get('map' + unique);
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
			L.leafletControlRoutingtoaddress({
				position: routing_simple_position,
				router: routing_simple_router,
				token: mapboxkey,
				placeholder: Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ROUTING_SIMPLE_TEXT_PLACEHOLDER'),
				errormessage: Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ROUTING_SIMPLE_TEXT_ERRORMESSAGE'),
				distance: Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ROUTING_SIMPLE_TEXT_DISTANCE'),
				duration: Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ROUTING_SIMPLE_TEXT_DURATION'),
				target: [lat, lon],
				addresserror: Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ROUTING_SIMPLE_TEXT_ADDRESSERROR'),
				requesterror: Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ROUTING_SIMPLE_TEXT_REQUESTERROR')
			}).addTo(window['map' + unique]);
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
				marker.bindPopup(popuptext);
			}
			if (popup === "2") {
				marker.bindPopup(popuptext).openPopup();
			}

		} catch (e) {
			window['map' + unique].setView(new L.LatLng(0, 0), 13);
			var marker = L.marker([0, 0]).addTo(window['map' + unique]);
			console.log(e);
		}
	});
	// For all maps [end]

}, false);