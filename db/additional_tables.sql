-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2021 at 09:14 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `check_purchasing`
--

-- --------------------------------------------------------

--
-- Table structure for table `jor_head`
--

CREATE TABLE IF NOT EXISTS `jor_head` (
`jor_id` int(11) NOT NULL,
  `jo_no` varchar(50) DEFAULT NULL,
  `user_jo_no` varchar(50) DEFAULT NULL,
  `jo_request` varchar(100) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `completion_date` varchar(20) DEFAULT NULL,
  `delivery_date` varchar(20) DEFAULT NULL,
  `purpose` text,
  `urgency` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `date_imported` varchar(20) DEFAULT NULL,
  `imported_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jor_items`
--

CREATE TABLE IF NOT EXISTS `jor_items` (
`jor_items_id` int(11) NOT NULL,
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `scope_of_work` text,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `uom` varchar(10) DEFAULT NULL,
  `unit_cost` decimal(10,0) NOT NULL DEFAULT '10',
  `total_cost` decimal(10,0) NOT NULL DEFAULT '10',
  `grouping_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jor_notes`
--

CREATE TABLE IF NOT EXISTS `jor_notes` (
`jor_notes_id` int(11) NOT NULL,
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jor_rfq_series`
--

CREATE TABLE IF NOT EXISTS `jor_rfq_series` (
`jrfq_id` int(11) NOT NULL,
  `year_month` varchar(50) DEFAULT NULL,
  `series` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jor_series`
--

CREATE TABLE IF NOT EXISTS `jor_series` (
`jors_id` int(11) NOT NULL,
  `series` int(11) NOT NULL DEFAULT '0',
  `year` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jor_vendors`
--

CREATE TABLE IF NOT EXISTS `jor_vendors` (
`jor_vendor_id` int(11) NOT NULL,
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `grouping_id` varchar(20) DEFAULT NULL,
  `noted_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jo_rfq_details`
--

CREATE TABLE IF NOT EXISTS `jo_rfq_details` (
`jo_rfq_details_id` int(11) NOT NULL,
  `jo_rfq_id` int(11) NOT NULL DEFAULT '0',
  `jor_items_id` int(11) NOT NULL DEFAULT '0',
  `scope_of_work` text,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `uom` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jo_rfq_head`
--

CREATE TABLE IF NOT EXISTS `jo_rfq_head` (
`jo_rfq_id` int(11) NOT NULL,
  `jo_rfq_no` varchar(50) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT '0',
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `rfq_date` varchar(20) DEFAULT NULL,
  `create_date` varchar(20) DEFAULT NULL,
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `noted_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `completed` int(11) NOT NULL DEFAULT '0',
  `completed_date` varchar(20) DEFAULT NULL,
  `grouping_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jor_head`
--
ALTER TABLE `jor_head`
 ADD PRIMARY KEY (`jor_id`);

--
-- Indexes for table `jor_items`
--
ALTER TABLE `jor_items`
 ADD PRIMARY KEY (`jor_items_id`);

--
-- Indexes for table `jor_notes`
--
ALTER TABLE `jor_notes`
 ADD PRIMARY KEY (`jor_notes_id`);

--
-- Indexes for table `jor_rfq_series`
--
ALTER TABLE `jor_rfq_series`
 ADD PRIMARY KEY (`jrfq_id`);

--
-- Indexes for table `jor_series`
--
ALTER TABLE `jor_series`
 ADD PRIMARY KEY (`jors_id`);

--
-- Indexes for table `jor_vendors`
--
ALTER TABLE `jor_vendors`
 ADD PRIMARY KEY (`jor_vendor_id`);

--
-- Indexes for table `jo_rfq_details`
--
ALTER TABLE `jo_rfq_details`
 ADD PRIMARY KEY (`jo_rfq_details_id`);

--
-- Indexes for table `jo_rfq_head`
--
ALTER TABLE `jo_rfq_head`
 ADD PRIMARY KEY (`jo_rfq_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jor_head`
--
ALTER TABLE `jor_head`
MODIFY `jor_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jor_items`
--
ALTER TABLE `jor_items`
MODIFY `jor_items_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jor_notes`
--
ALTER TABLE `jor_notes`
MODIFY `jor_notes_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jor_rfq_series`
--
ALTER TABLE `jor_rfq_series`
MODIFY `jrfq_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jor_series`
--
ALTER TABLE `jor_series`
MODIFY `jors_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jor_vendors`
--
ALTER TABLE `jor_vendors`
MODIFY `jor_vendor_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_rfq_details`
--
ALTER TABLE `jo_rfq_details`
MODIFY `jo_rfq_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_rfq_head`
--
ALTER TABLE `jo_rfq_head`
MODIFY `jo_rfq_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
