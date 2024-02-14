-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Feb 13, 2024 at 08:04 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.15

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

--
-- Dumping data for table `AgentCRM`
--

INSERT INTO `AgentCRM` (`contact_id`, `policy_number`, `first_name`, `last_name`, `phone`, `email`, `state`, `DOB`, `closure`, `closure_date`, `closure_time`, `closure_stage`, `closure_pipeline`, `team_name`) VALUES
('3DMqunUfroLD6SeVTySM', 'U98034752', 'Nathan', 'Zamora', '19563483191', 'lucygamez99@gmail.com', 'TX', '1971-11-27', 'Maryuri Carranza', '2023-12-06', '09:49:00', 'NPN', '2024 Ambetter Book NEW', 'LPW'),
('4LEnQzJ1wBebUBwSv4Gx', 'U98059929', 'Grant', 'Jefferson', '17653750032', 'christinavincent75@gmail.com', 'IN', '1994-06-01', 'Andrea  Garcia', '2023-12-06', '12:56:00', 'NPN', '2024 Ambetter Book NEW', 'MIA'),
('8GwPCJNRL2Oofonpoy1m', 'U98027918', 'Alice', 'Snow', '19366623826', 'swift3210@gmail.com', 'TX', '1985-12-03', 'Emily Avila', '2023-12-06', '14:04:00', 'NPN', '2024 Ambetter Book NEW', 'MIA'),
('CikU55W9D8KdPDtuovUm', 'U98055666', 'Maria', 'Pacheco', '13612545665', 'erknjerk361@gmail.com', 'TX', '1992-08-04', 'Emily Avila', '2023-12-06', '08:55:00', 'NPN', '2024 Ambetter Book NEW', 'MIA'),
('ciUi1duMrIfwbnmD7TeX', 'U98003687', 'Maria', 'Dionicio', '19563077692', 'jesscuellar92a@gmal.com', 'TX', '1992-07-15', 'Andrea  Garcia', '2023-12-07', '12:07:00', 'DO NOT WORK - Unfinished Leads', '2024 Ambetter Book NEW', 'MIA'),
('cyzaoajJEKCt7eRELDgD', 'U98042367', 'Chika', 'Hall', '16825324088', 'cmariedionicio@gmail.com', 'TX', '1982-10-19', 'Jorge Serna', '2024-01-01', '08:46:00', 'Carrier', 'Misc. Data Leads To Be Worked', 'SPS'),
('DTV8F2AVJoeciIUGVRjO', 'U98023668', 'Lucia', 'Dionicio', '19564572100', 'desireezamora2011989@healthh.one', 'TX', '1989-02-01', 'Katherine Paredes', '2023-12-11', '08:15:00', 'NPN', '2024 Ambetter Book NEW', 'SPS'),
('fOWH53FW8ZeVMjBiZ8Cv', 'U98028036', 'Ciara', 'Simp', '18175008653', 'ciarac9288@gmail.com', 'TX', '2069-09-20', 'Manuel Cruz', '2024-01-10', '11:46:00', 'Different AOR', 'MYACA End of month', 'LPW'),
('KQ52d3FcIvLJw8dll3XA', 'U97995212', 'Daunel', 'Cruz Alvarez', '18634316238', 'jeanvandiver76@gmail.com', 'FL', '1977-07-12', 'Jose Lopez', '2024-01-10', '14:09:00', 'Carrier', 'MYACA End of month', 'SPS'),
('KS4K8oQNOKQuygEERLbR', 'U98057180', 'Iva', 'Vandiver', '18506073284', 'tammoorer43@gmail.com', 'FL', '1975-02-20', 'Katherine Paredes', '2024-01-11', '16:21:00', 'Carrier', 'Misc. Data Leads To Be Worked', 'SPS'),
('MAqLzbvqevd47akqOyff', 'U98053890', 'Blanca', 'Downey', '12515380210', 'victorsmail77@aol.com', 'FL', '1984-04-23', 'Jose Lopez', '2024-01-11', '16:17:00', 'Found - CANX/No Plan/ - SSN/Income Needed', 'Misc. Data Leads To Be Worked', 'SPS'),
('nAuPQrsgfcAvSsOHtAFk', 'U97998905', 'Emily', 'Peterson', '17276089472', 'saoutsarah3@gmail.com', 'FL', '1986-07-16', 'Maryuri Carranza', '2024-01-11', '22:45:00', 'SOLD - AOR-  OP/RC', 'Misc. Data Leads To Be Worked', 'LPW'),
('nEoeXdNjVbtfBQbsydfo', 'U98020009', 'Celina', 'Gamez', '17065307578', 'walteryoung966@gmail.com', 'GA', '1982-05-06', 'Katherine Paredes', '2024-01-08', '09:33:00', 'Not Found-SSN/Income Needed Create New', 'New Lead Agent Pipeline No SSN/Income', 'SPS'),
('NTHKWUfUirkDrwc3APz1', 'U98033021', 'Spinach', 'Neighbors', '18128904377', 'sunshine193116@gmail.com', 'IN', '1990-09-19', 'Emily Avila', '2024-01-11', '10:33:00', 'Carrier', 'MYACA End of month', 'MIA'),
('oPgUzeoYfUIVqxEvzP1z', 'U98034743', 'Nicole', 'Campbell', '19033603338', 'alicesnow58@gmail.com', 'TX', '1973-06-30', 'Maryuri Carranza', '2023-12-06', '09:10:00', 'NPN', '2024 Ambetter Book NEW', 'LPW'),
('PbFDPgpJ66vO0sIZWXgG', 'U98042367', 'Desiree', 'Youngsr', '16825324088', 'cmariedioncio@gmail.com', 'TX', '1982-10-19', 'Jorge Serna', '2024-01-10', '12:29:00', 'Different AOR', 'MYACA End of month', 'SPS'),
('Q6UHLb1x4a0nTjOPKsb5', 'U98051222', 'Jessica', 'Rodriguez', '13463492700', 'goldsteinerika189@gmail.com', 'TX', '1976-01-06', 'Manuel Cruz', '2023-12-07', '08:23:00', 'NPN', '2024 Ambetter Book NEW', 'LPW'),
('Q938SkKmq0IhAu44Xf20', 'U98003416', 'Angela', 'Gonzalez', '19565862017', 'reynachiquitarodriguez87@gmail.com', 'TX', '1987-01-16', 'Jose Lopez', '2024-01-16', '10:17:00', 'Application Extended', 'Application Extensions', 'SPS'),
('rJoTBNaArEJFlQsNqoHR', 'U98000129', 'Adrianna', 'Leblanc', '15156032004', 'maryellen@gmail.com', 'TX', '1980-12-19', 'Maryuri Carranza', '2023-12-09', '11:32:00', 'NPN', '2024 Ambetter Book NEW', 'LPW'),
('RxKC74S1MMV1pYUgmTRn', 'U98049004', 'Walter', 'Arce', '13053018562', 'name.maria88@gmail.com', 'FL', '2064-07-17', 'Jorge Serna', '2024-01-11', '23:37:00', 'Different FL AOR', 'Misc. Data Leads To Be Worked', 'SPS'),
('Tblok2AsNAY9hRPeoukU', 'U98002418', 'Erika', 'Victor', '14092973535', 'laceymccoy1976@gmail.com', 'TX', '1976-12-19', 'Emily Avila', '2024-01-16', '16:08:00', 'Different AOR', 'Application Extensions', 'MIA'),
('W0yPzP9vmLaUdGiyS2Hc', 'U97994148', 'Tamara', 'Vincent', '19139672927', 'grantjefferson57@gmail.com', 'TX', '2065-03-15', 'Elena Urbina', '2023-12-06', '10:59:00', 'NPN', '2024 Ambetter Book NEW', 'MIA'),
('XhbBOTVNt31cs4emg9Sw', 'U98056824', 'Sarah', 'Saout', '17864424105', '', 'FL', '1997-09-19', 'Katherine Paredes', '2024-01-15', '14:46:00', 'DO NOT WORK - Unfinished Leads', 'Application Extensions', 'SPS'),
('YWrlYtVLWfphrRomoxxF', 'U98002773', 'James', 'Dilla', '19792569151', 'angelalois77@gmail.com', 'TX', '1977-11-16', 'Emily Avila', '2023-12-01', '08:48:00', 'Not Found-SSN/Income Needed Create New', 'Book 2023 MYACA Ambetter', 'MIA'),
('Zh5UmUiH8Jb2t1IVX5H5', 'U98055324', 'Philip', 'Moore', '12819125405', 'prettibrwnbrwn86@gmail.com', 'TX', '1986-01-11', 'Emily Avila', '2023-12-06', '09:22:00', 'NPN', '2024 Ambetter Book NEW', 'MIA');

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

