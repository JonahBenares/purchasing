-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2021 at 09:50 AM
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
-- Table structure for table `joi_ar`
--

CREATE TABLE IF NOT EXISTS `joi_ar` (
`joi_ar_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL,
  `year` varchar(20) DEFAULT NULL,
  `series` varchar(20) DEFAULT NULL,
  `ar_date` varchar(20) DEFAULT NULL,
  `delivered_to` text,
  `address` text,
  `requested_by` varchar(50) DEFAULT NULL,
  `gatepass_no` varchar(50) NOT NULL,
  `saved` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_coc`
--

CREATE TABLE IF NOT EXISTS `joi_coc` (
`coc_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `year` varchar(20) DEFAULT NULL,
  `series` varchar(20) DEFAULT NULL,
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `warranty` text,
  `saved` int(11) NOT NULL DEFAULT '0',
  `date_prepared` varchar(50) DEFAULT NULL,
  `date_created` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_dr`
--

CREATE TABLE IF NOT EXISTS `joi_dr` (
`joi_dr_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL,
  `joi_rfd_id` int(11) NOT NULL,
  `joi_dr_no` varchar(100) DEFAULT NULL,
  `joi_dr_date` varchar(20) DEFAULT NULL,
  `joi_dr_type` int(11) NOT NULL DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2 = no rfd',
  `saved` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `received` int(11) NOT NULL DEFAULT '0',
  `date_received` varchar(20) DEFAULT NULL,
  `requested_by` varchar(50) NOT NULL,
  `delivered_to` text NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_dr_details`
--

CREATE TABLE IF NOT EXISTS `joi_dr_details` (
`joi_dr_details_id` int(11) NOT NULL,
  `joi_dr_id` int(11) NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `purpose` text NOT NULL,
  `enduse` text NOT NULL,
  `requestor` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_dr_items`
--

CREATE TABLE IF NOT EXISTS `joi_dr_items` (
`joi_dr_items_id` int(11) NOT NULL,
  `joi_items_id` int(11) NOT NULL DEFAULT '0',
  `joi_dr_id` int(11) NOT NULL DEFAULT '0',
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `joi_aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `joi_aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `jor_items_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `currency` varchar(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `materials_offer` text,
  `materials_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_unitprice` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_currency` varchar(100) DEFAULT NULL,
  `materials_unit` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_dr_items_revised`
--

CREATE TABLE IF NOT EXISTS `joi_dr_items_revised` (
  `joi_items_id` int(11) NOT NULL DEFAULT '0',
  `joi_dr_id` int(11) NOT NULL DEFAULT '0',
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `joi_aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `joi_aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `jor_items_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `currency` varchar(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `joi_dr_items_id` int(11) NOT NULL,
  `materials_offer` text,
  `materials_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_unitprice` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_currency` varchar(100) DEFAULT NULL,
  `materials_unit` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_dr_revised`
--

CREATE TABLE IF NOT EXISTS `joi_dr_revised` (
  `joi_dr_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL,
  `joi_rfd_id` int(11) NOT NULL,
  `joi_dr_no` varchar(100) DEFAULT NULL,
  `joi_dr_date` varchar(20) DEFAULT NULL,
  `joi_dr_type` int(11) NOT NULL DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2 = no rfd',
  `saved` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_head`
--

CREATE TABLE IF NOT EXISTS `joi_head` (
`joi_id` int(11) NOT NULL,
  `joi_date` varchar(20) DEFAULT NULL,
  `joi_no` varchar(50) DEFAULT NULL,
  `dr_no` varchar(50) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `joi_type` int(11) NOT NULL DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2=repeat order',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `draft` int(11) NOT NULL DEFAULT '0',
  `done_joi` int(11) NOT NULL DEFAULT '0',
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
  `prepared_date` varchar(20) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `packing_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_percent` int(11) NOT NULL DEFAULT '0',
  `grouping_id` varchar(20) DEFAULT NULL,
  `date_needed` varchar(20) DEFAULT NULL,
  `start_of_work` varchar(20) DEFAULT NULL,
  `cenpri_jo_no` varchar(100) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `completion_date` varchar(20) DEFAULT NULL,
  `project_title` text,
  `verified_by` int(11) NOT NULL DEFAULT '0',
  `conforme` varchar(255) DEFAULT NULL,
  `total_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_in_ex` varchar(50) DEFAULT NULL,
  `general_desc` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_head_revised`
--

CREATE TABLE IF NOT EXISTS `joi_head_revised` (
  `joi_id` int(11) NOT NULL,
  `joi_date` varchar(20) DEFAULT NULL,
  `joi_no` varchar(50) DEFAULT NULL,
  `dr_no` varchar(50) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `joi_type` int(11) DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2=repeat order',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `done_joi` int(11) NOT NULL DEFAULT '0',
  `cancelled` int(11) DEFAULT '0',
  `cancelled_by` int(11) DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `cancel_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `date_revised` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `revise_attachment` varchar(255) DEFAULT NULL,
  `shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `packing_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_percent` int(11) NOT NULL DEFAULT '0',
  `date_needed` varchar(20) DEFAULT NULL,
  `completion_date` varchar(20) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `cenpri_jo_no` varchar(50) DEFAULT NULL,
  `start_of_work` varchar(20) DEFAULT NULL,
  `project_title` text,
  `conforme` varchar(50) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `verified_by` int(11) NOT NULL DEFAULT '0',
  `draft` int(11) NOT NULL DEFAULT '0',
  `vat_in_ex` varchar(20) DEFAULT NULL,
  `general_desc` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_head_temp`
--

CREATE TABLE IF NOT EXISTS `joi_head_temp` (
  `joi_id` int(11) NOT NULL,
  `joi_date` varchar(20) DEFAULT NULL,
  `joi_no` varchar(50) DEFAULT NULL,
  `dr_no` varchar(50) DEFAULT NULL,
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `joi_type` int(11) DEFAULT '0' COMMENT '0=po, 1=direct purchase, 2=repeat order',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `draft` int(11) NOT NULL DEFAULT '0',
  `done_joi` int(11) NOT NULL DEFAULT '0',
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
  `vat_percent` int(11) NOT NULL DEFAULT '0',
  `date_needed` varchar(20) DEFAULT NULL,
  `start_of_work` varchar(20) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `completion_date` varchar(20) DEFAULT NULL,
  `cenpri_jo_no` varchar(50) DEFAULT NULL,
  `project_title` text,
  `conforme` varchar(50) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `verified_by` int(11) NOT NULL DEFAULT '0',
  `vat_in_ex` varchar(20) DEFAULT NULL,
  `general_desc` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_items`
--

CREATE TABLE IF NOT EXISTS `joi_items` (
`joi_items_id` int(11) NOT NULL,
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `jor_items_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `currency` varchar(10) DEFAULT NULL,
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
  `cancel` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_date` varchar(20) DEFAULT NULL,
  `materials_offer` text,
  `materials_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_unitprice` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_currency` varchar(100) DEFAULT NULL,
  `materials_unit` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_items_revised`
--

CREATE TABLE IF NOT EXISTS `joi_items_revised` (
  `joi_items_id` int(11) NOT NULL,
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `jor_items_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `currency` varchar(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `cancel` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_date` varchar(20) DEFAULT NULL,
  `materials_offer` text,
  `materials_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_unitprice` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_currency` varchar(100) DEFAULT NULL,
  `materials_unit` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_items_temp`
--

CREATE TABLE IF NOT EXISTS `joi_items_temp` (
  `joi_items_id` int(11) NOT NULL,
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_offer_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `jor_items_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `currency` varchar(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) DEFAULT '0',
  `source_poid` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `materials_offer` text,
  `materials_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_unitprice` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_currency` varchar(100) DEFAULT NULL,
  `materials_unit` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_jor`
--

CREATE TABLE IF NOT EXISTS `joi_jor` (
`joi_jor_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_id` int(11) NOT NULL DEFAULT '0',
  `enduse` text,
  `purpose` text,
  `requestor` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_jor_revised`
--

CREATE TABLE IF NOT EXISTS `joi_jor_revised` (
  `joi_jor_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_id` int(11) NOT NULL DEFAULT '0',
  `enduse` text,
  `purpose` text,
  `requestor` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `received_by` int(11) NOT NULL DEFAULT '0',
  `payment_desc` text,
  `payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_series`
--

CREATE TABLE IF NOT EXISTS `joi_series` (
`series_id` int(11) NOT NULL,
  `series` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_tc`
--

CREATE TABLE IF NOT EXISTS `joi_tc` (
`joi_tc_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `tc_desc` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_tc_revised`
--

CREATE TABLE IF NOT EXISTS `joi_tc_revised` (
  `joi_tc_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `tc_desc` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_tc_temp`
--

CREATE TABLE IF NOT EXISTS `joi_tc_temp` (
  `joi_tc_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `tc_desc` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_terms`
--

CREATE TABLE IF NOT EXISTS `joi_terms` (
`joi_terms_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `terms` text NOT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jor_aoq_head`
--

CREATE TABLE IF NOT EXISTS `jor_aoq_head` (
`jor_aoq_id` int(11) NOT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text,
  `cancel_date` varchar(20) DEFAULT NULL,
  `cancelled_by` varchar(50) DEFAULT NULL,
  `aoq_date` varchar(20) DEFAULT NULL,
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `department` varchar(255) DEFAULT NULL,
  `purpose` text,
  `enduse` varchar(255) DEFAULT NULL,
  `requestor` varchar(255) DEFAULT NULL,
  `noted_by` varchar(255) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `saved` int(11) NOT NULL DEFAULT '0',
  `awarded` int(11) NOT NULL DEFAULT '0',
  `refer_mnl` int(11) NOT NULL DEFAULT '0',
  `refer_date` varchar(20) DEFAULT NULL,
  `served` int(11) NOT NULL DEFAULT '0',
  `open` int(11) NOT NULL DEFAULT '0',
  `date_served` varchar(20) DEFAULT NULL,
  `draft` int(11) NOT NULL DEFAULT '0',
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `prepared_date` varchar(20) DEFAULT NULL,
  `reviewed_by` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jor_aoq_items`
--

CREATE TABLE IF NOT EXISTS `jor_aoq_items` (
`jor_aoq_items_id` int(11) NOT NULL,
  `jor_aoq_id` int(11) NOT NULL DEFAULT '0',
  `jor_items_id` int(11) NOT NULL DEFAULT '0',
  `scope_of_work` text,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(100) NOT NULL,
  `offer_qty` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jor_aoq_offers`
--

CREATE TABLE IF NOT EXISTS `jor_aoq_offers` (
`jor_aoq_offer_id` int(11) NOT NULL,
  `jor_aoq_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_items_id` int(11) NOT NULL DEFAULT '0',
  `jor_items_id` int(11) NOT NULL DEFAULT '0',
  `offer` text,
  `currency` varchar(10) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `comments` text,
  `recommended` int(11) NOT NULL DEFAULT '0',
  `materials_qty` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_offer` text,
  `materials_unitprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `materials_recommended` int(11) NOT NULL DEFAULT '0',
  `materials_balance` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `materials_currency` varchar(50) DEFAULT NULL,
  `materials_unit` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jor_aoq_vendors`
--

CREATE TABLE IF NOT EXISTS `jor_aoq_vendors` (
`jor_aoq_vendors_id` int(11) NOT NULL,
  `jor_aoq_id` int(11) NOT NULL DEFAULT '0',
  `jor_rfq_id` int(11) NOT NULL DEFAULT '0',
  `vendor_id` int(11) NOT NULL DEFAULT '0',
  `price_validity` text,
  `payment_terms` text,
  `delivery_date` varchar(100) DEFAULT NULL,
  `item_warranty` text,
  `freight` text,
  `recommended_by` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `imported_by` int(11) NOT NULL DEFAULT '0',
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text NOT NULL,
  `cancelled_date` varchar(20) NOT NULL,
  `requested_by` varchar(100) DEFAULT NULL
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
  `grouping_id` varchar(10) DEFAULT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text NOT NULL,
  `cancelled_date` varchar(20) NOT NULL,
  `add_remarks` text,
  `remark_date` varchar(20) DEFAULT NULL,
  `remark_by` int(11) NOT NULL DEFAULT '0',
  `cancel_remarks` text,
  `on_hold` int(11) NOT NULL DEFAULT '0',
  `onhold_date` varchar(20) DEFAULT NULL,
  `onhold_by` int(11) NOT NULL DEFAULT '0',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `for_recom` int(11) NOT NULL DEFAULT '0',
  `fulfilled_by` int(11) NOT NULL DEFAULT '0',
  `general_desc` text,
  `status` varchar(150) DEFAULT NULL,
  `status_remarks` text
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
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `due_date` varchar(20) DEFAULT NULL
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
  `grouping_id` varchar(20) DEFAULT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text NOT NULL,
  `cancelled_date` varchar(20) NOT NULL,
  `served` int(11) NOT NULL DEFAULT '0',
  `notes` text NOT NULL,
  `quotation_date` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `joi_ar`
--
ALTER TABLE `joi_ar`
 ADD PRIMARY KEY (`joi_ar_id`);

--
-- Indexes for table `joi_coc`
--
ALTER TABLE `joi_coc`
 ADD PRIMARY KEY (`coc_id`);

--
-- Indexes for table `joi_dr`
--
ALTER TABLE `joi_dr`
 ADD PRIMARY KEY (`joi_dr_id`);

--
-- Indexes for table `joi_dr_details`
--
ALTER TABLE `joi_dr_details`
 ADD PRIMARY KEY (`joi_dr_details_id`);

--
-- Indexes for table `joi_dr_items`
--
ALTER TABLE `joi_dr_items`
 ADD PRIMARY KEY (`joi_dr_items_id`);

--
-- Indexes for table `joi_head`
--
ALTER TABLE `joi_head`
 ADD PRIMARY KEY (`joi_id`);

--
-- Indexes for table `joi_items`
--
ALTER TABLE `joi_items`
 ADD PRIMARY KEY (`joi_items_id`);

--
-- Indexes for table `joi_jor`
--
ALTER TABLE `joi_jor`
 ADD PRIMARY KEY (`joi_jor_id`);

--
-- Indexes for table `joi_rfd`
--
ALTER TABLE `joi_rfd`
 ADD PRIMARY KEY (`joi_rfd_id`);

--
-- Indexes for table `joi_series`
--
ALTER TABLE `joi_series`
 ADD PRIMARY KEY (`series_id`);

--
-- Indexes for table `joi_tc`
--
ALTER TABLE `joi_tc`
 ADD PRIMARY KEY (`joi_tc_id`);

--
-- Indexes for table `joi_terms`
--
ALTER TABLE `joi_terms`
 ADD PRIMARY KEY (`joi_terms_id`);

--
-- Indexes for table `jor_aoq_head`
--
ALTER TABLE `jor_aoq_head`
 ADD PRIMARY KEY (`jor_aoq_id`);

--
-- Indexes for table `jor_aoq_items`
--
ALTER TABLE `jor_aoq_items`
 ADD PRIMARY KEY (`jor_aoq_items_id`);

--
-- Indexes for table `jor_aoq_offers`
--
ALTER TABLE `jor_aoq_offers`
 ADD PRIMARY KEY (`jor_aoq_offer_id`);

--
-- Indexes for table `jor_aoq_vendors`
--
ALTER TABLE `jor_aoq_vendors`
 ADD PRIMARY KEY (`jor_aoq_vendors_id`);

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
-- AUTO_INCREMENT for table `joi_ar`
--
ALTER TABLE `joi_ar`
MODIFY `joi_ar_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_coc`
--
ALTER TABLE `joi_coc`
MODIFY `coc_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_dr`
--
ALTER TABLE `joi_dr`
MODIFY `joi_dr_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_dr_details`
--
ALTER TABLE `joi_dr_details`
MODIFY `joi_dr_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_dr_items`
--
ALTER TABLE `joi_dr_items`
MODIFY `joi_dr_items_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_head`
--
ALTER TABLE `joi_head`
MODIFY `joi_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_items`
--
ALTER TABLE `joi_items`
MODIFY `joi_items_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_jor`
--
ALTER TABLE `joi_jor`
MODIFY `joi_jor_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_rfd`
--
ALTER TABLE `joi_rfd`
MODIFY `joi_rfd_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_series`
--
ALTER TABLE `joi_series`
MODIFY `series_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_tc`
--
ALTER TABLE `joi_tc`
MODIFY `joi_tc_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_terms`
--
ALTER TABLE `joi_terms`
MODIFY `joi_terms_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jor_aoq_head`
--
ALTER TABLE `jor_aoq_head`
MODIFY `jor_aoq_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jor_aoq_items`
--
ALTER TABLE `jor_aoq_items`
MODIFY `jor_aoq_items_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jor_aoq_offers`
--
ALTER TABLE `jor_aoq_offers`
MODIFY `jor_aoq_offer_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jor_aoq_vendors`
--
ALTER TABLE `jor_aoq_vendors`
MODIFY `jor_aoq_vendors_id` int(11) NOT NULL AUTO_INCREMENT;
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
