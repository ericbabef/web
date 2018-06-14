<?php

require_once('model/actionManager.php');
require_once('model/functionUtile.php');

function viewAdmin()
{
    if (isset($_POST) && !empty($_POST['login']) && !empty($_POST['pass'])) {
    	//extract($_POST);

        if (isset($_POST['login'])) $login = htmlentities($_POST['login']);
        else $login = null;
        if (isset($_POST['pass'])) $pass = sha1($_POST['pass']);
        else $pass = null;
        if (!empty($login) && !empty($pass)) {
           $action_manager = new ActionManager();
    	   $user_connect =  $action_manager->getUser($login, $pass);
    	   $nb_row_req = $user_connect->rowCount();
    	   if ($nb_row_req > 0){
                $_SESSION['login'] = $login;
                header("Location: index.php?action=home");
                die();
    	   }
    	   else {
    	       	$pg_content_log = logWarning("Impossible d'établir la connexion : login ou mot de passe invalide");
                require('view/backend/viewAdmin.php');
    	   }
        }
	}
	else {
		require('view/backend/viewAdmin.php');
	}
}
function viewHome()
{
    $login = $_SESSION['login'];
    $datetimefrm_short = dateFunction();
    $action_manager = new ActionManager();
    $nb_location = $action_manager->nbLocation();
    $nb_location_fetch = $nb_location->fetch(PDO::FETCH_ASSOC);
    $nb_location_current_date = $action_manager->nbLocationCurrentDate();
    $nb_location_current_date_fetch = $nb_location_current_date->fetch(PDO::FETCH_ASSOC);
    $nb_location_laps_date = $action_manager->nbLocationLapsDate();
    $nb_location_laps_date_fetch = $nb_location_laps_date->fetch(PDO::FETCH_ASSOC);
    if ($_GET['mess'] == 'good'){
        $pg_content_log = logSuccess("Opération effectuée avec succès");
    }
    elseif ($_GET['mess'] == 'bad'){
        $pg_content_log = logWarning("Un problème a eu lieu lors de l'opération");
    }
    require('view/backend/viewHome.php');

}
function viewDeleteLocation()
{
    
    $action_manager = new ActionManager();
    $delete_location = $action_manager->deleteLocation($_GET['id']);
    //require('view/backend/viewDeleteLocation.php');
    $nb_row_req = $delete_location->rowCount();
    if ($nb_row_req > 0){
        header("Location: index.php?action=home&mess=good");
        $delete_location->closeCursor();
        exit;
    }
    else {
        header("Location: index.php?action=home&mess=bad");
        $delete_location->closeCursor();
        exit;
    }

}
function viewAddLocationForm()
{
    $login = $_SESSION['login'];
    $datetimefrm_short = dateFunction();
    $action_manager = new ActionManager();
    $type_rdm = $action_manager->typeRdm();
    $type_rdm_fetch = $type_rdm->fetchAll(PDO::FETCH_ASSOC);
    require('view/backend/viewAddLocation.php');
}
function viewAddLocation()
{
    if (!empty($_POST['nom'])) {
        $action_manager = new ActionManager();
        $add_location = $action_manager->addLocation($_POST['desc'], $_POST['mail'], $_POST['adresse'], $_POST['site_web'], $_POST['tel'], $_POST['type'], $_POST['ville'], $_POST['nom'], $_POST['lat'], $_POST['lng']);
        $nb_row_req = $add_location->rowCount();
        if ($nb_row_req > 0){
            header("Location: index.php?action=home&mess=good");
            $add_location->closeCursor();
            exit;
        }
        else {
            header("Location: index.php?action=home&mess=bad");
            $add_location->closeCursor();
            exit;
        }
    }
}
function viewEditLocationForm()
{
    $login = $_SESSION['login'];
    $datetimefrm_short = dateFunction();
    $action_manager = new ActionManager();
    $edit_location = $action_manager->editLocation($_GET['id']);
    $edit_location_fetch = $edit_location->fetch(PDO::FETCH_ASSOC);
    $type_rdm = $action_manager->typeRdm();
    $type_rdm_fetch = $type_rdm->fetchAll(PDO::FETCH_ASSOC);
    require('view/backend/viewEditLocation.php');
}
function viewEditLocation()
{
    if (!empty($_POST['nom'])) {
        $action_manager = new ActionManager();
        $edit_location = $action_manager->editLocation2($_GET['id'], $_POST['desc'], $_POST['mail'], $_POST['adresse'], $_POST['site_web'], $_POST['tel'], $_POST['type'], $_POST['ville'], $_POST['nom'], $_POST['lat'], $_POST['lng']);
        $nb_row_req = $edit_location->rowCount();
        if ($nb_row_req > 0){
            header("Location: index.php?action=home&mess=good");
            $edit_location->closeCursor();
            exit;
        }
        else {
            header("Location: index.php?action=home&mess=bad");
            $edit_location->closeCursor();
            exit;
        }
    }
}