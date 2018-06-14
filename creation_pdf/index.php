<?php
// begin output buffer
//ob_start();

// set content header
header('Content-type: application/pdf');
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// Load Dependecies
require('vendor/fpdf/fpdf.php');

// chargement du fichier config
require('model/model.php');

// Create PDF
$pdf = new PDF('P','mm','A4');
$pdf->SetTitle('Création d\'un PDF dynamique');
$pdf->SetAuthor('Eric BABEF');
$pdf->SetKeywords('PDF PostgreSQL WMS');

//connaître le nombre de pages pour le footer
$pdf->AliasNbPages();

/************************************************************
Page(s) des info
************************************************************/
$pdf->AddPage();

//position première ligne
$pdf->setXY(5, 30);

/************************************************************
Info du layer de référence + pj si présent
************************************************************/
//parcours du xml pour extraire valeur layer_ref
foreach ($xml->layer_ref as $layer) {
	$layer_ref = pg_escape_string($layer["name"]);
	$layer_ref_name_attachment = pg_escape_string($layer["name_attachment"]);
	$field_ref = pg_escape_string($layer["field"]);
	$field_cible_ref = pg_escape_string($layer["field_cible"]);
	$epsg = pg_escape_string($layer["epsg"]);
	$title_ref = pg_escape_string(utf8_decode($layer["title"]));
}
//execution sql pour obtenir les valeurs du layer_ref
$lists_value_ref = GetValueLayerRef($bdd, $field_ref, $layer_ref, $field_cible_ref, $_GET['ref']);
//mise en forme du résultat
$content_bdd_layer_ref = '';
foreach($lists_value_ref as $result){  
	foreach($result as $key => $value){
		$content_bdd_layer_ref .= "- ";
		$content_bdd_layer_ref .= "$key : $value";  
		$content_bdd_layer_ref .= "\n";
	}
}
$content_bdd_layer_ref .= '';
$pdf->SetFont('Arial','B',16);
$pdf->MultiCell(194,8,"* Caractéristiques de l'objet traité",false,'L',false);
$pdf->Ln(1);
$pdf->SetFont('Arial','',12);
//couleur de fond
//$pdf->SetFillColor(192);

//récupération pj et redimensionnement image si présent
if (!empty($layer_ref_name_attachment)) {
	//req sql pour récupérer la pj et encodage en base64 du champs type bytea
	$req_attachement = GetAttachmentLayerRef($bdd, $layer_ref, $layer_ref_name_attachment, $field_cible_ref, $_GET['ref']);
	$results = $req_attachement->fetch();
	$my_base64_string = 'data:'.$results[0].';base64,'.$results[3];
	//fonction qui transforme le texte encodé en image
	$image = base64ToImg($my_base64_string, 'tmp/'.$results[1]);
	//définition du type d'image (jpeg, png, gif)
	$type_image = strtoupper(str_replace("image/", "", $results[0]));
	//récupération des dimensions
	$dimensions = getimagesize($image);
	$dimensions_width = $dimensions[0];
	$dimensions_height = $dimensions[1];
	//redimensionnement image
	$image_redimension = ImageRedimension($dimensions_width, $dimensions_height, 200, 200);
	//position image
	$pdf->setXY(145, 30);
	$pdf->Image($image,null,null,$image_redimension[0], $image_redimension[1], $type_image);
	//position texte après image
	$pdf->setXY(5, 40);
	//calcul longeur cellule tableau
	$lenth_cell = CalcLenthCell(135, 100);
	$pdf->MultiCell($lenth_cell,8,utf8_decode($content_bdd_layer_ref),false,'L',false);
	$pdf->Ln(5);
}
//cas où pas de pj
else {
	//position texte
	$pdf->setXY(5, 40);
	//calcul longeur cellule tableau
	$lenth_cell = CalcLenthCell(190, 100);
	$pdf->MultiCell($lenth_cell,8,utf8_decode($content_bdd_layer_ref),false,'L',false);
	$pdf->Ln(5);
}

/************************************************************
info des layers intersectées
************************************************************/
//position ligne tableaux
$pdf->setX(5);
$pdf->SetFont('Arial','B',16);
$pdf->MultiCell(194,8,"* Informations relatives à l'objet traité",false,'L',false);
$pdf->Ln(2);

