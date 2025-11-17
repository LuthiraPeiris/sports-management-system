-- This table stores all facility booking information

USE sport_management;

-- Create bookings table
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'References users.id - nullable for non-logged-in bookings',
  `facility_type` varchar(50) NOT NULL COMMENT 'Type of facility: Ground Booking, Gym Booking, Indoor Booking',
  `name` varchar(100) NOT NULL COMMENT 'Name of person making the booking',
  `email` varchar(100) NOT NULL COMMENT 'Email address for confirmation',
  `phone` varchar(20) NOT NULL COMMENT 'Contact phone number',
  `booking_date` date NOT NULL COMMENT 'Date of the booking',
  `booking_time` time NOT NULL COMMENT 'Start time of the booking',
  `duration` int(11) NOT NULL COMMENT 'Duration in hours',
  `facility_details` text DEFAULT NULL COMMENT 'JSON field storing facility-specific details',
  `status` enum('Pending','Confirmed','Cancelled','Completed') NOT NULL DEFAULT 'Pending' COMMENT 'Booking status',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'When booking was created',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Last update time',
  PRIMARY KEY (`id`),
  KEY `fk_booking_user` (`user_id`),
  KEY `idx_booking_date` (`booking_date`),
  KEY `idx_facility_type` (`facility_type`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for storing facility bookings';

-- Add foreign key constraint
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_booking_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Create index for better performance on date range queries
CREATE INDEX idx_booking_datetime ON bookings(booking_date, booking_time);

-- Insert some sample data for testing 
INSERT INTO `bookings` 
(`user_id`, `facility_type`, `name`, `email`, `phone`, `booking_date`, `booking_time`, `duration`, `facility_details`, `status`, `created_at`) 
VALUES
(13, 'Ground Booking', 'Malith', 'malith@gmail.com', '0771234567', '2025-11-20', '10:00:00', 2, 
 '{"sports_type":"Cricket","num_players":"22","special_requirements":"Tournament preparation"}', 
 'Confirmed', '2025-11-17 10:30:00'),
 
(13, 'Gym Booking', 'Malith', 'malith@gmail.com', '0771234567', '2025-11-18', '18:00:00', 2, 
 '{"session_type":"General Workout","num_people":"1","preferred_area":"Weight Training","equipment_preference":""}', 
 'Confirmed', '2025-11-17 11:00:00'),
 
(16, 'Indoor Booking', 'Avishka', 'avishka@gmail.com', '0779876543', '2025-11-19', '16:00:00', 1, 
 '{"sport_type":"Badminton","court_selection":"Court 1","num_players":"2","court_type":"Singles","equipment_rental":"2 rackets","booking_purpose":"Casual play"}', 
 'Pending', '2025-11-17 12:00:00');

-- End of add_bookings_table.sql