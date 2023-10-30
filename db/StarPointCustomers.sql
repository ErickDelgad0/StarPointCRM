-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Oct 30, 2023 at 03:28 PM
-- Server version: 8.1.0
-- PHP Version: 8.2.10

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
-- Table structure for table `Ambetter`
--

CREATE TABLE `Ambetter` (
  `id` int NOT NULL,
  `broker_name` varchar(255) DEFAULT NULL,
  `broker_npn` varchar(255) DEFAULT NULL,
  `policy_number` varchar(255) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `broker_effective_date` date DEFAULT NULL,
  `broker_term_date` date DEFAULT NULL,
  `policy_effective_date` date DEFAULT NULL,
  `policy_term_date` date DEFAULT NULL,
  `paid_through_date` date DEFAULT NULL,
  `county` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `on/off_exchange` varchar(50) DEFAULT NULL,
  `exchange_subscriber_id` varchar(255) DEFAULT NULL,
  `member_phone_number` varchar(255) DEFAULT NULL,
  `member_email` varchar(255) DEFAULT NULL,
  `member_responsibility` varchar(255) DEFAULT NULL,
  `member_DOB` date DEFAULT NULL,
  `autopay` varchar(50) DEFAULT NULL,
  `eligible_for_commission` varchar(50) DEFAULT NULL,
  `number_of_members` int DEFAULT NULL,
  `payable_agent` varchar(255) DEFAULT NULL,
  `ar_policy_type` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Ambetter`
--

INSERT INTO `Ambetter` (`id`, `broker_name`, `broker_npn`, `policy_number`, `first_name`, `last_name`, `broker_effective_date`, `broker_term_date`, `policy_effective_date`, `policy_term_date`, `paid_through_date`, `county`, `state`, `on/off_exchange`, `exchange_subscriber_id`, `member_phone_number`, `member_email`, `member_responsibility`, `member_DOB`, `autopay`, `eligible_for_commission`, `number_of_members`, `payable_agent`, `ar_policy_type`, `created`) VALUES
(1, 'Erick Delgado', '8829666', 'U70153469', 'Candie', 'Cane', '2023-07-01', '2099-12-31', '2023-07-01', '2024-12-31', '2023-12-31', 'MIAMI-DADE', 'FL', 'On', '\'0010243871\'', '3050000000', NULL, '0', '2020-02-06', NULL, 'Yes', 1, 'Health Family Insurance', NULL, '2023-10-30 14:43:26'),
(2, 'Erick Delgado', '8829666', 'U70153466', 'Hello', 'World', '2023-07-01', '2099-12-31', '2023-07-01', '2024-12-31', '2023-12-31', 'Orange County', 'FL', 'On', '\'0010243871\'', '3051000000', NULL, '0', '2020-02-06', NULL, 'Yes', 1, 'Health Family Insurance', NULL, '2023-10-30 14:43:26'),
(3, 'Erick Delgado', '8829666', 'U70153666', 'Sunshine', 'Rainbow', '2023-07-01', '2099-12-31', '2023-07-01', '2024-12-31', '2023-12-31', 'MIAMI-DADE', 'FL', 'On', '\'0010243871\'', '3052000000', NULL, '0', '2020-02-06', NULL, 'Yes', 1, 'Health Family Insurance', NULL, '2023-10-30 14:43:26');

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
  `phone` varchar(100) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `registered` datetime NOT NULL,
  `role` varchar(25) NOT NULL DEFAULT 'guest',
  `last_seen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Employee`
--

INSERT INTO `Employee` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `activation_code`, `rememberme`, `phone`, `created`, `registered`, `role`, `last_seen`) VALUES
(1, 'cheesestick', 'Erick', 'Delgado', 'delrick2323@outlook.com', '$2y$10$imHdOR35Lii3u4LhG/gis.0o0M6gn3bCG5sh.1ZMnICTCjejIepUu', '1', '$2y$10$yrlEr8S2QIU8vRC60MvFBO6nccRdy5RlaTYE31Mek11kGeA2opixG', '', '2023-10-30 14:41:24', '2023-10-30 14:41:24', 'admin', '2023-10-30 14:43:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Employee`
--
ALTER TABLE `Employee`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `Ambetter`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Employee`
--
ALTER TABLE `Employee`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

ALTER TABLE `Ambetter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
