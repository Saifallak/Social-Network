-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 09, 2018 at 12:23 AM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sushi_network`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `posted_at` datetime NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment`, `user_id`, `posted_at`, `post_id`) VALUES
(20, 'sadasdas', 5, '2018-05-01 21:31:54', 5),
(21, 'sadasd', 5, '2018-05-01 21:31:56', 3),
(22, 'asdasd', 5, '2018-05-01 21:31:58', 5),
(23, 'helllllllllllllo', 5, '2018-05-01 21:32:02', 5),
(24, 'asdasd', 5, '2018-05-01 21:32:04', 3),
(25, 'klmomokm', 8, '2018-05-06 12:00:57', 84);

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

DROP TABLE IF EXISTS `followers`;
CREATE TABLE IF NOT EXISTS `followers` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `follower_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `follower_id` (`follower_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `user_id`, `follower_id`) VALUES
(9, 5, 1),
(10, 8, 1),
(15, 5, 8),
(16, 1, 8),
(18, 8, 5),
(19, 14, 5),
(21, 1, 5),
(22, 5, 15);

-- --------------------------------------------------------

--
-- Table structure for table `login_tokens`
--

DROP TABLE IF EXISTS `login_tokens`;
CREATE TABLE IF NOT EXISTS `login_tokens` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` char(64) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `body` text NOT NULL,
  `receiver` int(11) UNSIGNED NOT NULL,
  `sender` int(11) UNSIGNED NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `reciever` (`receiver`),
  KEY `sender` (`sender`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `body`, `receiver`, `sender`, `seen`) VALUES
(1, '213123', 5, 1, 0),
(2, 'sadasd', 1, 5, 1),
(3, 'sadasd', 1, 5, 1),
(4, 'sadasd', 5, 5, 1),
(5, 'asdasda', 5, 5, 1),
(6, 'sadasdsjkagdkgdkagdkjagdkjagdaas', 5, 5, 1),
(7, 'sadasd', 5, 5, 1),
(8, 'asdasd', 5, 5, 1),
(9, 'asdasdasd', 5, 5, 1),
(10, 'sadasda', 1, 5, 1),
(11, 'sadasdasd', 1, 5, 1),
(12, 'asdasda', 1, 5, 1),
(13, 'asdasda', 1, 5, 0),
(14, 'sadasd', 1, 5, 0),
(15, 'sadasd', 1, 5, 0),
(16, 'sadasd', 1, 5, 0),
(17, 'sadasd', 1, 5, 0),
(18, 'sadasd', 1, 5, 0),
(19, 'sadasd', 1, 5, 0),
(20, 'sadasd', 1, 5, 0),
(21, 'sadasd', 1, 5, 0),
(22, 'asdas', 1, 5, 0),
(23, 'asda', 1, 5, 0),
(24, 'asdasd', 1, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` int(11) UNSIGNED NOT NULL,
  `receiver` int(11) UNSIGNED NOT NULL,
  `sender` int(11) UNSIGNED NOT NULL,
  `extra` text,
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`),
  KEY `sender` (`sender`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `receiver`, `sender`, `extra`) VALUES
(4, 1, 8, 5, '{ \"postbody\": \"@saif\"}'),
(5, 1, 5, 5, '{ \"postbody\": \"@Saifallak sss\"}'),
(6, 1, 5, 5, '{ \"postbody\": \"@Saifallak sss\"}'),
(7, 1, 5, 5, '{ \"postbody\": \"@Saifallak sss\"}'),
(8, 1, 5, 5, '{ \"postbody\": \"@Saifallak sss\"}'),
(9, 1, 5, 5, '{ \"postbody\": \"@Saifallak sss\"}'),
(10, 1, 5, 5, '{ \"postbody\": \"@Saifallak sss\"}'),
(11, 1, 5, 5, '{ \"postbody\": \"@Saifallak 123123 b ola #hi\"}'),
(12, 1, 5, 5, '{ \"postbody\": \"@Saifallak 123123 b ola #hi\"}'),
(13, 1, 5, 5, '{ \"postbody\": \"@Saifallak hi from noti calss\"}'),
(14, 2, 5, 5, ''),
(15, 2, 5, 5, ''),
(16, 2, 5, 5, ''),
(17, 2, 8, 8, ''),
(18, 2, 8, 8, ''),
(19, 2, 8, 8, ''),
(20, 2, 1, 5, ''),
(21, 2, 5, 15, ''),
(22, 2, 5, 5, '');

-- --------------------------------------------------------

--
-- Table structure for table `password_tokens`
--

DROP TABLE IF EXISTS `password_tokens`;
CREATE TABLE IF NOT EXISTS `password_tokens` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` char(64) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `body` varchar(160) NOT NULL,
  `posted_at` datetime NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `likes` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `postimg` varchar(255) DEFAULT NULL,
  `topics` varchar(400) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `posted_at`, `user_id`, `likes`, `postimg`, `topics`) VALUES
