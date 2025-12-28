-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Dec 28, 2025 at 09:11 AM
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
-- Database: `dormdash_final_v1`
--

-- --------------------------------------------------------

--
-- Table structure for table `bh_images`
--

CREATE TABLE `bh_images` (
  `id` int(11) NOT NULL,
  `bh_id` int(11) NOT NULL,
  `image_path` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bh_images`
--

INSERT INTO `bh_images` (`id`, `bh_id`, `image_path`) VALUES
(1, 10, 'bh_10_2eccc9cc.jpg'),
(2, 11, 'bh_11_0274b89a.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `bh_listing`
--

CREATE TABLE `bh_listing` (
  `bh_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ownername` varchar(200) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `bh_description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `monthly_rent` decimal(10,2) DEFAULT NULL,
  `bh_address` varchar(255) DEFAULT NULL,
  `available_rooms` int(11) DEFAULT NULL,
  `roomtype` enum('Single Room','Studio Room','Shared Room') DEFAULT NULL,
  `amenities` varchar(255) DEFAULT NULL,
  `preferred_gender` enum('Male only','Female only','Mixed') DEFAULT NULL,
  `curfew_policy` enum('No Curfew','With Curfew') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `room_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bh_listing`
--

INSERT INTO `bh_listing` (`bh_id`, `user_id`, `ownername`, `contact`, `title`, `bh_description`, `image_path`, `monthly_rent`, `bh_address`, `available_rooms`, `roomtype`, `amenities`, `preferred_gender`, `curfew_policy`, `created_at`, `room_type`) VALUES
(9, 1, 'masloc', '09757553244', 'masloc boarding house', 'mahumot', NULL, 5500.00, 'UEP Zone 3', 2, 'Single Room', 'WiFi,Laundry Area,Parking Space,Study Area', 'Female only', 'No Curfew', '2025-12-27 13:31:31', NULL),
(10, 7, NULL, '09977756757', 'mara', 'okay po', NULL, 5000.00, 'catarman gaisano', 2, NULL, NULL, 'Female only', 'No Curfew', '2025-12-28 05:37:39', 'Single Room'),
(11, 9, NULL, '09786756343', 'Faj Boarding House', 'masangsang', NULL, 1000.00, 'UEP Zone 2 dalakit dapit', 3, NULL, NULL, 'Mixed', 'With Curfew', '2025-12-28 07:37:55', 'Single Room');

-- --------------------------------------------------------

--
-- Table structure for table `bh_reservations`
--

CREATE TABLE `bh_reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bh_id` int(11) NOT NULL,
  `status` enum('Pending','Approved','Declined') DEFAULT 'Pending',
  `decline_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bh_reservations`
--

INSERT INTO `bh_reservations` (`reservation_id`, `user_id`, `bh_id`, `status`, `decline_reason`, `created_at`, `updated_at`) VALUES
(7, 7, 10, 'Approved', NULL, '2025-12-28 05:51:12', '2025-12-28 05:51:27'),
(8, 7, 10, 'Declined', NULL, '2025-12-28 05:58:53', '2025-12-28 07:01:12'),
(10, 4, 10, 'Approved', NULL, '2025-12-28 06:20:00', '2025-12-28 06:40:19'),
(12, 4, 11, 'Pending', NULL, '2025-12-28 08:07:16', '2025-12-28 08:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `fdbk_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bh_id` int(11) NOT NULL,
  `comments` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner_details`
--

CREATE TABLE `owner_details` (
  `owner_detail_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `home_address` text DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT 'default_avatar.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner_details`
--

INSERT INTO `owner_details` (`owner_detail_id`, `user_id`, `full_name`, `contact_number`, `business_name`, `home_address`, `profile_img`) VALUES
(1, 7, 'owner2', '097834793', '', 'catarman', 'default_avatar.jpg'),
(3, 8, 'owner3', NULL, NULL, NULL, 'default_avatar.jpg'),
(4, 9, 'anilita masloc', '0979034790', 'ambit', 'palapag', 'owner_9_1766907006.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `renter_details`
--

CREATE TABLE `renter_details` (
  `renter_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `renterName` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `meetingtype` enum('Virtual Meeting','F2F Meeting') DEFAULT NULL,
  `meetingplace` varchar(200) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `renter_details`
--

INSERT INTO `renter_details` (`renter_id`, `user_id`, `renterName`, `contact`, `address`, `gender`, `date_time`, `meetingtype`, `meetingplace`, `profile_img`) VALUES
(26, 6, 'renter2', '097878683489', 'Catarman', NULL, '2025-12-28 04:37:49', NULL, NULL, NULL),
(32, 4, 'jerah', '098957847', 'rfrrg', NULL, '2025-12-28 07:09:05', NULL, NULL, 'renter_4_1766905745.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','owner','renter') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'owner@dormdash.com', '123', 'owner', '0000-00-00 00:00:00', '2025-12-27 13:30:15'),
(4, 'renter1@dormdash.com', '$2y$10$Y//LaDm.hG2Krk6RaVzYD.PWHzQmLvmelhus0muBsWoVL5b/783/.', 'renter', '2025-12-27 13:04:54', '2025-12-27 13:04:54'),
(5, 'owner1@dormdash.com', '$2y$10$AinKnF4jaLgpepcbunpaMepwXLGj54dGt84gztFMjTvHvjnnxllsK', 'owner', '2025-12-27 16:28:54', '2025-12-27 16:28:54'),
(6, 'renter2@dormdash.com', '$2y$10$WaEk.O82nC6PMsx4h5Cr7O3UTZrxO1gjzD3dCe/DYe0aJWsUgx44m', 'renter', '2025-12-27 18:05:40', '2025-12-27 18:05:40'),
(7, 'owner2@dormdash.com', '$2y$10$oFCOCMo1V8N7x9bQQz7ENugJ4iBG4MpcsL1Cvp0sYmRNVEnQpC4y2', 'owner', '2025-12-28 04:53:43', '2025-12-28 04:53:43'),
(8, 'owner3@dormdash.com', '$2y$10$V4KBnaKLA/CUyy0O/MoH1OIzLmChenIS0Z6bA4.IWpDzIjU.5yhhS', 'owner', '2025-12-28 07:19:33', '2025-12-28 07:19:33'),
(9, 'masloc@dormdash.com', '$2y$10$HbGCfw7xPvRBFMCEA2aRFewjJ3TI4b7.kpsSzRsK4bFNKUcoYVx86', 'owner', '2025-12-28 07:29:06', '2025-12-28 07:29:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bh_images`
--
ALTER TABLE `bh_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bh_id` (`bh_id`);

--
-- Indexes for table `bh_listing`
--
ALTER TABLE `bh_listing`
  ADD PRIMARY KEY (`bh_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bh_reservations`
--
ALTER TABLE `bh_reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bh_id` (`bh_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fdbk_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bh_id` (`bh_id`);

--
-- Indexes for table `owner_details`
--
ALTER TABLE `owner_details`
  ADD PRIMARY KEY (`owner_detail_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `renter_details`
--
ALTER TABLE `renter_details`
  ADD PRIMARY KEY (`renter_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bh_images`
--
ALTER TABLE `bh_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bh_listing`
--
ALTER TABLE `bh_listing`
  MODIFY `bh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bh_reservations`
--
ALTER TABLE `bh_reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `fdbk_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owner_details`
--
ALTER TABLE `owner_details`
  MODIFY `owner_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `renter_details`
--
ALTER TABLE `renter_details`
  MODIFY `renter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bh_images`
--
ALTER TABLE `bh_images`
  ADD CONSTRAINT `bh_id` FOREIGN KEY (`bh_id`) REFERENCES `bh_listing` (`bh_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `bh_listing`
--
ALTER TABLE `bh_listing`
  ADD CONSTRAINT `bh_listing_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `bh_reservations`
--
ALTER TABLE `bh_reservations`
  ADD CONSTRAINT `bh_reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bh_reservations_ibfk_2` FOREIGN KEY (`bh_id`) REFERENCES `bh_listing` (`bh_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`bh_id`) REFERENCES `bh_listing` (`bh_id`) ON DELETE CASCADE;

--
-- Constraints for table `owner_details`
--
ALTER TABLE `owner_details`
  ADD CONSTRAINT `owner_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `renter_details`
--
ALTER TABLE `renter_details`
  ADD CONSTRAINT `renter_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
