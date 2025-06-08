-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 08 juin 2025 à 14:50
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
-- Structure de la table `logkfi_band_tm24h`
--

CREATE TABLE `logkfi_band_tm24h` (
  `idbandtm24h` int(2) NOT NULL,
  `bandtm24h` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bandtm24hmin` float(8,4) NOT NULL,
  `bandtm24hmax` float(8,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logkfi_band_tm24h`
--

INSERT INTO `logkfi_band_tm24h` (`idbandtm24h`, `bandtm24h`, `bandtm24hmin`, `bandtm24hmax`) VALUES
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
-- Structure de la table `logkfi_data_tm24h`
--

CREATE TABLE `logkfi_data_tm24h` (
  `IdData` int(11) NOT NULL,
  `fkIdbandtm24h` int(2) NOT NULL,
  `fkidmodetm24h` int(1) NOT NULL,
  `fkidomtm24h` int(2) NOT NULL,
  `datafreq` decimal(8,4) NOT NULL,
  `dataactivationstart` datetime DEFAULT NULL,
  `dataactivationend` datetime DEFAULT NULL,
  `datastatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logkfi_data_tm24h`
--

INSERT INTO `logkfi_data_tm24h` (`IdData`, `fkIdbandtm24h`, `fkidmodetm24h`, `fkidomtm24h`, `datafreq`, `dataactivationstart`, `dataactivationend`, `datastatus`) VALUES
(1, 4, 1, 12, 14.1500, '2025-06-07 07:41:15', '2025-06-07 07:41:31', 0),
(2, 4, 5, 12, 14.2200, '2025-06-08 11:49:49', '2025-06-08 11:50:10', 0);

-- --------------------------------------------------------

--
-- Structure de la table `logkfi_mode_tm24h`
--

CREATE TABLE `logkfi_mode_tm24h` (
  `Idmodetm24h` int(2) NOT NULL,
  `modetm24h` varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logkfi_mode_tm24h`
--

INSERT INTO `logkfi_mode_tm24h` (`Idmodetm24h`, `modetm24h`) VALUES
(0, 'AM'),
(1, 'SSB'),
(2, 'USB'),
(3, 'FM'),
(4, 'CW'),
(5, 'DIGI');

-- --------------------------------------------------------

--
-- Structure de la table `logkfi_om_tm24h`
--

CREATE TABLE `logkfi_om_tm24h` (
  `idomtm24h` int(2) NOT NULL,
  `omtm24h` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logkfi_om_tm24h`
--

INSERT INTO `logkfi_om_tm24h` (`idomtm24h`, `omtm24h`) VALUES
(0, 'F1BJD'),
(1, 'F1HTU'),
(2, 'F1PGZ'),
(3, 'F1TZM'),
(4, 'F4FFL'),
(5, 'F4GDI'),
(6, 'F4GOH'),
(7, 'F4HGA'),
(8, 'F4HHR'),
(9, 'F4IKB'),
(10, 'F4IRT'),
(11, 'F4JRT'),
(12, 'F4LUC'),
(13, 'F5BEG'),
(14, 'F5HNQ'),
(15, 'F5JDJ'),
(16, 'F5JGB'),
(17, 'F5NYY'),
(18, 'F5OPN'),
(19, 'F5TJC'),
(20, 'F6HER'),
(21, 'F6HNW'),
(22, 'F6IFX'),
(23, 'F9FZ'),
(24, 'F4EW0'),
(25, 'F1PPH');

-- --------------------------------------------------------

--
-- Structure de la table `logkfi_pwd_tm24h`
--

CREATE TABLE `logkfi_pwd_tm24h` (
  `Idpwdtm24h` int(1) NOT NULL,
  `pwdtm24h` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `logkfi_pwd_tm24h`
--

INSERT INTO `logkfi_pwd_tm24h` (`Idpwdtm24h`, `pwdtm24h`) VALUES
(0, '$2y$10$Jv/OhawCxCA7hhIfGtbzzuv7s7QdGFXPvWq08KreMWHe4xcvwYPfC');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `logkfi_band_tm24h`
--
ALTER TABLE `logkfi_band_tm24h`
  ADD PRIMARY KEY (`idbandtm24h`);

--
-- Index pour la table `logkfi_data_tm24h`
--
ALTER TABLE `logkfi_data_tm24h`
  ADD PRIMARY KEY (`IdData`),
  ADD KEY `fkIdbandtm24h` (`fkIdbandtm24h`),
  ADD KEY `fkIdmodetm24h` (`fkidmodetm24h`),
  ADD KEY `fkIdomtm24h` (`fkidomtm24h`);

--
-- Index pour la table `logkfi_mode_tm24h`
--
ALTER TABLE `logkfi_mode_tm24h`
  ADD PRIMARY KEY (`Idmodetm24h`);

--
-- Index pour la table `logkfi_om_tm24h`
--
ALTER TABLE `logkfi_om_tm24h`
  ADD PRIMARY KEY (`idomtm24h`);

--
-- Index pour la table `logkfi_pwd_tm24h`
--
ALTER TABLE `logkfi_pwd_tm24h`
  ADD PRIMARY KEY (`Idpwdtm24h`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `logkfi_data_tm24h`
--
ALTER TABLE `logkfi_data_tm24h`
  MODIFY `IdData` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `logkfi_data_tm24h`
--
ALTER TABLE `logkfi_data_tm24h`
  ADD CONSTRAINT `fkIdbandtm24h` FOREIGN KEY (`fkIdbandtm24h`) REFERENCES `logkfi_band_tm24h` (`idbandtm24h`),
  ADD CONSTRAINT `fkIdmodetm24h` FOREIGN KEY (`fkidmodetm24h`) REFERENCES `logkfi_mode_tm24h` (`Idmodetm24h`),
  ADD CONSTRAINT `fkIdomtm24h` FOREIGN KEY (`fkidomtm24h`) REFERENCES `logkfi_om_tm24h` (`IDOMTM24H`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
