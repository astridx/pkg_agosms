;
document.addEventListener('DOMContentLoaded',function(){var t=document.querySelectorAll('.leafletmapModGpx');[].forEach.call(t,function(t){var e=t.getAttribute('data-module-id'),d=t.getAttribute('data-gpx_file_name'),r=t.getAttribute('data-startIconUrl'),i=t.getAttribute('data-endIconUrl'),l=t.getAttribute('data-shadowUrl'),u=t.getAttribute('data-wptIconUrls'),n=d.split(';;'),o=L.featureGroup([]);
window['mymap'+e].fitBounds([[0,0],[0,0]]);
for(var a=0;a<n.length;a++){new L.GPX(n[a],{marker_options:{startIconUrl:r,endIconUrl:i,shadowUrl:l,wptIconUrls:{'':u}},async:!0}).on('loaded',function(t){if(!window['mymap'+e].getBounds().contains(L.latLng(0,0))){var a=window['mymap'+e].getBounds().extend(t.target.getBounds());
window['mymap'+e].fitBounds(a)}
else{window['mymap'+e].fitBounds(t.target.getBounds())}}).addTo(o)};
o.addTo(window['mymap'+e])})},!1);