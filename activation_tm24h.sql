-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 06 juin 2025 à 18:10
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `activation_tm24h`
--

-- --------------------------------------------------------

--
-- Structure de la table `band_tm24h`
--

CREATE TABLE `band_tm24h` (
  `idbandtm24h` int(2) NOT NULL,
  `bandtm24h` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bandtm24hmin` float(8,4) NOT NULL,
  `bandtm24hmax` float(8,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `band_tm24h`
--

INSERT INTO `band_tm24h` (`idbandtm24h`, `bandtm24h`, `bandtm24hmin`, `bandtm24hmax`) VALUES
(0, '80 m', 3.5000, 3.8000),
(1, '60 m', 5.3515, 5.3665),
(2, '40 m', 7.0000, 7.2000),
(3, '30 m', 10.1000, 10.1500),
(4, '20 m', 14.0000, 14.3500),
(5, '17 m', 18.0680, 18.1680),
(6, '15 m', 21.0000, 21.4500),
(7, '12 m', 24.8900, 24.9900),
(8, '10 m', 28.0000, 29.7000),
(9, '6 m', 50.0000, 52.0000),
(10, '2 m', 144.0000, 146.0000),
(11, '70 cm', 430.0000, 440.0000),
(12, '23 cm', 1240.0000, 1300.0000),
(13, '13 cm', 2300.0000, 2450.0000),
(14, 'RRF', 145.3000, 145.3000),
(15, 'QO100', 7.0000, 2450.0000);

-- --------------------------------------------------------

--
-- Structure de la table `data_tm24h`
--

CREATE TABLE `data_tm24h` (
  `IdData` int(11) NOT NULL,
  `fkIdbandtm24h` int(2) NOT NULL,
  `fkidmodetm24h` int(1) NOT NULL,
  `fkidomtm24h` int(2) NOT NULL,
  `datafreq` decimal(8,4) NOT NULL,
  `dataactivationstart` datetime DEFAULT NULL,
  `dataactivationend` datetime DEFAULT NULL,
  `datastatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mode_tm24h`
--

CREATE TABLE `mode_tm24h` (
  `Idmodetm24h` int(2) NOT NULL,
  `modetm24h` varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mode_tm24h`
--

INSERT INTO `mode_tm24h` (`Idmodetm24h`, `modetm24h`) VALUES
(0, 'AM'),
(1, 'SSB'),
(2, 'USB'),
(3, 'FM'),
(4, 'CW'),
(5, 'DIGI');

-- --------------------------------------------------------

--
-- Structure de la table `om_tm24h`
--

CREATE TABLE `om_tm24h` (
  `idomtm24h` int(2) NOT NULL,
  `omtm24h` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `om_tm24h`
--

INSERT INTO `om_tm24h` (`idomtm24h`, `omtm24h`) VALUES
(0, 'F1BJD'),
(1, 'F1HTU'),
(2, 'F1PGZ'),
(3, 'F1TZM'),
(4, 'F4EWP'),
(5, 'F4FFL'),
(6, 'F4GDI'),
(7, 'F4GNS'),
(8, 'F4GOH'),
(9, 'F4HGA'),
(10, 'F4HHR'),
(11, 'F4IKB'),
(12, 'F4IRT'),
(13, 'F4JRT'),
(14, 'F4LUC'),
(15, 'F5BEG'),
(16, 'F5HNQ'),
(17, 'F5JDJ'),
(18, 'F5JGB'),
(19, 'F5NYY'),
(20, 'F5OPN'),
(21, 'F5TJC'),
(22, 'F6HER'),
(23, 'F6HNW'),
(24, 'F6IFX'),
(25, 'F8CGL'),
(26, 'F9FZ'),
(27, 'F1PPH');

-- --------------------------------------------------------

--
-- Structure de la table `pwd_tm24h`
--

CREATE TABLE `pwd_tm24h` (
  `Idpwdtm24h` int(1) NOT NULL,
  `pwdtm24h` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pwd_tm24h`
--

INSERT INTO `pwd_tm24h` (`Idpwdtm24h`, `pwdtm24h`) VALUES
(0, '$2y$10$Jv/OhawCxCA7hhIfGtbzzuv7s7QdGFXPvWq08KreMWHe4xcvwYPfC');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `band_tm24h`
--
ALTER TABLE `band_tm24h`
  ADD PRIMARY KEY (`idbandtm24h`);

--
-- Index pour la table `data_tm24h`
--
ALTER TABLE `data_tm24h`
  ADD PRIMARY KEY (`IdData`),
  ADD KEY `fkIdbandtm24h` (`fkIdbandtm24h`),
  ADD KEY `fkIdmodetm24h` (`fkidmodetm24h`),
  ADD KEY `fkIdomtm24h` (`fkidomtm24h`);

--
-- Index pour la table `mode_tm24h`
--
ALTER TABLE `mode_tm24h`
  ADD PRIMARY KEY (`Idmodetm24h`);

--
-- Index pour la table `om_tm24h`
--
ALTER TABLE `om_tm24h`
  ADD PRIMARY KEY (`idomtm24h`);

--
-- Index pour la table `pwd_tm24h`
--
ALTER TABLE `pwd_tm24h`
  ADD PRIMARY KEY (`Idpwdtm24h`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `data_tm24h`
--
ALTER TABLE `data_tm24h`
  MODIFY `IdData` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `data_tm24h`
--
ALTER TABLE `data_tm24h`
  ADD CONSTRAINT `fkIdbandtm24h` FOREIGN KEY (`fkIdbandtm24h`) REFERENCES `band_tm24h` (`idbandtm24h`),
  ADD CONSTRAINT `fkIdmodetm24h` FOREIGN KEY (`fkidmodetm24h`) REFERENCES `mode_tm24h` (`Idmodetm24h`),
  ADD CONSTRAINT `fkIdomtm24h` FOREIGN KEY (`fkidomtm24h`) REFERENCES `om_tm24h` (`IDOMTM24H`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
