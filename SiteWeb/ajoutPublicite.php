<?php
include 'BDD/bdd.php';
include 'entete.php';
$bdd=connexionbd();
?>

<h1> Ici vous pouvez enregistrer une nouvelle publicité ! </h1>

<form method="POST" action="./WebService/ajoutPubWS.php" enctype="multipart/form-data">
  <label for="titrePub">Titre de votre publicité</label>
  <input type="text" maxlength="50" name="titrePub" required/><br/>
  <label for="nomFournisseur">Nom du fournisseur de la publicité :</label>
  <input type="text" maxlength="50" name="nomFournisseur" required /><br/>
  <label for="fichierPub">Fichier de la publicité (mp4 ou jpg) :</label>
  <input type="file" name="fichierPub" required/><br/>
  <label for="lieu">Ville de la publicité :</label>
  <select name="lieu" required>
    <?php
    $requete='SELECT nom from Lieu ORDER BY nom LIMIT 5';
    $value=requete($bdd,$requete);
    echo "$value";
    foreach ($value as $array) {
      $nom=$array['nom'];
      echo "$nom";
      echo "<option value=\"$nom\">$nom</option>";
    }
      ?>
      <option selected value="MONTPELLIER">MONTPELLIER</option>
        <option value="PARIR">PARIS</option>
  </select>
  <input type="submit" value="Valider" />
</form>
