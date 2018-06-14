<?php
// ----------------------------------------------------------------
// TITLE
// ----------------------------------------------------------------

$pg_title = "RDM";
$pg_subtitle = "Editer un établissement";
$x = $edit_location_fetch['x'];
$y = $edit_location_fetch['y'];

// ----------------------------------------------------------------
// CSS - ajout des feuilles de style
// ----------------------------------------------------------------

$pg_css = <<<CSS
    <link rel="stylesheet" href="vendor/leaflet-0.7.5/leaflet.css">
    <link rel="stylesheet" href="vendor/jquery/jquery-ui.css">
    <link rel="stylesheet" href="vendor/select2/select2.min.css">
CSS;

// ----------------------------------------------------------------
// JS - ajout des plugins et scripts javascript
// ----------------------------------------------------------------

$pg_js = <<<JS
    <script src="vendor/leaflet-0.7.5/leaflet.js"></script>
    <script src="vendor/select2/select2.full.min.js"></script>
    <script src="vendor/validation/jquery.validate.min.js"></script>

    <script src="vendor/validation/additional-methods.min.js"></script>
    <script src="vendor/validation/localization/messages_fr.min.js"></script>
JS;

// map leaflet
$pg_js .= <<<JS
    <script type="text/javascript">

    // Initialize Select2 Elements
    $('.select2').select2();

    // disable submit form on enter keypress
    $('form').bind('keypress', function(e) {
        if (e.keyCode == 13) {               
            e.preventDefault();
            return false;
        }
    });

    
    /*$.validator.setDefaults({                
        ignore: [],                
        errorClass: 'e-validation-error',                
        errorPlacement: function (error, element) {
            $(error).insertAfter(element.closest(".e-widget"));
        }
    });*/

    $.validator.addMethod("phone", function (value, element) {
        return this.optional(element) || /^(\+33\.|0)[0-9]{9}$/i.test(value);
    }, "Le numéro de téléphone n'est pas valide");

    // jQuery Validation
    $("#frm").validate({
        rules: {
            "nom": {
                "required": true,
                "minlength": 5,
                "maxlength": 255
            },
            "type": {
                "required": true
            },
            "mail": {
                "email": true,
                "maxlength": 255
            },
            "tel": {
                phone: true
            },
            "lat": {
                "required": true
            },
            "lng": {
                "required": true
            },
            "adresse": {
                "required": true
            },
            "ville": {
                "required": true
            }
        },
        messages: {
            nom: {
                required: "Entrer le nom de l'établissement",
                minlength: jQuery.validator.format("Le nom est composé d'au moins {0} caractères.")
            },
            type: "Le type d'établissement est obligatoire",
            mail: "L'adresse mail  n'est pas valide",
            lat: "Vous devez obligatoirement rechercher une adresse",
            lng: "Vous devez obligatoirement rechercher une adresse",
            adresse: "Vous devez obligatoirement rechercher une adresse",
            ville: "Veuillez saisir une ville"
        },
        /*errorPlacement: function(error, element) {    
            error.appendTo(element.next().next());
        },
        highlight: function(element) {
            $(element).parent().addClass("has-error");
        },
        unhighlight: function(element) {
            $(element).parent().removeClass("has-error");
        },*/
        errorPlacement: function (error, element) {
            error.appendTo(element.parent().next());
        },
        submitHandler: function() {
            // Désactivation du bouton submit
            $("#frm_Submit").prop('disabled', true);

            // Validation du formulaire
            $("#frm")[0].submit();
        }
    });

    $("#adresse_autocomplete").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "https://api-adresse.data.gouv.fr/search/?",
                data: { q: request.term },
                dataType: "json",
                success: function (data) {
                    response($.map(data.features, function (item) {
                        //return { label: item.properties.label, value: item.geometry.coordinates[0]};
                        return item.properties.label + ', ' + item.geometry.coordinates[0];
                    }));
                }
            });
        }
    });

    function addr_search() {
        var inp = document.getElementById("addr");

        $.getJSON('https://nominatim.openstreetmap.org/search?format=json&limit=10&q=' + inp.value, function (data) {
            var items = [];

            $.each(data, function (key, val) {
                adr = val.display_name;
                var adr_exp = adr.replace(/'/g, '');
                //alert(adr_exp);
                items.push("<li><a href='#' onclick='locate(" + val.lon + ", " + val.lat + ", `" + adr_exp + "`);'>" + adr_exp + "</a></li>");
            });

            $('#results').empty();
            if (items.length !== 0) {
                $('<p>', {
                    html: "Cliquer sur l'adresse souhaité :"
                }).appendTo('#results');
                $('<ul/>', {
                    'class': 'my-new-list',
                    html: items.join('')
                }).appendTo('#results');
            } else {
                $('<p>', {
                    html: "Pas de résultat"
                }).appendTo('#results');
            }
        });
    }

    var point_location;
    function locate(lon, lat, adresse) {
        if(lat && lon) {
            map.setView([lat, lon],18,{animation: true});
            if (point_location != undefined) {
                map.removeLayer(point_location);
            };
            point_location = L.marker([lat, lon],{draggable: true}).addTo(map);
            document.getElementById('lat').value = point_location.getLatLng().lat;
            document.getElementById('lng').value = point_location.getLatLng().lng;
            document.getElementById('adresse').value = adresse;
            point_location.on('dragend', function (e) {
                document.getElementById('lat').value = point_location.getLatLng().lat;
                document.getElementById('lng').value = point_location.getLatLng().lng;
            });
        }
    }


    var map = L.map('map').setView([$y,$x], 18);

    point_location = L.marker([$y, $x],{draggable: true}).addTo(map);
    point_location.on('dragend', function (e) {
        document.getElementById('lat').value = point_location.getLatLng().lat;
        document.getElementById('lng').value = point_location.getLatLng().lng;
    });

    var style = 'osm-intl';
    var server = 'https://maps.wikimedia.org/';
    // Add a map layer
    L.tileLayer(server + style + '/{z}/{x}/{y}.png', {
        maxZoom: 18,
        id: 'wikipedia-map-01',
        attribution: 'Wikimedia maps beta | Map data &copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);
    </script>
JS;

// ----------------------------------------------------------------
// HTML
// ----------------------------------------------------------------

$pg_content = <<<CONTENT
<form action="index.php?action=edit&id={$edit_location_fetch['id']}" name="frm" id="frm" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <i class="fa fa-info-circle"></i>
                    <h3 class="box-title">Général</h3>
                </div>
                <div class="box-body">

                    <div class="form-group row">
                        <label for="nom" class="col-sm-2 col-form-label">Nom* :</label>
                        <div class="col-sm-10">
                            <input type="text" id="nom" name="nom" class="form-control" value="{$edit_location_fetch['nom']}" />
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <div class="form-group row">
                        <label for="type" class="col-sm-2 col-form-label">Type* :</label>
                        <div class="col-sm-10">
                            <select name="type" class="form-control select2" id="type" style="width: 100%;">
                                <option value="" disabled="disabled">-</option>
CONTENT;
                                foreach($type_rdm_fetch as $row) {
                                    $pg_content .= '<option value="'.$row['id'].'"'.(($row['id'] == $edit_location_fetch['type_rdm'])?' selected="true"':'').'>'.$row['name'].'</option>';
                                }
$pg_content .= <<<CONTENT
                            </select>
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <div class="form-group row">
                        <label for="mail" class="col-sm-2 col-form-label">Email :</label>
                        <div class="col-sm-10">
                            <input type="text" id="mail" name="mail" class="form-control" value="{$edit_location_fetch['mail']}" />
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <div class="form-group row">
                        <label for="site_web" class="col-sm-2 col-form-label">Site web :</label>
                        <div class="col-sm-10">
                            <input type="text" id="site_web" name="site_web" class="form-control" value="{$edit_location_fetch['site_web']}" />
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <div class="form-group row">
                        <label for="tel" class="col-sm-2 col-form-label">Téléphone :</label>
                        <div class="col-sm-10">
                            <input type="text" id="tel" name="tel" class="form-control" value="{$edit_location_fetch['telephone']}" />
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <label for="desc" class="control-label">Description :</label>
                    <textarea name="desc" class="form-control" id="desc" rows="4" placeholder="Description ...">{$edit_location_fetch['description']}</textarea>

                </div>
            </div>
        </div>
CONTENT;

$pg_content .= <<<CONTENT
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <i class="fa fa-globe"></i>
                    <h3 class="box-title">Localisation de l'établissement</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="overview alert alert-info">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Pour repositionner l'établissement, vous pouvez déplacer le marker ou rechercher une nouvelle adresse.
                    </div>

                    <label for="" class="control-label">Rechercher une adresse :</label>
                    <div class="input-group">
                        <input type="text" name="addr" value="" id="addr" class="form-control" id="" />
                        <span class="input-group-btn">
                            <button class="btn btn-primary mb-2" data-toggle="tooltip" type="button" title="Rechercher" onclick="addr_search();"><i class="fa fa fa-question"></i></button>
                        </span>
                    </div>

                    <div id="results"></div>
                    <br />

                    <div class="form-group row">
                        <label for="lat" class="col-sm-2 col-form-label">Latitude* :</label>
                        <div class="col-sm-10">
                            <input type="text" id="lat" name="lat" class="form-control" readonly="true" value="{$edit_location_fetch['y']}" />
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <div class="form-group row">
                        <label for="lng" class="col-sm-2 col-form-label">Longitude* :</label>
                        <div class="col-sm-10">
                            <input type="text" id="lng" name="lng" class="form-control"  readonly="true" value="{$edit_location_fetch['x']}" />
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <div class="form-group row">
                        <label for="adresse" class="col-sm-2 col-form-label">Adresse* :</label>
                        <div class="col-sm-10">
                            <input type="text" id="adresse" name="adresse" class="form-control" value="{$edit_location_fetch['adresse']}" />
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <div class="form-group row">
                        <label for="ville" class="col-sm-2 col-form-label">Ville* :</label>
                        <div class="col-sm-10">
                            <input type="text" id="ville" name="ville" class="form-control" value="{$edit_location_fetch['ville']}" />
                        </div>
                        <div class="col-sm-10"></div>
                    </div>

                    <div id="map" style="height: 350px;"></div>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
CONTENT;

$pg_content .= <<<CONTENT
    <div class="row">
        <div class="col-md-6">
            <div class="callout callout-warning">
                <p><i class="fa fa-info-circle"></i> Les champs indiqués par une * sont obligatoires</p>
            </div>
        </div>
        <div class="col-md-6">
            <button type="submit" id="frm_Submit" class="btn btn-primary btn-social btn-lg pull-right"><i class="fa fa-plus-circle"></i> Editer</button>
        </div><!-- /.col -->
    </div><!-- /.row -->
</form>
CONTENT;

?>
<?php require('template.php'); ?>