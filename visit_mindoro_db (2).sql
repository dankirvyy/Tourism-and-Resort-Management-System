-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 16, 2025 at 04:42 AM
-- Server version: 9.1.0
-- PHP Version: 8.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `visit_mindoro_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `guest_id` int NOT NULL,
  `room_id` int NOT NULL,
  `check_in_date` date NOT NULL,
  `check_in_time` time DEFAULT '14:00:00' COMMENT 'Default check-in time is 2:00 PM',
  `check_out_date` date NOT NULL,
  `check_out_time` time DEFAULT '12:00:00' COMMENT 'Default check-out time is 12:00 PM',
  `total_price` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `balance_due` decimal(10,2) DEFAULT '0.00',
  `payment_status` enum('paid','partial','unpaid') DEFAULT 'paid',
  `status` varchar(50) DEFAULT 'confirmed',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `guest_id` (`guest_id`),
  KEY `room_id` (`room_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `guest_id`, `room_id`, `check_in_date`, `check_in_time`, `check_out_date`, `check_out_time`, `total_price`, `amount_paid`, `balance_due`, `payment_status`, `status`, `notes`, `created_at`) VALUES
(1, 1, 8, '2025-11-17', '08:00:00', '2025-11-20', '12:00:00', 19500.00, 19500.00, 0.00, 'paid', 'confirmed', NULL, '2025-11-16 02:21:38'),
(2, 1, 1, '2025-11-10', '14:00:00', '2025-11-15', '12:00:00', 5000.00, 5000.00, 0.00, 'paid', 'completed', NULL, '2025-11-16 03:35:52');

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
CREATE TABLE IF NOT EXISTS `guests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text,
  `country` varchar(100) DEFAULT NULL COMMENT 'Guest country',
  `last_contacted_at` datetime DEFAULT NULL COMMENT 'Last time admin contacted guest',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `guest_type` enum('new','regular','vip','corporate') DEFAULT 'new',
  `preferences` text COMMENT 'JSON or text preferences like room type, dietary needs, etc.',
  `notes` text COMMENT 'Admin notes about the guest',
  `marketing_consent` tinyint(1) DEFAULT '0' COMMENT '1 = subscribed to marketing emails',
  `last_visit_date` date DEFAULT NULL COMMENT 'Last booking date',
  `total_visits` int DEFAULT '0' COMMENT 'Number of completed bookings',
  `total_revenue` decimal(10,2) DEFAULT '0.00' COMMENT 'Lifetime spending',
  `loyalty_points` int DEFAULT '0' COMMENT 'Reward points for future use',
  `tags` varchar(255) DEFAULT NULL COMMENT 'Comma-separated tags: honeymoon,business,family',
  `birthday` date DEFAULT NULL COMMENT 'Guest birthday for special offers',
  `avatar_filename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`(191))
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `first_name`, `last_name`, `email`, `phone_number`, `address`, `country`, `last_contacted_at`, `created_at`, `password`, `role`, `guest_type`, `preferences`, `notes`, `marketing_consent`, `last_visit_date`, `total_visits`, `total_revenue`, `loyalty_points`, `tags`, `birthday`, `avatar_filename`) VALUES
(1, 'Dan', 'Kirvy Manongsong', 'dankirvymanongsong@gmail.com', '09163235812', NULL, NULL, NULL, '2025-10-18 11:18:46', '$2y$12$QJLjrSpgWWtjgrirhUfCTuUvA3tERLJWU2Wkg8BrDQ.1Pb6S0jN/q', 'user', 'new', NULL, NULL, 0, '2025-11-21', 2, 21000.00, 210, NULL, NULL, '201b72f30356de815eebd494bc0a7858c9da51e9.jpg'),
(13, 'CheonYeo', 'Woon', 'wooncheonyeo@gmail.com', '09163235812', NULL, NULL, NULL, '2025-10-31 09:02:45', '$2y$12$6s7KORRYHrrYOiuj5BycwO77r2wjtO3sj23zzwjKIDgle2dVC4Cxu', 'user', 'new', NULL, NULL, 0, NULL, 0, 0.00, 0, NULL, NULL, '23441d4b6138441fe981e86e0cfbf64d3a84ca0f.jpg'),
(15, 'Hanna Grace', 'Manongsong', 'hannaaaaaa59@gmail.com', '09163235812', NULL, NULL, NULL, '2025-11-01 12:05:46', '$2y$12$V7Mt20RpFwuQkjht.UjX0OIJxeSwiEcOxQhtgHA.mmAJbXoUZGUj6', 'user', 'new', NULL, NULL, 0, NULL, 0, 0.00, 0, NULL, NULL, NULL),
(6, 'Kirvy Admin', 'Manongsong', 'admin@gmail.com', NULL, NULL, NULL, NULL, '2025-10-21 13:04:08', '$2y$10$E/g1.q.g3z9z.Fp/g3z9z.Fp/g3z9z.Fp/g3z9z.Fp/g3z9z.E', 'admin', 'new', NULL, NULL, 0, NULL, 0, 0.00, 0, NULL, NULL, NULL),
(8, 'Kirvy', 'Manongsong', 'kirvspogz@gmail.com', '09163235812', NULL, NULL, NULL, '2025-10-30 12:00:32', '$2y$12$Xgovrbcjlrahv.IoJcMK6ORe2LF5nI8aNwKkWXg2JNBQYhlH5d3k6', 'user', 'new', NULL, NULL, 0, NULL, 0, 0.00, 0, NULL, NULL, 'ebe4b43d5e999247087f01cffdef487816fbc528.jpg'),
(11, 'Kirvs', 'Manongsong', 'kirvygwapo11@gmail.com', '09163235812', NULL, NULL, NULL, '2025-10-31 08:28:58', '$2y$12$tECiuwjh63eyigm95LiMKeRYkGjmlq5vzywb159.Ge1xWc9SyGM/a', 'user', 'new', NULL, NULL, 0, NULL, 0, 0.00, 0, NULL, NULL, 'ed04380a375553a599e06bbdac65927771c644f7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `guest_communications`
--

DROP TABLE IF EXISTS `guest_communications`;
CREATE TABLE IF NOT EXISTS `guest_communications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `guest_id` int NOT NULL,
  `communication_type` enum('email','phone','sms','in-person','other') DEFAULT 'email',
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `sent_by` varchar(100) DEFAULT NULL COMMENT 'Admin who sent it',
  `sent_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_guest_id` (`guest_id`),
  KEY `idx_sent_at` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guest_preferences`
--

DROP TABLE IF EXISTS `guest_preferences`;
CREATE TABLE IF NOT EXISTS `guest_preferences` (
  `id` int NOT NULL AUTO_INCREMENT,
  `guest_id` int NOT NULL,
  `preference_category` varchar(100) DEFAULT NULL COMMENT 'room_type, dietary, activities, etc.',
  `preference_value` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_guest_id` (`guest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int DEFAULT NULL,
  `tour_booking_id` int DEFAULT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `idx_tour_booking_id` (`tour_booking_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Stores invoices for both room bookings and tour bookings';

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `booking_id`, `tour_booking_id`, `issue_date`, `due_date`, `total_amount`, `status`, `created_at`) VALUES
(1, 1, NULL, '2025-11-16', '2025-11-17', 19500.00, 'paid', '2025-11-16 02:21:38'),
(2, NULL, 1, '2025-11-16', '2025-11-19', 3900.00, 'paid', '2025-11-16 02:27:22');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 'Garden View Suite (RM G-Room 101) - 2025-11-17 to 2025-11-20', 3, 6500.00, 19500.00),
(2, 2, 'Tour: Tamaraw Falls Visit (2025-11-19) - 3 pax', 3, 1300.00, 3900.00);

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
CREATE TABLE IF NOT EXISTS `resources` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `capacity` int DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `name`, `type`, `capacity`, `is_available`, `created_at`) VALUES
(1, 'Car', 'Vehicle', 4, 1, '2025-10-25 07:04:33'),
(2, 'Juan Dela Cruz', 'Guide', NULL, 1, '2025-11-15 11:14:14');

