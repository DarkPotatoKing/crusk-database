-- Database must be named "cruskdb"


-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2016 at 12:17 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cruskdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_name` varchar(20) NOT NULL,
  `class_id` int(11) NOT NULL,
  `stalker_username` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `crusks`
--

CREATE TABLE `crusks` (
  `student_number` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `crusks`
--

INSERT INTO `crusks` (`student_number`, `name`, `facebook`, `twitter`, `instagram`) VALUES
(201400000, 'EMMA WATSON', 'facebook.com/emma.watson', 'twiiter.com/emma.watson', 'instagram.com/emma.watson'),
(201400001, 'EMMA STONE', 'facebook.com/emma.stone', 'twitter.com/emma.stone', 'instagram.com/emma.stone'),
(201400002, 'MARLOU ARIZALA', 'facebook.com/marlou.arizala', 'twitter.com/marlou.arizala', 'instagram.com/marlou.arizala'),
(201400003, 'DANA KAREGLAZAYA', 'facebook.com/dana.kareglazaya', 'twitter.com/dana.kareglazaya', 'instagram.com/dana.kareglazaya'),
(201427982, 'CLARENCE DAVID CO', 'facebook.com/profile.php?id=100000177876359', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `isclassmatein`
--

CREATE TABLE `isclassmatein` (
  `id` int(11) NOT NULL,
  `stalk_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `note` text NOT NULL,
  `stalker_username` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `crusk_student_number` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stalkers`
--

CREATE TABLE `stalkers` (
  `username` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `password` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stalks`
--

CREATE TABLE `stalks` (
  `id` int(11) NOT NULL,
  `stalker_username` varchar(20) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `crusk_student_number` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_name` (`class_name`,`class_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `stalker_username` (`stalker_username`);

--
-- Indexes for table `crusks`
--
ALTER TABLE `crusks`
  ADD PRIMARY KEY (`student_number`);

--
-- Indexes for table `isclassmatein`
--
ALTER TABLE `isclassmatein`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stalk_id_2` (`stalk_id`,`class_id`),
  ADD KEY `stalk_id` (`stalk_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stalker_username` (`stalker_username`),
  ADD KEY `crusk_student_number` (`crusk_student_number`);

--
-- Indexes for table `stalkers`
--
ALTER TABLE `stalkers`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `stalks`
--
ALTER TABLE `stalks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stalker_username` (`stalker_username`,`crusk_student_number`),
  ADD KEY `crusk_student_number` (`crusk_student_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `isclassmatein`
--
ALTER TABLE `isclassmatein`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `stalks`
--
ALTER TABLE `stalks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`stalker_username`) REFERENCES `stalkers` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `isclassmatein`
--
ALTER TABLE `isclassmatein`
  ADD CONSTRAINT `isclassmatein_ibfk_1` FOREIGN KEY (`stalk_id`) REFERENCES `stalks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `isclassmatein_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`stalker_username`) REFERENCES `stalkers` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`crusk_student_number`) REFERENCES `crusks` (`student_number`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `stalks`
--
ALTER TABLE `stalks`
  ADD CONSTRAINT `stalks_ibfk_1` FOREIGN KEY (`stalker_username`) REFERENCES `stalkers` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `stalks_ibfk_2` FOREIGN KEY (`crusk_student_number`) REFERENCES `crusks` (`student_number`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