(1, 'bolahfdhd', '2018-04-26 14:43:05', 8, 2, NULL, NULL),
(2, 'bolaten', '2018-04-26 15:02:46', 8, 4, NULL, NULL),
(3, 'bolaten', '2018-04-26 15:03:25', 8, 4, NULL, NULL),
(5, 'bola', '2018-04-26 15:09:06', 8, 2, NULL, NULL),
(10, 'bola', '2018-04-26 23:06:48', 5, 0, NULL, NULL),
(11, 'bola', '2018-04-26 23:07:38', 5, 0, NULL, NULL),
(12, 'bola', '2018-04-26 23:18:40', 5, 0, NULL, NULL),
(13, 'bola', '2018-04-26 23:19:21', 5, 0, NULL, NULL),
(17, 'bola', '2018-04-26 23:55:12', 5, 1, NULL, NULL),
(18, '@saif #hero bolten', '2018-04-27 16:29:38', 5, 0, NULL, 'hero,'),
(19, '@Saufakkaj @Saifallak', '2018-04-27 16:32:30', 5, 0, NULL, ''),
(20, '@Saifallak', '2018-04-27 16:34:53', 5, 0, NULL, ''),
(21, '@sad', '2018-04-27 16:34:56', 5, 0, NULL, ''),
(22, '@sad', '2018-04-27 16:57:02', 5, 0, NULL, ''),
(23, '@saif', '2018-04-27 17:01:14', 5, 0, NULL, ''),
(24, 'asd', '2018-04-27 17:01:41', 5, 0, NULL, ''),
(25, '@', '2018-04-27 17:01:43', 5, 0, NULL, ''),
(26, 'S', '2018-04-27 17:06:19', 5, 0, NULL, ''),
(27, 'S', '2018-04-27 17:06:19', 5, 0, NULL, ''),
(28, 'S', '2018-04-27 17:06:19', 5, 0, NULL, ''),
(29, '@post', '2018-04-27 17:06:23', 5, 1, NULL, ''),
(30, '@saif', '2018-04-27 17:07:03', 5, 1, NULL, ''),
(31, '@Saifallak sss', '2018-04-27 17:08:11', 5, 0, NULL, ''),
(32, '@Saifallak sss', '2018-04-27 17:08:20', 5, 0, NULL, ''),
(33, '@Saifallak sss', '2018-04-27 17:08:20', 5, 0, NULL, ''),
(34, '@Saifallak sss', '2018-04-27 17:08:21', 5, 1, NULL, ''),
(35, '@Saifallak sss', '2018-04-27 17:08:21', 5, 0, NULL, ''),
(36, '@Saifallak sss', '2018-04-27 17:08:21', 5, 1, NULL, ''),
(37, 'sadasdas', '2018-04-27 17:08:29', 5, 1, NULL, ''),
(38, '@Saifallak 123123 b ola #hi', '2018-04-27 17:08:38', 5, 1, NULL, 'hi,'),
(39, '@Saifallak 123123 b ola #hi', '2018-04-27 17:15:04', 5, 1, NULL, 'hi,'),
(41, '123', '2018-05-01 22:50:26', 5, 0, NULL, ''),
(42, '123', '2018-05-01 22:50:31', 5, 0, NULL, ''),
(43, '123123123', '2018-05-01 22:50:37', 5, 0, NULL, ''),
(44, '123123123123', '2018-05-01 22:51:16', 5, 0, NULL, ''),
(45, 'asdasdas', '2018-05-01 22:52:05', 5, 0, NULL, ''),
(46, 'hehjkadhkajhdsakhdakgdjksagdkjagd', '2018-05-01 22:53:27', 5, 0, NULL, ''),
(47, 'asdasd', '2018-05-01 22:54:09', 5, 0, NULL, ''),
(49, '123', '2018-05-01 22:58:07', 5, 0, NULL, ''),
(50, '123', '2018-05-01 23:00:48', 5, 0, NULL, ''),
(51, '123', '2018-05-01 23:02:36', 5, 0, NULL, ''),
(52, '123', '2018-05-01 23:02:46', 5, 0, NULL, ''),
(53, '123', '2018-05-01 23:04:05', 5, 0, NULL, ''),
(54, '123', '2018-05-01 23:04:08', 5, 0, NULL, ''),
(55, '123', '2018-05-01 23:04:39', 5, 0, NULL, ''),
(56, 'hi from here', '2018-05-04 02:32:54', 5, 0, NULL, ''),
(57, '12', '2018-05-04 02:35:04', 5, 0, NULL, ''),
(58, '12', '2018-05-04 02:37:46', 5, 0, NULL, ''),
(59, '123', '2018-05-04 02:38:05', 5, 0, NULL, ''),
(60, '213', '2018-05-04 02:38:32', 5, 0, NULL, ''),
(61, '213', '2018-05-04 02:39:33', 5, 0, NULL, ''),
(62, '213', '2018-05-04 02:39:38', 5, 0, NULL, ''),
(63, '213', '2018-05-04 02:40:12', 5, 0, NULL, ''),
(64, '213', '2018-05-04 02:40:14', 5, 0, NULL, ''),
(65, '213', '2018-05-04 02:40:31', 5, 0, NULL, ''),
(66, '213', '2018-05-04 02:40:52', 5, 0, NULL, ''),
(67, '213', '2018-05-04 02:41:08', 5, 0, NULL, ''),
(68, '213', '2018-05-04 02:41:26', 5, 0, NULL, ''),
(69, '213', '2018-05-04 02:41:42', 5, 0, NULL, ''),
(70, '213', '2018-05-04 02:41:55', 5, 0, NULL, ''),
(71, '213', '2018-05-04 02:42:21', 5, 0, NULL, ''),
(72, '213', '2018-05-04 02:43:00', 5, 0, NULL, ''),
(73, '213', '2018-05-04 02:43:15', 5, 0, NULL, ''),
(74, 'hi #bola', '2018-05-04 02:43:35', 5, 0, NULL, 'bola,'),
(75, 'hi #bola', '2018-05-04 02:44:19', 5, 0, NULL, 'bola,'),
(76, 'hi #bola', '2018-05-04 02:44:33', 5, 0, NULL, 'bola,'),
(77, 'hi #bola', '2018-05-04 02:45:19', 5, 0, NULL, 'bola,'),
(78, 'hi #bola', '2018-05-04 02:46:02', 5, 0, NULL, 'bola,'),
(79, 'hi #bola', '2018-05-04 02:48:26', 5, 0, NULL, 'bola,'),
(80, 'hi #bola', '2018-05-04 02:50:44', 5, 0, NULL, 'bola,'),
(81, 'hi #bola', '2018-05-04 02:51:20', 5, 0, NULL, 'bola,'),
(82, 'fuck that', '2018-05-04 02:51:59', 5, 1, NULL, ''),
(83, 'test', '2018-05-04 02:52:37', 5, 2, NULL, ''),
(84, 'Hi Post from Verified', '2018-05-16 00:00:00', 1, 182, NULL, NULL),
(86, 'r4fvrcf', '2018-05-05 19:09:54', 5, 3, NULL, ''),
(87, '', '2018-05-05 20:51:59', 5, 1, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

DROP TABLE IF EXISTS `post_likes`;
CREATE TABLE IF NOT EXISTS `post_likes` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `user_id`, `post_id`) VALUES
(194, 5, 1),
(208, 5, 83),
(209, 5, 82),
(213, 5, 85),
(217, 5, 2),
(218, 5, 86),
(219, 14, 88),
(223, 5, 5),
(224, 5, 3),
(225, 5, 88),
(230, 5, 84),
(234, 15, 86),
(235, 15, 85),
(236, 15, 83),
(237, 15, 87),
(238, 8, 84),
(240, 8, 86);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `email` text,
  `verified` tinyint(1) DEFAULT '0',
  `profileimg` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `verified`, `profileimg`) VALUES