foreach ($xml->layer_ref as $layer) {
	$layer_ref = $layer["name"];
	$field_cible_ref = $layer["field_cible"];
	foreach ($layer->layer_intersect as $layer) {
		$layer_intersect = $layer["name"];
		$field_intersect = $layer["field"];
		$field_alias_intersect = utf8_decode($layer["field_alias"]);
		$title_intersect = utf8_decode($layer["title"]);

		//titre de chaque layer intersect
		$pdf->SetFont('Arial','B',14);
		$pdf->MultiCell(194,8,$title_intersect,false,'L',false);
		$pdf->Ln(1);
		
		//utilisation du tableau multicell déclaré comme class
		//largeur variable des attributs en fonction du xml --> defini la largeur de tous les tableaux
		$lenth = array();
		$field_lenth_intersect = $layer["field_lenth"];
		$field_lenth_intersect = explode(", ", $field_lenth_intersect);
		foreach ($field_lenth_intersect as $value_lenth){
			$lenth_cell = CalcLenthCell(190, $value_lenth);
			$lenth[] = $lenth_cell;
		}
		$pdf->SetWidths($lenth);
		
		$req_intersect = GetValueLayerIntersect($bdd, $field_intersect, $layer_ref, $layer_intersect, $field_cible_ref, $_GET['ref']);
		//nb de ligne traité par la requete
		$nb_row_req_intersect = $req_intersect->rowCount();
		//nb de colonne traité par la requete
		$nb_col_req_intersect = $req_intersect->columnCount();
		
		if ($nb_row_req_intersect > 0) {

			// tableau
			// header --> récupératio de tous les alias du layer intersect
			//epaisseur bordure des tableaux
			$pdf->SetLineWidth(0.1);
			$pdf->SetFont('Arial','B',11);
			//couleur de fond du header
			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$alias = array();
			$field_alias_intersect = utf8_decode($layer["field_alias"]);
			$field_alias_intersect = explode(", ", $field_alias_intersect);
			foreach ($field_alias_intersect as $value_alias){
				$alias[] = $value_alias;
			}
			$pdf->RowHeader($alias);

			// attribute
			$pdf->SetFont('Arial','',8);
			$pdf->SetTextColor(0);
			//exécution de la requete
			while ($row = $req_intersect->fetch()) {
				$attribute = array();
				$field_intersect = $layer["field"];
				$field_intersect = explode(", ", $field_intersect);
				foreach($field_intersect as $key => $value){   	
					$attribute[]=utf8_decode($row[$key]);	
				}
				$pdf->RowAttribute($attribute);
			}
			
			$pdf->Ln(5);
		}
		else{
			$pdf->SetFont('Arial','I',10);
			$pdf->MultiCell(194,8,"Pas d'information",false,'L',false);
			$pdf->Ln(5);
		}
	}
	
}

/************************************************************
Page carto
************************************************************/
$pdf->AddPage();

// titre
// Position multicell
$pdf->setXY(5, 30);
$pdf->SetFont('Arial','B',16);
$pdf->MultiCell(194,8,"* Situation cartographique de l'objet traité",false,'L',false);

$titre = "(Vue aérienne 2012)";
$pdf->SetFont('Arial','I',8);
$pdf->MultiCell(0,8,$titre,false,'L',false);

$pdf->setY(40);

// carto
$req_extent = GetExtentLayerRef($bdd, $layer_ref, $field_cible_ref, $_GET['ref']);
$results = $req_extent->fetch();

//récupération étendue
$data['el'] = $results[0];
$data['eb'] = $results[2];
$data['er'] = $results[1];
$data['et'] = $results[3];

//calcul taille de l'image
$dx = $data['er'] - $data['el'];
$dy = $data['et'] - $data['eb'];

//redimensionnement image
$image_redimension = ImageRedimension($dx, $dy, 650, 650);
$pixel_width = $dx / $image_redimension[2];
$scale = $pixel_width / 0.00028;
$scale = round($scale);
$sizex = number_format($image_redimension[2], 0);
$sizey = number_format($image_redimension[3], 0);
$mapURL = $path_wms."&WIDTH=".$sizex."&HEIGHT=".$sizey."&SRS=EPSG:".$epsg."&BBOX=".$data['el'].",".$data['eb'].",".$data['er'].",".$data['et']."";
$pdf->setXY(12, 45);
$pdf->Image($mapURL,null,null,$image_redimension[0], $image_redimension[1], "PNG");
$pixelToMmHeightScale = $image_redimension[1] + 45;
$pdf->setXY(12, $pixelToMmHeightScale);
$titre = "1: $scale";
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(0,4,$titre,false,'L',false);

/************************************************************
Output PDF Report
************************************************************/
$pdf->Output('I',$title_ref . '.pdf');

/************************************************************
Suppression image temporaire
************************************************************/
unlink($image);

//ob_end_flush();