-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2021 at 05:40 AM
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
-- Table structure for table `aoq_head`
--

CREATE TABLE IF NOT EXISTS `aoq_head` (
`aoq_id` int(11) NOT NULL,
  `aoq_date` varchar(20) DEFAULT NULL,
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `department` varchar(255) DEFAULT NULL,
  `purpose` text,
  `enduse` varchar(255) DEFAULT NULL,
  `requestor` varchar(255) DEFAULT NULL,
  `noted_by` varchar(255) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `reviewed_by` varchar(150) DEFAULT NULL,
  `saved` int(11) NOT NULL DEFAULT '0',
  `open` int(11) NOT NULL DEFAULT '0',
  `awarded` int(11) NOT NULL DEFAULT '0',
  `refer_mnl` int(11) NOT NULL DEFAULT '0',
  `refer_date` varchar(20) DEFAULT NULL,
  `served` int(11) NOT NULL DEFAULT '0',
  `date_served` varchar(20) DEFAULT NULL,
  `cancelled` int(11) DEFAULT '0',
  `cancelled_reason` text,
  `cancel_date` varchar(20) DEFAULT NULL,
  `cancelled_by` int(11) DEFAULT '0',
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `prepared_date` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `aoq_items`
--

CREATE TABLE IF NOT EXISTS `aoq_items` (
`aoq_items_id` int(11) NOT NULL,
  `aoq_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `item_description` varchar(255) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `aoq_offers`
--

CREATE TABLE IF NOT EXISTS `aoq_offers` (
`aoq_offer_id` int(11) NOT NULL,
  `aoq_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `comments` text,
  `recommended` int(11) NOT NULL DEFAULT '0',
  `currency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `aoq_vendors`
--

CREATE TABLE IF NOT EXISTS `aoq_vendors` (
`aoq_vendors_id` int(11) NOT NULL,
  `aoq_id` int(11) NOT NULL DEFAULT '0',
  `rfq_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `price_validity` text,
  `payment_terms` text,
  `delivery_date` varchar(100) DEFAULT NULL,
  `item_warranty` text,
  `freight` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE IF NOT EXISTS `brand` (
`brand_id` int(11) NOT NULL,
  `brand_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
`department_id` int(11) NOT NULL,
  `department_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`) VALUES
(1, 'IT'),
(2, 'HR'),
(3, 'Purchasing'),
(4, 'Finance'),
(5, 'Accounting'),
(6, 'Warehouse'),
(7, 'Operations'),
(8, 'Trading'),
(9, 'Maintenance');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
`employee_id` int(11) NOT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT '0',
  `position` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `employee_name`, `department_id`, `position`) VALUES
(1, 'Lester Madriaga', 0, 'IT Encoder'),
(2, 'Jerry B. Abel', 0, 'Plant Mechanic'),
(3, 'Myra A. Aceveda', 0, 'Purchaser'),
(4, 'Norwell V. Acuzar', 0, 'Fuel Tender'),
(5, 'John Rio M. Adarlo', 0, 'Engine Operator'),
(6, 'John M. Agoncillo', 0, 'Operations Superintendent'),
(8, 'Rogelio A. Arita', 0, ''),
(9, 'Jennilyn M. Bagon', 0, 'Senior Bookkeeper'),
(10, 'John Bryan Baja', 0, 'Auxiliary Operator'),
(11, 'Gerry M. Bantulo', 0, 'Purchasing and Logistics Asst.'),
(12, 'Dhen Mark Belmonte', 0, 'Ware houseman'),
(13, 'Claro L. Bolarde', 0, 'CPGC Head Supervisor'),
(14, 'Gerald M. Castillo', 0, 'Auxiliary Operator'),
(15, 'Eugenio G. Cruz, Jr.', 0, 'Warehouse Supervisor'),
(16, 'Ricardo Del Mundo', 0, 'Utility/Driver'),
(17, 'Marlon G. Gealon', 0, ''),
(18, 'Elso H. Gervero', 0, ''),
(19, 'Marlon G. Gunday', 0, 'Facility In-Charge Officer'),
(20, 'Gilbert A. Ligaya', 0, ''),
(21, 'Reynaldo B. Lita', 0, 'Shift Supervisor'),
(22, 'Ray Anthony P. Lopez', 0, 'Engine Operator'),
(23, 'Rogelito P. Luna', 0, 'Security Officer'),
(24, 'Argiliza O. Maneja', 0, 'Data Encoder'),
(25, 'Delian Mariel B. Marasigan', 0, 'Lab & Fuel Engineer'),
(26, 'Eulalio B. Mendoza, Jr.', 0, 'Engine Operator'),
(27, 'Mariela M. Merciales', 0, 'Records Custodian'),
(28, 'Melvin D. Mutiangpili', 0, 'Auxiliary Operator'),
(29, 'Joy F. Najito', 0, 'Admin Manager'),
(30, 'Manolo V. Najito', 0, 'Supervisor/Contract Manager'),
(31, 'Jhan Jhevic P. Noble', 0, ''),
(32, 'Jediton E. Palma', 0, 'Plant Mechanic'),
(33, 'Lyan Kimberly F. Palomera', 0, 'Finance Assistant'),
(34, 'Jojo L. Valdez', 0, 'Control Room Operator'),
(35, 'Michael C. Vidal', 0, 'Control Room Operator'),
(36, 'Rodell C. Zamora', 0, 'Control Room Operator'),
(37, 'Enrico Brian Ani', 0, 'Plant Manager'),
(38, 'Cristina C. Young', 0, 'President'),
(39, 'Jaylord Abas', 0, 'EIC Technician'),
(40, 'Arnel Napa', 0, 'Plant Mechanic'),
(41, 'Jan Jhevic Noble', 0, 'EIC Technician'),
(42, 'Cole Vender Rodriguez', 0, 'Auxiliary Operator'),
(43, 'Trish C. Young', 0, ''),
(44, 'Elaine Anne Y. dela Cruz', 0, 'Data Encoder'),
(45, 'Alberto Jun Limuran, Jr.', 0, 'Plant Manager'),
(46, 'Raphael F.Ponce', 0, 'Assistant.Technical Director'),
(47, 'Hennelen Tanan', 1, 'Systems Implementer');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
`item_id` int(11) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_specs` text,
  `brand_name` varchar(255) DEFAULT NULL,
  `part_no` varchar(100) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `offer_date` varchar(20) DEFAULT NULL,
  `pr_details_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `date_needed` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `revised_date` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `approve_rev_by` int(11) NOT NULL DEFAULT '0',
  `approve_rev_date` varchar(20) DEFAULT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `vat_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `date_needed` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `revised_date` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `approve_rev_by` int(11) NOT NULL DEFAULT '0',
  `approve_rev_date` varchar(20) DEFAULT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `vat_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `date_needed` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `revised_date` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `approve_rev_by` int(11) NOT NULL DEFAULT '0',
  `approve_rev_date` varchar(20) DEFAULT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `vat_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jo_rfd`
--

CREATE TABLE IF NOT EXISTS `jo_rfd` (
`rfd_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
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
  `notes` text,
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `endorsed_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jo_series`
--

CREATE TABLE IF NOT EXISTS `jo_series` (
`jo_series_id` int(11) NOT NULL,
  `year` int(11) NOT NULL DEFAULT '0',
  `series` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jo_terms`
--

CREATE TABLE IF NOT EXISTS `jo_terms` (
`jo_terms_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `terms` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `jo_terms_temp`
--

CREATE TABLE IF NOT EXISTS `jo_terms_temp` (
  `jo_terms_id` int(11) NOT NULL,
  `jo_id` int(11) NOT NULL DEFAULT '0',
  `terms` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_dr`
--

CREATE TABLE IF NOT EXISTS `po_dr` (
`dr_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `rfd_id` int(11) NOT NULL,
  `dr_no` varchar(100) DEFAULT NULL,
  `dr_date` varchar(20) DEFAULT NULL,
  `dr_type` int(11) NOT NULL DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2 = no rfd',
  `saved` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `received` int(11) NOT NULL DEFAULT '0',
  `date_received` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_dr_details`
--

CREATE TABLE IF NOT EXISTS `po_dr_details` (
`dr_details_id` int(11) NOT NULL,
  `dr_id` int(11) NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `purpose` text NOT NULL,
  `enduse` text NOT NULL,
  `requestor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_dr_items`
--

CREATE TABLE IF NOT EXISTS `po_dr_items` (
`dr_items_id` int(11) NOT NULL,
  `po_items_id` int(11) NOT NULL DEFAULT '0',
  `dr_id` int(11) NOT NULL DEFAULT '0',
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `po_id` int(11) NOT NULL DEFAULT '0',
  `aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `currency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_dr_items_revised`
--

CREATE TABLE IF NOT EXISTS `po_dr_items_revised` (
  `dr_items_id` int(11) NOT NULL,
  `po_items_id` int(11) NOT NULL DEFAULT '0',
  `dr_id` int(11) NOT NULL DEFAULT '0',
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `po_id` int(11) NOT NULL DEFAULT '0',
  `aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `currency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_dr_revised`
--

CREATE TABLE IF NOT EXISTS `po_dr_revised` (
  `dr_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `rfd_id` int(11) NOT NULL,
  `dr_no` varchar(100) DEFAULT NULL,
  `dr_date` varchar(20) DEFAULT NULL,
  `dr_type` int(11) NOT NULL DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2 = no rfd',
  `saved` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_head`
--

CREATE TABLE IF NOT EXISTS `po_head` (
`po_id` int(11) NOT NULL,
  `po_date` varchar(20) DEFAULT NULL,
  `po_no` varchar(50) DEFAULT NULL,
  `dr_no` varchar(50) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `po_type` int(11) DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2=repeat order',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `shipping` decimal(10,2) DEFAULT '0.00',
  `discount` decimal(10,2) DEFAULT '0.00',
  `approved_by` int(11) DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `done_po` int(11) NOT NULL DEFAULT '0',
  `cancelled` int(11) DEFAULT '0',
  `cancelled_by` int(11) DEFAULT '0',
  `cancel_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `date_revised` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `revise_attachment` varchar(255) DEFAULT NULL,
  `served` int(11) NOT NULL DEFAULT '0',
  `date_served` varchar(20) DEFAULT NULL,
  `served_by` int(11) NOT NULL DEFAULT '0',
  `repeat_order` int(11) NOT NULL DEFAULT '0',
  `approve_rev_by` varchar(100) DEFAULT NULL,
  `approve_rev_date` varchar(20) DEFAULT NULL,
  `draft` int(11) NOT NULL DEFAULT '0',
  `prepared_date` varchar(20) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `packing_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_percent` int(11) NOT NULL DEFAULT '0',
  `grouping_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_head_revised`
--

CREATE TABLE IF NOT EXISTS `po_head_revised` (
  `po_id` int(11) NOT NULL,
  `po_date` varchar(20) DEFAULT NULL,
  `po_no` varchar(50) DEFAULT NULL,
  `dr_no` varchar(50) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `po_type` int(11) DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2=repeat order',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `shipping` decimal(10,2) DEFAULT '0.00',
  `discount` decimal(10,2) DEFAULT '0.00',
  `approved_by` int(11) DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `done_po` int(11) NOT NULL DEFAULT '0',
  `cancelled` int(11) DEFAULT '0',
  `cancelled_by` int(11) DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `cancel_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `date_revised` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `revise_attachment` varchar(255) DEFAULT NULL,
  `packing_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_percent` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_head_temp`
--

CREATE TABLE IF NOT EXISTS `po_head_temp` (
  `po_id` int(11) NOT NULL,
  `po_date` varchar(20) DEFAULT NULL,
  `po_no` varchar(50) DEFAULT NULL,
  `dr_no` varchar(50) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `po_type` int(11) DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2=repeat order',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `draft` int(11) NOT NULL DEFAULT '0',
  `done_po` int(11) NOT NULL DEFAULT '0',
  `cancelled` int(11) DEFAULT '0',
  `cancelled_by` int(11) DEFAULT '0',
  `cancel_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `date_revised` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `revise_attachment` varchar(255) DEFAULT NULL,
  `served` int(11) NOT NULL DEFAULT '0',
  `date_served` varchar(20) DEFAULT NULL,
  `served_by` int(11) NOT NULL DEFAULT '0',
  `repeat_order` int(11) NOT NULL DEFAULT '0',
  `approve_rev_by` varchar(100) DEFAULT NULL,
  `approve_rev_date` varchar(20) DEFAULT NULL,
  `shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `packing_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_percent` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_items`
--

CREATE TABLE IF NOT EXISTS `po_items` (
`po_items_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `po_id` int(11) NOT NULL DEFAULT '0',
  `aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) DEFAULT '0',
  `source_poid` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `currency` varchar(10) DEFAULT NULL,
  `cancel` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_date` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_items_revised`
--

CREATE TABLE IF NOT EXISTS `po_items_revised` (
  `po_items_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `po_id` int(11) NOT NULL DEFAULT '0',
  `aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `currency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_items_temp`
--

CREATE TABLE IF NOT EXISTS `po_items_temp` (
`po_items_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `po_id` int(11) NOT NULL DEFAULT '0',
  `aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) DEFAULT '0',
  `source_poid` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `currency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_pr`
--

CREATE TABLE IF NOT EXISTS `po_pr` (
`po_pr_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL DEFAULT '0',
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `aoq_id` int(11) NOT NULL DEFAULT '0',
  `enduse` text,
  `purpose` text,
  `requestor` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_pr_revised`
--

CREATE TABLE IF NOT EXISTS `po_pr_revised` (
  `po_pr_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL DEFAULT '0',
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `aoq_id` int(11) NOT NULL DEFAULT '0',
  `enduse` text,
  `purpose` text,
  `requestor` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_series`
--

CREATE TABLE IF NOT EXISTS `po_series` (
`series_id` int(11) NOT NULL,
  `series` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_tc`
--

CREATE TABLE IF NOT EXISTS `po_tc` (
`po_tc_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL DEFAULT '0',
  `tc_desc` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_tc_revised`
--

CREATE TABLE IF NOT EXISTS `po_tc_revised` (
  `po_tc_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL DEFAULT '0',
  `tc_desc` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `po_tc_temp`
--

CREATE TABLE IF NOT EXISTS `po_tc_temp` (
  `po_tc_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL DEFAULT '0',
  `tc_desc` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pr_details`
--

CREATE TABLE IF NOT EXISTS `pr_details` (
`pr_details_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(100) DEFAULT NULL,
  `part_no` varchar(100) DEFAULT NULL,
  `item_description` text,
  `date_needed` varchar(20) DEFAULT NULL,
  `grouping_id` varchar(5) DEFAULT NULL,
  `wh_stocks` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text NOT NULL,
  `cancelled_date` varchar(20) NOT NULL,
  `add_remarks` text,
  `remark_date` varchar(20) DEFAULT NULL,
  `remark_by` int(11) NOT NULL DEFAULT '0',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `cancel_remarks` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `processing_code` varchar(20) DEFAULT NULL,
  `date_imported` varchar(20) DEFAULT NULL,
  `imported_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text,
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_date` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pr_series`
--

CREATE TABLE IF NOT EXISTS `pr_series` (
`pr_series_id` int(11) NOT NULL,
  `series_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pr_vendors`
--

CREATE TABLE IF NOT EXISTS `pr_vendors` (
`pr_vendors_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `grouping_id` varchar(5) DEFAULT NULL,
  `due_date` varchar(20) DEFAULT NULL,
  `noted_by` int(11) NOT NULL,
  `approved_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE IF NOT EXISTS `reminder` (
`reminder_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `create_date` varchar(20) DEFAULT NULL,
  `due_date` varchar(20) DEFAULT NULL,
  `done` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rfd`
--

CREATE TABLE IF NOT EXISTS `rfd` (
`rfd_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL DEFAULT '0',
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
  `noted_by` int(11) DEFAULT '0',
  `received_by` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rfq_details`
--

CREATE TABLE IF NOT EXISTS `rfq_details` (
`rfq_details_id` int(11) NOT NULL,
  `rfq_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `pn_no` varchar(100) DEFAULT NULL,
  `item_desc` text,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `offer` text,
  `recommended` text,
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rfq_head`
--

CREATE TABLE IF NOT EXISTS `rfq_head` (
`rfq_id` int(11) NOT NULL,
  `rfq_no` varchar(50) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `grouping_id` varchar(5) DEFAULT NULL,
  `rfq_date` varchar(20) DEFAULT NULL,
  `quotation_date` varchar(20) DEFAULT NULL,
  `price_validity` varchar(100) DEFAULT NULL,
  `payment_terms` varchar(100) DEFAULT NULL,
  `delivery_date` varchar(20) DEFAULT NULL,
  `item_warranty` varchar(100) DEFAULT NULL,
  `tin` varchar(50) DEFAULT NULL,
  `vat` varchar(50) DEFAULT NULL,
  `notes` text,
  `processing_code` varchar(5) DEFAULT NULL,
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `noted_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `create_date` varchar(20) DEFAULT NULL,
  `saved` int(11) NOT NULL DEFAULT '0',
  `completed` int(11) NOT NULL DEFAULT '0',
  `served` int(11) NOT NULL DEFAULT '0',
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancel_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `pn_no` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rfq_series`
--

CREATE TABLE IF NOT EXISTS `rfq_series` (
`rfq_series_id` int(11) NOT NULL,
  `year_month` varchar(20) DEFAULT NULL,
  `series` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `to_do_today`
--

CREATE TABLE IF NOT EXISTS `to_do_today` (
`todo_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `create_date` varchar(20) DEFAULT NULL,
  `due_date` varchar(20) DEFAULT NULL,
  `done` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
`unit_id` int(11) NOT NULL,
  `unit_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`user_id` int(11) NOT NULL,
  `usertype_id` int(11) unsigned NOT NULL DEFAULT '0',
  `fullname` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `usertype_id`, `fullname`, `username`, `password`) VALUES
(1, 1, 'Hennelen Tanan', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `usertype`
--

CREATE TABLE IF NOT EXISTS `usertype` (
`usertype_id` int(11) NOT NULL,
  `usertype_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usertype`
--

INSERT INTO `usertype` (`usertype_id`, `usertype_name`) VALUES
(1, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_details`
--

CREATE TABLE IF NOT EXISTS `vendor_details` (
`vendordet_id` int(11) NOT NULL,
  `vendor_id` int(11) unsigned NOT NULL DEFAULT '0',
  `item_id` int(11) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendor_details`
--

INSERT INTO `vendor_details` (`vendordet_id`, `vendor_id`, `item_id`) VALUES
(1, 1505, 1),
(2, 1505, 2),
(3, 365, 3),
(4, 1, 4),
(5, 1, 5),
(6, 2, 6),
(7, 283, 7),
(8, 1788, 8),
(9, 1789, 9),
(10, 45, 10),
(11, 1788, 11),
(12, 1788, 12),
(13, 1789, 13),
(14, 1788, 14),
(15, 1789, 15),
(16, 45, 16);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_head`
--

CREATE TABLE IF NOT EXISTS `vendor_head` (
`vendor_id` int(11) NOT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `product_services` text,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `fax_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `terms` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `notes` text,
  `status` varchar(100) DEFAULT NULL,
  `ewt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` int(11) NOT NULL DEFAULT '0',
  `tin` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aoq_head`
--
ALTER TABLE `aoq_head`
 ADD PRIMARY KEY (`aoq_id`);

--
-- Indexes for table `aoq_items`
--
ALTER TABLE `aoq_items`
 ADD PRIMARY KEY (`aoq_items_id`);

--
-- Indexes for table `aoq_offers`
--
ALTER TABLE `aoq_offers`
 ADD PRIMARY KEY (`aoq_offer_id`);

--
-- Indexes for table `aoq_vendors`
--
ALTER TABLE `aoq_vendors`
 ADD PRIMARY KEY (`aoq_vendors_id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
 ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
 ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
 ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
 ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `jo_ar`
--
ALTER TABLE `jo_ar`
 ADD PRIMARY KEY (`joar_id`);

--
-- Indexes for table `jo_details`
--
ALTER TABLE `jo_details`
 ADD PRIMARY KEY (`jo_details_id`);

--
-- Indexes for table `jo_dr`
--
ALTER TABLE `jo_dr`
 ADD PRIMARY KEY (`jodr_id`);

--
-- Indexes for table `jo_head`
--
ALTER TABLE `jo_head`
 ADD PRIMARY KEY (`jo_id`);

--
-- Indexes for table `jo_rfd`
--
ALTER TABLE `jo_rfd`
 ADD PRIMARY KEY (`rfd_id`);

--
-- Indexes for table `jo_series`
--
ALTER TABLE `jo_series`
 ADD PRIMARY KEY (`jo_series_id`);

--
-- Indexes for table `jo_terms`
--
ALTER TABLE `jo_terms`
 ADD PRIMARY KEY (`jo_terms_id`);

--
-- Indexes for table `po_dr`
--
ALTER TABLE `po_dr`
 ADD PRIMARY KEY (`dr_id`);

--
-- Indexes for table `po_dr_details`
--
ALTER TABLE `po_dr_details`
 ADD PRIMARY KEY (`dr_details_id`);

--
-- Indexes for table `po_dr_items`
--
ALTER TABLE `po_dr_items`
 ADD PRIMARY KEY (`dr_items_id`);

--
-- Indexes for table `po_head`
--
ALTER TABLE `po_head`
 ADD PRIMARY KEY (`po_id`);

--
-- Indexes for table `po_items`
--
ALTER TABLE `po_items`
 ADD PRIMARY KEY (`po_items_id`);

--
-- Indexes for table `po_items_temp`
--
ALTER TABLE `po_items_temp`
 ADD PRIMARY KEY (`po_items_id`);

--
-- Indexes for table `po_pr`
--
ALTER TABLE `po_pr`
 ADD PRIMARY KEY (`po_pr_id`);

--
-- Indexes for table `po_series`
--
ALTER TABLE `po_series`
 ADD PRIMARY KEY (`series_id`);

--
-- Indexes for table `po_tc`
--
ALTER TABLE `po_tc`
 ADD PRIMARY KEY (`po_tc_id`);

--
-- Indexes for table `pr_details`
--
ALTER TABLE `pr_details`
 ADD PRIMARY KEY (`pr_details_id`);

--
-- Indexes for table `pr_head`
--
ALTER TABLE `pr_head`
 ADD PRIMARY KEY (`pr_id`);

--
-- Indexes for table `pr_series`
--
ALTER TABLE `pr_series`
 ADD PRIMARY KEY (`pr_series_id`);

--
-- Indexes for table `pr_vendors`
--
ALTER TABLE `pr_vendors`
 ADD PRIMARY KEY (`pr_vendors_id`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
 ADD PRIMARY KEY (`reminder_id`);

--
-- Indexes for table `rfd`
--
ALTER TABLE `rfd`
 ADD PRIMARY KEY (`rfd_id`);

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
-- Indexes for table `rfq_details`
--
ALTER TABLE `rfq_details`
 ADD PRIMARY KEY (`rfq_details_id`);

--
-- Indexes for table `rfq_head`
--
ALTER TABLE `rfq_head`
 ADD PRIMARY KEY (`rfq_id`);

--
-- Indexes for table `rfq_series`
--
ALTER TABLE `rfq_series`
 ADD PRIMARY KEY (`rfq_series_id`);

--
-- Indexes for table `to_do_today`
--
ALTER TABLE `to_do_today`
 ADD PRIMARY KEY (`todo_id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
 ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `usertype`
--
ALTER TABLE `usertype`
 ADD PRIMARY KEY (`usertype_id`);

--
-- Indexes for table `vendor_details`
--
ALTER TABLE `vendor_details`
 ADD PRIMARY KEY (`vendordet_id`);

--
-- Indexes for table `vendor_head`
--
ALTER TABLE `vendor_head`
 ADD PRIMARY KEY (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aoq_head`
--
ALTER TABLE `aoq_head`
MODIFY `aoq_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aoq_items`
--
ALTER TABLE `aoq_items`
MODIFY `aoq_items_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aoq_offers`
--
ALTER TABLE `aoq_offers`
MODIFY `aoq_offer_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `aoq_vendors`
--
ALTER TABLE `aoq_vendors`
MODIFY `aoq_vendors_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_ar`
--
ALTER TABLE `jo_ar`
MODIFY `joar_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_details`
--
ALTER TABLE `jo_details`
MODIFY `jo_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_dr`
--
ALTER TABLE `jo_dr`
MODIFY `jodr_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_head`
--
ALTER TABLE `jo_head`
MODIFY `jo_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_rfd`
--
ALTER TABLE `jo_rfd`
MODIFY `rfd_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_series`
--
ALTER TABLE `jo_series`
MODIFY `jo_series_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_terms`
--
ALTER TABLE `jo_terms`
MODIFY `jo_terms_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_dr`
--
ALTER TABLE `po_dr`
MODIFY `dr_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_dr_details`
--
ALTER TABLE `po_dr_details`
MODIFY `dr_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_dr_items`
--
ALTER TABLE `po_dr_items`
MODIFY `dr_items_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_head`
--
ALTER TABLE `po_head`
MODIFY `po_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_items`
--
ALTER TABLE `po_items`
MODIFY `po_items_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_items_temp`
--
ALTER TABLE `po_items_temp`
MODIFY `po_items_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_pr`
--
ALTER TABLE `po_pr`
MODIFY `po_pr_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_series`
--
ALTER TABLE `po_series`
MODIFY `series_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `po_tc`
--
ALTER TABLE `po_tc`
MODIFY `po_tc_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pr_details`
--
ALTER TABLE `pr_details`
MODIFY `pr_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pr_head`
--
ALTER TABLE `pr_head`
MODIFY `pr_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pr_series`
--
ALTER TABLE `pr_series`
MODIFY `pr_series_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pr_vendors`
--
ALTER TABLE `pr_vendors`
MODIFY `pr_vendors_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rfd`
--
ALTER TABLE `rfd`
MODIFY `rfd_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rfd_items`
--
ALTER TABLE `rfd_items`
MODIFY `rfd_items_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rfd_purpose`
--
ALTER TABLE `rfd_purpose`
MODIFY `rfd_purpose_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rfq_details`
--
ALTER TABLE `rfq_details`
MODIFY `rfq_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rfq_head`
--
ALTER TABLE `rfq_head`
MODIFY `rfq_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rfq_series`
--
ALTER TABLE `rfq_series`
MODIFY `rfq_series_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `to_do_today`
--
ALTER TABLE `to_do_today`
MODIFY `todo_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `usertype`
--
ALTER TABLE `usertype`
MODIFY `usertype_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vendor_details`
--
ALTER TABLE `vendor_details`
MODIFY `vendordet_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `vendor_head`
--
ALTER TABLE `vendor_head`
MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
