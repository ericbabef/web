function phoneformat(num, type) {
    switch(type) {
        case 1: phone = num.replace(/(\d{1})(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/, "+33$2$3$4$5$6"); break;
        default: phone = num.replace(/(\d{1})(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/, "+33 (0)$2 $3 $4 $5 $6");
    }
    return phone;
}

var map, fournitureSearch = [], restaurantSearch = [];

/*$(document).ready(function() {
  $("#introModal").modal("show");
});*/

$("#about-btn").click(function() {
  $("#aboutModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#nav-btn").click(function() {
  $(".navbar-collapse").collapse("toggle");
  return false;
});

/* Basemap Layers */
var street = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Fonds cartographiques d\'ESRI'
});
var imagery = L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
	attribution: 'Fonds cartographiques d\'ESRI'
});
//normal
/*var HERE_hybridDay = L.tileLayer('http://{s}.{base}.maps.cit.api.here.com/maptile/2.1/maptile/{mapID}/hybrid.day/{z}/{x}/{y}/256/png8?app_id={app_id}&app_code={app_code}', {
	attribution: 'Map &copy; 1987-2014 <a href="http://developer.here.com" target="_blank">HERE</a>',
	subdomains: '1234',
	mapID: 'newest',
	app_id: 'Y8m9dK2brESDPGJPdrvs',
	app_code: 'dq2MYIvjAotR8tHvY8Q_Dg',
	base: 'aerial',
	maxZoom: 18
});*/
//mobile
/*var HERE_hybridDay = L.tileLayer('http://{s}.{base}.maps.cit.api.here.com/maptile/2.1/maptile/{mapID}/hybrid.day.mobile/{z}/{x}/{y}/256/png8?app_id={app_id}&app_code={app_code}', {
	attribution: 'Map &copy; 1987-2014 <a href="http://developer.here.com" target="_blank">HERE</a>',
	subdomains: '1234',
	mapID: 'newest',
	app_id: 'Y8m9dK2brESDPGJPdrvs',
	app_code: 'dq2MYIvjAotR8tHvY8Q_Dg',
	base: 'aerial',
	maxZoom: 18
});*/
/* Overlay Layers */
var highlight = L.geoJson(null);

/* Single marker cluster layer to hold all clusters */
var markerClusters = new L.MarkerClusterGroup({
  spiderfyOnMaxZoom: true,
  showCoverageOnHover: false,
  zoomToBoundsOnClick: true,
  disableClusteringAtZoom: 16
});

