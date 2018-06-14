<?php

// ----------------------------------------------------------------
// TITLE
// ----------------------------------------------------------------

$pg_title = "RDM";
$pg_subtitle = "Tableau de bord";

// ----------------------------------------------------------------
// CSS - ajout des feuilles de style
// ----------------------------------------------------------------

$pg_css = <<<CSS
    <link href="vendor/DataTables/media/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="vendor/DataTables/extensions/SearchPane/css/dataTables.searchPane.min.css" />
    <link rel="stylesheet" href="vendor/leaflet-0.7.5/leaflet.css">
    <link rel="stylesheet" href="vendor/leaflet/awesome-markers/dist/leaflet.awesome-markers.css" />
    <!--<link rel="stylesheet" href="vendor/leaflet/zoom_home.css">-->
CSS;

// ----------------------------------------------------------------
// JS - ajout des plugins et scripts javascript
// ----------------------------------------------------------------

$pg_js = <<<JS
    <script type="text/javascript" src="vendor/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="vendor/DataTables/extensions/SearchPane/js/dataTables.searchPane.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="vendor/leaflet-0.7.5/leaflet.js"></script>
    <script src="vendor/leaflet/awesome-markers/dist/leaflet.awesome-markers.js" type="text/javascript"></script>
    <!--<script src="vendor/leaflet/zoom_home.js"></script>-->
    <script src="vendor/bootbox/bootbox.min.js"></script>
JS;

