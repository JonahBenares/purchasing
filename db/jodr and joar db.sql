-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2019 at 10:21 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_purchasing`
--

-- --------------------------------------------------------

--
-- Table structure for table `jo_ar`
--

CREATE TABLE IF NOT EXISTS `jo_ar` (
`joar_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `year` varchar(20) DEFAULT NULL,
  `series` varchar(20) DEFAULT NULL,
  `ar_date` varchar(20) DEFAULT NULL,
  `delivered_to` text,
  `address` text,
  `requested_by` varchar(50) DEFAULT NULL,
  `gatepass_no` varchar(50) DEFAULT NULL,
  `saved` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jo_ar`
--

INSERT INTO `jo_ar` (`joar_id`, `jo_id`, `year`, `series`, `ar_date`, `delivered_to`, `address`, `requested_by`, `gatepass_no`, `saved`) VALUES
(1, 1, 'AR 2019', '01', '2019-08-22', 'jason, stephine, henne', 'CENPRI BACOLOD', 'HENNE', '12345561267', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jo_dr`
--

CREATE TABLE IF NOT EXISTS `jo_dr` (
`jodr_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `year` varchar(50) NOT NULL,
  `series` varchar(20) DEFAULT NULL,
  `dr_date` varchar(20) DEFAULT NULL,
  `delivered_to` text,
  `address` text,
  `requested_by` varchar(50) DEFAULT NULL,
  `saved` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jo_dr`
--

INSERT INTO `jo_dr` (`jodr_id`, `jo_id`, `year`, `series`, `dr_date`, `delivered_to`, `address`, `requested_by`, `saved`) VALUES
(1, 1, 'DR 2019', '01', '2019-08-22', 'stephine, jason, jonah', 'Cenpri Site', 'Stephine David', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jo_ar`
--
ALTER TABLE `jo_ar`
 ADD PRIMARY KEY (`joar_id`);

--
-- Indexes for table `jo_dr`
--
ALTER TABLE `jo_dr`
 ADD PRIMARY KEY (`jodr_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jo_ar`
--
ALTER TABLE `jo_ar`
MODIFY `joar_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jo_dr`
--
ALTER TABLE `jo_dr`
MODIFY `jodr_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
