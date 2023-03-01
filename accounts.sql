-- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Vygenerováno: Pon 28. lis 2022, 12:10
-- Verze serveru: 10.3.27-MariaDB-log

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Struktura tabulky `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pid` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=15 ;

--
-- Vypisuji data pro tabulku `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `last_name`, `pid`, `phone`, `email`, `role`, `password`, `hash`, `active`) VALUES
(8, 'Martin', 'Pech', '0001020304', '123456789', 'martin@fitness.mpech.net', 'trainer', '$2y$10$KSccGSm/GS8wjKEDx85sAO7FWBXhNTvqq6heVdRQv12QiD/NpGoy2', 'ae0eb3eed39d2bcef4622b2499a05fe6', 1),
(11, 'Admin', 'Admin', '01', '', 'admin@fitness.mpech.net', 'admin', '$2y$10$z2rauiGPHNl/H0nx2kMvpuTR0TyHmQw3tzoGKHw7.w5HK/fSGzsTK', '109a0ca3bc27f3e96597370d5c8cf03d', 1),
(13, 'David', 'KoneÄnÃ½', '1234567', '123456789', 'david@fitness.mpech.net', 'trainer', '$2y$10$cU7DrSUJiAAfQmO4tK2j0.s91FCIyhLnBdbtYfiuiHCKJCHL0n82y', 'b7ee6f5f9aa5cd17ca1aea43ce848496', 1),
(14, 'MatÄ›j', 'KonopÃ­k', '12345678', '123456788', 'matej@fitness.mpech.net', 'reception', '$2y$10$qbvtTLO55RKMxdUGbGs4n.F0kobkfRzG1ELwbpefFfVAzqpaLvNSS', '8df707a948fac1b4a0f97aa554886ec8', 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `lecture`
--

CREATE TABLE IF NOT EXISTS `lecture` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int(10) NOT NULL,
  `time_start` datetime NOT NULL,
  `time_end` datetime NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Individual/Group',
  `description` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_id` int(100) NOT NULL,
  `lecturer_id` int(20) NOT NULL,
  `lecture_type` int(100) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`),
  KEY `lecturer_id` (`lecturer_id`),
  KEY `restriciton1` (`lecture_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=20 ;

--
-- Vypisuji data pro tabulku `lecture`
--

INSERT INTO `lecture` (`id`, `name`, `capacity`, `time_start`, `time_end`, `type`, `description`, `room_id`, `lecturer_id`, `lecture_type`) VALUES
(11, 'Jumping', 20, '2022-12-06 13:00:00', '2022-12-06 14:00:00', 'Group', 'PÅ™ijÄ si zaskÃ¡kat.', 11, 13, 138),
(12, 'KruhÃ¡Ä', 1, '2022-12-08 11:00:00', '2022-12-08 12:00:00', 'Group', 'ZamÄ›Å™Ã­me se na bÅ™iÅ¡nÃ­ svalstvo.', 9, 8, 140),
(17, 'ÃšternÃ­ FitBox', 5, '2022-11-29 10:00:00', '2022-11-29 11:00:00', 'Group', 'ÃšternÃ­ buÅ¡enÃ­', 10, 8, 139),
(18, 'Jumping', 10, '2022-12-12 09:00:00', '2022-12-12 10:00:00', 'Group', 'RannÃ­ Jumping', 11, 8, 138),
(19, 'Jumping', 14, '2022-12-13 11:00:00', '2022-12-13 12:00:00', 'Group', 'DopolednÃ­ jumping', 8, 13, 138);

-- --------------------------------------------------------

--
-- Struktura tabulky `lecture_types`
--

CREATE TABLE IF NOT EXISTS `lecture_types` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=146 ;

--
-- Vypisuji data pro tabulku `lecture_types`
--

INSERT INTO `lecture_types` (`id`, `name`, `color`) VALUES
(138, 'Jumping', 'blue'),
(139, 'FitBox', 'orange'),
(140, 'KruhÃ¡Ä', 'purple'),
(141, 'JÃ³ga', 'lime'),
(145, 'Others', 'green');