/* Empty layer placeholder to add to layer control for listening when to add/remove fournitures to markerClusters layer */
var fournitureLayer = L.geoJson(null);
var fournitures = L.geoJson(null, {
  filter: function(feature, layer) {
		return feature.properties.type == 'Fourniture';
  },
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "public/images/" + feature.properties.type + ".png",
        iconSize: [24, 28],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),
      title: feature.properties.nom,
      riseOnHover: true
    });
  },
  onEachFeature: function (feature, layer) {
    if (feature.properties) {
                    var content = '<div class="row">' +
                            '<div class="col-sm-4">' +
                            '<div class="row">' +
                            '<div id="listImage" class="col-sm-12 modalimg"></div>' +
                            '<div class="col-sm-12 modalnfo">' +
                            '<h5>' + feature.properties.ville + '</h5>' +
                            '<ul class="list-unstyled">' +
                            '<li id="listAdresse"><i class="glyphicon glyphicon-screenshot"></i> ' + feature.properties.adresse + '</li>' +
                            '<li id="listTel"><strong><i class="glyphicon glyphicon-earphone"></i> ' + phoneformat(feature.properties.telephone) + '</strong></li>' +
                            '</ul>' +
                            '</div>' +
                            '<div class="col-sm-12 modalpic">' +
                            '<p><img src="public/images/' + feature.properties.type + '.png" width="32" height="32" title="' + feature.properties.type + '"></p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-sm-8 modalct">' +
                            '<p id="desc">'+ feature.properties.description +'</p>' +
							'<hr />' +
							/*'<p><a target="_blank "href="http://maps.google.com/maps?q=&hl=fr&ie=UTF8&ll=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&layer=c&cbll=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&cbp=12,238.42,,0,0"><i class="glyphicon glyphicon-screenshot"></i> Panorama street-view de l\'emplacement</a></p>' +
							'<p class="hidden-xs"><a target="_blank "href="http://maps.google.com/maps?f=d&source=s_d&saddr=&daddr=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&hl=fr&z=16"><i class="glyphicon glyphicon-screenshot"></i> Itinéraire vers l\'emplacement</a></p>' +
							'<p><a target="_blank "href="http://maps.google.com/maps?f=d&source=s_d&saddr=&daddr=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&hl=fr&z=16"><i class="glyphicon glyphicon-screenshot"></i> Itinéraire vers l\'emplacement</a></p>' +*/
                            '<ul class="list-unstyled">' +
							'<li><a target="_blank "href="http://maps.google.com/maps?q=&hl=fr&ie=UTF8&ll=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&layer=c&cbll=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&cbp=12,238.42,,0,0"><i class="glyphicon glyphicon-screenshot"></i> Panorama street-view de l\'emplacement</a></li>' +
							'<li><a target="_blank "href="http://maps.google.com/maps?f=d&source=s_d&saddr=&daddr=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&hl=fr&z=16"><i class="glyphicon glyphicon-screenshot"></i> Itinéraire vers l\'emplacement</a></li>' +
                            '<li class="hidden-xs" id="listMail"><a href="mailto:' + feature.properties.mail + '"><i class="glyphicon glyphicon-envelope"></i> ' + feature.properties.mail + '</a></li>' +
                            '<li class="hidden-xs" id="listSite"><a href="http://' + feature.properties.site_web + '" target="_blank"><i class="glyphicon glyphicon-globe"></i> ' + feature.properties.site_web + '</a></li>' +
                            '</ul>' +
                            '</div>' +
                            '</div>';

                    layer.on({
                        click: function (e) {
                            $("#feature-title").html(feature.properties.nom);
                            $("#feature-info").html(content);
                            $("#featureModal").modal("show");
                            
                            $('#featureModal').removeClass('Fourniture');
                            $('#featureModal').addClass(feature.properties.type);

                            if (feature.properties.image === null || feature.properties.image.length === 0) { $('#listImage').css('background-image', 'url(public/images/drapeau_reunion.png)'); }
                            else { $('#listImage').css('background-image', 'url(' + feature.properties.image + ')'); }
							
							if (feature.properties.description === null || feature.properties.description.length === 0) { $('#desc').css('background-image', 'url(public/images/drapeau_reunion.png)'); }
                            else { $('#desc').removeClass('hidden'); }

                            if (feature.properties.adresse === null || feature.properties.adresse.length === 0) { $('#listAdresse').addClass('hidden'); }
                            else { $('#listAdresse').removeClass('hidden'); }

                            if (feature.properties.telephone === null || feature.properties.telephone.length === 0) { $('#listTel').addClass('hidden'); }
                            else { $('#listTel').removeClass('hidden'); }

                            if (feature.properties.mail === null || feature.properties.mail.length === 0) { $('#listMail').addClass('hidden'); }
                            else { $('#listMail').removeClass('hidden'); }

                            if (feature.properties.site_web === null || feature.properties.site_web.length === 0) { $('#listSite').addClass('hidden'); }
                            else { $('#listSite').removeClass('hidden'); }

                            $('#callBtn').prop("href", "tel:" + phoneformat(feature.properties.telephone, 1));
                            if (feature.properties.telephone === null || feature.properties.telephone.length === 0) { $('#callBtn').addClass('disabled'); }
                            else { $('#callBtn').removeClass('disabled'); }

                            $('#mailBtn').prop("href", "mailto:" + feature.properties.mail);
                            if (feature.properties.mail === null || feature.properties.mail.length === 0) { $('#mailBtn').addClass('disabled'); }
                            else { $('#mailBtn').removeClass('disabled'); }

                            $('#lienBtn').prop("href", "http://" + feature.properties.site_web);
                            $('#lienBtn').prop("target", "_blank");
                            if (feature.properties.site_web === null || feature.properties.site_web.length === 0) { $('#lienBtn').addClass('disabled'); }
                            else { $('#lienBtn').removeClass('disabled'); }

                            $('#ItineraireBtn').prop("href", "http://maps.google.com/maps?f=d&source=s_d&saddr=&daddr=" + e.target._latlng.lat + "," + e.target._latlng.lng + "&hl=fr&z=16");
                            $('#ItineraireBtn').prop("target", "_blank");
							
							highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], {
								stroke: false,
								fillColor: "#00FFFF",
								fillOpacity: 0.7,
								radius: 10
							}));
                        }
                    });
      fournitureSearch.push({
        name: layer.feature.properties.filtre,
        source: "Fournitures",
        id: L.stamp(layer),
        lat: layer.feature.geometry.coordinates[1],
        lng: layer.feature.geometry.coordinates[0]
      });
    }
  }
});
$.getJSON("model/dataJson.php?pFunction=data_geom", function (data) {
  fournitures.addData(data);
  map.addLayer(fournitureLayer);
});

