-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 24 Mars 2026 à 17:10
-- Version du serveur: 5.5.47
-- Version de PHP: 5.4.45-0+deb7u2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `morganl_b`
--

-- --------------------------------------------------------

--
-- Structure de la table `Ruche__alertes`
--

CREATE TABLE IF NOT EXISTS `Ruche__alertes` (
  `id_alerte` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `criticite` enum('LOW','MEDIUM','HIGH','CRITICAL') NOT NULL,
  `message` text,
  `date_heure` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_capteur` int(11) NOT NULL,
  PRIMARY KEY (`id_alerte`),
  KEY `fk_alerte_capteur` (`id_capteur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Ruche__capteur`
--

CREATE TABLE IF NOT EXISTS `Ruche__capteur` (
  `id_capteur` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `etat` varchar(20) DEFAULT 'ACTIF',
  `id_ruche` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_capteur`),
  KEY `fk_capteur_ruche` (`id_ruche`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Ruche__mesure`
--

CREATE TABLE IF NOT EXISTS `Ruche__mesure` (
  `id_mesure` int(11) NOT NULL AUTO_INCREMENT,
  `poids` decimal(6,2) DEFAULT NULL,
  `temp` decimal(5,2) DEFAULT NULL,
  `lat` decimal(9,6) DEFAULT NULL,
  `lng` decimal(9,6) DEFAULT NULL,
  `date_heure` datetime DEFAULT NULL,
  `id_capteur` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_mesure`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Ruche__ruche`
--

CREATE TABLE IF NOT EXISTS `Ruche__ruche` (
  `id_ruche` int(11) NOT NULL AUTO_INCREMENT,
  `localisation` varchar(100) NOT NULL,
  `poids` decimal(6,2) DEFAULT NULL,
  `humidite` decimal(5,2) DEFAULT NULL,
  `batterie` decimal(4,2) DEFAULT NULL,
  `etat_connexion` varchar(20) DEFAULT 'CONNECTE',
  `id_user` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ruche`),
  KEY `fk_ruche_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Ruche__utilisateur`
--

CREATE TABLE IF NOT EXISTS `Ruche__utilisateur` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `adresse` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `Ruche__utilisateur`
--

INSERT INTO `Ruche__utilisateur` (`id_user`, `prenom`, `nom`, `adresse`, `telephone`, `email`, `role`, `created_at`, `login`, `password`) VALUES
(1, 'Jean', 'Dupont', '12 rue des Abeilles, Limoges', '0612345678', 'jean.dupont@email.com', 'user', '2026-03-09 13:31:55', 'jdupont', '123456'),
(2, 'Koko', 'Channel', '31 Rue Camboi, Paris', '0210121514', 'kokonachannel@gogo.com', 'user', '2026-03-23 08:06:00', 'koko', '987654'),
(3, 'John', 'Deere', '1 John Deere Pl, Moline, Illinois', '0555329516', 'ILovieTractors@gmail.tract', 'user', '2026-03-23 08:13:17', 'ShaunLeSheep', 'BAHBAHblacksheep');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Ruche__alertes`
--
ALTER TABLE `Ruche__alertes`
  ADD CONSTRAINT `fk_alerte_capteur` FOREIGN KEY (`id_capteur`) REFERENCES `Ruche__capteur` (`id_capteur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Ruche__capteur`
--
ALTER TABLE `Ruche__capteur`
  ADD CONSTRAINT `fk_capteur_ruche` FOREIGN KEY (`id_ruche`) REFERENCES `Ruche__ruche` (`id_ruche`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Ruche__ruche`
--
ALTER TABLE `Ruche__ruche`
  ADD CONSTRAINT `fk_ruche_user` FOREIGN KEY (`id_user`) REFERENCES `Ruche__utilisateur` (`id_user`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
