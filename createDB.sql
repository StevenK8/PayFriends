-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 192.168.1.33
-- Généré le : jeu. 26 mars 2020 à 17:05
-- Version du serveur :  10.3.22-MariaDB-0+deb10u1
-- Version de PHP : 7.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `payfriends`
--

-- --------------------------------------------------------

--
-- Structure de la table `depenses`
--

CREATE TABLE `depenses` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `ide` int(11) NOT NULL,
  `prix` decimal(11,0) NOT NULL,
  `idu` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `depenses`
--

INSERT INTO `depenses` (`id`, `nom`, `ide`, `prix`, `idu`, `date`) VALUES
(1, 'Courses', 39, '100', 8, '2020-01-02'),
(2, 'Ski', 39, '70', 8, '2019-12-18'),
(3, 'Alcool', 41, '30', 8, '2019-12-26'),
(4, 'Mère Noël', 58, '210', 13, '2020-01-10'),
(5, 'Bouteilles d\'eau', 39, '25', 12, '2020-01-21'),
(6, 'Chaussures', 45, '70', 8, '2020-03-10'),
(8, 'Foin', 61, '15', 18, '2020-03-10'),
(9, 'Sapin', 41, '50', 8, '2020-03-10'),
(10, 'Boulette boulette', 63, '10', 8, '2020-03-11'),
(11, 'Essence', 39, '40', 8, '2020-03-11'),
(12, 'Rollers', 39, '50', 13, '2020-03-11'),
(13, 'Boules du père Noël', 41, '26', 18, '2020-03-12'),
(14, 'Bottes', 45, '11', 18, '2020-03-12'),
(15, 'Bottes', 45, '11', 8, '2020-03-12'),
(16, 'Bières', 61, '10', 18, '2020-03-12'),
(17, 'Pizza', 65, '65', 8, '2020-03-12'),
(18, 'Balles de ping pong', 65, '6', 8, '2020-03-12'),
(19, 'de shit', 63, '1', 13, '2020-03-12'),
(20, 'Morsay', 63, '20', 8, '2020-03-12'),
(30, 'tee-shirt', 63, '2', 8, '2020-03-12'),
(37, 'Kayak', 40, '100', 20, '2020-03-13'),
(38, 'Piramides', 63, '24', 8, '2020-03-13'),
(39, 'Chaussures qui courrent vite', 39, '40', 8, '2020-03-13'),
(40, 'Zoo 🐯 ', 67, '40', 8, '2020-03-15'),
(41, 'Masques de protection', 68, '50', 8, '2020-03-20'),
(42, 'gants', 68, '41', 13, '2020-03-20'),
(43, 'Vaccin', 69, '3', 8, '2020-03-20');

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `idu` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `idu`, `title`, `description`, `token`) VALUES
(39, 8, 'Marathon de Paris 🏃🏻', 'Les coureurs sont prêts', 'pvhpse5974bh'),
(40, 8, 'Vacances été ☀', 'En Juillet 2020', '77h2hm9m4ltc'),
(41, 8, 'Vacances de Noël 🎄', 'Already Christmas', 'tnuucwz8qyy3'),
(45, 8, 'Soirée cheval 🐎', 'Horses', 'qfje092sofx5'),
(58, 13, 'Voyage sarcelles 🧳', 'Streetzer tahu', 'gkfat1nltd5w'),
(61, 18, 'Semaine au châlet 🏚', 'Le 11/02 pendant une semaine, apportez vos bières.', 'w8orid6h5gti'),
(63, 18, 'Rencontre avec Cortex', 'draaaaaa 91 les piramides aïe aïe aïe', 'n08ryo7zlgcf'),
(65, 8, 'Pizza samedi 🍕', 'zébardi', 'j6nn586wlylcbimeb9ousp2e'),
(66, 20, 'fkfk', 'fkhgfkhgf', 'jhaiv871q5tznw8thanzva8j'),
(67, 18, 'Camille champomi ❤', 'Bisous sur la fesse droite', 'cqf8g7pl6dotmxzgzqsy4136'),
(68, 13, 'Confinement', 'Macron il a dit restez chez vous', 's29vo56n0io971wrpplboa0z'),
(69, 8, 'Voyage Coronavirus 🦠', 'Crachez sur les poignées de portes !', '3sm0on5g9zmst5bk02vuoljl');

-- --------------------------------------------------------

--
-- Structure de la table `invites`
--

