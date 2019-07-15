-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2019 at 04:45 AM
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
-- Table structure for table `rfd_items`
--

CREATE TABLE IF NOT EXISTS `rfd_items` (
`rfd_items_id` int(11) NOT NULL,
  `rfd_id` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rfd_items`
--

INSERT INTO `rfd_items` (`rfd_items_id`, `rfd_id`, `item_id`, `quantity`, `unit_price`) VALUES
(1, 1, 15, '10.00', '120.00'),
(2, 2, 319, '1.00', '123.00'),
(3, 2, 320, '2.00', '333.00'),
(4, 2, 321, '3.00', '111.00');

-- --------------------------------------------------------

--
-- Table structure for table `rfd_purpose`
--

CREATE TABLE IF NOT EXISTS `rfd_purpose` (
`rfd_purpose_id` int(11) NOT NULL,
  `rfd_id` int(11) NOT NULL DEFAULT '0',
  `purpose` text,
  `requestor` int(11) NOT NULL DEFAULT '0',
  `enduse` text,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rfd_purpose`
--

INSERT INTO `rfd_purpose` (`rfd_purpose_id`, `rfd_id`, `purpose`, `requestor`, `enduse`, `notes`) VALUES
(1, 1, 'Test Purpose1', 112, 'test enduse1', 'Test notes'),
(2, 2, 'Test1', 112, 'Test1', 'dasas'),
(3, 2, 'Test2', 120, 'Test2', 'Test notes');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
`unit_id` int(11) NOT NULL,
  `unit_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`unit_id`, `unit_name`) VALUES
(3, 'pc/s');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rfd_items`
--
ALTER TABLE `rfd_items`
 ADD PRIMARY KEY (`rfd_items_id`);

--
-- Indexes for table `rfd_purpose`
--
ALTER TABLE `rfd_purpose`
 ADD PRIMARY KEY (`rfd_purpose_id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
 ADD PRIMARY KEY (`unit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rfd_items`
--
ALTER TABLE `rfd_items`
MODIFY `rfd_items_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `rfd_purpose`
--
ALTER TABLE `rfd_purpose`
MODIFY `rfd_purpose_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
