-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2025 at 02:57 PM
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
  `bh_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bh_images`
--

INSERT INTO `bh_images` (`id`, `bh_id`, `image_path`) VALUES
(1, 3, 'uploads/img_693eafcfb6bda8.67579324.jpg');

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
  `monthly_rent` decimal(10,2) DEFAULT NULL,
  `bh_address` varchar(255) DEFAULT NULL,
  `available_rooms` int(11) DEFAULT NULL,
  `roomtype` enum('Single Room','Studio Room','Shared Room') DEFAULT NULL,
  `amenities` set('WiFi','Kitchen Area','Laundry Area','Parking Space','Study Area') DEFAULT NULL,
  `preferred_gender` enum('Male only','Female only','Mixed') DEFAULT NULL,
  `curfew_policy` enum('No Curfew','With Curfew') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bh_listing`
--

INSERT INTO `bh_listing` (`bh_id`, `user_id`, `ownername`, `contact`, `title`, `bh_description`, `monthly_rent`, `bh_address`, `available_rooms`, `roomtype`, `amenities`, `preferred_gender`, `curfew_policy`, `created_at`, `views`) VALUES
(1, 1, 'bj dwight ano', '9876534567', 'FAJ Boarding House', 'dre kona gin papanginano', 1000.00, 'UEP zone 2, sea side harani sa Acacia', 7, 'Shared Room', 'WiFi', 'Mixed', 'With Curfew', '2025-12-14 11:55:26', 27),
(2, 1, 'bj dwight ano', '9876534567', 'FAJ Boarding House', 'dre kona gin papanginano', 1000.00, 'UEP zone 2, sea side harani sa Acacia', 7, 'Shared Room', 'WiFi', 'Mixed', 'With Curfew', '2025-12-14 12:21:32', 0),
(3, 1, 'severo', '45678923', 'kn trexie bhouse', 'nadire naak sine na bhouse', 1000.00, 'bisan la diin', 2, '', 'WiFi', 'Female only', 'With Curfew', '2025-12-14 12:38:39', 0);

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
-- Table structure for table `renter_details`
--

CREATE TABLE `renter_details` (
  `renter_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `renterName` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `gender` varchar(15) DEFAULT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `meetingtype` enum('Virtual Meeting','F2F Meeting') DEFAULT NULL,
  `meetingplace` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','owner','renter') NOT NULL DEFAULT 'renter',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@dormdash.com', '$2y$10$uQvtR4VXjBpGJjbQdCqg0.sZL7eB6z1KQh2PHa4Vr7tA2G7WYywhu', 'admin', '2025-12-12 08:01:09'),
(3, 'Severo', 'severo@anglakimona', '$2y$10$J9I8LB49iwIP5upvFdfK2.YHIts.uQwH/BQdz34be0hxFwvcZ9Zye', 'renter', '2025-12-12 08:11:29'),
(4, 'bj dwight ano', 'Bjihinmoako@ikaymagingakin.com', '$2y$10$gfqJt0F5f6cdlqBOFFjdweaQVe23uq6JABT.EPYyGwiiP/IJyezOa', 'owner', '2025-12-12 08:18:10');

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
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fdbk_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `bh_id` (`bh_id`);

--
-- Indexes for table `renter_details`
--
ALTER TABLE `renter_details`
  ADD PRIMARY KEY (`renter_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bh_images`
--
ALTER TABLE `bh_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bh_listing`
--
ALTER TABLE `bh_listing`
  MODIFY `bh_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `renter_details`
--
ALTER TABLE `renter_details`
  MODIFY `renter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bh_images`
--
ALTER TABLE `bh_images`
  ADD CONSTRAINT `bh_images_ibfk_1` FOREIGN KEY (`bh_id`) REFERENCES `bh_listing` (`bh_id`) ON DELETE CASCADE;

--
-- Constraints for table `bh_listing`
--
ALTER TABLE `bh_listing`
  ADD CONSTRAINT `bh_listing_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`bh_id`) REFERENCES `bh_listing` (`bh_id`) ON DELETE CASCADE;

--
-- Constraints for table `renter_details`
--
ALTER TABLE `renter_details`
  ADD CONSTRAINT `renter_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
