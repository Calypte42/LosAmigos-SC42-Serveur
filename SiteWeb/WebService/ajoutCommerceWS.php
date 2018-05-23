<?php
include '../BDD/bdd.php';
$bdd = connexionbd();


$requete='INSERT INTO Commerce (nom,pseudoCommercant,latitude,longitude,numeroTelephone,adresse,description,horaires,localisation)
   SELECT :nom,:pseudoCommercant,:latitude,:longitude,:numeroTelephone,:adresse,:description,:horaires,nom FROM Lieu WHERE nom = :lieu';


$req = $bdd->prepare($requete);

$req->execute(array(
  'nom' => $_REQUEST['nomCommerce'],
  'pseudoCommercant' => $_REQUEST['pseudoCommercant'],
  'latitude' => $_REQUEST['latitude'],
  'longitude' => $_REQUEST['longitude'],
  'numeroTelephone' =>$_REQUEST['tel'],
  'adresse' => $_REQUEST['adresse'],
  'description' =>$_REQUEST['description'],
  'horaires' => $_REQUEST['horaire'],
  'lieu' => $_REQUEST['lieu'],

));


header("Refresh: 5; URL=../ajoutCommerce.php");

?>
