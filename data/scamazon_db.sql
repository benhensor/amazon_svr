-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 04, 2025 at 03:55 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scamazon_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `address_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `county` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `is_billing` tinyint(1) DEFAULT 0,
  `delivery_instructions` text DEFAULT NULL,
  `address_type` enum('home','work','other') DEFAULT 'home',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`address_id`, `user_id`, `full_name`, `phone_number`, `address_line1`, `address_line2`, `city`, `county`, `postcode`, `country`, `is_default`, `is_billing`, `delivery_instructions`, `address_type`, `created_at`, `updated_at`) VALUES
(3, 7, 'Mow Mows', '0987654321', 'Mowface Ltd', 'Mowbang Business Land', 'Horshaming', 'West Sussex', 'RH12 1RT', 'United Kingdom', 1, 0, 'Behind the water butt', 'other', '2025-01-30 16:45:50', '2025-01-31 15:34:37'),
(4, 7, 'Mow Mow', '01403285849', 'Mowface Inc', 'Mowbang Business Park', 'Horsham', 'West Sussex', 'RH12 1RS', 'United Kingdom', 0, 0, 'Leave at reception', 'home', '2025-01-31 13:16:32', '2025-01-31 15:34:37'),
(6, 1, 'Testy McTestFace', '0123456789', '1 Testing Street', '', 'Testington', 'Testfordshire', 'TE1 2ST', 'United Kingdom', 1, 1, 'Behind the bins', 'home', '2025-02-03 09:42:34', '2025-02-03 09:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `baskets`
--

CREATE TABLE `baskets` (
  `basket_id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `items_count` int(11) NOT NULL DEFAULT 0,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','saved_for_later','converted_to_order') NOT NULL DEFAULT 'active',
  `last_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baskets`
--

INSERT INTO `baskets` (`basket_id`, `user_id`, `items_count`, `total`, `status`, `last_modified`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 54.98, 'active', '2025-02-03 19:03:56', '2025-02-02 12:57:34', '2025-02-03 18:03:56'),
(7, 7, 0, 0.00, 'active', '2025-02-04 10:54:46', '2025-02-03 19:05:05', '2025-02-04 09:54:46');

-- --------------------------------------------------------

--
-- Table structure for table `basket_items`
--

