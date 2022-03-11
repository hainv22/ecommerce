-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: mysql:3306
-- Thời gian đã tạo: Th3 11, 2022 lúc 03:18 PM
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
(3, 'Ví Nhỏ Nữ', 0, NULL, NULL, 0, 1, '2022-03-11 20:32:29', '2022-03-11 20:33:17');

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
(5, 1, 1, 0, 25, 115000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(6, 1, 7, 0, 30, 71500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(7, 1, 9, 0, 20, 71500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(8, 1, 11, 0, 30, 71500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(9, 1, 13, 0, 30, 56500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(10, 1, 12, 0, 30, 58000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(11, 1, 8, 0, 30, 58000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(12, 1, 10, 0, 30, 62000, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL),
(13, 1, 6, 0, 30, 146500, '2022-03-11 21:46:12', '2022-03-11 21:46:12', NULL);

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
(1, 'Túi 1123', 1, 1, 115000, 0, '2022-03-11__59492cc416e7d9b980f6.jpg', 0, 1, 25, '115000', '115000', 999999999, 1, '2022-03-11 20:40:57', '2022-03-11 20:40:57'),
(2, 'Túi 2126', 1, 1, 127000, 0, '2022-03-11__7e58dec3e4e02bbe72f1.jpg', 0, 1, 30, '127000', '127000', 999999999, 1, '2022-03-11 20:42:55', '2022-03-11 20:42:55'),
(3, 'Túi 3114', 1, 1, 201000, 0, '2022-03-11__069920021a21d57f8c30.jpg', 0, 1, 25, '201000', '201000', 999999999, 1, '2022-03-11 20:46:48', '2022-03-11 20:46:48'),
(4, 'Túi 5019', 1, 1, 135000, 0, '2022-03-11__c3d06f78555b9a05c34a.jpg', 0, 1, 43, '135000', '135000', 999999999, 1, '2022-03-11 20:51:29', '2022-03-11 20:51:29'),
(5, 'Túi 035203', 1, 1, 123500, 0, '2022-03-11__1b8c2838121bdd45840a.jpg', 0, 1, 30, '123500', '123500', 999999999, 1, '2022-03-11 20:53:01', '2022-03-11 20:53:01'),
(6, 'túi 030459', 1, 1, 146500, 0, '2022-03-11__d9b27db4469789c9d086.jpg', 0, 1, 30, '146500', '146500', 999999999, 1, '2022-03-11 21:04:27', '2022-03-11 21:04:27'),
(7, 'Ví 63001-003', 3, 1, 71500, 0, '2022-03-11__f81114f79dd4528a0bc5.jpg', 0, 1, 30, '71500', '71500', 999999999, 1, '2022-03-11 21:05:47', '2022-03-11 21:05:47'),
(8, 'ví 86125', 3, 1, 58000, 0, '2022-03-11__ed718d9204b1cbef92a0.jpg', 0, 1, 30, '58000', '58000', 999999999, 1, '2022-03-11 21:06:40', '2022-03-11 21:06:40'),
(9, 'Ví 3676-247', 3, 1, 71500, 0, '2022-03-11__550ac0f749d4868adfc5.jpg', 0, 1, 20, '71500', '71500', 999999999, 1, '2022-03-11 21:07:36', '2022-03-11 21:07:36'),
(10, 'ví 1135-003', 3, 1, 62000, 0, '2022-03-11__ebb70e4d876e4830117f.jpg', 0, 1, 30, '62000', '62000', 999999999, 1, '2022-03-11 21:08:53', '2022-03-11 21:08:53'),
(11, 'ví 3676-242', 3, 1, 71500, 0, '2022-03-11__9efaef09662aa974f03b.jpg', 0, 1, 30, '71500', '71500', 999999999, 1, '2022-03-11 21:09:41', '2022-03-11 21:09:41'),
(12, 'ví 005', 3, 1, 58000, 0, '2022-03-11__0fd7240cad2f62713b3e.jpg', 0, 1, 30, '58000', '58000', 999999999, 1, '2022-03-11 21:15:03', '2022-03-11 21:15:03'),
(13, 'ví 9101', 3, 1, 56500, 0, '2022-03-11__0f03945d4c7e8320da6f.jpg', 0, 1, 30, '56500', '56500', 99999999, 1, '2022-03-11 21:19:30', '2022-03-11 21:19:30');

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
(1, 2, 38370000, 383, 'Chành xe\r\n1420 võ văn kiệt -phường 1 -quận 6 -HCM\r\nXe quốc anh \r\n090 9550444', 2, 1, '2022-03-11 21:46:12', '2022-03-11 21:50:34', 1, 0, 0, '2022-03-03', '2022-03-20', 5000000, 1);

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
(3, 1, 'Đã chuyển đơn hàng từ Tiếp nhận -> Đang vận chuyển', '2022-03-11 21:50:32', '2022-03-11 21:50:32');

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
(2, 'Minh Tâm Bình Phước', 'minhtam@gmail.com', NULL, '$2y$10$gy6bGnHxPpLkt1uwFF.RwuWVX.yx.swU9tf9A8IS8SX19/czMBWUO', '0946869998', 'Bình Phước', NULL, NULL, '2022-03-11 20:31:09', '2022-03-11 20:31:09');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `transaction_histories`
--
ALTER TABLE `transaction_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `transports`
--
ALTER TABLE `transports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
