document.addEventListener('DOMContentLoaded', function () {

	var form = document.querySelector('form');
	if (form) {
		form.addEventListener('change', function () {
			document.getElementById("gsearch-results").style.display = "none";
		});
	}

	var leafletmapsMod = document.querySelectorAll('.leafletmapModSearch');

	[].forEach.call(leafletmapsMod, function (element) {

		// Create map with worldWarp
		var scrollwheelzoom = element.getAttribute('data-scrollwheelzoom');
		var locate = element.getAttribute('data-locate');
		var uriroot = element.getAttribute('data-uriroot');
		var noWorldWarp = element.getAttribute('data-no-world-warp');
		var moduleId = element.getAttribute('data-module-id');
		var detectRetina = element.getAttribute('data-detect-retina');
		var baselayer = element.getAttribute('data-baselayer');
		var lonlat = element.getAttribute('data-lonlat').split(",", 3);
		var zoom = element.getAttribute('data-zoom');
		var mapboxkey = element.getAttribute('data-mapboxkey');
		var thunderforestkey = element.getAttribute('data-thunderforestkey');
		var stamenmaptype = element.getAttribute('data-stamenmaptype');
		var thunderforestmaptype = element.getAttribute('data-thunderforestmaptype');
		var googlemapstype = element.getAttribute('data-googlemapstype');
		var mapboxmaptype = element.getAttribute('data-mapboxmaptype');
		var attrModule = element.getAttribute('data-attr-module');
		var customBaselayer = element.getAttribute('data-customBaselayer');
		var customBaselayerURL = element.getAttribute('data-customBaselayerURL');
		var scale = element.getAttribute('data-scale');
		var scaleMetric = element.getAttribute('data-scale-metric');
		var scaleImperial = element.getAttribute('data-scale-imperial');
		var showgeocoder = element.getAttribute('data-showgeocoder');
		var useesri = element.getAttribute('data-useesri');
		var esrireversegeocoding = element.getAttribute('data-esrireversegeocoding');
		var geocodercollapsed = (element.getAttribute('data-geocodercollapsed') === "true");
		var geocoderposition = element.getAttribute('data-geocoderposition');
		var expand = element.getAttribute('data-expand');
		var dataEsrigeocoderopengetaddress = (element.getAttribute('data-esrigeocoderopengetaddress') === "true");
		var showgeocoderesri = element.getAttribute('data-showgeocoderesri');
		var positionesrigeocoder = element.getAttribute('data-positionesrigeocoder');
		var esrigeocoderzoomToResult = (element.getAttribute('data-esrigeocoderzoomToResult') === "true");
		var esrigeocoderuseMapBounds = (element.getAttribute('data-esrigeocoderuseMapBounds') === "true");
		var esrigeocodercollapseAfterResult = (element.getAttribute('data-esrigeocodercollapseAfterResult') === "true");
		var esrigeocoderexpanded = (element.getAttribute('data-esrigeocoderexpanded') === "true");
		var esriallowMultipleResults = (element.getAttribute('data-esriallowMultipleResults') === "true");
		var showrouting_simple = element.getAttribute('data-showrouting-simple');
		if (showrouting_simple === '1')
		{
			var routesimpleposition = element.getAttribute('data-route-simple-position');
			var routesimpletarget = element.getAttribute('data-route-simple-target');
			var routesimplerouter = element.getAttribute('data-route-simple-router');
			var routesimplerouterkey = element.getAttribute('data-route-simple-routerkey');
		}
		var showrouting = element.getAttribute('data-showrouting');
		if (showrouting === '1')
		{
			var routingstart = element.getAttribute('data-routingstart').split(",", 3);
			var routingend = element.getAttribute('data-routingend').split(",", 3);
			var mapboxkeyRouting = element.getAttribute('data-mapboxkey-routing');
			var routingprofile = element.getAttribute('data-routingprofile');
			var routinglanguage = element.getAttribute('data-routinglanguage');
			var routingmetric = element.getAttribute('data-routingmetric');
			var routewhiledragging = element.getAttribute('data-routewhiledragging');
		}
		var showpin = element.getAttribute('data-showpin');
		if (showpin === '1')
		{
			var specialpins = JSON.parse(element.getAttribute('data-specialpins'));
		}
		var showcomponentpin = element.getAttribute('data-showcomponentpin');
		if (showcomponentpin === '1')
		{
			var specialcomponentpins = JSON.parse(element.getAttribute('data-specialcomponentpins'));
		}
		var showcustomfieldpin = element.getAttribute('data-showcustomfieldpin');
		var specialcustomfieldpins = JSON.parse(element.getAttribute('data-specialcustomfieldpins'));
		var touch = element.getAttribute('data-touch');
		var scroll = element.getAttribute('data-scroll');
		var scrollmac = element.getAttribute('data-scrollmac');
		var owngooglegesturetext = element.getAttribute('data-owngooglegesturetext');

		// Default: worldCopyJump: false && scrollWheelZoom: true
		if (noWorldWarp === "1" && scrollwheelzoom === "0")
		{
			window['mysearchmap' + moduleId] = new L.Map('searchmap' + moduleId, {
				scrollWheelZoom: false,
				worldCopyJump: false,
				maxBounds: [[82, -180], [-82, 180]]
			}).setView(lonlat, zoom);
		} else if (noWorldWarp === "1" && scrollwheelzoom === "1") {
			window['mysearchmap' + moduleId] = new L.Map('searchmap' + moduleId, {
				worldCopyJump: false,
				maxBounds: [[82, -180], [-82, 180]]
			}).setView(lonlat, zoom);
		} else if (noWorldWarp === "1" && scrollwheelzoom === "2") {
			if (owngooglegesturetext === "1") {
				window['mysearchmap' + moduleId] = new L.Map('searchmap' + moduleId, {
					worldCopyJump: false,
					maxBounds: [[82, -180], [-82, 180]],
					gestureHandling: true,
					gestureHandlingText: {
						touch: touch,
						scroll: scroll,
						scrollMac: scrollmac
					}
				}).setView(lonlat, zoom);
			} else
			{
				window['mysearchmap' + moduleId] = new L.Map('searchmap' + moduleId, {
					worldCopyJump: false,
					maxBounds: [[82, -180], [-82, 180]],
					gestureHandling: true
				}).setView(lonlat, zoom);
			}
		} else if (noWorldWarp === "0" && scrollwheelzoom === "0") {
			window['mysearchmap' + moduleId] = new L.Map('searchmap' + moduleId, {
				scrollWheelZoom: false,
				worldCopyJump: true
			}).setView(lonlat, zoom);
		} else if (noWorldWarp === "0" && scrollwheelzoom === "2") {
			if (owngooglegesturetext === "1") {
				window['mysearchmap' + moduleId] = new L.Map('searchmap' + moduleId, {
					worldCopyJump: true,
					gestureHandling: true,
					gestureHandlingText: {
						touch: touch,
						scroll: scroll,
						scrollMac: scrollmac
					}
				}).setView(lonlat, zoom);
			} else
			{
				window['mysearchmap' + moduleId] = new L.Map('searchmap' + moduleId, {
					worldCopyJump: true,
					gestureHandling: true
				}).setView(lonlat, zoom);
			}
		} else {
			window['mysearchmap' + moduleId] = new L.Map('searchmap' + moduleId, {
				worldCopyJump: true
			}).setView(lonlat, zoom);
		}

		// Add Scrollwheele Listener, so that you can activate it on mouse click
		if (scrollwheelzoom === "0") {
			window['mysearchmap' + moduleId].on('click', function () {
				if (window['mysearchmap' + moduleId].scrollWheelZoom.enabled()) {
					window['mysearchmap' + moduleId].scrollWheelZoom.disable();
				} else
				{
					window['mysearchmap' + moduleId].scrollWheelZoom.enable();
				}
			});
		}

		// Baselayer
		var nowarp = "noWrap: false, ";
		if (noWorldWarp === "1")
		{
			nowarp = "noWrap: true, ";
		}
		var detectRetina = "detectRetina: false, ";
		if (detectRetina === "1")
		{
			detectRetina = "detectRetina: true, ";
		}

		// Base layer url
		var astrid = '';
		if (attrModule === '1')
		{
			astrid = ' | Joomla-Module by <a href="https://www.astrid-guenther.de">Astrid Günther</a>';
		}
		var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a>' + astrid
		});
		if (baselayer === 'mapbox') {
			tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
				attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>' + astrid,
				tileSize: 512,
				maxZoom: 18,
				zoomOffset: -1,
				id: mapboxmaptype,
				accessToken: mapboxkey
				});			
		}
		if (baselayer === 'mapnikde')
		{
			tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: '&copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a>' + astrid
			});
		}
		if (baselayer === 'stamen')
		{
			tileLayer = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/' + stamenmaptype + '/{z}/{x}/{y}.png', {
				subdomains: 'abcd', minZoom: 1, maxZoom: 16,
				attribution: 'Map data &copy; <a href=\"https://openstreetmap.org\">OpenStreetMap</a> contributors, ' +
					'<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY 3.0</a>, ' +
					'Imagery &copy; <a href=\"http://stamen.com\">Stamen Design</a>' + astrid,
				id: ''
			});
		}
		if (baselayer === 'opentopomap')
		{
			tileLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
				maxZoom: 16,
				attribution: '<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY 3.0</a>, ' +
					'Imagery &copy; <a href=\"http://viewfinderpanoramas.org\">SRTM</a>' + astrid,
				id: ''
			});
		}
		if (baselayer === 'openmapsurfer')
		{
			tileLayer = L.tileLayer('http://korona.geog.uni-heidelberg.de/tiles/roads/x={x}&y={y}&z={z}', {
				maxZoom: 20,
				attribution: '<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY 3.0</a>, ' +
					'Imagery &copy; <a href=\"http://giscience.uni-hd.de\">GIScience Research Group</a>' + astrid,
				id: ''
			});
		}
		if (baselayer === 'humanitarian')
		{
			tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
				maxZoom: 20,
				attribution: '<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY 3.0</a>, ' +
					'Imagery &copy; <a href=\"https://hotosm.org\">Humanitarian OpenStreetMap Team</a>' + astrid,
				id: ''
			});
		}
		if (baselayer === 'custom')
		{
			//tileLayer = L.tileLayer(customBaselayerURL, {customBaselayer});
		}
		if (baselayer === 'google')
		{
			tileLayer = L.gridLayer.googleMutant({
				type: googlemapstype,
				attribution: astrid
			});
		}
		if (baselayer === 'thunderforest')
		{
			tileLayer = L.tileLayer('https://{s}.tile.thunderforest.com/' + thunderforestmaptype + '/{z}/{x}/{y}.png?apikey={apikey}', {
				maxZoom: 22,
				apikey: thunderforestkey,
				attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a>'
			});
		}

		tileLayer.addTo(window['mysearchmap' + moduleId]);

		// SCALE CONTROL
		if ((scale) !== '0')
		{
			let aggpxScale = L.control.scale();

			if (scaleMetric !== '1')
			{
				aggpxScale.options.metric = false;
			}

			if (scaleImperial !== '1')
			{
				aggpxScale.options.imperial = false;
			}

			aggpxScale.addTo(window['mysearchmap' + moduleId]);

		}

		var clustermarkers = L.markerClusterGroup();

		// Show Pins from customfield
		if (true)
		{
			var clustermarkers = L.markerClusterGroup();

			for (var specialcustomfieldpin in specialcustomfieldpins) {
				// skip loop if the property is from prototype
				if (!specialcustomfieldpins.hasOwnProperty(specialcustomfieldpin)) {
					continue;
				}

				var objcf = specialcustomfieldpins[specialcustomfieldpin];
				if (Object.keys(objcf).length === 0) {
					continue;
				}

				let tempMarkercf = null;

				if (objcf.cords && !objcf.cords.startsWith(",,"))
				{
					var values = objcf.cords.split(",");

					tempMarkercf = L.marker(objcf.cords.split(",").slice(0, 2));

					if (values.length > 4 && objcf.type !== 'agosmsaddressmarker')
					{
						var AwesomeIcon = new L.AwesomeMarkers.icon(
							{
								icon: values[4],
								markerColor: values[2],
								iconColor: values[3],
								prefix: 'fa',
								spin: false,
								extraClasses: "agosmsmarkerextraklasse",
							})
						tempMarkercf.setIcon(AwesomeIcon);
					}

					if (objcf.type === 'agosmsaddressmarker' && objcf.iconcolor && objcf.markercolor && objcf.icon)
					{
						var AwesomeIcon = new L.AwesomeMarkers.icon(
							{
								icon: objcf.icon,
								markerColor: objcf.markercolor,
								iconColor: objcf.iconcolor,
								prefix: 'fa',
								spin: false,
								extraClasses: "agosmsaddressmarkerextraklasse",
							})
						tempMarkercf.setIcon(AwesomeIcon);
					}

					let url = "index.php?options=com_content&view=article&id=" + objcf.id;
					let title = objcf.title;
					let introtext = objcf.introtext;
					let image = '';
					if (objcf.image) {
						image = "<img class='searchpopupimage' src='" + uriroot + objcf.image + "' />" ;
					}

					if (values.length > 5 && values[5].trim() != '')
					{
						title = values[5];
					}

					let popuptext = "<a href=' " + url + " '> " + title + " </a>";
					tempMarkercf.bindPopup(
						image +
						popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images') +
						introtext
						);
					tempMarkercf.addTo(clustermarkers);
				}
			}
			if (JSON.parse(sessionStorage.getItem('mapState')) && savestate === "1")
			{
				window['mysearchmap' + moduleId].fitBounds(mapState.bounds);
			} else {
				window['mysearchmap' + moduleId].fitBounds(clustermarkers.getBounds());
			}
			clustermarkers.addTo(window['mysearchmap' + moduleId]);
		}
		window['mysearchmap' + moduleId].fitBounds(clustermarkers.getBounds());
		clustermarkers.addTo(window['mysearchmap' + moduleId]);

		// Show locate
		if (locate === '1')
		{
			var lc = L.control.locate({
				position: 'topright',
				initialZoomLevel: 17,
				strings: {
					title: "Wo bin ich?"
				}
			}).addTo(window['mysearchmap' + moduleId]);
		}

	})
}, false);
