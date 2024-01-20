-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jan 19, 2024 at 03:21 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `StarPointCustomers`
--

-- --------------------------------------------------------

--
-- Table structure for table `AgentCRM`
--

CREATE TABLE `AgentCRM` (
  `contact_id` varchar(255) NOT NULL,
  `policy_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `closure` varchar(100) NOT NULL,
  `closure_date` date NOT NULL,
  `closure_time` time NOT NULL,
  `closure_stage` varchar(500) DEFAULT NULL,
  `closure_pipeline` varchar(255) DEFAULT NULL,
  `team_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Ambetter`
--

CREATE TABLE `Ambetter` (
  `policy_number` varchar(50) NOT NULL,
  `broker_name` varchar(255) NOT NULL,
  `broker_npn` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `broker_effective_date` date NOT NULL,
  `broker_term_date` date NOT NULL,
  `policy_effective_date` date NOT NULL,
  `policy_term_date` date NOT NULL,
  `paid_through_date` date NOT NULL,
  `county` varchar(255) NOT NULL,
  `state` varchar(10) NOT NULL,
  `on_off_exchange` varchar(4) NOT NULL,
  `exchange_subscriber_id` varchar(255) NOT NULL,
  `member_phone_number` varchar(20) NOT NULL,
  `member_email` varchar(255) NOT NULL,
  `member_responsibility` varchar(255) NOT NULL,
  `member_DOB` date NOT NULL,
  `autopay` varchar(4) NOT NULL,
  `eligible_for_commission` varchar(4) NOT NULL,
  `number_of_members` int NOT NULL,
  `payable_agent` varchar(255) NOT NULL,
  `ar_policy_type` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Employee`
--

CREATE TABLE `Employee` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `activation_code` varchar(50) NOT NULL DEFAULT '',
  `rememberme` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `registered` datetime NOT NULL,
  `role` varchar(25) NOT NULL DEFAULT 'guest',
  `last_seen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Employee`
--

INSERT INTO `Employee` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `activation_code`, `rememberme`, `phone`, `created`, `registered`, `role`, `last_seen`) VALUES
(1, 'cheesestick', 'Erick', 'Delgado', 'delrick2323@outlook.com', '$2y$10$iuwGhY3xf/ScOPiGo5Ks6OULAoa6XPXXTo9YBxt3BuNauVluBQXQ2', '1', '$2y$10$yrlEr8S2QIU8vRC60MvFBO6nccRdy5RlaTYE31Mek11kGeA2opixG', '3059654400', '2023-10-30 14:41:24', '2023-10-30 14:41:24', 'admin', '2024-01-19 03:19:40');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `color` varchar(10) NOT NULL,
  `datestart` datetime NOT NULL,
  `dateend` datetime NOT NULL,
  `uid` int NOT NULL,
  `submit_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recurring` enum('never','daily','weekly','monthly','yearly') NOT NULL DEFAULT 'never',
  `photo_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '',
  `lead_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Leads`
--

CREATE TABLE `Leads` (
  `id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `state` varchar(50) NOT NULL,
  `DOB` date NOT NULL,
  `serviced` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recontact_date` datetime DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `AgentCRM`
--
ALTER TABLE `AgentCRM`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `Ambetter`
--
ALTER TABLE `Ambetter`
  ADD PRIMARY KEY (`policy_number`);

--
-- Indexes for table `Employee`
--
ALTER TABLE `Employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_id_fk` (`lead_id`);

--
-- Indexes for table `Leads`
--
ALTER TABLE `Leads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Employee`
--
ALTER TABLE `Employee`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `Leads`
--
ALTER TABLE `Leads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `lead_id_fk` FOREIGN KEY (`lead_id`) REFERENCES `Leads` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
