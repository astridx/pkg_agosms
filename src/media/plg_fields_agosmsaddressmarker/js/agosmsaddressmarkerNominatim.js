;
document.addEventListener('click',function(t){if(t.target.classList.contains('agosmsaddressmarkerbutton')){var a=t.target,e=[],i=a.getAttribute('data-fieldsnamearray').split(','),l=a.parentNode,n=l.getElementsByTagName('input'),m=n[0],o=n[1];[].forEach.call(i,function(t){var a=document.getElementById(t);
e.push(a.value)});
e=e.join();
var s=function(t,a){if(!a&&t.length===1){m.value=t[0].lat;
o.value=t[0].lon;
o.onchange();
Joomla.renderMessages({'notice':[(Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_NOTICE')+e+' (Nominatim)')]})}
else if(t.length>0){}
else{Joomla.renderMessages({'error':[Joomla.JText._('PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR')+e+' (Nominatim)']})}},r={q:e,limit:1,format:'json',addressdetails:1};
getJSON('https://nominatim.openstreetmap.org/',r,s)}});