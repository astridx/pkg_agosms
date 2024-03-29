document.addEventListener(
    "DOMContentLoaded",
    function () {
        var e = document.querySelectorAll(".agomsaddressfindersurroundingdiv");
        [].forEach.call(
            e,
            function (e) {

                setTimeout(function () {
                    var g = e.getElementsByTagName("button"),
                        s = g[0],
                        d = s.getAttribute("data-mapid"),
                        a = e.getElementsByTagName("input"),
                        t = a[0],
                        n = a[1],
                        v = a[2],
                        r = a[3];
                    if (r.value.split(",").length !== 2) {
                        r.value = "50.27,7.26,";
                    }
                    var i = r.value.split(",");
                    t.value = i[0];
                    n.value = i[1];
                    var o = L.map(d).setView([t.value, n.value], 4);
                    o.setMaxZoom(18);
                    o.setMinZoom(4);
                    o.setMaxBounds([
                        [-90, -180],
                        [90,180],
                      ]);
                    o.fitBounds([
                        [70,35],
                        [33,-10],
                      ]);                    
                      L.tileLayer("https://{s}.tile.osm.org/{z}/{x}/{y}.png").addTo(o);
                    var u = new L.marker([t.value, n.value], { draggable: "true" }).addTo(o);
                    u.on("dragend", function (a) {
                        var r = a.target,
                            e = r.getLatLng();
                        r.setLatLng(new L.LatLng(e.lat, e.lng), { draggable: "true" });
                        o.panTo(new L.LatLng(e.lat, e.lng));
                        t.value = e.lat;
                        n.value = e.lng;
                        l();
                    });
                    t.onchange = function () {
                        l();
                    };
                    n.onchange = function () {
                        l();
                    };
                    
                    // -180,-90,180,90
                    // falsch 46.830134,4.702148,55.128649,15.908203
                    //4,46,15,55
                    //34,72,-12,33
                    // 
                    let restricedCords='"-180,-90,180,90"';
                    if (e.querySelector('.adressfindermap')) {
                        const cordsAttribIsThere = e.querySelector('.adressfindermap').hasAttribute('data-restriced-cords');
                        if (cordsAttribIsThere) {
                            restricedCords='"' + e.querySelector('.adressfindermap').getAttribute('data-restriced-cords') + '"';
                        }
                    }
                    console.log(e.querySelector('.adressfindermap').hasAttribute('data-restriced-cords'));

                    s.onclick = function () {
                        var e = v.value,
                            a = function (a, r) {
                                if (!r && a.length === 1) {
                                    t.value = a[0].lat;
                                    n.value = a[0].lon;
                                    n.onchange();
                                    u.setLatLng([a[0].lat, a[0].lon]);
                                    o.panTo(new L.LatLng(a[0].lat, a[0].lon));
                                } else if (a.length > 0) {
                                } else {
                                    Joomla.renderMessages({ error: [Joomla.JText._("PLG_AGOSMSADDRESSMARKER_ADDRESSE_ERROR") + e + " (Nominatim)"] });
                                }
                            },
                            r = { q: e, limit: 1, format: "json", addressdetails: 1, viewbox: restricedCords, bounded:1 };
                        getJSON("https://nominatim.openstreetmap.org/", r, a);
                    };
                    function l() {
                        r.value = t.value + "," + n.value;
                    }
                });
            },
            1000
        );
    },
    !1
);
function getJSON(n, t, a) {
    var e = new XMLHttpRequest();
    e.onreadystatechange = function () {
        if (e.readyState !== 4) {
            return;
        }
        if (e.status !== 200 && e.status !== 304) {
            a("");
            return;
        }
        a(e.response);
    };
    e.open("GET", n + getParamString(t), !0);
    e.responseType = "json";
    e.setRequestHeader("Accept", "application/json");
    e.send(null);
}
function getParamString(e, n, l) {
    var r = [];
    for (var o in e) {
        var u = encodeURIComponent(l ? o.toUpperCase() : o),
            t = e[o];
        if (!L.Util.isArray(t)) {
            r.push(u + "=" + encodeURIComponent(t));
        } else {
            for (var a = 0; a < t.length; a++) {
                r.push(u + "=" + encodeURIComponent(t[a]));
            }
        }
    }
    return (!n || n.indexOf("?") === -1 ? "?" : "&") + r.join("&");
}
