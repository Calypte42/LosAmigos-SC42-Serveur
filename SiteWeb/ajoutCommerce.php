<?php
include 'BDD/bdd.php';
include 'entete.php';
$bdd=connexionbd();
?>

<h1> Ici vous pouvez enregistrer un nouveau commerce ! </h1>

<form method="POST" action="./WebService/ajoutCommerceWS.php">

  <label for="nomCommerce">Nom de votre commerce</label>
  <input type="text" required maxlength="50" name="nomCommerce"/> <br/>
  <label for="pseudoCommercant">Votre pseudo sur SmartCity :</label>
  <input type="text" required maxlength="20" name="pseudoCommercant" /><br/>



  <label for="lieu">Ville du commerce :</label>
  <select required name="lieu">
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
        <option value="PARIS">PARIS</option>
  </select>
  <br/>
  <label for="longitude">longitude de votre commerce : </label>
  <input type="text" required maxlength="50" name="longitude" /><br/>
  <label for="latitude">latitude de votre commerce: </label>
  <input type="text" required maxlength="50" name="latitude" /><br/>
  <label for="tel">Numero de téléphone: </label>
  <input type="text" maxlength="12" name="tel" /><br/>
  <label for="adresse">Adresse : </label>
  <input type="text" maxlength="300" name="adresse" /><br/>
  <label for="description">Description : </label>
  <input type="text" maxlength="500" name="description" /><br/>
  <label for="horaire">Horaire : </label>
  <input type="text" maxlength="400" name="horaire" /><br/>

  <input type="submit" value="Valider" />
</form>
