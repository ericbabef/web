<?php
header('Content-Type: text/plain; charset=utf-8'); 
require_once("manager.php");
$manager = new Manager();
$db = $manager->pgConnection();

if ( $_GET['pFunction'] == 'data_geom' )    {

    $sth = $db->prepare("
       SELECT ST_AsGeoJSON(a.geom) as geojson, a.ville, a.adresse, a.telephone, b.name as type, a.description, a.mail, trim(a.site_web) as site_web, a.nom, a.image, a.filtre 
       FROM rdm a, type_rdm b where a.type_rdm = b.id
    ");
    $sth->execute();
    
    $feature = array(); 
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) { 
        $geom = $row['geojson']; // chargement de la colonne géométrique en GeoJSON
        $res['ville'] = $row['ville']; 
        $res['adresse'] = strval ($row['adresse']); 
        $res['telephone'] = $row['telephone']; 
        $res['type'] = $row['type']; 
        $res['description'] = $row['description'];
        $res['mail'] = $row['mail'];
        $res['site_web'] = $row['site_web'];
        $res['nom'] = $row['nom'];
        $res['image'] = $row['image'];
        $res['filtre'] = $row['filtre'];

        array_walk($row,function(&$res){$res=strval($res);}); //converti valeur null en valeur vide
        $feature[] = '{"type": "Feature", "geometry": ' . $geom . ', "properties": ' . json_encode($row) . '}'; // objet GeoJSON contenant la géométrie et les valeurs attributaires d'un enregistrement de la base
    } 

    echo '{"type": "FeatureCollection", "features": [' . implode(', ',$feature) . ']}'; // liste de tous les objets GeoJSON provenants de la base

}

else if ( $_GET['pFunction'] == 'data_geom_zoom' &&  isset($_GET["idcible"]) )  {

    $sth = $db->query("
        SELECT ST_Extent(ST_Transform(geom,4326)), ST_X(ST_PointOnSurface(ST_TRANSFORM(geom,4326))), ST_Y(ST_PointOnSurface(ST_TRANSFORM(geom,4326)))
        FROM public.rdm WHERE id = '".pg_escape_string($_GET["idcible"])."' 
        GROUP BY geom
    ");
    $results = $sth->fetch();
        
    if ( $results[0] != '' ) {
        $PolygonBounds = $results[0];
        $PolygonBounds = str_replace(")", "", str_replace("BOX(", "", $PolygonBounds));
        $PolygonBounds = str_replace(",", " ", $PolygonBounds);

        $PolygonBounds = explode(' ', $PolygonBounds);
        
        $data['el'] = $PolygonBounds[0];
        $data['eb'] = $PolygonBounds[1];
        $data['er'] = $PolygonBounds[2];
        $data['et'] = $PolygonBounds[3];
        
        $data['CentroidX'] = $results[1];
        $data['CentroidY'] = $results[2];

        $data['success'] = true;
    }
    else {
        $data['success'] = false;
    }
    echo json_encode($data);

}

else if ( $_GET['pFunction'] == 'value' )    {

    $sth = $db->prepare("
        SELECT a.id, a.nom, a.ville, b.name as type, ST_X(a.geom) as x, ST_Y(a.geom) as y 
        FROM rdm a, type_rdm b where a.type_rdm = b.id
    ");
    $sth->execute();
    
    $feature = array(); 
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) { 
        $res['id'] = $row['id'];
        $res['nom'] = $row['nom'];
        $res['ville'] = $row['ville'];
        $res['type'] = $row['type'];
        $res['x'] = $row['x'];
        $res['y'] = $row['y'];

        $feature[] = '' . json_encode($res, JSON_NUMERIC_CHECK) . ''; // objet GeoJSON contenant la géométrie et les valeurs attributaires d'un enregistrement de la base
    } 

    echo '{"data": [' . implode(', ',$feature) . ']}'; // liste de tous les objets GeoJSON provenants de la base

}