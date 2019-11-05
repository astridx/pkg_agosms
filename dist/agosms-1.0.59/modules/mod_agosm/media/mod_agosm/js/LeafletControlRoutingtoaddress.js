L.LeafletControlRoutingtoaddress=L.Control.extend({options:{position:'topright',router:'osrm',token:'',placeholder:'Please insert your address here.',errormessage:'Address not valid.',distance:'Entfernung:',duration:'Fahrzeit',target:'Koblenz, Rheinland-Pfalz, Deutschland',requesterror:'"Too Many Requests" or "Not Authorized - Invalid Token"'},initialize:function(e){L.Util.setOptions(this,e)},onAdd:function(e){this._map=e;
var t='div',o='leaflet-control-routingtoaddress';
controlElement=this._controlElement=controlElement=L.DomUtil.create(t,o);
marker_startingpoint=this._marker_startingpoint=L.marker([0,0]);
marker_target=this._marker_tarket=L.marker([0,0]);
route_linestring=this._route_linestring=[{}];
input=this._input=L.DomUtil.create('input');
input.type='search';
input.placeholder=this.options.placeholder;
input.classList.add('addressinput');
input.setAttribute('id','addressinput');
controlElement.appendChild(input);
messagebox=this._messagebox=L.DomUtil.create('div');
messagebox.classList.add('messagebox');
controlElement.appendChild(messagebox);
L.DomEvent.disableClickPropagation(controlElement);
L.DomEvent.addListener(input,'keydown',this._keydown,this);
return controlElement},_keydown:function(s){this._messagebox.innerHTML='';
messagebox.classList.remove('messagebox');
switch(s.keyCode){case 13:messagebox.classList.add('messagebox');
if(this._marker_startingpoint){this._map.removeLayer(this._marker_startingpoint)};
if(this._marker_target){this._map.removeLayer(this._marker_target)};
if(this._route_linestring){this._map.removeLayer(this._route_linestring)};
var t=JSON.parse(i('https://nominatim.openstreetmap.org/search?format=json&limit=5&q='+this.options.target)),o=JSON.parse(i('https://nominatim.openstreetmap.org/search?format=json&limit=5&q='+input.value));
if(typeof o[0]==='undefined'||o[0]===null||typeof t[0]==='undefined'||t[0]===null){input.placeholder=this.options.errormessage;
this._input.value=''}
else{this._marker_target=L.marker([t[0].lat,t[0].lon]).addTo(this._map);
this._marker_startingpoint=L.marker([o[0].lat,o[0].lon]).addTo(this._map);
var e;
if(this.options.router==='mapbox'){e=JSON.parse(i('https://api.mapbox.com/directions/v5/mapbox/driving/'+t[0].lon+','+t[0].lat+';'+o[0].lon+','+o[0].lat+'?access_token='+this.options.token+'&overview=full&geometries=geojson'))}
else{e=JSON.parse(i('https://router.project-osrm.org/route/v1/driving/'+t[0].lon+','+t[0].lat+';'+o[0].lon+','+o[0].lat+'?overview=full&geometries=geojson'))};
if(e.message==='Too Many Requests'||e.message==='Not Authorized - Invalid Token'){this._messagebox.innerHTML=this.options.requesterror}
else if(typeof e.routes[0]==='undefined'){this._messagebox.innerHTML=this.options.errormessage+'( '+this.options.router+' )'}
else{this._route_linestring=L.geoJSON(e.routes[0].geometry).addTo(this._map);
var r=(e.routes[0].legs[0].distance)/1000,n=(e.routes[0].legs[0].duration)/60;
this._map.fitBounds(this._route_linestring.getBounds());
this._messagebox.innerHTML=this.options.distance+' '+r.toFixed(2)+' km, '+this.options.duration+' '+(n/60).toFixed(2)+' h. ( '+this.options.router+' )'}};
function i(e){var t=new XMLHttpRequest();
t.open('GET',e,!1);
t.send(null);
return t.responseText}};
return!0},onRemove:function(e){},});
L.leafletControlRoutingtoaddress=function(e){return new L.LeafletControlRoutingtoaddress(e)};