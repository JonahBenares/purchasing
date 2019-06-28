-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2019 at 07:33 AM
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
-- Table structure for table `pr_head`
--

CREATE TABLE IF NOT EXISTS `pr_head` (
`pr_id` int(11) NOT NULL,
  `user_pr_no` varchar(50) DEFAULT NULL,
  `pr_no` varchar(50) DEFAULT NULL,
  `purchase_request` varchar(150) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `requestor` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `enduse` varchar(255) DEFAULT NULL,
  `purpose` text,
  `urgency` int(11) NOT NULL DEFAULT '0',
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `date_imported` varchar(20) DEFAULT NULL,
  `imported_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pr_head`
--

INSERT INTO `pr_head` (`pr_id`, `user_pr_no`, `pr_no`, `purchase_request`, `date_prepared`, `requestor`, `department`, `enduse`, `purpose`, `urgency`, `cancelled`, `cancelled_by`, `cancelled_reason`, `cancelled_date`, `date_imported`, `imported_by`) VALUES
(1, 'PR1234', 'PR-2019-06-28', 'Test PR', '2019-06-28', 'Stephine David Severino', 'IT', 'Test Enduse', 'Test Purpose', 1, 1, 1, '', '2019-06-28 11:46:23', '2019-06-28 09:52:33', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pr_head`
--
ALTER TABLE `pr_head`
 ADD PRIMARY KEY (`pr_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pr_head`
--
ALTER TABLE `pr_head`
MODIFY `pr_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
