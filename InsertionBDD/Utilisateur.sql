-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 15 Avril 2018 à 20:29
-- Version du serveur :  5.7.21-0ubuntu0.16.04.1
-- Version de PHP :  7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `smartcity`
--

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateur`
--

CREATE TABLE `Utilisateur` (
  `pseudo` varchar(20) NOT NULL,
  `MDP` varchar(20) DEFAULT NULL,
  `dateNaissance` varchar(10) DEFAULT NULL,
  `sexe` int(11) DEFAULT NULL,
  `taille` float DEFAULT NULL,
  `poids` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Utilisateur`
--

INSERT INTO `Utilisateur` (`pseudo`, `MDP`, `dateNaissance`, `sexe`, `taille`, `poids`) VALUES
('alice', '1234', '12-01-1984', 0, 1.45, 88),
('arthur', '1234', '12-12-1996', 0, 1.78, 81),
('bamby', 'bamby', '14-05-1987', 1, 1.58, 88),
('etoile34', 'mama123', '04-11-1987', 0, 1.45, 56),
('macaline', '12121996', '12-12-1996', 1, 1.58, 55),
('maelle', '1234', '2018-03-09', 1, 1.5, 50),
('martin', 'tati', '2018-03-01', 0, 1.95, 80),
('martin23', '123456', '04-05-1996', 0, 1.57, 60),
('protect', '1234', '12-12-1987', 1, 1.69, 63),
('pseudo', '15968', '12-10-1996', 0, 1.58, 59),
('pseudo2', '1234', '04-05-1968', 0, 1.68, 60),
('tueuraurel', '123456789', '05-04-1996', 0, 1.75, 70),
('utilisateur1', 'mdp', 'date', 0, 180, 70),
('utilisateur2', 'mdp', 'date', 1, 150, 60),
('utilisateur3', 'mdp', 'date', 0, 100, 100);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Utilisateur`
--
ALTER TABLE `Utilisateur`
  ADD PRIMARY KEY (`pseudo`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
