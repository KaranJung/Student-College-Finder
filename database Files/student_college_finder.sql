-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2024 at 01:04 PM
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
-- Database: `student_college_finder`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'alpha1', '$2y$10$KsH4K8G0W9.NLTpckzTyHewue1lNMt9UvsMoVV713Dlquxs1XnF3S', '2024-12-27 10:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `user_id`, `file_name`, `file_type`, `uploaded_at`) VALUES
(5, 3, 'cert_676abaf660e624.09928594.jpg', 'seeCertificate', '2024-12-24 13:45:26'),
(6, 3, 'cert_676abaf66197d1.67140734.jpg', 'plus12Certificate', '2024-12-24 13:45:26'),
(7, 4, 'cert_676ad0cbc3f275.58043178.jpg', 'seeCertificate', '2024-12-24 15:18:35'),
(8, 4, 'cert_676ad0cbc4f5c8.59979360.jpg', 'plus12Certificate', '2024-12-24 15:18:35'),
(37, 21, 'cert_676fac81c1abd8.94425227.jpg', 'seeCertificate', '2024-12-28 07:45:05'),
(38, 21, 'cert_676fac81c265d2.82543857.jpg', 'plus12Certificate', '2024-12-28 07:45:05'),
(39, 22, 'cert_676fc909422ee6.62156405.jpg', 'seeCertificate', '2024-12-28 09:46:49'),
(40, 22, 'cert_676fc9094309e2.76762875.jpg', 'plus12Certificate', '2024-12-28 09:46:49'),
(41, 23, 'cert_676fc9a8319314.49050335.jpg', 'seeCertificate', '2024-12-28 09:49:28'),
(42, 23, 'cert_676fc9a832cda9.60402475.jpg', 'plus12Certificate', '2024-12-28 09:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `mother_name` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `slc_passout_year` year(4) NOT NULL,
  `plus2_passout_year` year(4) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `father_name`, `mother_name`, `phone_number`, `slc_passout_year`, `plus2_passout_year`, `email`, `password`, `created_at`) VALUES
(3, 'Karan Jung Budhathoki', 'Shambhu Bahadur Budhathoki', 'Rakhi Budhathoki', '9842388429', '2012', '2016', 'underside001@gmail.com', '$2y$10$VB1LNceeiAZ7b2g/q9GFzubF63dhsG13jK2ArE7SaxuPWRlzlomKC', '2024-12-24 13:45:26'),
(4, 'Barsha Kumari Sapkota', 'Anil Sapkota', 'Babita Sapkota', '9865164967', '2016', '2018', 'barshasapkota83@gmail.com', '$2y$10$Zne1XIuCKZQg0Xd9qoOYAubC11z4UNd9KbaAC9nMTo8r0PM.gdmqm', '2024-12-24 15:18:35'),
(21, 'Mansing Lama', 'Sanu Lama', 'Malati Lama', '9808631206', '2070', '2073', 'greentara891@gmail.com', '$2y$10$WgtAFyfJcFf8Ve1qWdUKnuQYFS08jzvn.pH3.0vge67ph3TGpE5PG', '2024-12-28 07:45:05'),
(22, 'Krishna Yadav', 'Hari yadev', 'Devi yadav', '9855044216', '2012', '2016', 'krishna@gmail.com', '$2y$10$sRVmDSaaP.BoO2frnfqn7Ozy4qcNo1qXAZJ6qyi2IHPckF7vFd1la', '2024-12-28 09:46:49'),
(23, 'Ram dev', 'hari dev', 'jason dev', '0987654321', '2017', '2022', 'ram@gmail.com', '$2y$10$t38sHMtb.jDe3WTidGuXIuqJSrkSbcTmLk/CGJUakIs7bww3TC2T6', '2024-12-28 09:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_type` varchar(100) NOT NULL,
  `activity_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id`, `activity_type`, `activity_time`) VALUES
(1, 3, 'admin_login', '2024-12-27 12:18:49'),
(2, 3, 'admin_login', '2024-12-27 12:20:15'),
(3, 11, 'admin_login', '2024-12-27 12:36:23'),
(4, 3, 'admin_login', '2024-12-27 12:48:01'),
(5, 3, 'admin_login', '2024-12-27 13:04:00'),
(6, 3, 'admin_login', '2024-12-27 13:47:58'),
(7, 3, 'admin_login', '2024-12-27 13:50:26'),
(8, 3, 'admin_login', '2024-12-27 13:52:48'),
(9, 3, 'admin_login', '2024-12-27 13:54:19'),
(10, 3, 'admin_login', '2024-12-27 14:00:30'),
(11, 3, 'admin_login', '2024-12-27 14:13:46'),
(12, 3, 'admin_login', '2024-12-27 14:36:18'),
(13, 3, 'admin_login', '2024-12-27 15:33:26'),
(14, 3, 'admin_login', '2024-12-27 16:00:07'),
(15, 3, 'admin_login', '2024-12-27 16:32:47'),
(16, 3, 'admin_login', '2024-12-27 17:12:22'),
(17, 3, 'admin_login', '2024-12-27 17:22:06'),
(18, 18, 'admin_login', '2024-12-27 17:25:37'),
(19, 3, 'admin_login', '2024-12-27 17:26:38'),
(20, 3, 'admin_login', '2024-12-27 17:40:39'),
(21, 20, 'admin_login', '2024-12-27 17:53:20'),
(22, 21, 'admin_login', '2024-12-28 08:45:21'),
(23, 3, 'admin_login', '2024-12-28 08:45:49'),
(24, 3, 'admin_login', '2024-12-28 08:55:32'),
(25, 3, 'admin_login', '2024-12-28 08:56:24'),
(26, 3, 'admin_login', '2024-12-28 08:58:23'),
(27, 21, 'admin_login', '2024-12-28 09:02:26'),
(28, 21, 'admin_login', '2024-12-28 09:04:08'),
(29, 3, 'admin_login', '2024-12-28 10:01:25'),
(30, 3, 'admin_login', '2024-12-28 10:04:37'),
(31, 3, 'admin_login', '2024-12-28 10:25:22'),
(32, 3, 'admin_login', '2024-12-28 10:32:37'),
(33, 22, 'admin_login', '2024-12-28 10:47:04'),
(34, 23, 'admin_login', '2024-12-28 10:49:37'),
(35, 3, 'admin_login', '2024-12-28 10:57:58'),
(36, 21, 'admin_login', '2024-12-28 11:06:58'),
(37, 3, 'admin_login', '2024-12-28 11:08:50'),
(38, 21, 'admin_login', '2024-12-28 11:22:40'),
(39, 21, 'admin_login', '2024-12-28 11:27:23'),
(40, 3, 'admin_login', '2024-12-28 12:47:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