(1, 'Verified', '$2y$10$7s9eNZ3OVEFKeNtG1ebfBOyE9/K9AzVZssdCYPnuQbtc6ADp6VPEO', 'Verified@Verification.bot', 0, NULL),
(5, 'Saifallak', '$2y$10$Dc57JYBVsofcUcLMlcxJFupA99/iNHZlaNziIV2cmONGMqWNRfPqO', 'Saifallak@yahoo.com', 1, 'https://i.imgur.com/pV0KD3C.jpg'),
(6, '20167431378', '$2y$10$jjH9ItYsUd7G8nSs548J1.1lqGoZnRgN5HQTxudkOAohMgY7hdmne', 'Saifallak@yahoo.com', 0, NULL),
(7, 'u121160884', '$2y$10$eRI2yPmSc3JkrA1rPrJA8efP2RdCOGbKLzjw1fH74FDtKhYgz1a46', 'Saifallak@yahoo.com', 0, NULL),
(8, 'Saif', '$2y$10$znZltCJgTCCxDpiRiIoYgu46Ea9WKdmrRqsVtc7wQMQv6O2xPm73a', 'Saif@yahoo.com', 1, NULL),
(9, 'Saifallak2', '$2y$10$Khvqk5BQphstmMRLhScbYOaaWB4cKyWpmUS4giAz5kUXZRDnVM.Ca', 'Saifallak1@yahoo.com', 0, NULL),
(10, 'testingnewacc', '$2y$10$jbegcapJtCnndjjHfepmgu5qgBjmp2bqBh5bYvTzslga/MRJpvOQu', 'hi@hi.hiz', 0, NULL),
(11, 'confilct', '$2y$10$jCBCFQJDJ5eiahTWzkaDMejygYzRoqk0P4qHW.dz5GRbGBipstBMW', '123@123.com', 0, NULL),
(12, 'asd', '$2y$10$O999G9h89uUjHi35mFTZbub5BsYEYUmc0H45cKxee/WjJsB4PgC4C', 'asd@asd.com', 0, NULL),
(13, '123123', '$2y$10$zv5PPyx60tMTg601EX/iNerZx3M3yj9sl9SNa4RpNliJmbPVIPGsC', '123123@123123.com', 0, NULL),
(14, 'Fathy', '$2y$10$y9wIkaL7pxV21I1De86sTu7o3YlcIsIkr.BCtd94Xnw0rhFIIBmgK', 'Fathy@asd.com', 0, NULL),
(15, 'sarasaad', '$2y$10$Y4LvnfvjVQdAIzqkgq4U8.8BsWAEEZ/mORcoRqi46dCidVVJEivUe', 'sara.sedhom@yahoo.som', 0, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `login_tokens`
--
ALTER TABLE `login_tokens`
  ADD CONSTRAINT `login_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`receiver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`receiver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`sender`) REFERENCES `users` (`id`);

--
-- Constraints for table `password_tokens`
--
ALTER TABLE `password_tokens`
  ADD CONSTRAINT `password_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
