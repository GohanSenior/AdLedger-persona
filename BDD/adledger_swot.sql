-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2026 at 09:05 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `adledger_persona`
--

-- --------------------------------------------------------

--
-- Table structure for table `swots`
--

CREATE TABLE `swots` (
  `id_swot` int NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `created_at` date NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `id_company` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `swot_items`
--

CREATE TABLE `swot_items` (
  `id_swot_item` int NOT NULL AUTO_INCREMENT,
  `category` enum('strength','weakness','opportunity','threat') NOT NULL,
  `content` text NOT NULL,
  `created_at` date NOT NULL,
  `id_swot` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `swots`
--
ALTER TABLE `swots`
  ADD PRIMARY KEY (`id_swot`),
  ADD KEY `id_company` (`id_company`);

--
-- Indexes for table `swot_items`
--
ALTER TABLE `swot_items`
  ADD PRIMARY KEY (`id_swot_item`),
  ADD KEY `id_swot` (`id_swot`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `swots`
--
ALTER TABLE `swots`
  ADD CONSTRAINT `swots_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`);

--
-- Constraints for table `swot_items`
--
ALTER TABLE `swot_items`
  ADD CONSTRAINT `swot_items_ibfk_1` FOREIGN KEY (`id_swot`) REFERENCES `swots` (`id_swot`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
