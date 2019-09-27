-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2019 at 04:14 AM
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
-- Table structure for table `jo_details`
--

CREATE TABLE IF NOT EXISTS `jo_details` (
`jo_details_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `scope_of_work` text,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `uom` varchar(50) DEFAULT NULL,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jo_details`
--

INSERT INTO `jo_details` (`jo_details_id`, `jo_id`, `scope_of_work`, `quantity`, `uom`, `unit_cost`, `total_cost`, `revision_no`) VALUES
(1, 1, '3', 6, 'pc/s', '2000.00', '12000.00', 4);

-- --------------------------------------------------------

--
-- Table structure for table `jo_details_revised`
--

CREATE TABLE IF NOT EXISTS `jo_details_revised` (
  `jo_details_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `scope_of_work` text,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `uom` varchar(50) DEFAULT NULL,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jo_details_revised`
--

INSERT INTO `jo_details_revised` (`jo_details_id`, `jo_id`, `scope_of_work`, `quantity`, `uom`, `unit_cost`, `total_cost`, `revision_no`) VALUES
(1, 1, 'TESTIN SCOPE', 5, 'pc/s', '2000.00', '10000.00', 0),
(1, 1, '1', 6, 'pc/s', '2000.00', '12000.00', 1),
(1, 1, '3', 6, 'pc/s', '2000.00', '12000.00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `jo_details_temp`
--

CREATE TABLE IF NOT EXISTS `jo_details_temp` (
`jo_details_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `scope_of_work` text,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `uom` varchar(50) DEFAULT NULL,
  `unit_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jo_head`
--

CREATE TABLE IF NOT EXISTS `jo_head` (
`jo_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `cenpri_jo_no` varchar(100) DEFAULT NULL,
  `jo_no` varchar(100) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `date_needed` varchar(20) DEFAULT NULL,
  `project_title` text,
  `start_of_work` varchar(20) DEFAULT NULL,
  `work_completion` varchar(20) DEFAULT NULL,
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `conforme` varchar(100) DEFAULT NULL,
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `revised` int(11) NOT NULL DEFAULT '0',
  `revised_date` varchar(20) NOT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `approve_rev_by` varchar(20) DEFAULT NULL,
  `approve_rev_date` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jo_head`
--

INSERT INTO `jo_head` (`jo_id`, `vendor_id`, `cenpri_jo_no`, `jo_no`, `date_prepared`, `date_needed`, `project_title`, `start_of_work`, `work_completion`, `total_cost`, `discount_percent`, `discount_amount`, `grand_total`, `conforme`, `prepared_by`, `approved_by`, `checked_by`, `saved`, `revised`, `revised_date`, `revision_no`, `approve_rev_by`, `approve_rev_date`) VALUES
(1, 365, 'CENPRI 2019-09-27', 'JO 2019-01', '2019-09-29', '2019-09-27', 'TEST', '2019-09-28', '2019-09-30', '10000.00', '0.00', '0.00', '10000.00', 'TEST CONFORME1', 1, 20, 24, 1, 0, '2019-09-27', 4, 'asd', '2019-09-27');

-- --------------------------------------------------------

--
-- Table structure for table `jo_head_revised`
--

CREATE TABLE IF NOT EXISTS `jo_head_revised` (
  `jo_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `cenpri_jo_no` varchar(100) DEFAULT NULL,
  `jo_no` varchar(100) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `date_needed` varchar(20) DEFAULT NULL,
  `project_title` text,
  `start_of_work` varchar(20) DEFAULT NULL,
  `work_completion` varchar(20) DEFAULT NULL,
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `conforme` varchar(100) DEFAULT NULL,
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `revised` int(11) NOT NULL DEFAULT '0',
  `revised_date` varchar(20) NOT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jo_head_revised`
--

INSERT INTO `jo_head_revised` (`jo_id`, `vendor_id`, `cenpri_jo_no`, `jo_no`, `date_prepared`, `date_needed`, `project_title`, `start_of_work`, `work_completion`, `total_cost`, `discount_percent`, `discount_amount`, `grand_total`, `conforme`, `prepared_by`, `approved_by`, `checked_by`, `saved`, `revised`, `revised_date`, `revision_no`) VALUES
(1, 1, '', 'JO 2019-01', '2019-09-29', '2019-09-27', 'TEST', '2019-09-28', '2019-09-30', '10000.00', '0.00', '0.00', '10000.00', 'TEST CONFORME', 1, 24, 41, 1, 1, '', 0),
(1, 1, 'CENPRI 2019-09-27', 'JO 2019-01', '2019-09-29', '2019-09-27', 'TEST', '2019-09-28', '2019-09-30', '10000.00', '0.00', '0.00', '10000.00', 'TEST CONFORME1', 1, 20, 24, 1, 1, '2019-09-27', 1),
(1, 365, 'CENPRI 2019-09-27', 'JO 2019-01', '2019-09-29', '2019-09-27', 'TEST', '2019-09-28', '2019-09-30', '10000.00', '0.00', '0.00', '10000.00', 'TEST CONFORME1', 1, 20, 24, 1, 1, '2019-09-27', 2),
(1, 365, 'CENPRI 2019-09-27', 'JO 2019-01', '2019-09-29', '2019-09-27', 'TEST', '2019-09-28', '2019-09-30', '10000.00', '0.00', '0.00', '10000.00', 'TEST CONFORME1', 1, 20, 24, 1, 1, '2019-09-27', 3);

-- --------------------------------------------------------

--
-- Table structure for table `jo_head_temp`
--

CREATE TABLE IF NOT EXISTS `jo_head_temp` (
`jo_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `cenpri_jo_no` varchar(100) DEFAULT NULL,
  `jo_no` varchar(100) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `date_needed` varchar(20) DEFAULT NULL,
  `project_title` text,
  `start_of_work` varchar(20) DEFAULT NULL,
  `work_completion` varchar(20) DEFAULT NULL,
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `conforme` varchar(100) DEFAULT NULL,
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `revised` int(11) NOT NULL DEFAULT '0',
  `revised_date` varchar(20) NOT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jo_terms`
--

CREATE TABLE IF NOT EXISTS `jo_terms` (
`jo_terms_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `terms` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jo_terms`
--

INSERT INTO `jo_terms` (`jo_terms_id`, `jo_id`, `terms`, `revision_no`) VALUES
(1, 1, 'Test Terms Lang', 4);

-- --------------------------------------------------------

--
-- Table structure for table `jo_terms_revised`
--

CREATE TABLE IF NOT EXISTS `jo_terms_revised` (
  `jo_terms_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `terms` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jo_terms_revised`
--

INSERT INTO `jo_terms_revised` (`jo_terms_id`, `jo_id`, `terms`, `revision_no`) VALUES
(1, 1, 'Test Terms', 0),
(1, 1, 'Test Terms Lang', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jo_terms_temp`
--

CREATE TABLE IF NOT EXISTS `jo_terms_temp` (
`jo_terms_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `terms` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jo_details`
--
ALTER TABLE `jo_details`
 ADD PRIMARY KEY (`jo_details_id`);

--
-- Indexes for table `jo_details_temp`
--
ALTER TABLE `jo_details_temp`
 ADD PRIMARY KEY (`jo_details_id`);

--
-- Indexes for table `jo_head`
--
ALTER TABLE `jo_head`
 ADD PRIMARY KEY (`jo_id`);

--
-- Indexes for table `jo_head_temp`
--
ALTER TABLE `jo_head_temp`
 ADD PRIMARY KEY (`jo_id`);

--
-- Indexes for table `jo_terms`
--
ALTER TABLE `jo_terms`
 ADD PRIMARY KEY (`jo_terms_id`);

--
-- Indexes for table `jo_terms_temp`
--
ALTER TABLE `jo_terms_temp`
 ADD PRIMARY KEY (`jo_terms_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jo_details`
--
ALTER TABLE `jo_details`
MODIFY `jo_details_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jo_details_temp`
--
ALTER TABLE `jo_details_temp`
MODIFY `jo_details_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jo_head`
--
ALTER TABLE `jo_head`
MODIFY `jo_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jo_head_temp`
--
ALTER TABLE `jo_head_temp`
MODIFY `jo_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jo_terms`
--
ALTER TABLE `jo_terms`
MODIFY `jo_terms_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `jo_terms_temp`
--
ALTER TABLE `jo_terms_temp`
MODIFY `jo_terms_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
