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
-- Structure de la table `Choisi`
--

CREATE TABLE `Choisi` (
  `pseudo` varchar(20) NOT NULL,
  `nomLieu` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `Choisi`
--

INSERT INTO `Choisi` (`pseudo`, `nomLieu`) VALUES
('macaline', 'PARIS'),
('martin23', 'MONTPELLIER'),
('pseudo2', 'MONTPELLIER'),
('tueuraurel', 'JUVIGNAC');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Choisi`
--
ALTER TABLE `Choisi`
  ADD PRIMARY KEY (`pseudo`,`nomLieu`),
  ADD KEY `Choisi_FK` (`nomLieu`),
  ADD KEY `pseudo` (`pseudo`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Choisi`
--
ALTER TABLE `Choisi`
  ADD CONSTRAINT `Choisi_FK` FOREIGN KEY (`nomLieu`) REFERENCES `Lieu` (`nom`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
