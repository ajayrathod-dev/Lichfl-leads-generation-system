-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 02, 2025 at 02:45 AM
-- Server version: 10.6.23-MariaDB-cll-lve
-- PHP Version: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siddhi`
--

-- --------------------------------------------------------

--
-- Table structure for table `aavak_reg`
--

CREATE TABLE `aavak_reg` (
  `id` int(11) NOT NULL,
  `rec_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `who` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `shera` varchar(255) NOT NULL,
  `forward_to` varchar(255) NOT NULL,
  `by` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `branch` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `add_visitors`
--

CREATE TABLE `add_visitors` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `whoose_meet` varchar(255) NOT NULL,
  `reasion` varchar(255) NOT NULL,
  `by` varchar(255) NOT NULL,
  `date` varchar(255) DEFAULT NULL,
  `origionaldate` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `branch` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `emp_name` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `designation_if` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `present_staff` varchar(255) NOT NULL DEFAULT 'Out',
  `origionaldate` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `f_name` varchar(255) DEFAULT NULL,
  `dob` varchar(20) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `nation` varchar(10) DEFAULT NULL,
  `martial_status` varchar(20) DEFAULT NULL,
  `p_address` varchar(255) DEFAULT NULL,
  `c_address` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(11) DEFAULT NULL,
  `alt_mob_no` varchar(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `i_mark` varchar(50) DEFAULT NULL,
  `blood_group` varchar(20) DEFAULT NULL,
  `adhar_no` varchar(100) DEFAULT NULL,
  `pan_no` varchar(20) DEFAULT NULL,
  `bank_acc` varchar(100) DEFAULT NULL,
  `doj` varchar(10) DEFAULT NULL,
  `appointment_type` varchar(20) DEFAULT NULL,
  `probation_perriod` varchar(20) DEFAULT NULL,
  `dop` varchar(20) DEFAULT NULL,
  `security_tokan` varchar(20) DEFAULT NULL,
  `qualification` varchar(20) DEFAULT NULL,
  `university` varchar(100) DEFAULT NULL,
  `yearofpass` varchar(20) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `dateofpro` varchar(10) DEFAULT NULL,
  `desig` varchar(20) DEFAULT NULL,
  `noc` varchar(20) DEFAULT NULL,
  `ord_no` varchar(10) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `casual_leave` varchar(10) DEFAULT NULL,
  `earned_leave` varchar(10) DEFAULT NULL,
  `medical_leave` varchar(10) DEFAULT NULL,
  `other_leave` varchar(10) DEFAULT NULL,
  `half_day_leave` varchar(10) DEFAULT NULL,
  `transfer_date` varchar(10) DEFAULT NULL,
  `from_branch` varchar(100) DEFAULT NULL,
  `to_branch` varchar(100) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `action_date` varchar(20) DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `disciplinary_remarks` varchar(100) DEFAULT NULL,
  `resignation_date` varchar(100) DEFAULT NULL,
  `reasion1` varchar(255) DEFAULT NULL,
  `notice_period` varchar(100) DEFAULT NULL,
  `final_settlement` varchar(10) DEFAULT NULL,
  `relieving_date` varchar(10) DEFAULT NULL,
  `remark1` varchar(11) DEFAULT NULL,
  `by` varchar(30) DEFAULT NULL,
  `daily_salary` varchar(255) DEFAULT NULL,
  `monthly_salary` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_update_log`
--

CREATE TABLE `admin_update_log` (
  `id` int(11) NOT NULL,
  `emp_name` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `present_staff` int(255) NOT NULL DEFAULT 0,
  `origionaldate` timestamp(6) NOT NULL DEFAULT current_timestamp(6),
  `f_name` varchar(255) DEFAULT NULL,
  `dob` varchar(20) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `nation` varchar(10) DEFAULT NULL,
  `martial_status` varchar(20) DEFAULT NULL,
  `p_address` varchar(255) DEFAULT NULL,
  `c_address` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(11) DEFAULT NULL,
  `alt_mob_no` varchar(11) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `i_mark` varchar(50) DEFAULT NULL,
  `blood_group` varchar(20) DEFAULT NULL,
  `adhar_no` varchar(100) DEFAULT NULL,
  `pan_no` varchar(20) DEFAULT NULL,
  `bank_acc` varchar(100) DEFAULT NULL,
  `doj` varchar(10) DEFAULT NULL,
  `appointment_type` varchar(20) DEFAULT NULL,
  `probation_perriod` varchar(20) DEFAULT NULL,
  `dop` varchar(20) DEFAULT NULL,
  `security_tokan` varchar(20) DEFAULT NULL,
  `qualification` varchar(20) DEFAULT NULL,
  `university` varchar(100) DEFAULT NULL,
  `yearofpass` varchar(20) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `dateofpro` varchar(10) DEFAULT NULL,
  `desig` varchar(20) DEFAULT NULL,
  `noc` varchar(20) DEFAULT NULL,
  `ord_no` varchar(10) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `casual_leave` varchar(10) DEFAULT NULL,
  `earned_leave` varchar(10) DEFAULT NULL,
  `medical_leave` varchar(10) DEFAULT NULL,
  `other_leave` varchar(10) DEFAULT NULL,
  `half_day_leave` varchar(10) DEFAULT NULL,
  `transfer_date` varchar(10) DEFAULT NULL,
  `from_branch` varchar(100) DEFAULT NULL,
  `to_branch` varchar(100) DEFAULT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `action_date` varchar(20) DEFAULT NULL,
  `action_type` varchar(20) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `disciplinary_remarks` varchar(100) DEFAULT NULL,
  `resignation_date` varchar(100) DEFAULT NULL,
  `reasion1` varchar(255) DEFAULT NULL,
  `notice_period` varchar(100) DEFAULT NULL,
  `final_settlement` varchar(10) DEFAULT NULL,
  `relieving_date` varchar(10) DEFAULT NULL,
  `remark1` varchar(11) DEFAULT NULL,
  `by` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_report`
--

CREATE TABLE `daily_report` (
  `id` int(11) NOT NULL,
  `achievement_in_no` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `work_type` varchar(200) DEFAULT NULL,
  `description` varchar(999) NOT NULL,
  `by` varchar(255) NOT NULL,
  `date` varchar(200) NOT NULL,
  `date1` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_log`
--

CREATE TABLE `driver_log` (
  `id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `fuel` varchar(255) NOT NULL,
  `oil` varchar(255) NOT NULL,
  `time_out` varchar(255) NOT NULL,
  `time_in` varchar(255) NOT NULL,
  `meter_reading` varchar(255) NOT NULL,
  `meterage` varchar(255) NOT NULL,
  `d_name` varchar(255) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `whoom_used` varchar(255) NOT NULL,
  `by` varchar(255) NOT NULL,
  `date1` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `javak_reg`
--

CREATE TABLE `javak_reg` (
  `id` int(11) NOT NULL,
  `javak_id` int(11) NOT NULL,
  `rec_name` varchar(255) NOT NULL,
  `rec` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `send_type` varchar(255) NOT NULL,
  `shera` varchar(255) NOT NULL,
  `outword_from` varchar(255) NOT NULL,
  `by` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `date1` timestamp NOT NULL DEFAULT current_timestamp(),
  `branch` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave`
--

CREATE TABLE `leave` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `from_date` varchar(255) NOT NULL,
  `to_date` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `by` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `day` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movement`
--

CREATE TABLE `movement` (
  `id` int(255) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `inout` varchar(255) NOT NULL,
  `reasion` varchar(255) NOT NULL,
  `by` varchar(20) NOT NULL,
  `time` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `branch` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ods`
--

CREATE TABLE `ods` (
  `id` int(255) NOT NULL,
  `ots` int(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int(11) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remark_for_my_work`
--

CREATE TABLE `remark_for_my_work` (
  `id` int(11) NOT NULL,
  `assign_work_id` varchar(255) NOT NULL,
  `remark` varchar(9999) NOT NULL,
  `by` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `date1` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remark_for_visitors`
--

CREATE TABLE `remark_for_visitors` (
  `id` int(200) NOT NULL,
  `vid` int(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `by` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `selfie_log`
--

CREATE TABLE `selfie_log` (
  `id` int(11) NOT NULL,
  `image` longblob NOT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `by` varchar(200) NOT NULL,
  `description` varchar(999) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `cname` varchar(200) DEFAULT NULL,
  `cmob` varchar(255) DEFAULT NULL,
  `next_visit` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_assign`
--

CREATE TABLE `work_assign` (
  `id` int(11) NOT NULL,
  `work_registered_by` varchar(255) NOT NULL,
  `work_assigned_to` varchar(255) NOT NULL,
  `work_description` mediumtext NOT NULL,
  `expected_date_of_completion` varchar(9999) NOT NULL,
  `by` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(10) NOT NULL DEFAULT 0,
  `work_in_no` varchar(200) NOT NULL,
  `about_work` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aavak_reg`
--
ALTER TABLE `aavak_reg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `add_visitors`
--
ALTER TABLE `add_visitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_update_log`
--
ALTER TABLE `admin_update_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_report`
--
ALTER TABLE `daily_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `driver_log`
--
ALTER TABLE `driver_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `javak_reg`
--
ALTER TABLE `javak_reg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave`
--
ALTER TABLE `leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movement`
--
ALTER TABLE `movement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ods`
--
ALTER TABLE `ods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remark_for_my_work`
--
ALTER TABLE `remark_for_my_work`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remark_for_visitors`
--
ALTER TABLE `remark_for_visitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `selfie_log`
--
ALTER TABLE `selfie_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_assign`
--
ALTER TABLE `work_assign`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aavak_reg`
--
ALTER TABLE `aavak_reg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `add_visitors`
--
ALTER TABLE `add_visitors`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_update_log`
--
ALTER TABLE `admin_update_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_report`
--
ALTER TABLE `daily_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `driver_log`
--
ALTER TABLE `driver_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `javak_reg`
--
ALTER TABLE `javak_reg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave`
--
ALTER TABLE `leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movement`
--
ALTER TABLE `movement`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ods`
--
ALTER TABLE `ods`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remark_for_my_work`
--
ALTER TABLE `remark_for_my_work`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remark_for_visitors`
--
ALTER TABLE `remark_for_visitors`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `selfie_log`
--
ALTER TABLE `selfie_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_assign`
--
ALTER TABLE `work_assign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
