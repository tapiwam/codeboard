-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2013 at 05:47 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cb`
--
CREATE DATABASE IF NOT EXISTS `cb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cb`;

-- --------------------------------------------------------

--
-- Table structure for table `announce`
--

CREATE TABLE IF NOT EXISTS `announce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(25) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(15) NOT NULL COMMENT 'e.g. fall2012',
  `class_name` varchar(25) NOT NULL COMMENT 'name of class',
  `section` varchar(8) NOT NULL DEFAULT '1' COMMENT 'section number',
  `instructor` varchar(25) NOT NULL COMMENT 'instructor username',
  `lang` varchar(15) NOT NULL DEFAULT 'cpp' COMMENT 'e.g. cpp, java, c',
  `passcode` varchar(25) NOT NULL DEFAULT 'codeboard',
  `class_name_id` varchar(75) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `term`, `class_name`, `section`, `instructor`, `lang`, `passcode`, `class_name_id`, `active`) VALUES
(3, 'spring2014', 'test1', '3', 'tapiwa', 'cpp', 'passcode', 'spring2014-test1-3-tapiwa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `class_instructors`
--

CREATE TABLE IF NOT EXISTS `class_instructors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `owner` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `class_instructors`
--

INSERT INTO `class_instructors` (`id`, `class_id`, `user_id`, `username`, `owner`) VALUES
(1, 1, 110, 'tapiwa', 110),
(2, 2, 110, 'tapiwa', 110),
(3, 3, 110, 'tapiwa', 110);

-- --------------------------------------------------------

--
-- Table structure for table `codeboard_sessions`
--

CREATE TABLE IF NOT EXISTS `codeboard_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_login`
--

CREATE TABLE IF NOT EXISTS `log_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE IF NOT EXISTS `registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `registration`
--

INSERT INTO `registration` (`id`, `user_id`, `class_id`, `active`) VALUES
(1, 111, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(50) NOT NULL,
  `level` int(1) NOT NULL DEFAULT '0',
  `student_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=112 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `password`, `email`, `level`, `student_id`, `active`) VALUES
(110, 'Tapiwa', 'Maruni', 'tapiwa', 'b4effb03f873c12e9de335c3a7d2794a', 'tapiwa.maruni@gmail.com', 2, 0, 1),
(110, 'Admin', 'Account', 'admin', 'b4effb03f873c12e9de335c3a7d2794a', 'rattler.cis.alumni@gmail.com', 3, 0, 1)
(111, 'T', 'M', 'user1', '1a1dc91c907325c69271ddf0c944bc72', 't@gmail.com', 0, 300087035, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
