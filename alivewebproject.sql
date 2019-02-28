-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Sam 26 Janvier 2019 à 11:58
-- Version du serveur :  5.7.24-0ubuntu0.18.04.1
-- Version de PHP :  7.2.10-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `alivewebproject`
--

-- --------------------------------------------------------

--
-- Structure de la table `alive_news`
--

CREATE TABLE `alive_news` (
  `id` bigint(20) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  `createdAt` int(11) NOT NULL,
  `createdBy` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `alive_projects`
--

CREATE TABLE `alive_projects` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `statusId` int(11) NOT NULL,
  `createdBy` bigint(20) NOT NULL,
  `createdAt` int(11) NOT NULL,
  `editedAt` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `alive_projects_members`
--

CREATE TABLE `alive_projects_members` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '1',
  `projectId` bigint(20) NOT NULL,
  `joinedAt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `alive_projects_ranks`
--

CREATE TABLE `alive_projects_ranks` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `alive_projects_ranks`
--

INSERT INTO `alive_projects_ranks` (`id`, `name`) VALUES
(1, 'Développeur'),
(2, 'Membre de l\'équipe'),
(3, 'Fondateur');

-- --------------------------------------------------------

--
-- Structure de la table `alive_projects_status`
--

CREATE TABLE `alive_projects_status` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `alive_settings`
--

CREATE TABLE `alive_settings` (
  `id` int(11) NOT NULL,
  `siteName` varchar(50) NOT NULL,
  `maintenanceStatus` enum('true','false') CHARACTER SET latin1 NOT NULL DEFAULT 'true',
  `contact` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `alive_settings`
--

INSERT INTO `alive_settings` (`id`, `siteName`, `maintenanceStatus`, `contact`) VALUES
(1, 'AliveWebProject', 'false', 'mehdi.rouis.1@laposte.net');

-- --------------------------------------------------------

--
-- Structure de la table `alive_support_tickets`
--

CREATE TABLE `alive_support_tickets` (
  `id` bigint(20) NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `createdBy` bigint(20) NOT NULL,
  `createdAt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `alive_users`
--

CREATE TABLE `alive_users` (
  `id` bigint(20) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `phoneNumber` varchar(12) NOT NULL,
  `birthDay` date NOT NULL,
  `password` text NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '1',
  `email` text NOT NULL,
  `shopPoints` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `alive_users`
--

INSERT INTO `alive_users` (`id`, `userName`, `lastName`, `firstName`, `phoneNumber`, `birthDay`, `password`, `rank`, `email`, `shopPoints`) VALUES
(2, '&Egrave;sska', 'ROUIS', 'Mehdi', '+33652632212', '1999-06-06', '$2y$10$8sNuFWa1bAiumDcxhUCTB.huZBgeg/4Hr4M7odSWmVSSdFkNZHVme', 9, 'mehdi.rouis.1@laposte.net', 0),
(3, '&Egrave;sskaTest', 'ROUIS', 'Mehdi', '+33121222332', '1999-06-06', '$2y$10$oHHy72X0zFnjgEgDWdfWc.RZqEXgQT7fuCSYKu8HfDeUCkr6IGzMS', 1, 'stuuf.kdev@gmail.com', 0),
(4, '&Egrave;sskaEntrepreneur', 'Personne', 'Autre', '+33101010101', '1999-06-06', '$2y$10$fhxXau/FX8jzuAG5prLvhuOgYHgUgkLIeINqoiEQkEcsEe31K6hSK', 3, 'qsd@qsd.fr', 0);

-- --------------------------------------------------------

--
-- Structure de la table `alive_users_actions`
--

CREATE TABLE `alive_users_actions` (
  `id` bigint(20) NOT NULL,
  `action` enum('register','login','forgotPassword','createProject','validateProject') NOT NULL,
  `createdAt` int(11) NOT NULL,
  `createdBy` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `alive_users_permissions`
--

CREATE TABLE `alive_users_permissions` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parseName` varchar(255) DEFAULT NULL,
  `parseDescription` text,
  `minRank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `alive_users_permissions`
--

INSERT INTO `alive_users_permissions` (`id`, `name`, `parseName`, `parseDescription`, `minRank`) VALUES
(1, 'admin-access', 'Accès à l\'administration', 'Permet à l\'utilisateur à accéder à l\'administration.', 5);

-- --------------------------------------------------------

--
-- Structure de la table `alive_users_ranks`
--

CREATE TABLE `alive_users_ranks` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `alive_users_ranks`
--

INSERT INTO `alive_users_ranks` (`id`, `name`) VALUES
(1, 'Particulier'),
(2, 'Entreprise'),
(3, 'D&eacute;veloppeur'),
(4, 'D&eacute;veloppeur AliveWebProject'),
(5, 'Gestionnaire'),
(6, 'Community Manager'),
(7, 'Mod&eacute;rateur'),
(8, 'Administrateur'),
(9, 'Fondateur');

-- --------------------------------------------------------

--
-- Structure de la table `alive_users_skills`
--

CREATE TABLE `alive_users_skills` (
  `id` int(11) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `skillType` int(11) NOT NULL,
  `skillLevel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `alive_users_skills_levels`
--

CREATE TABLE `alive_users_skills_levels` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `alive_users_skills_types`
--

CREATE TABLE `alive_users_skills_types` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `alive_visites`
--

CREATE TABLE `alive_visites` (
  `id` bigint(20) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `userId` bigint(20) DEFAULT NULL,
  `createdAt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `alive_visites`
--

INSERT INTO `alive_visites` (`id`, `ip`, `userId`, `createdAt`) VALUES
(1, '127.0.0.1', NULL, 1548401219);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `alive_news`
--
ALTER TABLE `alive_news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Index pour la table `alive_projects`
--
ALTER TABLE `alive_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createdBy` (`createdBy`),
  ADD KEY `statusId` (`statusId`);

--
-- Index pour la table `alive_projects_members`
--
ALTER TABLE `alive_projects_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `rank` (`rank`),
  ADD KEY `projectId` (`projectId`);

--
-- Index pour la table `alive_projects_ranks`
--
ALTER TABLE `alive_projects_ranks`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `alive_projects_status`
--
ALTER TABLE `alive_projects_status`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `alive_settings`
--
ALTER TABLE `alive_settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `alive_support_tickets`
--
ALTER TABLE `alive_support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Index pour la table `alive_users`
--
ALTER TABLE `alive_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rank` (`rank`);

--
-- Index pour la table `alive_users_actions`
--
ALTER TABLE `alive_users_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createdBy` (`createdBy`);

--
-- Index pour la table `alive_users_permissions`
--
ALTER TABLE `alive_users_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `minRank` (`minRank`);

--
-- Index pour la table `alive_users_ranks`
--
ALTER TABLE `alive_users_ranks`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `alive_users_skills`
--
ALTER TABLE `alive_users_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `skillId` (`skillType`),
  ADD KEY `levelId` (`skillLevel`);

--
-- Index pour la table `alive_users_skills_levels`
--
ALTER TABLE `alive_users_skills_levels`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `alive_users_skills_types`
--
ALTER TABLE `alive_users_skills_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `alive_visites`
--
ALTER TABLE `alive_visites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `alive_news`
--
ALTER TABLE `alive_news`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `alive_projects`
--
ALTER TABLE `alive_projects`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `alive_projects_members`
--
ALTER TABLE `alive_projects_members`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `alive_projects_ranks`
--
ALTER TABLE `alive_projects_ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `alive_projects_status`
--
ALTER TABLE `alive_projects_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `alive_settings`
--
ALTER TABLE `alive_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `alive_support_tickets`
--
ALTER TABLE `alive_support_tickets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `alive_users`
--
ALTER TABLE `alive_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `alive_users_actions`
--
ALTER TABLE `alive_users_actions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `alive_users_permissions`
--
ALTER TABLE `alive_users_permissions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `alive_users_ranks`
--
ALTER TABLE `alive_users_ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `alive_users_skills`
--
ALTER TABLE `alive_users_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `alive_users_skills_levels`
--
ALTER TABLE `alive_users_skills_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `alive_users_skills_types`
--
ALTER TABLE `alive_users_skills_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `alive_visites`
--
ALTER TABLE `alive_visites`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `alive_news`
--
ALTER TABLE `alive_news`
  ADD CONSTRAINT `alive_news_ibfk_1` FOREIGN KEY (`createdBy`) REFERENCES `alive_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `alive_projects`
--
ALTER TABLE `alive_projects`
  ADD CONSTRAINT `alive_projects_ibfk_1` FOREIGN KEY (`createdBy`) REFERENCES `alive_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `alive_projects_ibfk_2` FOREIGN KEY (`statusId`) REFERENCES `alive_projects_status` (`id`);

--
-- Contraintes pour la table `alive_projects_members`
--
ALTER TABLE `alive_projects_members`
  ADD CONSTRAINT `alive_projects_members_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `alive_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `alive_projects_members_ibfk_2` FOREIGN KEY (`rank`) REFERENCES `alive_projects_ranks` (`id`),
  ADD CONSTRAINT `alive_projects_members_ibfk_3` FOREIGN KEY (`projectId`) REFERENCES `alive_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `alive_support_tickets`
--
ALTER TABLE `alive_support_tickets`
  ADD CONSTRAINT `alive_support_tickets_ibfk_1` FOREIGN KEY (`createdBy`) REFERENCES `alive_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `alive_users`
--
ALTER TABLE `alive_users`
  ADD CONSTRAINT `alive_users_ibfk_1` FOREIGN KEY (`rank`) REFERENCES `alive_users_ranks` (`id`);

--
-- Contraintes pour la table `alive_users_actions`
--
ALTER TABLE `alive_users_actions`
  ADD CONSTRAINT `alive_users_actions_ibfk_1` FOREIGN KEY (`createdBy`) REFERENCES `alive_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `alive_users_permissions`
--
ALTER TABLE `alive_users_permissions`
  ADD CONSTRAINT `alive_users_permissions_ibfk_1` FOREIGN KEY (`minRank`) REFERENCES `alive_users_ranks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `alive_users_skills`
--
ALTER TABLE `alive_users_skills`
  ADD CONSTRAINT `alive_users_skills_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `alive_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `alive_users_skills_ibfk_2` FOREIGN KEY (`skillType`) REFERENCES `alive_users_skills_types` (`id`),
  ADD CONSTRAINT `alive_users_skills_ibfk_3` FOREIGN KEY (`skillLevel`) REFERENCES `alive_users_skills_levels` (`id`);

--
-- Contraintes pour la table `alive_visites`
--
ALTER TABLE `alive_visites`
  ADD CONSTRAINT `alive_visites_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `alive_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
