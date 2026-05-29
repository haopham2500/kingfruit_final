-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 29, 2026 at 02:22 AM
-- Server version: 8.4.7
-- PHP Version: 8.5.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kingfruit_full`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Trái cây nội địa', '2026-05-07 19:37:15', '2026-05-07 19:37:15'),
(2, 'Trái cây nhập khẩu', '2026-05-07 19:37:15', '2026-05-07 19:37:15'),
(3, 'Giỏ quà trái cây', '2026-05-07 19:37:15', '2026-05-07 19:37:15');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `receiver_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(20,2) NOT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `orders_ibfk_1` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `receiver_name`, `phone_number`, `address`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 9, 'Cụ. Ngụy Hải Xuyến', '0911901782', '942 Phố Mai, Ấp Xuyến Văn, Quận Chử Vượng Mạnh\nKhánh Hòa', 1419000.00, 'processing', '2026-04-17 23:12:08', '2026-05-12 03:38:56'),
(2, 4, 'Trương Chung', '0911901782', '8 Phố Nghiêm, Phường Hoàn, Quận Mi\nVĩnh Phúc', 929000.00, 'processing', '2026-04-27 02:28:46', '2026-05-12 03:38:56'),
(3, 6, 'Hồ Đại Pháp', '0911901782', '4 Phố Cù Đường Hữu, Xã Triều Cấn, Quận Thắm\nThanh Hóa', 739000.00, 'completed', '2026-04-26 18:41:50', '2026-05-12 03:38:56'),
(4, 4, 'Hình Phước Quyên', '0911901782', '5324 Phố Khoa Linh Thy, Ấp San Đạo, Quận Yên\nCần Thơ', 739000.00, 'processing', '2026-05-08 06:10:19', '2026-05-21 20:15:23'),
(5, 10, 'Lô Thơ Trung', '0911901782', '416 Phố Tiêu Hiếu Châu, Xã Bửu Diệp, Huyện Lễ Châu\nQuảng Ninh', 155000.00, 'processing', '2026-04-28 04:21:22', '2026-05-12 03:38:56'),
(6, 5, 'Bác. Khúc Từ Liên', '0911901782', '261 Phố Cổ Quỳnh Nhuận, Phường Đường Quế Yên, Quận Hoa Lữ\nHồ Chí Minh', 486000.00, 'cancelled', '2026-05-02 16:00:46', '2026-05-12 03:38:56'),
(7, 1, 'Đàm Hán Bằng', '0911901782', '234 Phố Quản, Xã 6, Quận Kha Ngọc\nHải Phòng', 344000.00, 'cancelled', '2026-05-02 01:56:30', '2026-05-12 03:38:56'),
(8, 1, 'Cô. Hứa Lý', '0911901782', '8736 Phố Hoàng Trung Thắng, Ấp Tường Mi, Quận Khiêm Lục\nLong An', 721000.00, 'cancelled', '2026-05-02 16:26:57', '2026-05-12 03:38:56'),
(9, 2, 'Trưng Đài', '0911901782', '71 Phố Liễu Khánh Khang, Phường Khanh Triệu, Quận Uyển Bá\nNinh Thuận', 167000.00, 'cancelled', '2026-04-28 14:22:35', '2026-05-12 03:38:56'),
(10, 11, 'Bà. Bàng Huyền', '0911901782', '8, Ấp 8, Xã Tụ, Huyện Trà\nHà Giang', 1747000.00, 'cancelled', '2026-05-06 23:45:19', '2026-05-12 03:38:56');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price_at_purchase` decimal(20,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_ibfk_1` (`order_id`),
  KEY `order_items_ibfk_2` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 7, 2, 85000.00),
