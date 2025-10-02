-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 02, 2025 at 02:08 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_tunggal_jaya_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `booking_date` date DEFAULT NULL,
  `booking_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passenger_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passenger_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passenger_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seat_numbers` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_seats` int NOT NULL DEFAULT '1',
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `booking_status` enum('pending','confirmed','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_started_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `schedule_id`, `booking_date`, `booking_code`, `passenger_name`, `passenger_phone`, `passenger_email`, `seat_numbers`, `number_of_seats`, `total_price`, `payment_status`, `booking_status`, `payment_started_at`, `created_at`, `updated_at`) VALUES
(3, 1, 10, '2025-09-29', 'BK68DA8E08F34C2', 'test', '1292929', 'test@gmail.com', '2', 1, 100000.00, 'paid', 'confirmed', NULL, '2025-09-29 13:47:52', '2025-09-29 13:48:17'),
(4, 1, 10, '2025-09-30', 'BK68DBA106049EF', 'Rahmadi', '99999', 'madi@gmail.com', '1', 1, 100000.00, 'paid', 'confirmed', NULL, '2025-09-30 09:21:10', '2025-09-30 09:21:33'),
(5, 4, 9, '2025-10-01', 'BK68DC6D4EDCA34', 'mamat', '08978638973', 'darellrangga188@gmail.com', '1', 1, 120000.00, 'paid', 'confirmed', NULL, '2025-09-30 23:52:46', '2025-09-30 23:53:25'),
(6, 4, 10, '2025-10-01', 'BK68DC70EBBCB8E', 'mamat', '08978638973', 'darrelrangga188@gmail.com', '1', 1, 100000.00, 'paid', 'confirmed', NULL, '2025-10-01 00:08:11', '2025-10-01 00:08:40'),
(7, 5, 9, '2025-10-01', 'BK68DC7EBB97555', 'jamet', '929929292', 'jamet@gmail.com', '2', 1, 120000.00, 'paid', 'confirmed', NULL, '2025-10-01 01:07:07', '2025-10-01 01:07:29'),
(8, 6, 10, '2025-10-01', 'BK68DCBB79D2C88', 'juki', '0897622626', 'juki@gmail.com', '2', 1, 100000.00, 'paid', 'confirmed', NULL, '2025-10-01 05:26:17', '2025-10-01 05:26:39'),
(9, 6, 10, '2025-10-01', 'BK68DD11F37183B', 'jukian', '0989373713', 'juki@gmail.com', '3', 1, 100000.00, 'paid', 'confirmed', NULL, '2025-10-01 11:35:15', '2025-10-01 11:35:39'),
(10, 6, 10, '2025-10-01', 'BK68DD1478E7D95', 'juki', '08972355251', 'juki@gmail.com', NULL, 3, 300000.00, 'pending', 'confirmed', '2025-10-01 11:46:00', '2025-10-01 11:46:00', '2025-10-01 11:46:00'),
(11, 6, 10, '2025-10-01', 'BK68DD1490EC960', 'juki', '0101010101', 'juki@gmail.com', NULL, 3, 300000.00, 'pending', 'confirmed', '2025-10-01 11:46:24', '2025-10-01 11:46:24', '2025-10-01 11:46:24'),
(12, 6, 10, '2025-10-01', 'BK68DD14A33F38B', 'juki', '100101010', 'juki@gmail.com', '4,5,6', 3, 300000.00, 'paid', 'confirmed', NULL, '2025-10-01 11:46:43', '2025-10-01 11:47:25'),
(13, 6, 9, '2025-10-02', 'BK68DDCC28370FA', 'juki', '0897361631', 'juki@gmail.com', '1', 1, 120000.00, 'paid', 'confirmed', NULL, '2025-10-02 00:49:44', '2025-10-02 00:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `year` year DEFAULT NULL,
  `status` enum('active','maintenance','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `name`, `plate_number`, `bus_type`, `capacity`, `description`, `year`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pesona X-Deres', 'K 1234 III', 'Economy', 49, 'Luxury bus with extra legroom and premium amenities', '2022', 'active', '2025-09-26 07:24:37', '2025-09-28 11:57:19'),
(2, 'Bentas 01', 'K 5678 ABC', 'Economy', 49, 'Comfortable bus with reclining seats and entertainment', '2021', 'active', '2025-09-26 07:24:37', '2025-09-28 11:57:29'),
(3, 'Resi Bisma', 'K 9012 DEF', 'Economy', 49, 'Affordable travel with basic comfort', '2022', 'active', '2025-09-26 07:24:37', '2025-09-28 11:57:43'),
(5, 'Bentas 03', 'K 1111  BNE', 'Economy', 49, '-', '2020', 'active', '2025-09-28 11:25:11', '2025-09-28 11:57:10'),
(6, 'Neptune', 'K 1929 KXZ', 'Economy', 40, '-', '2016', 'active', '2025-09-28 11:45:14', '2025-09-28 11:56:57');

-- --------------------------------------------------------

--
-- Table structure for table `bus_conductor`
--

CREATE TABLE `bus_conductor` (
  `id` bigint UNSIGNED NOT NULL,
  `bus_id` bigint UNSIGNED NOT NULL,
  `conductor_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_driver`
--

CREATE TABLE `bus_driver` (
  `id` bigint UNSIGNED NOT NULL,
  `bus_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_facility`
--

CREATE TABLE `bus_facility` (
  `id` bigint UNSIGNED NOT NULL,
  `bus_id` bigint UNSIGNED NOT NULL,
  `facility_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test', '123', 2, '2025-09-26 07:43:00', '2025-09-30 08:47:04'),
