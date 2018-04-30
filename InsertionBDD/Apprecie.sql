-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 15 Avril 2018 à 20:30
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
-- Structure de la table `Apprecie`
--

CREATE TABLE `Apprecie` (
  `pseudo` varchar(20) NOT NULL,
  `idTheme` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Apprecie`
--

INSERT INTO `Apprecie` (`pseudo`, `idTheme`) VALUES
('pseudo2', 10),
('protect', 27),
('pseudo2', 27),
('tueuraurel', 27),
('protect', 56),
('martin23', 68),
('tueuraurel', 68);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Apprecie`
--
ALTER TABLE `Apprecie`
  ADD PRIMARY KEY (`pseudo`,`idTheme`),
  ADD KEY `Apprecie_idTheme_FK` (`idTheme`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Apprecie`
--
ALTER TABLE `Apprecie`
  ADD CONSTRAINT `Apprecie_idTheme_FK` FOREIGN KEY (`idTheme`) REFERENCES `Theme` (`id`),
  ADD CONSTRAINT `Apprecie_pseudo_FK` FOREIGN KEY (`pseudo`) REFERENCES `Utilisateur` (`pseudo`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
