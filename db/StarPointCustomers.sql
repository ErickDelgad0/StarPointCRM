-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Oct 26, 2023 at 06:34 PM
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
-- Table structure for table `Customers`
--

CREATE TABLE `Customers` (
  `id` int NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Customers`
--

INSERT INTO `Customers` (`id`, `first_name`, `last_name`, `email`, `phone`, `title`, `created`) VALUES
(1, 'Sam', 'White', 'samwhite@example.com', '2004550121', 'Manager', '2023-09-12 17:29:00'),
(2, 'Colin', 'Chaplin', 'colinchaplin@example.com', '2022550178', 'Employee', '2023-09-12 17:29:00'),
(3, 'Ricky', 'Waltz', 'rickywaltz@example.com', '7862342390', 'Employee', '2023-09-12 19:16:00'),
(4, 'Arnold', 'Hall', 'arnoldhall@example.com', '5089573579', 'Manager', '2023-09-12 19:17:00'),
(5, 'Donald', 'Smith', 'donald1983@example.com', '7019007916', 'Employee', '2023-09-12 19:20:00'),
(6, 'Nadia', 'Doole', 'nadia.doole0@example.com', '6153353674', 'Employee', '2023-09-12 19:20:00'),
(7, 'Sarah', 'Jones', 'angela1977@example.com', '3094234980', 'Assistant', '2023-09-12 19:21:00'),
(8, 'Robert', 'Junior', 'robertjunior@example.com', '4209875343', 'Assistant', '2023-09-12 23:52:00'),
(9, 'Jakob', 'Biggs', 'jakobbiggs@example.com', '0125345786', 'Manager', '2023-09-11 16:48:00'),
(10, 'John', 'Doe', 'johndoe@example.com', '0675213823', 'Manager', '2023-09-12 20:17:00');

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
  `phone` varchar(100) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `registered` datetime NOT NULL,
  `type` varchar(25) NOT NULL DEFAULT 'guest',
  `last_seen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Customers`
--
ALTER TABLE `Customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Employee`
--
ALTER TABLE `Employee`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Customers`
--
ALTER TABLE `Customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Employee`
--
ALTER TABLE `Employee`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