/* Empty layer placeholder to add to layer control for listening when to add/remove restaurants to markerClusters layer */
var restaurantLayer = L.geoJson(null);
var restaurants = L.geoJson(null, {
  filter: function(feature, layer) {
		return feature.properties.type == 'Restaurant';
  },
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "public/images/" + feature.properties.type + ".png",
        iconSize: [24, 28],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),
      title: feature.properties.nom,
      riseOnHover: true
    });
  },
  onEachFeature: function (feature, layer) {
    if (feature.properties) {
                    var content = '<div class="row">' +
                            '<div class="col-sm-4">' +
                            '<div class="row">' +
                            '<div id="listImage" class="col-sm-12 modalimg"></div>' +
                            '<div class="col-sm-12 modalnfo">' +
                            '<h5>' + feature.properties.ville + '</h5>' +
                            '<ul class="list-unstyled">' +
                            '<li id="listAdresse"><i class="glyphicon glyphicon-screenshot"></i> ' + feature.properties.adresse + '</li>' +
                            '<li id="listTel"><strong><i class="glyphicon glyphicon-earphone"></i> ' + phoneformat(feature.properties.telephone) + '</strong></li>' +
                            '</ul>' +
                            '</div>' +
                            '<div class="col-sm-12 modalpic">' +
                            '<p><img src="public/images/' + feature.properties.type + '.png" width="32" height="32" title="' + feature.properties.type + '"></p>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="col-sm-8 modalct">' +
                            '<p id="desc">'+ feature.properties.description +'</p>' +
							'<hr />' +
							/*'<p><a target="_blank "href="http://maps.google.com/maps?q=&hl=fr&ie=UTF8&ll=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&layer=c&cbll=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&cbp=12,238.42,,0,0"><i class="glyphicon glyphicon-screenshot"></i> Panorama street-view de l\'emplacement</a></p>' +
							'<p class="hidden-xs"><a target="_blank "href="http://maps.google.com/maps?f=d&source=s_d&saddr=&daddr=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&hl=fr&z=16"><i class="glyphicon glyphicon-screenshot"></i> Itinéraire vers l\'emplacement</a></p>' +
							'<p><a target="_blank "href="http://maps.google.com/maps?f=d&source=s_d&saddr=&daddr=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&hl=fr&z=16"><i class="glyphicon glyphicon-screenshot"></i> Itinéraire vers l\'emplacement</a></p>' +*/
                            '<ul class="list-unstyled">' +
							'<li><a target="_blank "href="http://maps.google.com/maps?q=&hl=fr&ie=UTF8&ll=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&layer=c&cbll=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&cbp=12,238.42,,0,0"><i class="glyphicon glyphicon-screenshot"></i> Panorama street-view de l\'emplacement</a></li>' +
							'<li><a target="_blank "href="http://maps.google.com/maps?f=d&source=s_d&saddr=&daddr=' + feature.geometry.coordinates[1] + ',' + feature.geometry.coordinates[0] + '&hl=fr&z=16"><i class="glyphicon glyphicon-screenshot"></i> Itinéraire vers l\'emplacement</a></li>' +
                            '<li class="hidden-xs" id="listMail"><a href="mailto:' + feature.properties.mail + '"><i class="glyphicon glyphicon-envelope"></i> ' + feature.properties.mail + '</a></li>' +
                            '<li class="hidden-xs" id="listSite"><a href="http://' + feature.properties.site_web + '" target="_blank"><i class="glyphicon glyphicon-globe"></i> ' + feature.properties.site_web + '</a></li>' +
                            '</ul>' +
                            '</div>' +
                            '</div>';

                    layer.on({
                        click: function (e) {
                            $("#feature-title").html(feature.properties.nom);
                            $("#feature-info").html(content);
                            $("#featureModal").modal("show");
                            
                            $('#featureModal').removeClass('Restaurant');
                            $('#featureModal').addClass(feature.properties.type);

                            if (feature.properties.image === null || feature.properties.image.length === 0) { $('#listImage').css('background-image', 'url(public/images/drapeau_reunion.png)'); }
                            else { $('#listImage').css('background-image', 'url(' + feature.properties.image + ')'); }
							
							if (feature.properties.description === null || feature.properties.description.length === 0) { $('#desc').css('background-image', 'url(public/images/drapeau_reunion.png)'); }
                            else { $('#desc').removeClass('hidden'); }

                            if (feature.properties.adresse === null || feature.properties.adresse.length === 0) { $('#listAdresse').addClass('hidden'); }
                            else { $('#listAdresse').removeClass('hidden'); }

                            if (feature.properties.telephone === null || feature.properties.telephone.length === 0) { $('#listTel').addClass('hidden'); }
                            else { $('#listTel').removeClass('hidden'); }

                            if (feature.properties.mail === null || feature.properties.mail.length === 0) { $('#listMail').addClass('hidden'); }
                            else { $('#listMail').removeClass('hidden'); }

                            if (feature.properties.site_web === null || feature.properties.site_web.length === 0) { $('#listSite').addClass('hidden'); }
                            else { $('#listSite').removeClass('hidden'); }

                            $('#callBtn').prop("href", "tel:" + phoneformat(feature.properties.telephone, 1));
                            if (feature.properties.telephone === null || feature.properties.telephone.length === 0) { $('#callBtn').addClass('disabled'); }
                            else { $('#callBtn').removeClass('disabled'); }

                            $('#mailBtn').prop("href", "mailto:" + feature.properties.mail);
                            if (feature.properties.mail === null || feature.properties.mail.length === 0) { $('#mailBtn').addClass('disabled'); }
                            else { $('#mailBtn').removeClass('disabled'); }

                            $('#lienBtn').prop("href", "http://" + feature.properties.site_web);
                            $('#lienBtn').prop("target", "_blank");
                            if (feature.properties.site_web === null || feature.properties.site_web.length === 0) { $('#lienBtn').addClass('disabled'); }
                            else { $('#lienBtn').removeClass('disabled'); }

                            $('#ItineraireBtn').prop("href", "http://maps.google.com/maps?f=d&source=s_d&saddr=&daddr=" + e.target._latlng.lat + "," + e.target._latlng.lng + "&hl=fr&z=16");
                            $('#ItineraireBtn').prop("target", "_blank");
							
							highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], {
								stroke: false,
								fillColor: "#00FFFF",
								fillOpacity: 0.7,
								radius: 10
							}));
                        }
                    });
      restaurantSearch.push({
        name: layer.feature.properties.filtre,
        source: "Restaurants",
        id: L.stamp(layer),
        lat: layer.feature.geometry.coordinates[1],
        lng: layer.feature.geometry.coordinates[0]
      });
    }
  }
});
$.getJSON("model/dataJson.php?pFunction=data_geom", function (data) {
  restaurants.addData(data);
  map.addLayer(restaurantLayer);
});

