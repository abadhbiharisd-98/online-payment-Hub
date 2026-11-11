-- Cleaned and compatible SQL dump for MySQL 8+ / MariaDB / Cloud Providers (Aiven, TiDB, etc.)
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table structure for table `admin_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_list` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `fullname` text NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin_list` (`admin_id`, `fullname`, `username`, `password`, `type`, `status`, `date_created`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 1, 1, '2022-02-10 07:21:51')
ON DUPLICATE KEY UPDATE `admin_id`=`admin_id`;

-- --------------------------------------------------------
-- Table structure for table `department_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `department_list` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text NOT NULL,
  `status` text NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `department_list` (`department_id`, `name`, `status`, `date_created`) VALUES
(1, 'Information Technology', '1', '2022-02-10 07:38:48'),
(2, 'Human Resource Dept', '1', '2022-02-10 07:39:04'),
(3, 'Marketing', '1', '2022-02-10 07:39:09'),
(4, 'Graphic Design', '1', '2022-02-10 07:39:22'),
(5, 'Engineering', '1', '2022-02-10 07:39:27')
ON DUPLICATE KEY UPDATE `department_id`=`department_id`;

-- --------------------------------------------------------
-- Table structure for table `designation_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `designation_list` (
  `designation_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `designation_list` (`designation_id`, `name`, `description`, `status`, `date_created`) VALUES
(1, 'Sr. Software Engineer', 'Senior Software Engineer', 1, '2022-02-10 07:39:56'),
(2, 'Jr. Software Engineer', 'Junior Software Engineer', 1, '2022-02-10 07:40:07'),
(3, 'HR Manager', 'HR Manager', 1, '2022-02-10 07:40:24'),
(4, 'HR Staff', 'HR Staff', 1, '2022-02-10 07:40:35'),
(5, 'Graphic Designer', 'Graphic Designer', 1, '2022-02-10 07:40:48'),
(6, 'Content Writer', 'Content Writer', 1, '2022-02-10 07:41:00'),
(7, 'Project Manager', 'Project Manager', 1, '2022-02-10 07:41:14')
ON DUPLICATE KEY UPDATE `designation_id`=`designation_id`;

-- --------------------------------------------------------
-- Table structure for table `employee_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `employee_list` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `code` varchar(100) NOT NULL,
  `firstname` text NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` text NOT NULL,
  `gender` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `email` text NOT NULL,
  `contact` text NOT NULL,
  `address` text NOT NULL,
  `department_id` int(11) NOT NULL,
  `designation_id` int(11) NOT NULL,
  `monthly_salary` double NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `department_id` (`department_id`),
  KEY `designation_id` (`designation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `employee_list` (`employee_id`, `code`, `firstname`, `middlename`, `lastname`, `gender`, `dob`, `email`, `contact`, `address`, `department_id`, `designation_id`, `monthly_salary`, `date_created`) VALUES
(1, '2022-0001', 'John', 'D', 'Smith', 'Male', '1997-06-23', 'jsmith@sample.com', '09123456789', 'Sample Address Only', 1, 1, 45000, '2022-02-10 07:42:25'),
(2, '2022-0002', 'Claire', 'G', 'Blake', 'Female', '1995-10-14', 'cblake@sample.com', '09456789123', 'Sample Address 2', 2, 3, 35000, '2022-02-10 07:54:15')
ON DUPLICATE KEY UPDATE `employee_id`=`employee_id`;

-- --------------------------------------------------------
-- Table structure for table `payroll_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payroll_list` (
  `payroll_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `employee_id` int(11) NOT NULL,
  `payroll_month` varchar(50) NOT NULL,
  `salary` double NOT NULL DEFAULT 0,
  `present` double NOT NULL DEFAULT 0,
  `absent` double NOT NULL DEFAULT 0,
  `late_undertime` double NOT NULL DEFAULT 0,
  `late_undertime_deduction` double NOT NULL DEFAULT 0,
  `overtime` double NOT NULL DEFAULT 0,
  `overtime_amount` double NOT NULL DEFAULT 0,
  `total_earnings` double NOT NULL DEFAULT 0,
  `total_deduction` double NOT NULL DEFAULT 0,
  `taxable_income` double NOT NULL DEFAULT 0,
  `gross_income` double NOT NULL DEFAULT 0,
  `withholding_tax` double NOT NULL DEFAULT 0,
  `net_pay` double NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `payroll_list` (`payroll_id`, `employee_id`, `payroll_month`, `salary`, `present`, `absent`, `late_undertime`, `late_undertime_deduction`, `overtime`, `overtime_amount`, `total_earnings`, `total_deduction`, `taxable_income`, `gross_income`, `withholding_tax`, `net_pay`, `date_created`) VALUES
(2, 1, '2022-02', 45000, 11, 0, 12, 51.136, 15, 76.705, 76.705, 2330.136, 22449.605, 22526.31, 323.238, 19872.936, '2022-02-10 08:01:37')
ON DUPLICATE KEY UPDATE `payroll_id`=`payroll_id`;

-- --------------------------------------------------------
-- Table structure for table `deduction_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `deduction_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `payroll_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `payroll_id` (`payroll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `deduction_list` (`id`, `payroll_id`, `name`, `amount`, `date_created`) VALUES
(5, 2, 'Cash Advance', 1500, '2022-02-10 08:01:37'),
(6, 2, 'SSS', 300, '2022-02-10 08:01:37'),
(7, 2, 'HMDF', 100, '2022-02-10 08:01:37'),
(8, 2, 'PhilHealth', 379, '2022-02-10 08:01:37')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Table structure for table `earning_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `earning_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `payroll_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `taxable` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `payroll_id` (`payroll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `settings_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `meta_field` varchar(255) NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `tax_table_list`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tax_table_list` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `payroll_type` int(11) NOT NULL DEFAULT 1,
  `range_from` double NOT NULL DEFAULT 0,
  `range_to` double NOT NULL DEFAULT 0,
  `fixed_tax` double NOT NULL DEFAULT 0,
  `percentage_over` double NOT NULL DEFAULT 0,
  `effective_date` date NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tax_table_list` (`tax_id`, `payroll_type`, `range_from`, `range_to`, `fixed_tax`, `percentage_over`, `effective_date`, `date_created`) VALUES
(1, 2, 0, 10417, 0, 0, '2022-01-01', '2022-02-10 07:43:16'),
(2, 1, 0, 20833, 0, 0, '2022-01-01', '2022-02-10 07:43:30'),
(3, 1, 20834, 33332, 0, 20, '2022-01-01', '2022-02-10 07:43:54'),
(4, 2, 10418, 166660, 0, 20, '2022-01-01', '2022-02-10 07:44:10'),
(5, 1, 33333, 66666, 2500, 25, '2022-01-01', '2022-02-10 07:44:31'),
(6, 2, 16667, 33332, 1250, 25, '2022-01-01', '2022-02-10 07:44:50'),
(7, 2, 0, 8000, 500, 5, '2022-01-01', '2022-02-10 07:45:24'),
(8, 1, 0, 18000, 1000, 10, '2022-01-01', '2022-02-10 07:45:44')
ON DUPLICATE KEY UPDATE `tax_id`=`tax_id`;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
