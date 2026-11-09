-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2025 at 11:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `call_center`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `user_id`, `password`, `name`) VALUES
(1, 'suraj', 'shinde77', 'suraj'),
(2, 'ajay', 'Rathod96', 'Ajay');

-- --------------------------------------------------------

--
-- Table structure for table `admin1`
--

CREATE TABLE `admin1` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin1`
--

INSERT INTO `admin1` (`id`, `user_id`, `password`) VALUES
(1, 'admin', 'Call@1234');

-- --------------------------------------------------------

--
-- Table structure for table `care`
--

CREATE TABLE `care` (
  `1` int(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `care`
--

INSERT INTO `care` (`1`, `user_id`, `password`) VALUES
(1, 'admin', 'admin@5511');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(255) NOT NULL,
  `sent_by` varchar(255) NOT NULL,
  `recieved_by` varchar(255) NOT NULL,
  `msg` varchar(9999) NOT NULL,
  `category` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_datetime` timestamp(6) NULL DEFAULT NULL,
  `complaint_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `sent_by`, `recieved_by`, `msg`, `category`, `date`, `modified_datetime`, `complaint_id`) VALUES
(1, 'ajay', '9607170795', 'n', '1', '2025-11-02 16:44:51', '2025-11-03 05:14:51.000000', '69078a83e1256'),
(2, 'ajay', '9607170795', 'eedfe', '1', '2025-11-02 16:48:19', '2025-11-03 05:18:19.000000', '69078b5347149'),
(3, 'ajay', '9607170795', 'lkjjlsd', '1', '2025-11-02 16:49:59', '2025-11-03 05:19:59.000000', '69078bb7800c2'),
(4, 'ajay', '9607170795', 'fgldfkj', '1', '2025-11-02 16:53:21', '2025-11-03 05:23:21.000000', '69078c819904f'),
(5, 'ajay', '9607170795', 'lfgdfgflgk', '1', '2025-11-02 16:59:30', '2025-11-03 05:29:30.000000', '69078df2a515f'),
(6, 'ajay', 'dfgf', 'Thank you for registering your loan application. Your Loan ID is: COMP20251102223732374. We will update you on the progress shortly.', '1', '2025-11-02 17:07:32', '2025-11-03 05:37:32.000000', 'COMP20251102223732374'),
(7, 'suraj', '9607170795', 'Thank you for registering your loan application. Your Loan ID is: COMP20251102225557247. We will update you on the progress shortly.', '5', '2025-11-02 17:25:57', '2025-11-03 05:55:57.000000', 'COMP20251102225557247'),
(8, 'suraj', '9607170795', 'bnvn', '5', '2025-11-02 17:26:22', '2025-11-03 05:56:22.000000', 'COMP20251102225557247'),
(9, 'suraj', '9607170795', 'vbnb', '5', '2025-11-02 17:26:32', '2025-11-03 05:56:32.000000', 'COMP20251102225557247'),
(10, 'suraj', '9607170795', 'vggbnv', '5', '2025-11-02 17:26:49', '2025-11-03 05:56:49.000000', 'COMP20251102225557247'),
(11, 'suraj', '9607170795', 'fvbvbb', '5', '2025-11-02 17:26:59', '2025-11-03 05:56:59.000000', 'COMP20251102225557247'),
(12, 'Ajay', 'dfgf', 'something', '', '2025-11-12 10:42:00', '2025-11-12 23:12:00.000000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `id` int(11) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `registred_by` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `village` varchar(255) NOT NULL,
  `taluka` varchar(255) NOT NULL,
  `destrict` varchar(255) NOT NULL,
  `complaint_title` varchar(9999) NOT NULL,
  `assign_to` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_datetime` timestamp(6) NULL DEFAULT NULL,
  `complaint_id` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `review` varchar(255) DEFAULT '0',
  `dates` varchar(255) DEFAULT NULL,
  `loan_type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`id`, `mobile_no`, `registred_by`, `name`, `village`, `taluka`, `destrict`, `complaint_title`, `assign_to`, `date`, `modified_datetime`, `complaint_id`, `category`, `review`, `dates`, `loan_type`) VALUES
(1, '9607170795', 'ajay', 'Ajay Rathod', 'Dharashiv', 'Dharashiv', 'Dharashiv', 'n', '9075859528', '2025-11-02 16:44:51', '2025-11-03 05:14:51.000000', '69078a83e1256', '1', '0', NULL, 'Home Loan'),
(2, '9607170795', 'ajay', 'k123', 'weerwe', 'were', 'edfe', 'eedfe', '9075859528', '2025-11-02 16:48:19', '2025-11-03 05:18:19.000000', '69078b5347149', '1', '0', NULL, 'Home Loan'),
(3, '9607170795', 'ajay', 'Suraj sir', 'Dharashiv', 'tuljapur', 'dharashiv', 'lkjjlsd', '9075859528', '2025-11-02 16:49:59', '2025-11-03 05:19:59.000000', '69078bb7800c2', '1', '0', NULL, 'Property Loan'),
(4, '9607170795', 'ajay', 'uploads', 'dlfkjdl', 'dfdlgkjdl', 'dflgkjdl', 'fgldfkj', '9075859528', '2025-11-02 16:53:21', '2025-11-03 05:23:21.000000', '69078c819904f', '1', '0', NULL, 'Home Loan'),
(5, '9607170795', 'ajay', 'uploads', 'Dharashiv', 'were', 'lkkdfj;l', 'lfgdfgflgk', '9075859528', '2025-11-02 16:59:30', '2025-11-03 05:29:30.000000', '69078df2a515f', '1', '0', NULL, 'Home Loan'),
(6, 'dfgf', 'ajay', 'dgfd', 'ghfg', 'ghfg', 'hjtr', 'rgrhr', '9075859528', '2025-11-02 17:07:32', '2025-11-03 05:37:32.000000', 'COMP20251102223732374', '1', '0', NULL, 'Home Loan'),
(7, '9607170795', 'suraj', 'Ajay Rathod', 'Dharashiv', 'dharashiv', 'test', 'sdfksld', '9075859528', '2025-11-02 17:25:57', '2025-11-02 17:26:59.000000', 'COMP20251102225557247', '5', '0', NULL, 'Property Loan');

-- --------------------------------------------------------

--
-- Table structure for table `customer_care_log`
--

CREATE TABLE `customer_care_log` (
  `id` int(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `complaint_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `review` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dropdown`
--

CREATE TABLE `dropdown` (
  `id` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `name` mediumtext NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `dropdown`
--

INSERT INTO `dropdown` (`id`, `user_id`, `name`, `description`) VALUES
(1, '9075859528', 'Call Center', 'call'),
(2, '9075859528', 'Arvind Sir', 'Owner'),
(3, '9307954190', 'Sagar', 'Call Center Manager'),
(4, '7387435844', 'Shravani', 'Call Center'),
(5, '7350175425', 'Call Center', '');

-- --------------------------------------------------------

--
-- Table structure for table `loan_status_history`
--

CREATE TABLE `loan_status_history` (
  `id` int(11) NOT NULL,
  `complaint_id` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `remarks` text DEFAULT NULL,
  `changed_by` varchar(100) DEFAULT NULL,
  `changed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_status_history`
--

INSERT INTO `loan_status_history` (`id`, `complaint_id`, `status`, `remarks`, `changed_by`, `changed_at`) VALUES
(1, 'COMP20251102223732374', 1, 'Loan application registered', 'ajay', '2025-11-02 22:37:32'),
(2, 'COMP20251102225557247', 1, 'Loan application registered', 'suraj', '2025-11-02 22:55:57'),
(3, 'COMP20251102225557247', 2, 'bnvn', 'suraj', '2025-11-02 22:56:22'),
(4, 'COMP20251102225557247', 3, 'vbnb', 'suraj', '2025-11-02 22:56:32'),
(5, 'COMP20251102225557247', 4, 'vggbnv', 'suraj', '2025-11-02 22:56:49'),
(6, 'COMP20251102225557247', 5, 'fvbvbb', 'suraj', '2025-11-02 22:56:59');

-- --------------------------------------------------------

--
-- Table structure for table `ods`
--

CREATE TABLE `ods` (
  `id` int(11) NOT NULL,
  `ots` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ods`
