document.addEventListener('DOMContentLoaded', function () {

	var leafletmapsMod = document.querySelectorAll('.leafletmapMod');

	[].forEach.call(leafletmapsMod, function (element) {

		var layoutfordisplaylinkinmarker = element.getAttribute('data-layoutfordisplaylinkinmarker');

		var savestate = element.getAttribute('data-savestate');
		var fullscreen = element.getAttribute('data-fullscreen');
		var locate = element.getAttribute('data-locate');
		var mouseposition = element.getAttribute('data-mouseposition');

		var geojson = element.getAttribute('data-geojson');
		var geojsonTextRaw = element.getAttribute('data-geojson-text');
		var geojsonText = JSON.parse(element.getAttribute('data-geojson-text'));
		var geojsonfile = element.getAttribute('data-geojsonfile');
		var geojsonfilename = element.getAttribute('data-geojson-file');

		var uriroot = element.getAttribute('data-uriroot');
		var scrollwheelzoom = element.getAttribute('data-scrollwheelzoom');
		var noWorldWarp = element.getAttribute('data-no-world-warp');
		var moduleId = element.getAttribute('data-module-id');
		var detectRetina = element.getAttribute('data-detect-retina');
		var baselayer = element.getAttribute('data-baselayer');
		var layertree = element.getAttribute('data-layertree');

		var lonlat = element.getAttribute('data-lonlat').split(",", 3);
		var zoom = element.getAttribute('data-zoom');
		var disableClusteringAtZoom = element.getAttribute('data-disable-clustering-at-zoom');
		var mapboxkey = element.getAttribute('data-mapboxkey');
		var thunderforestkey = element.getAttribute('data-thunderforestkey');
		var stamenmaptype = element.getAttribute('data-stamenmaptype');
		var thunderforestmaptype = element.getAttribute('data-thunderforestmaptype');
		var googlemapstype = element.getAttribute('data-googlemapstype');
		var mapboxmaptype = element.getAttribute('data-mapboxmaptype');
		var customBaselayer = element.getAttribute('data-customBaselayer');
		var customBaselayerURL = element.getAttribute('data-customBaselayerURL');
		var scale = element.getAttribute('data-scale');
		var scaleMetric = element.getAttribute('data-scale-metric');
		var scaleImperial = element.getAttribute('data-scale-imperial');
		var showgeocoder = element.getAttribute('data-showgeocoder');
		var useesri = element.getAttribute('data-useesri');
		var esrireversegeocoding = element.getAttribute('data-esrireversegeocoding');
		var geocodercollapsed = (element.getAttribute('data-geocodercollapsed') == "true");
		var geocoderposition = element.getAttribute('data-geocoderposition');
		var expand = element.getAttribute('data-expand');
		var dataEsrigeocoderopengetaddress = (element.getAttribute('data-esrigeocoderopengetaddress') == "true");
		var showgeocoderesri = element.getAttribute('data-showgeocoderesri');
		var positionesrigeocoder = element.getAttribute('data-positionesrigeocoder');
		var esrigeocoderzoomToResult = (element.getAttribute('data-esrigeocoderzoomToResult') == "true");
		var esrigeocoderuseMapBounds = (element.getAttribute('data-esrigeocoderuseMapBounds') == "true");
		var esrigeocodercollapseAfterResult = (element.getAttribute('data-esrigeocodercollapseAfterResult') == "true");
		var esrigeocoderexpanded = (element.getAttribute('data-esrigeocoderexpanded') == "true");
		var esriallowMultipleResults = (element.getAttribute('data-esriallowMultipleResults') == "true");
		var showrouting_simple = element.getAttribute('data-showrouting-simple');

		var addprivacybox = element.getAttribute('data-addprivacybox');
		var unique = element.getAttribute('data-unique');
		var buttons = document.getElementsByClassName('b' + unique);
		var privacyfields = document.getElementsByClassName('p' + unique);

		if (showrouting_simple == '1') {
			var routesimpleposition = element.getAttribute('data-route-simple-position');
			var routesimpletarget = element.getAttribute('data-route-simple-target');
			var routesimplerouter = element.getAttribute('data-route-simple-router');
			var routesimplerouterkey = element.getAttribute('data-route-simple-routerkey');
		}
		var showrouting = element.getAttribute('data-showrouting');
		if (showrouting == '1') {
			var routingstart = element.getAttribute('data-routingstart').split(",", 3);
			var routingend = element.getAttribute('data-routingend').split(",", 3);
			var mapboxkeyRouting = element.getAttribute('data-mapboxkey-routing');
			var routingprofile = element.getAttribute('data-routingprofile');
			var routinglanguage = element.getAttribute('data-routinglanguage');
			var routingmetric = element.getAttribute('data-routingmetric');
			var routewhiledragging = element.getAttribute('data-routewhiledragging');
		}
		var showpin = element.getAttribute('data-showpin');
		if (showpin == '1') {
			var specialpins = JSON.parse(element.getAttribute('data-specialpins'));
		}
		var showcomponentpin = element.getAttribute('data-showcomponentpin');
		if (showcomponentpin == '1') {
			var specialcomponentpins = JSON.parse(element.getAttribute('data-specialcomponentpins'));
		}
		var showcomponentpinone = element.getAttribute('data-showcomponentpinone');
		if (showcomponentpinone == '1') {
			var specialcomponentpinone = JSON.parse(element.getAttribute('data-specialcomponentpinone'));
		}

		var showcustomfieldpin = element.getAttribute('data-showcustomfieldpin');
		if (showcustomfieldpin == '1') {
			var specialcustomfieldpins = JSON.parse(element.getAttribute('data-specialcustomfieldpins'));
		}
		var touch = element.getAttribute('data-touch');
		var scroll = element.getAttribute('data-scroll');
		var scrollmac = element.getAttribute('data-scrollmac');
		var owngooglegesturetext = element.getAttribute('data-owngooglegesturetext');

		// Fetch the States
		if (JSON.parse(sessionStorage.getItem('mapState')) && savestate == "1") {
			var mapState = JSON.parse(sessionStorage.getItem('mapState'));
			zoom = mapState.zoom;
			lonlat = mapState.center;
		}

		// Default: worldCopyJump: false && scrollWheelZoom: true
		if (noWorldWarp == "1" && scrollwheelzoom == "0") {
			window['mymap' + moduleId] = new L.Map('map' + moduleId, {
				scrollWheelZoom: false,
				worldCopyJump: false,
				maxBounds: [[82, -180], [-82, 180]]
			}).setView(lonlat, zoom);
		} else if (noWorldWarp == "1" && scrollwheelzoom == "1") {
			window['mymap' + moduleId] = new L.Map('map' + moduleId, {
				worldCopyJump: false,
				maxBounds: [[82, -180], [-82, 180]]
			}).setView(lonlat, zoom);
		} else if (noWorldWarp == "1" && scrollwheelzoom == "2") {
			if (owngooglegesturetext == "1") {
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
			} else {
				window['mymap' + moduleId] = new L.Map('map' + moduleId, {
					worldCopyJump: false,
					maxBounds: [[82, -180], [-82, 180]],
					gestureHandling: true
				}).setView(lonlat, zoom);
			}
		} else if (noWorldWarp == "0" && scrollwheelzoom == "0") {
			window['mymap' + moduleId] = new L.Map('map' + moduleId, {
				scrollWheelZoom: false,
				worldCopyJump: true
			}).setView(lonlat, zoom);
		} else if (noWorldWarp == "0" && scrollwheelzoom == "2") {
			if (owngooglegesturetext == "1") {
				window['mymap' + moduleId] = new L.Map('map' + moduleId, {
					worldCopyJump: true,
					gestureHandling: true,
					gestureHandlingText: {
						touch: touch,
						scroll: scroll,
						scrollMac: scrollmac
					}
				}).setView(lonlat, zoom);
			} else {
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

		// Privacy
		if (localStorage.getItem("privacyState") == null) {
			localStorage.setItem("privacyState", '0')
		}

		var i;
		for (i = 0; i < buttons.length; i++) {
			if (localStorage.getItem("privacyState") == '0') {
				buttons[i].innerHTML = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_SHOW_MAP');
				privacyfields[i].innerHTML = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYTEXT_SHOW_MAP');
			} else {
				buttons[i].innerHTML = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_HIDE_MAP');
				privacyfields[i].innerHTML = Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYTEXT_HIDE_MAP');
			}
			buttons[i].onclick = function () {
				if (localStorage.getItem("privacyState") == '0') {
					document.getElementById('map' + moduleId).style.display = "block";
					localStorage.setItem("privacyState", '1');
				} else {
					localStorage.setItem("privacyState", '0');
				}
				window.location.reload();
			}
		}

		if (addprivacybox == '1' && (localStorage.getItem("privacyState") == '0')) {
			document.getElementById('map' + moduleId).style.display = "none";
			return;
		}

		// Add Scrollwheele Listener, so that you can activate it on mouse click
		if (scrollwheelzoom == "0") {
			window['mymap' + moduleId].on('click', function () {
				if (window['mymap' + moduleId].scrollWheelZoom.enabled()) {
					window['mymap' + moduleId].scrollWheelZoom.disable();
				} else {
					window['mymap' + moduleId].scrollWheelZoom.enable();
				}
			});
		}

		// Baselayer
		var nowarp = "noWrap: false, ";
		if (noWorldWarp == "1") {
			nowarp = "noWrap: true, ";
		}
		var detectRetina = "detectRetina: false, ";
		if (detectRetina == "1") {
			detectRetina = "detectRetina: true, ";
		}

		// Base layer url
		var tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a>'
		});
		if (baselayer == 'mapbox') {
			tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
				attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>',
				tileSize: 512,
				maxZoom: 18,
				zoomOffset: -1,
				id: mapboxmaptype,
				accessToken: mapboxkey
			});
		}
		if (baselayer == 'esri_worldImagery') {
			tileLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
				maxZoom: 16,
				attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
				id: ''
			});
		}
		if (baselayer == 'mapnikde') {
			tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: '&copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a>'
			});
		}
		if (baselayer == 'stamen') {
			tileLayer = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/' + stamenmaptype + '/{z}/{x}/{y}.png', {
				subdomains: 'abcd', minZoom: 1, maxZoom: 16,
				attribution: 'Map data &copy; <a href=\"https://openstreetmap.org\">OpenStreetMap</a> contributors, ' +
					'<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY 3.0</a>, ' +
					'Imagery &copy; <a href=\"http://stamen.com\">Stamen Design</a>',
				id: ''
			});
		}
		if (baselayer == 'opentopomap') {
			tileLayer = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
				maxZoom: 16,
				attribution: '<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY 3.0</a>, ' +
					'Imagery &copy; <a href=\"http://viewfinderpanoramas.org\">SRTM</a>',
				id: ''
			});
		}
		if (baselayer == 'humanitarian') {
			tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
				maxZoom: 20,
				attribution: '<a href=\"https://creativecommons.org/licenses/by-sa/3.0/\">CC-BY 3.0</a>, ' +
					'Imagery &copy; <a href=\"https://hotosm.org\">Humanitarian OpenStreetMap Team</a>',
				id: ''
			});
		}
		if (baselayer == 'custom') {
			//tileLayer = L.tileLayer(customBaselayerURL, {customBaselayer});
		}
		if (baselayer == 'google') {
			tileLayer = L.gridLayer.googleMutant({
				type: googlemapstype,
				attribution: astrid
			});
		}
		if (baselayer == 'thunderforest') {
			tileLayer = L.tileLayer('https://{s}.tile.thunderforest.com/' + thunderforestmaptype + '/{z}/{x}/{y}.png?apikey={apikey}', {
				maxZoom: 22,
				apikey: thunderforestkey,
				attribution: '&copy; <a href=\"http://www.thunderforest.com/\">Thunderforest</a>, &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a>'
			});
		}

		tileLayer.addTo(window['mymap' + moduleId]);

		if (layertree == '1') {
			var tileLayer2 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
				maxZoom: 16,
				attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
				id: ''
			});
			var baseMaps = {
				"Map": tileLayer,
				"Satellit": tileLayer2
			};
			L.control.layers(baseMaps).addTo(window['mymap' + moduleId]);
		}

		// SCALE CONTROL
		if ((scale) !== '0') {
			let aggpxScale = L.control.scale();

			if (scaleMetric !== '1') {
				aggpxScale.options.metric = false;
			}

			if (scaleImperial !== '1') {
				aggpxScale.options.imperial = false;
			}

			aggpxScale.addTo(window['mymap' + moduleId]);

		}

		// Add Geocoder
		if (showgeocoder == "1") {
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
		if (useesri == "1") {
			if (dataEsrigeocoderopengetaddress) {
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


			if (esrireversegeocoding == 'true') {
				var r = L.marker();
				window['mymap' + moduleId].on('click', function (e) {
					L.esri.Geocoding.reverseGeocode()
						.latlng(e.latlng)
						.run(function (error, result, response) {
							r = L.marker(result.latlng).addTo(window['mymap' + moduleId]).bindPopup(result.address.Match_addr).openPopup();
						});
				});
			}

			if (showgeocoderesri == '1') {
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
		if (showrouting_simple == '1') {
			L.leafletControlRoutingtoaddress({
				position: routesimpleposition,
				router: routesimplerouter,
				token: routesimplerouterkey,
				placeholder: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_PLACEHOLDER'),
				errormessage: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_ERRORMESSAGE'),
				distance: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_DISTANCE'),
				duration: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_DURATION'),
				kilometer: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_KILOMETER'),
				stunden: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_STUNDEN'),
				target: routesimpletarget,
				addresserror: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_ADDRESSERROR'),
				requesterror: Joomla.JText._('MOD_AGOSM_ROUTING_SIMPLE_TEXT_REQUESTERROR')
			}).addTo(window['mymap' + moduleId]);

		}

		// Add Routing Mapbox
		if (showrouting == '1') {
			function button(label, container) {
				var btn = L.DomUtil.create('button', '', container);
				btn.setAttribute('type', 'button');
				btn.innerHTML = label;
				return btn;
			}

			if (routingstart == '0,0' || routingend == '0,0') {
				var control = L.Routing.control({
					geocoder: L.Control.Geocoder.nominatim({}),
					collapsible: true,
					show: false,
					autoRoute: true,
					router: L.Routing.mapbox(mapboxkeyRouting,
						{
							profile: routingprofile,
							language: routinglanguage,
						}),
					units: routingmetric,
					reverseWaypoints: true,
					routeWhileDragging: routewhiledragging
				}).addTo(window['mymap' + moduleId]);
			} else {
				var control = L.Routing.control({
					geocoder: L.Control.Geocoder.nominatim({}),
					waypoints: [
						L.latLng(routingstart),
						L.latLng(routingend)
					],
					collapsible: true,
					show: false,
					autoRoute: true,
					router: L.Routing.mapbox(mapboxkeyRouting,
						{
							profile: routingprofile,
							language: routinglanguage,
						}),
					units: routingmetric,
					reverseWaypoints: true,
					routeWhileDragging: routewhiledragging
				}).addTo(window['mymap' + moduleId]);
			}

			(window['mymap' + moduleId]).on('click', function (e) {
				var container = L.DomUtil.create('div');
				var startBtn = button('Start', container);
				var destBtn = button('End', container);
				L.DomEvent.on(startBtn, 'click', function () {
					control.spliceWaypoints(0, 1, e.latlng);
					(window['mymap' + moduleId]).closePopup();
				});
				L.DomEvent.on(destBtn, 'click', function () {
					control.spliceWaypoints(control.getWaypoints().length - 1, 1, e.latlng);
					(window['mymap' + moduleId]).closePopup();
				});
				L.popup().setContent(container).setLatLng(e.latlng).openOn(window['mymap' + moduleId]);
			});
		}

		// Special Pins
		if (showpin == '1') {
			var index = 0;
			for (var specialpin in specialpins) {
				// skip loop if the property is from prototype
				if (!specialpins.hasOwnProperty(specialpin))
					continue;

				var obj = specialpins[specialpin];
				let tempMarker = L.marker(obj.latlonpin.split(",", 3));

				if (obj.pin == "2" && obj.customPinPath != "") {
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

				if (obj.pin == "3") {
					var AwesomeIcon = new L.AwesomeMarkers.icon(
						{
							icon: obj.awesomeicon_icon,
							markerColor: obj.awesomeicon_markercolor,
							iconColor: obj.awesomeicon_iconcolor,
							prefix: 'fa',
							spin: (obj.awesomeicon_spin == "true"),
							extraClasses: obj.awesomeicon_extraclasses,
						})
					tempMarker.setIcon(AwesomeIcon);
				}

				tempMarker.addTo(window['mymap' + moduleId]);

				if (obj.popup == "1") {
					tempMarker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images'));
				}

				if (obj.popup == "2") {
					tempMarker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images')).openPopup();
				}

				if (obj.popup == "3") {
					tempMarker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images'));
					tempMarker.on('mouseover', function (e) {
						this.openPopup();
					});
					tempMarker.on('mouseout', function (e) {
						this.closePopup();
					});
				}

				index++;
				var clickmarkerlista = document.querySelector('.agmarkerlista_specialpin' + index);

				if (clickmarkerlista) {
					clickmarkerlista.addEventListener('click', function () {
						window['mymap' + moduleId].setView(tempMarker.getLatLng(), Math.max(15, disableClusteringAtZoom));
						tempMarker.openPopup();
					});
				}
				window['mymap' + moduleId].on("moveend", function (event) {
					var bounds = event.target.getBounds();
					var indexhidemove = 0;
					for (var specialpin in specialpins) {
						indexhidemove++;
						if (document.querySelector('.agmarkerlistli_specialpin' + indexhidemove)) {
							var cords = L.latLng(specialpins[specialpin].latlonpin.split(",", 3));
							if (!bounds.contains(cords)) {
								document.querySelector('.agmarkerlistli_specialpin' + indexhidemove).hidden = true;
							} else {
								document.querySelector('.agmarkerlistli_specialpin' + indexhidemove).hidden = false;
							}
						}
					}
				});
				window['mymap' + moduleId].on("zoomend", function (event) {
					var bounds = event.target.getBounds();
					var indexhidezoom = 0;
					for (var specialpin in specialpins) {
						indexhidezoom++;
						if (document.querySelector('.agmarkerlistli_specialpin' + indexhidezoom)) {
							var cords = L.latLng(specialpins[specialpin].latlonpin.split(",", 3));
							if (!bounds.contains(cords)) {
								document.querySelector('.agmarkerlistli_specialpin' + indexhidezoom).hidden = true;
							} else {
								document.querySelector('.agmarkerlistli_specialpin' + indexhidezoom).hidden = false;
							}
						}
					}
				});


			}
		}

		// Show Pins from component
		if (showcomponentpin == '1') {
			var clustermarkers = L.markerClusterGroup({
				maxClusterRadius: 80, //A cluster will cover at most this many pixels from its center
				iconCreateFunction: null,
				clusterPane: L.Marker.prototype.options.pane,

				spiderfyOnMaxZoom: true,
				showCoverageOnHover: true,
				zoomToBoundsOnClick: true,
				singleMarkerMode: false,

				disableClusteringAtZoom: disableClusteringAtZoom,

				// Setting this to false prevents the removal of any clusters outside of the viewpoint, which
				// is the default behaviour for performance reasons.
				removeOutsideVisibleBounds: true,

				// Set to false to disable all animations (zoom and spiderfy).
				// If false, option animateAddingMarkers below has no effect.
				// If L.DomUtil.TRANSITION is falsy, this option has no effect.
				animate: true,

				//Whether to animate adding markers after adding the MarkerClusterGroup to the map
				// If you are adding individual markers set to true, if adding bulk markers leave false for massive performance gains.
				animateAddingMarkers: false,

				//Increase to increase the distance away that spiderfied markers appear from the center
				spiderfyDistanceMultiplier: 1,

				// Make it possible to specify a polyline options on a spider leg
				spiderLegPolylineOptions: { weight: 1.5, color: '#222', opacity: 0.5 },

				// When bulk adding layers, adds markers in chunks. Means addLayers may not add all the layers in the call, others will be loaded during setTimeouts
				chunkedLoading: false,
				chunkInterval: 200, // process markers for a maximum of ~ n milliseconds (then trigger the chunkProgress callback)
				chunkDelay: 50, // at the end of each interval, give n milliseconds back to system/browser
				chunkProgress: null, // progress callback: function(processed, total, elapsed) (e.g. for a progress indicator)

				//Options to pass to the L.Polygon constructor
				polygonOptions: {}
			});

			for (var specialcomponentpin in specialcomponentpins) {
				// skip loop if the property is from prototype
				if (!specialcomponentpins.hasOwnProperty(specialcomponentpin))
					continue;

				var obj = specialcomponentpins[specialcomponentpin];
				let tempMarker = L.marker(obj.coordinates.split(",", 3));

				if (obj.showdefaultpin == "2" && obj.customPinPath != "") {

					if (obj.customPinShadowPath != "") {
						var LeafIcon = L.Icon.extend({
							options: {
								iconUrl: uriroot + obj.customPinPath,
								shadowUrl: uriroot + obj.customPinShadowPath,
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
					} else {
						var LeafIcon = L.Icon.extend({
							options: {
								iconUrl: uriroot + obj.customPinPath,
								iconSize: obj.customPinSize.split(",", 3).map(function (e) {
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
					}
					tempMarker.setIcon(new LeafIcon());
				}

				if (obj.showdefaultpin == "3") {
					var AwesomeIcon = new L.AwesomeMarkers.icon(
						{
							icon: obj.awesomeicon_icon,
							markerColor: obj.awesomeicon_markercolor,
							iconColor: obj.awesomeicon_iconcolor,
							prefix: 'fa',
							spin: (obj.awesomeicon_spin == "true"),
							extraClasses: obj.awesomeicon_extraclasses,
						})
					tempMarker.setIcon(AwesomeIcon);
				}

				tempMarker.addTo(clustermarkers);

				if (obj.showpopup == "1") {
					tempMarker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images'));
				}

				if (obj.showpopup == "2") {
					tempMarker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images')).openPopup();
				}

				if (obj.showpopup == "3") {
					tempMarker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images'));
					tempMarker.on('mouseover', function (e) {
						this.openPopup();
					});
					tempMarker.on('mouseout', function (e) {
						this.closePopup();
					});
				}

				if (obj.showpopup == "4") {
					let url = uriroot + "index.php?option=com_agosms&view=" + layoutfordisplaylinkinmarker + "&id=" + obj.id;
					let title = obj.name;
					let popuptext = "<a href=' " + url + " '> " + title + " </a>";
					tempMarker.bindPopup(popuptext);
				}

				var clickgmarkerlista = document.querySelector('.agmarkerlista_component' + obj.id);

				if (clickgmarkerlista) {
					clickgmarkerlista.addEventListener('click', function () {
						window['mymap' + moduleId].setView(tempMarker.getLatLng(), Math.max(15, disableClusteringAtZoom));
						tempMarker.openPopup();
					});
				}
				window['mymap' + moduleId].on("moveend", function (event) {
					var bounds = event.target.getBounds();
					for (var specialcomponentpin in specialcomponentpins) {
						if (specialcomponentpins[specialcomponentpin].id) {
							var cords = L.latLng(specialcomponentpins[specialcomponentpin].coordinates.split(",", 3));
							if (document.querySelector('.agmarkerlistli_component' + specialcomponentpins[specialcomponentpin].id)) {
								if (!bounds.contains(cords)) {
									document.querySelector('.agmarkerlistli_component' + specialcomponentpins[specialcomponentpin].id).hidden = true;
								} else {
									document.querySelector('.agmarkerlistli_component' + specialcomponentpins[specialcomponentpin].id).hidden = false;
								}
							}
						}
					}
				});
				window['mymap' + moduleId].on("zoomend", function (event) {
					var bounds = event.target.getBounds();
					for (var specialcomponentpin in specialcomponentpins) {
						if (specialcomponentpins[specialcomponentpin].id) {
							var cords = L.latLng(specialcomponentpins[specialcomponentpin].coordinates.split(",", 3));
							if (document.querySelector('.agmarkerlistli_component' + specialcomponentpins[specialcomponentpin].id)) {
								if (!bounds.contains(cords)) {
									document.querySelector('.agmarkerlistli_component' + specialcomponentpins[specialcomponentpin].id).hidden = true;
								} else {
									document.querySelector('.agmarkerlistli_component' + specialcomponentpins[specialcomponentpin].id).hidden = false;
								}
							}
						}
					}
				});

			}

			if (JSON.parse(sessionStorage.getItem('mapState')) && savestate == "1") {
				window['mymap' + moduleId].fitBounds(mapState.bounds);
			} else {
				window['mymap' + moduleId].fitBounds(clustermarkers.getBounds());
			}
			clustermarkers.addTo(window['mymap' + moduleId]);

		}

		// One Pin from component
		if (showcomponentpinone == '1') {
			var clustermarkers = L.markerClusterGroup({
				maxClusterRadius: 80, //A cluster will cover at most this many pixels from its center
				iconCreateFunction: null,
				clusterPane: L.Marker.prototype.options.pane,

				spiderfyOnMaxZoom: true,
				showCoverageOnHover: true,
				zoomToBoundsOnClick: true,
				singleMarkerMode: false,

				disableClusteringAtZoom: disableClusteringAtZoom,

				// Setting this to false prevents the removal of any clusters outside of the viewpoint, which
				// is the default behaviour for performance reasons.
				removeOutsideVisibleBounds: true,

				// Set to false to disable all animations (zoom and spiderfy).
				// If false, option animateAddingMarkers below has no effect.
				// If L.DomUtil.TRANSITION is falsy, this option has no effect.
				animate: true,

				//Whether to animate adding markers after adding the MarkerClusterGroup to the map
				// If you are adding individual markers set to true, if adding bulk markers leave false for massive performance gains.
				animateAddingMarkers: false,

				//Increase to increase the distance away that spiderfied markers appear from the center
				spiderfyDistanceMultiplier: 1,

				// Make it possible to specify a polyline options on a spider leg
				spiderLegPolylineOptions: { weight: 1.5, color: '#222', opacity: 0.5 },

				// When bulk adding layers, adds markers in chunks. Means addLayers may not add all the layers in the call, others will be loaded during setTimeouts
				chunkedLoading: false,
				chunkInterval: 200, // process markers for a maximum of ~ n milliseconds (then trigger the chunkProgress callback)
				chunkDelay: 50, // at the end of each interval, give n milliseconds back to system/browser
				chunkProgress: null, // progress callback: function(processed, total, elapsed) (e.g. for a progress indicator)

				//Options to pass to the L.Polygon constructor
				polygonOptions: {}
			});

			var obj = specialcomponentpinone;
			let tempMarker = L.marker(obj.coordinates.split(",", 3));

			if (obj.showdefaultpin == "2" && obj.customPinPath != "") {

				if (obj.customPinShadowPath != "") {
					var LeafIcon = L.Icon.extend({
						options: {
							iconUrl: uriroot + obj.customPinPath,
							shadowUrl: uriroot + obj.customPinShadowPath,
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
				} else {
					var LeafIcon = L.Icon.extend({
						options: {
							iconUrl: uriroot + obj.customPinPath,
							iconSize: obj.customPinSize.split(",", 3).map(function (e) {
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
				}
				tempMarker.setIcon(new LeafIcon());
			}

			if (obj.showdefaultpin == "3") {
				var AwesomeIcon = new L.AwesomeMarkers.icon(
					{
						icon: obj.awesomeicon_icon,
						markerColor: obj.awesomeicon_markercolor,
						iconColor: obj.awesomeicon_iconcolor,
						prefix: 'fa',
						spin: (obj.awesomeicon_spin == "true"),
						extraClasses: obj.awesomeicon_extraclasses,
					})
				tempMarker.setIcon(AwesomeIcon);
			}

			tempMarker.addTo(clustermarkers);

			if (obj.showpopup == "1") {
				tempMarker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images'));
			}

			if (obj.showpopup == "2") {
				tempMarker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images')).openPopup();
			}

			if (obj.showpopup == "3") {
				tempMarker.bindPopup(obj.popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images'));
				tempMarker.on('mouseover', function (e) {
					this.openPopup();
				});
				tempMarker.on('mouseout', function (e) {
					this.closePopup();
				});
			}


			if (JSON.parse(sessionStorage.getItem('mapState')) && savestate == "1") {
				window['mymap' + moduleId].fitBounds(mapState.bounds);
			}
			clustermarkers.addTo(window['mymap' + moduleId]);
		}



		if (!String.prototype.startsWith) {
			String.prototype.startsWith = function (searchString, position) {
				position = position || 0;
				return this.substr(position, searchString.length) == searchString;
			};
		}
		// Show Pins from customfield
		if (showcustomfieldpin == '1') {
			var clustermarkers = L.markerClusterGroup({
				maxClusterRadius: 80, //A cluster will cover at most this many pixels from its center
				iconCreateFunction: null,
				clusterPane: L.Marker.prototype.options.pane,

				spiderfyOnMaxZoom: true,
				showCoverageOnHover: true,
				zoomToBoundsOnClick: true,
				singleMarkerMode: false,

				disableClusteringAtZoom: disableClusteringAtZoom,

				// Setting this to false prevents the removal of any clusters outside of the viewpoint, which
				// is the default behaviour for performance reasons.
				removeOutsideVisibleBounds: true,

				// Set to false to disable all animations (zoom and spiderfy).
				// If false, option animateAddingMarkers below has no effect.
				// If L.DomUtil.TRANSITION is falsy, this option has no effect.
				animate: true,

				//Whether to animate adding markers after adding the MarkerClusterGroup to the map
				// If you are adding individual markers set to true, if adding bulk markers leave false for massive performance gains.
				animateAddingMarkers: false,

				//Increase to increase the distance away that spiderfied markers appear from the center
				spiderfyDistanceMultiplier: 1,

				// Make it possible to specify a polyline options on a spider leg
				spiderLegPolylineOptions: { weight: 1.5, color: '#222', opacity: 0.5 },

				// When bulk adding layers, adds markers in chunks. Means addLayers may not add all the layers in the call, others will be loaded during setTimeouts
				chunkedLoading: false,
				chunkInterval: 200, // process markers for a maximum of ~ n milliseconds (then trigger the chunkProgress callback)
				chunkDelay: 50, // at the end of each interval, give n milliseconds back to system/browser
				chunkProgress: null, // progress callback: function(processed, total, elapsed) (e.g. for a progress indicator)

				//Options to pass to the L.Polygon constructor
				polygonOptions: {}
			});

			for (var specialcustomfieldpin in specialcustomfieldpins) {
				// skip loop if the property is from prototype
				if (!specialcustomfieldpins.hasOwnProperty(specialcustomfieldpin)) {
					continue;
				}

				var objcf = specialcustomfieldpins[specialcustomfieldpin];

				if (Object.keys(objcf).length == 0) {
					continue;
				}

				let tempMarkercf = null;

				if (objcf.cords && !objcf.cords.startsWith(",,") && !objcf.cords.startsWith("0,0,")) {
					var values = objcf.cords.split(",");
					tempMarkercf = L.marker(objcf.cords.split(",").slice(0, 2));

					if (values.length > 4 && objcf.type !== 'agosmsaddressmarker') {
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

					if (objcf.type == 'agosmsaddressmarker' && objcf.iconcolor && objcf.markercolor && objcf.icon) {
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

					let url = "index.php?option=com_content&view=article&id=" + objcf.id;
					let title = objcf.title;

					if (values.length > 5 && values[5].trim() != '') {
						title = values[5];
					}

					let popuptext = "<a href=' " + url + " '> " + title + " </a>";

					if (objcf.specialpopuptext) {
						popuptext = objcf.specialpopuptext;
					}
					
					tempMarkercf.bindPopup(popuptext.replace(/<img src="images/g, '<img src="' + uriroot + 'images'));

					var clickgmarkerlista = document.querySelector('.agmarkerlista' + objcf.id);

					if (clickgmarkerlista) {
						clickgmarkerlista.addEventListener('click', function () {
							window['mymap' + moduleId].setView(tempMarkercf.getLatLng(), Math.max(15, disableClusteringAtZoom));
							tempMarkercf.openPopup();
						});
					}
					window['mymap' + moduleId].on("moveend", function (event) {
						var bounds = event.target.getBounds();
						for (var specialcustomfieldpin in specialcustomfieldpins) {
							if (specialcustomfieldpins[specialcustomfieldpin].id) {
								var cords = L.latLng(specialcustomfieldpins[specialcustomfieldpin].cords.split(",").slice(0, 2));
								if (document.querySelector('.agmarkerlistli' + specialcustomfieldpins[specialcustomfieldpin].id)) {
									if (!bounds.contains(cords)) {
										document.querySelector('.agmarkerlistli' + specialcustomfieldpins[specialcustomfieldpin].id).hidden = true;
									} else {
										document.querySelector('.agmarkerlistli' + specialcustomfieldpins[specialcustomfieldpin].id).hidden = false;
									}
								}
							}
						}
					});

					window['mymap' + moduleId].on("zoomend", function (event) {
						var bounds = event.target.getBounds();
						for (var specialcustomfieldpin in specialcustomfieldpins) {
							if (specialcustomfieldpins[specialcustomfieldpin].id) {
								var cords = L.latLng(specialcustomfieldpins[specialcustomfieldpin].cords.split(",").slice(0, 2));
								if (document.querySelector('.agmarkerlistli' + specialcustomfieldpins[specialcustomfieldpin].id)) {
									if (!bounds.contains(cords)) {
										document.querySelector('.agmarkerlistli' + specialcustomfieldpins[specialcustomfieldpin].id).hidden = true;
									} else {
										document.querySelector('.agmarkerlistli' + specialcustomfieldpins[specialcustomfieldpin].id).hidden = false;
									}
								}
							}
						}
					});

					tempMarkercf.addTo(clustermarkers);
				}
			}
			if (JSON.parse(sessionStorage.getItem('mapState')) && savestate == "1") {
				window['mymap' + moduleId].fitBounds(mapState.bounds);
			} else {
				window['mymap' + moduleId].fitBounds(clustermarkers.getBounds());
			}
			clustermarkers.addTo(window['mymap' + moduleId]);
		}

		// Show locate
		if (locate == '1') {
			var lc = L.control.locate({
				position: 'topright',
				initialZoomLevel: 17,
				strings: {
					title: "Wo bin ich?",
				}
			}).addTo(window['mymap' + moduleId]);
		}

		// Add Fullscreen
		if (fullscreen == "1") {
			window['mymap' + moduleId].addControl(new L.Control.Fullscreen());
		}

		// Show mouseposition
		if (mouseposition == '1') {
			L.control.mousePosition().addTo(window['mymap' + moduleId]);
		}

		// Show GeoJson
		if (geojson == '1') {



			// Get Style Start
			function areaStyle(feature) {
				return {
					fillColor: getAreaFill(feature),
					color: getAreaStroke(feature),
					weight: getAreaStrokeWidth(feature),
					opacity: getAreaStrokeOpacity(feature),
					fillOpacity: getAreaFillOpacity(feature)
				}
			};
			function getAreaFill(feature) {
				if (feature.properties.fill) {
					return feature.properties.fill;
				} else {
					return 'green';
				}
			};
			function getAreaStroke(feature) {
				if (feature.properties.stroke) {
					return feature.properties.stroke;
				} else {
					return 'black';
				}
			};
			function getAreaStrokeWidth(feature) {
				if (feature.properties["stroke-width"]) {
					return feature.properties["stroke-width"];
				} else {
					return 2;
				}
			};
			function getAreaStrokeOpacity(feature) {
				if (feature.properties["stroke-opacity"]) {
					return feature.properties["stroke-opacity"];
				} else {
					return 1;
				}
			};
			function getAreaFillOpacity(feature) {
				if (feature.properties["fill-opacity"]) {
					return feature.properties["fill-opacity"];
				} else {
					return 0.4;
				}
			};
			// Get Style End

			if (geojsonfile == '0') {
				try {
					if (geojsonTextRaw == '{}') {
						console.log('No GeoJson Object');
					} else {
						L.geoJSON(geojsonText, { style: areaStyle }).addTo(window['mymap' + moduleId]);
					}
				}
				catch (e) {
					console.log('GeoJsonError: ' + geojsonTextRaw);
				}
			}

			if (geojsonfile == '1') {
				geojsonTextRaw = '{}';

				async function postData() {
					const response = await fetch(uriroot + '/images/' + geojsonfilename);
					return response.json();
				}
				geojsonFileContent = postData();

				geojsonFileContent.then((response) => {
					geojsonTextRaw = response;

					try {
						if (geojsonTextRaw == '{}') {
							console.log('No GeoJson Object');
						} else {
							L.geoJSON(geojsonTextRaw, { style: areaStyle }).addTo(window['mymap' + moduleId]);
						}
					}
					catch (e) {
						console.log('GeoJsonError: ' + geojsonTextRaw);
					}
				});
			}
		}

		// Save the state
		if (savestate == "1") {
			window['mymap' + moduleId].on('zoom', function (ev) {
				var center = window['mymap' + moduleId].getCenter();
				var bounds = window['mymap' + moduleId].getBounds();
				var mapState = {
					zoom: window['mymap' + moduleId].getZoom(),
					center: [center.lat, center.lng],
					bounds: [
						[bounds.getNorth(), bounds.getWest()],
						[bounds.getSouth(), bounds.getEast()]
					],
				};
				sessionStorage.setItem('mapState', JSON.stringify(mapState));
			});
		}

		if (savestate == "1") {
			window['mymap' + moduleId].on('move', function (ev) {
				var center = window['mymap' + moduleId].getCenter();
				var bounds = window['mymap' + moduleId].getBounds();
				var mapState = {
					zoom: window['mymap' + moduleId].getZoom(),
					center: [center.lat, center.lng],
					bounds: [
						[bounds.getNorth(), bounds.getWest()],
						[bounds.getSouth(), bounds.getEast()]
					],
				};
				sessionStorage.setItem('mapState', JSON.stringify(mapState));
			});
		}
	})
}, false);

