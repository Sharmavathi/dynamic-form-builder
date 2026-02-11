-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 11, 2026 at 05:17 PM
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
(1, 4, 'Male'),
(2, 4, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `form_name`, `created_at`) VALUES
(1, 'Job Application Form', '2026-02-11 09:38:25'),
(2, 'Feedback Form', '2026-02-11 10:40:56'),
(3, 'Complaint Form', '2026-02-11 11:59:46'),
(4, 'Management Form', '2026-02-11 12:17:58'),
(5, 'Job Application', '2026-02-11 14:25:11'),
(6, 'Job Application', '2026-02-11 14:27:48');

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
(1, 3, 'Full Name', 'text', 1, 'Enter your Name', 1),
(2, 5, 'email', 'text', 1, 'Enter your email', 1),
(3, 5, 'Date of Birth', 'date', 1, '', 1),
(4, 6, 'gender', 'radio', 0, '', 1),
(5, 6, 'qualification', 'text', 1, '', 1),
(6, 6, 'Address', 'text', 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `form_responses`
--

CREATE TABLE `form_responses` (
  `id` int(11) NOT NULL,
  `form_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_responses`
--

INSERT INTO `form_responses` (`id`, `form_id`, `user_id`, `submitted_at`) VALUES
(1, 1, 1, '2026-02-11 10:31:46'),
(2, 3, 1, '2026-02-11 12:21:33');

-- --------------------------------------------------------

--
-- Table structure for table `form_response_values`
--

CREATE TABLE `form_response_values` (
  `id` int(11) NOT NULL,
  `response_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_response_values`
--

INSERT INTO `form_response_values` (`id`, `response_id`, `field_id`, `value`) VALUES
(1, 2, 1, 'sharma');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `field_options`
--
ALTER TABLE `field_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `form_fields`
--
ALTER TABLE `form_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `form_responses`
--
ALTER TABLE `form_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `form_response_values`
--
ALTER TABLE `form_response_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

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
