-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 11, 2025 at 08:49 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `update_at`) VALUES
(7, 'เครื่องดื่ม', '2025-01-09 09:45:47'),
(8, 'อื่นๆ', '2025-01-09 09:45:52'),
(9, 'ทั่วไป', '2025-01-09 09:45:56'),
(10, 'IT', '2025-01-10 03:47:25'),
(11, '-', '2025-01-11 06:31:27');

-- --------------------------------------------------------

--
-- Table structure for table `customer_money`
--

CREATE TABLE `customer_money` (
  `id` bigint(20) NOT NULL,
  `bill_id` varchar(50) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT 0.00,
  `return_money` decimal(10,2) NOT NULL DEFAULT 0.00,
  `update_at` datetime DEFAULT current_timestamp(),
  `user_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_money`
--

INSERT INTO `customer_money` (`id`, `bill_id`, `money`, `return_money`, `update_at`, `user_id`) VALUES
(1, '1736497086', '67900.00', '0.00', '2025-01-10 08:18:06', 1),
(2, '1736497123', '50000.00', '0.00', '2025-01-10 08:18:43', 1),
(3, '1736497139', '51890.00', '0.00', '2025-01-10 08:18:59', 1),
(4, '1736500301', '25000.00', '0.00', '2025-01-10 09:11:41', 1),
(5, '1736500394', '25000.00', '0.00', '2025-01-10 09:13:14', 1),
(6, '1736500440', '51890.00', '0.00', '2025-01-10 09:14:00', 1),
(7, '1736500478', '25000.00', '0.00', '2025-01-10 09:14:38', 1),
(8, '1736500562', '76890.00', '0.00', '2025-01-10 09:16:02', 1),
(9, '1736500616', '44950.00', '0.00', '2025-01-10 09:16:56', 1),
(10, '1736500642', '25000.00', '0.00', '2025-01-10 09:17:22', 1),
(11, '1736500729', '108990.00', '0.00', '2025-01-10 09:18:49', 1),
(12, '1736506310', '58990.00', '0.00', '2025-01-10 10:51:50', 1),
(13, '1736506361', '25000.00', '0.00', '2025-01-10 10:52:41', 1),
(14, '1736506491', '110800.00', '0.00', '2025-01-10 10:54:51', 1),
(15, '1736520674', '50000.00', '0.00', '2025-01-10 14:51:14', 2),
(16, '1736576111', '80000.00', '1000.00', '2025-01-11 06:15:11', 2);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `member_name`, `phone`, `update_at`) VALUES
(1, 'ทั่วไป', '0', '2025-01-10 03:52:08'),
(2, 'พสิษฐ์ ยอดสร้อย', '0927672000', '2025-01-09 09:45:35'),
(3, '-', '-', '2025-01-11 06:31:19');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` bigint(20) NOT NULL,
  `product_code` varchar(50) DEFAULT NULL,
  `product_name` varchar(100) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_qty` decimal(10,0) NOT NULL,
  `update_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_code`, `product_name`, `category_id`, `product_price`, `product_qty`, `update_at`) VALUES
(1, '1736417100', 'iPhone 14', 7, '25000.00', '82', '2025-01-09 10:05:11'),
(3, '1736480846', 'iPad', 10, '17900.00', '95', '2025-01-10 03:47:37'),
(4, '1736480964', 'Airpod', 10, '8990.00', '90', '2025-01-10 03:49:40'),
(5, '1736575950', 'MacBook Air M2', 10, '39990.00', '8', '2025-01-11 06:12:47');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bill_id` varchar(50) DEFAULT NULL,
  `is_type` varchar(50) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `member_id` bigint(20) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty` decimal(10,0) NOT NULL DEFAULT 0,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `bill_id`, `is_type`, `product_code`, `member_id`, `price`, `qty`, `total`, `discount`, `transaction_date`, `user_id`) VALUES
(1, '1736497086', 'ขาย', '1736417100', 1, '25000.00', '2', '50000.00', '0.00', '2025-01-10 15:17:21', 1),
(2, '1736497086', 'ขาย', '1736480846', 1, '17900.00', '1', '17900.00', '0.00', '2025-01-10 15:17:21', 1),
(3, '1736497123', 'ขาย', '1736417100', 1, '25000.00', '2', '50000.00', '0.00', '2025-01-10 15:18:35', 1),
(4, '1736497139', 'ขาย', '1736417100', 1, '25000.00', '1', '25000.00', '0.00', '2025-01-10 15:18:43', 1),
(5, '1736497139', 'ขาย', '1736480846', 1, '17900.00', '1', '17900.00', '0.00', '2025-01-10 15:18:43', 1),
(6, '1736497139', 'ขาย', '1736480964', 1, '8990.00', '1', '8990.00', '0.00', '2025-01-10 15:18:43', 1),
(7, '1736500729', 'ขาย', '1736417100', 1, '25000.00', '4', '100000.00', '0.00', '2025-01-10 16:18:39', 1),
(8, '1736500729', 'ขาย', '1736480964', 1, '8990.00', '1', '8990.00', '0.00', '2025-01-10 16:18:39', 1),
(9, '1736502464', 'นำเข้า', '1736417100', 0, '25000.00', '4', '100000.00', '0.00', '2025-01-10 16:47:35', 1),
(10, '1736506310', 'เบิก', '1736417100', 1, '25000.00', '2', '50000.00', '0.00', '2025-01-10 17:51:35', 1),
(11, '1736506310', 'เบิก', '1736480964', 1, '8990.00', '1', '8990.00', '0.00', '2025-01-10 17:51:35', 1),
(12, '1736506361', 'เบิก', '1736417100', 1, '25000.00', '1', '25000.00', '0.00', '2025-01-10 17:51:50', 1),
(13, '1736506491', 'เบิก', '1736417100', 1, '25000.00', '3', '75000.00', '0.00', '2025-01-10 17:54:40', 1),
(14, '1736506491', 'เบิก', '1736480846', 1, '17900.00', '2', '35800.00', '0.00', '2025-01-10 17:54:40', 1),
(15, '1736520674', 'เบิก', '1736417100', 1, '25000.00', '2', '50000.00', '0.00', '2025-01-10 21:48:48', 2),
(16, '1736576084', 'นำเข้า', '1736575950', 0, '39990.00', '1', '39990.00', '0.00', '2025-01-11 13:13:52', 2),
(17, '1736576111', 'เบิก', '1736575950', 1, '39990.00', '2', '79000.00', '980.00', '2025-01-11 13:14:54', 2);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_temp`
--

CREATE TABLE `transaction_temp` (
  `is_type` varchar(50) NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `member_id` bigint(20) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty` decimal(10,0) NOT NULL DEFAULT 0,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 0,
  `update_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `is_active`, `update_at`) VALUES
(1, 'admin', 'YWRtaW4=', 0, '2025-01-09 07:17:12'),
(2, 'nook', 'MjkwOQ==', 0, '2025-01-09 08:26:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `customer_money`
--
ALTER TABLE `customer_money`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `member_name` (`member_name`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_name` (`product_name`),
  ADD UNIQUE KEY `product_code` (`product_code`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer_money`
--
ALTER TABLE `customer_money`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
