-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 05:33 AM
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
-- Database: `sport_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `date` date NOT NULL,
  `event_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`id`, `user_id`, `title`, `date`, `event_name`) VALUES
(2, 13, 'Homerun', '2025-11-13', 'Slug'),
(3, 20, 'First place', '2025-11-11', 'Slug');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `facility_type` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `duration` int(11) NOT NULL,
  `facility_details` text DEFAULT NULL,
  `status` enum('Pending','Confirmed','Cancelled','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `facility_type`, `name`, `email`, `phone`, `booking_date`, `booking_time`, `duration`, `facility_details`, `status`, `created_at`, `updated_at`) VALUES
(1, 13, 'Ground Booking', 'Malith', 'malith@gmail.com', '0771234567', '2025-11-20', '10:00:00', 2, '{\"sports_type\":\"Cricket\",\"num_players\":\"22\",\"special_requirements\":\"Tournament preparation\"}', 'Confirmed', '2025-11-17 05:00:00', '2025-11-18 03:50:13'),
(2, 13, 'Gym Booking', 'Malith', 'malith@gmail.com', '0771234567', '2025-11-18', '18:00:00', 2, '{\"session_type\":\"General Workout\",\"num_people\":\"1\",\"preferred_area\":\"Weight Training\",\"equipment_preference\":\"\"}', 'Cancelled', '2025-11-17 05:30:00', '2025-11-18 04:05:46'),
(3, 16, 'Indoor Booking', 'Avishka', 'avishka@gmail.com', '0779876543', '2025-11-19', '16:00:00', 1, '{\"sport_type\":\"Badminton\",\"court_selection\":\"Court 1\",\"num_players\":\"2\",\"court_type\":\"Singles\",\"equipment_rental\":\"2 rackets\",\"booking_purpose\":\"Casual play\"}', 'Pending', '2025-11-17 06:30:00', '2025-11-18 03:50:13'),
(4, NULL, 'Ground Booking', 'Luthira', 'luthira@gmail.com', '76767667', '2025-11-12', '10:00:00', 1, '{\"sports_type\":\"Volleyball\",\"num_players\":\"34\",\"special_requirements\":\"Items sports\"}', 'Cancelled', '2025-11-18 03:52:42', '2025-11-18 03:53:02'),
(5, NULL, 'Gym Booking', 'Latha', 'latha@gmail.com', '45674', '2025-11-20', '00:40:00', 2, '{\"session_type\":\"Group Class\",\"num_people\":\"9\",\"preferred_area\":\"Weight Training\",\"equipment_preference\":\"Not all\"}', 'Confirmed', '2025-11-18 04:05:35', '2025-11-18 04:05:35'),
(6, NULL, 'Indoor Booking', 'Luthira', 'lu@gmail.com', '01125364', '2025-11-19', '00:00:00', 2, '{\"sport_type\":\"Basketball\",\"court_selection\":\"Court 2\",\"num_players\":\"55\",\"court_type\":\"Doubles\",\"equipment_rental\":\"Balls\",\"booking_purpose\":\"Casual play\"}', 'Confirmed', '2025-11-18 04:16:39', '2025-11-18 04:16:39');

-- --------------------------------------------------------

--
-- Table structure for table `coach`
--

CREATE TABLE `coach` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `sport_id` int(11) DEFAULT NULL,
  `coach_id` int(11) DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coach`
--

INSERT INTO `coach` (`id`, `user_id`, `name`, `nic`, `sport_id`, `coach_id`, `created_at`) VALUES
(4, 10, 'Sanka', '4321', 20, 4321, '2025-11-06'),
(5, 12, 'Githmi', '1234', 17, 1234, '2025-11-07'),
(6, 19, 'Thidushan', '1234d', 7, 1234, '2025-11-17');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `schedule_time` time DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `user_id`, `title`, `schedule_date`, `schedule_time`, `description`) VALUES
(1, 12, 'Tomorrow', '2025-11-13', '19:09:00', 'Need to go to practice'),
(3, 12, 'My daily routing', '2025-11-12', '07:16:00', 'I need to practice'),
(5, 13, 'Football', '2025-11-13', '10:00:00', 'lfgldf'),
(6, 13, 'Cricket', '2025-11-14', '06:33:00', ''),
(7, 19, 'Swimming session', '2025-11-19', '10:00:00', 'I have to be fast');

-- --------------------------------------------------------

--
-- Table structure for table `sports`
--

CREATE TABLE `sports` (
  `sport_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sports`
--

INSERT INTO `sports` (`sport_id`, `name`, `created_at`) VALUES
(1, 'Cricket', '2025-11-06'),
(2, 'Baseball', '2025-11-06'),
(3, 'Netball', '2025-11-06'),
(4, 'Basketball', '2025-11-06'),
(5, 'Football', '2025-11-06'),
(6, 'Tennis', '2025-11-06'),
(7, 'Swimming', '2025-11-06'),
(8, 'Table Tennis', '2025-11-06'),
(9, 'Athletics', '2025-11-06'),
(12, 'Hockey', '2025-11-06'),
(13, 'Elle', '2025-11-06'),
(14, 'Karate', '2025-11-06'),
(16, 'Rugby', '2025-11-06'),
(17, 'Volleyball', '2025-11-06'),
(18, 'Weight Lifting', '2025-11-06'),
(19, 'Wrestling', '2025-11-06'),
(20, 'Badminton', '2025-11-06');

-- --------------------------------------------------------

--
-- Table structure for table `sport_coach`
--

CREATE TABLE `sport_coach` (
  `id` int(11) NOT NULL,
  `sport_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sport_coach`
--

INSERT INTO `sport_coach` (`id`, `sport_id`, `coach_id`, `date`) VALUES
(1, 20, 10, '2025-11-06'),
(2, 17, 12, '2025-11-07'),
(3, 7, 19, '2025-11-17');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `sport_id` int(11) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `user_id`, `name`, `nic`, `sport_id`, `student_id`, `created_at`) VALUES
(4, 13, 'Malith', '0987', 17, '0987', '2025-11-09'),
(5, 16, 'Avishka', '12345', 14, '12345', '2025-11-12'),
(7, 18, 'Shehan', '21345', 14, '12345', '2025-11-16'),
(8, 20, 'Rodrigo', '654g', 7, '654', '2025-11-17');

-- --------------------------------------------------------

--
-- Table structure for table `student_sport`
--

CREATE TABLE `student_sport` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `coach_name` varchar(100) NOT NULL,
  `date_time` datetime NOT NULL,
  `location` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_sport`
--

INSERT INTO `student_sport` (`id`, `user_id`, `title`, `coach_name`, `date_time`, `location`) VALUES
(2, 13, 'Cricket', 'Mr. Silva', '2025-11-12 07:42:00', 'University Ground'),
(4, 20, 'Swimming ', 'THidushan', '2025-11-11 00:00:00', 'University Pool');

-- --------------------------------------------------------

--
-- Table structure for table `student_sport_registration`
--

CREATE TABLE `student_sport_registration` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sport_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `registered_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_sport_registration`
--

INSERT INTO `student_sport_registration` (`id`, `user_id`, `sport_id`, `coach_id`, `registered_at`) VALUES
(3, 13, 20, 10, '2025-11-12 14:00:51'),
(4, 20, 17, 12, '2025-11-17 21:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','coach') NOT NULL,
  `nic` varchar(20) NOT NULL,
  `sport_id` int(11) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `coach_id` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `nic`, `sport_id`, `student_id`, `coach_id`, `created_at`) VALUES
