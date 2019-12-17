CREATE DATABASE payfriends;
USE payfriends;

-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 17 déc. 2019 à 10:14
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `payfriends`
--

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

CREATE OR REPLACE TABLE `members` (
  `ide` int(11) NOT NULL,
  `idu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `members`
--

INSERT INTO `members` (`ide`, `idu`) VALUES
(18, 8),
(19, 8),
(20, 8),
(22, 8);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `members`
--
ALTER TABLE `members`
  ADD UNIQUE KEY `ide` (`ide`),
  ADD KEY `MembersIdu` (`idu`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `MembersIde` FOREIGN KEY (`ide`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `MembersIdu` FOREIGN KEY (`idu`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




GRANT INSERT, SELECT, DELETE, UPDATE ON payfriends.* TO 'payfriends'@'localhost' IDENTIFIED BY 'PayFriendsPayFriends2019;';

COMMIT;
