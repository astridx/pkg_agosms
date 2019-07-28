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

		// Add Marker if possible - fallback cords 0,0
		try {
			window['map' + unique].setView(new L.LatLng(lat, lon), 13);
			var marker = L.marker([lat, lon]).addTo(window['map' + unique]);
		} catch (e) {
			window['map' + unique].setView(new L.LatLng(0, 0), 13);
			var marker = L.marker([0, 0]).addTo(window['map' + unique]);
			console.log(e);
		}
	});
	// For all maps [end]

}, false);