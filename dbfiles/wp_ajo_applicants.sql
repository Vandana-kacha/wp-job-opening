-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 13, 2026 at 07:11 AM
-- Server version: 8.0.44-0ubuntu0.22.04.2
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wp_lifecare`
--

-- --------------------------------------------------------

--
-- Table structure for table `wp_ajo_applicants`
--

CREATE TABLE `wp_ajo_applicants` (
  `id` mediumint NOT NULL,
  `job_id` int NOT NULL,
  `job_post` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `candidate_type` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `age` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `job_location` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pincode` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `gender` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `marital_status` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `qualification` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `job_preference` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `current_designation` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `years_of_experience` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `current_ctc` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment` varchar(100) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `cv` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `submitted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `wp_ajo_applicants`
--

INSERT INTO `wp_ajo_applicants` (`id`, `job_id`, `job_post`, `candidate_type`, `full_name`, `email`, `phone`, `age`, `job_location`, `pincode`, `gender`, `marital_status`, `qualification`, `job_preference`, `current_designation`, `years_of_experience`, `current_ctc`, `comment`, `cv`, `submitted_at`) VALUES
(1, 1, 'Sr. Developer', 'experience', 'Vk', 'test@test.com', '6655778899', '26', 'Ahmedabad', '36000', 'Female', 'Single', 'MCA', 'IT', 'Jr. Developer', '5', '400000', '', '', '2026-01-13 12:22:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_ajo_applicants`
--
ALTER TABLE `wp_ajo_applicants`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_ajo_applicants`
--
ALTER TABLE `wp_ajo_applicants`
  MODIFY `id` mediumint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
