-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 07, 2023 at 12:39 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `docreview`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `commentId` int NOT NULL,
  `docId` int NOT NULL,
  `line` int NOT NULL,
  `content` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE IF NOT EXISTS `document` (
  `document_id` int NOT NULL,
  `email` varchar(25) CHARACTER SET latin1 COLLATE utf8mb3_general_ci NOT NULL,
  `fileName` varchar(30) CHARACTER SET armscii8 COLLATE utf8mb3_general_ci NOT NULL,
  `version` int NOT NULL,
  `file_type` varchar(10) NOT NULL,
  `uploadDate` date NOT NULL,
  `content` mediumblob NOT NULL,
  PRIMARY KEY (`document_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

DROP TABLE IF EXISTS `organization`;
CREATE TABLE IF NOT EXISTS `organization` (
  `email` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb3_general_ci NOT NULL,
  `officeName` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`email`, `officeName`) VALUES
('212222@slu.edu.ph', 'Finance'),
('212222@slu.edu.ph', 'Finance');

-- --------------------------------------------------------

--
-- Table structure for table `reviewsequence`
--

DROP TABLE IF EXISTS `reviewsequence`;
CREATE TABLE IF NOT EXISTS `reviewsequence` (
  `reviewID` int NOT NULL,
  `email` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb3_general_ci NOT NULL,
  `sequenceOrder` int NOT NULL,
  `Status` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviewtransaction`
--

DROP TABLE IF EXISTS `reviewtransaction`;
CREATE TABLE IF NOT EXISTS `reviewtransaction` (
  `reviewID` int NOT NULL,
  `document_id` int NOT NULL,
  `email` varchar(25) NOT NULL,
  `status` varchar(12) NOT NULL,
  `approved_date` date NOT NULL,
  PRIMARY KEY (`reviewID`),
  KEY `documentID` (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `firstName` varchar(30) NOT NULL,
  `lastName` varchar(30) NOT NULL,
  `role` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`email`, `password`, `firstName`, `lastName`, `role`, `status`) VALUES
('123@123.com', '123', '3234', '234', 'Admin', 'Online'),
('321@321.com', 'asd', 'asd', 'asd', 'Requester', 'Offline'),
('rev@rev.com', 'rev', 'rev', 'rev', 'Reviewer', 'Offline');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `email` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `reviewtransaction`
--
ALTER TABLE `reviewtransaction`
  ADD CONSTRAINT `documentID` FOREIGN KEY (`document_id`) REFERENCES `document` (`document_id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
