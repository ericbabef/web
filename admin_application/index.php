<?php
require('controller/frontend.php');
require('controller/backend.php');
// emplacement des sessions
ini_set('session.save_path', __DIR__.'/sessions');
// ouverture de la session
session_name("rdm");
session_start();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'index') {
        viewIndex();
    }
    elseif ($_GET['action'] == 'admin') {
    	// suppression des variables de la session
        if (isset($_SESSION['login'])) session_unset();
        viewAdmin();
    }
    elseif ($_GET['action'] == 'home') {
    	if (isset($_SESSION['login'])) {
    		viewHome();
    	}
    	else {
    		header("Location: index.php?action=admin");
			die();
    	}
    }
    elseif ($_GET['action'] == 'delete') {
        if (isset($_SESSION['login']) && isset($_GET['id']) && $_GET['id'] > 0) {
            viewDeleteLocation();
        }
        else {
            header("Location: index.php?action=admin");
            die();
        }
    }
    elseif ($_GET['action'] == 'viewAdd') {
        if (isset($_SESSION['login'])) {
            viewAddLocationForm();
        }
        else {
            header("Location: index.php?action=admin");
            die();
        }
    }
    elseif ($_GET['action'] == 'add') {
        if (isset($_SESSION['login'])) {
            viewAddLocation();
        }
        else {
            header("Location: index.php?action=admin");
            die();
        }
    }
    elseif ($_GET['action'] == 'viewEdit') {
        if (isset($_SESSION['login']) && isset($_GET['id']) && $_GET['id'] > 0) {
            viewEditLocationForm();
        }
        else {
            header("Location: index.php?action=admin");
            die();
        }
    }
    elseif ($_GET['action'] == 'edit') {
        if (isset($_SESSION['login']) && isset($_GET['id']) && $_GET['id'] > 0) {
            viewEditLocation();
        }
        else {
            header("Location: index.php?action=admin");
            die();
        }
    }
    elseif ($_GET['action'] == 'logout') {
        // suppression des variables de la session
        session_unset();
        // destruction de la session
        session_destroy();
        header("Location: index.php?action=admin");
        die();
    }
}
else {
    viewIndex();
}