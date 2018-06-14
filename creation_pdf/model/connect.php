<?php
$dsn = array(
	'phptype'	=> 'pgsql',
	'username'	=> '',
	'password'	=> '',
	'hostspec'	=> '',
	'database'	=> ''
);

// connexion à la base de données
try { $bdd = new PDO($dsn['phptype'].':host='.$dsn['hostspec'].';dbname='.$dsn['database'], $dsn['username'], $dsn['password']); }
catch (PDOException $e) { print "Vous ne pouvez pas utiliser la base : " . $e->getMessage() . "<br/>"; die(); }

// détail sur les erreurs
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);