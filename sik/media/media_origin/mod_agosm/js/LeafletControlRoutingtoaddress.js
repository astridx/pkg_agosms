
L.LeafletControlRoutingtoaddress = L.Control.extend({
    options: {
        position: 'topright',
        router: 'osrm',
        token: '',
        placeholder: 'Please insert your address here.',
        errormessage: 'Address not valid.',
        distance: 'Entfernung:',
        duration: 'Fahrzeit',
        target: 'Koblenz, Rheinland-Pfalz, Deutschland',
        requesterror: '"Too Many Requests" or "Not Authorized - Invalid Token"'
    },

    initialize: function (options) {
        L.Util.setOptions(this, options);
        // ...

    },

    onAdd: function (map) {
        this._map = map;
        
        var controlElementTag = 'div';
        var controlElementClass = 'leaflet-control-routingtoaddress';
        controlElement = this._controlElement = controlElement = L.DomUtil.create(controlElementTag, controlElementClass);

        marker_startingpoint = this._marker_startingpoint = L.marker([0, 0]);
        marker_target = this._marker_tarket = L.marker([0, 0]);
        route_linestring = this._route_linestring = [{}];

        input = this._input = L.DomUtil.create('input');
        input.type = 'search';
        input.placeholder = this.options.placeholder;
        input.classList.add("addressinput");
		input.setAttribute("id", "addressinput");

        controlElement.appendChild(input);

        messagebox = this._messagebox = L.DomUtil.create('div');
        messagebox.classList.add("messagebox");

        controlElement.appendChild(messagebox);
		
		L.DomEvent.disableClickPropagation(controlElement);

        L.DomEvent.addListener(input, 'keydown', this._keydown, this);

        return controlElement;
    },

    _keydown: function (e) {
        
        this._messagebox.innerHTML = '';
        messagebox.classList.remove("messagebox");

        switch (e.keyCode) {
            // Enter
            case 13:
                messagebox.classList.add("messagebox");
                if (this._marker_startingpoint) {
                    this._map.removeLayer(this._marker_startingpoint);
                }
                if (this._marker_target) {
                    this._map.removeLayer(this._marker_target);
                }
                if (this._route_linestring) {
                    this._map.removeLayer(this._route_linestring);
                }
            
                var json_obj_target = JSON.parse(Get('https://nominatim.openstreetmap.org/search?format=json&limit=5&q=' + this.options.target));
                var json_obj_startingpoint = JSON.parse(Get('https://nominatim.openstreetmap.org/search?format=json&limit=5&q=' + input.value));

                if (typeof json_obj_startingpoint[0] === 'undefined' || 
                        json_obj_startingpoint[0] === null || 
                        typeof json_obj_target[0] === 'undefined' || 
                        json_obj_target[0] === null
                        )
                {
                    input.placeholder = this.options.errormessage;
                    this._input.value = '';
                }
                else
                {
                    this._marker_target = L.marker([json_obj_target[0].lat, json_obj_target[0].lon]).addTo(this._map);
                    this._marker_startingpoint = L.marker([json_obj_startingpoint[0].lat, json_obj_startingpoint[0].lon]).addTo(this._map);

                    var json_obj_route;
                    if (this.options.router === 'mapbox')
                    {
                            json_obj_route = JSON.parse(Get('https://api.mapbox.com/directions/v5/mapbox/driving/' + 
                                json_obj_target[0].lon + 
                                ',' + 
                                json_obj_target[0].lat + 
                                ';' +
                                json_obj_startingpoint[0].lon +
                                ',' +
                                json_obj_startingpoint[0].lat +
                                '?access_token=' + this.options.token +
                                '&overview=full&geometries=geojson'));
                    }
                    else
                    {
                            json_obj_route = JSON.parse(Get('https://router.project-osrm.org/route/v1/driving/' + 
                                    json_obj_target[0].lon + 
                                    ',' + 
                                    json_obj_target[0].lat + 
                                    ';' +
                                    json_obj_startingpoint[0].lon +
                                    ',' +
                                    json_obj_startingpoint[0].lat +
                                    '?overview=full&geometries=geojson'));
                    }
                    
                    if (json_obj_route.message === 'Too Many Requests' || json_obj_route.message === 'Not Authorized - Invalid Token')
                    {
                        this._messagebox.innerHTML = this.options.requesterror;
                    } 
                    else if (typeof json_obj_route.routes[0] === 'undefined' )
                    {
                        this._messagebox.innerHTML = this.options.errormessage  + '( ' + this.options.router +' )';
                    }
                    else
                    {
                        this._route_linestring = L.geoJSON(json_obj_route.routes[0].geometry).addTo(this._map);
                        var distance = (json_obj_route.routes[0].legs[0].distance)/1000;
                        var duration = (json_obj_route.routes[0].legs[0].duration)/60;
                        this._map.fitBounds(this._route_linestring.getBounds());
                        this._messagebox.innerHTML = this.options.distance + ' ' + distance.toFixed(2) + ' km, ' + this.options.duration + ' ' +  (duration/60).toFixed(2) + ' h. ' + '( ' + this.options.router +' )';
                    }

                }
                
                function Get(url){
                    var Httpreq = new XMLHttpRequest(); // a new request
                    Httpreq.open("GET",url,false);
                    Httpreq.send(null);
                    return Httpreq.responseText;          
                }            

        }
        return true;
    },

    onRemove: function (map) {
        // ...
    },

});

L.leafletControlRoutingtoaddress = function (options) {
    return new L.LeafletControlRoutingtoaddress(options);
};