CREATE TABLE `invites` (
  `ide` int(11) NOT NULL,
  `idu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `invites`
--

INSERT INTO `invites` (`ide`, `idu`) VALUES
(39, 17),
(40, 19);

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
(39, 8),
(39, 12),
(39, 13),
(39, 19),
(40, 8),
(40, 13),
(40, 20),
(41, 8),
(41, 13),
(41, 18),
(41, 20),
(41, 21),
(45, 8),
(45, 18),
(58, 13),
(58, 18),
(61, 8),
(61, 18),
(63, 8),
(63, 13),
(63, 18),
(63, 21),
(65, 8),
(65, 13),
(65, 18),
(66, 20),
(67, 8),
(67, 18),
(68, 8),
(68, 13),
(69, 8),
(69, 13);

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
(1, 12, '0'),
(2, 12, '0'),
(12, 8, '0'),
(17, 13, '0'),
(38, 13, '0'),
(38, 18, '0'),
(39, 12, '0'),
(39, 13, '0'),
(39, 19, '0'),
(40, 18, '0'),
(41, 13, '0'),
(42, 8, '0'),
(43, 13, '0');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `prix_par_user`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `prix_par_user` (
`id_depense` int(11)
,`id` int(11)
,`prix/COUNT(idd)` decimal(15,4)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `sum_event`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `sum_event` (
`id` int(11)
,`somme` decimal(33,0)
);

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
(8, 'admin', '$2y$10$uaab7UUmMWLK/xWT9MP0XezEnPppWOXPTh/3qIPe5QMhNRP/D/8Je'),
(12, 'bob', '$2y$10$ModPSv7K4M3KwSrq1XFYteKGQJUOp8nSCvVEbrtPe6YiKJnop4NiC'),
(13, 'Dam', '$2y$10$Pod3/QuxMNTREBxiWh6BgOD1hxktJgeNETi/Eg1ghvy6ogD0PDxo6'),
(17, 'Fabrice', '$2y$10$BrF.wWa7tNfn7lNYtz6rDu2ndNsZO9kNvkm7RjoSSZ1IoiNqhwsqm'),
(18, 'Pascal', '$2y$10$AXbTYX4tQxmKRd0f6AponO3yWPfpoDOpAfPAx9jf3a8L2.zdo5pRm'),
(19, 'Robert', '$2y$10$bY6oxyumubmmWmXlNXUYZOb.E3Q/xHWQW8.r7gGd0Jl.K8ElI2ur6'),
(20, 'thomas', '$2y$10$H2utMCXn/h88KQmmRg3fnuBqkLDPR4vhE.8bUiRTV.AwmzC/RuHte'),
(21, 'ilskei', '$2y$10$E0YMhvaHLOn.1LCSfAwd3uEmX93D49DShUtRr0Cf704xcyyoSRotC'),
(22, 'elabat', '$2y$10$c9fPbZ5CdI2dMaxXgZURF.JUqAtOHzMUVoOE2ASjo2YBr92PtpvCa');

-- --------------------------------------------------------

--
-- Structure de la vue `prix_par_user`
--
DROP TABLE IF EXISTS `prix_par_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `prix_par_user`  AS  select `depenses`.`id` AS `id_depense`,`depenses`.`id` AS `id`,`depenses`.`prix` / (count(`members_depense`.`idu`) + 1) AS `prix/COUNT(idd)` from (`depenses` join `members_depense`) where `depenses`.`id` like `members_depense`.`idd` ;

-- --------------------------------------------------------

--
-- Structure de la vue `sum_event`
--
DROP TABLE IF EXISTS `sum_event`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `sum_event`  AS  select `events`.`id` AS `id`,sum(`depenses`.`prix`) AS `somme` from (`depenses` join `events`) where `depenses`.`ide` like `events`.`id` ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `depenses`
--
ALTER TABLE `depenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `depenses_ide` (`ide`),
  ADD KEY `depenses_idu` (`idu`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Index pour la table `invites`
--
ALTER TABLE `invites`
  ADD KEY `invites_ide` (`ide`),
  ADD KEY `invites_idu` (`idu`);

--
-- Index pour la table `members`
--
ALTER TABLE `members`
  ADD UNIQUE KEY `couple_unique` (`ide`,`idu`),
  ADD KEY `members_idu` (`idu`);

--
-- Index pour la table `members_depense`
--
ALTER TABLE `members_depense`
  ADD UNIQUE KEY `unique_couple_depense` (`idd`,`idu`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `depenses`
--
ALTER TABLE `depenses`
  ADD CONSTRAINT `depenses_ide` FOREIGN KEY (`ide`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `depenses_idu` FOREIGN KEY (`idu`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `invites`
--
ALTER TABLE `invites`
  ADD CONSTRAINT `invites_ide` FOREIGN KEY (`ide`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `invites_idu` FOREIGN KEY (`idu`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ide` FOREIGN KEY (`ide`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `members_idu` FOREIGN KEY (`idu`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
