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
