<?php
include '../BDD/bdd.php';
$bdd = connexionbd();


$requete='INSERT INTO Commerce (nom,pseudoCommercant,latitude,longitude,localisation)
   SELECT :nom,:pseudoCommercant,:latitude,:longitude,nom FROM Lieu WHERE nom = :lieu';


$req = $bdd->prepare($requete);

$req->execute(array(
  'nom' => $_REQUEST['nomCommerce'],
  'pseudoCommercant' => $_REQUEST['pseudoCommercant'],
  'latitude' => $_REQUEST['latitude'],
  'longitude' => $_REQUEST['longitude'],
  'lieu' => $_REQUEST['lieu'],

));


header("Refresh: 5; URL=../ajoutCommerce.php");

?>
