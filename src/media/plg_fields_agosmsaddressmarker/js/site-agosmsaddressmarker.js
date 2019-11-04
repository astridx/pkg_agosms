;
document.addEventListener('DOMContentLoaded',function(){var t=document.querySelectorAll('.agosmsaddressmarkerleafletmap');[].forEach.call(t,function(t){var s=t.getAttribute('data-uriroot'),e=t.getAttribute('data-unique'),i=t.getAttribute('data-lat'),a=t.getAttribute('data-lon'),n=t.getAttribute('data-scrollwheelzoom'),M=t.getAttribute('data-owngooglegesturetext'),h=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_TOUCH'),y=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLL'),v=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLLMAC'),S=t.getAttribute('data-specialicon'),g=t.getAttribute('data-popup'),l=t.getAttribute('data-showroutingcontrol');
if(l==='1'){var T=t.getAttribute('data-routingprofile'),A=t.getAttribute('data-routinglanguage'),b=t.getAttribute('data-routingmetric'),w=t.getAttribute('data-routewhiledragging'),m=t.getAttribute('data-routing_position'),C=t.getAttribute('data-routing_router'),c=t.getAttribute('data-fitSelectedRoutes'),p=(t.getAttribute('data-reverseWaypoints')==='true'),u=t.getAttribute('data-collapsible'),d=t.getAttribute('data-showAlternatives')};
var R=t.getAttribute('data-iconcolor'),k=t.getAttribute('data-markercolor'),x=t.getAttribute('data-icon'),E=t.getAttribute('data-popuptext'),D=t.getAttribute('data-mapboxkey'),W=L.DomUtil.get('map'+e);
if(!W.children.length>0){if(n==='0'){window['map'+e]=new L.Map('map'+e,{scrollWheelZoom:!1})}
else if(n==='2'){if(M==='1'){window['map'+e]=new L.Map('map'+e,{gestureHandling:!0,gestureHandlingText:{touch:h,scroll:y,scrollMac:v}})}
else{window['map'+e]=new L.Map('map'+e,{gestureHandling:!0})}}
else{window['map'+e]=new L.Map('map'+e,{scrollWheelZoom:!0})}};
if(n==='0'){window['map'+e].on('click',function(){if(window['map'+e].scrollWheelZoom.enabled()){window['map'+e].scrollWheelZoom.disable()}
else{window['map'+e].scrollWheelZoom.enable()}})};
if(l==='1'){var r;
if(C==='mapbox'){r=L.Routing.control(L.extend({fitSelectedRoutes:c,position:m,units:b,router:L.Routing.mapbox(D,{profile:T,language:A,}),waypoints:[L.latLng(i,a),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:w,reverseWaypoints:p,collapsible:u,showAlternatives:d,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+e])}
else{r=L.Routing.control(L.extend({fitSelectedRoutes:c,position:m,units:b,router:L.Routing.osrmv1({language:A}),waypoints:[L.latLng(i,a),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:w,reverseWaypoints:p,collapsible:u,showAlternatives:d,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+e])};
L.Routing.errorControl(r).addTo(window['map'+e])};
try{window['map'+e].setView(new L.LatLng(i,a),13);
var o=L.marker([i,a]);
if(S==='1'){var f=new L.AwesomeMarkers.icon({icon:x,markerColor:k,iconColor:R,prefix:'fa',spin:!1,extraClasses:'agosmsaddressmarkericonclass',});
o.setIcon(f)};
o.addTo(window['map'+e]);
if(g==='1'){o.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+s+'images'))};
if(g==='2'){o.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+s+'images')).openPopup()}}catch(O){window['map'+e].setView(new L.LatLng(0,0),13);
var o=L.marker([0,0]).addTo(window['map'+e]);
console.log(O)}})},!1);