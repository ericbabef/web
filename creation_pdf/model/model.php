<?php
require('connect.php');

//path xml
$path_xml = "public/config-layer/configLayer.xml";
//création d'un array à partir du xml
$xml=simplexml_load_file($path_xml) or die("Error: Cannot create object");

//retourne les attributs du layer de référence
function GetValueLayerRef($bdd, $field, $table, $field_cible, $ref) {
    $query = $bdd->prepare("SELECT ".$field." FROM ".$table." WHERE ".$field_cible." = ?");
	$query->execute(array($ref));
	$result = $query->fetchAll(PDO::FETCH_ASSOC);
	return $result;
}

//retourne les attributs de la PJ
function GetAttachmentLayerRef($bdd, $table_ref, $table_ref_attachment, $field_cible, $ref) {
    $query = $bdd->prepare("SELECT b.content_type, b.att_name, b.data, encode(b.data, 'base64') FROM ".$table_ref." a, ".$table_ref_attachment." b WHERE a.globalid = b.rel_globalid AND a.".$field_cible." = ?");
	$query->execute(array($ref));
	return $query;
}

//converti le texte encodé en image
function base64ToImg($base64_string, $output_file) {
    $ifp = fopen( $output_file, 'wb' ); 
    $data = explode( ',', $base64_string );
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );
    fclose( $ifp ); 
    return $output_file; 
}

//retourne les attributs de(s) layer(s) intersecté(s)
function GetValueLayerIntersect($bdd, $field_intersect, $table_ref, $table_intersect, $field_cible, $ref) {
    $query = $bdd->prepare("SELECT ".$field_intersect." FROM ".$table_ref." a, ".$table_intersect." b WHERE ST_INTERSECTS(a.shape, b.shape) AND a.".$field_cible." = ?");
	$query->execute(array($ref));
	return $query;
}

//retourne l'étendue cartographique du layer de référence --> utile pour le wms (bbox)
//fonctions propres au ST_Geometry d'ESRI
function GetExtentLayerRef($bdd, $table_ref, $field_cible, $ref) {
    $query = $bdd->prepare("select st_minx(st_envelope(st_buffer(a.shape, 50))) as minx, st_maxx(st_envelope(st_buffer(a.shape, 50))) as maxx, st_miny(st_envelope(st_buffer(a.shape, 50))) as miny, st_maxy(st_envelope(st_buffer(a.shape, 50))) as maxy from ".$table_ref." a where a.".$field_cible." = ? group by minx, maxx, miny, maxy");
	$query->execute(array($ref));
	return $query;
}

//calcul la longueur en % des cellules
function CalcLenthCell($x, $y) {
	$a = $x * ($y / 100);
	return $a;
}

//redimensionnement de l'image
function ImageRedimension($width_pixel, $height_pixel, $width_pixel_deisred, $height_pixel_deisred) {
	$ratio = $height_pixel / $width_pixel;
	$desired_ratio = $height_pixel_deisred / $width_pixel_deisred;
	if ($ratio > $desired_ratio){
		$size_y = $height_pixel_deisred;
		$size_x = $size_y / $ratio;
		//taille de l'image en mm (convertion pixel)
		$pixel_to_cm_width = $size_x / 37.7953;
		$pixel_to_mm_width = $pixel_to_cm_width * 10;
		$pixel_to_cm_height = $size_y / 37.7953;
		$pixel_to_mm_height = $pixel_to_cm_height * 10;
		return array($pixel_to_mm_width, $pixel_to_mm_height, $size_x, $size_y);
	}
	else{
		$size_x = $width_pixel_deisred;
		$size_y = $size_x * $ratio;		
		$pixel_to_cm_width = $size_x / 37.7953;
		$pixel_to_mm_width = $pixel_to_cm_width * 10;
		$pixel_to_cm_height = $size_y / 37.7953;
		$pixel_to_mm_height = $pixel_to_cm_height * 10;
		return array($pixel_to_mm_width, $pixel_to_mm_height, $size_x, $size_y);	
	}
}

