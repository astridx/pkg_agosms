document.addEventListener('DOMContentLoaded', function () {

	var agosmsaddressmarkersurroundingdiv = document.querySelectorAll('.agomsaddressfindersurroundingdiv');

	// For all fields [start]
	[].forEach.call(agosmsaddressmarkersurroundingdiv, function (element) {
		// Set the fields 
		// Todo Error Handling
		var inputs = element.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];
		var address = inputs[2];

		// Set the map
		var map = L.map('mapid').setView([50.27264, 7.26469], 13);
		L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);
		
		// Write the value to the hidden field if lat or lon is changed
		lat.onchange = function() {
		changeValue();
		};

		lon.onchange = function() {
		changeValue();
		};
		
		function changeValue(){
			hiddenfield.value = lat.value 
				+ ',' + lon.value;
		}
	}); // For all fields [end]
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
