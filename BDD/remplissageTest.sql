
INSERT INTO Utilisateur (  pseudo,MDP,dateNaissance,sexe,taille,poids) VALUES
("utilisateur1","mdp","date",0,180,70),
("utilisateur2","mdp","date",1,150,60),
("utilisateur3","mdp","date",0,100,100)
;


INSERT INTO Choisi (pseudo, nomLieu) VALUES
("utilisateur1","Montpellier"),
("utilisateur2","Toulouse"),
("utilisateur3","Montpellier")
;

INSERT INTO Apprecie (pseudo,idTheme) SELECT "utilisateur1", id FROM Theme WHERE nom="Handball";
INSERT INTO Apprecie (pseudo,idTheme) SELECT "utilisateur2", id FROM Theme WHERE nom="Rugby";
INSERT INTO Apprecie (pseudo,idTheme) SELECT "utilisateur3", id FROM Theme WHERE nom="Football";

INSERT INTO Commerce (nom,pseudoCommercant,localisation) VALUES
("Commerce1","utilisateur3","Montpellier");

INSERT INTO Annonce (titre,contenu,ageMin,ageMax,sexeConcerne,pseudoCommercant) VALUES
("A VENDRE","Petite Maison dans la prairie",20,25,"Mixte","utilisateur3"),
("Pour Jeune","Petite Maison dans la prairie",20,25,"Mixte","utilisateur3"),
("Pour Vieux","Petite Maison dans la prairie",60,99,"Mixte","utilisateur3"),
("Pour femmes","Petite Maison dans la prairie",20,25,"Femme","utilisateur3"),
("Pour hommes","Petite Maison dans la prairie",20,25,"Homme","utilisateur3");

INSERT INTO ListeAnnonce (idAnnonce,idCommerce,idTheme)
  SELECT a.id,c.id,t.id FROM Annonce a, Commerce c, Theme t WHERE a.titre ="A VENDRE" && c.nom="Commerce1"
    && t.nom="Agent immobilier";
INSERT INTO ListeAnnonce (idAnnonce,idCommerce,idTheme)
  SELECT a.id,c.id,t.id FROM Annonce a, Commerce c, Theme t WHERE a.titre ="Pour Jeune" && c.nom="Commerce1"
    && t.nom="Pop";
INSERT INTO ListeAnnonce (idAnnonce,idCommerce,idTheme)
  SELECT a.id,c.id,t.id FROM Annonce a, Commerce c, Theme t WHERE a.titre ="Pour Vieux" && c.nom="Commerce1"
    && t.nom="Classique";
INSERT INTO ListeAnnonce (idAnnonce,idCommerce,idTheme)
  SELECT a.id,c.id,t.id FROM Annonce a, Commerce c, Theme t WHERE a.titre ="Pour femmes" && c.nom="Commerce1"
    && t.nom="Psychanalyste";
INSERT INTO ListeAnnonce (idAnnonce,idCommerce,idTheme)
  SELECT a.id,c.id,t.id FROM Annonce a, Commerce c, Theme t WHERE a.titre ="Pour hommes" && c.nom="Commerce1"
    && t.nom="Carrosserie automobile";

INSERT INTO Reseau (sujet,description, pseudoAdmin,localisation,visibilite) VALUES
("Un sujet","Une description","utilisateur1","Montpellier",0),
("Un autre sujet","Une autre description","utilisateur2","Toulouse",1);

INSERT INTO Associe (sujetReseau,idTheme) SELECT "Un sujet", id FROM Theme Where nom="Natation";

INSERT INTO Appartient (idCommerce,idTheme) SELECT c.id,t.id FROM Commerce c, Theme t
  WHERE c.nom="Commerce1" && t.nom="Agent immobilier";


INSERT INTO Message (contenu,pseudoAuteur,sujetReseau) VALUES
("Bonjour, je serait interrese par la maison","utilisateur2","Un sujet");

/*INSERT INTO Evenement (intitule,dateEvenement,sujetReseau,pseudoCreateur);*/