//url du wms sans les paramètre width, height et bbox
$path_wms = "http://ericbabef.alwaysdata.net/cgi-bin/mapserv?map=mapfile/dict.map&service=wms&REQUEST=GetMap&VERSION=1.0.0&FORMAT=image/png&LAYERS=parcelle,tam,dict_polygon&STYLES=";

class PDF extends FPDF
{
	// En-tête
	function Header()
	{

		//epaisseur ligne pour la mise en forme
		$this->SetLineWidth(0.5);
		// Rectangle
		$this->SetFillColor(100);
		//bordure entête
		$this->rect(2,2,206, 22, 'DF');
		
		//Bordure sur toutes les pages
		$this->rect(2,2,206, 293);
		
		// Logo
		$this->Image('public/images/logo.png',10,8,35);
		
		// Position multicell
		$this->setXY(10, 8);
		
		$path_xml = "public/config-layer/configLayer.xml";
		$xml=simplexml_load_file($path_xml) or die("Error: Cannot create object");
		foreach ($xml->layer_ref as $layer) {
			$title_ref = utf8_decode($layer["title"]);
		}
		
		// Police
		$this->SetFont('Arial','B',20);
		//texte couleur
		$this->SetTextColor(0);
		// Titre
		$this->MultiCell(0,10,$title_ref,0,'C',false);
		//position ordonnée par défaut
		$this->setY(30);
		
	}
	
	// pied de page
	function Footer()
	{
		$today = getdate();
		// Positionnement à 1 cm du bas
		$this->SetY(-12);
		// Police
		$this->SetFont('Arial','I',8);
		$this->SetTextColor(128);
		$this->Cell(0,10,'Eric BABEF',0,0,'C', false);
		$this->Ln(3);
		$this->Cell(0,10,'Géomaticien',0,0,'C', false);
		$this->SetX(200);
		$this->Cell(0,10,'Page '.$this->PageNo().' / {nb}',0,0,'C', false);
		$this->SetX(-390);
		$this->Cell(0,10,''. $today['mday'] . "/" . $today['mon'] . "/" . $today['year'] . '',0,0,'C', false);
	}
	
	// tableau multicell
	// en cas de saut de page, récupère la position précédente à savoir ici $this->setY(30) déclaré en entête
	var $widths;
	var $aligns;

	function SetWidths($w)
	{
		//Tableau des largeurs de colonnes
		$this->widths=$w;
	}

	function SetAligns($a)
	{
		//Tableau des alignements de colonnes
		$this->aligns=$a;
	}

	function RowHeader($data)
	{
		//Calcule la hauteur de la ligne
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Effectue un saut de page si nécessaire
		$this->CheckPageBreak($h);
		//Dessine les cellules
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Sauve la position courante
			$x=$this->GetX();
			$y=$this->GetY();
			//Dessine le cadre
			$this->Rect($x,$y,$w,$h);
			//Imprime le texte avec fond de couleur
			$this->MultiCell($w,5,$data[$i],0,$a, true);
			//Repositionne à droite
			$this->SetXY($x+$w,$y);
		}
		//Va à la ligne
		$this->Ln($h);
	}
	
	function RowAttribute($data)
	{
		//Calcule la hauteur de la ligne
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=5*$nb;
		//Effectue un saut de page si nécessaire
		$this->CheckPageBreak($h);
		//Dessine les cellules
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Sauve la position courante
			$x=$this->GetX();
			$y=$this->GetY();
			//Dessine le cadre
			$this->Rect($x,$y,$w,$h);
			//Imprime le texte
			$this->MultiCell($w,5,$data[$i],0,$a);
			//Repositionne à droite
			$this->SetXY($x+$w,$y);
		}
		//Va à la ligne
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		//Si la hauteur h provoque un débordement, saut de page manuel
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//Calcule le nombre de lignes qu'occupe un MultiCell de largeur w
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
	
}