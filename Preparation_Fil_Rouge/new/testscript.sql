-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 29, 2024 at 07:36 PM
-- Server version: 8.3.0
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testscript`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `adr_id` int NOT NULL AUTO_INCREMENT,
  `adr_city` varchar(100) NOT NULL,
  `adr_address` varchar(255) NOT NULL,
  `address_type` enum('livraison','facturation') NOT NULL,
  `adr_cp` varchar(50) NOT NULL,
  `pers_id` int NOT NULL,
  PRIMARY KEY (`adr_id`),
  KEY `pers_id` (`pers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers_details`
--

DROP TABLE IF EXISTS `customers_details`;
CREATE TABLE IF NOT EXISTS `customers_details` (
  `cust_id` int NOT NULL AUTO_INCREMENT,
  `cust_type` enum('particulier','professionnel') NOT NULL,
  `cust_siret` varchar(20) DEFAULT NULL,
  `coef` decimal(15,2) NOT NULL,
  `pers_id` int NOT NULL,
  PRIMARY KEY (`cust_id`),
  UNIQUE KEY `cust_siret` (`cust_siret`),
  KEY `pers_id` (`pers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_note`
--

DROP TABLE IF EXISTS `delivery_note`;
CREATE TABLE IF NOT EXISTS `delivery_note` (
  `deliv_id` int NOT NULL AUTO_INCREMENT,
  `deli_date` datetime NOT NULL,
  `shipped_qty` int NOT NULL,
  `ord_id` int NOT NULL,
  PRIMARY KEY (`deliv_id`),
  KEY `ord_id` (`ord_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

DROP TABLE IF EXISTS `invoice`;
CREATE TABLE IF NOT EXISTS `invoice` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
  `invoice_date` datetime DEFAULT NULL,
  `total` decimal(15,2) NOT NULL,
  `ord_id` int NOT NULL,
  PRIMARY KEY (`invoice_id`),
  KEY `ord_id` (`ord_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders_details`
--

DROP TABLE IF EXISTS `orders_details`;
CREATE TABLE IF NOT EXISTS `orders_details` (
  `prod_id` int NOT NULL,
  `ord_id` int NOT NULL,
  `det_qty` int NOT NULL,
  `det_price` decimal(15,2) NOT NULL,
  `shipped_qty` int DEFAULT NULL,
  PRIMARY KEY (`prod_id`,`ord_id`),
  KEY `ord_id` (`ord_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

DROP TABLE IF EXISTS `order_history`;
CREATE TABLE IF NOT EXISTS `order_history` (
  `ord_id` int NOT NULL AUTO_INCREMENT,
  `ord_date` datetime DEFAULT NULL,
  `ord_status` enum('en attente','expédiée','partiellement expédiée','livrée') NOT NULL,
  `payment_date` datetime DEFAULT NULL,
  `payment_method` enum('virement','chèque','carte bancaire') NOT NULL,
  `shipping_date` datetime DEFAULT NULL,
  `cust_id` int NOT NULL,
  PRIMARY KEY (`ord_id`),
  KEY `cust_id` (`cust_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
CREATE TABLE IF NOT EXISTS `person` (
  `pers_id` int NOT NULL AUTO_INCREMENT,
  `pers_ref` varchar(50) NOT NULL,
  `pers_firstname` varchar(100) NOT NULL,
  `pers_lastname` varchar(100) NOT NULL,
  `pers_phone` varchar(20) DEFAULT NULL,
  `pers_email` varchar(100) NOT NULL,
  `pers_passwd` varchar(255) NOT NULL,
  `pers_role` enum('particulier','professionnel') NOT NULL,
  PRIMARY KEY (`pers_id`),
  UNIQUE KEY `pers_ref` (`pers_ref`),
  UNIQUE KEY `pers_email` (`pers_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `prod_id` int NOT NULL AUTO_INCREMENT,
  `prod_label` varchar(100) NOT NULL,
  `prod_ref` varchar(50) NOT NULL,
  `prod_slug` varchar(100) NOT NULL,
  `prod_desc` text,
  `prod_img` varchar(255) DEFAULT NULL,
  `prod_price` decimal(15,2) NOT NULL,
  `prod_stock` int NOT NULL,
  `spl_id` int NOT NULL,
  `rub_id` int NOT NULL,
  PRIMARY KEY (`prod_id`),
  UNIQUE KEY `prod_ref` (`prod_ref`),
  UNIQUE KEY `prod_slug` (`prod_slug`),
  KEY `spl_id` (`spl_id`),
  KEY `rub_id` (`rub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rubric`
--

DROP TABLE IF EXISTS `rubric`;
CREATE TABLE IF NOT EXISTS `rubric` (
  `rub_id` int NOT NULL AUTO_INCREMENT,
  `rub_label` varchar(100) NOT NULL,
  `rub_slug` varchar(50) NOT NULL,
  `rub_desc` text,
  `rub_id_1` int NOT NULL,
  PRIMARY KEY (`rub_id`),
  UNIQUE KEY `rub_slug` (`rub_slug`),
  KEY `rub_id_1` (`rub_id_1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_details`
--

DROP TABLE IF EXISTS `supplier_details`;
CREATE TABLE IF NOT EXISTS `supplier_details` (
  `spl_id` int NOT NULL AUTO_INCREMENT,
  `spl_siret` varchar(50) NOT NULL,
  `spl_type` enum('importateur','constructeur') NOT NULL,
  `spl_status` tinyint(1) NOT NULL,
  `pers_id` int NOT NULL,
  PRIMARY KEY (`spl_id`),
  UNIQUE KEY `spl_siret` (`spl_siret`),
  KEY `pers_id` (`pers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
