<?php

// cette fonction vous connecte à la base de données et retourne
// un objet grâce auquel vous allez effectuer des requêtes SQL
function connexionbd() {

    require 'login.php';

	// chaîne de connexion pour PDO
	$dsn = "mysql:host=$host;dbname=$dbname";

	// connexion au serveur de bases de données
	$bd = new PDO($dsn, $username, $password);

	return $bd;
}

// cette fonction effectue une requête SQL. On doit lui fournir
// l'objet base de données et la requête
function requete($bd, $req) {

	// appel de la méthode query() sur l'objet base de données :
	// la requête est traitée par le serveur et retourne un pointeur de résultat
	$resultat = $bd->query($req);

	// on demande à ce pointeur d'aller chercher toutes les données de résultat
	// d'un coup - on obtient un tableau de tableaux associatifs (un par ligne de la table)
	// Note : dans le cas d'une insertion, on ne récupère pas le résultat
	if ($resultat) {
		$tableau = $resultat->fetchAll(PDO::FETCH_ASSOC);
		// on retourne ce tableau
		return $tableau;
	}
}

?>
