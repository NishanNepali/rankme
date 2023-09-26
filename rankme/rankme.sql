-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2023 at 07:06 AM
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
-- Database: `rankme`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `comment_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id1` int(11) UNSIGNED DEFAULT NULL,
  `user_id2` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `user_id1`, `user_id2`, `created_at`) VALUES
(216, 228745, 629873, '2023-07-16 12:05:44'),
(217, 629873, 228745, '2023-07-16 12:05:44'),
(234, 629873, 0, '2023-07-18 07:36:46'),
(235, 0, 629873, '2023-07-18 07:36:46'),
(236, 629873, 0, '2023-07-18 07:37:43'),
(237, 0, 629873, '2023-07-18 07:37:43'),
(238, 629873, 0, '2023-07-18 07:41:00'),
(239, 0, 629873, '2023-07-18 07:41:00'),
(240, 629873, 0, '2023-07-18 07:41:01'),
(241, 0, 629873, '2023-07-18 07:41:01'),
(242, 629873, 0, '2023-07-18 07:41:45'),
(243, 0, 629873, '2023-07-18 07:41:45'),
(244, 629873, 0, '2023-07-18 07:43:54'),
(245, 0, 629873, '2023-07-18 07:43:54'),
(246, 629873, 0, '2023-07-18 07:44:03'),
(247, 0, 629873, '2023-07-18 07:44:03'),
(248, 629873, 0, '2023-07-18 07:44:05'),
(249, 0, 629873, '2023-07-18 07:44:05'),
(250, 629873, 0, '2023-07-18 07:44:09'),
(251, 0, 629873, '2023-07-18 07:44:09'),
(252, 629873, 501648, '2023-07-18 07:45:19'),
(253, 501648, 629873, '2023-07-18 07:45:19'),
(256, 629873, 472336, '2023-07-18 07:47:56'),
(257, 472336, 629873, '2023-07-18 07:47:56'),
(258, 275868, 629873, '2023-07-22 04:16:42'),
(259, 629873, 275868, '2023-07-22 04:16:42'),
(260, 629873, 308308, '2023-07-26 08:04:46'),
(261, 308308, 629873, '2023-07-26 08:04:46');

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) UNSIGNED NOT NULL,
  `requester_id` int(11) UNSIGNED DEFAULT NULL,
  `receiver_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `requester_id`, `receiver_id`, `created_at`, `status`) VALUES
(121, 629873, 715648, '2023-07-18 06:50:32', ''),
(123, 472336, 0, '2023-07-18 07:02:17', ''),
(124, 472336, 877052, '2023-07-18 07:02:41', ''),
(126, 629873, 0, '2023-07-18 07:03:47', ''),
(154, 562226, 275868, '2023-07-22 04:39:22', ''),
(155, 629873, 304286, '2023-07-22 07:43:40', ''),
(157, 583796, 629873, '2023-08-04 08:15:35', '');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `like_receiver_id` int(11) DEFAULT NULL,
  `like_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `post_id`, `user_id`, `like_receiver_id`, `like_created`) VALUES
