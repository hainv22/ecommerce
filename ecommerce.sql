-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: mysql:3306
-- Thời gian đã tạo: Th3 16, 2022 lúc 08:18 AM
-- Phiên bản máy phục vụ: 5.7.36
-- Phiên bản PHP: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ecommerce`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `baos`
--

CREATE TABLE `baos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `b_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `b_weight` int(11) NOT NULL COMMENT 'số cân nặng',
  `b_fee` int(11) DEFAULT '0' COMMENT 'số tiền / 1kg khi giao xong',
  `b_status` tinyint(4) NOT NULL,
  `b_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `b_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `b_success_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `b_transport_id` bigint(20) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `baos`
--

INSERT INTO `baos` (`id`, `b_name`, `b_weight`, `b_fee`, `b_status`, `b_note`, `b_transaction_id`, `b_success_date`, `created_at`, `updated_at`, `b_transport_id`) VALUES
(1, 'bao 1', 45, NULL, 1, 'túi: 5019, 035203', 1, NULL, '2022-03-11 21:49:34', '2022-03-11 21:49:34', 1),
(2, 'bao 2', 40, NULL, 1, 'túi: 3114, 2126, 1123', 1, NULL, '2022-03-11 21:49:34', '2022-03-11 21:49:34', 1),
(3, 'bao 3', 48, NULL, 1, 'có 1 loại túi + tất cả ví', 1, NULL, '2022-03-11 21:49:34', '2022-03-11 21:49:34', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `c_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `c_parent_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `c_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `c_hot` tinyint(4) NOT NULL DEFAULT '0',
  `c_status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `c_name`, `c_parent_id`, `c_avatar`, `c_description`, `c_hot`, `c_status`, `created_at`, `updated_at`) VALUES