-- --------------------------------------------------------

--
-- Table structure for table `resource_schedules`
--

DROP TABLE IF EXISTS `resource_schedules`;
CREATE TABLE IF NOT EXISTS `resource_schedules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `resource_id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `tour_booking_id` int DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  KEY `booking_id` (`booking_id`),
  KEY `tour_booking_id` (`tour_booking_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `resource_schedules`
--

INSERT INTO `resource_schedules` (`id`, `resource_id`, `booking_id`, `tour_booking_id`, `start_time`, `end_time`) VALUES
(1, 1, NULL, 1, '2025-11-19 08:00:00', '2025-11-19 17:00:00'),
(2, 2, NULL, 1, '2025-11-19 08:00:00', '2025-11-19 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_type_id` int NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `status` varchar(50) DEFAULT 'available',
  PRIMARY KEY (`id`),
  KEY `room_type_id` (`room_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_type_id`, `room_number`, `status`) VALUES
(1, 1, 'Room 101', 'available'),
(2, 2, 'Room 102', 'available'),
(3, 3, 'Villa A', 'available'),
(5, 1, 'Room 103', 'available'),
(6, 5, 'RM 101', 'available'),
(7, 2, 'Room 104', 'available'),
(8, 6, 'G-Room 101', 'occupied'),
(9, 6, 'G-Room 102', 'available'),
(10, 4, 'BC-101', 'available'),
(11, 4, 'BC-102', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

DROP TABLE IF EXISTS `room_types`;
CREATE TABLE IF NOT EXISTS `room_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `base_price` decimal(10,2) NOT NULL,
  `capacity` int NOT NULL,
  `image_filename` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `description`, `base_price`, `capacity`, `image_filename`) VALUES
(1, 'Standard Twin Room', 'A comfortable and affordable room with two single beds, an en-suite bathroom, and basic amenities. Perfect for friends or solo travelers.', 2500.00, 2, '2170856_17042609030052634768.jpg'),
(2, 'Deluxe Queen Room', 'A more spacious room featuring a queen-sized bed, a private balcony with a garden view, air-conditioning, and an upgraded bathroom. Ideal for couples.', 4000.00, 2, 'Deluxe-Queen-Bedroom-2-Park-Regis-by-Prince-scaled-2520x1400.jpg'),
(3, 'Family Villa', 'A large, two-bedroom villa with a separate living area and a small kitchenette. It includes one king-sized bed and two single beds, perfect for families or small groups.', 7500.00, 4, 'ebeb9f7ac5dba9befb222cdb83fbadb3_8f3b0314682b243d6156aa6b3efff16b_compressed.jpg'),
(4, 'Beachfront Casita', 'A private, detached bungalow right on the beach. Features a king-sized bed, a private veranda with a hammock, and direct access to the shore. The ultimate romantic getaway.', 9000.00, 2, 'CasitaBeachfrontinBatangas.jpg'),
(5, 'Barkada Room', 'A large, dormitory-style room with multiple bunk beds, lockers for each guest, and a shared bathroom. Perfect for groups of friends or backpackers on a budget.', 6000.00, 8, '20180831020027_BLQBSEXT-A4K9-4TKH-HY24-CPE2MTL8KPZM_lg.jpg'),
(6, 'Garden View Suite', 'A spacious suite with a separate living area, a king-sized bed, and a large balcony overlooking the resort\'s lush tropical gardens. Includes a minibar and premium amenities.', 6500.00, 2, 'LHorizon_54_3rmF2Vl.2e16d0ba.fill-1980x872-c100.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

DROP TABLE IF EXISTS `tours`;
CREATE TABLE IF NOT EXISTS `tours` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `image_filename` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `max_capacity` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `name`, `description`, `price`, `duration`, `is_active`, `image_filename`, `latitude`, `longitude`, `created_at`, `max_capacity`) VALUES
(2, 'Tamaraw Falls Visit', 'A refreshing trip to the scenic Tamaraw Falls. Enjoy a swim in the cool waters or have a picnic with a beautiful view. Located along the main highway, making it an easy and accessible stop.', 1300.00, 'Half-day', 1, 'Tamaraw-Falls-1.jpg', '13.4501062', '120.9912158', '2025-10-18 10:56:23', 0),
(5, 'Silonay Mangrove Ecotour', 'A relaxing walk through a protected mangrove forest on a bamboo boardwalk. Perfect for bird watching and learning about local conservation efforts.', 800.00, '2-3 hours', 1, '6c575ea0-9833-453d-9ca0-54e9b6a98612.jpg', '13.4007', '121.2250', '2025-10-18 12:18:15', 0),
(6, 'Calapan City Heritage Tour', 'Discover the history of Calapan. This tour includes visits to the Calapan Cathedral, the old Provincial Capitol building, and other significant local landmarks.', 1500.00, '4 hours', 1, '81e9fe50-9b6c-479d-b2cb-f39ab199819b.jpg', '13.4139', '121.1800', '2025-10-18 12:18:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tour_bookings`
--

DROP TABLE IF EXISTS `tour_bookings`;
CREATE TABLE IF NOT EXISTS `tour_bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `guest_id` int NOT NULL,
  `tour_id` int NOT NULL,
  `booking_date` date NOT NULL,
  `number_of_pax` int DEFAULT '1',
  `total_price` decimal(10,2) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `balance_due` decimal(10,2) DEFAULT '0.00',
  `payment_status` enum('paid','partial','unpaid') DEFAULT 'paid',
  `status` varchar(50) DEFAULT 'confirmed',
  PRIMARY KEY (`id`),
  KEY `guest_id` (`guest_id`),
  KEY `tour_id` (`tour_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_bookings`
--

INSERT INTO `tour_bookings` (`id`, `guest_id`, `tour_id`, `booking_date`, `number_of_pax`, `total_price`, `amount_paid`, `balance_due`, `payment_status`, `status`) VALUES
(1, 1, 2, '2025-11-19', 3, 3900.00, 3900.00, 0.00, 'paid', 'confirmed');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
