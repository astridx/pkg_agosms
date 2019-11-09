document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.agosmsaddressmarkerleafletmap');[].forEach.call(e,function(o){var m=o.getAttribute('data-uriroot'),t=o.getAttribute('data-unique'),M=o.getAttribute('data-maptype'),l=o.getAttribute('data-lat'),n=o.getAttribute('data-lon'),a=o.getAttribute('data-scrollwheelzoom'),S=o.getAttribute('data-owngooglegesturetext'),R=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_TOUCH'),W=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLL'),v=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLLMAC'),x=o.getAttribute('data-specialicon'),h=o.getAttribute('data-popup'),g=o.getAttribute('data-showroutingcontrol');
if(g==='1'){var C=o.getAttribute('data-routingprofile'),y=o.getAttribute('data-routinglanguage'),A=o.getAttribute('data-routingmetric'),f=o.getAttribute('data-routewhiledragging'),d=o.getAttribute('data-routing_position'),k=o.getAttribute('data-routing_router'),b=o.getAttribute('data-fitSelectedRoutes'),u=(o.getAttribute('data-reverseWaypoints')==='true'),w=o.getAttribute('data-collapsible'),c=o.getAttribute('data-showAlternatives')};
var O=o.getAttribute('data-iconcolor'),Z=o.getAttribute('data-markercolor'),T=o.getAttribute('data-icon'),H=o.getAttribute('data-popuptext'),s=o.getAttribute('data-mapboxkey'),r=L.DomUtil.get('map'+t);
if(M=='mapbox'){var s=o.getAttribute('data-mapboxkey');
if(!r.children.length>0){if(a==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var G='https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+s,E='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',D=new L.TileLayer(G,{attribution:E,id:'mapbox.streets'});
window['map'+t].addLayer(D)}
else if(M=='google'){if(!r.children.length>0){if(a==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var z=L.gridLayer.googleMutant({type:'roadmap'}).addTo(window['map'+t])}
else{[].forEach.call(e,function(e){if(!r.children.length>0){if(a==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var o='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',i='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',n=new L.TileLayer(o,{attribution:i});
window['map'+t].addLayer(n)})};
if(!r.children.length>0){if(a==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else if(a==='2'){if(S==='1'){window['map'+t]=new L.Map('map'+t,{gestureHandling:!0,gestureHandlingText:{touch:R,scroll:W,scrollMac:v}})}
else{window['map'+t]=new L.Map('map'+t,{gestureHandling:!0})}}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
if(a==='0'){window['map'+t].on('click',function(){if(window['map'+t].scrollWheelZoom.enabled()){window['map'+t].scrollWheelZoom.disable()}
else{window['map'+t].scrollWheelZoom.enable()}})};
if(g==='1'){var p;
if(k==='mapbox'){p=L.Routing.control(L.extend({fitSelectedRoutes:b,position:d,units:A,router:L.Routing.mapbox(s,{profile:C,language:y,}),waypoints:[L.latLng(l,n),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:f,reverseWaypoints:u,collapsible:w,showAlternatives:c,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])}
else{p=L.Routing.control(L.extend({fitSelectedRoutes:b,position:d,units:A,router:L.Routing.osrmv1({language:y}),waypoints:[L.latLng(l,n),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:f,reverseWaypoints:u,collapsible:w,showAlternatives:c,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])};
L.Routing.errorControl(p).addTo(window['map'+t])};
try{window['map'+t].setView(new L.LatLng(l,n),13);
var i=L.marker([l,n]);
if(x==='1'){var P=new L.AwesomeMarkers.icon({icon:T,markerColor:Z,iconColor:O,prefix:'fa',spin:!1,extraClasses:'agosmsaddressmarkericonclass',});
i.setIcon(P)};
i.addTo(window['map'+t]);
if(h==='1'){i.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+m+'images'))};
if(h==='2'){i.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+m+'images')).openPopup()}}catch(J){window['map'+t].setView(new L.LatLng(0,0),13);
var i=L.marker([0,0]).addTo(window['map'+t]);
console.log(J)}})},!1);