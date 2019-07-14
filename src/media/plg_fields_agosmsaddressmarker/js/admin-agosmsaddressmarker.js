document.addEventListener('DOMContentLoaded', function () {

	var agosmsaddressmarkersurroundingdiv = document.querySelectorAll('.agosmsaddressmarkersurroundingdiv');

	// For all fields [start]
	[].forEach.call(agosmsaddressmarkersurroundingdiv, function (element) {
		// Set the fields 
		// Todo Error Handling
		var inputs = element.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];
		var hiddenfield = inputs[2];
		
		// Write the value to the fields lat and lon 
		// Todo Check if number
		if (hiddenfield.value.split(',').length !== 2) {
			hiddenfield.value = '0,0';
		};
		var hf = hiddenfield.value.split(',');
		lat.value = hf[0];
		lon.value = hf[1];
		
		// Write the value to the hidden field if lat or lon is changed
		lat.onchange = function() {
		hiddenfield.value = lat.value + ',' + lon.value;
		};

		lon.onchange = function() {
		hiddenfield.value = lat.value + ',' + lon.value;
		};
	
	});
	// For all fields [end]

}, false);

function tempAlert(msg, duration, color)
{
	var el = document.createElement("div");
	el.setAttribute("style",
		"position:absolute;padding:5%;" +
		"top:40%;left:40%;" +
		"background-color:white;border-style:solid;border-color:#" + color + ";");
	el.innerHTML = msg;
	setTimeout(function () {
		el.parentNode.removeChild(el);
	}, duration);
	document.body.appendChild(el);
}

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
