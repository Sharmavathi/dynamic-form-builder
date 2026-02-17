-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 17, 2026 at 04:09 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dynamic_form_builder`
--

-- --------------------------------------------------------

--
-- Table structure for table `conditional_rules`
--

CREATE TABLE `conditional_rules` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `trigger_field_id` int(11) NOT NULL,
  `operator` varchar(10) NOT NULL,
  `trigger_value` varchar(255) NOT NULL,
  `target_field_id` int(11) NOT NULL,
  `action` varchar(10) DEFAULT 'show',
  `is_global` tinyint(1) DEFAULT 0,
  `version` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conditional_rules`
--

INSERT INTO `conditional_rules` (`id`, `form_id`, `trigger_field_id`, `operator`, `trigger_value`, `target_field_id`, `action`, `is_global`, `version`) VALUES
(29, 1, 24, '=', 'female', 36, 'show', 0, 1),
(46, 1, 24, '=', 'female', 36, 'show', 0, 1),
(47, 1, 25, '>', '5', 35, 'show', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `field_options`
--

CREATE TABLE `field_options` (
  `id` int(11) NOT NULL,
  `field_id` int(11) DEFAULT NULL,
  `option_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `field_options`
--

INSERT INTO `field_options` (`id`, `field_id`, `option_text`) VALUES
(18, 24, 'male'),
(19, 24, 'female');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `version` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `form_name`, `created_at`, `version`) VALUES
(1, 'Job Application Form', '2026-02-11 09:38:25', 1),
(3, 'Complaint Form', '2026-02-11 11:59:46', 1),
(5, 'Job Application', '2026-02-11 14:25:11', 1),
(6, 'Job Application', '2026-02-11 14:27:48', 1),
(14, 'Job Application', '2026-02-17 15:03:59', 2);

-- --------------------------------------------------------

--
-- Table structure for table `form_fields`
--

CREATE TABLE `form_fields` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `required` tinyint(1) DEFAULT 0,
  `placeholder` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_fields`
--

INSERT INTO `form_fields` (`id`, `form_id`, `label`, `type`, `required`, `placeholder`, `sort_order`) VALUES
(2, 5, 'email', 'text', 1, 'Enter your email', 1),
(3, 5, 'Date of Birth', 'date', 1, '', 1),
(5, 6, 'qualification', 'text', 1, '', 1),
(6, 6, 'Address', 'text', 0, '', 1),
(8, 3, 'Full Name', 'textarea', 1, 'Enter your Name', 1),
(9, 6, 'Full Name', 'text', 0, 'Enter your Name', 1),
(12, 6, 'Date of Birth', 'date', 0, '', 1),
(24, 1, 'gender', 'radio', 0, '', 1),
(25, 1, 'experience', 'text', 1, '', 2),
(34, 6, 'email', 'text', 1, '', 1),
(35, 1, 'Leadership Experience', 'text', 0, NULL, 3),
(36, 1, 'Maiden Name', 'text', 0, NULL, 4),
(51, 5, 'Full Name', 'text', 0, 'Enter your Name', 1),
(52, 3, 'Date of Birth', 'number', 1, '', 1),
(53, 3, 'qualication', 'text', 0, '', 1),
(54, 14, 'qualification', 'text', 1, '', 1),
(56, 14, 'Full Name', 'text', 0, 'Enter your Name', 1),
(57, 14, 'Date of Birth', 'date', 0, '', 1),
(58, 14, 'email', 'text', 1, '', 1),
(59, 14, 'adress', 'text', 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `form_responses`
--

CREATE TABLE `form_responses` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `version` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_responses`
--

INSERT INTO `form_responses` (`id`, `form_id`, `user_id`, `submitted_at`, `version`) VALUES
(1, 1, 1, '2026-02-11 10:31:46', NULL),
(2, 3, 1, '2026-02-11 12:21:33', NULL),
(3, 6, 1, '2026-02-16 07:16:22', NULL),
(4, 1, 1, '2026-02-16 07:17:53', NULL),
(5, 1, 1, '2026-02-16 07:21:33', NULL),
(6, 1, 1, '2026-02-16 07:21:38', NULL),
(7, 1, 1, '2026-02-16 07:26:31', NULL),
(8, 5, 1, '2026-02-16 07:38:04', NULL),
(9, 5, 1, '2026-02-16 07:47:57', NULL),
(10, 5, 1, '2026-02-16 07:50:15', NULL),
(11, 5, 1, '2026-02-16 07:55:31', NULL),
(12, 6, 1, '2026-02-16 08:48:08', NULL),
(13, 6, 1, '2026-02-16 08:52:18', NULL),
(25, 6, 1, '2026-02-17 05:50:15', NULL),
(27, 6, 1, '2026-02-17 08:34:38', NULL),
(28, 1, 1, '2026-02-17 09:21:22', NULL),
(29, 1, 1, '2026-02-17 09:54:26', NULL),
(30, 1, 1, '2026-02-17 09:57:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `form_response_values`
--

CREATE TABLE `form_response_values` (
  `id` int(11) NOT NULL,
  `response_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `field_label` varchar(255) DEFAULT NULL,
  `field_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_response_values`
--

INSERT INTO `form_response_values` (`id`, `response_id`, `field_id`, `value`, `field_label`, `field_type`) VALUES
(3, 3, 5, 'mca', NULL, NULL),
(4, 3, 6, 'puducherry', NULL, NULL),
(5, 3, 9, 'sharma', NULL, NULL),
(10, 8, 2, 'sharmavathibalu2002@gmail.com', NULL, NULL),
(11, 8, 3, '2026-02-11', NULL, NULL),
(13, 9, 2, 'sharmavathibalu2002@gmail.com', NULL, NULL),
(14, 9, 3, '2026-02-03', NULL, NULL),
(16, 10, 2, 'sharmavathibalu2002@gmail.com', NULL, NULL),
(17, 10, 3, '2026-02-18', NULL, NULL),
(19, 11, 2, 'sharmavathibalu2002@gmail.com', NULL, NULL),
(20, 11, 3, '2026-02-19', NULL, NULL),
(23, 12, 5, 'mn', NULL, NULL),
(24, 12, 6, 'hjk', NULL, NULL),
(25, 12, 9, 'nk', NULL, NULL),
(26, 12, 12, '2026-02-16', NULL, NULL),
(27, 13, 5, 'nl', NULL, NULL),
(28, 13, 6, 'ff', NULL, NULL),
(29, 13, 9, 'df', NULL, NULL),
(30, 13, 12, '2026-02-27', NULL, NULL),
(57, 25, 5, 'bca', NULL, NULL),
(58, 25, 6, 'puducherry', NULL, NULL),
(59, 25, 9, 'Sharma Balu', NULL, NULL),
(60, 25, 12, '2026-02-17', NULL, NULL),
(62, 25, 34, 'sharmavathibalu2002@gmail.com', NULL, NULL),
(66, 27, 5, 'mca', NULL, NULL),
(67, 27, 6, 'pondy', NULL, NULL),
(68, 27, 9, 'Sharma', NULL, NULL),
(69, 27, 12, '2026-02-24', NULL, NULL),
(71, 27, 34, 'sharmavathibalu2002@gmail.com', NULL, NULL),
(72, 28, 24, 'female', NULL, NULL),
(73, 28, 25, '5', NULL, NULL),
(74, 29, 24, 'female', NULL, NULL),
(75, 29, 25, '6', NULL, NULL),
(76, 29, 35, '', NULL, NULL),
(77, 29, 36, 'sr', NULL, NULL),
(78, 30, 24, 'female', NULL, NULL),
(79, 30, 25, '5', NULL, NULL),
(80, 30, 35, '', NULL, NULL),
(81, 30, 36, '', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conditional_rules`
--
ALTER TABLE `conditional_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cr_form` (`form_id`),
  ADD KEY `fk_cr_trigger_field` (`trigger_field_id`),
  ADD KEY `fk_cr_target_field` (`target_field_id`);

--
-- Indexes for table `field_options`
--
ALTER TABLE `field_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `form_responses`
--
ALTER TABLE `form_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `form_response_values`
--
ALTER TABLE `form_response_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `field_id` (`field_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conditional_rules`
--
ALTER TABLE `conditional_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `field_options`
--
ALTER TABLE `field_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `form_fields`
--
ALTER TABLE `form_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `form_responses`
--
ALTER TABLE `form_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `form_response_values`
--
ALTER TABLE `form_response_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conditional_rules`
--
ALTER TABLE `conditional_rules`
  ADD CONSTRAINT `fk_cr_form` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cr_target_field` FOREIGN KEY (`target_field_id`) REFERENCES `form_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cr_trigger_field` FOREIGN KEY (`trigger_field_id`) REFERENCES `form_fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `field_options`
--
ALTER TABLE `field_options`
  ADD CONSTRAINT `field_options_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `form_fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD CONSTRAINT `form_fields_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `form_responses`
--
ALTER TABLE `form_responses`
  ADD CONSTRAINT `form_responses_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `form_response_values`
--
ALTER TABLE `form_response_values`
  ADD CONSTRAINT `form_response_values_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `form_responses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `form_response_values_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `form_fields` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
