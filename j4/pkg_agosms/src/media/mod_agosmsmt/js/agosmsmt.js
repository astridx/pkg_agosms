document.addEventListener('DOMContentLoaded', function () {

	var leafletmapsMod = document.querySelectorAll('.leafletmapMod');

	[].forEach.call(leafletmapsMod, function (element) {

		var mtmarkers = element.getAttribute('data-mtmarkers');
		var arrCats = element.getAttribute('data-arrCats');

		var show_radiusZoom = element.getAttribute('data-show-radiusZoom');
		var showfullscreenlink = element.getAttribute('data-showfullscreenlink');
		var fullscreenlink = element.getAttribute('data-fullscreenlink');
		var moveForm = element.getAttribute('data-move-form');

		var useOwnMarkerImage = element.getAttribute('data-use-own-marker-image');

		var savestate = element.getAttribute('data-savestate');
		var fullscreen = element.getAttribute('data-fullscreen');
		var locate = element.getAttribute('data-locate');
		var mouseposition = element.getAttribute('data-mouseposition');

		var uriroot = element.getAttribute('data-uriroot');
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
		var scale = element.getAttribute('data-scale');
		var scaleMetric = element.getAttribute('data-scale-metric');
		var scaleImperial = element.getAttribute('data-scale-imperial');

		var showrouting_simple = element.getAttribute('data-showrouting-simple');

		var addprivacybox = element.getAttribute('data-addprivacybox');
		var unique = element.getAttribute('data-unique');
		var buttons = document.getElementsByClassName('b' + unique);
		var privacyfields = document.getElementsByClassName('p' + unique);

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
		var tileLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager_labels_under/{z}/{x}/{y}{r}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
			subdomains: 'abcd',
			maxZoom: 20
		});
		var tileLayer2 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
			maxZoom: 16,
			attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
			id: ''
		});

		if (baselayer == 'carto') {
		}
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

		//
		var baseMaps = {
			"Karte": tileLayer,
			"Sattelit": tileLayer2
		};
		L.control.layers(baseMaps).addTo(window['mymap' + moduleId]);






		/* MARKER */
		var clusterOptions = {
			spiderfyOnMaxZoom: false,
			showCoverageOnHover: false,
			zoomToBoundsOnClick: true,
		};
		var mtMarkerCluster = L.markerClusterGroup.layerSupport(clusterOptions);

		let catOverlays = [];
		let overlaysTreeChildren = [];
		JSON.parse(arrCats).forEach(function (arrCat) {
			let uri;
			catOverlays[arrCat] = L.layerGroup([]);

			L.geoJSON(JSON.parse(mtmarkers),

				{
					onEachFeature: function (feature, layer) {
						if (JSON.parse(feature.properties.cat_name) === arrCat) {
							if (useOwnMarkerImage == 0) {
								uri = uriroot + "/media/mod_agosmsmt/leaflet/images/marker-icon.png";
								feature.properties.iconUrl = uri;
							}
							else if (!feature.properties.iconUrl) {
								uri = uriroot + "/images/marker/" + JSON.parse(feature.properties.cat_name) + ".png";
								feature.properties.iconUrl = uri;
							}

							L.marker(
								[feature.geometry.coordinates[1], feature.geometry.coordinates[0]],
								{
									icon: L.icon({
										iconUrl: feature.properties.iconUrl,
									}),
								}
							)
								.bindPopup(
									L.popup({
										className: 'popup-fixed',
										autoPan: false,
									}).setContent(feature.properties.link_desc)
								)
								.addTo(catOverlays[arrCat]);

						}
					},
				}
			);

			mtMarkerCluster.checkIn(catOverlays[arrCat]);
			catOverlays[arrCat].addTo(window['mymap' + moduleId]);

			var overlaysTreeChild = {};
			overlaysTreeChild.label = "<img src='" + uri + "'/>" + arrCat;
			overlaysTreeChild.layer = catOverlays[arrCat];
			overlaysTreeChildren.push(overlaysTreeChild);

		}) //	orEach((arrCats)
		mtMarkerCluster.addTo(window['mymap' + moduleId]);

		L.control.layers.tree(null, overlaysTreeChildren, {
			namedToggle: true,
			selectorBack: false,
			closedSymbol: '',
			openedSymbol: '',
			collapseAll: '',
			expandAll: '',
			collapsed: false,
			position: 'bottomright',
		}).addTo(window['mymap' + moduleId]);

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



		// Show RadiusZoom
		if (show_radiusZoom == '1') {
			L.leafletControlRadiuszoom({
				position: "bottomright",
			}).addTo(window['mymap' + moduleId]);
		}

		// Show locate
		if (locate == '1') {
			var lc = L.control.locate({
				position: 'topleft',
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

		// Add Fullscreenlink
		if (showfullscreenlink == "1") {
			window['mymap' + moduleId].addControl(new L.Control.Fullscreenlink(
				{
					position: "topleft",
					title: "Zu größerer Ansicht wechseln",
					websitelink: fullscreenlink
				}
			));
		}

		// Show mouseposition
		if (mouseposition == '1') {
			L.control.mousePosition().addTo(window['mymap' + moduleId]);
		}




		if (moveForm == "1") {

			// Suche in rechte Seitenleiste verschieben
			var thismap = document.querySelector('#map' + moduleId);
			var agosmsMtFormLayer = document.getElementById("agosms-mt-form-layer" + moduleId);
			var agosmsMtFormRadius = document.getElementById("agosms-mt-form-radius" + moduleId);
			var clayer = thismap.querySelector(".leaflet-control-layers-expanded");
			var cradius = thismap.querySelector(".leaflet-control-radiuszoom");
			setParent(clayer, agosmsMtFormLayer);
			setParent(cradius, agosmsMtFormRadius);
			function setParent(el, newParent) {
				if (el)
					newParent.appendChild(el);
			}
		}
	})
}, false);
