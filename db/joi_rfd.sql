-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2021 at 07:14 AM
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
-- Table structure for table `joi_rfd`
--

CREATE TABLE IF NOT EXISTS `joi_rfd` (
`joi_rfd_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `apv_no` varchar(50) DEFAULT NULL,
  `rfd_date` varchar(20) DEFAULT NULL,
  `due_date` varchar(20) DEFAULT NULL,
  `check_due` varchar(20) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `pay_to` int(11) NOT NULL DEFAULT '0',
  `check_name` varchar(255) DEFAULT NULL,
  `cash_check` int(11) NOT NULL DEFAULT '0' COMMENT '1=cash, 2= check',
  `bank_no` varchar(100) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rfd_type` int(11) NOT NULL DEFAULT '0' COMMENT '0=po, 1=direct purchase',
  `notes` text,
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `endorsed_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `noted_by` int(11) NOT NULL DEFAULT '0',
  `received_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=979 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `joi_rfd`
--
ALTER TABLE `joi_rfd`
 ADD PRIMARY KEY (`joi_rfd_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `joi_rfd`
--
ALTER TABLE `joi_rfd`
MODIFY `joi_rfd_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=979;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
