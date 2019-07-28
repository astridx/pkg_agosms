document.addEventListener('DOMContentLoaded', function () {

	var leafletmapsMod = document.querySelectorAll('.leafletmapMod');

	[].forEach.call(leafletmapsMod, function (element) {

		// Create map with worldWarp
		var scrollwheelzoom = element.getAttribute('data-scrollwheelzoom');
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
		if (showcustomfieldpin === '1')
		{
			var specialcustomfieldpins = JSON.parse(element.getAttribute('data-specialcustomfieldpins'));
		}
		var touch = element.getAttribute('data-touch');
		var scroll = element.getAttribute('data-scroll');
		var scrollmac = element.getAttribute('data-scrollmac');
		var owngooglegesturetext = element.getAttribute('data-owngooglegesturetext');

		// Default: worldCopyJump: false && scrollWheelZoom: true
		if (noWorldWarp === "1" && scrollwheelzoom === "0")
		{
			window['mymap' + moduleId] = new L.Map('map' + moduleId, {
				scrollWheelZoom: false,
				worldCopyJump: false,
				maxBounds: [[82, -180], [-82, 180]]
			}).setView(lonlat, zoom);
		} else if (noWorldWarp === "1" && scrollwheelzoom === "1") {
			window['mymap' + moduleId] = new L.Map('map' + moduleId, {
				worldCopyJump: false,
				maxBounds: [[82, -180], [-82, 180]]
			}).setView(lonlat, zoom);
		} else if (noWorldWarp === "1" && scrollwheelzoom === "2") {
			if (owngooglegesturetext === "1") {
				window['mymap' + moduleId] = new L.Map('map' + moduleId, {
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
				window['mymap' + moduleId] = new L.Map('map' + moduleId, {
					worldCopyJump: false,
					maxBounds: [[82, -180], [-82, 180]],
					gestureHandling: true
				}).setView(lonlat, zoom);
			}
		} else if (noWorldWarp === "0" && scrollwheelzoom === "0") {
			window['mymap' + moduleId] = new L.Map('map' + moduleId, {
				scrollWheelZoom: false,
				worldCopyJump: true
			}).setView(lonlat, zoom);
		} else if (noWorldWarp === "0" && scrollwheelzoom === "2") {
			if (owngooglegesturetext === "1") {
				window['mymap' + moduleId] = new L.Map('map' + moduleId, {
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
				window['mymap' + moduleId] = new L.Map('map' + moduleId, {
					worldCopyJump: true,
					gestureHandling: true
				}).setView(lonlat, zoom);
			}
		} else {
			window['mymap' + moduleId] = new L.Map('map' + moduleId, {
				worldCopyJump: true
			}).setView(lonlat, zoom);
		}

		// Add Scrollwheele Listener, so that you can activate it on mouse click
		if (scrollwheelzoom === "0") {
			window['mymap' + moduleId].on('click', function () {
				if (window['mymap' + moduleId].scrollWheelZoom.enabled()) {
					window['mymap' + moduleId].scrollWheelZoom.disable();
				} else
				{
					window['mymap' + moduleId].scrollWheelZoom.enable();
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
			astrid = ' ' + Joomla.JText._('MOD_AGOSM_MODULE_BY') + ' <a href="https://www.astrid-guenther.de">Astrid Günther</a>';
		}
		var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a>' + astrid
		});
		if (baselayer === 'mapbox')
		{
			tileLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=' + mapboxkey, {
				maxZoom: 18,
				attribution: 'Map data &copy; <a href=\"https://openstreetmap.org\">OpenStreetMap</a> contributors, ' +
					'<a href=\"https://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, ' +
					'Imagery © <a href=\"https://mapbox.com\">Mapbox</a>' + astrid,
				id: mapboxmaptype
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

		tileLayer.addTo(window['mymap' + moduleId]);

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

			aggpxScale.addTo(window['mymap' + moduleId]);

		}

		// Add Geocoder
		if (showgeocoder === "1")
		{
			var osmGeocoder = new L.Control.Geocoder({
				collapsed: geocodercollapsed,
				position: geocoderposition,
				geocoder: new L.Control.Geocoder.Nominatim(),
				expand: expand,
				placeholder: Joomla.JText._('MOD_AGOSM_DEFAULT_TEXT_PLACEHOLDER'),
				errorMessage: Joomla.JText._('MOD_AGOSM_DEFAULT_TEXT_ERRORMESSAGE')
			});
			window['mymap' + moduleId].addControl(osmGeocoder);
		}

		// Add ESRI Geocoder
		if (useesri === "1")
		{
			if (dataEsrigeocoderopengetaddress)
			{
				function getURLParameter(name) {
					var value = decodeURIComponent((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, ""])[1]);
					return (value !== 'null') ? value : false;
				}
				var address = decodeURIComponent(getURLParameter('address'));
				L.esri.Geocoding.geocode().text(address).run(function (err, result, response) {
					if (typeof result !== 'undefined' && result.length > 0) {
						L.marker(result.results[0].latlng).addTo(window['mymap' + moduleId]);
						window['mymap' + moduleId].setView(result.results[0].latlng, 13);
					}
				});
			}


			if (esrireversegeocoding === 'true')
			{
				var r = L.marker();
				window['mymap' + moduleId].on('click', function (e) {
					L.esri.Geocoding.reverseGeocode()
						.latlng(e.latlng)
						.run(function (error, result, response) {
							r = L.marker(result.latlng).addTo(window['mymap' + moduleId]).bindPopup(result.address.Match_addr).openPopup();
						});
				});
			}

			if (showgeocoderesri === '1')
			{
				var esriGeocoder = L.esri.Geocoding.geosearch({
					position: positionesrigeocoder,
					zoomToResult: esrigeocoderzoomToResult,
					useMapBounds: esrigeocoderuseMapBounds,
					collapseAfterResult: esrigeocodercollapseAfterResult,
					expanded: esrigeocoderexpanded,
					allowMultipleResults: esriallowMultipleResults,
					placeholder: Joomla.JText._('MOD_AGOSM_DEFAULT_ESRI_GEOCODER_PLACEHOLDER'),
					title: Joomla.JText._('MOD_AGOSM_DEFAULT_ESRI_GEOCODER_TITLE')
				});
				var results = L.layerGroup().addTo(window['mymap' + moduleId]);
				esriGeocoder.on('results', function (data) {
					results.clearLayers();
					for (var i = data.results.length - 1; i >= 0; i--) {
						results.addLayer(L.marker(data.results[i].latlng));
					}
				});
				window['mymap' + moduleId].addControl(esriGeocoder);
			}
		}

		// Add Routing Simple
		if (showrouting_simple === '1')
		{
			L.leafletControlRoutingtoaddress({
				position: routesimpleposition,
				router: routesimplerouter,
				token: routesimplerouterkey,
				placeholder: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_PLACEHOLDER'),
				errormessage: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_ERRORMESSAGE'),
				distance: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_DISTANCE'),
				duration: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_DURATION'),
				target: routesimpletarget,
				addresserror: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_ADDRESSERROR'),
				requesterror: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_REQUESTERROR')
			}).addTo(window['mymap' + moduleId]);
		}

		// Add Routing Mapbox
		if (showrouting === '1')
		{
			L.Routing.control({
				geocoder: L.Control.Geocoder.nominatim({}),
				waypoints: [
					L.latLng(routingstart),
					L.latLng(routingend)
				],
				collapsible: true,
				router: L.Routing.mapbox(mapboxkeyRouting,
					{
						profile: routingprofile,
						language: routinglanguage,
					}),
				units: routingmetric,
				routeWhileDragging: routewhiledragging
			}).addTo(window['mymap' + moduleId]);
		}

		// Special Pins
		if (showpin === '1')
		{
			for (var specialpin in specialpins) {
				// skip loop if the property is from prototype
				if (!specialpins.hasOwnProperty(specialpin))
					continue;

				var obj = specialpins[specialpin];
				let tempMarker = L.marker(obj.latlonpin.split(",", 3));

				if (obj.pin === "2" && obj.customPinPath != "")
				{
					/*					var LeafIcon = L.Icon.extend({
					 options: {
					 iconUrl: obj.customPinPath,
					 shadowUrl: obj.customPinShadowPath,
					 iconSize: obj.customPinSize.split(",", 3).map(e => parseInt(e)),
					 shadowSize: obj.customPinShadowSize.split(",", 3).map(e => parseInt(e)),
					 iconAnchor: obj.customPinOffset.split(",", 3).map(e => parseInt(e)),
					 popupAnchor: obj.customPinPopupOffset.split(",", 3).map(e => parseInt(e)),
					 }
					 });*/
					var LeafIcon = L.Icon.extend({
						options: {
							iconUrl: obj.customPinPath,
							shadowUrl: obj.customPinShadowPath,
							iconSize: obj.customPinSize.split(",", 3).map(function (e) {
								return parseInt(e);
							}),
							shadowSize: obj.customPinShadowSize.split(",", 3).map(function (e) {
								return parseInt(e);
							}),
							iconAnchor: obj.customPinOffset.split(",", 3).map(function (e) {
								return parseInt(e);
							}),
							popupAnchor: obj.customPinPopupOffset.split(",", 3).map(function (e) {
								return parseInt(e);
							})
						}
					});
					tempMarker.setIcon(new LeafIcon());
				}

				if (obj.pin === "3")
				{
					var AwesomeIcon = new L.AwesomeMarkers.icon(
						{
							icon: obj.awesomeicon_icon,
							markerColor: obj.awesomeicon_markercolor,
							iconColor: obj.awesomeicon_iconcolor,
							prefix: 'fa',
							spin: (obj.awesomeicon_spin === "true"),
							extraClasses: obj.awesomeicon_extraclasses,
						})
					tempMarker.setIcon(AwesomeIcon);
				}



				tempMarker.addTo(window['mymap' + moduleId]);

				if (obj.popup === "1")
				{
					tempMarker.bindPopup(obj.popuptext);
				}

				if (obj.popup === "2")
				{
					tempMarker.bindPopup(obj.popuptext).openPopup();
				}
			}
		}

		// Show Pins from component
		if (showcomponentpin === '1')
		{

			for (var specialcomponentpin in specialcomponentpins) {
				// skip loop if the property is from prototype
				if (!specialcomponentpins.hasOwnProperty(specialcomponentpin))
					continue;

				var obj = specialcomponentpins[specialcomponentpin];
				let tempMarker = L.marker(obj.coordinates.split(",", 3));

				if (obj.showdefaultpin === "2" && obj.customPinPath != "")
				{
					/*					var LeafIcon = L.Icon.extend({
					 options: {
					 iconUrl: obj.customPinPath,
					 shadowUrl: obj.customPinShadowPath,
					 iconSize: obj.customPinSize.split(",", 3).map(e => parseInt(e)),
					 shadowSize: obj.customPinShadowSize.split(",", 3).map(e => parseInt(e)),
					 iconAnchor: obj.customPinOffset.split(",", 3).map(e => parseInt(e)),
					 popupAnchor: obj.customPinPopupOffset.split(",", 3).map(e => parseInt(e)),
					 }
					 });*/
					var LeafIcon = L.Icon.extend({
						options: {
							iconUrl: obj.customPinPath,
							shadowUrl: obj.customPinShadowPath,
							iconSize: obj.customPinSize.split(",", 3).map(function (e) {
								return parseInt(e);
							}),
							shadowSize: obj.customPinShadowSize.split(",", 3).map(function (e) {
								return parseInt(e);
							}),
							iconAnchor: obj.customPinOffset.split(",", 3).map(function (e) {
								return parseInt(e);
							}),
							popupAnchor: obj.customPinPopupOffset.split(",", 3).map(function (e) {
								return parseInt(e);
							})
						}
					});
					tempMarker.setIcon(new LeafIcon());
				}

				if (obj.showdefaultpin === "3")
				{
					var AwesomeIcon = new L.AwesomeMarkers.icon(
						{
							icon: obj.awesomeicon_icon,
							markerColor: obj.awesomeicon_markercolor,
							iconColor: obj.awesomeicon_iconcolor,
							prefix: 'fa',
							spin: (obj.awesomeicon_spin === "true"),
							extraClasses: obj.awesomeicon_extraclasses,
						})
					tempMarker.setIcon(AwesomeIcon);
				}



				tempMarker.addTo(window['mymap' + moduleId]);

				if (obj.showpopup === "1")
				{
					tempMarker.bindPopup(obj.popuptext);
				}

				if (obj.showpopup === "2")
				{
					tempMarker.bindPopup(obj.popuptext).openPopup();
				}
			}
		}

		// Show Pins from customfield
		if (showcustomfieldpin === '1')
		{
			var clustermarkers = L.markerClusterGroup();

			for (var specialcustomfieldpin in specialcustomfieldpins) {
				// skip loop if the property is from prototype
				if (!specialcustomfieldpins.hasOwnProperty(specialcustomfieldpin))
					continue;

				var objcf = specialcustomfieldpins[specialcustomfieldpin];

				let tempMarkercf = null;
				
				if (objcf.cords)
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

					if (values.length > 5 && values[5].trim() != '')
					{
						title = values[5];
					}
					let popuptext = "<a href=' " + url + " '> " + title + " </a>";
					tempMarkercf.bindPopup(popuptext);
					tempMarkercf.addTo(clustermarkers);
				}
			}
			window['mymap' + moduleId].fitBounds(clustermarkers.getBounds());
			clustermarkers.addTo(window['mymap' + moduleId]);
		}

	})
}, false);

