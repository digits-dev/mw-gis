-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 28, 2024 at 08:39 AM
-- Server version: 5.7.39
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dtc_dms`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval_matrix`
--

CREATE TABLE `approval_matrix` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_cms_privileges` int(10) UNSIGNED NOT NULL,
  `cms_users_id` int(10) UNSIGNED NOT NULL,
  `channel_id` int(10) UNSIGNED DEFAULT NULL,
  `store_list` text COLLATE utf8mb4_unicode_ci,
  `channels_visibility` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `approval_matrix`
--

INSERT INTO `approval_matrix` (`id`, `id_cms_privileges`, `cms_users_id`, `channel_id`, `store_list`, `channels_visibility`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 10, 1, '292,274,9,10,248,11,12,16,17,18,19,246,21,22,249,23,132,244,117,210,25,293,26,27,284,28,260,261,30,281,31,32,37,38,241,267,40,141,289,41,126,42,43,250,242,287,44,131,133,46,47,48,290,49,279,50,51,129,52,137,53,283,285,128,54,275,237,269,280,55,56,127,67,294,116,300,291,59,288,277,278,276,138,62,63,202,247,245', NULL, 'ACTIVE', '2021-02-05 02:35:08', '2024-02-21 03:52:05', NULL),
(2, 5, 11, 10, '115,273,271,286,161,243,163,164,282,265,166,169,201,254,173,263,264,177,270,295,179,180,183,257,185,186,187,189,195', NULL, 'ACTIVE', '2021-02-05 07:42:20', '2023-12-12 07:04:43', NULL),
(3, 5, 13, 4, '238,123,124,142,143,112,111,102,121,122,120,119,68,72,227,79,69,82,144,146,145,101,107,105,110,134,135,203,205,204,80,70,83,206,108,71', NULL, 'ACTIVE', '2021-02-26 09:16:30', '2022-09-29 00:29:32', NULL),
(4, 5, 163, 6, '196,233,232,199,197,209,212,215,213,214,200,216,217,222,218,221,226,224,219,225,223,220,211', NULL, 'ACTIVE', '2021-03-16 07:11:21', '2022-07-28 08:07:43', NULL),
(5, 5, 10, 5, '114', NULL, 'ACTIVE', '2021-03-16 07:12:05', NULL, NULL),
(6, 5, 154, 2, '45', NULL, 'ACTIVE', '2021-12-03 03:10:16', '2022-02-11 03:44:16', NULL),
(7, 5, 124, 2, '7,8,13,14,15,139,140,33,34,35,36,268,45,262,118,255,136', NULL, 'ACTIVE', '2021-12-04 01:58:57', '2023-05-24 10:32:11', NULL),
(8, 5, 136, 2, '7,14,15,139,140,118,255,136', NULL, 'ACTIVE', '2021-12-13 07:24:12', '2023-01-30 08:51:16', NULL),
(9, 5, 137, 1, '274,9,10,248,11,12,16,17,18,19,246,21,22,249,23,132,244,117,210,25,26,27,284,28,260,261,30,281,31,32,37,38,241,267,40,141,289,41,126,42,43,250,242,287,44,131,133,46,47,48,290,49,279,50,51,129,52,137,53,283,128,54,275,237,269,280,55,56,127,67,116,300,291,59,288,277,278,276,138,62,63,202,247,245', NULL, 'INACTIVE', '2022-01-05 10:00:45', '2024-02-21 03:53:15', NULL),
(10, 5, 147, 2, '8,29,130', NULL, 'ACTIVE', '2022-01-18 10:37:21', '2022-01-18 11:18:29', NULL),
(11, 5, 148, 2, '8', NULL, 'INACTIVE', '2022-01-18 10:38:00', '2024-01-11 05:36:26', NULL),
(12, 5, 149, 2, '33,34,35,36', NULL, 'ACTIVE', '2022-01-25 01:21:12', '2022-08-23 08:13:44', NULL),
(13, 5, 152, 2, '8,29,130', NULL, 'ACTIVE', '2022-01-27 04:22:53', NULL, NULL),
(14, 5, 163, 11, '229,147,240,148,150,151,152,153,154,272,155,208,156,157,228,158,159,160,162,252,165,239,230,167,168,170,171,253,172,198,174,175,176,231,299,178,181,182,259,236,235,184,258,188,251,190,234,256,191,192,193,194', NULL, 'ACTIVE', '2022-03-25 11:05:36', '2024-01-30 06:08:03', NULL),
(15, 5, 163, 10, '115,273,271,286,161,243,163,164,282,265,166,169,201,254,173,263,264,177,270,179,180,183,257,185,186,187,189,195', NULL, 'INACTIVE', '2022-03-25 11:05:57', '2023-12-12 07:04:48', NULL),
(16, 5, 163, 7, '149', NULL, 'ACTIVE', '2022-03-25 11:06:10', '2023-03-23 09:19:15', NULL),
(17, 5, 173, 2, '7,14,15,139,140,268,118,255,136', NULL, 'ACTIVE', '2022-05-05 10:55:57', '2024-01-05 06:15:29', NULL),
(18, 5, 130, 1, '292,274,9,10,248,11,12,16,17,18,19,246,21,22,249,23,132,244,117,210,25,26,27,284,28,260,261,30,281,31,32,37,38,241,267,40,141,289,41,126,42,43,250,242,287,44,131,133,46,47,48,290,49,279,50,51,129,52,137,53,283,285,128,54,275,237,269,280,55,56,127,67,116,291,59,288,277,278,276,138,202,247,245', NULL, 'ACTIVE', '2022-06-02 06:14:15', '2023-11-23 11:13:29', NULL),
(19, 5, 129, 1, '292,274,9,10,248,11,12,16,17,18,19,246,21,22,249,23,132,244,117,210,25,26,27,284,28,260,261,30,281,31,32,37,38,241,267,40,141,289,41,126,42,43,250,242,287,44,131,133,46,47,48,290,49,279,50,51,129,52,137,53,283,285,128,54,275,237,269,280,55,56,127,67,116,291,59,288,277,278,276,138,62,63,202,247,245', NULL, 'ACTIVE', '2022-06-02 06:14:59', '2023-11-23 11:13:47', NULL),
(20, 5, 126, 1, '292,274,9,10,248,11,12,16,17,18,19,246,21,22,249,23,132,244,117,210,25,26,27,284,28,260,261,30,281,31,32,37,38,241,267,40,141,289,41,126,42,43,250,242,287,44,131,133,46,47,48,290,49,279,50,51,129,52,137,53,283,285,128,54,275,237,269,280,55,56,127,67,116,291,59,288,277,278,276,138,62,63,202,247,245', NULL, 'ACTIVE', '2022-06-02 06:15:36', '2023-11-23 11:13:57', NULL),
(21, 5, 189, 6, '196,233,297,298,296,232,199,197,209,212,215,213,214,200,216,217,222,218,221,226,224,219,225,223,220,211', NULL, 'ACTIVE', '2022-09-13 01:38:17', '2024-01-16 01:43:25', NULL),
(22, 5, 189, 11, '229,147,240,148,150,151,152,153,154,272,155,208,156,157,228,158,159,160,162,252,165,239,230,167,168,170,171,253,172,198,174,175,176,231,299,178,181,182,259,236,235,184,258,188,251,190,234,256,191,192,193,194', NULL, 'ACTIVE', '2022-09-13 01:38:17', '2024-01-30 06:08:09', NULL),
(23, 5, 189, 10, '115,273,271,286,161,243,163,164,282,265,166,169,201,254,173,263,264,177,270,295,179,180,183,257,185,186,187,189,195', NULL, 'ACTIVE', '2022-09-13 01:38:17', '2023-12-12 07:05:12', NULL),
(24, 5, 189, 7, '149', NULL, 'ACTIVE', '2022-09-13 01:38:18', '2023-03-23 09:19:16', NULL),
(25, 5, 127, 1, '292,274,9,10,248,11,12,16,17,18,19,246,21,22,249,23,132,117,210,25,26,27,284,28,260,261,30,281,31,32,37,38,241,267,40,141,289,41,126,42,43,250,242,287,44,131,133,46,47,48,290,49,279,50,51,129,52,137,53,283,285,128,54,275,237,269,280,55,56,127,67,116,291,59,288,277,278,276,138,62,63,202,247,245', NULL, 'ACTIVE', '2022-09-15 05:45:54', '2023-11-23 11:14:22', NULL),
(26, 5, 190, 1, '62,63', NULL, 'INACTIVE', '2022-09-30 06:50:03', '2023-03-31 10:06:36', NULL),
(27, 5, 193, 1, '62,63', NULL, 'ACTIVE', '2022-09-30 06:50:18', NULL, NULL),
(28, 5, 130, 2, '7,8,13,14,15,140,29,130,33,34,35,36,45,118,136', NULL, 'ACTIVE', '2022-11-09 03:39:45', NULL, NULL),
(29, 5, 130, 4, '238,123,124,142,143,112,111,102,121,122,120,119,68,72,227,79,69,82,144,146,145,101,107,105,110,134,135,203,205,204,80,70,83,206,108,71', NULL, 'ACTIVE', '2022-11-09 03:40:21', NULL, NULL),
(30, 5, 211, 1, '292,274,9,10,248,11,12,16,17,18,19,246,21,22,249,23,132,244,117,210,25,26,27,284,28,260,261,30,281,31,32,37,38,241,267,40,141,289,41,126,42,43,250,242,287,44,131,133,46,47,48,290,49,279,50,51,129,52,137,53,283,285,128,54,275,237,269,280,55,56,127,67,116,300,291,59,288,277,278,276,138,62,63,202,247,245', NULL, 'ACTIVE', '2023-04-26 05:25:49', '2024-02-21 03:52:37', NULL),
(31, 5, 207, 1, '292,274,9,10,248,11,12,16,17,18,19,246,21,22,249,23,132,244,117,210,25,26,27,284,28,260,261,30,281,31,32,37,38,241,267,40,141,289,41,126,42,43,250,242,287,44,131,133,46,47,48,290,49,279,50,51,129,52,137,53,283,285,128,54,275,237,269,280,55,56,127,67,116,291,59,288,277,278,276,138,62,63,202,247,245', '1', 'ACTIVE', '2023-06-07 02:50:05', '2023-11-23 11:14:40', NULL),
(32, 5, 234, 2, '262', NULL, 'ACTIVE', '2023-08-31 00:44:42', NULL, NULL),
(33, 5, 235, 6, '196,233,232,199,197,209,212,215,213,214,200,216,217,222,218,221,226,224,219,225,223,220,211', NULL, 'ACTIVE', '2023-09-06 05:18:25', NULL, NULL),
(34, 5, 235, 7, '149', NULL, 'ACTIVE', '2023-09-06 05:18:47', NULL, NULL),
(35, 5, 235, 10, '115,273,271,286,161,243,163,164,282,265,166,169,201,254,173,263,264,177,270,295,179,180,183,257,185,186,187,189,195', NULL, 'ACTIVE', '2023-09-06 05:19:48', '2023-12-12 07:05:08', NULL),
(36, 5, 235, 11, '229,147,240,148,150,151,152,153,154,272,155,208,156,157,228,158,159,160,162,252,165,239,230,167,168,170,171,253,172,198,174,175,176,231,299,178,181,182,259,236,235,184,258,188,251,190,234,256,191,192,193,194', NULL, 'ACTIVE', '2023-09-06 05:20:25', '2024-01-30 06:08:15', NULL),
(37, 5, 11, 6, '196,233,232,199,197,209,212,215,213,214,200,216,217,222,218,221,226,224,219,225,223,220,211', NULL, 'ACTIVE', '2023-10-31 09:52:15', NULL, NULL),
(38, 5, 11, 7, '149', NULL, 'ACTIVE', '2023-10-31 09:52:57', NULL, NULL),
(39, 5, 11, 11, '229,147,240,148,150,151,152,153,154,272,155,208,156,157,228,158,159,160,162,252,165,239,230,167,168,170,171,253,172,198,174,175,176,231,299,178,181,182,259,236,235,184,258,188,251,190,234,256,191,192,193,194', NULL, 'ACTIVE', '2023-10-31 09:53:33', '2024-01-30 06:08:24', NULL),
(40, 5, 81, 2, '268', NULL, 'ACTIVE', '2024-01-02 01:07:26', NULL, NULL),
(41, 5, 253, 2, '8', NULL, 'ACTIVE', '2024-01-11 05:36:13', NULL, NULL),
(42, 5, 232, 1, '62,63', NULL, 'ACTIVE', '2024-01-24 06:44:17', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `backload_reasons`
--

CREATE TABLE `backload_reasons` (
  `id` int(10) UNSIGNED NOT NULL,
  `backload_reason` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trip_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `backload_reasons`
--

INSERT INTO `backload_reasons` (`id`, `backload_reason`, `trip_type`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'No Permit', 'IN', 'ACTIVE', 1, NULL, '2022-04-19 09:45:39', NULL, NULL),
(2, 'No Serial Number', 'IN', 'ACTIVE', 1, NULL, '2022-04-19 09:45:46', NULL, NULL),
(3, 'Item Mismatch', 'IN', 'ACTIVE', 1, NULL, '2022-04-19 09:46:15', NULL, NULL),
(4, 'Over', 'IN', 'ACTIVE', 1, NULL, '2022-04-19 09:46:23', NULL, NULL),
(5, 'Short', 'IN', 'ACTIVE', 1, NULL, '2022-04-19 09:46:28', NULL, NULL),
(6, 'Empty Box', 'IN', 'ACTIVE', 1, NULL, '2022-04-19 09:46:33', NULL, NULL),
(7, 'Incomplete Accessories', 'IN', 'ACTIVE', 1, NULL, '2022-04-19 09:46:40', NULL, NULL),
(8, 'Wrong Allocation', 'IN', 'ACTIVE', 1, NULL, '2022-04-19 09:46:45', NULL, NULL),
(9, 'No Serial Number', 'OUT', 'ACTIVE', 1, NULL, '2022-04-19 09:47:13', NULL, NULL),
(10, 'Item Mismatch', 'OUT', 'ACTIVE', 1, NULL, '2022-04-19 09:47:22', NULL, NULL),
(11, 'Over', 'OUT', 'ACTIVE', 1, NULL, '2022-04-19 09:47:31', NULL, NULL),
(12, 'Short', 'OUT', 'ACTIVE', 1, NULL, '2022-04-19 09:47:39', NULL, NULL),
(13, 'Wrong Allocation', 'OUT', 'ACTIVE', 1, NULL, '2022-04-19 09:47:45', NULL, NULL),
(14, 'For Void', 'IN', 'ACTIVE', 1, NULL, '2022-05-11 06:25:36', NULL, NULL),
(15, 'Damage Packaging', 'OUT', 'ACTIVE', 1, NULL, '2022-09-15 02:34:27', NULL, NULL),
(16, 'Calamity', 'OUT', 'ACTIVE', 1, NULL, '2022-11-07 05:35:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cancel_reasons`
--

CREATE TABLE `cancel_reasons` (
  `id` int(10) UNSIGNED NOT NULL,
  `cancel_reason` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cancel_reasons`
--

INSERT INTO `cancel_reasons` (`id`, `cancel_reason`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PACKAGING ONLY', 'ACTIVE', 1, NULL, '2022-04-29 03:42:10', NULL, NULL),
(2, 'INCOMPLETE PARTS', 'ACTIVE', 1, NULL, '2022-04-29 03:42:19', NULL, NULL),
(3, 'OTHERS', 'ACTIVE', 1, NULL, '2022-04-29 03:42:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `channel`
--

CREATE TABLE `channel` (
  `id` int(10) UNSIGNED NOT NULL,
  `channel_code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_description` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `channel`
--

INSERT INTO `channel` (`id`, `channel_code`, `channel_description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'RTL', 'RETAIL', 'ACTIVE', 1, NULL, '2021-01-05 08:33:37', NULL, NULL),
(2, 'FRA', 'FRANCHISE', 'ACTIVE', 1, NULL, '2021-01-05 08:33:43', NULL, NULL),
(3, 'DIS', 'DISTRIBUTION', 'ACTIVE', 1, NULL, '2021-01-05 08:33:50', NULL, '2021-03-16 06:56:35'),
(4, 'ONL', 'ONLINE', 'ACTIVE', 1, NULL, '2021-02-26 09:13:11', NULL, NULL),
(5, 'SLE', 'SALE', 'ACTIVE', 1, 1, '2021-03-16 06:24:18', '2021-03-16 06:54:43', NULL),
(6, 'CON', 'CONSIGNMENT', 'ACTIVE', 1, NULL, '2021-03-16 06:24:26', NULL, NULL),
(7, 'OUT', 'OUTRIGHT', 'ACTIVE', 1, NULL, '2021-03-16 06:24:43', NULL, NULL),
(8, 'DTC', 'DIGITS', 'ACTIVE', 1, NULL, '2021-03-16 06:24:49', NULL, NULL),
(9, 'SVC', 'SERVICE CENTER', 'ACTIVE', 1, NULL, '2021-03-16 06:25:36', NULL, NULL),
(10, 'DLR', 'DEALERSHIP', 'ACTIVE', 1, NULL, '2022-03-23 09:12:40', NULL, NULL),
(11, 'CRP', 'CORPORATE', 'ACTIVE', 1, NULL, '2022-03-23 09:12:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_apicustom`
--

CREATE TABLE `cms_apicustom` (
  `id` int(10) UNSIGNED NOT NULL,
  `permalink` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tabel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aksi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kolom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orderby` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_query_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sql_where` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameter` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `method_type` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` longtext COLLATE utf8mb4_unicode_ci,
  `responses` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_apicustom`
--

INSERT INTO `cms_apicustom` (`id`, `permalink`, `tabel`, `aksi`, `kolom`, `orderby`, `sub_query_1`, `sql_where`, `nama`, `keterangan`, `parameter`, `created_at`, `updated_at`, `method_type`, `parameters`, `responses`) VALUES
(1, 'mw_store_created', 'stores', 'list', NULL, NULL, NULL, 'created_at between DATE_FORMAT(CURDATE(), \"%Y-%m-%d 00:00:00\") and DATE_FORMAT(CURDATE()+ INTERVAL 1 DAY, \"%Y-%m-%d 23:59:00\")', 'mw_store_created', '<p>store creation</p>', NULL, NULL, NULL, 'get', 'a:0:{}', 'a:19:{i:0;a:4:{s:4:\"name\";s:17:\"bea_so_store_name\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:1;a:4:{s:4:\"name\";s:17:\"bea_mo_store_name\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:2;a:4:{s:4:\"name\";s:13:\"pos_warehouse\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:3;a:4:{s:4:\"name\";s:20:\"pos_warehouse_branch\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:4;a:4:{s:4:\"name\";s:21:\"pos_warehouse_transit\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:5;a:4:{s:4:\"name\";s:28:\"pos_warehouse_transit_branch\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:6;a:4:{s:4:\"name\";s:17:\"pos_warehouse_rma\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:7;a:4:{s:4:\"name\";s:24:\"pos_warehouse_rma_branch\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:8;a:4:{s:4:\"name\";s:18:\"pos_warehouse_name\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:9;a:4:{s:4:\"name\";s:16:\"doo_subinventory\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:10;a:4:{s:4:\"name\";s:16:\"sit_subinventory\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:11;a:4:{s:4:\"name\";s:16:\"org_subinventory\";s:4:\"type\";s:6:\"string\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:12;a:4:{s:4:\"name\";s:10:\"channel_id\";s:4:\"type\";s:7:\"integer\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:13;a:4:{s:4:\"name\";s:16:\"customer_type_id\";s:4:\"type\";s:7:\"integer\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:14;a:4:{s:4:\"name\";s:12:\"locations_id\";s:4:\"type\";s:7:\"integer\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:15;a:4:{s:4:\"name\";s:9:\"sts_group\";s:4:\"type\";s:3:\"int\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:16;a:4:{s:4:\"name\";s:6:\"status\";s:4:\"type\";s:4:\"enum\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:17;a:4:{s:4:\"name\";s:10:\"created_by\";s:4:\"type\";s:3:\"int\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}i:18;a:4:{s:4:\"name\";s:10:\"updated_by\";s:4:\"type\";s:3:\"int\";s:8:\"subquery\";N;s:4:\"used\";s:1:\"1\";}}');

-- --------------------------------------------------------

--
-- Table structure for table `cms_apikey`
--

CREATE TABLE `cms_apikey` (
  `id` int(10) UNSIGNED NOT NULL,
  `screetkey` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hit` int(11) DEFAULT NULL,
  `status` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_apikey`
--

INSERT INTO `cms_apikey` (`id`, `screetkey`, `hit`, `status`, `created_at`, `updated_at`) VALUES
(1, 'd07f5a698954451c7d135415fac2361b', 0, 'active', '2022-12-16 10:21:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_dashboard`
--

CREATE TABLE `cms_dashboard` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cms_privileges` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_email_queues`
--

CREATE TABLE `cms_email_queues` (
  `id` int(10) UNSIGNED NOT NULL,
  `send_at` datetime DEFAULT NULL,
  `email_recipient` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_cc_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_content` text COLLATE utf8mb4_unicode_ci,
  `email_attachments` text COLLATE utf8mb4_unicode_ci,
  `is_sent` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_email_templates`
--

CREATE TABLE `cms_email_templates` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cc_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_email_templates`
--

INSERT INTO `cms_email_templates` (`id`, `name`, `slug`, `subject`, `content`, `description`, `from_name`, `from_email`, `cc_email`, `created_at`, `updated_at`) VALUES
(1, 'Email Template Forgot Password Backend', 'forgot_password_backend', 'Request Forgot Password', '<p>Hi,</p><p>Someone requested forgot password, here is your new password : </p><p>[password]</p><p><br></p><p>--</p><p>Regards,</p><p>Michael Adrian Rodelas</p><p>System Admin</p>', '[password]', 'Digits MW', 'noreply@digits.ph', NULL, '2019-08-22 08:23:04', '2021-08-03 19:14:41');

-- --------------------------------------------------------

--
-- Table structure for table `cms_logs`
--

CREATE TABLE `cms_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `ipaddress` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `useragent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `id_cms_users` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_menus`
--

CREATE TABLE `cms_menus` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'url',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `id_cms_privileges` int(11) DEFAULT NULL,
  `sorting` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_menus`
--

INSERT INTO `cms_menus` (`id`, `name`, `type`, `path`, `color`, `icon`, `parent_id`, `is_active`, `is_dashboard`, `id_cms_privileges`, `sorting`, `created_at`, `updated_at`) VALUES
(1, 'DR Transactions', 'Route', 'AdminBeaTransactionsControllerGetIndex', 'normal', 'fa fa-circle-o', 71, 1, 0, 1, 1, '2020-10-28 06:04:52', '2022-08-18 06:00:24'),
(2, 'ST Status', 'Route', 'AdminStStatusControllerGetIndex', NULL, 'fa fa-circle-o', 0, 0, 0, 1, 7, '2020-11-03 04:03:00', NULL),
(3, 'STS Transactions', 'Route', 'AdminPosTransactionsControllerGetIndex', 'normal', 'fa fa-circle-o', 71, 1, 0, 1, 3, '2020-11-03 04:12:52', '2022-08-18 05:57:42'),
(4, 'Submaster', 'URL', '#', 'normal', 'fa fa-gears', 0, 1, 0, 1, 25, '2020-11-03 04:14:02', NULL),
(5, 'Store Name', 'Route', 'AdminStoresControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 11, '2020-11-13 03:02:23', NULL),
(6, 'Delivery Receiving', 'Route', 'AdminDeliveryReceivingControllerGetIndex', 'normal', 'fa fa-check-circle-o', 24, 1, 0, 1, 1, '2020-12-11 02:25:55', '2024-01-23 06:12:12'),
(7, 'STS Receiving', 'Route', 'AdminStoreTransferReceivingControllerGetIndex', 'normal', 'fa fa-check-circle-o', 24, 1, 0, 1, 2, '2020-12-17 11:31:22', '2024-01-12 02:20:15'),
(8, 'STS', 'Route', 'AdminStoreTransferControllerGetIndex', 'normal', 'fa fa-circle-o', 25, 0, 0, 1, 1, '2020-12-17 12:11:15', '2021-04-28 07:33:08'),
(9, 'Delivery History', 'Route', 'AdminDeliveryControllerGetIndex', 'normal', 'fa fa-file-text-o', 23, 1, 0, 1, 1, '2020-12-23 06:52:19', '2024-01-23 06:25:23'),
(10, 'STW and STR', 'Route', 'AdminPulloutControllerGetIndex', 'normal', 'fa fa-circle-o', 25, 0, 0, 1, 2, '2020-12-28 10:56:20', '2021-04-28 07:33:21'),
(11, 'STW and STR Receiving', 'Route', 'AdminPulloutReceivingControllerGetIndex', 'normal', 'fa fa-check-circle-o', 33, 0, 0, 1, 1, '2020-12-29 06:48:55', '2021-04-29 08:22:26'),
(12, 'Transaction Types', 'Route', 'AdminTransactionTypeControllerGetIndex', 'normal', 'fa fa-circle-o', 4, 1, 0, 1, 14, '2021-01-05 06:24:53', '2021-02-02 03:50:28'),
(13, 'Reasons', 'Route', 'AdminReasonControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 10, '2021-01-05 06:25:49', NULL),
(14, 'Channels', 'Route', 'AdminChannelControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 4, '2021-01-05 08:31:07', NULL),
(15, 'Transport Types', 'Route', 'AdminTransportTypeControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 13, '2021-02-02 03:47:47', NULL),
(16, 'Approval Matrix', 'Route', 'AdminApprovalMatrixControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 2, '2021-02-04 03:40:54', NULL),
(17, 'STS Approval', 'Route', 'AdminStoreTransferApprovalControllerGetIndex', 'normal', 'fa fa-thumbs-up', 26, 0, 0, 1, 1, '2021-02-05 01:28:04', '2021-02-05 02:24:56'),
(18, 'STW and STR Approval', 'Route', 'AdminPulloutApprovalControllerGetIndex', 'normal', 'fa fa-thumbs-up', 26, 0, 0, 1, 2, '2021-02-05 01:29:26', '2021-02-05 02:25:12'),
(19, 'Code Generator', 'Route', 'AdminCodeCounterControllerGetIndex', NULL, 'fa fa-gear', 4, 1, 0, 1, 5, '2021-03-01 06:31:28', NULL),
(20, 'HQ Pullout', 'Route', 'AdminHqPulloutControllerGetIndex', 'normal', 'fa fa-circle-o', 0, 0, 0, 1, 6, '2021-03-02 07:13:15', '2022-04-28 11:37:34'),
(21, 'HQ Deliveries', 'Route', 'AdminHqDeliveryControllerGetIndex', 'normal', 'fa fa-circle-o', 0, 0, 0, 1, 5, '2021-03-02 07:14:30', '2022-04-28 11:37:19'),
(22, 'Customer Type', 'Route', 'AdminCustomerTypeControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 6, '2021-03-16 06:14:05', NULL),
(23, 'History', 'URL', '#', 'normal', 'fa fa-file-text-o', 0, 1, 0, 1, 22, '2021-04-26 06:06:16', '2022-06-08 01:57:36'),
(24, 'Receive Delivery and STS', 'URL', '#', 'normal', 'fa fa-dropbox', 0, 1, 0, 1, 19, '2021-04-26 06:08:49', '2024-01-23 06:11:53'),
(25, 'Create STS', 'Route', 'AdminStoreTransferControllerGetIndex', 'normal', 'fa fa-file-text', 49, 1, 0, 1, 1, '2021-04-26 06:39:45', '2021-12-02 13:55:51'),
(26, 'Approvals', 'URL', '#', 'normal', 'fa fa-thumbs-o-up', 0, 1, 0, 1, 16, '2021-04-26 07:09:31', NULL),
(27, 'STS History', 'Route', 'AdminStoreTransferHistoryControllerGetIndex', 'normal', 'fa fa-file-text-o', 23, 1, 0, 1, 2, '2021-04-26 07:15:17', '2022-06-08 01:58:14'),
(28, 'STW and STR History', 'Route', 'AdminPulloutHistoryControllerGetIndex', 'normal', 'fa fa-file-text-o', 23, 1, 0, 1, 3, '2021-04-26 07:16:56', '2022-06-08 01:58:31'),
(29, 'Dashboard', 'Statistic', 'statistic_builder/show/requestor-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 14, '2021-04-26 07:31:57', '2021-05-04 09:39:01'),
(30, 'Schedule Pullout', 'URL', '#', 'normal', 'fa fa-file-text', 0, 1, 0, 1, 18, '2021-04-28 07:34:19', '2022-04-26 01:49:10'),
(31, 'STS', 'Route', 'AdminStoreTransferControllerGetIndex', 'normal', 'fa fa-circle-o', 30, 0, 0, 1, 1, '2021-04-28 07:35:12', '2021-04-28 07:35:50'),
(32, 'STW and STR', 'Route', 'AdminPulloutControllerGetIndex', 'normal', 'fa fa-circle-o', 30, 0, 0, 1, 2, '2021-04-28 07:36:36', NULL),
(33, 'Receive Pullout', 'URL', '#', 'normal', 'fa fa-dropbox', 0, 0, 0, 1, 1, '2021-04-28 08:21:41', '2022-04-28 05:46:43'),
(34, 'Dashboard', 'Statistic', 'statistic_builder/show/warehouse-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 10, '2021-05-04 03:30:27', '2021-10-28 09:40:32'),
(35, 'Dashboard', 'Statistic', 'statistic_builder/show/admin-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 9, '2021-05-04 06:36:37', '2021-12-13 03:26:14'),
(36, 'Dashboard', 'Statistic', 'statistic_builder/show/approver-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 12, '2021-05-04 07:35:14', '2021-05-04 07:35:50'),
(37, 'Dashboard', 'Statistic', 'statistic_builder/show/logistics-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 13, '2021-05-04 07:35:36', '2022-04-26 01:48:51'),
(38, 'Dashboard', 'Statistic', 'statistic_builder/show/online-warehouse-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 11, '2021-05-04 09:21:32', '2021-05-04 09:21:38'),
(39, 'Dashboard', 'Statistic', 'statistic_builder/show/requestor-ii', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 15, '2021-05-04 09:41:13', '2021-05-04 09:41:23'),
(40, 'Reports', 'URL', 'https://dms.digitstrading.ph/public/admin/reports', 'yellow', 'fa fa-file-text-o', 0, 1, 0, 1, 26, '2021-08-10 11:30:20', '2021-08-13 08:20:31'),
(41, 'Item Master', 'Route', 'AdminItemsControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 1, '2021-09-14 02:03:46', NULL),
(42, 'STS Pick Confirm', 'Route', 'AdminStsPickConfirmControllerGetIndex', 'normal', 'fa fa-file-text-o', 46, 1, 0, 1, 1, '2021-09-29 07:13:28', '2021-10-19 15:13:02'),
(43, 'STS Picklist', 'Route', 'AdminStsPicklistControllerGetIndex', 'normal', 'fa fa-file-text-o', 47, 1, 0, 1, 1, '2021-09-29 07:17:31', '2021-10-19 15:12:24'),
(44, 'STW Pick Confirm', 'Route', 'AdminPulloutPickConfirmControllerGetIndex', 'normal', 'fa fa-file-text-o', 46, 1, 0, 1, 2, '2021-09-29 07:20:50', '2021-10-19 15:13:25'),
(45, 'STW Picklist', 'Route', 'AdminPulloutPicklistControllerGetIndex', 'normal', 'fa fa-file-text-o', 47, 1, 0, 1, 2, '2021-09-29 08:04:03', '2021-10-19 15:12:43'),
(46, 'For Pick Confirm', 'URL', '#', 'normal', 'fa fa-cubes', 0, 1, 0, 1, 21, '2021-09-29 08:27:45', NULL),
(47, 'For Picklist', 'URL', '#', 'normal', 'fa fa-cubes', 0, 1, 0, 1, 20, '2021-09-29 08:28:14', NULL),
(48, 'Status Workflow', 'Route', 'AdminStatusWorkflowsControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 12, '2021-09-29 09:08:27', NULL),
(49, 'Create Pullout', 'URL', '#', 'normal', 'fa fa-file-text-o', 0, 1, 0, 1, 17, '2021-10-01 11:44:49', '2022-03-23 06:35:39'),
(50, 'Create STW and STR', 'Route', 'AdminPulloutControllerGetIndex', 'normal', 'fa fa-file-text', 49, 1, 0, 1, 2, '2021-10-01 13:28:00', '2022-03-23 06:35:58'),
(51, 'STS Approval', 'Module', 'transfer_approval', 'normal', 'fa fa-thumbs-o-up', 26, 1, 0, 1, 1, '2021-10-01 16:14:21', NULL),
(52, 'STR Approval', 'Module', 'pullout_approval', 'normal', 'fa fa-thumbs-o-up', 26, 1, 0, 1, 2, '2021-10-01 16:15:07', '2023-02-07 00:56:06'),
(53, 'Problem Details', 'Route', 'AdminProblemsControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 9, '2021-10-11 01:36:11', NULL),
(54, 'Dashboard', 'Statistic', 'statistic_builder/show/online-ops-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 2, '2021-10-19 18:46:33', '2021-10-19 18:46:46'),
(55, 'Dashboard', 'Statistic', 'statistic_builder/show/online-wsdm-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 3, '2021-10-19 20:00:28', '2021-10-19 20:00:39'),
(56, 'Dashboard', 'Statistic', 'statistic_builder/show/rtl-fra-ops-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 4, '2021-12-04 03:19:53', '2022-06-08 02:00:40'),
(57, 'Dashboard', 'Statistic', 'statistic_builder/show/retail-ops-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 5, '2021-12-07 05:59:54', '2021-12-07 06:00:02'),
(58, 'Dashboard', 'Statistic', 'statistic_builder/show/rtl-onl-viewer-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 6, '2021-12-10 09:02:41', '2021-12-10 09:02:48'),
(59, 'Dashboard', 'Statistic', 'statistic_builder/show/online-viewer-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 7, '2021-12-10 10:08:09', '2021-12-10 10:08:18'),
(60, 'Dashboard', 'Statistic', 'statistic_builder/show/merch-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 8, '2021-12-10 11:06:13', '2021-12-10 11:06:24'),
(61, 'Pullout Transactions', 'Route', 'AdminPulloutTransactionsControllerGetIndex', NULL, 'fa fa-circle-o', 71, 1, 0, 1, 2, '2022-02-09 01:38:40', NULL),
(62, 'Schedule STS', 'Route', 'AdminStoreTransferControllerGetIndex', 'normal', 'fa fa-file-text', 30, 1, 0, 1, 1, '2022-02-24 02:04:31', '2022-04-26 01:49:24'),
(63, 'Schedule STW and STR', 'Route', 'AdminPulloutControllerGetIndex', 'normal', 'fa fa-file-text', 30, 1, 0, 1, 2, '2022-02-24 02:05:43', '2022-04-26 01:49:41'),
(64, 'Dashboard', 'Statistic', 'statistic_builder/show/requestor-iii-dashboard', 'normal', 'fa fa-tachometer', 0, 1, 1, 1, 1, '2022-03-29 06:32:55', '2022-03-29 06:33:02'),
(65, 'Trip Ticket', 'Route', 'AdminTripTicketsControllerGetIndex', 'normal', 'fa fa-file-text-o', 0, 1, 0, 1, 24, '2022-04-06 09:02:56', '2022-04-26 08:35:14'),
(66, 'Distri Subinventory', 'Route', 'AdminDistriSubinventoriesControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 7, '2022-04-13 08:04:45', NULL),
(67, 'Backload Reason', 'Route', 'AdminBackloadReasonsControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 3, '2022-04-19 09:41:56', NULL),
(68, 'Location', 'Route', 'AdminLocationsControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 8, '2022-04-25 07:33:41', NULL),
(69, 'Trip Ticket Status', 'Route', 'AdminTripTicketStatusesControllerGetIndex', NULL, 'fa fa-circle-o', 4, 1, 0, 1, 15, '2022-04-27 03:57:17', NULL),
(70, 'STW and STR Receiving', 'Route', 'AdminPulloutReceivingControllerGetIndex', 'normal', 'fa fa-check-circle-o', 0, 0, 0, 1, 2, '2022-04-28 06:19:44', '2022-04-29 02:41:57'),
(71, 'Itemized Transactions', 'URL', '#', 'normal', 'fa fa-file-text', 0, 1, 0, 1, 23, '2022-04-28 11:36:39', NULL),
(72, 'WRS', 'Route', 'AdminWrsControllerGetIndex', NULL, 'fa fa-cubes', 0, 0, 0, 1, 3, '2022-04-29 03:24:10', NULL),
(73, 'Cancel Reason', 'Route', 'AdminCancelReasonsControllerGetIndex', NULL, 'fa fa-circle-o', 0, 0, 0, 1, 4, '2022-04-29 03:38:27', NULL),
(74, 'STW Approval', 'Route', 'AdminStwApprovalControllerGetIndex', 'normal', 'fa fa-thumbs-o-up', 26, 1, 0, 1, 3, '2023-02-07 00:39:06', '2023-02-07 01:20:35'),
(75, 'Create GIS STS', 'Route', 'AdminStoreTransferGisControllerGetIndex', NULL, 'fa fa-file-text', 49, 1, 0, 1, 3, '2024-02-27 01:44:23', '2024-02-27 01:46:57'),
(76, 'Gis STS Approval', 'Route', 'AdminGisPullApprovalControllerGetIndex', 'normal', 'fa fa-thumbs-up', 26, 1, 0, 1, 4, '2024-02-27 09:01:51', '2024-02-28 01:42:51'),
(77, 'ST Statuses', 'Route', 'AdminStockStatusesControllerGetIndex', NULL, 'fa fa-files-o', 0, 1, 0, 1, 27, '2024-02-28 02:28:19', NULL),
(78, 'Transfer Stock Statuses', 'Route', 'AdminTransferStockStatusesControllerGetIndex', NULL, 'fa fa-files-o', 4, 1, 0, 1, 16, '2024-02-28 02:29:46', NULL),
(79, 'GIS STS Receiving', 'Route', 'AdminStGisReceivingControllerGetIndex', 'normal', 'fa fa-check-circle-o', 24, 1, 0, 1, 3, '2024-02-28 03:02:24', '2024-02-28 03:15:42'),
(80, 'STS GIS History', 'Route', 'AdminStockTransferGisHistoryControllerGetIndex', NULL, 'fa fa-file-text', 23, 1, 0, 1, 4, '2024-02-28 07:35:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_menus_privileges`
--

CREATE TABLE `cms_menus_privileges` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_cms_menus` int(11) DEFAULT NULL,
  `id_cms_privileges` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_menus_privileges`
--

INSERT INTO `cms_menus_privileges` (`id`, `id_cms_menus`, `id_cms_privileges`) VALUES
(2, 2, 1),
(4, 4, 1),
(5, 5, 1),
(23, 13, 1),
(24, 14, 1),
(49, 15, 1),
(50, 12, 1),
(59, 16, 1),
(62, 17, 5),
(63, 17, 1),
(69, 18, 5),
(70, 18, 1),
(94, 19, 1),
(129, 22, 1),
(187, 26, 5),
(188, 26, 1),
(244, 8, 5),
(245, 8, 10),
(246, 8, 2),
(247, 8, 1),
(248, 10, 5),
(249, 10, 8),
(250, 10, 10),
(251, 10, 2),
(252, 10, 7),
(253, 10, 3),
(254, 10, 1),
(255, 10, 6),
(258, 31, 4),
(259, 31, 1),
(260, 32, 4),
(261, 32, 1),
(318, 11, 3),
(319, 11, 1),
(320, 11, 6),
(347, 36, 5),
(348, 38, 11),
(349, 29, 8),
(350, 29, 2),
(351, 29, 7),
(352, 39, 10),
(391, 40, 15),
(392, 40, 1),
(393, 41, 1),
(398, 46, 17),
(399, 46, 1),
(400, 47, 17),
(401, 47, 1),
(402, 48, 1),
(415, 51, 5),
(416, 51, 1),
(443, 53, 1),
(461, 43, 17),
(462, 43, 1),
(463, 45, 17),
(464, 45, 1),
(465, 42, 17),
(466, 42, 1),
(467, 44, 17),
(468, 44, 1),
(500, 54, 16),
(501, 55, 17),
(531, 34, 6),
(572, 25, 10),
(573, 25, 2),
(574, 25, 1),
(674, 57, 12),
(734, 58, 21),
(735, 59, 20),
(799, 60, 22),
(832, 35, 14),
(833, 35, 1),
(834, 61, 1),
(838, 49, 8),
(839, 49, 10),
(840, 49, 2),
(841, 49, 7),
(842, 49, 23),
(843, 49, 1),
(844, 50, 8),
(845, 50, 10),
(846, 50, 2),
(847, 50, 7),
(848, 50, 23),
(849, 50, 1),
(891, 64, 23),
(893, 66, 1),
(894, 67, 1),
(895, 68, 1),
(896, 37, 25),
(897, 37, 4),
(898, 30, 25),
(899, 30, 4),
(900, 62, 25),
(901, 62, 4),
(902, 63, 25),
(903, 63, 4),
(963, 65, 25),
(964, 65, 4),
(965, 65, 2),
(966, 65, 1),
(967, 69, 1),
(968, 33, 1),
(969, 33, 6),
(971, 71, 1),
(972, 21, 1),
(973, 20, 1),
(974, 70, 1),
(975, 72, 1),
(976, 73, 1),
(991, 23, 5),
(992, 23, 14),
(993, 23, 24),
(994, 23, 13),
(995, 23, 18),
(996, 23, 25),
(997, 23, 4),
(998, 23, 22),
(999, 23, 16),
(1000, 23, 8),
(1001, 23, 10),
(1002, 23, 20),
(1003, 23, 17),
(1004, 23, 26),
(1005, 23, 2),
(1006, 23, 7),
(1007, 23, 23),
(1008, 23, 12),
(1009, 23, 3),
(1010, 23, 19),
(1011, 23, 21),
(1012, 23, 1),
(1013, 23, 6),
(1014, 23, 11),
(1030, 27, 5),
(1031, 27, 14),
(1032, 27, 18),
(1033, 27, 25),
(1034, 27, 4),
(1035, 27, 22),
(1036, 27, 16),
(1037, 27, 8),
(1038, 27, 10),
(1039, 27, 20),
(1040, 27, 17),
(1041, 27, 26),
(1042, 27, 2),
(1043, 27, 12),
(1044, 27, 19),
(1045, 27, 21),
(1046, 27, 1),
(1047, 28, 5),
(1048, 28, 14),
(1049, 28, 24),
(1050, 28, 18),
(1051, 28, 25),
(1052, 28, 4),
(1053, 28, 22),
(1054, 28, 8),
(1055, 28, 10),
(1056, 28, 20),
(1057, 28, 17),
(1058, 28, 26),
(1059, 28, 2),
(1060, 28, 7),
(1061, 28, 23),
(1062, 28, 12),
(1063, 28, 3),
(1064, 28, 19),
(1065, 28, 21),
(1066, 28, 1),
(1067, 28, 6),
(1068, 56, 26),
(1069, 56, 19),
(1088, 3, 1),
(1089, 1, 1),
(1091, 52, 5),
(1092, 52, 1),
(1093, 74, 5),
(1094, 74, 1),
(1095, 7, 16),
(1096, 7, 2),
(1097, 7, 1),
(1098, 24, 16),
(1099, 24, 8),
(1100, 24, 10),
(1101, 24, 2),
(1102, 24, 7),
(1103, 24, 23),
(1104, 24, 1),
(1105, 24, 11),
(1106, 6, 8),
(1107, 6, 10),
(1108, 6, 2),
(1109, 6, 7),
(1110, 6, 23),
(1111, 6, 1),
(1112, 9, 5),
(1113, 9, 14),
(1114, 9, 13),
(1115, 9, 18),
(1116, 9, 22),
(1117, 9, 8),
(1118, 9, 10),
(1119, 9, 20),
(1120, 9, 26),
(1121, 9, 2),
(1122, 9, 7),
(1123, 9, 23),
(1124, 9, 12),
(1125, 9, 19),
(1126, 9, 21),
(1127, 9, 1),
(1128, 75, 2),
(1129, 75, 1),
(1130, 76, 5),
(1131, 76, 1),
(1132, 77, 1),
(1133, 78, 1),
(1134, 79, 2),
(1135, 79, 1),
(1136, 80, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_moduls`
--

CREATE TABLE `cms_moduls` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_protected` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_moduls`
--

INSERT INTO `cms_moduls` (`id`, `name`, `icon`, `path`, `table_name`, `controller`, `is_protected`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Notifications', 'fa fa-cog', 'notifications', 'cms_notifications', 'NotificationsController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(2, 'Privileges', 'fa fa-cog', 'privileges', 'cms_privileges', 'PrivilegesController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(3, 'Privileges Roles', 'fa fa-cog', 'privileges_roles', 'cms_privileges_roles', 'PrivilegesRolesController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(4, 'Users Management', 'fa fa-users', 'users', 'cms_users', 'AdminCmsUsersController', 0, 1, '2019-08-22 08:23:00', NULL, NULL),
(5, 'Settings', 'fa fa-cog', 'settings', 'cms_settings', 'SettingsController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(6, 'Module Generator', 'fa fa-database', 'module_generator', 'cms_moduls', 'ModulsController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(7, 'Menu Management', 'fa fa-bars', 'menu_management', 'cms_menus', 'MenusController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(8, 'Email Templates', 'fa fa-envelope-o', 'email_templates', 'cms_email_templates', 'EmailTemplatesController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(9, 'Statistic Builder', 'fa fa-dashboard', 'statistic_builder', 'cms_statistics', 'StatisticBuilderController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(10, 'API Generator', 'fa fa-cloud-download', 'api_generator', '', 'ApiCustomController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(11, 'Log User Access', 'fa fa-flag-o', 'logs', 'cms_logs', 'LogsController', 1, 1, '2019-08-22 08:23:00', NULL, NULL),
(12, 'DR Transactions', 'fa fa-circle-o', 'bea_transactions', 'ebs_pull', 'AdminBeaTransactionsController', 0, 0, '2020-10-28 06:04:51', NULL, NULL),
(13, 'ST Status', 'fa fa-circle-o', 'st_status', 'st_status', 'AdminStStatusController', 0, 0, '2020-11-03 04:03:00', NULL, NULL),
(14, 'STS Transactions', 'fa fa-circle-o', 'pos_transactions', 'pos_pull', 'AdminPosTransactionsController', 0, 0, '2020-11-03 04:12:52', NULL, NULL),
(15, 'Store Name', 'fa fa-circle-o', 'stores', 'stores', 'AdminStoresController', 0, 0, '2020-11-13 03:02:23', NULL, NULL),
(16, 'Delivery Receiving', 'fa fa-check-circle-o', 'delivery_receiving', 'ebs_pull', 'AdminDeliveryReceivingController', 0, 0, '2020-12-11 02:25:55', NULL, NULL),
(17, 'STS Receiving', 'fa fa-check-circle-o', 'transfer_receiving', 'pos_pull', 'AdminStoreTransferReceivingController', 0, 0, '2020-12-17 11:31:22', NULL, NULL),
(18, 'STS', 'fa fa-circle-o', 'store_transfers', 'pos_pull', 'AdminStoreTransferController', 0, 0, '2020-12-17 12:11:15', NULL, NULL),
(19, 'Deliveries', 'fa fa-circle-o', 'deliveries', 'ebs_pull', 'AdminDeliveryController', 0, 0, '2020-12-23 06:52:19', NULL, NULL),
(20, 'STW and STR', 'fa fa-circle-o', 'store_pullout', 'pullout', 'AdminPulloutController', 0, 0, '2020-12-28 10:56:19', NULL, NULL),
(21, 'STW and STR Receiving', 'fa fa-check-circle-o', 'pullout_receiving', 'pullout', 'AdminPulloutReceivingController', 0, 0, '2020-12-29 06:48:54', NULL, NULL),
(22, 'Transaction Types', 'fa fa-circle-o', 'transaction_type', 'transaction_type', 'AdminTransactionTypeController', 0, 0, '2021-01-05 06:24:53', NULL, NULL),
(23, 'Reasons', 'fa fa-circle-o', 'reason', 'reason', 'AdminReasonController', 0, 0, '2021-01-05 06:25:49', NULL, NULL),
(24, 'Channels', 'fa fa-circle-o', 'channel', 'channel', 'AdminChannelController', 0, 0, '2021-01-05 08:31:07', NULL, NULL),
(25, 'Transport Types', 'fa fa-circle-o', 'transport_type', 'transport_types', 'AdminTransportTypeController', 0, 0, '2021-02-02 03:47:46', NULL, NULL),
(26, 'Approval Matrix', 'fa fa-circle-o', 'approval_matrix', 'approval_matrix', 'AdminApprovalMatrixController', 0, 0, '2021-02-04 03:40:54', NULL, NULL),
(27, 'STS Approval', 'fa fa-thumbs-up', 'transfer_approval', 'pos_pull', 'AdminStoreTransferApprovalController', 0, 0, '2021-02-05 01:28:04', NULL, NULL),
(28, 'STR Approval', 'fa fa-thumbs-o-up', 'pullout_approval', 'pullout', 'AdminPulloutApprovalController', 0, 0, '2021-02-05 01:29:26', NULL, NULL),
(29, 'Code Generator', 'fa fa-gear', 'code_counter', 'code_counter', 'AdminCodeCounterController', 0, 0, '2021-03-01 06:31:28', NULL, NULL),
(30, 'HQ Pullout', 'fa fa-circle-o', 'hq_pullout', 'pullout', 'AdminHqPulloutController', 0, 0, '2021-03-02 07:13:15', NULL, NULL),
(31, 'HQ Deliveries', 'fa fa-circle-o', 'hq_deliveries', 'ebs_pull', 'AdminHqDeliveryController', 0, 0, '2021-03-02 07:14:30', NULL, NULL),
(32, 'Customer Type', 'fa fa-circle-o', 'customer_type', 'customer_type', 'AdminCustomerTypeController', 0, 0, '2021-03-16 06:14:04', NULL, NULL),
(33, 'STS History', 'fa fa-file-text-o', 'sts_history', 'pos_pull', 'AdminStoreTransferHistoryController', 0, 0, '2021-04-26 07:15:17', NULL, NULL),
(34, 'STW and STR History', 'fa fa-file-text-o', 'pullout_history', 'pullout', 'AdminPulloutHistoryController', 0, 0, '2021-04-26 07:16:56', NULL, NULL),
(35, 'Item Master', 'fa fa-circle-o', 'items', 'items', 'AdminItemsController', 0, 0, '2021-09-14 02:03:45', NULL, NULL),
(36, 'STS Pick Confirm', 'fa fa-file-text-o', 'sts_pick_confirm', 'pos_pull', 'AdminStsPickConfirmController', 0, 0, '2021-09-29 07:13:27', NULL, NULL),
(37, 'STS Picklist', 'fa fa-file-text-o', 'sts_picklist', 'pos_pull', 'AdminStsPicklistController', 0, 0, '2021-09-29 07:17:31', NULL, NULL),
(38, 'STW Pick Confirm', 'fa fa-file-text-o', 'pullout_pick_confirm', 'pullout', 'AdminPulloutPickConfirmController', 0, 0, '2021-09-29 07:20:50', NULL, NULL),
(39, 'STW Picklist', 'fa fa-file-text-o', 'pullout_picklist', 'pullout', 'AdminPulloutPicklistController', 0, 0, '2021-09-29 08:04:03', NULL, NULL),
(40, 'Status Workflow', 'fa fa-circle-o', 'status_workflows', 'status_workflows', 'AdminStatusWorkflowsController', 0, 0, '2021-09-29 09:08:27', NULL, NULL),
(41, 'Problem Details', 'fa fa-circle-o', 'problems', 'problems', 'AdminProblemsController', 0, 0, '2021-10-11 01:36:11', NULL, NULL),
(42, 'Pullout Transactions', 'fa fa-circle-o', 'pullout_transactions', 'pullout', 'AdminPulloutTransactionsController', 0, 0, '2022-02-09 01:38:40', NULL, NULL),
(43, 'Trip Ticket', 'fa fa-file-text-o', 'trip_tickets', 'trip_tickets', 'AdminTripTicketsController', 0, 0, '2022-04-06 09:02:56', NULL, NULL),
(44, 'Distri Subinventory', 'fa fa-circle-o', 'distri_subinventories', 'distri_subinventories', 'AdminDistriSubinventoriesController', 0, 0, '2022-04-13 08:04:45', NULL, NULL),
(45, 'Backload Reason', 'fa fa-circle-o', 'backload_reasons', 'backload_reasons', 'AdminBackloadReasonsController', 0, 0, '2022-04-19 09:41:56', NULL, NULL),
(46, 'Location', 'fa fa-circle-o', 'locations', 'locations', 'AdminLocationsController', 0, 0, '2022-04-25 07:33:41', NULL, NULL),
(47, 'Trip Ticket Status', 'fa fa-circle-o', 'trip_ticket_statuses', 'trip_ticket_statuses', 'AdminTripTicketStatusesController', 0, 0, '2022-04-27 03:57:17', NULL, NULL),
(48, 'WRS', 'fa fa-cubes', 'wrs', 'pullout_receivings', 'AdminWrsController', 0, 0, '2022-04-29 03:24:10', NULL, NULL),
(49, 'Cancel Reason', 'fa fa-circle-o', 'cancel_reasons', 'cancel_reasons', 'AdminCancelReasonsController', 0, 0, '2022-04-29 03:38:27', NULL, NULL),
(50, 'STW Approval', 'fa fa-thumbs-o-up', 'stw_approval', 'pullout', 'AdminStwApprovalController', 0, 0, '2023-02-07 00:39:06', NULL, NULL),
(51, 'Create GIS STS', 'fa fa-file-text', 'store_transfer_gis', 'gis_pulls', 'AdminStoreTransferGisController', 0, 0, '2024-02-27 01:36:37', NULL, NULL),
(52, 'Gis STS Approval', 'fa fa-thumbs-up', 'gis_pull_approval', 'gis_pulls', 'AdminGisPullApprovalController', 0, 0, '2024-02-27 09:01:51', NULL, NULL),
(53, 'ST Statuses', 'fa fa-files-o', 'stock_statuses', 'stock_statuses', 'AdminStockStatusesController', 0, 0, '2024-02-28 02:28:19', NULL, NULL),
(54, 'Transfer Stock Statuses', 'fa fa-files-o', 'transfer_stock_statuses', 'st_status', 'AdminTransferStockStatusesController', 0, 0, '2024-02-28 02:29:46', NULL, NULL),
(55, 'GIS STS Receiving', 'fa fa-check-circle-o', 'st_gis_receiving', 'gis_pulls', 'AdminStGisReceivingController', 0, 0, '2024-02-28 03:02:24', NULL, NULL),
(56, 'STS GIS History', 'fa fa-file-text', 'stock_transfer_gis_history', 'gis_pulls', 'AdminStockTransferGisHistoryController', 0, 0, '2024-02-28 07:35:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_notifications`
--

CREATE TABLE `cms_notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_cms_users` int(11) DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_privileges`
--

CREATE TABLE `cms_privileges` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_superadmin` tinyint(1) DEFAULT NULL,
  `theme_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_privileges`
--

INSERT INTO `cms_privileges` (`id`, `name`, `is_superadmin`, `theme_color`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrator', 1, 'skin-blue', '2019-08-22 08:23:00', NULL),
(2, 'Requestor', 0, 'skin-blue', NULL, NULL),
(3, 'RMA', 0, 'skin-blue', NULL, NULL),
(4, 'LOG TM', 0, 'skin-blue', NULL, NULL),
(5, 'Approver', 0, 'skin-blue', NULL, NULL),
(6, 'Warehouse', 0, 'skin-blue', NULL, NULL),
(7, 'Requestor II', 0, 'skin-blue', NULL, NULL),
(8, 'Online Requestor', 0, 'skin-blue', NULL, NULL),
(9, 'HQ Requestor', 0, 'skin-blue', NULL, NULL),
(10, 'Online Requestor II', 0, 'skin-blue', NULL, NULL),
(11, 'Warehouse Online', 0, 'skin-blue', NULL, NULL),
(12, 'Retail Ops', 0, 'skin-blue', NULL, NULL),
(13, 'Franchise Ops', 0, 'skin-blue', NULL, NULL),
(14, 'Audit', 0, 'skin-blue', NULL, NULL),
(15, 'SDM - Reports', 0, 'skin-blue', NULL, NULL),
(16, 'Online Ops', 0, 'skin-blue', NULL, NULL),
(17, 'Online WSDM', 0, 'skin-blue', NULL, NULL),
(18, 'Inventory Control', 0, 'skin-blue', NULL, NULL),
(19, 'Rtl Fra Ops', 0, 'skin-blue', NULL, NULL),
(20, 'Online Viewer', 0, 'skin-blue', NULL, NULL),
(21, 'Rtl Onl Viewer', 0, 'skin-blue', NULL, NULL),
(22, 'Merch', 0, 'skin-blue', NULL, NULL),
(23, 'Requestor III', 0, 'skin-blue', NULL, NULL),
(24, 'Distri Ops', 0, 'skin-blue', NULL, NULL),
(25, 'LOG TL', 0, 'skin-blue', NULL, NULL),
(26, 'Reports', 0, 'skin-blue', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_privileges_roles`
--

CREATE TABLE `cms_privileges_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `is_visible` tinyint(1) DEFAULT NULL,
  `is_create` tinyint(1) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT NULL,
  `is_edit` tinyint(1) DEFAULT NULL,
  `is_delete` tinyint(1) DEFAULT NULL,
  `id_cms_privileges` int(11) DEFAULT NULL,
  `id_cms_moduls` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_privileges_roles`
--

INSERT INTO `cms_privileges_roles` (`id`, `is_visible`, `is_create`, `is_read`, `is_edit`, `is_delete`, `id_cms_privileges`, `id_cms_moduls`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 0, 0, 0, 1, 1, '2019-08-22 08:23:00', NULL),
(2, 1, 1, 1, 1, 1, 1, 2, '2019-08-22 08:23:00', NULL),
(3, 0, 1, 1, 1, 1, 1, 3, '2019-08-22 08:23:00', NULL),
(4, 1, 1, 1, 1, 1, 1, 4, '2019-08-22 08:23:00', NULL),
(5, 1, 1, 1, 1, 1, 1, 5, '2019-08-22 08:23:00', NULL),
(6, 1, 1, 1, 1, 1, 1, 6, '2019-08-22 08:23:00', NULL),
(7, 1, 1, 1, 1, 1, 1, 7, '2019-08-22 08:23:00', NULL),
(8, 1, 1, 1, 1, 1, 1, 8, '2019-08-22 08:23:00', NULL),
(9, 1, 1, 1, 1, 1, 1, 9, '2019-08-22 08:23:00', NULL),
(10, 1, 1, 1, 1, 1, 1, 10, '2019-08-22 08:23:00', NULL),
(11, 1, 0, 1, 0, 1, 1, 11, '2019-08-22 08:23:00', NULL),
(12, 1, 1, 1, 1, 1, 1, 12, NULL, NULL),
(13, 1, 1, 1, 1, 1, 1, 13, NULL, NULL),
(14, 1, 1, 1, 1, 1, 1, 14, NULL, NULL),
(15, 1, 1, 1, 1, 1, 1, 15, NULL, NULL),
(16, 1, 1, 1, 1, 1, 1, 16, NULL, NULL),
(17, 1, 1, 1, 1, 1, 1, 17, NULL, NULL),
(18, 1, 1, 1, 1, 1, 1, 18, NULL, NULL),
(19, 1, 1, 1, 1, 0, 2, 18, NULL, NULL),
(20, 1, 0, 1, 1, 0, 2, 17, NULL, NULL),
(21, 1, 0, 1, 1, 0, 2, 16, NULL, NULL),
(22, 1, 1, 1, 1, 1, 1, 19, NULL, NULL),
(23, 1, 0, 1, 0, 0, 2, 19, NULL, NULL),
(24, 1, 1, 1, 1, 1, 1, 20, NULL, NULL),
(25, 1, 1, 1, 1, 0, 2, 20, NULL, NULL),
(26, 1, 1, 1, 1, 1, 1, 21, NULL, NULL),
(27, 1, 0, 1, 1, 0, 2, 21, NULL, NULL),
(28, 1, 1, 1, 1, 1, 1, 22, NULL, NULL),
(29, 1, 1, 1, 1, 1, 1, 23, NULL, NULL),
(30, 1, 1, 1, 1, 1, 1, 24, NULL, NULL),
(31, 1, 0, 1, 0, 0, 3, 19, NULL, NULL),
(32, 1, 0, 1, 1, 0, 3, 16, NULL, NULL),
(33, 1, 0, 1, 0, 0, 3, 21, NULL, NULL),
(34, 1, 1, 1, 0, 0, 3, 18, NULL, NULL),
(35, 1, 0, 1, 1, 0, 3, 17, NULL, NULL),
(36, 1, 0, 1, 0, 0, 3, 20, NULL, NULL),
(37, 1, 0, 1, 1, 0, 3, 4, NULL, NULL),
(38, 1, 0, 1, 1, 0, 4, 18, NULL, NULL),
(39, 1, 0, 1, 1, 0, 4, 20, NULL, NULL),
(40, 1, 0, 1, 1, 0, 4, 4, NULL, NULL),
(41, 1, 1, 1, 1, 1, 1, 25, NULL, NULL),
(42, 1, 0, 1, 1, 0, 2, 4, NULL, NULL),
(43, 1, 0, 1, 0, 0, 5, 18, NULL, NULL),
(44, 1, 0, 1, 0, 0, 5, 20, NULL, NULL),
(45, 1, 0, 1, 1, 0, 5, 4, NULL, NULL),
(46, 1, 1, 1, 1, 1, 1, 26, NULL, NULL),
(47, 1, 1, 1, 1, 1, 1, 27, NULL, NULL),
(48, 1, 1, 1, 1, 1, 1, 28, NULL, NULL),
(49, 1, 0, 1, 1, 0, 5, 27, NULL, NULL),
(50, 1, 0, 1, 1, 0, 5, 28, NULL, NULL),
(51, 1, 0, 1, 0, 0, 6, 19, NULL, NULL),
(52, 1, 0, 1, 1, 0, 6, 21, NULL, NULL),
(53, 1, 0, 1, 0, 0, 6, 20, NULL, NULL),
(54, 1, 0, 1, 1, 0, 6, 4, NULL, NULL),
(55, 1, 0, 1, 0, 0, 7, 19, NULL, NULL),
(56, 1, 0, 1, 1, 0, 7, 16, NULL, NULL),
(57, 1, 0, 1, 0, 0, 7, 20, NULL, NULL),
(58, 1, 0, 1, 1, 0, 7, 4, NULL, NULL),
(59, 1, 1, 1, 1, 1, 1, 29, NULL, NULL),
(60, 1, 1, 1, 1, 1, 1, 30, NULL, NULL),
(61, 1, 1, 1, 1, 1, 1, 31, NULL, NULL),
(62, 1, 0, 1, 0, 0, 8, 19, NULL, NULL),
(63, 1, 0, 1, 1, 0, 8, 16, NULL, NULL),
(64, 1, 1, 1, 0, 0, 8, 20, NULL, NULL),
(65, 1, 0, 1, 1, 0, 8, 4, NULL, NULL),
(66, 1, 0, 1, 1, 0, 9, 31, NULL, NULL),
(67, 1, 1, 1, 0, 0, 9, 30, NULL, NULL),
(68, 1, 0, 1, 1, 0, 9, 4, NULL, NULL),
(69, 1, 1, 1, 1, 1, 1, 32, NULL, NULL),
(70, 1, 1, 1, 0, 0, 8, 18, NULL, NULL),
(71, 1, 0, 1, 0, 0, 10, 19, NULL, NULL),
(72, 1, 0, 1, 1, 0, 10, 16, NULL, NULL),
(73, 1, 1, 1, 0, 0, 10, 18, NULL, NULL),
(74, 1, 1, 1, 0, 0, 10, 20, NULL, NULL),
(75, 1, 0, 1, 1, 0, 10, 4, NULL, NULL),
(76, 1, 0, 1, 0, 0, 11, 19, NULL, NULL),
(77, 1, 0, 1, 1, 0, 11, 17, NULL, NULL),
(78, 1, 0, 1, 0, 0, 11, 20, NULL, NULL),
(79, 1, 0, 1, 1, 0, 11, 21, NULL, NULL),
(80, 1, 0, 1, 1, 0, 11, 4, NULL, NULL),
(81, 1, 1, 1, 1, 1, 1, 33, NULL, NULL),
(82, 1, 1, 1, 1, 1, 1, 34, NULL, NULL),
(83, 1, 0, 1, 0, 0, 2, 33, NULL, NULL),
(84, 1, 0, 1, 0, 0, 2, 34, NULL, NULL),
(85, 1, 0, 1, 0, 0, 7, 34, NULL, NULL),
(86, 1, 0, 1, 0, 0, 8, 33, NULL, NULL),
(87, 1, 0, 1, 0, 0, 8, 34, NULL, NULL),
(88, 1, 0, 1, 0, 0, 10, 33, NULL, NULL),
(89, 1, 0, 1, 0, 0, 10, 34, NULL, NULL),
(90, 1, 0, 1, 0, 0, 5, 33, NULL, NULL),
(91, 1, 0, 1, 0, 0, 5, 34, NULL, NULL),
(92, 1, 0, 1, 0, 0, 4, 33, NULL, NULL),
(93, 1, 0, 1, 0, 0, 4, 34, NULL, NULL),
(94, 1, 0, 1, 0, 0, 6, 34, NULL, NULL),
(95, 1, 0, 1, 0, 0, 11, 34, NULL, NULL),
(96, 1, 0, 1, 0, 0, 3, 34, NULL, NULL),
(97, 1, 0, 1, 0, 0, 11, 33, NULL, NULL),
(98, 1, 0, 1, 0, 0, 12, 19, NULL, NULL),
(99, 1, 0, 1, 1, 0, 12, 4, NULL, NULL),
(100, 1, 0, 1, 0, 0, 13, 19, NULL, NULL),
(101, 1, 0, 1, 1, 0, 13, 4, NULL, NULL),
(102, 1, 0, 1, 0, 0, 14, 19, NULL, NULL),
(103, 1, 0, 1, 1, 0, 14, 4, NULL, NULL),
(104, 1, 0, 1, 1, 0, 15, 4, NULL, NULL),
(105, 1, 1, 1, 1, 1, 1, 35, NULL, NULL),
(106, 1, 0, 1, 0, 0, 16, 19, NULL, NULL),
(107, 1, 0, 1, 1, 0, 16, 17, NULL, NULL),
(108, 1, 0, 1, 0, 0, 16, 34, NULL, NULL),
(109, 1, 0, 1, 1, 0, 16, 4, NULL, NULL),
(110, 1, 1, 1, 1, 1, 1, 36, NULL, NULL),
(111, 1, 1, 1, 1, 1, 1, 37, NULL, NULL),
(112, 1, 1, 1, 1, 1, 1, 38, NULL, NULL),
(113, 1, 1, 1, 1, 1, 1, 39, NULL, NULL),
(114, 1, 0, 1, 0, 0, 17, 33, NULL, NULL),
(115, 1, 0, 1, 0, 0, 17, 36, NULL, NULL),
(116, 1, 0, 1, 0, 0, 17, 37, NULL, NULL),
(117, 1, 0, 1, 0, 0, 17, 34, NULL, NULL),
(118, 1, 0, 1, 0, 0, 17, 38, NULL, NULL),
(119, 1, 0, 1, 0, 0, 17, 39, NULL, NULL),
(120, 1, 0, 1, 1, 0, 17, 4, NULL, NULL),
(121, 1, 1, 1, 1, 1, 1, 40, NULL, NULL),
(122, 1, 0, 1, 0, 0, 18, 19, NULL, NULL),
(123, 1, 0, 1, 1, 0, 18, 4, NULL, NULL),
(124, 1, 1, 1, 1, 1, 1, 41, NULL, NULL),
(125, 1, 0, 1, 0, 0, 16, 33, NULL, NULL),
(126, 1, 0, 1, 0, 0, 18, 33, NULL, NULL),
(127, 1, 0, 1, 0, 0, 18, 34, NULL, NULL),
(128, 1, 0, 1, 0, 0, 19, 19, NULL, NULL),
(129, 1, 0, 1, 0, 0, 19, 33, NULL, NULL),
(130, 1, 0, 1, 0, 0, 19, 34, NULL, NULL),
(131, 1, 0, 1, 1, 0, 19, 4, NULL, NULL),
(132, 1, 0, 1, 0, 0, 12, 33, NULL, NULL),
(133, 1, 0, 1, 0, 0, 12, 34, NULL, NULL),
(134, 1, 0, 1, 0, 0, 20, 19, NULL, NULL),
(135, 1, 0, 1, 0, 0, 20, 33, NULL, NULL),
(136, 1, 0, 1, 0, 0, 20, 34, NULL, NULL),
(137, 1, 0, 1, 1, 0, 20, 4, NULL, NULL),
(138, 1, 0, 1, 0, 0, 21, 19, NULL, NULL),
(139, 1, 0, 1, 0, 0, 21, 33, NULL, NULL),
(140, 1, 0, 1, 0, 0, 21, 34, NULL, NULL),
(141, 1, 0, 1, 1, 0, 21, 4, NULL, NULL),
(142, 1, 0, 1, 0, 0, 22, 19, NULL, NULL),
(143, 1, 0, 1, 0, 0, 22, 33, NULL, NULL),
(144, 1, 0, 1, 0, 0, 22, 34, NULL, NULL),
(145, 1, 0, 1, 1, 0, 22, 4, NULL, NULL),
(146, 1, 0, 1, 0, 0, 14, 33, NULL, NULL),
(147, 1, 0, 1, 0, 0, 14, 34, NULL, NULL),
(148, 1, 1, 1, 1, 1, 1, 42, NULL, NULL),
(149, 1, 0, 1, 0, 0, 23, 19, NULL, NULL),
(150, 1, 0, 1, 0, 0, 23, 16, NULL, NULL),
(151, 1, 0, 1, 0, 0, 23, 20, NULL, NULL),
(152, 1, 0, 1, 0, 0, 23, 34, NULL, NULL),
(153, 1, 0, 1, 1, 0, 23, 4, NULL, NULL),
(154, 1, 0, 1, 0, 0, 24, 19, NULL, NULL),
(155, 1, 0, 1, 0, 0, 24, 34, NULL, NULL),
(156, 1, 0, 1, 1, 0, 24, 4, NULL, NULL),
(157, 1, 1, 1, 1, 1, 1, 43, NULL, NULL),
(158, 1, 1, 1, 1, 1, 1, 44, NULL, NULL),
(159, 1, 1, 1, 1, 1, 1, 45, NULL, NULL),
(160, 1, 1, 1, 1, 1, 1, 46, NULL, NULL),
(161, 1, 0, 1, 1, 0, 25, 18, NULL, NULL),
(162, 1, 0, 1, 0, 0, 25, 20, NULL, NULL),
(163, 1, 1, 1, 1, 0, 25, 43, NULL, NULL),
(164, 1, 0, 1, 1, 0, 25, 4, NULL, NULL),
(165, 1, 0, 1, 0, 0, 25, 33, NULL, NULL),
(166, 1, 0, 1, 0, 0, 25, 34, NULL, NULL),
(167, 1, 1, 1, 0, 0, 4, 43, NULL, NULL),
(168, 1, 0, 1, 0, 0, 2, 43, NULL, NULL),
(169, 1, 1, 1, 1, 1, 1, 47, NULL, NULL),
(170, 1, 1, 1, 1, 1, 1, 48, NULL, NULL),
(171, 1, 1, 1, 1, 1, 1, 49, NULL, NULL),
(172, 1, 0, 1, 0, 0, 5, 19, NULL, NULL),
(173, 1, 0, 1, 0, 0, 26, 19, NULL, NULL),
(174, 1, 0, 1, 0, 0, 26, 33, NULL, NULL),
(175, 1, 0, 1, 0, 0, 26, 34, NULL, NULL),
(176, 1, 0, 1, 1, 0, 26, 4, NULL, NULL),
(177, 1, 1, 1, 1, 1, 1, 50, NULL, NULL),
(178, 1, 0, 1, 1, 0, 5, 50, NULL, NULL),
(179, 1, 1, 1, 1, 0, 2, 51, NULL, NULL),
(180, 1, 1, 1, 1, 1, 1, 52, NULL, NULL),
(181, 1, 0, 1, 1, 0, 5, 52, NULL, NULL),
(182, 1, 1, 1, 1, 1, 1, 53, NULL, NULL),
(183, 1, 1, 1, 1, 1, 1, 54, NULL, NULL),
(184, 1, 1, 1, 1, 1, 1, 55, NULL, NULL),
(185, 1, 0, 1, 1, 0, 2, 55, NULL, NULL),
(186, 1, 1, 1, 1, 1, 1, 56, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_settings`
--

CREATE TABLE `cms_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `content_input_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dataenum` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `helper` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `group_setting` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_settings`
--

INSERT INTO `cms_settings` (`id`, `name`, `content`, `content_input_type`, `dataenum`, `helper`, `created_at`, `updated_at`, `group_setting`, `label`) VALUES
(1, 'login_background_color', NULL, 'text', NULL, 'Input hexacode', '2019-08-22 08:23:01', NULL, 'Login Register Style', 'Login Background Color'),
(2, 'login_font_color', NULL, 'text', NULL, 'Input hexacode', '2019-08-22 08:23:01', NULL, 'Login Register Style', 'Login Font Color'),
(3, 'login_background_image', 'uploads/2023-07/8f22ea02a850b1fcd58c9a5de33eb52c.jpg', 'upload_image', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Login Register Style', 'Login Background Image'),
(4, 'email_sender', 'noreply@digits.ph', 'text', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Email Setting', 'Email Sender'),
(5, 'smtp_driver', 'smtp', 'select', 'smtp,mail,sendmail', NULL, '2019-08-22 08:23:01', NULL, 'Email Setting', 'Mail Driver'),
(6, 'smtp_host', 'smtp.gmail.com', 'text', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Email Setting', 'SMTP Host'),
(7, 'smtp_port', '465', 'text', NULL, 'default 25', '2019-08-22 08:23:01', NULL, 'Email Setting', 'SMTP Port'),
(8, 'smtp_username', 'noreply@digits.ph', 'text', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Email Setting', 'SMTP Username'),
(9, 'smtp_password', 'edqpzdjyrvbrfomn', 'text', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Email Setting', 'SMTP Password'),
(10, 'appname', 'Middleware System', 'text', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Application Setting', 'Application Name'),
(11, 'default_paper_size', 'Legal', 'text', NULL, 'Paper size, ex : A4, Legal, etc', '2019-08-22 08:23:01', NULL, 'Application Setting', 'Default Paper Print Size'),
(12, 'logo', 'uploads/2021-01/237c13e5b21a5fb251ffced8a8f53f01.png', 'upload_image', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Application Setting', 'Logo'),
(13, 'favicon', 'uploads/2022-06/c016ea18aedc09a70ef44f997634dfb3.png', 'upload_image', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Application Setting', 'Favicon'),
(14, 'api_debug_mode', 'false', 'select', 'true,false', NULL, '2019-08-22 08:23:01', NULL, 'Application Setting', 'API Debug Mode'),
(15, 'google_api_key', NULL, 'text', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Application Setting', 'Google API Key'),
(16, 'google_fcm_key', NULL, 'text', NULL, NULL, '2019-08-22 08:23:01', NULL, 'Application Setting', 'Google FCM Key');

-- --------------------------------------------------------

--
-- Table structure for table `cms_statistics`
--

CREATE TABLE `cms_statistics` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_statistics`
--

INSERT INTO `cms_statistics` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Requestor Dashboard', 'requestor-dashboard', '2021-04-26 07:27:07', NULL),
(2, 'Warehouse/RMA Dashboard', 'warehouse-dashboard', '2021-05-04 03:21:42', '2021-05-04 03:28:37'),
(3, 'Admin Dashboard', 'admin-dashboard', '2021-05-04 06:36:09', NULL),
(4, 'Approver Dashboard', 'approver-dashboard', '2021-05-04 06:50:43', NULL),
(5, 'Logistics Dashboard', 'logistics-dashboard', '2021-05-04 06:50:53', NULL),
(6, 'Online Warehouse Dashboard', 'online-warehouse-dashboard', '2021-05-04 09:19:13', NULL),
(7, 'Requestor II Dashboard', 'requestor-ii', '2021-05-04 09:39:22', '2021-05-05 01:01:31'),
(8, 'Online Requestor II Dashboard', 'online-requestor-ii-dashboard', '2021-10-19 14:51:33', NULL),
(9, 'Online WSDM Dashboard', 'online-wsdm-dashboard', '2021-10-19 14:51:48', NULL),
(10, 'Online Ops Dashboard', 'online-ops-dashboard', '2021-10-19 14:52:02', NULL),
(11, 'Retail Ops Dashboard', 'retail-ops-dashboard', '2021-12-04 02:59:13', NULL),
(12, 'Franchise Ops Dashboard', 'franchise-ops-dashboard', '2021-12-04 02:59:37', NULL),
(13, 'RTL FRA Ops Dashboard', 'rtl-fra-ops-dashboard', '2021-12-04 02:59:50', NULL),
(14, 'RTL ONL Viewer Dashboard', 'rtl-onl-viewer-dashboard', '2021-12-10 08:58:41', NULL),
(15, 'Online Viewer Dashboard', 'online-viewer-dashboard', '2021-12-10 08:58:58', NULL),
(16, 'Merch Dashboard', 'merch-dashboard', '2021-12-10 11:01:13', NULL),
(17, 'Requestor III Dashboard', 'requestor-iii-dashboard', '2022-03-29 06:20:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_statistic_components`
--

CREATE TABLE `cms_statistic_components` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_cms_statistics` int(11) DEFAULT NULL,
  `componentID` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `component_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_name` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sorting` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_statistic_components`
--

INSERT INTO `cms_statistic_components` (`id`, `id_cms_statistics`, `componentID`, `component_name`, `area_name`, `sorting`, `name`, `config`, `created_at`, `updated_at`) VALUES
(1, 1, '9ac06976e3d1147739767ebdf12706a2', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Delivery Receiving\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/delivery_receiving?q=PENDING\",\"sql\":\"SELECT COUNT(DISTINCT ebs_pull.dr_number) FROM `ebs_pull` WHERE `ebs_pull`.status=\\\"PENDING\\\" AND (`ebs_pull`.customer_name LIKE \'%[admin_store_so_name]%\' OR `ebs_pull`.customer_name LIKE \'%[admin_store_mo_name]%\')\"}', '2021-04-26 07:27:54', NULL),
(3, 2, 'f3995a0764fcf7871800953718fea25f', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Pullout Receiving\",\"icon\":\"ion-cube\",\"color\":\"bg-green\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_receiving\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"FOR RECEIVING\\\" AND `pullout`.wh_to LIKE \'%[admin_pos_warehouse]%\'\"}', '2021-05-04 03:23:22', NULL),
(4, 4, '30168fcf1b4802fb76b115bf587bdf7f', 'smallbox', 'area1', 0, NULL, '{\"name\":\"STS for Approval\",\"icon\":\"ion-thumbsup\",\"color\":\"bg-green\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/transfer_approval\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"PENDING\\\" AND `pos_pull`.stores_id IN [approval_store_ids]\"}', '2021-05-04 06:51:03', NULL),
(5, 4, 'dcaa7b3219b15975398d51e4b6422d2c', 'smallbox', 'area2', 0, NULL, '{\"name\":\"STW & STR for Approval\",\"icon\":\"ion-thumbsup\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_approval\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" AND `pullout`.stores_id IN [approval_store_ids]\"}', '2021-05-04 06:51:04', NULL),
(6, 5, 'c443eb3f779310f2a1c79d5542536679', 'smallbox', 'area1', 0, NULL, '{\"name\":\"STS for Schedule\",\"icon\":\"ion-calendar\",\"color\":\"bg-green\",\"link\":\"http:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/store_transfers\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"FOR SCHEDULE\\\" AND `pos_pull`.transport_types_id = 1\"}', '2021-05-04 07:31:48', NULL),
(7, 5, '84926f8a0b7574cdedad76caae52a68e', 'smallbox', 'area2', 0, NULL, '{\"name\":\"STW & STR for Schedule\",\"icon\":\"ion-calendar\",\"color\":\"bg-aqua\",\"link\":\"http:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/store_pullout\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"FOR SCHEDULE\\\" AND `pullout`.transport_types_id = 1\"}', '2021-05-04 07:31:49', NULL),
(8, 6, '7666e9d4ffc6a9a2f458abc4fde23b07', 'smallbox', 'area1', 0, NULL, '{\"name\":\"STS for Receiving\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/transfer_receiving\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"FOR RECEIVING\\\" AND `pos_pull`.stores_id_destination IN [onlwh_store_ids]\"}', '2021-05-04 09:19:41', NULL),
(9, 7, 'cea6e41256105635f6027e61b1d82fdc', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Delivery Receiving\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/delivery_receiving\",\"sql\":\"SELECT COUNT(DISTINCT ebs_pull.dr_number) FROM `ebs_pull` WHERE `ebs_pull`.status=\\\"PENDING\\\" AND (`ebs_pull`.customer_name LIKE \'%[admin_store_so_name]%\' OR `ebs_pull`.customer_name LIKE \'%[admin_store_mo_name]%\')\"}', '2021-05-04 09:40:01', NULL),
(10, 3, '75c7fa69da00a1723362d1f607e93a0d', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Pending DR\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/deliveries?filter_column%5Bebs_pull.status%5D%5Btype%5D=%3D&filter_column%5Bebs_pull.status%5D%5Bvalue%5D=pending\",\"sql\":\"select FORMAT(COUNT(distinct dr_number),0) from ebs_pull where status=\'PENDING\'\"}', '2021-07-13 14:27:53', NULL),
(11, 3, '480bd267154c2715efebc6dbbe3ae105', 'smallbox', 'area2', 0, NULL, '{\"name\":\"Processing DR\",\"icon\":\"ion-alert\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/deliveries?filter_column%5Bebs_pull.customer_name%5D%5Bsorting%5D=asc&filter_column%5Bebs_pull.status%5D%5Btype%5D=like&filter_column%5Bebs_pull.status%5D%5Bvalue%5D=processing\",\"sql\":\"select COUNT(distinct dr_number) from ebs_pull where status=\'PROCESSING\'\"}', '2021-07-13 14:31:27', NULL),
(12, 3, '475f1655690448f81e84e6fb80e52d67', 'smallbox', 'area3', 0, NULL, '{\"name\":\"Closed DR\",\"icon\":\"ion-cube\",\"color\":\"bg-red\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/deliveries?filter_column%5Bebs_pull.status%5D%5Btype%5D=%3D&filter_column%5Bebs_pull.status%5D%5Bvalue%5D=closed\",\"sql\":\"select COUNT(distinct dr_number) from ebs_pull where status=\'CLOSED\'\"}', '2021-07-13 14:38:24', NULL),
(13, 3, 'edc9034ef9cc54ee465f4d4630baaa91', 'smallbox', 'area4', 0, NULL, '{\"name\":\"Received DR\",\"icon\":\"ion-cube\",\"color\":\"bg-green\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/deliveries?filter_column%5Bebs_pull.status%5D%5Btype%5D=%3D&filter_column%5Bebs_pull.status%5D%5Bvalue%5D=received\",\"sql\":\"select FORMAT(COUNT(distinct dr_number),0) from ebs_pull where status=\'RECEIVED\'\"}', '2021-07-13 14:40:08', NULL),
(14, 3, 'afb5a5d062f2b919c67e835b07831ea4', 'chartline', 'area5', 0, NULL, '{\"name\":\"Received DR Counter\",\"sql\":\"select COUNT(distinct dr_number) as value,date_format(received_at,\'%Y-%m-%d\') as label from ebs_pull where status=\'RECEIVED\' and created_at > \'2023-01-01 00:00:00\'  group by label order by label asc\",\"area_name\":\"Received DR\",\"goals\":\"0\"}', '2021-07-13 14:41:19', NULL),
(15, 10, '0e89dcde7ce2d441c6921c29f12cfef5', 'smallbox', 'area1', 0, NULL, '{\"name\":\"STS for Receiving\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/transfer_receiving\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"FOR RECEIVING\\\" and `pos_pull`.wh_to like \'%FBD\'\"}', '2021-10-19 14:53:02', NULL),
(16, 8, '23c8450ded42d6d5fc16542bb7a6808b', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Delivery Receiving\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/delivery_receiving\",\"sql\":\"SELECT COUNT(DISTINCT ebs_pull.dr_number) FROM `ebs_pull` WHERE `ebs_pull`.status=\\\"PENDING\\\" AND (`ebs_pull`.customer_name LIKE \'%[admin_store_so_name]%\' OR `ebs_pull`.customer_name LIKE \'%[admin_store_mo_name]%\')\"}', '2021-10-19 14:54:36', NULL),
(17, 9, 'de7c36fe6b2bf7a74c53f82b9142be53', 'smallbox', 'area1', 0, NULL, '{\"name\":\"STS for PICKLIST\",\"icon\":\"ion-document\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/sts_picklist?q=FOR+PICKLIST\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"FOR PICKLIST\\\"\"}', '2021-10-19 14:55:39', NULL),
(18, 9, '32e9dce202b07a7f577f5fbbef44a295', 'smallbox', 'area2', 0, NULL, '{\"name\":\"STS for PICK CONFIRM\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/sts_pick_confirm\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"FOR PICK CONFIRM\\\"\"}', '2021-10-19 14:55:40', NULL),
(19, 9, '16fd55036c271c568932a285e258f045', 'smallbox', 'area3', 0, NULL, '{\"name\":\"STW for PICKLIST\",\"icon\":\"ion-document\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_picklist\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"FOR PICKLIST\\\"\"}', '2021-10-19 14:55:41', NULL),
(20, 9, '2f9a02be0069c377ac7f933e80a63e66', 'smallbox', 'area4', 0, NULL, '{\"name\":\"STW for PICK CONFIRM\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_pick_confirm\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"FOR PICK CONFIRM\\\"\"}', '2021-10-19 14:55:42', NULL),
(21, 3, 'f718f2336cfc8c3751aa217a39433995', 'chartline', 'area5', 1, NULL, '{\"name\":\"Received STW Counter\",\"sql\":\"select COUNT(distinct st_document_number) as value,date_format(received_st_date,\'%Y-%m-%d\') as label from pullout where status=\'RECEIVED\' and wh_to=\'DIGITSWAREHOUSE\'  and created_at > \'2023-01-01 00:00:00\' group by label order by label asc\",\"area_name\":\"Received STW\",\"goals\":\"0\"}', '2021-11-19 10:14:16', NULL),
(22, 3, 'baff3fbb17127f29fe9511d0e7a3a2ec', 'chartline', 'area5', 2, NULL, '{\"name\":\"Received STR Counter\",\"sql\":\"select COUNT(distinct st_document_number) as value,date_format(received_st_date,\'%Y-%m-%d\') as label from pullout where status=\'RECEIVED\' and wh_to=\'DIGITSRMA\' and created_at > \'2023-01-01 00:00:00\' group by label order by label asc\",\"area_name\":\"Received STR\",\"goals\":\"0\"}', '2021-11-19 10:19:53', NULL),
(23, 1, 'b1763359b01d4e74f9585ee5c5f3ac76', 'smallbox', 'area2', 0, NULL, '{\"name\":\"STS Receiving\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/transfer_receiving?q=FOR+RECEIVING\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"FOR RECEIVING\\\" and `pos_pull`.wh_to LIKE \'%[admin_pos_warehouse]%\'\"}', '2021-12-02 13:51:13', NULL),
(24, 13, 'fc69901d52edb99c80891f278405fabb', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Pending DR\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/deliveries?filter_column%5Bebs_pull.status%5D%5Btype%5D=%3D&filter_column%5Bebs_pull.status%5D%5Bvalue%5D=pending\",\"sql\":\"select COUNT(distinct dr_number) from ebs_pull where status=\'PENDING\' and SUBSTRING(customer_name,-3) IN (\'FRA\',\'RTL\')\"}', '2021-12-04 03:00:53', NULL),
(25, 13, 'fd3c067fe3162dba3d570176167d10c0', 'smallbox', 'area2', 0, NULL, '{\"name\":\"Pending STS\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/sts_history?q=PENDING\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"PENDING\\\" and `pos_pull`.channel_id IN (1,2)\"}', '2021-12-04 03:03:40', NULL),
(26, 13, 'd035fa497f93c28266d2df62a7cf57ea', 'smallbox', 'area3', 0, NULL, '{\"name\":\"Pending STW\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSWAREHOUSE&filter_column%5Bpullout.status%5D%5Btype%5D=%3D&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSWAREHOUSE\\\" and `pullout`.channel_id in (1,2)\"}', '2021-12-04 03:06:26', NULL),
(27, 13, '71a959e09e3c519bcdde9dab3c84a80c', 'smallbox', 'area4', 0, NULL, '{\"name\":\"Pending STR\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSRMA&filter_column%5Bpullout.status%5D%5Btype%5D=like&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSRMA\\\" and `pullout`.channel_id IN (1,2)\"}', '2021-12-04 03:06:28', NULL),
(28, 11, 'c0001c5d7145133c2a98134722e4828d', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Pending DR\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/deliveries?filter_column%5Bebs_pull.status%5D%5Btype%5D=%3D&filter_column%5Bebs_pull.status%5D%5Bvalue%5D=pending\",\"sql\":\"select COUNT(distinct dr_number) from ebs_pull where status=\'PENDING\' and SUBSTRING(customer_name,-3) IN (\'RTL\')\"}', '2021-12-07 05:56:41', NULL),
(29, 11, '623d205d2ff550f6cf4b4bfee2e5853a', 'smallbox', 'area2', 0, NULL, '{\"name\":\"Pending STS\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/sts_history?q=PENDING\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"PENDING\\\" and `pos_pull`.channel_id IN (1)\"}', '2021-12-07 05:56:42', NULL),
(30, 11, '21f899ea028d31aa6bb9b34e2ddfae00', 'smallbox', 'area3', 0, NULL, '{\"name\":\"Pending STW\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSWAREHOUSE&filter_column%5Bpullout.status%5D%5Btype%5D=%3D&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSWAREHOUSE\\\" and `pullout`.channel_id in (1)\"}', '2021-12-07 05:56:43', NULL),
(31, 11, '5d0a90c50f88709fd5340f99e9e39dfe', 'smallbox', 'area4', 0, NULL, '{\"name\":\"Pending STR\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSRMA&filter_column%5Bpullout.status%5D%5Btype%5D=like&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSRMA\\\" and `pullout`.channel_id IN (1)\"}', '2021-12-07 05:56:44', NULL),
(32, 14, '21e2cc63bc57661f96c5dc58ece4b9c4', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Pending DR\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/deliveries?filter_column%5Bebs_pull.status%5D%5Btype%5D=%3D&filter_column%5Bebs_pull.status%5D%5Bvalue%5D=pending\",\"sql\":\"select COUNT(distinct dr_number) from ebs_pull where status=\'PENDING\' and SUBSTRING(customer_name,-3) IN (\'FBV\',\'FBD\',\'RTL\')\"}', '2021-12-10 08:59:23', NULL),
(33, 14, '9fb4421e5e418d79956e21d8dc969fc9', 'smallbox', 'area2', 0, NULL, '{\"name\":\"Pending STS\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/sts_history?q=PENDING\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"PENDING\\\" and `pos_pull`.channel_id IN (1,4)\"}', '2021-12-10 08:59:24', NULL),
(34, 14, '11211661b145177e3482203fdebbcf9c', 'smallbox', 'area3', 0, NULL, '{\"name\":\"Pending STW\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSWAREHOUSE&filter_column%5Bpullout.status%5D%5Btype%5D=%3D&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSWAREHOUSE\\\" and `pullout`.channel_id in (1,4)\"}', '2021-12-10 08:59:25', NULL),
(35, 14, '21f423fdb08332029a34a9e6632dec77', 'smallbox', 'area4', 0, NULL, '{\"name\":\"Pending STR\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSRMA&filter_column%5Bpullout.status%5D%5Btype%5D=like&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSRMA\\\" and `pullout`.channel_id IN (1,4)\"}', '2021-12-10 08:59:26', NULL),
(36, 15, '5c00885ccc3f6e9d2b1cfbed4a891f28', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Pending DR\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/deliveries?filter_column%5Bebs_pull.status%5D%5Btype%5D=%3D&filter_column%5Bebs_pull.status%5D%5Bvalue%5D=pending\",\"sql\":\"select COUNT(distinct dr_number) from ebs_pull where status=\'PENDING\' and SUBSTRING(customer_name,-3) IN (\'FBV\',\'FBD\')\"}', '2021-12-10 10:05:42', NULL),
(37, 15, 'c271c5c004f443a83f72652bcd1aae11', 'smallbox', 'area2', 0, NULL, '{\"name\":\"Pending STS\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/sts_history?q=PENDING\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"PENDING\\\" and `pos_pull`.channel_id IN (4)\"}', '2021-12-10 10:05:44', NULL),
(38, 15, 'b242a88feaddcaf8b7d5b793e341c246', 'smallbox', 'area3', 0, NULL, '{\"name\":\"Pending STW\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSWAREHOUSE&filter_column%5Bpullout.status%5D%5Btype%5D=%3D&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSWAREHOUSE\\\" and `pullout`.channel_id in (4)\"}', '2021-12-10 10:05:45', NULL),
(39, 15, '540e359103515bdd08e8a1b147ac1051', 'smallbox', 'area4', 0, NULL, '{\"name\":\"Pending STR\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSRMA&filter_column%5Bpullout.status%5D%5Btype%5D=like&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSRMA\\\" and `pullout`.channel_id IN (4)\"}', '2021-12-10 10:05:46', NULL),
(40, 16, 'f3dfce831097e944d60402f5d572eb8c', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Received DR\",\"icon\":\"ion-cube\",\"color\":\"bg-green\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/deliveries?filter_column%5Bebs_pull.status%5D%5Btype%5D=%3D&filter_column%5Bebs_pull.status%5D%5Bvalue%5D=received\",\"sql\":\"select COUNT(distinct dr_number) from ebs_pull where status=\'RECEIVED\'\"}', '2021-12-10 11:01:29', NULL),
(41, 16, 'd70705825e556505e99c71e488283c30', 'smallbox', 'area2', 0, NULL, '{\"name\":\"Received STS\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/sts_history?q=RECEIVED\",\"sql\":\"SELECT COUNT(DISTINCT pos_pull.st_document_number) FROM `pos_pull` WHERE `pos_pull`.status=\\\"RECEIVED\\\"\"}', '2021-12-10 11:01:30', NULL),
(42, 16, '2c3940934b99543ed4312dda80203cba', 'smallbox', 'area3', 0, NULL, '{\"name\":\"Received STW\",\"icon\":\"ion-cube\",\"color\":\"bg-green\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSWAREHOUSE&filter_column%5Bpullout.status%5D%5Btype%5D=%3D&filter_column%5Bpullout.status%5D%5Bvalue%5D=RECEIVED&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"RECEIVED\\\" and `pullout`.wh_to=\\\"DIGITSWAREHOUSE\\\"\"}', '2021-12-10 11:01:31', NULL),
(43, 16, 'f13970b65588e977b2bf549e2a76ae4b', 'smallbox', 'area4', 0, NULL, '{\"name\":\"Received STR\",\"icon\":\"ion-cube\",\"color\":\"bg-aqua\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSRMA&filter_column%5Bpullout.status%5D%5Btype%5D=like&filter_column%5Bpullout.status%5D%5Bvalue%5D=RECEIVED&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"RECEIVED\\\" and `pullout`.wh_to=\\\"DIGITSRMA\\\"\"}', '2021-12-10 11:01:32', NULL),
(44, 16, '65317babecc1fca0c389e08fc7bf0fd9', 'chartarea', NULL, 0, 'Untitled', NULL, '2021-12-10 11:01:43', NULL),
(45, 16, '520984f81f79d53088f5d2826f1697c2', 'chartline', 'area5', 0, NULL, '{\"name\":\"Received DR Counter\",\"sql\":\"select COUNT(distinct dr_number) as value,date_format(received_at,\'%Y-%m-%d\') as label from ebs_pull where status=\'RECEIVED\' group by label order by label asc\",\"area_name\":\"Received DR\",\"goals\":\"0\"}', '2021-12-10 11:01:46', NULL),
(46, 17, '9bfa3ba851ffe7bb2a3189bf017540f4', 'smallbox', 'area1', 0, NULL, '{\"name\":\"Pending STW\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSWAREHOUSE&filter_column%5Bpullout.status%5D%5Btype%5D=%3D&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSWAREHOUSE\\\" and `pullout`.stores_id IN [admin_requestor_store]\"}', '2022-03-29 06:21:21', NULL),
(47, 17, 'cb04d4e249807da8518d64323b06464f', 'smallbox', 'area2', 0, NULL, '{\"name\":\"Pending STR\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSRMA&filter_column%5Bpullout.status%5D%5Btype%5D=like&filter_column%5Bpullout.status%5D%5Bvalue%5D=PENDING&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"PENDING\\\" and `pullout`.wh_to=\\\"DIGITSRMA\\\" and `pullout`.stores_id IN [admin_requestor_store]\"}', '2022-03-29 06:21:22', NULL),
(48, 17, 'b3ae7a5a67c3d74865dc8ca00aa8ef97', 'smallbox', 'area3', 0, NULL, '{\"name\":\"Received STW\",\"icon\":\"ion-cube\",\"color\":\"bg-green\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSWAREHOUSE&filter_column%5Bpullout.status%5D%5Btype%5D=like&filter_column%5Bpullout.status%5D%5Bvalue%5D=RECEIVED&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"RECEIVED\\\" and `pullout`.wh_to=\\\"DIGITSWAREHOUSE\\\" and `pullout`.stores_id IN [admin_requestor_store]\"}', '2022-03-29 06:21:25', NULL),
(49, 17, '5491acc059a7fb5fc2cc87df819c541a', 'smallbox', 'area4', 0, NULL, '{\"name\":\"Received STR\",\"icon\":\"ion-cube\",\"color\":\"bg-green\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/pullout_history?filter_column%5Bpullout.wh_to%5D%5Btype%5D=%3D&filter_column%5Bpullout.wh_to%5D%5Bvalue%5D=DIGITSRMA&filter_column%5Bpullout.status%5D%5Btype%5D=like&filter_column%5Bpullout.status%5D%5Bvalue%5D=RECEIVED&\",\"sql\":\"SELECT COUNT(DISTINCT pullout.st_document_number) FROM `pullout` WHERE `pullout`.status=\\\"RECEIVED\\\" and `pullout`.wh_to=\\\"DIGITSRMA\\\" and `pullout`.stores_id IN [admin_requestor_store]\"}', '2022-03-29 06:21:27', NULL),
(50, 5, '5e810da5f97ea2db111b1c230a19e03f', 'smallbox', 'area3', 0, NULL, '{\"name\":\"Pending Trip Ticket\",\"icon\":\"ion-cube\",\"color\":\"bg-yellow\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/trip_tickets\",\"sql\":\"SELECT COUNT(DISTINCT (trip_number)) FROM `trip_tickets` WHERE trip_ticket_statuses_id in (5,6)\"}', '2022-05-05 10:06:13', NULL),
(51, 5, 'a83bb12f4ad1227d6ff963b35eef8035', 'smallbox', 'area4', 0, NULL, '{\"name\":\"Backload Trip Ticket\",\"icon\":\"ion-cubes\",\"color\":\"bg-red\",\"link\":\"https:\\/\\/dms.digitstrading.ph\\/public\\/admin\\/trip_tickets\",\"sql\":\"SELECT COUNT(DISTINCT (trip_number)) FROM `trip_tickets` WHERE trip_ticket_statuses_id in (1,2)\"}', '2022-05-05 10:06:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cms_users`
--

CREATE TABLE `cms_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cms_privileges` int(11) DEFAULT NULL,
  `stores_id` text COLLATE utf8mb4_unicode_ci,
  `channel_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_users`
--

INSERT INTO `cms_users` (`id`, `first_name`, `last_name`, `name`, `photo`, `email`, `password`, `id_cms_privileges`, `stores_id`, `channel_id`, `created_at`, `updated_at`, `status`) VALUES
(1, NULL, NULL, 'Mike Rodelas', NULL, 'mikerodelas@digits.ph', '$2y$10$sLjrhSj5ULWTtBXPCXvXC.koZ0oiZSEl5tddzhEaV7SBMFw/VSY1K', 1, NULL, NULL, '2019-08-22 08:23:00', '2022-11-11 06:00:26', 'ACTIVE'),
(2, NULL, NULL, 'DW TRINOMA', 'uploads/1/2020-12/mrs_avatar.png', 'dwtrinoma@digitalwalker.ph', '$2y$10$c/Lfy7CYt9BjEnEt8zqLA.xHTxh8bsz9pEz9tokjETNguJnt5vG6a', 2, '32', 1, '2020-12-18 07:23:52', '2022-01-05 02:36:57', 'ACTIVE'),
(4, NULL, NULL, 'Digits Warehouse', 'uploads/1/2020-12/mrs_avatar.png', 'warehouse@digits.ph', '$2y$10$PvoCeB5.Hn6CABZFrN2hoOi3o.dOaBplQ3HRKiIJgBjju9hi9tXNS', 6, '64', NULL, '2020-12-29 07:49:46', '2021-02-22 10:08:49', 'ACTIVE'),
(5, NULL, NULL, 'RMA Warehouse', 'uploads/1/2020-12/mrs_avatar.png', 'rma@digits.ph', '$2y$10$4Wy3GSIFQnB0u.d3lttiAOqN5xWznckP3JKktggfK.p1gk9rAt/Ne', 3, '65', NULL, '2020-12-29 08:06:38', '2021-12-27 01:32:54', 'ACTIVE'),
(6, NULL, NULL, 'BTB FORBESTOWN', 'uploads/1/2021-01/mrs_avatar.png', 'btbforbestown@beyondthebox.ph', '$2y$10$1FFzfTrIOsV.c9djozwwTesjV2CFCcvXrOQiIdPrmvo4eY51udAfm', 2, '14', 2, '2021-01-06 07:51:22', '2022-01-05 10:54:15', 'ACTIVE'),
(7, NULL, NULL, 'BTB PIAZZA', 'uploads/1/2021-01/mrs_avatar.png', 'btbpiazza@beyondthebox.ph', '$2y$10$en1UNKEoXwVblx4SGifnPurORpzyfFQslCHGqmCZ/IF/9ZkkqtkWa', 2, '19', 1, '2021-01-15 01:51:47', '2024-02-03 02:47:58', 'ACTIVE'),
(8, NULL, NULL, 'Digits Logistics', 'uploads/1/2021-02/mrs_avatar.png', 'logistics@digits.ph', '$2y$10$EdkMnriC4/18/HzK1mGPteO4HGMJzApfTEoKLsUNr9p1bSDwGGo4m', 4, NULL, NULL, '2021-02-02 06:04:02', NULL, 'ACTIVE'),
(9, NULL, NULL, 'DW CENTURY', 'uploads/1/2021-02/mrs_avatar.png', 'dwcentury@digitalwalker.ph', '$2y$10$HUU1K1a22TFLD.eOeGZ2eugctWBTsvjj3WtKkZR1WKLZ75y98cwWq', 2, '38', 1, '2021-02-03 07:29:51', '2022-11-11 06:11:48', 'ACTIVE'),
(10, NULL, NULL, 'Evelyn Letran RTL', 'uploads/1/2021-02/mrs_avatar.png', 'evelynletran@digitalwalker.ph', '$2y$10$YRLNNs1LUMNmqjLslrMZwuIhxEJTG7fCPRei7.m/Y3pGtACT0CJEq', 5, NULL, 1, '2021-02-05 02:18:37', '2022-08-08 07:29:47', 'ACTIVE'),
(11, NULL, NULL, 'Howard Paw', 'uploads/1/2021-02/mrs_avatar.png', 'howardpaw@beyondthebox.ph', '$2y$10$ekkzuFfkCtELaT1qC0GsS.OHsM4qFPJ7ipRaWq0nTfehz3.eEeGzm', 5, '196,233,232,199,197,209,212,215,213,214,200,216,217,222,218,221,226,224,219,225,223,220,211', 6, '2021-02-05 07:41:54', '2023-10-20 08:00:22', 'ACTIVE'),
(12, NULL, NULL, 'BEYOND THE BOX LAZADA FBV', 'uploads/1/2021-02/mrs_avatar.png', 'btblazadafbv@online.com', '$2y$10$/2HoN65YTchivX.7Gn83..daONCoVxbiC9HS9/CdhNT0EnhkKt27C', 8, '102', 4, '2021-02-26 09:15:33', '2022-01-05 08:22:03', 'ACTIVE'),
(13, NULL, NULL, 'Precious Tan', 'uploads/1/2021-02/mrs_avatar.png', 'precioustan@digits.ph', '$2y$10$GI5KrKajFGkafB0an7UkReh31/8vMpe4ubv4agk0tKj.gxNTp2EMe', 5, NULL, 4, '2021-02-26 09:16:11', '2021-02-26 09:19:13', 'ACTIVE'),
(14, NULL, NULL, 'DW ONE BONIFACIO', 'uploads/1/2021-03/mrs_avatar.png', 'onebonifacio@digitalwalker.ph', '$2y$10$lscXe0ZNUzwk9HBUf4zPyuIVD26LM6l2McBuakcP.8mYuU540Aw.u', 2, '30', 1, '2021-03-10 03:34:33', '2022-01-05 02:38:34', 'ACTIVE'),
(15, NULL, NULL, 'DIGITAL WALKER LAZADA FBV', 'uploads/1/2021-03/mrs_avatar.png', 'digitalwalkerlazadafbv@online.com', '$2y$10$kqx/yeVUNiEwcZHcGzbgWOrkuJhfwJN6xBFdOd2IxzitfHgatRjcO', 8, '68', 4, '2021-03-11 03:13:15', '2022-01-05 08:22:03', 'ACTIVE'),
(16, NULL, NULL, 'FITBIT LAZADA FBD', 'uploads/1/2021-03/mrs_avatar.png', 'fitbitlazadafbd@online.com', '$2y$10$8pnry5R876PkdCC9M9UAp.H3m7bMPhYeGRxca.U5cmIK6x762SQPi', 10, '79', 4, '2021-03-11 03:39:00', '2022-01-04 03:40:04', 'ACTIVE'),
(17, NULL, NULL, 'DIGITAL WALKER LAZADA FBD', 'uploads/1/2021-03/mrs_avatar.png', 'digitalwalkerlazadafbd@online.com', '$2y$10$mpAOkWAkWM1PCtsuCfr2h.UDHXGlZo1zUJNJJcc1CrlkpNzDpMtxW', 10, '119', 4, '2021-03-12 09:57:28', '2022-01-05 07:24:03', 'ACTIVE'),
(18, NULL, NULL, 'ABENSON ASCOTT BGC CON', 'uploads/1/2021-03/mrs_avatar.png', 'ascotbgc@abenson.com', '$2y$10$tDyTBeUS2gFKdS0xOPwIYuVO26am.6RfH1aWKF324x2tCGgdn3hGW', 7, '115', 3, '2021-03-15 06:31:02', '2021-03-16 05:51:23', 'ACTIVE'),
(19, NULL, NULL, 'DW SANTOLAN SALE', 'uploads/1/2021-03/mrs_avatar.png', 'santolansale@digitalwalker.ph', '$2y$10$daCkAJrPaE0vgtVY8OK30.rXLga1SgUTRh06pJ0zJvNU3Sm.vgCPm', 7, '114', 5, '2021-03-15 06:32:22', '2021-03-16 07:10:48', 'ACTIVE'),
(20, NULL, NULL, 'Consignment Sales', 'uploads/1/2021-03/mrs_avatar.png', 'consignmentsales@digits.ph', '$2y$10$xfVVBfPAnqtA6emPyodiAunBcqh6gt5QCtRHxvMkwnYgJCaJ66we.', 23, '196,233,297,298,296,232,199,197,209,212,215,213,214,200,216,217,222,218,221,226,224,219,225,223,220,211', 6, '2021-03-15 06:34:15', '2024-01-23 06:14:55', 'ACTIVE'),
(21, NULL, NULL, 'BEYOND THE BOX LAZADA FBD', 'uploads/1/2021-03/mrs_avatar.png', 'btblazadafbd@online.com', '$2y$10$dRUf31xz7jICR9TwsXkFOe5GonOPOp/EIl7N0E..WX0FmbDwks.wu', 10, '111', 4, '2021-03-17 10:13:03', '2022-01-04 02:48:00', 'ACTIVE'),
(22, NULL, NULL, 'Online Warehouse', 'uploads/1/2021-03/mrs_avatar.png', 'onlwarehouse@digits.ph', '$2y$10$c3Gin0zpkDy1LwHsTnAMZOrD0tzLKhNrBFDdWvTjQwiDEPTG6KQpK', 11, '123,124,112,111,102,121,122,120,119,68,72,79,69,82,107,105,110,134,135,80,70,83,108,71', 4, '2021-03-31 00:28:44', '2021-10-25 06:35:34', 'ACTIVE'),
(23, NULL, NULL, 'Jean Hebres', NULL, 'jeanhebres@beyondthebox.ph', '$2y$10$M1YHtnzBH301JE56UqgOeeJmZBWmkpqypj7jlLWL2z0Hs4VARa0Pa', 19, NULL, 1, '2021-06-01 06:02:56', '2023-03-15 10:46:48', 'INACTIVE'),
(24, NULL, NULL, 'Jean Hebres', NULL, 'jeanhebresapprover@beyondthebox.ph', '$2y$10$f4y2ZWuhrncWbAjr/MgJY.WkHCl9wCMQzFKeukAGfqN7GHSXXfnha', 5, NULL, 1, '2021-06-01 09:47:06', '2022-02-11 03:49:35', 'INACTIVE'),
(25, NULL, NULL, 'BTB ERMITA', NULL, 'btbermita@beyondthebox.ph', '$2y$10$SHFehIH1k/69fG4YTotw4uWzInozg4w6DFojCFOMtlkqlPs8WC3ia', 2, '21', 1, '2021-06-02 04:52:27', '2022-01-05 02:17:25', 'ACTIVE'),
(26, NULL, NULL, 'BTB CENTURY', 'uploads/mrs-avatar.png', 'btbcentury@beyondthebox.ph', '$2y$10$UyteWnrDrGwTQFKYpfyzc..uXdBsBcoyGKFc4s2t0HNHGLLkkAIuO', 2, '9', 1, '2021-06-06 16:34:00', '2022-01-05 02:19:03', 'ACTIVE'),
(27, NULL, NULL, 'BTB GALLERIA', 'uploads/mrs-avatar.png', 'btbgalleria@beyondthebox.ph', '$2y$10$WmmnyP5VAFX8CXE0YsAM2utZ1iYtOwd0U71CrbnKD1Q3Jntwzc422', 2, '22', 1, '2021-06-06 16:34:46', '2022-01-05 02:57:02', 'ACTIVE'),
(28, NULL, NULL, 'BTB BAGUIO', 'uploads/mrs-avatar.png', 'btbbaguio@beyondthebox.ph', '$2y$10$UlXdXUgP/gy4NrTPNquxKuqG1yofxjzjj56gcuZfpKLkQi05PVfu.', 2, '23', 1, '2021-06-06 16:35:25', '2022-01-05 10:53:57', 'ACTIVE'),
(29, NULL, NULL, 'BTB SOUTHWOODS', NULL, 'btbsouthwoods@beyondthebox.ph', '$2y$10$j9kZuUWqCvnUlW..EjCvkujKblGT/UJxYziDngZfJaVziXEzO5Fmu', 2, '17', 1, '2021-06-06 16:38:52', '2022-12-12 10:18:50', 'ACTIVE'),
(30, NULL, NULL, 'BTB UPTOWN', 'uploads/mrs-avatar.png', 'btbuptown@beyondthebox.ph', '$2y$10$MKJn8BTJTskh5JmqLrKMy.YkXAG9Gg0LMpEE/mfKIJvYnG23k6Hne', 2, '18', 1, '2021-06-06 16:39:33', '2022-01-05 02:19:42', 'ACTIVE'),
(31, NULL, NULL, 'BTB VERANZA', 'uploads/mrs-avatar.png', 'btbveranza@beyondthebox.ph', '$2y$10$Fplz5ZzPp1an1Y3CzK6IXOziellu2lHpngzJG2Fxb8kiw0dAZgquW', 2, '12', 1, '2021-06-06 16:40:09', '2022-01-04 00:22:23', 'ACTIVE'),
(32, NULL, NULL, 'BTB VMALL', 'uploads/mrs-avatar.png', 'btbvmall@beyondthebox.ph', '$2y$10$qrdUWqQmdU48ZYOTDd29X.S8LzSCwuKkst6/h6ZsZBXuM6h7pxQiS', 2, '11', 1, '2021-06-06 16:40:49', '2022-01-05 02:36:18', 'ACTIVE'),
(33, NULL, NULL, 'BTB CITY OF DREAMS', NULL, 'btbcod@beyondthebox.ph', '$2y$10$RtA3RInUV1QlHIt5v1SS.eNgKUHWXk.EerA/THxSbGYVYk31KZ/GO', 2, '10', 1, '2021-06-06 16:41:27', '2023-02-04 14:12:23', 'ACTIVE'),
(34, NULL, NULL, 'BTB FAIRVIEW', 'uploads/mrs-avatar.png', 'btbfairview@beyondthebox.ph', '$2y$10$zTzcf8.Lm/isFpBcuqZ.teeBYa0OYmXqYW3kG.jxh8TSu.sMejOTq', 2, '7', 2, '2021-06-06 16:43:41', '2022-01-05 10:54:15', 'ACTIVE'),
(35, NULL, NULL, 'BTB CDO', 'uploads/mrs-avatar.png', 'btbcdo@beyondthebox.ph', '$2y$10$9JSJNaT.WwdOoSFYUEAmDuGLWf1xJgVuKtWnZp9bRBZ6u3b/HIat6', 2, '13', 2, '2021-06-06 16:44:11', '2022-01-05 10:54:15', 'ACTIVE'),
(36, NULL, NULL, 'BTB LUCKY CHINATOWN', 'uploads/mrs-avatar.png', 'btblucky@beyondthebox.ph', '$2y$10$PEcilFmhGkAXAKrcppp27uKs4TjgT5.yh58BH/dfiX0kW9NJDbI6K', 2, '15', 2, '2021-06-06 16:45:01', '2022-01-05 10:54:15', 'ACTIVE'),
(37, NULL, NULL, 'BTB MARKET MARKET', 'uploads/mrs-avatar.png', 'btbmarket@beyondthebox.ph', '$2y$10$C6S9/wy0OgPXhhaG1LQhfu.HOiVGqv0dF8G0xISMswaynVPuTyIhC', 2, '8', 2, '2021-06-06 16:45:30', '2022-01-05 10:54:15', 'ACTIVE'),
(38, NULL, NULL, 'BTB NEWPORT', 'uploads/38/2022-12/nwr_350x68_0_190x39.png', 'btbrwm@beyondthebox.ph', '$2y$10$ophG3/ea0U2nI88tmdWq4eFeovU7QyS1PeHHHcaA2bnNYKaZeJUPS', 2, '16', 1, '2021-06-06 16:46:23', '2022-12-12 11:53:22', 'ACTIVE'),
(39, NULL, NULL, 'DW ABREEZA', 'uploads/mrs-avatar.png', 'dwabreeza@digitalwalker.ph', '$2y$10$/wMwHyBcmwJcJEkVIEdA0OTdmyktNo2N5ydJh6/ygkp/k1PRxBaR.', 2, '25', 1, '2021-06-06 18:01:05', '2022-01-03 09:00:12', 'ACTIVE'),
(40, NULL, NULL, 'DW BAY AREA', 'uploads/40/2023-01/wc_cms_2019_10_30_12_58_07853.png', 'thebayarea@digitalwalker.ph', '$2y$10$DeOo31ziwx2.kUH/IOeHk.Q3Vo859SHfOQ6M/XP66/GZLxyaPNVa.', 2, '26', 1, '2021-06-06 18:01:05', '2023-01-01 10:05:30', 'ACTIVE'),
(41, NULL, NULL, 'DW AYALA CEBU', NULL, 'ayalacebu@digitalwalker.ph', '$2y$10$Yy7qA9M4MT/oRjo95LSgIOEw.k25DGzjgAvrXELdrwOPBgMgmNC7e', 2, '27', 1, '2021-06-06 18:01:05', '2022-01-05 05:03:09', 'ACTIVE'),
(42, NULL, NULL, 'DW GLORIETTA 2', 'uploads/mrs-avatar.png', 'dwglorietta@digitalwalker.ph', '$2y$10$qlWkVAuzWU7lk4XJiCDeoewJWVt.1QKaQ6/.6Qwo0zFs7rhpgULoW', 2, '28', 1, '2021-06-06 18:01:05', '2022-01-05 02:35:41', 'ACTIVE'),
(43, NULL, NULL, 'DW GREENBELT 5', NULL, 'dwgreenbelt5@digitalwalker.ph', '$2y$10$y3wMRMb/J5A.0Z91O3O9quy4t61ZhSFk.pNYrJMRCdDjsROl4tgou', 2, '260', 1, '2021-06-06 18:01:05', '2023-05-26 10:12:17', 'ACTIVE'),
(44, NULL, NULL, 'DW THE 30TH', NULL, 'dwayalathe30th@digitalwalker.ph', '$2y$10$OYB/aJdVy0F2ujUCTPhg0uFCBjWCGMl46naAmMAJdSeA/G1IMnyV6', 2, '31', 1, '2021-06-06 18:01:05', '2022-01-05 10:53:57', 'ACTIVE'),
(45, NULL, NULL, 'DW UPTC', NULL, 'uptowncenter@digitalwalker.ph', '$2y$10$uJ6yzNo0AqpJpVwYcogEouWHcw9Fq9h21RIcA7apr/TJ3stIpE3qO', 2, '33', 2, '2021-06-06 18:01:05', '2022-01-05 10:54:15', 'ACTIVE'),
(46, NULL, NULL, 'DW VERTIS NORTH', NULL, 'vertis@digitalwalker.ph', '$2y$10$8m971fm53HWZ2RZaNWlq0uEFONJThMW23.4.fzEdQK0pr4yrgBMR.', 2, '34', 2, '2021-06-06 18:01:06', '2022-03-17 06:33:57', 'ACTIVE'),
(47, NULL, NULL, 'DW ESTANCIA', 'uploads/mrs-avatar.png', 'estancia@digitalwalker.ph', '$2y$10$pffCq4L/eUKeC2461zLL1eciUfWRWwBQFxNkL7CRiLk6wZO3GHLSG', 2, '35', 2, '2021-06-06 18:01:06', '2022-01-05 10:54:15', 'ACTIVE'),
(48, NULL, NULL, 'DW ESTANCIA EXPANSION', NULL, 'dwestanciaexpansion@digitalwalker.ph', '$2y$10$HjU.4o/s8eDAfVKDfRI16eSZRtm3o2Rs0wX66fCC8rA2pL8L4ZYKS', 2, '36', 2, '2021-06-06 18:01:06', '2022-08-23 05:15:43', 'ACTIVE'),
(49, NULL, NULL, 'DW CDO', NULL, 'dwcagayan@digitalwalker.ph', '$2y$10$Jnxa0ho9IzCaGSwN0FwmgOfsUndOznLg/X/HIlaNyEMMgBhVhnsQO', 2, '37', 1, '2021-06-06 18:01:06', '2022-01-04 09:29:03', 'ACTIVE'),
(50, NULL, NULL, 'DW VMALL', NULL, 'dwvmall@digitalwalker.ph', '$2y$10$BDVPGdL7YqRj9/09BgMkk.L16T/PHzZn9xH0Q4lz2AN0SIh1ZrcO.', 2, '40', 1, '2021-06-06 18:01:06', '2022-01-05 02:36:18', 'ACTIVE'),
(51, NULL, NULL, 'DW LAZADA CLL', NULL, 'lazadacll@digitalwalker.ph', '$2y$10$GoMN8Z5Caw4qJMtz2NFTSu7t8Pap3j3EJkbqiUosm1IhJQPo81wuq', 2, '88', 4, '2021-06-06 18:01:06', '2022-01-03 07:27:47', 'INACTIVE'),
(52, NULL, NULL, 'DW LAZADA FBD', NULL, 'lazadafbd@digitalwalker.ph', '$2y$10$Mq2IM6w9yF9Hhxy82p47wuhRQH/FYtPj/AvujArhE74ldUIx/FtjG', 10, '119', 4, '2021-06-06 18:01:06', '2022-01-03 07:27:47', 'INACTIVE'),
(53, NULL, NULL, 'DW LAZADA FBV', NULL, 'lazadafbv@digitalwalker.ph', '$2y$10$Rm.8DRpNUbQfPNHZS.CPcetbSu3dK2d2GstiDgDlm3eOuyA9Fsdi2', 8, '68', 4, '2021-06-06 18:01:06', '2022-01-05 10:53:48', 'ACTIVE'),
(54, NULL, NULL, 'DW EASTWOOD', 'uploads/mrs-avatar.png', 'dweastwood@digitalwalker.ph', '$2y$10$z7lZHXorA6jMdFHYplUg4.F3MCaip.ydLAfuc56031miIdCavxx5y', 2, '41', 1, '2021-06-06 18:01:06', '2022-01-05 02:39:09', 'ACTIVE'),
(55, NULL, NULL, 'DW FESTIVE WALK', NULL, 'dwfestivewalk@digitalwalker.ph', '$2y$10$A6E3kelzGEXhH2DOp2Rrd.xDWBNh4ONAZZAx.fMHuUQFec2NidVfi', 2, '42', 1, '2021-06-06 18:01:06', '2022-07-06 11:41:00', 'ACTIVE'),
(56, NULL, NULL, 'DW OKADA', 'uploads/mrs-avatar.png', 'okada@digitalwalker.ph', '$2y$10$cL3U7JHmIW9iVusTn8XtdOQmkkbc3/HUlL7IGHuHnaiE2KcArRoRi', 2, '43', 1, '2021-06-06 18:01:06', '2022-01-05 02:18:34', 'ACTIVE'),
(57, NULL, NULL, 'DW ERMITA', 'uploads/mrs-avatar.png', 'dwermita@digitalwalker.ph', '$2y$10$VQNjZ1SerXm7p3tRAJ.bZegR.xMeM2PtwB800YGm9MJ4wdy3KM5KC', 2, '44', 1, '2021-06-06 18:01:06', '2022-01-05 02:17:25', 'ACTIVE'),
(58, NULL, NULL, 'DW MAGNOLIA', 'uploads/mrs-avatar.png', 'dwmagnolia@digitalwalker.ph', '$2y$10$208xvVU5pE.8IQHlOgaG2ONZdUouA0pTS6.RWI.guNhw2MyIQSrxe', 2, '45', 2, '2021-06-06 18:01:07', '2022-01-05 10:54:15', 'ACTIVE'),
(59, NULL, NULL, 'DW PIONEER', NULL, 'dwpioneer@digitalwalker.ph', '$2y$10$607asX44zjdXn7iaEAwrcunMksl5OCtCYoOkA0LLxA5/4FMk3AeSu', 2, '46', 1, '2021-06-06 18:01:07', '2022-01-18 07:50:09', 'ACTIVE'),
(60, NULL, NULL, 'DW POWERPLANT', 'uploads/mrs-avatar.png', 'dwrockwell@digitalwalker.ph', '$2y$10$OljezCF1tnOGp1iZ2OjHQe50rcBCQtGjPylxa7ckrKcDEOS0JfbY6', 2, '47', 1, '2021-06-06 18:01:07', '2022-01-05 10:53:57', 'ACTIVE'),
(61, NULL, NULL, 'DW SHANGRILA', 'uploads/mrs-avatar.png', 'dwshangrila@digitalwalker.ph', '$2y$10$4ZZZzOImE2bYuICtrM2HnOhkVsWbEUBn6lHw8R3B7cr5to94zmJrq', 2, '48', 1, '2021-06-06 18:01:07', '2022-01-05 10:53:57', 'ACTIVE'),
(62, NULL, NULL, 'DW SHOPEE CLL', NULL, 'shopeecll@online.com', '$2y$10$UrgIeZeV4a8f2D5lOzyIPe1cpDfO.4RN4t5AmkLnZT7Hu39Mk0FGm', 2, '91', 4, '2021-06-06 18:01:07', '2022-01-03 07:27:47', 'INACTIVE'),
(63, NULL, NULL, 'DW AURA', 'uploads/mrs-avatar.png', 'dwaura@digitalwalker.ph', '$2y$10$5aAVIRfN1W8xsZaooEGAEOH6/CpLIUoaUYXHmeZGTzt34HKf8rEfW', 2, '49', 1, '2021-06-06 18:01:07', '2022-01-05 02:38:49', 'ACTIVE'),
(64, NULL, NULL, 'DW BAGUIO', 'uploads/mrs-avatar.png', 'baguio@digitalwalker.ph', '$2y$10$oAdT4iLXMsGAdiXxgXhLEOIPRlYO.CwD1n.UUf3ieXzVUI530LuuC', 2, '50', 1, '2021-06-06 18:01:07', '2022-01-05 10:53:57', 'ACTIVE'),
(65, NULL, NULL, 'DW SM CEBU', 'uploads/65/2022-02/viber_image_2020_10_11_09_58_43.jpg', 'smcebu@digitalwalker.ph', '$2y$10$OGHV/8XcOFHSjA4fk4dLiub4qfp7bsUcQ1WTaXRHIuTIXtIkR.4Ey', 2, '51', 1, '2021-06-06 18:01:07', '2022-02-27 02:54:59', 'ACTIVE'),
(66, NULL, NULL, 'DW MALL OF ASIA', 'uploads/mrs-avatar.png', 'dwmoa@digitalwalker.ph', '$2y$10$.cS8SG2Q90LmyvV4wPJfF.ubpjg72Xm8JK4AUMa0ThrDnozBOgcf6', 2, '52', 1, '2021-06-06 18:01:07', '2022-01-05 02:17:39', 'ACTIVE'),
(67, NULL, NULL, 'DW NORTH EDSA', 'uploads/mrs-avatar.png', 'dwsmne@digitalwalker.ph', '$2y$10$OybQtAyM0q/4M86rddALsO0sPGZ5hoUbww.HhhcC.3Y5pD0mePrNq', 2, '53', 1, '2021-06-06 18:01:07', '2022-01-05 02:37:25', 'ACTIVE'),
(68, NULL, NULL, 'DW SOUTHMALL', 'uploads/mrs-avatar.png', 'dwsouthmall@digitalwalker.ph', '$2y$10$a0Ss8n0eEZPivvyQu/VT6ePQNcVNyWK9cbkGpnbsraHGeZw9z/7e6', 2, '54', 1, '2021-06-06 18:01:07', '2022-01-05 10:53:57', 'ACTIVE'),
(69, NULL, NULL, 'DW CONRAD', 'uploads/mrs-avatar.png', 'dwconrad@digitalwalker.ph', '$2y$10$LOn4vJPGPePT7YtgXdglgOWvhYoDUoLd.6Qg2sQnrLfAU61ui840e', 2, '55', 1, '2021-06-06 18:01:07', '2022-01-05 02:18:22', 'ACTIVE'),
(70, NULL, NULL, 'DW THE PODIUM', NULL, 'dwpodium@digitalwalker.ph', '$2y$10$UsZ16tsnNey89U4PMJjQKeq8DKKt3QU6ET4XlvSnD97C632w7Lme.', 2, '56', 1, '2021-06-06 18:01:08', '2022-01-05 02:36:35', 'ACTIVE'),
(71, NULL, NULL, 'DW VISTA MALL TAGUIG', 'uploads/mrs-avatar.png', 'vistamalltaguig@digitalwalker.ph', '$2y$10$HueYz7g/4L96bod4lsdYI.qK3oZSFuNMtRI6I/fUtxT995tNBSXwm', 2, '67', 1, '2021-06-06 18:01:08', '2022-01-05 02:38:02', 'ACTIVE'),
(72, NULL, NULL, 'Christoff Sy', 'uploads/mrs-avatar.png', 'christoffsy@digits.ph', '$2y$10$4EGRjeI2DHcVM3N2.Jc3Ye1J3u5yotHObQl3wVTIWA/Y591J0b.8C', 1, NULL, NULL, '2021-06-06 22:16:17', NULL, 'ACTIVE'),
(73, NULL, NULL, 'Fhilip Acosta', NULL, 'fhilipacosta@digits.ph', '$2y$10$rLj54JmQ7Hf5dUS4EyjswOJcAhXNoa8n.Ra1mbqXlQoqqbh4JLPgW', 1, NULL, NULL, '2021-06-06 22:16:50', '2021-10-12 10:16:22', 'ACTIVE'),
(74, NULL, NULL, 'DW FAIRVIEW', 'uploads/mrs-avatar.png', 'dwsmfairview@digitalwalker.ph', '$2y$10$ps28Yx4cd8oto/C31IcYdOpnC0AwOTTRcxt4bFiRFRLOff8690zlW', 2, '118', 2, '2021-06-07 02:46:00', '2022-01-05 10:54:15', 'ACTIVE'),
(75, NULL, NULL, 'Rochelle Monuz', NULL, 'rochellemunoz@digits.ph', '$2y$10$swhVhRg2R8joQgFAgZlK7u2BQ1FkES.YvIojxww1n1iWjQXjK1TDm', 14, NULL, NULL, '2021-06-07 02:48:59', '2022-01-25 00:38:27', 'ACTIVE'),
(76, NULL, NULL, 'SC BONIFACIO HIGH STREET', 'uploads/mrs-avatar.png', 'service@beyondthebox.ph', '$2y$10$giRpOrlpu9x5svsGdhoOFefZ1P7eNSeYJ7p.8WC.Gaux/23.BnUla', 2, '62', 1, '2021-06-09 10:27:53', '2022-01-05 02:38:34', 'ACTIVE'),
(77, NULL, NULL, 'SC GREENHILL VMALL', NULL, 'btbservicevmall@beyondthebox.com.ph', '$2y$10$kxCuf/uLbqQI.TqyGfhOyeC3bIrrxnqgs1tq/busDz2/kkuXtsafm', 2, '63', 1, '2021-06-09 10:28:26', '2022-01-05 02:36:18', 'ACTIVE'),
(78, NULL, NULL, 'BPG USER', 'uploads/mrs-avatar.png', 'bpg@digits.ph', '$2y$10$GjbRY73KKN8owZd0I2Q4z.KTQwSoBzLAAuisTplXxETl8kOrF5cwm', 2, '22', 1, '2021-06-10 05:44:36', NULL, 'ACTIVE'),
(79, NULL, NULL, 'DW MACHINES ROCKWELL', 'uploads/79/2021-12/dd.jfif', 'dwmachinesrockwell@digitalwalker.ph', '$2y$10$4..abZ3kPkjMQlK0htYd9uKMhpkNmhjDeStAhutvZaIynQ0PJsXT.', 2, '116', 1, '2021-06-10 10:13:52', '2022-01-05 10:53:57', 'ACTIVE'),
(80, NULL, NULL, 'DW MACHINES TRINOMA', 'uploads/mrs-avatar.png', 'dwmachinestrinoma@digitalwalker.ph', '$2y$10$ShYdMVcgIQRO2Pikm5wYy.nj5hLAULBc6j/bEdkVJ9V9DkDL6jlKK', 2, '117', 1, '2021-06-10 10:14:49', '2022-01-05 02:36:57', 'ACTIVE'),
(81, NULL, NULL, 'John Doe', NULL, 'johndoe@digits.ph', '$2y$10$r/wqXloWfcZlihsOcWVJguuaTQdjU3U3Nve1QCtyRMRvWNduvXuXi', 5, '268', 2, '2021-06-20 05:12:47', '2024-01-02 00:56:11', 'ACTIVE'),
(82, NULL, NULL, 'Jane Doe', NULL, 'janedoe@digits.ph', '$2y$10$5O686ziF/GieGMJQqlyzg.TM5K.wFCDldUmxeJCZfJPltjpr/0A56', 10, '121', 4, '2021-06-20 05:14:19', '2023-09-13 02:19:46', 'ACTIVE'),
(83, NULL, NULL, 'DW SM MARILAO', 'uploads/mrs-avatar.png', 'dwsmmarilao@digitalwalker.ph', '$2y$10$BLcFkE9bh0Ixvzr.Frv0Yu4LkUHHqBpKXRefAn3ZoQ1pmT7OzxO5W', 2, '125', 1, '2021-07-07 09:04:44', '2023-03-28 14:00:59', 'INACTIVE'),
(84, NULL, NULL, 'OMG ROBINSONS ERMITA', NULL, 'ermita@omgstore.ph', '$2y$10$SzUgyIML9yVoSyu2Y1xDFOTQU555BMtrL.StP7dm96DEVvmhHSqXS', 2, '59', 1, '2021-07-09 06:18:20', '2022-11-03 02:29:50', 'ACTIVE'),
(85, NULL, NULL, 'Reycel Castro', 'uploads/85/2022-06/signature.JPG', 'reycelcastro@digits.ph', '$2y$10$wvHHmDhIR6JueqdMQ.YH5.1kcMOJSr6Iknjxo2o69sCN0pkihArJq', 26, NULL, NULL, '2021-08-13 07:49:08', '2022-06-24 00:47:46', 'ACTIVE'),
(86, NULL, NULL, 'DW UPTOWN MALL', 'uploads/mrs-avatar.png', 'dwuptown@digitalwalker.ph', '$2y$10$E8A/tWZFawddHADujuDbmu20pyYXIWBroWBS7OEK5fNijE33AN9Xq', 2, '126', 1, '2021-09-24 05:26:06', '2022-01-05 02:19:42', 'ACTIVE'),
(87, NULL, NULL, 'DW LIPA', 'uploads/mrs-avatar.png', 'dwlipa@digitalwalker.ph', '$2y$10$5Hh20ziC0Fs.5QeacYC5I.l6df6fz01wEAsktMzNKAfabMYcXlSlu', 2, '127', 1, '2021-09-24 05:26:54', '2022-01-04 02:56:02', 'ACTIVE'),
(88, NULL, NULL, 'DW SM STA MESA', 'uploads/mrs-avatar.png', 'dwsmstamesa@digitalwalker.ph', '$2y$10$sr.kfjqtRUu/DAMqdhIOGuWpTftjP3QsmOjcBStaEio/H6SCF4fXW', 2, '128', 1, '2021-09-28 03:25:54', '2022-01-05 10:53:57', 'ACTIVE'),
(89, NULL, NULL, 'DW SM GRAND CENTRAL', NULL, 'dwsmgrandcentral@digitalwalker.ph', '$2y$10$XIFWP.KM0VKDsrrfn85myuLHfhLgR2H0k.kFNZxox85JmZ3HsW2WS', 2, '129', 1, '2021-10-04 02:20:36', '2022-01-04 02:55:22', 'ACTIVE'),
(90, NULL, NULL, 'Marlon Binos', 'uploads/mrs-avatar.png', 'marlonbinos@digits.ph', '$2y$10$6zsfIIyx5rqJKbxN7T//UuYXI37oFWFa.hRBydfnSytQ2AKTX6Wua', 18, NULL, NULL, '2021-10-05 02:26:41', NULL, 'ACTIVE'),
(91, NULL, NULL, 'Jack Doe', NULL, 'jackdoe@digits.ph', '$2y$10$xxnEEBbgLXzzt5RE/wGx1.zjB3bq/rP05a.Yhepra3JYGNgZgPYMK', 4, NULL, NULL, '2021-10-12 10:03:53', '2024-01-18 05:22:17', 'ACTIVE'),
(92, NULL, NULL, 'DW HIGH STREET', NULL, 'highstreet@digitalwalker.ph', '$2y$10$w8FvI3TT91LNpvXnsGGkI.b8ZH3h9TVrqwABBiKkZ9bNgBx9RPjhK', 2, '261', 1, '2021-10-13 01:28:52', '2023-03-31 10:11:19', 'ACTIVE'),
(93, NULL, NULL, 'DW ROBINSONS GALLERIA', NULL, 'dwgalleria@digitalwalker.ph', '$2y$10$inB3K81jaiWMN5THp0TO.ONIIRbX/ZBZojGhc82zU1TUbmYITgO.e', 2, '131', 1, '2021-10-18 03:51:43', '2022-01-10 07:30:05', 'ACTIVE'),
(94, NULL, NULL, 'DW LA UNION', 'uploads/mrs-avatar.png', 'dwlaunion@digitalwalker.ph', '$2y$10$nil1lB5rcW7ChPwvmLMuquZzonGh3i5lfWqKO.aoCc0kavrooELwC', 2, '133', 1, '2021-10-19 02:26:23', '2022-01-05 10:53:57', 'ACTIVE'),
(95, NULL, NULL, 'BTB VISTAMALL NOMO', 'uploads/mrs-avatar.png', 'btbvistamallnomo@beyondthebox.ph', '$2y$10$f1IdsjEO45lk8xF4YO3nbO40mcCFG9dHfXLfOyey.RFzMLJuN33CO', 2, '132', 1, '2021-10-19 02:55:40', '2022-01-05 10:53:57', 'ACTIVE'),
(96, NULL, NULL, 'BEYOND THE BOX DIGITS FBD', NULL, 'beyondtheboxdigitsfbd@online.com', '$2y$10$U9tyB0tpTtOn.nuU0IIzV.AwO53aekFNV0SAevp6f9xRLtYeNRXO2', 10, '112', 4, '2021-10-19 21:59:39', '2022-01-04 08:23:10', 'ACTIVE'),
(97, NULL, NULL, 'DIGITAL WALKER DIGITS FBD', 'uploads/mrs-avatar.png', 'digitalwalkerdigitsfbd@online.com', '$2y$10$RHvGhXw2jGSlslCRNM7TC.fAFkwOVnwT/Sy2.Ip98ImflKQT6nUqG', 10, '120', 4, '2021-10-19 22:01:14', '2022-01-05 02:26:11', 'ACTIVE'),
(98, NULL, NULL, 'HOME OFFICE DIGITS FBD', NULL, 'homeofficedigitsfbd@online.com', '$2y$10$Y75gR./EDl08PDw3gtFBXeZj4WkecqlP9O/V4LfbBAtHdHEfd/HM6', 10, '101', 4, '2021-10-19 22:03:31', '2022-01-03 07:27:47', 'INACTIVE'),
(99, NULL, NULL, 'Online WSDM', NULL, 'onlinewsdm@digits.ph', '$2y$10$p74o5n32O8IPOWK/qd0H/et7rkpSs5TwsP5wUom80sDKrYnALjlm6', 17, '123,124,142,143,112,111,102,121,122,120,119,68,72,227,79,69,82,144,146,145,101,107,105,110,134,135,203,205,204,80,70,83,206,108,71', 4, '2021-10-19 22:13:34', '2022-07-22 00:57:20', 'ACTIVE'),
(100, NULL, NULL, 'UAG LAZADA FBD', 'uploads/mrs-avatar.png', 'uaglazadafbd@online.com', '$2y$10$/ZjO/Wpg/yqVtKHBx8dvgOHXgXXTKM7oED4o8xuaisT1ssOzRaNSi', 10, '108', 4, '2021-10-19 22:17:48', '2022-01-05 10:53:48', 'ACTIVE'),
(101, NULL, NULL, 'AFTERSHOKZ LAZADA FBD', 'uploads/mrs-avatar.png', 'aftershokzlazadafbd@online.com', '$2y$10$SRPZkLGSsKWT8XY29VyU8uaQoXUoJmKbJAMslrXlrvZ5rxkiYUDwS', 10, '123', 4, '2021-10-19 22:17:48', '2022-01-04 05:51:10', 'ACTIVE'),
(102, NULL, NULL, 'MARSHALL LAZADA FBD', 'uploads/mrs-avatar.png', 'marshalllazadafbd@online.com', '$2y$10$SNfansbtMwO/RQcY5wuzH.nV52bwQRvwifmrHhTPPQBpm5niNwJI6', 10, '107', 4, '2021-10-19 22:17:48', '2022-01-04 06:12:06', 'ACTIVE'),
(103, NULL, NULL, 'ONEPLUS LAZADA FBD', 'uploads/mrs-avatar.png', 'onepluslazadafbd@online.com', '$2y$10$hDpEL.nEtnoRKmiNrc8QO.xTFLbO4JGj4UGB6NqQym6fKYGY22/a2', 10, '80', 4, '2021-10-19 22:17:48', '2022-01-05 10:53:48', 'ACTIVE'),
(104, NULL, NULL, 'MOMAX LAZADA FBD', 'uploads/mrs-avatar.png', 'momaxlazadafbd@online.com', '$2y$10$EqQqX/.otFyVa3jlycqPSugIF4dcprvOtDwn6NqfnVj4/.VqvxTy6', 10, '134', 4, '2021-10-19 22:17:48', '2022-01-05 10:53:48', 'ACTIVE'),
(105, NULL, NULL, 'BEYOND THE BOX SHOPEE FBD', 'uploads/mrs-avatar.png', 'beyondtheboxshopeefbd@online.com', '$2y$10$ZbUej4/FgZB0K9arcR6N1OqzAVPnIZdr7qqDo9TN8qTeyn9jqLuV.', 10, '121', 4, '2021-10-19 22:17:49', '2022-01-04 03:01:28', 'ACTIVE'),
(106, NULL, NULL, 'DIGITAL WALKER SHOPEE FBD', 'uploads/mrs-avatar.png', 'digitalwalkershopeefbd@online.com', '$2y$10$xQjP6ivlhcKw3ACgIZ68NOhBBkrffpGww/LM5YyM4.Yf17BtFAmqC', 10, '72', 4, '2021-10-19 22:17:49', '2022-01-04 00:23:53', 'ACTIVE'),
(107, NULL, NULL, 'FITBIT SHOPEE FBD', 'uploads/mrs-avatar.png', 'fitbitshopeefbd@online.com', '$2y$10$RmWovgDJGWR5egzDAV70QO4BFVIaHJlSjIkVPhu8CT5JzPheQZ2fm', 10, '82', 4, '2021-10-19 22:17:49', '2022-01-05 07:34:01', 'ACTIVE'),
(108, NULL, NULL, 'MARSHALL SHOPEE FBD', 'uploads/mrs-avatar.png', 'marshallshopeefbd@online.com', '$2y$10$rwzG2NC9rNSNzbGp7dmYcOD3SA7heA1oxUj.M0D3frbxwBaHFAvuS', 10, '110', 4, '2021-10-19 22:17:49', '2022-01-04 06:29:16', 'ACTIVE'),
(109, NULL, NULL, 'ONEPLUS SHOPEE FBD', 'uploads/mrs-avatar.png', 'oneplusshopeefbd@online.com', '$2y$10$5gw8Zol4r0tugWpnOSqqwOWVHZdmFkflMfIRwxrqHHMD/6zeNKpbO', 10, '83', 4, '2021-10-19 22:17:49', '2022-01-04 05:16:30', 'ACTIVE'),
(110, NULL, NULL, 'LAZADA AFTERSHOKZ FBV', NULL, 'lazadaaftershokzfbv@online.com', '$2y$10$ZLnPOEVbmk/3Ab8DYrdQJeuPBEAaBkDiouky.10VyEP6Yo67LD9t.', 8, '124', 4, '2021-10-19 22:23:00', '2022-01-05 10:53:48', 'ACTIVE'),
(111, NULL, NULL, 'SHOPEE BTB FBV', NULL, 'shopeebtbfbv@online.com', '$2y$10$IV16ngK9Lsd..ImMwNwm/O1jXUJ/aKyx.3DIcohS39Ei4VXD6Ljt.', 8, '122', 4, '2021-10-19 22:23:00', '2022-01-05 10:53:48', 'ACTIVE'),
(112, NULL, NULL, 'LAZADA FITBIT FBV', NULL, 'lazadafitbitfbv@online.com', '$2y$10$M/rh1Tv9i/JkHFZgbEJno.i7BzWeljiowb8Vbx4XmfblXLQr66/5q', 8, '69', 4, '2021-10-19 22:23:01', '2022-01-05 10:53:48', 'ACTIVE'),
(113, NULL, NULL, 'LAZADA MARSHALL FBV', NULL, 'lazadamarshallfbv@online.com', '$2y$10$dSPvp52Ephvj3eKdBatl2OJ3sKms7EIBy016CwyuYtdfzl7pRj/8q', 8, '105', 4, '2021-10-19 22:23:01', '2022-01-05 04:37:20', 'ACTIVE'),
(114, NULL, NULL, 'LAZADA MOMAX FBV', NULL, 'lazadamomaxfbv@online.com', '$2y$10$tXZ4PPrAMRcPBmDbXiVxcOQH.mkvBclt7/S8lxx7niC7GsmeyhoFm', 8, '135', 4, '2021-10-19 22:23:01', '2022-01-05 10:53:48', 'ACTIVE'),
(115, NULL, NULL, 'LAZADA ONEPLUS FBV', NULL, 'lazadaoneplusfbv@online.com', '$2y$10$9YH3LjoW/YVEXoyA8UxLH.aRaDtx.mur2snObYyWYX1swYT2Oj0y.', 8, '70', 4, '2021-10-19 22:23:01', '2022-01-05 10:53:48', 'ACTIVE'),
(116, NULL, NULL, 'LAZADA UAG FBV', NULL, 'lazadauagfbv@online.com', '$2y$10$6LqV53hUzt/euIkv61yIhuUgtQrUa4Y.jBJ.MBi0IiM6Gc4SxfHQO', 8, '71', 4, '2021-10-19 22:23:01', '2022-01-05 09:03:30', 'ACTIVE'),
(117, NULL, NULL, 'MARICEL ROBLES', 'uploads/mrs-avatar.png', 'maricelrobles@digits.ph', '$2y$10$DA9EKGCUv8F6zyastnh9KuND5NMIkbVK2lcp6nYPKECeZXLupMj.O', 4, NULL, NULL, '2021-10-19 22:27:49', NULL, 'ACTIVE'),
(118, NULL, NULL, 'AMADO CORPUZ', NULL, 'amadocorpuz@digits.ph', '$2y$10$7hMU4RGzXIgCNQEHpu34ZOXvlhr5hW0PjfIBcQKD5MwOfb7QKvHbG', 25, NULL, NULL, '2021-10-19 22:28:18', '2022-04-26 02:08:40', 'ACTIVE'),
(119, NULL, NULL, 'ANGELA MANGULABNAN', 'uploads/mrs-avatar.png', 'angelamangulabnan@digits.ph', '$2y$10$Ki0HIRgiL/VvJ2iJ.oEv0.k2aOCAEdk3099cG35NputsH4M539lka', 4, NULL, NULL, '2021-10-19 22:30:04', '2021-11-04 09:14:09', 'INACTIVE'),
(120, NULL, NULL, 'SHERWIN REGALA', 'uploads/mrs-avatar.png', 'sherwinregala@digits.ph', '$2y$10$YGgYphBYmtATNcks5rTr1OnY.lqNy38IUH5tULG8g5zRUiCX3hIkK', 4, NULL, NULL, '2021-10-19 22:30:22', NULL, 'ACTIVE'),
(121, NULL, NULL, 'ECOM OPS', NULL, 'ecomops@digits.ph', '$2y$10$CJRcE2xaC0Koiy6V4t0Qje8EI6sY3ttxHXgHOKA8rrRlLyqmb8WJy', 16, NULL, 4, '2021-10-19 22:39:03', '2021-10-26 03:01:06', 'ACTIVE'),
(122, NULL, NULL, 'PATRICK OLAZO', NULL, 'patrickolazo@digits.ph', '$2y$10$wcVQ4fHGNha5akTe.Mxjve7MU/hgz.CFHdAyl29uapETslYGMmS/e', 4, NULL, NULL, '2021-11-05 06:04:13', '2021-11-05 06:04:32', 'ACTIVE'),
(123, NULL, NULL, 'DW SM MEGAMALL', NULL, 'dwsmmegamall@digitalwalker.ph', '$2y$10$b/.0ghB3wZSsiSCIOXbAXeZEOb193dZ.C3wmgdyy/U2pklS3g1vTe', 2, '137', 1, '2021-11-09 07:18:43', '2022-01-10 07:52:15', 'ACTIVE'),
(124, NULL, NULL, 'Evelyn Letran FRA', 'uploads/mrs-avatar.png', 'evelynletran@beyondthebox.ph', '$2y$10$S7m8SVxb4bHmPTuAz0WJk.gZdPnItIWWG/B6Ue/BnP8F4rwncj6kC', 5, NULL, 2, '2021-12-03 03:26:57', NULL, 'ACTIVE'),
(125, NULL, NULL, 'BLUESPACE HQ', 'uploads/mrs-avatar.png', 'hq@bluespace.com', '$2y$10$rntnnEc84ll9SdzzyxMQJe51HrBhTfM6OW8pG2yOxHmQylCtKEawy', 2, '139', 2, '2021-12-04 01:57:48', '2022-01-05 10:54:15', 'ACTIVE'),
(126, NULL, NULL, 'Kimpee Bautista', NULL, 'kimpeebautista@digitalwalker.ph', '$2y$10$0.LFsXKbATN.n0FtMYStCO2wcuT87rSfWUjvrceLrV.AHEUf.u.Q6', 5, NULL, 1, '2021-12-04 03:21:58', '2023-06-07 02:35:20', 'ACTIVE'),
(127, NULL, NULL, 'Reynalda Osares', NULL, 'reynaldaosares@digitalwalker.ph', '$2y$10$CTO/ftWEwqc.btjI5tGfm.rdygOQlS36eecUG2mhIHRbYc4NJDQiq', 5, NULL, NULL, '2021-12-04 03:22:29', '2023-10-20 08:50:09', 'INACTIVE'),
(128, NULL, NULL, 'JOHN TIMOTHY MARIANO', 'uploads/mrs-avatar.png', 'johntimothymariano@digits.ph', '$2y$10$Oe3NWYWOsc/BZHDTdqTWOe220XJkfwCUsVXOQHrDioXkty2w1A6.u', 19, NULL, NULL, '2021-12-04 03:23:12', NULL, 'ACTIVE'),
(129, NULL, NULL, 'Patrick Ray Samiano', NULL, 'patrickraysamiano@digits.ph', '$2y$10$bboYqrjz4MrGGYhU3hun8OLiVKXOO3xPOUud6kn3wu8n7vPWHTz12', 5, NULL, 1, '2021-12-07 06:04:12', '2023-06-15 03:02:40', 'ACTIVE'),
(130, NULL, NULL, 'JOANNA ONG', NULL, 'joannaong@digits.ph', '$2y$10$C9XhuuITM20dE.eRqXwlZ.tZkUgc0HRQ5kWrr81FVFFoyyEsXhVK6', 5, NULL, NULL, '2021-12-10 04:56:28', '2022-06-02 06:12:51', 'ACTIVE'),
(131, NULL, NULL, 'Ma. Lourdes Ngo', NULL, 'lourdesngo@digits.ph', '$2y$10$Kt1AxI6V55GenaT1QrTneeYPRiqDglPa/vQ..IuDWPcnGiAG9F0vq', 21, NULL, 4, '2021-12-10 04:57:30', '2021-12-10 11:20:48', 'ACTIVE'),
(132, NULL, NULL, 'Jonalyn Armenion', NULL, 'jonalynarmenion@digits.ph', '$2y$10$3tXBa.q0oV73o9AlM/AyHuPIWvB3UGZ32uFSbgCJ7TEDVdwUSUyP.', 21, NULL, 4, '2021-12-10 04:58:02', '2021-12-10 11:20:38', 'ACTIVE'),
(133, NULL, NULL, 'Leslie Anne Gutierrez', NULL, 'lesliegutierrez@digits.ph', '$2y$10$ZpoNJ3flBDk75fU1x3QV4eZhUE/HPvbGTWVD2ts09asgy6qslvggG', 21, NULL, 4, '2021-12-10 04:58:25', '2022-12-21 02:47:36', 'INACTIVE'),
(134, NULL, NULL, 'MERCH TEAM', 'uploads/mrs-avatar.png', 'merchandising@digits.ph', '$2y$10$qaEXJT3xGZDU1bOogBbHQeSBMThmRyb/BrJHRH3UJakAV/ZARszry', 22, NULL, NULL, '2021-12-10 11:07:25', NULL, 'ACTIVE'),
(135, NULL, NULL, 'Rafael Segue', 'uploads/mrs-avatar.png', 'rafaelsegue@digits.ph', '$2y$10$y..SSirPGo5zqYSRDm7SuedvRNigwFusq/66ThaqSf1grWGHgg0Yy', 14, NULL, NULL, '2021-12-13 03:29:33', NULL, 'ACTIVE'),
(136, NULL, NULL, 'Kimberly Kate Sy', 'uploads/mrs-avatar.png', 'kimberlykatesy@gmail.com', '$2y$10$1bWluE0s4MYEE48U5TkTXuKBiQo.TGecAOCtIqE0hO4wleu1O4rWS', 5, NULL, 2, '2021-12-13 06:41:44', NULL, 'ACTIVE'),
(137, 'GERALD', 'DINGLASAN', 'GERALD DINGLASAN', NULL, 'geralddinglasan@digits.ph', '$2y$10$86Zco.lmy4PXL4KIiXJf7.4ERSzk2/YRPwDnWFo0uIRG/qJwXXopW', 5, NULL, 1, '2022-01-05 09:48:39', '2022-08-08 07:29:28', 'INACTIVE'),
(138, NULL, NULL, 'DW AYALA ATC', NULL, 'dwayalaatc@digitalwalker.ph', '$2y$10$8sEdoOexkgR7W5.fxxtlmeYLObx7EFTX1iQE98nkXkO.vVkvVFuoS', 2, '140', 2, '2022-01-06 06:18:24', '2022-01-06 09:53:47', 'ACTIVE'),
(139, NULL, NULL, 'DW KCC MALL ZAMBOANGA', 'uploads/mrs-avatar.png', 'dwkccmallzamboanga@digitalwalker.ph', '$2y$10$uVowF2y1prdz4ikiSZW6f.FqHS9g/YV6fJkQwJo2f2anaioY9rmpK', 2, '141', 1, '2022-01-11 02:13:36', NULL, 'ACTIVE'),
(140, NULL, NULL, 'SHOPEE SOUNDPEATS', 'uploads/mrs-avatar.png', 'soundpeats@bluespace.com', '$2y$10$ZjYQxEFQvVfkPT6VASPViuESsTbVxG3YWdVfu7OTkJ8VfC64XLK.y', 2, '136', 2, '2022-01-12 01:40:39', NULL, 'ACTIVE'),
(141, NULL, NULL, 'LAZADA AUDIOENGINE FBD', 'uploads/mrs-avatar.png', 'lazadaaudioenginefbd@online.com', '$2y$10$iVv7z/o5m7mphc4KIeCSiu99IOfLg9rSL6t0qwQnZzNjpDAMCgw3u', 10, '142', 4, '2022-01-13 06:39:51', NULL, 'ACTIVE'),
(142, NULL, NULL, 'LAZADA HIDRATESPARK FBD', 'uploads/mrs-avatar.png', 'lazadahidratesparkfbd@online.com', '$2y$10$2e1QnpQFtgHsaqge/XycmOeZxvVaITC/tA1ls05BXtH/HZTwS3Mba', 10, '144', 4, '2022-01-13 06:40:48', NULL, 'ACTIVE'),
(143, NULL, NULL, 'HIDRATESPARK SHOPEE FBD', 'uploads/mrs-avatar.png', 'shopeehidratesparkfbd@online.com', '$2y$10$v4P1ZYac7F9vosPlc9fy1u5NxIpIUfvLXm8RgUtraE93OzlVam5Rm', 10, '145', 4, '2022-01-13 06:41:38', NULL, 'ACTIVE'),
(144, NULL, NULL, 'AUDIOENGINE LAZADA FBV', 'uploads/mrs-avatar.png', 'lazadaaudioenginefbv@online.com', '$2y$10$3F/kCaJuyEvpuupKbdV0hObnb0bAPXutfQPRN3ata06/gd9cWtJCe', 8, '143', 4, '2022-01-13 06:42:32', NULL, 'ACTIVE'),
(145, NULL, NULL, 'HIDRATESPARK LAZADA FBV', NULL, 'lazadahidratesparkfbv@online.com', '$2y$10$.cDCuE0lve2nbjbMPV3hOeoUZwlHJ51rOLUohuWZBBePAr/Lyijci', 8, '146', 4, '2022-01-13 06:43:13', '2022-01-13 06:43:49', 'ACTIVE'),
(146, NULL, NULL, 'Ricky Alnin', NULL, 'rickyalnin@digits.ph', '$2y$10$ASTfp3cA5sUNMLb8Aj06B.sQZJQOzQOgheqBAWawoIZpMHKM59rqu', 1, NULL, NULL, '2022-01-18 09:56:01', '2022-11-18 04:02:30', 'INACTIVE'),
(147, NULL, NULL, 'Mafelyn Aguilar', 'uploads/mrs-avatar.png', 'mafaguilarkickstart@gmail.com', '$2y$10$GD/0TkN4Rb8TJAtVKCdVtucipzcJ4ENKg3bcy9sm5IOaRLfWhrCP.', 5, NULL, 2, '2022-01-18 10:36:01', NULL, 'ACTIVE'),
(148, NULL, NULL, 'Rovelyn Jeresano', 'uploads/mrs-avatar.png', 'rovelynjeresanokickstart@gmail.com', '$2y$10$KJu8fDV1JJdhQncz4iCXB.G9OK40Z2aMCSJ19ARhaQVyCVWdaAEcO', 5, NULL, 2, '2022-01-18 10:36:36', '2024-01-11 06:01:49', 'INACTIVE'),
(149, NULL, NULL, 'Galaxy Hub Company', NULL, 'galaxyhubcompany@yahoo.com', '$2y$10$sdvHC1hQyrfpvoO9oWW/j.cWJHR8cu5PfElAiz1SBLgDfs.pozt36', 5, NULL, 2, '2022-01-25 01:18:56', '2022-01-25 03:46:48', 'ACTIVE'),
(150, NULL, NULL, 'Jenny Gatlabayan', NULL, 'jennygatlabayan@digits.ph', '$2y$10$qYStF7ZIWOLPjPcCtDs4G.IfOy1ZPFJMD3gmqxivS8EhHKOqKJ5s2', 21, NULL, 4, '2022-01-26 05:32:13', '2022-03-17 07:48:52', 'ACTIVE'),
(151, NULL, NULL, 'Lewie Adona', NULL, 'lewieadona@digits.ph', '$2y$10$PZ3u4ssJatz.3Gin5FJ82eN6DGx31tH2DGu7dX4AFtfrBHB9JbGw.', 1, NULL, NULL, '2022-01-27 03:47:05', '2022-01-27 05:12:03', 'INACTIVE'),
(152, NULL, NULL, 'Tin Chua', NULL, 'tinnchua@gmail.com', '$2y$10$Y6KNOpZK22lorRkxWfwNneFIfoBsFauwRskUdpf6nVCeEGgz0XvVK', 5, NULL, 2, '2022-01-27 04:20:49', '2023-03-25 07:30:39', 'ACTIVE'),
(153, NULL, NULL, 'Noriza Vitacion', 'uploads/mrs-avatar.png', 'norizavitacion@digits.ph', '$2y$10$l0MzEQc4VQN8QMIfQYhKzeox3qRX054YfP3NnRIhVr5UJbS0AaSU6', 12, NULL, 1, '2022-02-09 01:03:00', NULL, 'ACTIVE'),
(154, NULL, NULL, 'Sharleene See', NULL, 'shar806@gmail.com', '$2y$10$gFpmGin3x18/zGga1V7EkuVA7oFiaZL5xrsTIk0zy/p.oDsg3ovze', 5, NULL, 2, '2022-02-11 03:43:37', '2022-02-11 03:45:10', 'ACTIVE'),
(155, NULL, NULL, 'Pegie Piquero', NULL, 'pegiepiquero@digits.ph', '$2y$10$H3MBZ66hzo3rX5KBs/M9XuiPK2K5kgqNxRoschjfDsji7JWeQ74FO', 18, NULL, NULL, '2022-03-01 06:43:32', '2022-03-02 03:04:57', 'INACTIVE'),
(156, NULL, NULL, 'Bernie Ann Salarosan', 'uploads/mrs-avatar.png', 'bernieannsalarosan@digits.ph', '$2y$10$xXTYZkKlr6N6CBqArreJyuuJhJSRiPN0NyAGARuyabMSH6b8lOYmG', 18, NULL, NULL, '2022-03-01 06:44:21', '2023-02-22 03:32:49', 'INACTIVE'),
(157, NULL, NULL, 'Marty Castro', NULL, 'martycastro@digits.ph', '$2y$10$85nLML5z1X.wqYXkgC35ketDwQbK59j.NtK3y5raTM4mRdPuWqIsa', 18, NULL, NULL, '2022-03-01 06:45:42', '2022-03-02 03:40:57', 'ACTIVE'),
(158, NULL, NULL, 'Jomark Tamboong', NULL, 'jomarktamboong@digitalwalker.ph', '$2y$10$k6L15cu5jq3ejT7Ag58dNeT4u1KyfDwEithhosDYY1WfgZdhDNomC', 12, '62,63', 1, '2022-03-08 08:14:18', '2022-11-18 10:06:42', 'ACTIVE'),
(159, NULL, NULL, 'Corprate Sales', NULL, 'corporatesales@digits.ph', '$2y$10$FX23FIp/dS0vTbKZPa6CouPh/QVfSicy9xRGdkLIwqai/2hbSjgjq', 23, '229,147,240,148,150,151,152,153,154,272,155,208,156,157,228,158,159,160,162,252,165,239,230,167,168,170,171,253,172,198,174,175,176,231,299,178,181,182,259,236,235,184,258,188,251,190,234,256,191,192,193,194', 11, '2022-03-25 10:58:09', '2024-01-30 06:08:54', 'ACTIVE'),
(160, NULL, NULL, 'Outright Sales', NULL, 'outrightsales@digits.ph', '$2y$10$koiQAHPM0tZvEecsXHeLSuGbUir/RJiI50.wMibv97LB5c4mQEDP6', 23, '149', 7, '2022-03-25 10:58:46', '2023-03-23 09:18:56', 'ACTIVE'),
(161, NULL, NULL, 'Dealership Sales', NULL, 'dealership@digits.ph', '$2y$10$koX12xMx3v3g6gyfcRIMaeMP23EvJXZNKhEONkyiiJH2FFWk5t3rS', 23, '115,273,271,286,161,243,163,164,282,265,166,169,201,254,173,263,264,177,270,295,179,180,183,257,185,186,187,189,195', 10, '2022-03-25 11:00:01', '2023-12-12 07:04:22', 'ACTIVE'),
(162, NULL, NULL, 'Distri Ops', 'uploads/mrs-avatar.png', 'distribution@digits.ph', '$2y$10$Qyv8Ru1vqlEVZft4ywjXael/avx9yLundSH3OiJjr4g1TCZpdLWkO', 24, NULL, NULL, '2022-03-25 11:00:33', NULL, 'ACTIVE'),
(163, NULL, NULL, 'Cristine Tiu Santos', 'uploads/mrs-avatar.png', 'cristinetiusantos@digits.ph', '$2y$10$E96t/oSBAV7F4AeR8wEfJ.rtGNdDRTveUzhVOX4qDrs9GMhmIDPaO', 5, NULL, NULL, '2022-03-25 11:04:30', '2023-10-20 07:59:01', 'INACTIVE'),
(164, NULL, NULL, 'XIAOMI CIRCUIT MAKATI', 'uploads/mrs-avatar.png', 'xiaomicircuitmakati@digitalwalker.ph', '$2y$10$VbX0B..dRCmYVU5hbHlaMuEVxyuo5.3Q9.9cjWte0phl554UWvaUS', 2, '202', 1, '2022-04-21 07:28:26', NULL, 'ACTIVE'),
(165, NULL, NULL, 'CRISTINE ROSALES', 'uploads/mrs-avatar.png', 'christinerosales@digits.ph', '$2y$10$Xtj71BY2cuTSee3hryQqQuJJnVpi0lM4xa0wvmG4skxkZ05rcYm4S', 22, NULL, NULL, '2022-04-27 01:42:03', NULL, 'ACTIVE'),
(166, NULL, NULL, 'Maria Clarisse Bajita', 'uploads/mrs-avatar.png', 'mariabajita@digits.ph', '$2y$10$GeyUX4ekC/tg586pK0hf9u7TwFJeXjoSEKYfBPF.Jcxno/7vr7cp.', 22, NULL, NULL, '2022-04-27 01:42:22', NULL, 'ACTIVE'),
(167, NULL, NULL, 'Rona Abadilla', NULL, 'ronaabadilla@digits.ph', '$2y$10$mi2tDgCJpoQlSWYzQ9cwmOcotiX1IBaHMODUHBdYKNujL9GOo/7Ue', 22, NULL, NULL, '2022-04-27 01:42:35', '2022-04-27 01:51:26', 'ACTIVE'),
(168, NULL, NULL, 'Mitchelle Gomez', 'uploads/mrs-avatar.png', 'mitchellegomez@digits.ph', '$2y$10$zwM5dhcE0iNnI5v/rEri..uynD7k2RNYq.wp5.zc3UbsXLb0YvXKK', 22, NULL, NULL, '2022-04-27 01:42:56', NULL, 'ACTIVE'),
(169, NULL, NULL, 'KRISTINE SANTOS', 'uploads/mrs-avatar.png', 'kristinesantos@digits.ph', '$2y$10$9YqFHCG7wsCb91dzwVfGdOW5.01hKapQ0uXGhkPIhaxXxxAxJBhOO', 22, NULL, NULL, '2022-04-27 01:43:11', NULL, 'ACTIVE'),
(170, NULL, NULL, 'NOTHING LAZADA FBD', 'uploads/mrs-avatar.png', 'nothinglazadafbd@online.com', '$2y$10$9TzuQBE7EFBhourrhEoTtePtzUd1q4KSNfoDW6jEViULwpzEn57qS', 10, '203', 4, '2022-04-28 02:53:58', NULL, 'ACTIVE'),
(171, NULL, NULL, 'NOTHING SHOPEE FBD', 'uploads/mrs-avatar.png', 'nothingshopeefbd@online.com', '$2y$10$nX.Be4FytFD7nd2Em09tkuPUfklz5TSa/YxA.AH8v2G9ecwGEPQ8C', 10, '204', 4, '2022-04-28 02:54:40', NULL, 'ACTIVE'),
(172, NULL, NULL, 'NOTHING LAZADA FBV', 'uploads/mrs-avatar.png', 'nothinglazadafbv@online.com', '$2y$10$p/45mRnZwUDv2TppqRk8iePQjdY4VmWG4YO/bZZy8gvXBn9b0QNu.', 8, '205', 4, '2022-04-28 02:55:22', NULL, 'ACTIVE'),
(173, NULL, NULL, 'Geovan Ang', 'uploads/mrs-avatar.png', 'geovan.ang@bluespacetechnologies.com', '$2y$10$eJ/VIpwL2rGAKXh5zQVSwezuam3AWAHLBikbEIAq2X4IFUJz67S8a', 5, NULL, 2, '2022-05-05 10:54:11', NULL, 'ACTIVE'),
(174, NULL, NULL, 'SPECK LAZADA FBV', NULL, 'specklazadafbv@online.com', '$2y$10$c9s3oSQkERwv4j3TnWWPk.k4TZRyTs/OYHw5FkrHFrWfLYHWHt2m2', 8, '206', 4, '2022-05-12 03:52:12', '2022-05-12 03:53:45', 'ACTIVE'),
(175, NULL, NULL, 'DW ANTIPOLO MADNESS 1', 'uploads/mrs-avatar.png', 'dwmadantipolo1@digitalwalker.ph', '$2y$10$kIvVae8Ni/SC1tE4sSj4YOyU1fYgLHzZDYtji6R5tHWq5oEZUN9Iu', 2, '207', 1, '2022-05-19 10:51:50', NULL, 'ACTIVE'),
(176, NULL, NULL, 'DW ANTIPOLO MADNESS 2', 'uploads/mrs-avatar.png', 'dwmadantipolo2@digitalwalker.ph', '$2y$10$kZPWutKPkOV0tdDiJbx6sOBdbzSMdawuR5TunfMkXNu7Ft1i05e1K', 2, '207', 1, '2022-05-19 10:52:26', NULL, 'ACTIVE'),
(177, NULL, NULL, 'DW ANTIPOLO MADNESS 3', 'uploads/mrs-avatar.png', 'dwmadantipolo3@digitalwalker.ph', '$2y$10$PoPsbeNz6ITHdUXGj9NFWOIk5441zKsVBdM7LUDW0QErnh8GGkD0G', 2, '207', 1, '2022-05-19 10:52:56', NULL, 'ACTIVE'),
(178, NULL, NULL, 'DW ANTIPOLO MADNESS 4', 'uploads/mrs-avatar.png', 'dwmadantipolo4@digitalwalker.ph', '$2y$10$sdMtjhZ5pDG2I4Jku5HEz.cdcO87doxt3R2uyl1pPpUmUj4el.I0y', 2, '207', 1, '2022-05-19 10:53:33', NULL, 'ACTIVE'),
(179, NULL, NULL, 'DW ANTIPOLO MADNESS 5', 'uploads/mrs-avatar.png', 'dwmadantipolo5@digitalwalker.ph', '$2y$10$.L7DQ/9tWsen2n2lg3pP4eSoKW2yKXbG041c3aMNkVwdwpgwXy6pW', 2, '207', 1, '2022-05-19 10:54:03', NULL, 'ACTIVE'),
(180, NULL, NULL, 'DW ANTIPOLO MADNESS 6', 'uploads/mrs-avatar.png', 'dwmadantipolo6@digitalwalker.ph', '$2y$10$Xj7HhljJh50Lo1oQbjUSjeBc32ik7q.ZfsxkRoptmR2oKB21mlwg.', 2, '207', 1, '2022-05-19 10:54:36', NULL, 'ACTIVE'),
(181, NULL, NULL, 'DW ANTIPOLO MADNESS 7', 'uploads/mrs-avatar.png', 'dwmadantipolo7@digitalwalker.ph', '$2y$10$N6TDuhS.wpjNFwWX9GKEruwGqRc29Nv5f9Ep/TTwyyZI3fjYewPJG', 2, '207', 1, '2022-05-19 10:55:22', NULL, 'ACTIVE'),
(182, NULL, NULL, 'DW ANTIPOLO MADNESS 8', 'uploads/mrs-avatar.png', 'dwmadantipolo8@digitalwalker.ph', '$2y$10$bvkoXC8cGc8IPHVnU2CEDuOnmnU1YWIfesudOiFSDRroEKRjO16Pm', 2, '207', 1, '2022-05-19 10:55:54', NULL, 'ACTIVE'),
(183, NULL, NULL, 'Janine Corcochea', 'uploads/mrs-avatar.png', 'janinecorcochea@digits.ph', '$2y$10$/E2lSukm97ssWRAPwjnxNeifb.cywXq2Urmtj5.wZlxTuPkhup8Ay', 22, NULL, NULL, '2022-06-01 06:21:37', NULL, 'ACTIVE'),
(184, NULL, NULL, 'Adrienne dela Cruz', 'uploads/mrs-avatar.png', 'adriennedelacruz@digits.ph', '$2y$10$cftFJBUPbVOiQjKJFOalwO73.ywPW1KfFMP2JS0bSLFSIrWtWmbvG', 22, NULL, NULL, '2022-06-01 06:21:59', NULL, 'ACTIVE'),
(185, NULL, NULL, 'DW AYALA TRIANGLE SERENE', NULL, 'dwayalaserene@digitalwalker.ph', '$2y$10$jifKUZ9oDKTZ4OiPFXhMY.0fOMX/5vK5xs3P2LtFrlxxRXequDFh2', 2, '210', 1, '2022-06-13 05:35:50', '2024-01-25 07:31:54', 'ACTIVE'),
(186, NULL, NULL, 'Marechie Ratilla', NULL, 'marechieratilla@digits.ph', '$2y$10$l4YM8v9gY5daYiC9.KgEaOef2rEfPabV6xQhHyFExK/BtmMHVIY6e', 21, NULL, 4, '2022-06-20 05:33:39', '2022-06-20 05:33:57', 'ACTIVE'),
(187, NULL, NULL, 'ECOM FBD', 'uploads/mrs-avatar.png', 'ecomfbd@digits.ph', '$2y$10$laKc8zZqgGgIkzbhDHarr.M2pBa7fYvKpU/jCdktXdPvS0pXhUWrC', 10, '227', 4, '2022-06-22 07:33:45', NULL, 'ACTIVE'),
(188, NULL, NULL, 'Marvin Mosico', NULL, 'marvinmosico@digits.ph', '$2y$10$m.wu/L.ekWaubyH7u8cnceKDeoVttSLTIuKemJl6VOmjStBPZL8Jq', 1, NULL, NULL, '2022-08-08 06:06:07', '2023-10-26 06:27:17', 'ACTIVE'),
(189, NULL, NULL, 'Jimson Villano', NULL, 'jimsonvillano@digits.ph', '$2y$10$g9FWvRy6h5JZGBoxtbUVhed1/8i8V6EHJ4ewzN7c4LYhnYSMuN36e', 5, NULL, NULL, '2022-09-13 01:31:42', '2023-10-25 06:37:11', 'ACTIVE'),
(190, NULL, NULL, 'Ana Lacorte', NULL, 'analacorte@digits.ph', '$2y$10$LzNMg3ZG9nus63ruXYr5qumzmR43K8pvc4XHQcwurNnaKOJdIU9a2', 5, '62,63', 1, '2022-09-22 09:24:22', '2023-05-24 10:23:24', 'INACTIVE'),
(191, NULL, NULL, 'DW SM TANZA CAVITE', NULL, 'dwtanzacavite@digitalwalker.ph', '$2y$10$BKb2TDM.sTcczGqzE4d5feMAiJrl938ZR1DoFRHm.1mjCBeRddeMC', 2, '237', 1, '2022-09-23 13:00:19', '2022-10-21 06:46:55', 'ACTIVE'),
(192, NULL, NULL, 'ACEFAST LAZADA FBV', 'uploads/mrs-avatar.png', 'acefastlazadafbv@online.com', '$2y$10$7wVaUCs6QiCbbCzZZie7sOI9FAQclRxq2qhKuqiboTCwh4pHUtD0e', 8, '238', 4, '2022-09-29 00:29:14', NULL, 'ACTIVE'),
(193, NULL, NULL, 'Raffy Colico', 'uploads/mrs-avatar.png', 'raffycolico@digits.ph', '$2y$10$ALTFvdJPofU6puwZKE/RWO2Qw9dWxBt8192RWX3XKMKdWjFEFVw4S', 5, NULL, NULL, '2022-09-30 06:49:33', NULL, 'ACTIVE'),
(194, NULL, NULL, 'DW ROBINSON ANTIPOLO', NULL, 'dwrobinsonsantipolo@digitalwalker.ph', '$2y$10$8Yum.xDJnCaESfkcCdQkWOwWatIfJE7ItNx2/rk5Cu9qhT39JIwdC', 2, '242', 1, '2022-10-21 06:43:03', '2022-11-11 10:20:57', 'ACTIVE'),
(195, NULL, NULL, 'DW EVIA MALL', 'uploads/mrs-avatar.png', 'dweviamall@digitalwalker.ph', '$2y$10$H2XBq1Uyqj9fwTRqY7Crc.f35P/xflPw5bMdu6gi4qKoK1tgiiZQu', 2, '241', 1, '2022-10-21 06:46:22', NULL, 'ACTIVE'),
(196, NULL, NULL, 'BTB ROB MALOLOS', 'uploads/mrs-avatar.png', 'btbrobmalolos@beyondthebox.ph', '$2y$10$ZhTcShlFVennEfzQVfi.I.dIF3epqNnOlrP5EXzeFFFKSUf86ecqq', 2, '249', 1, '2022-11-04 08:32:44', NULL, 'ACTIVE'),
(197, NULL, NULL, 'XIOAMI MITSUKOSHI BGC', 'uploads/mrs-avatar.png', 'xiaomimitsukoshibgc@digitalwalker.ph', '$2y$10$eTDVzykvLTNyx5ox3em6Me7VbCdUJG47REHN2EyH.NvgOmWwGKCY2', 2, '245', 1, '2022-11-04 08:33:53', NULL, 'ACTIVE'),
(198, NULL, NULL, 'BTB CITYFRONT CLARK', 'uploads/mrs-avatar.png', 'btbcityfrontclark@beyondthebox.ph', '$2y$10$TGvlSfO/5mf7vgJsNNlequr3rRujsdXtZJOVAvlT0sbNT1rU4DgPi', 2, '248', 1, '2022-11-04 08:34:52', NULL, 'ACTIVE'),
(199, NULL, NULL, 'OS MITSUKOSHI BGC', NULL, 'osmitsukoshibgc@beyondthebox.ph', '$2y$10$laNAdIxS307GAwK4ylph/Oghi2K119BV6DOEEKo.//AI7WX05t8fq', 2, '246', 1, '2022-11-04 08:36:04', '2024-02-21 08:59:18', 'ACTIVE'),
(200, NULL, NULL, 'CLEARANCE SMX MOA', NULL, 'smxmoa@digitalwalker.ph', '$2y$10$sUaRikf84OOSCR.IYl9JQeqoy6dpskBqJLUOstD5bpr.TD1LZns9a', 2, '244', 1, '2022-11-11 09:35:20', '2022-11-11 10:19:31', 'ACTIVE'),
(201, NULL, NULL, 'DW POP MITSUKOSHI', NULL, 'dwmitsukoshibgc@digitalwalker.ph', '$2y$10$RzVz5mmWJgMMqf1.GRIGZe2MYDVa8rhQmqytB2.WiN7Rx6FzMK8xK', 2, '250', 1, '2022-11-14 05:52:20', '2023-02-27 03:57:12', 'ACTIVE'),
(202, NULL, NULL, 'JOY ABRAJANO', 'uploads/mrs-avatar.png', 'joyabrajano@digits.ph', '$2y$10$MtB3NrckvEhaWdz3trc4l.bUEm7lvD9ObRV0j2667q0X/p8yjM6FK', 12, '62,63', 1, '2022-11-21 08:37:15', '2023-09-18 07:18:22', 'INACTIVE'),
(203, NULL, NULL, 'Jomark Tamboong', 'uploads/mrs-avatar.png', 'jomarktamboong@digits.ph', '$2y$10$M/nok8pN3GpzO4OBQfZ6OuSLIIxpj9LnwN3LGSXGp0baud6KF5MZy', 3, '65', NULL, '2022-11-25 07:41:01', NULL, 'INACTIVE'),
(204, NULL, NULL, 'JOHN LARRY AGMATA', 'uploads/mrs-avatar.png', 'johnagmata@digits.ph', '$2y$10$pyeI8gjZd8pQnRl.qc/6xO2OgOcls/zaFrzUCni57UlqozzCH9yRu', 18, NULL, NULL, '2023-01-16 03:04:13', NULL, 'ACTIVE'),
(205, NULL, NULL, 'Ace Miguel  Binuya', 'uploads/mrs-avatar.png', 'acebinuya@digits.ph', '$2y$10$7EMCpzpR8LvEK0AFKlzu/OmtBbi/4NFiwecdof.yTeoiz1ufRV2nu', 18, NULL, NULL, '2023-01-16 03:04:36', NULL, 'ACTIVE'),
(206, NULL, NULL, 'DW SM MAKATI', 'uploads/mrs-avatar.png', 'dwsmmakati@digitalwalker.ph', '$2y$10$z34bKLZSziwbnzD/eEyR/eObdQ2rc1UKJkKNJDYlWHXl8JMfG5tOK', 2, '255', 2, '2023-01-30 08:52:59', NULL, 'ACTIVE'),
(207, NULL, NULL, 'Charlotte Abihay', NULL, 'charlotteabihay@digits.ph', '$2y$10$NNlETQHju6Wj5ZiSXgWZu.jFihlGym.neBDAKs0vasUnWJaTMdGde', 5, '', 1, '2023-02-03 01:51:03', '2023-06-07 02:41:54', 'ACTIVE'),
(208, 'Vincent', 'Jesalva', 'Vincent Jesalva', NULL, 'vincentjesalva@digits.ph', '$2y$10$ISHvRRexFuZWHq46/lVRy.TFBG2pXUlCN0KY3jq8jcylf7YLnSSa6', 24, NULL, NULL, NULL, '2023-02-07 06:50:22', 'INACTIVE'),
(209, 'ANALYN', 'EVANGELISTA', 'Analyn Evangelista', NULL, 'analynevangelista@digits.ph', '$2y$10$Vx6rkQgh6ueXGcZV0MIeXOoFCrqMD7RmdLJg009U5bE6Cl8Li/36y', 22, NULL, NULL, NULL, '2023-03-01 05:10:39', 'ACTIVE'),
(210, 'Lorie Anne', 'Teobengco', 'Lorie Anne Teobengco', NULL, 'lorieteobengco@digits.ph', '$2y$10$F4T5JlTdheh3crMYc/NdTOAhnKizBP94tG/XMWiA/JmBf7g6XENP.', 22, NULL, NULL, NULL, '2023-03-02 09:21:20', 'ACTIVE'),
(211, 'Laiza', 'Sandayan', 'Laiza Sandayan', NULL, 'laizasandayan@digits.ph', '$2y$10$jvq7jbiYlGcKfQ0SVA6jBuwwzeLUZYLwegeO6sZxOzXb/oSQmeGlW', 5, '9', 1, NULL, '2023-04-26 05:29:49', 'ACTIVE'),
(212, NULL, NULL, 'DW HIGH STREET', 'uploads/mrs-avatar.png', 'ayalahighstreet@digitalwalker.ph', '$2y$10$zSLb7AuaSuqANw5EBslvOuIJYkZUKjESLBbfGm4cf/e7hLfSzBghe', 2, '261', 1, '2023-03-20 11:06:20', NULL, 'ACTIVE'),
(213, NULL, NULL, 'DW GREENBELT 5', 'uploads/mrs-avatar.png', 'greenbelt5@digitalwalker.ph', '$2y$10$Ee4Qs56ieg3hBJAuQ7GUw.3/XfYDJJ8YuWZyQglvpkJ15y4pit0Ry', 2, '260', 1, '2023-03-20 11:07:24', NULL, 'ACTIVE'),
(214, 'DIGITAL WALKER', 'ROBINSONS TACLOBAN', 'DIGITAL WALKER ROBINSONS TACLOBAN', NULL, 'dwrobtacloban@digitalwalker.ph', '$2y$10$5calxPUju8LcYnPrbqDwg.VFn8Sjmrnll/0Gt9jbtvqftmIkphsZK', 2, '262', 2, NULL, '2023-03-30 05:59:02', 'ACTIVE'),
(215, 'DIGITAL WALKER', 'ONE AYALA', 'DIGITAL WALKER ONE AYALA', NULL, 'dwoneayala@digitalwalker.ph', '$2y$10$p5iMTu.cFwXgYTCBGwx4guiudTY2JuS/FHlCum65qKx/LvBVDLZyS', 2, '268', 2, NULL, '2023-12-27 01:54:02', 'ACTIVE'),
(216, 'DIGITAL WALKER', 'SM TELEBASTAGAN', 'DIGITAL WALKER SM TELEBASTAGAN', NULL, 'dwsmtelabastagan@digitalwalker.ph', '$2y$10$EcmlrM0MOvlLkYGBLUa1XOj/oOvniX0yPk3JQq70Ov6jsce9b4DLq', 2, '269', 1, NULL, '2023-05-11 08:43:05', 'ACTIVE'),
(217, 'DIGITAL WALKER', 'GREENHILL', 'DIGITAL WALKER GREENHILLS', NULL, 'dwgreenhills@digitalwalker.ph', '$2y$10$/yvBZRIRDvqE.30odYUS5uh6FiAXJfDdi5ggCb.SFDUrxSwHdk1Vm', 2, '267', 1, NULL, '2023-05-11 08:43:37', 'ACTIVE'),
(218, 'DIGITAL WALKER', 'GATEWAY', 'DIGITAL WALKER GATEWAY', NULL, 'dwgateway@digitalwalker.ph', '$2y$10$8QD9DFOA9e6X80HPirl0E./zdUm7sJbDGjTpmMDznezdImIjmeYr2', NULL, NULL, NULL, NULL, '2023-05-24 10:22:52', 'INACTIVE'),
(219, 'BEYOND THE BOX', 'GREENHILLS', 'BEYOND THE BOX GREENHILLS', NULL, 'btbgreenhills@beyondthebox.ph', '$2y$10$sBM9ezEbCJAcsCpsgxWdKu/lYpILfJ2FCY46sro51mmBnwK4pyNUS', 2, '266', 1, NULL, '2023-05-11 08:44:05', 'ACTIVE'),
(220, 'DIGITAL WALKER', 'STO TOMAS', 'DIGITAL WALKER STO TOMAS', NULL, 'dwsto.tomas@digitalwalker.ph', '$2y$10$xA7lKPWVHetkEgLZYEytmO5HR2Cq/i.p5zzu8ZTtcN.CMfFg4/c0K', 2, '275', 1, NULL, '2023-07-13 04:11:18', 'ACTIVE'),
(221, 'BEYOND THE BOX', 'CLARK AIRPORT', 'BTB CLARK AIRPORT', NULL, 'btbclarkairport@beyondthebox.ph', '$2y$10$VuJdcGBSHNIEcNJaRioB.erAsy4MstDxCZ39JWRXH1fHyhwH.wXaC', 2, '274', 1, NULL, '2024-01-16 07:53:14', 'ACTIVE'),
(222, 'Digital Walker', 'Ayala Solenad Laguna', 'Digital Walker Ayala Solenad Laguna', NULL, 'dwayalasolenadlaguna@digitalwalker.ph', '$2y$10$71fzhIMn2cYGAn.gS1.mderDqtQf.HRR0wJK7Q6clifwmDiqWql32', 2, '281', 1, NULL, '2023-07-31 09:51:10', 'ACTIVE'),
(223, 'Digital Walker', 'SM Valenzuela', 'Digital Walker SM Valenzuela', NULL, 'dwsmvalenzuela@digitalwalker.ph', '$2y$10$P3s3cPZXV3DFQM9VLG4I0.C/PQCs8MjcLUgntJ4gI2kfdkwAwkbm.', 2, '280', 1, NULL, '2023-07-31 09:50:32', 'ACTIVE'),
(224, 'Digital Walker', 'SM Bacoor', 'Digital Walker SM Bacoor', NULL, 'dwsmbacoor@digitalwalker.ph', '$2y$10$NXc2Jmv4LrOmlrLv2cx6/.LForigof8hS2JhFVREFECokeX.HSgEm', 2, '279', 1, NULL, '2023-07-31 09:49:54', 'ACTIVE'),
(225, 'Open Source', 'Rob Gapan', 'Open Source Rob Gapan', NULL, 'osrobgapan@beyondthebox.ph', '$2y$10$EK591O1BZFm/LAJxU82uSO9hjmB4EjhPMWOM9PTLBIRa54O40NI7e', 2, '278', 1, NULL, '2023-07-31 09:45:52', 'ACTIVE'),
(226, 'Open Source', 'Nustar Cebu', 'Open Source Nustar Cebu', NULL, 'osnustarcebu@beyondthebox.ph', '$2y$10$j8.dZ.ZT87oHESkp5Dj6SeGmRbMLwP3sAIfEzX134McCTYrwh6oI2', 2, '277', 1, NULL, '2023-07-31 09:45:28', 'ACTIVE'),
(227, 'Open Source', 'OPUS', 'Open Source OPUS', NULL, 'osupus@beyondthebox.ph', '$2y$10$rH8JGs2UaOtF1Q.ZH57S8uetEBRcd1BZ6Dv.JSIsmWJkS1oaisGYG', 2, '276', 1, NULL, '2023-07-31 09:44:56', 'ACTIVE'),
(228, 'Digital Walker', 'SM Rosario', 'Digital Walker SM Rosario', NULL, 'dwsmrosario@digitalwalker.ph', '$2y$10$1sGXX5NkHPyZBA2OPVdWputA9Gi0hkpZg6HGdmeuJeWEzuiBhBe6m', 2, '283', 1, NULL, '2023-08-08 01:35:58', 'ACTIVE'),
(229, 'Digital Walker', 'SM San Pedro', 'Digital Walker SM San Pedro', NULL, 'dwsmsanpedro@digitalwalker.ph', '$2y$10$OdiOF5IU2sPWWkNK3lbtpOagjRcajRfZc30xheL9jG9ygj7JBv6cq', 2, '285', 1, NULL, '2023-08-08 02:20:11', 'ACTIVE');
INSERT INTO `cms_users` (`id`, `first_name`, `last_name`, `name`, `photo`, `email`, `password`, `id_cms_privileges`, `stores_id`, `channel_id`, `created_at`, `updated_at`, `status`) VALUES
(230, 'Digital Walker', 'Circuit Makati', 'Digital Walker Circuit Makati', NULL, 'dwcircuitmakati@digitalwalker.ph', '$2y$10$n15reG32c1mKiJFAgcO.1.EW7NpQ9vZpEDUuTcUhf5r3qqnL8mQ8W', 2, '284', 1, NULL, '2023-08-08 01:35:32', 'ACTIVE'),
(231, 'Danica Marie', 'Dinong', 'Danica Marie Dinong', NULL, 'danicamariedinong@digits.ph', '$2y$10$WWUTsG4brQGWps21wMPL5ugOoIpCktqwXn8tjEq0ASiGwsUpZ0.e2', 22, NULL, NULL, NULL, '2023-08-16 08:10:50', 'INACTIVE'),
(232, 'Jonathan', 'Melegrito', 'Jonathan Melegrito', NULL, 'jonathanmelegrito@digits.ph', '$2y$10$BW1BXkh4xUv1gfMcoBCKmOgaK7/s9.53xwjrJfx0Sea3S8tTARE1m', 5, NULL, NULL, NULL, '2023-08-25 06:03:10', 'ACTIVE'),
(233, 'Digital Walker', 'Clark Airport', 'Digital Walker Clark Airport', NULL, 'dwclarkairport@digitalwalker.ph', '$2y$10$d.0.bb.l/EQ/C5Od3imkqul/VTzxHS2WSqi5CXZqnvFa/N8ZhC/A6', NULL, NULL, NULL, NULL, '2023-09-13 03:25:52', 'INACTIVE'),
(234, 'Amber', 'Yaokasin', 'Amber Yaokasin', NULL, 'jiannamber@gmail.com', '$2y$10$gbXF8Ym7mM.vmr9tZamiXe9UB114BZR6g9ZDZxNlrgB.bIfjowI8K', 5, '262', 2, NULL, '2023-08-31 00:44:13', 'ACTIVE'),
(235, 'Arleine', 'Dela Cruz', 'Arleine Dela Cruz', NULL, 'arleinedelacruz@digits.ph', '$2y$10$nOO1APEXEwvYNZcrbfmBBuN.YxuXZSIr6CCGxyDpNE.Eqv0Xo/Qba', 5, NULL, NULL, NULL, '2023-09-06 05:16:57', 'ACTIVE'),
(236, 'Digital Walker', 'Robinsons Ermita 2F', 'Digital Walker Robinsons Ermita 2F', NULL, 'dwrobinsonsermita2f@digitalwalker.ph', '$2y$10$c6hXmmuOcyRyV6IkA0GQ3.ziIfxQLCNNg9kjGyaV.Qv/.q6kvXjYy', 2, '287', 1, NULL, '2023-09-13 02:20:34', 'ACTIVE'),
(237, 'Open Source', 'Parqal Aseana', 'Open Source Parqal Aseana', NULL, 'osparqalaseana@beyondthebox.ph', '$2y$10$s4LIaW7knCkrCVc0EO2NKef/6xevi/agFk2gK1iLoh7B5iK.KO6sW', 2, '288', 1, NULL, '2023-09-13 02:20:14', 'ACTIVE'),
(238, 'Digital Walker', 'Lipad Clark', 'Digital Walker Lipad Clark', NULL, 'dwlipadclark@digitalwalker.ph', '$2y$10$xH.R9vmVXyaOvq6o9jAjauIxVZaawSy9eHjSirJ69JnhpMeEjgkbi', 2, '289', 1, NULL, '2023-09-13 03:26:24', 'ACTIVE'),
(239, 'Digital Walker', 'Sm Araneta City', 'Digital Walker Sm Araneta City', NULL, 'dwsmaraneta@digitalwalker.ph', '$2y$10$jB/M/2kkIjh/ya22L26p7uOQ03SxI14FSVpseUsBtoxEijmUAv07O', 2, '290', 1, NULL, '2023-09-28 02:18:07', 'ACTIVE'),
(240, 'Ronald', 'Yap', 'Ronald Yap', NULL, 'shermanyap@qualityappliance.ph', '$2y$10$9Oipuf2VjxSdDK0cFPQVEO5Xp6.XekbXOvGSpw6fTnhmCIIuw/hWe', 5, NULL, 2, NULL, '2023-10-13 05:22:44', 'ACTIVE'),
(241, 'Robelyn', 'Balayo', 'Robelyn Balayo', 'uploads/mrs-avatar.png', 'robelynbalayo@digits.ph', '$2y$10$FG9H.AvnE57eA3m/J8fawupF0x13CoUFwp5v74YA2ZWCHzDI0Q6Fe', 3, '65', NULL, NULL, '2023-10-18 00:12:03', 'ACTIVE'),
(242, NULL, NULL, 'Howard Paw Distri', NULL, 'howardpaw@digits.ph', '$2y$10$yz6QhhcDcIdDPEK4vP9PHORiob9zO8NyBOpI7niR5QPZmNSOsgeoa', 5, NULL, NULL, '2023-10-20 08:51:02', '2023-10-20 08:51:21', 'ACTIVE'),
(243, 'Mitsukoshi', 'BGC', 'GBO MITSUKOSHI BGC', NULL, 'mitsukoshibgc@gashapon.ph', '$2y$10$ftey7QmkTFl2K.Pzf6rsE.ojOihQ6MOGA4jVpuNqO5QhYcb.ERa9K', 2, '291', 1, NULL, '2024-02-01 03:28:59', 'ACTIVE'),
(244, 'Crisanto', 'Tapangan', 'Crisanto Tapangan', NULL, 'crisantotapangan@digits.ph', '$2y$10$LBjrMqtNGjryN0yPx90KxOseFWGrY0zGKSNVVw034qDVNXFPrdTxq', 3, '65', NULL, NULL, '2023-11-09 04:08:48', 'INACTIVE'),
(245, 'ACEFAST', 'SM MOA', 'ACEFAST SM MOA', 'uploads/mrs-avatar.png', 'acefastsmmoa@acefast.ph', '$2y$10$/EaRJ32lsFs8vA67tiP0iuR/3hE0yOy8jHjB5UbgLyMuQBo71fMd.', 2, '292', 1, '2023-11-23 11:08:11', NULL, 'ACTIVE'),
(246, 'Jhon Michael', 'Mediavillo', 'Jhon Michael Mediavillo', NULL, 'jhonmichaelmediavillo@digits.ph', '$2y$10$rT8/yttnwWAKZA4wYibCLu6hbLVht4VYHm1iDE6kxkByQNMrUIUC6', 3, '65', NULL, NULL, '2023-11-28 08:14:19', 'ACTIVE'),
(247, 'Digital Walker', 'Ayala Bacolod', 'Digital Walker Ayala Bacolod', NULL, 'dwayalabacolod@digitalwalker.ph', '$2y$10$t9zbhibeHAJShkZf3P5ZcOQSiHlD2qzFvQpRd6hFkSQ3qmIDx6yEq', 2, '293', 1, NULL, '2023-12-04 01:33:01', 'ACTIVE'),
(248, 'Digital Walker', 'Vista Mall Sta Rosa', 'Digital Walker Vista Mall Sta Rosa', 'uploads/248/2024-02/digital_walker_logo.jpg', 'dwvistamallstarosa@digitalwalker.ph', '$2y$10$afKUCZeI7e0VNaZnPIh.XO4lA3pOmRHRmKzax/gZe6w0uGoxkV7E.', 2, '294', 1, NULL, '2024-02-17 11:56:54', 'ACTIVE'),
(249, 'ROBERT', 'MENDOZA', 'ROBERT MENDOZA', NULL, 'robertmendoza@digits.ph', '$2y$10$zdmEF499ZobAlERUduABwOI91R5.NJ8eYKxoTHMsGbsh/hoQNcgwm', 18, NULL, NULL, NULL, '2023-12-19 03:07:19', 'ACTIVE'),
(250, 'Kyla Nicole E', 'Castillo', 'Kyla Nicole E Castillo', NULL, 'kylanicolecastillo@digits.ph', '$2y$10$wRIz0fZd6QhJm5KeH6Oy2O.8scIy4gxMGLYutIzN06uG9Xb4ovJTK', 18, NULL, NULL, NULL, '2023-12-19 03:07:29', 'ACTIVE'),
(251, 'Joelan', 'Delota', 'Joelan Delota', NULL, 'joelandelota@digits.ph', '$2y$10$kKNZJ30AKuwWdZTNJVAMceMeOfKFNMEYAJnwUlEI7dUKyyXuKRw66', 3, '65', NULL, NULL, '2023-12-19 04:47:14', 'ACTIVE'),
(252, 'Ma. Katrina Kaye', 'Librias', 'Ma. Katrina Kaye Librias', NULL, 'ma.katrinakayelibrias@digits.ph', '$2y$10$r34RTE0h3i6aV2uSDYpp5ec5xpy9arMx90JPhB63OMLSQEYhiOK.6', 22, NULL, 1, NULL, '2024-01-03 09:12:27', 'ACTIVE'),
(253, NULL, NULL, 'Hazel Martinez', 'uploads/253/2024-01/323174182_842333940216319_7365629982984367658_n.jpg', 'hazelmartinezkickstart@gmail.com', '$2y$10$QE8mBxl.RrNOrqbn63ImguTwwHY1Jxx9MntIl9IfiM7dPlmJNttOO', 5, '8', 2, '2024-01-11 05:34:07', '2024-01-11 08:25:19', 'ACTIVE'),
(254, NULL, NULL, 'Rose Ann Abinan', NULL, 'roseabinan@digits.ph', '$2y$10$p3UCDvH92z8wck3w4CfD9.Eivblm4yLJafomNg./WEdn79UG7xTRO', 19, NULL, NULL, '2024-01-16 06:37:05', '2024-01-16 06:54:07', 'ACTIVE'),
(255, NULL, NULL, 'Raffy Pondales', 'uploads/mrs-avatar.png', 'raffypondales@digits.ph', NULL, 19, NULL, NULL, '2024-01-16 06:39:13', NULL, 'ACTIVE'),
(256, NULL, NULL, 'DIGIMAP USER', NULL, 'digimap@gmail.com', '$2y$10$Gq7hjpjF2D8O0Kt10KCLpuzgRRWUsMbNfYgOgXWSiqE6U9/Nq5cLu', 23, '298', 6, '2024-01-23 06:38:22', '2024-01-23 07:01:03', 'INACTIVE'),
(257, 'PIOLO', 'ARRABIS', 'PIOLO ARRABIS', NULL, 'pioloarrabis@digits.ph', '$2y$10$xm1BeGMSb//xOhuys5brP.cvfshxEkgU5l0gg/StTbrmxlfoqXXai', 1, NULL, NULL, NULL, '2024-01-30 06:11:37', 'ACTIVE'),
(258, 'DIGITAL WALKER', 'DAET', 'DIGITAL WALKER DAET', NULL, 'dwsmdaet@digitalwalker.ph', '$2y$10$xSbauqb42gSoHXXqgNZsUOjnIVyDePmPN6pvnLOI3vZrtLoV1loj2', NULL, NULL, NULL, NULL, NULL, 'ACTIVE'),
(259, 'OPEN SOURCE', 'GREENHILLS', 'OPEN SOURCE GREENHILLS', NULL, 'osgreenhills@beyondthebox.ph', '$2y$10$t8nZk0D7NN5v4T36Gi.ygO0STUMi3PoV2ohogIKrj3I4Aq8byTO1.', NULL, NULL, NULL, NULL, NULL, 'ACTIVE'),
(260, 'GASHAPON', 'GREENHILLS', 'GASHAPON GREENHILLS', NULL, 'greenhills@gashapon.ph', '$2y$10$cXuHiIEWVxVi11wtpK/QyeuneBo/aChbp.bAtGs7kE/dHQqf.KvBy', 2, '300', 1, NULL, '2024-02-21 03:51:25', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `code_counter`
--

CREATE TABLE `code_counter` (
  `id` int(10) UNSIGNED NOT NULL,
  `pullout_refcode` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sts_refcode` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `dr_refcode` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `trip_refcode` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `code_counter`
--

INSERT INTO `code_counter` (`id`, `pullout_refcode`, `sts_refcode`, `dr_refcode`, `trip_refcode`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3519, 1, 1, 16071, 'ACTIVE', 1, NULL, '2021-03-01 06:34:08', '2024-02-23 04:06:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_type`
--

CREATE TABLE `customer_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_type_code` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_type_description` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_type`
--

INSERT INTO `customer_type` (`id`, `customer_type_code`, `customer_type_description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SLE', 'CLEARANCE', 'ACTIVE', 1, NULL, '2021-03-16 06:21:00', NULL, NULL),
(2, 'CON', 'CONSIGNMENT', 'ACTIVE', 1, NULL, '2021-03-16 06:21:35', NULL, NULL),
(3, 'CRP', 'CORPORATE', 'ACTIVE', 1, NULL, '2021-03-16 06:21:50', NULL, NULL),
(4, 'DIG', 'DIGITS', 'ACTIVE', 1, NULL, '2021-03-16 06:21:59', NULL, NULL),
(5, 'DEP', 'DEPARTMENT', 'ACTIVE', 1, NULL, '2021-03-16 06:22:15', NULL, NULL),
(6, 'FRA', 'FRANCHISE', 'ACTIVE', 1, NULL, '2021-03-16 06:22:25', NULL, NULL),
(7, 'ONL', 'ONLINE', 'ACTIVE', 1, NULL, '2021-03-16 06:22:31', NULL, NULL),
(8, 'OUT', 'OURIGHT', 'ACTIVE', 1, NULL, '2021-03-16 06:22:36', NULL, NULL),
(9, 'RTL', 'RETAIL', 'ACTIVE', 1, NULL, '2021-03-16 06:23:21', NULL, NULL),
(10, 'SVC', 'SERVICE CENTER', 'ACTIVE', 1, 1, '2021-03-16 06:23:32', '2021-03-16 07:29:01', NULL),
(11, 'SUP', 'SUPPLIER', 'ACTIVE', 1, NULL, '2021-03-16 06:23:37', NULL, NULL),
(12, 'EEE', 'EMPLOYEE', 'ACTIVE', 1, NULL, '2021-03-16 06:23:44', NULL, NULL),
(13, 'BCI', 'TASTELESS', 'ACTIVE', 1, NULL, '2021-03-16 06:23:51', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `distri_subinventories`
--

CREATE TABLE `distri_subinventories` (
  `id` int(10) UNSIGNED NOT NULL,
  `subinventory` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `distri_subinventories`
--

INSERT INTO `distri_subinventories` (`id`, `subinventory`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'RUSTANS', 'ACTIVE', 1, NULL, '2022-04-13 08:16:51', NULL, NULL),
(2, 'ABENSON', 'ACTIVE', 1, NULL, '2022-04-13 08:16:51', NULL, NULL),
(3, 'ROX', 'ACTIVE', 1, NULL, '2022-04-13 08:16:51', NULL, NULL),
(4, 'PURE MOVE', 'ACTIVE', 1, NULL, '2022-04-13 08:16:51', NULL, NULL),
(5, 'SM DEPT', 'ACTIVE', 1, NULL, '2022-04-13 08:16:51', NULL, NULL),
(6, 'TOBYS', 'ACTIVE', 1, NULL, '2022-06-22 04:38:45', NULL, NULL),
(7, 'I STUDIO', 'ACTIVE', 1, NULL, '2022-07-22 00:49:51', NULL, NULL),
(8, 'SM APP', 'ACTIVE', 1, NULL, '2023-02-15 01:21:37', NULL, NULL),
(9, 'COMMONWLTH', 'ACTIVE', 1, NULL, '2023-03-21 05:37:34', NULL, NULL),
(10, 'DIGIMAP', 'ACTIVE', 1, NULL, '2024-01-30 06:43:39', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ebs_pull`
--

CREATE TABLE `ebs_pull` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `io_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_number` int(10) UNSIGNED DEFAULT NULL,
  `ordered_item` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ordered_item_id` int(10) UNSIGNED DEFAULT NULL,
  `has_serial` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_trade` int(3) NOT NULL DEFAULT '1',
  `ordered_quantity` int(10) UNSIGNED DEFAULT NULL,
  `shipped_quantity` int(10) UNSIGNED DEFAULT NULL,
  `from_locator_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stores_id` int(10) UNSIGNED DEFAULT NULL,
  `locator_id` int(10) UNSIGNED DEFAULT NULL,
  `transaction_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `hand_carrier` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_types_id` int(10) UNSIGNED DEFAULT NULL,
  `dr_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_po` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_instruction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_document_number` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `si_document_number` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ship_confirmed_date` datetime DEFAULT NULL,
  `serial1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial5` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial6` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial7` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial8` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial9` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial10` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial11` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial12` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial13` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial14` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial15` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_pull_date` date DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gis_pulls`
--

CREATE TABLE `gis_pulls` (
  `id` int(10) UNSIGNED NOT NULL,
  `ref_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `quantity_total` int(11) DEFAULT NULL,
  `memo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stores_id` int(11) DEFAULT NULL,
  `location_id_from` int(11) DEFAULT NULL,
  `sub_location_id_from` int(11) DEFAULT NULL,
  `location_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stores_id_destination` int(11) DEFAULT NULL,
  `location_id_to` int(11) DEFAULT NULL,
  `sub_location_id_to` int(11) DEFAULT NULL,
  `location_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `approver_comments` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_by` int(11) DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `rejected_by` int(11) DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `transfer_date` datetime DEFAULT NULL,
  `hand_carrier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_types_id` int(11) DEFAULT NULL,
  `reason_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gis_pulls`
--

INSERT INTO `gis_pulls` (`id`, `ref_number`, `status_id`, `quantity_total`, `memo`, `stores_id`, `location_id_from`, `sub_location_id_from`, `location_from`, `stores_id_destination`, `location_id_to`, `sub_location_id_to`, `location_to`, `approved_by`, `approved_at`, `approver_comments`, `received_by`, `received_at`, `rejected_by`, `rejected_at`, `transfer_date`, `hand_carrier`, `transport_types_id`, `reason_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ST-0000001', 4, 10, NULL, 291, 2, 1, 'GGBOMITSUKOSHI', 300, 3, 2, 'GGBOGREENHILLS', 211, '2024-02-28 14:50:30', NULL, 260, '2024-02-28 14:51:37', NULL, NULL, NULL, NULL, 1, 26, '2024-02-28 06:41:28', '2024-02-28 06:51:37', NULL),
(2, 'ST-0000002', 3, 10, NULL, 291, 2, 1, 'GGBOMITSUKOSHI', 300, 3, 2, 'GGBOGREENHILLS', NULL, NULL, 'comments', NULL, NULL, 211, '2024-02-28 15:10:29', NULL, 'Test', 2, 43, '2024-02-28 07:08:57', '2024-02-28 07:10:29', NULL),
(3, 'ST-0000003', 4, 10, NULL, 291, 2, 1, 'GGBOMITSUKOSHI', 300, 3, 2, 'GGBOGREENHILLS', 211, '2024-02-28 15:18:51', 'Approved', 260, '2024-02-28 15:21:12', NULL, NULL, NULL, NULL, 1, 43, '2024-02-28 07:18:21', '2024-02-28 07:21:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gis_pull_lines`
--

CREATE TABLE `gis_pull_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `gis_pull_id` int(11) DEFAULT NULL,
  `item_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gis_pull_lines`
--

INSERT INTO `gis_pull_lines` (`id`, `gis_pull_id`, `item_code`, `item_description`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, '4549660971436', 'ANIMAL SAUNA 09', '10', '2024-02-28 06:41:28', NULL),
(2, 2, '4549660971436', 'ANIMAL SAUNA 09', '10', '2024-02-28 07:08:57', NULL),
(3, 3, '4549660971436', 'ANIMAL SAUNA 09', '10', '2024-02-28 07:18:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(10) UNSIGNED NOT NULL,
  `bea_item_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `digits_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `upc_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upc_code2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upc_code3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upc_code4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `upc_code5` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_serial` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `allowed_multi_serial` tinyint(4) NOT NULL DEFAULT '0',
  `distri_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `digimap_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `commonwlth_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sm_app_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `i_studio_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `tobys_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rustans_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `abenson_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rox_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `pure_move_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `sm_dept_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `lazada_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `shopee_reserve_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240226_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240225_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240224_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240223_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240222_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240221_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240220_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240219_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240218_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240217_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240216_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240215_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240214_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240213_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240212_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240211_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240210_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240210_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240209_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240208_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240207_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240206_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240205_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240204_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240203_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240202_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240201_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_digimap_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240131_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240130_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240129_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_tobys_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_sm_dept_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_sm_app_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_rustans_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_rox_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_pure_move_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_i_studio_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_commonwlth_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_abenson_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `20240128_reserved_qty` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `store_cost` decimal(16,2) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `location_description` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Metro Manila', 'ACTIVE', 1, 1, '2022-04-25 10:25:32', '2022-04-25 10:26:12', NULL),
(2, 'Outside Metro Manila', 'ACTIVE', 1, 1, '2022-04-25 10:25:39', '2022-04-25 10:26:02', NULL);

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
(1, '2016_08_07_145904_add_table_cms_apicustom', 1),
(2, '2016_08_07_150834_add_table_cms_dashboard', 1),
(3, '2016_08_07_151210_add_table_cms_logs', 1),
(4, '2016_08_07_151211_add_details_cms_logs', 1),
(5, '2016_08_07_152014_add_table_cms_privileges', 1),
(6, '2016_08_07_152214_add_table_cms_privileges_roles', 1),
(7, '2016_08_07_152320_add_table_cms_settings', 1),
(8, '2016_08_07_152421_add_table_cms_users', 1),
(9, '2016_08_07_154624_add_table_cms_menus_privileges', 1),
(10, '2016_08_07_154624_add_table_cms_moduls', 1),
(11, '2016_08_17_225409_add_status_cms_users', 1),
(12, '2016_08_20_125418_add_table_cms_notifications', 1),
(13, '2016_09_04_033706_add_table_cms_email_queues', 1),
(14, '2016_09_16_035347_add_group_setting', 1),
(15, '2016_09_16_045425_add_label_setting', 1),
(16, '2016_09_17_104728_create_nullable_cms_apicustom', 1),
(17, '2016_10_01_141740_add_method_type_apicustom', 1),
(18, '2016_10_01_141846_add_parameters_apicustom', 1),
(19, '2016_10_01_141934_add_responses_apicustom', 1),
(20, '2016_10_01_144826_add_table_apikey', 1),
(21, '2016_11_14_141657_create_cms_menus', 1),
(22, '2016_11_15_132350_create_cms_email_templates', 1),
(23, '2016_11_15_190410_create_cms_statistics', 1),
(24, '2016_11_17_102740_create_cms_statistic_components', 1),
(25, '2017_06_06_164501_add_deleted_at_cms_moduls', 1),
(26, '2019_08_22_164428_create_deliveries_table', 2),
(27, '2019_08_27_133108_create_stock_statuses_table', 2),
(28, '2019_09_04_161320_create_purchase_order_alls_table', 2),
(29, '2020_10_28_091532_create_ebs_pull_table', 3),
(30, '2020_10_29_124431_create_pos_pull_table', 4),
(31, '2020_11_03_115222_create_st_status_table', 4),
(32, '2020_11_11_132656_add_document_number_column_to_ebs_pull_table', 5),
(34, '2020_11_11_150339_add_st_document_number_column_to_ebs_pull_table', 6),
(35, '2020_11_11_150440_add_st_document_number_column_to_pos_pull_table', 6),
(36, '2020_11_13_102117_create_store_table', 7),
(37, '2020_12_03_162824_create_serials_table', 8),
(38, '2020_12_03_174028_add_locator_id_to_ebs_pull_table', 9),
(39, '2020_12_04_114359_add_has_serial_column_to_ebs_pull_table', 10),
(40, '2020_12_10_103737_create_item_table', 11),
(41, '2020_12_15_114953_add_item_id_to_ebs_pull_table', 12),
(42, '2020_12_15_132144_add_doo_subinventory_to_stores_table', 13),
(43, '2020_12_18_144842_add_store_column_to_cms_users_table', 14),
(44, '2020_12_18_155444_add_transit_warehouse_to_stores_table', 15),
(45, '2020_12_18_184920_add_price_column_to_items_table', 16),
(46, '2020_12_22_134220_add_status_column_to_pos_pull_table', 17),
(47, '2020_12_22_181841_add_has_serial_column_to_pos_pull_table', 18),
(48, '2020_12_22_200626_add_pos_warehouse_branch_column_to_stores_table', 19),
(49, '2020_12_23_160208_add_pos_warehouse_rma_to_stores_table', 20),
(50, '2020_12_28_184419_create_pullout_table', 21),
(51, '2020_12_28_184915_add_pullout_id_to_serials_table', 22),
(52, '2021_01_04_143958_add_transaction_type_to_pullout_table', 23),
(54, '2021_01_05_135603_create_reason_table', 24),
(56, '2021_01_05_135702_create_transaction_type_table', 25),
(58, '2021_01_05_135017_add_reason_id_to_pullout_table', 26),
(59, '2021_01_05_160229_create_channel_table', 27),
(60, '2021_01_05_160329_add_channel_id_to_cms_users_table', 27),
(61, '2021_01_05_161116_add_org_subinventory_to_stores_table', 28),
(62, '2021_01_12_165308_add_bea_so_reason_column_to_reason_table', 29),
(63, '2021_01_23_143525_add_reason_id_to_pos_pull_table', 30),
(64, '2021_02_01_173259_add_transport_type_id_column_to_ebs_pull_table', 31),
(65, '2021_02_01_173629_create_transport_types_table', 31),
(66, '2021_02_02_110946_add_hand_carrier_column_to_ebs_pull_table', 32),
(67, '2021_02_02_150650_add_transport_type_id_column_to_pos_pull_table', 33),
(68, '2021_02_02_150806_add_hand_carrier_column_to_pos_pull_table', 33),
(69, '2021_02_02_152012_add_schedule_date_column_to_pos_pull_table', 34),
(70, '2021_02_02_152029_add_schedule_date_column_to_ebs_pull_table', 34),
(74, '2021_02_02_175825_add_schedule_date_column_to_pullout_table', 35),
(75, '2021_02_02_175926_add_transport_type_id_column_to_pullout_table', 35),
(76, '2021_02_02_175951_add_hand_carrier_column_to_pullout_table', 36),
(77, '2021_02_04_103204_create_approval_matrix_table', 37),
(78, '2021_02_04_114724_add_channels_id_to_stores_table', 38),
(79, '2021_02_04_145511_add_stores_id_to_pullout_table', 39),
(80, '2021_02_04_145533_add_stores_id_to_pos_pull_table', 39),
(81, '2021_02_05_100903_add_review_column_to_pos_pull_table', 40),
(82, '2021_02_05_101007_add_review_column_to_pullout_table', 40),
(83, '2021_03_01_120622_add_received_by_at_column_in_pullout_table', 41),
(84, '2021_03_01_141834_create_code_counter_table', 42),
(85, '2020_11_11_133043_add_document_number_column_to_pos_pull_table', 43),
(86, '2021_03_05_170553_add_customer_po_column_to_ebs_pull_table', 44),
(87, '2021_03_10_170741_create_customer_type_table', 45),
(88, '2021_03_10_180042_add_transit_rma_branch_to_stores_table', 45),
(89, '2021_03_11_145445_add_sit_subinventory_to_stores_table', 46),
(90, '2021_03_11_163136_add_channel_id_column_to_pullout_table', 47),
(91, '2021_03_16_140051_add_customer_type_id_column_to_stores_table', 48),
(92, '2021_03_16_165243_add_sts_group_column_to_stores_table', 49),
(93, '2021_03_19_103412_add_stores_id_destination_to_pos_pull_table', 50),
(94, '2021_05_06_175900_add_stores_id_to_ebs_pull_table', 51),
(95, '2021_05_06_180025_add_stores_id_destination_to_pullout_table', 51),
(96, '2021_09_15_155252_create_status_workflows_table', 52),
(97, '2021_09_29_170438_add_step_column_to_pullout_table', 53),
(98, '2021_09_29_170523_add_step_column_to_pos_pull_table', 53),
(99, '2021_09_30_005928_add_reservation_qty_to_pullout_table', 54),
(100, '2021_09_30_010214_add_reservation_qty_to_items_table', 54),
(101, '2021_10_11_093052_create_problems_table', 55),
(102, '2021_10_11_103927_add_problem_columns_to_pullout_table', 56),
(103, '2021_12_02_113829_add_logistics_info_in_pullout_table', 57),
(104, '2021_12_02_133416_add_logistics_info_in_pos_pull_table', 58),
(105, '2022_02_18_152450_add_purchase_date_to_pullout_table', 59),
(106, '2022_03_23_170827_add_distri_reserve_qty_to_items_table', 60),
(107, '2022_03_31_020212_add_other_distri_reserve_qty_to_items_table', 61),
(108, '2022_03_31_085947_create_trip_tickets_table', 62),
(109, '2022_04_06_170018_add_trip_ref_to_code_counter_table', 62),
(110, '2022_04_07_090956_add_created_updated_by_trip_ticket_table', 63),
(111, '2022_04_07_093023_add_dr_qty_to_trip_ticket_table', 63),
(112, '2022_04_13_125834_add_additional_distri_subinventory_to_items_table', 64),
(113, '2022_04_13_160155_create_distri_subinventories_table', 65),
(114, '2022_04_18_092719_add_other_details_to_trip_ticket_table', 66),
(115, '2022_04_19_100327_add_is_released_to_trip_ticket_table', 67),
(116, '2022_04_19_104922_add_ref_type_to_trip_ticket_table', 67),
(117, '2022_04_19_172617_create_backload_reasons_table', 68),
(118, '2022_04_19_181250_add_backload_reason_to_trip_ticket_table', 69),
(119, '2022_04_25_152556_add_locations_id_to_stores_table', 70),
(120, '2022_04_25_152630_create_locations_table', 70),
(121, '2022_04_26_100215_add_store_logistics_details_to_trip_ticket_table', 71),
(122, '2022_04_27_114849_add_status_column_to_trip_ticket_table', 72),
(123, '2022_04_27_115118_create_trip_ticket_statuses_table', 72),
(124, '2022_04_28_184411_create_pullout_receivings_table', 73),
(125, '2022_04_29_103817_create_pullout_receiving_lines_table', 73),
(126, '2022_04_29_105333_create_pullout_receiving_serials_table', 73),
(127, '2022_04_29_111726_create_cancel_reasons_table', 74),
(128, '2023_01_25_141209_add_trip_ticket_lines_table', 75),
(129, '2023_01_25_142243_add_backload_reason_to_trip_ticket_lines_table', 76),
(130, '2023_02_03_105629_add_name_details_to_cms_users_table', 77),
(144, '2024_02_27_082051_create_gis_pulls_table', 78),
(145, '2024_02_27_124317_create_gis_pull_lines_table', 78);

-- --------------------------------------------------------

--
-- Table structure for table `pos_pull`
--

CREATE TABLE `pos_pull` (
  `id` int(10) UNSIGNED NOT NULL,
  `step` int(10) UNSIGNED DEFAULT NULL,
  `received_st_date` date DEFAULT NULL,
  `received_st_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_document_number` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `memo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transfer_date` date DEFAULT NULL,
  `hand_carrier` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_types_id` int(10) UNSIGNED DEFAULT NULL,
  `reason_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wh_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wh_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_id` int(10) UNSIGNED DEFAULT NULL,
  `stores_id` int(10) UNSIGNED DEFAULT NULL,
  `stores_id_destination` int(10) UNSIGNED DEFAULT NULL,
  `item_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_serial` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `serial` longtext COLLATE utf8mb4_unicode_ci,
  `st_status_id` int(10) DEFAULT NULL,
  `scheduled_by` int(10) UNSIGNED DEFAULT NULL,
  `scheduled_at` date DEFAULT NULL,
  `received_by` int(10) UNSIGNED DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `log_printed_at` datetime DEFAULT NULL,
  `log_printed_by` int(10) UNSIGNED DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejected_by` int(10) UNSIGNED DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

CREATE TABLE `problems` (
  `id` int(10) UNSIGNED NOT NULL,
  `problem_details` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`id`, `problem_details`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'AESTHETICS - DISCOLORATION', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(2, 'AESTHETICS - MISSING PARTS / COMPONENTS', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(3, 'AESTHETICS - SCRATCHES / DENTS', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(4, 'AESTHETICS - WEAK ADHESIVE', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(5, 'BATTERY - BLOATED', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(6, 'BATTERY - EASILY DRAINED', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(7, 'BATTERY - NOT CHARGING', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(8, 'BATTERY - OVERHEATING', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(9, 'CAMERA - FOCUS ISSUES', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(10, 'CONNECTIVITY - SIGNAL', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(11, 'CONNECTIVITY - SYNC', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(12, 'CONNECTIVITY - WIFI', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(13, 'DISPLAY - CRACKED SCREEN', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(14, 'DISPLAY - FLICKERING SCREEN', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(15, 'DISPLAY - TOUCH MALFUNCTIONING', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(16, 'INPUT - BUTTON MALFUNCTIONING', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(17, 'MICROPHONE - NO SOUND INPUT', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(18, 'OTHERS', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(19, 'POWER - INTERMITTENT CHARGING', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(20, 'POWER - NOT CHARGING', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(21, 'POWER - NOT OPENING', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(22, 'SCREEN - DEAD PIXELS', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(23, 'SOFTWARE - HANGING', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(24, 'SOFTWARE - VIRUS', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(25, 'SOUND - DISTORTED/STATIC', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL),
(26, 'SOUND - NO VOLUME', 'ACTIVE', 1, NULL, '2021-10-11 01:42:29', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pullout`
--

CREATE TABLE `pullout` (
  `id` int(10) UNSIGNED NOT NULL,
  `step` int(10) UNSIGNED DEFAULT NULL,
  `received_st_date` date DEFAULT NULL,
  `received_st_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sor_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `st_document_number` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `si_document_number` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `memo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pullout_date` date DEFAULT NULL,
  `picklist_date` date DEFAULT NULL,
  `pickconfirm_date` date DEFAULT NULL,
  `hand_carrier` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_types_id` int(10) UNSIGNED DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `schedule_by` int(10) UNSIGNED DEFAULT NULL,
  `log_printed_at` datetime DEFAULT NULL,
  `log_printed_by` int(10) UNSIGNED DEFAULT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `problems` text COLLATE utf8mb4_unicode_ci,
  `problem_detail` text COLLATE utf8mb4_unicode_ci,
  `wh_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wh_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stores_id` int(10) UNSIGNED DEFAULT NULL,
  `stores_id_destination` int(10) UNSIGNED DEFAULT NULL,
  `channel_id` int(10) UNSIGNED DEFAULT NULL,
  `item_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(10) DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_serial` int(10) UNSIGNED DEFAULT NULL,
  `serial` longtext COLLATE utf8mb4_unicode_ci,
  `st_status_id` int(10) UNSIGNED DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_by` int(10) UNSIGNED DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `rejected_by` int(10) UNSIGNED DEFAULT NULL,
  `rejected_at` datetime DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pullout_receivings`
--

CREATE TABLE `pullout_receivings` (
  `id` int(10) UNSIGNED NOT NULL,
  `wrs_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sor_mor_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stores_id` int(10) UNSIGNED DEFAULT NULL,
  `reason_id` int(10) UNSIGNED DEFAULT NULL,
  `pullout_from` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pullout_receiving_lines`
--

CREATE TABLE `pullout_receiving_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `pullout_receivings_id` int(10) UNSIGNED DEFAULT NULL,
  `item_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(10) UNSIGNED DEFAULT NULL,
  `received_quantity` int(10) UNSIGNED DEFAULT NULL,
  `serial` longtext COLLATE utf8mb4_unicode_ci,
  `has_serial` int(10) UNSIGNED DEFAULT NULL,
  `received_serial` longtext COLLATE utf8mb4_unicode_ci,
  `cancel_quantity` int(10) UNSIGNED DEFAULT NULL,
  `cancel_reasons` longtext COLLATE utf8mb4_unicode_ci,
  `cancel_reason_details` longtext COLLATE utf8mb4_unicode_ci,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pullout_receiving_serials`
--

CREATE TABLE `pullout_receiving_serials` (
  `id` int(10) UNSIGNED NOT NULL,
  `pullout_receiving_lines_id` int(10) UNSIGNED DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('MISMATCHED','RECEIVED') COLLATE utf8mb4_unicode_ci DEFAULT 'RECEIVED',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reason`
--

CREATE TABLE `reason` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_type_id` int(10) UNSIGNED DEFAULT NULL,
  `bea_mo_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bea_so_reason` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pullout_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reason`
--

INSERT INTO `reason` (`id`, `transaction_type_id`, `bea_mo_reason`, `bea_so_reason`, `pullout_reason`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '166', 'R-05', 'DEFECTIVE RETURN', 'INACTIVE', 1, 1, '2021-01-04 23:48:33', '2022-02-21 18:08:24', NULL),
(2, 2, '181', 'R-21', 'SUPPLIER PRODUCT RECALL', 'ACTIVE', 1, 1, '2021-01-04 23:48:53', '2021-10-10 19:51:24', NULL),
(3, 2, '167', 'R-06', 'DEFECTIVE STORE STOCK', 'ACTIVE', 1, 1, '2021-01-04 23:49:02', '2021-10-10 19:50:52', NULL),
(4, 2, '169', 'R-08', 'DIGITS PRODUCT RECALL', 'ACTIVE', 1, 1, '2021-01-04 23:49:12', '2021-10-10 19:51:08', NULL),
(5, 1, '163', 'R-02', 'BACKLOAD/WRONG DELIVERY', 'ACTIVE', 1, 1, '2021-01-04 23:50:07', '2021-10-10 19:45:30', NULL),
(6, 1, '162', 'R-01', 'ASSET REQUISITION', 'ACTIVE', 1, 1, '2021-01-04 23:50:31', '2021-10-10 19:45:11', NULL),
(7, 1, '164', 'R-03', 'CUSTOMER REPLACEMENT', 'ACTIVE', 1, 1, '2021-01-04 23:50:46', '2021-10-10 19:45:44', NULL),
(8, 1, '168', 'R-07', 'DEMO RECLASS', 'ACTIVE', 1, 1, '2021-01-04 23:50:59', '2021-10-10 19:46:13', NULL),
(9, 1, '170', 'R-09', 'DIS-CON REPLACEMENT', 'ACTIVE', 1, 1, '2021-01-04 23:51:11', '2021-10-10 19:46:28', NULL),
(10, 1, '171', 'R-10', 'DIS-CON RETURN', 'ACTIVE', 1, 1, '2021-01-04 23:51:23', '2021-10-10 19:46:43', NULL),
(11, 1, '172', 'R-11', 'DIS-OUT REPLACEMENT', 'ACTIVE', 1, 1, '2021-01-04 23:51:33', '2021-10-10 19:46:56', NULL),
(12, 1, '173', 'R-12', 'DISTRIBUTION REQUEST', 'ACTIVE', 1, 1, '2021-01-04 23:51:42', '2021-10-10 19:47:10', NULL),
(13, 1, '174', 'R-14', 'FRANCHISE RETURN', 'ACTIVE', 1, 1, '2021-01-04 23:51:53', '2021-10-10 19:47:47', NULL),
(14, 1, '175', 'R-15', 'HQ PULL-OUT', 'ACTIVE', 1, 1, '2021-01-04 23:52:03', '2021-10-10 19:48:06', NULL),
(15, 1, '176', 'R-16', 'MARKETING', 'ACTIVE', 1, 1, '2021-01-04 23:52:24', '2021-10-10 19:48:19', NULL),
(16, 1, '177', 'R-17', 'OPEN BOX SALE', 'ACTIVE', 1, 1, '2021-01-04 23:52:55', '2021-10-10 19:48:34', NULL),
(17, 1, '178', 'R-18', 'REORDER', 'ACTIVE', 1, 1, '2021-01-04 23:53:04', '2021-10-10 19:48:54', NULL),
(18, 1, '179', 'R-19', 'SALE EVENT REQUEST', 'ACTIVE', 1, 1, '2021-01-04 23:53:18', '2021-10-10 19:49:12', NULL),
(19, 1, '180', 'R-20', 'SALE EVENT RETURN', 'ACTIVE', 1, 1, '2021-01-04 23:53:29', '2021-10-10 19:49:38', NULL),
(20, 1, '182', 'R-22', 'TOTAL PULL-OUT', 'ACTIVE', 1, 1, '2021-01-04 23:53:43', '2021-10-10 19:49:54', NULL),
(21, 1, '165', 'R-04', 'CUSTOMER REQUEST', 'ACTIVE', 1, 1, '2021-01-22 03:07:15', '2021-10-10 19:45:57', NULL),
(22, 1, '142', 'R-13', 'FRANCHISE REQUEST', 'ACTIVE', 1, 1, '2021-01-28 10:48:51', '2021-10-10 19:47:30', NULL),
(23, 3, '176', 'R-16', 'MARKETING', 'ACTIVE', 1, 1, '2021-02-28 22:03:57', '2021-10-10 19:53:00', NULL),
(24, 4, NULL, NULL, 'BACKLOAD/WRONG DELIVERY', 'ACTIVE', 1, NULL, '2021-10-05 19:06:45', NULL, NULL),
(25, 4, NULL, NULL, 'CUSTOMER REPLACEMENT', 'ACTIVE', 1, NULL, '2021-10-05 19:07:16', NULL, NULL),
(26, 4, NULL, NULL, 'CUSTOMER REQUEST', 'ACTIVE', 1, NULL, '2021-10-05 19:07:51', NULL, NULL),
(27, 4, NULL, NULL, 'ECOMM REQUEST', 'INACTIVE', 1, 1, '2021-10-05 19:08:57', '2021-12-06 20:24:48', NULL),
(28, 4, NULL, NULL, 'FRANCHISE REQUEST', 'ACTIVE', 1, NULL, '2021-10-05 19:09:26', NULL, NULL),
(29, 4, NULL, NULL, 'MARKETING', 'ACTIVE', 1, NULL, '2021-10-05 19:09:50', NULL, NULL),
(30, 4, NULL, NULL, 'OPEN BOX SALE', 'ACTIVE', 1, NULL, '2021-10-05 19:10:14', NULL, NULL),
(31, 4, NULL, NULL, 'REORDER', 'ACTIVE', 1, NULL, '2021-10-05 19:10:33', NULL, NULL),
(32, 4, NULL, NULL, 'SALE EVENT REQUEST', 'ACTIVE', 1, NULL, '2021-10-05 19:10:52', NULL, NULL),
(33, 4, NULL, NULL, 'DEFECTIVE STORE STOCK', 'ACTIVE', 1, NULL, '2021-10-05 19:11:54', NULL, NULL),
(34, 4, NULL, NULL, 'DIGITS PRODUCT RECALL', 'ACTIVE', 1, NULL, '2021-10-05 19:12:14', NULL, NULL),
(35, 4, NULL, NULL, 'SUPPLIER PRODUCT RECALL', 'ACTIVE', 1, NULL, '2021-10-05 19:12:35', NULL, NULL),
(36, 1, '122', 'R-24', 'ECOMM REQUEST', 'INACTIVE', 1, 1, '2021-10-10 19:43:40', '2021-12-06 20:29:26', NULL),
(37, 1, '123', 'R-25', 'ECOMM RETURN', 'ACTIVE', 1, NULL, '2021-10-10 19:43:56', NULL, NULL),
(38, 1, '124', 'R-26', 'RETAIL REQUEST', 'ACTIVE', 1, NULL, '2021-10-10 19:44:42', NULL, NULL),
(39, 4, NULL, NULL, 'ECOMM REQUEST FBV', 'ACTIVE', 1, NULL, '2021-12-06 20:24:35', NULL, NULL),
(40, 4, NULL, NULL, 'ECOMM REQUEST FBD', 'ACTIVE', 1, NULL, '2021-12-06 20:24:39', NULL, NULL),
(41, 1, '203', 'R-28', 'ECOMM REQUEST FBV', 'ACTIVE', 1, NULL, '2021-12-06 20:29:18', NULL, NULL),
(42, 1, '202', 'R-27', 'ECOMM REQUEST FBD', 'ACTIVE', 1, NULL, '2021-12-06 20:29:19', NULL, NULL),
(43, 4, NULL, NULL, 'STOCK REALLOCATION', 'ACTIVE', 1, NULL, '2022-02-17 00:10:38', NULL, NULL),
(44, 1, '222', 'R-29', 'EMPLOYEE REQUEST', 'ACTIVE', 1, NULL, '2022-02-17 00:45:40', NULL, NULL),
(45, 1, '282', 'R-30', 'DIS-OUT EXCHANGE', 'ACTIVE', 1, NULL, '2022-10-05 18:34:29', NULL, NULL),
(46, 1, '322', 'R-31', 'MADNESS', 'ACTIVE', 1, NULL, '2023-03-14 22:41:19', NULL, NULL),
(47, 1, '366', 'R-32', 'STOCK REALLOCATION', 'ACTIVE', 1, NULL, '2023-05-25 18:49:03', NULL, NULL),
(48, 1, '369', 'R-33', 'DEMO - SALE', 'ACTIVE', 1, NULL, '2023-05-29 18:57:15', NULL, NULL),
(49, 4, NULL, NULL, 'DEMO - SALE', 'ACTIVE', 1, NULL, '2023-05-30 17:03:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `serials`
--

CREATE TABLE `serials` (
  `id` int(10) UNSIGNED NOT NULL,
  `ebs_pull_id` int(10) UNSIGNED DEFAULT NULL,
  `pos_pull_id` int(10) UNSIGNED DEFAULT NULL,
  `pullout_id` int(10) UNSIGNED DEFAULT NULL,
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `counter` int(10) NOT NULL DEFAULT '0',
  `status` enum('PENDING','FAILED','RECEIVED') COLLATE utf8mb4_unicode_ci DEFAULT 'PENDING',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `status_workflows`
--

CREATE TABLE `status_workflows` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_types_id` int(10) UNSIGNED DEFAULT NULL,
  `workflow_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_step` int(10) UNSIGNED DEFAULT NULL,
  `next_step` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `status_workflows`
--

INSERT INTO `status_workflows` (`id`, `transaction_type`, `channel_id`, `customer_type`, `transport_types_id`, `workflow_status`, `current_step`, `next_step`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'STW', 1, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', NULL, NULL, '2021-08-16 02:15:34', NULL, NULL),
(2, 'STW', 1, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', NULL, NULL, '2021-08-16 02:16:01', NULL, NULL),
(3, 'STW', 1, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2021-08-16 02:18:02', NULL, NULL),
(4, 'STW', 1, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2021-08-16 02:18:19', NULL, NULL),
(5, 'STW', 1, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2021-08-16 02:19:15', NULL, NULL),
(6, 'STW', 4, 'FBD', NULL, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-08-16 11:39:59', NULL, NULL),
(7, 'STW', 4, 'FBD', NULL, 'FOR PICKLIST', 2, 4, 'ACTIVE', 1, 1, '2021-08-16 11:40:25', '2021-09-07 11:01:32', NULL),
(9, 'STW', 4, 'FBD', NULL, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, 1, '2021-08-16 11:41:38', '2021-08-16 11:42:01', NULL),
(10, 'STW', 4, 'FBD', NULL, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2021-08-16 11:42:18', NULL, NULL),
(16, 'STW', 4, 'FBV', 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-08-17 12:43:26', NULL, NULL),
(18, 'STW', 4, 'FBV', 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, 1, '2021-08-17 12:44:27', '2021-09-07 15:07:24', NULL),
(19, 'STW', 4, 'FBV', 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2021-08-17 12:44:45', NULL, NULL),
(20, 'STW', 4, 'FBV', 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2021-08-17 12:45:07', NULL, NULL),
(26, 'RMA', 4, 'FBV', 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-08-17 13:47:21', NULL, NULL),
(28, 'RMA', 4, 'FBV', 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, 1, '2021-08-17 13:49:05', '2021-09-08 09:59:54', NULL),
(29, 'RMA', 4, 'FBV', 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2021-08-17 13:49:19', NULL, NULL),
(30, 'RMA', 4, 'FBV', 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2021-08-17 13:49:33', NULL, NULL),
(31, 'STW', 4, 'FBV', 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-07 15:07:43', NULL, NULL),
(32, 'RMA', 4, 'FBV', 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-08 10:00:15', NULL, NULL),
(33, 'STW', 1, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-09-13 09:59:56', NULL, NULL),
(34, 'STW', 1, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-13 10:00:10', NULL, NULL),
(35, 'STW', 1, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2021-09-13 10:00:31', NULL, NULL),
(36, 'STW', 1, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2021-09-13 10:00:51', NULL, NULL),
(37, 'RMA', 1, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-09-13 14:02:50', NULL, NULL),
(38, 'RMA', 1, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-13 14:03:06', NULL, NULL),
(39, 'RMA', 1, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2021-09-13 14:03:21', NULL, NULL),
(40, 'RMA', 1, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2021-09-13 14:03:36', NULL, NULL),
(41, 'RMA', 1, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2021-09-13 14:03:50', NULL, NULL),
(42, 'RMA', 1, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-09-13 14:04:24', NULL, NULL),
(43, 'RMA', 1, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-13 14:04:38', NULL, NULL),
(44, 'RMA', 1, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2021-09-13 14:04:53', NULL, NULL),
(45, 'RMA', 1, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2021-09-13 14:05:07', NULL, NULL),
(46, 'STW', 2, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-09-14 05:05:47', NULL, NULL),
(47, 'STW', 2, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-14 05:06:13', NULL, NULL),
(48, 'STW', 2, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2021-09-14 05:06:31', NULL, NULL),
(49, 'STW', 2, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2021-09-14 05:07:01', NULL, NULL),
(50, 'STW', 2, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2021-09-14 05:07:17', NULL, NULL),
(51, 'STW', 2, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-09-14 05:38:57', NULL, NULL),
(52, 'STW', 2, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-14 05:39:19', NULL, NULL),
(53, 'STW', 2, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2021-09-14 05:39:34', NULL, NULL),
(54, 'STW', 2, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2021-09-14 05:39:49', NULL, NULL),
(55, 'RMA', 2, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-09-14 05:42:23', NULL, NULL),
(56, 'RMA', 2, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-14 05:42:41', NULL, NULL),
(57, 'RMA', 2, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2021-09-14 05:42:54', NULL, NULL),
(58, 'RMA', 2, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2021-09-14 05:43:06', NULL, NULL),
(59, 'RMA', 2, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2021-09-14 05:43:20', NULL, NULL),
(60, 'RMA', 2, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-09-14 05:43:35', NULL, NULL),
(61, 'RMA', 2, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-14 05:43:52', NULL, NULL),
(62, 'RMA', 2, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2021-09-14 05:44:05', NULL, NULL),
(63, 'RMA', 2, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2021-09-14 05:44:17', NULL, NULL),
(64, 'RMA', 4, 'FBD', NULL, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2021-09-22 17:54:56', NULL, NULL),
(65, 'RMA', 4, 'FBD', NULL, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2021-09-22 17:55:15', NULL, NULL),
(66, 'RMA', 4, 'FBD', NULL, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2021-09-22 17:55:46', NULL, NULL),
(67, 'RMA', 4, 'FBD', NULL, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2021-09-22 17:56:04', NULL, NULL),
(68, 'STW', 6, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-01 17:20:49', NULL, NULL),
(69, 'STW', 6, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-01 17:21:05', NULL, NULL),
(70, 'STW', 6, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2022-03-01 17:21:20', NULL, NULL),
(71, 'STW', 6, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2022-03-01 17:21:34', NULL, NULL),
(72, 'STW', 6, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2022-03-01 17:21:46', NULL, NULL),
(73, 'STW', 6, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-01 17:24:10', NULL, NULL),
(74, 'STW', 6, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-01 17:24:21', NULL, NULL),
(75, 'STW', 6, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2022-03-01 17:24:35', NULL, NULL),
(76, 'STW', 6, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2022-03-01 17:24:51', NULL, NULL),
(77, 'RMA', 6, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:43:28', NULL, NULL),
(78, 'RMA', 6, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:43:53', NULL, NULL),
(79, 'RMA', 6, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:44:14', NULL, NULL),
(80, 'RMA', 6, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:44:40', NULL, NULL),
(81, 'RMA', 6, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2022-03-06 13:44:58', NULL, NULL),
(82, 'RMA', 6, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:46:15', NULL, NULL),
(83, 'RMA', 6, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:46:29', NULL, NULL),
(84, 'RMA', 6, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:46:49', NULL, NULL),
(85, 'RMA', 6, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(86, 'STW', 10, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(87, 'STW', 10, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(88, 'STW', 10, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(89, 'STW', 10, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(90, 'STW', 10, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(91, 'STW', 10, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(92, 'STW', 10, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(93, 'STW', 10, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(94, 'STW', 10, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(95, 'RMA', 10, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(96, 'RMA', 10, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(97, 'RMA', 10, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(98, 'RMA', 10, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(99, 'RMA', 10, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(100, 'RMA', 10, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(101, 'RMA', 10, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(102, 'RMA', 10, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(103, 'RMA', 10, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(104, 'STW', 11, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(105, 'STW', 11, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(106, 'STW', 11, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(107, 'STW', 11, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(108, 'STW', 11, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(109, 'STW', 11, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(110, 'STW', 11, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(111, 'STW', 11, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(112, 'STW', 11, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(113, 'RMA', 11, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(114, 'RMA', 11, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(115, 'RMA', 11, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(116, 'RMA', 11, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(117, 'RMA', 11, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(118, 'RMA', 11, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(119, 'RMA', 11, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(120, 'RMA', 11, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(121, 'RMA', 11, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2022-03-06 13:47:05', NULL, NULL),
(122, 'RMA', 7, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-05-15 19:08:59', NULL, NULL),
(123, 'RMA', 7, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-05-15 19:10:57', NULL, NULL),
(124, 'RMA', 7, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2022-05-15 19:11:26', NULL, NULL),
(125, 'RMA', 7, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2022-05-15 19:11:40', NULL, NULL),
(126, 'RMA', 7, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2022-05-15 19:11:55', NULL, NULL),
(127, 'RMA', 7, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-05-15 19:26:35', NULL, NULL),
(128, 'RMA', 7, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-05-15 19:26:51', NULL, NULL),
(129, 'RMA', 7, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2022-05-15 19:27:05', NULL, NULL),
(130, 'RMA', 7, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2022-05-15 19:27:20', NULL, NULL),
(131, 'STW', 7, NULL, 1, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-05-15 19:29:02', NULL, NULL),
(132, 'STW', 7, NULL, 1, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-05-15 19:29:15', NULL, NULL),
(133, 'STW', 7, NULL, 1, 'FOR SCHEDULE', 3, 4, 'ACTIVE', 1, NULL, '2022-05-15 19:29:29', NULL, NULL),
(134, 'STW', 7, NULL, 1, 'FOR RECEIVING', 4, 5, 'ACTIVE', 1, NULL, '2022-05-15 19:29:44', NULL, NULL),
(135, 'STW', 7, NULL, 1, 'RECEIVED', 5, 6, 'ACTIVE', 1, NULL, '2022-05-15 19:30:00', NULL, NULL),
(136, 'STW', 7, NULL, 2, 'PENDING', 1, 2, 'ACTIVE', 1, NULL, '2022-05-15 19:30:42', NULL, NULL),
(137, 'STW', 7, NULL, 2, 'FOR PROCESSING', 2, 3, 'ACTIVE', 1, NULL, '2022-05-15 19:31:02', NULL, NULL),
(138, 'STW', 7, NULL, 2, 'FOR RECEIVING', 3, 4, 'ACTIVE', 1, NULL, '2022-05-15 19:33:05', NULL, NULL),
(139, 'STW', 7, NULL, 2, 'RECEIVED', 4, 5, 'ACTIVE', 1, NULL, '2022-05-15 19:33:23', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock_statuses`
--

CREATE TABLE `stock_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(10) UNSIGNED NOT NULL,
  `bea_so_store_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bea_mo_store_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_warehouse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_warehouse_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_warehouse_transit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_warehouse_transit_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_warehouse_rma` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_warehouse_rma_branch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pos_warehouse_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doo_subinventory` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sit_subinventory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_subinventory` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `channel_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_type_id` int(10) UNSIGNED DEFAULT NULL,
  `locations_id` int(10) UNSIGNED DEFAULT NULL,
  `sts_group` int(10) UNSIGNED DEFAULT NULL,
  `allowed_bulk_receiving` tinyint(4) NOT NULL DEFAULT '0',
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `bea_so_store_name`, `bea_mo_store_name`, `pos_warehouse`, `pos_warehouse_branch`, `pos_warehouse_transit`, `pos_warehouse_transit_branch`, `pos_warehouse_rma`, `pos_warehouse_rma_branch`, `pos_warehouse_name`, `doo_subinventory`, `sit_subinventory`, `org_subinventory`, `channel_id`, `customer_type_id`, `locations_id`, `sts_group`, `allowed_bulk_receiving`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BASEUS.AYALA.BONIFACIO HIGH STREET.FRA', 'BASEUS AYALA BONIFACIO HIGH STREET FRA', 'BASEUSBGC', 'BSBGC', 'TBASEUSBGC', 'TBASEUSBGC', 'BASEUSBGR', 'RBASEUSBGC', 'BASEUS BGC', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 2, 0, 'INACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-12-02 05:37:37', NULL),
(2, 'BASEUS.AYALA.TRINOMA.RTL', 'BASEUS AYALA TRINOMA RTL', 'GBASEUSTRINOMA', 'BASEUSTRINOMA', 'TBASEUSTRINOMA', NULL, 'BSTRINOMARM', NULL, 'BASEUS TRINOMA', 'RETAIL', NULL, 'RETAIL', 1, NULL, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-10-10 22:07:54', NULL),
(3, 'BASEUS.AYALA.UPTC.RTL', 'BASEUS AYALA UPTC RTL', 'BASEUSUPTOWN', 'BASEUSUPTC', 'TBASEUSUPTOWN', NULL, 'BASEUSUPTOWNRM', NULL, 'BASEUS UP TOWN', 'RETAIL', NULL, 'RETAIL', 1, NULL, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-12-02 06:04:25', NULL),
(4, 'BASEUS.SM.FAIRVIEW.RTL', 'BASEUS SM FAIRVIEW RTL', 'GBASEUSFAIRVIEW', 'BASEUSFVW', 'TBASEUSFAIRVIEW', NULL, 'BASEUSFAIRVIEWRM', NULL, 'BASEUS FAIRVIEW', 'RETAIL', NULL, 'RETAIL', 1, NULL, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-12-02 06:03:50', NULL),
(5, 'BASEUS.SM.NORTH EDSA.RTL', 'BASEUS SM NORTH EDSA RTL', 'GBASEUSSMNORTH', 'GBASEUSSMNORTH', NULL, NULL, NULL, NULL, 'BASEUS SM NORTH', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-12-02 06:03:50', NULL),
(6, 'BASEUS.SM.SOUTHMALL.RTL', 'BASEUS SM SOUTHMALL RTL', 'GBASEUSSOUTHMALL', 'BASEUSSOUTHMALL', 'TBASEUSSOUTHMALL', NULL, 'BASEUSSOUTHRM', NULL, 'BASEUS SOUTHMALL', 'RETAIL', NULL, 'RETAIL', 1, NULL, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-12-02 06:03:50', NULL),
(7, 'BEYOND THE BOX.AYALA.FAIRVIEW TERRACES.FRA', 'BEYOND THE BOX FAIRVIEW TERRACES FRA', 'GBTBFAIRVIEW', 'BTBFAIRVIEW', 'TBTBFAIRVIEW', 'TBTBFAIRVIEW', 'BTBFAIRVIEWRM', 'RBTBFAIRVIEW', 'BTB FAIRVIEW', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 4, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-12-02 04:16:02', NULL),
(8, 'BEYOND THE BOX.AYALA.MARKET MARKET.FRA', 'BEYOND THE BOX AYALA MARKET MARKET FRA', 'GBTBMARKETMARKET', 'BTBMARKETMARKET', 'TBTBMARKETMARKET', 'TBTBMARKETMARKET', 'BTBMARKETMARKTRM', 'RBTBMARKETMARKET', 'BTB MARKET MARKET', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 6, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-12-02 04:20:28', NULL),
(9, 'BEYOND THE BOX.CENTURY CITY.MAKATI.RTL', 'BEYOND THE BOX CENTURY CITY MAKATI RTL', 'GNBTBCENTURY', 'BTBCENTURY', 'TBTBCENTURY', 'TBTBCENTURY', 'BTBCENTURYRM', 'RBTBCENTURY', 'BTB CENTURY', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2022-03-11 05:13:30', NULL),
(10, 'BEYOND THE BOX.CITY OF DREAMS.MANILA.RTL', 'BEYOND THE BOX CITY OF DREAMS MANILA RTL', 'GBTBCOD', 'BTBCOD', 'TBTBCOD', 'TBTBCITYOFDREAMS', 'BTBCODRM', 'RBTBCITYOFDREAMS', 'BTB CITY OF DREAMS', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-06-05 17:09:08', NULL),
(11, 'BEYOND THE BOX.GREENHILLS.VMALL.RTL', 'BEYOND THE BOX GREENHILLS VMALL RTL', 'GBTBVMALL', 'BTBVMALL', 'TBTBVMALL', 'TBTBVMALL', 'BTBVMALLRM', 'RBTBVMALL', 'BTB VMALL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-06-05 17:10:10', NULL),
(12, 'BEYOND THE BOX.KCC.VERANZA.RTL', 'BEYOND THE BOX KCC VERANZA RTL', 'GBTBVERANZA', 'BTBVERANZA', 'TBTBVERANZA', 'TBTBVERANZA', 'BTBVERANZARM', 'RBTBVERANZA', 'BTB VERANZA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-06-05 17:14:06', NULL),
(13, 'BEYOND THE BOX.LIMKETKAI MALL.CDO.FRA', 'BEYOND THE BOX LIMKETKAI CDO FRA', 'BTBCDOG', 'BTBCDO', 'TBTBCDO', 'TBTBCDO', 'BTBCDORM', 'RBTBCDO', 'BTB CDO', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 11, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-12-02 05:20:11', NULL),
(14, 'BEYOND THE BOX.MEGAWORLD.FORBESTOWN.FRA', 'BEYOND THE BOX MEGAWORLD FORBESTOWN FRA', 'GBTBFORBESTOWN', 'BTBFORBESTOWN', 'TBTBFORBESTOWN', 'TBTBFORBESTOWN', 'BTBFORBESTOWNRM', 'RBTBFORBESTOWN', 'BTB FORBESTOWN', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 4, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-12-02 04:16:15', NULL),
(15, 'BEYOND THE BOX.MEGAWORLD.LUCKY CHINATOWN.FRA', 'BEYOND THE BOX MEGAWORLD LUCKY CHINATOWN FRA', 'GBTBLUCKYCHINA', 'BTBLUCKYCHINA', 'TBTBLUCKYCHINA', 'TBTBLUCKY', 'BTBLUCKYCHINARM', 'RBTBLUCKY', 'BTB LUCKY', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 4, 0, 'ACTIVE', 1, 73, '2020-11-12 19:28:58', '2024-01-01 17:02:20', NULL),
(16, 'BEYOND THE BOX.MEGAWORLD.NEWPORT.RTL', 'BEYOND THE BOX MEGAWORLD NEWPORT RTL', 'GBTBRESORTSWORLD', 'BTBRESORTSWORLD', 'TBTBRESORTSWORLD', 'TBTBNEWPORT', 'BTBRSORTSWORLDRM', 'RBTBNEWPORT', 'BTB NEWPORT', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-06-05 17:15:13', NULL),
(17, 'BEYOND THE BOX.MEGAWORLD.SOUTHWOODS.RTL', 'BEYOND THE BOX MEGAWORLD SOUTHWOODS RTL', 'GBTBSOUTHWOODS', 'BTBSOUTHWOODS', 'TBTBSOUTHWOODS', 'TBTBSOUTHWOODS', 'BTBSOUTHWOODSRM', 'RBTBSOUTHWOODS', 'BTB SOUTHWOODS', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-06-05 17:25:22', NULL),
(18, 'BEYOND THE BOX.MEGAWORLD.UPTOWN MALL.RTL', 'BEYOND THE BOX MEGAWORLD UPTOWN RTL', 'GBTBUPTOWN', 'BTBUPTOWN', 'TBTBUPTOWN', 'TBTBUPTOWN', 'BTBUPTOWNRM', 'RBTBUPTOWN', 'BTB UPTOWN', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-06-05 17:25:22', NULL),
(19, 'BEYOND THE BOX.MEGAWORLD.VENICE GRAND CANAL.RTL', 'BEYOND THE BOX VENICE GRAND CANAL RTL', 'GBTBPIAZZA', 'BTBPIAZZA', 'TBTBPIAZZA', 'TBTBPIAZZA', 'BTBPIAZZARM', 'RBTBPIAZZA', 'BTB PIAZZA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:58', '2021-06-09 02:21:42', NULL),
(20, 'BEYOND THE BOX.ONE ROCKWELL.MAKATI.RTL', 'BEYOND THE BOX ONE ROCKWELL MAKATI RTL', 'GBTBROCKWELL', 'GBTBROCKWELL', NULL, NULL, NULL, NULL, 'BTB ROCKWELL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-12-02 06:36:07', NULL),
(21, 'BEYOND THE BOX.ROBINSONS.ERMITA.RTL', 'BEYOND THE BOX ROBINSONS ERMITA RTL', 'GBTBERMITA', 'BTBERMITA', 'TBTBERMITA', 'TBTBERMITA', 'BTBERMITARM', 'RBTBERMITA', 'BTB ERMITA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(22, 'BEYOND THE BOX.ROBINSONS.GALLERIA.RTL', 'BEYOND THE BOX ROBINSONS GALLERIA RTL', 'GBTBGALLERIA', 'BTBGALLERIA', 'TBTBGALLERIA', 'TBTBGALLERIA', 'BTBGALLERIARM', 'RBTBGALLERIA', 'BTB GALLERIA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(23, 'BEYOND THE BOX.SM.BAGUIO.RTL', 'BEYOND THE BOX SM BAGUIO RTL', 'GBTBBAGUIO', 'BTBBAGUIO', 'TBTBBAGUIO', 'TBTBBAGUIO', 'BTBBAGUIORM', 'RBTBBAGUIO', 'BTB BAGUIO', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(24, 'BEYOND THE BOX.THE PLAZA.GUAM.FRA', NULL, 'BTBGUAMPLAZA', NULL, NULL, NULL, NULL, NULL, 'BTB GUAM', 'FRANCHISE', NULL, 'FRANCHISE', 2, NULL, NULL, 2, 0, 'INACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-12-02 05:40:43', NULL),
(25, 'DIGITAL WALKER.AYALA.ABREEZA.RTL', 'DIGITAL WALKER AYALA ABREEZA RTL', 'GDWABREEZA', 'DWABREEZA', 'TDWABREEZA', 'TDWABREEZA', 'DWABREEZARM', 'RDWABREEZA', 'DW ABREEZA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(26, 'DIGITAL WALKER.AYALA.BAY AREA.RTL', 'DIGITAL WALKER AYALA BAY AREA RTL', 'GDWTHEBAY', 'DWTHEBAY', 'TDWTHEBAY', 'TDWTHEBAY', 'DWTHEBAYRM', 'RDWTHEBAY', 'DW THE BAY', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(27, 'DIGITAL WALKER.AYALA.CEBU.RTL', 'DIGITAL WALKER AYALA CEBU RTL', 'DWAYALACEBUG', 'DWAYALACEBU', 'TDWAYALACEBU', 'TDWAYALACEBU', 'DWALAYACEBURM', 'RDWAYALACEBU', 'DW AYALA CEBU', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(28, 'DIGITAL WALKER.AYALA.GLORIETTA 2.RTL', 'DIGITAL WALKER AYALA GLORIETTA 2 RTL', 'GDWGLORIETTA', 'DWGLORIETTA2', 'TDWGLORIETTA5', 'TDWGLORIETTA', 'DWGLORIETTARM', 'RDWGLORIETTA', 'DW GLORIETTA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(29, 'DIGITAL WALKER.AYALA.GREENBELT 5.FRA', 'DIGITAL WALKER AYALA GREENBELT 5 FRA', 'GDWGREENBELT5FRA', 'DWGREENBELT5FRA', 'TDWGREENBELT', 'TDWGREENBELT', 'DWGREENBELT5RM', 'RDWGREENBELT', 'DW GREENBELT FRA', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 6, 0, 'INACTIVE', 1, 1, '2020-11-12 19:28:59', '2023-07-04 17:36:32', NULL),
(30, 'DIGITAL WALKER.AYALA.ONE BONIFACIO.RTL', 'DIGITAL WALKER AYALA ONE BONIFACIO RTL', 'DWONEBONIG', 'DWONEBONI', 'DWONEBONIR', 'TDWONEBONIFACIO', 'DWONEBONIRM', 'RDWONEBONIFACIO', 'DW ONE BONIFACIO', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 73, '2020-11-12 19:28:59', '2023-11-24 01:32:39', NULL),
(31, 'DIGITAL WALKER.AYALA.THE 30TH.RTL', 'DIGITAL WALKER AYALA THE 30TH RTL', 'GDWTHE30TH', 'DWTHE30TH', 'TDWTHE30TH', 'TDWTHE30TH', 'DWTHE30THRM', 'RDWTHE30TH', 'DW THE 30TH', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(32, 'DIGITAL WALKER.AYALA.TRINOMA.RTL', 'DIGITAL WALKER AYALA TRINOMA RTL', 'GDWTRINOMA', 'DWTRINOMA', 'TDWTRINOMA', 'TDWTRINOMA', 'DWTRINOMARM', 'RDWTRINOMA', 'DW TRINOMA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(33, 'DIGITAL WALKER.AYALA.UPTC.FRA', 'DIGITAL WALKER AYALA UPTC FRA', 'GDWUPTOWN', 'DWUPTC', 'TDWUPTOWNCENTER', 'TDWUPTC', 'DWUPTOWNRM', 'RDWUPTC', 'DW UPTC', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 5, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-12-02 04:19:37', NULL),
(34, 'DIGITAL WALKER.AYALA.VERTIS NORTH.FRA', 'DIGITAL WALKER AYALA VERTIS NORTH FRA', 'GDWVERTIS', 'DWVERTISNORTH', 'TDWVERTISNORTH', 'TDWVERTIS', 'DWVERTIS', 'RDWVERTIS', 'DW VERTIS', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 5, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-12-02 04:19:33', NULL),
(35, 'DIGITAL WALKER.CAPITOL COMMONS.ESTANCIA.FRA', 'DIGITAL WALKER CAPITOL COMMONS ESTANCIA FRA', 'GDWESTANCIA', 'DWESTANCIA', 'TDWESTANCIA', 'TDWESTANCIA', 'DWESTANCIARM', 'RDWESTANCIA', 'DW ESTANCIA', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 5, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-12-02 04:19:41', NULL),
(36, 'DIGITAL WALKER.CC.ESTANCIA EXPANSION.FRA', 'DIGITAL WALKER CC ESTANCIA EXPANSION FRA', 'DWESTANCIAEG', 'DWESTANCIAEXP', 'DWESTANCIAEXT', 'TDWESTANCIAEXP', 'DWESTANCIAEXPR', 'RDWESTANCIAEXP', 'DW ESTANCIA EXP', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 5, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2022-08-22 22:06:53', NULL),
(37, 'DIGITAL WALKER.CENTRIO.CDO.RTL', 'DIGITAL WALKER CENTRIO CDO RTL', 'GDWCAGAYAN', 'DWCAGAYAN', 'TDWCAGAYAN', 'TDWCDO', 'DWCAGAYANRM', 'RDWCDO', 'DW CDO', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(38, 'DIGITAL WALKER.CENTURY CITY.MAKATI.RTL', 'DIGITAL WALKER CENTURY CITY MAKATI RTL', 'GDWCENTURY', 'DWCENTURY', 'TDWCENTURY', 'TDWCENTURY', 'DWCENTURYRM', 'RDWCENTURY', 'DW CENTURY', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:28:59', '2021-06-05 17:25:22', NULL),
(39, 'DIGITAL WALKER.FESTIVAL MALL.ALABANG.FRA', 'DIGITAL WALKER FESTIVAL MALL ALABANG FRA', 'DWFESTIVAL', 'GDWFESTIVAL', NULL, NULL, NULL, NULL, 'DW FESTIVAL MALL', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 9, 0, 'INACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-12-02 06:41:47', NULL),
(40, 'DIGITAL WALKER.GREENHILLS.VMALL.RTL', 'DIGITAL WALKER GREENHILLS VMALL RTL', 'GDWVMALL', 'DWVMALL', 'TDWVMALL', 'TDWVMALL', 'DWVMALL', 'RDWVMALL', 'DW VMALL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-05 17:25:22', NULL),
(41, 'DIGITAL WALKER.MEGAWORLD.EASTWOOD.RTL', 'DIGITAL WALKER MEGAWORLD EASTWOOD RTL', 'GDWEASTWOOD', 'DWEASTWOOD', 'TDWEASTWOOD', 'TDWEASTWOOD', 'DWEASTWOODRM', 'RDWEASTWOOD', 'DW EASTWOOD', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-05 17:25:22', NULL),
(42, 'DIGITAL WALKER.MEGAWRLD.FESTIVE WALK.RTL', 'DIGITAL WALKER MEGAWRLD FESTIVEWALK RTL', '678001', 'DWFESTIVEWALK', '678002', 'TDWFESTIVEWALK', '678003', 'RDWFESTIVEWALK', 'DW FESTIVE WALK', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-23 03:16:04', NULL),
(43, 'DIGITAL WALKER.OKADA.MANILA.RTL', 'DIGITAL WALKER OKADA MANILA RTL', 'DWOKADAG', 'DWOKADA', 'TDWOKADA', 'TDWOKADA', 'DWOKADARM', 'RDWOKADA', 'DW OKADA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-05 17:25:22', NULL),
(44, 'DIGITAL WALKER.ROBINSONS.ERMITA.RTL', 'DIGITAL WALKER ROBINSONS ERMITA RTL', 'GDWERMITA', 'DWERMITA', 'TDWERMITA', 'TDWERMITA', 'DWERMITARM', 'RDWERMITA', 'DW ERMITA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-05 17:25:22', NULL),
(45, 'DIGITAL WALKER.ROBINSONS.MAGNOLIA.FRA', 'DIGITAL WALKER ROBINSONS MAGNOLIA FRA', 'GDWMAGNOLIA', 'DWMAGNOLIA', 'TWDMAGNOLIA', 'TDWMAGNOLIA', 'DWMAGNOLIARM', 'RDWMAGNOLIA', 'DW MAGNOLIA', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 8, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-12-02 05:18:52', NULL),
(46, 'DIGITAL WALKER.ROBINSONS.PIONEER.RTL', 'DIGITAL WALKER ROBINSONS PIONEER RTL', 'GDWPIONEER', 'DWPIONEER', 'TDWPIONEER', 'TDWPIONEER', 'DWPIONEERRM', 'RDWPIONEER', 'DW PIONEER', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-05 17:25:22', NULL),
(47, 'DIGITAL WALKER.ROCKWELL.POWERPLANT.RTL', 'DIGITAL WALKER ROCKWELL POWERPLANT RTL', 'GDWROCKWELL', 'DWROCKWELL', 'TDWROCKWELL', 'TDWROCKWELL', 'DWROCKWELLRM', 'RDWROCKWELL', 'DW ROCKWELL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2023-10-10 02:23:31', NULL),
(48, 'DIGITAL WALKER.SHANGRI-LA.EDSA.RTL', 'DIGITAL WALKER SHANGRI-LA EDSA RTL', 'GDWSHANGRILA', 'DWSHANGRILA', 'TDWSHANGRILA', 'TDWSHANGRILA', 'DWSHANGRILARM', 'RDWSHANGRILA', 'DW SHANGRILA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-05 17:25:23', NULL),
(49, 'DIGITAL WALKER.SM.AURA.RTL', 'DIGITAL WALKER SM AURA RTL', 'GDWSMAURA', 'DWSMAURA', 'TDWSMAURA', 'TDWSMAURA', 'DWSMAURARM', 'RDWSMAURA', 'DW SM AURA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-05 17:25:23', NULL),
(50, 'DIGITAL WALKER.SM.BAGUIO.RTL', 'DIGITAL WALKER SM BAGUIO RTL', 'GDWBAGUIO', 'DWBAGUIO', 'TDWBAGUIO', 'TDWBAGUIO', 'DWBAGUIORM', 'RDWBAGUIO', 'DW BAGUIO', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-05 17:25:23', NULL),
(51, 'DIGITAL WALKER.SM.CEBU.RTL', 'DIGITAL WALKER SM CEBU RTL', 'DWSMCEBUG', 'DWSMCEBU', 'TDWSMCEBU', 'TDWSMCEBU', 'DWSMCEBURM', 'RDWSMCEBU', 'DW SM CEBU', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:00', '2021-06-05 17:25:23', NULL),
(52, 'DIGITAL WALKER.SM.MALL OF ASIA.RTL', 'DIGITAL WALKER SM MALL OF ASIA RTL', 'GDWMOA', 'DWMOA', 'TDWMOA', 'TDWMOA', 'DWMOARM', 'RDWMOA', 'DW MOA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-06-05 17:25:23', NULL),
(53, 'DIGITAL WALKER.SM.NORTH EDSA.RTL', 'DIGITAL WALKER SM NORTH EDSA RTL', 'DWSMNORTHG', 'DWSMNORTHEDSA', 'TDWSMNORTH', 'TDWNORTHEDSA', 'DWSMNORTHRM', 'RDWNORTHEDSA', 'DW NORTH EDSA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-06-05 17:25:23', NULL),
(54, 'DIGITAL WALKER.SM.SOUTHMALL.RTL', 'DIGITAL WALKER SM SOUTHMALL RTL', '687001', 'DWSOUTHMALL', '687002', 'TDWSOUTHMALL', '687003', 'RDWSOUTHMALL', 'DW SOUTHMALL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-06-05 17:25:23', NULL),
(55, 'DIGITAL WALKER.SUPERMALLS.CONRAD HOTEL.RTL', 'DIGITAL WALKER CONRAD HOTEL RTL', 'GDWCONRAD', 'DWCONRAD', 'TDWCONRAD', 'TDWCONRAD', 'DWCONRADRM', 'RDWCONRAD', 'DW CONRAD', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-06-08 23:03:13', NULL),
(56, 'DIGITAL WALKER.SUPERMALLS.THE PODIUM.RTL', 'DIGITAL WALKER SUPERMALLS THE PODIUM RTL', 'GDWPODIUM', 'DWPODIUM', 'TDWPODIUM', 'TDWPODIUM', 'DWPODIUMRM', 'RDWPODIUM', 'DW PODIUM', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-06-05 17:25:23', NULL),
(57, 'OMG.GREENHILLS.VMALL.RTL', 'OMG GREENHILLS VMALL RTL', 'OMGVMALLG', 'OMGVMALLG', NULL, NULL, NULL, NULL, 'OMG VMALL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-12-02 06:12:29', NULL),
(58, 'OMG.MEGAWORLD.FESTIVE WALK.RTL', 'OMG MEGAWORLD FESTIVE WALK RTL', 'GOMGFESTIVE', 'OMGFESTIVE', 'OMGFESTIVER', NULL, 'OMGFESTIVERM', NULL, 'OMG FESTIVE WALK', 'RETAIL', NULL, 'RETAIL', 1, NULL, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-12-02 06:12:29', NULL),
(59, 'OMG.ROBINSONS.ERMITA.RTL', 'OMG OMG ROBINSONS ERMITA RTL', 'GOMGERMITA', 'OMGERMITA', 'TOMGERMITA', 'TOMGERMITA', 'OMGERMITARM', 'ROMGERMITA', 'OMG ERMITA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-07-08 22:17:13', NULL),
(60, 'OMG.SM.CDO.RTL', 'OMG SM CDO RTL', 'OMGCDO', 'OMGCDO', 'TOMGCDO', NULL, 'OMGCDORM', NULL, 'OMG CDO', 'RETAIL', NULL, 'RETAIL', 1, NULL, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-12-02 06:12:29', NULL),
(61, 'OUTERSPACE.NAIA.TERMINAL 3.RTL', 'OUTERSPACE NAIA TERMINAL 3 RTL', 'GOSNAIA', 'OUTERSPACENAIAT3', 'TOSNAIA', NULL, 'OSNAIARM', NULL, 'OUTERSPACE NAIA TERMINAL', 'RETAIL', NULL, 'RETAIL', 1, NULL, NULL, 1, 0, 'INACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-12-02 06:35:57', NULL),
(62, 'SERVICE CENTER.AYALA.BONIFACIO HIGH STREET.RTL', 'SERVICE CENTER BONIFACIO HIGH STREET RTL', 'BTBSCBGCG', 'BTBSERVICEBGC', 'BTBSCBGCR', 'TSCBGC', 'RSCBGC', 'RSCBGC', 'SC BGC', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-06-09 02:25:14', NULL),
(63, 'SERVICE CENTER.GREENHILLS.VMALL.RTL', 'SERVICE CENTER GREENHILLS VMALL RTL', 'BTBSCVMALLG', 'BTBSCVMALL', 'BTBSCVMALLR', 'TSCVMALL', 'RSCVMALL', 'RSCVMALL', 'SC VMALL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2020-11-12 19:29:01', '2021-06-05 17:25:23', NULL),
(64, 'DIGITS WAREHOUSE', 'DIGITS WAREHOUSE', 'DIGITSWAREHOUSE', 'DIGITSWAREHOUSE', 'DIGITSWAREHOUSE', NULL, 'DIGITSWAREHOUSE', NULL, 'DIGITS WAREHOUSE', 'DIGITS', NULL, 'DIGITS', NULL, NULL, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2020-12-28 03:32:43', NULL, NULL),
(65, 'DIGITS RMA', 'DIGITS RMA', 'DIGITSRMA', 'DIGITSRMA', 'DIGITSRMA', NULL, 'DIGITSRMA', NULL, 'DIGITS RMA', 'RMA', NULL, 'RMA', NULL, NULL, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2020-12-28 03:33:09', NULL, NULL),
(67, 'DIGITAL WALKER.VISTA MALL.TAGUIG.RTL', 'DIGITAL WALKER VISTA MALL TAGUIG RTL', '698001', 'DWVISTAMALLTAG', '698002', 'TDWVISTATAGUIG', '698003', 'RDWVISTATAGUIG', 'DW VISTA TAGUIG', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2021-03-09 17:38:20', '2021-06-05 17:25:23', NULL),
(68, 'DIGITAL WALKER.LAZADA.FBV.ONL', 'LAZADA DIGITAL WALKER FBV', 'GLAZADADWFBV', 'LAZADADWFBV', NULL, NULL, NULL, NULL, 'LAZADA DW FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2021-03-09 21:14:01', '2021-06-05 17:50:51', NULL),
(69, 'FITBIT.LAZADA.FBV.ONL', 'LAZADA FITBIT FBV', 'GLAZADAFITBITFBV', 'LAZADAFITBITFBV', NULL, NULL, NULL, NULL, 'LAZADA FITBIT FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2021-03-09 21:14:01', '2021-06-05 17:54:50', NULL),
(70, 'ONEPLUS.LAZADA.FBV.ONL', 'LAZADA ONEPLUS FBV', 'GLAZONEPLUSFBV', 'LAZADAONEPLUSFBV', NULL, NULL, NULL, NULL, 'LAZADA ONEPLUS FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2021-03-09 21:14:01', '2021-06-05 18:00:23', NULL),
(71, 'UAG.LAZADA.FBV.ONL', 'LAZADA UAG FBV', 'GLAZADAUAGFBV', 'LAZADAUAGFBV', NULL, NULL, NULL, NULL, 'LAZADA UAG FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2021-03-09 21:14:01', '2021-06-05 18:08:07', NULL),
(72, 'DIGITAL WALKER.SHOPEE.FBD.ONL', 'DIGITAL WALKER SHOPEE FBD', 'GSHOPEEDWFBD', 'DIGITS', 'TSHOPEEDWFBD', 'TSHOPEEDWFBD', 'RSHOPEEDWFBD', 'RSHOPEEDWFBD', 'SHOPEE DW FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:01', '2021-10-19 18:44:18', NULL),
(73, 'ONEPLUS.SHOPEE.FBV.ONL', 'SHOPEE ONEPLUS FBV', 'SHOPEEFBV', 'SHOPEEFBV', NULL, NULL, NULL, NULL, 'SHOPEE ONEPLUS FBV', 'SHOPEE', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:01', '2021-10-19 08:48:47', NULL),
(74, 'BASEUS.LAZADA.FBV.ONL', 'LAZADA BASEUS FBV', 'LAZADAFBV', 'LAZADAFBV', NULL, NULL, NULL, NULL, 'LAZADA BASEUS FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 09:57:20', NULL),
(75, 'FITBIT.SHOPEE.FBV.ONL', 'SHOPEE FITBIT FBV', 'SHOPEEFBV', 'SHOPEEFBV', NULL, NULL, NULL, NULL, 'SHOPEE FITBIT FBV', 'SHOPEE', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 08:48:47', NULL),
(76, 'BASEUS.SHOPEE.FBV.ONL', 'SHOPEE BASEUS FBV', 'SHOPEEFBV', 'SHOPEEFBV', NULL, NULL, NULL, NULL, 'SHOPEE BASEUS FBV', 'SHOPEE', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 08:48:47', NULL),
(77, 'JOYROOM.LAZADA.FBV.ONL', 'LAZADA JOYROOM FBV', 'LAZADAFBV', 'LAZADAFBV', NULL, NULL, NULL, NULL, 'LAZADA JOYROOM FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 09:56:55', NULL),
(78, 'JOYROOM.SHOPEE.FBV.ONL', 'JOYROOM SHOPEE FBV', 'SHOPEEFBV', 'SHOPEEFBV', NULL, NULL, NULL, NULL, 'JOYROOM SHOPEE FBV', 'SHOPEE', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 08:48:47', NULL),
(79, 'FITBIT.LAZADA.FBD.ONL', 'FITBIT LAZADA FBD', 'GLAZADAFITBITFBD', 'DIGITS', 'TLAZADAFITBITFBD', 'TLAZADAFITBITFBD', 'RLAZADAFITBITFBD', 'RLAZADAFITBITFBD', 'LAZADA FITBIT FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 18:38:10', NULL),
(80, 'ONEPLUS.LAZADA.FBD.ONL', 'ONEPLUS LAZADA FBD', 'GLAZONEPLUSFBD', 'DIGITS', 'TLAZONEPLUSFBD', 'TLAZONEPLUSFBD', 'RLAZONEPLUSFBD', 'RLAZONEPLUSFBD', 'LAZADA ONEPLUS FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 18:40:34', NULL),
(81, 'BASEUS.LAZADA.FBD.ONL', 'BASEUS LAZADA FBD', 'LAZADAFBD', 'LAZADAFBD', NULL, NULL, NULL, NULL, 'BASEUS LAZADA FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-06-05 18:22:41', NULL),
(82, 'FITBIT.SHOPEE.FBD.ONL', 'FITBIT SHOPEE FBD', 'GSHOPEEFITBITFBD', 'DIGITS', 'TSHOPEEFITBITFBD', 'TSHOPEEFITBITFBD', 'RSHOPEEFITBITFBD', 'RSHOPEEFITBITFBD', 'SHOPEE FITBIT FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 18:44:41', NULL),
(83, 'ONEPLUS.SHOPEE.FBD.ONL', 'ONEPLUS SHOPEE FBD', 'GSHPONEPLUSFBD', 'DIGITS', 'TSHPONEPLUSFBD', 'TSHPONEPLUSFBD', 'RSHPONEPLUSFBD', 'RSHPONEPLUSFBD', 'SHOPEE ONEPLUS FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 18:45:32', NULL),
(84, 'BASEUS.SHOPEE.FBD.ONL', 'BASEUS SHOPEE FBD', 'SHOPEEFBD', 'SHOPEEFBD', NULL, NULL, NULL, NULL, 'BASEUS SHOPEE FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-06-05 18:22:41', NULL),
(85, 'JOYROOM.SHOPEE.FBD.ONL', 'JOYROOM SHOPEE FBD', 'SHOPEEFBD', 'SHOPEEFBD', NULL, NULL, NULL, NULL, 'JOYROOM SHOPEE FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-06-05 18:20:04', NULL),
(86, 'JOYROOM.LAZADA.FBD.ONL', 'JOYROOM LAZADA FBD', 'LAZADAFBD', 'LAZADAFBD', NULL, NULL, NULL, NULL, 'JOYROOM LAZADA FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-06-05 18:19:55', NULL),
(87, 'AFTERSHOKZ.LAZADA.CLL.ONL', 'CLOUD LOGIC NEW MANILA FBV', 'CLOUDLOGIC', 'CLOUDLOGIC', NULL, NULL, NULL, NULL, 'AFTERSHOKZ LAZADA CLL', 'LAZADA', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 08:48:47', NULL),
(88, 'DIGITAL WALKER.LAZADA.CLL.ONL', 'CLOUD LOGIC NEW MANILA FBV', 'CLOUDLOGIC', 'CLOUDLOGIC', NULL, NULL, NULL, NULL, 'DIGITAL WALKER LAZADA CLL', 'LAZADA', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 08:48:47', NULL),
(89, 'MOMAX.LAZADA.CLL.ONL', 'CLOUD LOGIC NEW MANILA FBV', 'CLOUDLOGIC', 'CLOUDLOGIC', NULL, NULL, NULL, NULL, 'MOMAX LAZADA CLL', 'LAZADA', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 08:48:47', NULL),
(90, 'AFTERSHOKZ.SHOPEE.CLL.ONL', 'CLOUD LOGIC NEW MANILA FBV', 'CLOUDLOGIC', 'CLOUDLOGIC', NULL, NULL, NULL, NULL, 'AFTERSHOKZ SHOPEE CLL', 'SHOPEE', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 08:48:47', NULL),
(91, 'DIGITAL WALKER.SHOPEE.CLL.ONL', 'CLOUD LOGIC NEW MANILA FBV', 'CLOUDLOGIC', 'CLOUDLOGIC', NULL, NULL, NULL, NULL, 'DIGITAL WALKER SHOPEE CLL', 'SHOPEE', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 08:48:47', NULL),
(92, 'MOMAX.SHOPEE.CLL.ONL', 'CLOUD LOGIC NEW MANILA FBV', 'CLOUDLOGIC', 'CLOUDLOGIC', NULL, NULL, NULL, NULL, 'MOMAX SHOPEE CLL', 'SHOPEE', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 08:48:47', NULL),
(93, '1MORE.LAZADA.FBV.ONL', 'LAZADA 1MORE FBV', 'LAZADAFBV', 'LAZADAFBV', NULL, NULL, NULL, NULL, 'LAZADA 1MORE FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:02', '2021-10-19 09:56:10', NULL),
(94, '1MORE.SHOPEE.FBV.ONL', '1MORE SHOPEE FBV', 'SHOPEEFBV', 'SHOPEEFBV', NULL, NULL, NULL, NULL, '1MORE SHOPEE FBV', 'SHOPEE', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-10-19 08:48:47', NULL),
(95, '1MORE.LAZADA.FBD.ONL', '1MORE LAZADA FBD', 'LAZADAFBD', 'LAZADAFBD', NULL, NULL, NULL, NULL, '1MORE LAZADA FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-06-05 18:19:27', NULL),
(96, '1MORE.SHOPEE.FBD.ONL', '1MORE SHOPEE FBD', 'SHOPEEFBD', 'SHOPEEFBD', NULL, NULL, NULL, NULL, '1MORE SHOPEE FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-06-05 18:19:27', NULL),
(97, 'ZENDURE.LAZADA.FBV.ONL', 'LAZADA ZENDURE FBV', 'LAZADAFBV', 'LAZADAFBV', NULL, NULL, NULL, NULL, 'LAZADA ZENDURE FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-10-19 09:55:53', NULL),
(98, 'ZENDURE.SHOPEE.FBV.ONL', 'ZENDURE SHOPEE FBV', 'SHOPEEFBV', 'SHOPEEFBV', NULL, NULL, NULL, NULL, 'ZENDURE SHOPEE FBV', 'SHOPEE', NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-10-19 08:48:47', NULL),
(99, 'ZENDURE.LAZADA.FBD.ONL', 'ZENDURE LAZADA FBD', 'LAZADAFBD', 'LAZADAFBD', NULL, NULL, NULL, NULL, 'ZENDURE LAZADA FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-06-05 18:20:18', NULL),
(100, 'ZENDURE.SHOPEE.FBD.ONL', 'ZENDURE SHOPEE FBD', 'SHOPEEFBD', 'SHOPEEFBD', NULL, NULL, NULL, NULL, 'ZENDURE SHOPEE FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-06-05 18:20:18', NULL),
(101, 'HOME OFFICE.DIGITS.FBD.ONL', 'HOME OFFICE DIGITS FBD', 'GHOMEOFFICEFBD', 'ZERO INVENTORY', NULL, NULL, NULL, NULL, 'HOMEOFFICE FBD', NULL, 'HMEOFC FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-10-19 08:28:53', NULL),
(102, 'BEYOND THE BOX.LAZADA.FBV.ONL', 'LAZADA BTB FBV', 'GLAZADABTBFBV', 'LAZADABTBFBV', NULL, NULL, NULL, NULL, 'LAZADA BTB FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2021-03-09 21:14:03', '2021-10-19 09:49:21', NULL),
(103, 'ECQDIGITS.ONL', 'ECQ DIGITS', NULL, NULL, NULL, NULL, NULL, NULL, 'ECQ DIGITS', NULL, NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-10-19 08:48:47', NULL),
(104, 'ECQRETAIL.ONL', 'ECQ RETAIL', NULL, NULL, NULL, NULL, NULL, NULL, 'ECQ RETAIL', NULL, NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-10-19 08:48:47', NULL),
(105, 'MARSHALL.LAZADA.FBV.ONL', 'LAZADA MARSHALL FBV', 'GLAZMARSHALLFBV', 'LAZMARSHALLFBV', NULL, NULL, NULL, NULL, 'LAZADA MARSHALL FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2021-03-09 21:14:03', '2021-06-05 17:57:13', NULL),
(106, 'THE GROVE HOWARD.ONL', 'THE GROVE HOWARD', 'THEGROVEHOWARD', 'THEGROVEHOWARD', NULL, NULL, NULL, NULL, 'THE GROVE HOWARD', NULL, NULL, 'ECOM', 4, NULL, NULL, NULL, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-10-19 08:48:47', NULL),
(107, 'MARSHALL.LAZADA.FBD.ONL', 'MARSHALL LAZADA FBD', 'GLAZMARSHALLFBD', 'DIGITS', 'TLAZMARSHALLFBD', 'TLAZMARSHALLFBD', 'RLAZMARSHALLFBD', 'RLAZMARSHALLFBD', 'LAZADA MARSHALL FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-10-19 18:40:09', NULL),
(108, 'UAG.LAZADA.FBD.ONL', 'UAG LAZADA FBD', 'GLAZADAUAGFBD', 'DIGITS', 'TLAZADAUAGFBD', 'TLAZADAUAGFBD', 'RLAZADAUAGFBD', 'RLAZADAUAGFBD', 'LAZADA UAG FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-10-19 18:38:35', NULL),
(109, 'STORK PH.FBD.ONL', 'STORK PH FBD', 'STORKPHFBDONL', 'STORKPHFBDONL', NULL, NULL, NULL, NULL, 'STORK PH FBD', NULL, 'DIGITS FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:03', '2021-03-16 22:47:34', NULL),
(110, 'MARSHALL.SHOPEE.FBD.ONL', 'MARSHALL SHOPEE FBD', 'GSHPMARSHALLFBD', 'DIGITS', 'TSHPMARSHALLFBD', 'TSHPMARSHALLFBD', 'RSHPMARSHALLFBD', 'RSHPMARSHALLFBD', 'SHOPEE MARSHALL FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:04', '2021-10-19 18:45:01', NULL),
(111, 'BEYOND THE BOX.LAZADA.FBD.ONL', 'BEYOND THE BOX LAZADA FBD', 'GLAZADABTBFBD', 'DIGITS', 'TLAZADABTBFBD', 'TLAZADABTBFBD', 'RLAZADABTBFBD', 'RLAZADABTBFBD', 'LAZADA BTB FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:04', '2021-10-19 18:37:22', NULL),
(112, 'BEYOND THE BOX.DIGITS.FBD.ONL', 'BEYOND THE BOX DIGITS FBD', 'GECOMBTBFBD', 'DIGITS', 'TECOMBTBFBD', 'TECOMBTBFBD', 'RECOMBTBFBD', 'RECOMBTBFBD', 'ECOM BTB FBD', NULL, 'WEBBTB FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-03-09 21:14:04', '2021-10-21 23:42:24', NULL),
(113, 'AFTERSHOKZ.SHOPEE.FBD.ONL', 'AFTERSHOKZ SHOPEE FBD', 'SHOPEEFBD', 'SHOPEEFBD', NULL, NULL, NULL, NULL, 'AFTERSHOKZ SHOPEE FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'INACTIVE', 1, 1, '2021-03-09 21:14:04', '2021-06-05 18:21:48', NULL),
(114, 'CLEARANCE.ROCKWELL.SANTOLAN TOWN PLAZA.SLE', 'CLEARANCE SANTOLAN TOWN PLAZA SLE', 'DWSANTOLANESALE', 'DWSANTOLANESALE', NULL, NULL, NULL, NULL, 'DW SANTOLAN SALE', 'CLEARANCE', NULL, 'DIGITS', 5, 1, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2021-03-14 22:28:49', '2021-03-15 23:09:08', NULL),
(115, 'ABENSON.DEPOT.PASIG.DLR', 'ABENSON DEPOT PASIG', 'GABENSONDEPOTPASIG', 'ABENSONDEPOTPASIG', NULL, NULL, NULL, NULL, 'ABENSON DEPOT PASIG', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', NULL, 1, '2021-03-14 22:29:51', '2023-06-20 19:49:00', NULL),
(116, 'DW MACHINE.ROCKWELL.POWERPLANT.RTL', 'DW MACHINES ROCKWELL POWERPLANT RTL', 'GDWMACHINEROCK', 'DWMACHINEROCKWEL', 'TRNSTDWMACHINERO', 'TDWMACHROCKWELL', 'RMADWMACHINEROCK', 'RDWMACHROCKWELL', 'DW MACH ROCKWELL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2021-06-05 17:25:12', '2021-06-10 02:16:39', NULL),
(117, 'DIGITAL WALKER MACHINE.TRINOMA.RTL', 'DW MACHINES TRINOMA RTL', 'DWMCHNETRINOMAGD', 'DWMCHNTRINOMA', 'DWMHNETRINOMATR', 'TDWMACHTRINOMA', 'RMADWMCHNTRINOMA', 'RDWMACHTRINOMA', 'DW MACH TRINOMA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2021-06-05 17:25:12', '2021-06-10 02:16:19', NULL),
(118, 'DIGITAL WALKER.SM.FAIRVIEW.FRA', 'DIGITAL WALKER SM FAIRVIEW FRA', 'DWSMFAIRVIEWG', 'DWSMFAIRVIEW', 'DWSMFAIRVIEWTR', 'TDWFAIRVIEW', 'DWSMFAIRVIEWRMA', 'RDWFAIRVIEW', 'DW FAIRVIEW', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 4, 0, 'ACTIVE', 1, NULL, '2021-06-05 17:27:43', '2021-12-02 04:16:11', NULL),
(119, 'DIGITAL WALKER.LAZADA.FBD.ONL', 'DIGITAL WALKER LAZADA FBD', 'GLAZADADWFBD', 'DIGITS', 'TLAZADADWFBD', 'TLAZADADWFBD', 'RLAZADADWFBD', 'RLAZADADWFBD', 'LAZADA DW FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-06-05 18:13:19', '2021-10-19 18:37:46', NULL),
(120, 'DIGITAL WALKER.DIGITS.FBD.ONL', 'WEBSITE DW FBD', 'GECOMDWFBD', 'DIGITS', 'TECOMDWFBD', 'TECOMDWFBD', 'RECOMDWFBD', 'RECOMDWFBD', 'ECOM DW FBD', NULL, 'WEBDW FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-06-05 18:13:19', '2021-10-28 18:19:13', NULL),
(121, 'BEYOND THE BOX.SHOPEE.FBD.ONL', 'SHOPEE BTB FBD', 'GSHOPEEBTBFBD', 'DIGITS', 'TSHOPEEBTBFBD', 'TSHOPEEBTBFBD', 'RSHOPEEBTBFBD', 'RSHOPEEBTBFBD', 'SHOPEE BTB FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-06-05 18:13:19', '2021-10-19 18:43:51', NULL),
(122, 'BEYOND THE BOX.SHOPEE.FBV.ONL', 'SHOPEE BTB FBV', 'GSHOPEEBTBFBV', 'SHOPEEBTBFBV', NULL, NULL, NULL, NULL, 'SHOPEE BTB FBV', 'SHOPEE', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, 1, '2021-06-05 18:13:19', '2021-10-19 09:27:47', NULL),
(123, 'AFTERSHOKZ.LAZADA.FBD.ONL', 'AFTERSHOKZ LAZADA FBD', 'GLAZAFTFBD', 'DIGITS', 'TLAZAFTFBD', 'TLAZAFTFBD', 'RLAZAFTFBD', 'RLAZAFTFBD', 'LAZADA AFTERSHOKZ FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 1, '2021-06-05 18:13:19', '2022-02-10 22:34:35', NULL),
(124, 'AFTERSHOKZ.LAZADA.FBV.ONL', 'LAZADA AFTERSHOKZ FBV', 'GLAZAFTFBV', 'LAZAFTFBV', NULL, NULL, NULL, NULL, 'LAZADA AFTERSHOKZ FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, 1, '2021-06-05 18:13:19', '2021-06-05 18:14:48', NULL),
(125, 'DIGITAL WALKER.SM.MARILAO.RTL', 'DIGITAL WALKER SM MARILAO RTL', 'GDWSMMARILAO', 'DWSMMARILAO', 'TDWSMMARILAO', 'TDWSMMARILAO', 'RDWSMMARILAO', 'RDWSMMARILAO', 'DW SM MARILAO', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'INACTIVE', NULL, 1, '2021-07-07 01:03:55', '2023-03-28 06:00:42', NULL),
(126, 'DIGITAL WALKER.MEGAWORLD.UPTOWN MALL.RTL', 'DIGITAL WALKER MEGAWORLD UPTOWN MALL RTL', 'GDWUPTOWNMALL', 'DWUPTOWNMALL', 'TDWUPTOWNMALL', 'TDWUPTOWNMALL', 'RDWUPTOWNMALL', 'RDWUPTOWNMALL', 'DW UPTOWN MALL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2021-09-23 21:17:10', '2021-09-23 21:41:33', NULL),
(127, 'DIGITAL WALKER.THE OUTLETS.LIPA.RTL', 'DIGITAL WALKER THE OUTLETS LIPA RTL', 'GDWLIPA', 'DWLIPA', 'TDWLIPA', 'TDWLIPA', 'RDWLIPA', 'RDWLIPA', 'DW LIPA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2021-09-23 21:18:45', '2021-09-23 21:41:13', NULL),
(128, 'DIGITAL WALKER.SM.SANTA MESA.RTL', 'DIGITAL WALKER SM SANTA MESA RTL', 'GDWSMSANTAMESA', 'DWSMSANTAMESA', 'TDWSMSANTAMESA', 'TDWSMSANTAMESA', 'RDWSMSANTAMESA', 'RDWSMSANTAMESA', 'DW SM SANTA MESA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2021-09-27 19:23:56', NULL, NULL),
(129, 'DIGITAL WALKER.SM.GRAND CENTRAL.RTL', 'DIGITAL WALKER SM GRAND CENTRAL RTL', 'GDWCENTRAL', 'DWCENTRAL', 'TDWCENTRAL', 'TDWCENTRAL', 'RDWCENTRAL', 'RDWCENTRAL', 'DW GRAND CENTRAL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2021-10-03 18:17:25', '2021-11-25 00:22:04', NULL),
(130, 'DIGITAL WALKER.AYALA.HIGH STREET.FRA', 'DIGITAL WALKER AYALA HIGH STREET FRA', 'GDWHIGHSTREETFRA', 'DWHIGHSTREETFRA', 'TDWHIGHSTREET', 'TDWHIGHSTREET', 'RDWHIGHSTREET', 'RDWHIGHSTREET', 'DW HIGH STREET FRA', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 6, 0, 'INACTIVE', NULL, 1, '2021-10-12 17:26:30', '2023-07-04 17:37:22', NULL),
(131, 'DIGITAL WALKER.ROBINSONS.GALLERIA.RTL', 'DIGITAL WALKER ROBINSONS GALLERIA RTL', 'GDWORTIGAS', 'DWORTIGAS', 'TDWORTIGAS', 'TDWORTIGAS', 'RDWORTIGAS', 'RDWORTIGAS', 'DW GALLERIA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2021-10-17 19:48:56', '2022-01-09 23:15:41', NULL),
(132, 'BEYOND THE BOX.VISTA MALL.NOMO.RTL', 'BEYOND THE BOX VISTA MALL NOMO RTL', 'GBTBNOMO', 'BTBNOMO', 'TBTBNOMO', 'TBTBNOMO', 'RBTBNOMO', 'RBTBNOMO', 'BTB NOMO', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2021-10-18 18:20:38', '2021-10-18 19:11:47', NULL),
(133, 'DIGITAL WALKER.ROBINSONS.LA UNION.RTL', 'DIGITAL WALKER ROBINSONS LA UNION RTL', 'GDWLAUNION', 'DWLAUNION', 'TDWLAUNION', 'TDWLAUNION', 'RDWLAUNION', 'RDWLAUNION', 'DW LA UNION', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2021-10-18 18:21:33', NULL, NULL),
(134, 'MOMAX.LAZADA.FBD.ONL', 'MOMAX LAZADA FBD', 'GMOMAXLAZADAFBD', 'DIGITS', 'TMOMAXLAZADAFBD', 'TMOMAXLAZADAFBD', 'RMOMAXLAZADAFBD', 'RMOMAXLAZADAFBD', 'LAZADA MOMAX FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', NULL, NULL, '2021-10-19 08:56:07', '2021-10-19 18:43:30', NULL),
(135, 'MOMAX.LAZADA.FBV.ONL', 'MOMAX LAZADA FBV', 'GLAZADAMOMAXFBV', 'LAZADAMOMAXFBV', NULL, NULL, NULL, NULL, 'LAZADA MOMAX FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2021-10-19 09:04:28', NULL, NULL),
(136, 'SOUNDPEATS.SHOPEE.FRA', 'SOUNDPEATS SHOPEE FRA', 'GSHPESOUNDPEATS', 'SHPESOUNDPEATS', 'TSHPESOUNDPEATS', 'TSHPESOUNDPEATS', 'RSHPESOUNDPEATS', 'RSHPESOUNDPEATS', 'SHPE SOUNDPEATS', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 4, 0, 'ACTIVE', NULL, NULL, '2021-11-02 19:36:50', '2022-02-15 17:33:41', NULL),
(137, 'DIGITAL WALKER.SM.MEGAMALL.RTL', 'DIGITAL WALKER SM MEGAMALL RTL', 'GDWMANDALUYONG', 'DWMANDALUYONG', 'TDWMANDALUYONG', 'TDWMANDALUYONG', 'RDWMANDALUYONG', 'RDWMANDALUYONG', 'DW SM MEGAMALL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2021-11-08 23:16:22', '2021-12-29 04:35:45', NULL),
(138, 'POP UP STORE.SOLAIRE.DIGITAL WALKER.RTL', 'POP UP STORE SOLAIRE DIGITAL WALKER RTL', 'GDWSOLAIRE', 'DWSOLAIRE', 'TDWSOLAIRE', 'TDWSOLAIRE', 'RDWSOLAIRE', 'RDWSOLAIRE', 'DW SOLAIRE', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2021-11-11 16:50:05', NULL, NULL),
(139, 'BLUESPACE HQ', 'BLUESPACE HQ', 'BLUESPACE', 'BLUESPACE', 'BLUESPACET', 'BLUESPACET', NULL, NULL, 'BLUESPACE HQ', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 4, 0, 'ACTIVE', NULL, NULL, '2021-12-03 17:55:15', '2021-12-15 18:45:15', NULL),
(140, 'DIGITAL WALKER.AYALA.ATC.FRA', 'DIGITAL WALKER AYALA ATC FRA', 'GDWAYALAALABANG', 'DWAYALAALABANG', 'TDWAYALAALABANG', 'TDWAYALAALABANG', 'RDWAYALAALABANG', 'RDWAYALAALABANG', 'DW AYALA ALABANG', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 4, 0, 'ACTIVE', NULL, NULL, '2022-01-05 22:07:58', '2022-01-06 00:05:05', NULL),
(141, 'DIGITAL WALKER.KCC MALL.ZAMBOANGA.RTL', 'DIGITAL WALKER KCC MALL ZAMBOANGA RTL', 'GDWKCCMALL', 'DWKCCMALL', 'TDWKCCMALL', 'TDWKCCMALL', 'RDWKCCMALL', 'RDWKCCMALL', 'DW KCC MALL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2022-01-10 18:12:52', NULL, NULL),
(142, 'AUDIOENGINE.LAZADA.FBD.ONL', 'AUDIOENGINE LAZADA FBD', 'GLAZADAAUDIOENG', 'LAZADAAUDIOENG', 'TLAZADAAUDIOENG', 'TLAZADAAUDIOENG', 'RLAZADAAUDIOENG', 'RLAZADAAUDIOENG', 'LAZADA AUDIOENGINE FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', NULL, NULL, '2022-01-10 23:49:34', '2022-01-12 22:38:40', NULL),
(143, 'AUDIOENGINE.LAZADA.FBV.ONL', 'AUDIOENGINE LAZADA FBV', 'GLAZAUDFBV', 'LAZAUDFBV', NULL, NULL, NULL, NULL, 'AUDIOENGINE LAZADA FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2022-01-11 01:04:56', NULL, NULL),
(144, 'HIDRATESPARK.LAZADA.FBD.ONL', 'HIDRATESPARK LAZADA FBD', 'GLAZADAHDRTESPRK', 'LAZADAHDRTESPRK', 'TLAZADAHDRTESPRK', 'TLAZADAHDRTESPRK', 'RLAZADAHDRTESPRK', 'RLAZADAHDRTESPRK', 'LAZADA HIDRATESPARK FBD', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', NULL, NULL, '2022-01-12 22:26:21', NULL, NULL),
(145, 'HIDRATESPARK.SHOPEE.FBD.ONL', 'HIDRATESPARK SHOPEE FBD', 'GSHOPEEHDRTESPRK', 'SHOPEEHDRTESPRK', 'TSHOPEEHDRTESPRK', 'TSHOPEEHDRTESPRK', 'RSHOPEEHDRTESPRK', 'RSHOPEEHDRTESPRK', 'HIDRATESPARK SHOPEE FBD', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', NULL, NULL, '2022-01-12 22:29:25', NULL, NULL),
(146, 'HIDRATESPARK.LAZADA.FBV.ONL', 'HIDRATESPARK LAZADA FBV', 'GLAZHIDFBV', 'LAZHIDFBV', NULL, NULL, NULL, NULL, 'HIDRATESPARK LAZADA FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2022-01-12 22:31:58', NULL, NULL),
(147, 'ASUS PHILIPPINES CORPORATION.CRP', 'ASUS PHILIPPINES CORPORATION', 'GASUSPHILIPPINESCORPORATION', 'GASUSPHILIPPINESCORPORATION', NULL, NULL, NULL, NULL, 'ASUS PHILIPPINES CORPORATION', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(148, 'AUTOMOBILE CENTRAL ENTERPRISE INC.CRP', 'AUTOMOBILE CENTRAL ENTERPRISE INC', 'GAUTOMOBILECENTRALENTERPRISEINC', 'GAUTOMOBILECENTRALENTERPRISEINC', NULL, NULL, NULL, NULL, 'AUTOMOBILE CENTRAL ENTERPRISE INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(149, 'AZEUS SYSTEMS PHILIPPINES LIMITED.OUT', 'AZEUS SYSTEMS PHILIPPINES LIMITED', 'GAZEUSSYSTEMSPHILIPPINESLIMITED', 'GAZEUSSYSTEMSPHILIPPINESLIMITED', NULL, NULL, NULL, NULL, 'AZEUS SYSTEMS PHILIPPINES LIMITED', 'DISTRI', NULL, 'DISTRI', 7, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(150, 'BANK OF CHINA (HONGKONG) LIMITED.CRP', 'BANK OF CHINA (HONGKONG) LIMITED', 'GBANKOFCHINAHONGKONGLIMITED', 'GBANKOFCHINAHONGKONGLIMITED', NULL, NULL, NULL, NULL, 'BANK OF CHINA (HONGKONG) LIMITED', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(151, 'BANK OF SINGAPORE LIMITED.CRP', 'BANK OF SINGAPORE LIMITED', 'GBANKOFSINGAPORELIMITED', 'GBANKOFSINGAPORELIMITED', NULL, NULL, NULL, NULL, 'BANK OF SINGAPORE LIMITED', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(152, 'BANK OF THE PHIL.ISLANDS.CRP', 'BANK OF THE PHIL.ISLANDS', 'GBANKOFTHEPHILISLANDS', 'GBANKOFTHEPHILISLANDS', NULL, NULL, NULL, NULL, 'BANK OF THE PHIL.ISLANDS', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(153, 'BERMOR TECHZONE COMPUTER PARTS & AAC.CRP', 'BERMOR TECHZONE COMPUTER PARTS & AAC', 'GBERMORTECHZONECOMPUTERPARTSAAC', 'GBERMORTECHZONECOMPUTERPARTSAAC', NULL, NULL, NULL, NULL, 'BERMOR TECHZONE COMPUTER PARTS & AAC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(154, 'BETTZEIT SOUTHEAST ASIA INC.CRP', 'BETTZEIT SOUTHEAST ASIA INC', 'GBETTZEITSOUTHEASTASIAINC', 'GBETTZEITSOUTHEASTASIAINC', NULL, NULL, NULL, NULL, 'BETTZEIT SOUTHEAST ASIA INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(155, 'CREDIT ACCESS PH FINANCING CO.INC.CRP', 'CREDIT ACCESS PH FINANCING CO.INC', 'GCREDITACCESSPHFINANCINGCOINC', 'GCREDITACCESSPHFINANCINGCOINC', NULL, NULL, NULL, NULL, 'CREDIT ACCESS PH FINANCING CO.INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(156, 'ELECTROCOMPUTER DATA SYSTEM.CRP', 'ELECTROCOMPUTER DATA SYSTEM', 'GELECTROCOMPUTERDATASYSTEM', 'GELECTROCOMPUTERDATASYSTEM', NULL, NULL, NULL, NULL, 'ELECTROCOMPUTER DATA SYSTEM', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(157, 'ENJOYLIFE MARKETING CORP.CRP', 'ENJOYLIFE MARKETING CORP', 'GENJOYLIFEMARKETINGCORP', 'GENJOYLIFEMARKETINGCORP', NULL, NULL, NULL, NULL, 'ENJOYLIFE MARKETING CORP', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(158, 'EUROFRAGANCE PHILIPPINES INC.CRP', 'EUROFRAGANCE PHILIPPINES INC', 'GEUROFRAGANCEPHILIPPINESINC', 'GEUROFRAGANCEPHILIPPINESINC', NULL, NULL, NULL, NULL, 'EUROFRAGANCE PHILIPPINES INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(159, 'F MUSIQUE BANC ENTERPRISES.CRP', 'F MUSIQUE BANC ENTERPRISES', 'GFMUSIQUEBANCENTERPRISES', 'GFMUSIQUEBANCENTERPRISES', NULL, NULL, NULL, NULL, 'F MUSIQUE BANC ENTERPRISES', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(160, 'FASTJOBS PHILIPPINES INC.CRP', 'FASTJOBS PHILIPPINES INC', 'GFASTJOBSPHILIPPINESINC', 'GFASTJOBSPHILIPPINESINC', NULL, NULL, NULL, NULL, 'FASTJOBS PHILIPPINES INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(161, 'FELCRIS DAVAO.FELCRIS CENTRALE.DAVAO.DLR', 'FELCRIS DAVAO.FELCRIS CENTRALE.DAVAO', 'GFELCRISDAVAOFELCRISCENTRALEDAVAO', 'GFELCRISDAVAOFELCRISCENTRALEDAVAO', NULL, NULL, NULL, NULL, 'FELCRIS DAVAO.FELCRIS CENTRALE.DAVAO', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(162, 'FELIPE ROY RUFINO.CRP', 'FELIPE ROY RUFINO', 'GFELIPEROYRUFINO', 'GFELIPEROYRUFINO', NULL, NULL, NULL, NULL, 'FELIPE ROY RUFINO', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(163, 'GADGET HEADZ GADGETS AND ACCESSORIES.DLR', 'GADGET HEADZ GADGETS AND ACCESSORIES', 'GGADGETHEADZGADGETSANDACCESSORIES', 'GGADGETHEADZGADGETSANDACCESSORIES', NULL, NULL, NULL, NULL, 'GADGET HEADZ GADGETS AND ACCESSORIES', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(164, 'GLOBE.HEAD OFFICE.DLR', 'GLOBE.HEAD OFFICE', 'GGLOBEHEADOFFICE', 'GGLOBEHEADOFFICE', NULL, NULL, NULL, NULL, 'GLOBE.HEAD OFFICE', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, 1, '2022-03-25 02:56:21', '2023-03-22 18:52:06', NULL),
(165, 'GRUNDFOS PUMPS (PHILS.), INC.CRP', 'GRUNDFOS PUMPS (PHILS.), INC', 'GGRUNDFOSPUMPSPHILSINC', 'GGRUNDFOSPUMPSPHILSINC', NULL, NULL, NULL, NULL, 'GRUNDFOS PUMPS (PHILS.), INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(166, 'JMB ALBAY GADGETS.DLR', 'JMB ALBAY GADGETS', 'GJMBALBAYGADGETS', 'GJMBALBAYGADGETS', NULL, NULL, NULL, NULL, 'JMB ALBAY GADGETS', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(167, 'JP SAN PEDRO MUSICAL INSTRUMENT TRD.CRP', 'JP SAN PEDRO MUSICAL INSTRUMENT TRD', 'GJPSANPEDROMUSICALINSTRUMENTTRD', 'GJPSANPEDROMUSICALINSTRUMENTTRD', NULL, NULL, NULL, NULL, 'JP SAN PEDRO MUSICAL INSTRUMENT TRD', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(168, 'KEYSYS INC.CRP', 'KEYSYS INC', 'GKEYSYSINC', 'GKEYSYSINC', NULL, NULL, NULL, NULL, 'KEYSYS INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(169, 'KIMSTORE ENTERPRISE CORP.DLR', 'KIMSTORE ENTERPRISE CORP', 'GKIMSTOREENTERPRISECORP', 'GKIMSTOREENTERPRISECORP', NULL, NULL, NULL, NULL, 'KIMSTORE ENTERPRISE CORP', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(170, 'KUMU INC.CRP', 'KUMU INC', 'GKUMUINC', 'GKUMUINC', NULL, NULL, NULL, NULL, 'KUMU INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(171, 'LIKE ME TRADING.CRP', 'LIKE ME TRADING', 'GLIKEMETRADING', 'GLIKEMETRADING', NULL, NULL, NULL, NULL, 'LIKE ME TRADING', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(172, 'METRO PACIFIC HEALTH TECH CORP.CRP', 'METRO PACIFIC HEALTH TECH CORP', 'GMETROPACIFICHEALTHTECHCORP', 'GMETROPACIFICHEALTHTECHCORP', NULL, NULL, NULL, NULL, 'METRO PACIFIC HEALTH TECH CORP', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:21', NULL, NULL),
(173, 'NEST MANILA HOME APPLIANCES TRADING.DLR', 'NEST MANILA HOME APPLIANCES TRADING', 'GNESTMANILAHOMEAPPLIANCESTRADING', 'GNESTMANILAHOMEAPPLIANCESTRADING', NULL, NULL, NULL, NULL, 'NEST MANILA HOME APPLIANCES TRADING', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, 73, '2022-03-25 02:56:22', '2022-12-19 17:56:05', NULL),
(174, 'NEXUS TECHNOLOGIES INC.CRP', 'NEXUS TECHNOLOGIES INC', 'GNEXUSTECHNOLOGIESINC', 'GNEXUSTECHNOLOGIESINC', NULL, NULL, NULL, NULL, 'NEXUS TECHNOLOGIES INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(175, 'NURTUREMED PHARMA, INC.CRP', 'NURTUREMED PHARMA, INC', 'GNURTUREMEDPHARMAINC', 'GNURTUREMEDPHARMAINC', NULL, NULL, NULL, NULL, 'NURTUREMED PHARMA, INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(176, 'OR1 MARKETING MANAGEMENT.CRP', 'OR1 MARKETING MANAGEMENT', 'GOR1MARKETINGMANAGEMENT', 'GOR1MARKETINGMANAGEMENT', NULL, NULL, NULL, NULL, 'OR1 MARKETING MANAGEMENT', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(177, 'PC HUB COMPUTER TECHNOLOGY, INC.DLR', 'PC HUB COMPUTER TECHNOLOGY, INC', 'GPCHUBCOMPUTERTECHNOLOGYINC', 'GPCHUBCOMPUTERTECHNOLOGYINC', NULL, NULL, NULL, NULL, 'PC HUB COMPUTER TECHNOLOGY, INC', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(178, 'PROFESSIONAL CREATIVES AND TRADE MARKETERS INC.CRP', 'PROFESSIONAL CREATIVES AND TRADE MARKETERS INC', 'GPROFESSIONALCREATIVESANDTRADEMARKETERSINC', 'GPROFESSIONALCREATIVESANDTRADEMARKETERSINC', NULL, NULL, NULL, NULL, 'PROFESSIONAL CREATIVES AND TRADE MARKETERS INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL);
INSERT INTO `stores` (`id`, `bea_so_store_name`, `bea_mo_store_name`, `pos_warehouse`, `pos_warehouse_branch`, `pos_warehouse_transit`, `pos_warehouse_transit_branch`, `pos_warehouse_rma`, `pos_warehouse_rma_branch`, `pos_warehouse_name`, `doo_subinventory`, `sit_subinventory`, `org_subinventory`, `channel_id`, `customer_type_id`, `locations_id`, `sts_group`, `allowed_bulk_receiving`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(179, 'RUSTANS.MAKATI.DLR', 'RUSTANS.MAKATI', 'GRUSTANSMAKATI', 'GRUSTANSMAKATI', NULL, NULL, NULL, NULL, 'RUSTANS.MAKATI', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(180, 'RUSTANS.SHANGRI-LA.EDSA.DLR', 'RUSTANS.SHANGRI-LA.EDSA', 'GRUSTANSSHANGRILAEDSA', 'GRUSTANSSHANGRILAEDSA', NULL, NULL, NULL, NULL, 'RUSTANS.SHANGRI-LA.EDSA', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(181, 'SCHENKER MNL ADMIN COMP CENTER INC.CRP', 'SCHENKER MNL ADMIN COMP CENTER INC', 'GSCHENKERMNLADMINCOMPCENTERINC', 'GSCHENKERMNLADMINCOMPCENTERINC', NULL, NULL, NULL, NULL, 'SCHENKER MNL ADMIN COMP CENTER INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(182, 'SEAOIL PHILIPPINES INC.CRP', 'SEAOIL PHILIPPINES INC', 'GSEAOILPHILIPPINESINC', 'GSEAOILPHILIPPINESINC', NULL, NULL, NULL, NULL, 'SEAOIL PHILIPPINES INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(183, 'SHOPHERO TV INC.DLR', 'SHOPHERO TV INC', 'GSHOPHEROTVINC', 'GSHOPHEROTVINC', NULL, NULL, NULL, NULL, 'SHOPHERO TV INC', 'DISTRI', NULL, 'DISTRI', 10, 2, NULL, NULL, 0, 'ACTIVE', 1, 1, '2022-03-25 02:56:22', '2023-03-02 23:11:41', NULL),
(184, 'START UP ENTERPRISES.CRP', 'START UP ENTERPRISES', 'GSTARTUPENTERPRISES', 'GSTARTUPENTERPRISES', NULL, NULL, NULL, NULL, 'START UP ENTERPRISES', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(185, 'TATRONIC GROUP, INC.DLR', 'TATRONIC GROUP, INC', 'GTATRONICGROUPINC', 'GTATRONICGROUPINC', NULL, NULL, NULL, NULL, 'TATRONIC GROUP, INC', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(186, 'TECHBYTRX COMPUTER PARTS AND ACCESSORIES TRADING.DLR', 'TECHBYTRX COMPUTER PARTS AND ACCESSORIES TRADING', 'GTECHBYTRXCOMPUTERPARTSANDACCESSORIESTRADING', 'GTECHBYTRXCOMPUTERPARTSANDACCESSORIESTRADING', NULL, NULL, NULL, NULL, 'TECHBYTRX COMPUTER PARTS AND ACCESSORIES TRADING', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(187, 'THE INBOX STORE.SM.CLARK PAMPANGA.DLR', 'THE INBOX STORE.SM.CLARK PAMPANGA', 'GTHEINBOXSTORESMCLARKPAMPANGA', 'GTHEINBOXSTORESMCLARKPAMPANGA', NULL, NULL, NULL, NULL, 'THE INBOX STORE.SM.CLARK PAMPANGA', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(188, 'THE PHILIPPINE AMERICAN LIFE AND GENERAL INSURANCE COMPANY.CRP', 'THE PHILIPPINE AMERICAN LIFE AND GENERAL INSURANCE COMPANY', 'GTHEPHILIPPINEAMERICANLIFEANDGENERALINSURANCECOMPANY', 'GTHEPHILIPPINEAMERICANLIFEANDGENERALINSURANCECOMPANY', NULL, NULL, NULL, NULL, 'THE PHILIPPINE AMERICAN LIFE AND GENERAL INSURANCE COMPANY', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(189, 'TL SALES AND MANAGEMENT SERVICES, INC.DLR', 'TL SALES AND MANAGEMENT SERVICES INC DLR', 'GTLSALESANDMANAGEMENTSERVICES', 'GTLSALESANDMANAGEMENTSERVICES', NULL, NULL, NULL, NULL, 'TL SALES AND MANAGEMENT SERVICES INC DLR', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, 1, '2022-03-25 02:56:22', '2022-08-16 01:27:42', NULL),
(190, 'TOTAL BUSINESS AGENCY.CRP', 'TOTAL BUSINESS AGENCY', 'GTOTALBUSINESSAGENCY', 'GTOTALBUSINESSAGENCY', NULL, NULL, NULL, NULL, 'TOTAL BUSINESS AGENCY', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(191, 'VICTORIA COURT.HILLCREST.CRP', 'VICTORIA COURT.HILLCREST', 'GVICTORIACOURTHILLCREST', 'GVICTORIACOURTHILLCREST', NULL, NULL, NULL, NULL, 'VICTORIA COURT.HILLCREST', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(192, 'WARNER MUSIC PHILIPPINES INC.CRP', 'WARNER MUSIC PHILIPPINES INC', 'GWARNERMUSICPHILIPPINESINC', 'GWARNERMUSICPHILIPPINESINC', NULL, NULL, NULL, NULL, 'WARNER MUSIC PHILIPPINES INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(193, 'WESTCON SOLUTIONS PHILIPPINES INC.CRP', 'WESTCON SOLUTIONS PHILIPPINES INC', 'GWESTCONSOLUTIONSPHILIPPINESINC', 'GWESTCONSOLUTIONSPHILIPPINESINC', NULL, NULL, NULL, NULL, 'WESTCON SOLUTIONS PHILIPPINES INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(194, 'WORLD SOLUTION TECHNOLOGY INC.CRP', 'WORLD SOLUTION TECHNOLOGY INC', 'GWORLDSOLUTIONTECHNOLOGYINC', 'GWORLDSOLUTIONTECHNOLOGYINC', NULL, NULL, NULL, NULL, 'WORLD SOLUTION TECHNOLOGY INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(195, 'XUNDD TRADING CORPORATION.DLR', 'XUNDD TRADING CORPORATION', 'GXUNDDTRADINGCORPORATION', 'GXUNDDTRADINGCORPORATION', NULL, NULL, NULL, NULL, 'XUNDD TRADING CORPORATION', 'DISTRI', NULL, 'DISTRI', 10, NULL, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-03-25 02:56:22', NULL, NULL),
(196, 'ABENSON.HEAD OFFICE.CON', 'ABENSON HEAD OFFICE CON', 'GABENSONHOFCCON', 'GABENSONHOFCCON', NULL, NULL, NULL, NULL, 'ABENSON HEAD OFFICE CON', 'ABENSON', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2022-03-30 09:52:23', '2022-03-30 10:16:21', NULL),
(197, 'R.O.X.AYALA.BONIFACIO HIGH STREET.CON', 'R.O.X. AYALA BONIFACIO HIGH STREET CON', 'GROXAYALABHSCON', 'GROXAYALABHSCON', NULL, NULL, NULL, NULL, 'R.O.X. AYALA BONIFACIO HIGH STREET CON', 'ROX', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2022-03-30 09:54:56', '2022-03-30 10:16:45', NULL),
(198, 'MONEYGURU PHILIPPINES CORP.CRP', 'MONEYGURU PHILIPPINES CORP', 'MONEYGURUPHILIPPINESCORP', 'MONEYGURUPHILIPPINESCORP', NULL, NULL, NULL, NULL, 'MONEYGURU PHILIPPINES CORP', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', NULL, NULL, '2022-04-12 18:50:38', NULL, NULL),
(199, 'PURE MOVEMENT LAB.CON', 'PURE MOVEMENT LAB CON', 'PUREMOVEMENTLABCON', 'PUREMOVEMENTLABCON', NULL, NULL, NULL, NULL, 'PURE MOVEMENT LAB CON', 'PURE MOVE', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', NULL, 1, '2022-04-12 23:35:46', '2022-07-21 16:48:48', NULL),
(200, 'SM DEPARTMENT STORE.SM.MEGAMALL.CON', 'SM DEPARTMENT STORE SM MEGAMALL CON', 'SMDEPTSMMEGAMALLCON', 'SMDEPTSMMEGAMALLCON', NULL, NULL, NULL, NULL, 'SM DEPT STORE SM MEGAMALL CON', 'SM DEPT', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', NULL, 1, '2022-04-12 23:39:59', '2022-06-21 16:40:59', NULL),
(201, 'LISTENING ROOM.SM.MEGAMALL.DLR', 'LISTENING ROOM SM MEGAMALL', 'LISTENINGROOMSMMEGAMALL', 'LISTENINGROOMSMMEGAMALL', NULL, NULL, NULL, NULL, 'LISTENING ROOM SM MEGAMALL', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', NULL, 1, '2022-04-17 18:37:27', '2023-03-19 18:33:39', NULL),
(202, 'XIAOMI.AYALA.CIRCUIT MAKATI.RTL', 'XIAOMI AYALA CIRCUIT MAKATI RTL', 'GXMICIRCUITMKTI', 'XMICIRCUITMKTI', 'TXMICIRCUITMKTI', 'TXMICIRCUITMKTI', 'RXMICIRCUITMKTI', 'RXMICIRCUITMKTI', 'XIAOMI CIRCUIT MAKATI', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', NULL, NULL, '2022-04-20 23:27:17', NULL, NULL),
(203, 'NOTHING.LAZADA.FBD.ONL', 'NOTHING LAZADA FBD', 'GNOTHINGLAZADA', 'NOTHINGLAZADA', 'TNOTHINGLAZADA', 'TNOTHINGLAZADA', 'RNOTHINGLAZADA', 'RNOTHINGLAZADA', 'NOTHING LAZADA', 'LAZADA', 'LAZADA FBD', 'ECOM', 4, 7, 1, 3, 0, 'ACTIVE', 1, 1, '2022-04-27 18:31:36', '2022-06-30 18:00:44', NULL),
(204, 'NOTHING.SHOPEE.FBD.ONL', 'NOTHING SHOPEE FBD', 'GNOTHINGSHOPEE', 'NOTHINGSHOPEE', 'TNOTHINGSHOPEE', 'TNOTHINGSHOPEE', 'RNOTHINGSHOPEE', 'RNOTHINGSHOPEE', 'NOTHING SHOPEE', 'SHOPEE', 'SHOPEE FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, 73, '2022-04-27 18:33:52', '2022-06-29 17:22:23', NULL),
(205, 'NOTHING.LAZADA.FBV.ONL', 'NOTHING LAZADA FBV', 'GNOTHINGLAZADAFBV', 'NOTHINGLAZADAFBV', NULL, NULL, NULL, NULL, 'NOTHING LAZADA FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-04-27 18:41:51', NULL, NULL),
(206, 'SPECK.LAZADA.FBV.ONL', 'SPECK LAZADA FBV', 'GLAZSPECKFBV', 'LAZSPECKFBV', NULL, NULL, NULL, NULL, 'SPECK LAZADA FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-05-11 19:50:39', NULL, NULL),
(207, 'CLEARANCE.DW ROB ANTIPOLO 2022.RTL', 'CLEARANCE DW ROB ANTIPOLO 2022 RTL', 'GDWROBANTIPOLO', 'DWROBANTIPOLO', 'TDWROBANTIPOLO', 'TDWROBANTIPOLO', 'RDWROBANTIPOLO', 'RDWROBANTIPOLO', 'DW ROB ANTIPOLO', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 0, 'INACTIVE', 1, 188, '2022-05-19 02:45:57', '2023-09-06 01:26:37', NULL),
(208, 'EL OBSERVATORIO DE MANILA, INC.CRP', 'EL OBSERVATORIO DE MANILA, INC', 'ELOBSERVATORIODEMANILA', 'ELOBSERVATORIODEMANILA', NULL, NULL, NULL, NULL, 'EL OBSERVATORIO DE MANILA, INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-05 22:03:30', NULL, NULL),
(209, 'SM APPLIANCE.SM.MEGAMALL.CON', 'SM APPLIANCE SM MEGAMALL CON', 'GSMAPPMEGAMALL', 'SMAPPMEGAMALL', NULL, NULL, NULL, NULL, 'SM APP MEGAMALL CON', 'SM APP', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-08 19:09:23', NULL, NULL),
(210, 'DIGITAL WALKER.AYALA TRIANGLE.SERENE.RTL', 'DIGITAL WALKER AYALA TRIANGLE SERENE RTL', 'GDWAYALASERENE', 'DWAYALASERENE', 'TDWAYALASERENE', 'TDWAYALASERENE', 'RDWAYALASERENE', 'RDWAYALASERENE', 'DW AYALA SERENE', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 0, 'ACTIVE', 1, NULL, '2022-06-10 01:34:55', NULL, NULL),
(211, 'UNIGLOBE TRAVELWARE CO. INC.CON', 'UNIGLOBE TRAVELWARE CO INC CON', 'GUNIGLOBE', 'UNIGLOBE', NULL, NULL, NULL, NULL, 'UNIGLOBE TRAVELWARE CO INC CON', 'TRAVELCLUB', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, 1, '2022-06-21 16:08:32', '2022-06-21 16:09:20', NULL),
(212, 'SM DEPARTMENT STORE.SM.MAKATI.CON', 'SM DEPARTMENT STORE SM MAKATI CON', 'GSMMAKATICON', 'GSMMAKATICON', NULL, NULL, NULL, NULL, 'SM DEPARTMENT STORE SM MAKATI CON', 'SM DEPT', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 16:30:52', NULL, NULL),
(213, 'SM DEPARTMENT STORE.SM.MANILA.CON', 'SM DEPARTMENT STORE SM MANILA CON', 'GSMMANILACON', 'GSMMANILACON', NULL, NULL, NULL, NULL, 'SM DEPARTMENT STORE SM MANILA CON', 'SM DEPT', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 16:30:52', NULL, NULL),
(214, 'SM DEPARTMENT STORE.SM.MARIKINA.CON', 'SM DEPARTMENT STORE SM MARIKINA CON', 'GSMMARIKINACON', 'GSMMARIKINACON', NULL, NULL, NULL, NULL, 'SM DEPARTMENT STORE SM MARIKINA CON', 'SM DEPT', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 16:30:52', NULL, NULL),
(215, 'SM DEPARTMENT STORE.SM.MALL OF ASIA.CON', 'SM DEPARTMENT STORE SM MALL OF ASIA CON', 'GSMMOACON', 'GSMMOACON', NULL, NULL, NULL, NULL, 'SM DEPARTMENT STORE SM MALL OF ASIA CON', 'SM DEPT', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 16:30:52', NULL, NULL),
(216, 'SM DEPARTMENT STORE.SM.NORTH EDSA.CON', 'SM DEPARTMENT STORE SM NORTH EDSA CON', 'GSMNORTHEDSACON', 'GSMNORTHEDSACON', NULL, NULL, NULL, NULL, 'SM DEPARTMENT STORE SM NORTH EDSA CON', 'SM DEPT', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 16:30:52', NULL, NULL),
(217, 'SM DEPARTMENT STORE.SM.SOUTHMALL.CON', 'SM DEPARTMENT STORE SM SOUTHMALL CON', 'GSMSOUTHMALLCON', 'GSMSOUTHMALLCON', NULL, NULL, NULL, NULL, 'SM DEPARTMENT STORE SM SOUTHMALL CON', 'SM DEPT', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 16:30:53', NULL, NULL),
(218, 'TOBY\'S.AYALA.GLORIETTA 4.CON', 'TOBY\'S AYALA GLORIETTA 4 CON', 'GAYALAGLORIETTA4', 'GAYALAGLORIETTA4', NULL, NULL, NULL, NULL, 'TOBY\'S AYALA GLORIETTA 4 CON', 'TOBYS', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 17:49:21', NULL, NULL),
(219, 'TOBY\'S.SM.MALL OF ASIA.CON', 'TOBY\'S SM MALL OF ASIA CON', 'GSMMALLOFASIA', 'GSMMALLOFASIA', NULL, NULL, NULL, NULL, 'TOBY\'S SM MALL OF ASIA CON', 'TOBYS', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 17:49:21', NULL, NULL),
(220, 'TOBY\'S.SM.THE BLOCK.CON', 'TOBY\'S SM THE BLOCK CON', 'GSMTHEBLOCK', 'GSMTHEBLOCK', NULL, NULL, NULL, NULL, 'TOBY\'S SM THE BLOCK CON', 'TOBYS', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 17:49:21', NULL, NULL),
(221, 'TOBY\'S.AYALA.TRINOMA.CON', 'TOBY\'S AYALA TRINOMA CON', 'GAYALATRINOMA', 'GAYALATRINOMA', NULL, NULL, NULL, NULL, 'TOBY\'S AYALA TRINOMA CON', 'TOBYS', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 17:49:21', NULL, NULL),
(222, 'TOBY\'S.AYALA.GLORIETTA 2.CON', 'TOBY\'S AYALA GLORIETTA 2 CON', 'GAYALAGLORIETTA2', 'GAYALAGLORIETTA2', NULL, NULL, NULL, NULL, 'TOBY\'S AYALA GLORIETTA 2 CON', 'TOBYS', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 17:49:21', NULL, NULL),
(223, 'TOBY\'S.SM.SAN LAZARO.CON', 'TOBY\'S SM SAN LAZARO CON', 'GSMSANLAZARO', 'GSMSANLAZARO', NULL, NULL, NULL, NULL, 'TOBY\'S SM SAN LAZARO CON', 'TOBYS', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 17:49:22', NULL, NULL),
(224, 'TOBY\'S.SM.AURA.CON', 'TOBY\'S SM AURA CON', 'GSMAURA', 'GSMAURA', NULL, NULL, NULL, NULL, 'TOBY\'S SM AURA CON', 'TOBYS', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 17:49:22', NULL, NULL),
(225, 'TOBY\'S.SM.MEGAMALL.CON', 'TOBY\'S SM MEGAMALL CON', 'GSMMEGAMALL', 'GSMMEGAMALL', NULL, NULL, NULL, NULL, 'TOBY\'S SM MEGAMALL CON', 'TOBYS', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 17:49:22', NULL, NULL),
(226, 'TOBY\'S.SHANGRI-LA.EDSA.CON', 'TOBY\'S SHANGRI-LA EDSA CON', 'GSHANGRILAEDSA', 'GSHANGRILAEDSA', NULL, NULL, NULL, NULL, 'TOBY\'S SHANGRI-LA EDSA CON', 'TOBYS', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-21 17:49:22', NULL, NULL),
(227, 'ECOM.DIGITS.FBD.ONL', 'ECOM DIGITS FBD', 'GECOMFBD', 'ECOMFBD', 'TECOMFBD', 'TECOMFBD', 'RECOMFBD', 'RECOMFBD', 'ECOM FBD', NULL, 'ECOM FBD', 'ECOM', 4, 7, NULL, 3, 0, 'ACTIVE', 1, NULL, '2022-06-21 23:32:38', NULL, NULL),
(228, 'ESP COMMERCIAL.CRP', 'ESP COMMERCIAL', 'GESPCOMMERCIAL', 'GESPCOMMERCIAL', NULL, NULL, NULL, NULL, 'ESP COMMERCIAL', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-06-22 00:05:10', NULL, NULL),
(229, 'ASTRAL DATA SYSTEMS INC.CRP', 'ASTRAL DATA SYSTEMS INC', 'GASTRALDATASYSTEMSINC', 'GASTRALDATASYSTEMSINC', NULL, NULL, NULL, NULL, 'ASTRAL DATA SYSTEMS INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-07-07 23:05:23', NULL, NULL),
(230, 'IONTECH INC.CRP', 'IONTECH INC', 'GIONTECHINC', 'GIONTECHINC', NULL, NULL, NULL, NULL, 'IONTECH INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-07-12 23:41:54', NULL, NULL),
(231, 'PHILIPPINE RED CROSS.CRP', 'PHILIPPINE RED CROSS', 'GPHILIPPINE RED CROSS', 'GPHILIPPINE RED CROSS', NULL, NULL, NULL, NULL, 'PHILIPPINE RED CROSS', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-07-18 21:55:38', NULL, NULL),
(232, 'I STUDIO.SM.MANILA.CON', 'I STUDIO SM MANILA CON', 'GISTUDIOSMMANILACON', 'GISTUDIOSMMANILACON', NULL, NULL, NULL, NULL, 'I STUDIO SM MANILA CON', 'I STUDIO', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-07-21 16:51:00', NULL, NULL),
(233, 'COMMONWEALTH.HEAD OFFICE.CON', 'COMMONWEALTH HEAD OFFICE CON', 'GCOMMONWEALTHHEADOFFICECON', 'GCOMMONWEALTHHEADOFFICECON', NULL, NULL, NULL, NULL, 'COMMONWEALTH HEAD OFFICE CON', 'COMMONWLTH', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 1, 1, '2022-07-28 00:07:03', '2022-11-09 19:09:56', NULL),
(234, 'TRAVELLERS.MEGAWORLD.NEWPORT.CRP', 'TRAVELLERS MEGAWORLD NEWPORT CRP', 'GTRAVELLERSMEGAWORLDNEWPORTCRP', 'GTRAVELLERSMEGAWORLDNEWPORTCRP', NULL, NULL, NULL, NULL, 'TRAVELLERS MEGAWORLD NEWPORT CRP', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-07-31 23:24:18', NULL, NULL),
(235, 'SMART COMMUNICATIONS, INC.CRP', 'SMART COMMUNICATIONS INC', 'GSMARTCOMMS', 'GSMARTCOMMS', NULL, NULL, NULL, NULL, 'SMART COMMUNICATIONS INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-09-06 23:35:07', NULL, NULL),
(236, 'SHOPEE.CRP', 'SHOPEE CRP', 'GSHOPEECRP', 'GSHOPEECRP', NULL, NULL, NULL, NULL, 'SHOPEE CRP', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-09-11 19:29:03', NULL, NULL),
(237, 'DIGITAL WALKER.SM.TANZA CAVITE.RTL', 'DIGITAL WALKER SM TANZA CAVITE RTL', 'GDWSMTANZA', 'DWSMTANZA', 'TDWSMTANZA', 'TDWSMTANZA', 'RDWSMTANZA', 'RDWSMTANZA', 'DW SM TANZA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2022-09-21 21:46:46', NULL, NULL),
(238, 'ACEFAST.LAZADA.FBV.ONL', 'ACEFAST LAZADA FBV', 'LAZACEFBV', 'LAZACEFBV', NULL, NULL, NULL, NULL, 'ACEFAST LAZADA FBV', 'LAZADA', NULL, 'ECOM', 4, 7, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-09-28 16:27:53', NULL, NULL),
(239, 'INDIEVENTS, INC.CRP', 'INDIEVENTS INC CRP', 'GINDIEVENTSINCCRP', 'GINDIEVENTSINCCRP', NULL, NULL, NULL, NULL, 'INDIEVENTS INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-10-10 02:28:26', NULL, NULL),
(240, 'ATENEO DE DAVAO UNIVERSITY.CRP', 'ATENEO DE DAVAO UNIVERSITY', 'GATENEO', 'GATENEO', NULL, NULL, NULL, NULL, 'ATENEO DE DAVAO UNIVERSITY', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 73, NULL, '2022-10-17 01:16:31', NULL, NULL),
(241, 'DIGITAL WALKER.EVIA MALL.LAS PINAS.RTL', 'DIGITAL WALKER EVIA MALL LAS PINAS RTL', 'GDWEVIAMALL', 'DWEVIAMALL', 'TDWEVIAMALL', 'TDWEVIAMALL', 'RDWEVIAMALL', 'RDWEVIAMALL', 'DW EVIA MALL', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2022-10-20 22:30:19', NULL, NULL),
(242, 'DIGITAL WALKER.ROBINSONS.ANTIPOLO.RTL', 'DIGITAL WALKER ROBINSONS ANTIPOLO RTL', 'GDWRANTIPOLO', 'DWRANTIPOLO', 'TDWRANTIPOLO', 'TDWRANTIPOLO', 'RDWRANTIPOLO', 'RDWRANTIPOLO', 'DW ROBINSON ANTIPOLO', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 1, '2022-10-20 22:40:14', '2022-10-20 22:43:40', NULL),
(243, 'G-MAGMASTER CORPORATION.DLR', 'G-MAGMASTER CORPORATION', 'GMAGMASTERCORPORATION', 'GMAGMASTERCORPORATION', NULL, NULL, NULL, NULL, 'G-MAGMASTER CORPORATION', 'DISTRI', NULL, 'DISTRI', 10, 3, NULL, NULL, 0, 'ACTIVE', 1, 1, '2022-10-27 01:14:00', '2023-06-20 19:41:13', NULL),
(244, 'CLEARANCE.SMX.MOA.RTL', 'CLEARANCE SMX MOA RTL', 'GSMXMOA', 'SMXMOA', 'TSMXMOA', 'TSMXMOA', 'RSMXMOA', 'RSMXMOA', 'SMX MOA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2022-11-03 23:02:13', NULL, NULL),
(245, 'XIAOMI.MITSUKOSHI.BGC.RTL', 'XIAOMI MITSUKOSHI BGC RTL', 'GXMIMITSUKOSHI', 'XMIMITSUKOSHI', 'TXMIMITSUKOSHI', 'TXMIMITSUKOSHI', 'RXMIMITSUKOSHI', 'RXMIMITSUKOSHI', 'XIOAMI MITSUKOSHI', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2022-11-03 23:45:12', NULL, NULL),
(246, 'OPEN SOURCE.MITSUKOSHI.BGC.RTL', 'OPEN SOURCE MITSUKOSHI BGC RTL', 'GBTBMITSUKOSHI', 'BTBMITSUKOSHI', 'TBTBMITSUKOSHI', 'TBTBMITSUKOSHI', 'RBTBMITSUKOSHI', 'RBTBMITSUKOSHI', 'BTB MITSUKOSHI', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 73, '2022-11-03 23:46:11', '2024-02-22 19:31:39', NULL),
(247, 'XIAOMI.AYALA.VERTIS NORTH.RTL', 'XIAOMI AYALA VERTIS NORTH RTL', 'GXMIAYALAVERTIS', 'XMIAYALAVERTIS', 'TXMIAYALAVERTIS', 'TXMIAYALAVERTIS', 'RXMIAYALAVERTIS', 'RXMIAYALAVERTIS', 'XIOAMI AYALA VERTIS', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2022-11-03 23:47:17', NULL, NULL),
(248, 'BEYOND THE BOX.CITYFRONT.CLARK.RTL', 'BEYOND THE BOX CITYFRONT CLARK RTL', 'GBTBCITYFRONT', 'BTBCITYFRONT', 'TBTBCITYFRONT', 'TBTBCITYFRONT', 'RBTBCITYFRONT', 'RBTBCITYFRONT', 'BTB CITYFRONT CLARK', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2022-11-04 00:03:23', NULL, NULL),
(249, 'BEYOND THE BOX.ROBINSONS.MALOLOS.RTL', 'BEYOND THE BOX ROBINSONS MALOLOS RTL', 'GBTBROBMALOLOS', 'BTBROBMALOLOS', 'TBTBROBMALOLOS', 'TBTBROBMALOLOS', 'RBTBROBMALOLOS', 'RBTBROBMALOLOS', 'BTB ROBINSON MALOLOS', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2022-11-04 00:04:25', NULL, NULL),
(250, 'DIGITAL WALKER.POP UP MITSUKOSHI.BGC.RTL', 'DIGITAL WALKER POP UP MITSUKOSHI BGC RTL', 'GDWPOPMITSUKOSHI', 'DWPOPMITSUKOSHI', 'TDWPOPMITSUKOSHI', 'TDWPOPMITSUKOSHI', 'RDWPOPMITSUKOSHI', 'RDWPOPMITSUKOSHI', 'DW POP MITSUKOSHI', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2022-11-13 21:47:28', NULL, NULL),
(251, 'TIGER RESORT, LEISURE AND ENT., INC.CRP', 'TIGER RESORT, LEISURE AND ENT., INC', 'GTIGERRESORTLEISUREENTINC', 'GTIGERRESORTLEISUREENTINC', NULL, NULL, NULL, NULL, 'TIGER RESORT, LEISURE AND ENT., INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-12-06 16:36:23', NULL, NULL),
(252, 'GOLDEN PRESTIGE MRKTG, INC.CRP', 'GOLDEN PRESTIGE MRKTG INC', 'GGOLDENPRESTIGEMRKTGINC', 'GGOLDENPRESTIGEMRKTGINC', NULL, NULL, NULL, NULL, 'GOLDEN PRESTIGE MRKTG INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-12-14 01:20:43', NULL, NULL),
(253, 'MC KINSEY & CO. (PHILS.).CRP', 'MC KINSEY & CO. (PHILS.)', 'GMCKINSEYCOPHILS', 'GMCKINSEYCOPHILS', NULL, NULL, NULL, NULL, 'MC KINSEY & CO. (PHILS.)', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2022-12-19 19:47:39', NULL, NULL),
(254, 'MISTER GREY MARKETING INC.DLR', 'MISTER GREY MARKETING INC', 'GMISTERGREYMARKETINGINC', 'GMISTERGREYMARKETINGINC', NULL, NULL, NULL, NULL, 'MISTER GREY MARKETING INC', 'DISTRI', NULL, 'DISTRI', 10, 3, NULL, NULL, 0, 'ACTIVE', 1, 73, '2023-01-12 19:37:43', '2023-01-26 22:04:41', NULL),
(255, 'DIGITAL WALKER.SM.MAKATI.FRA', 'DIGITAL WALKER SM MAKATI FRA', 'GDWSMMAKATI', 'DWSMMAKATI', 'TDWSMMAKATI', 'TDWSMMAKATI', 'RDWSMMAKATI', 'RDWSMMAKATI', 'DW SM MAKATI', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 4, 0, 'ACTIVE', 1, NULL, '2023-01-30 00:50:27', NULL, NULL),
(256, 'UNIGLOBE TRAVELWARE CO. INC.CRP', 'UNIGLOBE TRAVELWARE CO. INC', 'GUNIGLOBETRAVELWARECOINC', 'GUNIGLOBETRAVELWARECOINC', NULL, NULL, NULL, NULL, 'UNIGLOBE TRAVELWARE CO. INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2023-02-02 19:02:37', NULL, NULL),
(257, 'STYLERIGHT GLOBAL CORPORATION.DLR', 'STYLERIGHT GLOBAL CORPORATION', 'GSTYLERIGHTGLOBALCORP', 'GSTYLERIGHTGLOBALCORP', NULL, NULL, NULL, NULL, 'STYLERIGHT GLOBAL CORPORATION', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, 1, '2023-03-10 01:22:19', '2023-03-19 18:32:35', NULL),
(258, 'STEAG STATE POWER INC.CRP', 'STEAG STATE POWER INC', 'GSTEAGSTATEPOWER', 'GSTEAGSTATEPOWER', NULL, NULL, NULL, NULL, 'STEAG STATE POWER INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2023-03-14 22:25:44', NULL, NULL),
(259, 'SERVIER PHILIPPINES, INC.CRP', 'SERVIER PHILIPPINES INC', 'GSERVIERPHILIPPINES', 'GSERVIERPHILIPPINES', NULL, NULL, NULL, NULL, 'SERVIER PHILIPPINES INC', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, 73, '2023-03-14 22:26:37', '2023-03-23 22:37:49', NULL),
(260, 'DIGITAL WALKER.AYALA.GREENBELT 5.RTL', 'DIGITAL WALKER AYALA GREENBELT 5 RTL', 'GDWGREENBELT5', 'DWGREENBELT5', 'TDWGREENBELT', 'TDWGREENBELT', 'DWGREENBELT5RM', 'RDWGREENBELT', 'DW GREENBELT', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2023-03-20 03:02:06', NULL, NULL),
(261, 'DIGITAL WALKER.AYALA.HIGH STREET.RTL', 'DIGITAL WALKER AYALA HIGH STREET RTL', 'GDWHIGHSTREET', 'DWHIGHSTREET', 'TDWHIGHSTREET', 'TDWHIGHSTREET', 'RDWHIGHSTREET', 'RDWHIGHSTREET', 'DW HIGH STREET', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2023-03-20 03:03:41', NULL, NULL),
(262, 'DIGITAL WALKER.ROBINSONS.TACLOBAN.FRA', 'DIGITAL WALKER ROBINSONS TACLOBAN FRA', 'GDWTACLOBAN', 'DWTACLOBAN', 'TDWTACLOBAN', 'TDWTACLOBAN', 'RDWTACLOBAN', 'RDWTACLOBAN', 'DW TACLOBAN', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 20, 0, 'ACTIVE', 1, 1, '2023-03-29 21:57:20', '2023-11-23 19:58:20', NULL),
(263, 'NEWTRENDS INTERNATIONAL CORPORATION.DLR', 'NEWTRENDS INTERNATIONAL CORPORATION', 'GNEWTRENDSINTERNATIONALCORPORATION', 'GNEWTRENDSINTERNATIONALCORPORATION', NULL, NULL, NULL, NULL, 'NEWTRENDS INTERNATIONAL CORPORATION', 'NEWTRENDS', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2023-04-17 19:20:13', NULL, NULL),
(264, 'O&J GLOBAL TRADING.DLR', 'O&J GLOBAL TRADING', 'GO&JGLOBALTRADING', 'GO&JGLOBALTRADING', NULL, NULL, NULL, NULL, 'O&J GLOBAL TRADING', 'GLOBAL TRADING', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2023-04-17 19:21:58', NULL, NULL),
(265, 'IWH COMP., ELEC. GADGETS AND ACC.DLR', 'IWH COMP., ELEC. GADGETS AND ACC', 'GIWHCOMPELECGADGETSANDACC', 'GIWHCOMPELECGADGETSANDACC', NULL, NULL, NULL, NULL, 'IWH COMP., ELEC. GADGETS AND ACC', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2023-04-19 20:03:08', NULL, NULL),
(266, 'BEYOND THE BOX.GREENHILLS.ORTIGAS.RTL', 'BEYOND THE BOX GREENHILLS ORTIGAS RTL', 'GBTBGREENHILLS', 'BTBGREENHILLS', 'TBTBGREENHILLS', 'TBTBGREENHILLS', 'RBTBGREENHILLS', 'RBTBGREENHILLS', 'BTB GREENHILLS', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'INACTIVE', 1, 188, '2023-05-11 00:26:50', '2023-09-06 01:27:21', NULL),
(267, 'DIGITAL WALKER.GREENHILLS.ORTIGAS.RTL', 'DIGITAL WALKER GREENHILLS ORTIGAS RTL', 'GDWGREENHILLS', 'DWGREENHILLS', 'TDWGREENHILLS', 'TDWGREENHILLS', 'RDWGREENHILLS', 'RDWGREENHILLS', 'DW GREENHILLS', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, 73, '2023-05-11 00:28:09', '2023-12-14 19:11:29', NULL),
(268, 'DIGITAL WALKER.ONE.AYALA.FRA', 'DIGITAL WALKER ONE AYALA FRA', 'GDWONEAYALA', 'DWONEAYALA', 'TDWONEAYALA', 'TDWONEAYALA', 'RDWONEAYALA', 'RDWONEAYALA', 'DW ONE AYALA', 'FRANCHISE', NULL, 'FRANCHISE', 2, 6, NULL, 4, 0, 'ACTIVE', 1, 73, '2023-05-11 00:29:07', '2023-11-23 23:56:03', NULL),
(269, 'DIGITAL WALKER.SM.TELABASTAGAN.RTL', 'DIGITAL WALKER SM TELABASTAGAN RTL', 'GDWTELABASTAGAN', 'DWTELABASTAGAN', 'TDWTELABASTAGAN', 'TDWTELABASTAGAN', 'RDWTELABASTAGAN', 'RDWTELABASTAGAN', 'DW TELABASTAGAN', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2023-05-11 00:41:20', NULL, NULL),
(270, 'POLKADOT VENTURES CORPORATION.DLR', 'POLKADOT VENTURES CORPORATION', 'GPOLKADOTVENTURESCORPORATION', 'POLKADOTVENTURESCORPORATION', NULL, NULL, NULL, NULL, 'POLKADOT VENTURES CORPORATION', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, 1, '2023-06-05 22:37:07', '2023-06-05 23:07:02', NULL),
(271, 'BEYOND INNOVATION INC.DLR', 'BEYOND INNOVATION INC', 'GBEYONDINNOVATIONINC', 'BEYONDINNOVATIONINC', NULL, NULL, NULL, NULL, 'BEYOND INNOVATION INC', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, 1, '2023-06-05 22:38:06', '2023-08-07 23:24:57', NULL),
(272, 'CARL ZEISS PHILIPPINES PTE. LTD.CRP', 'CARL ZEISS PHILIPPINES PTE. LTD.CRP', 'GCARLZEISSPHILIPPINESPTELTD', 'GCARLZEISSPHILIPPINESPTELTD', NULL, NULL, NULL, NULL, 'CARL ZEISS PHILIPPINES PTE.LTD', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, 1, '2023-06-16 03:08:48', '2023-06-22 02:27:10', NULL),
(273, 'ALLHOME CORP.STARMALL.LAS PINAS.DLR', 'ALLHOME CORP STARMALL LAS PINAS', 'ALLHOME CORP.STARMALL.LAS PINAS.', 'GALLHOMECORPSTARMALLLASPINAS', NULL, NULL, NULL, NULL, 'ALLHOME CORP STARMALL LAS PINAS', 'DISTRI', NULL, 'DISTRI', 10, 3, NULL, NULL, 0, 'ACTIVE', 1, 1, '2023-06-20 19:47:10', '2023-06-20 19:48:57', NULL),
(274, 'BEYOND THE BOX.AIRPORT.CLARK.RTL', 'BEYOND THE BOX AIRPORT CLARK RTL', 'GBTBAIRPORTCLARK', 'BTBAIRPORTCLARK', 'TBTBAIRPORTCLARK', 'TBTBAIRPORTCLARK', 'RBTBAIRPORTCLARK', 'RBTBAIRPORTCLARK', 'BTB AIRPORT CLARK', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 1, 'ACTIVE', 188, 73, '2023-07-12 19:31:42', '2023-12-19 23:52:13', NULL),
(275, 'DIGITAL WALKER.SM.STO.TOMAS.RTL', 'DIGITAL WALKER SM STO TOMAS RTL', 'GDWSMSTOTOMAS', 'DWSMSTOTOMAS', 'TDWSMSTOTOMAS', 'TDWSMSTOTOMAS', 'RDWSMSTOTOMAS', 'RDWSMSTOTOMAS', 'DW SM STO TOMAS', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 0, 'ACTIVE', 188, 73, '2023-07-12 19:35:25', '2023-10-26 07:41:55', NULL),
(276, 'OPEN SOURCE.ROBINSONS.OPUS.RTL', 'OPEN SOURCE ROBINSONS OPUS RTL', 'GOSOPUS', 'OSOPUS', 'TOSOPUS', 'TOSOPUS', 'ROSOPUS', 'ROSOPUS', 'OS OPUS', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 0, 'ACTIVE', 188, NULL, '2023-07-31 01:24:46', NULL, NULL),
(277, 'OPEN SOURCE.ROBINSONS.CEBU.RTL', 'OPEN SOURCE ROBINSONS CEBU RTL', 'GOSCEBU', 'OSCEBU', 'TOSCEBU', 'TOSCEBU', 'ROSCEBU', 'ROSCEBU', 'OS CEBU', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 0, 'ACTIVE', 188, NULL, '2023-07-31 01:27:10', NULL, NULL),
(278, 'OPEN SOURCE.ROBINSONS.GAPAN.RTL', 'OPEN SOURCE ROBINSONS GAPAN RTL', 'GOSGAPAN', 'OSGAPAN', 'TOSGAPAN', 'TOSGAPAN', 'ROSGAPAN', 'ROSGAPAN', 'OS GAPAN', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 0, 'ACTIVE', 188, NULL, '2023-07-31 01:28:44', NULL, NULL),
(279, 'DIGITAL WALKER.SM.BACOOR.RTL', 'DIGITAL WALKER SM BACOOR RTL', 'GDWSMBACOOR', 'DWSMBACOOR', 'TDWSMBACOOR', 'TDWSMBACOOR', 'RDWSMBACOOR', 'RDWSMBACOOR', 'DW SM BACOOR', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 0, 'ACTIVE', 188, NULL, '2023-07-31 01:30:38', NULL, NULL),
(280, 'DIGITAL WALKER.SM.VALENZUELA.RTL', 'DIGITAL WALKER SM VALENZUELA RTL', 'GDWSMVALENZUELA', 'DWSMVALENZUELA', 'TDWSMVALENZUELA', 'TDWSMVALENZUELA', 'RDWSMVALENZUELA', 'RDWSMVALENZUELA', 'DW SM VALENZUELA', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 0, 'ACTIVE', 188, 73, '2023-07-31 01:32:07', '2023-12-14 19:10:53', NULL),
(281, 'DIGITAL WALKER.AYALA.SOLENAD.RTL', 'DIGITAL WALKER AYALA SOLENAD RTL', 'GDWSOLENAD', 'DWSOLENAD', 'TDWSOLENAD', 'TDWSOLENAD', 'RDWSOLENAD', 'RDWSOLENAD', 'DW SOLENAD', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 0, 'ACTIVE', 188, 1, '2023-07-31 01:33:35', '2023-11-30 19:18:47', NULL),
(282, 'INFINITY LOOP RETAIL INC.DLR', 'INFINITY LOOP RETAIL INC', 'GINFINITYLOOPRETAILINC', 'GINFINITYLOOPRETAILINC', NULL, NULL, NULL, NULL, 'INFINITY LOOP RETAIL INC', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2023-08-01 19:01:55', NULL, NULL),
(283, 'DIGITAL WALKER.SM.ROSARIO.RTL', 'DIGITAL WALKER SM ROSARIO RTL', 'GDWSMROSARIO', 'DWSMROSARIO', 'TDWSMROSARIO', 'TDWSMROSARIO', 'RDWSMROSARIO', 'RDWSMROSARIO', 'DW SM ROSARIO', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 0, 'ACTIVE', 188, 1, '2023-08-07 17:32:38', '2023-10-07 22:34:03', NULL),
(284, 'DIGITAL WALKER.AYALA.CIRCUIT.RTL', 'DIGITAL WALKER AYALA CIRCUIT RTL', 'GDWAYALACIRCUIT', 'DWAYALACIRCUIT', 'TDWAYALACIRCUIT', 'TDWAYALACIRCUIT', 'RDWAYALACIRCUIT', 'RDWAYALACIRCUIT', 'DW AYALA CIRCUIT', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 0, 'ACTIVE', 188, NULL, '2023-08-07 17:34:39', NULL, NULL),
(285, 'DIGITAL WALKER.SM.SAN PEDRO.RTL', 'DIGITAL WALKER SM SAN PEDRO RTL', 'GDWSMSANPEDRO', 'DWSMSANPEDRO', 'TDWSMSANPEDRO', 'TDWSMSANPEDRO', 'RDWSMSANPEDRO', 'RDWSMSANPEDRO', 'DW SM SAN PEDRO', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 0, 'ACTIVE', 188, 1, '2023-08-07 18:19:47', '2023-11-23 19:58:35', NULL),
(286, 'DOCTOR TEXTUS GADGETS AND ACC. SHOP.DLR', 'DOCTOR TEXTUS GADGET AND ACC', 'DOCTORTEXTUSGADGETANDACC', 'DOCTORTEXTUSGADGETANDACC', NULL, NULL, NULL, NULL, 'DOCTOR TEXTUS GADGET AND ACC', NULL, NULL, 'DOCTORTEXTUSGADGETANDACC', 10, 3, NULL, NULL, 0, 'ACTIVE', 188, 1, '2023-09-06 23:00:54', '2023-09-13 20:07:50', NULL),
(287, 'DIGITAL WALKER.ROBINSONS.ERMITA 2F.RTL', 'DIGITAL WALKER ROBINSONS ERMITA 2F RTL', 'GDWERMITA2F', 'DWERMITA2F', 'TDWERMITA2F', 'TDWERMITA2F', 'RDWERMITA2F', 'RDWERMITA2F', 'DW ERMITA 2F', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 0, 'ACTIVE', 188, NULL, '2023-09-12 18:17:01', NULL, NULL),
(288, 'OPEN SOURCE.PARQAL.ASEANA.RTL', 'OPEN SOURCE PARQAL ASEANA RTL', 'GOSASEANA', 'OSASEANA', 'TOSASEANA', 'TOSASEANA', 'ROSASEANA', 'ROSASEANA', 'OS ASEANA', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 0, 'ACTIVE', 188, 1, '2023-09-12 18:18:51', '2023-10-07 22:32:43', NULL),
(289, 'DIGITAL WALKER.LIPAD.CLARK.RTL', 'DIGITAL WALKER LIPAD CLARK RTL', 'GDWLIPADCLARK', 'DWLIPADCLARK', 'TDWLIPADCLARK', 'TDWLIPADCLARK', 'RDWLIPADCLARK', 'RDWLIPADCLARK', 'DW LIPAD CLARK', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 0, 'ACTIVE', 188, NULL, '2023-09-12 19:25:31', NULL, NULL),
(290, 'DIGITAL WALKER.SM.ARANETA CITY.RTL', 'DIGITAL WALKER SM ARANETA CITY RTL', 'GDWSMARANETA', 'DWSMARANETA', 'TDWSMARANETA', 'TDWSMARANETA', 'RDWSMARANETA', 'RDWSMARANETA', 'DW SM ARANETA', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 0, 'ACTIVE', 188, 73, '2023-09-27 18:16:00', '2023-12-14 19:11:06', NULL),
(291, 'GASHAPON.MITSUKOSHI.BGC.RTL', 'GASHAPON MITSUKOSHI BGC RTL', 'GGBOMITSUKOSHI', 'GBOMITSUKOSHI', 'TGBOMITSUKOSHI', 'TGBOMITSUKOSHI', 'RGBOMITSUKOSHI', 'RGBOMITSUKOSHI', 'GBO MITSUKOSHI', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 1, 'ACTIVE', 188, 73, '2023-10-25 22:28:59', '2023-11-07 14:52:00', NULL),
(292, 'ACEFAST.SM.MALL OF ASIA.RTL', 'ACEFAST SM MALL OF ASIA RTL', 'GACEFASTSMMOA', 'ACEFASTSMMOA', 'TACEFASTSMMOA', 'TACEFASTSMMOA', 'RACEFASTSMMOA', 'RACEFASTSMMOA', 'ACEFAST SM MOA', 'RETAIL', NULL, 'RETAIL', 1, 9, NULL, 1, 0, 'ACTIVE', 1, NULL, '2023-11-23 03:04:17', NULL, NULL),
(293, 'DIGITAL WALKER.AYALA.BACOLOD.RTL', 'DIGITAL WALKER AYALA BACOLOD RTL', 'GDWAYALABACOLOD', 'DWAYALABACOLOD', 'TDWAYALABACOLOD', 'TDWAYALABACOLOD', 'RDWAYALABACOLOD', 'RDWAYALABACOLOD', 'DW AYALA BACOLOD', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 0, 'ACTIVE', 188, NULL, '2023-11-27 23:28:37', NULL, NULL),
(294, 'DIGITAL WALKER.VISTAMALL.STA. ROSA.RTL', 'DIGITAL WALKER VISTAMALL STA ROSA RTL', 'GDWSTAROSA', 'DWSTAROSA', 'TDWSTAROSA', 'TDWSTAROSA', 'RDWSTAROSA', 'RDWSTAROSA', 'DW STA ROSA', 'RETAIL', NULL, 'RETAIL', 1, 9, 2, 1, 1, 'ACTIVE', 188, 73, '2023-12-03 17:31:24', '2024-02-06 18:53:30', NULL),
(295, 'RUSTANS.AYALA.ALABANG TOWN CENTER.DLR', 'RUSTANS AYALA ALABANG TOWN CENTER', 'GRUSTANSAYALAATC', 'RUSTANSAYALAATC', NULL, NULL, NULL, NULL, 'RUSTANS AYALA ALABANG TOWN CENTER', 'DISTRI', NULL, 'DISTRI', 10, 8, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2023-12-11 22:44:43', NULL, NULL),
(296, 'DIGIMAP.ORTIGAS MALLS.GREENHILLS.CON', 'DIGIMAP ORTIGAS MALLS GREENHILLS CON', 'N/A', 'N/A', NULL, NULL, NULL, NULL, 'DIGIMAP ORTIGAS MALLS GREENHILLS', 'DIGIMAP', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 73, 1, '2024-01-15 17:39:52', '2024-01-29 22:47:14', NULL),
(297, 'DIGIMAP.ASEANA.PARQAL.CON', 'DIGIMAP ASEANA PARQAL CON', 'N/A', 'N/A', NULL, NULL, NULL, NULL, 'DIGIMAP ASEANA PARQAL', 'DIGIMAP', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 73, 1, '2024-01-15 17:40:25', '2024-01-29 22:46:30', NULL),
(298, 'DIGIMAP.AYALA.ONE.CON', 'DIGIMAP AYALA ONE CON', 'N/A', 'N/A', NULL, NULL, NULL, NULL, 'DIGIMAP AYALA ONE', 'DIGIMAP', NULL, 'DISTRI', 6, 2, NULL, NULL, 0, 'ACTIVE', 73, 1, '2024-01-15 17:41:52', '2024-01-29 22:46:37', NULL),
(299, 'PRIMER DIGIWORX CORPORATION.CRP', 'PRIMER DIGIWORX CORPORATION', 'GPRIMERDIGIWORXCORPORATION', 'GPRIMERDIGIWORXCORPORATION', NULL, NULL, NULL, NULL, 'PRIMER DIGIWORX CORPORATION', 'DISTRI', NULL, 'DISTRI', 11, 3, NULL, NULL, 0, 'ACTIVE', 1, NULL, '2024-01-29 22:07:18', NULL, NULL),
(300, 'GASHAPON.GREENHILLS.VMALL.RTL', 'GASHAPON GREENHILLS VMALL RTL', 'GGBOGREENHILLS', 'GBOGREENHILLS', 'TGBOGREENHILLS', 'TGBOGREENHILLS', 'RGBOGREENHILLS', 'RGBOGREENHILLS', 'GBO GREENHILLS', 'RETAIL', NULL, 'RETAIL', 1, 9, 1, 1, 0, 'ACTIVE', 188, NULL, '2024-02-20 19:50:55', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `st_status`
--

CREATE TABLE `st_status` (
  `id` int(10) UNSIGNED NOT NULL,
  `status_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `st_status`
--

INSERT INTO `st_status` (`id`, `status_description`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PENDING', 'ACTIVE', 1, NULL, '2020-11-12 20:32:01', NULL, NULL),
(2, 'FOR RECEIVING', 'ACTIVE', NULL, NULL, '2024-02-28 02:33:55', NULL, NULL),
(3, 'REJECTED', 'ACTIVE', NULL, NULL, '2024-02-28 02:41:41', NULL, NULL),
(4, 'RECEIVED', 'ACTIVE', NULL, NULL, '2024-02-28 03:16:49', '2024-02-28 04:16:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_type`
--

CREATE TABLE `transaction_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_type`
--

INSERT INTO `transaction_type` (`id`, `transaction_type`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'STW', 'ACTIVE', 1, NULL, '2021-01-04 22:41:56', NULL, NULL),
(2, 'RMA', 'ACTIVE', 1, NULL, '2021-01-04 22:42:03', NULL, NULL),
(3, 'STW Marketing', 'ACTIVE', 1, NULL, '2021-02-28 21:58:26', NULL, NULL),
(4, 'STS', 'ACTIVE', 1, NULL, '2021-10-03 18:09:57', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transport_types`
--

CREATE TABLE `transport_types` (
  `id` int(10) UNSIGNED NOT NULL,
  `transport_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transport_types`
--

INSERT INTO `transport_types` (`id`, `transport_type`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Logistics', 'ACTIVE', 1, NULL, '2021-02-01 19:52:54', NULL, NULL),
(2, 'Hand Carry', 'ACTIVE', 1, NULL, '2021-02-01 19:53:03', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trip_tickets`
--

CREATE TABLE `trip_tickets` (
  `id` int(10) UNSIGNED NOT NULL,
  `trip_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trip_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stores_id` int(10) UNSIGNED DEFAULT NULL,
  `ref_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trip_qty` int(10) UNSIGNED DEFAULT NULL,
  `plastic_qty` int(10) UNSIGNED DEFAULT NULL,
  `box_qty` int(10) UNSIGNED DEFAULT NULL,
  `is_backload` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `backload_reasons_id` int(10) UNSIGNED DEFAULT NULL,
  `backload_at` datetime DEFAULT NULL,
  `is_received` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `received_at` datetime DEFAULT NULL,
  `is_released` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `released_at` datetime DEFAULT NULL,
  `logistics_personnel` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_personnel` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trip_ticket_statuses_id` int(10) UNSIGNED DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_tickets_temp`
--

CREATE TABLE `trip_tickets_temp` (
  `id` int(10) UNSIGNED NOT NULL,
  `trip_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trip_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stores_id` int(10) UNSIGNED DEFAULT NULL,
  `ref_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trip_qty` int(10) UNSIGNED DEFAULT NULL,
  `plastic_qty` int(10) UNSIGNED DEFAULT NULL,
  `box_qty` int(10) UNSIGNED DEFAULT NULL,
  `is_backload` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `backload_reasons_id` int(10) UNSIGNED DEFAULT NULL,
  `backload_at` datetime DEFAULT NULL,
  `is_received` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `received_at` datetime DEFAULT NULL,
  `is_released` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `released_at` datetime DEFAULT NULL,
  `logistics_personnel` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_personnel` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trip_ticket_statuses_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_ticket_lines`
--

CREATE TABLE `trip_ticket_lines` (
  `id` int(10) UNSIGNED NOT NULL,
  `trip_tickets_id` int(10) UNSIGNED DEFAULT NULL,
  `ref_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trip_qty` int(10) UNSIGNED DEFAULT NULL,
  `plastic_qty` int(10) UNSIGNED DEFAULT NULL,
  `box_qty` int(10) UNSIGNED DEFAULT NULL,
  `is_backload` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `backload_reasons_id` int(10) UNSIGNED DEFAULT NULL,
  `backload_at` datetime DEFAULT NULL,
  `is_received` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `received_at` datetime DEFAULT NULL,
  `is_released` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `released_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_ticket_statuses`
--

CREATE TABLE `trip_ticket_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `trip_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trip_type` enum('IN','OUT') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci DEFAULT 'ACTIVE',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_ticket_statuses`
--

INSERT INTO `trip_ticket_statuses` (`id`, `trip_status`, `trip_type`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Partially Released', 'IN', 'ACTIVE', 1, 1, '2022-04-26 20:01:39', '2022-04-26 20:02:01', NULL),
(2, 'Partially Received', 'OUT', 'ACTIVE', 1, NULL, '2022-04-26 20:01:44', NULL, NULL),
(3, 'Fully Received', 'OUT', 'ACTIVE', 1, NULL, '2022-04-26 20:02:14', NULL, NULL),
(4, 'Fully Released', 'IN', 'ACTIVE', 1, NULL, '2022-04-26 20:02:23', NULL, NULL),
(5, 'Pending', 'IN', 'ACTIVE', 1, NULL, '2022-04-26 20:02:29', NULL, NULL),
(6, 'Pending', 'OUT', 'ACTIVE', 1, NULL, '2022-04-26 20:02:48', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval_matrix`
--
ALTER TABLE `approval_matrix`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backload_reasons`
--
ALTER TABLE `backload_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cancel_reasons`
--
ALTER TABLE `cancel_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `channel`
--
ALTER TABLE `channel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_apicustom`
--
ALTER TABLE `cms_apicustom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_apikey`
--
ALTER TABLE `cms_apikey`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_dashboard`
--
ALTER TABLE `cms_dashboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_email_queues`
--
ALTER TABLE `cms_email_queues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_email_templates`
--
ALTER TABLE `cms_email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_logs`
--
ALTER TABLE `cms_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_menus`
--
ALTER TABLE `cms_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_menus_privileges`
--
ALTER TABLE `cms_menus_privileges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_moduls`
--
ALTER TABLE `cms_moduls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_notifications`
--
ALTER TABLE `cms_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_privileges`
--
ALTER TABLE `cms_privileges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_privileges_roles`
--
ALTER TABLE `cms_privileges_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_settings`
--
ALTER TABLE `cms_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_statistics`
--
ALTER TABLE `cms_statistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_statistic_components`
--
ALTER TABLE `cms_statistic_components`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_users`
--
ALTER TABLE `cms_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `code_counter`
--
ALTER TABLE `code_counter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_type`
--
ALTER TABLE `customer_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `distri_subinventories`
--
ALTER TABLE `distri_subinventories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebs_pull`
--
ALTER TABLE `ebs_pull`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_items_unique` (`line_number`,`ordered_item`,`dr_number`),
  ADD KEY `stores_id` (`stores_id`),
  ADD KEY `locator_id` (`locator_id`),
  ADD KEY `transport_types_id` (`transport_types_id`),
  ADD KEY `ordered_item` (`ordered_item`);

--
-- Indexes for table `gis_pulls`
--
ALTER TABLE `gis_pulls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gis_pull_lines`
--
ALTER TABLE `gis_pull_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `digits_code` (`digits_code`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pos_pull`
--
ALTER TABLE `pos_pull`
  ADD PRIMARY KEY (`id`),
  ADD KEY `st_document_number` (`st_document_number`),
  ADD KEY `item_code` (`item_code`),
  ADD KEY `transport_types_id` (`transport_types_id`),
  ADD KEY `reason_id` (`reason_id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `stores_id` (`stores_id`),
  ADD KEY `stores_id_destination` (`stores_id_destination`);

--
-- Indexes for table `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pullout`
--
ALTER TABLE `pullout`
  ADD PRIMARY KEY (`id`),
  ADD KEY `st_document_number` (`st_document_number`),
  ADD KEY `item_code` (`item_code`),
  ADD KEY `transport_types_id` (`transport_types_id`),
  ADD KEY `reason_id` (`reason_id`),
  ADD KEY `stores_id` (`stores_id`),
  ADD KEY `stores_id_destination` (`stores_id_destination`),
  ADD KEY `channel_id` (`channel_id`);

--
-- Indexes for table `pullout_receivings`
--
ALTER TABLE `pullout_receivings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pullout_receiving_lines`
--
ALTER TABLE `pullout_receiving_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pullout_receiving_serials`
--
ALTER TABLE `pullout_receiving_serials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reason`
--
ALTER TABLE `reason`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `serials`
--
ALTER TABLE `serials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `serial_number` (`serial_number`),
  ADD KEY `ebs_pull_id` (`ebs_pull_id`),
  ADD KEY `pos_pull_id` (`pos_pull_id`),
  ADD KEY `pullout_id` (`pullout_id`);

--
-- Indexes for table `status_workflows`
--
ALTER TABLE `status_workflows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_statuses`
--
ALTER TABLE `stock_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `customer_type_id` (`customer_type_id`);

--
-- Indexes for table `st_status`
--
ALTER TABLE `st_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_type`
--
ALTER TABLE `transaction_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transport_types`
--
ALTER TABLE `transport_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_tickets`
--
ALTER TABLE `trip_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_tickets_temp`
--
ALTER TABLE `trip_tickets_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_ticket_lines`
--
ALTER TABLE `trip_ticket_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_ticket_statuses`
--
ALTER TABLE `trip_ticket_statuses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval_matrix`
--
ALTER TABLE `approval_matrix`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `backload_reasons`
--
ALTER TABLE `backload_reasons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cancel_reasons`
--
ALTER TABLE `cancel_reasons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `channel`
--
ALTER TABLE `channel`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cms_apicustom`
--
ALTER TABLE `cms_apicustom`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cms_apikey`
--
ALTER TABLE `cms_apikey`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cms_dashboard`
--
ALTER TABLE `cms_dashboard`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_email_queues`
--
ALTER TABLE `cms_email_queues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_email_templates`
--
ALTER TABLE `cms_email_templates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cms_logs`
--
ALTER TABLE `cms_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_menus`
--
ALTER TABLE `cms_menus`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `cms_menus_privileges`
--
ALTER TABLE `cms_menus_privileges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1137;

--
-- AUTO_INCREMENT for table `cms_moduls`
--
ALTER TABLE `cms_moduls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `cms_notifications`
--
ALTER TABLE `cms_notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_privileges`
--
ALTER TABLE `cms_privileges`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `cms_privileges_roles`
--
ALTER TABLE `cms_privileges_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `cms_settings`
--
ALTER TABLE `cms_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `cms_statistics`
--
ALTER TABLE `cms_statistics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cms_statistic_components`
--
ALTER TABLE `cms_statistic_components`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `cms_users`
--
ALTER TABLE `cms_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `code_counter`
--
ALTER TABLE `code_counter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_type`
--
ALTER TABLE `customer_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `distri_subinventories`
--
ALTER TABLE `distri_subinventories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ebs_pull`
--
ALTER TABLE `ebs_pull`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gis_pulls`
--
ALTER TABLE `gis_pulls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gis_pull_lines`
--
ALTER TABLE `gis_pull_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `pos_pull`
--
ALTER TABLE `pos_pull`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `problems`
--
ALTER TABLE `problems`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `pullout`
--
ALTER TABLE `pullout`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pullout_receivings`
--
ALTER TABLE `pullout_receivings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pullout_receiving_lines`
--
ALTER TABLE `pullout_receiving_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pullout_receiving_serials`
--
ALTER TABLE `pullout_receiving_serials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reason`
--
ALTER TABLE `reason`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `serials`
--
ALTER TABLE `serials`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_workflows`
--
ALTER TABLE `status_workflows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `stock_statuses`
--
ALTER TABLE `stock_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT for table `st_status`
--
ALTER TABLE `st_status`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaction_type`
--
ALTER TABLE `transaction_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transport_types`
--
ALTER TABLE `transport_types`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trip_tickets`
--
ALTER TABLE `trip_tickets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_tickets_temp`
--
ALTER TABLE `trip_tickets_temp`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_ticket_lines`
--
ALTER TABLE `trip_ticket_lines`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_ticket_statuses`
--
ALTER TABLE `trip_ticket_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
