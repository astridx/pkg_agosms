L.LeafletControlRadiuszoom = L.Control.extend({
    options: {
        position: 'topright',
        router: 'http://project-osrm.org/',
        token: '',
        placeholder: '',
        errormessage: 'Address not valid.',
        distance: 'Entfernung:',
        duration: 'Fahrzeit',
        stunden: ' Stunden. ',
        kilometer: ' Kilometer. ',
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
        var controlElementClass = 'leaflet-control-radiuszoom';
        controlElement = this._controlElement = controlElement = L.DomUtil.create(controlElementTag, controlElementClass);

        marker_startingpoint = this._marker_startingpoint = L.marker([0, 0]);
        marker_target = this._marker_tarket = L.marker([0, 0]);
        route_linestring = this._route_linestring = [{}];



        var distanceArray = ["5", "10", "20", "50", "100", "150"];
        select = this._select = L.DomUtil.create('select');
        for (var i = 0; i < distanceArray.length; i++) {
            var option = L.DomUtil.create('option');
            option.value = distanceArray[i]*1000;
            option.text = distanceArray[i] + " km";
            select.appendChild(option);
        }
        controlElement.appendChild(select);


        input = this._input = L.DomUtil.create('input');
        input.type = 'search';
        input.placeholder = this.options.placeholder;
        input.classList.add("addressinput");
        input.setAttribute("id", "addressinput");
        controlElement.appendChild(input);


        button = this._button = L.DomUtil.create('input');
        button.type = 'button';
        this._button.value = "Suchbereich einzoomen";
        controlElement.appendChild(button);





        L.DomEvent.disableClickPropagation(controlElement);

        L.DomEvent.addListener(button, 'click', this._click, this);

        return controlElement;
    },

    _click: function (e) {

        if (this._circle) {
            this._map.removeLayer(this._circle);
        }

        var json_obj_startingpoint = JSON.parse(Get('https://nominatim.openstreetmap.org/search?format=json&limit=5&q=' + input.value));
        if (typeof json_obj_startingpoint[0] === 'undefined' ||
            json_obj_startingpoint[0] === null
        ) {
            input.placeholder = this.options.errormessage;
            this._input.value = '';
        } else {
            this._circle = L.circle([json_obj_startingpoint[0].lat, json_obj_startingpoint[0].lon], {radius: select.value }).addTo(this._map);
            this._map.fitBounds(this._circle.getBounds(), {})
        }


        console.log(json_obj_startingpoint[0].lon + "  " + json_obj_startingpoint[0].lat);


        function Get(url) {
            var Httpreq = new XMLHttpRequest();
            Httpreq.open("GET", url, false);
            Httpreq.send(null);
            return Httpreq.responseText;
        }


        return true;
    },

    onRemove: function (map) {
        // ...
    },

});

L.leafletControlRadiuszoom = function (options) {
    return new L.LeafletControlRadiuszoom(options);
};