(2, 1, 15, 1, 450000.00),
(3, 4, 9, 1, 150000.00),
(4, 4, 14, 1, 850000.00);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(20,2) NOT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'kg',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_quantity` int DEFAULT '100',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_best_seller` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `products_ibfk_1` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `unit`, `image`, `stock_quantity`, `created_at`, `updated_at`, `is_best_seller`) VALUES
(7, 1, 'Xoài Cát Hòa Lộc', 'Xoài cát Hòa Lộc loại 1, thơm ngon đặc sản.', 85000.00, 'kg', 'xoai.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(8, 1, 'Vú Sữa Lò Rèn', 'Vú sữa chín cây, ngọt lịm.', 65000.00, 'kg', 'vusua.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(9, 1, 'Sầu Riêng Ri6', 'Sầu riêng cơm vàng hạt lép.', 150000.00, 'kg', 'saurieng.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(10, 1, 'Bưởi Da Xanh', 'Bưởi da xanh Bến Tre, mọng nước.', 70000.00, 'quả', 'buoi.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(11, 1, 'Măng Cụt Lái Thiêu', 'Măng cụt đầu mùa giòn ngọt.', 95000.00, 'kg', 'mangcut.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(12, 1, 'Cam Sành Hàm Yên', 'Cam sành ngọt đậm, nhiều vitamin C.', 35000.00, 'kg', 'cam.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(13, 2, 'Táo Envy Mỹ', 'Táo Envy nhập khẩu Mỹ, giòn ngọt.', 180000.00, 'kg', 'taoenvy.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(14, 2, 'Nho Mẫu Đơn Nhật', 'Nho Shine Muscat thượng hạng.', 850000.00, 'chùm', 'nhomaudon.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(15, 2, 'Cherry Đỏ Mỹ', 'Cherry đỏ size lớn, mọng nước.', 450000.00, 'kg', 'cherry.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(16, 2, 'Kiwi Vàng New Zealand', 'Kiwi vàng giàu dinh dưỡng.', 120000.00, 'kg', 'kiwi.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(18, 2, 'Lê Hàn Quốc', 'Lê nâu Hàn Quốc, ngọt mát.', 130000.00, 'kg', 'lehan.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(19, 3, 'Giỏ Quà Phú Quý', 'Kết hợp táo, nho và cherry.', 1200000.00, 'giỏ', 'gio1.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(20, 3, 'Giỏ Quà An Khang', 'Giỏ trái cây nội địa cao cấp.', 800000.00, 'giỏ', 'gio2.jpg', 100, '2026-05-07 19:38:11', '2026-05-07 19:38:11', 0),
(21, 3, '1234', 'Sự kết hợp hoàn hảo giữa hoa và quả.', 1500001.00, 'thùng', 'gio3.jpg', 100, '2026-05-07 19:38:11', '2026-05-21 20:18:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` int NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_ibfk_1` (`product_id`),
  KEY `reviews_ibfk_2` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `parent_id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, NULL, 13, 1, 5, 'aaaaaaa', '2026-05-14 12:18:50', NULL),
(3, NULL, 16, 1, 3, 'ngon hk', '2026-05-14 05:25:01', NULL),
(4, NULL, 21, 14, 5, 'ngon ko shop', '2026-05-14 05:28:07', NULL),
(5, NULL, 20, 14, 5, 'kk', '2026-05-14 09:16:29', NULL),
(6, NULL, 13, 1, 5, 'quá đã', '2026-05-14 09:46:22', NULL),
(7, NULL, 7, 1, 5, 'fffff', '2026-05-14 10:21:17', '2026-05-14 10:21:17'),
(8, 1, 13, 1, 5, 'bbbbb', '2026-05-14 10:39:01', '2026-05-14 10:39:01'),
(9, 4, 21, 1, 5, 'ngon lắm nha', '2026-05-14 10:39:53', '2026-05-14 10:39:53'),
(10, 4, 21, 1, 5, 'thiệc hơm', '2026-05-14 10:50:24', '2026-05-14 10:50:24'),
(11, 6, 13, 1, 5, 'đúng ời', '2026-05-14 10:56:56', '2026-05-14 10:56:56'),
(12, 4, 21, 1, 5, 'thiệc hơm', '2026-05-14 11:07:34', '2026-05-14 11:07:34'),
(13, 4, 21, 1, 5, 'thiệc hơm', '2026-05-14 11:43:01', '2026-05-14 11:43:01'),
(14, NULL, 21, 1, 1, 'ko hợp', '2026-05-14 11:43:09', '2026-05-14 11:43:09');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0SebjAqRkVmzP6m0qVBxRzcZwWRs3wgfXNfx9IyE', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiJjUHF0cEEzQ0dzY055dGtHeWZzTjJJRUNCSjJTS0NNdlN6enViYXM5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1778816824),
('CWPcXz1TBWo1jzqFIjofLCQezo26cycjElVLST7g', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiJ3eDRVZTZiWVdJMTQ4dkR6aUVmRVdMT2ZBTDB6aXRjTXJxcGE5aTRyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvY3J1ZCIsInJvdXRlIjoiY3J1ZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1779420703);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `email_verified_at`, `password`, `role`, `address`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Trương Giang Admin', '0123456789', 'admin@gmail.com', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'admin', 'Hà Nội, Việt Nam', NULL, '2026-05-11 19:44:21', '2026-05-11 19:44:21'),
(2, 'Bì Khải', '+84-710-788-0090', 'tien.khu@example.com', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '7 Phố An, Xã 07, Huyện 18\nSơn La', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(3, 'Ông. Trưng Liêm', '072-119-0326', 'nga.che@example.org', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '88 Phố Trác, Phường 2, Huyện Giang Mai Toại\nĐà Nẵng', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(4, 'Em. Ân Thắm', '0510-546-4549', 'xuan.phan@example.com', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '92 Phố Lạc Bằng Lâm, Xã 8, Huyện Hảo\nĐà Nẵng', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(5, 'Chú. Lê Đình', '0710-511-0369', 'quach.ha@example.org', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '21 Phố Dư Quỳnh Kính, Ấp An Tụ, Quận Đái Mỹ Vinh\nSóc Trăng', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(6, 'Đái Chương', '094-915-2180', 'y91@example.org', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '725, Thôn Bành Tiên, Phường Cấn Tiến Bình, Huyện 12\nHậu Giang', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(7, 'Bà. Hán Hường', '0241-803-7352', 'dan.lo@example.com', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '276 Phố Lưu Tiếp Thiện, Phường Khuyên, Huyện Khôi Lai\nHà Nội', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(8, 'Em. Trịnh Nhuận', '077-669-0969', 'khuong48@example.org', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '9, Thôn 0, Xã Thiều, Quận 4\nSơn La', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(9, 'Cụ. Thôi Lam', '(84)(72)549-9867', 'tam22@example.com', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '1, Ấp Xuân, Thôn Trang Thùy, Quận Vừ Lai Quế\nNam Định', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(10, 'Bà. Vi Hoan', '(84)(70)846-0543', 'hai10@example.net', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '2820, Thôn 40, Phường 4, Quận 17\nHưng Yên', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(11, 'Ông. Mạc Khải Kỷ', '84-63-886-1455', 'han.chuong@example.net', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'user', '393 Phố Trịnh Nam Tráng, Ấp Hạ Phương, Huyện Bành Pháp Sĩ\nCần Thơ', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(12, 'Người Dùng Bị Khóa', '0999888777', 'banned@gmail.com', NULL, '$2y$12$N1W8.1Il0RSrGy6ouRfde.4OtJr0mbe5qutApi.yLWPyzS81fr5CO', 'banned', 'Sài Gòn, Việt Nam', NULL, '2026-05-11 19:44:22', '2026-05-11 19:44:22'),
(14, 'Giang', '0123456782', 'Giang@gmail.com', NULL, '$2y$12$.xXOCZ350T3aLGvfXAmq.eNb6lcH4phwXA/6TRBY2ZdkREy3vhZdu', 'user', NULL, NULL, '2026-05-14 05:27:48', '2026-05-14 05:27:48');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(20,2) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('fixed','percent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fixed',
  `min_order_value` decimal(20,2) DEFAULT '0.00',
  `quantity` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_value`, `expiry_date`, `created_at`, `type`, `min_order_value`, `quantity`, `is_active`, `updated_at`) VALUES
(1, 'KINGFRUIT10', 10.00, '2026-12-31', '2026-05-11 19:51:35', 'percent', 150000.00, 100, 1, '2026-05-11 19:51:35'),
(2, 'GIAYPHUTDAU', 20000.00, '2026-08-15', '2026-05-11 19:51:35', 'fixed', 0.00, 50, 1, '2026-05-11 19:51:35'),
(3, 'FREESHIP', 15000.00, '2026-10-10', '2026-05-11 19:51:35', 'fixed', 200000.00, 200, 1, '2026-05-11 19:51:35'),
(4, 'FRUITVIP50', 50000.00, '2026-11-20', '2026-05-11 19:51:35', 'fixed', 500000.00, 30, 1, '2026-05-11 19:51:35'),
(5, 'HELLOSUMMER', 15.00, '2026-07-30', '2026-05-11 19:51:35', 'percent', 300000.00, 80, 1, '2026-05-11 19:51:35'),
(6, 'DEALHOCSINH', 5000.00, '2026-12-31', '2026-05-11 19:51:35', 'fixed', 50000.00, 500, 1, '2026-05-11 19:51:35'),
(7, 'ANSATCHAO', 30.00, '2026-09-09', '2026-05-11 19:51:35', 'percent', 400000.00, 20, 1, '2026-05-11 19:51:35'),
(8, 'LUCKY777', 7777.00, '2026-07-07', '2026-05-11 19:51:35', 'fixed', 77000.00, 77, 1, '2026-05-11 19:51:35'),
(9, 'HETHAN2025', 100000.00, '2025-01-01', '2026-05-11 19:51:35', 'fixed', 0.00, 10, 1, '2026-05-11 19:51:35'),
(10, 'HETLUOTDUNGg', 50.00, '2026-12-31', '2026-05-11 19:51:35', 'percent', 0.00, 0, 1, '2026-05-14 20:00:49');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
