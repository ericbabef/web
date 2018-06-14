<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="description" content="Géolocalisation des établissements proposant des produits réunionnais en métropole">
		<meta name="author" content="Eric BABEF">
		<meta name="copyright" content="© 2017 Eric BABEF">
		<title><?= $title ?></title>

		<link rel="stylesheet" href="vendor/bootstrap-3.2.0/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/font-awesome-4.4.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/leaflet-0.7.5/leaflet.css">
		<link rel="stylesheet" href="vendor/leaflet/MarkerCluster.css">
		<link rel="stylesheet" href="vendor/leaflet/MarkerCluster.Default.css">
		<link rel="stylesheet" href="//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.css">
		<link rel="stylesheet" href="vendor/leaflet/leaflet.groupedlayercontrol.css">
		<link rel="stylesheet" href="vendor/leaflet/zoom_home.css">
		<link rel="stylesheet" href="vendor/leaflet-search-master/src/leaflet-search.css" />
		<link rel="stylesheet" href="vendor/appli_bootleaf/assets/css/app.css">
		
		<link rel="stylesheet" href="public/css/style.css">
		
		<link rel="icon" type="image/x-icon" href="public/images/favicon_eb.ico"> 
    </head>
        
    <body>
        <?= $content ?>
    </body>
	
	<script src="vendor/jquery/jquery-1.11.1.min.js"></script>
    <script src="vendor/bootstrap-3.2.0/dist/js/bootstrap.min.js"></script>
    <script src="vendor/typeahead/typeahead.bundle.min.js"></script>
    <script src="vendor/handlebars/handlebars.min.js"></script>
    <script src="vendor/ajax/list.min.js"></script>
    <script src="vendor/leaflet-0.7.5/leaflet.js"></script>
    <script src="vendor/leaflet/leaflet.markercluster.js"></script>
    <script src="//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.js"></script>
    <script src="vendor/leaflet/leaflet.groupedlayercontrol.js"></script>
	<script src="vendor/leaflet/zoom_home.js"></script>
	<script src="vendor/leaflet-search-master/src/leaflet-search.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false"></script>
    <script src="public/js/carto.js"></script>
</html>