CREATE TABLE `basket_items` (
  `basket_item_id` varchar(255) NOT NULL,
  `basket_id` bigint(20) NOT NULL,
  `product_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`product_data`)),
  `quantity` int(11) NOT NULL DEFAULT 1,
  `is_selected` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basket_items`
--

INSERT INTO `basket_items` (`basket_item_id`, `basket_id`, `product_data`, `quantity`, `is_selected`, `created_at`, `updated_at`) VALUES
('ad966d28-3c52-45e9-a5fb-e7391bd9d58f', 1, '{\"id\":83,\"title\":\"Blue & Black Check Shirt\",\"description\":\"The Blue & Black Check Shirt is a stylish and comfortable men\'s shirt featuring a classic check pattern. Made from high-quality fabric, it\'s suitable for both casual and semi-formal occasions.\",\"category\":\"mens-shirts\",\"price\":29.989999999999998436805981327779591083526611328125,\"discountPercentage\":1.4099999999999999200639422269887290894985198974609375,\"rating\":4.19000000000000039079850466805510222911834716796875,\"stock\":44,\"tags\":[\"clothing\",\"men\'s shirts\"],\"brand\":\"Fashion Trends\",\"sku\":\"6RJDTVCU\",\"weight\":6,\"dimensions\":{\"width\":17.25,\"height\":27.309999999999998721023075631819665431976318359375,\"depth\":20.879999999999999005240169935859739780426025390625},\"warrantyInformation\":\"No warranty\",\"shippingInformation\":\"Ships in 1 month\",\"availabilityStatus\":\"In Stock\",\"reviews\":[{\"rating\":4,\"comment\":\"Highly recommended!\",\"date\":\"2024-05-23T08:56:21.623Z\",\"reviewerName\":\"Mason Parker\",\"reviewerEmail\":\"mason.parker@x.dummyjson.com\"},{\"rating\":5,\"comment\":\"Highly impressed!\",\"date\":\"2024-05-23T08:56:21.623Z\",\"reviewerName\":\"Avery Perez\",\"reviewerEmail\":\"avery.perez@x.dummyjson.com\"},{\"rating\":5,\"comment\":\"Fast shipping!\",\"date\":\"2024-05-23T08:56:21.623Z\",\"reviewerName\":\"Nathan Reed\",\"reviewerEmail\":\"nathan.reed@x.dummyjson.com\"}],\"returnPolicy\":\"7 days return policy\",\"minimumOrderQuantity\":4,\"meta\":{\"createdAt\":\"2024-05-23T08:56:21.623Z\",\"updatedAt\":\"2024-05-23T08:56:21.623Z\",\"barcode\":\"8840720880947\",\"qrCode\":\"https:\\/\\/assets.dummyjson.com\\/public\\/qr-code.png\"},\"images\":[\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Blue%20&%20Black%20Check%20Shirt\\/1.png\",\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Blue%20&%20Black%20Check%20Shirt\\/2.png\",\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Blue%20&%20Black%20Check%20Shirt\\/3.png\",\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Blue%20&%20Black%20Check%20Shirt\\/4.png\"],\"thumbnail\":\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Blue%20&%20Black%20Check%20Shirt\\/thumbnail.png\"}', 1, 1, '2025-02-03 18:03:56', '2025-02-03 18:03:56'),
('fc0389c0-f220-4e54-b046-219a8042c99e', 1, '{\"id\":84,\"title\":\"Gigabyte Aorus Men Tshirt\",\"description\":\"The Gigabyte Aorus Men Tshirt is a cool and casual shirt for gaming enthusiasts. With the Aorus logo and sleek design, it\'s perfect for expressing your gaming style.\",\"category\":\"mens-shirts\",\"price\":24.989999999999998436805981327779591083526611328125,\"discountPercentage\":12.5999999999999996447286321199499070644378662109375,\"rating\":4.95000000000000017763568394002504646778106689453125,\"stock\":64,\"tags\":[\"clothing\",\"men\'s t-shirts\"],\"brand\":\"Gigabyte\",\"sku\":\"QA703Y60\",\"weight\":2,\"dimensions\":{\"width\":8.53999999999999914734871708787977695465087890625,\"height\":23.519999999999999573674358543939888477325439453125,\"depth\":5.660000000000000142108547152020037174224853515625},\"warrantyInformation\":\"1 month warranty\",\"shippingInformation\":\"Ships in 1 week\",\"availabilityStatus\":\"In Stock\",\"reviews\":[{\"rating\":4,\"comment\":\"Highly recommended!\",\"date\":\"2024-05-23T08:56:21.623Z\",\"reviewerName\":\"Logan Lawson\",\"reviewerEmail\":\"logan.lawson@x.dummyjson.com\"},{\"rating\":4,\"comment\":\"Great value for money!\",\"date\":\"2024-05-23T08:56:21.623Z\",\"reviewerName\":\"Logan Lawson\",\"reviewerEmail\":\"logan.lawson@x.dummyjson.com\"},{\"rating\":5,\"comment\":\"Great value for money!\",\"date\":\"2024-05-23T08:56:21.623Z\",\"reviewerName\":\"Oscar Powers\",\"reviewerEmail\":\"oscar.powers@x.dummyjson.com\"}],\"returnPolicy\":\"30 days return policy\",\"minimumOrderQuantity\":4,\"meta\":{\"createdAt\":\"2024-05-23T08:56:21.623Z\",\"updatedAt\":\"2024-05-23T08:56:21.623Z\",\"barcode\":\"3072645939073\",\"qrCode\":\"https:\\/\\/assets.dummyjson.com\\/public\\/qr-code.png\"},\"images\":[\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Gigabyte%20Aorus%20Men%20Tshirt\\/1.png\",\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Gigabyte%20Aorus%20Men%20Tshirt\\/2.png\",\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Gigabyte%20Aorus%20Men%20Tshirt\\/3.png\",\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Gigabyte%20Aorus%20Men%20Tshirt\\/4.png\"],\"thumbnail\":\"https:\\/\\/cdn.dummyjson.com\\/products\\/images\\/mens-shirts\\/Gigabyte%20Aorus%20Men%20Tshirt\\/thumbnail.png\"}', 1, 1, '2025-02-03 18:03:56', '2025-02-03 18:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(255) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `order_placed` datetime NOT NULL,
  `delivery_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`delivery_address`)),
  `payment_method` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payment_method`)),
  `shipping` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`shipping`)),
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_placed`, `delivery_address`, `payment_method`, `shipping`, `total`, `status`, `created_at`, `updated_at`) VALUES
('972adf6e-9186-40d6-9d4a-1db3f2d577ae', 7, '2025-02-04 09:54:45', '{\"address_id\":3,\"user_id\":7,\"full_name\":\"Mow Mows\",\"phone_number\":\"0987654321\",\"address_line1\":\"Mowface Ltd\",\"address_line2\":\"Mowbang Business Land\",\"city\":\"Horshaming\",\"county\":\"West Sussex\",\"postcode\":\"RH12 1RT\",\"country\":\"United Kingdom\",\"is_default\":true,\"is_billing\":false,\"delivery_instructions\":\"Behind the water butt\",\"address_type\":\"other\"}', '{\"payment_method_id\":32,\"user_id\":7,\"bank\":\"Lloyds Bank\",\"card_type\":\"VISA\",\"card_account\":\"Credit\",\"card_number\":\"42592593482777670\",\"cardholder_name\":\"Mow Mow\",\"start_date\":\"02\\/25\",\"end_date\":\"02\\/30\",\"cvv\":\"289\",\"status\":\"default\",\"created_at\":\"2025-02-03 19:03:30\",\"updated_at\":\"2025-02-03 19:03:33\"}', '{\"method\":\"standard\",\"price\":3.9900000000000002131628207280300557613372802734375,\"dates\":\"Tuesday 11th Feb - Thursday 13th Feb\",\"range_from\":\"2025-02-11T09:54:43.877Z\",\"range_to\":\"2025-02-13T09:54:43.877Z\",\"description\":\"Delivery within 5-7 working days\"}', 54.98, 'pending', '2025-02-04 09:54:45', '2025-02-04 09:54:45');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
('1d0123af-16b8-4a93-ac2f-e07de0aeceaa', '972adf6e-9186-40d6-9d4a-1db3f2d577ae', 83, 1, 29.99, 29.99, '2025-02-04 09:54:45', '2025-02-04 09:54:45'),
('dd22c508-e00b-48c8-aa65-59eb115f40f3', '972adf6e-9186-40d6-9d4a-1db3f2d577ae', 84, 1, 24.99, 24.99, '2025-02-04 09:54:45', '2025-02-04 09:54:45');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `payment_method_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `bank` varchar(255) NOT NULL,
  `card_type` varchar(255) NOT NULL,
  `card_account` varchar(255) NOT NULL,
  `card_number` varchar(255) NOT NULL,
  `cardholder_name` varchar(255) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `cvv` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`payment_method_id`, `user_id`, `bank`, `card_type`, `card_account`, `card_number`, `cardholder_name`, `start_date`, `end_date`, `cvv`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Barclays', 'visa', 'Debit', '4532012345673773', 'Testy McTestFace', '01/23', '01/28', '397', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:27:17'),
