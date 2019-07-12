document.addEventListener('click', function (e) {
	if (e.target.classList.contains('agosmsaddressmarkerbutton')) {
		var button = e.target;
		var addressstring = button.getAttribute('data-addressstring');
		alert(addressstring);

	Util = _dereq_('../util');
		
		var test = Util.jsonp(addressstring + 'search', L.extend({
			q: query,
			limit: 5,
			format: 'json',
			addressdetails: 1
		}, this.options.geocodingQueryParams),
		function(data) {
			var results = [];
			for (var i = data.length - 1; i >= 0; i--) {
				var bbox = data[i].boundingbox;
				for (var j = 0; j < 4; j++) bbox[j] = parseFloat(bbox[j]);
				results[i] = {
					icon: data[i].icon,
					name: data[i].display_name,
					html: this.options.htmlTemplate ?
						this.options.htmlTemplate(data[i])
						: undefined,
					bbox: L.latLngBounds([bbox[0], bbox[2]], [bbox[1], bbox[3]]),
					center: L.latLng(data[i].lat, data[i].lon),
					properties: data[i]
				};
			}
			cb.call(context, results);
		}, this, 'json_callback');
		
		console.log(test);
		
		var surroundingDiv = button.parentNode;
		var inputs = surroundingDiv.getElementsByTagName('input');
		var lat = inputs[0];
		var lon = inputs[1];
		var hiddenfield = inputs[2];
	}
});