(10, 'Sanka', 'sanka@gmail.com', '$2y$10$X.yplUFYhN.70clYmve6ceWewc5MSbUCYEeWdbgAzZPJjk1YcIj1C', 'coach', '4321', 20, NULL, '4321', '2025-11-06 18:03:57'),
(12, 'Githmi', 'githmi@gmail.com', '$2y$10$rrPdXFLCTjXKqwxE7cHmnu0Z6b.U.fyy3DJMOIhjIM/Vi1nEmLwHi', 'coach', '1234', 17, NULL, '1234', '2025-11-07 13:07:18'),
(13, 'Malith', 'malith@gmail.com', '$2y$10$W0CTtqVwG8EQ.Cfkk1xVm.B78I2DiK7zQS5QVwp31Eh0w/PXU.vKa', 'student', '0987', 17, '987', NULL, '2025-11-09 07:42:40'),
(16, 'Avishka', 'avishka@gmail.com', '$2y$10$1nOUZcxVwlOLsK2izRcNSeFIyrFGv.fV3gEEsndwhQaMAKpluP.N2', 'student', '12345', 14, '12345', NULL, '2025-11-12 13:53:42'),
(18, 'Shehan', 'shehan@gmail.com', '$2y$10$YVBJ23h5IKa47Q3hbYq14O04cyquz8/2sHn0lVhHgHxM26GdZvJVK', 'student', '21345', 14, '12345', NULL, '2025-11-16 16:11:02'),
(19, 'Thidushan', 'thidu@gmail.com', '$2y$10$PhQpZpDUmQaB8bt2t4av8e9sKBvB6CVsMo.06GGJLpsr5Cic0/Hcy', 'coach', '1234d', 7, NULL, '1234', '2025-11-17 16:20:35'),
(20, 'Rodrigo', 'rod@gmail.com', '$2y$10$5GY4FoPb9owpiAahAcXf4ek2CgISfz3fxJa0LAHXKZAp3gycDiHqy', 'student', '654g', 7, '654', NULL, '2025-11-17 16:24:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_booking_user` (`user_id`),
  ADD KEY `idx_booking_date` (`booking_date`),
  ADD KEY `idx_facility_type` (`facility_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_booking_datetime` (`booking_date`,`booking_time`);

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user2_id` (`user_id`),
  ADD KEY `fk_sport5_id` (`sport_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sports`
--
ALTER TABLE `sports`
  ADD PRIMARY KEY (`sport_id`);

--
-- Indexes for table `sport_coach`
--
ALTER TABLE `sport_coach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sport2_id` (`sport_id`),
  ADD KEY `fk_coach2_id` (`coach_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users1_id` (`user_id`),
  ADD KEY `fk_sport4_id` (`sport_id`);

--
-- Indexes for table `student_sport`
--
ALTER TABLE `student_sport`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sport_student` (`user_id`);

--
-- Indexes for table `student_sport_registration`
--
ALTER TABLE `student_sport_registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_sport_id` (`sport_id`),
  ADD KEY `fk_ssr_coach` (`coach_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sport3_id` (`sport_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coach`
--
ALTER TABLE `coach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sports`
--
ALTER TABLE `sports`
  MODIFY `sport_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `sport_coach`
--
ALTER TABLE `sport_coach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student_sport`
--
ALTER TABLE `student_sport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_sport_registration`
--
ALTER TABLE `student_sport_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `coach`
--
ALTER TABLE `coach`
  ADD CONSTRAINT `fk_sport5_id` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`sport_id`),
  ADD CONSTRAINT `fk_user2_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sport_coach`
--
ALTER TABLE `sport_coach`
  ADD CONSTRAINT `fk_coach2_id` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sport2_id` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`sport_id`) ON DELETE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_sport4_id` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`sport_id`),
  ADD CONSTRAINT `fk_users1_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `student_sport`
--
ALTER TABLE `student_sport`
  ADD CONSTRAINT `fk_sport_student` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_sport_registration`
--
ALTER TABLE `student_sport_registration`
  ADD CONSTRAINT `fk_ssr_coach` FOREIGN KEY (`coach_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ssr_sport` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`sport_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ssr_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_sport3_id` FOREIGN KEY (`sport_id`) REFERENCES `sports` (`sport_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