map = L.map("map", {
  zoom: 5,
  center: [46.52863469527167,2.43896484375],
  layers: [street, markerClusters, highlight],
  zoomControl: false,
  attributionControl: false
});

/* Layer control listeners that allow for a single markerClusters layer */
map.on("overlayadd", function(e) {
  if (e.layer === fournitureLayer) {
    markerClusters.addLayer(fournitures);
  }
});

map.on("overlayremove", function(e) {
  if (e.layer === fournitureLayer) {
    markerClusters.removeLayer(fournitures);
  }
});

map.on("overlayadd", function(e) {
  if (e.layer === restaurantLayer) {
    markerClusters.addLayer(restaurants);
  }
});

map.on("overlayremove", function(e) {
  if (e.layer === restaurantLayer) {
    markerClusters.removeLayer(restaurants);
  }
});

/* Clear feature highlight when map is clicked */
map.on("click", function(e) {
  highlight.clearLayers();
});

/* Attribution control */
function updateAttribution(e) {
  $.each(map._layers, function(index, layer) {
    if (layer.getAttribution) {
      $("#attribution").html((layer.getAttribution()));
    }
  });
}
map.on("layeradd", updateAttribution);
map.on("layerremove", updateAttribution);

var attributionControl = L.control({
  position: "bottomright"
});
attributionControl.onAdd = function (map) {
  var div = L.DomUtil.create("div", "leaflet-control-attribution");
  div.innerHTML = "<span>Réalisée par <a href='http://ericbabef.alwaysdata.net' target=_blank>Eric Babef</a> | </span><a href='#' onclick='$(\"#attributionModal\").modal(\"show\"); return false;'>Attribution</a> | <span><a href='index.php?action=admin' target=_blank>Administration</a></span>";
  return div;
};
map.addControl(attributionControl);

