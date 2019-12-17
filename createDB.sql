CREATE DATABASE payfriends;
USE payfriends;

-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mar 17 Décembre 2019 à 12:45
-- Version du serveur :  10.3.17-MariaDB-0+deb10u1
-- Version de PHP :  7.3.11-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `token`) VALUES
(2, 'Vacances été', 'Juillet 2019', 'nytv3cln'),
(3, 'Vacances été', 'd', '0tgtlk7d537k'),
(4, 'Vacances été', 'd', '6vkrafor06et'),
(6, 'Soirée cheval', 'Avec mamie', 'oq6d1c5674cr'),
(8, 'Soirée cheval', 'oki', 'go0sm51d6qqz'),
(9, 'Chalet vacances', 'Dans les Alpes', 'fpc6ntk2mjbj'),
(12, 'Anniversaire de steven', '&lt;3', 'if0re8s1o7w6'),
(13, 'Anniversaire de steven', '&lt;3', 'vbbal5q42c4p'),
(14, 'Soirée Montparnasse', 'Des barres', '5k04ch58qbbq'),
(15, 'OK lenovo', 'Mdrr', 'vudrg52t7qjq');

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

CREATE TABLE `members` (
  `ide` int(11) NOT NULL,
  `idu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `members`
--

INSERT INTO `members` (`ide`, `idu`) VALUES
(15, 1),
(15, 9),
(14, 1),
(15, 8);

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
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$4VMnfkJVZF5O3RxF/.ko0OgAhvDK/WteO2iv9n6kT4y.cMYg9e72i'),
(8, 'camillechampomi', '$2y$10$JTa3eu1BlgNQhP1fyJ82XeNVp7j1Ob0u/AO8DHcxTVJVaOpdKIW1S'),
(9, 'Dam', '$2y$10$oBy6DeflCNM2nu9bUO7YBOjD7TXHE6HNOIotw48ixmpgu0/QiigwO'),
(14, 'Silvain', '$2y$10$s8khAcFOq3DhbabYTNHqZOhNO2TBiJpvJORIRIgxtj7UC5K6x.HhK');

--
-- Index pour les tables exportées
--

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
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `MembersIde` FOREIGN KEY (`ide`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `MembersIdu` FOREIGN KEY (`idu`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




GRANT INSERT, SELECT, DELETE, UPDATE ON payfriends.* TO 'payfriends'@'localhost' IDENTIFIED BY 'password';

COMMIT;
