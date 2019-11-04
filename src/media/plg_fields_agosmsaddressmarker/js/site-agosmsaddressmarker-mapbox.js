;
document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.agosmsaddressmarkerleafletmap');[].forEach.call(e,function(a){var e=a.getAttribute('data-unique'),n=a.getAttribute('data-scrollwheelzoom'),p=a.getAttribute('data-mapboxkey'),m=L.DomUtil.get('map'+e);
if(!m.children.length>0){if(n==='0'){window['map'+e]=new L.Map('map'+e,{scrollWheelZoom:!1})}
else{window['map'+e]=new L.Map('map'+e,{scrollWheelZoom:!0})}};
var t='https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token='+p,o='Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',r=new L.TileLayer(t,{attribution:o,id:'mapbox.streets'});
window['map'+e].addLayer(r)})},!1);