document.addEventListener('DOMContentLoaded', function () {

	var agosmsaddressmarkersurroundingdiv = document.querySelectorAll('.agomsaddressfindersurroundingdiv');

	// For all fields [start]
	[].forEach.call(agosmsaddressmarkersurroundingdiv, function (element) {
		// Set the fields 
		// Todo Error Handling
		setTimeout(function () {
			var buttons = element.getElementsByTagName('button');
			var addressbutton = buttons[0];
			var mapid = addressbutton.getAttribute('data-mapid');

			var inputs = element.getElementsByTagName('input');
			var lat = inputs[0];
			var lon = inputs[1];
			var address = inputs[2];
			var hiddenfield = inputs[3];

			// Write the value to the fields
			if (hiddenfield.value.split(',').length !== 2) {
				hiddenfield.value = '50.27,7.26,';
			}
			;
			var hf = hiddenfield.value.split(',');

			lat.value = hf[0];

			lon.value = hf[1];

			// Set the map
			var map = L.map(mapid).setView([lat.value, lon.value], 9);
			L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);
			var marker = new L.marker([lat.value, lon.value], {draggable: 'true'}).addTo(map);
			marker.on('dragend', function (event) {
				var marker = event.target;
				var position = marker.getLatLng();
				marker.setLatLng(new L.LatLng(position.lat, position.lng), {draggable: 'true'});
				map.panTo(new L.LatLng(position.lat, position.lng));
				lat.value = position.lat;
				lon.value = position.lng;
				changeValue();
			});

			// Write the value to the hidden field if lat or lon is changed
			lat.onchange = function () {
				changeValue();
			};

			lon.onchange = function () {
				changeValue();
			};

			addressbutton.onclick = function () {
				var addressstring = address.value;
				var cords = function (results, suggest) {
					if (!suggest && results.length === 1) {
						lat.value = results[0].lat;
						lon.value = results[0].lon;
						lon.onchange();
						marker.setLatLng([results[0].lat, results[0].lon]);
						map.panTo(new L.LatLng(results[0].lat, results[0].lon));
					} else if (results.length > 0) {
						// Limit is fix set to 1 up to now
					} else {
						Joomla.renderMessages({"error": [Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR') + addressstring + ' (Nominatim)']});
					}
				}
				var params = {
					q: addressstring,
					limit: 1,
					format: 'json',
					addressdetails: 1
				};
				getJSON("https://nominatim.openstreetmap.org/", params, cords);
			}

			function changeValue() {
				hiddenfield.value = lat.value
					+ ',' + lon.value;
			}
		}); // For all fields [end]
	}, 1000);
}, false);

function getJSON(url, params, callback) {
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function () {
		if (xmlHttp.readyState !== 4) {
			return;
		}
		if (xmlHttp.status !== 200 && xmlHttp.status !== 304) {
			callback('');
			return;
		}
		callback(xmlHttp.response);
	};
	xmlHttp.open('GET', url + getParamString(params), true);
	xmlHttp.responseType = 'json';
	xmlHttp.setRequestHeader('Accept', 'application/json');
	xmlHttp.send(null);
}

function getParamString(obj, existingUrl, uppercase) {
	var params = [];
	for (var i in obj) {
		var key = encodeURIComponent(uppercase ? i.toUpperCase() : i);
		var value = obj[i];
		if (!L.Util.isArray(value)) {
			params.push(key + '=' + encodeURIComponent(value));
		} else {
			for (var j = 0; j < value.length; j++) {
				params.push(key + '=' + encodeURIComponent(value[j]));
			}
		}
	}
	return (!existingUrl || existingUrl.indexOf('?') === -1 ? '?' : '&') + params.join('&');
}
