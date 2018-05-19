<?php
include '../BDD/bdd.php';
$bdd = connexionbd();

$requete='INSERT INTO Publicite (titre,nomFournisseur,lienPub,lieu)
   SELECT :titre,:nomFournisseur,:lienPub,nom FROM Lieu WHERE nom = :lieu';


$req = $bdd->prepare($requete);

// GESTION DES PUB
$tmpNamePUB = $_FILES['fichierPub']['tmp_name'];

if(file_exists($tmpNamePUB) || is_uploaded_file($tmpNamePUB)) {
    $nom = $_FILES['fichierPub']['name'];

    move_uploaded_file($tmpNamePUB, "../files/pub/".$nom);
    $pub = "files/pub/" . $nom;

} else {
  $pub=null;
}

$req->execute(array(
  'titre' => $_REQUEST['titrePub'],
  'nomFournisseur' => $_REQUEST['nomFournisseur'],
  'lienPub' => $pub,
  'lieu' => $_REQUEST['lieu'],

));

echo $_REQUEST['titrePub'];
echo $_REQUEST['nomFournisseur'];
echo "$pub";
echo $_REQUEST['lieu'];
header("Refresh: 5; URL=../ajoutPublicite.php");

?>
