-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2019 at 09:15 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_procurement`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
`employee_id` int(11) NOT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT '0',
  `position` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `employee_name`, `department_id`, `position`) VALUES
(1, 'Ma. Milagros Arana', 0, 'General Manager'),
(2, 'Rhea Arsenio', 0, 'Trader'),
(3, 'Jonah Faye Benares', 0, 'Software Development Supervisor'),
(4, 'Kervic Biñas', 0, 'Procurement Assistant'),
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
(18, 'Nazario Shyde Jr. Ibañez', 0, 'Trader'),
(19, 'Gebby Jalandoni', 0, 'Accounting Assistant'),
(20, 'Caesariane Jo', 0, 'Trader'),
(21, 'Lloyd Jamero', 0, 'IT Specialist'),
(22, 'Annavi Lacambra', 0, 'Corporate Accountant'),
(23, 'Ma. Erika Oquiana', 0, 'Trader'),
(24, 'Charmaine Rei Plaza', 0, 'Energy Market Analyst'),
(25, 'Cresilda Mae Ramirez', 0, 'Internal Auditor'),
(26, 'Melanie Rocha', 0, 'Utility'),
(27, 'Zyndyryn Rosales', 0, 'Finance Supervisor'),
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
(112, 'Accounting Department', NULL, NULL),
(113, 'Admin Department', NULL, NULL),
(114, 'IT Department', NULL, NULL),
(115, 'Silena Jomiller', 0, 'Admin Assistant'),
(117, 'Board Room', NULL, NULL),
(118, 'Carlos Antonio Leonardia', 0, 'Senior Project Engineer'),
(119, 'Liza Marie Tasic', 0, ''),
(120, 'Adrian Nemes', 0, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
 ADD PRIMARY KEY (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=121;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
