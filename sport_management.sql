-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2025 at 02:13 PM
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
-- Database: `sport_manag_test`
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
(1, 11, 'My leg day', '2025-11-19', 'Faculty of Engineering'),
(2, 13, 'Homerun', '2025-11-13', 'Slug');

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
(5, 12, 'Githmi', '1234', 17, 1234, '2025-11-07');

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
(6, 13, 'Cricket', '2025-11-14', '06:33:00', '');

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
(10, 'Carrom', '2025-11-06'),
(11, 'Chess', '2025-11-06'),
(12, 'Hockey', '2025-11-06'),
(13, 'Elle', '2025-11-06'),
(14, 'Karate', '2025-11-06'),
(15, 'Road Race', '2025-11-06'),
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
(2, 17, 12, '2025-11-07');

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
(3, 11, 'Luthira', '5678', 15, '5678', '2025-11-07'),
(4, 13, 'Malith', '0987', 17, '0987', '2025-11-09');

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
(2, 13, 'Cricket', 'Mr. Silva', '2025-11-12 07:42:00', 'University Ground');

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
(3, 13, 20, 10, '2025-11-12 14:00:51');

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
(11, 'Luthira', 'luthi@gmail.com', '$2y$10$YnyGJnSHEH6/dXTK2/qHJ.FXjMgCnLhfZ7HTSzPvm431zrXFKRKWq', 'student', '5678', 15, '5678', NULL, '2025-11-07 12:59:06'),
(12, 'Githmi', 'githmi@gmail.com', '$2y$10$rrPdXFLCTjXKqwxE7cHmnu0Z6b.U.fyy3DJMOIhjIM/Vi1nEmLwHi', 'coach', '1234', 17, NULL, '1234', '2025-11-07 13:07:18'),
(13, 'Malith', 'malith@gmail.com', '$2y$10$W0CTtqVwG8EQ.Cfkk1xVm.B78I2DiK7zQS5QVwp31Eh0w/PXU.vKa', 'student', '0987', 17, '987', NULL, '2025-11-09 07:42:40');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coach`
--
ALTER TABLE `coach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sports`
--
ALTER TABLE `sports`
  MODIFY `sport_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `sport_coach`
--
ALTER TABLE `sport_coach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_sport`
--
ALTER TABLE `student_sport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_sport_registration`
--
ALTER TABLE `student_sport_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
