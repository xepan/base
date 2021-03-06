-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 06, 2016 at 04:23 PM
-- Server version: 10.1.19-MariaDB-1~xenial
-- PHP Version: 7.0.8-0ubuntu0.16.04.3

DROP TABLE IF EXISTS file;
DROP TABLE IF EXISTS folder;
DROP TABLE IF EXISTS document_share;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `printonclick`
--

-- --------------------------------------------------------

--
-- Table structure for table `document_share`
--

CREATE TABLE `document_share` (
  `id` int(11) NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  `shared_by_id` int(11) NOT NULL,
  `shared_to_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `shared_type` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `can_edit` tinyint(4) DEFAULT NULL,
  `can_delete` tinyint(4) DEFAULT NULL,
  `can_share` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE `file` (
  `document_id` int(11) NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `content` longtext,
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `file_id` int(11) DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `mime` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `document_share`
--
ALTER TABLE `document_share`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Folder id` (`folder_id`) USING BTREE,
  ADD KEY `File id` (`file_id`) USING BTREE,
  ADD KEY `department` (`department_id`) USING BTREE;

--
-- Indexes for table `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Folder id` (`folder_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `document_share`
--
ALTER TABLE `document_share`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;