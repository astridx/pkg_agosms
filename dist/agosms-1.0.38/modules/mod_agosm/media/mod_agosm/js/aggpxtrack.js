document.addEventListener('DOMContentLoaded', function () {
	var leafletmaps = document.querySelectorAll('.leafletmapModGpx');

	// For all maps [start]
	[].forEach.call(leafletmaps, function (element) {

		var moduleId = element.getAttribute('data-module-id');
		var gpx_file_name = element.getAttribute('data-gpx_file_name');
		var startIconUrl = element.getAttribute('data-startIconUrl');
		var endIconUrl = element.getAttribute('data-endIconUrl');
		var shadowIconUrl = element.getAttribute('data-shadowUrl');
		var wptIconUrls = element.getAttribute('data-wptIconUrls');

		var gpxfilenames = gpx_file_name.split(';;');
		
		var group = L.featureGroup([]);
		window['mymap' + moduleId].fitBounds([[0, 0],[0, 0]]);

		for (var i = 0; i < gpxfilenames.length; i++) {
			console.log(gpxfilenames[i]);
			new L.GPX(gpxfilenames[i],
				{
					marker_options: {
						startIconUrl: startIconUrl,
						endIconUrl: endIconUrl,
						shadowUrl: shadowIconUrl,
						wptIconUrls: {
							'': wptIconUrls
						}
					},
					async: true
				}).on('loaded', function (e) {
				if (!window['mymap' + moduleId].getBounds().contains(L.latLng(0, 0))){
					var bounds = window['mymap' + moduleId].getBounds().extend(e.target.getBounds());
					window['mymap' + moduleId].fitBounds(bounds);
				} else {
					window['mymap' + moduleId].fitBounds(e.target.getBounds());
				}
			}).addTo(group);
		}
		;
		group.addTo(window['mymap' + moduleId]);
	});

}, false);