(1, 'Túi Xách Nữ', 0, NULL, NULL, 0, 1, '2022-03-11 20:31:32', '2022-03-11 20:33:05'),
(3, 'Ví Nhỏ Nữ', 0, NULL, NULL, 0, 1, '2022-03-11 20:32:29', '2022-03-11 20:33:17'),
(4, 'Balo Học Sinh', 0, NULL, NULL, 0, 1, '2022-03-14 20:39:05', '2022-03-14 20:39:05'),
(5, 'Balo', 0, NULL, NULL, 0, 1, '2022-03-15 12:55:44', '2022-03-15 12:55:44'),
(6, 'Cặp', 0, NULL, NULL, 0, 1, '2022-03-15 12:55:58', '2022-03-15 12:55:58'),
(7, 'Túi Du lịch', 0, NULL, NULL, 0, 1, '2022-03-15 18:21:44', '2022-03-15 18:21:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `images`
--

CREATE TABLE `images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `img_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img_product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_03_03_135745_create_categories_table', 1),
(6, '2022_03_03_135812_create_products_table', 1),
(7, '2022_03_03_140054_create_images_table', 1),
(8, '2022_03_03_140206_create_transactions_table', 1),
(9, '2022_03_03_140412_create_orders_table', 1),
(10, '2022_03_03_140951_create_transports_table', 1),
(11, '2022_03_04_144101_create_baos_table', 1),
(12, '2022_03_04_144121_create_transaction_histories_table', 1),
(13, '2022_03_06_061114_add_tst_transport_id_to_transactions_table', 1),
(14, '2022_03_06_064235_add_tst_total_paid_to_transactions_table', 1),
(15, '2022_03_06_095139_add_tst_total_transport_paid_to_transactions_table', 1),
(16, '2022_03_06_212645_add_column_to_transactions_table', 1),
(17, '2022_03_06_212934_add_b_transport_id_to_baos_table', 1),
(18, '2022_03_06_215938_add_od_note_to_orders_table', 1),
(19, '2022_03_08_152509_add_tst_lock_to_transactions_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `od_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `od_product_id` bigint(20) UNSIGNED NOT NULL,
  `od_sale` int(11) NOT NULL DEFAULT '0',
  `od_qty` int(11) NOT NULL DEFAULT '0',
  `od_price` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `od_note` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `od_transaction_id`, `od_product_id`, `od_sale`, `od_qty`, `od_price`, `created_at`, `updated_at`, `od_note`) VALUES
(1, 1, 4, 0, 43, 135000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(2, 1, 5, 0, 30, 123500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(3, 1, 3, 0, 25, 201000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(4, 1, 2, 0, 30, 127000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(5, 1, 1, 0, 25, 115500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(6, 1, 7, 0, 30, 71500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(7, 1, 9, 0, 20, 71500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(8, 1, 11, 0, 30, 71500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(9, 1, 13, 0, 30, 56500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(10, 1, 12, 0, 30, 58000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(11, 1, 8, 0, 30, 58000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(12, 1, 10, 0, 30, 62000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(13, 1, 6, 0, 30, 146500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(14, 2, 14, 0, 10, 58500, '2022-03-14 20:44:51', '2022-03-14 20:44:51', NULL),
(15, 2, 17, 0, 4, 72000, '2022-03-14 20:44:51', '2022-03-14 20:44:51', NULL),
(16, 2, 16, 0, 9, 152000, '2022-03-14 20:44:51', '2022-03-14 20:44:51', NULL),
(17, 2, 15, 0, 3, 165000, '2022-03-14 20:44:51', '2022-03-14 20:44:51', NULL),
(18, 3, 18, 0, 1, 190000, '2022-03-15 13:03:21', '2022-03-15 13:03:21', NULL),
(19, 3, 19, 0, 1, 220000, '2022-03-15 13:03:21', '2022-03-15 13:03:21', NULL),
(20, 3, 20, 0, 5, 139000, '2022-03-15 13:03:21', '2022-03-15 13:03:21', NULL),
(21, 3, 21, 0, 5, 139000, '2022-03-15 13:03:21', '2022-03-15 13:03:21', NULL),
(22, 3, 22, 0, 4, 159000, '2022-03-15 13:03:21', '2022-03-15 13:03:21', NULL),
(23, 3, 23, 0, 6, 149000, '2022-03-15 13:03:21', '2022-03-15 13:03:21', NULL),
(24, 4, 24, 0, 2, 90000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(25, 4, 25, 0, 2, 85000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(26, 4, 26, 0, 2, 73000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(27, 4, 27, 0, 2, 70000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(28, 4, 17, 0, 12, 70000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(29, 4, 28, 0, 5, 110000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(30, 4, 16, 0, 2, 155000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(31, 4, 29, 0, 4, 110000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(32, 4, 30, 0, 4, 135000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(33, 4, 31, 0, 4, 160000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(34, 4, 32, 0, 4, 140000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(35, 4, 33, 0, 5, 105000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(36, 4, 34, 0, 5, 105000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(37, 4, 41, 0, 7, 115000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(38, 4, 35, 0, 4, 110000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(39, 4, 44, 0, 4, 120000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(40, 4, 43, 0, 10, 120000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(41, 4, 36, 0, 3, 115000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(42, 4, 42, 0, 5, 115000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(43, 4, 37, 0, 3, 100000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(44, 4, 38, 0, 14, 60000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(45, 4, 15, 0, 7, 165000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(46, 4, 39, 0, 3, 130000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(47, 4, 40, 0, 3, 135000, '2022-03-15 20:15:08', '2022-03-15 20:15:08', NULL),
(48, 5, 45, 0, 7, 188000, '2022-03-16 14:27:23', '2022-03-16 14:27:23', NULL),
(49, 5, 46, 0, 17, 189000, '2022-03-16 14:27:23', '2022-03-16 14:27:23', NULL),
(50, 5, 47, 0, 11, 163000, '2022-03-16 14:27:23', '2022-03-16 14:27:23', NULL),
(51, 5, 16, 0, 8, 153000, '2022-03-16 14:27:23', '2022-03-16 14:27:23', NULL),
(52, 6, 55, 0, 2, 97000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(53, 6, 48, 0, 2, 177000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(54, 6, 17, 0, 6, 72000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(55, 6, 16, 0, 2, 157000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(56, 6, 50, 0, 1, 157000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(57, 6, 49, 0, 1, 157000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(58, 6, 22, 0, 3, 160000, '2022-03-16 15:14:16', '2022-03-16 15:14:57', NULL),
(59, 6, 51, 0, 3, 142000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(60, 6, 52, 0, 1, 142000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(61, 6, 53, 0, 1, 142000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(62, 6, 54, 0, 3, 147000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL),
(63, 6, 56, 0, 2, 142000, '2022-03-16 15:14:16', '2022-03-16 15:14:16', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pro_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pro_category_id` bigint(20) UNSIGNED NOT NULL,
  `pro_user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `pro_price` int(11) NOT NULL DEFAULT '0' COMMENT 'giá',
  `pro_sale` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'giảm giá',
  `pro_avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pro_hot` tinyint(4) NOT NULL DEFAULT '0',
  `pro_active` tinyint(4) NOT NULL DEFAULT '1',
  `pro_pay` int(11) NOT NULL DEFAULT '0' COMMENT 'số lượt mua',
  `pro_description` mediumtext COLLATE utf8mb4_unicode_ci,
  `pro_content` text COLLATE utf8mb4_unicode_ci,
  `pro_number` int(11) NOT NULL DEFAULT '0' COMMENT 'tổng số lượng',
  `pro_country` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'xuất xứ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `pro_name`, `pro_category_id`, `pro_user_id`, `pro_price`, `pro_sale`, `pro_avatar`, `pro_hot`, `pro_active`, `pro_pay`, `pro_description`, `pro_content`, `pro_number`, `pro_country`, `created_at`, `updated_at`) VALUES
(1, 'Túi 1123', 1, 1, 115000, 0, '2022-03-11__59492cc416e7d9b980f6.jpg', 0, 1, 25, '115500', '115000', 999999999, 1, '2022-03-11 20:40:57', '2022-03-13 17:36:04'),
(2, 'Túi 2126', 1, 1, 127000, 0, '2022-03-11__7e58dec3e4e02bbe72f1.jpg', 0, 1, 30, '127000', '127000', 999999999, 1, '2022-03-11 20:42:55', '2022-03-11 20:42:55'),
(3, 'Túi 3114', 1, 1, 201000, 0, '2022-03-11__069920021a21d57f8c30.jpg', 0, 1, 25, '201000', '201000', 999999999, 1, '2022-03-11 20:46:48', '2022-03-11 20:46:48'),
(4, 'Túi 5019', 1, 1, 135000, 0, '2022-03-11__c3d06f78555b9a05c34a.jpg', 0, 1, 43, '135000', '135000', 999999999, 1, '2022-03-11 20:51:29', '2022-03-11 20:51:29'),
(5, 'Túi 035203', 1, 1, 123500, 0, '2022-03-11__1b8c2838121bdd45840a.jpg', 0, 1, 30, '123500', '123500', 999999999, 1, '2022-03-11 20:53:01', '2022-03-11 20:53:01'),
(6, 'túi 030459', 1, 1, 146500, 0, '2022-03-11__d9b27db4469789c9d086.jpg', 0, 1, 30, '146500', '146500', 999999999, 1, '2022-03-11 21:04:27', '2022-03-11 21:04:27'),
(7, 'Ví 6301-003', 3, 1, 71500, 0, '2022-03-11__f81114f79dd4528a0bc5.jpg', 0, 1, 30, '71500', '71500', 999999999, 1, '2022-03-11 21:05:47', '2022-03-13 17:19:11'),
(8, 'ví 86125', 3, 1, 58000, 0, '2022-03-11__ed718d9204b1cbef92a0.jpg', 0, 1, 30, '58000', '58000', 999999999, 1, '2022-03-11 21:06:40', '2022-03-11 21:06:40'),
(9, 'Ví 3676-247', 3, 1, 71500, 0, '2022-03-11__550ac0f749d4868adfc5.jpg', 0, 1, 20, '71500', '71500', 999999999, 1, '2022-03-11 21:07:36', '2022-03-11 21:07:36'),
(10, 'ví 1135-003', 3, 1, 62000, 0, '2022-03-11__ebb70e4d876e4830117f.jpg', 0, 1, 30, '62000', '62000', 999999999, 1, '2022-03-11 21:08:53', '2022-03-11 21:08:53'),
(11, 'ví 3676-242', 3, 1, 71500, 0, '2022-03-11__9efaef09662aa974f03b.jpg', 0, 1, 30, '71500', '71500', 999999999, 1, '2022-03-11 21:09:41', '2022-03-11 21:09:41'),
(12, 'ví 005', 3, 1, 58000, 0, '2022-03-11__0fd7240cad2f62713b3e.jpg', 0, 1, 30, '58000', '58000', 999999999, 1, '2022-03-11 21:15:03', '2022-03-11 21:15:03'),
(13, 'ví 9101', 3, 1, 56500, 0, '2022-03-11__0f03945d4c7e8320da6f.jpg', 0, 1, 30, '56500', '56500', 99999999, 1, '2022-03-11 21:19:30', '2022-03-11 21:19:30'),
(14, 'balo học sinh lin', 4, 1, 58500, 0, '2022-03-14__ba4c21aa1a90d5ce8c81.jpg', 0, 1, 10, '58500', '58500', 999999999, 1, '2022-03-14 20:40:11', '2022-03-14 20:40:11'),
(15, 'túi 56-149', 1, 1, 165000, 0, '2022-03-14__76a90f583462fb3ca273.jpg', 0, 1, 10, '165000', '165000', 999999999, 1, '2022-03-14 20:40:55', '2022-03-14 20:40:55'),
(16, 'túi 9070', 1, 1, 157000, 0, '2022-03-14__51c58520be1a7144280b.jpg', 0, 1, 21, 'túi 9070', 'túi 9070', 999999999, 1, '2022-03-14 20:41:32', '2022-03-16 14:55:33'),
(17, 'ví 2856', 3, 1, 72000, 0, '2022-03-14__e72efbdbc0e10fbf56f0.jpg', 0, 1, 22, 'ví 2856', 'ví 2856', 999999999, 1, '2022-03-14 20:42:20', '2022-03-16 14:54:30'),
(18, 'balo vi tinh', 5, 1, 190000, 0, '2022-03-15__733c0f7b65beaae0f3af.jpg', 0, 1, 1, '190000', '190000', 1, 1, '2022-03-15 12:57:06', '2022-03-15 12:57:06'),
(19, 'cặp 9127', 6, 1, 220000, 0, '2022-03-15__0d7705346ff1a0aff9e0.jpg', 0, 1, 1, '220000', '220000', 1, 1, '2022-03-15 12:57:32', '2022-03-15 12:57:32'),
(20, 'balo f2', 5, 1, 139000, 0, '2022-03-15__1b95edbe877b4825116a.jpg', 0, 1, 5, '139000', '139000', 999999999, 1, '2022-03-15 12:58:42', '2022-03-15 12:58:42'),
(21, 'balo f3', 5, 1, 139000, 0, '2022-03-15__16c448912254ed0ab445.jpg', 0, 1, 5, '139000', '139000', 999999999, 1, '2022-03-15 12:59:23', '2022-03-15 12:59:23'),
(22, 'balo 886', 5, 1, 160000, 0, '2022-03-15__6d0e77e6c0230f7d5632.jpg', 0, 1, 7, 'balo 886', 'balo 886', 999999999, 1, '2022-03-15 13:00:48', '2022-03-16 15:01:45'),
(23, 'balo 907', 5, 1, 149000, 0, '2022-03-15__187ea8641ea1d1ff88b0.jpg', 0, 1, 6, '149000', '149000', 999999999, 1, '2022-03-15 13:01:27', '2022-03-15 13:01:27'),
(24, 'túi deo 1630', 1, 1, 90000, 0, '2022-03-15__5d962532f1f43eaa67e5.jpg', 0, 1, 2, '90000', '90000', 999999999, 1, '2022-03-15 18:15:16', '2022-03-15 18:15:16'),
(25, 'túi deo 1631', 1, 1, 85000, 0, '2022-03-15__5d962532f1f43eaa67e5.jpg', 0, 1, 2, '85000', '85000', 999999999, 1, '2022-03-15 18:15:39', '2022-03-15 18:15:39'),
(26, 'tui deo 1628', 1, 1, 73000, 0, '2022-03-15__5d962532f1f43eaa67e5.jpg', 0, 1, 2, '73000', '73000', 999999999, 1, '2022-03-15 18:16:03', '2022-03-15 18:16:03'),
(27, 'tui deo 1632', 1, 1, 70000, 0, '2022-03-15__5d962532f1f43eaa67e5.jpg', 0, 1, 2, '70000', '70000', 999999999, 1, '2022-03-15 18:16:25', '2022-03-15 18:16:25'),
(28, 'túi chống 010', 7, 1, 110000, 0, '2022-03-15__b65e79faad3c62623b2d.jpg', 0, 1, 5, '110000', '110000', 999999999, 1, '2022-03-15 18:22:59', '2022-03-15 18:22:59'),
(29, 'tui 9444', 1, 1, 110000, 0, '2022-03-15__ac9f643bb0fd7fa326ec.jpg', 0, 1, 4, '110000', '110000', 999999999, 1, '2022-03-15 18:31:31', '2022-03-15 18:31:31'),
(30, 'túi 210-2', 1, 1, 135000, 0, '2022-03-15__479c6c38b8fe77a02eef.jpg', 0, 1, 4, '135000', '135000', 999999999, 1, '2022-03-15 18:37:10', '2022-03-15 18:37:10'),
(31, 'Túi 035049', 1, 1, 160000, 0, '2022-03-15__0f7089d45d12924ccb03.jpg', 0, 1, 4, '160000', '160000', 999999999, 1, '2022-03-15 18:39:12', '2022-03-15 18:39:12'),
(32, 'Túi 08 dù', 1, 1, 140000, 0, '2022-03-15__95e5974143878cd9d596.jpg', 0, 1, 4, '140000', '140000', 999999999, 1, '2022-03-15 18:40:58', '2022-03-15 18:40:58'),
(33, 'balo 1653', 5, 1, 105000, 0, '2022-03-15__a36dacc9780fb751ee1e.jpg', 0, 1, 5, '105000', '105000', 999999999, 1, '2022-03-15 18:44:02', '2022-03-15 18:44:02'),
(34, 'balo 1656', 5, 1, 105000, 0, '2022-03-15__7692fb362ff0e0aeb9e1.jpg', 0, 1, 5, 'balo 1656', 'balo 1656', 999999999, 1, '2022-03-15 18:44:33', '2022-03-15 18:44:33'),
(35, 'balo 2 đáy', 5, 1, 110000, 0, '2022-03-15__321913bdc77b0825516a.jpg', 0, 1, 4, 'balo 2 đáy', 'balo 2 đáy', 999999999, 1, '2022-03-15 18:47:51', '2022-03-15 18:47:51'),
(36, 'balo 92608', 5, 1, 115000, 0, '2022-03-15__da8ae42e30e8ffb6a6f9.jpg', 0, 1, 3, 'balo 92605', 'balo 92605', 999999999, 1, '2022-03-15 18:50:11', '2022-03-15 20:05:37'),
(37, 'balo 2639', 5, 1, 100000, 0, '2022-03-15__64e6404294845bda0295.jpg', 0, 1, 3, 'balo 2639', 'balo 2639', 999999999, 1, '2022-03-15 18:53:40', '2022-03-15 18:53:40'),
(38, 'balo nhỏ', 4, 1, 60000, 0, '2022-03-15__49c9bd6d69aba6f5ffba.jpg', 0, 1, 14, 'balo nhỏ', 'balo nhỏ', 999999999, 1, '2022-03-15 18:55:22', '2022-03-15 18:55:22'),
(39, 'balo b212', 5, 1, 130000, 0, '2022-03-15__2ac9106dc4ab0bf552ba.jpg', 0, 1, 3, 'balo b212', 'balo b212', 999999999, 1, '2022-03-15 18:57:43', '2022-03-15 18:57:43'),
(40, 'balo b6983', 5, 1, 135000, 0, '2022-03-15__3bf23c56e89027ce7e81.jpg', 0, 1, 3, 'balo b6983', 'balo b6983', 999999999, 1, '2022-03-15 18:59:03', '2022-03-15 18:59:03'),
(41, 'balo 1877', 5, 1, 115000, 0, '2022-03-15__7144ace07826b778ee37.jpg', 0, 1, 7, 'balo 1877', 'balo 1877', 999999999, 1, '2022-03-15 19:52:25', '2022-03-15 19:52:25'),
(42, 'balo 822', 4, 1, 115000, 0, '2022-03-15__47ccc9b3897a46241f6b.jpg', 0, 1, 5, 'balo 822', 'balo 822', 99999999, 1, '2022-03-15 19:54:44', '2022-03-15 19:54:44'),
(43, 'balo ảnh', 4, 1, 120000, 0, '2022-03-15__aed3b3ab3c62f33caa73.jpg', 0, 1, 10, 'balo ảnh', 'balo ảnh', 999999999, 1, '2022-03-15 19:57:59', '2022-03-15 19:57:59'),
(44, 'balo 1883', 5, 1, 120000, 0, '2022-03-15__bc4bdc439d8a52d40b9b.jpg', 0, 1, 4, 'balo 1883', 'balo 1883', 999999999, 1, '2022-03-15 20:12:43', '2022-03-15 20:12:54'),
(45, 'tui 99001', 1, 1, 188000, 0, '2022-03-16__7bfe0797e05e2f00764f.jpg', 0, 1, 7, 'tui 99001', 'tui 99001', 999999999, 1, '2022-03-16 14:15:59', '2022-03-16 14:15:59'),
(46, 'tui 040316', 1, 1, 189000, 0, '2022-03-16__787db20d55c49a9ac3d5.jpg', 0, 1, 17, 'tui 040316', 'tui 040316', 999999999, 1, '2022-03-16 14:18:05', '2022-03-16 14:18:05'),
(47, 'tui 01980266', 1, 1, 163000, 0, '2022-03-16__6624945a7393bccde582.jpg', 0, 1, 11, 'tui 01980266', 'tui 01980266', 999999999, 1, '2022-03-16 14:19:10', '2022-03-16 14:19:10'),
(48, 'tui 12250870', 1, 1, 177000, 0, '2022-03-16__3f6304f3533f9c61c52e.jpg', 0, 1, 2, 'tui 12250870', 'tui 12250870', 999999999, 1, '2022-03-16 14:59:56', '2022-03-16 14:59:56'),
(49, 'balo 1937-2', 1, 1, 157000, 0, '2022-03-16__9525924bc68709d95096.jpg', 0, 1, 1, 'balo 1937-2', 'balo 1937-2', 999999999, 1, '2022-03-16 15:00:26', '2022-03-16 15:00:26'),
(50, 'balo 1937-3', 1, 1, 157000, 0, '2022-03-16__9525924bc68709d95096.jpg', 0, 1, 1, 'balo 1937-3', 'balo 1937-3', 99999999, 1, '2022-03-16 15:00:46', '2022-03-16 15:00:46'),
(51, 'balo 2530-', 5, 1, 142000, 0, '2022-03-16__8c0ebd46e98a26d47f9b.jpg', 0, 1, 3, 'balo 2530-', 'balo 2530-', 999999999, 1, '2022-03-16 15:03:11', '2022-03-16 15:03:11'),
(52, 'balo f90', 1, 1, 142000, 0, '2022-03-16__41794d0a19c6d6988fd7.jpg', 0, 1, 1, 'balo f90', 'balo f90', 99999999, 1, '2022-03-16 15:06:29', '2022-03-16 15:06:29'),
(53, 'balo f689', 5, 1, 142000, 0, '2022-03-16__41794d0a19c6d6988fd7.jpg', 0, 1, 1, 'balo f689', 'balo f689', 999999999, 1, '2022-03-16 15:06:57', '2022-03-16 15:06:57'),
(54, 'tui 9824', 1, 1, 147000, 0, '2022-03-16__c877060852c49d9ac4d5.jpg', 0, 1, 3, 'tui 9824', 'tui 9824', 999999999, 1, '2022-03-16 15:07:23', '2022-03-16 15:07:23'),
(55, 'balo but', 4, 1, 97000, 0, '2022-03-16__38646af63d3af264ab2b.jpg', 0, 1, 2, 'balo but', 'balo but', 99999999, 1, '2022-03-16 15:10:08', '2022-03-16 15:10:08'),
(56, 'tui 0252352', 1, 1, 142000, 0, '2022-03-16__5a3c8342d78e18d0419f.jpg', 0, 1, 2, 'tui 0252352', 'tui 0252352', 99999999, 1, '2022-03-16 15:13:57', '2022-03-16 15:13:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tst_user_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `tst_total_money` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `tst_total_products` int(11) NOT NULL DEFAULT '0',
  `tst_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tst_status` tinyint(4) NOT NULL DEFAULT '1',
  `tst_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 thanh toan thuong, 2 la online',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tst_transport_id` bigint(20) UNSIGNED NOT NULL DEFAULT '1',
  `tst_total_paid` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'tổng tiền khách trả tiền hàng',
  `total_transport_paid` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'tổng tiền khách trả tiền vận chuyển',
  `tst_order_date` date NOT NULL DEFAULT '2022-03-11' COMMENT 'ngày đặt hàng',
  `tst_expected_date` date NOT NULL DEFAULT '2022-03-11' COMMENT 'ngày dự kiến giao hàng thành công',
  `tst_deposit` int(11) NOT NULL DEFAULT '0' COMMENT 'số tiền khách đặt cọc',
  `tst_lock` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 là khóa, 0 là mở khóa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `transactions`
--

INSERT INTO `transactions` (`id`, `tst_user_id`, `tst_total_money`, `tst_total_products`, `tst_note`, `tst_status`, `tst_type`, `created_at`, `updated_at`, `tst_transport_id`, `tst_total_paid`, `total_transport_paid`, `tst_order_date`, `tst_expected_date`, `tst_deposit`, `tst_lock`) VALUES
(1, 2, 38382500, 383, 'Chành xe\r\n1420 võ văn kiệt -phường 1 -quận 6 -HCM\r\nXe quốc anh \r\n090 9550444', 2, 1, '2022-03-11 21:46:12', '2022-03-11 21:50:34', 1, 0, 0, '2022-03-03', '2022-03-20', 5000000, 1),
(2, 3, 2736000, 26, 'đây là khách lấy lẻ.\r\nsố lượng ít.\r\ntổng hóa đơn 2236\r\nlãi 170k', 2, 1, '2022-03-14 20:44:51', '2022-03-14 21:02:51', 1, 500000, 0, '2022-03-21', '2022-03-21', 0, 1),
(3, 4, 3330000, 22, 'lãi 225', 2, 1, '2022-03-15 13:03:21', '2022-03-15 13:13:56', 1, 300000, 0, '2022-03-22', '2022-03-22', 0, 1),
(4, 5, 12501000, 116, 'lai 700k', 2, 1, '2022-03-15 20:15:08', '2022-03-15 20:19:27', 1, 500000, 0, '2022-03-22', '2022-03-22', 0, 1),
(5, 6, 7546000, 43, 'lãi 330', 2, 1, '2022-03-16 14:27:23', '2022-03-16 14:29:43', 1, 500000, 0, '2022-03-23', '2022-03-23', 0, 1),
(6, 7, 3523000, 27, 'lai 250k', 2, 1, '2022-03-16 15:14:16', '2022-03-16 15:18:03', 1, 300000, 0, '2022-03-23', '2022-03-23', 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transaction_histories`
--

CREATE TABLE `transaction_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `th_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `th_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `transaction_histories`
--

INSERT INTO `transaction_histories` (`id`, `th_transaction_id`, `th_content`, `created_at`, `updated_at`) VALUES
(1, 1, 'tạo đơn hàng thành công:  Tổng số sản phẩm: 383,\n                    Tổng số tiền: 38.370.000, Số tiền đặt cọc: 5.000.000, Tổng số bao: 0', '2022-03-11 21:46:12', '2022-03-11 21:46:12'),
(2, 1, 'Cập nhật: \n số lượng bao: 0 -> 3 \n số cân: 0 -> 133 ', '2022-03-11 21:49:34', '2022-03-11 21:49:34'),
(3, 1, 'Đã chuyển đơn hàng từ Tiếp nhận -> Đang vận chuyển', '2022-03-11 21:50:32', '2022-03-11 21:50:32'),
(4, 2, 'tạo đơn hàng thành công:  Tổng số sản phẩm: 26,\n                    Tổng số tiền: 2.736.000, Số tiền đặt cọc: 500.000, Tổng số bao: 0', '2022-03-14 20:44:51', '2022-03-14 20:44:51'),
(5, 2, 'Convert tiền cọc: \n Tiền cọc: 500.000 đ \n/\n                Số tiền còn nợ = (số tiền nợ cuối cũ - số tiền cọc): 2.736.000 - 500.000 = 2.236.000 /\n                Tổng số tiền hàng đã trả: 500.000\n                (còn nợ tổng: 2.236.000)', '2022-03-14 20:45:13', '2022-03-14 20:45:13'),
(6, 2, 'Đã chuyển đơn hàng từ Tiếp nhận -> Đang vận chuyển', '2022-03-14 20:45:42', '2022-03-14 20:45:42'),
(7, 3, 'tạo đơn hàng thành công:  Tổng số sản phẩm: 22,\n                    Tổng số tiền: 3.330.000, Số tiền đặt cọc: 300.000, Tổng số bao: 0', '2022-03-15 13:03:21', '2022-03-15 13:03:21'),
(8, 3, 'Convert tiền cọc: \n Tiền cọc: 300.000 đ \n/\n                Số tiền còn nợ = (số tiền nợ cuối cũ - số tiền cọc): 3.330.000 - 300.000 = 3.030.000 /\n                Tổng số tiền hàng đã trả: 300.000\n                (còn nợ tổng: 3.030.000)', '2022-03-15 13:03:31', '2022-03-15 13:03:31'),
(9, 3, 'Đã chuyển đơn hàng từ Tiếp nhận -> Đang vận chuyển', '2022-03-15 13:03:43', '2022-03-15 13:03:43'),
(10, 4, 'tạo đơn hàng thành công:  Tổng số sản phẩm: 116,\n                    Tổng số tiền: 12.501.000, Số tiền đặt cọc: 500.000, Tổng số bao: 0', '2022-03-15 20:15:08', '2022-03-15 20:15:08'),
(11, 4, 'Convert tiền cọc: \n Tiền cọc: 500.000 đ \n/\n                Số tiền còn nợ = (số tiền nợ cuối cũ - số tiền cọc): 12.501.000 - 500.000 = 12.001.000 /\n                Tổng số tiền hàng đã trả: 500.000\n                (còn nợ tổng: 12.001.000)', '2022-03-15 20:15:32', '2022-03-15 20:15:32'),
(12, 4, 'Đã chuyển đơn hàng từ Tiếp nhận -> Đang vận chuyển', '2022-03-15 20:15:39', '2022-03-15 20:15:39'),
(13, 5, 'tạo đơn hàng thành công:  Tổng số sản phẩm: 43,\n                    Tổng số tiền: 7.546.000, Số tiền đặt cọc: 500.000, Tổng số bao: 0', '2022-03-16 14:27:23', '2022-03-16 14:27:23'),
(14, 5, 'Convert tiền cọc: \n Tiền cọc: 500.000 đ \n/\n                Số tiền còn nợ = (số tiền nợ cuối cũ - số tiền cọc): 7.546.000 - 500.000 = 7.046.000 /\n                Tổng số tiền hàng đã trả: 500.000\n                (còn nợ tổng: 7.046.000)', '2022-03-16 14:29:01', '2022-03-16 14:29:01'),
(15, 5, 'Đã chuyển đơn hàng từ Tiếp nhận -> Đang vận chuyển', '2022-03-16 14:29:35', '2022-03-16 14:29:35'),
(16, 6, 'tạo đơn hàng thành công:  Tổng số sản phẩm: 184,\n                    Tổng số tiền: 28.643.000, Số tiền đặt cọc: 300.000, Tổng số bao: 0', '2022-03-16 15:14:16', '2022-03-16 15:14:16'),
(17, 6, 'Đã chuyển đơn hàng từ Tiếp nhận -> Đang vận chuyển', '2022-03-16 15:14:25', '2022-03-16 15:14:25'),
(18, 6, 'Cập nhật: \n số lượng sp: 184 -> 27 \n / Tiền: 28.643.000 -> 3.523.000 ', '2022-03-16 15:14:57', '2022-03-16 15:14:57'),
(19, 6, 'Convert tiền cọc: \n Tiền cọc: 300.000 đ \n/\n                Số tiền còn nợ = (số tiền nợ cuối cũ - số tiền cọc): 3.523.000 - 300.000 = 3.223.000 /\n                Tổng số tiền hàng đã trả: 300.000\n                (còn nợ tổng: 3.223.000)', '2022-03-16 15:15:12', '2022-03-16 15:15:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transports`
--

CREATE TABLE `transports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tp_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tp_fee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'phí vận chuyển / 1kg',
  `tp_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `transports`
--

INSERT INTO `transports` (`id`, `tp_name`, `tp_fee`, `tp_description`, `created_at`, `updated_at`) VALUES
(1, '[ Thường ] Trung Quốc -> Sài Gòn', '22000', '22000', '2022-03-11 21:47:34', '2022-03-11 21:47:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `address`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Supper', 'admin@gmail.com', '2022-03-11 20:29:30', '$2y$10$NaT1P3IMoTRinz2dv5iyDe3YN4m0A1oWMnADabr4TGC0Py7HK7GXe', '0377708868', NULL, NULL, NULL, NULL, NULL),
(2, 'Minh Tâm Bình Phước', 'minhtam@gmail.com', NULL, '$2y$10$gy6bGnHxPpLkt1uwFF.RwuWVX.yx.swU9tf9A8IS8SX19/czMBWUO', '0946869998', 'Bình Phước', NULL, NULL, '2022-03-11 20:31:09', '2022-03-11 20:31:09'),
(3, 'linh nguyễn', 'linh@gmail.com', NULL, '$2y$10$CcWdz1hhMp4KLowPodpxte2uXmnScHj5VXejHlxjFZTHm5nJmebti', '0979434820', '133 quang trung phường lê hồng phong thành phố quảng ngãi', NULL, NULL, '2022-03-14 20:43:22', '2022-03-14 20:43:22'),
(4, 'Lê xinh shop', 'lexinh@gmail.com', NULL, '$2y$10$rYa8CD1VjMvMStKky3s6tO/4bRJ/D4lJJeax.X2SyM5JE.Aw4F7ny', '0941946004', 'Nghĩa thuận - huyện tư nghĩa - Tỉnh quảng ngãi', NULL, NULL, '2022-03-15 13:02:14', '2022-03-15 13:02:14'),
(5, 'Phương Gấu', 'phuong@gmail.com', NULL, '$2y$10$U4.gYsFY4eMAsdNfa2NEM.YYAjniL6gK2OjPfvDGmWbr2ZfNpzave', '0877239336', 'Sn24/ngõ 29 Hùng vương.  Đồng Tâm. Vĩnh Yên. Vĩnh Phúc', NULL, NULL, '2022-03-15 18:13:45', '2022-03-15 18:13:45'),
(6, 'huyền phú thọ', 'huyenpt@gmail.com', NULL, '$2y$10$wSHRXG24fe2fn88/ScNAEegPFnZaS9YAx0dijOiC.JC.0zpIfvVoy', '0326261062', 'khu 3 sông lô, việt trì, phú thọ', NULL, NULL, '2022-03-16 14:20:37', '2022-03-16 14:20:51'),
(7, 'Thanh thủy', 'thanhthuy1@gmail.com', NULL, '$2y$10$1d1Ipm4aecgQ22Itb0r2COEhFvS2eQfKQ51VpXnXkZHwXFqM6otXy', '0937604675', 'số 382 đương dt 766 thôn 2 đức hạnh đức linh bình thuận', NULL, NULL, '2022-03-16 14:50:04', '2022-03-16 14:50:04');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `baos`
--
ALTER TABLE `baos`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_c_name_unique` (`c_name`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_pro_name_unique` (`pro_name`);

--
-- Chỉ mục cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_tst_user_id_index` (`tst_user_id`);

--
-- Chỉ mục cho bảng `transaction_histories`
--
ALTER TABLE `transaction_histories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `transports`
--
ALTER TABLE `transports`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `baos`
--
ALTER TABLE `baos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT cho bảng `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `transaction_histories`
--
ALTER TABLE `transaction_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `transports`
--
ALTER TABLE `transports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
