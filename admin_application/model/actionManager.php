<?php

require_once("manager.php");

class ActionManager extends Manager
{
    public function getUser($login, $pass)
    {
        $db = $this->pgConnection();
        $req = $db->prepare("SELECT * FROM public.user WHERE login = ? and pass = ?");
        $req->execute(array($login, $pass));
        return $req;
    }

    public function deleteLocation($getId)
    {
        $db = $this->pgConnection();
        $req = $db->prepare("DELETE FROM public.rdm WHERE id = :id");
        $req->execute(array('id' => $getId));
        return $req;
    }

    public function addLocation($postDesc, $postMail, $postAdresse, $postSiteWeb, $postTel, $postType, $postVille, $postNom, $postLat, $postLng)
    {
        $db = $this->pgConnection();
        $req = $db->prepare("INSERT INTO rdm(description, mail, filtre, adresse, site_web, telephone, type_rdm, ville, nom, geom, date) VALUES(:description, :mail, :adresse || ' - ' || UPPER(:nom), :adresse, :site_web, :telephone, :type, UPPER(:ville), UPPER(:nom), ST_SetSRID(ST_MakePoint(:lng, :lat), 4326), current_timestamp)");
        $req->execute(array('description' => $postDesc, 'mail' => $postMail, 'adresse' => $postAdresse, 'site_web' => $postSiteWeb, 'telephone' => $postTel, 'type' => $postType, 'ville' => $postVille, 'nom' => $postNom, 'lng' => $postLng, 'lat' => $postLat));
        return $req;
    }

    public function editLocation($getId)
    {
        $db = $this->pgConnection();
        $req = $db->prepare("SELECT id, description, mail, adresse, site_web, telephone, type_rdm, ville, nom, ST_X(geom) as x, ST_Y(geom) as y FROM public.rdm WHERE id = :id");
        $req->execute(array('id' => $getId));
        return $req;
    }

    public function editLocation2($getId, $postDesc, $postMail, $postAdresse, $postSiteWeb, $postTel, $postType, $postVille, $postNom, $postLat, $postLng)
    {
        $db = $this->pgConnection();
        $req = $db->prepare("UPDATE public.rdm SET description = :description, mail = :mail, filtre = :adresse || ' - ' || UPPER(:nom), adresse = :adresse, site_web = :site_web, telephone = :telephone, type_rdm = :type, ville = UPPER(:ville), nom = UPPER(:nom), geom = ST_SetSRID(ST_MakePoint(:lng, :lat), 4326) WHERE id = :id");
        $req->execute(array('id' => $getId, 'description' => $postDesc, 'mail' => $postMail, 'adresse' => $postAdresse, 'site_web' => $postSiteWeb, 'telephone' => $postTel, 'type' => $postType, 'ville' => $postVille, 'nom' => $postNom, 'lng' => $postLng, 'lat' => $postLat));
        return $req;
    }

    public function typeRdm(){
        $db = $this->pgConnection();
        $req = $db->prepare("SELECT * FROM public.type_rdm");
        $req->execute();
        return $req;
    }

    public function nbLocation(){
        $db = $this->pgConnection();
        $req = $db->prepare("SELECT COUNT(*) AS total FROM public.rdm");
        $req->execute();
        return $req;
    }

    public function nbLocationCurrentDate(){
        $db = $this->pgConnection();
        $req = $db->prepare("SELECT COUNT(*) AS total FROM public.rdm WHERE date = (CURRENT_DATE)");
        $req->execute();
        return $req;
    }

    public function nbLocationLapsDate(){
        $db = $this->pgConnection();
        $req = $db->prepare("SELECT COUNT(*) AS total FROM public.rdm WHERE date >= (CURRENT_DATE - INTERVAL '30 day')");
        $req->execute();
        return $req;
    }
}