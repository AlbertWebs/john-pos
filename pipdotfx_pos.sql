-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 06, 2025 at 06:53 PM
-- Server version: 5.7.44-cll-lve
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pipdotfx_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `country`, `created_at`, `updated_at`) VALUES
(1, 'Bosch', 'Germany', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(2, 'Denso', 'Japan', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(3, 'NGK', 'Japan', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(4, 'Mann Filter', 'Germany', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(5, 'Mobil', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(6, 'Castrol', 'UK', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(7, 'Delphi', 'UK', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(8, 'Valeo', 'France', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(9, 'Continental', 'Germany', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(10, 'TRW', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(11, 'Brembo', 'Italy', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(12, 'Monroe', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(13, 'KYB', 'Japan', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(14, 'Gates', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(15, 'Mahle', 'Germany', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(16, 'Hella', 'Germany', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(17, 'Philips', 'Netherlands', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(18, 'Osram', 'Germany', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(19, 'ACDelco', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(20, 'Mopar', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(21, 'Motorcraft', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(22, 'Beck Arnley', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(23, 'Wix', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(24, 'Fram', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(25, 'K&N', 'USA', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(26, 'Meyle', 'Germany', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(27, 'Febi', 'Germany', '2025-11-04 09:20:15', '2025-11-04 09:20:15'),
(28, 'Lemforder', 'Germany', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(29, 'Magneti Marelli', 'Italy', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(30, 'Pierburg', 'Germany', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(31, 'Sachs', 'Germany', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(32, 'Luk', 'Germany', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(33, 'ZF', 'Germany', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(34, 'SKF', 'Sweden', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(35, 'Timken', 'USA', '2025-11-04 09:20:16', '2025-11-04 09:20:16');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('johlly-auto-spares-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:12:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:16:\"manage inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"create sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"edit sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"delete sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"view reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"manage settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"manage customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"manage returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"process payments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"cashier\";s:1:\"c\";s:3:\"web\";}}}', 1762519600),
('one-eleven-cache-mpesa_access_token_sandbox', 's:28:\"qokEoD7lFYLDslnCvFDLhInuGcqe\";', 1762274417),
('one-eleven-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:12:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:16:\"manage inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"create sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:10:\"edit sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:12:\"delete sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"view reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"manage settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"manage customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"manage returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"process payments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"cashier\";s:1:\"c\";s:3:\"web\";}}}', 1762497729);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Engine Parts', 'Engine components and accessories', '2025-11-04 09:20:12', '2025-11-04 09:20:12'),
(2, 'Brake System', 'Brake pads, discs, and related components', '2025-11-04 09:20:12', '2025-11-04 09:20:12'),
(3, 'Suspension', 'Shocks, struts, and suspension parts', '2025-11-04 09:20:12', '2025-11-04 09:20:12'),
(4, 'Electrical', 'Batteries, alternators, and electrical components', '2025-11-04 09:20:12', '2025-11-04 09:20:12'),
(5, 'Cooling System', 'Radiators, water pumps, and cooling parts', '2025-11-04 09:20:12', '2025-11-04 09:20:12'),
(6, 'Transmission', 'Transmission components and fluids', '2025-11-04 09:20:12', '2025-11-04 09:20:12'),
(7, 'Exhaust System', 'Mufflers, pipes, and exhaust components', '2025-11-04 09:20:12', '2025-11-04 09:20:12'),
(8, 'Filters', 'Air, oil, and fuel filters', '2025-11-04 09:20:12', '2025-11-04 09:20:12'),
(9, 'Belts & Hoses', 'Timing belts, serpentine belts, and hoses', '2025-11-04 09:20:12', '2025-11-04 09:20:12'),
(10, 'Lights & Bulbs', 'Headlights, tail lights, and bulbs', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(11, 'Body Parts', 'Bumpers, fenders, and body panels', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(12, 'Interior Parts', 'Seats, dashboards, and interior components', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(13, 'Wheels & Tires', 'Rims, tires, and wheel accessories', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(14, 'Steering', 'Steering wheels, racks, and related parts', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(15, 'Fuel System', 'Fuel pumps, injectors, and fuel system parts', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(16, 'Ignition System', 'Spark plugs, coils, and ignition components', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(17, 'Oil & Fluids', 'Engine oil, transmission fluid, and lubricants', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(18, 'Gaskets & Seals', 'Gaskets, seals, and O-rings', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(19, 'Sensors', 'Oxygen sensors, temperature sensors, and more', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(20, 'Clutch System', 'Clutch plates, pressure plates, and related parts', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(21, 'Drive Shaft', 'CV joints, drive shafts, and axles', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(22, 'Timing Components', 'Timing chains, gears, and components', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(23, 'Valve Train', 'Valves, springs, and valve train parts', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(24, 'Pistons & Rings', 'Pistons, rings, and cylinder components', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(25, 'Camshaft', 'Camshafts and related components', '2025-11-04 09:20:13', '2025-11-04 09:20:13'),
(26, 'Crankshaft', 'Crankshafts and bearings', '2025-11-04 09:20:14', '2025-11-04 09:20:14'),
(27, 'Oil Pump', 'Oil pumps and related components', '2025-11-04 09:20:14', '2025-11-04 09:20:14'),
(28, 'Water Pump', 'Water pumps and gaskets', '2025-11-04 09:20:14', '2025-11-04 09:20:14'),
(29, 'Thermostat', 'Thermostats and housing', '2025-11-04 09:20:14', '2025-11-04 09:20:14'),
(30, 'Radiator', 'Radiators and cooling fans', '2025-11-04 09:20:14', '2025-11-04 09:20:14'),
(31, 'AC Components', 'AC compressors, condensers, and parts', '2025-11-04 09:20:14', '2025-11-04 09:20:14'),
(32, 'Wiper System', 'Wiper blades, motors, and arms', '2025-11-04 09:20:14', '2025-11-04 09:20:14'),
(33, 'Mirrors', 'Side mirrors and rearview mirrors', '2025-11-04 09:20:14', '2025-11-04 09:20:14'),
(34, 'Weatherstripping', 'Door seals and weatherstripping', '2025-11-04 09:20:14', '2025-11-04 09:20:14'),
(35, 'Fasteners', 'Bolts, nuts, screws, and clips', '2025-11-04 09:20:14', '2025-11-04 09:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `loyalty_points` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `email`, `address`, `loyalty_points`, `created_at`, `updated_at`) VALUES
(1, 'Matthew Garcia', '254721508834', 'matthew.garcia@example.com', '6171 Main Street, Nairobi, Kenya', 2592, '2025-11-04 09:20:18', '2025-11-04 09:20:22'),
(2, 'Margaret Hernandez', '254773657097', 'margaret.hernandez@example.com', '6636 Main Street, Nairobi, Kenya', 3289, '2025-11-04 09:20:18', '2025-11-04 09:20:20'),
(3, 'Robert Jones', '254732710237', 'robert.jones@example.com', '4864 Main Street, Nairobi, Kenya', 3302, '2025-11-04 09:20:18', '2025-11-04 09:20:23'),
(4, 'Patricia Moore', '254753593135', 'patricia.moore@example.com', '5828 Main Street, Nairobi, Kenya', 4138, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(5, 'Jessica White', '254780989925', 'jessica.white@example.com', '8271 Main Street, Nairobi, Kenya', 2536, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(6, 'Matthew Walker', '254725478631', 'matthew.walker@example.com', '864 Main Street, Nairobi, Kenya', 4418, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(7, 'Jessica Garcia', '254794724341', 'jessica.garcia@example.com', '5322 Main Street, Nairobi, Kenya', 3943, '2025-11-04 09:20:18', '2025-11-04 09:20:23'),
(8, 'John Rodriguez', '254756194211', 'john.rodriguez@example.com', '403 Main Street, Nairobi, Kenya', 4336, '2025-11-04 09:20:18', '2025-11-04 09:20:22'),
(9, 'Thomas Martin', '254723573548', 'thomas.martin@example.com', '3392 Main Street, Nairobi, Kenya', 2173, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(10, 'Elizabeth Jones', '254756165484', 'elizabeth.jones@example.com', '6911 Main Street, Nairobi, Kenya', 5832, '2025-11-04 09:20:18', '2025-11-04 09:20:23'),
(11, 'William Williams', '254745768278', 'william.williams@example.com', '3514 Main Street, Nairobi, Kenya', 142, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(12, 'Daniel Thompson', '254776645597', 'daniel.thompson@example.com', '4379 Main Street, Nairobi, Kenya', 4185, '2025-11-04 09:20:18', '2025-11-04 09:20:23'),
(13, 'Joseph Lopez', '254737764373', 'joseph.lopez@example.com', '3748 Main Street, Nairobi, Kenya', 5444, '2025-11-04 09:20:18', '2025-11-04 09:20:23'),
(14, 'Christopher Robinson', '254714173140', 'christopher.robinson@example.com', '4499 Main Street, Nairobi, Kenya', 4950, '2025-11-04 09:20:18', '2025-11-04 09:20:22'),
(15, 'Elizabeth Lewis', '254765743930', 'elizabeth.lewis@example.com', '7903 Main Street, Nairobi, Kenya', 1560, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(16, 'Karen Williams', '254793917479', 'karen.williams@example.com', '9199 Main Street, Nairobi, Kenya', 2486, '2025-11-04 09:20:18', '2025-11-04 09:20:20'),
(17, 'Donald Walker', '254766164026', 'donald.walker@example.com', '5116 Main Street, Nairobi, Kenya', 3117, '2025-11-04 09:20:18', '2025-11-04 09:20:22'),
(18, 'Donald Jackson', '254720209137', 'donald.jackson@example.com', '4789 Main Street, Nairobi, Kenya', 1342, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(19, 'David Thomas', '254772268094', 'david.thomas@example.com', '6533 Main Street, Nairobi, Kenya', 107, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(20, 'Betty Robinson', '254794291283', 'betty.robinson@example.com', '297 Main Street, Nairobi, Kenya', 788, '2025-11-04 09:20:18', '2025-11-04 09:20:23'),
(21, 'Joseph Miller', '254751538397', 'joseph.miller@example.com', '4892 Main Street, Nairobi, Kenya', 1872, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(22, 'Donald Martinez', '254798017953', 'donald.martinez@example.com', '6110 Main Street, Nairobi, Kenya', 6942, '2025-11-04 09:20:18', '2025-11-04 09:20:23'),
(23, 'William Martin', '254780167854', 'william.martin@example.com', '3036 Main Street, Nairobi, Kenya', 2882, '2025-11-04 09:20:18', '2025-11-04 09:20:22'),
(24, 'Karen Johnson', '254732144985', 'karen.johnson@example.com', '2579 Main Street, Nairobi, Kenya', 1046, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(25, 'Karen Jackson', '254759250034', 'karen.jackson@example.com', '3508 Main Street, Nairobi, Kenya', 4208, '2025-11-04 09:20:18', '2025-11-04 09:20:21'),
(26, 'Elizabeth Garcia', '254763123193', 'elizabeth.garcia@example.com', '1924 Main Street, Nairobi, Kenya', 3591, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(27, 'Linda Martinez', '254755406000', 'linda.martinez@example.com', '1379 Main Street, Nairobi, Kenya', 895, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(28, 'Linda White', '254762465063', 'linda.white@example.com', '7619 Main Street, Nairobi, Kenya', 6001, '2025-11-04 09:20:18', '2025-11-04 09:20:23'),
(29, 'Barbara Smith', '254768534606', 'barbara.smith@example.com', '2663 Main Street, Nairobi, Kenya', 4369, '2025-11-04 09:20:18', '2025-11-04 09:20:21'),
(30, 'Karen Harris', '254772400210', 'karen.harris@example.com', '6502 Main Street, Nairobi, Kenya', 1832, '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(31, 'Susan Anderson', '254734855648', 'susan.anderson@example.com', '8517 Main Street, Nairobi, Kenya', 3562, '2025-11-04 09:20:18', '2025-11-04 09:20:19'),
(32, 'Christopher Garcia', '254717553078', 'christopher.garcia@example.com', '6989 Main Street, Nairobi, Kenya', 7072, '2025-11-04 09:20:18', '2025-11-04 09:20:23'),
(33, 'Jane Rodriguez', '254757121504', 'jane.rodriguez@example.com', '8198 Main Street, Nairobi, Kenya', 2740, '2025-11-04 09:20:19', '2025-11-04 09:20:22'),
(34, 'Robert Lee', '254779929869', 'robert.lee@example.com', '2606 Main Street, Nairobi, Kenya', 4594, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(35, 'Susan Thompson', '254789636519', 'susan.thompson@example.com', '9822 Main Street, Nairobi, Kenya', 5258, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(36, 'Albert', '254723014043', 'albert@pos.local', NULL, 0, '2025-11-04 11:45:01', '2025-11-04 11:45:01');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_make_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `year_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `min_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT '0',
  `reorder_level` int(11) NOT NULL DEFAULT '0',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `part_number`, `sku`, `barcode`, `name`, `description`, `brand_id`, `category_id`, `vehicle_make_id`, `vehicle_model_id`, `year_range`, `cost_price`, `min_price`, `selling_price`, `stock_quantity`, `reorder_level`, `location`, `status`, `created_at`, `updated_at`) VALUES
(1, 'OIL-FLT-001', 'SKU-000001', 'BC0000000001', 'Engine Oil Filter', 'High quality Engine Oil Filter for various vehicle models', 18, 12, 33, 23, '2010-2024', 500.00, 600.00, 800.00, 50, 20, 'Shelf A-1', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(2, 'AIR-FLT-001', 'SKU-000002', 'BC0000000002', 'Air Filter', 'High quality Air Filter for various vehicle models', 27, 4, 29, 18, '2010-2024', 300.00, 400.00, 600.00, 73, 30, 'Shelf B-2', 'active', '2025-11-04 09:20:17', '2025-11-06 03:14:20'),
(3, 'BRK-PAD-001', 'SKU-000003', 'BC0000000003', 'Brake Pad Set', 'High quality Brake Pad Set for various vehicle models', 9, 13, 11, 28, '2010-2024', 2500.00, 3000.00, 4000.00, 29, 10, 'Shelf C-3', 'active', '2025-11-04 09:20:17', '2025-11-06 03:14:20'),
(4, 'BRK-DSC-001', 'SKU-000004', 'BC0000000004', 'Brake Disc', 'High quality Brake Disc for various vehicle models', 21, 18, 10, 11, '2010-2024', 4500.00, 5500.00, 7500.00, 24, 8, 'Shelf D-4', 'active', '2025-11-04 09:20:17', '2025-11-06 03:14:20'),
(5, 'SPK-PLG-001', 'SKU-000005', 'BC0000000005', 'Spark Plug', 'High quality Spark Plug for various vehicle models', 20, 28, 7, 31, '2010-2024', 800.00, 1000.00, 1500.00, 100, 40, 'Shelf E-5', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(6, 'TIM-BLT-001', 'SKU-000006', 'BC0000000006', 'Timing Belt', 'High quality Timing Belt for various vehicle models', 30, 13, 5, 1, '2010-2024', 3500.00, 4500.00, 6000.00, 20, 5, 'Shelf F-6', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(7, 'WAT-PMP-001', 'SKU-000007', 'BC0000000007', 'Water Pump', 'High quality Water Pump for various vehicle models', 28, 6, 23, 31, '2010-2024', 5500.00, 7000.00, 9500.00, 15, 5, 'Shelf G-7', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(8, 'RAD-001', 'SKU-000008', 'BC0000000008', 'Radiator', 'High quality Radiator for various vehicle models', 29, 7, 18, 23, '2010-2024', 8500.00, 11000.00, 15000.00, 12, 4, 'Shelf H-8', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(9, 'ALT-001', 'SKU-000009', 'BC0000000009', 'Alternator', 'High quality Alternator for various vehicle models', 20, 9, 34, 24, '2010-2024', 12000.00, 15000.00, 20000.00, 10, 3, 'Shelf I-9', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(10, 'STR-MTR-001', 'SKU-000010', 'BC0000000010', 'Starter Motor', 'High quality Starter Motor for various vehicle models', 23, 10, 3, 8, '2010-2024', 10000.00, 13000.00, 18000.00, 8, 3, 'Shelf J-10', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(11, 'BAT-001', 'SKU-000011', 'BC0000000011', 'Battery', 'High quality Battery for various vehicle models', 28, 13, 17, 15, '2010-2024', 8000.00, 10000.00, 14000.00, 39, 15, 'Shelf A-11', 'active', '2025-11-04 09:20:17', '2025-11-06 03:14:20'),
(12, 'SHK-ABS-001', 'SKU-000012', 'BC0000000012', 'Shock Absorber', 'High quality Shock Absorber for various vehicle models', 32, 6, 13, 8, '2010-2024', 4500.00, 6000.00, 8500.00, 18, 6, 'Shelf B-12', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(13, 'STR-ASM-001', 'SKU-000013', 'BC0000000013', 'Strut Assembly', 'High quality Strut Assembly for various vehicle models', 9, 13, 30, 10, '2010-2024', 12000.00, 15000.00, 20000.00, 12, 4, 'Shelf C-13', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(14, 'FUL-FLT-001', 'SKU-000014', 'BC0000000014', 'Fuel Filter', 'High quality Fuel Filter for various vehicle models', 31, 31, 34, 26, '2010-2024', 600.00, 800.00, 1200.00, 60, 25, 'Shelf D-14', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(15, 'FUL-PMP-001', 'SKU-000015', 'BC0000000015', 'Fuel Pump', 'High quality Fuel Pump for various vehicle models', 4, 21, 18, 14, '2010-2024', 7500.00, 9500.00, 13000.00, 14, 5, 'Shelf E-15', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(16, 'O2-SNS-001', 'SKU-000016', 'BC0000000016', 'Oxygen Sensor', 'High quality Oxygen Sensor for various vehicle models', 8, 33, 8, 6, '2010-2024', 3500.00, 4500.00, 6500.00, 22, 8, 'Shelf F-16', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(17, 'MAF-SNS-001', 'SKU-000017', 'BC0000000017', 'Mass Air Flow Sensor', 'High quality Mass Air Flow Sensor for various vehicle models', 21, 31, 8, 15, '2010-2024', 5500.00, 7000.00, 9500.00, 14, 6, 'Shelf G-17', 'active', '2025-11-04 09:20:17', '2025-11-06 09:26:58'),
(18, 'THR-BDY-001', 'SKU-000018', 'BC0000000018', 'Throttle Body', 'High quality Throttle Body for various vehicle models', 5, 2, 16, 31, '2010-2024', 6500.00, 8500.00, 12000.00, 10, 4, 'Shelf H-18', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(19, 'IGN-CIL-001', 'SKU-000019', 'BC0000000019', 'Ignition Coil', 'High quality Ignition Coil for various vehicle models', 5, 12, 4, 30, '2010-2024', 2500.00, 3500.00, 5000.00, 28, 10, 'Shelf I-19', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(20, 'DST-CAP-001', 'SKU-000020', 'BC0000000020', 'Distributor Cap', 'High quality Distributor Cap for various vehicle models', 20, 33, 31, 3, '2010-2024', 1500.00, 2000.00, 3000.00, 35, 12, 'Shelf J-20', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(21, 'ROT-001', 'SKU-000021', 'BC0000000021', 'Rotor', 'High quality Rotor for various vehicle models', 2, 16, 11, 1, '2010-2024', 800.00, 1000.00, 1500.00, 45, 18, 'Shelf A-1', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(22, 'WIP-BLD-001', 'SKU-000022', 'BC0000000022', 'Wiper Blade', 'High quality Wiper Blade for various vehicle models', 9, 3, 15, 30, '2010-2024', 600.00, 800.00, 1200.00, 80, 30, 'Shelf B-2', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(23, 'HDL-BLB-001', 'SKU-000023', 'BC0000000023', 'Headlight Bulb', 'High quality Headlight Bulb for various vehicle models', 10, 12, 14, 22, '2010-2024', 1200.00, 1500.00, 2500.00, 50, 20, 'Shelf C-3', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(24, 'TAL-BLB-001', 'SKU-000024', 'BC0000000024', 'Tail Light Bulb', 'High quality Tail Light Bulb for various vehicle models', 8, 29, 24, 3, '2010-2024', 400.00, 500.00, 800.00, 100, 40, 'Shelf D-4', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(25, 'FOG-LGT-001', 'SKU-000025', 'BC0000000025', 'Fog Light', 'High quality Fog Light for various vehicle models', 4, 3, 1, 19, '2010-2024', 3500.00, 4500.00, 6500.00, 15, 5, 'Shelf E-5', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(26, 'SRP-BLT-001', 'SKU-000026', 'BC0000000026', 'Serpentine Belt', 'High quality Serpentine Belt for various vehicle models', 32, 2, 35, 2, '2010-2024', 2000.00, 2500.00, 4000.00, 32, 12, 'Shelf F-6', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(27, 'PSP-PMP-001', 'SKU-000027', 'BC0000000027', 'Power Steering Pump', 'High quality Power Steering Pump for various vehicle models', 4, 6, 2, 8, '2010-2024', 8500.00, 11000.00, 15000.00, 9, 3, 'Shelf G-7', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(28, 'STR-RCK-001', 'SKU-000028', 'BC0000000028', 'Steering Rack', 'High quality Steering Rack for various vehicle models', 10, 17, 16, 8, '2010-2024', 15000.00, 20000.00, 28000.00, 6, 2, 'Shelf H-8', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(29, 'TIE-ROD-001', 'SKU-000029', 'BC0000000029', 'Tie Rod End', 'High quality Tie Rod End for various vehicle models', 9, 3, 10, 16, '2010-2024', 2500.00, 3500.00, 5000.00, 24, 8, 'Shelf I-9', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(30, 'BAL-JNT-001', 'SKU-000030', 'BC0000000030', 'Ball Joint', 'High quality Ball Joint for various vehicle models', 24, 13, 2, 15, '2010-2024', 3000.00, 4000.00, 6000.00, 19, 7, 'Shelf J-10', 'active', '2025-11-04 09:20:17', '2025-11-06 03:14:20'),
(31, 'CV-JNT-001', 'SKU-000031', 'BC0000000031', 'CV Joint', 'High quality CV Joint for various vehicle models', 34, 34, 3, 22, '2010-2024', 4500.00, 6000.00, 8500.00, 16, 6, 'Shelf A-11', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(32, 'DRV-SFT-001', 'SKU-000032', 'BC0000000032', 'Drive Shaft', 'High quality Drive Shaft for various vehicle models', 16, 15, 33, 29, '2010-2024', 12000.00, 15000.00, 20000.00, 8, 3, 'Shelf B-12', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(33, 'CLT-KIT-001', 'SKU-000033', 'BC0000000033', 'Clutch Kit', 'High quality Clutch Kit for various vehicle models', 9, 11, 4, 31, '2010-2024', 8500.00, 11000.00, 15000.00, 11, 4, 'Shelf C-13', 'active', '2025-11-04 09:20:17', '2025-11-06 03:14:20'),
(34, 'FLY-WHL-001', 'SKU-000034', 'BC0000000034', 'Flywheel', 'High quality Flywheel for various vehicle models', 17, 22, 28, 1, '2010-2024', 15000.00, 20000.00, 28000.00, 5, 2, 'Shelf D-14', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(35, 'MUF-001', 'SKU-000035', 'BC0000000035', 'Muffler', 'High quality Muffler for various vehicle models', 17, 3, 33, 8, '2010-2024', 5500.00, 7000.00, 9500.00, 14, 5, 'Shelf E-15', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(36, 'CAT-CNV-001', 'SKU-000036', 'BC0000000036', 'Catalytic Converter', 'High quality Catalytic Converter for various vehicle models', 28, 29, 30, 18, '2010-2024', 25000.00, 32000.00, 45000.00, 3, 2, 'Shelf F-16', 'active', '2025-11-04 09:20:17', '2025-11-06 03:14:20'),
(37, 'EXH-PIP-001', 'SKU-000037', 'BC0000000037', 'Exhaust Pipe', 'High quality Exhaust Pipe for various vehicle models', 33, 27, 21, 27, '2010-2024', 3500.00, 4500.00, 6500.00, 18, 6, 'Shelf G-17', 'active', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(38, 'THM-001', 'SKU-000038', 'BC0000000038', 'Thermostat', 'High quality Thermostat for various vehicle models', 4, 29, 2, 20, '2010-2024', 1200.00, 1500.00, 2500.00, 42, 15, 'Shelf H-18', 'active', '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(39, 'CLN-HOS-001', 'SKU-000039', 'BC0000000039', 'Coolant Hose', 'High quality Coolant Hose for various vehicle models', 10, 4, 10, 6, '2010-2024', 800.00, 1000.00, 1500.00, 54, 20, 'Shelf I-19', 'active', '2025-11-04 09:20:18', '2025-11-04 09:24:17'),
(40, 'RAD-CAP-001', 'SKU-000040', 'BC0000000040', 'Radiator Cap', 'High quality Radiator Cap for various vehicle models', 12, 20, 4, 11, '2010-2024', 400.00, 500.00, 800.00, 70, 25, 'Shelf J-20', 'active', '2025-11-04 09:20:18', '2025-11-04 09:20:18'),
(41, 'ABS-ABU', 'BEL-ABSABU-20251106', '6223001580270', 'Item 1', 'Item 1', 22, 9, NULL, NULL, '2015', 12000.00, 15000.00, 18000.00, 10, 12, 'A-12', 'active', '2025-11-06 03:57:30', '2025-11-06 08:47:04');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part_id` bigint(20) UNSIGNED NOT NULL,
  `change_quantity` int(11) NOT NULL,
  `movement_type` enum('sale','purchase','return','adjust','damage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sale',
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_movements`
--

INSERT INTO `inventory_movements` (`id`, `part_id`, `change_quantity`, `movement_type`, `reference_id`, `reference_type`, `user_id`, `notes`, `timestamp`, `created_at`, `updated_at`) VALUES
(1, 39, -1, 'sale', 51, NULL, 1, NULL, '2025-11-04 09:24:17', '2025-11-04 09:24:17', '2025-11-04 09:24:17'),
(2, 2, -1, 'sale', 52, NULL, 2, NULL, '2025-11-06 02:46:33', '2025-11-06 02:46:33', '2025-11-06 02:46:33'),
(3, 17, -1, 'sale', 53, NULL, 2, NULL, '2025-11-06 03:04:02', '2025-11-06 03:04:02', '2025-11-06 03:04:02'),
(4, 2, -1, 'sale', 54, NULL, 2, NULL, '2025-11-06 03:14:20', '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(5, 11, -1, 'sale', 54, NULL, 2, NULL, '2025-11-06 03:14:20', '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(6, 30, -1, 'sale', 54, NULL, 2, NULL, '2025-11-06 03:14:20', '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(7, 4, -1, 'sale', 54, NULL, 2, NULL, '2025-11-06 03:14:20', '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(8, 3, -1, 'sale', 54, NULL, 2, NULL, '2025-11-06 03:14:20', '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(9, 33, -1, 'sale', 54, NULL, 2, NULL, '2025-11-06 03:14:20', '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(10, 36, -1, 'sale', 54, NULL, 2, NULL, '2025-11-06 03:14:20', '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(11, 41, -1, 'sale', 55, NULL, 2, NULL, '2025-11-06 03:57:45', '2025-11-06 03:57:45', '2025-11-06 03:57:45'),
(12, 41, -1, 'sale', 56, NULL, 1, NULL, '2025-11-06 08:47:04', '2025-11-06 08:47:04', '2025-11-06 08:47:04'),
(13, 17, -1, 'sale', 57, NULL, 1, NULL, '2025-11-06 09:26:58', '2025-11-06 09:26:58', '2025-11-06 09:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_vehicle_model`
--

CREATE TABLE `inventory_vehicle_model` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_model_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_vehicle_model`
--

INSERT INTO `inventory_vehicle_model` (`id`, `inventory_id`, `vehicle_model_id`, `created_at`, `updated_at`) VALUES
(1, 41, 8, '2025-11-06 03:57:30', '2025-11-06 03:57:30'),
(2, 41, 7, '2025-11-06 03:57:30', '2025-11-06 03:57:30'),
(3, 41, 6, '2025-11-06 03:57:30', '2025-11-06 03:57:30'),
(4, 41, 9, '2025-11-06 03:57:30', '2025-11-06 03:57:30'),
(5, 41, 18, '2025-11-06 03:57:30', '2025-11-06 03:57:30'),
(6, 41, 22, '2025-11-06 03:57:30', '2025-11-06 03:57:30'),
(7, 41, 21, '2025-11-06 03:57:30', '2025-11-06 03:57:30'),
(8, 41, 20, '2025-11-06 03:57:30', '2025-11-06 03:57:30'),
(9, 41, 19, '2025-11-06 03:57:30', '2025-11-06 03:57:30'),
(10, 41, 23, '2025-11-06 03:57:30', '2025-11-06 03:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_03_165916_create_permission_tables', 1),
(5, '2025_11_03_165934_update_users_table_for_pin_auth', 1),
(6, '2025_11_03_165952_create_categories_table', 1),
(7, '2025_11_03_165957_create_brands_table', 1),
(8, '2025_11_03_170001_create_vehicle_makes_table', 1),
(9, '2025_11_03_170005_create_vehicle_models_table', 1),
(10, '2025_11_03_170009_create_inventory_table', 1),
(11, '2025_11_03_170013_create_customers_table', 1),
(12, '2025_11_03_170017_create_sales_table', 1),
(13, '2025_11_03_170021_create_sale_items_table', 1),
(14, '2025_11_03_170025_create_payments_table', 1),
(15, '2025_11_03_170030_create_inventory_movements_table', 1),
(16, '2025_11_03_170034_create_returns_table', 1),
(17, '2025_11_03_170039_create_price_history_table', 1),
(18, '2025_11_03_170042_create_settings_table', 1),
(19, '2025_11_03_182118_create_work_orders_table', 1),
(20, '2025_11_03_182337_update_returns_table_structure', 1),
(21, '2025_11_03_184117_add_barcode_to_inventory_table', 1),
(22, '2025_11_04_070516_create_sessions_table', 1),
(23, '2025_11_04_071101_update_sales_payment_status_enum', 1),
(24, '2025_11_04_071637_update_payments_payment_method_enum', 1),
(25, '2025_11_04_073828_create_inventory_vehicle_model_pivot_table', 1),
(26, '2025_11_04_112009_create_pending_payments_table', 1),
(27, '2025_11_04_130553_fix_username_column_length_in_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method` enum('Cash','M-Pesa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `amount` decimal(10,2) NOT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `sale_id`, `payment_method`, `amount`, `transaction_reference`, `payment_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'M-Pesa', 124700.00, 'MP0003493099', '2025-09-17 12:55:19', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(2, 2, 'M-Pesa', 69129.00, 'MP0009567520', '2025-09-09 19:24:19', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(3, 3, 'Cash', 156600.00, NULL, '2025-10-11 14:25:19', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(4, 4, 'M-Pesa', 35960.00, 'MP0008825679', '2025-10-08 11:08:19', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(5, 5, 'Cash', 1856.00, NULL, '2025-10-23 15:13:19', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(6, 6, 'Cash', 153120.00, NULL, '2025-09-16 21:43:19', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(7, 7, 'M-Pesa', 2088.00, 'MP0001807162', '2025-10-31 23:21:20', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(8, 8, 'M-Pesa', 75400.00, 'MP0005543489', '2025-09-29 17:49:20', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(9, 9, 'M-Pesa', 167645.00, 'MP0003484738', '2025-09-25 12:10:20', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(10, 10, 'M-Pesa', 86373.00, 'MP0005272103', '2025-10-23 07:25:20', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(11, 11, 'M-Pesa', 45820.00, 'MP0009472913', '2025-10-16 14:39:20', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(12, 12, 'Cash', 159119.00, NULL, '2025-10-29 13:24:20', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(13, 13, 'M-Pesa', 88624.00, 'MP0001742909', '2025-11-01 21:20:20', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(14, 14, 'M-Pesa', 8700.00, 'MP0004350849', '2025-09-04 19:51:21', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(15, 15, 'M-Pesa', 14191.00, 'MP0003613141', '2025-09-26 01:37:21', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(16, 16, 'M-Pesa', 6960.00, 'MP0002225047', '2025-10-24 16:56:21', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(17, 17, 'Cash', 30160.00, NULL, '2025-10-04 01:05:21', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(18, 18, 'Cash', 73776.00, NULL, '2025-11-01 03:33:21', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(19, 19, 'M-Pesa', 176553.00, 'MP0001945973', '2025-10-06 22:58:21', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(20, 20, 'Cash', 128760.00, NULL, '2025-09-16 01:37:21', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(21, 21, 'Cash', 143212.00, NULL, '2025-09-21 13:10:21', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(22, 22, 'M-Pesa', 30057.00, 'MP0006174925', '2025-09-15 08:29:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(23, 23, 'M-Pesa', 64534.00, 'MP0003924286', '2025-11-02 21:34:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(24, 24, 'M-Pesa', 87232.00, 'MP0004359683', '2025-10-02 21:25:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(25, 25, 'M-Pesa', 116000.00, 'MP0004140182', '2025-09-09 10:45:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(26, 26, 'Cash', 1629.00, NULL, '2025-09-23 20:28:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(27, 27, 'M-Pesa', 89896.00, 'MP0001312378', '2025-10-07 18:43:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(28, 28, 'M-Pesa', 241147.00, 'MP0002124191', '2025-09-14 00:43:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(29, 29, 'M-Pesa', 132612.00, 'MP0008955924', '2025-10-07 04:37:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(30, 30, 'M-Pesa', 24592.00, 'MP0005211510', '2025-10-07 19:18:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(31, 31, 'M-Pesa', 63356.00, 'MP0007337917', '2025-10-08 22:12:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(32, 32, 'Cash', 51603.00, NULL, '2025-10-30 00:02:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(33, 33, 'Cash', 98020.00, NULL, '2025-09-07 15:26:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(34, 34, 'M-Pesa', 36447.00, 'MP0003142225', '2025-10-19 01:32:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(35, 35, 'Cash', 85795.00, NULL, '2025-09-16 04:07:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(36, 36, 'M-Pesa', 67478.00, 'MP0002611465', '2025-09-23 00:36:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(37, 37, 'M-Pesa', 67280.00, 'MP0004002749', '2025-09-06 13:11:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(38, 38, 'Cash', 30147.00, NULL, '2025-10-12 14:56:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(39, 39, 'M-Pesa', 97819.00, 'MP0008264734', '2025-10-17 05:34:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(40, 40, 'M-Pesa', 80388.00, 'MP0007260201', '2025-09-12 08:14:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(41, 41, 'M-Pesa', 16704.00, 'MP0005500369', '2025-09-16 18:24:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(42, 42, 'M-Pesa', 71269.00, 'MP0009316942', '2025-10-02 19:07:22', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(43, 43, 'Cash', 216688.00, NULL, '2025-10-28 23:06:23', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(44, 44, 'Cash', 203000.00, NULL, '2025-10-08 19:13:23', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(45, 45, 'Cash', 117160.00, NULL, '2025-10-23 19:38:23', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(46, 46, 'M-Pesa', 168585.00, 'MP0002050866', '2025-10-02 11:43:23', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(47, 47, 'Cash', 20750.00, NULL, '2025-11-03 07:59:23', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(48, 48, 'Cash', 48720.00, NULL, '2025-09-21 08:37:23', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(49, 49, 'M-Pesa', 40600.00, 'MP0004176833', '2025-11-01 21:37:23', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(50, 50, 'M-Pesa', 124700.00, 'MP0009284277', '2025-10-11 14:54:23', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(51, 51, 'M-Pesa', 1500.00, 'ws_CO_04112025162352640723014032', '2025-11-04 13:24:17', '2025-11-04 09:24:17', '2025-11-04 09:24:17'),
(52, 52, 'Cash', 600.00, NULL, '2025-11-06 06:46:33', '2025-11-06 02:46:33', '2025-11-06 02:46:33'),
(53, 53, 'Cash', 9500.00, NULL, '2025-11-06 07:04:02', '2025-11-06 03:04:02', '2025-11-06 03:04:02'),
(54, 54, 'Cash', 92100.00, NULL, '2025-11-06 07:14:20', '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(55, 55, 'Cash', 18000.00, NULL, '2025-11-06 07:57:45', '2025-11-06 03:57:45', '2025-11-06 03:57:45'),
(56, 56, 'Cash', 18000.00, NULL, '2025-11-06 12:47:04', '2025-11-06 08:47:04', '2025-11-06 08:47:04'),
(57, 57, 'Cash', 9500.00, NULL, '2025-11-06 13:26:58', '2025-11-06 09:26:58', '2025-11-06 09:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `pending_payments`
--

CREATE TABLE `pending_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `account_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'C2B',
  `status` enum('pending','allocated','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `transaction_date` datetime NOT NULL,
  `raw_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage inventory', 'web', '2025-11-04 09:20:10', '2025-11-04 09:20:10'),
(2, 'view sales', 'web', '2025-11-04 09:20:10', '2025-11-04 09:20:10'),
(3, 'create sales', 'web', '2025-11-04 09:20:10', '2025-11-04 09:20:10'),
(4, 'edit sales', 'web', '2025-11-04 09:20:10', '2025-11-04 09:20:10'),
(5, 'delete sales', 'web', '2025-11-04 09:20:10', '2025-11-04 09:20:10'),
(6, 'view reports', 'web', '2025-11-04 09:20:10', '2025-11-04 09:20:10'),
(7, 'manage users', 'web', '2025-11-04 09:20:10', '2025-11-04 09:20:10'),
(8, 'manage settings', 'web', '2025-11-04 09:20:10', '2025-11-04 09:20:10'),
(9, 'view customers', 'web', '2025-11-04 09:20:10', '2025-11-04 09:20:10'),
(10, 'manage customers', 'web', '2025-11-04 09:20:11', '2025-11-04 09:20:11'),
(11, 'manage returns', 'web', '2025-11-04 09:20:11', '2025-11-04 09:20:11'),
(12, 'process payments', 'web', '2025-11-04 09:20:11', '2025-11-04 09:20:11');

-- --------------------------------------------------------

--
-- Table structure for table `price_history`
--

CREATE TABLE `price_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `part_id` bigint(20) UNSIGNED NOT NULL,
  `old_price` decimal(10,2) NOT NULL,
  `new_price` decimal(10,2) NOT NULL,
  `changed_by` bigint(20) UNSIGNED NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `sale_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `part_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_returned` int(11) NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','credited') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-11-04 09:20:11', '2025-11-04 09:20:11'),
(2, 'cashier', 'web', '2025-11-04 09:20:11', '2025-11-04 09:20:11');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(1, 2),
(2, 2),
(3, 2),
(9, 2),
(10, 2),
(12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','partial','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_number`, `customer_id`, `user_id`, `date`, `subtotal`, `tax`, `discount`, `total_amount`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 'INV-202509-0001', 35, 1, '2025-09-17', 107500.00, 17200.00, 0.00, 124700.00, 'completed', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(2, 'INV-202509-0002', NULL, 2, '2025-09-09', 61000.00, 9760.00, 1631.00, 69129.00, 'completed', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(3, 'INV-202510-0001', 13, 1, '2025-10-11', 135000.00, 21600.00, 0.00, 156600.00, 'completed', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(4, 'INV-202510-0002', NULL, 1, '2025-10-08', 31000.00, 4960.00, 0.00, 35960.00, 'completed', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(5, 'INV-202510-0003', 8, 1, '2025-10-23', 1600.00, 256.00, 0.00, 1856.00, 'completed', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(6, 'INV-202509-0003', 31, 2, '2025-09-16', 132000.00, 21120.00, 0.00, 153120.00, 'completed', '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(7, 'INV-202510-0004', NULL, 1, '2025-10-31', 1800.00, 288.00, 0.00, 2088.00, 'completed', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(8, 'INV-202509-0004', NULL, 2, '2025-09-29', 65000.00, 10400.00, 0.00, 75400.00, 'completed', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(9, 'INV-202509-0005', NULL, 2, '2025-09-25', 146200.00, 23392.00, 1947.00, 167645.00, 'completed', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(10, 'INV-202510-0005', 29, 1, '2025-10-23', 75500.00, 12080.00, 1207.00, 86373.00, 'completed', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(11, 'INV-202510-0006', 16, 1, '2025-10-16', 39500.00, 6320.00, 0.00, 45820.00, 'completed', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(12, 'INV-202510-0007', 7, 1, '2025-10-29', 138500.00, 22160.00, 1541.00, 159119.00, 'completed', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(13, 'INV-202511-0001', 2, 2, '2025-11-01', 76400.00, 12224.00, 0.00, 88624.00, 'completed', '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(14, 'INV-202509-0006', NULL, 1, '2025-09-04', 7500.00, 1200.00, 0.00, 8700.00, 'completed', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(15, 'INV-202509-0007', 13, 1, '2025-09-26', 13000.00, 2080.00, 889.00, 14191.00, 'completed', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(16, 'INV-202510-0008', 25, 1, '2025-10-24', 6000.00, 960.00, 0.00, 6960.00, 'completed', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(17, 'INV-202510-0009', NULL, 1, '2025-10-04', 26000.00, 4160.00, 0.00, 30160.00, 'completed', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(18, 'INV-202511-0002', NULL, 1, '2025-11-01', 63600.00, 10176.00, 0.00, 73776.00, 'completed', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(19, 'INV-202510-0010', 29, 1, '2025-10-06', 153900.00, 24624.00, 1971.00, 176553.00, 'completed', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(20, 'INV-202509-0008', NULL, 1, '2025-09-16', 111000.00, 17760.00, 0.00, 128760.00, 'completed', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(21, 'INV-202509-0009', 14, 2, '2025-09-21', 125000.00, 20000.00, 1788.00, 143212.00, 'completed', '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(22, 'INV-202509-0010', 28, 2, '2025-09-15', 27000.00, 4320.00, 1263.00, 30057.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(23, 'INV-202511-0003', NULL, 1, '2025-11-02', 56700.00, 9072.00, 1238.00, 64534.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(24, 'INV-202510-0011', NULL, 2, '2025-10-02', 75200.00, 12032.00, 0.00, 87232.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(25, 'INV-202509-0011', 33, 2, '2025-09-09', 100000.00, 16000.00, 0.00, 116000.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(26, 'INV-202509-0012', NULL, 2, '2025-09-23', 3000.00, 480.00, 1851.00, 1629.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(27, 'INV-202510-0012', NULL, 2, '2025-10-07', 79100.00, 12656.00, 1860.00, 89896.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(28, 'INV-202509-0013', 1, 1, '2025-09-14', 209000.00, 33440.00, 1293.00, 241147.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(29, 'INV-202510-0013', 8, 1, '2025-10-07', 116000.00, 18560.00, 1948.00, 132612.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(30, 'INV-202510-0014', 17, 2, '2025-10-07', 21200.00, 3392.00, 0.00, 24592.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(31, 'INV-202510-0015', 14, 2, '2025-10-08', 55900.00, 8944.00, 1488.00, 63356.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(32, 'INV-202510-0016', NULL, 1, '2025-10-30', 45000.00, 7200.00, 597.00, 51603.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(33, 'INV-202509-0014', NULL, 1, '2025-09-07', 84500.00, 13520.00, 0.00, 98020.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(34, 'INV-202510-0017', 22, 1, '2025-10-19', 32500.00, 5200.00, 1253.00, 36447.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(35, 'INV-202509-0015', 28, 2, '2025-09-16', 75000.00, 12000.00, 1205.00, 85795.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(36, 'INV-202509-0016', 23, 2, '2025-09-23', 59000.00, 9440.00, 962.00, 67478.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(37, 'INV-202509-0017', NULL, 2, '2025-09-06', 58000.00, 9280.00, 0.00, 67280.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(38, 'INV-202510-0018', 32, 2, '2025-10-12', 27500.00, 4400.00, 1753.00, 30147.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(39, 'INV-202510-0019', 12, 2, '2025-10-17', 84800.00, 13568.00, 549.00, 97819.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(40, 'INV-202509-0018', NULL, 2, '2025-09-12', 69300.00, 11088.00, 0.00, 80388.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(41, 'INV-202509-0019', NULL, 2, '2025-09-16', 14400.00, 2304.00, 0.00, 16704.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(42, 'INV-202510-0020', 3, 1, '2025-10-02', 63000.00, 10080.00, 1811.00, 71269.00, 'completed', '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(43, 'INV-202510-0021', 32, 2, '2025-10-28', 186800.00, 29888.00, 0.00, 216688.00, 'completed', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(44, 'INV-202510-0022', 22, 1, '2025-10-08', 175000.00, 28000.00, 0.00, 203000.00, 'completed', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(45, 'INV-202510-0023', 10, 2, '2025-10-23', 101000.00, 16160.00, 0.00, 117160.00, 'completed', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(46, 'INV-202510-0024', 13, 1, '2025-10-02', 146000.00, 23360.00, 775.00, 168585.00, 'completed', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(47, 'INV-202511-0004', 7, 1, '2025-11-03', 19400.00, 3104.00, 1754.00, 20750.00, 'completed', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(48, 'INV-202509-0020', 28, 1, '2025-09-21', 42000.00, 6720.00, 0.00, 48720.00, 'completed', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(49, 'INV-202511-0005', 20, 2, '2025-11-01', 35000.00, 5600.00, 0.00, 40600.00, 'completed', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(50, 'INV-202510-0025', 12, 1, '2025-10-11', 107500.00, 17200.00, 0.00, 124700.00, 'completed', '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(51, 'INV-202511-0026', NULL, 1, '2025-11-04', 1500.00, 0.00, 0.00, 1500.00, 'completed', '2025-11-04 09:24:17', '2025-11-04 09:24:17'),
(52, 'INV-202511-0027', NULL, 2, '2025-11-06', 600.00, 0.00, 0.00, 600.00, 'completed', '2025-11-06 02:46:33', '2025-11-06 02:46:33'),
(53, 'INV-202511-0028', NULL, 2, '2025-11-06', 9500.00, 0.00, 0.00, 9500.00, 'completed', '2025-11-06 03:04:02', '2025-11-06 03:04:02'),
(54, 'INV-202511-0029', NULL, 2, '2025-11-06', 92100.00, 0.00, 0.00, 92100.00, 'completed', '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(55, 'INV-202511-0030', NULL, 2, '2025-11-06', 18000.00, 0.00, 0.00, 18000.00, 'completed', '2025-11-06 03:57:45', '2025-11-06 03:57:45'),
(56, 'INV-202511-0031', NULL, 1, '2025-11-06', 18000.00, 0.00, 0.00, 18000.00, 'completed', '2025-11-06 08:47:04', '2025-11-06 08:47:04'),
(57, 'INV-202511-0032', NULL, 1, '2025-11-06', 9500.00, 0.00, 0.00, 9500.00, 'completed', '2025-11-06 09:26:58', '2025-11-06 09:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `part_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `part_id`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 4, 6000.00, 24000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(2, 1, 17, 5, 9500.00, 47500.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(3, 1, 23, 3, 2500.00, 7500.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(4, 1, 35, 3, 9500.00, 28500.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(5, 2, 4, 1, 7500.00, 7500.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(6, 2, 23, 2, 2500.00, 5000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(7, 2, 29, 2, 5000.00, 10000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(8, 2, 35, 2, 9500.00, 19000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(9, 2, 37, 3, 6500.00, 19500.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(10, 3, 36, 3, 45000.00, 135000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(11, 4, 16, 4, 6500.00, 26000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(12, 4, 19, 1, 5000.00, 5000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(13, 5, 24, 2, 800.00, 1600.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(14, 6, 3, 1, 4000.00, 4000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(15, 6, 6, 4, 6000.00, 24000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(16, 6, 15, 4, 13000.00, 52000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(17, 6, 30, 4, 6000.00, 24000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(18, 6, 34, 1, 28000.00, 28000.00, '2025-11-04 09:20:19', '2025-11-04 09:20:19'),
(19, 7, 2, 1, 600.00, 600.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(20, 7, 14, 1, 1200.00, 1200.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(21, 8, 15, 5, 13000.00, 65000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(22, 9, 6, 2, 6000.00, 12000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(23, 9, 8, 5, 15000.00, 75000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(24, 9, 34, 2, 28000.00, 56000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(25, 9, 40, 4, 800.00, 3200.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(26, 10, 18, 4, 12000.00, 48000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(27, 10, 37, 4, 6500.00, 26000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(28, 10, 39, 1, 1500.00, 1500.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(29, 11, 2, 1, 600.00, 600.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(30, 11, 4, 2, 7500.00, 15000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(31, 11, 7, 2, 9500.00, 19000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(32, 11, 24, 3, 800.00, 2400.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(33, 11, 38, 1, 2500.00, 2500.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(34, 12, 3, 3, 4000.00, 12000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(35, 12, 13, 3, 20000.00, 60000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(36, 12, 16, 5, 6500.00, 32500.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(37, 12, 24, 5, 800.00, 4000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(38, 12, 30, 5, 6000.00, 30000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(39, 13, 18, 4, 12000.00, 48000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(40, 13, 24, 1, 800.00, 800.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(41, 13, 30, 1, 6000.00, 6000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(42, 13, 32, 1, 20000.00, 20000.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(43, 13, 40, 2, 800.00, 1600.00, '2025-11-04 09:20:20', '2025-11-04 09:20:20'),
(44, 14, 39, 5, 1500.00, 7500.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(45, 15, 1, 5, 800.00, 4000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(46, 15, 5, 4, 1500.00, 6000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(47, 15, 20, 1, 3000.00, 3000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(48, 16, 30, 1, 6000.00, 6000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(49, 17, 6, 1, 6000.00, 6000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(50, 17, 8, 1, 15000.00, 15000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(51, 17, 38, 2, 2500.00, 5000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(52, 18, 7, 5, 9500.00, 47500.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(53, 18, 20, 2, 3000.00, 6000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(54, 18, 22, 3, 1200.00, 3600.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(55, 18, 25, 1, 6500.00, 6500.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(56, 19, 2, 4, 600.00, 2400.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(57, 19, 4, 3, 7500.00, 22500.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(58, 19, 8, 3, 15000.00, 45000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(59, 19, 13, 3, 20000.00, 60000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(60, 19, 30, 4, 6000.00, 24000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(61, 20, 3, 5, 4000.00, 20000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(62, 20, 15, 2, 13000.00, 26000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(63, 20, 26, 5, 4000.00, 20000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(64, 20, 27, 3, 15000.00, 45000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(65, 21, 3, 3, 4000.00, 12000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(66, 21, 4, 4, 7500.00, 30000.00, '2025-11-04 09:20:21', '2025-11-04 09:20:21'),
(67, 21, 9, 4, 20000.00, 80000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(68, 21, 39, 2, 1500.00, 3000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(69, 22, 18, 1, 12000.00, 12000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(70, 22, 19, 3, 5000.00, 15000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(71, 23, 11, 2, 14000.00, 28000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(72, 23, 15, 1, 13000.00, 13000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(73, 23, 22, 5, 1200.00, 6000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(74, 23, 24, 4, 800.00, 3200.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(75, 23, 37, 1, 6500.00, 6500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(76, 24, 6, 2, 6000.00, 12000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(77, 24, 7, 5, 9500.00, 47500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(78, 24, 14, 4, 1200.00, 4800.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(79, 24, 31, 1, 8500.00, 8500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(80, 24, 40, 3, 800.00, 2400.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(81, 25, 9, 5, 20000.00, 100000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(82, 26, 5, 2, 1500.00, 3000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(83, 27, 14, 3, 1200.00, 3600.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(84, 27, 15, 5, 13000.00, 65000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(85, 27, 21, 3, 1500.00, 4500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(86, 27, 22, 5, 1200.00, 6000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(87, 28, 7, 4, 9500.00, 38000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(88, 28, 25, 5, 6500.00, 32500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(89, 28, 28, 4, 28000.00, 112000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(90, 28, 35, 2, 9500.00, 19000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(91, 28, 39, 5, 1500.00, 7500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(92, 29, 3, 1, 4000.00, 4000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(93, 29, 7, 1, 9500.00, 9500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(94, 29, 21, 1, 1500.00, 1500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(95, 29, 28, 2, 28000.00, 56000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(96, 29, 36, 1, 45000.00, 45000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(97, 30, 24, 4, 800.00, 3200.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(98, 30, 30, 3, 6000.00, 18000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(99, 31, 1, 3, 800.00, 2400.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(100, 31, 17, 5, 9500.00, 47500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(101, 31, 22, 5, 1200.00, 6000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(102, 32, 8, 3, 15000.00, 45000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(103, 33, 3, 1, 4000.00, 4000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(104, 33, 15, 3, 13000.00, 39000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(105, 33, 31, 4, 8500.00, 34000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(106, 33, 39, 5, 1500.00, 7500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(107, 34, 37, 5, 6500.00, 32500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(108, 35, 27, 2, 15000.00, 30000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(109, 35, 36, 1, 45000.00, 45000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(110, 36, 6, 5, 6000.00, 30000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(111, 36, 23, 3, 2500.00, 7500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(112, 36, 25, 1, 6500.00, 6500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(113, 36, 27, 1, 15000.00, 15000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(114, 37, 15, 1, 13000.00, 13000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(115, 37, 27, 3, 15000.00, 45000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(116, 38, 19, 3, 5000.00, 15000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(117, 38, 38, 5, 2500.00, 12500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(118, 39, 7, 5, 9500.00, 47500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(119, 39, 14, 4, 1200.00, 4800.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(120, 39, 16, 5, 6500.00, 32500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(121, 40, 12, 2, 8500.00, 17000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(122, 40, 22, 4, 1200.00, 4800.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(123, 40, 35, 5, 9500.00, 47500.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(124, 41, 24, 3, 800.00, 2400.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(125, 41, 26, 3, 4000.00, 12000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(126, 42, 18, 4, 12000.00, 48000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(127, 42, 19, 3, 5000.00, 15000.00, '2025-11-04 09:20:22', '2025-11-04 09:20:22'),
(128, 43, 2, 3, 600.00, 1800.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(129, 43, 4, 4, 7500.00, 30000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(130, 43, 8, 2, 15000.00, 30000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(131, 43, 15, 5, 13000.00, 65000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(132, 43, 27, 4, 15000.00, 60000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(133, 44, 5, 5, 1500.00, 7500.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(134, 44, 12, 5, 8500.00, 42500.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(135, 44, 16, 2, 6500.00, 13000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(136, 44, 34, 4, 28000.00, 112000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(137, 45, 34, 2, 28000.00, 56000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(138, 45, 36, 1, 45000.00, 45000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(139, 46, 22, 5, 1200.00, 6000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(140, 46, 28, 5, 28000.00, 140000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(141, 47, 14, 2, 1200.00, 2400.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(142, 47, 18, 1, 12000.00, 12000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(143, 47, 19, 1, 5000.00, 5000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(144, 48, 17, 4, 9500.00, 38000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(145, 48, 40, 5, 800.00, 4000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(146, 49, 37, 5, 6500.00, 32500.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(147, 49, 38, 1, 2500.00, 2500.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(148, 50, 13, 2, 20000.00, 40000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(149, 50, 15, 5, 13000.00, 65000.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(150, 50, 38, 1, 2500.00, 2500.00, '2025-11-04 09:20:23', '2025-11-04 09:20:23'),
(151, 51, 39, 1, 1500.00, 1500.00, '2025-11-04 09:24:17', '2025-11-04 09:24:17'),
(152, 52, 2, 1, 600.00, 600.00, '2025-11-06 02:46:33', '2025-11-06 02:46:33'),
(153, 53, 17, 1, 9500.00, 9500.00, '2025-11-06 03:04:02', '2025-11-06 03:04:02'),
(154, 54, 2, 1, 600.00, 600.00, '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(155, 54, 11, 1, 14000.00, 14000.00, '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(156, 54, 30, 1, 6000.00, 6000.00, '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(157, 54, 4, 1, 7500.00, 7500.00, '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(158, 54, 3, 1, 4000.00, 4000.00, '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(159, 54, 33, 1, 15000.00, 15000.00, '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(160, 54, 36, 1, 45000.00, 45000.00, '2025-11-06 03:14:20', '2025-11-06 03:14:20'),
(161, 55, 41, 1, 18000.00, 18000.00, '2025-11-06 03:57:45', '2025-11-06 03:57:45'),
(162, 56, 41, 1, 18000.00, 18000.00, '2025-11-06 08:47:04', '2025-11-06 08:47:04'),
(163, 57, 17, 1, 9500.00, 9500.00, '2025-11-06 09:26:58', '2025-11-06 09:26:58');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2URDszdrPkxwTq477KSWg45U98obL8gbOvzp5ph1', NULL, '44.255.52.152', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWViZk5SY0p2QXh6T0JuRTBOVXV3V0cwclhXRUhiNkdiSTNraFVoTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly93d3cucG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762435058),
('3eVt5AcPosqJTWuRA2USe6C4kBovOJSgKoBpAVpq', NULL, '64.15.129.109', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib3RvWUtOQ1FJWlE0YVh2WTNmQUJSTlhXUVg5dm0walV4UVlIa1Q0SiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762437502),
('5N26wpjzYTJm73F8JoK1hhSXNKJImACVyrp65qOM', NULL, '18.246.229.62', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G965U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.111 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmJMRjFTRXQ2TkgzamJiNVEzc2l1YmpmY1NDQllsOHhDaXE1eG95NCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762435057),
('7b3HDsjTGswDkbTgyVXYpctTuozhqYGPmdyzF9pa', NULL, '18.235.110.182', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWVMzSzhXWEhoTFNJUEU2cXJ3OENoTjE4cFRFdmNkU0k3TkU0ejNoTCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vd3d3LnBvcy5qb2hsbHlhdXRvc3BhcmVzLmNvLmtlIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762434121),
('7bns2oHw3xLjtHurodDgW9D3chjBX5CfVGjRRaT2', NULL, '44.255.52.152', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1RxZXhXODc5ckRrQUw5Y1pOU0RoTTFhWW55T0xBSjVCc05ON3lqVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762435057),
('AdbpFGLi1C9aE2usTecrCg552087uwmlnBClkrv4', NULL, '197.136.9.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXpkaVpZNmNxbzdMVDRjYVp3dkNrUllmazNSOUI2WEMzMlh0dTlmQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762433199),
('AEUcNu9gmmAfplQ0AHoXSLP68XPtNfJ6heXC0Fbk', NULL, '205.169.39.196', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.61 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0xRRjN6NFphZzNPZ3duRXRFaEJXd01WUEZtSHhiSlAwYnJuZnVCbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762434435),
('aJdJXN06QBCND1aGhptbGElqDxLyWprvSt7f7s3n', NULL, '18.237.41.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3B0ajNjZG5MNmJnd0s5SE9mdjJIVjRxYTBGWk43WDAybUk3VG9zeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vd3d3LnBvcy5waXBkb3RmeC5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762433483),
('b7B5MI8oHBA0nIonMk9uibZcZOg7Myj2mc6PEuFl', NULL, '44.255.52.152', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ0M0b3NQY3U3Z3ZaS01CQzQycnRhOUtHNU5FbDE2dUdYeFJQcDVFNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly93d3cucG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762435055),
('ChKa1FqUEvvfRyjB534oZjNssfvv7BtSEnAgmYis', NULL, '52.38.166.98', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G965U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.111 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3BjVEl3b2duc3RsNlczVW1VMkljWkpybVMwU2FybFRLS0Y2N1hleCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHA6Ly9wb3MucGlwZG90ZnguY29tIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762431891),
('DNnVfyinPG7jtHbiHSrI9jWX6PsGY5t5bAZvRu4n', NULL, '54.149.35.233', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidU1ORDhCNm01NVdsMnRXTENmWWp6NzV2dTB0SW8wWEl0OVFVUVVGSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vd3d3LnBvcy5qb2hsbHlhdXRvc3BhcmVzLmNvLmtlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762436606),
('EvfuvC9gA1m6qP316GhbxygKucBbYaO76wPJBVYF', NULL, '23.27.145.51', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGJJZ242dUR4bXk3QVZTRVdtRFdhd3pCcjRuMkNXREhQTkdKREZRRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vd3d3LnBvcy5qb2hsbHlhdXRvc3BhcmVzLmNvLmtlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762435702),
('F4FSmC8UMcoZfBgL1xlJGegSQIzVeVTrvpQMUKXO', NULL, '51.77.212.12', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.71 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiemp1NlRMUHRtcTEzY1lkMU5xWWRSUXBySW16SWl3U3VnQUdXMDV6byI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTc6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvcHVibGljL2luZGV4LnBocC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6NDQ6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvZGFzaGJvYXJkIjt9fQ==', 1762434793),
('FUjSuVuogkImKjTQ7PsT4VWisaZtXNaGT1qZLGfu', NULL, '44.255.123.241', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G965U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.111 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM0JUMmJkamR3cEVVTlZOdXlMb2Ryc1c1MEdRMXhsVTV2c0o0VXZrVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762437035),
('G4HnuMMAbi8eOKJyxNr4vaZnyQrs7Z2eYTgBgx3a', NULL, '44.249.121.57', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G965U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.111 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmdUQkk2aEt4djJEdExZOVFyV0JZc1FxZUphNURXbGE4M1pMQ3RxVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8vcG9zLnBpcGRvdGZ4LmNvbS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762432211),
('hfVhI2IuHdKVi7FagsBnBNWYyjEAny3VvGaRnfEC', NULL, '18.235.110.182', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUxjSHJ2STVZMkZVeFdsTFo4TmlRUlltcWxrbVNiQzRQazAzVVFaMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vd3d3LnBvcy5qb2hsbHlhdXRvc3BhcmVzLmNvLmtlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762434123),
('HLdtGzzQZciJ9oF8pd0pbnTGLCkJ7hJ7cMMFLfUn', NULL, '35.204.135.22', 'Scrapy/2.13.3 (+https://scrapy.org)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVlpZnkwdEJHbDdBVmM3NkxVWE9OSm5mOGtFdXNNNW16WDJEempTOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762436038),
('ibU7uUridJCSntbBKw879Rbkt0MQ75tW7H7cpmDH', NULL, '172.233.62.72', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36 Vivaldi/6.1.3035.300', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicGs2QVVxblNnOVNXYmhzSTRpSm93eDkyanBGbGtqVEZvcVJRdHlOSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762440454),
('Je5956nc35ajTGYGt2861VuZOHknP3636UKcn5DE', NULL, '205.169.39.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.5938.132 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYXMwYjQ3NVlhWWJwakRXT0kxeFVLbGtleDR4ejU5RUphb3dWazdvbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762433327),
('JHTo1x5cHZwOxNyCUde38mKlj1wSIX5Cc3x9I97j', NULL, '3.233.59.216', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYW9NVjlTSlJpdGFCSTBMYm9vYTMyN2JJU0Q2QXYzdEpNcEFsdXIyRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762434314),
('KCVEHNUnagsT87qM18hGPSL25qaVOeylHl9xP0P4', NULL, '64.15.129.126', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaXhobkJzbGJvdXZONzJNcURpeE51VlBaOFJHY1BrQzJXV0oxcW5nTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762437493),
('Ke6tF94XI5am2oIdHxdM7TQw0bNZ7vNmNgA51aYj', NULL, '205.169.39.196', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.79 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNDVTV0ZINVd1QVF5SGZpNTBERnk5VnNrUUZ4cWY4Z05tYUZGeHpBRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762434448),
('KpdkIADaxgpKwiEDhYiWEN5qPFuQxShIleXDe8aJ', NULL, '18.246.229.62', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G965U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.111 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNW5ldmVmN2FPMG5RSml0aXIwcUtiaE16ZGw0a0NFRHl3UEJ1bUk0QiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762435056),
('lkKQ4tfqXuLMRDE7SeL8KHFe80rJjTbD77Dm6CNS', NULL, '23.27.145.204', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSG5RdVl3RmdBbGdpamJ0aWhvVWp0VEFWNXBaQ21VUXpDM1hVbjBHTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vd3d3LnBvcy5qb2hsbHlhdXRvc3BhcmVzLmNvLmtlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762440641),
('LmTHtc4lQ2dtL2XJXkr404K17VMKR5sU3bCleXVP', NULL, '35.204.135.22', 'Scrapy/2.13.3 (+https://scrapy.org)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUmhIUEF4Rkh0cFdaclc3bHlPQlpQNFFlQlR3SGozR3J4eVhPOFRPcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vd3d3LnBvcy5qb2hsbHlhdXRvc3BhcmVzLmNvLmtlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762434840),
('LSkr4v68tSWNid1pNzzEyfFJZvVVcyrxVgo6PWJD', NULL, '199.45.155.84', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieXJJMmxGQ09CUzZ4QXBydm1NVXBpR25ZT2FvTzRkcmQ2dlFEZE9oZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762435086),
('mmU7aNO8ZuJsTcV9FhtLQLec8bqKDJr5xGCuA5o3', NULL, '23.27.145.123', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid29pYVdEbUFjOEQ0NXk3ZFM2c2c0TXFlWG1TeEszWk5uYXB4V3NnSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762435683),
('MtYtTwbuyPu48XE7Dq3JFxQUqwPvSxi1QWlLUG8o', NULL, '18.235.110.182', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTTBUTzcwUFdKVFdBT2xvOTVVdnBhcGxTWFFacHMxbEVvTHhBRGU0TSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762434309),
('mUHKmpW3w5lDSotjxqRaniWT4QAbJG9oMqq0cskl', 1, '105.161.162.162', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVEFlbGozOHA0NU5abGdvVXRzcjFWY1BYZTN0d1VUQkpFWFNhNFZQcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjY6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvcG9zL3NlYXJjaD9zZWFyY2g9NjIyMzAwMTU4MDI3MCI7czo1OiJyb3V0ZSI7czoxMDoicG9zLnNlYXJjaCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1762440672),
('MYnS36Ws5UB541FLIrsdz967UuIaGHjxCT3uU8xr', NULL, '44.255.123.241', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibGdXcGFVWTlUVjE2U1gyVm8weEE3R09JbHR1bW5za3ByWmVIZEM5byI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762437032),
('N4mFs0wJDKzX6BDiznfUjru72G191X02nkQSI5j0', NULL, '199.45.155.84', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoielFZM0ROTkdSbjh5U0RJSUN0UG1hb1BVS1BsZWhqTnU0eXh3V3dEbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762435078),
('OMI7CogM0tommRSrkNt8MLty7MSURkEZwTuhmwCT', NULL, '91.84.86.2', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWFGTDNtakVFd2hJWm50R2JCSXJmcVo0R282ejlxWFU3YklDMDBsUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762435120),
('oRyuzIdE2CgmK8T154n7al8wdNuPJSuNDqa0A3CD', NULL, '18.246.212.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFNrYmg3ZHhoZks1RWpDRUhmaXBuOWZETExUNFJsdEZEaHNCbTR3NCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762436435),
('PazPzdumokh2ptnTCSIC029yQvwzjBFEJNebCh5f', NULL, '44.255.52.152', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRk90bmlwWHZRRXBDWXRpZWF5ZjB1TjdqZzF5SEY4SW0xNnhWb1BWdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly93d3cucG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762435058),
('pSpjNaXZunRAIla45Qv3843cGSYsP6wncILNDO09', NULL, '172.233.62.72', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36 Vivaldi/6.1.3035.300', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieFJuSmZvZHJqSHlyME5sVW5mdEhhZ0VtaHZFSmdKOW1JM1VQUDBlcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762440454),
('pZE8VsA40OAOep0GNRCk6A6GcUeeWtKP6OqRzYod', NULL, '192.175.111.237', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid2JiQ0lJSURrbG5LN2RkcmhQaHpkWW1QS3dqZHJ3MWhzWTZCWUZTWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762437503),
('Q707pFuFu2DBXBkgtjthJPwvbw9LXul5JjTjJtgS', NULL, '199.45.154.140', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV0liSmVYck1WbEtRNk5LOXRnR3JhSTlnVWt1OVg4TEtYMFRGYUNmbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly93d3cucG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762436695),
('qEowTsItfcnKMUQFSjTw0Y1nj4DAFHFXIoBu6VdF', NULL, '64.15.129.126', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVngzZDJWQjNXQmh4MFRyeUoxNlVYN3J4M2w3TFJIWjliY3dvT2Q1UyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762437493),
('rf2SoV1gajaV8ebNqAe3qBM6ijfiRzv2UHgYvEHW', NULL, '192.175.111.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUVpwM2FBQ2s2M0xtdHlJTjVUMEVydFhJSlFJZ2U4TVNMN2duTEF0NSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762437494),
('RIwupmV1mN9DaQE2Ae31oCXkfW96SsIFKpFTxXD5', NULL, '199.45.154.147', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTTJjYUJ4c0xsSTdLMjczTk1qU3djM3p3dHNiZWp6bzNDekVyVWIxOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vd3d3LnBvcy5qb2hsbHlhdXRvc3BhcmVzLmNvLmtlIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762434585),
('Rm2h1KEN4CPjLXibk50AIOC59hbeyZa5cXI5VgWQ', NULL, '18.246.212.127', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G965U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.111 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1ZWZWZNeGlCbEFiWHc1ZkJIVUc3YjE1emFDNmR2bndodDBHSk9aQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762436439),
('rPV5ufS5fsONKC8hZhzOIAIi6FZUEZS1RbgTdw4e', NULL, '199.45.154.140', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWXZ0YjNWNWQ3dmMyaGplMzFyUU85ZzNKY0pFTXQwb0tDcDlVN1hVbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly93d3cucG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762436700),
('RxYWwwgO6eIBlllmbvqaWNNBAVhThutt4YNibRDL', NULL, '44.255.52.152', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN1JpbHlDY1ozV2Jjc0s5eXh6SmN0VWd2SHR0MFBTZ1U5aTM2MUtLeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly93d3cucG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762435056),
('s7t3JS4a5Dx7U7MhJKc7RxvDCNj5DTxQ5GGyzVIl', NULL, '18.246.229.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQm95VkJ3b2wxOXVDRDVnc0VDNmgxaGJSMlRiZ0N3Tks3SFA1aGwwQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762435057),
('Su4Uz70Pusddsb14aniALnX1oPLNksJcByXFFRcu', NULL, '192.175.111.237', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic2txME41TkJqcTVuVTVLaUZzcEFSREVkWWlhMjZudWJQRGVMYmN4ayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762437503),
('TjjfBB4bMH1SFZh4h98GKPDbSoK6LYMc96NtaZ9o', NULL, '192.175.111.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDZqeno3WFRVWlE4c2pBdlNzbHVKeXJGYktwWjhiUjVIMzhiRkFUcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762437502),
('u08083GN8n967Qpho0gApUwQnCawtfNd5xYyKzzJ', NULL, '192.175.111.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1FBeFR2WkhmaU1OSnNjdmt0UTB4d1Z2TTBTTnFZQUdrTmNCbU05ciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762437494),
('U0jW33okCWRyShNbJuMHA7KKGjUhP5gRuQMEWdPM', NULL, '34.220.239.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEM2R1UzVTJlMkZiSmtwQ0hlc1FQUTVLQVZhSklXUDFjRkF2M1QybCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly93d3cucG9zLnBpcGRvdGZ4LmNvbS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762432443),
('U7yHn93yIgZYsWSVyrBfpBoaxGRuhKVWYPGWwa5v', NULL, '52.38.166.98', 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G965U Build/R16NW) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.111 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEQ4MUFId0QwTHBQUDh4YlRpQWJvUWo2RkhsMjQ5UjFKb0VGWjdOOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9wb3MucGlwZG90ZnguY29tL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762431891),
('UsauKpn1miail7L0yuE7EfZvuN59Cr8onKdPQgX2', NULL, '199.45.154.147', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3NuUkF4UE5CcGw2NDk1ZkVWMzRDMmd5dER4RGl2TVVFZTZJOUUzNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vd3d3LnBvcy5qb2hsbHlhdXRvc3BhcmVzLmNvLmtlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762434603),
('VANLxAuMzz1JqQcDwn61vhd98LMWRTqE3zhjAFAK', NULL, '91.84.86.2', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkFCOUExSkxlNTlrRnp5OFVENnBRNXhlSk5rQVpEbHVTU2FBRW16RCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762435119),
('VAvn3xhsH0SkPhrpXlWyPFObIOr7CzcIQdFoJq6Y', NULL, '23.27.145.38', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEhWUHQ2WnNsWDlNYXFkMGtTejZwVzZLT1NvbFNpYVcxQktYZWI1VSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762440593),
('vCf7BjdvJwqjAqhKK1ZoO9xItvQS0qHtLExytsA1', NULL, '18.246.229.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia2FPNmxUaXBiSHlvME5udTEybnpwY2k2M25WQjFKS201bXpvUzZkcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762435056),
('wbO1vsZnFPbUi8WCqjmKEJGQdrC3xQ6PKNxinpNa', NULL, '44.255.52.152', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36 Edge/18.19582', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibkg0WFNIbTVWQ2toYU5CeEkzVmRMRzFBcWViU1d5NVJiY3NjRFJGTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762435056),
('WStGbiEmsQ3fp4HCHBwJy6NOoOGeL0kW3hStgquq', NULL, '64.15.129.109', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYmZacnhLRHdQRFpDbjNSSGRWRktTNU9LTjNZRm5xVFFPb3dJaEtqTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9wb3Muam9obGx5YXV0b3NwYXJlcy5jby5rZS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762437502),
('xfxbDPU6HGIaJvNrhExGZDK2YHgCYvpIVFyOubaH', NULL, '51.77.212.12', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.71 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidEdzS0NxWnpTWFE0WVd5MlpUUnA1eXdRRGc0SEV3WnBnRFdHOThjTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTc6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvcHVibGljL2luZGV4LnBocC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6NDQ6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvZGFzaGJvYXJkIjt9fQ==', 1762434792),
('XoFgND57X7b9vvVEq2AnJX3cgzE8HYwn51osNqK4', NULL, '192.175.111.252', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMFhWUFB1YURzTURlaHNOSHQ5b1pKT0tFam1JbndmS0xKWHhWS2dsYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vcG9zLmpvaGxseWF1dG9zcGFyZXMuY28ua2UvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1762437503),
('y98raVsQfC6rrpG8xT1u7j4mA1OWnsS8M1B5JJqm', NULL, '34.220.239.94', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmgzS2NGMU5aYmVxN09BSXJaYk9wTk5tZ2hhV3dHZHB6TFBUMldHWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly93d3cucG9zLnBpcGRvdGZ4LmNvbSI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762432443),
('YxNYOBk6W0gEiaaQTnj7xm2O3oECZE0w8OpgNmRZ', NULL, '54.149.35.233', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR3hJeXFsc0tsYXNqR1BtMzBCMFd0MGRvZHpjTURCTUkyZm9OVzBRbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHBzOi8vd3d3LnBvcy5qb2hsbHlhdXRvc3BhcmVzLmNvLmtlL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1762436602);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'company_name', 'Johlly Auto Spares', 'string', NULL, NULL, '2025-11-06 09:26:36'),
(2, 'email', 'info@johllyautospares.co.ke', 'string', NULL, NULL, '2025-11-06 09:26:36'),
(3, 'admin_email', 'johnnjonge99@gmail.com', 'string', NULL, NULL, '2025-11-06 09:26:36'),
(4, 'phone', '+254723014032', 'string', NULL, NULL, '2025-11-06 09:26:36'),
(5, 'address', 'Mwimuto Road, Wangige Market', 'string', NULL, NULL, '2025-11-06 09:26:36'),
(6, 'paybill_number', '68252624', 'string', NULL, NULL, '2025-11-06 09:26:36'),
(7, 'till_number', '68252624', 'string', NULL, NULL, '2025-11-06 09:26:36'),
(8, 'currency', 'KES', 'string', NULL, NULL, '2025-11-06 09:26:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','cashier') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cashier',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `pin`, `role`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin', '$2y$12$zGcfvkmjacYJVw74nXIps.hOqrRD7YqLnWnPmWxGtFAkazZ8zH2Qy', 'super_admin', 'active', NULL, '2025-11-04 09:20:11', '2025-11-04 09:20:11'),
(2, 'Cashier', 'cashier', '$2y$12$6Re2BwBYaRDLFilYmvync.fdnfCYerwZEYSqBXMhC09TJ7/CY4b1e', 'cashier', 'active', NULL, '2025-11-04 09:20:12', '2025-11-04 09:20:12');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_makes`
--

CREATE TABLE `vehicle_makes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `make_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_makes`
--

INSERT INTO `vehicle_makes` (`id`, `make_name`, `created_at`, `updated_at`) VALUES
(1, 'Toyota', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(2, 'Honda', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(3, 'Nissan', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(4, 'Mazda', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(5, 'Subaru', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(6, 'Mitsubishi', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(7, 'Suzuki', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(8, 'Isuzu', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(9, 'Ford', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(10, 'Chevrolet', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(11, 'BMW', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(12, 'Mercedes-Benz', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(13, 'Volkswagen', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(14, 'Audi', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(15, 'Volvo', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(16, 'Peugeot', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(17, 'Renault', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(18, 'Hyundai', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(19, 'Kia', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(20, 'Land Rover', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(21, 'Range Rover', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(22, 'Jeep', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(23, 'Dodge', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(24, 'Chrysler', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(25, 'Daihatsu', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(26, 'Lexus', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(27, 'Infiniti', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(28, 'Acura', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(29, 'Cadillac', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(30, 'Buick', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(31, 'GMC', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(32, 'Ram', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(33, 'Lincoln', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(34, 'Jaguar', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(35, 'Porsche', '2025-11-04 09:20:16', '2025-11-04 09:20:16');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_models`
--

CREATE TABLE `vehicle_models` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_make_id` bigint(20) UNSIGNED NOT NULL,
  `model_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_start` year(4) DEFAULT NULL,
  `year_end` year(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_models`
--

INSERT INTO `vehicle_models` (`id`, `vehicle_make_id`, `model_name`, `year_start`, `year_end`, `created_at`, `updated_at`) VALUES
(1, 1, 'Corolla', '1990', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(2, 1, 'Camry', '1990', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(3, 1, 'RAV4', '1994', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(4, 1, 'Hilux', '1968', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(5, 1, 'Land Cruiser', '1951', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(6, 1, 'Prius', '1997', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(7, 1, 'Highlander', '2000', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(8, 1, '4Runner', '1984', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(9, 1, 'Tacoma', '1995', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(10, 1, 'Sienna', '1997', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(11, 2, 'Civic', '1972', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(12, 2, 'Accord', '1976', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(13, 2, 'CR-V', '1995', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(14, 2, 'Pilot', '2002', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(15, 2, 'Odyssey', '1994', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(16, 2, 'Fit', '2001', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(17, 2, 'Ridgeline', '2005', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(18, 3, 'Altima', '1992', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(19, 3, 'Sentra', '1982', '2024', '2025-11-04 09:20:16', '2025-11-04 09:20:16'),
(20, 3, 'Rogue', '2007', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(21, 3, 'Pathfinder', '1986', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(22, 3, 'Frontier', '1997', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(23, 3, 'Titan', '2003', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(24, 9, 'F-150', '1948', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(25, 9, 'Mustang', '1964', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(26, 9, 'Explorer', '1990', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(27, 9, 'Escape', '2000', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(28, 9, 'Focus', '1998', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(29, 9, 'Fusion', '2005', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(30, 10, 'Silverado', '1998', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(31, 10, 'Tahoe', '1992', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(32, 10, 'Equinox', '2004', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(33, 10, 'Malibu', '1964', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17'),
(34, 10, 'Cruze', '2008', '2024', '2025-11-04 09:20:17', '2025-11-04 09:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `work_orders`
--

CREATE TABLE `work_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_make_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_registration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `estimated_cost` decimal(10,2) DEFAULT NULL,
  `actual_cost` decimal(10,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `work_order_items`
--

CREATE TABLE `work_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `work_order_id` bigint(20) UNSIGNED NOT NULL,
  `part_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `type` enum('part','labor','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'part',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_part_number_unique` (`part_number`),
  ADD UNIQUE KEY `inventory_barcode_unique` (`barcode`),
  ADD KEY `inventory_brand_id_foreign` (`brand_id`),
  ADD KEY `inventory_vehicle_make_id_foreign` (`vehicle_make_id`),
  ADD KEY `inventory_vehicle_model_id_foreign` (`vehicle_model_id`),
  ADD KEY `inventory_category_id_vehicle_make_id_vehicle_model_id_index` (`category_id`,`vehicle_make_id`,`vehicle_model_id`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_user_id_foreign` (`user_id`),
  ADD KEY `inventory_movements_part_id_movement_type_index` (`part_id`,`movement_type`),
  ADD KEY `inventory_movements_timestamp_index` (`timestamp`);

--
-- Indexes for table `inventory_vehicle_model`
--
ALTER TABLE `inventory_vehicle_model`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_vehicle_model_inventory_id_vehicle_model_id_unique` (`inventory_id`,`vehicle_model_id`),
  ADD KEY `inventory_vehicle_model_inventory_id_index` (`inventory_id`),
  ADD KEY `inventory_vehicle_model_vehicle_model_id_index` (`vehicle_model_id`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_sale_id_foreign` (`sale_id`),
  ADD KEY `payments_transaction_reference_index` (`transaction_reference`);

--
-- Indexes for table `pending_payments`
--
ALTER TABLE `pending_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pending_payments_transaction_reference_unique` (`transaction_reference`),
  ADD KEY `pending_payments_sale_id_foreign` (`sale_id`),
  ADD KEY `pending_payments_transaction_reference_index` (`transaction_reference`),
  ADD KEY `pending_payments_status_index` (`status`),
  ADD KEY `pending_payments_transaction_date_index` (`transaction_date`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `price_history`
--
ALTER TABLE `price_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `price_history_changed_by_foreign` (`changed_by`),
  ADD KEY `price_history_part_id_changed_at_index` (`part_id`,`changed_at`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `returns_sale_id_foreign` (`sale_id`),
  ADD KEY `returns_sale_item_id_foreign` (`sale_item_id`),
  ADD KEY `returns_part_id_foreign` (`part_id`),
  ADD KEY `returns_user_id_foreign` (`user_id`);

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
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_invoice_number_unique` (`invoice_number`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_user_id_foreign` (`user_id`),
  ADD KEY `sales_invoice_number_index` (`invoice_number`),
  ADD KEY `sales_date_index` (`date`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_part_id_foreign` (`part_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `vehicle_makes`
--
ALTER TABLE `vehicle_makes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_models`
--
ALTER TABLE `vehicle_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_models_vehicle_make_id_foreign` (`vehicle_make_id`);

--
-- Indexes for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `work_orders_work_order_number_unique` (`work_order_number`),
  ADD KEY `work_orders_customer_id_foreign` (`customer_id`),
  ADD KEY `work_orders_vehicle_make_id_foreign` (`vehicle_make_id`),
  ADD KEY `work_orders_vehicle_model_id_foreign` (`vehicle_model_id`),
  ADD KEY `work_orders_assigned_to_foreign` (`assigned_to`),
  ADD KEY `work_orders_created_by_foreign` (`created_by`),
  ADD KEY `work_orders_work_order_number_index` (`work_order_number`),
  ADD KEY `work_orders_status_index` (`status`);

--
-- Indexes for table `work_order_items`
--
ALTER TABLE `work_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_order_items_work_order_id_foreign` (`work_order_id`),
  ADD KEY `work_order_items_part_id_foreign` (`part_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `inventory_vehicle_model`
--
ALTER TABLE `inventory_vehicle_model`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `pending_payments`
--
ALTER TABLE `pending_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `price_history`
--
ALTER TABLE `price_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicle_makes`
--
ALTER TABLE `vehicle_makes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `vehicle_models`
--
ALTER TABLE `vehicle_models`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `work_orders`
--
ALTER TABLE `work_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_order_items`
--
ALTER TABLE `work_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_vehicle_make_id_foreign` FOREIGN KEY (`vehicle_make_id`) REFERENCES `vehicle_makes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_vehicle_model_id_foreign` FOREIGN KEY (`vehicle_model_id`) REFERENCES `vehicle_models` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_vehicle_model`
--
ALTER TABLE `inventory_vehicle_model`
  ADD CONSTRAINT `inventory_vehicle_model_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_vehicle_model_vehicle_model_id_foreign` FOREIGN KEY (`vehicle_model_id`) REFERENCES `vehicle_models` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pending_payments`
--
ALTER TABLE `pending_payments`
  ADD CONSTRAINT `pending_payments_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `price_history`
--
ALTER TABLE `price_history`
  ADD CONSTRAINT `price_history_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `price_history_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `returns_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `returns_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_models`
--
ALTER TABLE `vehicle_models`
  ADD CONSTRAINT `vehicle_models_vehicle_make_id_foreign` FOREIGN KEY (`vehicle_make_id`) REFERENCES `vehicle_makes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `work_orders`
--
ALTER TABLE `work_orders`
  ADD CONSTRAINT `work_orders_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_vehicle_make_id_foreign` FOREIGN KEY (`vehicle_make_id`) REFERENCES `vehicle_makes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_orders_vehicle_model_id_foreign` FOREIGN KEY (`vehicle_model_id`) REFERENCES `vehicle_models` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `work_order_items`
--
ALTER TABLE `work_order_items`
  ADD CONSTRAINT `work_order_items_part_id_foreign` FOREIGN KEY (`part_id`) REFERENCES `inventory` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_order_items_work_order_id_foreign` FOREIGN KEY (`work_order_id`) REFERENCES `work_orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
