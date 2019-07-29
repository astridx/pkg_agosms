document.addEventListener('DOMContentLoaded', function () {

	var agosmsaddressmarkersurroundingdiv = document.querySelectorAll('.agosmsaddressmarkersurroundingdiv');

	// For all fields [start]
	[].forEach.call(agosmsaddressmarkersurroundingdiv, function (element) {
		// Set the fields 
		// Todo Error Handling
		var inputs = element.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];
		var popuptext = inputs[5];
		var hiddenfield = inputs[6];

		var selects = element.getElementsByTagName('select');
		var iconcolor = selects[0];
		var markercolor = selects[1];
		var icon = selects[2];
		var iconcolorSpan = iconcolor.parentNode.getElementsByTagName('span')[0];
		var markercolorSpan = markercolor.parentNode.getElementsByTagName('span')[0];
		var iconSpan = icon.parentNode.getElementsByTagName('span')[0];
		
		
		// Write the value to the fields
		if (hiddenfield.value.split(',').length !== 6) {
			hiddenfield.value = '0,0,,,,,';
		};
		var hf = hiddenfield.value.split(',');

		lat.value = hf[0];
		
		lon.value = hf[1];
		
		if ( hf[2] !== '') {		
			iconcolor.value = hf[2];
			while(iconcolorSpan.firstChild ) {
				iconcolorSpan.removeChild(iconcolorSpan.firstChild);
			}		
			iconcolorSpan.appendChild(document.createTextNode(hf[2]));
		}
		
		if ( hf[3] !== '') {		
			markercolor.value = hf[3];
			while(markercolorSpan.firstChild ) {
				markercolorSpan.removeChild(markercolorSpan.firstChild);
			}		
			markercolorSpan.appendChild(document.createTextNode(hf[3]));
		}
		
		if ( hf[4] !== '') {		
			icon.value = hf[4];
			while(iconSpan.firstChild ) {
				iconSpan.removeChild(iconSpan.firstChild);
			}		
			iconSpan.appendChild(document.createTextNode(hf[4]));
		}

		if ( hf[5] !== '') {
			popuptext.value = hf[5];
		}
		
		
		// Write the value to the hidden field if lat or lon is changed
		lat.onchange = function() {
		changeValue();
		};

		lon.onchange = function() {
		changeValue();
		};
		
		iconcolor.onchange = function() {
		changeValue();
		};

		markercolor.onchange = function() {
		changeValue();
		};

		icon.onchange = function() {
		changeValue();
		};

		popuptext.onchange = function() {
		changeValue();
		};

		function changeValue(){
			hiddenfield.value = lat.value 
				+ ',' + lon.value
				+ ',' + iconcolor.value
				+ ',' + markercolor.value
				+ ',' + icon.value
				+ ',' + popuptext.value;
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
