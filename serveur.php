<?php
	require_once __DIR__.'/vendor/autoload.php';
	require_once 'connexionBDD.php';
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;

	function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

	$app = new Silex\Application();
	$app['debug']=true;

	/**************GET - THEME********************/
	$app->get('/themes', function () use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * from Theme";

		$query = $connexion->query($sql);
		while ($donnees=$query->fetch()) {
			$data[]=Array('id'=>$donnees['id'],'nom'=>$donnees['nom'],'idNomPere'=>$donnees['idNomPere']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($data)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/themes/{id}', function ($id) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Theme WHERE id = :id";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/themesprincipaux', function () use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Theme WHERE idNomPere is null";

		$query = $connexion->query($sql);
		while ($donnees=$query->fetch()) {
			$data[]=Array('id'=>$donnees['id'],'nom'=>$donnees['nom'],'idNomPere'=>$donnees['idNomPere']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($data)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

    $app->get('/themes/listeThemesEnfant/id/{id}', function ($id) use ($app) {
	   	$connexion=connexionbd();
		$sql="SELECT * FROM Theme WHERE idNomPere = ".$id. " ORDER BY nom";
		$query = $connexion->query($sql);
        $data=null;
        while ($donnees=$query->fetch()) {
			$data[]=Array('id'=>$donnees['id'],'nom'=>$donnees['nom'],'idNomPere'=>$donnees['idNomPere']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode($data));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

    $app->get('/themes/listeThemesEnfant/nom/{nom}', function ($nom) use ($app) {
	   	$connexion=connexionbd();
		$sql="SELECT * FROM Theme WHERE idNomPere = (SELECT id FROM Theme WHERE nom LIKE '".$nom."') ORDER BY nom";
		$query = $connexion->query($sql);
        $data=null;
		while ($donnees=$query->fetch()) {
			$data[]=Array('id'=>$donnees['id'],'nom'=>$donnees['nom'],'idNomPere'=>$donnees['idNomPere']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode($data));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	/********** GET - VILLES ****************/

	$app->get('/villes/{nom}', function ($nom) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Lieu WHERE nom LIKE '".$nom."'";
		$stmt=$connexion->prepare($sql);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});


	$app->get('/villes/proximite/{latitude}/{longitude}', function ($latitude,$longitude) use ($app) {
	   	$connexion=connexionbd();
	   	$formule="(6366*acos(cos(radians(".$latitude."))*cos(radians(`latitude`))*cos(radians(`longitude`) -radians(".$longitude."))+sin(radians(".$latitude."))*sin(radians(`latitude`))))";

		$sql="SELECT nom, ".$formule." AS dist, latitude, longitude FROM Lieu WHERE ".$formule." <= 10 ORDER BY dist ASC LIMIT 3";
		$query = $connexion->query($sql);
		while ($donnees=$query->fetch()) {
			$data[]=Array('nom'=>$donnees['nom'],'dist' => $donnees['dist'],'latitude'=>$donnees['latitude'],'longitude'=>$donnees['longitude']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($data)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	/********** GET - UTILISATEUR **************/

	$app->get('/utilisateur/{pseudo}', function ($pseudo) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Utilisateur WHERE pseudo = :pseudo";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':pseudo', $pseudo);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/utilisateur/{pseudo}/{mdp}', function ($pseudo, $mdp) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Utilisateur WHERE pseudo = :pseudo AND MDP = :mdp";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':pseudo', $pseudo);
		$stmt->bindParam(':mdp', $mdp);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/choisiVille/{pseudo}', function ($pseudo) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT pseudo, nom, latitude, longitude FROM Choisi, Lieu WHERE nomLieu = nom AND pseudo = '".$pseudo."'";
		$stmt=$connexion->prepare($sql);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});
//a ete modifié <-


	$app->get('/utilisateur/apprecie/{pseudo}', function ($pseudo) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Apprecie WHERE pseudo = :pseudo";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':pseudo', $pseudo);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

    $app->get('/utilisateur/favoriCommerce/{pseudo}/{id}', function ($pseudo, $id) use ($app) {
        $connexion=connexionbd();

        $sql="SELECT * FROM FavoriCommerce WHERE pseudo = '".$pseudo."' AND idCommerce = :id";
        $stmt=$connexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $response = new Response();
        $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    });

    $app->get('/utilisateur2/favorisCommerce/{pseudo}', function ($pseudo) use ($app) {
        $connexion=connexionbd();

        $sql="SELECT * FROM Commerce WHERE id IN (SELECT idCommerce FROM FavoriCommerce WHERE pseudo = '".$pseudo."') ORDER BY nom;";
        $query = $connexion->query($sql);
        $data=null;
		while ($donnees=$query->fetch()) {
			$data[]=Array('id'=>$donnees['id'],'nom'=>$donnees['nom'],
            'pseudoCommercant'=>$donnees['pseudoCommercant'],
            'localisation'=>$donnees['localisation'],
            'longitude'=>$donnees['longitude'],
            'latitude'=>$donnees['latitude']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($data)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
    });

	/********************* GET - COMMERCES **************/

    $app->get('/commerce/id/{id}', function ($id) use ($app) {
	   	$connexion=connexionbd();
		$sql="SELECT * FROM Commerce WHERE id = :id";
        $stmt=$connexion->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode($stmt->fetch(PDO::FETCH_OBJ)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

    $app->get('/commerce/theme/{id}/{localisation}', function ($id, $localisation) use ($app) {
	   	$connexion=connexionbd();
		$sql="SELECT * FROM Commerce WHERE id IN (SELECT idCommerce FROM Appartient WHERE idTheme = ".$id.") AND localisation='".$localisation."' ORDER BY nom;";
        $query = $connexion->query($sql);
        $data=null;
		while ($donnees=$query->fetch()) {
			$data[]=Array('id'=>$donnees['id'],'nom'=>$donnees['nom'],
            'pseudoCommercant'=>$donnees['pseudoCommercant'],
            'localisation'=>$donnees['localisation'],
            'longitude'=>$donnees['longitude'],
            'latitude'=>$donnees['latitude']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode($data));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/commerce/{localisation}', function ($localisation) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Commerce WHERE localisation = :localisation";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode($stmt->fetch(PDO::FETCH_OBJ)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->post('/utilisateurModifie', function (Request $request) use ($app) {
		$data = [];
			if ($content = $request->getContent()) {
					$data = json_decode($content, true);
			}
		$connexion=connexionbd();
		$sql="UPDATE Utlisateur SET pseudo = :pseudo, MDP = :MDP,dateNaissance = :dateNaissance,sexe = :sexe,taille= :taille,poids= :poids WHERE pseudo = :pseudo";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':pseudo', $data['pseudo']);
		$stmt->execute(array('pseudo'=>$data['pseudo'], 'MDP'=>$data['MDP'], 'dateNaissance'=>$data['dateNaissance'], 'sexe'=>$data['sexe'],'taille'=>$data['taille'],'poids'=>$data['poids']));
		return $app->json($data, 201);
	});

	$app->get('/commerce/{localisation}/{theme}', function ($localisation, $theme) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Commerce, Appartient WHERE id = idCommerce AND localisation = :localisation AND theme = :theme";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':theme', $theme);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode($stmt->fetch(PDO::FETCH_OBJ)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

    $app->get('/commerce/proximite/{idTheme}/{localisation}/{latitude}/{longitude}', function ($idTheme, $localisation, $latitude, $longitude) use ($app) {
	   	$connexion=connexionbd();
	   	$formule="(6366*acos(cos(radians(".$latitude."))*cos(radians(`latitude`))*cos(radians(`longitude`) -radians(".$longitude."))+sin(radians(".$latitude."))*sin(radians(`latitude`))))";

		$sql="SELECT id, pseudoCommercant, localisation, nom,".$formule." AS dist, latitude, longitude FROM Commerce WHERE (id IN (SELECT idCommerce FROM Appartient WHERE idTheme = $idTheme)) AND localisation = '$localisation' AND ".$formule." <= 10 ORDER BY dist ASC";

		$query = $connexion->query($sql);
        $data = null;
		while ($donnees=$query->fetch()) {
			$data[]=Array('id'=>$donnees['id'], 'localisation' =>$donnees['localisation'],'pseudoCommercant'=>$donnees['pseudoCommercant'], 'nom'=>$donnees['nom'],'dist' => $donnees['dist'],'latitude'=>$donnees['latitude'],'longitude'=>$donnees['longitude']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode($data));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

/*
	$app->get('/commerce/annonce/{localisation}/{theme}/{sexe}', function ($localisation, $theme, $sexe) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Commerce, Appartient WHERE id = idCommerce AND localisation = :localisation AND theme = :theme AND sexe = :sexe AND sexe = \"Mixte\"";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':theme', $theme);
		$stmt->bindParam(':sexe', $sexe);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/commerce/annonce/{localisation}/{sexe}', function ($localisation, $sexe) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Commerce WHERE localisation = :localisation AND sexe = :sexe AND sexe = \"Mixte\"";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':sexe', $sexe);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/commerce/annonce/{localisation}/{ageMin}/{ageMax}', function ($localisation, $ageMin, $ageMax) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Commerce WHERE localisation = :localisation AND ageMin >= :ageMin AND age <= :ageMax";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':ageMin', $ageMin);
		$stmt->bindParam(':ageMax', $ageMax);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});


	$app->get('/commerce/annonce/{localisation}/{theme}/{ageMin}/{ageMax}', function ($localisation, $theme, $ageMin, $ageMax) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Commerce, Appartient WHERE id = idCommerce AND theme = :theme AND localisation = :localisation AND ageMin >= :ageMin AND age <= :ageMax";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':ageMin', $ageMin);
		$stmt->bindParam(':ageMax', $ageMax);
		$stmt->bindParam(':theme', $theme);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});
	$app->get('/commerce/annonce/{localisation}/{sexe}/{ageMin}/{ageMax}', function ($localisation,$sexe, $ageMin, $ageMax) use ($app) {
	   	$connexion=connexionbd();
		$sql="SELECT * FROM Commerce WHERE localisation = :localisation AND ageMin >= :ageMin AND age <= :ageMax AND sexe = :sexe";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':ageMin', $ageMin);
		$stmt->bindParam(':ageMax', $ageMax);
		$stmt->bindParam(':sexe', $sexe);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});*/


	/******** Methode importante de filtrage des annonces par themes, par sexe et par categorie d'age ***************/
	$app->get('/annonces/filtre/{pseudo}/{localisation}', function ($pseudo, $localisation) use ($app) {
	   	$connexion=connexionbd();
	   	//recuperer l'utilisateur
	   	$sql="SELECT * FROM Utilisateur WHERE pseudo = :pseudo";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':pseudo', $pseudo);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if($user['sexe'] == 0){$sexe = 'Homme';}else{$sexe = 'Femme';}

		//calcul age
		$age = (time() - strtotime($user['dateNaissance'])) / 3600 / 24 / 365;

		$sql="SELECT DISTINCT ann.id, ann.titre, ann.contenu, l.idCommerce, l.idTheme, c.nom AS nomCommerce FROM Utilisateur u, Commerce c, Annonce ann, ListeAnnonce l, Apprecie ap WHERE u.pseudo = ap.pseudo AND l.idtheme = ap.idtheme AND c.localisation = '$localisation' AND c.id = l.idCommerce AND ap.pseudo = '$pseudo' AND $age >= ageMin AND $age <= ageMax AND (sexe = '$sexe' OR sexe = 'Mixte')";
        $data = null;
        $query=$connexion->query($sql);
			while ($donnees=$query->fetch()) {
				$data[]=Array('id'=>$donnees['id'],'titre'=>$donnees['titre'],'contenu'=>$donnees['contenu'],'idCommerce'=>$donnees['idCommerce'],'idTheme'=>$donnees['idTheme'],'nomCommerce'=>$donnees['nomCommerce']);
			}
	   	$response = new Response();
	    $response->setContent(json_encode($data));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

    $app->get('/annonces/{idCommerce}', function ($idCommerce) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT DISTINCT ann.id, ann.titre, ann.contenu, l.idCommerce, l.idTheme, c.nom AS nomCommerce FROM Commerce c, Annonce ann, ListeAnnonce l WHERE l.idCommerce = $idCommerce AND c.id = l.idCommerce";
        $data = null;
        $query=$connexion->query($sql);
			while ($donnees=$query->fetch()) {
				$data[]=Array('id'=>$donnees['id'],'titre'=>$donnees['titre'],'contenu'=>$donnees['contenu'],'idCommerce'=>$donnees['idCommerce'],'idTheme'=>$donnees['idTheme'],'nomCommerce'=>$donnees['nomCommerce']);
			}
	   	$response = new Response();
	    $response->setContent(json_encode($data));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

    $app->get('/annonces/favoris/{pseudo}', function ($pseudo) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT DISTINCT ann.id, ann.titre, ann.contenu, l.idCommerce, l.idTheme, c.nom AS nomCommerce FROM Commerce c, Annonce ann, ListeAnnonce l, FavoriCommerce f WHERE l.idCommerce = f.idCommerce AND c.id = l.idCommerce AND f.pseudo = '$pseudo'";
        $data = null;
        $query=$connexion->query($sql);
			while ($donnees=$query->fetch()) {
				$data[]=Array('id'=>$donnees['id'],'titre'=>$donnees['titre'],'contenu'=>$donnees['contenu'],'idCommerce'=>$donnees['idCommerce'],'idTheme'=>$donnees['idTheme'],'nomCommerce'=>$donnees['nomCommerce']);
			}
	   	$response = new Response();
	    $response->setContent(json_encode($data));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

    $app->get('/annonces/toutes/{pseudo}', function ($pseudo) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT DISTINCT ann.id, ann.titre, ann.contenu, l.idCommerce, l.idTheme, c.nom AS nomCommerce FROM Commerce c, Annonce ann, ListeAnnonce l, Choisi ch WHERE ch.pseudo = '$pseudo' AND c.localisation = ch.nomLieu AND ann.id = l.idAnnonce AND l.idCommerce = c.id";
        $data = null;
        $query=$connexion->query($sql);
			while ($donnees=$query->fetch()) {
				$data[]=Array('id'=>$donnees['id'],'titre'=>$donnees['titre'],'contenu'=>$donnees['contenu'],'idCommerce'=>$donnees['idCommerce'],'idTheme'=>$donnees['idTheme'],'nomCommerce'=>$donnees['nomCommerce']);
			}
	   	$response = new Response();
	    $response->setContent(json_encode($data));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

    $app->get('/annonce/{idAnnonce}', function ($idAnnonce) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT DISTINCT ann.id, ann.titre, ann.contenu, l.idCommerce, l.idTheme, c.nom AS nomCommerce FROM Commerce c, Annonce ann, ListeAnnonce l WHERE ann.id = $idAnnonce AND l.idAnnonce = ann.id AND c.id = l.idCommerce ";
        $stmt=$connexion->prepare($sql);
        $stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode($stmt->fetch(PDO::FETCH_OBJ)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});


/**************** GET RESEAU *************************/

// Recupere les reseaux d une ville dont la visibilite est a 1 et dont l'utilisateur n'est pas inscrit
	$app->get('/reseau/{localisation}/{pseudo}', function ($localisation,$pseudo) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Reseau WHERE localisation = '".$localisation."' AND visibilite=1 AND pseudoAdmin!='".$pseudo."' AND sujet NOT IN (SELECT sujetReseau FROM Adhere WHERE pseudo='".$pseudo."')";
		$query=$connexion->query($sql);
			while ($donnees=$query->fetch()) {
				$data[]=Array('sujet'=>$donnees['sujet'],'description'=>$donnees['description'],'pseudoAdmin'=>$donnees['pseudoAdmin'],'localisation'=>$donnees['localisation'],'visibilite'=>$donnees['visibilite']);
			}
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($data)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});
/*	$connexion=connexionbd();

	$sql="SELECT contenu,pseudoAuteur,sujetReseau FROM Message WHERE sujetReseau= '".$sujet."'";
	$query = $connexion->query($sql);
	while ($donnees=$query->fetch()) {
		$data[]=Array('sujetReseau'=>$donnees['sujetReseau'],'contenu'=>$donnees['contenu'],'pseudoAuteur'=>$donnees['pseudoAuteur']);
	}
		$response = new Response();
		$response->setContent(json_encode(utf8ize($data)));
	$response->headers->set('Content-Type', 'application/json');
		return $response;*/

	$app->get('/reseau/{localisation}/{sujet}', function ($localisation, $sujet) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Reseau WHERE localisation = :localisation AND sujet = :sujet";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':sujet', $sujet);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/reseau/{localisation}/{sujet}/{theme}', function ($localisation, $sujet, $theme) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Reseau r , Associe a WHERE a.sujetReseau = r.sujet AND localisation = :localisation AND sujet = :sujet AND theme =:theme ";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':sujet', $sujet);
		$stmt->bindParam(':theme', $theme);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/message/{sujet}', function ($sujet) use ($app) {
		$connexion=connexionbd();

		$sql="SELECT contenu,pseudoAuteur,sujetReseau FROM Message WHERE sujetReseau= '".$sujet."'";
		$query = $connexion->query($sql);
		while ($donnees=$query->fetch()) {
			$data[]=Array('sujetReseau'=>$donnees['sujetReseau'],'contenu'=>$donnees['contenu'],'pseudoAuteur'=>$donnees['pseudoAuteur']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($data)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;

	});


	$app->get('/reseau/{localisation}/{theme}', function ($localisation, $sujet, $theme) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Reseau r , Associe a WHERE a.sujetReseau = r.sujet AND localisation = :localisation AND theme =:theme ";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':theme', $theme);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/reseaux/{pseudoAdmin}', function ($pseudoAdmin) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Reseau WHERE pseudoAdmin = '".$pseudoAdmin."'";
		$query = $connexion->query($sql);
		while ($donnees=$query->fetch()) {
			$data[]=Array('sujet'=>$donnees['sujet'],'description'=>$donnees['description'],'pseudoAdmin'=>$donnees['pseudoAdmin'],'localisation'=>$donnees['localisation'],'visibilite'=>$donnees['visibilite']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($data)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;

	});

	$app->get('/reseauAccess/{pseudoAdmin}', function ($pseudoAdmin) use ($app) {
		$connexion=connexionbd();
		$sql="SELECT * FROM Reseau WHERE pseudoAdmin = '".$pseudoAdmin."' OR sujet in (SELECT sujetReseau FROM Adhere WHERE pseudo= '".$pseudoAdmin."')";
		$query = $connexion->query($sql);
		while ($donnees=$query->fetch()) {
			$data[]=Array('sujet'=>$donnees['sujet'],'description'=>$donnees['description'],'pseudoAdmin'=>$donnees['pseudoAdmin'],'localisation'=>$donnees['localisation'],'visibilite'=>$donnees['visibilite']);
		}
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($data)));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});

	$app->get('/reseau/{localisation}/{sujet}/message', function ($localisation, $sujet) use ($app) {
	   	$connexion=connexionbd();

		$sql="SELECT * FROM Reseau r , Message m WHERE r.sujet = m.sujetReseau AND localisation = :localisation AND sujet = :sujet";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':localisation', $localisation);
		$stmt->bindParam(':sujet', $sujet);
		$stmt->execute();
	   	$response = new Response();
	    $response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
		$response->headers->set('Content-Type', 'application/json');
	    return $response;
	});


/*********** POST - Utilisateur ***************/
	use Symfony\Component\HttpFoundation\ParameterBag;

	$app->before(function (Request $request) {
	    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
	        $data = json_decode($request->getContent(), true);
	        $request->request->replace(is_array($data) ? $data : array());
	    }
	});

	$app->post('/utilisateur', function (Request $request) use ($app) {
		$data = [];
	    if ($content = $request->getContent()) {
	        $data = json_decode($content, true);
	    }
		$connexion=connexionbd();
		$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql="INSERT INTO Utilisateur (pseudo,MDP,dateNaissance, sexe, taille, poids) VALUES ('".$data['pseudo']."','".$data['MDP']."','".$data['dateNaissance']."',".intval($data['sexe']).",".floatval($data['taille']).",".floatval($data['poids']).")";
		$connexion->exec($sql);

		return $app->json($data, 201);
    });

    $app->post('/utilisateur/apprecie', function (Request $request) use ($app) {
		$data = [];
	    if ($content = $request->getContent()) {
	        $data = json_decode($content, true);
	    }
		$connexion=connexionbd();
		$sql="INSERT INTO Apprecie(pseudo, idTheme) values (:pseudo, :idTheme)";
		$stmt=$connexion->prepare($sql);
		$stmt->execute(array('pseudo'=>$data['pseudo'], 'idTheme'=>$data['idTheme']));
		return $app->json($data, 201);
    });

    $app->post('/utilisateur/favoriCommerce/ajout', function (Request $request) use ($app) {
		$data = [];
	    if ($content = $request->getContent()) {
	        $data = json_decode($content, true);
	    }
		$connexion=connexionbd();
		$sql="INSERT INTO FavoriCommerce(pseudo, idCommerce) values (:pseudo, :idCommerce)";
		$stmt=$connexion->prepare($sql);
		return $stmt->execute(array('pseudo'=>$data['pseudo'], 'idCommerce'=>$data['idCommerce']));
    });

    $app->get('/utilisateur/favoriCommerce/supprimer/{pseudo}/{idCommerce}', function ($pseudo, $idCommerce) use ($app) {
        $connexion=connexionbd();

	    $sql="DELETE FROM FavoriCommerce WHERE pseudo='".$pseudo."' AND idCommerce = $idCommerce";
	    $stmt=$connexion->prepare($sql);
	    return $stmt->execute();
    });

    $app->post('/utilisateur/choisiVille', function (Request $request) use ($app) {
		$data = [];
	    if ($content = $request->getContent()) {
	        $data = json_decode($content, true);
	    }
		$connexion=connexionbd();
		$sql="INSERT INTO Choisi (pseudo, nomLieu) VALUES ('".$data['pseudo']."', '".$data['nomVille']."')";
		$stmt=$connexion->prepare($sql);
		$stmt->execute();
		return $app->json($data, 201);
    });


    /************** POST - Reseau ************/
   /************** POST - Reseau ************/

		$app->post('/reseau/ajoutReseau', function (Request $request) use ($app) {
		$data = [];
			if ($content = $request->getContent()) {
					$data = json_decode($content, true);
			}
		$connexion=connexionbd();
		$sql="INSERT INTO Reseau(sujet,description,pseudoAdmin,localisation,visibilite) values (:sujet,:description,:pseudoAdmin,:localisation,:visibilite)";
		$stmt=$connexion->prepare($sql);
		$stmt->execute(array('sujet'=>$data['sujet'],'description'=>$data['description'],'pseudoAdmin'=>$data['pseudoAdmin'],'localisation'=>$data['localisation'], 'visibilite'=>$data['visibilite']));
		return $app->json($data, 201);
		});


    $app->post('/reseau/message', function (Request $request) use ($app) {
		$data = [];
	    if ($content = $request->getContent()) {
	        $data = json_decode($content, true);
	    }
		$connexion=connexionbd();
		$sql="INSERT INTO Message(contenu, pseudoAuteur, sujetReseau) values (:contenu, :pseudoAuteur, :sujetReseau)";
		$stmt=$connexion->prepare($sql);
		$stmt->execute(array('contenu'=>$data['contenu'],'pseudoAuteur'=>$data['pseudoAuteur'], 'sujetReseau'=>$data['sujetReseau']));
		return $app->json($data, 201);
    });


    $app->post('/reseau/association/theme', function (Request $request) use ($app) {
		$data = [];
	    if ($content = $request->getContent()) {
	        $data = json_decode($content, true);
	    }
		$connexion=connexionbd();
		$sql="INSERT INTO Associe(sujetReseau, theme) values (:sujetReseau, :theme)";
		$stmt=$connexion->prepare($sql);
		$stmt->execute(array('sujetReseau'=>$data['sujetReseau'], 'theme'=>$data['theme']));
		return $app->json($data, 201);
    });

    $app->post('/reseau/evenement', function (Request $request) use ($app) {
		$data = [];
	    if ($content = $request->getContent()) {
	        $data = json_decode($content, true);
	    }
		$connexion=connexionbd();
		$sql="INSERT INTO Evenement(intitule, dateEvenement, sujetReseau, pseudoCreateur) values (:intitule, :dateEvenement, :sujetReseau, :pseudoCreateur)";
		$stmt=$connexion->prepare($sql);
		$stmt->execute(array('intitule'=>$data['intitule'],'dateEvenement'=>$data['dateEvenement'],'sujetReseau'=>$data['sujetReseau'],'pseudoCreateur'=>$data['pseudoCreateur']));
		return $app->json($data, 201);
    });


    $app->post('/reseau', function (Request $request) use ($app) {
		$data = [];
	    if ($content = $request->getContent()) {
	        $data = json_decode($content, true);
	    }
		$connexion=connexionbd();
		$sql="INSERT INTO Reseau(description,pseudoAdmin,sujet, localisation) values (:description,:pseudoAdmin,:sujet, :localisation)";
		$stmt=$connexion->prepare($sql);
		$stmt->execute(array('description'=>$data['description'], 'pseudoAdmin'=>$data['pseudoAdmin'], 'sujet'=>$data['sujet'], 'localisation'=>$data['localisation']));
		return $app->json($data, 201);
    });



    /****************** DELETE - Utilisateur ******************/
    $app->delete('/utilisateur/{pseudo}', function($pseudo) use ($app) {
    	$connexion=connexionbd();
		$sql="SELECT * FROM Utilisateur WHERE pseudo =:pseudo";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':pseudo', $pseudo);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
	    if (!$user)
	        $app->abort(404, "Utilisateur inexistant");
	    else {
	       	$sql="DELETE FROM Utilisateur WHERE pseudo =:pseudo";
			$stmt=$connexion->prepare($sql);
			$stmt->bindParam(':pseudo', $pseudo);
			$stmt->execute();
			$stmt->fetch(PDO::FETCH_OBJ);
	        return json_encode($user,JSON_PRETTY_PRINT);
	    }
    });

    $app->delete('/utilisateur/theme/{pseudo}/{theme}', function($theme, $pseudo) use ($app) {
    	$connexion=connexionbd();
		$sql="SELECT * FROM Apprecie WHERE idTheme =:theme AND pseudo = :pseudo";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':theme', $theme);
		$stmt->bindParam(':pseudo', $pseudo);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
	    if (!$user)
	        $app->abort(404, "Annonce inexistante");
	    else {
	       	$sql="DELETE FROM Apprecie WHERE idTheme =:theme AND pseudo = :pseudo";
			$stmt=$connexion->prepare($sql);
			$stmt->bindParam(':theme', $theme);
			$stmt->bindParam(':pseudo', $pseudo);
			$stmt->execute();
			$stmt->fetch(PDO::FETCH_OBJ);
	        return json_encode($user,JSON_PRETTY_PRINT);
	    }
    });


    /************** DELETE - COMMERCE **************/
    $app->delete('/commerce/annonce/{id}', function($id) use ($app) {
    	$connexion=connexionbd();
		$sql="SELECT * FROM Annonce WHERE id =:id";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
	    if (!$user)
	        $app->abort(404, "Annonce inexistante");
	    else {
	       	$sql="DELETE FROM Annonce WHERE id =:id";
			$stmt=$connexion->prepare($sql);
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$stmt->fetch(PDO::FETCH_OBJ);
	        return json_encode($user,JSON_PRETTY_PRINT);
	    }
    });

	/********** PUT - Utilisateur **************/
    $app->put('/commerce/annonce/{id}', function($id) use ($app) {
    	$connexion=connexionbd();
		$sql="SELECT * FROM Annonce WHERE id =:id";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_OBJ);
	    if (!$user)
	        $app->abort(404, "Annonce inexistante");
	    else {
	       	$sql="DELETE FROM Annonce WHERE id =:id";
			$stmt=$connexion->prepare($sql);
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$stmt->fetch(PDO::FETCH_OBJ);
	        return json_encode($user,JSON_PRETTY_PRINT);
	    }
    });

    $app->put('/utilisateur/{pseudo}', function ($pseudo, Request $request) use ($app) {
		$data = [];
	    if ($content = $request->getContent()) {
	        $data = json_decode($content, true);
	    }
		$connexion=connexionbd();
		$sql="UPDATE Utlisateur SET pseudo = :pseudo, MDP = :MDP,dateNaissance = :dateNaissance,sexe = :sexe,taille= :taille,poids= :poids WHERE pseudo = :pseudo";
		$stmt=$connexion->prepare($sql);
		$stmt->bindParam(':pseudo', $data['pseudo']);
		$stmt->execute(array('pseudo'=>$data['pseudo'], 'MDP'=>$data['MDP'], 'dateNaissance'=>$data['dateNaissance'], 'sexe'=>$data['sexe'],'taille'=>$data['taille'],'poids'=>$data['poids']));
		return $app->json($data, 201);
    });

// --------------------------- RAJOUT PUB -----------------------------------------
		$app->get('/publicite/{lieu}', function ($lieu) use ($app) {
				$connexion=connexionbd();

			$sql="SELECT titre,nomFournisseur,lienPub,lieu FROM Publicite WHERE lieu='".$lieu."' ORDER BY RAND() LIMIT 1";
			$stmt=$connexion->prepare($sql);
			$stmt->execute();
				$response = new Response();
				$response->setContent(json_encode(utf8ize($stmt->fetch(PDO::FETCH_OBJ))));
			$response->headers->set('Content-Type', 'application/json');
				return $response;
		});

// ----------------------- RAJOUT ADHERE -------------------------------------------

$app->post('/adhere/adhesion', function (Request $request) use ($app) {
$data = [];
	if ($content = $request->getContent()) {
			$data = json_decode($content, true);
	}
$connexion=connexionbd();
$sql="INSERT INTO Adhere(pseudo,sujetReseau) values (:pseudo,:sujetReseau)";
$stmt=$connexion->prepare($sql);
$stmt->execute(array('pseudo'=>$data['pseudo'],'sujetReseau'=>$data['sujetReseau']));
return $app->json($data, 201);
});


// Rajout rechercheReseau

$app->get('/rechercheReseau/{localisation}/{pseudo}/{recherche}', function ($localisation,$pseudo,$recherche) use ($app) {
		$connexion=connexionbd();

	$sql="SELECT * FROM Reseau WHERE localisation = '".$localisation."' AND visibilite=1 AND pseudoAdmin!='".$pseudo."' AND LOWER (sujet) LIKE '%". strtolower($recherche) ."%' AND sujet NOT IN (SELECT sujetReseau FROM Adhere WHERE pseudo='".$pseudo."')";
	$query=$connexion->query($sql);
		while ($donnees=$query->fetch()) {
			$data[]=Array('sujet'=>$donnees['sujet'],'description'=>$donnees['description'],'pseudoAdmin'=>$donnees['pseudoAdmin'],'localisation'=>$donnees['localisation'],'visibilite'=>$donnees['visibilite']);
		}
		$response = new Response();
		$response->setContent(json_encode(utf8ize($data)));
	$response->headers->set('Content-Type', 'application/json');
		return $response;
});


// Rajout invitation

$app->get('/invitation/{pseudo}', function ($pseudo) use ($app) {
		$connexion=connexionbd();

	$sql="SELECT pseudo,sujet,description,pseudoAdmin,localisation,visibilite FROM Invitation,Reseau WHERE pseudo='".$pseudo."' AND sujet=sujetReseau";
	$query=$connexion->query($sql);
		while ($donnees=$query->fetch()) {
			$data[]=Array('pseudo'=>$donnees['pseudo'],'sujetReseau'=>$donnees['sujet'],'description'=>$donnees['description'],'pseudoAdmin'=> $donnees['pseudoAdmin'],'localisation'=>$donnees['localisation'],'visibilite'=>$donnees['visibilite']);
		}
		$response = new Response();
		$response->setContent(json_encode(utf8ize($data)));
	$response->headers->set('Content-Type', 'application/json');
		return $response;
});


// A modifier ? pas correcte de supprimer par un get

$app->get('/SuppressionInvitation/{pseudo}/{sujet}', function ($pseudo,$sujet) use ($app) {
		$connexion=connexionbd();

	$sql="DELETE FROM Invitation WHERE pseudo='".$pseudo."' AND sujetReseau='".$sujet."'";
	$stmt=$connexion->prepare($sql);
	$stmt->execute();
		$response = new Response();
		return $response;
});


$app->get('/listeReseauAdmin/{pseudo}', function ($pseudo) use ($app) {
		$connexion=connexionbd();

	$sql="SELECT sujet,description,pseudoAdmin,localisation,visibilite FROM Reseau WHERE pseudoAdmin='".$pseudo."'";
	$query=$connexion->query($sql);
		while ($donnees=$query->fetch()) {
			$data[]=Array('sujet'=>$donnees['sujet'],'description'=>$donnees['description'],'pseudoAdmin'=> $donnees['pseudoAdmin'],'localisation'=>$donnees['localisation'],'visibilite'=>$donnees['visibilite']);
		}
		$response = new Response();
		$response->setContent(json_encode(utf8ize($data)));
	$response->headers->set('Content-Type', 'application/json');
		return $response;
});



// ----------------------- RAJOUT ENVOYER INVIT -------------------------------------------

$app->post('/envoiInvit', function (Request $request) use ($app) {
$data = [];
	if ($content = $request->getContent()) {
			$data = json_decode($content, true);
	}
$connexion=connexionbd();
$sql="INSERT INTO Invitation(pseudo,sujetReseau) values (:pseudo,:sujetReseau)";
$stmt=$connexion->prepare($sql);
$stmt->execute(array('pseudo'=>$data['pseudo'],'sujetReseau'=>$data['sujetReseau']));
return $app->json($data, 201);
});


	$app->run();
?>