// datatable : ajout de toutes les valeurs
$pg_js .= <<<JS
    <script type="text/javascript">
    
    let draw = false;
    const tooltipThreshold = 10;
    const tooltipDetailsThreshold = 12;
    // Create map, markers array, and markers layer
    var initial_location = [46.52863469527167, 2.43896484375], initial_zoom = 5;
    var mymap = L.map('map', {zoomControl: true});
    var markersLayer = new L.LayerGroup();
    // Add markers layer
    markersLayer.addTo(mymap);

    $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $('#example tfoot th.filter').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Filtre '+title+'" />' );
        } );

        var table = $('#example').DataTable( {
            "ajax": "model/dataJson.php?pFunction=value",
            "columns": [
                { "data": "id" },
                { "data": "nom" },
                { "data": "ville" },
                { "data": "type",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                        if (oData.type === 'Fourniture'){
                            $(nTd).html('<span class="label label-warning">'+oData.type+'</span>');
                        }
                        else if (oData.type === 'Restaurant'){
                            $(nTd).html('<span class="label label-success">'+oData.type+'</span>');
                        }
                    }
                },
                { "data": "id",
                    "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
                            $(nTd).html('<div class="btn-group" role="group" style="white-space: nowrap;"><button class="btn btn-default btn-xs btn-zoom" data-toggle="tooltip" title="Zoom" id="zoomBtn" value="'+oData.id+'"><i class="fa fa-globe"></i></button><a href="index.php?action=viewEdit&id='+oData.id+'" class="btn btn-default btn-xs" data-toggle="tooltip" title="Modifier"><i class="fa fa-pencil"></i></a><a href="index.php?action=delete&id='+oData.id+'" class="btn btn-default btn-xs btn-confirm" data-toggle="tooltip" title="Supprimer"><i class="fa fa-times"></i></a></div>');
                    }
                },
                { "data": "x" },
                { "data": "y" }
                /*{
                    data: null,
                    className: "center",
                    defaultContent: '<a href="" class="editor_edit">Edit</a> / <a href="" class="editor_remove">Delete</a>'
                }*/
            ],
            "columnDefs": [
                {
                    "targets": [ 0, 5, 6 ],
                    "visible": false,
                    "searchable": false
                },
                {
                    "orderable": false,
                    "targets": "no-sort"
                },
                { width: 50, targets: 4 }
            ],
            fixedColumns: true,
            "order": [[ 1, "asc" ]],
            /*"searchPane": {
                threshold: 1
            },*/
            //"scrollY": 202,
            "scrollY": 400,
            "scrollX": true,
            "language": {
                "sProcessing":     "Traitement en cours...",
                "sSearch":         "Rechercher&nbsp;:",
                "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix":    "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst":      "Premier",
                    "sPrevious":   "Pr&eacute;c&eacute;dent",
                    "sNext":       "Suivant",
                    "sLast":       "Dernier"
                },
                "oAria": {
                    "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            }
        } )
        .on("page", () => {
            draw = true;
        })
        .on("draw", () => {
            if (draw) {
                draw = false;
            } else {
                //createHighcharts(getChartData(table));
                updateMap(table);
            }
        });

        // Apply the search
        table.columns().every( function () {
            var that = this;
     
            $( 'input', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            } );
        } );
    
        // create Highcharts
        //createHighcharts(getChartData(table));
        // create Leaflet
        createMap();
        // Set table events
        setTableEvents(table);

        /*
        // event handler for row selection
        $('#example tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();
            //alert( 'You clicked on '+data.id+'\'s row' );
            if ( data.id != '' ) {
                $.getJSON(
                    "model/data.php",
                    { pFunction: "data_geom_zoom", idcible: data.id },
                    function (data) {
                        if ( data['success'] == true ) {
                            mymap.setView([data['CentroidY'], data['CentroidX']],18,{animation: true})
                        }               
                    }
                );
            }
        } );
        */

        $('#example').on('click', '.btn-zoom', function () {
            var data = $(this).attr('value');
            console.log(data)
            if ( data != '' ) {
                $.getJSON(
                    "model/dataJson.php",
                    { pFunction: "data_geom_zoom", idcible: data },
                    function (data) {
                        if ( data['success'] == true ) {
                            mymap.setView([data['CentroidY'], data['CentroidX']],18,{animation: true})
                        }               
                    }
                );
            }
        } );

        // delete confirm
        $('#example').on('click', '.btn-confirm', function () {
            var href = $(this).attr('href');
            bootbox.confirm({
                title: "Confirmer la suppression",
                message: "Vous êtes sur le point de supprimer un élément, cette procédure est irréversible.<br />Voulez-vous poursuivre ?",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Non',
                        className: 'btn-default'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Oui',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        window.location = href;
                    }
                }
            });
            return false;
        });
        /*
        // Delete a record
        $('#example').on('click', 'a.editor_remove', function (e) {
            alert("test");
        } );
        */

    } );

    /*function getChartData(table) {
        const dataArray = [], categores = [], series_data = [];
        // loop table rows
        table.rows({ search: "applied" }).every(function() {
            var data = table.rows().data();
            //var data = this.data();
            console.log( data.length)
            var categories=[]; //creating array for storing browser type in array.
            for(var i=0;i<data.length;i++){
                categories.push(data[i].type)
            }
            console.log(categories)

            var  count = {}; //creating object for getting categories with count
            categories.forEach(function(i) {
                count[i] = (count[i]||0)+1;  
            });
            
            //console.log(count)

            //var series_data=[]; //creating empty array for highcharts series data
            //var categores=[];//creating empty array for highcharts categories
            Object.keys(count).map(function(item, key) {
                series_data.push(count[item]) ;
                categores.push(item)
            });
            //console.log(series_data)
            //console.log(categores)
        });
        //console.log(categores)
        dataArray.push(categores, series_data);
        return dataArray;
    }

    function createHighcharts(data) {
        Highcharts.chart("chart", {
            chart:{
                type:'column'
            },
            xAxis: {
                categories: data[0]
            },
             yAxis: {
                title: {
                    text: 'Count'
                }
            },
            series:[{
                name:'Browser',
                data:data[1]
            }]
        });
    }*/

    function createMap() {
        var style = 'osm-intl';
        var server = 'https://maps.wikimedia.org/';
        
        // Set view
        mymap.setView(initial_location, initial_zoom);
        
        // Add a map layer
        L.tileLayer(server + style + '/{z}/{x}/{y}.png', {
            maxZoom: 18,
            id: 'wikipedia-map-01',
            attribution: 'Wikimedia maps beta | Map data &copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
        }).addTo(mymap);

        /*var zoomHome = L.Control.zoomHome();
        zoomHome.addTo(map);*/
    }

    function updateMap(table) {    
        markersLayer.clearLayers();

        var markerIcon = L.AwesomeMarkers.icon({
            icon: 'circle',
            markerColor: 'darkblue',
            prefix: 'fa'
        });

        table.rows({ search: "applied" }).every(function() {
            var data = this.data();

            var lat = parseFloat(data.y);
            var lng = parseFloat(data.x);
            var popupText = '<div class="modal-content" style="width:18rem;">'+
                '<div class="modal-header">'+
                    '<h5 class="modal-title">'+data.nom+'</h5>'+
                '</div>'+
            '</div>';
            var tooltipText = '<strong>'+data.id+'</strong><br/>';
            
            var markerLocation = new L.LatLng(lat, lng);
            var marker = new L.Marker(markerLocation, {icon: markerIcon} ).bindPopup(popupText);
            
            markersLayer.addLayer(marker);
            
        });
    }

    function setTableEvents(table) {
        // listen for page clicks
        table.on("page", () => {
          draw = true;
        });
    
        // listen for updates and adjust the chart accordingly
        table.on("draw", () => {
            if (draw) {
                draw = false;
            } else {
                //createHighcharts(getChartData(table));
                updateMap(table);
            }
        });
    }

    </script>
