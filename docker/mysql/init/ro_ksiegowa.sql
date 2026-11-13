-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2026 at 10:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ro_ksiegowa`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_client`
--

CREATE TABLE `email_client` (
  `email_id` int(11) NOT NULL,
  `client_nip` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_client`
--

INSERT INTO `email_client` (`email_id`, `client_nip`, `email`) VALUES
(1, '9371030253', 'energo@test.com'),
(2, '9371030253', 'energo@test.com'),
(3, '9371030253', 'energo2@test.com'),
(4, '9371030253', 'energo3@test.com'),
(6, '9371030253', 'energo4@test.com'),
(7, '9371030253', 'energo5@test.com'),
(8, '1080028106', 'jadawel@jad.com'),
(9, '1080028106', 'jadawel2@test.com'),
(10, '5273165607', 'kacper05112004@gmail.com'),
(11, '5273165607', 'kacpercwiek@tutamail.com'),
(13, '5273165607', 'hunter292@tutanota.com');

-- --------------------------------------------------------

--
-- Table structure for table `email_report`
--

CREATE TABLE `email_report` (
  `report_id` int(11) NOT NULL,
  `email_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_report`
--

INSERT INTO `email_report` (`report_id`, `email_id`) VALUES
(1, 1),
(2, 2),
(6, 1),
(8, 1),
(11, 10),
(11, 11),
(31, 10),
(32, 10),
(33, 10),
(33, 13),
(34, 10),
(34, 13),
(35, 10),
(35, 13),
(36, 10),
(36, 13),
(37, 10),
(37, 13),
(38, 10),
(38, 13),
(39, 10),
(39, 13),
(40, 13),
(46, 10),
(47, 10),
(49, 10),
(50, 10),
(51, 10),
(51, 13),
(52, 10);

-- --------------------------------------------------------

--
-- Table structure for table `faktura`
--

CREATE TABLE `faktura` (
  `faktura_id` int(11) NOT NULL,
  `client_nip` varchar(50) NOT NULL,
  `amount` float NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faktura`
--

INSERT INTO `faktura` (`faktura_id`, `client_nip`, `amount`, `date`) VALUES

(5, '9371030253', 16000, '2026-05-28'),
(8, '5273165607', 1000, '2026-05-28'),
(25, '1080028106', 500, '2026-05-29');


-- --------------------------------------------------------

--
-- Table structure for table `klient`
--

CREATE TABLE `klient` (
  `client_nip` varchar(50) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `klient`
--

INSERT INTO `klient` (`client_nip`, `name`) VALUES
('0', 'wewnętrzne'),
('1', 'klient11'),
('1080028106', 'JADAWEL UNITED COMPANY FOR MILITARY SUPPLIES SPÓŁKA Z OGRANICZONĄ ODPOWIEDZIALNOŚCIĄ ODDZIAŁ W POLSCE'),
('2', 'klient2'),
('3', 'klient3'),
('5273165607', 'ALEGA SPÓŁKA Z OGRANICZONĄ ODPOWIEDZIALNOŚCIĄ'),
('9371030253', '\"ENERGOPOL-TRADE-OPOLE\" SPÓŁKA Z OGRANICZONĄ ODPOWIEDZIALNOŚCIĄ');


-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2026_10_26_202725_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `praca`
--

CREATE TABLE `praca` (
  `work_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `client_nip` varchar(50) DEFAULT NULL,
  `work_type` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time_start` time NOT NULL,
  `time_finish` time NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `praca`
--

INSERT INTO `praca` (`work_id`, `employee_id`, `client_nip`, `work_type`, `date`, `time_start`, `time_finish`, `notes`) VALUES
(1, 1, '1', 'wyliczenia', '2026-05-15', '12:00:00', '13:10:00', ''),
(2, 1, '1', 'spotkanie', '2026-05-16', '11:00:00', '13:30:00', ''),
(3, 3, '1', 'spotkanie', '2026-06-16', '11:00:00', '13:30:00', ''),
(4, 3, '2', 'wyliczenia', '2026-07-16', '11:10:00', '13:30:00', ''),
(7, 3, '1', 'spotkanie', '2026-07-08', '22:00:00', '23:00:00', ''),
(8, 6, '1', 'Księgowanie faktur', '2026-07-18', '13:00:00', '14:00:00', ''),
(9, 6, '2', 'Księgowanie WB', '2026-07-17', '14:00:00', '15:00:00', ''),
(13, 6, '1', 'Analizy', '2026-07-19', '13:00:00', '14:30:00', ''),
(14, 6, '1080028106', 'Księgowanie faktur', '2026-05-19', '12:00:00', '14:00:00', ''),
(17, 6, '9371030253', 'Usługi kadrowe', '2026-05-21', '12:00:00', '14:00:00', ''),
(20, 6, '9371030253', 'Podatki', '2026-07-27', '10:00:00', '14:00:00', ''),
(24, 6, '5273165607', 'Księgowanie WB', '2026-08-12', '00:12:00', '05:00:00', ''),
(25, 6, '9371030253', 'Księgowanie faktur', '2026-10-27', '00:00:00', '14:00:00', 'test dupy'),
(26, 6, '9371030253', 'Usługi kadrowe', '2026-10-29', '00:00:00', '14:00:00', 'test dupy'),
(29, 3, '9371030253', 'Analizy', '2026-11-12', '20:03:00', '22:03:00', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `pracownik`
--

CREATE TABLE `pracownik` (
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `otp` int(11) DEFAULT NULL,
  `token_life` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pracownik`
--

INSERT INTO `pracownik` (`employee_id`, `name`, `email`, `password`, `admin`, `updated`, `otp`, `token_life`) VALUES
(1, 'test', 'test_new', '$argon2id$v=19$m=65536,t=4,p=1$T1liRGdkaUFvSTJEdC5EVw$povXwOBi+E2PbDMe0X5Xe2XQP87UJLqZdr4c56afgDU', 2, '0000-00-00 00:00:00', NULL, NULL),
(3, 'anna', 'hunter292@tutanota.com', '$argon2id$v=19$m=65536,t=4,p=1$MEtsNURndVptSEwwOEVvVw$nPIfOe8tRCKxEYn2BeevbQMjTLFWFaXV9kvrlfgW2wQ', 2, '2026-11-12 19:22:02', 779228, '2026-11-12 19:01:05'),
(4, 'hanna', 'test2_new', '$argon2id$v=19$m=65536,t=4,p=1$T1liRGdkaUFvSTJEdC5EVw$povXwOBi+E2PbDMe0X5Xe2XQP87UJLqZdr4c56afgDU', 0, '0000-00-00 00:00:00', NULL, NULL),
(6, 'admin', 'admin@rozowaksiegowa.pl', '$argon2id$v=19$m=65536,t=4,p=1$RDZiWXhoY3AzNXRsNWpVYw$5O03CPfpRztK/RLqUZVp0TN9NDhKlIR57b0w4wXEtBQ', 2, '2026-08-17 15:47:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rachunek`
--

CREATE TABLE `rachunek` (
  `acc_number` varchar(255) NOT NULL,
  `client_nip` varchar(255) NOT NULL,
  `ZUS` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rachunek`
--

INSERT INTO `rachunek` (`acc_number`, `client_nip`, `ZUS`) VALUES
('1111', '9371030253', 1),
('1234', '9371030253', 0);

-- --------------------------------------------------------

--
-- Table structure for table `raport`
--

CREATE TABLE `raport` (
  `report_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `client_nip` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `content` text NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `raport`
--

INSERT INTO `raport` (`report_id`, `employee_id`, `client_nip`, `date`, `content`, `notes`) VALUES
(1, 6, '9371030253', '2026-08-12', 'VAT7: 123 okres: 2025;\nVAT7K: 1111 okres: 12-2025;\n', ''),
(2, 6, '9371030253', '2026-08-12', 'VAT8: 123 okres: 2025;\nCIT 10Z: 555 okres: 11-2026;', NULL),
(6, 6, '9371030253', '2026-08-12', 'VAT7: 100 okres: 2025;\nVAT7K: 1000.00 okres: 11-2025;\nCIT8: 200.95 okres: 12-2025;\n\n', ''),
(8, 6, '9371030253', '2026-08-12', 'VAT7: 123 okres: 2025;ZUS: 200.00 rachunek: 1111 okres: 12-2025;', 'test spacji'),
(11, 6, '5273165607', '2026-08-15', 'VAT7 - 123.00 zł termin płatności:2026-08-31;VAT8 - 1000.29 zł termin płatności:2026-09-30;PIT36 - 200.68 zł termin płatności:2026-08-31;', 'test notatki'),
(31, 6, '5273165607', '2026-11-07', 'VAT7 - 123 zł termin płatności:2026-11-18;', 'est'),
(32, 6, '5273165607', '2026-11-07', 'VAT7 - 123 zł termin płatności:2026-11-18;', 'test'),
(33, 6, '5273165607', '2026-11-07', 'VAT7 - 123 zł termin płatności:2026-11-15;PIT36 - 100 zł termin płatności:2026-11-30;', 'test'),
(34, 6, '5273165607', '2026-11-07', 'VAT7 - 123 zł termin płatności:2026-11-11;VAT8 - 100 zł termin płatności:2026-11-26;', 'ttttttttttttttttttttttttttt'),
(35, 6, '5273165607', '2026-11-07', 'VAT9 - 222 zł termin płatności:2026-12-03;PIT8AR - 300 zł termin płatności:2026-11-15;', 'tset'),
(36, 6, '5273165607', '2026-11-07', 'VAT9 - 200 zł termin płatności:2026-11-04;VAT7K - 200 zł termin płatności:2026-12-02;', 'tst'),
(37, 6, '5273165607', '2026-11-07', 'VAT7 - 100 zł termin płatności:2026-11-11;', 'test'),
(38, 6, '5273165607', '2026-11-07', 'VAT7 - 200 zł termin płatności:2026-12-02;', 'test'),
(39, 6, '5273165607', '2026-11-07', 'VAT7 - 200 zł termin płatności:2026-11-18;', 'test'),
(40, 6, '5273165607', '2026-11-07', 'VAT7 - 200 zł termin płatności:2026-11-17;', 'test'),
(46, 6, '5273165607', '2026-11-07', 'VAT8 - 100 zł termin płatności:2026-11-11;', 'test'),
(47, 6, '5273165607', '2026-11-07', 'VAT9 - 100 zł termin płatności:2026-11-11;VAT8 - 200 zł termin płatności:2026-11-28;', 'test'),
(49, 6, '5273165607', '2026-11-07', 'VAT9 - 100 zł termin płatności:2026-11-17;', 'test'),
(50, 6, '5273165607', '2026-11-07', 'PIT36 - 100 zł termin płatności:2026-11-18;', 'test'),
(51, 6, '5273165607', '2026-11-07', 'VAT9 - 200 zł termin płatności:2026-11-10;', 'test'),
(52, 6, '5273165607', '2026-11-07', 'VAT9 - 100 zł termin płatności:2026-11-25;', 'tst');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `task_worker`
--

CREATE TABLE `task_worker` (
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_worker`
--

INSERT INTO `task_worker` (`task_id`, `employee_id`, `status`) VALUES
(5, 3, 1),
(5, 6, 3),
(6, 3, 0),
(6, 6, 0),
(7, 3, 0),
(8, 6, 3),
(9, 3, 0),
(10, 3, 0),
(10, 4, 0),
(10, 6, 0),
(11, 6, 0),
(13, 1, 0),
(13, 3, 0),
(13, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `zadanie`
--

CREATE TABLE `zadanie` (
  `task_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `deadline` date DEFAULT NULL,
  `employee_id` int(11) NOT NULL,
  `client_nip` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `priority` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zadanie`
--

INSERT INTO `zadanie` (`task_id`, `date`, `deadline`, `employee_id`, `client_nip`, `description`, `priority`) VALUES
(5, '2026-08-11 19:03:32', '2026-08-12', 6, '9371030253', 'test', 0),
(10, '2026-08-11 22:38:23', '0000-00-00', 6, '5273165607', 'test czy w obu się pojawi', 0),
(11, '2026-08-16 19:33:50', '2026-08-17', 6, '9371030253', 'Wyliczyć podatki na wczoraj', 1),
(13, '2026-11-09 23:00:00', '2026-11-19', 6, '0', 'interest rate', 0);


-
--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_client`
--
ALTER TABLE `email_client`
  ADD PRIMARY KEY (`email_id`) USING BTREE,
  ADD KEY `client_nip` (`client_nip`);

--
-- Indexes for table `email_report`
--
ALTER TABLE `email_report`
  ADD PRIMARY KEY (`report_id`,`email_id`),
  ADD KEY `email_id` (`email_id`);

--
-- Indexes for table `faktura`
--
ALTER TABLE `faktura`
  ADD PRIMARY KEY (`faktura_id`),
  ADD KEY `client_nip` (`client_nip`);

--
-- Indexes for table `klient`
--
ALTER TABLE `klient`
  ADD PRIMARY KEY (`client_nip`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `praca`
--
ALTER TABLE `praca`
  ADD PRIMARY KEY (`work_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `client_nip` (`client_nip`);

--
-- Indexes for table `pracownik`
--
ALTER TABLE `pracownik`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `rachunek`
--
ALTER TABLE `rachunek`
  ADD PRIMARY KEY (`acc_number`),
  ADD KEY `client_nip` (`client_nip`);

--
-- Indexes for table `raport`
--
ALTER TABLE `raport`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `client_nip` (`client_nip`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_employee_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `task_worker`
--
ALTER TABLE `task_worker`
  ADD PRIMARY KEY (`task_id`,`employee_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `zadanie`
--
ALTER TABLE `zadanie`
  ADD PRIMARY KEY (`task_id`);


--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `email_client`
--
ALTER TABLE `email_client`
  MODIFY `email_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `faktura`
--
ALTER TABLE `faktura`
  MODIFY `faktura_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `praca`
--
ALTER TABLE `praca`
  MODIFY `work_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `pracownik`
--
ALTER TABLE `pracownik`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `raport`
--
ALTER TABLE `raport`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `zadanie`
--
ALTER TABLE `zadanie`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;


--
-- Constraints for dumped tables
--

--
-- Constraints for table `email_client`
--
ALTER TABLE `email_client`
  ADD CONSTRAINT `email_client_ibfk_3` FOREIGN KEY (`client_nip`) REFERENCES `klient` (`client_nip`) ON UPDATE CASCADE;

--
-- Constraints for table `email_report`
--
ALTER TABLE `email_report`
  ADD CONSTRAINT `email_report_ibfk_1` FOREIGN KEY (`email_id`) REFERENCES `email_client` (`email_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `email_report_ibfk_2` FOREIGN KEY (`report_id`) REFERENCES `raport` (`report_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `faktura`
--
ALTER TABLE `faktura`
  ADD CONSTRAINT `faktura_ibfk_1` FOREIGN KEY (`client_nip`) REFERENCES `klient` (`client_nip`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `praca`
--
ALTER TABLE `praca`
  ADD CONSTRAINT `praca_ibfk_3` FOREIGN KEY (`client_nip`) REFERENCES `klient` (`client_nip`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `rachunek`
--
ALTER TABLE `rachunek`
  ADD CONSTRAINT `rachunek_ibfk_1` FOREIGN KEY (`client_nip`) REFERENCES `klient` (`client_nip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `raport`
--
ALTER TABLE `raport`
  ADD CONSTRAINT `raport_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `pracownik` (`employee_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `raport_ibfk_2` FOREIGN KEY (`client_nip`) REFERENCES `klient` (`client_nip`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `task_worker`
--
ALTER TABLE `task_worker`
  ADD CONSTRAINT `task_worker_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `pracownik` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `zadanie`
--
ALTER TABLE `zadanie`
  ADD CONSTRAINT `zadanie_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `pracownik` (`employee_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `zadanie_ibfk_2` FOREIGN KEY (`client_nip`) REFERENCES `klient` (`client_nip`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
