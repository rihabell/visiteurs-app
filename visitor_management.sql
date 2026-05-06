-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 11:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `visitor_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `etat` enum('disponible','attribue') DEFAULT 'disponible',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `badges`
--

INSERT INTO `badges` (`id`, `code`, `etat`, `date_creation`) VALUES
(1, 'BADGE-001', 'attribue', '2025-10-13 13:12:00'),
(2, 'BADGE-002', 'disponible', '2025-10-13 13:12:00'),
(3, 'BADGE-003', 'attribue', '2025-10-13 13:12:00'),
(4, 'BADGE-004', 'disponible', '2025-10-13 13:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `action` text NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `date_action` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `action`, `utilisateur_id`, `date_action`) VALUES
(1, 'Utilisateur admin@localhost.com connecté', 1, '2025-10-12 13:12:00'),
(2, 'Visiteur Ali Ben enregistré avec badge BADGE-001', 2, '2025-10-13 11:12:00'),
(3, 'Check-in visiteur Karim badge BADGE-003', 2, '2025-10-13 12:12:00'),
(4, 'Visiteur Nadia ajouté (en attente)', 2, '2025-10-13 13:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('accueil','visite','superviseur','admin') NOT NULL,
  `actif` tinyint(1) DEFAULT 1,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `actif`, `date_creation`) VALUES
(1, 'Admin', 'Systeme', 'admin@localhost.com', '$2y$10$MqhJTxQRxAIpS9SzYLILguChFQU39P97jVxKUqzCXTFgnrJ8zW6au', 'admin', 1, '2025-10-13 13:12:00'),
(2, 'Ahmed', 'Reception', 'accueil@localhost.com', '$2y$10$MqhJTxQRxAIpS9SzYLILguChFQU39P97jVxKUqzCXTFgnrJ8zW6au', 'accueil', 1, '2025-10-13 13:12:00'),
(3, 'Sami', 'Superviseur', 'superviseur@localhost.com', '$2y$10$MqhJTxQRxAIpS9SzYLILguChFQU39P97jVxKUqzCXTFgnrJ8zW6au', 'superviseur', 1, '2025-10-13 13:12:00'),
(4, 'Leila', 'Visite', 'visite@localhost.com', '$2y$10$MqhJTxQRxAIpS9SzYLILguChFQU39P97jVxKUqzCXTFgnrJ8zW6au', 'visite', 1, '2025-10-13 13:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `visiteurs`
--

CREATE TABLE `visiteurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `cin` varchar(50) NOT NULL,
  `societe` varchar(150) DEFAULT NULL,
  `personne_a_visiter` varchar(150) NOT NULL,
  `objet` varchar(255) NOT NULL,
  `badge_id` int(11) DEFAULT NULL,
  `date_arrivee` datetime DEFAULT NULL,
  `date_depart` datetime DEFAULT NULL,
  `statut` enum('en_attente','present','parti') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visiteurs`
--

INSERT INTO `visiteurs` (`id`, `nom`, `prenom`, `cin`, `societe`, `personne_a_visiter`, `objet`, `badge_id`, `date_arrivee`, `date_depart`, `statut`) VALUES
(1, 'Ali', 'Ben', 'AA12345', 'Société X', 'Mme Leila', 'Réunion technique', 1, '2025-10-13 09:00:00', '2025-10-13 11:00:00', 'parti'),
(2, 'Karim', 'Said', 'BB56789', 'TechCorp', 'M. Ahmed', 'Livraison matériel', 3, '2025-10-13 10:00:00', NULL, 'present'),
(3, 'Nadia', 'Zara', 'CC98765', 'Indépendante', 'Mme Leila', 'Entretien RH', NULL, NULL, NULL, 'en_attente');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visiteurs`
--
ALTER TABLE `visiteurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cin` (`cin`),
  ADD KEY `badge_id` (`badge_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visiteurs`
--
ALTER TABLE `visiteurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `visiteurs`
--
ALTER TABLE `visiteurs`
  ADD CONSTRAINT `visiteurs_ibfk_1` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
