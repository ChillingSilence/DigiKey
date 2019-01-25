-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 24, 2019 at 04:26 AM
-- Server version: 5.7.24-0ubuntu0.16.04.1
-- PHP Version: 7.0.32-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digiidjo`
--

-- --------------------------------------------------------

--
-- Table structure for table `digiid_nonces`
--

CREATE TABLE `digiid_nonces` (
  `s_ip` varchar(46) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `dt_datetime` datetime NOT NULL,
  `s_nonce` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `s_address` varchar(34) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `digiid_users`
--

CREATE TABLE `digiid_users` (
  `addr` varchar(46) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `fio` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `isadmin` int(1) NOT NULL DEFAULT '0' COMMENT 'User is an Admin?',
  `ispermitted` int(1) NOT NULL DEFAULT '0' COMMENT 'User is permitted to access?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_nonces`
--

CREATE TABLE `tbl_nonces` (
  `s_ip` varchar(46) COLLATE utf8_bin NOT NULL,
  `dt_datetime` datetime NOT NULL,
  `s_nonce` varchar(32) COLLATE utf8_bin NOT NULL,
  `s_address` varchar(34) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `tbl_nonces`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `digiid_nonces`
--
ALTER TABLE `digiid_nonces`
  ADD UNIQUE KEY `s_nonce` (`s_nonce`),
  ADD KEY `dt_datetime` (`dt_datetime`);

--
-- Indexes for table `tbl_nonces`
--
ALTER TABLE `tbl_nonces`
  ADD UNIQUE KEY `s_nonce` (`s_nonce`),
  ADD KEY `dt_datetime` (`dt_datetime`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
