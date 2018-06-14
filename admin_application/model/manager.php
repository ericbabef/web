<?php
// connexion à la base de données
class Manager
{
    public function pgConnection()
    {
        
        $dsn = array(
		'phptype'	=> 'pgsql',
		'username'	=> '',
		'password'	=> '',
		'hostspec'	=> '',
		'database'	=> ''
		);
		try { 
			$bdd = new PDO($dsn['phptype'].':host='.$dsn['hostspec'].';dbname='.$dsn['database'], $dsn['username'], $dsn['password']); 
			return $bdd;
		}
		catch (PDOException $e) { 
			print "Vous ne pouvez pas utiliser la base : " . $e->getMessage() . "<br/>"; die(); 
		}
		// détail sur les erreurs
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}