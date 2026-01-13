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
-- Table structure for table `wp_wp_jobs`
--

CREATE TABLE `wp_wp_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `jobPostingID` bigint DEFAULT NULL,
  `jobTitle` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `jobDescription` longtext COLLATE utf8mb4_unicode_520_ci,
  `requiredSkills` text COLLATE utf8mb4_unicode_520_ci,
  `minQualification` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `jobLocation` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `minExperience` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `jobDuration` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `jobIndustry` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `monthlySalary` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `wp_wp_jobs`
--

INSERT INTO `wp_wp_jobs` (`id`, `jobPostingID`, `jobTitle`, `jobDescription`, `requiredSkills`, `minQualification`, `jobLocation`, `department`, `designation`, `minExperience`, `jobDuration`, `jobIndustry`, `monthlySalary`, `updated_at`) VALUES
(1, 2, 'Sr. Developer', 'Design, develop, and maintain scalable, secure, and high-performance applications\r\nLead architecture decisions and ensure adherence to best coding practices and standards\r\nReview code and mentor junior and mid-level developers through guidance and feedback\r\nTranslate business requirements into technical solutions and implementation plans\r\nOwn end-to-end development lifecycle: analysis, development, testing, deployment, and support\r\nIdentify performance bottlenecks and optimize applications for speed and scalability\r\nEnsure application security, data protection, and compliance with industry standards\r\nCollaborate closely with product managers, designers, QA, and stakeholders\r\nTroubleshoot complex technical issues and provide long-term solutions\r\nDrive technical innovation and recommend improvements in tools, frameworks, and processes\r\nWrite and maintain technical documentation and system design specifications', 'Strong proficiency in one or more programming languages\r\nDeep understanding of software architecture, design patterns, and best practices\r\nExperience with modern frameworks and libraries\r\nStrong knowledge of databases (MySQL, PostgreSQL) and query optimization\r\nExperience with REST APIs, AJAX, and third-party API integrations\r\nSolid understanding of frontend technologies (HTML, CSS, JavaScript)\r\nKnowledge of version control systems (Git) and collaborative workflows\r\nExperience with performance optimization, caching, and scalability techniques\r\nStrong understanding of application security, authentication, and authorization\r\nFamiliarity with CI/CD pipelines and deployment processes\r\nExperience working with Agile/Scrum methodologies\r\nAbility to review code, mentor team members, and enforce coding standards\r\nStrong problem-solving, debugging, and analytical skills\r\nExcellent communication and documentation skills', 'BCA, MCA, M.sc It', 'Ahmedabad', 'HR', 'HR Admin', '5+ Years', '3 Years', 'IT', '35000 to 45000', '2026-01-13 06:58:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wp_wp_jobs`
--
ALTER TABLE `wp_wp_jobs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wp_wp_jobs`
--
ALTER TABLE `wp_wp_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
