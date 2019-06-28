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
-- Table structure for table `pr_details`
--

CREATE TABLE IF NOT EXISTS `pr_details` (
`pr_details_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `uom` varchar(100) DEFAULT NULL,
  `part_no` varchar(100) DEFAULT NULL,
  `item_description` text,
  `date_needed` varchar(20) DEFAULT NULL,
  `grouping_id` varchar(5) DEFAULT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pr_details`
--

INSERT INTO `pr_details` (`pr_details_id`, `pr_id`, `quantity`, `uom`, `part_no`, `item_description`, `date_needed`, `grouping_id`, `cancelled`, `cancelled_by`, `cancelled_reason`, `cancelled_date`) VALUES
(1, 1, 31, 'lgths', '123', 'Lumber, Coco, 2" x 3" x 12ft', '2019-06-30', 'A', 1, 1, '', '2019-06-28 11:46:23'),
(2, 1, 10, 'lgths', '1234', 'Lumber, Coco, 2" x 2" x 12ft', '2019-07-01', 'B', 1, 1, '', '2019-06-28 11:46:23'),
(3, 1, 15, 'shts', '12345', 'Plywood, Ordinary, 4ft x 8ft x 1/4"', '2019-07-02', 'C', 1, 1, '', '2019-06-28 11:46:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pr_details`
--
ALTER TABLE `pr_details`
 ADD PRIMARY KEY (`pr_details_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pr_details`
--
ALTER TABLE `pr_details`
MODIFY `pr_details_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
