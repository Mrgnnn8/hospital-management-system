-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: Dec 10, 2025 at 09:47 AM
-- Server version: 10.8.8-MariaDB-1:10.8.8+maria~ubu2204
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `staffno` varchar(20) NOT NULL,
  `role` varchar(50) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `staffno`, `role`, `action_type`, `description`, `ip_address`, `timestamp`) VALUES
(6, 'MJ111', 'Admin', 'PARKING_APPROVE', 'Approved Application #10. Permit: P-191', '192.168.65.1', '2025-12-08 13:35:56'),
(7, 'MJ111', 'Admin', 'PARKING_REQUEST', 'Requested Yearly permit for YH61ZVP', '192.168.65.1', '2025-12-08 13:36:37'),
(8, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21814', '192.168.65.1', '2025-12-08 13:39:32'),
(9, 'MJ111', 'Admin', 'RECORD_TEST', 'Recorded Test ID 10 for Patient W21814', '192.168.65.1', '2025-12-08 13:40:05'),
(10, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21814', '192.168.65.1', '2025-12-08 13:40:05'),
(11, 'JE909', 'Admin', 'CREATE_PATIENT', 'Registered patient Olivia Turner (NHS: W12345)', '192.168.65.1', '2025-12-08 13:42:22'),
(12, 'JE909', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W12345', '192.168.65.1', '2025-12-08 13:42:22'),
(13, 'MJ111', 'Admin', 'UPDATE_PROFILE', 'Updated personal details and login username (to admin) for Staff ID MJ111.', '192.168.65.1', '2025-12-08 13:46:33'),
(14, 'MJ111', 'Admin', 'UPDATE_PROFILE', 'Updated personal details and login username (to admin) for Staff ID MJ111.', '192.168.65.1', '2025-12-08 13:46:47'),
(15, 'MJ111', 'Admin', 'UPDATE_PROFILE', 'Updated personal details and login username (to admin) for Staff ID MJ111.', '192.168.65.1', '2025-12-08 13:55:28'),
(16, 'MJ111', 'Admin', 'UPDATE_PROFILE', 'Updated personal details and login username (to admin) for Staff ID MJ111.', '192.168.65.1', '2025-12-08 13:55:30'),
(17, 'MJ111', 'Admin', 'UPDATE_PROFILE', 'Updated personal details and login username (to admin) for Staff ID MJ111.', '192.168.65.1', '2025-12-08 13:55:34'),
(18, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Atkin (QM004). Username: N/A', '192.168.65.1', '2025-12-08 14:00:55'),
(19, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Atkin (QM004). Username: N/A', '192.168.65.1', '2025-12-08 14:01:01'),
(20, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Atkin (QM004). Username: N/A', '192.168.65.1', '2025-12-08 14:01:05'),
(21, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Atkin (QM004). Username: jaktkin', '192.168.65.1', '2025-12-08 14:01:33'),
(22, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Atkin (QM004). Username: jaktkin', '192.168.65.1', '2025-12-08 14:01:44'),
(23, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Atkin (QM004). Username: jaktkin', '192.168.65.1', '2025-12-08 14:05:50'),
(24, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Atkin (QM004). Username: jaktkin', '192.168.65.1', '2025-12-08 14:05:56'),
(25, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Atkin (QM004). Username: jaktin', '192.168.65.1', '2025-12-08 14:06:56'),
(26, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Atkin (QM004). Username: jatkin', '192.168.65.1', '2025-12-08 14:09:24'),
(27, 'MJ111', 'Admin', 'SEARCH_DOCTOR', 'Searched directory for: \'N/Amorgan\'', '192.168.65.1', '2025-12-08 14:11:40'),
(28, 'MJ111', 'Admin', 'SEARCH_DOCTOR', 'Searched directory for: \'Morgan\'', '192.168.65.1', '2025-12-08 14:11:45'),
(29, 'MJ111', 'Admin', 'SEARCH_DOCTOR', 'Searched directory for: \'Morgan\'', '192.168.65.1', '2025-12-08 14:11:58'),
(30, 'MJ111', 'Admin', 'SEARCH_DOCTOR', 'Searched directory for: \'Morgan\'', '192.168.65.1', '2025-12-08 14:12:10'),
(31, 'MJ111', 'Admin', 'CHANGE_PASSWORD', 'User manually changed their password.', '192.168.65.1', '2025-12-08 14:14:38'),
(32, 'MJ111', 'Admin', 'CREATE_PATIENT', 'Registered patient Olivia Turner (NHS: W12345)', '192.168.65.1', '2025-12-08 14:16:34'),
(33, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W12345', '192.168.65.1', '2025-12-08 14:16:34'),
(34, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21814', '192.168.65.1', '2025-12-08 14:17:25'),
(35, 'MJ111', 'Admin', 'RECORD_TEST', 'Recorded Test ID 20 for Patient W21814', '192.168.65.1', '2025-12-08 14:17:29'),
(36, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21814', '192.168.65.1', '2025-12-08 14:17:29'),
(37, 'MJ081', 'Staff', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21895', '192.168.65.1', '2025-12-08 14:18:33'),
(38, 'MJ081', 'Staff', 'RECORD_TEST', 'Recorded Test ID 20 for Patient W21895', '192.168.65.1', '2025-12-08 14:18:37'),
(39, 'MJ081', 'Staff', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21895', '192.168.65.1', '2025-12-08 14:18:37'),
(40, 'MJ111', 'Admin', 'CREATE_DOCTOR', 'Added Dr. Turner (wfjge)', '192.168.65.1', '2025-12-08 14:21:52'),
(41, 'MJ111', 'Admin', 'UPDATE_DOCTOR', 'Updated Dr. Jones (MJ111). Username: admin', '192.168.65.1', '2025-12-08 14:25:41'),
(42, 'MJ081', 'Staff', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21961', '192.168.65.1', '2025-12-08 14:27:36'),
(43, 'ML690', 'Staff', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21961', '192.168.65.1', '2025-12-08 16:05:48'),
(44, 'ML690', 'Staff', 'RECORD_TEST', 'Recorded Test ID 3 for Patient W21961', '192.168.65.1', '2025-12-08 16:05:52'),
(45, 'ML690', 'Staff', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21961', '192.168.65.1', '2025-12-08 16:05:52'),
(46, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21814', '192.168.65.1', '2025-12-08 16:06:43'),
(47, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21814', '192.168.65.1', '2025-12-08 16:09:03'),
(48, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21814', '192.168.65.1', '2025-12-08 17:02:39'),
(49, 'MJ081', 'Staff', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21814', '192.168.65.1', '2025-12-08 17:20:44'),
(50, 'MJ111', 'Admin', 'CREATE_TEST', 'Added new test type: UI', '172.18.0.1', '2025-12-08 17:58:05'),
(51, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21961', '172.18.0.1', '2025-12-08 17:58:15'),
(52, 'MJ111', 'Admin', 'RECORD_TEST', 'Recorded Test ID 21 for Patient W21961', '172.18.0.1', '2025-12-08 17:58:20'),
(53, 'MJ111', 'Admin', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21961', '172.18.0.1', '2025-12-08 17:58:20'),
(54, 'MJ081', 'Staff', 'VIEW_PATIENT', 'Accessed record for Patient NHS: W21961', '172.18.0.1', '2025-12-08 19:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `staffno` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `Specialisation` varchar(100) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `pay` int(11) NOT NULL,
  `gender` int(11) DEFAULT NULL,
  `consultantstatus` int(11) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`staffno`, `firstname`, `lastname`, `Specialisation`, `qualification`, `pay`, `gender`, `consultantstatus`, `address`, `username`) VALUES
('CH007', 'Steve', 'Fan', '0', NULL, 67000, 0, 1, '45 The Barnum Nottingham NG2 6TY', NULL),
('GT067', 'Julie', 'Ford', '0', 'CCT', 66000, 1, 1, NULL, NULL),
('JE909', 'Angelina', 'Joline', 'N/A', NULL, 50120, 1, 1, 'N/A', 'jelina'),
('MJ081', 'Nelly', 'Mceards', '0', NULL, 100000, 1, 1, '20 Aspen Court, Church Street, Emley, HD8 9RW', 'mceards'),
('MJ111', 'Morgan', 'Jones', '0', 'N/A', 1000000000, 0, 1, 'N/A', 'admin'),
('ML690', 'Jacob', 'Moorland', '0', NULL, 27000, 0, 0, '11 Nottingham Road, Nottingham, NG1 1GN', 'moorland'),
('QM003', 'Joel ', 'Graham', '0', NULL, 44000, 0, 0, '1 Chatsworth Avenue, Carlton, Nottingham, NG4', NULL),
('QM004', 'Jason', 'Atkin', '0', 'CCT', 60000, 0, 1, '102 Leeming Lane South, Mansfield Woodhouse, Mansfield', 'jatkin'),
('QM009', 'Grazziela', 'Luis', '0', 'CCT', 62000, 1, 1, '16 Lenton Boulevard, Lenton, Nottingham, NG7 2ES', NULL),
('QM122', 'David', 'Ulrik', '0', NULL, 46000, 0, 0, '3 Rolleston Drive, Nottingham', NULL),
('QM267', 'Andrew', 'Xin', '0', 'CCT', 58000, 0, 1, '44 Dunlop Avenue, Lenton, Nottingham NG1 5AW', NULL),
('QM300', 'Joy', 'Liz', '0', 'CCT', 52000, 1, 0, '55 Wishford Avenue, Lenton, Nottingham', NULL),
('QT001', 'Martin', 'Peter', '0', NULL, 48000, 0, 0, '47 Derby Road, Nottingham, NG1 5AW', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `parking_permit_status`
--

CREATE TABLE `parking_permit_status` (
  `permit_application_id` int(11) NOT NULL,
  `StaffNo` varchar(20) NOT NULL,
  `vehicle_reg` varchar(20) NOT NULL,
  `permit_choice` enum('Monthly','Yearly','Annual') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `permit_activation_date` date DEFAULT NULL,
  `permit_end_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `request_date` text NOT NULL,
  `last_update` text NOT NULL,
  `permit_no` varchar(50) DEFAULT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking_permit_status`
--

INSERT INTO `parking_permit_status` (`permit_application_id`, `StaffNo`, `vehicle_reg`, `permit_choice`, `amount`, `permit_activation_date`, `permit_end_date`, `status`, `request_date`, `last_update`, `permit_no`, `notes`) VALUES
(10, 'MJ111', 'YH61ZVP', 'Monthly', 20.00, NULL, NULL, 'Approved', '2025-12-08 13:22:23', '2025-12-08 13:35:55', 'P-191', 'Pending'),
(11, 'MJ111', 'YG74 XXX', 'Yearly', 200.00, NULL, NULL, 'Approved', '2025-12-08 13:24:03', '2025-12-08 13:33:01', 'P-191', 'Pending'),
(12, 'MJ111', 'YH61ZVP', 'Yearly', 200.00, NULL, NULL, 'Awaiting approval', '2025-12-08 13:36:37', '2025-12-08 13:36:37', NULL, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `NHSno` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `phone` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `emergencyphone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`NHSno`, `firstname`, `lastname`, `phone`, `address`, `age`, `gender`, `emergencyphone`) VALUES
('W20616', 'Zoya', 'Kalim', '07656999653', '668 Watnall Road, Hucknall, Nottingham, NG15', 18, '1', NULL),
('W20620', 'Nazia', 'Rafiq', '07798522777', '1 Pelham Crescent, Beeston NG9', 37, '1', NULL),
('W21028', 'Max', 'Wilson', '07740312868', '4 Lake Street, Nottingham, NG7 4BT', 33, '0', NULL),
('W21758', 'Alex', 'Kai', '06654742456', '52 Chatsworth Avenue, Carlton, Nottingham, NG4', 46, '0', NULL),
('W21814', 'Chao', 'Chen', '077 25 765428', 'Lake Street, Nottingham, NG7 4BT\r\n\r\n', 36, '0', NULL),
('W21895', 'Liz', 'Felton', '074 56 733 487', '100 Hawton Crescent, Wollaton, NG8 1BZ', 23, '1', NULL),
('W21961', 'Jeremie ', 'Clos', '07754312868', '22 Hawton Crescent, Wollaton, NG8 1BZ', 45, '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patientexamination`
--

CREATE TABLE `patientexamination` (
  `patientid` varchar(100) NOT NULL,
  `doctorid` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patientexamination`
--

INSERT INTO `patientexamination` (`patientid`, `doctorid`, `date`, `time`) VALUES
('W20616', 'CH007', '2023-12-21', '11:23:11'),
('W20616', 'QM004', '2022-10-18', '10:23:19'),
('W20616', 'QM267', '2022-02-02', '08:23:19'),
('W20620', 'GT067', '2023-06-18', '07:06:05'),
('W20620', 'QM300', '2023-11-08', '09:09:19'),
('W21028', 'QM003', '2021-11-08', '09:23:19'),
('W21758', 'GT067', '2020-11-11', '11:23:05'),
('W21814', 'QM122', '2023-12-12', '02:02:10'),
('W21814', 'QT001', '2016-03-03', '08:18:18'),
('W21895', 'QM003', '2019-11-19', '08:09:10'),
('W21895', 'QM009', '2021-11-19', '08:08:08');

-- --------------------------------------------------------

--
-- Table structure for table `patient_test`
--

CREATE TABLE `patient_test` (
  `pid` varchar(100) NOT NULL,
  `testid` int(11) NOT NULL,
  `date` varchar(100) NOT NULL,
  `report` varchar(100) DEFAULT NULL,
  `doctorid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_test`
--

INSERT INTO `patient_test` (`pid`, `testid`, `date`, `report`, `doctorid`) VALUES
('W20616', 6, '2023-10-01', NULL, 'QM003'),
('W21028', 3, '2021-11-07', NULL, 'QM004'),
('W21028', 8, '2021-11-11', NULL, 'QM004'),
('W21758', 6, '', NULL, 'CH007'),
('W21758', 12, '', NULL, 'QM122'),
('W21814', 3, '2023-02-17', NULL, 'QM267'),
('W21814', 3, '2023-02-18', NULL, 'QM300'),
('W21814', 5, '', NULL, 'QM009'),
('W21895', 5, '2023-06-07', NULL, 'QM300'),
('W21895', 5, '2023-06-08', NULL, 'QM267'),
('W21895', 7, '2023-06-09', NULL, 'CH007'),
('W21961', 3, '2025-12-08', 'N/A', 'moorland'),
('W21961', 4, '2019-10-18', NULL, 'QM004');

-- --------------------------------------------------------

--
-- Table structure for table `TEST`
--

CREATE TABLE `TEST` (
  `testid` int(11) NOT NULL,
  `testname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `TEST`
--

INSERT INTO `TEST` (`testid`, `testname`) VALUES
(1, 'Blood count'),
(2, 'Urinalysis'),
(3, 'CT scan'),
(4, 'Ultrasonography'),
(5, 'Colonoscopy'),
(6, 'Genetic testing'),
(7, 'Hematocrit'),
(8, 'Pap smear'),
(9, 'X-ray'),
(10, 'Biopsy'),
(11, 'Mammography'),
(12, 'Lumbar puncture'),
(13, 'thyroid function test'),
(14, 'prenatal testing'),
(15, 'electrocardiography'),
(16, 'skin test'),
(20, 'Covid-19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(50) NOT NULL,
  `password` varchar(250) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `is_admin`) VALUES
('mceards', 'lord456', 0),
('moorland', 'buzz48', 0),
('admin', '12345', 1),
('jelina', 'iron99', 1),
('jatkin', 'jab77', 0),
('olivia', 'turner', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ward`
--

CREATE TABLE `ward` (
  `wardid` int(11) NOT NULL,
  `wardname` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `noofbeds` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ward`
--

INSERT INTO `ward` (`wardid`, `wardname`, `address`, `phone`, `noofbeds`) VALUES
(1, 'Dermatology', 'Floor A Room 234 Derby Rd, Lenton, Nottingham NG7 2UH', '0115 970 9215', 45),
(2, 'Urology', 'Queen\'s Medical Centre, Derby Rd, Lenton, Nottingham NG7 2UG', '0115 870 9215', 43),
(3, 'Orthopaedics ', 'Floor C Room 234 Derby Rd, Lenton, Nottingham NG7 2UH', '0115 678 9215', 33),
(4, 'Accident and emergency', 'Queen\'s Medical Centre, Derby Rd, Lenton, Nottingham NG7 2UH', '0115 986 9215', 66),
(5, 'Cardiology', 'Floor A Room 32 Derby Rd, Lenton, Nottingham NG7 2UH', '0115 986 6578', 67);

-- --------------------------------------------------------

--
-- Table structure for table `wardpatientaddmission`
--

CREATE TABLE `wardpatientaddmission` (
  `pid` varchar(100) NOT NULL,
  `wardid` int(11) NOT NULL,
  `consultantid` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wardpatientaddmission`
--

INSERT INTO `wardpatientaddmission` (`pid`, `wardid`, `consultantid`, `date`, `time`, `status`) VALUES
('W20616', 1, 'QM004', '2022-10-07', '09:23:19', 1),
('W20616', 2, 'QM122', '2023-10-01', '07:23:19', 1),
('W20616', 3, 'QM009', '2018-12-07', '08:13:55', 1),
('W20616', 5, 'QM267', '2022-06-07', '21:23:19', 0),
('W20620', 4, 'QM267', '2021-10-07', '08:08:08', 1),
('W21028', 2, 'CH007', '2021-11-07', '08:23:19', 0),
('W21758', 2, 'QM122', '2018-11-27', '23:55:56', 0),
('W21758', 4, 'QT001', '2023-09-29', '08:23:19', 1),
('W21814', 3, 'QM003', '2023-02-17', '08:33:33', 1),
('W21895', 4, 'CH007', '2023-06-07', '21:23:19', 0),
('W21961', 5, 'QM009', '2019-10-18', '08:34:19', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`staffno`);

--
-- Indexes for table `parking_permit_status`
--
ALTER TABLE `parking_permit_status`
  ADD PRIMARY KEY (`permit_application_id`),
  ADD UNIQUE KEY `permit_application_id` (`permit_application_id`),
  ADD KEY `fk_doctor` (`StaffNo`) USING BTREE;

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`NHSno`);

--
-- Indexes for table `patientexamination`
--
ALTER TABLE `patientexamination`
  ADD PRIMARY KEY (`patientid`,`doctorid`,`date`,`time`);

--
-- Indexes for table `patient_test`
--
ALTER TABLE `patient_test`
  ADD PRIMARY KEY (`pid`,`testid`,`date`),
  ADD KEY `testid` (`testid`);

--
-- Indexes for table `TEST`
--
ALTER TABLE `TEST`
  ADD PRIMARY KEY (`testid`);

--
-- Indexes for table `ward`
--
ALTER TABLE `ward`
  ADD PRIMARY KEY (`wardid`);

--
-- Indexes for table `wardpatientaddmission`
--
ALTER TABLE `wardpatientaddmission`
  ADD PRIMARY KEY (`pid`,`wardid`,`consultantid`,`date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `parking_permit_status`
--
ALTER TABLE `parking_permit_status`
  MODIFY `permit_application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `TEST`
--
ALTER TABLE `TEST`
  MODIFY `testid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parking_permit_status`
--
ALTER TABLE `parking_permit_status`
  ADD CONSTRAINT `fk_parking_staff` FOREIGN KEY (`StaffNo`) REFERENCES `doctor` (`staffno`) ON UPDATE CASCADE;

--
-- Constraints for table `patient_test`
--
ALTER TABLE `patient_test`
  ADD CONSTRAINT `patient_test_ibfk_1` FOREIGN KEY (`testid`) REFERENCES `TEST` (`testid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