JS;

/*
// map leaflet
$pg_js .= <<<JS
    <script type="text/javascript">
    var map = L.map('map').setView([46.52863469527167,2.43896484375], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    //couche rdm
    var rdm_geom = L.geoJson(null, {
        style: function (feature) {
            return {
                radius: 8,
                fillColor: "#ff7800",
                color: "#000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            };
        }
    });
    $.getJSON("model/data.php?pFunction=data_geom", function (data) {
        rdm_geom.addData(data);
        map.addLayer(rdm_geom);
    });
    </script>
JS;
*/

// ----------------------------------------------------------------
// HTML
// ----------------------------------------------------------------

$pg_content = <<<CONTENT
CONTENT;

$pg_content .= <<<CONTENT
<!-- 2 blocs -->
<div class="row">
    <div class="col-lg-4 col-sm-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    {$nb_location_current_date_fetch['total']} <sup style="font-size: 0.6em">Etablissement(s)</sup>
                </h3>
                <p>
                    Ajouté(s) aujourd'hui
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-ios7-pricetag-outline"></i>
            </div>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-4 col-sm-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    {$nb_location_laps_date_fetch['total']} <sup style="font-size: 0.6em">Etablissement(s)</sup>
                </h3>
                <p>
                    Ajouté(s) les 30 derniers jours
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-ios7-pricetag-outline"></i>
            </div>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-4 col-sm-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    {$nb_location_fetch['total']} <sup style="font-size: 0.6em">Etablissement(s)</sup>
                </h3>
                <p>
                    Au total
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-ios7-pricetag-outline"></i>
            </div>
        </div>
    </div><!-- ./col -->
</div><!-- /.row -->
CONTENT;

//1ere ligne
$pg_content .= <<<CONTENT
<div class="row">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="glyphicon glyphicon-th-list"></i>
                <h3 class="box-title">Liste des établissements</h3>
                <!-- <div class="box-tools pull-right no-print">
                    <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                </div> -->
            </div><!-- /.box-header -->
            <div class="box-body">
                
                <table id="example" class="table table-striped table-hover table-sm" cellspacing="0" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nom établissement</th>
                            <th>Ville</th>
                            <th>Type</th>
                            <th class="no-sort">Actions</th>
                            <th>X</th>
                            <th>Y</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>-</th>
                            <th class="filter">Nom établissement</th>
                            <th class="filter">Ville</th>
                            <th class="filter">Type</th>
                            <th>-</th>
                            <th>-</th>
                            <th>-</th>
                        </tr>
                    </tfoot>
                </table>
            </div><!-- /.box-body -->
            <!--ajouter-->
            <div class="box-footer clearfix">
                <div class="btn-group pull-right">
                    <a class="btn btn-default btn-sm" href="index.php?action=viewAdd">
                        <i class="fa fa-plus-circle"></i> Ajouter
                    </a>
                </div>
            </div>
        </div><!-- /.box -->
    </div>

CONTENT;

$pg_content .= <<<CONTENT

    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <i class="fa fa-globe"></i>
                <h3 class="box-title">Localisation des établissements</h3>
                <!-- <div class="box-tools pull-right no-print">
                    <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                </div> -->
            </div><!-- /.box-header -->
            <div class="box-body">
                
                <div id="map" style="height: 605px;"></div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div>
CONTENT;

?>
<?php require('template.php'); ?>