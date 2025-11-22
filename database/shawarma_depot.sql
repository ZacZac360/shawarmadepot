-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 06:39 PM
-- Server version: 8.0.44
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shawarma_depot`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('owner','staff') COLLATE utf8mb4_general_ci DEFAULT 'staff',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `full_name`, `role`, `created_at`, `updated_at`) VALUES
(2, 'Zai', '$2y$10$yrHK4Sb1sw0a4q06Z22SJ./vBft9LVS6jmkAdqSenetcB0tMEVnoW', 'Site Administrator', 'owner', '2025-11-23 00:54:34', '2025-11-23 00:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_code` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `customer_messenger` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_email` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fulfillment_mode` enum('delivery','pickup') COLLATE utf8mb4_general_ci NOT NULL,
  `delivery_subdivision` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `delivery_address` text COLLATE utf8mb4_general_ci,
  `delivery_landmark` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_method` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `order_notes` text COLLATE utf8mb4_general_ci,
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `cart_json` json DEFAULT NULL,
  `status` enum('pending','confirmed','preparing','out_for_delivery','completed','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `cancel_requested` tinyint(1) NOT NULL DEFAULT '0',
  `cancel_reason` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cancel_requested_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `customer_name`, `customer_phone`, `customer_messenger`, `customer_email`, `fulfillment_mode`, `delivery_subdivision`, `delivery_address`, `delivery_landmark`, `payment_method`, `order_notes`, `subtotal`, `delivery_fee`, `total_amount`, `cart_json`, `status`, `cancel_requested`, `cancel_reason`, `cancel_requested_at`, `created_at`) VALUES
(1, 'DY37JLS867', 'Zyrus Crispino', '09293811134', '', '', 'pickup', '', 'Block 25 lot 13', 'White Gate Mitsubishi Mirage', 'cod', '', 300.00, 0.00, 300.00, '[{\"key\": \"shawarma-wrap|LARGE Buy 1 Take 1|Chicken|LARGE Buy 1 Take 1|spicy|extra-veggies\", \"qty\": 1, \"name\": \"Shawarma Wrap\", \"summary\": \"LARGE Buy 1 Take 1 • Chicken • Spicy • 1 extra\", \"unitPrice\": 185}, {\"key\": \"special-all-meat-wrap|Large|Chicken|Large|spicy|extra-veggies\", \"qty\": 1, \"name\": \"All Meat Wrap\", \"summary\": \"Chicken • Large • Spicy • 1 extra\", \"unitPrice\": 115}]', 'cancelled', 0, NULL, NULL, '2025-11-22 21:20:23'),
(2, 'ZHQFJTBUAY', 'Zyrus Crispino', '09293811134', '', '', 'delivery', 'classic', 'Blk 25 Lot 13', 'White gate with white tarp on half.', 'cod', '', 190.00, 30.00, 220.00, '[{\"key\": \"premium-steak-double-cheese|Large||Large|not-spicy|extra-veggies,extra-garlic-sauce\", \"qty\": 1, \"name\": \"Double Cheese Premium Steak & Fries\", \"summary\": \"Large • 2 extras (Veggies, Garlic Sauce)\", \"unitPrice\": 190}]', 'cancelled', 1, 'test', '2025-11-22 23:28:22', '2025-11-22 22:22:27'),
(3, 'T44UPYPCMV', 'Zyrus Crispino', '09293811134', '', '', 'pickup', '', '', '', 'cod', '', 128.00, 0.00, 128.00, '[{\"key\": \"coated-fries|Solo||Solo|spicy|extra-meat\", \"qty\": 1, \"name\": \"Coated Fries\", \"summary\": \"Solo • Spicy • 1 extra (Meat)\", \"unitPrice\": 128}]', 'cancelled', 1, 'Test', '2025-11-22 23:35:34', '2025-11-22 23:33:51'),
(4, 'GVVN837U7L', 'Zyrus Crispino', '09293811134', '', '', 'pickup', '', '', '', 'cod', '', 165.00, 0.00, 165.00, '[{\"key\": \"shawarma-wrap|LARGE Buy 1 Take 1|Beef|LARGE Buy 1 Take 1|not-spicy|\", \"qty\": 1, \"name\": \"Shawarma Wrap\", \"summary\": \"LARGE Buy 1 Take 1 • Beef\", \"unitPrice\": 165}]', 'completed', 0, NULL, NULL, '2025-11-23 00:31:46');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_key` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `product_name` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `summary` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `line_total` decimal(10,2) NOT NULL,
  `raw_json` json DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_key`, `product_name`, `summary`, `unit_price`, `quantity`, `line_total`, `raw_json`, `created_at`) VALUES
(1, 3, 'coated-fries|Solo||Solo|spicy|extra-meat', 'Coated Fries', 'Solo • Spicy • 1 extra (Meat)', 128.00, 1, 128.00, '{\"key\": \"coated-fries|Solo||Solo|spicy|extra-meat\", \"qty\": 1, \"name\": \"Coated Fries\", \"summary\": \"Solo • Spicy • 1 extra (Meat)\", \"unitPrice\": 128}', '2025-11-22 23:33:51'),
(2, 4, 'shawarma-wrap|LARGE Buy 1 Take 1|Beef|LARGE Buy 1 Take 1|not-spicy|', 'Shawarma Wrap', 'LARGE Buy 1 Take 1 • Beef', 165.00, 1, 165.00, '{\"key\": \"shawarma-wrap|LARGE Buy 1 Take 1|Beef|LARGE Buy 1 Take 1|not-spicy|\", \"qty\": 1, \"name\": \"Shawarma Wrap\", \"summary\": \"LARGE Buy 1 Take 1 • Beef\", \"unitPrice\": 165}', '2025-11-23 00:31:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
