(function(e,o,i){"use strict";
L.AwesomeMarkers={};
L.AwesomeMarkers.version="2.0.1";
L.AwesomeMarkers.Icon=L.Icon.extend({options:{iconSize:[35,45],iconAnchor:[17,42],popupAnchor:[1,-32],shadowAnchor:[10,12],shadowSize:[36,16],className:"awesome-marker",prefix:"glyphicon",spinClass:"fa-spin",extraClasses:"",icon:"home",markerColor:"blue",iconColor:"white"},initialize:function(e){e=L.Util.setOptions(this,e)},createIcon:function(){var i=o.createElement("div"),e=this.options;
if(e.icon){i.innerHTML=this._createInner()};
if(e.bgPos){i.style.backgroundPosition=(-e.bgPos.x)+"px "+(-e.bgPos.y)+"px"};
this._setIconStyles(i,"icon-"+e.markerColor);
return i},_createInner:function(){var o,i="",n="",s="",e=this.options;
if(e.icon.slice(0,e.prefix.length+1)===e.prefix+"-"){o=e.icon}
else{o=e.prefix+"-"+e.icon};
if(e.spin&&typeof e.spinClass==="string"){i=e.spinClass};
if(e.iconColor){if(e.iconColor==="white"||e.iconColor==="black"){n="icon-"+e.iconColor}
else{s="style='color: "+e.iconColor+"' "}};
return"<i "+s+"class='"+e.extraClasses+" "+e.prefix+" "+o+" "+i+" "+n+"'></i>"},_setIconStyles:function(e,s){var i=this.options,n=L.point(i[s==="shadow"?"shadowSize":"iconSize"]),o;
if(s==="shadow"){o=L.point(i.shadowAnchor||i.iconAnchor)}
else{o=L.point(i.iconAnchor)};
if(!o&&n){o=n.divideBy(2,!0)};
e.className="awesome-marker-"+s+" "+i.className;
if(o){e.style.marginLeft=(-o.x)+"px";
e.style.marginTop=(-o.y)+"px"};
if(n){e.style.width=n.x+"px";
e.style.height=n.y+"px"}},createShadow:function(){var e=o.createElement("div");
this._setIconStyles(e,"shadow");
return e}});
L.AwesomeMarkers.icon=function(e){return new L.AwesomeMarkers.Icon(e)}}(this,document));