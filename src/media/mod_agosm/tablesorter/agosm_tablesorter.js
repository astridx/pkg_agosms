  jQuery( function() {
   var t = jQuery('.tge_agosm_tablesorter');
   t.tablesorter( {
    debug: true,
    theme : 'jui', // theme "jui" and "bootstrap" override the uitheme widget option in v2.7+
    headerTemplate : '{content} {icon}', // needed to add icon for jui theme
    // widget code now contained in the jquery.tablesorter.widgets.js file
    widgets : ['uitheme', 'filter', 'zebra'],
    widgetOptions : {
      // zebra striping class names - the uitheme widget adds the class names defined in
      // $.tablesorter.themes to the zebra widget class names
      zebra   : ["even", "odd"],
      // set the uitheme widget to use the jQuery UI theme class names
      // ** this is now optional, and will be overridden if the theme name exists in $.tablesorter.themes **
      uitheme : 'jui',

      filter_functions :
      {
        // See: https://mottie.github.io/tablesorter/docs/example-widget-filter-custom.html
        //  e = exact text from cell
        //  n = normalized value returned by the column parser
        //  f = search filter input value
        //  i = column index
        // Filter coordinates:
        2 : function(e, n, f, i, $r, c, data)
        {
          console.log("TGE: Filtering by coords " + f + ": " + e);
          var co = e.split(",");
          var bb = f.split(",");
          var bbox = new L.LatLngBounds(new L.LatLng(bb[0], bb[1]), new L.LatLng(bb[2], bb[3]));
          return bbox.contains(new L.LatLng(co[1], co[0]));
        }
      }
    } } );

    // Hide coordinates column
    t.find('th:nth-child(3)').css("display", "none");
    t.find('td:nth-child(3)').css("display", "none");

    // And the ID columns
    t.find('th:nth-child(4)').css("display", "none");
    t.find('td:nth-child(4)').css("display", "none");

    function filterMapOnTableChange(e, filter)
    {
      // Filter function triggered by the table - possibly the number of visible rows has changed.
      // Update the map correspondingly.
            console.log("TGE: List filtered. Show/hide map markers.");

      // Determine the map ID and set the marker objects
      var mapID = jQuery(".leafletmapMod").prop("id").replace('map', '');

      if(!  window['agosm' + mapID])
      {
        // May happen is map is not ready yet (and table has initialized earlier)
        console.log("TGE: Map not (yet) ready.");
        return;
      }

      var markers = window['agosm' + mapID]['markers'];
      var cluster = window['agosm' + mapID]['cluster'];

      // Loop over all rows (visible or not):
      t.find('tr').each( function()
      {
        var row = jQuery(this);
        var id = row.find('td:nth-child(4)').text();

        // Skip rows w/o ID (the header)
        if(! id) { return; }

        // Is the row hidden, i.e. filtered out?
        var name = row.find('td:nth-child(1)').text();
        var hidden = (row.css('display') == 'none');
        console.log("TGE: Row " + name + "(" + id + ") hidden: " + hidden);

        var m = markers[id];
        console.log("TGE: Marker " + id + " visible: " + m.visible + " -> " + (! hidden));

        // If not yet hidden, hide the marker and update visibility status
        if(m.visible)
        {
          if(hidden)
          {
            console.log("TGE: Hiding marker " + id);
            cluster.ref.removeLayer(m.ref);
            m.visible = false;
          }
        }
        else
        {
          // Same the other way around
          if(! hidden)
          {
            console.log("TGE: Showing marker " + id);
            cluster.ref.addLayer(m.ref);
            m.visible = true;
          }
        }
      });
    }

    // TGE: Does not work, possibly version too old?
    // t.bind('tablesorter-ready', filterMapMarkers);
    t.bind('filterEnd', filterMapOnTableChange);
    t.bind('pagerComplete', filterMapOnTableChange);

    t.tablesorterPager(
    {
      container: jQuery(".tge-ts-pager"),
      saveSort: false,
      size: 9999,
      output: '{startRow} - {endRow} ({totalRows})'
    });

    // Initialize - TGE: No, initially the map shall display everything
    // filterMapMarkers();

  } );

  // Focus the map to the desired entry
  function focusMap(lat, lon)
  {
    console.log("TGE: Table focus clicked - updating map ...");
    var pos = new L.LatLng(lat, lon);
    var mapID = jQuery(".leafletmapMod").prop("id");
    var map = window["my" + mapID];
    // map.setView(pos, map.getZoom() + 2);
    map.setView(pos, 18);
  }

  // The map has been zoomed or moved, update the table to show only the items visible in map
  // Works by setting the filter value of the (invisible) coordinates column and then trigger a filter,
  // which will call the filter function "Filtering by coords" above
  function filterListOnMapChange(e)
  {
    console.log("TGE: Map changed - updating table ...");
    var t = jQuery('.tge_agosm_tablesorter');
    if(t.length)
    {
      // Of courseo this only on pages where there is a table
      var mapID = jQuery(".leafletmapMod").prop("id");
      var map = window["my" + mapID];
      var columns = jQuery.tablesorter.getFilters(t);
      columns[2] = map.getBounds().toBBoxString();
      t.trigger('search', [ columns ] );
    }
  }