(101, 53, 629873, 629873, NULL),
(105, 51, 629873, 275868, NULL),
(106, 52, 629873, 275868, NULL),
(107, 57, 629873, 629873, NULL),
(108, 58, 629873, 629873, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `is_seen` int(1) DEFAULT 0,
  `time_stamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `incoming_msg_id`, `outgoing_msg_id`, `msg`, `is_seen`, `time_stamp`) VALUES
(802, 629873, 228745, 'hi', 1, '2023-07-16 12:06:06'),
(803, 228745, 629873, 'hi', 1, '2023-07-16 12:29:54'),
(804, 629873, 228745, 'asdkf', 1, '2023-07-16 12:30:41'),
(805, 629873, 228745, 'hi', 1, '2023-07-16 12:33:03'),
(806, 228745, 629873, 'sadlkf', 1, '2023-07-16 12:33:46'),
(807, 629873, 228745, 'sdafklajsd', 1, '2023-07-16 12:35:58'),
(808, 629873, 228745, 'dsfakk', 1, '2023-07-16 12:37:01'),
(809, 629873, 228745, 'sdfjksad\'f', 1, '2023-07-16 12:37:02'),
(810, 629873, 228745, 'asdf', 1, '2023-07-16 12:40:30'),
(811, 228745, 629873, 'asdfkasf', 1, '2023-07-16 12:41:49'),
(812, 228745, 629873, 'sdfhk', 1, '2023-07-16 12:45:56'),
(813, 472336, 629873, 'hsdaf', 1, '2023-07-17 06:47:46'),
(814, 472336, 629873, 'hi', 1, '2023-07-17 06:47:48'),
(815, 472336, 629873, 'nishan', 1, '2023-07-17 06:47:49'),
(816, 472336, 629873, 'i am nishan', 1, '2023-07-17 06:47:51'),
(817, 275868, 629873, '[pfhjkg]', 1, '2023-07-23 02:45:22'),
(818, 629873, 275868, 'safdasdf', 1, '2023-07-23 02:45:26'),
(819, 275868, 629873, 'sadfads', 1, '2023-07-23 02:45:29'),
(820, 629873, 275868, 'safdgd', 1, '2023-07-23 02:45:31'),
(821, 275868, 629873, 'sadfadf', 1, '2023-07-23 02:45:34'),
(822, 629873, 275868, 'asdfasdf', 1, '2023-07-23 02:45:35'),
(823, 275868, 629873, 'asdfasdf', 1, '2023-07-23 02:45:38'),
(824, 629873, 275868, 'adsfasf', 1, '2023-07-23 02:45:40'),
(825, 275868, 629873, 'asdfsdaf', 1, '2023-07-23 02:45:42'),
(826, 629873, 275868, 'sdfsadfasdfsadf', 1, '2023-07-23 02:45:44'),
(827, 275868, 629873, 'asdjf', 0, '2023-07-30 06:23:03'),
(828, 275868, 629873, 'asdhjfkj', 0, '2023-07-30 06:23:04'),
(829, 275868, 629873, 'asjdfh', 0, '2023-07-30 06:23:04'),
(830, 275868, 629873, 'asfdjsakflk', 0, '2023-07-30 06:23:05'),
(831, 275868, 629873, 'sdfjsk', 0, '2023-07-30 06:23:06'),
(832, 275868, 629873, 'dfsahdfkjasd', 0, '2023-07-30 06:23:06');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notification_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`, `notification_time`) VALUES
(1329, 629873, 'You received an upvote.', 1, '2023-07-23 06:02:12', '2023-07-23 06:02:12'),
(1330, 275868, 'You received a like.', 0, '2023-07-23 06:06:09', '2023-07-23 06:06:09'),
(1331, 275868, 'You received a like.', 0, '2023-07-23 06:06:34', '2023-07-23 06:06:34'),
(1332, 275868, 'You received a like.', 0, '2023-07-23 06:06:48', '2023-07-23 06:06:48'),
(1333, 275868, 'You received a like.', 0, '2023-07-23 06:06:56', '2023-07-23 06:06:56'),
(1334, 275868, 'You received a like.', 0, '2023-07-23 06:11:07', '2023-07-23 06:11:07'),
(1335, 629873, 'You received an upvote.', 1, '2023-07-26 08:03:35', '2023-07-26 08:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `persistent_tokens`
--

CREATE TABLE `persistent_tokens` (
  `id` int(11) NOT NULL,
  `unique_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persistent_tokens`
--

INSERT INTO `persistent_tokens` (`id`, `unique_id`, `token`, `expires_at`, `created_at`) VALUES
(193, 472336, '5d698b542cf7b51b224eb5de78d265332fcbb6709376c52a01f6a65f7015a1a3', '2023-08-17 08:09:24', '2023-07-17 11:54:24'),
(194, 472336, 'e139dce37c0b86a69fbe01aa3896099a0f0efd6c5db21c66907bed4d2fbfe836', '2023-08-17 08:10:37', '2023-07-17 11:55:37'),
(195, 472336, 'dc8f85467da3c3b94e0fe9b8e81445b5b6c9a3e6e61061615cb46c13ab5ecef0', '2023-08-17 08:20:40', '2023-07-17 12:05:40'),
(196, 877052, '41d432f950e378281d89afb173052c31feb8e77299b76a8ec9539a500c5dd6c8', '2023-08-17 08:24:37', '2023-07-17 12:09:37'),
(197, 629873, '37075ca152e9b9bae7d01fbc62fccb212d9129e49f4995b79ef9c5e79e3ee7f9', '2023-08-17 08:47:32', '2023-07-17 12:32:32'),
(198, 228745, 'd39e488d436e131a54bb7058082775821c5b974250363a77a787f6ee5e20b48e', '2023-08-17 10:32:14', '2023-07-17 14:17:14'),
(199, 715648, '81c6bd59ddfff67ae763fb8e42eeabc56aef7872e04172ed3afac2c361196e7b', '2023-08-18 07:44:58', '2023-07-18 11:29:58'),
(200, 501648, '6efc9d3ca1c7ca7ac71ff314ef0b33b7c3306bf2ee3b7e1d8f70c0aacb97e458', '2023-08-18 09:32:50', '2023-07-18 13:17:50'),
(201, 304286, '53ec3f087f00329469ae94e6f42f512b999baa11ebe2115c54d47c516dafa688', '2023-08-18 09:53:31', '2023-07-18 13:38:31'),
(202, 629873, 'fc263618e05dc9e8106583cb79c0bdb1aae33f1cf2a43edb81f69e89808d256e', '2023-08-18 12:48:12', '2023-07-18 16:33:12'),
(203, 629873, '894789ad3ecce541dcb136f4cf052af15c44cc49b30dc7ceea7b636a332fbc73', '2023-08-18 13:15:40', '2023-07-18 17:00:40'),
(204, 629873, '32d811b3bd92b2ab33b7f1329d6eab0abd2c437d9ac3aa4c86fe765a4accc2c1', '2023-08-18 13:43:30', '2023-07-18 17:28:30'),
(205, 629873, '6673d9d40ffac0b72e41d67314600cbc3446f1262e0d82f7ab9e1dd4ce82fb93', '2023-08-19 07:36:23', '2023-07-19 11:21:23'),
(206, 629873, '9a3fdc30c5db548770dd6d2fc9ea8972133ba9113c02c015c210d1b4b06ff1a5', '2023-08-19 08:15:40', '2023-07-19 12:00:40'),
(207, 629873, '1fb13583e0cc11321ef703b0160ade38a3703025a0ae7278e8e048dba28545dd', '2023-08-20 12:06:43', '2023-07-20 15:51:43'),
(208, 629873, '89d3778c66cdabfffd5d78d59f99995e2a54d4e73cb51559818d58a70ceb7277', '2023-08-21 09:07:00', '2023-07-21 12:52:00'),
(209, 629873, '080bf148bccd503c072f0a2cc4e5c2cd04e7ba2a6c8000293516962a1055fa1d', '2023-08-21 10:49:16', '2023-07-21 14:34:16'),
(210, 629873, '21a95d451dda7528f601faf5aab35c34fc870060e41d040426f05f4a8b0c633d', '2023-08-22 04:45:35', '2023-07-22 08:30:35'),
(211, 304286, '817188cbccabf8db7e62ddaae8f58eb2e00960891cc52d19eaf63f56f484ab5e', '2023-08-22 05:13:54', '2023-07-22 08:58:54'),
(212, 275868, '1fa4e14596d6055abadbe10ee56def01901205c8edefb24bcdefb84c0ff0237d', '2023-08-22 05:37:46', '2023-07-22 09:22:46'),
(213, 275868, '8e64c34b635d67576778633cb630a1df7910d6873bdb7914a0efb9e6c0c73674', '2023-08-22 06:12:43', '2023-07-22 09:57:43'),
(214, 562226, '6bbf50cec2086fd0edf4ee04810bf0b5f9bca392d6dabce00d7dd7add9d6b610', '2023-08-22 06:39:05', '2023-07-22 10:24:05'),
(215, 275868, '9b7244abf63ebc6584fb0939c91e772d401d0c4ee7d5f4b23e43cf45d4369478', '2023-08-23 04:09:16', '2023-07-23 07:54:16'),
(216, 275868, '8903ba3538c4983007afc0c2641d3d5f605ba025f21dfc17088bc92b9a78edbd', '2023-08-23 08:04:41', '2023-07-23 11:49:41'),
(217, 308308, '8f36e8d10cc1ce87f570c259b987593edc06dfce3085a9c6d2eee2a092f36152', '2023-08-26 10:03:24', '2023-07-26 13:48:24'),
(218, 629873, 'a413309270a82c450b75c28de5eb687df391ebb9e179bbbfffbd4ee912ba99fc', '2023-08-30 08:19:56', '2023-07-30 12:04:56'),
(219, 629873, '19753d352d0f79fa2267f491944d1c1d07dcf52a098dbaf3957e17f4b4ceedf1', '2023-08-30 09:45:01', '2023-07-30 13:30:01'),
(220, 629873, '9e679415ad9115a5b653f72b3998cafc89b5cfab693f352832269abcb7d2b25a', '2023-08-30 09:50:35', '2023-07-30 13:35:35'),
(221, 629873, 'dd214d81e980d738f238d5f5dbcebf88328c77bcf80781f8d94b73b415a52eea', '2023-08-30 09:55:31', '2023-07-30 13:40:31'),
(222, 583796, 'c8ff2daf2d0fa19f7a62f7ccc3ff1c6bdf5900926316c6f2cb2da46a866b54c5', '2023-09-04 10:13:31', '2023-08-04 13:58:31'),
(223, 304286, '5f3cc35341c91c02101703a0877c0204fe69c93cec1be78e30ef7ea79cde84bc', '2023-09-05 10:51:21', '2023-08-05 14:36:21');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `photo_filename` varchar(255) DEFAULT NULL,
  `post_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `visibility` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `caption`, `photo_filename`, `post_created`, `visibility`) VALUES
(51, 275868, 'hi', '64bb4f4d0bf27.png', '2023-07-22 03:38:53', 2),
(52, 275868, 'asdfdsaf', '64bb5019cfa23.png', '2023-07-22 03:42:17', 2),
(53, 629873, '', '64bb505473089.png', '2023-07-22 03:43:16', 2),
(54, 275868, '', '64bb5188f38bf.png', '2023-07-22 03:48:25', 2),
(55, 629873, 'asdf', '64bb583b9fb9c.png', '2023-07-22 04:16:59', 1),
(56, 308308, 'adf sdf sdf dsf dsfsdf dsf dsafasdf asdf sadf asfd asf sdf sadfsdfasd f', '64c0d3733f823.jpg', '2023-07-26 08:04:03', 2),
(57, 629873, 'sdasfd', '64cb4037e7dd2.png', '2023-08-03 05:50:48', 1),
(58, 629873, 'sdfsdfa', '64cc9fd2d70ab.png', '2023-08-04 06:50:58', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `unique_id` int(255) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `resized_photo` varchar(255) DEFAULT NULL,
  `thumbnail_photo` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `upvotes` int(11) DEFAULT 0,
  `downvotes` int(11) DEFAULT 0,
  `voted` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gender` varchar(10) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `remember_me` tinyint(1) DEFAULT 0,
  `notification_count` int(11) DEFAULT 0,
  `bio` varchar(255) DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `tiktok_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `session_id`, `fname`, `lname`, `email`, `password`, `img`, `resized_photo`, `thumbnail_photo`, `status`, `upvotes`, `downvotes`, `voted`, `created_at`, `gender`, `last_login_at`, `remember_me`, `notification_count`, `bio`, `facebook_link`, `tiktok_link`, `instagram_link`) VALUES
(282, 228745, NULL, 'Hagrid', 'Toyws', 'nishannepali91@gmail.com', '$2y$10$Z0kzEK2hAxwzzzWviMF94u/0KEGNtQpVEdXNBWeEjpy6yXAzV00kS', 'uploads/1689509112_Default_Plague_Doctor_Al_Silmons_is_Plague_doctor_drawn_by_Tod_0_55901757-3cfe-4979-ac89-9f55c33836a9_1.jpg', 'resized/1689509112_resized_Default_Plague_Doctor_Al_Silmons_is_Plague_doctor_drawn_by_Tod_0_55901757-3cfe-4979-ac89-9f55c33836a9_1.jpg', 'resize_mini/1689509112_thumbnail_Default_Plague_Doctor_Al_Silmons_is_Plague_doctor_drawn_by_Tod_0_55901757-3cfe-4979-ac89-9f55c33836a9_1.jpg', 'inactive', 0, 0, 0, '2023-07-16 12:05:13', 'male', NULL, 0, 0, NULL, NULL, NULL, NULL),
(283, 629873, NULL, 'jenishan', 'magar', 'jenishamagar68@gmail.com', '$2y$10$f3s79TpvBGBcCrWSuhGp8.l.toUwiYSZca6StFtPp/mIaxszoDBZ6', 'uploads/1689509132_Default_black_character_sitting_in_a_gamer_room_with_gamer_hea_0_d9caf49c-d037-46ae-b4f6-b83e7c477f1d_1.jpg', 'resized/1689509132_resized_Default_black_character_sitting_in_a_gamer_room_with_gamer_hea_0_d9caf49c-d037-46ae-b4f6-b83e7c477f1d_1.jpg', 'resize_mini/1689509132_thumbnail_Default_black_character_sitting_in_a_gamer_room_with_gamer_hea_0_d9caf49c-d037-46ae-b4f6-b83e7c477f1d_1.jpg', 'inactive', 7, 0, 0, '2023-07-16 12:05:33', 'female', NULL, 0, 0, NULL, 'https://facebook.com', 'fafds', 'sdffsad'),
(284, 472336, NULL, 'Nishan', 'Nepali', 'nishannp2@gmail.com', '$2y$10$GqYUZbC1MM2ddDAAENm3Zu1LJZ795anoTtIAOBpIoziBShJRU7FU.', 'uploads/1689574163_Default_Plague_Doctor_Al_Silmons_is_Plague_doctor_drawn_by_Tod_0_55901757-3cfe-4979-ac89-9f55c33836a9_1.jpg', 'resized/1689574163_resized_Default_Plague_Doctor_Al_Silmons_is_Plague_doctor_drawn_by_Tod_0_55901757-3cfe-4979-ac89-9f55c33836a9_1.jpg', 'resize_mini/1689574163_thumbnail_Default_Plague_Doctor_Al_Silmons_is_Plague_doctor_drawn_by_Tod_0_55901757-3cfe-4979-ac89-9f55c33836a9_1.jpg', 'inactive', 1, 0, 0, '2023-07-17 06:09:24', 'male', NULL, 0, 0, NULL, NULL, NULL, NULL),
(285, 877052, NULL, 'asdfjk', 'jklajsdfkl', 'jkljaksldj@gmail.com', '$2y$10$X06aMuG5rXbUH2K8mqFEOe9A2q0x5SCZajtGsQfsSFQDY8j4glwNK', 'uploads/64b4df03000b7.png', 'resized/64b4df0307807_resized.png', 'resize_mini/64b4df03000bd_thumbnail.png', 'Offline now', 0, 0, 0, '2023-07-17 06:24:37', 'female', NULL, 0, 0, NULL, '', '', 'fsb'),
(286, 715648, NULL, 'asdjfk', 'kjfskdlaj', 'jdklgjkl@gmail.com', '$2y$10$Qz2l9swHQiKlyaQmKupnP.vDHSrCwHFxH1NGL8E9E1u1hDr2B.oB.', 'uploads/1689659098_image-removebg-preview.png', 'resized/1689659098_resized_image-removebg-preview.png', 'resize_mini/1689659098_thumbnail_image-removebg-preview.png', 'inactive', 0, 0, 0, '2023-07-18 05:44:58', 'male', NULL, 0, 0, NULL, NULL, NULL, NULL),
(287, 501648, NULL, 'chrome', 'Nepali', 'chrome@gmail.com', '$2y$10$6ITVxPnXYIyfoF32TyAaLe10SvJNPN8eMmwvxsoaxKxr3HGeJB/PK', 'uploads/1689665569_dineshcut.jpg', 'resized/1689665569_resized_dineshcut.jpg', 'resize_mini/1689665569_thumbnail_dineshcut.jpg', 'inactive', 0, 0, 0, '2023-07-18 07:32:50', 'male', NULL, 0, 0, NULL, NULL, NULL, NULL),
(288, 304286, NULL, 'brave', 'nepali', 'brave@gmail.com', '$2y$10$fVN.VVxXhrOClfz7DgJZx.uFsqXHS3k1a574VzHaUWOmilBFYljUi', 'uploads/1689666811_F04Nn24XgAQulxi.jpeg', 'resized/1689666811_resized_F04Nn24XgAQulxi.jpeg', 'resize_mini/1689666811_thumbnail_F04Nn24XgAQulxi.jpeg', 'inactive', 0, 0, 0, '2023-07-18 07:53:31', 'male', NULL, 0, 0, NULL, NULL, NULL, NULL),
(289, 275868, NULL, 'edge', '', 'edge@gmail.com', '$2y$10$V8U1kiQwN/YCRcQO//9nsOXWobeLTXvgMUCD8l22dztJYTgiM/Aje', 'uploads/1689997066_[removal.ai]_2611714b-267f-4eb3-b15c-bda0a666936e-image.png', 'resized/1689997066_resized_[removal.ai]_2611714b-267f-4eb3-b15c-bda0a666936e-image.png', 'resize_mini/1689997066_thumbnail_[removal.ai]_2611714b-267f-4eb3-b15c-bda0a666936e-image.png', 'inactive', 1, 0, 0, '2023-07-22 03:37:46', 'male', NULL, 0, 0, NULL, NULL, NULL, NULL),
(290, 562226, NULL, 'adsfh', 'hjfadsh', 'hjdsfhj@gmail.com', '$2y$10$xRdeJ.GJi.vJ5tS4K/MTce8CNBGjXBciB.m7Zp.DqxCsMN9Q7A4rm', 'uploads/1690000744_[removal.ai]_2611714b-267f-4eb3-b15c-bda0a666936e-image.png', 'resized/1690000744_resized_[removal.ai]_2611714b-267f-4eb3-b15c-bda0a666936e-image.png', 'resize_mini/1690000744_thumbnail_[removal.ai]_2611714b-267f-4eb3-b15c-bda0a666936e-image.png', 'inactive', 0, 0, 0, '2023-07-22 04:39:05', 'female', NULL, 0, 0, NULL, NULL, NULL, NULL),
(291, 308308, NULL, 'asdf sdfsd jfh sdfh sdfh ajhfhj', 'lkfdjsk lsdlkfj kladsf jalskdf j', 'kldfajkfjds@gmail.com', '$2y$10$8OJDQjnbBHedWgEHtaj3/.ny6jzawUFrWhz4mDowHnoDkpT8cV4cu', 'uploads/1690358604_avatar.jpg', 'resized/1690358604_resized_avatar.jpg', 'resize_mini/1690358604_thumbnail_avatar.jpg', 'inactive', 0, 0, 0, '2023-07-26 08:03:24', 'male', NULL, 0, 0, NULL, NULL, NULL, NULL),
(292, 583796, NULL, 'brave', '', 'brave@gmail.com', '$2y$10$hlami1Sp45saRzcZ3LD4yekxznq0TvBDAHEsoYv0uG.RViUc.At6y', 'uploads/1691136809_image-removebg (1).png', 'resized/1691136809_resized_image-removebg (1).png', 'resize_mini/1691136809_thumbnail_image-removebg (1).png', 'inactive', 0, 0, 0, '2023-08-04 08:13:31', 'male', NULL, 0, 0, NULL, 'sadf', 'sdfsdf', 'sadf');

-- --------------------------------------------------------

--
-- Table structure for table `user_photos`
--

CREATE TABLE `user_photos` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_photos`
--

INSERT INTO `user_photos` (`id`, `user_id`, `photo`, `caption`, `created_at`) VALUES
(67, 877052, 'photo-album/compressed_photo_64b4def48b3c5_2927.png', 'fd', '2023-07-17 06:25:56'),
(68, 629873, 'photo-album/compressed_photo_64bb5f17b9510_7563.png', '', '2023-07-22 04:46:15'),
(69, 629873, 'photo-album/compressed_photo_64bb5f1c0c676_2107.png', '', '2023-07-22 04:46:20'),
(70, 629873, 'photo-album/compressed_photo_64bb5f2095699_2741.png', '', '2023-07-22 04:46:24');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `voter_id` int(11) UNSIGNED DEFAULT NULL,
  `profile_owner_id` int(11) UNSIGNED DEFAULT NULL,
  `vote_type` varchar(10) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`voter_id`, `profile_owner_id`, `vote_type`, `id`) VALUES
(228745, 629873, 'upvote', 256),
(275868, 275868, 'upvote', 257),
(629873, 629873, 'upvote', 258),
(308308, 629873, 'upvote', 259);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id1` (`user_id1`),
  ADD KEY `user_id2` (`user_id2`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `likes_ibfk_3` (`like_receiver_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `persistent_tokens`
--
ALTER TABLE `persistent_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unique_id` (`unique_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- Indexes for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_photos_user_id` (`user_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=833;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1336;

--
-- AUTO_INCREMENT for table `persistent_tokens`
--
ALTER TABLE `persistent_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;

--
-- AUTO_INCREMENT for table `user_photos`
--
ALTER TABLE `user_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`unique_id`);

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`unique_id`),
  ADD CONSTRAINT `likes_ibfk_3` FOREIGN KEY (`like_receiver_id`) REFERENCES `users` (`unique_id`);

--
-- Constraints for table `persistent_tokens`
--
ALTER TABLE `persistent_tokens`
  ADD CONSTRAINT `persistent_tokens_ibfk_1` FOREIGN KEY (`unique_id`) REFERENCES `users` (`unique_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`unique_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