(2, 'test2', 'test2', '-', NULL, '2025-09-30 08:46:56', '2025-09-30 08:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `conductors`
--

CREATE TABLE `conductors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `employee_id`, `license_number`, `phone`, `email`, `address`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Deni permana', 'DRV01', '12345679', '08978283736', 'deni@gmail.com', '-', 'active', '2025-09-29 22:51:43', '2025-09-29 22:51:43'),
(3, 'Komarudin', 'DRV02', '22222222', '0836136251', 'komarudin@gmail.com', '-', 'active', '2025-09-29 22:54:28', '2025-09-29 22:54:28');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `icon`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Air Conditioning', 'fas fa-wind', 'Climate control system for comfortable travel', '2025-09-26 08:13:17', '2025-09-26 08:13:17'),
(2, 'Wi-Fi', 'fas fa-wifi', 'Free wireless internet access throughout the journey', '2025-09-26 08:13:17', '2025-09-26 08:13:17'),
(3, 'Entertainment System', 'fas fa-tv', 'Onboard entertainment with movies and music', '2025-09-26 08:13:17', '2025-09-26 08:13:17'),
(4, 'Restroom', 'fas fa-toilet', 'Clean restroom facilities available', '2025-09-26 08:13:17', '2025-09-26 08:13:17'),
(5, 'Reclining Seats', 'fas fa-couch', 'Comfortable seats with adjustable recline', '2025-09-26 08:13:17', '2025-09-26 08:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(5, 'App\\Models\\Bus', 5, '174f9bb9-b620-4ed5-8c73-d927d4e4813d', 'buses', 'Bentas03', 'Bentas03.png', 'image/webp', 'public', 'public', 1047762, '[]', '[]', '[]', '[]', 1, '2025-09-28 11:25:11', '2025-09-28 11:25:11'),
(6, 'App\\Models\\Bus', 1, '3881e4f4-b114-40f4-9489-0e92ded52331', 'buses', 'New Pesona X-Deres TJ SR2🔥Unit pertama SR2 dari Tunggal Jaya😱❤Merah Merona❤Via Sindang Laut😎.heic', 'New-Pesona-X-Deres-TJ-SR2🔥Unit-pertama-SR2-dari-Tunggal-Jaya😱❤Merah-Merona❤Via-Sindang-Laut😎.heic.jpg', 'image/jpeg', 'public', 'public', 174068, '[]', '[]', '[]', '[]', 1, '2025-09-28 11:27:10', '2025-09-28 11:27:10'),
(8, 'App\\Models\\Bus', 3, '50c7beb7-ef8c-4ae8-ac03-f3d38cfd585f', 'buses', 'Resbisma', 'Resbisma.webp', 'image/webp', 'public', 'public', 231200, '[]', '[]', '[]', '[]', 1, '2025-09-28 11:33:33', '2025-09-28 11:33:33'),
(9, 'App\\Models\\Bus', 2, 'ccc94791-95e2-40fd-8168-329677e21883', 'buses', 'Bentas 01', 'Bentas-01.webp', 'image/webp', 'public', 'public', 132406, '[]', '[]', '[]', '[]', 1, '2025-09-28 11:36:20', '2025-09-28 11:36:20'),
(10, 'App\\Models\\Bus', 6, '0e5aaf32-2428-431e-8426-97a69329c443', 'buses', 'Neptune', 'Neptune.jpg', 'image/jpeg', 'public', 'public', 99581, '[]', '[]', '[]', '[]', 1, '2025-09-28 11:45:14', '2025-09-28 11:45:14'),
(11, 'App\\Models\\Driver', 2, '191edf45-ab66-46ce-87a7-f017be6e23fd', 'drivers', 'deni permana', 'deni-permana.jpg', 'image/jpeg', 'public', 'public', 148463, '[]', '[]', '[]', '[]', 1, '2025-09-29 22:51:44', '2025-09-29 22:51:44');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_14_051831_create_permission_tables', 1),
(5, '2025_09_14_051835_create_media_table', 1),
(6, '2025_09_14_051847_create_personal_access_tokens_table', 1),
(7, '2025_09_14_051939_create_buses_table', 1),
(8, '2025_09_14_051942_create_routes_table', 1),
(9, '2025_09_14_051948_create_schedules_table', 1),
(10, '2025_09_14_051954_create_bookings_table', 1),
(11, '2025_09_14_052004_create_categories_table', 1),
(12, '2025_09_14_052005_create_news_articles_table', 1),
(13, '2025_09_14_052011_create_facilities_table', 1),
(14, '2025_09_14_052015_create_drivers_table', 1),
(15, '2025_09_14_052358_create_bus_facility_table', 1),
(16, '2025_09_14_052414_create_bus_driver_table', 1),
(17, '2025_09_15_130908_create_conductors_table', 1),
(18, '2025_09_15_130957_create_bus_conductor_table', 1),
(19, '2025_09_15_132741_add_employee_id_to_drivers_table', 1),
(20, '2025_09_15_142009_create_media_collections_for_drivers_and_conductors', 1),
(21, '2025_09_18_125822_add_weekly_schedule_fields_to_schedules_table', 2),
(22, '2025_09_19_221142_add_is_daily_to_schedules_table', 3),
(23, '2025_09_26_120000_add_booking_date_to_bookings_table', 4),
(24, '2025_09_16_095137_add_number_of_seats_to_bookings_table', 5),
(25, '2025_09_19_120433_fix_schedule_time_fields_to_datetime', 6),
(26, '2025_09_19_113037_fix_bookings_table_structure', 7),
(27, '2025_09_16_094642_ensure_seat_numbers_is_nullable_in_bookings_table', 8),
(28, '2025_09_28_184944_add_year_and_fuel_type_to_buses_table', 9),
(29, '2025_09_29_000000_remove_weekly_schedule_fields_from_schedules_table', 10),
(34, '2025_01_01_000000_create_otp_codes_table', 11),
(35, '2025_01_01_000001_add_phone_verification_to_users_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6);

-- --------------------------------------------------------

--
-- Table structure for table `news_articles`
--

CREATE TABLE `news_articles` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news_articles`
--

INSERT INTO `news_articles` (`id`, `title`, `slug`, `content`, `excerpt`, `is_published`, `published_at`, `category_id`, `author_id`, `created_at`, `updated_at`) VALUES
(3, 'Test', 'test', '123', '123', 0, NULL, NULL, 1, '2025-09-30 08:45:13', '2025-09-30 08:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `phone`, `otp`, `expires_at`, `used`, `created_at`, `updated_at`) VALUES
(3, '08978638973', '793411', '2025-09-30 23:48:02', 1, '2025-09-30 23:38:02', '2025-09-30 23:38:14'),
(4, '08978638973', '241891', '2025-09-30 23:49:50', 1, '2025-09-30 23:39:50', '2025-09-30 23:41:05'),
(5, '08978638973', '127383', '2025-10-01 00:07:29', 1, '2025-09-30 23:57:29', '2025-09-30 23:57:35'),
(6, '08978638973', '823607', '2025-10-01 00:07:45', 1, '2025-09-30 23:57:45', '2025-09-30 23:57:51'),
(7, '928183173', '760528', '2025-10-01 00:55:33', 1, '2025-10-01 00:45:33', '2025-10-01 00:45:40'),
(8, '0897622626', '316977', '2025-10-01 05:35:46', 1, '2025-10-01 05:25:46', '2025-10-01 05:25:56'),
(9, '0897622626', '243521', '2025-10-01 11:37:06', 0, '2025-10-01 11:27:06', '2025-10-01 11:27:06');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-09-26 07:24:07', '2025-09-26 07:24:07'),
(2, 'user', 'web', '2025-09-26 07:24:07', '2025-09-26 07:24:07');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `distance` decimal(8,2) DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `name`, `origin`, `destination`, `distance`, `duration`, `description`, `created_at`, `updated_at`) VALUES
(2, 'Jakarta - Kuningan', 'Jakarta', 'Kuningan', 100.00, 90, '-', '2025-09-27 08:34:30', '2025-09-27 08:34:30'),
(3, 'Kuningan - Jakarta', 'Kuningan', 'Jakarta', 100.00, 90, '-', '2025-09-27 08:34:54', '2025-09-27 08:34:54'),
(4, 'Kuningan - Palembang', 'Kuningan', 'Palembang', 888.00, 1268, '-', '2025-09-27 08:35:22', '2025-09-27 08:35:22'),
(5, 'Kuningan - Cileungsi', 'Kuningan', 'Cileungsi', 232.00, 132, '-', '2025-09-27 08:37:30', '2025-09-27 08:37:30');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `bus_id` bigint UNSIGNED NOT NULL,
  `route_id` bigint UNSIGNED NOT NULL,
  `departure_time` datetime NOT NULL,
  `arrival_time` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','cancelled','delayed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_daily` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `bus_id`, `route_id`, `departure_time`, `arrival_time`, `price`, `status`, `is_daily`, `created_at`, `updated_at`) VALUES
(8, 6, 4, '2025-09-29 23:00:00', '2025-09-29 04:00:00', 240000.00, 'active', 0, '2025-09-29 13:39:40', '2025-09-29 13:39:40'),
(9, 2, 2, '2000-01-01 09:00:00', '2000-01-01 15:00:00', 120000.00, 'active', 1, '2025-09-29 13:41:35', '2025-09-29 13:41:35'),
(10, 3, 5, '2000-01-01 21:00:00', '2000-01-01 02:00:00', 100000.00, 'active', 1, '2025-09-29 13:47:01', '2025-09-29 13:47:01');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('00Fw0Rpz2orkAZpQO2SpjA2Y5oL5ZpCnsltNH3Ak', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36 Edg/140.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRHFrN3ZVTkI3VVNiV3ljeXVPMnlLNlJyVXhad3k5VHA2clM0NXdTOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly90dW5nZ2FsamF5YXRyYW5zcG9ydC50ZXN0L2FkbWluL2J1c2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9', 1759366740);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `phone_verified_at`, `is_verified`) VALUES
(1, 'Admin User', 'admin@example.com', NULL, '$2y$12$19Uo3s/0.2DS0iQYimUAaOS2O.p7vRkygJXhNGxtHOaLKdLfIPLi6', 'FK1hfF8XzrINq8M4MZv8xw1ds2uYtE3EujJTwzfs7ZbM399ed0tBuxqTJXME', '2025-09-26 07:24:08', '2025-09-26 07:24:08', NULL, NULL, 0),
(2, 'Regular User', 'user@example.com', NULL, '$2y$12$/iLz6jHruGgia7K3dV81ruS3X7lGRXOVXGx0z6/HTVskV2s2nzRbS', NULL, '2025-09-26 07:24:09', '2025-09-26 07:24:09', NULL, NULL, 0),
(4, 'Mamat', 'darellrangga188@gmail.com', NULL, '$2y$12$XcVpQQBOXJdTiBD5N/XBquq..6HTtT4MaPCqP5c68KyrzLeUKR9cy', 'DlyqxQdfnfMWvuXXyANqzp3tYUW4hT0SwZL97VtDh8BruDMmXswrLmc4p0Ht', '2025-09-30 23:32:50', '2025-09-30 23:32:50', '08978638973', NULL, 0),
(5, 'jamet', 'jamet@gmail.com', NULL, '$2y$12$U8cv00Ln88iupDtVw0AHAen6xYdY2rDPjFBC.gM1LRBczhFbi3bUm', NULL, '2025-10-01 00:45:28', '2025-10-01 00:45:28', '928183173', NULL, 0),
(6, 'juki', 'juki@gmail.com', NULL, '$2y$12$zrPxHXkeFoQsn3XK.83hbeF0UCqIKGNa8/IWocHr/oMXRmWtD7Ke6', NULL, '2025-10-01 05:25:43', '2025-10-01 05:25:43', '0897622626', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  ADD KEY `bookings_user_id_foreign` (`user_id`),
  ADD KEY `idx_schedule_booking_date` (`schedule_id`,`booking_date`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buses_plate_number_unique` (`plate_number`);

--
-- Indexes for table `bus_conductor`
--
ALTER TABLE `bus_conductor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_conductor_bus_id_foreign` (`bus_id`),
  ADD KEY `bus_conductor_conductor_id_foreign` (`conductor_id`);

--
-- Indexes for table `bus_driver`
--
ALTER TABLE `bus_driver`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_driver_bus_id_foreign` (`bus_id`),
  ADD KEY `bus_driver_driver_id_foreign` (`driver_id`);

--
-- Indexes for table `bus_facility`
--
ALTER TABLE `bus_facility`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_facility_bus_id_foreign` (`bus_id`),
  ADD KEY `bus_facility_facility_id_foreign` (`facility_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `conductors`
--
ALTER TABLE `conductors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conductors_employee_id_unique` (`employee_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `drivers_license_number_unique` (`license_number`),
  ADD UNIQUE KEY `drivers_employee_id_unique` (`employee_id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `news_articles`
--
ALTER TABLE `news_articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_articles_slug_unique` (`slug`),
  ADD KEY `news_articles_category_id_foreign` (`category_id`),
  ADD KEY `news_articles_author_id_foreign` (`author_id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otp_codes_phone_index` (`phone`),
  ADD KEY `otp_codes_otp_index` (`otp`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_bus_id_foreign` (`bus_id`),
  ADD KEY `schedules_route_id_foreign` (`route_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bus_conductor`
--
ALTER TABLE `bus_conductor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_driver`
--
ALTER TABLE `bus_driver`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bus_facility`
--
ALTER TABLE `bus_facility`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `conductors`
--
ALTER TABLE `conductors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `news_articles`
--
ALTER TABLE `news_articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bus_conductor`
--
ALTER TABLE `bus_conductor`
  ADD CONSTRAINT `bus_conductor_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bus_conductor_conductor_id_foreign` FOREIGN KEY (`conductor_id`) REFERENCES `conductors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bus_driver`
--
ALTER TABLE `bus_driver`
  ADD CONSTRAINT `bus_driver_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bus_driver_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bus_facility`
--
ALTER TABLE `bus_facility`
  ADD CONSTRAINT `bus_facility_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bus_facility_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_articles`
--
ALTER TABLE `news_articles`
  ADD CONSTRAINT `news_articles_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_articles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_bus_id_foreign` FOREIGN KEY (`bus_id`) REFERENCES `buses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
