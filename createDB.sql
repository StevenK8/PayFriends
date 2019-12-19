CREATE DATABASE payfriends;
USE payfriends;

-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  jeu. 19 déc. 2019 à 12:26
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
-- Structure de la table `depenses`
--

CREATE TABLE `depenses` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `ide` int(11) NOT NULL,
  `prix` int(11) NOT NULL,
  `idu` int(11) NOT NULL,
  `prix_par_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `depenses`
--

INSERT INTO `depenses` (`id`, `nom`, `ide`, `prix`, `idu`, `prix_par_user`) VALUES
(1, 'Courses', 18, 100, 8, 50);

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `token`) VALUES
(18, 'Vacances de Noël', 'Aux ïles canari', 'p6ffc2f3ms4e'),
(19, 'Vacances été', 'Dans les alpes', 'ylrx18kodsvl'),
(20, 'Raclette', 'Samedi', 'p7b1caqctkmy'),
(22, 'Ski', 'En Janvier', 'ltlq7vmxhwai'),
(35, 'Soirée cheval', 'Avec Charlie', '0x1sk9i8ytl8'),
(37, 'Banane samedi', 'dza', 'uwwa9d6qx4bc'),
(38, 'Bob et ses événements', 'alahu', 'ndpxlm5seufh');

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

CREATE TABLE `members` (
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
(22, 8),
(35, 8),
(37, 8),
(38, 12),
(20, 12),
(19, 12),
(18, 12),
(35, 12);

-- --------------------------------------------------------

--
-- Structure de la table `members_depense`
--

CREATE TABLE `members_depense` (
  `idd` int(11) NOT NULL,
  `idu` int(11) NOT NULL,
  `haspaid` decimal(1,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `members_depense`
--

INSERT INTO `members_depense` (`idd`, `idu`, `haspaid`) VALUES
(1, 12, '0');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(8, 'admin', '$2y$10$MYqNmuorDPCvoKG.yAnKOOcUR2aYagoj9TR5x39fbqmdbB6/Z/R3.'),
(12, 'bob', '$2y$10$ModPSv7K4M3KwSrq1XFYteKGQJUOp8nSCvVEbrtPe6YiKJnop4NiC');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `depenses`
--
ALTER TABLE `depenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `DepenseIde` (`ide`),
  ADD KEY `DepenseIdu` (`idu`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Index pour la table `members`
--
ALTER TABLE `members`
  ADD KEY `MembersIdu` (`idu`),
  ADD KEY `MembersIde` (`ide`);

--
-- Index pour la table `members_depense`
--
ALTER TABLE `members_depense`
  ADD KEY `Members_d_Idu` (`idu`),
  ADD KEY `Members_d_Idd` (`idd`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `depenses`
--
ALTER TABLE `depenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `depenses`
--
ALTER TABLE `depenses`
  ADD CONSTRAINT `DepenseIde` FOREIGN KEY (`ide`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `DepenseIdu` FOREIGN KEY (`idu`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `MembersIde` FOREIGN KEY (`ide`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `MembersIdu` FOREIGN KEY (`idu`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `members_depense`
--
ALTER TABLE `members_depense`
  ADD CONSTRAINT `Members_d_Idd` FOREIGN KEY (`idd`) REFERENCES `depenses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Members_d_Idu` FOREIGN KEY (`idu`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




GRANT INSERT, SELECT, DELETE, UPDATE ON payfriends.* TO 'payfriends'@'localhost' IDENTIFIED BY 'PayFriendsPayFriends2019;';

COMMIT;
