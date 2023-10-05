-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 15 juil. 2023 à 21:32
-- Version du serveur : 5.7.40
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_agent`
--

-- --------------------------------------------------------

--
-- Structure de la table `agent`
--

DROP TABLE IF EXISTS `agent`;
CREATE TABLE IF NOT EXISTS `agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_carte_agent` varchar(255) NOT NULL,
  `nom_agent` varchar(255) NOT NULL,
  `fonction_agent` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `agent`
--

INSERT INTO `agent` (`id`, `id_carte_agent`, `nom_agent`, `fonction_agent`) VALUES
(2, 'E18CC51C22514019729', 'EMMANUEL NGOYI', 'PROGRAMMEUR'),
(3, 'E18CC51C22514019730', 'JOSEPH LOUKOUNYI', 'DIRECTEUR GENERAL ADJOIN'),
(12, 'E18CC51C22514019738', 'HARMONY NDAYA', 'MODELISTE'),
(11, 'E18CC51C22514019737', 'JORDY YAMBA', 'MARKETEUR'),
(26, 'E18CC51C22514019780', 'EMMANUEL ILUNGA', 'DIRECTEUR GENERAL'),
(9, 'E18CC51C22514019733', 'RAISSA BOBOLA', 'DECORATRICE'),
(18, 'E18CC51C22514019770', 'EMMANUELLA KAMBALA', 'CHARGE DE LA COMMUNICATION'),
(14, 'E18CC51C22514019740', 'HERVE NGOYI', 'DIRECTEUR TECHNIQUE'),
(29, 'E18CC51C22514019801', 'PATRICK BHA', 'DIRECTEUR MARKETING'),
(19, 'E18CC51C22514019771', 'DAVID LUTUBA', 'DIRECTEUR DE SANTE'),
(20, 'E18CC51C22514019772', 'BERNICE ZUIYA', 'SUPERVISEUR'),
(25, 'E18CC51C22514019728', 'CHRIS KIEKIE', 'TECHNICIEN'),
(28, 'E18CC51C22514019799', 'CHRISTIANT NSINGI', 'DIRECTEUR DE COMMUNICATION'),
(24, 'E18CC51C22514019779', 'THESIA LABENGI', 'SECRETAIRE (TEMPORAIRE)'),
(31, 'E18CC51C22514019786', 'Astrid Nsonga', 'Directrice FinanciÃ¨re');

-- --------------------------------------------------------

--
-- Structure de la table `register`
--

DROP TABLE IF EXISTS `register`;
CREATE TABLE IF NOT EXISTS `register` (
  `id_registration` int(11) NOT NULL AUTO_INCREMENT,
  `agent_id` int(11) NOT NULL,
  `nom_agent` varchar(255) NOT NULL,
  `fonction_agent` varchar(255) NOT NULL,
  `date_enregistrement` date NOT NULL,
  `heure_enregistrement` time NOT NULL,
  PRIMARY KEY (`id_registration`),
  KEY `agent_id` (`agent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