/*var zoomControl = L.control.zoom({
  position: "bottomright"
}).addTo(map);*/

var zoomHome = L.Control.zoomHome({position:"bottomleft"});
zoomHome.addTo(map);

/* GPS enabled geolocation control set to follow the user's location */
var locateControl = L.control.locate({
  position: "bottomleft",
  drawCircle: true,
  follow: true,
  setView: true,
  keepCurrentZoomLevel: true,
  markerStyle: {
    weight: 1,
    opacity: 0.8,
    fillOpacity: 0.8
  },
  circleStyle: {
    weight: 1,
    clickable: false
  },
  icon: "icon-direction",
  metric: true,
  strings: {
    title: "Mon emplacement",
    popup: "Vous êtes à {distance} {unit} de ce point",
    //outsideMapBoundsMsg: "You seem located outside the boundaries of the map"
  },
  locateOptions: {
    maxZoom: 18,
    watch: true,
    enableHighAccuracy: true,
    maximumAge: 10000,
    timeout: 10000
  }
}).addTo(map);

//localisation
/*
var geocoder = new google.maps.Geocoder();

function googleGeocoding(text, callResponse)
{
	geocoder.geocode({address: text}, callResponse);
}

function filterJSONCall(rawjson)
{
	var json = {},
		key, loc, disp = [];

	for(var i in rawjson)
	{
		key = rawjson[i].formatted_address;
		
		loc = L.latLng( rawjson[i].geometry.location.lat(), rawjson[i].geometry.location.lng() );
		
		json[ key ]= loc;	//key,value format
	}

	return json;
}

map.addControl( new L.Control.Search({
		callData: googleGeocoding,
		filterJSON: filterJSONCall,
		markerLocation: false,
		circleLocation:true,
		autoType: false,
		autoCollapse: true,
		minLength: 2,
		text: 'Chercher une adresse ...',
		textCancel: 'Annulé',
		textErr: 'Taper une autre adresse, svp',
		zoom: 18
}) );
*/
//fin localisation

/* Larger screens get expanded layer control and visible sidebar */
/*if (document.body.clientWidth <= 767) {
  var isCollapsed = true;
} else {
  var isCollapsed = false;
}*/

var baseLayers = {
  //"Satellite et label": HERE_hybridDay,
  "Satellite": imagery,
  "Plan": street
};

var groupedOverlays = {
  "Points d'intérêt": {
    "<img src='public/images/Fourniture.png' width='24' height='28'>&nbsp;Supermarchés": fournitureLayer,
	"<img src='public/images/Restaurant.png' width='24' height='28'>&nbsp;Restaurants": restaurantLayer
  }
};

var layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
  //collapsed: isCollapsed
  position: "topright"
}).addTo(map);

/* Highlight search box text on click */
$("#searchbox").click(function () {
  $(this).select();
});

$("#searchbox2").click(function () {
  $(this).select();
});

