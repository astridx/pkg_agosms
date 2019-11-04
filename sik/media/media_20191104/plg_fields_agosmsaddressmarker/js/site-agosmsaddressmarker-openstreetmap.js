;
document.addEventListener('DOMContentLoaded',function(){var e=document.querySelectorAll('.agosmsaddressmarkerleafletmap');[].forEach.call(e,function(t){var e=t.getAttribute('data-unique'),n=t.getAttribute('data-scrollwheelzoom'),l=L.DomUtil.get('map'+e);
if(!l.children.length>0){if(n==='0'){window['map'+e]=new L.Map('map'+e,{scrollWheelZoom:!1})}
else{window['map'+e]=new L.Map('map'+e,{scrollWheelZoom:!0})}};
var a='https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',o='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',r=new L.TileLayer(a,{attribution:o});
window['map'+e].addLayer(r)})},!1);