-- --------------------------------------------------------

--
-- Struktura tabulky `reservation`
--

CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `room_id` int(100) NOT NULL,
  `client_id` int(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `room_id` (`room_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=254 ;

--
-- Vypisuji data pro tabulku `reservation`
--

INSERT INTO `reservation` (`id`, `date_start`, `date_end`, `room_id`, `client_id`) VALUES
(247, '2022-11-29 10:00:00', '2022-11-29 11:00:00', 10, 47),
(248, '2022-12-08 11:00:00', '2022-12-08 12:00:00', 9, 47),
(249, '2022-12-13 11:00:00', '2022-12-13 12:00:00', 8, 47),
(250, '2022-12-14 12:00:00', '2022-12-14 13:00:00', 8, 47),
(251, '2022-12-14 11:00:00', '2022-12-14 12:00:00', 8, 47),
(252, '2022-12-02 11:00:00', '2022-12-02 12:00:00', 11, 47),
(253, '2022-12-01 12:00:00', '2022-12-01 13:00:00', 10, 47);

-- --------------------------------------------------------

--
-- Struktura tabulky `reservation_lecture`
--

CREATE TABLE IF NOT EXISTS `reservation_lecture` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(100) NOT NULL,
  `lecture_id` int(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lecture_id` (`lecture_id`),
  KEY `reservation_id` (`reservation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=84 ;

--
-- Vypisuji data pro tabulku `reservation_lecture`
--

INSERT INTO `reservation_lecture` (`id`, `reservation_id`, `lecture_id`) VALUES
(81, 247, 17),
(82, 248, 12),
(83, 249, 19);

-- --------------------------------------------------------

--
-- Struktura tabulky `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max` int(10) NOT NULL,
  `img` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_room_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=13 ;

--
-- Vypisuji data pro tabulku `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `description`, `max`, `img`) VALUES
(8, 'TÄ›locviÄna T1', 'MalÃ½ sÃ¡l', 10, '1.jpg'),
(9, 'TÄ›locviÄna T2', 'VelkÃ½ sÃ¡l', 20, 'gym.jpg'),
(10, 'SoukromÃ¡ posilovna', 'Posilovna vyhrazenÃ¡ pro FitBox, obsahuje mnoÅ¾stvÃ­ pytlÅ¯ a pomÅ¯cek.', 15, 'gym2.jpg'),
(11, 'TÄ›locviÄna T3', 'NovÃ¡ tÄ›locviÄna s pomÅ¯ckami pro jÃ³gu a stretching. PP', 20, 'gym3.jpg');

-- --------------------------------------------------------

--
-- Struktura tabulky `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hash` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT AUTO_INCREMENT=72 ;

--
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `hash`, `active`) VALUES
(42, 'Unverified', 'User', 'unverifieduser@mpech.net', '$2y$10$ulQ1me/XL2BdblSQS0.Bc.vqGwEiiXCF/CfEzb0C1FGG/gnMMkqx2', 'bc6dc48b743dc5d013b1abaebd2faed2', 0),
(43, 'Verified', 'User', 'verifieduser@mpech.net', '$2y$10$AgX/t0xC5l2YjK02tHRbq.mAgMoQcI2lJjmVeueG.xSOh4ekePsdO', 'a4300b002bcfb71f291dac175d52df94', 1),
(47, 'User', 'User', 'testuser@mpech.net', '$2y$10$mvKyvSecmUOXKl/K1rAO2.eSO7Mv8XzLVthEHbOJl8UHTTkvxolEq', '2f2b265625d76a6704b08093c652fd79', 1);

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `lecture`
--
ALTER TABLE `lecture`
  ADD CONSTRAINT `restriciton1` FOREIGN KEY (`lecture_type`) REFERENCES `lecture_types` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
