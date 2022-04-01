(function (factory) {
    if (typeof define === "function" && define.amd) {
      // AMD
      define(["leaflet"], factory);
    } else if (typeof module !== "undefined") {
      // Node/CommonJS
      module.exports = factory(require("leaflet"));
    } else {
      // Browser globals
      if (typeof window.L === "undefined") {
        throw new Error("Leaflet must be loaded first");
      }
      factory(window.L);
    }
  })(function (L) {
    L.Control.Fullscreenlink = L.Control.extend({
      options: {
        position: "topleft",
        title: "Vollbildschirm anzeigen",
        websitelink: "./onlyMap.html",
      },
  
      onAdd: function (map) {
        var container = L.DomUtil.create(
          "div",
          "leaflet-control-fullscreen leaflet-bar leaflet-control"
        );
  
        this.link = L.DomUtil.create(
          "a",
          "leaflet-control-fullscreen-button leaflet-bar-part",
          container
        );
        this.link.href = this.options.websitelink;
        this.link.title = this.options.title;
  
        this._map = map;
  
        return container;
      },
    });
  
    L.control.fullscreenlink = function (options) {
      return new L.Control.Fullscreenlink(options);
    };
  });