document.addEventListener('DOMContentLoaded', function () {
	var leafletmaps = document.querySelectorAll('.agosmsaddressmarkerleafletmap');

	// For all maps [start]
	[].forEach.call(leafletmaps, function (element) {

		var unique = element.getAttribute('data-unique');
		var scrollwheelzoom = element.getAttribute('data-scrollwheelzoom');

		// Initialize the Map if needed
		var container = L.DomUtil.get('map' + unique);
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
	});
	// For all maps [end]

}, false);