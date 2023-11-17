-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2023 at 04:24 AM
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
-- Database: `phpforum`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `recipient_admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `role`, `name`, `email`, `admin_id`, `message`, `created_at`, `recipient_admin_id`) VALUES
(30, 27, 'admin', 'root123', NULL, 0, 'aaaa', '2023-10-26 11:19:50', 27),
(31, 27, 'admin', 'root123', NULL, 0, '1111', '2023-10-26 11:19:52', 28),
(32, 27, 'admin', 'root123', NULL, 0, 'ádasdasdasd', '2023-10-26 11:25:30', 27),
(33, 27, 'admin', 'root123', NULL, 0, 'ádasdasdasd', '2023-10-26 11:31:28', 27),
(34, 28, 'admin', 'admin123', NULL, 0, '123123', '2023-10-26 11:34:38', 28),
(35, 28, 'admin', 'admin123', NULL, 0, 'asd', '2023-10-26 12:24:38', 27),
(36, 28, 'admin', 'admin123', NULL, 0, 'asd', '2023-10-26 12:28:47', 27),
(37, 28, 'admin', 'admin123', NULL, 0, 'asd', '2023-10-26 12:28:48', 27),
(38, 28, 'admin', 'admin123', NULL, 0, 'asd', '2023-10-26 12:28:48', 27),
(39, 29, '', '123123', NULL, 0, '123', '2023-10-26 12:56:39', 28),
(40, 29, '', '123123', NULL, 0, '123', '2023-10-26 12:56:45', 28),
(41, 29, '', '123123', NULL, 0, '111', '2023-10-26 12:56:49', 28),
(42, 29, '', '123123', NULL, 0, '1111111111111111111111111', '2023-10-26 12:57:38', 27),
(43, 29, '', '123123', NULL, 0, '22222222222222222222222222', '2023-10-26 12:57:40', 28),
(46, 30, 'admin', 'baola', NULL, 0, 'abcka', '2023-11-13 08:46:24', 30),
(48, 30, 'admin', 'baola', NULL, 0, 'xinh gai qua di thoi', '2023-11-13 08:46:41', 30);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `topic_id`, `title`, `content`, `user_id`, `timestamp`, `image_path`) VALUES
(78, 29, 'asdasd', 'asdasd', 28, '2023-10-26 07:41:12', ''),
(79, 29, '123', '123', 27, '2023-10-26 08:08:07', ''),
(80, 29, 'asdasd', 'asdasd', 27, '2023-10-26 08:21:42', ''),
(81, 29, 'dasd', 'asdasd', 29, '2023-10-26 08:23:21', ''),
(82, 29, '123123', '123123', 27, '2023-10-26 08:27:26', ''),
(83, 29, 'ád', 'ád', 28, '2023-10-26 12:38:28', 'post upload/1698323908_01h - 0en23ot.png'),
(84, 29, 'sdas', 'dasd', 27, '2023-10-26 13:19:42', 'post upload/1698326382_01h - 0en23ot.png'),
(85, 29, 'dfs', 'sdf', 27, '2023-10-26 13:40:18', 'post upload/1698327618_01h - 0en23ot.png'),
(86, 29, 'cdsdsd', 'csdd', 27, '2023-10-26 13:43:51', ''),
(87, 29, 'ads', 'csdc', 27, '2023-10-26 13:46:57', ''),
(88, 29, 'ads', 'csdc', 27, '2023-10-26 13:49:07', ''),
(89, 29, 'ads', 'csdc', 27, '2023-10-26 13:50:50', ''),
(90, 29, 'sdffd', 'ssdfsdfsdf', 27, '2023-10-26 14:07:11', 'post upload/1698329231_00h - 0f6rgh9.png'),
(92, 29, '123', '\r\n65+65', 30, '2023-11-05 14:03:01', 'post upload/1699192981_849b9193dc318671773fc3979ae576aa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`id`, `question_id`, `user_id`, `reply_content`, `timestamp`) VALUES
(78, 78, 28, 'Deleted reply', '2023-10-26 07:45:06'),
(79, 78, 28, 'Deleted reply', '2023-10-26 07:48:23'),
(80, 78, 28, 'asdasd', '2023-10-26 07:58:01'),
(81, 78, 28, 'asdasda', '2023-10-26 07:58:03'),
(82, 78, 28, 'asdasd', '2023-10-26 07:58:04'),
(83, 78, 28, 'asdasd', '2023-10-26 07:58:06'),
(84, 78, 28, 'asdasd', '2023-10-26 07:58:08'),
(85, 78, 27, '123123', '2023-10-26 08:08:11'),
(86, 79, 27, 'Deleted reply', '2023-10-26 08:13:26'),
(87, 80, 29, 'asdasd', '2023-10-26 08:23:24'),
(88, 82, 27, '123123123123', '2023-10-26 08:27:29'),
(89, 92, 30, 'Deleted reply', '2023-11-08 07:03:55'),
(90, 92, 30, '123', '2023-11-08 07:05:44'),
(91, 92, 30, 'Deleted reply', '2023-11-08 07:05:49'),
(92, 92, 30, '123', '2023-11-08 07:05:52'),
(93, 92, 30, '123', '2023-11-08 07:05:56'),
(94, 92, 30, '123', '2023-11-08 07:08:10'),
(95, 92, 30, 'check', '2023-11-08 07:25:42'),
(96, 92, 30, 'Deleted reply', '2023-11-08 07:25:45'),
(97, 92, 30, 'check', '2023-11-08 07:25:47'),
(98, 92, 30, 'Deleted reply', '2023-11-08 07:28:34'),
(99, 92, 30, '123', '2023-11-08 07:30:30'),
(100, 92, 30, 'che', '2023-11-08 07:33:47'),
(101, 92, 30, 'che', '2023-11-08 07:33:50'),
(102, 92, 30, 'Deleted reply', '2023-11-08 07:33:51'),
(103, 92, 30, 'che', '2023-11-08 07:34:05'),
(104, 92, 30, 'che', '2023-11-08 07:34:23');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `title`, `content`, `user_id`) VALUES
(29, 'asd', NULL, 27),
(30, '123', NULL, 27),
(31, 'check', NULL, 30),
(32, '123', NULL, 30);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` longtext NOT NULL,
  `password` longtext NOT NULL,
  `email` mediumtext NOT NULL,
  `date` datetime NOT NULL,
  `replies` int(11) NOT NULL,
  `topics` int(11) NOT NULL,
  `profile_pic` varchar(9999) NOT NULL DEFAULT '',
  `topic_count` int(11) NOT NULL DEFAULT 0,
  `role` varchar(20) NOT NULL,
  `question_count` int(11) DEFAULT 0,
  `reply_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `date`, `replies`, `topics`, `profile_pic`, `topic_count`, `role`, `question_count`, `reply_count`) VALUES
(27, 'root123', '$2y$10$ddB97APN5mG9mgqLPmDoy.ZVp3EvwaBd0lPdt2qtGHt1Vhzhqws6C', 'loroqua0@gmail.com', '2023-10-26 05:42:09', 0, 0, 'purple.png', 0, 'admin', 32, 14),
(28, 'admin123', '$2y$10$ymG.Rv0p.kERuv4Q6Fo.P.Grr4Uo950/shEfihFq.pEW8d6sieb5O', 'loroqua0@gmail.com', '2023-10-26 05:45:22', 0, 0, 'profilepic/test.png', 0, 'admin', 6, 21),
(29, '123123', '$2y$10$mncPLRbetZsjukCWuH4Do.EH1uqto9pudohmk.LgmJuscN03JVari', 'loroqua0@gmail.com', '2023-10-26 08:59:55', 0, 0, 'profilepic/test.png', 0, '', 1, 2),
(30, 'baola', '$2y$10$HUyr43Pgks6o19zXLZFD8ewBoPfZpGzHuOpQfasszzT.8lmiKTc.O', 'ptmaiphuong91@gmail.com', '2023-10-27 00:13:28', 0, 0, 'profilepic/test.png', 0, 'admin', 2, 18);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_topic` (`topic_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`),
  ADD CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
