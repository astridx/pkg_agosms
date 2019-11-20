;
document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.agosmsaddressmarkerleafletmap');[].forEach.call(e,function(a){var O=a.getAttribute('data-addprivacybox'),t=a.getAttribute('data-unique'),p=document.getElementsByClassName(t);
if(localStorage.getItem('privacyState')===null){localStorage.setItem('privacyState','0')};
var i;
for(i=0;i<p.length;i++){if(localStorage.getItem('privacyState')==='0'){p[i].innerHTML=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_SHOW_MAP')}
else{p[i].innerHTML=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_PRIVACYBUTTON_HIDE_MAP')};
p[i].onclick=function(){if(localStorage.getItem('privacyState')==='0'){document.getElementById('map'+t).style.display='block';
localStorage.setItem('privacyState','1')}
else{localStorage.setItem('privacyState','0')};
window.location.reload()}};
if(O==='1'&&(localStorage.getItem('privacyState')==='0')){document.getElementById('map'+t).style.display='none';
return};
var S=a.getAttribute('data-uriroot'),y=a.getAttribute('data-maptype'),l=a.getAttribute('data-lat'),n=a.getAttribute('data-lon'),o=a.getAttribute('data-scrollwheelzoom'),c=a.getAttribute('data-owngooglegesturetext'),B=a.getAttribute('data-googekey'),m=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_TOUCH'),b=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLL'),u=Joomla.JText._('PLG_AGOSMSADDRESSMARKER_SCROLLMAC'),k=a.getAttribute('data-specialicon'),h=a.getAttribute('data-popup'),f=a.getAttribute('data-showroutingcontrol');
if(f==='1'){var K=a.getAttribute('data-routingprofile'),W=a.getAttribute('data-routinglanguage'),E=a.getAttribute('data-routingmetric'),T=a.getAttribute('data-routewhiledragging'),R=a.getAttribute('data-routing_position'),Z=a.getAttribute('data-routing_router'),v=a.getAttribute('data-fitSelectedRoutes'),x=(a.getAttribute('data-reverseWaypoints')==='true'),A=a.getAttribute('data-collapsible'),M=a.getAttribute('data-showAlternatives')};
var G=a.getAttribute('data-iconcolor'),P=a.getAttribute('data-markercolor'),J=a.getAttribute('data-icon'),U=a.getAttribute('data-popuptext'),w=a.getAttribute('data-mapboxkey'),s=L.DomUtil.get('map'+t);
if(y=='mapbox'){var w=a.getAttribute('data-mapboxkey');
if(!s.children.length>0){if(o==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
var D='https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+w,I='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',H=new L.TileLayer(D,{attribution:I,id:'mapbox.streets'});
window['map'+t].addLayer(H)}
else if(y=='google'){if(!document.getElementById('google-map-script')){var g=document.createElement('script');
g.setAttribute('src','https://maps.googleapis.com/maps/api/js?key='+B);
g.setAttribute('id','google-map-script');
document.head.appendChild(g)};
if(!s.children.length>0){if(o==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else if(o==='1'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}
else{if(c==='1'){window['map'+t]=new L.Map('map'+t,{gestureHandling:!0,gestureHandlingText:{touch:m,scroll:b,scrollMac:u}})}
else{window['map'+t]=new L.Map('map'+t,{gestureHandling:!0})}}};
var Y=L.gridLayer.googleMutant({type:'roadmap'}).addTo(window['map'+t])}
else{[].forEach.call(e,function(e){if(!s.children.length>0){if(o==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else if(o==='1'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}
else{if(c==='1'){window['map'+t]=new L.Map('map'+t,{gestureHandling:!0,gestureHandlingText:{touch:m,scroll:b,scrollMac:u}})}
else{window['map'+t]=new L.Map('map'+t,{gestureHandling:!0})}}};
var a='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',i='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',r=new L.TileLayer(a,{attribution:i});
window['map'+t].addLayer(r)})};
if(!s.children.length>0){if(o==='0'){window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!1})}
else if(o==='2'){if(c==='1'){window['map'+t]=new L.Map('map'+t,{gestureHandling:!0,gestureHandlingText:{touch:m,scroll:b,scrollMac:u}})}
else{window['map'+t]=new L.Map('map'+t,{gestureHandling:!0})}}
else{window['map'+t]=new L.Map('map'+t,{scrollWheelZoom:!0})}};
if(o==='0'){window['map'+t].on('click',function(){if(window['map'+t].scrollWheelZoom.enabled()){window['map'+t].scrollWheelZoom.disable()}
else{window['map'+t].scrollWheelZoom.enable()}})};
if(f==='1'){var d;
if(Z==='mapbox'){d=L.Routing.control(L.extend({fitSelectedRoutes:v,position:R,units:E,router:L.Routing.mapbox(w,{profile:K,language:W,}),waypoints:[L.latLng(l,n),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:T,reverseWaypoints:x,collapsible:A,showAlternatives:M,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])}
else{d=L.Routing.control(L.extend({fitSelectedRoutes:v,position:R,units:E,router:L.Routing.osrmv1({language:W}),waypoints:[L.latLng(l,n),],geocoder:L.Control.Geocoder.nominatim(),routeWhileDragging:T,reverseWaypoints:x,collapsible:A,showAlternatives:M,altLineOptions:{styles:[{color:'black',opacity:0.15,weight:9},{color:'white',opacity:0.8,weight:6},{color:'blue',opacity:0.5,weight:2}]}})).addTo(window['map'+t])};
L.Routing.errorControl(d).addTo(window['map'+t])};
try{window['map'+t].setView(new L.LatLng(l,n),13);
var r=L.marker([l,n]);
if(k==='1'){var C=new L.AwesomeMarkers.icon({icon:J,markerColor:P,iconColor:G,prefix:'fa',spin:!1,extraClasses:'agosmsaddressmarkericonclass',});
r.setIcon(C)};
r.addTo(window['map'+t]);
if(h==='1'){r.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+S+'images'))};
if(h==='2'){r.bindPopup(obj.popuptext.replace(/<img src="images/g,'<img src="'+S+'images')).openPopup()}}catch(V){window['map'+t].setView(new L.LatLng(0,0),13);
var r=L.marker([0,0]).addTo(window['map'+t]);
console.log(V)}})},!1);