--

INSERT INTO `ods` (`id`, `ots`, `user_id`, `uuid`, `status`, `date`) VALUES
(1, '779397', 'ajay', '690789af3360e', 'unverified', '2025-11-02 17:24:55'),
(2, '657725', 'suraj', '690793f81a40a', 'verified', '2025-11-02 17:25:12'),
(3, '239306', 'Ajay', '69146469ad788', 'verified', '2025-11-12 10:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `whats_app` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `education` varchar(255) DEFAULT NULL,
  `village` varchar(255) NOT NULL,
  `taluka` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `intrest` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `dt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `description` varchar(9999) NOT NULL,
  `village` varchar(255) DEFAULT NULL,
  `taluka` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `by` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_datetime` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `mobile_no`, `description`, `village`, `taluka`, `district`, `by`, `date`, `modified_datetime`) VALUES
(226, 'Ajay Rathod', '9607170795', '', 'Dharashiv', 'dharashiv', 'test', '', '2025-11-02 16:48:19', '2025-11-03 05:18:19.000000'),
(227, '', 'dfgf', '', NULL, NULL, NULL, '', '2025-11-02 17:07:32', '2025-11-03 05:37:32.000000'),
(228, '', '', '', NULL, NULL, NULL, '', '2025-11-12 10:42:09', '2025-11-12 23:12:09.000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin1`
--
ALTER TABLE `admin1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `care`
--
ALTER TABLE `care`
  ADD PRIMARY KEY (`1`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_care_log`
--
ALTER TABLE `customer_care_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dropdown`
--
ALTER TABLE `dropdown`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_status_history`
--
ALTER TABLE `loan_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_id` (`complaint_id`);

--
-- Indexes for table `ods`
--
ALTER TABLE `ods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin1`
--
ALTER TABLE `admin1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `care`
--
ALTER TABLE `care`
  MODIFY `1` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `customer_care_log`
--
ALTER TABLE `customer_care_log`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dropdown`
--
ALTER TABLE `dropdown`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loan_status_history`
--
ALTER TABLE `loan_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ods`
--
ALTER TABLE `ods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