--
-- Dumping data for table `Ambetter`
--

INSERT INTO `Ambetter` (`policy_number`, `broker_name`, `broker_npn`, `first_name`, `last_name`, `broker_effective_date`, `broker_term_date`, `policy_effective_date`, `policy_term_date`, `paid_through_date`, `county`, `state`, `on_off_exchange`, `exchange_subscriber_id`, `member_phone_number`, `member_email`, `member_responsibility`, `member_DOB`, `autopay`, `eligible_for_commission`, `number_of_members`, `payable_agent`, `ar_policy_type`, `created`) VALUES
('U70035082', 'Tommy John', '7792962', 'Julian', 'Ortiz', '2021-09-01', '9999-12-31', '2021-09-01', '2021-12-31', '2021-12-31', 'MIAMI-DADE', 'FL', 'On', '\'0010721359\'', '7865876954', '', '0', '2061-07-16', 'Yes', 'No', 2, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U70060841', 'Tommy John', '7792962', 'Narmy', 'Valdes', '2020-02-01', '9999-12-31', '2023-02-01', '2023-02-28', '2023-02-28', 'MIAMI-DADE', 'FL', 'On', '\'0014229882\'', '7863172723', 'ehernandez69@gmail.com', '0', '2068-11-23', 'Yes', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U90511598', 'Tommy John', '7792962', 'Felipe', 'Rios', '2020-01-01', '9999-12-31', '2021-05-01', '2021-12-31', '2021-12-31', 'MIAMI-DADE', 'FL', 'On', '\'0013179845\'', '3059103142', 'oramas_a@yahoo.com', '230.34', '2062-07-02', 'No', 'No', 3, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U91453701', 'Tommy John', '7792962', 'Ian', 'Mcknight', '2023-09-01', '9999-12-31', '2022-07-01', '2024-12-31', '2024-12-31', 'Charleston', 'SC', 'On', '\'0002928447\'', '8435346087', 'chefion86@gmail.com', '0', '1986-12-09', '', 'Yes', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U91623673', 'Tommy John', '7792962', 'Sheepy', 'Mcstevens', '2024-01-01', '9999-12-31', '2023-07-01', '1999-12-31', '2024-12-31', 'DUVAL', 'FL', 'On', '\'0004571095\'', '9045089191', 'cathysanders81@gmail.com', '0', '1977-06-27', 'No', 'Yes', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U91806031', 'Tommy John', '7792962', 'Cathy', 'Sanders', '2021-05-01', '9999-12-31', '2021-05-01', '2022-03-31', '2022-03-31', 'MIAMI-DADE', 'FL', 'On', '\'0013730400\'', '3057219243', 'camiloboza@aca100.com', '25.96', '2061-10-20', 'No', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U91950007', 'Tommy John', '7792962', 'Camilo', 'Perez Caseres', '2022-01-01', '9999-12-31', '2021-06-01', '2021-10-31', '2021-10-31', 'GLADES', 'FL', 'On', '\'0012867500\'', '3053452807', '', '52.95', '2069-02-05', 'No', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U92052870', 'Tommy John', '7792962', 'Idalmis', 'Pita Hernandez', '2021-09-01', '9999-12-31', '2021-09-01', '2022-04-30', '2022-04-30', 'MIAMI-DADE', 'FL', 'On', '\'0012446728\'', '7863555314', '', '0', '2062-07-15', 'Yes', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U92112585', 'Tommy John', '7792962', 'Leobel', 'Martin', '2021-09-01', '9999-12-31', '2021-06-01', '2021-06-16', '2021-06-16', 'POLK', 'FL', 'On', '\'0012994592\'', '7864789190', 'dianaroldo10@gmail.com', '28.3', '2061-06-25', 'No', 'No', 2, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U92821355', 'Tommy John', '7792962', 'Alberto', 'Oramas', '2022-01-01', '9999-12-31', '2021-01-01', '2022-07-31', '2022-07-31', 'BROWARD', 'FL', 'On', '\'0013303602\'', '9549185827', '', '23.05', '1983-07-10', 'No', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U93302298', 'Tommy John', '7792962', 'Vivian', 'Maxwell', '2022-01-01', '9999-12-31', '2018-01-01', '2021-12-31', '2021-12-31', 'MIAMI-DADE', 'FL', 'On', '\'0014021340\'', '3052312483', 'kayrie.mamry@yahoo.com', '34.53', '1989-12-17', 'Yes', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U93338386', 'Tommy John', '7792962', 'Yosmany', 'Perez', '2021-06-01', '9999-12-31', '2023-01-01', '2023-06-30', '2023-06-30', 'MIAMI-DADE', 'FL', 'On', '\'0013713471\'', '7863061316', '', '87.48', '2066-06-07', 'Yes', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U93387092', 'Tommy John', '7792962', 'Shirley', 'Siblesz', '2019-11-27', '9999-12-31', '2023-01-01', '2023-01-31', '2023-01-31', 'MIAMI-DADE', 'FL', 'On', '\'0015566952\'', '7863800556', 'reinaldoelrey86@gmal.com', '0', '1974-02-28', 'No', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U93455759', 'Tommy John', '7792962', 'Pedro', 'Davila', '2022-01-01', '9999-12-31', '2020-01-01', '2021-12-31', '2021-12-31', 'MIAMI-DADE', 'FL', 'On', '\'0013916508\'', '3057475369', 'idalmis@yahoo.com', '45.93', '1974-10-14', 'No', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U93561567', 'Tommy John', '7792962', 'Hector', 'Garrido', '2021-09-01', '9999-12-31', '2021-11-01', '2023-09-30', '2023-09-30', 'MIAMI-DADE', 'FL', 'On', '\'0016385856\'', '3055437690', 'reyesme@gmail.com', '0', '2064-09-24', 'No', 'No', 2, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U93656005', 'Tommy John', '7792962', 'Osvaldo', 'Garcia Lopez', '2021-09-01', '9999-12-31', '2021-09-01', '2023-04-07', '2023-04-07', 'PASCO', 'FL', 'On', '\'0011485791\'', '7272372340', 'sareyes64@gmail.com', '34.03', '2064-02-12', 'Yes', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U93743437', 'Tommy John', '7792962', 'Pedro', 'Gomez', '2020-04-04', '9999-12-31', '2021-04-01', '2022-04-30', '2022-04-30', 'MIAMI-DADE', 'FL', 'On', '\'0012785049\'', '3055607739', '', '0', '2053-10-18', 'No', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U94034275', 'Tommy John', '7792962', 'Beatriz', 'Fernandez', '2019-11-24', '9999-12-31', '2021-02-01', '2023-02-08', '2023-02-08', 'HILLSBOROUGH', 'FL', 'On', '\'0009682689\'', '8136068815', 'vivianmaxwell78@gmail.com', '0', '1996-12-14', 'Yes', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U94583865', 'Tommy John', '7792962', 'Reinaldo', 'Moreno', '2019-12-05', '9999-12-31', '2021-08-01', '2022-04-30', '2022-04-30', 'MIAMI-DADE', 'FL', 'On', '\'0013639273\'', '7863576446', '', '28.72', '2058-06-25', 'No', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U94707016', 'Tommy John', '7792962', 'Ernesto', 'Hernandez', '2023-09-01', '9999-12-31', '2023-06-01', '2024-12-31', '2024-12-31', 'Lancaster', 'SC', 'On', '\'0003299520\'', '8032353693', 'riccobell43g@gmail.com', '0', '1975-10-08', '', 'Yes', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U94756024', 'Tommy John', '7792962', 'Ricardo', 'Reyes', '2020-01-01', '9999-12-31', '2021-09-01', '2022-04-30', '2022-04-30', 'MIAMI-DADE', 'FL', 'On', '\'0010405807\'', '7863198111', 'yosgl@gmail.com', '0', '1989-04-11', 'No', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U94977387', 'Tommy John', '7792962', 'Angel', 'Navarro', '2020-01-01', '9999-12-31', '2021-06-01', '2021-12-31', '2021-12-31', 'MIAMI-DADE', 'FL', 'On', '\'0009377371\'', '3059106264', 'ssilblesz64@gmail.com', '224.66', '2064-08-10', 'No', 'No', 2, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U94981951', 'Tommy John', '7792962', 'Sandra', 'Bell', '2021-08-01', '9999-12-31', '2021-08-01', '2022-12-31', '2022-12-31', 'LEE', 'FL', 'On', '\'0012282919\'', '7863086843', 'valdess2323@aol.com', '198.47', '2061-11-16', 'Yes', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46'),
('U95186074', 'Tommy John', '7792962', 'Mercedes', 'Zapata', '2020-11-01', '9999-12-31', '2021-05-01', '2022-12-31', '2022-12-31', 'MIAMI-DADE', 'FL', 'On', '\'0013527263\'', '7867577961', '', '70.36', '2055-12-07', 'Yes', 'No', 1, 'Health Family Insurance', '', '2024-02-13 20:00:46');

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
  `last_seen` datetime DEFAULT NULL,
  `forgot_password_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Employee`
--

INSERT INTO `Employee` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `activation_code`, `rememberme`, `phone`, `created`, `registered`, `role`, `last_seen`, `forgot_password_token`) VALUES
(1, 'cheesestick', 'Erick', 'Delgado', 'delrick2323@outlook.com', '$2y$10$jOuAMq8Hj5lPqE81NraOhuyJrfT1STtkx/fVkAomVQsqYTPZ6KxN2', '1', '$2y$10$yrlEr8S2QIU8vRC60MvFBO6nccRdy5RlaTYE31Mek11kGeA2opixG', '3059654400', '2023-10-30 14:41:24', '2023-10-30 14:41:24', 'admin', '2024-02-13 19:29:26', NULL),
(4, 'SimpleTester', 'Simple', 'Tester', 'yoitserick2323@gmail.com', '$2y$10$IfP2nO6FbK1ds2OI4XPFLu7tjcYprCGyriZ.9zUpGwZj1hIbIZ3Nu', '1', '', '3059654400', '2024-01-24 14:36:05', '2024-01-24 14:36:05', 'agent', NULL, NULL),
(5, 'jjFinder', 'jj', 'Finder', 'wbielski24@gmail.com', '$2y$10$huSvwTKzNSpgSjNzkMRR6OVw9tZ3ZEHVeQ6gRB78Y3jNb8EWZ0/4i', '1', '', '3059654400', '2024-01-24 16:51:21', '2024-01-24 16:51:21', 'agent', NULL, NULL);

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

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `color`, `datestart`, `dateend`, `uid`, `submit_date`, `recurring`, `photo_url`, `lead_id`) VALUES
(1, 'Aaron Donald', 'Email: adonald123456@tempest.com<br><br>\r\nPhone: 7778884444<br><br>\r\nThis person is smelly', '#5373ae', '2024-02-08 13:00:00', '2024-02-08 13:00:00', 0, '2024-02-06 23:55:52', 'never', '', 1),
(2, 'Belgium Barron', 'Email: BBarronBelgium1234567@yacboob.com\nPhone: 7778884444\nnew note', '#5373ae', '2024-02-13 04:40:00', '2024-02-13 23:59:59', 0, '2024-02-12 18:39:46', 'never', '', 2);

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
-- Dumping data for table `Leads`
--

