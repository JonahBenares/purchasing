-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2021 at 09:00 AM
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
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text,
  `cancel_date` varchar(20) DEFAULT NULL,
  `cancelled_by` varchar(50) DEFAULT NULL,
  `aoq_date` varchar(20) DEFAULT NULL,
  `pr_id` int(11) NOT NULL DEFAULT '0',
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
-- Table structure for table `aoq_items`
--

CREATE TABLE IF NOT EXISTS `aoq_items` (
`aoq_items_id` int(11) NOT NULL,
  `aoq_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `item_description` text,
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
  `currency` varchar(10) DEFAULT NULL,
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `quantity` decimal(10,2) DEFAULT '0.00',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `comments` text,
  `recommended` int(11) NOT NULL DEFAULT '0'
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
  `freight` text,
  `recommended_by` int(11) NOT NULL DEFAULT '0'
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
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
`company_id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
`department_id` int(11) NOT NULL,
  `department_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`) VALUES
(1, 'Accounting Department\r\n'),
(2, 'Auxiliary\r\n'),
(3, 'Bacolod HR'),
(4, 'Billing Department'),
(5, 'EIC'),
(7, 'Environment/PCO'),
(8, 'Finance Department'),
(9, 'Fuel and Lube Management'),
(10, 'Health and Safety'),
(11, 'IT Department'),
(12, 'Laboratory and Chemical'),
(13, 'Maintenance'),
(14, 'Office of the GM'),
(15, 'Operation'),
(16, 'Purchasing Department'),
(17, 'Reconditioning'),
(18, 'Security'),
(19, 'Site HR'),
(20, 'Special Proj/Facilities Imp'),
(21, 'Trading Department'),
(22, 'Warehouse Department'),
(23, 'Progen Warehouse'),
(24, 'Testing Group');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
`employee_id` int(11) NOT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT '0',
  `position` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `employee_name`, `department_id`, `position`) VALUES
(1, 'Ma. Milagros Arana', 0, 'General Manager'),
(2, 'Rhea Arsenio', 0, 'Trader'),
(3, 'Jonah Faye Benares', 0, 'Software Development Supervisor'),
(4, 'Kervic Bi', 0, 'Procurement Assistant'),
(5, 'Joemarie Calibjo', 0, 'Service Vehicle Driver'),
(6, 'Maylen Cabaylo', 0, 'Purchasing Officer'),
(7, 'Rey  Carbaquil', 0, 'Service Vehicle Driver'),
(8, 'Cristy Cesar', 0, 'Accounting Associate'),
(9, 'Gretchen Danoy', 0, 'Accounting Supervisor'),
(10, 'Merry Michelle Dato', 0, 'Projects and Asset Management Assistant'),
(11, 'Joemar De Los Santos', 0, 'Lead Trader'),
(12, 'Imelda Espera', 0, 'A/P & Credit Supervisor'),
(13, 'Elaisa Jane Febrio', 0, 'HR Assistant'),
(14, 'Jason Flor', 0, 'Software Development Assistant'),
(15, 'Zara Joy Gabales', 0, 'Billing Assistant'),
(16, 'Relsie Gallo', 0, '0'),
(17, 'Celina Marie Grabillo', 0, 'Billing & Settlement Officer'),
(18, 'Nazario Shyde Jr. Iba', 0, 'Trader'),
(19, 'Gebby Jalandoni', 0, 'Accounting Assistant'),
(20, 'Caesariane Jo', 0, 'Trader'),
(21, 'Lloyd Jamero', 0, 'IT Specialist'),
(22, 'Annavi Lacambra', 0, 'Corporate Accountant'),
(23, 'Ma. Erika Oquiana', 0, 'Trader'),
(24, 'Charmaine Rei Plaza', 0, 'Energy Market Analyst'),
(25, 'Cresilda Mae Ramirez', 0, 'Internal Auditor'),
(26, 'Melanie Rocha', 0, 'Utility'),
(28, 'Genie Saludo', 0, 'HR Assistant'),
(29, 'Daisy Jane Sanchez', 0, 'EMG Manager / WESM Compliance Officer'),
(30, 'Rosemarie Sarroza', 0, 'Trader'),
(31, 'Stephine David Severino', 0, 'Software Development Assistant'),
(32, 'Henry Sia', 0, 'Grid Integration Manager'),
(33, 'Syndey Sinoro', 0, 'HR Supervisor'),
(34, 'Marianita Tabilla', 0, 'Finance Assistant'),
(35, 'Krystal Gayle Tagalog', 0, 'Payroll Assistant'),
(36, 'Hennelen Tanan', 0, 'IT Encoder '),
(37, 'Teresa Tan', 0, 'Contracts & Compliance Asst.'),
(38, 'Dary Mae Villas', 0, 'Trader'),
(39, 'Marlon Adorio', 0, 'E & IC Technician'),
(40, 'John Ezequiel Alejandro', 0, 'Auxiliary Operator '),
(41, 'Carlito Alevio', 0, 'Plant Mechanic'),
(42, 'Regina Alova', 0, 'Operations Analyst'),
(43, 'Rebecca Alunan ', 0, 'Performance Monitoring Supervisor'),
(44, 'Fleur de Liz Ambong', 0, 'Fuel Management Asst.'),
(45, 'Beverly  Ampog', 0, 'Operations Analyst'),
(46, 'Genaro Angulo', 0, 'Electrical Supervisor'),
(47, 'Rey Argawanon', 0, 'Power Delivery & Technical Manager'),
(48, 'Alona Arroyo', 0, 'Operations Planner'),
(49, 'Joemillan Baculio', 0, 'Auxiliary Operator'),
(50, 'Rashelle Joy Bating', 0, 'Projects Coordinator Assistant'),
(51, 'Gener Bawar', 0, 'Machine Shop & Reconditioning Supervisor'),
(52, 'Ruel Beato', 0, 'Plant Mechanic'),
(53, 'Mary Grace Bugna', 0, 'Asset Management Asst.'),
(54, 'Vency Cababat', 0, ' E&IC Technician'),
(55, 'Rusty Canama', 0, 'Plant Mechanic'),
(56, 'Exequil Corino', 0, 'Engine Room Operator'),
(57, 'Juanito Dagupan', 0, 'Operation Shift Supervisor'),
(58, 'Julyn May Divinagracia', 0, 'Admin Assistant'),
(59, 'Melfa Duis', 0, 'Purchasing Assistant'),
(60, 'Jerson Factolerin', 0, 'Utility'),
(61, 'Julius Fernandez', 0, 'Auxiliary Operator'),
(62, 'Luisito Fortuno', 0, 'Auxiliary Operator'),
(63, 'Donna Gellada', 0, 'Parts Inventory  Assistant'),
(64, 'Felipe, III Globert', 0, 'Warehouse Assistant'),
(65, 'Mikko Golvio', 0, 'E&IC Technician'),
(66, 'Eric Jabiniar', 0, 'Plant Director'),
(67, 'Jushkyle Jambongana', 0, 'IT Assistant'),
(68, 'Bobby  Jardiniano', 0, 'Service Vehicle Driver'),
(69, 'Stephen Jardinico', 0, 'Warehouse Assistant'),
(70, 'Joey Labanon', 0, 'Auxiliary Operator Trainee'),
(71, 'Roan Renz Liao', 0, 'Parts Engineer'),
(72, 'Gino Lovico', 0, 'Foreman (Machine Shop & Recon)'),
(73, 'Ricky Madeja', 0, 'Safety Officer'),
(74, 'Danilo Maglinte', 0, 'Engine Room Operator'),
(75, 'Alex Manilla Jr.', 0, 'Fuel Tender'),
(76, 'Concordio Matuod', 0, 'Project Consultant'),
(77, 'Genielyne Mondejar', 0, 'Pollution Control Officer  '),
(78, 'Francis Montero', 0, 'Plant Mechanic'),
(79, 'Andro Ortega', 0, 'Shift Supervisor Trainee'),
(80, 'Joselito Panes', 0, 'Plant Mechanic'),
(81, 'Nonito Pocong', 0, 'Control Room Operator'),
(82, 'Mario Dante Purisima', 0, 'Shift Supervisor Trainee'),
(83, 'Romeo Quiocson Jr.', 0, 'Technical Assistant'),
(84, 'Lawrence Vincent Roiles', 0, 'E&IC Technician'),
(85, 'Roy Sabit', 0, 'Control Room Operator'),
(86, 'Robert Sabando', 0, 'Project Consultant'),
(87, 'Godfrey Stephen Samano', 0, 'O&M Superintendent'),
(88, 'Kennah Sasamoto', 0, 'Test  Engineer'),
(89, 'Iris Sixto', 0, 'Site Facilities Supervisor'),
(90, 'Kelwin Sarcauga', 0, 'Engine Room Operator Trainee'),
(91, 'Ranie Tabanao', 0, '0'),
(92, 'Alexander Tagarda', 0, 'Control Room Operator'),
(93, 'Ariel Tandoy', 0, 'Driver'),
(94, 'Ryan Tignero', 0, 'Shift Supervisor Trainee'),
(95, 'Elmer Torrijos', 0, 'Mechanical Maintenance Supervisor / Equipment & Parts Engr.'),
(96, 'Democrito Urnopia', 0, 'Plant Mechanic'),
(97, 'Jobelle Villarias', 0, 'Company Nurse'),
(98, 'Melinda Aquino', 0, 'Accounting Assistant/ Bookkeeper'),
(99, 'Irish Dawn Torres', 0, 'Site Admin Officer'),
(100, 'Vincent Jed Depasupil', 0, 'Auxiliary Operator'),
(101, 'William Soltes', 0, ''),
(105, 'TESTING', 3, 'sss'),
(107, 'trial', 0, 'test'),
(112, 'Accounting Department', 0, ''),
(113, 'Admin Department', 0, ''),
(114, 'IT Department', 0, ''),
(115, 'Silena Jomiller', 0, 'Admin Assistant'),
(117, 'Board Room', 0, ''),
(118, 'Carlos Antonio Leonardia', 0, 'Senior Project Engineer'),
(119, 'Liza Marie Tasic', 0, ''),
(120, 'Adrian Nemes', 0, ''),
(121, 'Ma. Milagros Arana /  David Tan', 0, ''),
(122, 'David Tan', 0, ''),
(123, 'Elmer Torrijos / Eric D. Jabiniar', 13, ''),
(124, 'Godfrey Stephen Samano / Eric D. Jabiniar', 15, ''),
(125, 'Donna Gellada / Eric Jabiniar', 0, ''),
(126, 'Romeo Quiocson Jr. / Eric D. Jabiniar', 0, ''),
(127, 'Donna Gellada/Ariel Tandoy/Eric Jabiniar', 0, ''),
(128, 'Beverly Ampog / Godfrey Stephen Samano', 0, ''),
(129, 'Beverly Ampog / Eric D. Jabiniar', 0, ''),
(130, 'Ruel Beato / Eric D. Jabiniar', 15, ''),
(131, 'Ruel Beato / Godfrey Samano / Eric D. Jabiniar', 15, ''),
(132, 'Exequil Corino/ Eric D. Jabiniar', 0, ''),
(133, 'Gebby Jalandoni / Cristy Cesar', 1, ''),
(134, 'Iris Sixto / Eric D. Jabiniar', 0, ''),
(135, 'Genielyne Mondejar / Eric D. Jabiniar', 0, ''),
(136, 'Ricky Madeja / Analyn Sulig / Eric D. Jabiniar', 0, ''),
(137, 'Juanito Dagupan / Eric D. Jabiniar', 0, ''),
(138, 'Analyn Sulig / Eric D. Jabiniar', 0, ''),
(139, 'Mario Dante Purisima / Eric D. Jabiniar', 0, ''),
(140, 'Rey D. Argawanon / Eric D. Jabiniar', 0, ''),
(141, 'Zyndyryn Pastera', 8, 'Finance Supervisor');

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
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `offer_date` varchar(20) DEFAULT NULL,
  `pr_details_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `revision_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `joi_dr_items_revised`
--

CREATE TABLE IF NOT EXISTS `joi_dr_items_revised` (
  `joi_dr_items_id` int(11) NOT NULL,
  `joi_items_id` int(11) NOT NULL DEFAULT '0',
  `joi_dr_id` int(11) NOT NULL,
  `jor_id` int(11) NOT NULL,
  `joi_id` int(11) NOT NULL,
  `joi_aoq_offer_id` int(11) NOT NULL,
  `joi_aoq_items_id` int(11) NOT NULL,
  `jor_items_id` int(11) NOT NULL,
  `offer` text NOT NULL,
  `currency` varchar(10) NOT NULL,
  `item_id` int(11) NOT NULL,
  `delivered_quantity` decimal(10,0) NOT NULL DEFAULT '10',
  `quantity` decimal(10,0) NOT NULL DEFAULT '10',
  `unit_price` decimal(10,0) NOT NULL DEFAULT '10',
  `uom` varchar(50) NOT NULL,
  `amount` decimal(10,0) NOT NULL DEFAULT '10',
  `item_no` int(11) NOT NULL,
  `revision_no` int(11) NOT NULL
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
  `vat_in_ex` int(11) NOT NULL DEFAULT '0'
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
  `start_of_work` varchar(20) DEFAULT NULL,
  `cenpri_jo_no` varchar(20) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `completion_date` varchar(20) DEFAULT NULL,
  `project_title` text,
  `verified_by` int(11) NOT NULL DEFAULT '0',
  `conforme` varchar(20) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `draft` int(11) NOT NULL DEFAULT '0',
  `vat_in_ex` int(11) NOT NULL DEFAULT '0'
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
  `cenpri_jo_no` varchar(50) DEFAULT NULL,
  `date_prepared` varchar(20) DEFAULT NULL,
  `completion_date` varchar(20) DEFAULT NULL,
  `project_title` text,
  `verified_by` int(11) NOT NULL DEFAULT '0',
  `conforme` varchar(255) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `vat_in_ex` int(11) NOT NULL DEFAULT '0'
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
  `cancelled_date` varchar(20) DEFAULT NULL
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
  `cancelled_date` varchar(20) DEFAULT NULL
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
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `cancel` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_date` varchar(20) DEFAULT NULL
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
  `joi_id` int(11) NOT NULL DEFAULT '0',
  `jor_id` int(11) NOT NULL DEFAULT '0',
  `jor_aoq_id` int(11) NOT NULL DEFAULT '0',
  `enduse` text,
  `purpose` text,
  `requestor` text,
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `joi_jor_id` int(11) NOT NULL
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
  `payment_amount` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `payment_desc` text
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
  `uom` varchar(100) NOT NULL
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
  `recommended` int(11) NOT NULL DEFAULT '0'
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
  `requested_by` varchar(100) DEFAULT NULL,
  `processing_code` varchar(20) NOT NULL
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
  `part_no` varchar(100) NOT NULL,
  `add_remarks` text,
  `remark_date` varchar(20) NOT NULL,
  `remark_by` int(11) NOT NULL,
  `cancel_remarks` text,
  `on_hold` int(11) NOT NULL,
  `onhold_date` varchar(20) DEFAULT NULL,
  `onhold_by` varchar(20) DEFAULT NULL,
  `item_no` int(11) NOT NULL DEFAULT '0',
  `for_recom` int(11) NOT NULL DEFAULT '0',
  `fulfilled_by` int(11) NOT NULL DEFAULT '0'
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
  `unit_cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
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
  `unit_cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
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
  `unit_cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
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
  `total_cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `discount_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `conforme` varchar(100) DEFAULT NULL,
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `date_needed` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `revised_date` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `approve_rev_by` varchar(50) DEFAULT NULL,
  `approve_rev_date` varchar(20) DEFAULT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `vat_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `verified_by` int(11) NOT NULL DEFAULT '0'
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
  `total_cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `discount_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `conforme` varchar(100) DEFAULT NULL,
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `date_needed` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `revised_date` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `approve_rev_by` varchar(50) DEFAULT NULL,
  `approve_rev_date` varchar(20) DEFAULT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_date` varchar(20) DEFAULT NULL,
  `cancelled_reason` text,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `vat_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `verified_by` int(11) NOT NULL DEFAULT '0'
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
  `total_cost` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `discount_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `conforme` varchar(100) DEFAULT NULL,
  `prepared_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `date_needed` varchar(20) DEFAULT NULL,
  `revised` int(11) NOT NULL DEFAULT '0',
  `revised_date` varchar(20) DEFAULT NULL,
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `approve_rev_by` varchar(50) DEFAULT NULL,
  `approve_rev_date` varchar(20) DEFAULT NULL,
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_date` varchar(20) DEFAULT NULL,
  `cancelled_reason` text,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `vat_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `verified_by` int(11) NOT NULL DEFAULT '0'
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
  `total_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `notes` text,
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `endorsed_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0'
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
  `quotation_date` varchar(20) DEFAULT NULL
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
  `date_received` varchar(20) DEFAULT NULL,
  `po_dr_no` int(11) NOT NULL DEFAULT '0'
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
  `currency` varchar(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0'
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
  `currency` varchar(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0'
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
  `prepared_date` varchar(20) DEFAULT NULL,
  `recommended_by` int(11) NOT NULL DEFAULT '0',
  `packing_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_percent` int(11) NOT NULL DEFAULT '0',
  `grouping_id` varchar(20) DEFAULT NULL,
  `vat_in_ex` varchar(50) DEFAULT NULL
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
  `shipping` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `packing_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_percent` int(11) NOT NULL DEFAULT '0',
  `vat_in_ex` varchar(50) DEFAULT NULL
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
  `vat_percent` int(11) NOT NULL DEFAULT '0',
  `vat_in_ex` varchar(50) DEFAULT NULL
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
  `currency` varchar(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `item_no` int(11) DEFAULT '0',
  `source_poid` int(11) NOT NULL DEFAULT '0',
  `notes` text,
  `revision_no` int(11) NOT NULL DEFAULT '0',
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
  `currency` varchar(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `item_no` int(11) DEFAULT '0',
  `revision_no` int(11) NOT NULL DEFAULT '0',
  `cancel` int(11) NOT NULL DEFAULT '0',
  `cancelled_by` int(11) NOT NULL DEFAULT '0',
  `cancelled_date` varchar(20) DEFAULT NULL
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
  `currency` varchar(10) DEFAULT NULL,
  `item_id` int(11) DEFAULT '0',
  `delivered_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `uom` varchar(50) DEFAULT NULL,
  `amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `item_no` int(11) DEFAULT '0',
  `source_poid` int(11) NOT NULL DEFAULT '0',
  `notes` text
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
-- Table structure for table `project_activity`
--

CREATE TABLE IF NOT EXISTS `project_activity` (
`proj_act_id` int(11) NOT NULL,
  `proj_activity` text NOT NULL,
  `c_remarks` text NOT NULL,
  `duration` varchar(20) NOT NULL,
  `target_start_date` varchar(20) NOT NULL,
  `target_completion` varchar(20) NOT NULL,
  `actual_start` varchar(20) NOT NULL,
  `actual_completion` varchar(20) NOT NULL,
  `est_total_materials` decimal(12,4) NOT NULL,
  `status` varchar(100) NOT NULL,
  `total_weekly_schedule` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pr_calendar`
--

CREATE TABLE IF NOT EXISTS `pr_calendar` (
`pr_calendar_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL DEFAULT '0',
  `pr_details_id` int(11) NOT NULL DEFAULT '0',
  `proj_act_id` int(11) NOT NULL,
  `ver_date_needed` varchar(20) DEFAULT NULL,
  `estimated_price` decimal(12,4) NOT NULL DEFAULT '0.0000'
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
  `cancel_remarks` text,
  `company_id` int(11) NOT NULL,
  `date_delivered` varchar(50) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `qty_delivered` int(11) NOT NULL,
  `fulfilled_by` int(11) NOT NULL DEFAULT '0',
  `for_recom` int(11) NOT NULL,
  `recom_by` int(11) NOT NULL,
  `recom_date_from` varchar(100) NOT NULL,
  `recom_date_to` varchar(100) NOT NULL,
  `recom_date` varchar(100) NOT NULL,
  `on_hold` int(11) NOT NULL,
  `onhold_date` varchar(20) DEFAULT NULL,
  `onhold_by` int(11) NOT NULL,
  `terms_id` int(11) NOT NULL,
  `work_duration` varchar(255) NOT NULL,
  `recom_unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000'
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
  `total_amount` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `rfd_type` int(11) NOT NULL DEFAULT '0' COMMENT '0=po, 1=direct purchase',
  `notes` text,
  `checked_by` int(11) NOT NULL DEFAULT '0',
  `endorsed_by` int(11) NOT NULL DEFAULT '0',
  `approved_by` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `saved` int(11) NOT NULL DEFAULT '0',
  `noted_by` int(11) NOT NULL DEFAULT '0',
  `received_by` int(11) NOT NULL DEFAULT '0'
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
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000'
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
  `item_desc` text,
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `uom` varchar(50) DEFAULT NULL,
  `offer` text,
  `recommended` text,
  `unit_price` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `pn_no` varchar(100) DEFAULT NULL
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
  `pn_no` varchar(50) DEFAULT NULL,
  `served` int(11) NOT NULL DEFAULT '0',
  `cancelled` int(11) NOT NULL DEFAULT '0',
  `cancel_reason` text,
  `cancelled_date` varchar(20) DEFAULT NULL,
  `cancelled_by` int(11) NOT NULL DEFAULT '0'
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
-- Table structure for table `terms`
--

CREATE TABLE IF NOT EXISTS `terms` (
`terms_id` int(11) NOT NULL,
  `terms` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`terms_id`, `terms`) VALUES
(1, 'COD'),
(2, '15 days PDC'),
(3, '20 days PDC'),
(4, '30 days PDC'),
(5, '60 days PDC');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `usertype_id`, `fullname`, `username`, `password`) VALUES
(1, 1, 'Jonah Benares', 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(2, 1, 'Maylen Cabaylo', 'maylencabaylo', 'a9fe9c871d6b39edc5a493be7147d5de'),
(3, 1, 'Kervic Binas', 'kervicbinas', '5e5b2037a5cd5586bd27e4078c252445'),
(4, 1, 'Julyn Divinagracia', 'julyndivinagracia', '7af00e631ad83a5f85d6f47e2f456f1f'),
(5, 1, 'Prency Francisco', 'prency', '05a8d65ce8003935e75509ccca496c0f'),
(6, 1, 'Syndey Sinoro', 'syndey', 'b14a60afb583df6263f3f0d09fa9bd8d'),
(7, 1, 'Glenn Paul Toledo', 'glenn', 'cenpri'),
(8, 1, 'Kervic Biñas', 'kerviccenpri', '8bc30c78bd1bdfb272fa8a842fec9749'),
(9, 1, 'Babylyn Providencia', 'babylyn', 'babylyn123');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=661 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendor_head`
--

INSERT INTO `vendor_head` (`vendor_id`, `vendor_name`, `product_services`, `category_id`, `address`, `phone_number`, `mobile_number`, `fax_number`, `email`, `terms`, `type`, `contact_person`, `notes`, `status`, `ewt`, `vat`, `tin`) VALUES
(1, '2GO Express, Inc.', 'Forwarder', 0, 'BREDCO, Port 2, Reclamation Area, Brgy. 10, Bacolod City', '(034) 435-4965 / 704-2039 / 704-2396', '', '', '', 'Freight Collect / Prepaid', 'Forwarder', 'Ms Apple/Ms Liza', '', 'Active', '2.00', 1, '000-313-401-00021'),
(2, '7RJ Brothers Sand & Gravel & Gen. Mdse.', 'Aggregates', 0, 'Circumferential Road, Brgy. Villamonte, Bacolod City', '(034)458-0190/213-2249', '', '', '', 'COD-Actual Quantity (delivered to site)', 'Manufacturer/Supplier', 'Ms. Tata', '', 'Active', '1.00', 1, '153-905-447-0000 '),
(3, 'A.C. Parts Merchandising', '', 12, 'Gonzaga Street - Tifanny Bldg, Brgy. 24, Bacolod City', '(034) 433-2512', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(4, 'A-1 Gas Corporation', 'Industrial Gas', 0, 'Alijis, Bacolod City', '434-0708; 433-3637; 433-3638; 432-2079', '', '434-4670', 'negrosa_1gascorp@yahoo.com', 'COD', 'Manufacturer', 'Ms. Mary', '', 'Active', '1.00', 1, '000-422-415-000'),
(5, 'AA Electrical Supply', 'Electrical Supplies', 3, 'C & L Bldg., Lacson-Luzuriaga St., BC', '435-3811; 432-3712; 708-1212', '', '434-7736', '', '30 days PDC', 'Wholesaler / Retailer / Distributor', 'Sir Rene ', '', 'Active', '1.00', 1, '923-228-187-0000'),
(6, 'Ablao Enterprises', 'Aggregates', 0, 'Bago City', '461-0376', '', '', '', 'COD ', '', '', '', 'Active', '1.00', 0, '165-161-721-0000NV'),
(7, 'Abomar Equipment Sales Corporation', 'Heavy Equipment    ', 9, 'Lacson Ext., Cor. Goldenfield Sts. Liroville Subd, Singcang, Bacolod City', '433-1687; 432-3673', '0917-720-2153', '432-3673', 'sales@abomar.net', '', '           ', 'Danilo Palomar', '', 'Active', '1.00', 1, ''),
(9, 'Ace Hardware Philippines, Inc. - Bacolod Branch', 'Hardware, Bulbs, Tools, PPE, Electrical Supplies', 0, 'G/F Sm City Bacolod Bldg. A, Poblacion Reclamation Area, Bacolod City', '(034) 468 0135', '', '', '', 'Cash, Check Payment subject for clearing', '', '', 'TIN Number: 200-035-311-000', 'Active', '1.00', 1, '200-035-311-033'),
(10, 'Ace Rubber Manufacturing and Marketing Corp.', 'Rubber Fabricator', 0, 'Galo Street, Bacolod City', '(034)433-2145', '', '', '', 'COD', 'Manufacturer / Fabricator (Rubber)', 'Sir Ike/Ms. Carla', '', 'Active', '1.00', 1, '004-246-093-000'),
(11, 'Agro Star Industrial Trading Corp.', 'Welding Machine, Water Pumps', 2, 'Lacson-Luzuriaga, Bacolod City', '441-3624', '', '441-3624', '', '', '', 'Allan Lapastora/Jenny Mayuno', '', 'Active', '1.00', 1, '446-627-128-000'),
(13, 'AIC Industrial & Safety Supply Inc.', 'PRODUCTS 1. Kings Safety Products-Kings Safety Shoes 2. Testo Electronic Measurement 3. OMNI Lightning & Wiring Devices 4. Aircon, Auto Aircon & Refrigeration Spare parts, motor compressor  5. Installer, Dealer & Service Center of Koppel & Everest Brand Aircon 6. Installer of all type of Air Conditioing Unit 7. Danfoss Products 8. Pressure Gauges, Vacuum & Thermometers 9. Industrial, Repair and Maintenance Chemicals (LPS & Alchem Brand) 10. Safety Products: Hard Hat, Gloves, Welding Jackets and Apron, Masks & Respirators, Goggles, Visitors, Spectacles, Earplugs & Earmuff 11. Hydraulic and Industrial hoses and fittings 12. Roller chain, V-Belts, Sprockets and Conveyors, Table Yop Chain, Packings and Gaskets 13. Industrial and Laboratory Chemicals & Equipments 14. Complete Line of Fire Equipment', 4, 'Lopez Jaena St., Shopping, Bacolod City', '433-8921', '', '432-3416', '', 'COD', 'Distributor/Contractor', 'Ms. Irene', '', 'Active', '1.00', 1, '010-110-878-000'),
(15, 'Almark Chemical Corporation', 'Chemicals', 0, 'Alijis Road, Bacolod City', '433-2864/432-3778', '', '', '', 'COD', '', 'Ms. April', '', 'Active', '1.00', 1, '005-691-529-000'),
(16, 'AMT Computer Solutions', 'Computer Supplies and Accessories, Printers', 11, 'Door #5, Prudentialife Building, Luzuriaga St, Bacolod City', '435-1207 / 213-3607', '', '', 'Mark', 'COD', 'COD', 'Sir Mark Labanon', '', 'Active', '1.00', 0, '269-037-505 NV'),
(17, 'Andreas Hollow Blocks Enterprises', 'Aggregates', 0, 'Brgy. Bata, Bacolod City', '(034) 476-1207', '', '', '', '30 days', '', 'Ms. Jona', '', 'Active', '1.00', 1, ''),
(18, 'Ang Bata Hardware', '', 12, 'Carlos Hilado Highway, Bata, Bacolod City', '(034) 441-3141', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(19, 'Ang Design Studios , Inc.', 'Office Supplies', 0, 'Hilado Street, Barangay 17, Bacolod City', '(034) 435 0463', '', '435-0463', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(20, 'Anilthone Motor Parts & General Merchandise', '', 12, 'Lacson Street - Bacolod North Terminal, Banago, Bacolod City', '(034) 434-7539', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(21, 'A-one Industrial Sales', 'Hardware / Construction Supplies / Consumables / Electrical / Paints / Pipe Fittings / Tools / Equipment, Generator Set, Welding Machine', 2, 'Lopez Jaena St., Libertad, Bacolod', '435-7383; 432-0652; 476-1127', '', '435-7383', '', '', 'Wholesale / Retail / Distributor', 'Ms. Miles', '', 'Active', '1.00', 1, '219-237-560-000'),
(26, 'Ap Cargo Logistics Network Corporation', 'Forwarder', 0, 'Door 2, SYC Building, Lacson Street, Bacolod City', '(034) 432 3981', '', '', '', 'COD', '', '', '', 'Active', '2.00', 1, ''),
(27, 'Apollo Machine Shop', 'Metal Fabrications', 9, 'Lacson St., Bacolod City 6100', '(034) 434-9512', '', '', '', '30 days PDC', 'Manufacturer', 'Mr. Clerence Sy', '', 'Active', '2.00', 1, '434-480-172-0000'),
(28, 'Arising Builders Hardware and Construction Supply', 'Hardware / Construction Supplies / Consumables / Electrical / Paints / Pipe Fittings / Tools / Equipment', 4, 'Door #5 Dona Angela Bldg., Gonzaga St., Bacolod City', '435-4302', '', '708-7070', '', 'COD', 'Distributor', 'Ms. Jovelyn Macahipay', '', 'Active', '1.00', 1, '260-405-968-0001'),
(30, 'Arvin International Marketing Inc.', 'Industrial Salt', 0, 'Bredco Port 4, Bacolod City', '434-7941', '', '', '', 'COD-Cash', 'Manufacturer', '', '', 'Active', '1.00', 1, '215-261-911-000'),
(31, 'Asco Auto Supply', 'Auto Parts', 12, 'Gonzaga Street - Tiffany Building, Barangay 24, Bacolod City', '(034) 433-8963', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, '104-070-176-001'),
(33, 'Atlantic Auto Parts', 'Auto Parts', 12, 'Gonzaga Street, Barangay 24, Bacolod City', '(034) 435-0136', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(34, 'Atlas Industrial Hardware Inc', 'Hardware, Bosch dealer', 2, '56 Lacson St, Bacolod City', '433-8170; 476-4708; 476-8161', '', '435-0715', '', 'COD', '', '', '', 'Active', '1.00', 1, '428-722-144-0000'),
(38, 'Atom Chemical Company, Inc.', 'Chemicals', 0, 'Mansilingan, Bacolod City', '(034)707-0826', '', '446-1571', '', 'COD', '', '', '', 'Active', '1.00', 1, '000-154-904-000'),
(39, 'Automation and Security Inc.', 'CCTV', 0, 'G/F Cineplex Building, Araneta St., Bacolod City', '(034) 704-1842 / 0977-732-5013', '', '', 'bacolod@asi.com.ph/ranelyn@asi.com.ph ', 'COD', '', 'Mr. Jazpe', '', 'Active', '1.00', 1, ''),
(40, 'Ava Construction Supply', 'Hardware, Pipe Fittings, Construction', 5, 'Cor. Yakal-Lopez Jaena Sts., Capitol Shopping Center, Bacolod City', '434-1822; 433-0263; 435-1901; 708-3757', '', '434-6633', '', 'COD', 'COD', 'Sir Lito', '', 'Active', '1.00', 1, '000-426-920-000'),
(41, 'B. Benedicto and Sons., Inc.', '', 0, '99-101 Plaridel St., Cebu City', '(032) 254-4624(032) 255-0941/256-2218', '', '255-2022', '', 'COD', 'Distributor', 'Fre Dagundon', '', 'Active', '1.00', 1, '000-310-804'),
(42, 'B. A. Oriental Tire Supply', 'Tires', 0, 'Liroville Subdivision - D Cruz Drive, Taculing, Bacolod City', '(34)433 0780', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, '135-514-531-000'),
(43, 'Bacolod Canvas And Upholstery Supply', '', 0, 'Gonzaga St, Bacolod City', '(034) 434-3188', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, '000-707-540'),
(44, 'Bacolod Chemical Supply', 'Chemicals', 0, 'Lopez Jaena, Bacolod City, Negros Occidental', '(34)433-3141', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, '166-164-338-000'),
(45, 'Bacolod China Mart', 'Office Supplies', 0, '70 Lacson St., Bacolod City', '434-7293/434-7670', '', '435-0361', '', '', 'Distributor', 'Ms. Donna/Ms, Angela', '', 'Active', '2.00', 1, ''),
(46, 'Bacolod Erd Enterprises', '', 12, 'Rizal Street - Corner Lacson Street, Barangay 22, Bacolod City', '(034) 434-2272', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(47, 'Bacolod General Parts Marketing', '', 12, 'Lacson - Gonzaga Street, Barangay 24, Bacolod City', '(034) 433-1174', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(48, 'Bacolod Global Parts Sales', '', 12, 'Gonzaga Street - Jacman Building, Barangay 24, Bacolod City', '(034) 433-2091', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(49, 'KLS Electrical Supply', 'Electrical Supplies', 3, 'Locsin-Gonzaga Sts. , Bacolod City', '433-3807', '', '435-0243', '', '', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(50, 'Bacolod Integral Trading', 'Hardware, Bosch dealer', 2, 'Luzuriaga St., Bacolod City', '433-8170', '', '435-0715', 'bacolodintegral@yahoo.com', 'COD', 'Distributor', 'Ms. Riza', '', 'Active', '1.00', 1, '923-219-312-000'),
(53, 'Bacolod Kingston Hardware', '', 1, 'Gonzaga, Bacolod City', '435-4734-36', '', '433-7912', '', '', '', 'May Diamante', '', 'Active', '1.00', 1, '006-110-809-0000'),
(56, 'Bacolod Marjessie Trading', '', 12, 'Cuadra Street, Barangay 21, Bacolod City', '(034) 456-2519', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(57, 'Bacolod Marton Industrial Hardware Corp', '', 2, 'Bonifacio St., Bacolod City', '434-2236-37; 435-0637', '', '', '', '', '', '', '', 'Active', '1.00', 1, '274-407-252-0000'),
(61, 'Bacolod Mindanao Lumber and Plywood', 'Lumber, Hardware, Construction Supplies', 1, 'BLMPC Bldg., Lopez Jaena-Malaspina Sts., Bacolod', '433-3610-12', '', '433-3611;433-7485', '', '', '', '', '', 'Active', '1.00', 1, '000-424-993-000'),
(66, 'Bacolod National Trading', 'Hardware', 2, 'Luzuriaga St., Bacolod City', '433-2959', '0920-969-4688', '', 'bacolodnationaltrading@yahoo.com', 'COD', 'Distributor', 'Ms. Rosemary', '', 'Active', '1.00', 1, '005-429-813-000'),
(68, 'Bacolod Office Solutions Unlimited, Inc.', 'Office Supplies', 0, 'Lacson Street, Bacolod City', '433-9636', '', '433-7710', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(69, 'Bacolod Oxygen Acetylene Gas Corp.', 'Industrial Gas', 0, 'Brgy. Alijis, Bacolod City', '(034) 434-1780', '', '', '', '30 days PDC', '', '', '', 'Active', '1.00', 1, '266-459-813-0000'),
(70, 'Bacolod Paint Marketing', 'Paints', 0, 'Luzuriaga St., Bacolod City', '(034) 433-2063', '', '703-2226', '', 'COD', '', 'Ms. Angie', '', 'Active', '1.00', 1, '114-136-910-0000'),
(71, 'Republic Hardware', 'Hardware', 0, 'Bonifacio St., Bacolod City', '434-8317; 434-5125; 433-9941', '', '434-5125', 'republic_hardware@yahoo.com', 'COD', 'Distributor', 'Mr. Romie G. Li / Ms. Susan', '', 'Active', '1.00', 1, ''),
(72, 'Bacolod Steel Center Corporation', 'Structural Steels / Pipes / Welding Electrodes (Rod) / Tool Steel', 1, '#22 LM Bldg., Gonzaga St., Bacolod City', '435-2721-25', '', '434-5385', 'bscc.ph@gmail.com', 'COD', 'Wholesale / Retail', 'Ms. Pinky', '', 'Active', '1.00', 1, '003-293-458-000'),
(73, 'Bacolod Sure Computer, Inc.', 'Computer Supplies and Accessories, Printers', 11, 'Capitol Shopping Center, Hilado St, Villamonte, Bacolod City', '(034) 435-1949', '(34) 435-1948', '435-1948', 'Ms. Vivian', 'COD', 'Distributor / Supplier', '', '', 'Active', '1.00', 1, ''),
(74, 'Bacolod Triumph Hardware (Main Branch)', 'Structural Steels, Pipes , Welding Electrodes (Rod), Cement, Hardware, Construction Supplies', 1, 'Narra Extension, Hervias Subd., Brgy. Villamonte, Bacolod City', '(034) 488-7888', '', '488-7888', '', '30 days PDC ', 'Distributor', 'Ms. Jingle', 'Credit Limit (Php 300,000.00)', 'Active', '1.00', 1, '151-111-318-0000'),
(75, 'Bacolod Truckers Parts Corporation', '', 12, 'Gonzaga Street - Ralph Building, Barangay 24, Bacolod City', '(034) 433-3335', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(76, 'Bacolod Visayan Lumber', '', 4, 'No. 2725 Lopez Jaena Bacolod', '433-8888', '', '433-1572', '', '', '', '', '', 'Active', '1.00', 1, ''),
(78, 'Khaiyen and Khaila Lumber Merchandising', 'Lumber', 0, 'Bangga Cory, Taculing, Bacolod City', '09164080028 / 0943-200-3145 / 0922-210-3206', '', '', '', 'COD', '', 'Ms. Vanessa Grandea', '', 'Active', '1.00', 0, ''),
(79, 'BCG Computers', 'Computer Supplies and Accessories, Printers', 11, 'Lopez-Jaena St., Bacolod City', '(034) 434-2532/709-1888', '', '434-6603', '', 'COD', 'Distributor / Retailer', '', '', 'Active', '1.00', 1, '151-181-378-000'),
(80, 'Bearing Center & Machinery Inc.', 'Bearings, PRODUCT LINES & SERVICES 1. Industrial Bearings - FAG, INA, TIMKEN, JIB, REXNORD, SEAL MASTER 2. Industrial Vee-Belts - Optibelt 3. Timing Belts - Optibelt 4. Pulleys with Taper Bushing 5. Pulleys with Plain Boring 6. Maintenance Tools for Bearings & Vee-Belts', 10, 'Door #8 G/F GGG Bldg., Hilado Ext. Capitol Shopping Center, Bacolod City', '(034) 433-8370', '', '(034) 433-8370', 'bcmi.mla@bearing.ph', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, '000-081-273-009'),
(82, 'Bens Machine Shop', '', 9, 'Lopez Jaena St., Bacolod City', '433-8990', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(83, 'Bright Summit Distribution Corporation', 'Electrical Supplies', 3, '2nd Flr. VCY Cntr. Bldg., Hilado Ext., Bacolod City', '(034) 433-7111', '', '', '', 'COD', 'Distributor', 'Mr. Carlos', '', 'Active', '1.00', 1, ''),
(84, 'B-Seg Sand And Gravel', 'Aggregates', 0, 'Prk. San Jose Circumferential Rd., Brgy. Alijis, Bacolod City', '(034) 457-1173 / 0929-6762-702', '', '', '', 'COD-Actual Quantity (delivered at site)', '', 'Mr. Benjie Garcia', '', 'Active', '1.00', 0, '196-812-178 NV'),
(85, 'C. Y. Ong Multi-Distributor', 'Tools, Hardware', 4, 'Door #4 Asian Realty Bldg., Lacson St., Bacolod City', '434-4360; 709-1159', '', '434-4360', '', 'COD', 'Distributor', 'Ma''am Ping Doctora', '', 'Active', '1.00', 1, '142-551-821-0000'),
(87, 'Capitol Subdivision Inc.', '', 0, 'Homesite Subd., Bacolod City', '433-9190', '', '433-3877', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(88, 'CAR-V Industrial Sales', 'SS304 CHECKERED PLATE SS304 Plates sizes 2mm to 24mm x 4 feet x 8 feet SS316/316L Plates sizes up to 24mm x 4 feet x 8 feet SS304 Sheets SS304 Angle Bars SS304/304L & SS316/316L Butt Weld Fittings SS Thread Fittings Seamless Boiler Tubes 20 feet x 24 feet Lengths (Germany) EVER CAST Iron Valves SS304 Valves Screw End & Flange End SS316 Valves Screw End & Flange End SS304/304L , 316/316L Flanges (Slip-on & Welding Neck) Sarco Thermodynamic Steam Traps Ichigo Butterfly Valves SS304 Disc, Cast Iron Body B. I. Seamless Pipes Sch 40 & Sch 80 Forged Cast Steel Welding Flanges ASTM-A105 (Italy) SLG Cast Steel Valves  ALL STATE MAINTENANCE WELDING ALLOYS & FLUXES Alloys rods hard facing electrodes Kadomax carbon electrodes Uni-air carbon electrode torch All-star welding rod redrying Portable Oven Purox welding & cutting equipment Nkk tin base & lead base Babbitt Weld-Tech Satellite #6 U. S. Welding Rods, Bare & Flux Coated  MISCELLANEOUS PRODUCTS 1. BEARINGS all kinds 2. Motolite Batteries  OIL & LUBRICANTS 1. OIL Seals, O-rings, & V-rings  FILTERS-INDUSTRIAL & AUTOMOTIVES 1. mechanical Seals 2. Circlips 3. Industrial Hoses-Plastic & Rubber  FITTINGS - Hydraulic, brass & pneumatic valves Automotive Wires & Cables Couplings Black Rubber Conveyors Chains & Sprockets Tubings: Stainless & Copper Industrial Belts-Light & Heavy Duty Bolts & Nuts Pulleys Welding Products Tiger bronze bushings Canvass Belts Beltings Fasteners & Lacings Roller Chains Cam Clutches Speed Reducers Car Accessories Non-Asbestos Compressed Gasket Asbestos, Packings & Gaskets Teflon, Packings, Sheets, tapes Solid Rods & bushing Calcium silicate industrial heat insulation  PNEUMATIC ELECTRIC TOOLS 1. SHINANO SP AIR Pneumatic Tools-Japan 2. DEWALT Power Tools-USA/Italy 3. HILTI Power Tools-Switzerland 4. CHAMPION ROTOBRUTE Magnetic Drill-USA  MISCELLANEOUS 1. DALO Metal Markers-USA 2. TEMPILSTIK Temperature Indicator-USA 3. DISC-LOCK Vibration-Proof Locknuts & Washers-USA 4. RINGFEDER Shaft/Hub Locking Devices 5. GREENLEE Electrical Insulation Tools-USA 6. 3M Scotch Electrical Products 7. DURAFLEX Wires and Cables 8. EAGLE Wiring Devices  9. NATIONAL Wiring Devices 10. APPLETON Explosion-proof Conduit Fittings 11. CROUSE HINDS Explosion-proof Conduit Fittings 12. WHEATLAND Conduit Pipes and Fittings 13. PHILIPS Industrial Lightning Fixtures 14. WESTINGHOUSE Circuit Breakers  OTHERS 1. STANLEY Work Tools 2. CRESCENT Tools 3. RIDGID Industrial Tools 4. PROTO Industrial Tools 5. UNIOR Tools 6. FACOM Fitting, production & Professional Maintenance Tools 7. HYDROTECH Hydraulic Pumps, Equipment, Tools, Accessories 8. KWIK METAL Steel Rein Forced Putty 9. ENERPACK industrial Tools and Hydraulic Systems  ALLIED PRODUCTS WELDING CONSUMABLES 1. ALL STATE WELDING PRODUCTS - Dealer 2. KOBE ELECTRODE - Japan 3. CHOSUN ELECTRODES - Korea 4. METRODE Welding Consumables - UK 5. LOCTITE - Adhesives & Sealant  WELDING ACCESSORIES 1. BURTON Flexible Welding Cable-Australia 2. Magnaflux Dye Penetrant - UK 3. DYNAFLUX Welding Chemicals-USA 4. PHOENIX Electrode and Flux Oven-USA 5. ARCAIR Goughing Torch and Rods-USA 6. WELDCRAFT Tig Torches and Accessories-USA 7. G. A. L. Welding Gauges-USA 8. JACKSON Arc Welding Accessories-USA 9. MAKO Arc Welding Accessories-USA  WELDING MACHINES 1. MILLER Diesel Driven Welding Machine-USA 2. THERMAL ARC Diesel Driven Welding Machine-USA 3. CIGWELD Tig/Mig Welding Machine-Australia 4. OTC Mig Welding Machine-Japan  GAS WELDING AND CUTTING EQUIPMENT 1. TANAKA Thermal Cutting Machine-Japan 2. WESCOL Flowmeter/Regulator & Flashback Arrestor-UK 3. THERMOID Gas Welding Twin Hoses-USA 4. CIGWELD Cutting Outfit-Australia  SAFETY PRODUCTS 1. RED WING Industrial Safety Shoes & Coveralls-USA 2. FIBER-METAL Safety Spectacles, Faceshield, Welding 3. Mask-USA 4. JACKSON safety Spectacles, Faceshiled, Welding Mask 5. Safety Helmet-USA 6. CIGWELD Safety Helmet, Welding Mask-Australia 7. WELDAS Welding Gloves & Garments-USA 8. ACES Safety Spectacles and Faceshield  SKF PRODUCTS 1. SPECIAL LUBRICANT      A. LUBCON OIL      B. LUBCON GREASE      C. SOLUTION GREASE such as Conductive Grease, Extreme Temp. 2. OTHER SOLUTION or CUSTOMIZED PRODUCTS      A. BEVER GEAR      B. SKF BEARING UNIT for IDLER ROLLER CONVEYORS used in      mining & cement      C. CUSTOMIZATION 3. TRAININGS BEARING MAINTENANCE & TECHNOLOGY AND APPLICATION SKF BM I - Basics of Bearing Technology SKF BM II - Bearing Lubrication and Maintenance Applications SKF III - Beyond Troubleshooting: A Study of Advanced Procedural Methodologies  CENTRALIZED LUBRICATION SYSTEMS (Lubrication Solutions) 1. Grease Lubrication Systems 2. Oil Lubrication Systems (Oil Air, Oil Circulation,..) 3. Chain Lubrication (Spray, Brush, Grease Injection,…) 4. Lubrication Systems for On and Off Road Vehicles, Heavy Equipments 5. Minimum Quantity Lubrication (MQL) 6. Wheel Flange Lubrication for Rail Vehicles 7. Dry Lubrication Systems (Initially for Plastic Table Top Chain)  POWER TRANSMISSION PRODUCTS 1. BELT DRIVES: V-BELTS, SYNCHRONOUS BELTS & PULLEYS (STATICALLY BALANCE @ 6.3G OR DNAMICALLY BALANCE @ 1G) 2. CHAIN DRIVES: ROLLER CHAIN & SPROCKETS COUPLINGS 3. PULLEYS 4. SPROCKETS 5. TAPER LOCK BUSHING TECHNOLOGY AND HUBS  CONDITION MONITORING 1. Machine Condition Advisor (MCA) 2. Machine Reliability Inspection System (MARLIN) Microlog Systems (Portable Instruments) 3. OLS (On Line Systems) Condition Monitoring & Protection Systems 4. Ultrasonic Gauge (Detect Pressure & Vacuum leaks including Compressed Air, Steam Trap Inspection, Electrical inspection & General  Mechanical Inspection) 5. Motor Analysis, Test for all types of electrical rotating machinery,  on-line monitoring of power circuit issues, overall motor health, load  & performance. 6. Electrical Condition Based Maintenance with dynamic and static  technoogy for electric rotating machinery diagnostics and performance  monitoring.  MAINTENANCE PRODUCTS 1. Bearing Mounting Tools 2. Bearing Dismounting Tools 3. Bearing Heaters 4. Lubricants 5. Lubricators 6. Laser Alignment Precision Tools for Shaft & Belts (Pulleys) 7. Other Maintenance Tools, Instruments 8. Bearing Analysis Kit 9. Basic Condition Monitoring Kit  LINEAR MOTION & PRECISION TECHNOLOGIES 1. Guiding Systems: Profile, Precision Rail Guides 2. Actuating Systems: Actuators 3. Driving Systems: Ball & Roller Screws, Linear Ball Bearings, Ground Ball Screw 4. Positioning Systems: Standard and Precision Slides, Telescopic pillars 5. Precision Systems: Bolt Tensioner  Coupling Systems 1. OK Coupling for long shafts 2. Super Grip Bolts  SEALS Oil Seals, Grease Seals, Speedi Sleeve, Large Diameter Seals, Split seals, Machined Seals  BEARINGS & UNITS 1. Ball & Roller Bearings, Plain Bearings, CARB Torroidal Bearing, Slewing Bearings 2. Bearing Housings & Accessories, Plummer & Pillow blocks, Y-Units Concentra 3. Engineering Products: Stainless Steel, Insocoat, Hybrid, NoWear, Sensorized, High Temperature, Seize Resistant, ICOS, Vibratory Applications  Sealed, Composite(Corrosion & Chemical Resistant), Solid Oil, Precision  Bearings (SNFA), Sealed Spherical Roller Bearings. 4. Large Size Bearings (Greater than 420mm Outside Diameter) 5. Energy Efficient Bearings', 7, 'No. 25 Valtram Bldg., Lacson-Gonzaga Sts., BC', '434-4661; 433-3835; 708-0210', '', '434-4660', 'sales@car-v.ph / niko@car-v.ph / tramcar@car-v.ph', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, '003-977-662-0000'),
(91, 'Catcom Marketing', 'Fire Suppression System', 0, 'CATCOM Building, Door 1 L2-A3 Taculing Road, Bacolod City 6100', '(034) 434 8732', '', '704-2062', 'catcommktg_rico@yahoo.com', 'COD', '', 'Mr. Rico Catalogo', '', 'Active', '1.00', 1, '919-463-263-000'),
(92, 'Cebu Bolt And Screw Sales', 'Bolts', 0, 'Unit A, PCI Center, Kalubihan, Cebu City', '(032) 412-3561', '', '254-0062', 'sales@cebubolt.com', '30 days', 'Wholesaler / Retailer', 'Ma''am Evelyn / Ms. Cathy', '', 'Active', '1.00', 1, '171-847-217-000'),
(93, 'Central Gas Corporation (CEGASCO)', 'Industrial Gas, Oxygen Gas, Acetylene Gas, Argon Gas', 0, 'Km7 Natl South Rd., Brgy. Pahanocoy, Bacolod City', '(034) 444-0048 / 444-1113 / 444-1109 / 444-1996 / 444-1348 / 444-1344 / 444-1348', '', '', '', '15 days PDC', 'Manufacturer / Wholesaler / Retailer', 'Ms. Mary', '', 'Active', '1.00', 1, '000-424-714-000'),
(94, 'Cezar Machine Shop', '', 9, '92 Rizal Estanzuela St., Iloilo City', '(033) 337-1068', '', '', 'cmsiloilo@yahoo.com', '', '', '', '', 'Active', '1.00', 1, ''),
(95, 'Char Pete General Merchandise', 'Aggregates', 0, 'Bago City', '473-0300', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(96, 'Cibba Paint Center, Inc.', 'Paints', 0, 'CEJ Building, Lopez-Jaena StreetBacolod City', '(034) 433 9291', '', '(034) 433 9291', '', 'COD', '', 'Mr. Philip', '', 'Active', '1.00', 1, '006-264-642'),
(97, 'CLG Commercial Corporation', 'Hardware, Concrete Louvers', 0, 'Narra Ext., Bacolod City', '433-5329/707-0474 / 0909-260-4184 / 0925-828-1156', '', '', '', 'COD', 'Manufacturer / Distributor', 'Ms. Joan', '', 'Active', '1.00', 1, '293-496-177-0000'),
(98, 'ColorSteel System Corp.', '', 1, 'EAC Building - Pacific Home Depot,Lacson - Mandalagan St.,Brgy. Banago, Sta. Clara Subd.,Bacolod City, Bacolod', '(034) 421 2267', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(99, 'CORDS Industrial Sales and Services', '', 8, 'Dr. 1 SC Bldg. Libertad Ext., Mansilingan, Bacolod City', '446-2339', '', '707-8059', 'cords_indl@yahoo.com', '', '', '', '', 'Active', '1.00', 1, ''),
(100, 'Crismar Enterprises', 'Bolts, Hardware', 0, 'Cuadra St.,  Brgy. 21, Bacolod City', '434-1210', '', '707-0288', '', 'COD', 'Distributor', 'Mr. Noel', '', 'Active', '1.00', 1, '192-077-063'),
(101, 'Cro-Magnon Corporation', 'HIGH DIELECTRIC INSULATING VARNISH  1. Electrical & Electronic Cleaners 2. Corrosion Inhibitors 3. Lubricants 4. Lubricating & Penetrating Oil 5. Penetratings Oils 6. Solvent Cleaners & Degreasers 7. Varnishes 8. Greases 9. Cutting Fluid', 0, '45 Gochuico Bldg., Mabini Cor. Rosario St., Bacolod City', '433-3221; 434-1416', '', '', 'cromag@eastern.com.ph', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(102, 'Crossworlds Trading and Engg Services', '', 9, 'Door 3 Zerrudo Commercial Complex (former Lopez Arcade) E. Lopez St. Jaro, Iloilo', '', '0932-883-5832; 0939-848-3037; 0917-779-1544', '', 'trading.crossworlds@yahoo.com', '', '', '', '', 'Active', '1.00', 1, ''),
(103, 'CS Sales', '', 12, 'LACSON STREET - CORNER LUZURIAGA STREET, BARANGAY 37, BACOLOD CITY', '(034) 434-5390', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(104, 'Daks Auto Supply', 'Auto Supply / Parts / Accessories', 12, 'Lopues Mandalagan - Annex Building , Mandalagan, Bacolod City', '0922-8561591', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(105, 'DBO Auto Parts', 'Auto Parts', 12, 'Rizal Street - Door 5 Lizlop Building, Barangay 21, Bacolod City', '(034) 435-6304', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(106, 'Warlen Industrial Sales Corp.', 'Air Conditioning Units', 0, ' Lot 20 Block 2, Lacson Extension, Alijis Road, Bacolod City', '(034) 435-1573', '', '', 'deka.bcd.service@yahoo.com', 'COD', '', 'Ms. Manilyn Estorco', '', 'Active', '2.00', 1, ''),
(107, 'Philippine DFC Cargo Forwarding Corp.', 'Forwarder', 13, 'LGV Building, Molave Street, Capitol Shopping Center, Bacolod City', '(034) 709-1128', '', '', '', 'COD', 'Forwarder', 'Ms. Jonah', '', 'Active', '1.00', 1, ''),
(108, 'Direct Electrix Equipment Corporation', 'Electrical Supplies, Electrical Contractor, PRODUCT & SERVICES OFFERED: 1. Servicing of Motors.Transformer 2. Complete Test Instrument 3. Rewinding AC and DC Motors 4. Rewinding of Generator 5. Servicing of Substation 6. Electrical Design & Installation 7. Transmission Line  PRODUCT LINE 1. Load Break Switch 2. CT/PT 3. Switchgear 4. Transformer 5. Capacitor 6. Motor / Generator 7. Civil Works', 3, '#28 Ramylu Drive, Tangub, Bacolod City', '(034) 444-2023 / (032) 948-0221 / (032) 942-2871 / 0916-600-3244 / 0922-853-5384', '', '(034) 444-2023 / (032) 942-0017', 'directeletrixbacolod@gmail.com / jfrotea.sales@gmail.com / deec.salesinfo@directelectrix.com', 'COD', 'Distributor/Contractor', '', 'website: www.directelectrix.com', 'Active', '1.00', 1, ''),
(111, 'DMC Industrial Supplies', '', 0, 'Mabini St., Bacolod City', '(034) 441-3621 / 0943-283-1688', '', '', '', 'COD', 'Distributor', 'Mr. Marlon Chiu', '', 'Active', '1.00', 1, '125-769-785-000'),
(112, 'DY Home Builders, Inc.', '', 3, 'No. 2725 Lopez Jaena Bacolod', '433-2222', '', '433-6696', '', '', '', '', '', 'Active', '1.00', 1, ''),
(113, 'Dynasty Management & Devt Corporation', '', 4, 'Araneta St., Brgy. Singcang, Bacolod City', '', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(115, 'Dynasty Paint Center', 'Paints', 0, 'Lopez-Jaena Taal Sts., Bacolod city', '(034) 435-4777', '', '435-4777', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(116, 'Dypo Auto Parts', 'Auto Parts', 12, 'Lacson Street - Door 2 Jr Building, Barangay 21, Bacolod City', '(034) 707-7055', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(117, 'Ebara Benguet, Inc', 'Pumps', 2, 'Door 3 Eusebio Arcade, Lacson St., Brgy 40, Bacolod City', '435-8162', '', '', 'pumpsales@ebaraphilippines.com', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(118, 'Eduard Metal Industries', '', 9, '23rd St, Bacolod City', '432-0490 / 0917-300-4160', '', '', '', '15 days PDC', 'Manufacturing', '', '', 'Active', '2.00', 1, '125-782-251-000'),
(119, 'Enigma Technologies Inc.', 'Computer Supplies and Accessories, Printers', 11, '2F Terra Dolce Center, Hilado Ext., Bacolod City', '(034) 435 8144', '', '', '', 'COD', 'Distributor', '', '', 'Inactive', '1.00', 1, ''),
(120, 'Far Eastern Hardware & Furniture Enterprises, Inc.', '', 2, '38 Quezon St. Iloilo City', '(033) 335-0891 ; 337-2654 ; 337-2222 ; 337-5321 ; 508-4196', '', '(033) 3382996', 'feh_qzn@yahoo.com', '', '', '', '', 'Active', '1.00', 1, ''),
(124, 'Federal Johnson Engineering Works', 'Fabrication', 9, 'Circumferential Rd, Bacolod City', '441-2110; 441-0306', '', '441-0356', '', 'COD', '', '', '', 'Active', '1.00', 0, ''),
(125, 'FGV Enterprises', '', 12, 'Luzuriaga Street - Door 1 Lucias Building, Barangay 25, Bacolod City', '(034) 433-2672', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(126, 'Fil-Power Group and Marketing Corp', 'Generators', 9, 'St Anthony Bldg Lopez Jaena St, Bacolod City', '434-7957; 707-8035', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(127, 'Firbon Multi Sales', '', 12, 'Cuadra Street - Door 3 Rgr Building, Barangay 21, Bacolod City', '(0920)479 5919', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(128, 'Francis New Tractor Parts', '', 12, 'Lacson - Cuadra Street, Barangay 24, Bacolod City', '(034) 433-1456', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(129, 'Fuman Industries Inc.', 'Welding and Cutting Materials, pumps, valves, gauges', 8, 'Brgy. Banago, Bacolod City', '435-0973', '0916-943-1989', '', 'molinesjay@gmail.com', 'COD', '', 'Mr. Jayrud F. Molines', '', 'Active', '1.00', 1, '000-067-482-000'),
(130, 'Gini GTB Industrial Network Inc./AsiaPhil', 'SERVICES 1. Turn Key Electrical Works. 2. Supply and Installation of Substation and Distribution Equipment 3. Supply and Installation Of Transmission Lines 4. Supply and installation of Service Entrances 5. Supply and installation of Perimeter Lightnings 6. Supply and installation of Cable Trays and Feeder Lines 7. Installation of Power Generation Equipment 8. Other Electrical Contracting Services  PRODUCTS 1. Panel Board (for AC/DC Applications), Load Center, Enclosed Circuit Breaker,  2. Compact Distribution Lightning Panel 3. Compact Distribution Panel 4. Enclosed Circuit Breakers 5. 69kv Primary Metering 6. Primary Protection 7. Medium Voltage Solution (4 feeders and 2 feeders) 8. Power Transformer 9. Substation Solution 10. 69KV  Transmission Line 11. Consignment of Utility Products 12. Systems Loss Reduction Equipment (MV Capacitors, Preventive Maintenance)  POWER QUALITY SOLUTIONS SERVICES 1. Periodic & Preventive Maintenance Services 2. Measurement & Analysis 3. Energy Savings & System Efficiency 4. Retrofitting Works 5. Testing and Commissioning 6. Emergency Services  ASIAPHIL ELECTRIC PRODUCTS 1. BEA LVSG 2. CDLP Lightning Panel (100A-Below) 3. CDP-32 (Above 250A) 4. Series-4 (250A-125A) 5. Eazybox (ECB) 6. GINI LC (Load Center) 7. VRC Capacitor Units 8. ALDO (Capacitor Bank) 9. BEA MFT (MCC Fixed Type) 10. BEA MFW (MCC Fully Withdrawable) 11. ROBIE (MVSG 7 V Indoor/Outdoor) up to 36KV 12. UNA MVA (Unitized Assembly, 15KV) 13. UNIPAL (Unitized Panel) 14. Victory Seriers (Loose Controllers) 15. EazyTrans (Transfer Switches) -E-Trans-M (MTS) -E-Trans-A (ATS), A. TRANSMISSION EQUIPMENT    a.1. Deadend Transline    a.2. Suspension Tension Transline    a.3. Post Insulator Transline    a.4. Suspension Insulator Transline    a.5. Arrester w/ Corona Ring Transline B. DISTRIBUTION EQUIPMENT     b.1. Fuse Links     b.2. Fuse Cut-outs     b.3. Arresters     b.4. Pin-post Insulator     b.5. Distribution Insulator PDI     b.6. Sectionalizers     b.7.Single Phase Recloser     b.8. Three Phase Recloser     b.9. Overhead Disconnector     b.10. Load Break Switch C. SUBSTATION EQUIPMENT     c.1. Surge Arresters with counter     c.2. Dead Tank Breakers     c.3. Power Transformer     c.4. Protective Relays     c.5. Pad Connectors     c.6. HV Instrument Transformer     c.7. Live Tank Breakers     c.8. Battery and battery charger     c.9. XLPES Cables     c.10. Tee Connectors     c.11. HV Disconnector     c.12. SF6 Gas and filling services     c.13. Grounding Rods     c.14. MV Cable Termination Kit     c.15. Bus Supports D. CIRCUIT BREAKERS AND CONTROLS     d.1. 15-34kV Vacuum circuit breakers     d.2. 630A-6300A Air Circuit Breakers     d.3. 1A-1600A,10kA Minature Circuit Breakers     d.4. Overload Relays     d.5.Capacitor cells     d.6. 24kV-36kV Load Break Switch Indoor     d.7. 160A-1600A molded case circuit breakers     d.8. Contactors     d.9. Motor Protector     d.10. PF Controllers E. PANEL BUILDERS COMPONENTS     e.1. Digital Power Meters     e.2. Circuitbreaker control switch     e.3. Indoor Instrument Transformer     e.4. Shrinkable insulator tube     e.5. Post Insulators     e.6. Analog Meters     e.7. lock-out relay     e.8. copper busbars     e.9. insulating sheets     e.10. Terminal Blocks F.CONTRACTORS PRODUCTS     f.1. Exothemic Groundings     f.2. Hand-held Tools     f.3. Wildlife covers     f.4. HV Gloves and sleeves     f.5. Lineman''s wrenches     f.6. Hotline tools     f.7. Grounding Clusters     f.8. Hoists     f.9.Ropes     f.10. Load Lookers', 0, 'Room 209, 2nd Floor Boston Finance and Investment Corp Bldg., Bacolod City', '(034) 435-6269 / 0998-844-3078', '', '435-6269', 'raymundo.manalang@asiaphil.com', 'COD', 'Distributor/Contractor', 'Mr. Raymund Manalang', '', 'Active', '1.00', 1, '007-577-018-000'),
(131, 'GLE Sand and Gravel Enterprises', '', 0, 'GSIS Corner Medel Road Tangub Highway, Bacolod City', '444-1644', '', '444-2591', '', 'COD', '', 'Maam Grace/Ms. Bolyn', '', 'Active', '1.00', 1, ''),
(132, 'Golden Gate Hardware', 'Pipe Fittings, Hardware, Hose, Elbows, Flanges, Valves, Coupling', 4, 'Gonzaga-Lacson Sts., Bacolod City', '(034) 433-0995 / 434-6848', '', '(034) 434-6848', '', '30 days PDC', 'Wholesale / Retail / Distributor', 'Ma''am Susan', '', 'Active', '1.00', 1, '175-516-681-000'),
(134, 'Golden Jal Marketing', '', 4, 'Cokins Bldg, Bacolod City', '433-0698; 435-0061', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(136, 'Golden Tower Commercial', '', 4, 'Dr. 3, Emerald Bldg., Lacson St., Bacolod City', '476-8043 fax', '', '435-12068', '', '', '', '', '', 'Active', '1.00', 1, '931-444-864-000'),
(138, 'Good Hope Enterprises', '', 4, 'Bonifacio St., Bacolod City', '434-8588-89', '', '', '', 'COD', 'COD', '', '', 'Active', '1.00', 1, ''),
(140, 'Greenlane Hardware and Construction Supply Inc', 'Hardware / Construction Supplies / Consumables / Electrical / Paints / Pipe Fittings / Tools / Equipment', 4, 'Lacson St., Bacolod City', '432-1119', '', '434-5948', '', 'COD', 'Wholesale / Retail', 'Ronaldo Lao', '', 'Active', '1.00', 1, '002-194-607-0000'),
(142, 'Highway Tire Supply', 'Car Batteries, Tires', 0, 'Lacson Street, Barangay 38, Bacolod City', '(034) 433-1257', '', '433-1257', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(143, 'HRA Paint Center', '', 0, 'Dr # JQ Center Bldg., Lopez Jaena St., Bacolod City', '(034) 435-6684', '', '', '', 'COD', 'Distributor', 'Sir Allan', '', 'Active', '1.00', 1, ''),
(144, 'Ideal System Komponents', 'TOMOE 1. Ultimate Process Butterfly Valves 2. High Performance Butterfly Valves 3. Rotary Control Valves 4. Chemically Resistant Butterly Valves 5. Rubber Seated Valves 6. Ball Valves 7. Check Valves 8. Pneumatic Rotary Actuators 9. Electric Actuators 10. Manually Operated Actuators 11. Electro-Paneumatic Positioner  LUMEL 1. Analog Panel Meters 2. Digital Meters 3. Large Displays 4. Digital Controllers 5. Recorders 6. Power Controllers 7. Synchronizing Units 8. Measuring Transducers and Separators 9. Meters & analyzers of Power  10. Network Parameters 11. Distributed Control Systems (DCS) 12. PLC, I/O Modules & Converters 13. HMI 14. Transducers 15. Current Transformers 16. Shunts  SUMA 1. In-line Brix Monitoring (Radio Frequency) 2. Panscope 3. Nutsch Filter 4. Slurry Mil  ATAGO 1. In-line Brix Monitoring (Process Refractometer)  APISTE 1. Panel Cooling Units 2. Precision Airconditioning Units 3. Air Cooled Chillers  SAMSON 1. Control & On-Off Valves 2. Globe, Three-way and angle valves 3.  Steam Converters (Steam Conditioning Valve) 4. Diaphragm Actuators 5. Self-operated Regulator 6. Temperature Regulator 7. Pressure Regulator 8. Differential Pressure & Flow Regulator 9. Boiler Regulator, Steam Trap, Air Vent & Strainer 10. Control Valve / On-Off Valve Accessories 11. Positioner 12. Limit Switch 13. Solenoid Valve and Accessories  DANFOSS  1. Variable Frequency Drives Soft Starters  SENSETECH (HEAT-EDGE) 1. Thermocouples 2. Resistance Temperature Detector (RTD) 3. Heaters  PG POWER-GENEX 1. Electro-Pneumatic Positioner 2. Pneumatic to Pneumatic Positioner 3. Rotary Actuators 4. Limit Switch 5. Air Volume Booster 6. Lock-up Valve   NORGREN 1. Pressure Sensing 2. Actuators 3. Solenoid Valves 4. Air Filter/Regulator/Lubricator 5. Pneumatic Fitting/Tubing/Silencers  MASTER GAUGES 1. Pressure Gauges 2. Digital Pressure Gauges 3. Temperature Gauges  KROHNE 1. Flow Measurements 2. Magnetic Flowmeter 3. Variable Area Flowmeter 4. Ultrasonic Flowmeter 5. Vortex Flowmeter 6. Mass (Coriolis) Flowmeter 7. Differential Pressure Flowmeter 8. Pitot Tube Flowmeter 9. Level Measurements 10. Radar (FMCW & TDR) 11. Ultrasonic 12. Float 13. Displacer 14. Potentiometric 15. Level Switch 16. PH/ORP Measurements  HOFAMAT 1. Light Oil Burners 2. Heavy Oil Burners 3. Gas Burners  HUBA CONTROL 1. Pressure Transmitter 2. Diff. Pressure Transmitter 3. Electronic Pressure Switch 4. Mechanical Pressure Switch 5. Pressure Level Transmitter 6. Pressure Measuring Cell 7. Digital Indicator  CMO 1. Knife Gate Valve  PR ELECTRONICS 1. Thermocouple Converter 2. PT100 Converter 3. TC Converter Isolated 4. Isolated Repeater 5. Isolated Converter 6. Hart Transparent Repeater 7. Hart Transparent Driver 8. Temperature/mA Converter', 3, 'Room 4B/4F Villa Angela Metro Plaza Bldg., Araneta St. Bacolod City', '433-4224', '0917-300-6939', '708-6183', 'iskbacolod@yahoo.com; iskbacolod@gmail.com', '', '', 'Ms. Jessica A. Deang (Sales / App. Engineer) - 0998-9730-360 / 0915-952-1615', '', 'Active', '1.00', 1, ''),
(145, 'IEC Computers', 'Computer Supplies and Accessories, Printers', 11, '(034) 433 9472/708-4322', '', '', '', 'Mr. Raymund', 'COD', 'COD', '', '', 'Active', '1.00', 1, ''),
(146, 'Iloilo City Hardware, Inc.', '', 3, '105-107 Iznart St., Iloilo City', '(033) 337-2952; 337-2969 ; 338-1455; 337-5553', '', '(033) 337-4621', '', '', '', '', '', 'Active', '1.00', 1, ''),
(148, 'Iloilo National Hardware', '', 1, '', '(033) 337-0449; 509-8985 ; 337-2841 ; 509-7785', '', '(033) 337-2841/335-8377', 'nationwide888@yahoo.com', '', '', 'Jimmy', '', 'Active', '1.00', 1, ''),
(149, 'Innovative Controls Incorporated (Bacolod Branch)', 'Service & product Provider of Motor Controls and Factory & Building Automation.  PARTNERS SIEMENS, YAVUZ PANO, CARLO GAVAZZI, PHOENIX CONTACT, STEGO, LOGSTRUP, ELECTRA, PICOBOX, SOLCON POWERED, NDC TECHNOLOGIES, BETA LASERMIKE, iO2 WATER  PRODUCTS  1. SIEMENS Programmable Logic Controllers (PLC), Human Machine Interface (HMI), Variable Frequency Drives (VFD), Soft Starters, Control Gears, Circuit Breakers  2. CARLO GAVAZZI Energy Management Meters Software & Systems, Safety Devices, Dupline Field Installation Bus, Monitoring Relays, Timers, Panel Meters, PID Controllers & Counters, Solid State & Electromechanical Relays, Inductive, Capacitive, Conductive, Ultrasonic Sensors  3. YAVUZ PANO Free  Standing System Panels (FSS), Wall Mounted Enclosures (WMS), Outdoor Type Enclosures (HFSS), Console Panels, Polycarbonate Boxes & Enclosures, 19 inches Rack Cabinets  4. STEGO Filter Fans Available From 21m3/h to 550m3/h, IP54 Outdoor Filter Fans, IP55 Roof Filter Fans, Enclosure Lamps, Thermostat & Hygrostats, Airflow Monitor, Heaters  5. PHOENIX CONTACT Relay & Optocoupler Interfaces, Varioface System Cabling for Siemens, Allen-Bradley, Mitsubishi, MODICON, & Mitsubishi PLCs, Power Supplies (for Universal Use & Ex-proof for Zone 2), Signal Conditioners, Transducers, Signal Converters, Serial Interfaces (Wireless Signal Transmission), Industrial Modem, RS-232 & RS-485-Bluetooth Converter  6. SOLCON Low Voltage Soft Starters, Medium Voltage Soft Starters up to 48MW  7. LOGSTRUP Modular Type-Tested Motor Control Centers, Type-Tested Switchgears, Type-Tested Panels, Customized Enclosures  8. PICOBOX Environmental Monitoring System, Facility Monitoring Server  9. ELEKTRA Control Transformers, Medium Voltage Transformers, Isolation Transformers, Medical Transformers, High Frequency Transformers, Reactors, Filters  10. BETA LASERMIKE Precision Measurement & Control Solutions  11. i20 WATER Smart Pressure Management  SERVICES AND SOLUTIONS 1. AUTOMATION SOLUTIONS 2. SCADA SYSTEMS 3. WIRELESS TELEMETRY 4. POWER MANAGEMENT SOLUTIONS 5. DESIGN & ASSEMBLY OF TYPE-TESTED MCC''s & SWITCHGEARS 6. ENERGY SAVING SYSTEMS', 0, 'Rm. 1-10 JDI Bldg., Galo St., Bacolod City', '(034) 708-1727 / 0908-8162189', '', '(034) 708-1727', 'dianne.villareal@innovativecontrols.com.ph', 'COD', 'Distributor', 'Ms. Dianne Villareal - 0908-816-2189', 'website: www.innovativecontrols.com.ph', 'Active', '1.00', 1, ''),
(150, 'Inovadis, Inc.', '', 4, 'Rizal St, Brgy 22, Bacolod City', '435-4634-35', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(152, 'Integrated Power and Control Provider, Inc.', 'PRODUCTS 1. WOODWARD; Governors, Actuators, Engine Controllers and Power Management                                Gas and steam turbine products (retrofit) 2. L & S Hydroelectric Products & Services (Retrofit) 3. GENERATOR: Exciters, AVR''s, Meterings, Protective Relays, Synchronizers, ATs 4. ABB Unitrol 1000 5. Engine parts, filters, accessories, preventive maintenance  SERVICES 1. Service, repair & overhaul of woodward governors and diesel engines. 2. Retrofit/Upgrade of governors, exciters and switchboard for diesel engines, steam, gas & hydro turbines 3. Preventive Maintenance Program for Diesel Gensets 4. On-site Installation and Field Services for Generator-Prime Mover Controls. 5. Integration of Controls to Synchronizing Switchgear', 9, 'Unit #5 East Plaza Commercial Bldg, Suntal Phase II, Circumferential Rd., Brgy Taculing, BC', '446-7612', '', '', 'ipowerbacolod@hotmail.com', '15 days PDC', 'Distributor', 'Mr. Voltaire Piccio', '', 'Active', '1.00', 1, ''),
(153, 'Intrax Industrial Sales/ U2 Machine Shop', '', 8, 'Lot 1 Blk 4 Along Murcia Rd, Hermelinda Homes, Mansilingan, BC', '446-3268', '0917-5475996; 0922-885-8483', '708-1195', 'intraxindustrial@yahoo.com; u2machineshop@yahoo.com', '', '', 'Ronces "Bong" Ababao', '', 'Active', '1.00', 1, ''),
(155, 'ISO Industrial Sales', 'Bolts', 7, 'Luzuriaga St., Bacolod City', '(034) 432-3007', '0917-301-3007', '432-3440', 'iso_boltsnuts@yahoo.com.ph', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(156, 'J. T. Oil Philippines', '', 0, 'Bacolod City', '(034) 435-2666', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(157, 'Jas''t Marketing Co.', 'ELECTRICAL SUPPLIES', 3, '#6 GGG Bldg., Capitol Shopping, Bacolod City', '434-0043', '', '704-1456', '', '30 days', '30 days', 'Samuel Takahara/ Regina Lopez', '', 'Active', '1.00', 1, ''),
(158, 'Johnson Parts Center & General Merchandise', '', 12, '6th Street Lacson - Gensoli Building, Barangay 24, Bacolod City', '(034) 433-5708', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(159, 'Jojo 4 Wheel Parts Supply', '', 12, 'Gonzaga Street - Door 1 Suntal Invst Building, Barangay 24, Bacolod City', '(034) 435-0626', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(160, 'Karl-Gelson Industrial Sales', 'Structural Steels, Gate Valves, Check Valves, Y-Strainer, Elbows, Pipe Fittings', 0, 'Araneta St., Bacolod City', '(034) 432-6318', '', '(034) 432-6318', 'kgsbacolod.rizza@yahoo.com', 'COD', 'Wholesale / Retail', '', '', 'Active', '1.00', 1, ''),
(161, 'Kemras Industrial Supply', '', 0, 'Blk. 5, Lot 11 NHA ACCO Housing, Circumferential Road, Brgy. Alijis, Bacolod City', '(034) 446-3162 / 0906-1464-064 / 0936-927-9953', '', '446-3162', 'wilfredo.fardon@kemrasindustrialsupply.com', 'COD', '', 'Mr. Alden Erasmo/Ms. Maria Fatima Pillado', '', 'Active', '2.00', 1, ''),
(162, 'KLP Easy Electrical', '', 3, 'Libertad extension, 6100 Bacolod City, Negros Occidental, Philippines', '', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(164, 'Kuntel Construction', 'Ready Mix Concrete', 0, 'Rooms 3-6, 2nd Floor, Villa Angela Arcade, Burgos Extension, Bacolod City', '434-7866', '', '434-7866', '', 'COD', '', 'Mr. Joseph Yanson', '', 'Active', '1.00', 1, ''),
(165, 'Leeleng Commercial, Inc.', '', 3, 'Bacolod City', '446-1084', '', '', 'leeleng_bacolod@yahoo.com', '', '', '', '', 'Active', '1.00', 1, ''),
(166, 'Liberty First Enterprises', '', 4, 'T. Gensoli Bldg., Lacson St., Bacolod City', '435-1530; 435-0533', '', '433-1492', '', '', '', 'Ging-ging', '', 'Active', '1.00', 1, ''),
(168, 'Linde Corporation', '', 0, 'Bago City', '213-4596/213-4594', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(169, 'Linton Incorporated', '', 1, 'For Additional Lightning in Powerhouse DG Area', '(02) 733-8800 ; 733-8810 ; 734-1059 ; 733-8817', '', '(02) 733-0493 / 733-0615', 'linton_incorporated@yahoo.com', '', '', '', '', 'Active', '1.00', 1, ''),
(170, 'LMS Electrical Supply', 'Electrical Supplies', 0, 'Gonzaga Street, Bacolod City', '435-0424/434 8423', '', '435-0863', '', 'COD', '', '', '', 'Active', '1.00', 1, '');
INSERT INTO `vendor_head` (`vendor_id`, `vendor_name`, `product_services`, `category_id`, `address`, `phone_number`, `mobile_number`, `fax_number`, `email`, `terms`, `type`, `contact_person`, `notes`, `status`, `ewt`, `vat`, `tin`) VALUES
(171, 'Loc-Seal Industrial Corporation', 'AEG POWERTOOLS 1. Rotary and Percussion Drills 2. Screwdriver 3. Magnetic Drill & Stand 4. Rotary/Combination Hammers 5. Chipping/Demolition Hammer 6. Angle Grinders 7. Die/Straight Grinders 8. Polishers 9. Cut-off Machine 10. Mini Reciprocating Saw 11. Jig Saws 12. Circular Saws 13. Router 14. Laminate Trimmer 15. Blower/Hot Airgun 16. Random/Orbit Sander 17. Planer 18. Belt Sander 19. Cordless Jigsaw 20. Cordless Drills & Drivers 21. Cordless Rotary Hammer 22. Screwdriver 23. Cordless Circular Saw 24. Cordless Reprocating Saw 25. Cordless Reprocating Saw Kit 26. Flourescent Light 27. Flashlight 28. Batteries & Charger 29. Wet & Dry Dust Extractor 30. Arc Welding Machines 31. Welding Cutting Outfit 32. Mig Transformer Welding Machines 33. Tig Inverter Welding 34. Plasma Cutters 35. Mig Welding Machines 36. DC Submerge Arc Welding 37. AC/DC Tig Inverter 38. DC Tig Arc Inverter 39. DC Tig Inverter with Pulse 40. DC Arc Weldig Machine 41. Multi Purpose Machine 42. Spot Welding 43. Battery Chargers 44. Drill Press 45. Cut-off Machine 46. Drill Bits; Wood Boring Bit, Cobalt Drill Bit, Reduced Shank Drill Bit, SDS Plus, Chisel 47. Router Bits; Router Bit Set 48. Disc, Blades & Wire Wheel Brush; Circular Saw Blade, Diamond Blade, Circular Brush, Cutting Disc, Flap Disc, Grinding Disc. 49. Air Tools; Air Hopper, Sand Blasting Gun, Spray Guns, Air Duster Guns 50. Safety Accessories; Danger & Caution Tape, Safety Spectacles, Safety Helmets, Vest, Safety Harness, Safety Shoes  CONTENDER AIR COMPRESSOR, BENCH GRINDER, DRILL PRESS  MEASURING INSTRUMENTS Digital Caliper, Digital Caliper with Round Depth Bar, Digital Caliper with Ceramic Tipped Jaws, Coolant Proof Digital Caliper, Mini Digital Caliper, Vernier Caliper, Dial Caliper, Long Jaw Vernier Caliper, Digital Inside Groove Caliper, Digital Inside Point Caliper, Digital Gear Tooth Caliper, Digital Height Gage, Dial Height Gage, Vernier Height Gage, Digital Depth Gage, Digital Double Hook Depth Gage, Vernier Depth Gage, Dial Depth Gage, Depth Micrometer, Metric Digital Outside Micrometer, Digital Outside Micrometer, Outside Micrometer, Graduation Outside Micrometer, Outside Micrometer with Interchangeable Anvils, Indicating Micrometer, Blade Micrometer, Point Micrometer, Spherical Anvil Tube Micrometer, Disk Micrometer, Screw Thread Micrometer, Measuring Tips for Screw Thread Micrometer, Can Seam Micrometer, Micrometer Stand, Inside Micrometer, Digital Inside Micrometer, Tubular Inside Micrometer, Three Points Internal Micrometer, Bore Gage for Small Holes, Long Handle, Anvil for Bore Gages, Small Hole Gage Set, Telescoping Gage Set, Digital Indicator, Setting Ring, Dial Indicator, Waterproof Dial Indicator, Precision Dial Indicator, One Revolutio Dial Indicator, Contact Point, Lug Back, Flat Back, Extension Rod, Dial Test Indicator, Styli For Dial Test Indicator, Dial Test Indicator Holder, Magnetic Stand, Dial Test Indicator Centering Holder, Magnetic Stand, Universal Magnetic Stand, Flex Arm Magnetic Stand, Internal  Dial Caliper Gage, Granite Comparator Stand, External Dial Caliper Gage, Thickness Gage, Digital Thickness Gage, Thread Ring Gage, Steel Gage Block Set, Thread Plug Gage, Block Level, Digital Level and Protractor, Digital Level and Protractor, Digital Protractor,  Combination Square Set, 90° Flat Edge Square, Machinist Square with Wide Base, 90° Beveled Edge Square, Straight Edge, Feeler Gage, Long Feeler Gage, Feeler Gage, Feeler Gage Tape, Pitch Gage, Center Gage, Angle Gage, Radius Gage, Radius Gage Set, Taper Gage, Welding Gage, Inside Spring Caliper, Outside Spring Caliper, Spring Divider, Steel Rule,  Straight Edge, Circumference Tape, Scriber, Granite Surface Plate, V-Block Set, Magnetic  V-Block, Electronic Edge Finder, Electronic Edge Finder, Centering Indicator, 2-piece Measuring Tool Set, Data Output System, Profile Projector, Video Measuring Microscope Code ISD-A100*, Portable Measuring Microscope Code ISM-PM100, Digital Microscope Code ISM-PM200, Software for Digital Microscope Code ISM-PRO, Digital Force Gage, Surface Roughness Specimen Set Code ISR-CS130-W, Roughness Tester, Coating Thickness Gage Code ISO-3500FN-W, Ultrasonic Thickness Gage Code ISU-200D*, Endoscope Code ISV-E55*, Manual Rockwell Hardness Tester Code ISH-R150, Portable Hardness Tester Code ISH-PHA*, Shore Durometer, Digital Torque Wrench, Safety Seals, PPE, Industrial Adhesives Saws & Blades, Cutting & Grinding Disc, Welding Machine & Rods, Skim Coat & Tile Adhesives, Installer of Fire Alarm System, CCTV, architectural hardware, sandvik coromant, paints, carbon brush, safety products, saws and blades, cutting and grinding disc, abrasive products, machine and automotive shop supplies, tapes, tools, welding rod, industrial adhesives, security seal, chemicals & lubricants, epoxy products, thinners, primers, light bulbs, janitorial products, installer of fire alarm system & CCTV', 4, 'Ma. Kho Apartment, Door # 2 1034, Sierra Madre St., Brgy. Villamonte, Bacolod City', '(034) 461-8073 / 0922-2969237', '0932-892-4909', '461-8073', 'locsealcorp.aljeroabellana@gmail.com', 'COD', 'Wholesaler / Retailer / Distributor', 'Mr. Benjamin David Carranza', 'email add: dave.locseal@gmail.com', 'Active', '1.00', 1, ''),
(174, 'Bacolod Luis Paint Center', 'Paints', 0, 'Gonzaga St., Bacolod City', '(034) 435-0301/3108', '', '(034) 435-3108', '', '30 days PDC', 'Wholesaler / Retailer / Distributor ', '', '', 'Active', '1.00', 1, '258-267-375-001'),
(175, 'Luvimar Enterprises', '', 0, 'Rizal Street corner Gatuslao Street (beside LLC), Bacolod City', '(034) 476-3612', '', '', 'luvimarfirecontrol@luvimar.com', 'COD', 'Distributor', 'Mr. Angelo Abdul', '', 'Active', '1.00', 1, ''),
(176, 'Lyfline Marketing', '', 0, 'Galo Hilado, Bacolod City', '(034) 434 6543/(34)434-2582', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(177, 'MAC JILS Refrigeration / Airconditioning Repair Shop', '', 0, 'Prk. Sto. Rosario, lacson St., Bacolod City', '(034) 707-0639 / 0919-637-0637', '', '', 'mamertoyalong@gmail.com', 'COD', 'Distributor', 'Mr. Mamerto Yalong', '', 'Active', '2.00', 0, ''),
(178, 'MB United Commercial', 'Structural Steel, Construction Supplies, Hardware', 1, 'Yakal St., Villamonte, Bacolod City', '435-3131; 434-7283; 709-1053', '', '435-2901', '', 'COD', 'COD', 'Ms. Melanie Alvarado', '', 'Active', '1.00', 1, ''),
(181, 'Metro Pacific Construction Supply, Inc.', '', 3, 'No. 47 Mabini Street, Iloilo City', '(033) 338-1316 ; 337-1210 ; 337-3762; 337-0815', '', '(034) 336-3279', '', '', '', '', '', 'Active', '1.00', 1, ''),
(182, 'MF Computer Solutions, Inc.', 'Computer Supplies and Accessories, Printers', 11, 'JTL Bldg. BS Aquino Drive Shopping, Bacolod City', '434-6544', '', '434-6544', 'info@mfcomputersolution.com', 'COD', 'Distributor / Retailer', 'Sir Che / Ms. Nova Oricio', '', 'Active', '1.00', 1, ''),
(184, 'MGNR Hardware & Construction Supply', '', 4, '2780 Hilado Ext., Brgy Villamonte, Bacolod City', '435-3790', '', '', '', '', '', 'Ian Paglumotan', '', 'Active', '1.00', 1, ''),
(187, 'Micro Valley', '', 0, 'Reclamation Area, Bacolod City', '(034) 704-4317', '', '', 'MVbacolod5@yahoo.com.ph', 'COD-Cash', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(189, 'Milco Malcolm Mktg', 'PPE, Fire Protection System, Construction', 3, 'M & M Aceron Bldg II, Mabini-San Sebastian Sts., BC', '433-3429; 434-2918; 434-3986; 435-7161', '', '433-3429', '', 'COD', 'COD', 'Romeo ', '', 'Active', '1.00', 1, ''),
(193, 'Mirola Hardware', '', 3, 'Poblacion Sur, Ivisan, Capiz', '(036) 632-0104; 632-0028 ; 632-0108', '', '(036) 632-0104', 'info@mirolahardware.com', '', '', '', '', 'Active', '1.00', 1, ''),
(195, 'Negros Bolts & General Mdse', 'Bolts', 4, '2879 Burgos Ext., BS Aquino Drive, Bacolod City', '435-2260; 708-1183', '', '', '', 'COD', 'Wholesale / Retail', '', '', 'Active', '1.00', 1, ''),
(200, 'Negros International Auto Parts', '', 12, 'Rizal Street - Corner Lacson Street - Sgo Building, Barangay 21, Bacolod City', '(034) 435-1416', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(201, 'Negros Marketing', '', 0, 'Cuadra St., Bacolod City', '(034) 435-4708', '', '', '', 'COD', '', 'Mr. Terence Sy', '', 'Active', '1.00', 1, ''),
(202, 'Negros Metal Corporation', '', 0, 'Brgy. Alijis, Bacolod City', '(034) 433-7398', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(203, 'Negros Pioneer Enterprises', '', 12, 'Gonzaga - Lacson Street, Barangay 24, Bacolod City', '(034) 433-2088', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(204, 'Netmax Solutions', '', 0, 'Silay City', '(034) 213-6120 / 0949-883-2535/0923-141-2611', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(205, 'New Colomatic Motor Parts', '', 12, 'Gonzaga Street - Lm Building, Barangay 25, Bacolod City', '(034) 434-5955', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(206, 'New Yutek Hardware and Industrial Supply Corporation', 'Pipe Fittings, Structural Steels, Gate Valves, Check Valves, Y-Strainer, Elbows', 7, 'Zulueta St., Cebu City, Cebu', '(032) 255-5406', '', '(032) 254-1365', '', 'COD', 'COD', 'Sir Berto', '', 'Active', '1.00', 1, ''),
(207, 'Newbridge Electrical Enterprises', '', 3, 'Lacson Ext., Cor LT Vista St. Singcang, Bacolod City', '433-9298; 433-2365; 434- 2185', '', '', 'newbridge@pldtdsl.net', 'COD', 'COD', 'Ms. Joy/Mr. Clause', '', 'Active', '1.00', 1, ''),
(208, 'Nikko Industrial Parts Center', '', 12, 'Lacson Street - Door 3 Tmg Building , Barangay 25, Bacolod City', '(034) 708-0210/(034) 433-7908/(034) 433-3835', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(209, 'Nippon Engineering Works', '', 2, 'Corner-Mabini Ledesma Sts., Iloilo City', '(033) 338-1122', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(211, 'Northern Iloilo Lumber & Hardware', '', 5, '24 Ledesma, Iloilo City', '(033) 337-4749', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(212, 'NS Java Industrial Supply', '', 3, 'Room 1-11 JDI Bldg, Galo St., Bacolod City', '433-0668', '0917-300-3182', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(213, 'Octagon Computer Superstore', 'Computer Supplies and Accessories, Printers', 11, 'SM City Bacolod, Rizal St., Reclamation Area, Bacolod City', '(034) 468-0205', '(034) 468-0204', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(214, 'Panay Negros Steel Corporation', '', 1, 'Door 2, Torres Bldg, No. 61 Burgos, Bacolod City', '434-8272', '0917-303-1680', '709-1141', 'pnscbacolod@gmail.com', '', '', '', '', 'Active', '1.00', 1, ''),
(215, 'Philippine DFC Cargo Forwarding Corp.', 'Forwarder', 0, 'Siment Warehouse, Zuellig Ave., Reclemation Area, Mandaue City', '0917-629-3024', '', '', '', 'Freight Collect', '', 'Ms. Joy', '', 'Active', '2.00', 1, ''),
(216, 'Pins Auto Supply', '', 12, 'Gonzaga Street - Purok Masinadyahon, Barangay 24, Bacolod City', '(034) 434-9349', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(217, 'Platinum Construction Supply', '', 12, 'Bugnay Road, Villamonte, Bacolod City', '(034) 433-1886', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(218, 'Power Steel Specialist', '', 1, '1714 Ma. Clara St., Sampaloc, Manila', '(02) 731-0000', '', '', 'sales@powersteel.com.ph', '', '', '', '', 'Active', '1.00', 1, ''),
(219, 'Power Systems, Inc', '', 9, 'AU & Sons Bldg., Sto. Nino, Bacolod City', '433-4293', '', '433-7363', '', '', '', '', '', 'Active', '1.00', 1, ''),
(220, 'Prism Import-Export, Inc.', 'Roofing Materials, Lubricants', 0, 'C.L. Montelibano Avenue, Bacolod City', '(034) 433-6045/708-4443/433-5327', '', '434-6433', '', '30 days PDC', 'Manufacturer / Wholesaler / Retail', 'Ms. Veron Golez - 0917-722-2297', '', 'Active', '1.00', 1, ''),
(221, 'Procolors T-Shirts Printing', '', 0, 'Lacson St., Bacolod City', '(034) 434 3403', '', '', '', 'COD', '', '', '', 'Active', '0.00', 1, ''),
(222, 'Ravson Enterprises', '', 0, 'Atrium Bldg., Gonzaga St., Bacolod City', '434-8929', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(223, 'Rc Fishing Supply', '', 0, 'Gonzaga St, Bacolod City', '(034) 434 8299', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(226, 'Richard and Zachary Woodcraft', 'Lumber, Hard Wood', 0, 'Victorina Heights, Libertad Ext., Brgy. Mansilingan', '431-5866/213-3858/0928-337-7568, 0927-325-4497, 0922-562-1005', '', '', 'rzwoodcraft_05@yahoo.com', '15 days PDC', 'Wholesaler / Retailer', 'Mr. Richard Dulos', '', 'Active', '1.00', 1, ''),
(227, 'RTH Marketing', '', 2, 'Door 1, St. Francis Bldg., Lizares Ave.,Bacolod City', '433-1199; 433-8152', '0928-5015595', '433-1199; 433-8152', 'rthmarketing@yahoo.com', '', '', 'Ranilo "Toto" Hulleza', '', 'Active', '1.00', 1, ''),
(230, 'Sam Parts Marketing', '', 12, 'Cuadra Street, Barangay 24, Bacolod City', '(034) 434-6119', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(231, 'SGS Hardware Corporation', 'Construction Supplies, Hardware', 1, 'Gatuslao St., Bacolod City', '(034) 435 3023 to 25', '', '434-6061', '', 'COD', 'COD', '', '', 'Active', '1.00', 1, ''),
(234, 'Sian Marketing', '', 5, 'Luzuriaga-Lacson Sts., Bacolod City', '431-1375', '', '', '', '', '', 'Ken Shi', '', 'Active', '1.00', 1, ''),
(235, 'Silicon Valley', '', 11, 'SM Bacolod City', '(034) 431-3251', '', '', 'Ms. Ping', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(236, 'Silver Horizon Trading Co. Inc.', '', 0, 'Julio Las Piñas St., Brgy. Villamonte, Bacolod City', '476-2590/09284495903/09296291246', '', '', '', 'COD', '', 'Ms Gelyn/Sir Carlito', '', 'Active', '1.00', 1, ''),
(238, 'Simplex Industrial Corp.', 'Mechanical Seal, Packings, O-rings, Gaskets, Coupling, Insulation Materials, Heavy Equipment (Forklift), Hydraulic Hoses', 0, 'Tiffany bldg., Door 8, Gonzaga St., Bacolod City', '(0932)878-8882, (0925)868-8882, 432-3760', '', '', 'salesbacolod@simplex.com.ph', 'COD', 'Distributor', 'Mr. Jonathan J. Radan Jr. - 0916-476-1333, jonathan.radan@simplex.com.ph', '', 'Active', '1.00', 1, ''),
(239, 'SKT Saturn Network, Inc.', '', 0, 'SKT Compound, Rizal St., Bacolod City', '433-2494', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(240, 'Sol Glass And Grills', '', 0, 'Rosario Heights, Libertad Ext., Brgy. Taculing, Bacolod City', '(034) 213-3935 / 0917-5039-183', '', '', '', 'COD', '', 'Mr. Karl Bryan Solinap', '', 'Active', '1.00', 1, ''),
(241, 'Specialized Bolt Center and Industrial Supply Inc.', 'BOLTS 1. Hub Bolt, Stud Bolt, Propeller Bolt, Center Bolt, Track Shoe Bolt, Plow Bolt w/ Nut, G. I. Battery Bolt w/ Wing Nut, Battery Terminal & Lug  ALLEN SOCKET SCREWS - High Tensile 1. Allen Socket Head Cap Screw, Allen Socket Head Set Screw, Allen Square Head, Alen Button Head, Allen Flat Head Socket Screw, Allen Wrench Key  U-bolt for Cars & Trucks, U-bolt for Pipe (g. I./Stainless), Copper Washer,  Plainwasher (B. I./G. I./Stainless/Brass), Lockwasher (B. I./Tetanized/Stainless) Conewasher, Plastic Tox, Drill Bit,  HARDWARE ITEMS & INDUSTRIAL TOOLS Grinding Stones, Cut-off Wheels, Depressed Center Wheels, Diamond Cutting Wheels, Carbonatum Grindings Wheels, Sanding Disks, Sand Papers, Steel Wheel Wire Brushes, Steel Wire Brushes, Cup Brush, WD-40 Penetrating Oil, Pillow Blocks,  Post Straps, Safety Helmet, Safety Googles-TW, Cotton Gloves, Cable Ties, Steel Tapes, Drill Chucks, Spark Plug Wrench, Cross Wrench, Oil Filter Wrench, Box Wrench, Open Wrench, Combination Wrench, Pipe Wrench, Adjustable Wrench, Socket Wrench, Impact Socket Wrench, Quick Ratchet Handles, Flexible Handles, Vise Grips, Combination Pliers, Diagonal Side Cutter Pliers, Putty Knife (with & without handle) Welding Machine (AC & DC), Welding Rods, Welding Lens, Welding Masks, Welding Gloves, Welding Electrode Holder, Welding Cables, Bronze Rode, Copper Tubes, Tool Bits, Twist Drill Bits, Masonry Drill Bits, End Mills, Hand Taps, Hacksaw Frames, Hacksaw Blades, Handsaw (PVC Handle)  GENERAL & INDUSTRIAL HARDWARE Tri-Squares, Aluminum Levels, Cement Trowels, Ball-Plen Hammers, Arm Pullers/ Bearing Pullers, Vital-Chain Blocks, Vital-Lever Blocks, Bench Grinders, Hole Saw Set, Tin Snips, Gate Valves, Ship Chains, Stainless Hinges, Heavy Duty Padlocks, Ordinary Padlocks, Electrical Tapes, Teflon Tapes, Masking Tapes, Packaging Tapes, Glue Guns, Water Gun Nozzles, Spray Guns, SKS-Sliding T-Handles, SKS-Drill Press Vise, SKS-Tap & Die Set, SKS- Feeler Gauge, SKS-Screw Extractor Set, SKS-Heavy Duty  Cross Vise, SKS-Hydraulic Jacks, Pioneer-Epoxy Tubes, Pioneer-Mighty Gaskets, Pioneer- Contact Bonds, Pioneer-Elastoseals, Pioneer- Marine Epoxy, Pioneer- Non Sag Epoxy, Aluminum ladders, Foldable Flatform Carts, Slotted Angle Bar Corner  Plates, Steel Footers, Plastic Footers, Caster Wheels.  PRODUCT LINES VALVES Ball Valve, Gate Valve, Check Valve, Wye Strainer, Butterfly Valve, Globe Valve,  PIPES Stainless Pipes, B. I. Seamless Pipes, Superior Pipes, G. I. Pipes, B. I. Pipes  FLANGES Stainless Slip on Flange, Stainless Blind Flange, B. I. Slip on Flange # 150 & #300 B. I. Blind Flange # 15 & #300  STEEL PLATES, SHEETS & BARS Mild Steel Plates 4''x8'' Mild Steel Plates 6''x20'' Mild Steel Plates 8''x20'' B. I. & G. I Sheets Angle Bars Flat Bars  FITTINGS Butt-Weld Elbow, Butt-Weld Concentric Reducer, Butt-Weld Tee, Welded Cap, Stub End, Elbow Threaded, Coupling Threaded, Coupling Reducer Threaded, Bushing Reducer Threaded, Bushing Reducer Threaded, Tee Threaded, Cross Tee Threaded, Hex Nipple, Nipple Threaded, Cap Threaded, Union Trhreaded, plug Threaded, "MECH" US Elbow 90 degree Threaded, Elbow 45 degree Threaded, Straight Elbow 90 degree Threaded, Tee Threaded, Caps Threaded, Elbow Reducing  Threaded, Coupling Threaded, Plugs Threaded, Plugs Threaded, Reducing Socket/ Coupling Reducer Threaded, Union Threaded, Tee Reducing Threaded, Hexagonal Bushing, B. I. Butt-Weld Sch 40 & 80, Elbow 902 degree and 40 degree Concentric Reducer Tee.  THREADED ROD Hi-Tensile Threaded Rod, Stainless Threaded Rod, Galvanized Threaded Rod  EXPANSION BOLT Bolt Anchor, Dyna Bolt, Drop in Anchor, Cut Anchor, Hit Anchor, Wedge Anchor,  FISCHER PRODUCTS Fiscer Threaded Rod, Fischer Expansion Bolt, Fischer Resin Capsules, Fischer  Sleeve Anchor, Fischer Foams & Sealants  SCAFFOLDING CLAMPS Swivel Clamp, Fixed Clamp  ALL TYPES OF SCREWS Job Screw, Hardiflex Screw, Self Tapping Screw, Gypsum (Drywall) Screw for Wood  & Steel, Teckscrew for wood & Steel, Tekscrew for stainlee steel, Metal Screw (G. I./Stainless), Wood Screw (G. I./B.I. Stainless), Confirmat Screw, Wafer Screw Tekscrew Adaptor, Hand Riveter, Screw Bit, Screw Extractor, Blind Rivets (Aluminum/ Stainless), E Clip, Internal Circlip, External C. Clip, Spring/Dowel Pin, Cutter Pin (G.I./Stainless), Hose Clamp, Shackle, Cable Clip, Turn Buckle, Eye Bolt, Pipe Fittings, Structural Steel', 7, '11 V. Sotto, Cebu City, Cebu', '(032) 2531345 / 253-1535', '', ' (032) 239-7705 / 255-7681', 'specialized_bolt@yahoo.com', 'COD', 'Distributor', 'Ms. Janeth/Mr. Ramon', '', 'Active', '1.00', 1, ''),
(242, 'State Motor Parts Company', '', 12, 'Gonzaga Street, Barangay 24, Bacolod City', '(034) 433-1683', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(243, 'Sugarland Hardware Corp.', 'Paints', 4, 'Lacson St., Bacolod City', '434-5390; 434-4549; 708-8850', '', '433-9748', '', 'COD', 'COD', 'Ma/am Sara / Ma''am Nimfa', '', 'Active', '1.00', 1, ''),
(245, 'Sunrise Marketing', '', 0, 'Bldg./Street: Hilado Extension\r\nMunicipality: Bacolod City ', '434-5746', '', '435-1067', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(246, 'Svtec Industrial Enterprises', 'Hardware, Tools, Equipment', 0, 'Gonzaga-Lacson St., Bacolod City', '(034) 707-7496', '', '', '', '30 days PDC', '', 'Mr. Benjie ', '', 'Active', '1.00', 1, ''),
(247, 'Technomart', '', 11, '(034) 431-5994', '9322065585', '', 'technomart.smbacolod@yahoo.com', '', 'COD', 'COD', '', '', 'Active', '1.00', 1, ''),
(248, 'Teranova Computers', 'Computer Supplies and Accessories, Printers', 11, 'G/F Cineplex Building, Araneta St., Bacolod City', '(034) 435 - 7227 / 709 - 7737/ 0999-817-4815 / 0942-009-1433', '(034) 435 - 7227', '435 - 7227', 'teranova_computers@yahoo.com', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(250, 'Tingson Builders Mart', '', 4, '3 Gonzaga, Bacolod City', '434-1046; 707-5507', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(252, 'Alpha Titan Hardware', 'Hardware, Tools, Welding Machine', 2, '888 Chinatown Square, Gatuslao St.', '435-7496; 476-4106', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(255, 'TMVG Multi-Sales, Inc.', 'INSTITUTIONAL COMMERCIAL/INDUSTRIAL CHEMICALS Air Freshener/Deodorizer, All Purpose Liquid Cleaner, Biocide & Algaecide,  Carpet Shampoo, Deodorant Spray/Cake Liquid, Diswashing paste/Gel, Drain & Clog Openers Liquid, Food Services Degreaser, Fabric Softener, Glass Cleaner, Heavy Duty Matisurface cleaner, Hospital-Strength Disinfectant cleaner, floor wax, liquid hand soap, Marble Cleaner (Non-acid), QuatDisinfectant (Lysol), Non-acid disinfectant bathroom cleaner, Oven Grill Cleaner, Powdered Detergent Soap, Sanitizer, Spot & Stain Remover, Tile & Bowl Remover, Toilet Bowl Disinfectant cleaner, wax stripper.  JANITORIAL TOOLS/PARTS & SAFETY SHOES PRODUCTS/FIRE FIGHTING EQUIPMENTS Safety Shoes (Hi-cut, Low-cut), Face Shields, Goggles, Helmet, Aprons (Maong, Leather), Glove (Laong, Leather), Masks (Half mask/Full face), Waste Cotton/Remnants, Deck Brush, Floor mops/Rugs, Floor Brush (Wilson Universal)  LAUNDRY WASH/CLEANING CHEMICALS (WHITE WASH BRAND) Bleach Powder (Salinox), Brighter (Sourex), Chlorine, Fabric Softener (Sanisoft), Machine Wash (Laundry), Oxalic, Oxygen Bleach, Dishwashing Paste, Powder Degreaser (Breaker), Rust & Stain Remover (Vista), White Wash All Purpose (Powdered Detergent)  SWIMMING POOL CHEMICALS/EQUIPMENTS/PARTS Algaecide, PH adjuster, Filter Aid (Impt/Local), Perlite, Chlorine Table/Powder, Dry Acid, Bio-Blue (Pool Clarifier), Copper Sulfate, Telescope Handle, industrial Motor Pump (Hayward/ Purex/Challenger and parts), Filter Assembly (Titan/Hayward/Challenger & Parts), Vacuum  Head & Parts, Brush, Leaf Scope, Stainex (Anti-Scale), Aluminum, Sulfate.  AUTO/AIRCON REPAIR/PARTS/ACCESSORIES Fedders, Carrier, Koppel, Daikin, Allen Aire / Amana, Expansion Valve, Refrigerant 12/ 134A/22/410/406/141B, Auto Evaporator (Original/Local), Condenser, All Types of Filter Drier, Compressor (Window, Ceiling Mounted, Packaged type, auto aircon & other industrial type.  3M PRODUCTS/COMMERCIAL CARE CHEMICALS 3M Floor Cleaning Chemicals for Vinyl/Marble, 3M Matting/Carpet Products, 3M Electrical Products, 3M Abrasives/Grinding/Cutting Disc, 3M Safety Products, 3M Floor Pads, 3M Industrial Cleaning Pads, 3M Home Care Products  INDUSTRIAL/PROCESS CHEMICALS Acetone, Acid Descaler & Chemicals Cleaner, Ammonia Water Strong, Ammonium Bilfluoride, Ammonium Sulfate, Anti-Sealant Chemicals for Distellery, Acid Dihibitor, Benzalkonium Chloride, Biocide, Borax Powder, Blackburn Products (Antifoam), Bleaching Earth, Calcium Hypochloride 70%/65%, Carbon Tetra Chloride, Carbon & Varnish Remover, Carboxyl Methyl Cellulose, Caustic Soda Flakes/Pearl, Cetyl Alcohol, Citric Acid, Citronela oil, Cocodietthanolamine (CDEA), Cooling Tower Treatment, Copper Sulfate, Defoamer (Silicone -blackburn-UK, Ethyl Alcohol, Ethylene Glycol, Glycerine, Garratt Callahan, Glacial Acetic Acid, Hydrogen Peroxide, Hydrochloric Acid, Isopropyl Alcohol, Isophropyl Myristate,Methylene Chloride, Methanol, Naphthalene, nitric  Acidtoluence, Oxalic Acid, Paradichlorobenzene, Perchloroethylene, Phosporic Acid (Food Grade), Polyammonium Chloride, Potash Alum, Propylene Glycol, Propyl Paraben, Rhodamine, Silicon Oil,  SLES, Stearic Acid, Soda Ash, Sodium Nitrate, Sodium Phospate Dibasic, Sodium Silicate, Sodium  Tripolyphosphate, Tergitol, Titanium Dioxide, Trichloroethylene, Triethanolamine, Zinc Oxide (Tech Grade), Soluble Oil  PREVENTIVE MAINTENANCE CHEMICALS Aircraft Runaway Cleaner, Algaecide (Algae Control), Aluminum/Coil Cleaner (aircon cleaner), Anti-Seize Compound (LPS), Belt Dressing, Brand Boiler Feed Water Treatment, Bunker Fuel Oil Additive (Conditioner), Carbon Descaler, Cold Galvanizing Compound, Cold Tank Degreaser, Contact Cleaner, Cooling Tower Water Treatment, Corrosion Preventive Compound, Cutting Oil & Coolant, Demoisturizer, Descaler & Cement Remover, Emulsified Degreaser, CIP Cleaning (Acechlor), Food Grease (USA), High Temperature Grease, High Pressure Lubricant, Industial Solvent-rated first, Insulating Varnish-(Red/clear)-3m USA, Kleenkote (Phophating Conditioner), Liquid Epoxy Coating,  Ozphose, Oven/Grill Detergent Cleaner, Paint Stripper, Paint & Varnish Remover, Penetrating Oil, Poo Powder Degreaser, Battery Terminal Cleaner, Radiator Coolant/Flush, Rubber Burns Remover, Rust Converter, Rust Remover, Safety Solvent Degreaser, Silicon Mold Release, Sludge Disperant, Stainless Steel Cleaner, Steam Cleaner Additive, Stoddart Solvent, Tile Cleaner, Dynaflux Crack Check Detector, Transformer Oil, Waterless Hand Cleaner, Water Soluble Degreaser, Wire rope chain Lubricant, Stainex (Swimming Pool), Bio-Blue (Swimming), PH Adjuster (Swimming Pool).  HARDWARE TOOLS/EQUIPMENTS & OTHER SPECIALIZED TOOLS Air Tools (Uryu-Japan), Rigid-USA, Stanley USA (Original), Proto-USA, Metabo Tools (Adaptable to 3M Combi-Brush), Black  Decker.  TOOLBIT ASSAB 17 SWEDEN Round Bit, Square Bit, Cut-off Bit  PRESSURE GAUGES/THERMOMETER Weis/Weksler/Kunkle/Watts/Marsh/Ametek/Insa/Ashcroft Made in USA, Wika-Germany, NH-England  VALVES Fairbanks USA, Henry-USA, Schmidth, Fairfortune-USA, MC Rayann-USA, Armstrong   LABORATORY CHEMICALS/EQUIPMENTS Ajax-USA, Pyrex/Duran-USA, Kimax  XIV Ertalon Round Rod, Sheet, Tubular, Polycarbonate Plastics, PPE', 9, 'Dr. 2, Genito Bldg., Lopez Jaena St., Bacolod City', '(034) 708-1819 / 434-7471 / 435-6003 / 476-4355 / 435-0905', '', '(034) 434-7471;', 'chemtrustunlimiteds@yahoo.com.ph', 'COD', 'Distributor', 'Engr. Norlene A. Amagan', '', 'Active', '1.00', 1, ''),
(256, 'Tokoname Enterprises', 'Lumber, Hardware, Construction Supplies', 0, 'Hernaez St., Bacolod City', '433-3610/707-1844', '', '', '', 'COD/7 days', '', '', '', 'Active', '1.00', 1, ''),
(257, 'Tri-con Marketing Center Inc.', '', 4, 'Capitol Shopping Ctr, Bacolod City', '435-0889', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(259, 'Triumph Machinery Corporation', 'Pumps', 2, 'Bacolod City', '(034) 441-0298', '', '', 'trimcorsales@trimcorph.com', 'COD', 'wholesaler / Retailer', '', '', 'Active', '1.00', 1, ''),
(260, 'U.S. Commercial', '', 12, 'Gatuslao Street - Purok Bagong Silang, Barangay 13, Bacolod City', '(034) 433-1174', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(261, 'Unikel Industrial Supplies and Safety Equipments', 'PPE, Fire Protection System', 8, 'Door 2 G/F Malayan Bldg, 3rd St Lacson, Bacolod City', '476-3191; 435-8677', '0917-703-9797; 0932-864-5350', '', 'unikelenterprises@yahoo.com', '', '', 'Kristel Curbilla', '', 'Active', '1.00', 1, ''),
(263, 'Union Galvasteel Corp', 'Roofing Materials', 1, 'Soliman Bldg, Bacolod', '435-7175', '', '435-7175', '', '', '', 'Jessica', '', 'Active', '1.00', 1, ''),
(264, 'United Bearing Industrial Corp', 'PRODUCTS LINE/S OR SERVICES OFFERED 1. Exclusive Distributor of: NSK Bearings Japan - All types of Bearings 2. FHY Bearings - Exclusive Distributor of Bearing Units All Types 3. Emerson Power Transmission Products (authorized distributor) 4. BEGA Maintenance Tools and Induction Heaters - Made in Holland 5. Federal Mogul Products - National Oil Seal, BCA-Bower, Champion Spark Plugs 6. UBC Bearings - Exclusive Distributor', 4, 'AP Bldg Lacson St, Bacolod City', '(034) 435-4541 / 435-4497', '', '(034) 434-1218', 'sales@unitedbearing.com', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(266, 'United Steel Technology International Corp.', '', 1, 'Door 2, Goldbest Warehouse, Guzman St., Hibao-an, Mandurriao, Iloilo City', '(033) 333-7663', '0917-811-7663', '', 'info@steeltech.com.ph', '', '', '', '', 'Active', '1.00', 1, ''),
(267, 'US Commercial Inc (Uy Sian Commercial)', '', 1, 'Gov V M Gatuslao, Bacolod City', '434-8989; 433-8017', '', '433-8015', '', '', '', '', '', 'Active', '1.00', 1, ''),
(268, 'VCY Sales Corporation (Lubricants)', 'Lubricants', 0, 'Lacson Ext., Bacolod City', '432-0180', '', '', 'fay.carvajal@vcygroup.com', '30 days PDC', 'Wholesaler / Retailer', 'Ms. Fay Carvajal', '', 'Active', '1.00', 1, ''),
(269, 'Vendor 1', '', 0, 'Vendor 1 address', '1111', '', '2222', 'vendor@email.com', '', '', 'test', '', 'Active', '1.00', 1, ''),
(270, 'Visayan Construction Supply', 'Hardware / Construction Supplies / Consumables / Electrical / Paints / Pipe Fittings / Tools / Equipment', 0, 'Lacson St., Bacolod City', '434-7277 / 213-6200 / 434-7278 / 431-1375', '', '434-5537', '', 'COD', 'Wholesale / Retail', '', '', 'Active', '1.00', 1, ''),
(271, 'Vosco Trading ', '', 0, 'Cuadra St., Bacolod City', '(034) 435-8515', '', '', '', 'COD', '', 'Mr. Silver/Sir Jam', '', 'Active', '1.00', 1, ''),
(272, 'Wellmix Aggregates Inc', 'Ready Mix Concrete', 0, 'Ralph Townhouse, Bacolod City', '(034) 434-4704', '', '', '', '', 'Manufacturer', '', '', 'Active', '2.00', 1, ''),
(273, 'Western Hardware', '', 4, 'EH Bldg., Lacson-San Sebastian Sts., Bacolod City', '434-5305-06', '', '435-0808', '', '', '', 'Gee Belita', '', 'Active', '1.00', 1, ''),
(275, 'Westlake Furnishings Inc.', '', 0, 'Araneta St.,  Bacolod City', '(034) 433-9489/433-9498', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(276, 'Yousave Electrical Supply', 'Electrical Supplies', 3, 'Door #s 1-2 Sunstar Bldg., Hilado Ext., Bacolod City', '709-0594', '', '431-3050', 'yousave.electrical@yahoo.com.ph', 'COD', 'Wholesale / Retail / Distributor', '', '', 'Active', '1.00', 1, ''),
(277, 'Alta-Weld, Inc. / Alta (Far East) Welding Materials, Inc.', 'Welding Rod', 0, 'Sun Valley Drive KM. 15 West Service Road, South Superhighway, Parañaque City', '(02) 823-4032 / 824-2966 / 824-2988 / 0917-636-1187 / 0922-625-6397', '', '(02) 821-1782', 'altaweld@compass.com.ph / leointes@yahoo.com.ph', 'COD', 'Distributor', 'Mr. Leo C. Intes', 'Home Based Office / Some Products are Manila Stocks', 'Active', '1.00', 1, ''),
(278, 'Chokie Heavy Equipment Parts Center', 'Heavy Equipment Parts', 0, 'AGPA Bldg. Lacson St., Bacolod City', '(034) 431-5303 / 0925-866-2081 / 0942-072-6467', '', '', '', 'COD', 'Distributor', 'Arnel B. Altiche - 0995-612-1929', '', 'Active', '1.00', 1, ''),
(279, 'Hydrauking Industrial Corp.', 'PRODUCT LINES: 1. ENERPAC - Hydraulic Torque Wrenches, Jacks, Pumps, Bolt Tensioner. 2. TK SIMPLEX USA - Hydraulic Tools & Equipment (Jacks. Pumps) 3. POSI-LOCK PULLER - Hydraulic & Mechanical Puller 4. TORC LLC - Torque Wrenches (Made in USA)  SERVICES OFFERED: Bolting,/Torquing & LiftingSpecialist (on-site) Service/Repairs of Hydraulic Tools & Equipment Rentals of Jacks, Pumps, Torque Wrenches, Bolt Tensioners, Pressure Gauges, Pullers, Pipe Benders, Hydraulic Punch, Heavy Duty Load Skates Sets, Hydraulic Cutters, Presses, Manual Torque Multipliers, Rigid Steel Hydraulic Torque Wrenches, Ultraslim Cassettes, Pneumatic Torque Wrenches, Electric Torque Wrenches, Torque Wrench Pumps,  Hydraulic Bolt Tensioners & Tensioning Pumps, Flange Maintenance Tools, Hydraulic Nut Splitters, Jack-Up Systems, Telescopic Hydraulic Gantries, Strand Jacks, Synchronous Hoisting, Skidding System, Modular Transporter, Climbing Jacks, Split Flow Pumps, Synchronous Lifting Systems, General Purpose Cylinders, Cylinder Base Plates, Low Height Cylinders, Hollow Plunger Cylinders, Ultra-Flat Cylinders, Cylinder-Pump Set, Power-Box, Maintenance Set, Pull Cylinders, Spreader Cylinders, Aluminum Cylinders - lightweight, Double-Acting Cylinders, Specialty Lifting Tools, Steel Bottle Jacks, Pow''r-Riser Lifting Jacks, Pow''r Lock Portable Lifting System, Aluminum and steel Jacks, Enerpac High Tonnage Cylinders, Enerpac High Tonnage and Telescopic Cylinders', 0, '4659 & 4661 Arellano St., Palanan Makati 1235', '(032) 340-6467 / 514-7901 / 514-7902 / 0928-828-2878 / 0905-228-4345', '', '', 'rose@hydrauking.com / cebu@hydrauking.com', '30 days', 'Distributor', 'Ms. Mary Rose Remes', '', 'Active', '2.00', 1, ''),
(280, 'Ionic Impact One Nation Industrial Corporation', '', 0, '6-D Pearl St., Golden Acres Subd. Las Piñas City', '(02) 800-9104 / 806-2048 / 805-2959 / 0977-824-5812', '', '', 'impactonenation@gmail.com', 'COD', 'Distibutor', 'Mr. Rossano Del Castillo - 0906-758-4638', '', 'Active', '1.00', 1, ''),
(281, 'Cebu Champion Hardware and Electric Depot, Inc.', 'Industrial Control & Automation, Water System, Fire Protection System, Pnuematic, Ebara Pump & Motor, Omron, Inverter Ready, Abb, Baldor Automation Products, E. F.G. Willem Piping System, Duvalco, Dutch Valve Company, Tozen Valve/Gate/Strainer, 3M, KSB, Stainless Steel And Aluminum Products, Water Work System, Fire Protection System', 0, 'Pres. Quirino St, Cebu City, Cebu', '(032) 234 4342 / 231-7139 / 0917-632-6505', '', '(032) 234-4342', 'info@cebuchampionhardware.com / felixgzn@yahoo.com', 'Advance Payment(Bank to Bank)', 'Distributor', '', '', 'Active', '1.00', 1, '004-443-424-000'),
(282, 'FH Commercial Inc.', 'James Walker, Henkel Loctite MRO Products, Lamons, Teadit, Klinger, Panam Engineers ltd. , Mcallister Mills', 0, 'FH Building, #22 Anonas Rd., Potrero, Malabon City, 1475', '(02) 362-2265 / 330-2019 / 330-2021 / 366-8598 / 361-4235 / 364-3352 / 0918-922-0974', '', '(02) 361-3759 / 366-7724', 'indayaeriejoy@yahoo.com', 'COD', 'Distributor', 'Ms. Suzette A. Espera', '', 'Active', '1.00', 1, ''),
(283, 'A & M Medcare Products Distributors', 'Medical Supplies', 0, ' Door 4 & 5, Estban Building, 5 Lacson St, Barangay 17, Bacolod City', '(034) 433 5728', '', '', '', 'COD', 'Distributor', '-', '', 'Active', '1.00', 1, ''),
(284, 'Archi Glass & Aluminum Supply', 'Aluminum and Glass Supplies', 0, 'P Hernaez St Ext, Bacolod City', '(034) 433 7116', '', '', '', '50% Downpayment, 50% upon completion', 'Installer', 'Ms. Evelyn', '', 'Active', '1.00', 1, ''),
(285, 'Bacolod Electrical Supply', 'Electrical Supplies', 0, 'Gonzaga Corner Lacson St., Bacolod City', '(034) 434-0526', '', '(034) 433-7238', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, '104-068-106-0000'),
(286, 'Morse Hydraulics System Corp.', 'Manuli Hydraulics, Rexroth, Bosch Group, Eaton Vickers, Danfoss, Hydro Control, Stauff, Kpm, Kawasaki Precision Machinery, Seal Maker, Graco, Jsg Industrial System/Muster II Fire Suppression Systems, Hy-Lok, Alfagomma Industrial Hoses, Nacol Accumulator, Poclain Hydraulics, Brevini Group, Rexpower, Whitedriveproducts, Ashun, Bva Hydraulics, Paccar Winch Division, Braden Carco, Gearmatic, Engineering Solutions, Hydraulic Trainer/Simulator, Power Unit Submerged Type, Power Unit w/ Enclosure, Power Unit With Spare Unit, Stackable-Type Power Unit, Accumulator Type Power Unit', 0, 'DMC Bldg., Narra Ext. Bacolod City', '(034) 433-1538 / 0917-633-9634', '', '(034) 435-2588', 'morsehsc@salgroupco.com', 'COD', 'Distributor', 'Ms. Jean', '', 'Active', '1.00', 1, ''),
(287, 'JHM Industrial Supplies', 'Products: Generating Sets: Brand New And Slightly Used, Generator Parts & Accessories, Synchronizing and switch gear, Automatic Transfer Switch (ATS), Automatic Voltage Regulator (AVR), Meter Water, Automatic Trickle Charger, Transformers, Hydraulic Pumps, Hydraulic & Pneumatic Tools, Heavy Equipments, Mill Supplies, Marine Parts, Chemicals. Services: Electrical Design & Installation, Preventive Maintenance & Repair, Engine Overhauling, Rewinding Generator & Electric Motor, Repair & Rewiring of Motor Control, Troubleshooting of any kind of Generator Set, Calibration of Woodward Governor, Generation Protection Relay. Others: Globe Valves, Flange, Installation of CCTV Systems', 0, 'Gov. Rafael Lacson St., Zone 12 Talisay City, Negros Occidental', '0949-846-7820 / 0923-568-3661', '', '', 'jhm_industrial@yahoo.com', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(288, 'Negros GT Group', 'Bosch dealer, Drill, Impact Drill, Cordless Screwdriver, Corded Screwdriver, Cordless Drill Driver, Cordless Impact Driver, Cordless Impact Drill, Battery with Charger, Cordless Freedom Tool (Batteries/Charger Not Included), Rotary Hammer, Demolition Hammer, Demolition Hammer Breaker, Straight Grinder, Small Angle Grinder, Large Angle Grinder, Polisher, Bench Grinder, Cutt-off Saw, Compound Mitre Saw, Sliding Mitre Saw, Jigsaw, Circular Saw, Sander, Planer, Trimmer, Plunge Router, . Table Saw/stand, Heat Gun, Blower, Wet & Dry Vaccuum Cleaner, High Pressure Cleaner, Laser Rangefinder, Line Laser, Point & Line Laser, Surface Laser, Detector, Optical Slope, Angle Measurer, Digital Inclinometer, Tripods, Levelling staff, Metal Drill Bits, Masonry Drill Bits, Chisels, Bonded Abrasives, Diamond Cutting Disc, Coated Abrasives, Jigsaw Blades, Planer Blades, Sabre Saw Blades, Screwdriver Bits, Chuck Keys, Bi-mtel Hole Saws, Router Bits-1/4', 0, '159-161 Lacson St., Bacolod City', '(034) 434-6154', '', '(034) 433-4983', '', 'COD', 'Distributor', 'Mr. Benjamin G. Sy Jr.', '', 'Active', '0.00', 1, ''),
(289, 'Powersteel Hardware', 'Structural Steels', 0, 'Coastal Road, Brgy. Banuyao, Lapaz, Iloilo City', '(033) 330-3792 | (033) 329-4484', '', '(033) 330-3867', 'sales_iloilo@powersteel.com.ph', 'Advance Payment (Bank to Bank)', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(290, 'Propmech Corporation', 'CAT MARINE POWER ENGINEERING SERVICES-Parts Supply, engine, transmission, generator & waterjet servicing, marine products-transmissions, waterjets, electric generators, marine products engineering services-install, repair, troubleshoot, automation products-control systems (dcs), programmables controllers (pcls), supervisory control, data acquisition system (scada), industrial networking solutions, motor control, field instrumentation, automation engineering services-design consultation, instrumentation detailed design, control panel design and building, plc/scada,dcs programming, industrial networking design, motor control center design & installation, upgrade/migration of control system, project management. COASTAL REFRACTORIES-Dense and Insulating Castables, Low & Ultra-low Cement Castables, No Cement Castables, Super-Duty Plastic Mouldables, Refractory and Ramming Mixes, Super Bond Mortar (Wet Type). EXLUSIVE DISTRIBUTOR OF: ACUMEN SEALS-Cartridge Single Mechanical Seals, Cartridge Double Mechanical Seals, Single Spring Mechanical Seals, Multi-Spring Mechanical Seals, Metal Bellows Mechanical Seals, Customized Mechanical Seal Design. DISTRIBUTOR OF: PSP-Zero leak Pump Technology for Sugar and Chemical Plant, Positive Displacement Pumps, Chemical Pumps, Gear Pumps. THERMAX: MULTI-FUEL CAPABLE BOILERS-Coal-Fired Boilers, Biomass Fired Boilers, Spent-Wash Fired Boilers, Gas-Fired Boilers, AFBC/CFBC Design, Travelling Grate Design. POWER DIVISION: EPC CAPABILITIES IN-Biomass Based Power Plants, Pulverized Coal-based power plants, AFBC/CFBC boiler based power plants, Waste Heat recovery boiler based power plants. AIR POLLUTION CONTROL EQUIPMENT-Electro-static Precipitators, Bag Filters, Flu De-sulphurisers, Scrubbers, Dust and Fume Extraction System, Spare Parts. CHEMICAL DIVISION-ion-Exchange Resins, Waste Water Treatment Chemicals, Boiler Water Treatment Chemicals, Cooling Tower Treatment Chemicals, Fireside Treatment Chemicals, Fuel Treatment Chemicals. OTHER SERVICES OF ASSISTCO:-Furnace lining design & installation, building and erection, Boiler & heater repair, hot & Cold Insulation for Vessels & pipelines, ceramic fiber & firemaster installation, Refractory Castables & Firebricks Installation, Civil Construction. Mechanical Seal & Pump Repair. PRODUCTS-Mechanical Seals, Insulation-Kaowool Ceramic Fiber Blanket, Mineral Wool Blankets, Calcium Silicate Blocks, ACUMEN Seals, LTD: PSP Pumps, THERMAX-Ion Exchange Resin, LARS Enviro. SERVICES-Furnace Lining Designs & Installation, Building & Erection Boiler & Heater Repair, Hot & Cold Insulation for Vessels & Pipelines, Ceramic Fiber & Firemaster Installation, Refractory Castable & Firebricks Installation, Civil Construction, Mechanical Seal & Pump Repair', 0, 'J. king Warehouse, M. Sanchez St., Alang-alang, Mandaue City, Cebu', '(032) 344-0738', '', '(032) 344-0624', '', 'COD', 'Distributor', '', 'The leading local supplier of CAT Marine Production Propulsion Systems as well as primary & auxiliary electrical power installations. We''ve supplied & supervised the installation of propulsion systems in naval, coast guard, ferry service, yachts, fishing boats and many types of work vessels Philwide. The owners/operators of these vessels have recognized the numerous advantages indealing with us as a  single-source supplier of engines, parts & services.', 'Active', '1.00', 1, ''),
(291, 'Assistco Energy & Industrial Corporation', 'Conventional Castables, Light Weight Insulating Castables, Low & Ultra Low Cement/Self Flow Castables, Silicon Carbide (sic) Low Cement Castables, Plastic Mouldables, Ramming Mixes, Bonding Mortar', 0, 'Alijis Road, Bacolod City', '(034) 435-1605', '', '(034) 435-1605', '', 'COD', 'Distributor', 'Engr. Ernie ', 'Engr. Ernie "Kurt" P. Fuentebella - 0928-636-9111; erniekurtf@gmail.com', 'Active', '1.00', 1, ''),
(292, 'Joules Enterprise & Engineering Services', 'MAIN PRODUCT LINES: Steam boilers and accessories, DISTRIBUTORSHIP: Grundfos pumps and donaldson ih filters, HONEYWELL CONTROL INSTRUMENTATION; indicators, controller, recorders. DANFOSS PRESSURE AND TEMPERATURE SWITCHES; pressure and temperature switches. MCDONNELL & MILLER; for steam boilers, MAGNETIC ROLLER DISPLAY; kuebler/finetec, SAFETY RELIEF BULB; kunkle, STEAMTRAP FOR STEAM BOILER; tlv, spirax sarco, adca, dsc, CONTOIL; fuel oil meter, SUNTEC PUMPS; fuel oil pump, fuel solenoid valves, burner fuel pumps. JEES STEAM BOILERS, HORIZONTAL STEAM BOILER, PLATE HEAT EXCHANGER, JEES VERTICAL. STEAM BOILER, BIOMASS STEAM BOILER. PRESSURE AND TEMPERATURE GAUGES; wika, ascroft, weksler. ASCO; for steam, air and fuel. ALIA; flowmeter transmitter indicator. ADCA, TLV, SPIRAX SARCO; control valves, prv, regulators. NICE; pressure, transducers, transmitters. WARREN; electric heater. BIOMASS STEAM BOILER; olympia, dunphy. Webster, etc. DIESEL ENGINE PARTS: Pistons, piston rings, valves, bearings, fuel injection pumps, o-rings. BRANDS: Sulzer, mak, wartzila, daihatsu, caterpillar, man b & w, mitsubishi, yanmar. PLATE HEAT EXCHANGER PARTS: PLATES AND RUBBER GASKETS: Alfa-laval, apv-invensys, tranter, hisaka, fisher and others. OIL-SEALS AND O-RINGS: Oil seal and o-rings in various sizes and materials such as viton, silicon, nbr, etc. Imported Or locally fabricated. OTHERS: Chiller and air compressors, pneumatics and instrumentations, sensors, electronic flowmeters And other equipment accessories. ENGINEERING SERVICES: Insulation and aluminum cladding of engine exhaust manifold, Insulation of pipeline from fuel line to engine. MAJOR ENGINEERING SERVICES: Installation, supervision, technical expertise and commisioning of major product line, Supplied by joules enterprise. This includes comprehensive training to plant personnel, Regarding the technical aspects of the supplied equipment, products and/or spare Parts, Steam boiler fabrication, rehabilitation and installation, Pumps and piping installation and pipe insulation, Refrigeration and air-condition services. Diesel Engine Parts (Sulzer, Daihatzu, SEMT Pielstick, Mitsubishi, Yanmar, Deutz), Fabrication and Machining Services, Printing Rollers, Crankshaft and Roller Grinding, Laser Alignment, Dynamic Balancing and Gear Fabrication', 0, 'G/F Unit 4, GA Esteban Bldg., Lacson St., Bacolod City, Negros Occidental', 'Bacolod-(034) 213-8574, 0923-171-3197, Head Office-(045) 458-0848: 0918-940-7243: 0917-919-5258, Vertec Marine - +6567468575/+6567467166', '', '(045) 322-4144', 'info@joulesengineering.com / jovenruby888@yahoo.com / jjm@joulesengineering.com / eddie@vertec.com.sg', 'COD', 'Distributor', 'Ms. Ruby P. Joven (Sales & Marketing Executive-Bacolod), Mr. Joel J. Manalang (President/CEO), Vertec Marine (Mr. Eddie Lim-Director)', 'Main Office Address: Jees bldg., Blk. 6 Lot 10 Doña Juana Cor. R. A. Canlas St., Springside, Pandan, Angeles City', 'Active', '1.00', 1, ''),
(293, 'Nexus Industrial Prime Solutions Corp.', 'SMC- air preparation equipment, flow control equipment, actuators/air cylinders, electric actuators/cylinders, fittings & tubings, directional control valves/solenoid valves. KOBELCO- oil flooded compressor, oil free compressor, group controller ecomild II, OMRON- covering the complete spectrum of control components and automation controls, relays, power supply, timers, digital panel meter, sensors, temperature, controller, push buttons, counters, variable frequency drives/inverter, encoders, hmi-human machine interface. lESER- api, high performance, compact performance, clean service, critical service, modulate action. WEIGHING SCALE- pioneer series, valor series, explorer series, defender series, single load cell flatform, ckw series. WIKA- pressure gauges, pressure gauges transmitter/switch, sf6 gas, air2guide, mechanical temperature, electrical temperature, electrical temperature, thermowells, diaphragm seals, high purity/ultra, high purity (uhp), pressure transmitters, high precision & calibration test. PROCESS VALVES- angle seat valves stainless/standard, butterfly valves, mounting type, 3-piece ball valves direct  mounting type, butterfly valves w/ actuators, ball valves with actuator, 3 way ball valves, ball valves flange type, general purpose valves, gate, knife, check valves.', 0, 'Unit B, Roselindees Building, Galo-Hilado St., Bacolod City', '(034) 435-0560 / 0928-5079-9741', '', '(034) 435-0560', 'sales-ceb@nexusindustrial.com.ph', 'COD', 'Distributor', 'Ms. Maricel Gumban - Sales Engineer', 'www.nexusindustrial.com.ph', 'Active', '1.00', 1, ''),
(294, 'AGEC Engineering Supplies', '1. Supply of API std. valves up to 12 inches 2. Supply of seamless brass tubes 1/2 inch to 3/4 inch diameter 3. Supply of SS perforated screen. 4. Supply of Flexitallic gaskets. 5. Supply of packings and gaskets. 6. Supply of engineering for up grading/retrofitting of boilers. 7. Supply of engineering in the fabrication of the following:       a. Juice Screening       b. Air and Gas dampers.       c. Oil Cooler (Tubular)       d. Tubular Heaters       e. AgriculturalFarm Implements       f. Rice and Cassava Mechanical Drier 8. Mechanical equipment installation & Mechanical Fabrication. 9. Piping and Duct Works. 10. REPAIR OF OIL COOLER 11. INDUSTRIAL SCREW CONVEYOR  PRODUCTS CONTROL VALVE, GATE VALE, STEEL SWING VALVE, BUTT WELD GATE VALVE, SEAMLESS BRASS TUBES, SS PERFORATED SHEET', 0, 'American Packing Ind., Mandalagan, Bacolod City', '0947-776-8124 / 0916-300-8019', '', '', 'cepe.andres@yahoo.com', 'COD', 'Distributor', '', 'email add - AVCengineeringandservices@gmail.com', 'Active', '1.00', 1, ''),
(295, 'Sealand Industrial Supply', 'INSULATION, ENGINEERING PLASTICS, INDUSTRIAL PLASTIC CURTAINS, INJECTABLE PACKING. PRODUCT LINES 1. Packings & Gaskets 1.a Non-asbestos & Asbestos 1.b Manhole/Handhole/Tadpole, Tools 2. Engineering Plastic 2.a Nylon, UHMW, Acetal 2.b Fibra, Polycarbonate (Rod, Sheets & Fabrication) 3. PVC Curtain-Clear, Ribbed & Anti-Insect 4. Preventive Maintenance Chemicals 4.a Lubricants, Grease, Cleaners & Oils 4.b Hi Heat Paint, Epoxy Pant, Floor Ceiling & Wall Coating 5. Metal Repair System 6. Insulation-Ceramic / Rockwool Blanket 6.a Fiberglass/Asbestos Cloth, Tape, Rope 7. Neoprene Rubber - Plain, Cloth & Nylon Insertion 8. Hydraulics- O ring Kit/Rubber Fabrication 9. Air Slide Canvass/Filter Bag/ Cloth 10. Stuffing Box Sealant 11. Teflon Products (Sheet, Rod, w/ Filler) 12. Labor & Materials of Expansion Joint', 0, 'Plazamart, Araneta St., Bacolod City', '0932-9034-564', '', '', 'bacolod@sealandindustrial.com / clarisseleria15@gmail.com', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, '');
INSERT INTO `vendor_head` (`vendor_id`, `vendor_name`, `product_services`, `category_id`, `address`, `phone_number`, `mobile_number`, `fax_number`, `email`, `terms`, `type`, `contact_person`, `notes`, `status`, `ewt`, `vat`, `tin`) VALUES
(296, 'EFRC Industrial Services & Trading Corp.', 'SERVICES OFFERED: 1. IN-Situ Machining, Crankshaft Grinding, Honing and Polishing 2. On-Situ Inspection of Ovality, Run-out, Deflection, Bend & (MPI=UV, DRY and WET  PROCESS) Magnetic Particle Inspection for Crank testing. 3. On site Straightening of Bend Crankshaft 4. In-situ Machining of Liner Seat (Landing Surface) 5. In-place Reboring & Resleeving of Bottom Liner Seat. 6. In-Place Machining of Slip Ring on Turbine for Hydro Electric Power Plant. 7. In-Place Vertical Reboring of tube sheet holes of vacuum pan for refinery & raw sugar 8. In-Place line boring of bearing saddle, rubber stalk etc. (using brand new sir mechanica-italia portable machine) 9. In Place Reboring of bearing housing for tyre roller of vertical raw mill, coal mill and shoe-slide guide of corliss engine 10. In-place Machining of Large Fillet Radius on grinding rollers for sugar mills & other similar machinery parts. 11. In-place Resizing of Dowel Bolt Hole on Coupling Falnge of Hydro Turbine & Other Machinery with Similar Job. 12. "Rotalign Ultra" Laser alignment check CRACK REPAIR BY METAL STITCHING (COLD WELD Process) 13. Cracked & Busted Engine Frame & Blco 14. Turbo Charger Casing 15. Ball Mill Trunnion Head & Large Gear 16. Cylinder Heads & Gear Boxes 17. Mill Cheek, Column, Mill Bed 18. Other Materials That are unsafe to weld OTHER SERVICES/TRADING & PARTS SUPPLY 19. Cladding 20. Casting (local) Fabrication of new cyl. Head & casing w/ warranty and fusion  welding repair  21. Stellitting of Valve Spindle and valve cage 22. Supply of Expansion Joint (Bellow) in any sizes 23. Bi-Metal & Tri-Metal Crankshaft Journal Bearing From Korea 24. Supply of new OEM/Surplus Marine Engine & Generator Engine Parts. 25. Repair & Fabrication of New Cooler, Heat Exchanger from singapore 26. Supply of ex-stock or made to order stainless & non-stainless ''ampo'' valves 27. Supply of imported vertical mill roller, table liner made by jung-won of korea that are locally distributed by union-lock ind''l & trading corp. for cement and power generation.', 0, '252 Dr. Jose Fabella St., Plainview, Mandaluyong City', '(02) 533-6673 / 0917-324-9530 / 0917-599-3366 / 0918-939-7962', '', '(02) 533-6673', 'efrcindustrial@yahoo.com', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(297, 'New Interlock Sales & Services', 'GRUNDFOS 1. SP-4 inches to 10 inches diameter Stainless-Steel Sub. Pumps 2. CR-Vertical Multi-stage centrifugal pump 3. MMS-Rewindable Submersible Motors 4. NB/NK-Horizontal Single Stage Pumps 5. SQ3 inches diameter Submersible Pumps 6. Hydro 2000-Booster Unit 7. DME/DMS-Digital Dosing 8. HS-Horizontal Split-Case Pump 9. Pressure Reducing and Control Valves 10. Soft-Starters and Variable Speed Drives 11. Electromagnetic and ultrasonic flowmeters 12. Resilient type gate valves and fittings', 0, 'Door # 3 NGS Bldg., M. J. Cuenco Avenue, Mabolo Cebu 6000', '(032) 2315-906 to 907; 412-8431; 412-8278 to 79', '', '(032) 2315-907', 'limsamben168@yahoo.com', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(298, 'Fil Generators And Services Company', 'PRODUCT LINES 1. Electric Diesel Generator Sets 2. Automatic Transfer Switch (ATS) 3. Synchronizing Panels 4. Automatic Voltage Regulator (AVR) 5. Generator Controls & Protection Devices/Gauges 6. Woodward Governors 7. Engine Lube oil, Fuel, Air & Water Separator/Filters 8. Autmatic Battery Float Charger 9. Generator Mechanical & Electrical Parts  SERVICES 1. Repair/Troubleshooting of Generator Sets 2. Electrical & Civil Works 3. Calibration/Repair of Woodward Governors 4. Generator and Motor Rewinding 5. Engine Overhauling 6. Engine Tune Up 7. Gen Set Installation & Commissioning 8. Gen Set Hauling 9. Preventive Maintenance 10. Radiator Repair/Overhauling 11. Installation & Commisioning of power generating equipments', 0, 'Door # 7, East Plaza Bldg., Circumferential Road, Brgy. Taculing, Bacolod City', '(034) 446-2674 / 0917-140-4763', '', '(034) 446-2674', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(299, 'Acster Marketing', 'SPECIALIST: Waterproofing & Retrofitting Services 1. Structural Epoxy Injection 2. Structural Re-enhancement Carbon Fiber Installation 3. Non-Shrink Structural Grout 4. Parex Davco/Lanko Putty & Waterproofing Products 5. Ultracote Industrial Epoxy, Paints & Coatings', 0, '128 Araneta St., Singcang, Bacolod City', '(034) 458-4077 / 0927-291-2209', '', '', '', 'COD', 'Contractor', 'Mr. Domingo Rodrigo Jr. (0918-784-5691; 0915-420-0971)', '', 'Active', '1.00', 1, ''),
(300, 'Mandaue Atlas Steel Fabrication Corp.', 'SERVICES 1. Steel Fabrications, Bending, Rolling, Shearing, Power Press, Machining Stainless, Aluminum, Galvanize, MS Plates, Brass 2. Tig Welding, Planer, Pipe Bending, Rolling, Dishing  1. Bending 1/2 inch capacity of steel plate (stainless or Mild Steel) 2. Cutting 1/2 inch capacity of steel plate (stainless or Mild Steel) 3. Rolling up to 2 inch capacity of steel plate (stainless or mild steel) 4. Welding using mig weld or tig weld on stainless or aluminum metals 5. Pipe Bending and Rolling up to 4 inch dia of B. I. Pipe or stainless pipe   6. Angle Bar of Flar Bar cutting and rolling 1/2 inch thickness 4', 0, 'Plaridel St, Paknaan, Mandaue City, Cebu', '(032) 505-1806 / 316-2364', '', '(032) 420-4646', 'matlas_steel@yahoo.com', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(301, 'YKG Industrial Sales Corp.', 'PRODUCTS 1. Aluminum, Brass, Copper & Stainless (Angle & Flat Bars, Shaftings, Rods, Tubes, Sheets, Pipes & Fittings) 2. Black Iron & Galvanized Iron Pipes 3. Cold Rolled and Tool Steel Shaftings (1045, 4140, 4340) 4. Channels Bars, I-Beams & H-Beams 5. Mild Steel Angle & Flat Bars 6. Mild Steel & Checkered Plates 7. Galvanized & Perforated Sheets 8. Purlins & Tubings 9. Tube & Pipe Fittings of all kinds 10. Ordinary and stainless Spring Wires 11. Bolts & nuts of all kinds 12. Welding Rods of all kinds 13. Industrial Valves', 0, '7-9 M. C. Briones St., Cebu City, 6000', '(032) 255-0870 to 73', '', '(032) 255-0873; 412-1908', 'ykgindustrial@gmail.com', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(302, 'Worldwide Steel Group, Inc.', 'PRODUCTS 1. Deformed Steel Bars, Plywood, Smartboard, Phenolic Boards, Holcim Cement, Angle Bar, Deformed Wire Rods, Steel Matting/Wire Mesh, C-Purlins, Metal Framings, Nails, Finishing Nail Wires, Corrugated Sheets, Umbrella Nails, G. I. Wire', 0, 'Sacris Road Ext., Mandaue Cebu 6014', '(032) 346-0959; 345-0458: 344-0660', '', '(032) 345-3748 to 49', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(303, 'Tokyu Hardware & Industrial Supply', 'STEEL ITEMS 1. BARS - deformed, angle, channel, flat, plain, square and c-purlins 2. STEEL PLATES - various sizes of mild steel, checkered, boiler, A. R. and A. B. S. plates 3. BEAMS - various sizes of wide flange, beams & I-beams 4. PIPES - B. I. & G. I., Aluminum, brass and copper 5. SHEETS - stainless, G. I., Alumuminum, Brass and Copper 6. SHAFTING - CR, tool steel and stainless 7. TUBE - B. I., stainless, square, rectangular and copper 8. FITTINGS - Sch 40 - 80, G. I.. B. I. & Stainless Fittings  INDUSTRIAL ITEMS 1. POWER TOOLS (AEG, MAKITA, POWER CRAFT) - percussion drill, rotary hammers, angle grinders, jigsaw, belt sanders, demolition hammer 2. PRECISION TOOLS - air compressors, hand chisel, REIN STAG, hot air gun, crimping tools, automotive tools, plumbing tools, CIGWELD, gas cutting outfit, LENOX 3. MACHINE TOOLS 4. TOOLING 5. CUTTING TOOLS 6. INDUSTRIAL EQUIPMENT  HARDWARE ITEMS 1. Drill Bit, Hand Tap, Tool Bit 2. Abrasive - Sand Paper, Cutting and Grinding Wheels 3. AIR COMPRESSOR - various CFM and model 4. CHAIN BLOCK - hydraulic jack, overhead hoist motor 5. HOSE - hydraulic, fire nylon, rubber and high pressure hoses 6. MOTOR - electric motors various type and model 7. WELDING ROD - Nihon Weld, Fuji, Wipweld & ferrocord 8. GASKETS - asbestos, rubber gasket, asbestos cloth, asbestos packing 9. WELDING MACHINE 10. CASTERS - various model and sizes 11. VALVES - various types and sizes  CONSTRUCTION ITEMS 1.PVC PIPES AND FITTINGS - various sizes type ATLANTA, MOLDEX, EMERALD, ESLON and NELTEX 2. V arious wires - galvanized, barb wires, cyclone wire, CW Nails and finishing nails 3. EXPANDED METAL and STEEL MATTING 4. BOLTS & NUTS - various sizes of ordinary bolt/nuts, stainless and high tensile 5. Various Paints - Dutchboy, Boysen and Island 6. Adhesives - Apollo, pioneer, rugby and vulcaseal 7. Cement, Plywood  SAFETY PRODUCTS 1. Hard Hat, Welding Mask, Dust Mask, Safety Shoes, Rubber Boots and Welders Coat 2. Safety Gloves - maong cotton, working gloves, leather, welding gloves, industrial and rubber gloves for chemicals  BRANDS CARRIED: 1. WELDING ELECTRODE - NIHONWELD, WIPWELD, FUJI and FERROCORD. 2. CUTTING OUTFIT - COMET and HARRIS brand including SMITH and WIPARC 3. Distributor of - MAKITA, DE WALT, TYROLIT, TAILIN, ULTRA, KEMMAFLEX & STARRETT 4. RIGID, STANLEY, YAMATO, KITZ, UNIOR, SKC, TONE, TAJIMA, and NICHOLSON', 0, '1175-9 Highway 77, Talamban 6000 Cebu City', '(032) 345-1500 / 345-0498 / 416-0088', '', '(032) 344-1344', 'info@tokyuhardware.com', 'COD', 'Importer, Wholesaler, Retailer', '', 'website: www.tokyuhardware.com', 'Active', '1.00', 1, ''),
(304, 'CJ KARR Industrial Sales And Services', 'LIST OF PRODUCT LINES, ERIKS, RANGE OF PRODUCTS EXCLUSIVE LINES (HOLLAND), Sheets Gasketing Materials, Compressed Asbestos & Non-asbestos gasket sheet, Fluid Sealing Rubber Gasket Sheet, Granulated & Rubberized cork gasket at continuous length of 45 meters, ERIK’S PRECISION O-RINGS: Nitrile Compound Heat and Oil resistant 105 deg. Cent., Maximum working temperature at continuous service, Viton Compound heat and oil resistant 230 deg. Cent., Maximum working temperature at continuous service, Silicone Compound Heat and oil resistant 230 deg. Cent., For High Temp. Fire, Oxygen, & heated water. Excellent for static application, ERIK''S, OIL SEALS AND V-RING SEAL, ENGINEERING PLASTICS, MECHANICAL SEALS, BALL VALVES, One (1) piece design, stainless steel reduced bore teflon seated, Three (3) piece design, full bore type 316 stainless steel teflon seated, 1000 PSI, SKF BALL & ROLLER BEARINGS/GREASES AND INSTRUMENTS, CROWN Chemicals (USA), Fault Finder Cleaner, Penetrant & Developer; Insulation Tester, Mold Cleaner, Gen., Purpose Silicone Mold ready release, Heavy Duty Silicone Mold Ready Release, Paintable Mold Ready Release, Zinc Stearate, Mold Release, Waterbase Cleaner/ Degreaser, Toolmaker''s Ink-blue, Kleer Kote, Battery Terminal coating, Rus Inhibitor, Penetrating Oil, Corrosion Suppressant/Formula 101, Food Safe Lubricant, Moly, Lift Truck grease, TFE Dry Film Lubricant, TFE Lubricant Permanent Film, Dry Moly Lube, Red Insulating Varnish, Prussian Blue Spot Indicator, Freon TF Degreaser, Cold Galvanizing Compound, Cutting Oil, Lithium Grease, Moly Oil, Open chain lube, HD open gear/wire rope, box-saver-tan & white, High Temp. Paint-Black & Aluminum, All 4 Moisture Displacing Lube, Welder''s Anti-Spatter Liquid, General Purpose Silicone Lube, Drive Belt Dressing, General Purpose adhesive, Heavy duty cleaner/ degreaser, electronic component cleaner, Freeze it, Anti-seize compound, tap tool Heavy Duty Cleaner, Off-line Contact Cleaner Devcon, Epoxies, Urethanes, Adhesives and Sealant Maintenance chemicals, Tig welding machines – gensiang, Expansion joints and bellows, Rnw pacific pipes (erw gi/bi pipes & fittings), Hyundai welding electrode', 0, 'Dr # 2, E & R Bldg, Hernaez Ext., Prk. Kabukiran, Taculing, Bacolod City', '(034) 709-0130 / 446-3843', '', '(034) 446-3843', 'cjkarr_bac@yahoo.com', 'COD', 'Distributor', 'Mr. Ramil Arquilola-Technical Sales Representative', '', 'Active', '1.00', 1, ''),
(305, 'Goldensteel Construction Supply', 'Wide Flange, Tubulars, Square Bar, Deformed Bars, GI/BI Pipes, Flat Bars, Light Metal Frames, C-Purlins, Round Bar, Angle Bars, Channel Bars, Pre-Painted Roofing, PVC Pipes, Cements, Pipe Fittings, Gate Valves, Check Valves', 0, 'G/Floor, Casals Building, Pagsabungan Mandaue City', '(032) 405-3262 / 0998-5394-560 / 0942-356-6747 / 0910-613-2888', '', '(032) 414-4584', '', 'Advance Payment-Bank to Bank Transaction', 'Distributor', 'Ms. Menia', '', 'Active', '1.00', 1, ''),
(306, 'RJ Spring Rubber & Metal Parts Manufacturing Corp.', 'Manufacturer of all kinds of spring, rubber, metal stamping and metal fabrication,  bended wire. SPECIALIZE in:  Compression, Torsion, Tension Springs etch, metal fabrications and engineering plastic fabrication, vulcanizing & molded rubber products.', 0, '#171National Road, Ortigas Ext., Brgy. Delapaz, Antipolo City, Rizal', '(02) 658-1951; 384-9315; 473-0433; 215-3069', '', '(02) 658-1987', 'sales@rjspringrubber.com', 'COD', 'Distributor', '', 'website: www.rjspringrubber.com', 'Active', '1.00', 1, ''),
(307, 'Moldex Products Inc.', 'O-ring Type PVC-U DWV Sanitary Piping System  PIPES  FITTINGS Bend 45 degree, Bend 87.5 degree, Single Branch Tee 87.5 degree, Double Branch Tee 87.5 degree, U-trap with plug, Single Branch Wye 45 degree, Double Branch Wye 45 degree, Double Socket, Eccentric Reducer, Clean out with plug, flat bottom o-ring, round bottom o-ring  Water Pipes and Fittings PE Fusion Pipe & Fittings All Purpose, Heavy-Duty, High Density Polyethylene Pipes PVC-U Heavy Duty Rigid Electrical Conduit PVC-U Flexible Electrical Conduit Fire Sprinkler Press System PVC Films PVC Pipe Cement Zero-toluene PVC-U Drain, Waste and Vent Sanitary Piping System', 0, 'Moldex Building., Ligaya St., Cor. West Ave., Quezon City', '(032) 373-8888 / 373-4009 / 0917-863-9237', '', '', 'sales@moldex.com.ph', 'COD', 'Distributor', 'Mr. Dennis Blanc-0917-863-9237', 'website: www.mpi.moldex.com.ph', 'Active', '1.00', 1, ''),
(308, 'Gibrosen Fire Safety Products', 'SERVICES 1. Installation Services-Contractor 2. Installation of Automatic Sprinkler System 3. Fire Alarm System 4. Fire Protection System 5. Kitchen Fire Suppression System 6. Fire Hydrant System 7. CCTV Alarm System 8. Fire Safety Products & Equipment 9. Industrial Products Equipments & Services 10. Manufacturer/Wholesaler/Retailer-GIBROSEN Fire Extinguisher', 0, 'Door # 2 Triple E''s Siasat Bldg., Burgos Ext., 4th Road, Villamonte, B. C.', '(034) 434-2881', '', '(034) 708-7299', '', 'COD', 'Contractor', '', '', 'Active', '1.00', 1, ''),
(309, 'Phil-Nippon Kyoei Corp.', 'EXCLUSIVE PRODUCT LINE 1. Boilers, Water Treatment and Chemicals 2. Fresh Water Generators 3. Pumps 4. Marine Diesel Engine 5. Industrial Valves 6. Anti-Sway Motor 7. Anchor Windlass                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        9. Heavy Equipments  GENERAL PRODUCT LINES 1. Marine Vessel and Auxiliaries 2. Diesel Generating Power Plants 3. Refrigeration Compressors, Insulating Panel and Racking System 4. Switches/Sensors 5. Pumps 6. Compressors 7. Purifiers 8. Boilers 9. Pipes & Fittings 10. Separators 11. Electrical Control 12. Turbo Chargers 13. Marathon Hose 14. Nautical Equipment  ENGINEERING SOLUTIONS & SERVICES Boiler Inspection, Analysis, Troubleshooting and repair, tube replacement, burner troubleshooting and repair, combustion control upgrade, feed water line, feed pumps, water softener, valves, steam line, safety valves repair and installation, refractory repair and replacement, chimney/flue and duct cleaning, boiler preventive maintenance.  Fresh water generator inspection, troubleshooting, repair & insstallation.  Hydraulic and Pneumatic System Design, Repair, Servicing and Installation of Hydraulic and Pneumatic Cylinders, Pumps, Motors, Power Units & Valves.  Repair and Maintenance of all equipment parts, spares and auxiliaries pertaining to power, petrochemical and marine industries.  Provide on-board performance testing using state of the art combustion analyzer to assess practical maintenance activities and replacement parts that reached service life.  Conduct a comprehensive energy audit of industrial plants and commercial building to align design and actual electrical load consumption. Corresponding practical  reccomendations will be advised the correct electrical recommendations thru workable maintenance and operations.  Repair and turn key, installation Refrigeration and Air-Conditioning Equipment.  Repair and Installation and Commisioning of Lube Oil/Fuel Oil Purifiers/Turbo Chargers/ Air Coolers  Supply, Turn Key, Installation and Commissioning of Power Plant.  OTHER PRODUCTS MECHANICAL Valves, Air Motors, Electric Chain Hoists, Hydraulic Power Units, Pressure Gauges, Flowmeters, Tachometers, Valves, Positioners, Valve Mounted, Process Controls, Control Systems, Air Filters Regulators, Lock-up Valves, Transfer Valve Regulator, Reducing, Back, Liquid Pressure and Differential Pressure Controls for Steam, Gas Services Signal Conditioning, I/P for Field Installation Switches.  INSTRUMENTATION & ELECTRICAL Level Switches, Sensors, Cables and Accessories, Temperature Transmitter Circuit Breakers, Servo Motors, Thermometers and Well Temperatures, Thermocouples, Thermo Elements, Temperature Gauges, Switches, Pressure Switches, Volumetric Flow Meters, Oxygen Analyzers, Ultrasonic Transmitters, Mill Roll Lift Detectors, Dial Thermometers, Torque Anti-Sway Motors, Level Transmitters, Reconditioning of Nozzle Tips, Plungers and Barrels, Delivery Valves', 0, 'S705 Royal Plaza Twin Towers 648 Remedios St., Malate, Manila', '(02) 400-5778 / 328-3270', '', '(02) 400-9130; 310-0649', 'allan.velarde@philnippon.com.ph', 'COD', 'Distributor', 'Mr. Allan B. Velarde', 'website: www.philnippon.com.ph', 'Active', '1.00', 1, ''),
(310, 'Able Machine Industries', '1. FABRICATION OF FUEL STORAGE TANKS 2,000 - 6,000 U.S. gal. & up capacity using MIG Welding  2. REPAIR OF ALUMINUM TANKERS & IRRIGATION PIPES Using TIG (High Frequency) (Welding & MIG Welding)  3. FABRICATION OF STAINLESS Stair Railings, Table Sink, Electrical Enclosure, Bending of Plates', 0, '618 Ylac Ave., Villamonte, Bacolod City', '(034) 435-5960', '', '(034) 433-0009', 'ablemachineind@yahoo.com', '30 days PDC', 'Contractor / Machine Shop', 'Mr. Oscar B. Rojas Jr. - 0917-301-6321', '', 'Active', '1.00', 1, '152-630-191-000'),
(311, 'First Pilipinas Power and Automation, Inc.', 'PRODUCTS: Generators, Automatic Voltage Regulator, Automatic Voltage Regulator, Manual Transfer Switch, Automatic Transfer Switch, Transformers, AC/DC Motors, Motor Starters, Soft  Starters, Variable Speed Drives, Gear Motors, Lightning Protection, Surge Protection, Ground Resistors, LV & MV Switchgear, Motor Control Centers, Power Distribution Panels, Capacitor Banks, Genset Synchronizing Panel. AUTOMATION: Programmable Logic Controller (PLC), Human-Machine Inteface (HMI), Distributed Control System (DCS), Supervisory Control and Data Acquisition (SCADA), Instrumentation for  Level, Pressure Flow, Temperature, Weighing Gas & Fluid Analyzers, Loop Controllers, Fieldbus, Profibus.  SERVICES Project Engineering & Management, Installation, Testing & Commissioning, Preventive Maintenance and Maintenance Contracts, Transformer Repair, Rewinding, Reconditioning, Generator Set Repair/Electric motor Repair or Reconditioning.', 0, 'Unit 1609 Cityland Tower 2 H. V. Dela Costa St., Salcedo Village, Makati City 1227 Philippines', '(02) 666-1843 / 892-1914 / 0922-881-4382/0927-311-5672', '', '(02) 753-1501', 'anne.teves@firstpilipinas.com', 'COD', 'Distributor', 'MS. Anne Teves/Jed Balaod - 0922-881-4382/0927-311-5672', 'website: www.firstpilipinas.com', 'Active', '1.00', 1, ''),
(312, 'LP Solutions', '1. Filtration 2. Lubrication 3. Conveyor 4. Tools & Instruments 5. Condition Monitoring', 0, '3/F Leeleng Bldg., 718 Shaw Blvd., Mandaluyong City, Phil, 1552 ', '(02) 723-7767 to 70 / 0999-855-3875', '', '(02)-726-5461', 'sales@le-price.com', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(313, 'Starlube Corporation', 'PRODUCT LINES: RIMULA R3+40 CF, DELO GOLD SAE 40, RIMULA TX 15W/40, RIMULA RX 15W40 CI-4/, DELO GOLD /500 15W40 CI-4 , TELLUS S2 V68, RANDO AW 68, GADUS S3 V220 C 2, GADUS S3 V220 C 2, high impact grease, GADUS S3 Wire Roap A, TURBO T-68, OMALA S2 G 150, OMALA S2 G 220, OMALA S2 G 320, ARC TRANSFORMER OIL, Degreaser (Water Base)', 0, 'Camia Street, Espinos Village 1, Circumferential Road, Bacolod City', '(034) 446-2420 / 446-2174', '', '', 'starlubecorp@gmail.com', 'COD', 'Distributor', 'Ms. Malou A. Derrama', 'COR TIN No.: 004-249-850-000 (VAT)', 'Active', '1.00', 1, ''),
(314, 'Berpa-Flex Technologies', 'PRODUCTS: A. FLEXIBLE PIPES / EXPANSION  JOINT (SS MATERIALS / FABRIC) - FABRICATION 1. Single and Multi Bellows 2. Single and Multi-Ply Bellows 3. Flange and Flangeless Flexible Pipe 4. Standard or made to order B. RUBBER PRODUCTS 1. Rubber pads and Gaskets 2. Rubber Impellers, Bushings. 3. O-rings, Rollers, Gaskets, Diaphragms 4. Rubber Expansion Joints. 5. Mechanical Seal.  SERVICES: 1. Machining 2. Design and Fabrication (Mechanical) 3. Repair of Industrial and Marine Boilers Super Heaters, Air Heaters D. Marine Hot Works. E. Repair and Reconditioning of all kinds of Industrial Pump. F. Fabrication & Assembly of Agricultural Machineries & Farm Implements.', 0, 'St. Michael Subdivision, Alicante, E. B.Magalona, Negros Occidental', '0908-1092-386 / 0917-4631-769', '', '', 'berpaflextech@yahoo.com / berpa_bacolod@yahoo.com.ph', 'COD', 'Contractor / Machine Shop', '', '', 'Active', '1.00', 1, '444-149-539-000'),
(315, 'Filtertech General Trading', 'PRODUCTS Stainless Steel Filter Housings Melt Blown Filter Cartridges Membrane Filter Cartridges Pleated Filter Cartridges Carbon Filter Cartridges Wound Filter Cartridges  > Water Treatment Equipments & Parts Supply > Filtertech Filtration-Bags, Sterile Filter, Vent Filter, Dust Collectors, Ultrafilters, Mist Filters, Particulate Filters, Filter Cloth, Filter Press, Sintered SS Filters, Chillers, > Pumps-Oil Seals, Gaskets, Orings, Rewinding, Wires > Aircon AHU-Primary Filters, Bag Filters, Varicell, Mini Pleats, Hepa Filters, Ulpa Filters > Aluminum Glass Windows, Doors, Curtain Wall > Fuels and Lubricants > Water Refilling Station, Building & Installation > Power Transformers & Gen Sets > Automotive Filters, Oil Filters, Hydraulic Filters, Fuel Filters >Chemical Pumps, Air Operated Diaphragm Pumps >Valves, Meters, Gauges', 0, 'N & N Bldg., Cortes Ave. Maguikay, Mandaue City', '(032) 505-8490 / 0922-2266-86 / 0920-2593-077', '', '', 'filtertech_cvsale@yahoo.com', 'COD', 'Distributor', 'Mr. Jeovani C. Pigarido(Area Sales Manager)', 'COR TIN No.: 161-817-584-000 (vat)', 'Active', '1.00', 1, ''),
(316, 'Compresstech Resources, Inc.', 'riprocating compressors pet riprocating compressors rotary screw compressors oil free/variable speed rotary screw compressors centrifugal air compressor oil free & dental lab compressor oxygen & nitrogen generator portable compressors and generators desiccant dryers combined dryer refrigerated air dryer air filters oil filter & filter element automatic condensate drain system controllers & air audit inverters air leak detection parts & service plug & flow piping system flow sensor and data  logger blower/vacuum pumps fluid handling metallic & non-metallic diaphragm pumps piston pumps electric diesel & oil pumps manual/air operated grease pumps manual/air operated oil pumps waste oil drain receiver fluid metering & dispensing device electronic tire inflator washing tank manual/air operated grease gun hose reels for air, water, grease, oil & electricity progressive cavity pump material handling spring & air balancer manual/air electric hoist air/electric/hydraul winches cordless power tools air starter riveter, Ingersoll  Rand, Niower Systems, SEEPEX., BOGE, SFSCurtis, RISHENG, COAIRE AIR COMPRESSORS, INFINITY, ARMSTRONG, GAST, INMATEC (The World of Gases), CSiTEC, BEKO, SAMOA, POWERLINK, KYUNGWON COMPRESSOR, AIRpipe, FLUID HANDLING TOOLS (Made in Italy), MANN+HUMMEL,  VACON (DRIVEN BY DRIVES), INVT, SIMPLAIR, AIRMAN, Air tools Ratchet, impact wrenches, tire buffers, air hammer/needle scaler, air saw/angle grinder, Air drill, screw driver, ar sander, chipping hammer, paving breaker, digger   Air tools accessories Impact sockets, recoil hose, quick connect couplers, filter regulator & lubricator, Pneumatic tool oil, chisels', 0, 'CRI Bldg., 665 Pres. E. Quirino Ave., Malate Manila', '(02) 567-4389 to 95 to 98 / 0922-8063885', '', '(02) 567-4397', '', 'COD', 'Distributor', 'Ms Agnes (Cebu Branch) -0923-658-9375', 'www.compresstech.com.ph / AFFILIATES\r\nENERPRO  MARKETING,   INC.\r\nCRI Bldg., 665 Pres. E. Quirino Ave., Malate Manila\r\nTrunkline: (632) 567-4389 to 95 to 98\r\nFax No.: (632) 567-4397\r\n24 Hour Hotline: 0918-9421152/0922-8161261/0917-6245208\r\n\r\nAEONSTAR MULTIPRODUCTS SALES, INC.\r\n', 'Active', '1.00', 1, ''),
(317, 'Access Frontier Technologies, Inc.', 'Electromechanical > Element 14 > Newark  Power > TDK Lambda > Vicor  Test & Measurement > Fluke, Fluke Calibration, Fluke Networks, Pomona, EXFO, ECOM, OFIL, HIOKI  Telecommunication > Amplus communication, Grentech, Emerson Network Power, Maestro Wireless  Enterprise Network > Wireless Network, Wired Network, Network Security, Network Optimization  Outsource > SMT Printing Production & Tools, Safety Equipment  ELECTROMECHANICAL COMPONENT LED Driver, High-Capacitance-Electrolyte Capacitor, Slide Switch (2 series DPDT Through Hole 16A, 250V) TPS2660x Industrial eFuses from Texas Instruments, A7xx Series Aluminum Polymer Capacitors from KEMET OsiSense XS  Inductive Proximity Sensors From Schneider Electric, Industrial RJ45 Push Pull Connector -  variant 14 from TE Connectivity.  POWER POWER MODULE/ON BOARD TYPE, SYSTEM POWER SUPPLY, TDK-LAMBDA Noise Filter, ELC/ELV-SERIES  TEST & MEASUREMENT Fluke 8808A Digital Multimeter, Fluke 8846A AND 8845A Digital Multimeter  Telecommunication 2W-200W Outdoor C-Band Transceiver (70MHz), 2W-200W C Band BUC (L Band) 70MHz & L-Band Satellite Modem, L band to 70MHz Converter, Redundant System,  Ku/C Band PLL/DRO LNB/LNA  Multimeter, Clamp Meter, Industrial Thermal Imager, Earth Ground Tester, Earth Ground Tester,  Airflow Meter, Temperature Humidity Meter, Carbon Monoxide Meter, Aspirator Kit , Particle Counter Contact Thermometer: Fluke 561 Infrared and Contact Thermometer, ', 0, 'Unit # 207 Grand Arcade Bldg., AC Cortez., Mandaue City 6014, Cebu, Philippines', '(032) 420-2429, 420-7818, 239-2629', '', '(032) 345-0510', 'georgeliwag@accessfrontier.net', 'COD', 'Distributor', 'Mr. George W.F. Liwag - Cebu Branch Head', '', 'Active', '1.00', 1, ''),
(318, 'Flex-a-Seal Industrial Supply and Services', 'Mechanical Seals, Gland Packings, Gaskets, O-rings, Filter Clothes  1. MECHANICAL SEALS > Single Spring, Multi-Spring, Rubber Bellows Type Seal Design Seal Face Material >Carbon Vs. Ceramic >Carbon Vs Silicone Carbide >Silicone Carbide Vs. Silicone Carbide >Tungsten Carbide Vs. Tungsten Carbide etc.  SECONDARY SEAL (RUBBER ELASTOMER) > Nitrile, Viton, Epdm, Epr, Pure Teflon, Teflon Coated Rubber, Aflas, Expansion Joints, Insulation Glass, Ceramic Cloth, tape & board, All kinds of valves, strainers, couplings, special electrodes, bolts and nuts, electricals, measuring tapes, steel/ceramic/rubber brush on chemical lubricants, filter clothes, all industrial safety equipments', 0, 'Blk. 2, Lot 29 Eufemia Compound Circumferential Rd., Taculing, Bacolod City', '(034) 458-3290 / 213-5221 / 0939-955-3716 / 0998-9896-690 / 0922-8051-480', '', '(034) 458-3290 / 213-5221', 'flexaseal@yahoo.com, simonsingo38@yahoo.com', 'COD', 'Distributor', 'Engr. Simon T. Singo - 0939-955-3716 / 0977-064-5056', 'TIN Number: 946-180-356-000', 'Active', '1.00', 1, ''),
(319, 'AVK Philippines Inc.', '1. Gate Valves (Ductile Iron & Bronze) 2. Butterfly Valves (Concretic, Offset, Resilient Seated, Metal Seated) 3. Couplings & Adaptors 4. Fire Hydrants 5. Bail Valves 6. Pressure Control Valves 7. Repair Clamps', 0, '70 Wes Ave. West Triangle Quezon City', '(02) 376-6400 to 01 - 02-376-6399', '', '(02) 332-0609', 'sales@avk.ph', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(320, 'Bernabe Construction & Industrial Corp.', 'LINE OF WORKS > General Construction - roads, highways, bridges, buildings, site development, etc. >  General steel fabrication. > Car Assembly/Manufacturing Equipment - Painting Oven and booth, Electro-Deposit Coating Line,  Assembly Line and others. > Elevated, Underground and Surface Storage Tanks for water, oil, gas, chemicals, molasses and  pressurized vessels all according to ASME Code, Sec. VIII and API 650 Standards. > Design and Fabrication of Sugar Process Equipment - SRI Clarifier, Evaporator, Juice Heater,  Vacuum Pan, Crystallizer, Mill Installation, Massecuite Reheater. > Conveyors - Belt, Screw Conveyor, Bucket elevator, Sugar intermediate main cane carrier and  Auxiliary Chain Cane Carriers Cement, Mining, Paper mills, among others. > Pressure Vessels, Atmospheric Tanks, Floating Roof and Telescopic Tanks. > Steel forms (Moulds) for Pre-Cast and Pre-stressed concrete. > Bottling - Bottle Washer Carriers (Soaker). > Bailey Bridges and accessories. > Steel Towers and Structural Steel for building, factories and warehouses. > Dam and Irrigation facilities - Penstocks, dam and irrigation gates and accessories. > Water and Sewage Treatment Facilities - Purifier, Clarifier, Water Softeners, Stainless Blending  and Settling Tanks, etc. > Sugar Mills and Mining equipment and facilities. > Hydraulic Tilting Platforms/Truck Dumpers up to 100 tons. > Cane Gantry and other loading /transloading facilities. > ON and OFF the road (Farm and Highway) Trailers - Tanks, Bulk Sugar Trailers, Molasses, etc. > Farm implements - Harrows, Plows, Tractor attachments and other allied implements. > Rail Box cars, Steel Rail Ties and Cane Containers. > Foundry works and machine shops. > Smokestacks, Bugles, Racks, Cyclone, Ducting, Industrial Exhaust Fans and Blowers. > Shearing, Rolling and Bending; Dishing and Flanging services. > Fabricated Rolled Steam or Condensate Pipes. > Water Spray Ponds. > Distillery equipment and facilities - Distilling columns, Bubble caps, etc. > Project Engineering and Management. > Plant erection, Machinery and equipment installation - contract and/or cost plus. > Domestic and International Trading:  A. TAM S.r.l. (Italy) Hydraulic Hook-lift B. BONEL Mfg. (Australia) - Farm implements and Cane Harvesters  (chopped/whole stalk) C. Copper and Stainless Tubing (all sizes) D. Hydraulic Platform Truck Dumpers  (Tippers) E. Special Steel Application - heat and abrasive resisting plates, high tensile up to 155,000 PSI  tensile strength, mill shaft and other special application steel', 0, 'Roosevelt Avenue, Quezon City', '(02) 292-3401 / (02) 292-1540 / (02) 293-7625', '', '(02) 292-1745', 'bernabeconst@yahoo.com  / bernabeconstruction@yahoo.com', 'COD', 'Distributor', '', 'WEBSITE ADDRESS: http://bernabeconstruction.weebly.com', 'Active', '1.00', 1, ''),
(321, 'Dawson Technology PTY LTD.', 'PRODUCT LINES 1. Design an dmanufacturer of "Si-TEC Xtend" Integrated Digital Governors Hydraulic Amplifiers 2. CGC (Co-Generator Control) Governors for wide range of steam turbine generators. 3. GSM (Generator System Master) Control for Automatic Grid Synchronizing and load control. 4. ADG (Advanced Digital Governor) for steam turbine drives, (eg. Shredder, Mill, Fan, Knife, etc) 5. Advanced Software for Diagnostic (PC Tune) and data logging & remote monitoring (Data View) 6. Accessories including Opal Annunciator, Temperature, Scanner, Remote I/Os, MPU Expander, Etc. 7. Digital Integrated Governors (including CGC, TGC and ADG) for Diesel Engine Applications.  SERVICES OFFERED: 1. Governor retrofit/upgrade, design for optimum solution, site commissioning and training. 2. Governor services including service/calibration of hydraulic amplifiers & mechanical governors. 3. Design consultancy and the following engineering services using DigSILENT Power Factory Software through our subsidiary company Dawson Engineering.', 0, '231 Holt Street, Eagle Farm Queensland 4009, Australia', '+61 738-684-777', '', '+61 738-684-666', 'remesh@govtec.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(322, 'Deco Machine Shop', 'PRODUCT LINES: Gears, Sprockets, Pins, Bushings, Blowers, Impellers, Compressors, Valves, Seaming, Chucks & Rolls, Bottling Vent Tube, Linear Shafts, Conveyors, Rollers, D2 Punches & Dies.  SERVICES OFFERED: Engine Rebuilding, Computerized (CNC) Machining, Dynamic, Balancing 1 kg -10,000 kg, Zvibratory Stress Relieving, Fabrication and heat treatment of gears, gearbox rebuilding, on site Machining & Repair Exhaust Valve, Satellite Rebuilding, Resurface of Lathe Redways, Blade Sharpening (10fit Max). Laser Alignment of Gensets, Pumps, etc.', 0, 'J. P. Cabaguio Avenue, Davao City', '(082) 226-4338', '', '(082) 226-4339', 'sales@decomach.com', '', 'Machine Shop', '', '', 'Inactive', '1.00', 1, ''),
(323, 'Dynamic Castings', 'PRODUCT LINES OR SERVICES OFFERED: 1. MILL ROLLER BEARINGS - Mill Journal Bearing for WALKER MILL (SAE 67), Mill Journal Bearing (SAE 67), Water Cooled Top Jurnal Bearing (SAE 67), Mill Bearing (SAE 62), Top Roll, Bearing Liner (SAE 63) Top Roller Upper half Pintle Side (SAE 67), Top Roll Journal Bearing (SAE 67), Bearing Half Top Roller (SAE 63). 2. CHAIR LINER - Mill Roller Journal Bearing (SAE 67), Bottom Roll Bearing Liner (SAE 67), Bottom Bearing Liner of Mill Feed & Gear Side (SAE 63), Mill Top Roll Bearing Liner (SAE 63). 3. TRAVELLING GRATES - bigelow Traveling Grate Split Type (Aluminum Bronze 9D). 4. CROFT LINERS - Bottom Roll Liner for walker Mill (SAE 67) Top Roller Journal Bearing (SAE 67), Croft Gear  Liner (SAE 67), Split Bearing Liner Assembly for Pillo Block Bearing Final Motion (SAE 67) 5. PISTON LINERS - {iston Segment (SAE 63), Hydraulic Piston Liner For Farrel Mill (SAE 67). 6. PUMP IMPELLERS - Centrifugal Pump Multi-Stage Close Type Assembly (SAE 62). 7. MILL BEARINGS - Bearing Bottom Roller Discharge Pintle Pinion Side (SAE 67).', 0, '473 Gerardo Quano Street, Alang2x, Mandaue City, Metro Cebu', '(032) 345-6171 / 346-0300', '', '', 'cebu@dynacast.ph / pollyngo@dynacast.ph / ck@dynamicpower.ph', '', '', '', '', 'Inactive', '1.00', 1, ''),
(324, 'EESI Material and Controls Corp.', 'PRODUCT LINES OR SERVICES OFFERED: 1. SIEMENS SC Process Instrumentation (Germany) - Pressure, Temperature, Flow, Level, Weighing Technology,  Valve Positioners & Protection Relays 2. SIEMENS Drive Technologies & Motion Control - (Germany) - LV & MV Motors, MV & LV Drives, Standard Drives 3. SIEMENS Process Analytics (Germany) - Continuous Gas Analyzers, Process Gas Chromatograph. 4. BAUMER Process Instrumentation (France) - Pressure& temperature Measurement & Control,  Level & Liquid Quality Measurement & Control. 5. PHOENIX Contact 9germany) -Industrial Connectivity and Interface Systems, Industrial Automation,  Surge & Lighting Protection. 6. Temperature Sensors Services Pte. Ltd (singapore) - Temperature Sensors (RTD, TC). 7. Ametek O'' Brien Analytical (USA) - Analyser Sample Transport & Conditoning Systems, Instruments Shelter and Mounting Systems. 8. Dr. A. Kuntze (Germany) - Water/Liquid Quality Measurement & Control System. 9. Rittmeyer (Switzerland) - Flow and Level Measurement & Control for the Hydro-Electric Power Industry. 10. Lacroix Sofrel (France) - Wireless dataloggers and SCADA systems for the water and water industry. NAGMAN Instruments & Electronics Ltd. (India) - Calibration Standards, Systems & Softwares, Test & Measuring  Instruments, Consultancy for Calibration Laboratory Set-up.', 0, '124 A. N. Manapat St., Poblacion Arayat, Pampanga / Unit 1402 14th Floor The One Executive Office Building # 5 West Ave, QC', '(02) 410-3622', '', '(02) 351-7775', 'mar.ignacio@emcc.com.ph / sales@emcc.com.ph / emil.enriquez3 @emcc.com.ph', '', '', '', '', 'Inactive', '1.00', 1, ''),
(325, 'Festo, Inc.', '1.	PNEUMATIC, Complete range of cylinder, valves, sensors, filters, regulators, lubricators, vacuum units, valve sensors, terminals, tubing, fittings and accessories. 2. Process Automation: Pneumatic and electronic products, process valves, instrumentation and controls for Process Automation. 3. Electronic: Industrial PC Electronic Programmable Logic Controllers, Software and Interface, etc. 4. System: Design, Installation, Programming and Commissioning of Control Engineering Projects. 5. Didactic: Weekly, Hands on, Seminar for Engineers, Technicians and Technical Instructors, Hardware and Tech ware for Technology Training', 0, 'Km. 18, West Service Road, South Super Highway, 1700 Parañaque City', '1800-10-12(FESTO) 33786', '', '1800-10-14(FESTO) 33786', 'festo_ph@ph.festo.com', '', 'Distributor', '', '', 'Inactive', '1.00', 1, ''),
(326, 'ICI Systems Inc.', 'PRODUCT LINES OR SERVICES OFFERED: 1. Endress + Hauser  - Leading Supplier of Measuring Instruments and Automation Solutions for the Industrial Process Engineering Industry. 2. Anton Paar- High Quality Measuring and Analysis Instruements for Laboratory and Process Applications.', 0, '14F Belvedere Tower # 15 San Miguel Ave., Ortigas Center', '(032) 344-1584 (Cebu) / (02) 637-8577 - Head Office', '', '(032) 344-1584 (Cebu) / (02) 633-5127 - Head Office', 'cebusales@icisystems.net / customer.care@icisystems.net', '', 'Distributor / Supplier', '', '', 'Inactive', '1.00', 1, ''),
(327, 'Ishan International Pvt. Ltd.', 'PRODUCT LINES OR SERVICES OFFERED: 1. Mill Rollers (Conventional/Perforated) 1.1 Assembly 1.2 Reshelling 2. Gearboxes/Reducers 2.1 Helical 2.2 Special 2.3 Planetary 3. Rope / less coupling 4. Forged Shafts 5. Forged Chains 6. Mill Pinion & Mill Coupling 7. VFD and Motors 8. SS Tubes 9. Boilers 10. Boiling House Equipment 11. Complete Mills and Head Stocks 12. Bronze Bearing Liners and Box Bearing Liners 13. All Sugar, Hydro and Parmaceutical Machineries', 0, 'B-68, Sector-14, Noida-201 301, UP India', '(+91-120) 2518261 / 62', '', '', 'navneet@ishangroup.co.in / ishan-ho@ishangroup.co.in', '', '', '', '', 'Inactive', '1.00', 1, ''),
(328, 'Jan Dale Enterprises Corp.', '1. SCHMIDT + HAENSCH QUARTZ  WEDGE SACCHAROMAT 2. SCHMIDT + HAENSCH Automatic Precision Sugar Refractometer 3. Bio-ethanol Cane and Alcohol Analysis 4. Cane Purity Analyzers with Data Acquisition Systems 5. ISI Automatic pH Liming Stations, SPECTRA 6. ISI High Temperature and TDS Sensors 7. ISI Laboratory pH, DO, TDS Electrodes 8. On-line Process Refractometers,  Brix Controllers 9. Automat Level, Pressure and Flow Instruments 10. ITECA, COLOBSERVER, On Line Colour Analyzer for DRY or WET Sugar 11. ITECA, PART, SIZER: On Line Particle Size Analyzer 12. BATCH PAN MICROSCOPE and video Cam System for Sugar Crystals 13. Temperature Sensors Infrared or Probe Type and Temperature Controllers 14. Laboratory pH, DO, TDS, Color and Turbidity Analyzer 15. Water and Waste Water Automation Equipment 16. Control Valves and Accessories 17. ASL Temperature Calibrators and Calibration Baths 18. Repair and Calibration of Refrometers, Saccharimeters and other sugar analyzer 19. Huba Pressure and level transmitters 20. JAN DALE designed In-line Entertainment Protection Systems. 21. JAN DALE designed Floculant Control Systems 22. Repair and Calibration of industrial weighing scales 23. Conversion of Mechanical Scale to Electro, Mechanical 24. Conversion of Servo Weighers to electro, Mechanical 25. JAN DALE / SCHMIDT, HAESCH LAB, PC AUTOMATION', 0, 'G-19 South Star Plaza, South Superhighway, Makati City', '(+632) 813-1396 / (+632) 806-3006', '', '(+632) 813-1397', 'jandalecorporation@yahoo.com', '', 'Distributor / Supplier', '', '', 'Inactive', '1.00', 1, ''),
(329, 'JM Brenton Industries Corp.', 'PRODUCT LINES OR SERVICES OFFERED: 1. Gardner Denver Nash Vacuum Pumps & Compressors 2. Borger Rotary Lobe Pumps 3. Griswold Ansi Process Centrifugal Pumps 4. Neptune Chemical Dosing / Metering Pumps 5. Neptune Agitators & Mixers 6. Graco Air Operated Double Diaphragm Pumps', 0, '2nd Flr., JM Bldg., Superhighway Corner Rocketfeller St., Makati City', '(02) 817-5732', '', '(02) 817-5739', 'jmbicorp@pldtdsl.net', '', 'Distributor / Supplier', '', '', 'Inactive', '1.00', 1, ''),
(330, 'Manly Plastics Inc.', 'PRODUCT LINES OR SERVICES OFFERED: 1. Pallets 2. Plastic Crates 3. Trolley 4. Pails (Plastic)', 0, '60 West Ave. CBT Condominium Quezon City', '(02) 373-9797 loc. 141', '', '(02) 373-4750', 'sales@sanko.com.ph', '', 'Distributor / Supplier', '', '', 'Inactive', '1.00', 1, ''),
(331, 'Kupler DCMC Philippines Corp.', 'PRODUCT LINES OR SERVICES OFFERED: 1. MOBIL LUBRICANTS - Automotive & Industrial Lubricants', 0, 'Paradise Road, Km 9 Sasa Davao City, 8000', '(082) 234-9018 / (082) 234-8088 / (+63) 922-8544013', '', '(082) 373-4750', 'mobilsales.dvo@kuplerdcmc.com', '', 'Distributor / Supplier', '', '', 'Inactive', '1.00', 1, ''),
(332, 'MHE - Demag (P) Inc.', 'PRODUCT LINES OR SERVICES OFFERED: 1. Industrial Crane 2. Crane Components 3. Rope and chain Hoists 4. Warehouse Trucks 5. Docking Equipment 6. Building Maintenance Units 7. Aerial Work Platforms 8. Car Parking Systems 9. Profile Rails 10. Fastening Systems', 0, 'Door No.4 Ground Floor, Mahogany St. Capitol Shopping Center, Bacolod City ', '(034) 786-7500 /  441-9605 / 312-8113', '', '786-7555', 'jonathan_gonzales@mhe-demag.com', '30 days upon receipt of Invoice', 'Distributor / Supplier', 'Mr. Neil G. Balibagoso - neil.balibagoso@mhe-demag.com; Mr. Ramon Eslava - ramon_eslava@mhe-demag.com', '', 'Inactive', '1.00', 1, ''),
(333, 'Motology Electric Pte Ltd.', 'PRODUCT LINES OR SERVICES OFFERED: 1. Cycloidar Gearmotor 2. Gearbox, Speed Reducer 3. Roller Chain & Sprocket 4. Conveyor Rollers 5. Conveyor Components 6. Conveyor Belt 7. Gear, Grid, Jaw Flexible Coupling 8. Electric Motor Control 9. AC Induction Motor 10. Design, Fabrication & Estimate', 0, 'Unit 1 RGA Bldg., Suba Basbas, Lapu-Lapu City Cebu 6015', '(032) 494-3844', '', '(032) 494-3844', 'tecmesh@pldtsl.net', '', 'Distributor / Supplier', '', '', 'Inactive', '1.00', 1, ''),
(334, 'Omron Asia Pacific Pte. Ltd.', 'PRODUCT LINES OR SERVICES OFFERED: 1. FACTORY AUTOMATION SYSTEM Programmable Controllers Programmable Terminal Variable Frequency Inverter Servomotors/Servo Drives Automation Software 2. SENSING DEVICES Fiber Sensor Photoelectric Sensor Proximity Sensor Photomicro Sensor Rotary Encoder Pressure Sensor Displacement/Measurement Sensor Vision Sensors/Machine Vision System Code Readers/OCR Ultrasonic Sensor 3. INDUSTRIAL DEVICES/ELECTRONIC AND MECHANICAL COMPONENTS: General Purpose and Power Relay PCB Relay Solid-state relay Basic Switches Limit Switches Timers/Counters Cam Positioner Simple Logic Controller Switching Power Supply Temperature Controller Intelligent Signal Processor Gdigital Panel Meter Level Controller Level Controller', 0, '2/F King''s Court II Building, 2129 Chino Roces, Avenue Corner Dela Rosa St., 1231 Makati City', '(02) 811-2831 to 36', '', '(02) 811-2583', 'ph_enquiry@ap.omron.com', '', 'Distributor / Supplier', '', 'Manila Represesntative Office', 'Inactive', '1.00', 1, ''),
(335, 'P. T. Cerna Corporation', 'PRODUCT LINES OR SERVICES OFFERED: 1. Rockwell Automation 2. Valves & Pumps Automation 3. Steam Engineering 4. Engineering Services 5. Process Instrumentation Products 6. Power and Climate Controls 7. Sew Mechanical Drive Systems 8. Water and Gas Analysis', 0, 'Unit 2, Yusay Bldg., 23rd St., Brgy. 5, Bacolod City', '(034) 708-1932', '', '(034) 441-2193', 'bacolod@ptcerna.com', '', 'Distributor / Supplier', 'Ms. Caneth B. Ariola (Application Sales Engineer) - 0917-324-6701 / 0919-773-1641', '', 'Inactive', '1.00', 1, ''),
(336, 'Process Technik Solutions', 'PRODUCT LINES OR SERVICES OFFERED: 1. P+F-Govan (Australia) - Exd Terminal and Junction Boxes, Exd Local Control units & stations, Exd Flameproof Distributions and Control Stations, Exp Purge Solutions, Exd Control Starters and Signalling Devices. 2. TECFLUID (Spain)-Flowmeters, Level Transmitters, Mechanical Counters 3. ORBINOX (Spain) - Knife Gate Valves, Penstocks, Dampers 4. AUER SIGNAGERATE (austria) - Visual Signalling Equipments and Signal Towers, Visual Audible Signalling Equipments, Ex Audible Signalling Equipment, Ex Telephone and Ex Accessories. 5. ELDON ENCLOSURE SYSTEMS (Netherland) IP66 Rated Wall Mounted Panels, IP66 Floor Standing  Enclosures and Accessories 6. MAUSA (Spain) - Continuos and Batch Centrifugals, Separator Centrifugals, Filtration (Rotary Vacuum Filters, Vacuum Belt Filter Pressure Filter), Drying - Peaddle Dryer, Rotary and Cooler, Rotary Flakers) Pumps, (Vacuum Liquid Ring, Lobe Pump, Evaporation (Falling Film, Batch Vacuum Pans). 7. LOESCHE (Germany) - Vertical Grinding Mills for Solid Fuels, (Coal, Biomass, Wooden Pellets), Mobile Grinding Plants for Solid Fuels. 8. PEKOS (Spain) - Ball Valves, Actuated Ball Valves, Special Designed Ball Valves (Anti-Static and for Flammable Mediums). 9. AYVAZ (Turkey) - Expansion Joints, Steam Traps, Steam Separators 10. FAIRFORD (England) - Soft Starters (Three Phase and Single Phase), Synergy Soft Starters, Centris Medium Voltage Soft Starters. 11. CF NIELSEN (Denmark) - Complete Briquetting Line for Biomass (Bagasse, Rice Husk, King Grass, Wood Chips and etc.)', 0, 'Unit 502 Yrreverre Building, No. 888 Mindanao Ave., Brgy. Quezon City', '(044) 896-3450', '', '(044) 896-3450', 'processtechnik@gmail.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(337, 'Sanyoseike Stainless Steel Corporation', 'PRODUCT LINES OR SERVICES OFFERED: 1. Stainless Steel Sheet 2. Stainless Steel Plate 3. Stainless Steel Pipe 4. Stainless Steel Tube 5. Stainless Steel Bars 6. Stainless Steel Coil', 0, '28th Floor, World Trade Exchange Building, Juan Luna St., Binondo Manila', '(02) 247-9777', '', '(02) 247-7877', 'info@sanyoseiki.com.ph', '', '', '', '', 'Inactive', '1.00', 1, ''),
(338, 'Schaeffler Philippines, Inc.', '1. Rolling Bearings, housing Units and its Accessories 2. Maintenance Products 2.a Bearing Mounting and Dismounting Tools. 2.b Online and Offline Vibration Analyzer 2.c Laser Alignment tools for shaft, pulleys and belts 3. Lubricants 3.a Food Grade Grease 3.b Grease for High Load Applications 3.c Grease for High Temperature Applications 3.d Grease for High Speed Applications 4. Professional Services 4.a Bearing Technology and Maintenance Training Course 4.b Bearing Failure and Damage Analysis 4.c Precision Alignment and Dynamic Balancing', 0, '5th Floor Optima Bldg., 221 Salcedo St., Legaspi Village, Makati City 1229', '(+632) 759-3583 to 84', '', '(+632) 779-8703 / (+632) 759-3578', 'campoeva@schaeffler.com', '', '', '', '', 'Inactive', '1.00', 1, '');
INSERT INTO `vendor_head` (`vendor_id`, `vendor_name`, `product_services`, `category_id`, `address`, `phone_number`, `mobile_number`, `fax_number`, `email`, `terms`, `type`, `contact_person`, `notes`, `status`, `ewt`, `vat`, `tin`) VALUES
(339, 'Schneider Electric', 'The Global Specialties in Energy Management As a global specialist in energy management with operations in more than 100 countries, Schneider Electric offers integrated solution across multiple market segments, including leadership positions in Utilities & Infrastructure, Industries & Machines Manufacturers, Non-residential Building, Data Centres & Networks  and in Residential. Focused on making energy safe, reliable, efficient,  productive and green, the group''s 150,000 plus employees achieved sales of 24 billion euros in 2013  through an active commitment to help individuals and organizations make the most of their energy.', 0, 'Manila Office: 24/Fort Legend Tower, Block 7 lot 3, 3rd Ave. Cor. 31st St. Fort Bonifacio Global City, Taguig City 1634', '(02) 976-9999', '', '(02) 976-9961 or 64', 'customercare.ph@schneider-electric.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(340, 'Schneider Electric', 'The Global Specialties in Energy Management As a global specialist in energy management with operations in more than 100 countries, Schneider Electric offers integrated solution across multiple market segments, including leadership positions in Utilities & Infrastructure, Industries & Machines Manufacturers, Non-residential Building, Data Centres & Networks  and in Residential. Focused on making energy safe, reliable, efficient,  productive and green, the group''s 150,000 plus employees achieved sales of 24 billion euros in 2013  through an active commitment to help individuals and organizations make the most of their energy.', 0, '4th Flr, DISPO Building, AC Cortes Ave., Mandaue City Cebu', '(032) 344-7117', '', '(032) 344-7119', 'customercare.ph@schneider-electric.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(341, 'Siemens, Inc.', 'Automation System  Distributed Control Systems (DCS), Programmable Logic Controllers (PLC), Human Machine Interface (HMI), Operator Panels, Industrial PC.  Control Components & System Engineering Motor Protection Circuit Breakers, Contactors, Thermal Overload Relays, Timing Relays, Soft Starter, Motor Management and Control Devices  Sensors and Communication (Process Instrumentation)  Flowmeters, Transmitters and Switches (Level, Pressure, Temperature, etc.) Sensors, Weighing, Communication Switches, Power Supplies.  Large Drives AC & DC Motors, Low Voltage and High Voltage Motors and VFDs, Explosion-proof motors & drives  Motion Control Variable Frequency Drives (VFD) Motion Control Systems, Servo and Linear Motors, SINAMICS  Mechanical Drives Flender Gear Motors, Couplings, Flender Motox', 0, '14/F Salcedo Tower, 169 HV Dela Costa St., Salcedo Village, Makati City 1227', '(632) 814-9861', '', '(632) 814-9807', 'Carolina.araneta@siemens.com / april.santos@siemens.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(342, 'Simpson''s Phils. Inc.', 'PRODUCTS LINE/S OR SERVICES OFFERED 1. Filter Bags, Cartridge & Housing 2. Hayward Strainers, Automatic Self Cleaning 3. Aviation Fuel Filters 4. Industrial Lube/Fuel Filters, Sewage Wastewater System 5. Filter Presses, Agitators & Pressure Leaf Filter 6. Solid/Liquid Separator 7. Cooling Tower Basin Sweeping System 8. Deepwell Sand Separator', 0, '410 D. Lucas Cuadra St., Sta. Quiteria Caloocan City', '(02) 983-7556/983-7546/983-7572', '', '(02) 983-2286', 'elenperlado@simpsonsphil.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(343, 'Spectrum Chemicals Inc.', '1. Internal Water Treatment for Boilers and Cooling Towers 2. Design/Install/Supply/Commissioning of Reverse Osmosis System/Multi-media Filter/Activated/ Water Softener 3. Upgrading/Rehabilitation of R. O. System 4. Premier Vacuum Pumps 5. Longji Electromagnetic Separator/Jonhking Industrial Chains 6. Prathap Mill Rollers/Barriquand Heat Exchangers 7. Design/Supply/Install/Commissioning of Concrete Type Water Clarifier', 0, 'R. 203 Cityland Con. 8, 98 Gil Puyat Ave., Makati City', '(02) 817-3975/892-8536', '', '(02) 892-9536', 'speche888@yahoo.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(344, 'Prime Opus Inc.', 'Sulzer, Netzsch, Leroy Somer, Wirebelt, Addinol, Kongskilde, Haug, Macintyre, Sandvik, Hafco, TPS', 0, 'B 6 L26 Faith Street, St. Catherine Village, Brgy. San Isidro, Sucat Road, Para?aque City', '(632) 820-1421 / (632) 478-6013', '', '(632) 825-8121', 'primeopus@pldtdsl.net', '', 'Exclusive Agent of Netzsch Progressive Cavity Pumps, Addinol Lube Oil, Kongskilde Industries A/s, Sandvik Carbon/Stainless Steel Belts and Wirebelt U. K.', 'Mr. Sidney S. de la Cruz -0998-580-0041', 'Website: www.primeopusinc.com, TIN Number: 209-100-507-000, SSS No. 03-9142882-5, SEC Reg No.: A200018820, Date of Org: Dec. 14, 2000', 'Inactive', '1.00', 1, ''),
(345, 'GPM Trading & Engineering Services', 'Water Pumps', 0, 'Lot 888H, National Highway, Alijis, Bacolod City / Cor. Mabini-Luzuriaga Sts., Bacolod City', '(034) 435 0742/433-1464', '', '', 'gpmengineering_services@yahoo.com', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, '941-394-825-0000'),
(346, 'Topbrass Construction & Trading Corp.', 'Ready Mix Concrete', 0, 'Prk. Paho 2, Brgy. Estefania, Bacolod City', '0949-1150-567', '', '', '', 'COD', 'Manufacturer ', 'Mr. Ismael Fuentes Jr.', '', 'Active', '1.00', 1, ''),
(347, 'West Point Engineering Supplies', 'PRODUCTS LINE/S OR SERVICES OFFERED 1. NESSTECH Inc Japan - Temperature and Pressure Gauges 2. Camille Bauer Switzerland - Instrumentation And Positioners 3. Additel Corporation USA - Digital Pressure Gauges and Multifunction Calibrators 4. SSS Co., LTD. - Positioners, I/P Converter, Booster Relay and Filter Regulators 5. Kansai Automation Co. LTD - Level Switch, Level Meter, Level Sensor 6. Fossil Power Systems CANADA-Boiler Level Drum and Valves 7. Samson AG Germany - Control Valves, Steam Conditioning Valve, Flow Measurement 8. Fairford Electronics UK - Soft Starters, Synergy 9. V & t Drive - AC Frequency Inverter 10. Aplisens Poland - Pressure, temperature & level products', 0, 'West Point Bldg., Bacood St., Brgy. Patubig, Marilao, Bulacan Philippines 3019', '(044) 248-3301', '', '(044) 248-3309', 'westpointengineering@gmail.com', '', 'Distributor', '', '', 'Inactive', '1.00', 1, ''),
(348, 'Yokogawa Philippines, Inc.', 'PRODUCTS LINE/S OR SERVICES OFFERED 1. Factory and Process Automation Control Systems, Programmable Logic Controller (PLC),  Distributed Control System (DCS) Network Based Control Systems (NCS) 2. Field Instruments - Flowmeter, Pressure Transmitter, Temperature Transmitter, Level Transmitter, Control Valves, Valves Positioner, Field Wireless Products. Pressure Gauge, Temperature Gauge, Temperature Senor. 3. Analyzers-Liquid, pH/ORP, Conductivity, Dissolved Oxygen, Turbidity, Gas, Chlorine, Zirconia, Oxygen, Stack Gas Analyzer, Dust Monitor; Recorders and Controllers-Paperless, Strip Chart Recorder,  Controllers and indicators, Signal Conditioner, Power Monitor. 4. Waveform Measurement & Analysis: Oscilloscopes, scopeCorder, Low Speed DAQ & Industrial Recorder, High Speed Data Acquisition Equipment. 5. Optical Measuring Instruments: OTDR, Optical loss Test Set, Optical Spectrum Analyzer, Ethernet Handheld Tester, Optical Power Meters, Optical Light Source, LD Light Source. 6. Power Monitoring Instruments: Precision Power Analyzer, Digital Power Meters, High Performance Power Analyzer, Digital Power Analyzer. 7. Portable Test Instruments: Digital Multimeters, Circuit Testers, Insulation Testers, Earth Tester, Insulation Polytester, C;amp on Powermeters, Wheatstone Bridge, Resistance Box, Slide Resister,  Galvanometer, Luxmeters 8. Generators/Sources: Source Measure Unit, DC Voltage, Current Source, Synthesized Function Generators. 9. Instrumentation Calibration, Instrumentation Bench Repair, Shutdown Maintenance, Commissioning & Start Up System Upgrading, Panel Engineering and Manufac turing, DCS Software/ Hardware Engineering 10. Training: Process Control and Instrumentation Courses, PLC Training, DCS Training.', 0, 'Topy Industries Bldg., No. 3 Economia St., Bagumbayan, Quezon City', '(632) 238-7777', '', '(632) 238-7799', 'feedback@ph.yokogawa.com', '', 'Distributor', '', '', 'Inactive', '1.00', 1, ''),
(349, 'Esetek Equipment (Philippines) Inc.', 'PRODUCTS LINE/S OR SERVICES OFFERED 1. Calibration Services 2. Repair 3. Trading 4. Distributor of Fluke, Megger, Kikusui etc.', 0, 'Unit 507-508 Alpap II Bldg., Madrigal Business Park, Investment Drive, Cor. Trade Ave., Alabang, Muntinlupa City', '772-2301', '', '772-2298', 'jesuspadilla@ph/ese-asia.com', '', 'Distributor', '', '', 'Inactive', '1.00', 1, ''),
(350, 'Fabcon Philippines, Inc.', 'PRODUCTS LINE/S OR SERVICES OFFERED MILL AND BOILER DEPT. > Unigrator > Lotus Roll > Thyssenkrupp mills > Wer Scrubbers > NQEA Bagasse Bins > NQEA Truck Dumper > Cooling Towers - Designed for Sugar Mill Water Conditions > Hiniron Core Samplers > Domite Cane Knives & Unigrator Hammer Tips > Elecon Planetary Gear Drive  BOILING HOUSE > Thyssenkrupp Centrifugals > Continuous Vacuum Pans and Crystallizers > Fabcon Jsp Syrup Clarifier > Shrijee Sugar Dryer > Evaporators >VRP Energy Savings and Automation  PROCESS CHEMICALS >cma, ZUCLAR, COLORGONE, I-12, VISC-AID, ARW, SUGAR DECOLORANT  BOILER WATER TREATMENT > FABCOL, FABFOS, FABOX, FABSCALEX, FABCAR  REFINERY CHEMICALS >COLORGONE, PUROLITE DECOLORIZING RESINS  ENVIRONMENT 1. Turnkey Design, Construct, Operate for Waste Water Treatment 2. Turnkey Design, Construct, Operate Wet Scrubber 3. Complete Ash Settling Clarifier Design and Construct 4. Closed Loop Cooling Water System', 0, '12/F Jollibee Center Bldg, San Miguel Ave., Pasig City, Philippines-Manila Office / Rm. 203, St. Jude Bldg., San Sebastian-Gatuslao Sts., Bacolod City - Bacolod Office', '633-4234 to 38 / 435-4741', '', '633-4211 / 435-4741', 'dmvellanueva@fabcon.ph', '', 'Distributor', '', '', 'Inactive', '1.00', 1, ''),
(351, 'Rurex', '(Complete Heat Exchangers Solutions), Specialize in Charge Air Coolers Servicing, Repair & New Fabrication offering a one-stop solution.   A. Shell and Tube Heat Exchangers    - Provide a complete solutions for shell and tube heat exchangers e.g      oil coolers,heaters,condenser B. Plate Heat Exchangers C. Other Product and Services     a.Dynamic Balancing of Fans & Blowers     b. Repair/Reconditioning of Fans & Blowers     c. Supply of:           1. Pressure vessels           2. Aircon Coil for Marine Vessels          3. Blower Fans          4. "V" Type Rasiator          5. Finned tubes          6. Radiator for Genset          7. Oil Cooler          8. Remote Radiator', 0, 'Cebu Branch: P.C Suico St. Brgy. Tabok Mandaue City Cebu', '(032)343-9861,239-7361', '', '(032) 343-7165', '', '', '', 'Leonardo Pontillo: Sales Mktg (09328677328), (09178013285)', '', 'Inactive', '1.00', 1, ''),
(352, 'NCH', 'A. CHEM-AQUA (Water Treatment)     1. Cooling Treatment     2. Boiler Treatment     3. Solid Solutions     4. ROI Calculation     5.On-Site Water Analysis & Report     6. Water Management Plan-Legionella Risk Assessment     7. Automated Equipment Solution     8. Cleaning & Services B. LUBRICANTS     1. Greases & Oils     2. Specialty Lubricants     3.Release Agents     4.Metal Working Fluids     5. Fuel Additives     6.Water Based Parts Cleaning     7. Cleaning & Services C. WASTEWATER     1.Drains     2.Wastewater Treatment Plants     3.Cleaning & Services     4.Lift Stations     5.Grease Traps     6.Odor Control     7.BioAmp Systems D. OTHERS:     1. Premalube Black     2.Premalube Red     3.Premalube Extreme Green     4.Premalube extreme heatshield     5.Premalube Extreme FG     6. Premalube 0     7.Premalube white aerosol     8.Pureplex FG     9.Certop multi-grade SAE 80w-90     10.Certop multi-grade SAE 85w-140     11.Certop 90 FG     12.Certop 140 FG     13.Certop industrial ISO VG 220     14.Certop industrial ISO VG 460     15.Gear-up plus     16.System Purge     17.Hi-top multi-grade     18.Hi-top single grade     19.Dri-lube plus aerosol     20.Excelube plus/bulk     21.Yield aerosol     22.Accel     23.Androil FG     24.Diesel-mate 2000 plus     25. Full Blast     26.CCX-77     27.Hi-gear plus     28.Lok-cease 20/20     29.cool flush     30.cool plus     31.ND-165     32.ND-150     33.Hold fast plus     34.Resist-x plus     35.Voltz     36.Torrent 400', 0, 'Door #20 Mercedez Comm''l Ctr.A. Cortez Avenue, Mandaue Cebu', '(032) 346-5288 / (032) 346-5631', '', '', 'rotchel.mendoza@nch.com', '', '', 'Rotchel Mendoza: Water Treatment Specialist and Field Product Manager', '', 'Active', '1.00', 1, ''),
(353, 'Safari', 'Meters, (Power & Water Beyond Frontiers), A. VIOSERIES - Anti- Tamper/pilferage features,large LCD display         register,large laser print serial no., battery back up for display,        longer effective life,full CT-PT equipped. B. ED200Vio 3W C. CT 888i D. 88 series CT 88i E. CT 888- large cyclometer register, large laser print serial no.,       anti-corrosion meter base,CT-PT equipped F. 88 Series CT 88 G. AUTOMATED kWhr Meter Testbench- can be operated thru       special built in keyboard and PC, equipped with automated      photoelectrical scanning head, settings on harmonics waveform      display,progmmable voltage,current frequency & phase angle H.Automated 3P KWHM Testbench I. KWHM Running Tester Bench J. 1P/3P on-site meter calibration-measure normal parameter of      powerline,harmonics,waveform and transformer''s ratio K. 1P on-site meter calibration tester L. Electronic Voltage and Current Loader-rotary dial for V/A input,      single power on and off toggle switch M. iSWITCH- Theatrical-grade work light switch,wall mounting in,      single or multi-gang boxes,normal wiring procedures N. Model SW 15(Multijet Brass Water Meter      a. Magnetic drive polymer counter      b.Rotary vane wheel or impeller      c.Uni-directional/reversible w/ magnetic      d.Copper alloy brass O. Model SW 15i      a.3600  Rotable Magnetic Drive Polymer counter      b. Rotary Vane wheel or impeller      c. Uni-directional/reversible with anti-magnetic shield      d. Copper alloy brass P. Model LS-4B', 0, '#27 VMCC Complex Granada Avenue cor. Santolan Road Q.C.', '(632) 724-7785', '', '', 'safarimeter@hotmail.com / safarimeter@yahoo.com / safarimeter.wordpress.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(354, 'Kyung Dong Electric Co., ltd.', 'A. Interrup switch B. Disconnecting switch C. Disconnecting switch(motor operating drive) D. Cut-out swich E. Power fuse F. Power switchs(kpfv) G.power switchs(kpfc)', 0, '#178 Eunhaengnamoo-ro,Yanggam-myun,Hwaseong-Shi,Kyungggi-do,Korea', '82-31-224-9093', '', '82-31-8059-8144', 'kdec9093@naver.com', '', '', '', 'www.kdelectric.co.kr', 'Inactive', '1.00', 1, ''),
(355, 'Dongwoo Electric Corp. / BMJE Marketing and Electrical Services Inc.', 'A.OUTDOOR TYPE VOLTAGE TRANSFORMER DPO-203N      -Insertion between phase and earth (phase and phase)      -Installation in any position      -Dry insulationin resin-outdoor installation      -Eco friendly voltage transformer is encapsulated with silica-filled epoxy resin      -Excellent electric characteristics and mechanical strength      -Maintenance-free      -Standard:IEC 61869-3, Ieee c57.13, JEC 1201      -Max. system voltage: 15.5 kV      -Rated power-frequency withstand voltage: 34 kV      -Rated lightning impulse withstand voltage: 110kV      -Rated frequency: 60Hz      -Rated primary current: 10-5A/600-300A      -Rated secondary current:5A      -Rated short time content: 100In/1s      -Rated Burden: B-1.9(22.5VA)      -Weight: 25kg      -Accuracy Class:0.3      -RF: 1.5      Creepage distance: 440mm  B.OUTDOOR TYPE CURRENT TRANSFORMER DCO-101A      -Installation in any position        -max voltage: 15.5kV      -Rated power frequency:34kV      -rated lightning impluse:110kV      -rated frequency: 60Hz      -rated primary voltage: 14560v      -rated secondary voltage: 208v      -Rated Burden: x(25VA)v      -Weight: 48kg      -Rated voltage factor: 125%      -Thermal Burden: 100VA      -Accuracy Class 0.3      -Voltage ratio: 70:1      -Creepage distance: 823 mm', 0, 'Blk. 209 lot 32, Labayane, St. North Fairview,Quezon City', '441-8431 / 352-1132 / 621-96060', '', '', 'jojo_bmjemarketing@yahoo.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(356, 'Samwon/BMJE Marketing and Electrical Incorporated', 'A.SAMWON TRANSFORMER B. PHASE POWER SUBSTATION TRANSFORMER      -High reliability power transformers are manufactured through insulation        design and optimum cooling structure design using accumulated technology C.POLE MOUNTED      -Pole transformer type for overseas export D.PHASE PAD MOUNTED TRANSFORMER- adequate to be used for a city center a school where the power part is not externally exposed as power is trasmitted through under ground cables. A device to protect the primary and secondary voltage parts can be installed in accordance with the user requirements E.PHASE DISTRIBUTION TRANSFORMER-it has low noise, superior circuit electro magnetic force withstanding and can be installed anywhere,indoor or outdoor', 0, 'Blk 209 Lot 32 Labayane St North Fairview Q.C. Phl. 6112', '441-8431, 372-60665, 352-1132', '', '621-9060', 'jojo_bmjemarketing@yahoo.com', '', '', '', '', 'Inactive', '1.00', 1, ''),
(357, 'Tan Delta Electric Corp.', 'SERVICES: A.PREVENTIVE MAINTENANCE,TESTING & COMMISSIONING OF HIGH AND LOW      VOLTAGE SUBSTATION EQUIPMENT      1. AC dielectric Withstand Test up to 500kV      2.Air and Structure Born Ultrasonic detection for(motors,air&gas leaks,corona)      3.AC VLF/DC High Potential Leakage Current Test      4.Battery capacity Discharge      5.Contact Resistance Test      6.Circuit Breaker Analysis      7. Earth and ground resistance      8. Instrument Transformer      9.Insulation Power      10. Insulation resistance Test      11.Generator/motor phase resolve partial discharge test      12.Lightning arrester leakage current test      13. Oil Dielectric Breakdown Voltage(DBV) test      14.Partial discharge analysis      15.Partial Discharge test      16.Power Quality Analysis      17.Protective Relay Testing and calibration      18. Sweep frequency Response Analysis      19.SF6 Gas Analysys (moisture&Purity)      20.SF6 Gas Recovery and Purification      21.SF6 Gas leak Detectio      22.Surge Comparison Test      23.Tap changer analysis      24.Transformer Turns Ratio test      25.Winding Resistance Test B. TRANSFORMER OIL LABORATORY SERVICES      1.Acidity      2.Color and Visual Examination      3.Corrosive sulfur     4.Degree of Polymerizatio     5.Dielectic Breakdown Voltage     6.Dissolve gas analysis     7.Furan Analysis     8.Interfacial Tension Test     9.Liquid power factor test     10.Metal Passivator     11.Oil Conductivity     12.Oxidation Inhibator Content     13.PCB test     14.Particle Count Analysis     15.Relative Density     16.Water-in-oil Analysis C. INFRARED/THERMAL SCANNING SERVICES     1. Electrical System/Mechanical System/Building Inspection     2. Manufacturing Process/Refractory/Furnaces/Energy Audits D. AIR & STRUCTURE BORNE ULTRASONIC INSPECTION SERVICES     1.Leak detection      2. Bearing Condition Monitoring     3. Ultrasound Based Lubrication     4. Steam Trap Inspection     5. Valves and Hydraulics     6. Pump Cavitation     7. Boiler,Heat Exchangers,Condenser Leaks     8.Electrical Corona Discharge     9. Tightness Integrity-wind noise &n water leak     10. Bearings   PRODUCT(PRODUCTS LISTING) A. AC/DC Dielectric Test System    a.1. AC/VLF Hi-pot Tet Set up to 200 kV    a.2. AC volatage/Current Impulse Test System    a.3. AC Resonant Test System    a.4. DC Hi-pot Set up to 350kV B.BATTERY TEST EQUIPMENT    b.1.Discharge/Capacity Test    b.2. Cell Resistance & Voltage Test    b.3. Specific gravity & temperature    b.4. Online Monitoring C.CURRENT INJECTION TEST SETS    c.1.Primary Current Injection uo to 15,000 apms    c.2. Secondary Current Injection/Protection Relay Test Set  D. DIGITAL HANDHELD INSTRUMENTS    d.1. AC/DC Clamp Ammeter    d.2. AC/DC Multimeter    d.3. Multi-Function Installation Tester E. ELECTRICAL TEST INSTRUMENTS    e.1. AC/DC VLF test set 0-12 KV    e.2. AC/DC Variable power supply 40amps,0-300 volts    e.3. Capacitance & Tan Delta/IPF Test set    e.4. Circuit breaker analyzer    e.5.Earth/Ground Resistance Tester    e.6. Instrument Transformer Test set    e.7. Insulation resistance Tester    e.8. Insulation Resistance Tester    e.9. Insulation oil dielectic breakdown voltage test set    e.10. Micro-ohmmeter 0-600 Amps    e.11. Partial discharge analyzer for transformer & rotating machines    e.12.Power Quality Analyzer    e.13. Surge/lightning Arrester Leakage Current Tester    e.14.Sweep Frequency Response Analyzer    e.15.Surge Tester/Analyzer for rotating machines    e.16.Transformer Turns ratio test set    e.17. Winding Ohmmeter & tap changer analyzer F.TRANSFORMER OIL LABORATORY INSTRUMENTS    f.1.Acidity Analyzer    f.2. Colorimeter    f.3. Corrosive sulfur    f.4. Dissolved Gas Ananlyzer    f.5. Furan Analyzer    f.6. Interfacial Tensiometer    f.7. Moisture Content Analyzer    f.8. Oil DBV Tester    f.9. Particle Count Analyzer    f.10.Oil Power Factor Tester    f.11. Viscosity Meter G. PREDICTIVE MAINTENANCE INSTRUMENTS    g.1. Thermal Imaging/Infrared Camera    g.2.Ultrasonic Detection(air/gas leak, bearing & corona) H. SF6 GAS TEST & HANDLING EQUIPMENT    h.1. SF6 Gas Analyzer(purity, moisture)    h.2.SF6 H=Gas infrared leak detector    h.3. SF6 gas recovery & filtration cart I. TRANSFORMER INSULATING OIL/MAINTENANCE EQUIPMENT    i.1. On-line/offline oil regeneration system    i.2. On-line/offline vacuum oil purification system    i.3. On-line OLTC Purifier w/ heater    i.4. Vacuum pumps & dehydration system    i.5. Dry air generator for XFMR Maintenanc J. TRANSFORMER ON-LINE MONITORING/ PROTECTION SYSTEM    j.1. Nitrogen Injection Explosion & fire Extinguising System    j.2.Bushing Monitoring System K. MISCELLANNEOUS PRODUCTS    k.1. Steel Pressed Radiators for transformers    k.2. On load Tap Changers', 0, '34A J.P. Rizal St. Project 4, Quezon City Phl 1109', '(632) 911-5858 / (632) 911-2073', '', '(632) 911-2157', 'sales@tandelta.com.ph / acctg@tandelta.com.ph', '', '', 'Dennis Tolentino- Technical Sales Supervisor, Tel No. 0917-520-9071', '', 'Inactive', '1.00', 1, ''),
(358, 'Solid Concrete Solutions', 'The only solid I-section Concrete Pole made in the PHL. Lomg lifespan, high strength  and wide range of sizes/classes. Heat/Fire resistance and corrosion free. The product  is pre-stresse,pre-tensioned rectangular concrete poles of "I" section shape specially  engineered and designed for PHL market. This product like wood and steel poles are  support structures for overhead power conduction and equipment   Advantages:    - The high strength-to-weight ratio of pre-stressed concrete power poles     sets them apart from poles made of other materials. Poles are thin and      functional yet relatively light and convenient to handle.   - Concrete power poles are more durable and resistant to weather and termites   - Do not contain any chemicals that will leech to the ground like those of      wood plates.   - Resilient and will cover from effects of a great degree of overload than any other     structural materials. They remain crack-free at working loads', 0, 'Suite 809 Richmonde Plaza N. 21 San Miguel Avenue cor Lourdes Drive Ortigas Center, Pasig City', '(02) 633-58921 / CP No. 09189208971/09178900565', '', '', 'lornasanguay7714@gmail.com', '', '', '', 'Plant Office: Km 208 Marthur Highway Brgy. Cauringan Sison Pangasinan - (075) 567-6117   Email: stresscrete.1998@gmail.com/spc@yahoo.com', 'Inactive', '1.00', 1, ''),
(359, 'Lushun Filtration & Purification', 'PRODUCTS: 1.Leybold Vacuum Pump 2. Vacuum Pump 3.Roots Pump 4. Leybold Roots Pump 5. Germany Water-ring Vacuum Pump 6. Imported Oil Pump 7. Screw Pump 8. Gear Pump 9. Inner Gearing Pump 10. Heater 11. Intelligent Temperature Controller 12. High Quality Wheels 13. Meter 14. Quick Change Coupler 15. Type 304 Stainless Steel Ball Valve 16. Filter 17. PVC Wire Reinforcing Tube 18. Coupling 19. Aluminum Board 20. Photoelectric Switch 21. 304 Check Valve 22. Separator 23.Flow Meter 24. Circuits', 0, 'Xianqiao Industrial Zone, Shapingla District, Chongqing,China  Zip:400037', '0086-23-65226013', '', '0086-23-65226013', 'sale@lushuntec.com', '', '', 'Isabel Wan- Sales Manager    Tel No. 8615826183872   Zip: 400037', '', 'Inactive', '1.00', 1, ''),
(360, 'APD Enterprises', 'PRODUCTS: > KSH - Filter Nozzle / PPN Strainer > LISSE - Filter Press, Filter Cloth > HAMON - Cooling Tower Spare Parts > FUCHS - Aerators for Waste Water Treatment > WIKA - Gauges (Pressure, Temperature & Level) > ICOM - VHF Transceiver Radio > INDUSTRIAL CHEMICALS:Ion Exchange Resin, Anti-scalant, Anti-foam    SERVICES: > Reconditioning of Valves & Pumps > Calibrations of Weighing Scale (Truck Scale)', 0, 'Door # 6B, The SITE Bldg., Mt. View (Buri) Road, Mandalagan, Bacolod City', '(034) 441-3732', '', '(034) 441-3732 / Mobile No.: 0915-702-7941 / 0917-585-8038', 'ma_teresagonzales@yahoo.com', 'COD', 'Distributor', 'Mr. Aljohn Dela Torre', 'Website: www.apd-enterprises.com, emaid add:  sales1@apd-enterprises.com / apd.enterprises@yahoo.com', 'Inactive', '1.00', 1, ''),
(362, 'GB Turbophil Turbocharger Service Repair & Parts Supply', 'Turbocharger repair parts and services', 0, 'Suba-Masulog Road, Lapu-Lapu City, Cebu', '(32)2606392', '', '', '', '', 'Contractor', 'Mr. Samuel John Rios - 0918-803-8644', '', 'Inactive', '1.00', 1, ''),
(363, 'Mustard Seed Systems Corporation - Bacolod', 'Door Access, Switch Hub, Ncomputing', 0, 'Door no. 5 SK Realty Building, Kamagong cor. 6th St, Bacolod City', '(034) 432 1650 / 707-1342', '', '', 'mary09mseedsystem@gmail.com', 'COD', 'Contractor / Distributor', 'Ms. Mary', '', 'Active', '1.00', 1, ''),
(364, 'PJL Auto  Center, Inc.', 'A Goodyear Servitek, is your one stop shop for all your vehicle needs and repairs.  It offers a wide selection of automotive tires, lubricants, and imported batteries as wells as car parts and accessories. It also provides repairs and maintenance services such as nitrogen tire fill, computerized engine system diagnosis, computerized wheel alignment, battery life testing, tire changing, wheel balancing, under chassis repair, chamber correction, suspension service, brake system servicing, oil change, total engine overhaul, fuel injection cleaning, air condition repair, among others. We also offer emergency rescue service for your vehicles. And with our highly trained, knowledgeable, and service oriented staff, we are here to address your every automotive need.', 0, 'Lacson Street, Brgy. Mandalagan, Bacolod City', '(034) 441-1222, 441-1444', '', '', 'contact@pjlgroup.ph', '', '', '', '', 'Active', '1.00', 1, ''),
(365, 'Tough Performance AutoWorkz', 'Wheel Alignment, Wheel Balancing, Change oil, Car Electrical Repair, Under Chassis Repair, Engine Tune-up, Suspension Modification, Car Body Repair and Painting, Car Audio Accessories, Change Car, Car Tint', 0, 'Circumferential Road, Brgy. Bata (In front of Adam''s Lodge), Bacolod City', '(034) 432 0544 ', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(366, 'TOWER Motors SHOP', 'Automotive Repair and Services', 0, 'Purok Hollowblocks, Lacson extension, Alabado street, Bacolod City', ' (034) 707 9947', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(367, 'Valing Auto Repair Shop', 'Automotive Repair and Services, Services Offered: Overhauling Engine Brake System Under Chassis And Other Mechanical Repair', 0, ' Lucerne Berne St., Helvetia Heights Subd., Bacolod City', '709 7224', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(368, 'H. Y. Hablo Services Company', 'Trucking Services', 0, 'Henrietta Village, Bacolod City', '0922-897-9326', '', '', '', 'COD', 'Trucking Services', 'Sir Yoyo', 'VAT Reg. TIN 450-101-583-0000', 'Active', '1.00', 1, ''),
(369, 'Castle''s Electronic Services', 'Electronics, Two Way Radio Products & Services', 0, 'Cor. 2nd Road-Burgos St., Villamonte, Bacolod City', '(034) 435-0992, 434-7429, 433-8467', '', '', '', 'COD', 'Supplier', 'Mr. Calixto Del Castillo III', 'NON VAT Reg. TIN 113-616-541-0000', 'Active', '1.00', 0, '113-616-541-0000NV'),
(370, 'Rosal Machine Services', 'Machine Shop, Fabrications, Threading, Machining', 0, 'Akishola Circumferential Road, Brgy. Villamonte, Bacolod City', '(034) 708-0216, 0920-983-0092, 0922-879-3905', '', '', '', '15 days PDC', 'Manufacturer', 'Mr. Rey Geronimo - General Manager', 'NON VAT Reg. TIN 475-877-774-0000', 'Active', '1.00', 0, ''),
(371, 'Bacolod Freedom Enterprises', 'Hardware, Electrical', 0, 'BS Aquino Drive, Bacolod City', '(034) 433-2130 / 432-0756 / 433-4664', '', '(034) 433-9054', '', 'COD', 'Supplier', 'Ms. Lalyn', '', 'Active', '1.00', 1, ''),
(372, 'New Bacolod Pyramid Construction Supply', 'Goulds Pumps Distributor, Pumps, Welding Machine, Tanks', 0, '507 BS Aquino Drive, Capitol Shopping Center, Benigno S. Aquino Drive, Bacolod City', '(034) 433-4648 to 49', '', '433-4649', 'rthurch@yahoo.com.ph', 'Cash', 'Distributor / Supplier', 'Mr. Arthur Ang', '', 'Active', '1.00', 1, ''),
(373, 'Arcspray Engineering Services', 'Thermal Spray, Turbine and crankshaft services', 0, 'Laray Road, Rjaj Bldg., B2, Cansaga Consolacion, Cebu', '(032) 423-0948', '', '', 'simonsingo38@yahoo.com / arcsprayengineering@yahoo.com', 'COD', 'Contractor / Supplier', 'Mr. Simon Sigo - 0939-9553-716 / 0977-0645-056', 'Affiliated to Flex-a-seal Industrial Supply and Services', 'Active', '1.00', 1, ''),
(374, 'Gendiesel Philippines, Inc.', 'GGensets (Generator Sets), Diesel Engines, Automatic transmissions, and Heavy-duty trucks, Generating Set (Brand New & Slightly Used), Generator Parts & Accesories, Synchronizing and Switch gear, AVR, Transformers, Chemicals, AVR', 0, 'Liroville Subdivision, Singcang-Airport, Bacolod City', '(034) 433-8518', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, '000-120-669'),
(375, 'MAXSAVER', 'CCTV  ', 0, 'Rizal St., Bacolod City', '(034) 435-1930', '', '', '', 'COD', 'Supplier ', '', '', 'Active', '1.00', 1, ''),
(376, 'Security Warehouse Philppines Inc.', 'CCTV (Rover Systems)', 0, 'Unit CZ204, 2/F Cyberzone, Rizal St., Reclamation Area, Bacolod City', '(034) 704-2271', '', '(034) 704-2271', '', 'COD', 'Distributor', 'Mr. Filart Juridico', '', 'Active', '1.00', 1, ''),
(377, 'Zalia Information Technology Solutions (ZITS)', 'CCTV    ', 0, 'Bacolod City', '0933-869-1612 ', '', '', 'lredoblo@gmail.com', '', '', '', '', 'Active', '1.00', 1, ''),
(378, 'IPC Security Surveillance System Gadgets & Electronics Center', 'CCTV (Hikvison)', 0, 'Door 4 Sun-In Bldg., Lacson St., Bacolod City', '(034) 704-2330', '', '704-2330', 'ipcbacolod@yahoo.com', 'COD', 'Contractor / Distributor', '', '', 'Active', '1.00', 1, ''),
(379, 'Central Sales and Heavy Equipment Service', 'Hydraulic Jacks Repair and Servicing, Heavy Equipment Services', 0, '39-1 Rizal St., Bacolod City', '(034) 435-5860', '', '', '', 'COD-upon completion', 'Repairs & Services for Heavy Equipments', 'Mr. Romeo Andrada', 'TIN No: 077-183-783-199', 'Active', '1.00', 0, '077-183-783-199NV'),
(380, 'NCH Philippines Inc.', 'Water Treatment, Cold and High Temp. Sealant, Degreaser, Lubricants', 0, 'Bet. Kms 19 & 20 North Ortigas Ave., Ext., Cainta, 1900 Rizal', '(02) 655-7389 to 7392', '', '(02) 656-8063', 'Rotchel.Mendoza@NCH.com', '30 days upon Delivery of Chemicals', 'Manufacturer, Contractor', 'Engr. Rotchel Mendoza - 09177016793', '', 'Active', '1.00', 1, ''),
(381, 'Alpha Pacific Electric Co., Incorporated', 'Molded Circuit Breaker, 250 amps, 3 pole, 18 kaic @ 480 VAC without Lugs, Model: EXC250F3250, Brand: Schneider', 0, 'Madison Manor Condominium, Alabang-Zapote Road, Las Pinas City, Metro Manila', '(02) 800-0489 / 800-0870', '', '', '', 'COD', '', 'Mr. Rodel De Lara', '', 'Active', '1.00', 1, ''),
(382, 'Lancet Enterprises', '', 0, '2251-C Adonis St, Bgy 862, Zone 094 STA Ana, Manila', '(02) 254-7292', '', '', '', '15 days PDC', '', 'Mr.Alan Ferrer', '', 'Active', '1.00', 1, ''),
(383, 'Marshal Electrical & Metal Products Co. Ltd.', '', 0, 'Lot7 Blk2Orion St., Sterling Ind''l. Park, Meycuayan, Bulacan', '(044) 836-1865 / 417 0101', '', '', '', 'COD', '', 'Mr.Kirby King', '', 'Active', '1.00', 1, ''),
(384, 'NG CHUA TRADING', '', 0, 'Espana Tower, 2203 Espana Street, City of Manila, Metro Manila', '(02) 354-9808 / 353-7620', '', '', '', 'COD', '', 'Mr. Leo Ace Devis', '', 'Active', '1.00', 1, ''),
(385, 'RS Components', 'Electrical Supplies', 0, '21 Floor, Multinational Bancorporation Center, Ayala Avenue, Makati, 1226 Metro Manila', '(02) 888-4030', '', '', '', '30 days PDC upon Delivery', 'Distributor / Supplier', 'Ms. Donna Quejano', '', 'Active', '1.00', 1, ''),
(386, 'Best Electrical Components, Inc.', '', 0, 'Omron-APP Bldg., 40 Buendia Avenue, Between Bautista St. and Dian St., Makati', '(02) 843-0785', '', '(02)843-0675', '', '30 days PDC upon Delivery', '', 'Mr. Ferdie', '', 'Active', '1.00', 1, ''),
(387, 'Rozemar Hardware', '', 0, '1528 Alvarez St, Bgy 321, Zone 032 STA Cruz, Manila', '(02) 731-5140', '', '', '', 'COD', '', 'Mr. Chris Austria', '', 'Active', '1.00', 1, ''),
(388, 'Portalloy Industrial Supply Corporation', '', 0, '1011-1013 Oroquieta Street Sta. Cruz, Manila', '(02) 733 7957 / 734-8137', '', '', '', 'COD', '', 'Mr. Chris', '', 'Active', '1.00', 1, ''),
(389, 'Maximum Electronics & Communications Inc.', '', 0, '123 Kamuning Rd, Diliman, Quezon City, Metro Manila', '(02) 929 9511 / 412-7849', '', '', '', 'COD', '', 'Mr. / Ms. Danny / Helen Ferrer', '', 'Active', '1.00', 1, ''),
(390, 'Maximum Electronics & Communications Inc.', '', 0, '23 Kamuning Rd, Diliman, Quezon City, Metro Manila', '(02) 929-9511 / 412-7849', '', '', '', 'COD', '', 'Mr. Danny / Ms. Helen Ferrer', '', 'Active', '1.00', 1, ''),
(391, 'Maximum Electronics & Communications Inc.', '', 0, '123 Kamuning Rd, Diliman, Quezon City, Metro Manila', '(02) 929-9511', '', '', '', '', '', '', '', 'Active', '1.00', 1, ''),
(392, 'Maximum Electronics & Communications Inc.', 'VHF/FM Portable Radio. Brand: Motorola', 0, '23 Kamuning Rd, Diliman, Quezon City, Metro Manila', '(02) 929-9511', '', '', '', 'COD', '', 'Ms. Helen', '', 'Active', '1.00', 1, ''),
(393, 'Blue Sapphire Telecoms', '', 0, 'Unit 1101 Entrata Tower 1, 2609 Civic Drive Filinvest  Alabang, Muntinlupa City', '(02) 846-7876 / (02) 404-8387 / 514-8727 / 553-6526 / 553-6529', '', '(02) 846-2758', 'sales@bstelecoms.com.ph', 'COD', '', 'Ms. Malu Mendoza', '', 'Active', '1.00', 1, ''),
(394, 'Maximum Electronics & Communications, Inc.', '', 0, '123 Kamuning Rd, Diliman, Quezon City, Metro Manila', '(02) 929-9511 / 412-7849', '', '', '', 'COD', '', 'Ms. Helen', '', 'Active', '1.00', 1, ''),
(395, 'Bacolod Plastic Supply ', 'Plastic Supplies', 0, '5 Hilado St, Bacolod City', '(034) 434-0067', '', '', '', 'COD', '', '', '', 'Active', '0.00', 1, '104-068-573-0000'),
(396, 'CITI Hardware - Tangub Branch', '', 0, 'Araneta Street, Brgy. Tangub, Bacolod City', '(034) 444-0591 / (034) 704-3400', '', '(034) 704-3400', 'tangub@citihardware.com', '', 'COD', '', '', 'Active', '1.00', 1, ''),
(397, 'D.C. Cruz Trading Corp. ', '', 0, '158-C Singcang, Bacolod City', '(034) 434-3944', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(398, 'Firebase Industrial Supply and Services', '', 0, 'Bacolod City', '(034) 445-0689', '', '', '', '15 days PDC', '', 'Mr. Rommel Genovia - 0919-692-5104', '', 'Active', '2.00', 0, ''),
(399, 'Kelvin Nicoli Enterprises', '', 0, 'Gatuslao St, Brgy. 15, Bacolod City', '(034) 476-9756 / (034) 433-4441', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(401, 'Bacolod Triumph Depot', 'Bosch dealer', 0, 'Hilado St., Bacolod City', '(034) 434-0111', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(402, 'Modbus Electrical Supplies Corp.', '', 0, 'The Big Orange Building, 328 Edsa Avenue, Caloocan City, Metro Manila', '(02) 361-0500 / 361-0500', '', '', '', '', '', 'Mr. Allan Que', '', 'Active', '1.00', 1, ''),
(403, 'Upshaw Industrial Corporation', '', 0, 'Room 201, VAG Building, Ortigas Avenue, San Juan City, Metro Manila', '(02) 721-5451', '', '', '', '', '', 'Mr. Armando Noga', '', 'Active', '1.00', 1, ''),
(404, 'West Point Engineering Supplies', '', 0, 'West Point Bldg. Bacood St. Brgy. Patubig,, Marilao, Bulacan, Patubig Rd, Marilao, Bulacan', '0917 801 4750 / (044) 797-2524', '', '', '', 'COD', '', 'Ms. Jessa Paglinawan', '', 'Active', '1.00', 1, ''),
(405, 'West Point Engineering Supplies', '', 0, 'West Point Bldg. Bacood St. Brgy. Patubig,, Marilao, Bulacan, Patubig Rd, Marilao, Bulacan', '0917 801 4750 / (044) 797-2524', '', '', '', '', '', 'Ms. Jessa Paglinawan', '', 'Active', '1.00', 1, ''),
(406, 'Test Equipment Connection', '', 0, '30 Skyline Dr, Lake Mary, FL 32746, USA', '+1 407-804-1299 / 800-615-8378  loc. 174', '', '', '', '', '', 'Mr. John Bahng', '', 'Active', '1.00', 1, ''),
(407, 'Carvi-Upholstery & Home Supply', 'Tarpaulin', 0, 'Gonzaga St, Bacolod City', '(034) 434-5020', '', '', '', 'COD', '', '', '', 'Active', '1.00', 1, ''),
(408, 'CMC 417 Enterprises Corporation', 'Plastic Supplies', 0, 'Hilado St., Bacolod City', '(034) 476-9756 / 704-1311 / 702-8402', '', '', '', '', '', 'Ms. Rain', '', 'Active', '0.00', 1, '459-773-349-001'),
(409, 'Chris Marine (Sweden)', '', 0, 'Stenyxegatan 3 Fosie, Malmö', '+46 733 518466', '', '', '', '', '', 'Mr. Ralph Rosengren', '', 'Active', '1.00', 1, ''),
(410, 'Asell Tglobal Inc', '', 0, '40 London Street, Capitol Homes Old Balara, 1100 Quezon City, Metro Manila', '709-0842', '', '', '', '', '', 'Mr. Ramil Cornico', '', 'Active', '1.00', 1, ''),
(411, 'Panda Construction Supply Incorporated', '', 0, '405 Nueva Street (E T. Yuchengco), Manila City, Metro Manila', '(02) 236-5500 / 716-8361', '', '', '', '', '', 'Mr. Romy', '', 'Active', '1.00', 1, ''),
(412, 'Josmee', 'Medical Supplies', 0, 'Bacolod City', '(034) 474-0388', '', '', '', 'COD', 'Distributor', 'Ms. Johanah', '', 'Active', '1.00', 1, ''),
(413, 'Medical Center Trading Corporation', 'Medical Supplies', 0, 'Burgos-Lacson Street, Brgy.19, Bacolod City', '0908 898 0274', '', '', '', 'COD', 'Distributor', 'Ms. Angelouan Molina', '', 'Active', '1.00', 1, ''),
(414, 'Hardware and Industrial Solutions Incorporated', '', 0, '56 Madison Street, Mandaluyong City', '(02) 631-8366 / 638-1432', '', '', 'mventigan@uptown.com.ph', '', '', 'Ms. Melanie Ventigan', '', 'Active', '1.00', 1, ''),
(415, 'Rainehans Trading', '', 0, 'Manila', '(02) 756-0674', '', '', 'rainehanstrading@gmail.com', '', '', 'Ms. Alma Yap', '', 'Active', '1.00', 1, ''),
(416, 'MD Trade & Spares GmbH', '', 0, 'Alte Kreisstrasse 1, 39171 Sülzetal, Germany', '+49 391 727678-13', '', '', '', '', '', 'Mr. Steven Wdent', '', 'Active', '1.00', 1, ''),
(417, 'ENEX Maschinenhandel- und Ersatzteilservice GmbH', '', 0, 'Schnackenburgallee 116, 22525 Hamburg, Germany', '+49 40 5472160', '', '', '', '', '', 'Ms. Susanne Strauss', '', 'Active', '1.00', 1, ''),
(418, 'MOTEX Teile GmbH (Philippines)', '', 0, '21423, Winsen (Luhe) , Niedersachsen Germany', '+49-417188570', '', '', '', '', '', 'Mr. Melvin Sitaca', '', 'Active', '1.00', 1, ''),
(419, 'Twinco Pte Ltd', '', 0, '3 Loyang Way 4, Singapore 506956', '+65 6542 9618', '', '', '', '', '', 'Mr. Kenneth Ng', '', 'Active', '1.00', 1, ''),
(420, 'Industrial & Marine Services Eng (Malaysia)', '', 0, 'Malaysia', '+603 5524 6898', '', '', '', '', '', 'Pang Siew Mei', '', 'Active', '1.00', 1, ''),
(421, 'Industrial & Marine Services Eng (Malaysia)', '', 0, 'Malaysia', '+603 5524 6898', '', '', '', '', '', 'Pang Siew Mei', '', 'Active', '1.00', 1, ''),
(422, 'All Tools Industrial Sales and Services', 'Tools, Safety Products, Hardware, Confined Space Automatic Fire Suppressor System, Personal Protective Equipment (PPE) such as Hard Hats, Safety Gloves, Safety Shoes, Polar Tools, On-line Non-Chemical Industrial Water Treatment System, Battery On-line Monitoring and Maintenance System:  Inuo-BMM, Insulating Oil Purifier and Degassing System, On-line Generator and Motors Condition Monitoring System, Sensors such as Epoxy Mica Capacitors, Stator Slot Couplers, EVAII Fiber Optic Accelerometer, Air Gap Sensor, Flux Probes, FFProbe, Offline Test Instruments\r\nServices: \r\nPower System Short Circuit Analysis and Protective Relay Coordination Settings\r\nPower Quality Measurement of the Power System\r\nSupply, delivery, installation, wiring, testing and commissioning of multifunction overcurrent protection relay\r\nSupply of labor, tools, test equipment and technical expertise in testing of  Multifunction Generator Protective Relays, Substation and Distribution Protective Relays.\r\nSupply of labor, tools, test equipment and technical expertise in testing of Medium and High Voltage Power Circuit Breakers with its Timing Measurement, Motion or Travel Measurement, Coil Current Profile and Contact Dynamic and Static Resistance Measurement\r\nSubstation Battery Capacity Test\r\nPower Equipment and Cable Insulation test and many others\r\nOn-line Maintenance of Power Station Battery System with analysis', 0, '12 Block 1 Lot 4 United Glorietta, Cañogan, Pasig City, Metro Manila, 1605', '(+632) 922-8810472  (+632) 917-8761210 / (02) 542-0988 / 641-9811 / 903-0574 / 09228810472', '', '(02) 640-3898', 'laarni.mata@alltoolsindustrial.com', 'Advance Payment', 'wholesaler / Retailer / Distributor', 'Ms. Laarni G. Mata', 'www.alltoolsindustrial.com\r\nlaarni.mata@alltoolsindustrial.com\r\nWebsite: www.alltoolsindustrial.com', 'Active', '1.00', 1, ''),
(423, 'MACYS Photo Video Audio Store', 'Electrical Equipment Case, camera shop', 0, 'GF, F14 , APM Shoping Mall, A. Soriano Avenue, Cebu City', '(032) 418-1008 / 0922 856 2297 / ', '', '', 'inquiry@macyscamerashop.com', 'Advance Payment', 'Distributor', 'Ms. Kathlyn', '', 'Active', '0.00', 0, ''),
(424, 'New Llacer Electronics & Electrical Supply', 'Electronics / Battery', 0, 'Gonzaga St., Bacolod City', '(034) 433-5658', '', '', '', 'COD', 'Wholesale / Retail', '', '', 'Active', '1.00', 1, ''),
(425, 'Genuine Mercantile Corp.', 'Auto Supplies and Accessories', 0, 'CMU Bldg., Gonzaga St., Bacolod City', '(034) 434-7923 / 434-7924 / 434-7925', '', '(034) 435-0965', 'genuinemercantile@gmail.com', 'COD', 'Distributor', 'Mr. Juje Valencia', '', 'Active', '0.00', 0, '004-247-920-0000'),
(426, 'Philippine HOH Industries, Incorporated (PHII)', 'water and waste water treatment chemicals and equipment, Design and installation of process, water and waste water treatment systems, Supply of chemicals and miscellaneous materials and parts related to process, water, and waste water treatment, Supply of equipment and systems for process, water, and waste water treatment. WATER AND WASTEWATER EQUIPMENT AND PARTS\r\n> CHEMICAL DOSING/METERING PUMP > CHEMICAL MIXERS (MOTOR/SHAFT/ PROPELLER)\r\n> CHEMICAL PUMPS > SUBMERSIBLE PUMPS\r\n> IWAKI PUMPS > CHLORINATOR PUMP\r\n> ROOTS BLOWER > DIFFUSSER (FINE & COARSE BUBBLES)\r\n> BAG FILTER VESSEL > MEMBRANE PRESSURE VESSEL (4” & 8” DIAMETER)\r\n> FILTER PRESS SYSTEM (EQUIPMENT) > SEDIMENT CARTRIDGE VESSEL\r\n> FILTER BAG VESSEL > MULTIMEDIA FIL TER TANK (MMF)\r\n> ACTIVATED CARBON FILTER TANK (ACF) > SOFTENER SYSTEM\r\n> SAND FILTER SYSTEM (SF) > REVERSE OSMOSIS SYSTEM (RO)\r\n> ULTRAFILTRATION SYSTEM (UF) > NANOFILTRATION SYSTEM (NF)\r\n> WASTEWATER TREATMENT PLANT FACILITY (WWTP) > SEWAGE TREATMENT PLANT (STP)\r\n> SILICA SAND > ACTIVATED CARBON GRANULES\r\n> ANTHRACITE > PEBBLES\r\n> FLOW METERS > PH METERS\r\n> CONDUCTIVITY/TDS METERS > DISSOLVED OXYGEN (DO) METER\r\n> PH /ORP CONTROLLER > TDS /CONDUCTIVITY CONTROLLER\r\n> LABORATORY FUMEHOODS > CHEMICAL FUME SCRUBBER\r\n> ODOR FUME SCRUBBER > WET SCRUBBER\r\n> FUME SCRUBBER > OFFICE/FACTORY/CLEANROOMS FACILITIES\r\n> FIBERGLASS TANKS\r\n\r\nWASTEWATER TREATMENT CHEMICALS\r\n> HEAVY METAL PRECIPITANT\r\n> COAGULANTS\r\n> FLOCCULANTS\r\n> ENZYME/BACTERIA\r\n> CHLORINATION\r\n> DE-CHLORINATION\r\n> UREA\r\n> PHOSPHORIC ACID\r\n> FILTER PRESS CLOTH', 0, 'Unit 241 Cityland Dela Rosa Condominium7648 Dela Rosa St., Pio Del Pilar, Makati City, 1230', '(02) 818-6725, (02) 810-9282', '', '(02) 810-9282', 'mikecylanan@yahoo.com or m.ylanan@philhoh.com', 'COD', 'Contractor, Manufaturer', 'Mr. MICHAEL “MIKE” YLANAN - 0949-9948024', '', 'Active', '0.00', 0, ''),
(427, 'Federal Express Pacific, LLC', 'Cargo Forwarder    ', 0, 'Bacolod City', '0916-852-1463 / ', '', '', '', 'COD', 'Forwarder', '', 'VAT Reg TIN 275-540-614-00000', 'Active', '0.00', 0, ''),
(428, 'TGA Chemical Enterprises', 'Manufacturer, Liquid Soap, Cleaning Chemicals', 0, 'Burgos St., Bacolod City', '(034) 432-1899', '', '', '', 'COD', 'Manufacturer', '', 'non VAT Reg TIN 187-521-215-001', 'Active', '0.00', 0, ''),
(429, 'City Vet Trading', 'Agricultural Chemicals', 0, 'Mabini-Libertad St., Bacolod City', '(034) 4340869', '', '', '', 'COD', 'Distributor', '', '', 'Active', '0.00', 0, ''),
(430, 'Crown Agri-Trading Corp.', 'Agricultural Chemicals', 0, 'D-47 Narra Avenue, Capitol Shopping Center, Bacolod City', '(034) 434 5322', '', '', '', '', 'Distributor', '', '', 'Active', '0.00', 0, '005-429-634-0000'),
(431, 'MTG Gasoline Service Station', 'Shell Distributor, Diesel, Gasoline', 0, 'Araneta St., Tangub, Bacolod City', '(034) 474-2431 / 09173014439', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(432, 'Yale Hardware Corp.', 'Hardware, Industrial Tools', 0, 'Punta Del Norte Bldg, Cor.Mc Briones , MJ Cuenco, Cebu City', '(032) 255 8891', '', '', '', 'Advance Payment', 'Wholesale / Retail / Distributor', '', '', 'Active', '1.00', 1, ''),
(433, 'Cebu Atlantic Hardware Inc.', 'Line Hardware', 0, '66-72 B. Aranas St, Cebu City', '(032) 261 4692', '', '', '', 'Advance Payment', 'Distributor', '', '', 'Active', '1.00', 0, ''),
(434, 'SM Appliance Center', 'Home Appliances', 0, 'Father M. Ferrero St, Bacolod City', '(034) 468-0080', '', '', '', '', 'Distributor', '', '', 'Active', '0.00', 0, ''),
(435, 'Imperial Appliance Plaza', 'Home Appliances', 0, '69-2 Araneta Ave, Bacolod City', '(034) 435-0469', '', '', '', 'COD', 'Distributor', 'Ms. Jenry', '', 'Active', '1.00', 1, ''),
(436, 'ESAA CCTV and Computer Solution', 'CCTV Installation and Parts, Computer, Laptop, Printer', 0, 'Bacolod City', '(034) 704-8259', '', '', '', 'COD', 'Installer, Contractor, Distributor', '', '', 'Active', '1.00', 1, ''),
(437, 'JPEL Construction Supply & Services', 'Aggregates', 0, 'Crossing High School, Brgy.  Lag-asan Bago City', '0929-3500-395 / 0939-306-5115 0926-685-5154', '', '', '', 'COD', '', 'Mr. Joemar T. Pellejo', '', 'Active', '0.00', 0, ''),
(438, 'Rowel Hydraulic Hose Center', 'Hydraulic Hose ', 0, 'Lacson St., Bacolod City', '(034) 434 1611', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(439, 'GC & C, INC.', 'Aggregates', 0, 'Carlos Hilado Ave. Circumferential Road, Barangay Bata, Bacolod City', '(034) 441-2409; Local 105/115', '', '', '', '50% down, 50% upon delivery', 'Manufacturer', 'Ms. Jeselle Hisanza', '', 'Active', '1.00', 1, '005-429-626-0000'),
(440, 'Kippy Rubber Trading', 'All kinds of rubber moulded products for sugar mills and highly technical compound rubber products', 0, '#2 Kanla-on St., Mt. View Subdivision, Mandalagan, Bacolod City', '(034) 713-1577 / 0943-535-8102', '', '', '', 'COD-upon completion', '', 'Ms. Aleen Aspera', '', 'Active', '2.00', 0, ''),
(441, 'Citi Hardware - Mandalagan', 'Hardware,Tools, Tiles, Furnitures ', 0, '24th Lacson St., Bacolod City', '(034) 432 3944', '', '', '', 'Cash', 'Distributor', '', '', 'Active', '1.00', 1, '005-919-438-002'),
(442, 'T5 Sumag Enterprises, Inc.', 'Aggregates', 0, 'Sum-ag, Bacolod City', '(034) 444-0491', '', '', '', '50% downpayment, 50% upon delivery', 'Distributor', 'Mr. Wilfredo Tan', '', 'Active', '0.00', 0, ''),
(443, 'Elastic Industrial Sales', 'PVC Pipes and Fittings    ', 0, 'Jl Building, Burgos Street, Bacolod City', '(034) 433-7540 / 434-5812', '', '', '', 'COD', 'Distributor', '', '', 'Active', '0.00', 0, '113-644-838'),
(444, 'NHK Glass and Aluminum Enterprises', 'Glass Installation, Window Glass', 0, '560, B.S. Aquino Drive, Bacolod City', '(034) 432-3106', '', '', '', 'COD - upon completion', 'Manufacturer / Contractor / Distributor', 'Sir Nonoy', '', 'Active', '1.00', 0, ''),
(445, 'Pacific Ads Creative Outdoor', 'Tarpaulin Printing, Signages', 0, 'Door # 5 Vemre Bldg., Rizal St., Bacolod City', '(034) 434-2360 / 708-7785 / 435-1095', '', '', 'pacificadscreative@yahoo.com / pacificadscreative@gmail.com', 'COD upon completion', '', '', '', 'Active', '0.00', 0, ''),
(446, 'MJD Motor Parts Supply', 'Auto Supply', 0, 'Rizal St., Bacolod City', '(034) 708-7940', '', '', '', '', '', 'Sir Bonnie', 'non Vat TIN 121-335-248-0000', 'Active', '0.00', 0, ''),
(447, 'Hiltor Corporation', 'Trucking Services', 0, 'San Sebastian St., Bacolod City', '(034) 433-8872', '', '', '', 'COD upon completion', '', '', '', 'Active', '0.00', 0, ''),
(448, 'AJAT Phil. Inc.', 'Chemical Disposal', 0, 'Lot 3&5 Salangsang Subd., Guinto St, General Santos City, South Cotabato', '0925-501-9737 / (083) 552 9021', '', '', '', '', '', '', '', 'Active', '0.00', 0, ''),
(449, 'EMB (Energy Management)', 'Chemical Disposal', 0, 'Philippines', '0917-314-1892', '', '', '', '', '', '', '', 'Active', '0.00', 0, ''),
(450, 'Cebu Oversea Hardware', 'Structural Steels, Wires, Tools', 0, '82 Plaridel St., Cebu City, 6000 Cebu', '(032) 254-1511 / 412-0107', '', '', '', 'Advance Payment', 'Distributor', 'Mr. Cresencio Lariosa', '', 'Active', '0.00', 0, '000-177-760'),
(451, 'TDT Powersteel Corp.', 'Structural Steels', 0, 'M.C Briones St. Highway Mandaue, Cebu City of Mandaue City, Cebu', '(032) 236 4052 / 236 4052 / 236 5011 / 0917-654-2032', '', '(032) 236 4052', '', 'Advance Payment', 'Distributor', 'Mr. Michael Mangubat', 'Contact Person: Michael V. Mangubat', 'Active', '1.00', 1, '');
INSERT INTO `vendor_head` (`vendor_id`, `vendor_name`, `product_services`, `category_id`, `address`, `phone_number`, `mobile_number`, `fax_number`, `email`, `terms`, `type`, `contact_person`, `notes`, `status`, `ewt`, `vat`, `tin`) VALUES
(452, 'Heaters Instrumentation and Control Equipment Corporation', 'Tubular Heater, Cartridge Heater, Band Heater, Strip Heater, Temperature and Humidity Controllers, Thermostat and Thermocouples, Quarts, Titanium, Teflon Infrared, Monel, SS 316, Heaters for Chemical, Immersion Heaters for Industrial Use, Circulation Heater for:   • Thermal Heat Transfer Oil,   • Heavy Bunker Oil, • Water, Steam Super Heater, Swimming Pools and Sauna,  • Hot Air Blower and Dryers', 0, '228 7th Avenue (West) corner Rizal Avenue, Caloocan City  Metro Manila 1400, Philippines', '(02) 367-2062 / 794-9761', '', '(02) 362-0653', 'heatersinstrumentation@yahoo.com', 'COD', '', '', '', 'Active', '0.00', 0, ''),
(453, 'Aleja Blower Corporation', 'Blower, Industrial Exhaust Fans', 0, '#457-A Boni Ave., Mandaluyong City', '(02) 532-5675, 532-5860, 532-6137', '', '(02) 535-2059', 'randy.lucas@aleja.com.ph', 'COD', 'Manufacturer', 'Mr. Randy Lucas', '', 'Active', '0.00', 0, ''),
(454, 'Stratman Turbo Fans and Blowers', 'Blower, Industrial Exhaust Fans', 0, '# 47 Bach St. Capitol District Fairview Quezon City', '(02) 930-8992 / 427-1391 / 428-1175 / 930-8939', '', '', 'sales@granstratman.com', '', '', 'Ms. Vanessa', '', 'Active', '0.00', 0, ''),
(455, 'Fil General Blower Corp.', 'Blower, Industrial Exhaust Fans', 0, '16 Mabolo Road Northern Hills, Malabon', '(02) 361-2659, 361-2663, 361-0652, 447-6721, 447-6722', '', '(02) 361-4189', 'sales@filgenblowers.com', 'COD', 'Manufacturer', '', '', 'Active', '0.00', 0, ''),
(456, 'Katmar Industrial Center', 'Blower, Industrial Exhaust Fans', 0, 'L2F, First Street , Golden Gate Avenue, Park Homes Commercial Complex, Tunasan, Muntinlupa City', '(02) 842-8171 / 809-7723 / 973-8053 / 986-8026', '', '09209235508', 'ding_katmar@yahoo.com', 'COD', 'Manufacturer', 'Mr. Ronnie', '', 'Active', '0.00', 0, ''),
(457, 'MFive Industrial & Construction Supplies', 'TIG Machine, industrial tools', 0, '2870 Hilado Extension, Capitol Shopping Center, Bacolod City', '(034) 432-7493', '', '', '', 'COD', 'Distributor', 'Miss Cathy / Sir Mark', '', 'Active', '0.00', 0, ''),
(458, 'Robinsons Appliances - Bacolod ', 'Hardware, Appliances', 0, 'Robinsons Place Bacolod, Lacson Street, Mandalagan, Bacolod City, ', '(034) 441-2662', '', '', '', 'Cash', '', '', '', 'Active', '0.00', 0, ''),
(459, 'Insuphil Industrial Corp.', 'Ceramic Fiber, Insulation materials, Rockwool', 0, 'IIC Bldg., Mabugat Road, Tabok, Mandaue City', '(032) 344-6268/514-8938; 344-6756/345-1070', '', '', 'insuphil@yahoo.com', 'advance payment', 'Distributor', 'Mr. John A. Tabucanon', '', 'Active', '0.00', 0, ''),
(460, 'IMX-Europacific Industries Corp.', 'Repair and Maintenance Electrodes, Butterfly Valves and Controls, Check Valves, Flexible Cables, Gauges, Delta Systems Steam Traps, Mechanical Devices, Control & instrumentation, electrical components, Carboweld Electrodes, Actuators.', 0, 'Sool 2, Silay City, Negros Occidental ; Unit 202 Avenue Square Garden Bldg., 532 U. N. Ave. cor. J. Bocobo St., Ermita, Manila, Phils.', '(02) 522-1782 / 526-8157 / 526-7227 / 400-2226 / 09227989946', '', '(02) 526-0705', 'richeltm@imx.com.ph', 'COD', 'Distributor', 'Ms. Richel T. Madayag', '', 'Active', '0.00', 0, ''),
(461, 'H. V. Power Concepts', 'VRLA Battery, UPS, Connectors', 0, 'Unit 107 The Orient Bldg., General Echavez Street, Cebu City', '(032) 231 1004', '', 'hanz.goopio@hvpowerconcepts.com', '', 'Advance Payment', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(462, 'GC Appliance Centrum', 'Appliances', 0, 'Rizal-Lacson Sts., Bacolod City', '(034) 434 6995', '', '', '', 'Cash', 'Distributor', 'Ms. Marivic', '', 'Active', '1.00', 1, ''),
(463, 'Asian Home Appliance', 'Appliances', 0, 'Ground Floor Ayala Mall, The district north point, Talisay City', '(034) 441 6553', '', '', '', 'Cash', 'Distributor', 'Ms. Camille', '', 'Active', '1.00', 1, ''),
(464, 'ICO Electrical Services', 'Electrical Services, Repair of Electric Motors', 0, 'M. C. Briones, Mandaue City, Cebu', '(032) 346 4690', '', '', '', 'Advance Payment', 'Electrical Servicing', 'Mr. Erwin Salvador', '', 'Active', '1.00', 1, ''),
(465, 'PMS Electrical Sales & Services', 'Current Transformers and Regulators, Universal Changeover Switch, Circuit Breakers, Record Breakers, Relays, Fuses, Limit Switches, Command Switches, Shunt Capacitors, Timers and Sockets, FS-Box, Metallic Outlet Boxes, Conduit Fittings, Polyphase Meters, Vacuum Circuit Breakers, Frequency Inverters, Inverter Accessories, Molded Case Circuit Breakers, Air Circuit Breakers, Earth Leakage Circuit Breakers, Wall Lights, Rectangular Flood Lights 1000W', 0, 'Door # 1 CTD Bldg., Mabini Corner Zulueta Sts., San Roque Cebu City, 6000', '(032) 406 6980', '', '(032) 236 5890', '', 'Advance Payment', 'Distributor', '', '', 'Active', '2.00', 1, ''),
(466, 'BJ Marthel International, Inc.', 'Machinery Parts for Diesel Engines, Industrial Machinery and Spare Parts, Marine Diesel Engines, Parts and Accessories, Marine Ship Deck and Engine Auxiliary Equipment and Spare Parts, Stationary and Portable Generator Sets, Construction and Earth Moving Equipment: Backhoe Loaders, Skid Steer Loaders, Excavators, Motor graders, Vibratory Compactors, Bulldozers, Fire Fighting Trucks and Equipment: Fire Trucks, Fire Extinguishers, Fireman?s Safety Apparel, Fire Hoses, Absorbents for Chemical and Oil Spills, Construction and Hydraulic Tools and Equipment, Sewer and Catch basin Vacuum and Jetting Equipment, Dredgers, Port Cargo Handling Equipment, Water Filtration, Disinfection and Supply Systems, Tools and Equipment for the Power and Telecommunication Industries', 0, 'Door No. 2 Angela Building, Mandalagan Highway', '(034) 708-7217 / 09328504522', '', '', '', 'COD', 'Distributor', 'Sir Francis', '', 'Active', '0.00', 1, ''),
(467, 'Samson Merchandising Inc.', 'Car Battery, Tires', 0, '96, Lacson Street, Bacolod City', '(034) 433 1208', '', '', '', 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(468, 'DHL Express (Philippines) Corp.', 'Forwarder', 0, '9, Esteban Building, Lacson St, Bacolod City', '(034) 435 0581', '', '', '', 'COD', 'Forwarder', '', '', 'Active', '1.00', 1, ''),
(469, 'Philippine Airlines (PAL) CARGO', 'Air Cargo Forwarder', 0, 'Silay City, Negros Occidental', '(034) 436 5772', '', '', '', 'COD', 'Forwarder', '', '', 'Active', '0.00', 0, ''),
(470, 'Windoor Glass and Aluminum Supply', 'Glass and Aluminum Supply', 0, 'Rizal Street, Bacolod City', '(034) 433-3121 / 709-0162', '', '', '', 'COD', 'Manufacturer', 'Mr. John Gacayan', '', 'Active', '1.00', 1, ''),
(471, 'Inter-Continental Systems Integrators Incorporated (ICSII) - Bacolod', 'Services: Electrical, Civil works, Mechanical, Automation and Architectural works, supply/installation and commissioning of machineries and equipment, fabrication and utilities piping, systems integrator, engineering & technical services provider, Automation system, programming & commissioning of PLC, SCADA, Variable frequency drives and Servo drives, Electric Motors, Gear-motor, Control products (Contactors and Circuit breakers)', 0, 'Filinvest City, Alabang', '0917-568-1412', '', '', '', 'COD', 'General Contractor', 'Celphy/Hannah', '', 'Active', '1.00', 1, ''),
(472, 'Flomont Trading General Merchandise', 'Industrial Gas, Oxygen, Acetylene, Argon', 0, 'Burgos Street, Bacolod City', '(034) 4321481', '', '', '', 'COD (Cash Deposit for each cylinder)', 'Manufacturer', '', '', 'Active', '1.00', 1, ''),
(473, 'HKL Enterprises Incorporated', 'Electrical Supplies', 0, 'Locsin Street, Bacolod City', '(034) 458 9588', '', '', '', 'COD', 'Wholesale / Retail / Distributor', '', '', 'Active', '1.00', 1, '756-548-654-0000'),
(474, 'Lubritek, Inc.', 'Lubricants, Tires, Batteries, filters, Fuels', 0, 'Jovita Street Libertad, Bacolod City', '(034) 435 1976', '', '435 1965', '', '30 days PDC', 'Wholesale / Retail / Distributor', 'Ms. Liberty', '', 'Active', '1.00', 1, ''),
(475, 'Linde Corporation', 'Industrial Gas, Oxygen, Acetylene, Argon', 0, 'Bago City', '(034) 213 4596 / 213 4594', '', '', '', 'COD (Cash Deposit for each cylinder)', 'Wholesaler / Retailer / Manufacturer', '', '', 'Active', '1.00', 1, ''),
(476, 'Aces Aluminum', 'Fabrication using Aluminum (Doors, Windows)', 0, 'Araneta Ave, Bacolod City', '(034) 444-2146 / 444-0246 / 0932-872-6687', '', '', '', '50% Down 50% upon Completion', 'Manufacturer / Fabricator', 'Ms. Babylyn', 'aces.aluminum@yahoo.com', 'Active', '1.00', 1, '445-396-964-000'),
(477, 'Bacolod Kwik-Way Corp.', 'Machine Shop, Fabricator, Calibration of Injection Pump, Fabrication / Machining as per drawing / sample Main Service: Engine Reconditioning', 0, 'Araneta St., Brgy. Singcang, Bacolod City', '(034) 434-8940 / 0927-5779184', '', '', '', 'COD', 'Fabricator / Manufacturer', 'Mr.Aselo Azuelo', '', 'Active', '2.00', 1, ''),
(478, 'MCKL Tires & Battery Supply Trading', 'Tires, Lubricants, Batteries', 0, 'Murcia Road, Prk. Pag-asa, Alijis, Bacolod City', '(034) 432-1635 / 0935-4119008', '', '', '', 'COD', 'Fabricator / Manufacturer / Wholesale / Retail', 'Mr. Jonathan Oh', '', 'Active', '1.00', 1, ''),
(479, 'MCKL Glass', 'Fabricator / Glass Works', 0, 'Murcia Road, Prk. Pag-asa, Alijis, Bacolod City', '(034) 432-1635 / 0935-4119008', '', '', '', 'COD', 'Fabricator / Manufacturer', 'Mr. Jonathan Oh', '', 'Active', '2.00', 1, ''),
(480, 'RU Foundry and Machine Shop Corp.', 'Machine Shop, Foundry, Fabrication as per drawing & as per sample Main Product: Various Type of Shredder Machine', 0, 'Sitio Aning, brgy. Pahanocoy, Bacolod City', '(034) 444-1337 / 0922-8157018', '', '(034) 444-1337', '', 'COD', 'Fabricator / Manufacturer / Wholesale / Retail', 'Mr. Joel M. Telmoso', '', 'Active', '2.00', 1, ''),
(481, 'First Engine Rebuilder, Inc.', '', 0, 'Bangga Rose Lawn, Bata, Bacolod City', '(034) 441-1798 / 708-8490 / 0933-4662211 / 0939-9234540', '', '', '', '30 days', 'Fabricator / Manufacturer', 'Ms. Liane Java / Mr. Ernie John Java / Mr. Raymund Sardo', '', 'Active', '2.00', 1, '000-310-511-0000'),
(482, 'Tri-Jems Machine Shop', 'Machine Shop, Engine Reconditioning, Fabrication / Machining as per drawing / sample', 0, 'Prk. Mahimayaon, Brgy. Bata, Bacolod City', '(034) 707-7428 / 0999-3522935', '', '', '', '', 'Fabricator / Manufacturer', 'Mr. jimmy Trinilla / Shiela May Almencion', '', 'Active', '2.00', 1, ''),
(483, 'BCD Industrial Services', 'Rewinding, Electrical Services', 0, '100 Rizal St., Bacolod City', '(034) 432-1160', '', '', '', 'COD', 'Fabricator / Manufacturer', 'Mr. Noel Lim - noel_lm_12@yahoo.com.ph', 'Mr. Noel Lim - noel_lm_12@yahoo.com.ph', 'Active', '2.00', 0, '113-629-561-0000NV'),
(484, 'Ruds Electrical Repair', 'Electrical Shop, Electrical Rewinding and Repair', 0, 'Araneta St., Bacolod City', '0909-2166317', '', '', '', 'COD', 'Electrical Servicing / Rewinding', 'Mr. Roberto Pate?o / Irenia Pate', '', 'Active', '0.00', 1, ''),
(485, 'Lito Rewinding Repair Center', 'Electrical Shop, Electrical Rewinding and Repair', 0, 'Singcang, Bacolod City', '0933-4380808', '', '', '', 'COD', 'Electrical Servicing / Rewinding', 'Mr. Gerry Omega', '', 'Active', '2.00', 1, ''),
(486, 'JAV Thermal Solutions Inc.', '', 0, 'Unit 504 Tritan Plaza Paseo de Magallanes, Magallanes Village, Makati City, 1232', '(02) 853-6274 / 0917-5575975', '', '(02) 854-1575', '', 'COD', 'General Contractor', 'Mr. Joseph D. Deriada (Project Supervisor)', '', 'Active', '2.00', 1, ''),
(487, 'Genit Auto Parts Surplus', 'Repair Shop, Auto Parts (Surplus), Repair of Hydraulic Jack, repair of crocodile', 0, 'Lacson-Luzuriaga Sts., Bacolod City', '(034) 431-1203 / 0956-3927475', '', '', '', 'COD', 'Repair Shop (Hydraulic Jack 5T)', '', '', 'Active', '2.00', 1, ''),
(488, 'Iron Dragon Metal Fabrication', 'Metal Fabrication (Stainless Steel), Stainless Steel Kitchen Equipment, Ducting For Ventilation and Cool Air, Manufacturer of Centrifugal Blower', 0, 'Lacson-Luzuriaga Sts., Bacolod City', '(034) 474-1442', '', '', '', 'COD', 'Fabricator / Manufacturer', '', '', 'Active', '2.00', 1, ''),
(489, 'A. H. C. Rewinding Shop', 'Electrical Shop, Electrical Rewinding and Repair', 0, 'Narra Ext., Villamonte, Bacolod City', '0943-2506840', '', '', '', 'COD', 'Electrical Servicing / Rewinding', 'Mr. Belarmino Casuyon', '', 'Active', '2.00', 1, ''),
(490, 'RKS Hardware Supply', 'Steel Materials, PVC Pipes, Roofing Materials, Bending of Plain Sheets', 0, 'Narra Ext., Villamonte, Bacolod City', '0998-9978749', '', '', '', 'COD', 'Wholesale / Retailer / Distributor', 'Mr. Richard Santiago', '', 'Active', '2.00', 1, ''),
(497, 'Cheney Enterprises', 'Hardware, Electrical, Construction Supplies (Masonry Drill, Drill Bit, Faucets, GI Pipe, PVC Pipes, Steel Bars, Corrugated GI Sheets, GI Tie Wire, V-Belt, Lacquer Thinner, Denatured Alcohol, Paints, Mounted Stone', 0, '1st Road, brgy. Villamonte, Bacolod City', '(034) 433-1583 / 0933-8571009', '', '(034) 433-2464', '', 'COD', 'Wholesale / Retail / Distributor', 'Ms. Jean Jarder', 'cheney.enterprises01@gmail.com', 'Active', '1.00', 0, ''),
(498, 'Spectrum Buildway and Supplies Inc. - Bacolod', '1. Design and construction of bagasse shed and other structure 2. Design and construction of compact type mud/silt clarifier for process and factory water, potable water, boiler feed water and other application. 3. Design, installation and supply of water filtration system, reverse osmosis, deionizers, water softeners and spares 4. Design, rehabilitation and installatioh of sugar mill equipment and spares 5. Design, installation and supply of electrical and instrumentation products and spares 6. Design, supply, fabricate and install other mechanical equipment 7. Supply, installation and erection of firetube and wate tube boilers 8. Supply of various pumps like vacuum, centrifugal, rotary, etc. 9. Supply of water treatment chemicals for boilers and cooling towers 10. Supply of various commodity chemicals (Caustic soda, phosporic acid, chlorine dioxide, PAC, hydrated lime, etc.) 11. Supply of Ion exchange resins, media filters, membranes, UF, etc. 12. Supply of Maintenance chemicals (Degreasers, acid and alkaline detergents, etc.)', 0, 'Room 203, Cityland Condo 8, 98 Sen. Gil Puyat Ave., Makati City', '(02) 892-9536 / 0918-5432019', '', '(02) 817-3975', '', 'COD', 'Distributor / Contractor / Water Treatment Chemical Supplier', 'Mr. Wennie D. Patrimonio', '', 'Active', '1.00', 1, ''),
(499, 'Rosler Enterprises', 'Dealer of: Heavy Equipment & Spareparts, PP Grids Plates, Stainless Screen, DSM Screen, Valve for Sugar Mill and Construction, Civil, Mechanical, Electrical, Painting and Water Proofing Works Dealer and Applicator of (Sealbond) Product Industrial Adhesive, Epoxy Paint Sealant & Structural Epoxy, Tank Lining, Epoxy Flooring Compound, Concrete Cracks Repair, Epoxy Injection Bridge, Building and other Concrete Structure. Special Services: Retrofitting, Carbon Fiber Plate Sheet', 0, 'Lot 12, Blk 9, Greenplains, Singcang-Airport, Bacolod City', '(034) 433-1326', '', '', '', 'COD', 'General Contractor', '', '', 'Active', '2.00', 1, ''),
(500, 'RC Avila Construction and Engineering Supplies', 'Contractor (Mechanical), Building Construction', 0, 'CYA Industries Bldg. Ground Floor Unit B. Magsaysay Avenue, Bacolod City', '(034) 703-2258 / 0917-5236141', '', '', '', 'COD', 'General Contractor', '', '', 'Active', '2.00', 1, ''),
(501, 'GDC Roofing Marketing & Services', 'Roofing Materials, Contractor, Foam Insulator, C-Purlins, Roofing Materials, Steel Deck', 0, 'Block 9, Lot 9  & 10, CYA Building, Magsaysay Ave., Brgy. Taculing, Bacolod City', '(034) 708-8601', '', '', '', 'COD', 'General Contractor', '', '', 'Active', '2.00', 1, ''),
(502, 'Dynamic Power Marine & Industrial Hardware, Inc.', 'Industrial Generating Sets (Generator), Marine Engine (48hp-1960hp), Dynacast Mill Bearings, Caprari (Industrial Pump), Dynacast Marine Propeller', 0, 'CYA Building, Magsaysay Ave., Brgy. Taculing, Bacolod City', '(034) 458-3158', '', '', '', 'COD', 'General Contractor/ Dealer / Distributor', '', '', 'Active', '2.00', 1, ''),
(503, 'R. V. C. Glass & Aluminum Supplies', 'Tempered Glass, Fixed Glass, Mirror, Jalousies, Shower Enclosure, Swing Door, Sliding Door, Roll-Up Door, Swing Screen Door, Sliding Screen Door, Sliding Windows, Awning Windows, Sliding Screen Doors, L-Type Window, Showcase, Accessories, Frameless Door, Patch Fitting Door', 0, 'Blk. 2, Lot 15, Providencia Subd., Brgy. Alijis, Bacolod City', '(034) 709-7022 / 0997-3266510 / 0929-5414018', '', '', '', 'COD', 'General Contractor/ Dealer / Distributor', '', '', 'Active', '1.00', 1, ''),
(504, 'New G'' Marker Enterprises', 'Fabrication as per Drawing and as per sample Lighted Sign, Tarpaulin Printing, Stickers, Safety Signage, T-Shirt Printing, PVC ID, Calling Card, Plaque, Embroidery Services (Patches, Name Plate, Cap)', 0, 'Blk. 2, Lot 11, Providencia Subd., Circumferential Road, Brgy. Alijis, Bacolod City', '(034) 446-7587 / 0927-7710492 / 0999-6688842', '', '', '', 'COD', 'Wholesale / Retail', '', '', 'Active', '1.00', 1, ''),
(505, 'Eastman Electrical Center', 'Electrical Supplies (iloilo city), Electrical Supplies, Wires, Outlets and Switches, etc.', 0, 'Quezon St, Villa Arevalo District, Iloilo City', '(033) 337 1753 / 0930-1274571', '', '(033) 335-1138', '', 'Advance Payment', 'Wholesale / Retail / Distributor', 'Ms. Mitch / Sir Eduardo Ong', '', 'Active', '1.00', 1, ''),
(506, 'Aim Merchandising', 'Electrical Supplies (iloilo city), Distributor of Phelps Dodge, Electrical Supplies and Hardware', 0, '28 Quezon St, Iloilo City Proper, Iloilo City', '(033) 337-5034 / 337-6551', '', '(033) 335-8533', '', 'Advance Payment', 'Wholesale / Retail / Distributor', 'Ma''am Susan Go', '', 'Active', '1.00', 1, ''),
(507, 'Benson Electrical Supply', 'Electrical Supplies (iloilo city), Electrical Supplies, Wires, Outlets and Switches, Metal Halide, etc.', 0, '41 Quezon St, Iloilo City Proper, Iloilo City', '(033) 337-8882 / 337-0434 / 0950-4580825 / 0956-9665546', '', '(033) 337-0434', '', 'Advance Payment', 'Wholesale / Retail / Distributor', 'Clang Gumban', '', 'Active', '1.00', 1, ''),
(508, 'Power Industrial Sales', 'Tools (iloilo city), Machtools, Dust Collector with motor, Drill Bit Sets, Exhaust Fans, Blowers / Ventilators (Explosion Proof), Flexible Duct Hose, Wood Lathe Machine, Impact Drills, Scroll Saw, Bench Grinder Stand, Rotary Hammers, Die Grinders, Chipping Hammers, Combination Hammers, Magnetic Drills, Grinders, Polishers, Jigsaws, Hot Air Guns, Cordless Drills, Vacuum Cleaners, Sanders, Cut-off Machine, Planer, Wall Chaser, Screwdriver, Impact Wrench, Dustless Systems, Circular Saws, Tile Cutter, Bevelling Machine, Miter Saw, Torq, Pilot pins, arbors and adapter, holesaws', 0, '40 Quezon St, Iloilo City Proper, Iloilo City', '(033) 508-6468', '', '(033) 337-6574', '', '30 days PDC', 'Wholesale / Retail / Distributor', 'Mr. Celecsorio "Dodong" Siao / Ms. Annabelle', '', 'Active', '1.00', 1, ''),
(509, 'Iloilo Electrical Supply', 'Electrical Supplies (iloilo city)', 0, 'Iznart St, City Proper, Iloilo City', '(033) 337-4751', '', '(033) 337-4751', '', 'Advance Payment', 'Wholesale / Retail / Distributor', 'Tio Eng Hio', '', 'Active', '1.00', 1, ''),
(510, 'Prefer Electrical Engineering & Supply', 'Electrical Supplies (iloilo city), Design, Construction and Maintenance, Authorized Dealer of Power Transformer and Distribution Transformer (Shihlin Electric & Engineering Corp.), Various Electrical Testing Services: a. Transformer Oil Degasify, Vacuum Regeneration and Filtering b. Transformer and Switchgear Preventive Maintenance Services (PMS) c. Generator and Transformer Protective Relay Calibration d. Substation, Transmission Line & Plant Thermography Inspection & Report e. Control Panel Instrument Calibration and Rehabilitation f. SF6 GIS Switchgear Repair & Preventive Maintenance Services g. Rewinding of transformer (oil immersed and dry type) h. Power Sub-station Operation and Tendering Services i. Power Voltage & Medium Voltage Cable Splicing and Terminsation Electrical Construction Works:  a.1 Design, installation and commisioning of substation  a.2 Design installation and commisioning of general power and lightning system for residential, commercial and industrial plants a.3 Transmission and Distribution Pole Line Erection, Cable Dressing and line hardware installation Design and Install: b.1 Power Substation Turnkey Projects Supply of the Following:  c.1 Shihlin Power Transformer, Pad Mounted Transformer, Cast Resin Transformer c.2 Shihlin Instrument Transformers. c.3 Shihlin Switchgears c.4 Solar Street Lights c.5 Power Transformer Oil', 0, 'Sto. Nino Bldg., MC Arthur Highway, Tabuc Suba, Jaro, Iloilo City', '(033) 329-4077', '', '', '', 'Mobilization down payment, Balance 30 days PDC', 'Wholesale / Retail / Distributor / General Contractor', 'Engr. Eduard Lim, PEE', 'preferelectric65@gmail.com / preferelectric@yahoo.com', 'Active', '2.00', 1, ''),
(511, 'Lace Center', 'Buttons, Lace, Garments', 0, 'Gatuslao St, Bacolod, 6100 Negros Occidental', '(034) 434 6506', '', '', '', '', '', '', '', 'Active', '0.00', 0, ''),
(512, 'Zuniga', 'Garments', 0, '', '', '', '', '', '', '', '', '', 'Active', '0.00', 0, ''),
(513, 'Galaxy Marketing', 'Tarpaulin', 0, '61-D 6th St, Bacolod City', '(034) 433 0647', '', '', '', 'COD', 'Wholesale / Retail', '', '', 'Active', '1.00', 1, ''),
(514, 'Sum-ag Petron Gas Station', 'Diesel, Gasoline, Kerosene', 0, 'Sum-ag, Bacolod City', '(034) 444-0493', '', '', '', 'COD', 'Wholesaler / Retailer', '', '', 'Active', '1.00', 1, ''),
(515, 'RM Shell', 'Diesel, Gasoline, Kerosene', 0, 'B. S. Aquino Drive, Villamonte, Bacolod City', '(034) 433-6038', '', '', '', 'COD', 'Wholesaler / Retailer', 'Ms. Aida', '', 'Active', '1.00', 1, ''),
(516, 'New Energy Marketing Corporation', 'Car Battery', 0, 'Luzuriaga St, Bacolod City', '(034) 432 0271', '', '', '', 'COD', 'Wholesaler / Retailer', '', '', 'Active', '1.00', 1, ''),
(517, 'South-Gas Petron Service Station', 'Diesel, Gasoline, Kerosene', 0, 'Araneta St., Tangub, Bacolod City', '(034) 444-2489', '', '', '', 'COD', 'Wholesaler / Retailer', '', '', 'Active', '1.00', 1, ''),
(518, 'Inshallah Enterprises', 'Flourescent (Louver Type, Box Type, Dustproof, Surface Mounted, Architectural, Industrial, T-5 System), Downlight (LED, Square, Round, Rectangular, Surface, Recess), Exit Lights, Emergency Lights, Battery Pack, Motion Sensor, Shop Lights, Wall Washer, Street Light/Lamp post, Fire Alarm System / Detectors, Multi-Hazard Suppression System, Garden Light, Tract Light, Wall Lamp, LED Floodlight, Integrated Solar Street Light, Exhaust Fan, CCTV Cameras, LED Power Supply, Panel Boards, Blowers & Fans', 0, 'B 34 L18 Adelfa St., Sunflower Homes, Brgy. San Roque, Angono Rizal', '0917-1537914', '', '', '', 'COD', 'Wholesaler / Retailer', '', '', 'Active', '1.00', 1, ''),
(519, 'Doods Sack Trading', 'Empty Sacks', 0, 'Lopez Jaena - San Sebastian St.,Bacolod City', '09075156093', NULL, '', NULL, '', '', '', '', '', '0.00', 0, ''),
(520, 'Pyromancer Fire Safety Products and Installation Services', 'Fire Protection System Installation, Fire Extinguishers', 0, '2nd Floor Doña Manuela Bldg., Plazamart, Bacolod City', '(034) 700-1442 / 0920-2311673', NULL, '', NULL, '15 days', 'Manufacturer', '', '', 'Active', '1.00', 1, ''),
(521, 'Asia Pacific Safety & Industrial Supply Inc.', 'Personal Protective Equipment (PPE)', 0, 'Lopez Jaena St, Mandaue City', '(032) 239 2083', NULL, '', NULL, 'Advance Payment', 'Wholesaler / Retailer / Distributor', '', '', 'Active', '1.00', 1, ''),
(522, 'Moto Industrial Traders Corporation', 'Chemtool Inc. (USA) - Food Grade Lubricants, Biodegradable Lubricants, Synthetic Lubricants, Metal Working Fluids, Die Cast Lubricants, Spare Parts Specialist For Japanese, USA, European and Korean Electro-mechanical, Pumps, Compressors, Turbines, Refrigeration, and heating equipment, blowers, fans, Municipal Waste Incineration, Indutrial Waste Incineration Plants, Water Treatment Plants, Dry Vacuum Pumps, CMP Equipment, Plating Equipment, Exhaust-Gas Treatment Equipment, Gas Turbines, Steam Turbines, Generators, Boilers, Control System, Air Quality Control Systems (AQCS), Fuel Cells, Control / Shut Off Valve, Butterfly Valve, Valve Remote Control System, Level Gauging System, Control Valves, Safety Relief Valves, Valves for Special Application & Accessories, Motors, Generators, Medium Voltage AC Drives, Low Voltage AC Drives, DC Drives, PV Inverters, Laminated Steel Flexible Joints, Turbocharger Parts, Turbo Compressor Parts ( Bearing), Form-Flex Coupling, Electro Magnetic-Coupling, Rotex coupling, Water Pumps, Vacuum Pumps, Water Treatment Equipment, Generating Equipment, Hypochlorator Equipment, Sensors and Measurement, Factory Automation System, Distribution and Controls, Transmission & Distribution, Flowmeters, Industrial Batteries, Power Supply Systems, Conveyor Modules, SF6 Recovery System, Measuring Equipment, Hydraulic Equipment, Electrical Equipment, Steel Casings, Oil Filtration System, Air Coolers / Heat Exchanger, Air Conditioners, Control Instruments, Servo Motor, Servo Drive', 0, 'Hi Residences Condominum Tower 2, Ground Floor Unit 17, Burgos Ext., Brgy. Villamonte, Bacolod City', '0927-8479949 / 0923-5756744 / 0915-4520599', NULL, '(02) 894-2116 / 894-2333 / 813-1572', NULL, 'COD', 'Wholesaler / Retailer / Distributor', '', '', 'Active', '1.00', 1, ''),
(523, 'Wingrace Industrial Supply Trading', 'Distributor of Blueshark Cutting Disc, Superthin Cutting Disc for Fiber, Ceramic All in One, Abrasive Flap Discs, Aluminum, Silicon Carbide, Zirconia, Abrasives Backing Pads, Polishing Disc for Aluminum and Copper, Welding Consumables, Welding Machines, TIG / MIG / ARC, Tig Torch Assembly, Plasma, Welding Earth Clamp, Contact Tip, Welding Machine, Collet, Collet Body, Ceramic Cup, Tungsten Red Tip', 0, '115 National Highway, Brgy. Banlic, City of Cabuyao, Laguna', '0939-4188481 / 0956-6216745', NULL, '', NULL, '', 'Wholesaler / Retailer / Distributor', 'Ms. Merrie Grace B. Agapin', '', 'Active', '1.00', 1, ''),
(524, 'Bigmind Engineering & Industrial Supply Company', 'Metal Fabrication, Rubber Fabrication, Plastic and Acrylic Fabrication, Precision and Non-Precision Tooling, Roof Framing, Teflon Products, Gears, Rubber Roller, Industrial Hose, O-ring and Oil Seal, Polyurethane Roller, Neoprene Oil Resistant, Engineering Services, Motor Rewinding, Conveyor System, Ducting and Ventilation System', 0, '115 National Highway, Brgy. Banlic, City of Cabuyao, Laguna', '0939-4188481 / 0956-6216745 / (049) 304-4669 / ', NULL, '', NULL, 'COD', 'Wholesaler / Distributor / Manufacturer', 'Ms. Merrie Grace B. Agapin', 'bigmind.engineering@yahoo.com\r\nmerriegracebausing27@gmail.com', 'Active', '1.00', 1, ''),
(525, 'Negros Tires R Us Enterprises, Inc.', 'Petron Engine Oils Gasoline Engine Oils, Diesel Engine Oils, Gear Oils, Transmission Fluids, Hydraulic Oils, Cutting Oils, Trunk and Piston Oils, Air Compressor Oils, Refrigeration Oils, Transformer Oils, Greases', 0, 'Lorenzo Ruiz Bldg., Lopez Jaena St., Bacolod City', '(034) 434-7777 / 434-6666', NULL, '707-6666', NULL, 'COD', 'Wholesaler / Distributor', 'Mr. Gerry Guerrero', '', 'Active', '1.00', 1, ''),
(526, 'Rainchem International Inc.', 'Lubricants, Hydraulic Oil, Industrial Gear Oil, Compressor Oil, Refrigeration Oil, Slideway Oil, Spindle/Needle Oil, Cutting Oil, Marine Diesel Oil, Grease (MP3 Grease / Multi-Purpose Lithium NLGI 3), EP2 Grease (Extreme Pressue Lithium NLGI 2), Transformer Oil, Stamping Oil, Honing Oil, Quenching Oil, Die-Casting Oil, Electric Discharge (EDM) Fluid, Degreasers, Corrosion Preventive, Turbine Oil, Synthetic Engine Oil, Semi-Synthetic Engine Oil, Multi-Grade Gasoline Engine Oil, Multi-Grade Diesel Engine Oil, Mono-Grade Diesel Engine Oil, Multi-Purpose Grease, Synthetic Four-Stroke Motorcycle Oil, Multi-Grade Four-Stroke Motorcycle Oil, Manual Transmission Gear Oil, Automatic Transmission Fluid, Brake Fluid', 0, '4015 Le Cul de Sac St. Sun Valley, Parañaque City', '0956-3229917 / 0917-3031358 / (02) 403-8197', NULL, '', NULL, 'COD', 'Wholesaler / Retailer / Distributor', 'Mr. Reynaldo A. Palma', '', 'Active', '1.00', 1, ''),
(527, 'JUPP & Co. Incorporated', 'Product Lines:  > Lenze Drive Systems (Frequency Inverters, Gearmotors, Servomotors, Controls) > Bornemann Pumps (Twin Screw Pump, Hygienic Twin ScrewPump, Progressive Cavity Pump) > Robuschi (Rotary lobe Blowers, Vacuum Pumps, Centrifugal Pumps) > Lutz Pumpen GmbH and Co. KG (Drum Pumps, Flow Meters, Centrifugal Pumps, AOD/Diaphragm Pumps) > Ebara Pumps (Volume Pumps, Sewage Pumps, Centrifugal Pumps, Clean Water Pumps, Submersible Pumps) > Wright Flow Technologies (Rotary Lobe Pump, Sanitary Centrifugal Pumps, Circumferential Piston Pumps) > Graco (Drum Unloader, Lubricant Injector, Sanitary Diaphragm Pumps, Chemical Diaphragm Pumps, Automatic Lubrication System) > Tecnicapompe (CIP Pump, Portable Sanitary Pump, Sanitary Centrifugal Pump) > PCM Pumps (Progressive Cavity Pumps; Waste Water, Food Processing, Personal Care) > OSNA (Centrifugal Pumps, Multi-Stage Vertical Pumps, Multi0Stage Horizontal Pumps) > Bernard Controls (Quarter Turn Actuator, Multi Turn Actuator, Modulating Actuator, Fail Safe Actuator) > Doseuro (Dosing Pump, Metering Pump, Industrial Agitators) > Lubriplate (Hydraulic and Metalworking oils, Biodegradable Grease and Oils, Automatic Lubricators (PERMA), NSF Registered Food Grade Grease and Oils, Fully Synthetic and Mineral Base Oil or Grease) > Envirogear (Internal Gear Pumps, Magnetically Coupled Seal-Less Internal Gear Pumps)', 0, 'Unit 224 Cityland Pioneer St., Mandaluyong City, 1550 Phils.', '(632) 687-7423-683-0042 / 683-0047', NULL, '(632) 687-7421', NULL, 'COD', 'Wholesaler / Retailer / Distributor', 'Mr. Dustin John Escaler ', 'email add: jupp@jupp.com.ph website: Jupp.com.ph', 'Active', '1.00', 1, ''),
(528, 'WELtech Enterprises', 'Jigs & Fixtures, Semicon Toolings, Gear Making, Industrial Jobs, Wire Cutting, Engineering Jobs, Mold and Die Making, Metal Stamping, Machining Works, EDM Jobs, Roller/Conveyor, Cutter / Blades, Chrome Plating, Welding Jobs, Trading, Powder Coating Sample Products: Chamfering Machine, Cold Saw Cutting Machine, Carbide (Punch and Die) Machineries and Equipment:  1 unit Surface Grinding 2 unit Milling Machine 1 unit Lathe Machine 1 unit Precision Vise 1 unit Punch Former 1 unit TIG Welding Machine 1 unit ARC Welding Machine 1 unit TIG with Aluminum Welding', 0, 'I Bayong Ilat, Kaong Silang Cavite', '(046) 513-0239 / 0948-1770042 / 0977-4074688', NULL, '', NULL, 'Advance Payment', 'Manufacturer', 'Mr. Anselmo Basabe / Mr. Walter Olorcisimo', '', 'Active', '1.00', 1, 'VAT TIN 221-550-234-000'),
(529, 'Bacolod Pro Sanicleaners Supply Center', 'Manufacturer, Liquid Soap, Cleaning Chemicals', 0, 'Burgos St., Bacolod City', '(034) 432-1899', NULL, '', NULL, 'COD', '', '', '', 'Active', '1.00', 1, '740-422-809-0000 '),
(530, 'SM SUPERMARKET', 'General Needs, Liquid Soap, Tools, Appliance', 0, 'Annex Building, South Wing-SM City, Bacolod', '(034) 468 0168', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 0, ''),
(531, 'Artemis Salt Corporartion', 'Industrial Salt', 0, 'Bredco Port, Reclamation Area, Brgy.10, Bacolod City', '09269909479 / 09178288052', NULL, '', NULL, 'COD-Cash', 'Manufacturer', '', '', 'Active', '0.00', 1, '206-963-426-013'),
(532, 'Belen Store', 'Broom Stick', 0, 'Burgos Market, Bacolod City', '', NULL, '', NULL, '', '', '', '', '', '0.00', 0, ''),
(533, 'D&L SOLUTION PROVIDERS CORPORATION', 'Manufacturer, Liquid Soap, Cleaning Chemicals', 0, 'Rm 211-212, Del Rio 2, B.S. Aquino Drive, San Agustin Ave, Bacolod City', '(034) 476-1341 / 435-8659', NULL, '', NULL, 'COD', 'Manufacturer', 'Ms. Merlyn Posadas', '', 'Active', '0.00', 1, ''),
(534, 'PLM Chemical Corporation', 'Boiler and Heat Exchanger Chemical Cleaning', 0, '130 Loreto St. Sampaloc, Manila', '(02) 714-2741', NULL, '(02) 780-0096', NULL, 'COD', 'Wholesaler / Retailer / Importer', '', '', 'Active', '0.00', 0, ''),
(535, 'Zimmox International Product Specialist, Inc.', 'Chemicals, Water Treatment Chemicals, Caustic Soda Flakes, Industrial Salt, Aluminum Sulfate, Activated Carbon, Ferric Chloride, Pinch Valves, Knife Gates, Control Valves, Pressure Sensors, Expansion Joints, Cast and Fabricated Valves, Custom Valves, Induction Motors, Inverters, Gears, Ball Valves, Cryogenic, Sanitary, Special and Exotic Alloys, Industrial Hoses and Assembly, Hydraulic Hoses and Assembly, Rubber Sheets, lined wafter butterfly valves, lined ball valves, lined check valves, lined weir diaphragm valves, lined sight glass, lined swing check valve, lug type and wafer type butterfly valves, actuators, special alloy valves, solenoid valve, sluice valve, aluminum flap gates, fiberglass reinforced plastic (FRP) Slide gates, stop logs, flumes, penstocks, SS Gate Valves, Swing Check Valve, Magnetic Flow Meters, Program Controllers and Indicators, Centrifugal Pump, Industrial Salt, Caustic Soda, Activated Carbon, Ferric Chloride, Aluminum Sulfate', 0, 'Door # 6B The Site Bldg., Mt. View (Buri) Road, Mandalagan, Bacolod City', '(034) 458-8821', NULL, '(034) 458-8821', NULL, 'COD', 'Wholesaler / Retailer / Importer', 'Mr. Hermie Lowell Sayson - 0917-717-3543, email add: sales04@zimmox.com', 'website: www.zimmox.com', 'Active', '1.00', 1, ''),
(536, 'Uniquev Industrial Corporation', 'Cooling Tower, HVAC, Gear Drives, Bearing, Coupling, Fan, Blades, Valves (Butterfly Valves, Gate Valves, Check Valves, Air Valves, Fire Hydrant, Dismantling Joints) Actuator, Pumps, Instrumentations, Water Tanks, Studbolts, Seal Bonding, (Anodes, Net Cutters, Seal Protection), Prime Standard Overhaul Kit (Wartsila/Lips Installations), Prime Blade Foot Seal Kit (Wartsila/Lips C, D, L and VBS Hubs), Prime Overhaul Kit for Oil Supply Units (Wartsila Type VL)', 0, 'Unit 8, 3rd Flroor, Cifra Bldg. No. 641 Boni Avenue, Mandaluyong City', '(02) 477-0511 / 470-0147 / 0917-5075416', NULL, '', NULL, 'COD', 'Wholesaler / Retailer / Importer', '', '', 'Active', '1.00', 1, '007-434-244-000'),
(537, 'Lectrix Solutions Electrical Supplies & Services Cebu', 'Circuit Breakers, Switches, Starters, Panel Boards, Transformers, Motor Pumps, Alternators, Sewage Pumps, Pressure Gauges, Transmittors, Wires and Cables, Limit Switches, Contacters, Isolators, Relays', 0, '#1 Montebello Road, Apas, Cebu City', '(032) 343 4664', NULL, '', NULL, 'COD', 'Wholesaler / Retailer / Distributor', 'Ms. kehly - +639173153646 | +639173157931', '', 'Active', '1.00', 1, ''),
(538, 'N. A. Systems, Inc.', 'Electrical Supplies', 0, 'Suite 1507, 15th Floor, ALAI-FGU Center, Mindanao Ave., Cebu Business Park, Cebu City', '(032) 233 8760', NULL, '', NULL, 'COD', 'Wholesaler / Retailer / Distributor', '', '', 'Active', '1.00', 1, ''),
(539, 'Richworld Electrical & Industrial Corp.', 'Personal Protective Equipment, Safety Googles (B421), B-1 Chemical Googles, Rubber Frame Googles, A-3 Softframe Single Lens, Safety Spectacles, Safety Glasses,  Head Protection, Helmet/Hard Hats/Safety Cap, Safety Shoes, Industrial Gloves, Safety Equipment, Fall Protection Equipment, Body Harness, Lanyards, Revolving Lights, Sirens, Fire Alarm, Smoke Detector, Scanner, Safety Barrier, Megaphone, Automatic Voltage Regulators (AVR), Falmmable Cabinets, Acid Corrosive Cabinet, Absorbent Pillow, Absorbent Boom, Electrical Supplies, Wire and Cables, Miniature Circuit Breaker, DIN Rail, Breaker and Switchgear System, Air Circuit Breaker, Automatic Transfer Switches, Motor Control (Contactor), Surge Protective Device, Smart Meter, Inverter, Low Voltage Power Capacitor, Exhaust Fansion, Helmet/Hard Hats/Safety Cap, Safety Shoes, Industrial Gloves, Safety Equipment, Fall Protection Equipment, Body Harness, Lanyards, Revolving Lights, Sirens, Fire Alarm, Smoke Detector, Scanner, Safety Barrier, Megaphone, Automatic Voltage Regulators (AVR), Falmmable Cabinets, Acid Corrosive Cabinet, Absorbent Pillow, Absorbent Boom, Electrical Supplies, Wire and Cables, Miniature Circuit Breaker, DIN Rail, Breaker and Switchgear System, Air Circuit Breaker, Automatic Transfer Switches, Motor Control (Contactor), Surge Protective Device, Smart Meter, Inverter, Low Voltage Power Capacitor, Exhaust Fans', 0, '17-1 D. Jakosalem St. Cebu City', '(32) 254 6778 / 253 3464 / 253 3464', NULL, '', NULL, 'COD', 'Wholesaler / Retailer / Distributor', '', '', 'Active', '1.00', 1, ''),
(540, 'Houston Home Depot', 'Tools, Hardware, Tiles, Furnitures', 0, 'Narra-Lopez Jaena Sts., Bacolod City', '(034) 434 5328', NULL, '', NULL, 'Cash', 'Wholesaler / Retailer / Distributor', '', '', 'Active', '1.00', 1, ''),
(541, 'HEBI CYCLE COMMERCIAL CO., LTD.', '-METAL (Magnesium dust, Aluminum dust, Calcium, Fe, Silicon, Titanium, Copper, Rare metals) etc.  -          CHEMICALS (amino acid, Acesulfame potassium, Phenol, Sodium benzoate, pyridine, Glycerol, Calcium propionate, Oxalic acid, Tea polyphenols, Tranexamic acid, Sodium acetate trihydrate, Cholesterol, Boronnitride, N-Butyric anhydride, P-Phthalic acid, Dimethylbenzene, Ferrocene, Dibromohydantoin, Vaseline, Phenolphthalein, Glutathione, Cyclohexanone, Resorcinol, Polyaluminium chloride, Sodium hyposulfite, Piperazine, Salicylic acid, L-carnitine) etc.  -          Minerals (Charcoal, Tourmaline, Fluorite, Titanium dioxide, Zeolite, Molecular sieve, Kaolin, Pyrite, Talcum, slag, Montmorillonite, Gypsum, Graphite, petroleum coke, chromium sesquioxide) etc.  -          RESIN, PLASTIC (PU,BOPP, UHMWPE, PP,PS,PET,EVA,PA,PE,TPU, PVC resin, Epoxide resin, Ion exchange resin, Super absorbent polymer, phenol-formaldehyde resin, Polyurethane prepolymer, Terpene resin), etc  -          WASTE PLASTIC (PE scrap granules, PP scrap granules, PVC scrap granules, PS scrap granules, pet scrap granules, PA scrap granules, BOPP scrap granules) etc.  -          METAL SCRAP (aluminum scrap, iron scrap, zinc ash) etc…', 0, 'Hebi city, Henan province, China', 'WhatsApp: +1 (219) 413-7046', NULL, '', NULL, '', 'Importer / Exporter / Manufacturer', 'Mr. Kim Johnson (Business Development Manager)', '', 'Active', '0.00', 0, ''),
(542, 'MED CARE SUPPLIES', 'Distributor, Medical and Nursing Supply', 0, 'Room # 4 and 7 J and E Building, BS Aquino Drive, Bacolod City', '(034) 435-5118', NULL, '', NULL, 'COD-Cash', '', '', '', 'Active', '1.00', 1, ''),
(543, 'Robinsons Handyman Inc.', '', 0, 'Mandalagan, Bacolod City', '(034)441-3166', NULL, '', NULL, 'COD-Cash', 'Distributor', '', '', '', '1.00', 1, ''),
(544, 'Toy Trucks Inc.', '', 0, 'Mansilingan, Bacolod City', '(034) 476 5304', NULL, '', NULL, 'COD', 'Distributor', 'Ms. Shane / Sir Walter - service.parts@toytrucks.com.ph / 0918-3632-644', '', 'Active', '2.00', 1, ''),
(545, 'Electro Power Enterprises', '69 KV Line Correction', 0, 'Bonifacio St., Talisay City', '0919-466-1944', NULL, '', NULL, 'COD', 'Contractor', 'Mr. honorato Chavez Cabile Jr.', '', 'Active', '2.00', 1, 'VAT Reg TIN: 128-639-390-0000'),
(546, 'The New Season Trading', 'General Merchandise', 0, 'The Atrium, Gonzaga St, Bacolod City', '(034) 434 2270', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 1, ''),
(547, 'VCY SALES CORPORATION', 'Distributor,Water Tank,Tiles', 0, 'Cor. Yakal, Molave St, Brgy. Villamonte, Bacolod City', '(034)433-7112', NULL, '', NULL, '30 days PDC', '', 'Ms. Kara', '', 'Active', '1.00', 1, ''),
(548, 'Compressair Center, Inc.', 'Ingersoll Rand, Armstrong, FSCurtis, Gardner Denver, Nirvana, Boge Kompressoren, Rotary Screw Compressors, Compressed Air Treatment, Air Filter, Dessicant Dryers, Filter Element, Combined Dryer, Refrigerated Air Dryer, Automatic Condensate Drain, Centrifugal Air Compressor, Power Generator, Blower, Vacuum, Booster Pumps, Air Tools such as Ratchet and Impact Wrenches, Digger, Air Hammer, Air Cutting Tools and Grinders, Impact Socket Sets, Quic Connect Couplers, Pneumatic Oil, Gloves, Cordless Power Tools, Spring and Air Balancer, Air/Electric/Hydraulic Winches, Manual/Air/Electric Hoist', 0, 'CRI Bldg., 665 Pres. E. Quirino Ave., Malate, Manila, 1004 Phils.', 'MNL: (632) 8567-4389 to 95, (632) 8567-4398; CEBU: (032) 343-8380, 343-8381', NULL, 'MNL: (632) 8567-4389 to 95, (632) 8567-4398; CEBU: (032) 343-8380, 343-8381', NULL, 'COD', 'Distributor', 'Mr. Joel Tajanlangit (Sales Engineer) - 0919-085-8993, joel.tajanlangit@compresstech.com.ph', '', 'Active', '1.00', 1, ''),
(549, 'E. B. Testing Center Inc.', 'Soil Testing', 0, 'Roxas Ave., Brgy. 39, Bacolod City', '(034) 474-3126 / 0977-011-6487', NULL, '', NULL, 'COD', 'Contractor', '', '', 'Active', '2.00', 1, ''),
(550, 'HVC Materials Testing Laboratory', 'Soil Testing', 0, 'Mansilingan, Bacolod City', '(034) 708-9654', NULL, '', NULL, 'COD', 'Contractor', 'Mr. Ernesto S. Mapa - hvcmaterialstestinglab@gmail.com / esmce07@yahoo.com', '', 'Active', '2.00', 1, ''),
(551, 'Megatesting Center Inc.', 'Soil Testing', 0, 'Circumferential Road, Bacolod City', '0998-999-3898', NULL, '', NULL, 'COD', 'Contractor', 'Ms. Marilou F. Porras', '', 'Active', '2.00', 1, ''),
(552, 'Smart-Tech Electro-Industrial Systems, Inc..', '1. High/Low Voltage Electrical Equipment/Accessories\r\n1) Power Transformers up to 69 kV (Local / Imported)\r\n2) Distribution Transformers, Oil / Dry Type, up to 34.5 kV (Local / Imported)\r\n3) Transformer/s for Rental\r\n4) Current Transformer / Potential Transformer\r\n5) Load Break Switch / Air Break switch\r\n6) Fused cut-out / Power fuse / Oil Circuit Breakers\r\n7) Concrete Poles (Slightly Used / Brand New)\r\n8) Pole line hard Wares (Cross-Arms, Insulators, Machine Bolts etc.)\r\n9) Transformer Flat forms / Steel Tower\r\n1.2 Fabricator/Supplier of the following:\r\n1) Capacitor Banks\r\n2) Manual / Auto Transfer Switch Panels\r\n3) Panel Board, Switchgear, Motor Control Panels\r\n5) Cable Tray / Wire Gutter / Made to order enclosures', 0, 'Purok 4, Brgy. Langkaan 1, Dasmariñas, Cavite', 'Cavite -  (02) 985-3729 / 524-8428, Laguna Lines:  (046) 413-7175 / 686 4820 / 686-7726', NULL, '', NULL, '', 'Supplier / Contractor', '', '', 'Active', '1.00', 1, ''),
(553, 'Communications Electrical Equipment & Supply Co., Inc. (CELEASCO)', '1. ALDUTI-RUPTER SWITCH, S&C 2. ARMOR RODS (SIZE: #1/0, #2, #2/0, #4/0, 336.4/795, 9 PIECES/SET), PLP 3. FULL TENSION SPLICE (ACSR CONDUCTOR SIZE: #2, #4, 1/0, 2/0, 3/0, 4/0, 336.26, 795.26 ), DSM 4. GUY GRIPS (SIZE: 1/4, 3/8, 5/16, 7/16 ), PLP 5. SERVICE GRIPS (CONDUCTOR RANGE: 0.169?- 0.198?), DSM 6. FULL TENSION SPLICE (ACSR CONDUCTOR SIZE: #2, #4, 1/0, 2/0, 3/0, 4/0, 336.26, 795.26 ), DSM 7. BUCKLE, HONT 8. STRAPS HONT 9. DISTRIBUTION TIES FOR BARE ACSR, PLP 10. BUTT CONNECTORS (LONG BARREL LENGTH, FOR 600 VOLT HEAVY DUTY), BURNDY 11. COMPRESSION TOOL (REVOLVER TYPE), BURNDY 12. SOLDERLESS CONNECTORS (3-PIECE), BURNDY 13. TERMINAL LUGS, BURNDY 14. BUTTON, SALISBURY 15. TRANSFORMER GIN, AB CHANCE 16. CLAMP PIN, SALISBURY 17. STRAIN CLAMP (2- U BOLT, 3-U BOLT, 4-U BOLT, 5-U BOLT) 18. SNATCH BLOCK WITH FORGE STEEL HOOK (1250 LBS, 3500 LBS.) AB CHANCE 19. MOISTURE EAER II CLEANER (1 GAL. ) AB CHANCE 20. GROUNDING CLUSTER (ALL ANGLE GROUND CLAMP) 6 FT, 12 FT, AB CHANCE 21. GROUND ROD COPPERWELD, BLACKBURN 22. SKINNING KNIFE, BUCKINGHAM (7“) 23. CABLE JOINTS, ELASTIMOLD (T&B) 24. CABLE TERMINATIONS, 3M 25. COMPRESSION LINE TAPS, BURNDY 26. CONDUCTOR COVER (25KV, 46KV) AB CHANCE 27. RUBBER GLOVE (CLASS 4), SALISBURY 28. RUBBER BLANKET SLOTTED, SALISBURY 29. RUBBER BLANKETS, SALISBURY 30. LINEMAN RUBBER GLOVES, SALISBURY (CLASS 2 & CLASS 4) 31. CURRENT LIMITING FUSE, HI-TECH (T&B) 32. FAULT INDICATOR, EMG (GERMAN) 33. RECLOSER, JOSLYN (T&B) 34. PLASTIC TIES FOR TREE WIRES (TOP TIE, TANGENT SIDE TIE, DOUBLE ANGLE SIDE TIE), PLP 35. FIBERGLASS CROSSARMS, PUPI 36. SINGLE / EXTENSION LADDER, WERNER 37. LIGHTNING ARRESTER (RATED VOLATAGE: 10KV, 12KV, 15KV, 18KV, 27KV, 36KV, MCOV: 8.4KV), OHIO BRASS 38. HDPE PIN TYPE INSULATOR (PLP) 39. LINE POST INSULATOR, 69 KV, DALIAN 40. PIN TYPE INSULATOR (15KV-34.5KV), DALIAN 41. PORCELAIN SUSPENSION INSULATOR, DALIAN 42. SCREW INSULATOR, CHINA 43. SPOOL INSULATOR (1 3/8?, 1 3/4?, 3?), DALIAN 44. SUSPENSION INSULATOR (VOLTAGE RATING: 15KV, 34.5KV, 69KV) DALIAN/VICTOR/PL 45. SM-4, POWER FUSE ASSEMBLY (15KV MAX 200 AMP S&C) 46. SM-5, POWER FUSE ASSEMBLY (34.5KV MAX 300 AMP) S&C 47. SMD-20 POWER FUSE ASSEMBLY (200 A, 15KV), S&C 48. SMD-50, POWER FUSE ASSEMBLY RATING: 65A, 80A, 100A (69KV) S&C', 0, 'Ground Floor, Keystone Building 220 Sen. Gil Puyat Ave. Makati City Metro Manila', '+632-843-8981 to 86, +632-816-1329, +632-893-1975, +632-843-1720', NULL, '+63-2-818-6527 / +63-2-843-6777', NULL, '', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(554, 'Mclenton Engineering Services and Supply', 'Installation of Automatic Sprinkler System, Fire Alarm System, Plumbing System, Electrical System (Residential, Building & Industrial), Civil Works, Painting Works & Finishing Works, Fire Safety Products & Equipment, Industrial Products Equipment & Services, General Engineering', 0, '636 Ylac Brgy. Villamonte, Bacolod City', '(034) 434-8446 / 435-2247 / 0950-385-7441', NULL, '', NULL, '', 'Contractor', '', '', 'Active', '2.00', 1, ''),
(555, 'Paradigm Top-Notch Trading', 'Gaskets, Packings & Seals Fabrication: Polyurethane, Engineering Plastic, Rubber, Teflon, Metal, Ducting, Ventilation  System and Accessories, cooling tower accessories Services: Fiberglass coating / installation, rubber product repair Mineral Wool Installation Mechanical Supplies: KOSO Valves & Accessories, CHINO Instruments, Bearings Insulation Materials. 1. Fabrication Using Polyurethane, ENGINEERING PLASTICS, Rubber, Metals such as Machine Parts, Ventillators, Dust Collector, Fans and Blowers, Screw Conveyor, Rebonding and Relining, Teflon Machining. 2. Gaskets such as COMPRESSED NON-ASBESTOS SHEET, GRAPHITE SHEET, GRAPHITE SHEET W/ WIRE, NEOPRENE RUBBER SHEET, TEFLON SHEET / RODS. 3. Packings such as ASBESTOS GRAPHITED PACKING, ASBESTOS GRAPHITED w/ wire, KEVLAR PACKING, LUBRICATED, MARINE PACKING, PURE GRAPHITE PACKING, PURE TEFLON PACKING, NON ASBESTOS PACKING, TEFLON GRAPHITE PACKING. 4. Insulation Materials such as MINERAL WOOL, SILICA/FIBERGLASS FIBERS, CERAMIC BOARD, FIBERGLASS ROPE, FIBERGLASS TAPE. ', 0, 'Unit  602, Prime Land, Market St., Medrigal Business Park, Alabang, Muntinlupa City', '772-1667 / Telefax: 772-1755', NULL, '', NULL, '', 'Distributor / Supplier / Contractor', 'Mr. Cresencio Garcia - 0927-0276-115 / 0917-885-6371', '', 'Active', '1.00', 1, ''),
(556, 'BP Industrial Supplies & Services Inc', 'Product Lines: - Air Dryers (Desiccant & Refrigerated) - Nitrogen Generator - Air Compressor - Filters (Industrial & Commercial) - Condensate Automatic Drain - Replacement Filter for Ingersoll Rand, Domnick Hunter, Atlas Copco, Sullair, others  - Dust, Mist Fumes Collection System Torit, DCE - Water Filtration - Sterile, Steam  =and Vent Filters - Industrial Chiller - Fabrication of Spiral Duct - Fabrication of Filters Services Offered: - Compressed Air Purification Preventive Maintenance -Desiccant Air Dryer - Refrigerated Dryers - Chillers - Refrigerated System - Air Conditioning System  - Dew Point Testing - Air Flow Measurement', 0, 'Sangi Road, Lapu-Lapu City, Cebu', '(032) 342 8284', NULL, '', NULL, '', 'Distributor / Supplier', '', '', 'Active', '1.00', 1, ''),
(557, 'Icetech Systems', 'Portable Radios, Antenna, Power Supplies, Accessories (mounts, magnets and cables, earpiece, speaker mics, charger and eliminator)', 0, '1125 Iris Tower Tivoli Garden Residences, Mandaluyong City', '0905-519-0734; 0933-816-5330', NULL, '', NULL, '', 'Distributor / Supplier', '', '', 'Active', '1.00', 1, ''),
(558, 'Ultra-Seer Inc.', '- Respiratory Protection, Personal Protective Equipment (PPEs), Fire Service Products,  Gas Monitoring Equipment, Mining Specialty Equipment, Fall Protection Equipment - Latchways Sealed Self Retracting Lanyards, Latchways Transfastener,  - Latchways Personal Rescue Device - Air Purifying Respirators, Supplied Air Respirators - Head, Face, Eye and Ear Protection - Fire Service Products - Gas Detector - Mining and Specialty Equipment - Fall Protection (Full Body Harness, Lanyards, Carabiners, Anchorage Connectors,  Suspension Trauma, FP Rescue Ropes - Confined Space Equipment - Suretyman Rescue Utility System', 0, 'No. 3, 2nd Street Brgy. Kapitolyo, Pasig City', '(632) 470-6047; 470-6048; 470-4232; 637-2166', NULL, '(632) 637-2166', NULL, '', 'Distributor / Supplier', '', '', 'Active', '1.00', 1, ''),
(559, 'ASISI Sytems Corp.', 'Fire Protection System', 0, 'JMC Bldg., Blk 4 Lot 4, Ocean Park Street, Brgy. Sauyo, Sauyo Road, Novaliches, Quezon City', '(02) 983-1082 / 983-5640 / 935-6680', NULL, '(02) 939-5759', NULL, '', 'Distributor / Supplier', 'Shela Mae Cadilig &  Angelica Salcedo', '', 'Active', '1.00', 1, ''),
(560, 'Mysolutions', '- Time Keeping, Access Control, Smart Locks, Hotel Locks, Entrance Control, Parking Barrier X-Ray Inspection System, Metal Detectors, Visitor & Elevator Management, ZKBioSecurity 3.0,  ZKBioGo App, Touchlink Software', 0, '2/F-3/F, 201 Del Monte Ave., Masambong Quezon City, Phils 1105', '(632) 365-8488 / 367-8188 / 365-7313 / 410-6176', NULL, '(632) 364-4068', NULL, '', 'Distributor / Supplier', 'Mr. Aldwin C. Fajardo', '', 'Active', '1.00', 1, ''),
(561, 'HiAdvance Philippines Inc.', 'Services: > Waste Water Analysis > Ambient Air Monitoring > Stack Emission Testing > Asbestos Sampling & Analysis > Drinking Water Analysis > Soil Analysis > Sediments > Hazardous Waste Characterization > Petroleum Hydrocarbons > Trace Metals Determination > Organic & Inorganic Analysis > Work Environment Measurement', 0, '3F Maga Centre Building Street Paseo de, San Antonio, Makati, 1232 Metro Manila', '09175579507 / 729-4327', NULL, '', NULL, '', 'Distributor / Supplier / Contractor', 'Ms. Curee Cruz', '', 'Active', '2.00', 1, '');
INSERT INTO `vendor_head` (`vendor_id`, `vendor_name`, `product_services`, `category_id`, `address`, `phone_number`, `mobile_number`, `fax_number`, `email`, `terms`, `type`, `contact_person`, `notes`, `status`, `ewt`, `vat`, `tin`) VALUES
(563, 'A.M. Builders'' Depot', 'Hardware,Tools, Tiles, Furnitures', 0, 'Chang Building, B.S. Aquino Drive, Capitol Shopping Center, Bacolod City', '(034)709-0055', NULL, '', NULL, 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(564, 'Atlantic Water Store Parts Services', 'Drilling of Deep Well', 0, 'Hernaez St., Bacolod City', '', NULL, '', NULL, '', 'Contractor', 'Mr. Dennis', '', 'Active', '2.00', 0, '171-357-149-0005NV'),
(565, 'Carfix Services', 'Forklift PMS', 0, 'Fuentebella Subd., Bacolod City', '0995-0549-931', NULL, '', NULL, '', '', 'Mr. Juanito Bocol Jr.', '', 'Active', '2.00', 0, ''),
(566, 'New Season Trading, The', 'Fans, Kitchen wares', 0, 'The Atrium, Gonzaga St, Bacolod, 6100 Negros Occidental', '', NULL, '', NULL, '', '', '', '', 'Active', '1.00', 1, '104-069-278-0000'),
(567, 'K-Mart', 'General Needs, Liquid Soap, Tools', 0, 'Galo St., Bacolod City', '(034)434-9614', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 1, ''),
(568, 'Priscilla Ysabelle Tuazon', 'Alcohol', 0, 'Bacolod City', '', NULL, '', NULL, 'COD', '', '', '', '', '0.00', 0, ''),
(569, 'First Gear Industrial Supply', 'Oil Seal, O-ring, Mechanical Seals', 0, 'Lot 70 a&b Eusebio Arcade, Brgy. 40, Lacson St., Bacolod City', '(034)432-7862 / 432-9135 / 213-8469', NULL, '', NULL, 'COD', 'Distributor', 'Jordan Beranio', '', 'Active', '0.00', 1, ''),
(570, 'DIAMOND MARKETING', 'Fishing Supply', 0, 'Luzuriaga St, Bacolod City', '(034)435-4721', NULL, '', NULL, 'COD', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(571, 'NNT68 Fishing Supply', 'Fish Net, Poly Rope, Nylon line, Hook, Fishing Supplies', 0, 'Luzuriaga St, Bacolod City', '(034)435-0499 / 434-1821', NULL, '', NULL, 'COD-Cash', 'Distributor', '', '', 'Active', '1.00', 1, ''),
(572, 'Soonest Global Express Corp.', 'Customs Brokerage, Domestic and International Forwarding, Dor-to-Door Delivery, Breakbulk Agent, Cargo Consolidator,', 0, '1908 Mendoza Guazon St., Brgy. 832 Zone 90, District VI Paco, Manila 1007', '(02) 564-8652, 562-7279, 562-7624', NULL, '(02) 563-8988', NULL, '', 'Cargo Forwarder', '', '', 'Active', '2.00', 1, 'VAT Reg. TIN 006-798-503-000'),
(573, 'Tech-Quipped Corporation', 'distributor of ALFA LAVAL boiler parts, Danfoss VFD, Victaulic Valves and Pipe Coupling, Thermo Cables Specialty Cables, We also supply boiler equipment,', 0, '2/F Elizabeth Center, National Road, Putatan, Muntinlupa City, 1772', '632-861-3607 / 6: 632-861-3506 / 09088669644', NULL, '', NULL, '', 'Distributor', 'Weng Mohammed - weng.mohammed@tech-quipped.com', '', 'Active', '0.00', 1, ''),
(574, 'ALEX LUMBER YARD', 'HARDWARE|LUMBER/WOOD DEALER: ALL KINDS', 0, 'GALO-MABINI STS., [FIELD_BARANGAY], BACOLOD CITY', '434 4041', NULL, '', NULL, 'COD', '', '', '', 'Active', '0.00', 0, ''),
(575, 'Unilink Industrial Instrumentation Sales & Services', 'KROHNE (Germany) - Flow, Level and Analytical Instruments  TOKYO KEISO (Japan) - Flow and Level Instruments  TOKICO (Japan) - PD Type Oil(Diesel, Bunker, and Kerosene) Flowmeters  FORBES  MARSHALL (India) - Pressure and Temperature Transmitters, Control Valves  TOMOE (Japan) - Manual and Control Butterfly Valves  VATAC (China) - Various Valves and accessories  ASAHI YUKIZAI (Japan) - Manual and Control Plastic Valves, Plastic pipes and Fittings  IBS (Germany) - Batch Controller  UFLOW (India) - Solenoid Valves  GENERAL GAUGES (India) - Temperature and Pressure Gauges, Thermocouples and accessories.  POLYGON PIPE (China) - Plastic Pipes and fittings  Advance CAE Pte Ltd - Packaged (Analyser & Dosing) Systems', 0, 'Unit 1201, España Tower, 2203 España Blvd, Corner Josefina St. Sampaloc Manila, 1008', '(+632) 5310-1991 l (+632) 8567-1985 l (+632) 5310-5368; Mobile: (+63956) 051-3806', NULL, '(+632) 5310-8963', NULL, '', 'Distributor / Supplier', 'Dann Genesis A. Pilapil - dapilapil@unilinkph.com', '', 'Active', '1.00', 1, ''),
(576, 'Switch Industrial Sales Corporation', 'Electrical', 0, 'Benavidez St, Tondo, Manila, 1008', '(02) 8251-2553', NULL, '', NULL, 'COD', 'Distributor / Supplier', 'Mr. Paul Vivar', '', 'Active', '1.00', 1, ''),
(577, 'Native Store', 'Broom (Pay- Pay), Broom Stick, Dust Pan', 0, 'Burgos Street, Bacolod City', '', NULL, '', NULL, 'COD', '', 'Ms. Ma. Fe', '', 'Active', '0.00', 0, ''),
(578, 'Cebu Microasia Marketing', 'Braided Packings (Pure PTFE Packing) Cutting Disc (Stainless Steel Metal) Diamond Blade "Gaskets (BRAXTON 201 - Glass Fiber Sleeve, BRAXTON 801 - Compressed Non-Asbestos Gaskets, BRAXTON 803 - Rubber GasketSheet Reinforced with ply, BRAXTON 806 - Expanded PTFE Joint Sealant, BRAXTON 808 -  Nitrile O-Ring Cord, BRAXTON 4038 - Silicone Rubber Coated Fiberglass Sleeve, " Grinding Disc PPR Fittings (Coupling, Elbow, Female Adaptor, Female Elbow, Female Tee, Male Adaptor, Male Elbow, Male Tee, Plastic Union, Reducer, Shower Valve Plain, Tee, Tee Reducer) Welding Kit PPR PIPES Valves', 0, 'Alfonso & Sons Building Door 11, Corner Rizal St. & A.C Cortes Avenue Ibabao, Mandaue City, Cebu', '(032) 272-6996 / 417 4485 / 0925-7425-321/09338195039', NULL, '', NULL, 'COD', 'Distributor / Supplier', 'Jacinto Pechan (Sales Representative) - 0933-819-5039', '', 'Active', '1.00', 1, ''),
(579, 'VCY Sales Corporation', 'Construction, Hardware', 0, 'Sum-ag, Bacolod City', '(034) 445 2382', NULL, '', NULL, 'COD', 'Supplier', 'Ms. Ann', 'For RFQ and POs email to roseann.resco@vcygroup.com', 'Active', '1.00', 1, '005-430-314-0015'),
(580, 'Javiero''s Hollow Blocks Factory', 'Hollow Blocks, Mixing Sand, Gravel', 0, 'Bago City', '', NULL, '', NULL, 'COD', '', 'Ms. Jocelyn', '', 'Active', '1.00', 1, ''),
(581, 'Mirasol Tire Supply', 'Battery', 0, 'Rizal St, Bacolod', '433-6123', NULL, '', NULL, '', '', 'Sir Roy', '', 'Active', '0.00', 0, ''),
(582, 'Sasaki Auto Parts', 'Battery', 0, 'Gonzaga Lacson St., Bacolod', '(034) 4376-1167 / 0916 467 8618', NULL, '', NULL, 'COD', '', 'Sir Romer', '', 'Active', '0.00', 0, ''),
(583, 'ENERGREEN POWER INTER-ISLAND CORP. / ECMG', '', 0, '88 Cor. Rizal-Mabini St., Prk. Mahigugmaon, Brgy. 22, Bacolod City', '0909-991-1466', NULL, '', NULL, '50% DP, 50 % - 30 days Upon Delivery', '', '', '', 'Active', '2.00', 1, ''),
(584, 'Central Pilipinas Power & Automation,inc.', 'Electric Motors, Generator Sets', 0, '2/F, Veloso Building, Prisco Deiparine Street, Zone 3, San Isidro, Talisay City, Cebu', '+63 (922) 858 3920 / +63 (917) 561 5266', NULL, '', NULL, 'COD', 'Supplier / Contractor', 'Ms. Weng - 0995-315-9211', '', 'Active', '2.00', 1, ''),
(585, 'Negros PJL Multi-Line Distribution Inc.', 'Petron Lubricants, Tires', 0, 'San Lorenzo Ruiz Bldg., Brgy, 31 Lopez Jaena St, Bacolod City', '(034) 434-6666', NULL, '', NULL, '', 'Distributor / Supplier', '', '', 'Active', '1.00', 1, ''),
(586, 'DAILY DOCTORS VENTURES INC.', 'medical Supplies', 0, 'Door 1 AVA Construction Bldg., Lopez Jaena St., Bacolod City', '(034) 458-5226/435-2839', NULL, '', NULL, 'COD', '', 'Mary Joy', '', 'Active', '0.00', 0, ''),
(590, 'B & A PHARMACY, DRUG DISTRIBUTOR AND GENERAL MERCHANDISE as Medical Devices Wholesaler', 'medical Supplies', 0, 'Amado Parreno Agricultural Corp. Bldg., Burgos-Lacson St., Barangay 19, Barangay 19, Bacolod, Negros Occidental', '(034) 700-0076', NULL, '', NULL, 'COD', '', 'Althea', '', 'Active', '0.00', 0, '153-735-252-000'),
(591, 'H.A.S Calibration Shop And Services', 'Generator Set Calibration, PMS, Servicing', 0, '#22 Encarnacion Subd., Bakyas, Mansilingan, Bacolod City', '(034) 707-6420 / 0933-0435-485', NULL, '', NULL, '50% DP, 50% 30 days PDC', 'Contractor', 'Mr. Alfredo Sardon - fsardpng@gmail.com', '', 'Active', '1.00', 1, 'VAT Reg. 428-864-155-000'),
(592, 'Central Pilipinas Power & Automation,inc.', 'Generator Set Calibration, PMS, Servicing', 0, '2nd Floor, Veloso Building, Rizal Street, Talisay, 6015', '0917 561 5266 /', NULL, '', NULL, 'COD', 'Supplier / Contractor', 'Ms. Weng - 0995-315-9211', '', 'Active', '2.00', 1, ''),
(593, 'JBR Electro-Mechanical Services, Inc.', 'Generator Testing ( Hi Pot)', 0, 'No. 6 Pisces St., Pleasant Homes Subd., Punta Princesa, Cebu City, 6000', '(032) 272-1145 / 512-6360', NULL, '', NULL, 'COD', 'Contractor', 'Mr. Oscar P. Pasilan - 0918-781-2932 / 0942-074-0190', '', 'Active', '2.00', 1, ''),
(594, 'B. S. Pharma Drugs and Medical Supplies', 'Medical Supplies, Disposable PPE', 0, 'Lacson Cor. Burgos Sts., Brgy. 19, Bacolod City', '(034) 434-4315', NULL, '', NULL, 'COD', 'Supplier', '', '', 'Active', '1.00', 1, '148-427-904-0001'),
(595, 'JDN Pharmacy', 'medical supplies', 0, '2 Burgos Ave, Bacolod, 6100 Negros Occidental', '(034) 474 0561', NULL, '', NULL, 'COD', '', '', '', 'Active', '0.00', 0, ''),
(596, 'Aeshan Motor Oils Trading', 'Fuel Filter, Tellus, Oils', 0, 'Brgy. 33 Amapola Street Bacolod City', '09303602291', NULL, '', NULL, 'COD', '', 'Ms. Grace', '', 'Active', '0.00', 0, ''),
(597, 'Synergy Sales International Corporation', 'plastics supllies', 0, '2nd Floor, Door 2, 7-C Building, Lacson Extension, Bacolod, 6100 Negros Occidental', '(034) 434-8024 | 476-2156 | 433-0087 | 433-0089', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 0, ''),
(598, 'Trio Med Multi Sales, Inc.', 'medical supply', 0, 'TMSI Building, Doña Carmen Ave, Bacolod, 6100 Negros Occidental', '434-445/4615', NULL, '', NULL, 'COD', '', '', '', 'Active', '0.00', 0, ''),
(599, 'Maksamiya Medical Supplies & Equipment Trading', 'medical supply', 0, 'Double D Bldg. Door #1, BS Aquino Dr, Bacolod, 6100 Negros Occidental', '0929 129 3772', NULL, '', NULL, 'COD', '', '', '', 'Active', '0.00', 0, ''),
(600, 'Kametsino', 'Customed Tshirt, Polo shirt, shirt jack, PE,Office uniform, sportswear, formal attire, and a lot more.', 0, 'San Juan St, Bacolod, 6100, 6100 Negros Occidental', '0919 990 2268', NULL, '', NULL, 'COD', '', '', '', 'Active', '0.00', 0, ''),
(601, 'Joshbianc Motor Parts', 'Auto Parts and Accessories', 0, 'Door # 9 LM Bldg. Gonzaga St. Bacolod City', '(034) 474-0549', NULL, '', NULL, 'COD', 'Distributor / Supplier', '', '', 'Active', '0.00', 1, ''),
(602, 'Bacolod National Lumber Yard', 'Hardware / Construction Supplies', 0, 'Hernaez St, Brgy.31, Bacolod, 6100 Negros Occidental', '(034) 435-4454', NULL, '', NULL, 'COD', '', '', '', '', '0.00', 0, ''),
(603, 'Progen Dieseltech Services Corp.', 'O-ring, Cylinder Head', 0, 'Purok San Jose, Brgy. Calumangan, Bago City, Negros Occidental, 6101', '213-6591 / 0966-5043-886', NULL, '', NULL, '50% Down payment, 50% upon Delivery', '', 'Ms. Merry Michelle Dato', '', 'Active', '1.00', 1, ''),
(604, 'MCO Trading and Services', 'Cylinder Head Repair', 0, 'VTL Compound, Sector 2, Mandaue City, Cebu', '0917-633-8038 / (032) 418-3565 / 318-4587', NULL, '', NULL, 'COD', 'Contractor', 'Mr. Harold Seno - mco_cebu@yahoo.com.ph', '', 'Active', '2.00', 0, ''),
(605, 'RK RUBBER ENTERPRISE CO.', 'Rubber Seal, Rubber Strip,Rubber Gasket,Rubber Matting,Rubber Column Guard, Rubber SDock bumper, Anti Vibration Pad,topper, Rubber Dock Fender, Rubber Bumper, Loading  Rubber Pad, Seismic Gap Filler, Rubber Wheel Stopper, Rubber Matting, etc., Industrial Parts/ Components, Engineering Sector (Civil, Automobile, Mechanical, etc…), Trucking Industry, Constructions (Roads, Railroads, Dams, etc…, Commercial and Residential, Food Industry', 0, '#63 F.Gochan St., Mabolo, Cebu City', '(+63)-916-444-0421/(+63)-961-604-3588', NULL, '', NULL, '', '', 'Josel Requiza Manila', '', 'Inactive', '0.00', 0, ''),
(606, 'Macjils Refrigeration and Air Conditioning Repair Shop', 'Air Conditioning Unit (ACU) Brand New Units, Repair, Maintenance', 0, 'Prk. Sto. Rosario, lacson St., Bacolod City', '(034) 707-0639 / 0919-637-0637', NULL, '', NULL, 'COD', 'Supplier / Contractor', 'Mr. Mamerto Yalong - mamertoyalong@gmail.com', '', 'Active', '2.00', 0, ''),
(607, 'Bernie Macahusay', 'Installation of Electrical Materials and Wiring', 0, 'Prk. PBR, Brgy. Calumangan, Bago City', '0951-583-7226', NULL, '', NULL, 'COD Upon Completion', 'Contractor', '', '', 'Active', '0.00', 0, ''),
(608, 'ANE Electronic & Airconditioning Technology', 'Air Conditioning Unit (ACU) Brand New Units, Repair, Maintenance', 0, 'Trivi Bldg., Dawis San Sebastian, Bacolod City', '(034) 441-3451 / 0932-290-7618', NULL, '', NULL, 'COD', 'Supplier / Contractor', 'Mr. Vicente Tingjuy', '', 'Active', '2.00', 1, ''),
(609, 'United Power & Resources', 'Rental of Generators, Load Banks, Transformer and Cooling System. Rental of  100 kVA to 800 kVA Denyo Brand Generator set from Japan, 1250 kVA Cummins Brand Generator set from United Kingdom and Transformer 2500KVA and 3500 kVA rated LV 460 VAC HV 13800/22000 Charoenchai Brand from Thailand, Load Bank / Dummy Load, Liquid Chiller, Air Handling Unit and Packaged Aircon Unit', 0, 'Nissan Technopark Brgy. Pulong Sta. Cruz, Sta. Rosa Laguna', '049 572 2375 / 049 572 2412 / 0928 446 6861 / 0928 663 4891 / 0915 543 1285 / 0915 770 7933', NULL, '', NULL, 'COD', 'Rental', 'Mark Santos - Sales Engineer ( mark.s@upr.ph ) - 09478896127', '', 'Active', '2.00', 1, ''),
(610, 'GC APPLIANCE CENTRUM', 'Appliances; Furniture', 0, 'Lacson St, Bacolod, 6100 Negros Occidental', '(034) 434 6995', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 0, ''),
(611, 'EMCOR', 'Appliances; Furniture', 0, 'JVR Bldg., Cor. Araneta-San Sebastian Sts, Bacolod, 6100', '(034) 435 2077', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 0, ''),
(612, 'Builder''s World', 'Hardware tools', 0, 'BMLPC Bldg.,, Lopez Jaena St, Bacolod, Negros Occidental', '(034) 707 7107', NULL, '', NULL, 'COD', '', '', '', 'Active', '0.00', 1, ''),
(613, 'JBEE DEPARTMENT STORE', 'Appliances; Tools; households', 0, 'Luzuriaga St, Barangay 39 Pob., Bacolod City, 6100, Pilipinas', '', NULL, '', NULL, 'COD', '', '', '', 'Active', '0.00', 1, ''),
(614, 'AVC Engineering Supplies & Services', 'Civil / Mechanical, Structural Works, Boiler Installation, Piping Installation, Solar Heater Installation, Mechanical Equipment Installation, Design and Fabrication of Expansion Joint, Sealing Materials, SS Screen, Oil Cups, Seamless Heat Exchanger Tubes, Metal Compensators, Rubber Compensators, Fabric Compensators, Compressed Gaskets, Graphited Gaskets, Asbestos Gasket, Teflon Packing, Flexible Graphite Packing, Metallic Gasket, Mechanical Seal, Boiler Plenum Seal', 0, 'Block 27 Lot 2 East Home 4 Subd., Mansilingan, Bacolod City', '(034) 458-7245 / 0947-768-6124 / 0916-300-8019', NULL, '', NULL, 'COD', 'Fabricator / Manufacturer / Supplier / Contractor', 'Mr. Andres Cepe - 0995-199-1951', 'email add: AVCengineeringandservices@gmail.com', 'Active', '1.00', 1, '153-732-952-000'),
(615, 'Bacolod Pumps & Machinery Centre', '', 0, 'A-9, Negros First Cybercenter, Hernaez St, Bacolod City', '(034) 433 6332', NULL, '', NULL, 'COD', 'Supplier / Contractor', 'Ms. Donna', '', 'Active', '2.00', 1, ''),
(616, 'DCM General Technology', 'Air Conditioning Unit (ACU) Brand New Units, Repair, Maintenance', 0, 'Ground Floor Jacman Bldg., Gonzaga St., Bacolod City', '(034) 458-3283', NULL, '', NULL, 'COD', 'Contractor', 'Ms. Joyce Cabalfin', '', 'Active', '2.00', 1, ''),
(617, 'Dinnis Ambong', 'Bending of Plain Sheets', 0, 'Brgy. Villamonte, Bacolod City', '', NULL, '', NULL, 'COD', 'Contractor', 'Mr. Dinnis Ambong', '', 'Active', '0.00', 0, ''),
(618, 'Fashion House Marketing, Inc.', '', 0, 'Gonzaga St, Bacolod City', '0946-636-9274', NULL, '', NULL, 'COD', 'Supplier', 'Ms. Marites', '', 'Active', '1.00', 1, 'VAT Reg. TIN 000-425-539-000'),
(619, 'Venancio Arpal Tailoring', 'Tailoring Services  at Fashion House', 0, 'Prk. San Roque, Brgy. Tangub, Bacolod City', '0922-701-2326 / 0947-7696-285 / 445-1726', NULL, '', NULL, 'COD', 'Manufacturer', 'Nong Ben', '', 'Active', '0.00', 0, ''),
(620, 'Bacolod Luis Paint Center Enterprises. Inc.', 'Paints', 0, 'Capitol Shopping Center, Tindalo Ave, Brgy.,Villamonte, Bacolod', '(034) 434 1609', NULL, '', NULL, 'COD-Cash', 'Wholesaler / Retailer / Distributor', 'Ms. Marissa Estrada', '', 'Active', '1.00', 1, '258-267-375-000'),
(621, 'Phoenix Petroleum Philippines Inc.', 'FUEL', 0, 'Cambodia St. BREDCO Reclamation Area, Bacolod City', '403-4013', NULL, '', NULL, 'COD', '', '', '', 'Active', '1.00', 1, ''),
(622, 'Negros Prawn Producers Cooperative', 'Water Analysis', 0, 'Door No. 1 & 2., NEDF Bldg., 6th Street, Bacolod City 6100', '(034) 433-2131', NULL, '(034) 433-2131', NULL, 'COD', 'Analytical & Diagnostic Laboratory', '', '', 'Active', '2.00', 0, ''),
(623, 'Hyundai Corporation', 'Generator Sets', 0, 'Unit 1608 Ayala Tower One and Exchange Plaza, Makati City', '+632-8814-0595  Mob: +63-998-952-7814', NULL, '', NULL, 'COD', 'Manufacturer', 'Ms. Angelica A. Pasion', '', 'Active', '1.00', 1, ''),
(624, 'KSB Philippines, Inc.', 'Pumps, Butterfly Valves, Diaphragm Valves, Control Valves, Pumps and Fire Protection Systems, Control Cabinets and emergency power generators', 0, '3rd Floor, Executive Building Center, 369 Senator Gil Puyat Avenue, Corner Makati Avenue, Makati City 1209', '(02) 845-0392 to 92', NULL, '(02) 551-1057', NULL, 'COD', 'Manufacturer', 'Mr. Noriel Oquias - Noriel.Oquias@ksb.com - 0917-3081-656', '', 'Active', '1.00', 1, ''),
(625, 'Cummins Sales and Service Philippines Incorporated', '', 0, '1954 LIIP Ave, Biñan, Laguna', '(02) 7717 8165', NULL, '', NULL, '', '', '', '', '', '0.00', 0, ''),
(626, 'Southline Automotive Parts, Tire and Services', 'Plain Sheet Bending', 0, 'Phase 2, Blk 4, Lot 10, San Esteban Subd., Bago City, Negros Occidental', '0933-850-0154', NULL, '', NULL, 'COD', 'Supplier', '', '', 'Active', '2.00', 0, '278-124-888-0002'),
(627, 'Traders Industrial Supply Co., Inc. (TRISCO)', 'Ultrasonic Flaw Detector ( EPOCH 6LT, EPOCH 650), Ultrasonic Thickness Gauging (38DL PLUS, 27MG, 45MG), EDDY Current Testing (NORTEC 600), Composite Bond Testing (Bondmaster 600), Mineral Wool Insulation, Ceramic Fiber Insulation, Stud Welding System, Safety Jogger Safety Shoes, PPE (Safety Vest, Hard Hat, Safety Googles, Dusk Mask)', 0, '24th Floor Trident Tower 312 Sen. Gil J. Puyat Ave., Bel-Air Makati City', '(+632) 8817-8914 (+632) 8817-9004 (Local 111)', NULL, '', NULL, 'COD', 'Distributor / Supplier', 'Gemima D. Feeney - 09297622575 / 09759601152', '', 'Active', '1.00', 1, ''),
(628, 'Handyman- Robinsons Bacolod', 'Tools; Home Decor; Plumbing;Chemicals and cleaning; Electrical & Lighting; Emergency and Safety', 0, 'Lacson St, Bacolod, 6100 Negros Occidental', '(034) 441 0466', NULL, '', NULL, 'COD', '', '', '', 'Active', '1.00', 1, ''),
(629, 'Amp''s Refrigeration & Airconditioning Service Center', 'Heating, Ventilating & Air Conditioning Service', 0, 'Door 1 JR Building, Rizal St., Brgy. 24, 6100 Bacolod City', '(034) 703-3948 / 0907-665-8770', NULL, '', NULL, 'COD', 'Contractor / Supplier', '', '', 'Active', '2.00', 1, ''),
(630, 'Pos Marketing Enterprises Inc', 'Appliances, Furniture and households materials', 0, '50 San Sebastian Ave, Bacolod, Negros Occidental', '434-1793/708-0600', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 1, ''),
(631, 'Benjamin V. Alano', 'Mechanical Coupler', 0, 'Villa Caridad Subd. La Carlota City', '09088971856', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 0, ''),
(632, 'Giga Ohms Electrical Supply and Services', 'Electrical / Mechanical Supplies and Services, motor rewinding', 0, 'Block 6 lot 19 east homes 3 Brgy. Estefania Bacolod City', '(034) 458-7136', NULL, '(034) 458-7136', NULL, 'COD', '', 'Mr. Jury Bayson - jurybayson@yahoo.com', '', 'Active', '2.00', 1, ''),
(633, 'ERT Industrial Trading Corporation', 'Diesel', 0, 'Alijis Road, Bacolod City, Negros Occidental', '', NULL, '', NULL, '', '', '', '', 'Active', '1.00', 1, ''),
(634, 'PJL LIBERTAD SERVICE STATION', 'Diesel', 0, 'Hernaez-Lacson Sts., 40, Bacolod City', '(034) 434-0925/09177007730', NULL, '', NULL, '', '', 'Ms. Leah', '', 'Active', '1.00', 1, '000-427-066-0000'),
(635, 'BACOLOD CHINA MART', 'School Supplies/Office Supplies', 0, '8-7 Luzuriaga St, Bacolod, 6100 Negros Occidental', '434-7292/435-2468', NULL, 'COD', NULL, '', '', 'Juri', '', 'Active', '1.00', 1, ''),
(636, 'New China Enterprise', 'School Supplies/Office Supplies', 0, '55 Luzuriaga St, Bacolod, 6100 Negros Occidental', '433-5808/444-2773', NULL, '', NULL, '', '', 'Jonah', '', 'Active', '1.00', 1, ''),
(637, 'Ken-tool Hardware Corp.', '', 0, 'Balintawak St, Tondo, Manila, 1012 Metro Manila', '(02) 8252 0871', NULL, '', NULL, '', '', '', '', '', '0.00', 0, ''),
(638, 'Sensors & Measuring Instruments Corp.', '', 0, '8418, Mayapis Street, Makati City 1224 Metro Manila', '+63 (2) 8 896 6896', NULL, '', NULL, '', '', '', '', '', '0.00', 0, ''),
(639, 'Far Eastern Drug, Incorporated', '', 0, '103-105 Pres. Osmena Boulevard, Cebu City 6000', '(032) 255 8742', NULL, '', NULL, '', '', '', '', '', '0.00', 0, ''),
(640, 'TOP-RIGID INDUSTRIAL SAFETY SUPPLY INC.', '', 0, 'West 4th, Lungsod Quezon No 9 Kalakhang Maynila, 1104 Philippines', '+632 83737576', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 0, ''),
(641, 'PRESIDIUM.PH CORPORATION', '', 0, 'PRESIDIUM.PH CORPORATION', '(02) 8257 0795', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 0, ''),
(642, 'DM''s Oil Purifier Services and Supply', '', 0, '', '', NULL, '', NULL, '', '', '', '', '', '0.00', 0, ''),
(643, 'Petron Corporation', 'Diesel Fuel, Gasoline, Lubricants', 0, 'San Patricio, Bacolod City', '(632) 884-9200 VOIP 55206 / 34433-9685 to 86 / 0917-567-8372', NULL, '(632) 34433-9687', NULL, 'COD', 'Manufacturer', 'Mr. Karlo Jim E. Mayote', 'kemayote@petron.com', 'Active', '1.00', 1, ''),
(644, 'ATEX Automation and Technologies Corporation', 'We provide services in: 1.) Calibration of Instruments (Transmitters, Gauges, Testers, Analyzers, etc.) 2.) FDAS - Fire Detection and Alarm System and Fire Suppression System (Supply and Maintenance) 4.) Burner System Maintenance 5.) Pneumatic Actuator Maintenance 6.) Travel and Tripper Maintenance 3.) Automation (Systems-related project: PLC and DCS Programming, Graphic designing)  We supply: 1.) Time Electronics -Calibration Bench System (CalBench) -Portable Loop and Temperature Calibration Instrument -Multifunction Calibrators  2.) Georgin Instrument -Pressure Transmitters -Pressure Gauges -Temperature Transmitters -Temperature Gauge with Switch Contacts  3.) VESDA -Detection for DCS, PLC, Switchgear, MCC -Early Warning Aspirating Smoke Detection -Visual Smoke Verification -STATX Fire Suppression System  4.) TYCO GAS & FLAME (Group: Scott Safety, GMI, Detcon Inc., Simtronics, Oldham) -Controllers and Transmitters for Fixed Gas Detection Systems -Portable Gas Detectors -Gas Detector Accessories -Gas Analysers -Fixed Gas and Flame Detection Systems  5.) Supmea -Pressure/Temperature Recorder -Magnetic Flowmeters -Vortex Flowmeter -Handheld Ultrasonic Flowmeter  6.) Heat-Edge -Fabricated Heaters -Fabricated Temperature Sensor  7.) Indurad -Industrial Radar Scanners  8.) WEKA Level Indicators -Float Level Switch -Magnetic Level Switch  9.) Logika - Furnace Camera  10.) Hyundai -Heavy Equipments', 0, '354-A St. Louis corner St. Paul Brookside Cainta 1900 Rizal', '+632 903 7988 / +63 997 795 1787', NULL, '+63 997 795 1787', NULL, 'COD', 'Distributor / Supplier', 'Ms. Cathrena C. Balisbes - Sales and Application Engineer,  ccb@atexautomation.com / info@atexautomation.com', '', 'Active', '1.00', 1, ''),
(645, 'POWERCOMP INDUSTRIAL SALES & SERVICES', '', 0, 'B56, L27 Legazpi St., Upper Bicutan, , Taguig City, Metro Manila, NCR, Philippines, 1630.', '', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 0, ''),
(646, 'Wescor Transformer Corporation', '', 0, 'Villilia Village, 10 Tikling St, Novaliches, Quezon City, 1116 Metro Manila', '(02) 8356 2309', NULL, '', NULL, '', '', '', '', 'Active', '0.00', 0, ''),
(647, 'Arditech Corporation', '', 0, '2163 Rizal Avenue corner A. Lorenzo Jr. St (formerly, Batangas St, Santa Cruz, Manila, 1014 Metro Manila', '(02) 8251 9991', NULL, '', NULL, 'COD', 'Distributor / Supplier', '', '', 'Active', '1.00', 1, ''),
(648, 'Powertechnic Lightning Inc.', '', 0, 'Galeria De Binondo Condo, Elegant Towers, 603, Numancia St, Binondo, Manila, 1006', '(02) 8243 6946', NULL, '', NULL, 'COD', 'Distributor / Supplier', '', '', 'Active', '1.00', 1, ''),
(649, 'Heng Ji Commercial Corporation', 'Petron Ditributor', 0, '67 Rizal St, Bacolod City', '(034) 434 2187 / 0917-3228-060', NULL, '', NULL, 'COD', 'Distributor / Supplier', 'Ms. Raymundo', '', 'Active', '1.00', 1, ''),
(650, 'Royal Cargo Inc.', '-International and Domestic Sea Freight, International and Domestic Air Freight, Customs Clearance Brokerage, Hauling / Trucking, Contract Logistics / Warehousing, Projects and Heavy Lifts', 0, 'Bacolod City', '+63 (917) 853-3870', NULL, '', NULL, '', 'Logistics Services', 'Mr. Ron Michael Subang', '', 'Active', '2.00', 1, ''),
(651, 'Jayawon Airconditioning Services - Bacolod', 'Air Conditioning Unit (ACU) Brand New Units, Repair, Maintenance', 0, '#31 Zircon Street, City heights, Bacolod City', '0946 439 9989', NULL, '', NULL, 'COD Upon Completion', 'Supplier / Contractor', '', '', 'Active', '2.00', 0, ''),
(652, 'Dije P. Ref & Aircon Repair & Services', 'Air Conditioning Unit (ACU) Brand New Units, Repair, Maintenance', 0, 'Araneta Ave, Singcang-Airport, Bacolod City', '0908 308 4840', NULL, '', NULL, 'COD Upon Completion', 'Supplier / Contractor', '', '', 'Active', '2.00', 0, ''),
(653, 'Temp Expert Airconditioning Corporation', 'Air Conditioning Unit (ACU) Brand New Units, Repair, Maintenance', 0, 'Door 5, Goldenfield Commercial Complex, Singcang-Airport, Bacolod City', '(034) 433 0971', NULL, '', NULL, 'COD Upon Completion', 'Supplier / Contractor', '', '', 'Active', '2.00', 1, ''),
(654, 'GEL AIRE - Airconditioning & Refrigeration Supply & Services', 'Air Conditioning Unit (ACU) Brand New Units, Repair, Maintenance', 0, 'Cor. Makiling Street, BS Aquino Dr, Bacolod City', '(034) 709 1334', NULL, '', NULL, 'COD Upon Completion', 'Supplier / Contractor', '', '', 'Active', '2.00', 1, ''),
(655, 'Lhl Aircon Repair Shop', 'Air Conditioning Unit (ACU) Brand New Units, Repair, Maintenance', 0, 'Circumferential Road, C.L. Montelibano Ave., Brgy. Villamonte, Bacolod City', '(034) 434 8238', NULL, '', NULL, 'COD Upon Completion', 'Supplier / Contractor', '', '', 'Active', '2.00', 1, ''),
(656, 'Lan''s Refrigeration And Airconditioning Repair And Services', 'Air Conditioning Unit (ACU) Brand New Units, Repair, Maintenance', 0, 'Brgy. Sum-ag, Bacolod City', '0912 902 1812', NULL, '', NULL, 'COD Upon Completion', 'Supplier / Contractor', '', '', 'Active', '2.00', 1, ''),
(657, 'WELD POWERTOOLS INDUSTRIAL MACHINERY CORP', 'Powertools', 0, 'WELD Bldg, S.B.Cabahug, Mandaue City, 6014 Cebu', '0922-751-0251', NULL, '', NULL, 'COD', 'Distributor', '', '', 'Active', '0.00', 1, ''),
(658, 'Vilma''s Dry Goods Store', 'drygoods', 0, '291, Central Public Market, Gatuslao Street, Barangay 12, Bacolod, 6100', '434-8112', NULL, '', NULL, '', '', 'Chona', '', 'Active', '0.00', 0, ''),
(659, 'Monark CAT', '', 0, 'Araneta Ave, Bacolod, 6100 Negros Occidental', 'Araneta Ave, Bacolod, 6100 Negros Occidental', NULL, '', NULL, '', '', 'Ms. Hanna', '', 'Active', '1.00', 1, '000-385-447-006'),
(660, 'E.VALENCIA CALTEX SERVICE STATION', 'Diesel', 0, 'Rizal St, Bacolod, 6100 Negros Occidental', '435-8704', NULL, '', NULL, '', '', 'Ms. Glaiza', '', 'Active', '1.00', 1, '');

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
-- Indexes for table `company`
--
ALTER TABLE `company`
 ADD PRIMARY KEY (`company_id`);

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
-- Indexes for table `joi_ar`
--
ALTER TABLE `joi_ar`
 ADD PRIMARY KEY (`joi_ar_id`);

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
-- Indexes for table `project_activity`
--
ALTER TABLE `project_activity`
 ADD PRIMARY KEY (`proj_act_id`);

--
-- Indexes for table `pr_calendar`
--
ALTER TABLE `pr_calendar`
 ADD PRIMARY KEY (`pr_calendar_id`);

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
-- Indexes for table `terms`
--
ALTER TABLE `terms`
 ADD PRIMARY KEY (`terms_id`);

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
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=142;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `joi_ar`
--
ALTER TABLE `joi_ar`
MODIFY `joi_ar_id` int(11) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `jo_rfq_details`
--
ALTER TABLE `jo_rfq_details`
MODIFY `jo_rfq_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `jo_rfq_head`
--
ALTER TABLE `jo_rfq_head`
MODIFY `jo_rfq_id` int(11) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `project_activity`
--
ALTER TABLE `project_activity`
MODIFY `proj_act_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pr_calendar`
--
ALTER TABLE `pr_calendar`
MODIFY `pr_calendar_id` int(11) NOT NULL AUTO_INCREMENT;
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
-- AUTO_INCREMENT for table `terms`
--
ALTER TABLE `terms`
MODIFY `terms_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
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
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `usertype`
--
ALTER TABLE `usertype`
MODIFY `usertype_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `vendor_details`
--
ALTER TABLE `vendor_details`
MODIFY `vendordet_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `vendor_head`
--
ALTER TABLE `vendor_head`
MODIFY `vendor_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=661;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