/* Typeahead search functionality */
$(document).one("ajaxStop", function () {
  $("#loading").hide();
  /* Fit map to boroughs bounds */
  //map.fitBounds(fournitures.getBounds());

  var fournituresBH = new Bloodhound({
    name: "Fournitures",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: fournitureSearch,
    limit: 10
  });
  
  var restaurantsBH = new Bloodhound({
    name: "Restaurants",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: restaurantSearch,
    limit: 10
  });

  var geonamesBH = new Bloodhound({
    name: "GeoNames",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      url: "http://api.geonames.org/searchJSON?username=bootleaf&featureClass=P&maxRows=5&countryCode=US&name_startsWith=%QUERY",
      filter: function (data) {
        return $.map(data.geonames, function (result) {
          return {
            name: result.name + ", " + result.adminCode1,
            lat: result.lat,
            lng: result.lng,
            source: "GeoNames"
          };
        });
      },
      ajax: {
        beforeSend: function (jqXhr, settings) {
          settings.url += "&east=" + map.getBounds().getEast() + "&west=" + map.getBounds().getWest() + "&north=" + map.getBounds().getNorth() + "&south=" + map.getBounds().getSouth();
          $("#searchicon").removeClass("fa-search").addClass("fa-refresh fa-spin");
        },
        complete: function (jqXHR, status) {
          $('#searchicon').removeClass("fa-refresh fa-spin").addClass("fa-search");
        }
      }
    },
    limit: 10
  });
  fournituresBH.initialize();
  restaurantsBH.initialize();
  geonamesBH.initialize();

  /* instantiate the typeahead UI */
  $("#searchbox, #searchbox2").typeahead({
    minLength: 3,
    highlight: true,
    hint: false
  }, {
    name: "Fournitures",
    displayKey: "name",
    source: fournituresBH.ttAdapter(),
    templates: {
      header: "<h4 class='typeahead-header'><img src='public/images/Fourniture.png' width='24' height='28'>&nbsp;Supermarchés</h4>",
      suggestion: Handlebars.compile(["{{name}}<br>&nbsp;<small>{{address}}</small>"].join(""))
    }
  }, {
    name: "Restaurants",
    displayKey: "name",
    source: restaurantsBH.ttAdapter(),
    templates: {
      header: "<h4 class='typeahead-header'><img src='public/images/Restaurant.png' width='24' height='28'>&nbsp;Restaurants</h4>",
      suggestion: Handlebars.compile(["{{name}}<br>&nbsp;<small>{{address}}</small>"].join(""))
    }
  }, {
    name: "GeoNames",
    displayKey: "name",
    source: geonamesBH.ttAdapter(),
    templates: {
      header: "<h4 class='typeahead-header'><img src='public/images/globe.png' width='25' height='25'>&nbsp;GeoNames</h4>"
    }
  }).on("typeahead:selected", function (obj, datum) {
    if (datum.source === "Fournitures") {
      if (!map.hasLayer(fournitureLayer)) {
        map.addLayer(fournitureLayer);
      }
      map.setView([datum.lat, datum.lng], 17);
      if (map._layers[datum.id]) {
        map._layers[datum.id].fire("click");
      }
    }
	if (datum.source === "Restaurants") {
      if (!map.hasLayer(restaurantLayer)) {
        map.addLayer(restaurantLayer);
      }
      map.setView([datum.lat, datum.lng], 17);
      if (map._layers[datum.id]) {
        map._layers[datum.id].fire("click");
      }
    }
    if (datum.source === "GeoNames") {
      map.setView([datum.lat, datum.lng], 14);
    }
    if ($(".navbar-collapse").height() > 50) {
      $(".navbar-collapse").collapse("hide");
    }
  }).on("typeahead:opened", function () {
    $(".navbar-collapse.in").css("max-height", $(document).height() - $(".navbar-header").height());
    $(".navbar-collapse.in").css("height", $(document).height() - $(".navbar-header").height());
  }).on("typeahead:closed", function () {
    $(".navbar-collapse.in").css("max-height", "");
    $(".navbar-collapse.in").css("height", "");
  });
  $(".twitter-typeahead").css("position", "static");
  $(".twitter-typeahead").css("display", "block");
});