(2, 1, 'HSBC', 'mastercard', 'Credit', '5412345678901234', 'Testy McTestFace', '03/24', '03/29', '456', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:39:43'),
(3, 1, 'NatWest', 'visa', 'Credit', '4123456789012345', 'Testy McTestFace', '06/24', '06/29', '789', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(4, 1, 'Lloyds Bank', 'mastercard', 'Debit', '5234567890123456', 'Testy McTestFace', '12/19', '12/28', '234', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(5, 1, 'Santander', 'visa', 'Credit', '4345678901234567', 'Testy McTestFace', '09/20', '09/29', '567', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(6, 1, 'Halifax', 'mastercard', 'Debit', '5345678901234567', 'Testy McTestFace', '03/19', '03/22', '890', 'expired', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(7, 1, 'RBS', 'visa', 'Credit', '4456789012345678', 'Testy McTestFace', '08/21', '08/26', '345', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(9, 1, 'Nationwide', 'visa', 'Credit', '4567890123456789', 'Testy McTestFace', '04/22', '04/27', '901', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(10, 1, 'Metro Bank', 'mastercard', 'Debit', '5567890123456789', 'Testy McTestFace', '07/19', '07/22', '234', 'expired', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(12, 1, 'HSBC', 'mastercard', 'Debit', '5678901234567890', 'Testy McTestFace', '05/20', '05/23', '890', 'expired', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(13, 1, 'NatWest', 'visa', 'Credit', '4789012345678901', 'Testy McTestFace', '10/21', '10/26', '123', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(14, 1, 'Lloyds', 'mastercard', 'Debit', '5789012345678901', 'Testy McTestFace', '01/19', '01/22', '456', 'expired', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(15, 1, 'Santander', 'american express', 'Credit', '3789012345678901', 'Testy McTestFace', '12/21', '12/26', '789', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:18:04'),
(16, 1, 'Halifax', 'visa', 'Debit', '4890123456789012', 'Testy McTestFace', '03/20', '03/23', '012', 'expired', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(18, 1, 'TSB', 'visa', 'Debit', '4901234567890123', 'Testy McTestFace', '09/22', '09/27', '678', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(19, 1, 'Nationwide', 'mastercard', 'Credit', '5901234567890123', 'Testy McTestFace', '11/21', '11/26', '901', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:17:03'),
(20, 1, 'Metro Bank', 'american express', 'Credit', '3901234567890123', 'Testy McTestFace', '08/22', '08/27', '234', 'valid', '2025-02-03 15:46:23', '2025-02-04 14:25:00'),
(28, 1, 'TSB', 'american express', 'Credit', '3753477349838969', 'Testy McTestFace', '02/25', '02/30', '2483', 'valid', '2025-02-03 16:44:17', '2025-02-04 14:18:04'),
(29, 1, 'HSBC', 'mastercard', 'Debit', '52260055684483927', 'Testy McTestFace', '02/25', '02/30', '630', 'valid', '2025-02-03 16:52:30', '2025-02-04 14:17:03'),
(30, 1, 'Metro Bank', 'visa', 'Debit', '46617904315334030', 'Testy McTestFace', '02/25', '02/30', '950', 'valid', '2025-02-03 16:52:58', '2025-02-04 14:17:03'),
(31, 1, 'Lloyds Bank', 'visa', 'Debit', '48784973544934442', 'Testy McTestFace', '02/25', '02/30', '268', 'valid', '2025-02-03 16:53:10', '2025-02-04 14:17:03'),
(32, 7, 'Lloyds Bank', 'visa', 'Credit', '42592593482777670', 'Mow Mow', '02/25', '02/30', '289', 'default', '2025-02-03 19:03:30', '2025-02-04 14:17:03'),
(33, 7, 'RBS', 'mastercard', 'Credit', '55685331776964476', 'Mow Mow', '02/25', '02/30', '524', 'default', '2025-02-04 10:31:30', '2025-02-04 14:17:03'),
(34, 1, 'Co-op', 'visa', 'Credit', '41507170948864353', 'Testy McTestFace', '02/25', '02/30', '670', 'default', '2025-02-04 14:39:04', '2025-02-04 14:39:43'),
(35, 1, 'Co-op', 'visa', 'Credit', '44463029006208645', 'Testy McTestFace', '02/25', '02/30', '661', 'valid', '2025-02-04 14:39:38', '2025-02-04 14:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `profile_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `browsing_history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`browsing_history`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Testy McTestFace', 'test@testing.com', '$2y$10$HVQAm2NnPEK8wzGKOilIqOk/.5.WxBdAPNDRTnjOcpWIjkk5Knc6C', '2025-01-30 11:49:45', '2025-01-30 11:49:45'),
(7, 'Mow Mow', 'momo@geemail.com', '$2y$10$guo32vz6ZcrR.Ai0p99HmOrjK8VSdlGKZAmPHJ8pTziGK1Pl/OhFq', '2025-01-30 13:11:41', '2025-01-30 13:11:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `idx_addresses_user_id` (`user_id`);

--
-- Indexes for table `baskets`
--
ALTER TABLE `baskets`
  ADD PRIMARY KEY (`basket_id`),
  ADD KEY `idx_baskets_user_id` (`user_id`);

--
-- Indexes for table `basket_items`
--
ALTER TABLE `basket_items`
  ADD PRIMARY KEY (`basket_item_id`),
  ADD KEY `idx_basket_items_basket_id` (`basket_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`payment_method_id`),
  ADD KEY `idx_payment_methods_user_id` (`user_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `idx_profiles_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `payment_method_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `profile_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `baskets`
--
ALTER TABLE `baskets`
  ADD CONSTRAINT `baskets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `basket_items`
--
ALTER TABLE `basket_items`
  ADD CONSTRAINT `basket_items_ibfk_1` FOREIGN KEY (`basket_id`) REFERENCES `baskets` (`basket_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