INSERT INTO `Leads` (`id`, `first_name`, `last_name`, `email`, `phone`, `state`, `DOB`, `serviced`, `created`, `recontact_date`, `notes`) VALUES
(1, 'Aaron', 'Donald', 'adonald123456@tempest.com', '7778884444', 'AL', '2024-02-07', 1, '2024-01-24 14:55:00', '2024-02-08 13:00:00', '                                                                                    Email: adonald123456@tempest.com<br><br>\r\nPhone: 7778884444<br><br>\r\nThis person is smelly                                                                        '),
(2, 'Belgium', 'Barron', 'BBarronBelgium1234567@yacboob.com', '7778884444', 'AK', '1998-11-11', 1, '2024-01-24 14:56:08', '2024-02-13 04:40:00', 'new note'),
(3, 'Carson', 'Guiterre', 'CGguiti11HelloWorld@yahoo.com', '7778884444', 'AZ', '2001-09-11', 0, '2024-01-24 14:56:42', NULL, NULL),
(4, 'Dixie', 'Enourmous', 'DixieEnourmous123321@yahoo.com', '1112223333', 'AR', '2000-08-02', 0, '2024-01-24 14:57:34', NULL, NULL);

--
-- Triggers `Leads`
--
DELIMITER $$
CREATE TRIGGER `update_events_after_lead_update` AFTER UPDATE ON `Leads` FOR EACH ROW BEGIN
    IF OLD.recontact_date <> NEW.recontact_date THEN
        UPDATE events
        SET datestart = NEW.recontact_date, dateend = NEW.recontact_date
        WHERE lead_id = NEW.id;
    END IF;
END
$$
DELIMITER ;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Leads`
--
ALTER TABLE `Leads`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
